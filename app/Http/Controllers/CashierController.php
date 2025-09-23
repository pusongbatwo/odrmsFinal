<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentRequest;
use App\Models\RequestedDocument;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CashierController extends Controller
{
    private function formatDateTime($value)
    {
        try {
            if ($value instanceof \DateTimeInterface) {
                return $value->format('Y-m-d H:i:s');
            }
            if (is_string($value) && !empty($value)) {
                return Carbon::parse($value)->format('Y-m-d H:i:s');
            }
        } catch (\Throwable $e) {
            // fallthrough to empty string
        }
        return '';
    }
    public function dashboard()
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();
        $fees = Config::get('services.document_fees', []);

        // Total approved requests
        $total_approved = DocumentRequest::where('status', 'approved')->count();

        // Pending payments (approved but unpaid)
        $pending_payments = DocumentRequest::where('status', 'approved')->where('payment_status', 'unpaid')->count();

        // Paid today (sum of amount_paid)
        $paid_today = DocumentRequest::where('payment_status', 'paid')
            ->whereDate('paid_at', $today)
            ->sum('amount_paid');

        // Total collected in current month (sum of amount_paid)
        $total_collected_month = DocumentRequest::where('payment_status', 'paid')
            ->whereBetween('paid_at', [$monthStart, $monthEnd])
            ->sum('amount_paid');

        // Document type counts (from requested_documents joined with document_requests, filtered by approved, paid, completed status)
        $document_type_counts = RequestedDocument::join('document_requests', 'requested_documents.request_id', '=', 'document_requests.id')
            ->whereIn('document_requests.status', ['approved', 'paid', 'completed'])
            ->select('requested_documents.document_type', DB::raw('SUM(requested_documents.quantity) as total'))
            ->groupBy('requested_documents.document_type')
            ->pluck('total', 'requested_documents.document_type')
            ->toArray();

        // Fetch all document requests with status approved, paid, or completed
        $document_requests = DocumentRequest::with('requestedDocuments')
            ->whereIn('status', ['approved', 'paid', 'completed'])
            ->orderByDesc('created_at')
            ->get();

        // For backward compatibility, keep approved_requests as only approved
        $approved_requests = $document_requests->where('status', 'approved');

        // Fetch latest cashier logs
        $cashier_logs = \App\Models\CashierLog::orderByDesc('created_at')->take(30)->get();

        return view('cashier.dashboard', compact(
            'total_approved',
            'pending_payments',
            'paid_today',
            'total_collected_month',
            'document_type_counts',
            'approved_requests',
            'document_requests',
            'cashier_logs'
        ));
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'reference_number' => 'required|string',
            'amount_received' => 'required|numeric|min:0',
        ]);
        $docRequest = DocumentRequest::where('reference_number', $request->reference_number)->with('requestedDocuments')->first();
        if (!$docRequest) {
            return response()->json(['success' => false, 'message' => 'Request not found.'], 404);
        }
        // Calculate amount due
        $fees = config('services.document_fees', []);
        $amountDue = 0;
        foreach ($docRequest->requestedDocuments as $doc) {
            $amountDue += ($fees[$doc->document_type] ?? 250) * $doc->quantity;
        }
        $docRequest->payment_status = 'paid';
        $docRequest->status = 'paid';
        $docRequest->paid_at = now();
        $docRequest->amount_paid = $amountDue;
        $docRequest->save();
        \App\Models\CashierLog::create([
            'type' => 'process_payment',
            'message' => 'Processed payment for reference #' . $docRequest->reference_number . ' (₱' . number_format($amountDue, 2) . ')'
        ]);
        return response()->json(['success' => true]);
    }

    public function reportsData(Request $request)
    {
        // If no date range provided, get all data
        if ($request->has('start_date') && $request->has('end_date')) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date',
            ]);
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
        } else {
            // Get all data (no date filtering)
            $start = null;
            $end = null;
        }

        // Build base queries
        $paidQuery = DocumentRequest::where('payment_status', 'paid');
        $requestsQuery = DocumentRequest::query();
        $documentTypeQuery = RequestedDocument::join('document_requests', 'requested_documents.request_id', '=', 'document_requests.id');

        // Apply date filtering if provided
        if ($start && $end) {
            $paidQuery->whereBetween('paid_at', [$start, $end]);
            $requestsQuery->whereBetween('created_at', [$start, $end]);
            $documentTypeQuery->whereBetween('document_requests.created_at', [$start, $end]);
        }

        // Totals collected from paid requests
        $totalCollected = $paidQuery->sum('amount_paid');

        // Requests counts
        $totalRequests = (clone $requestsQuery)->count();
        $completed = (clone $requestsQuery)->where('status', 'completed')->count();
        $pending = (clone $requestsQuery)->where('status', 'approved')->where('payment_status', 'unpaid')->count();

        // By document type
        $byDocumentType = (clone $documentTypeQuery)
            ->select('requested_documents.document_type', DB::raw('SUM(requested_documents.quantity) as total'))
            ->groupBy('requested_documents.document_type')
            ->pluck('total', 'requested_documents.document_type')
            ->toArray();

        // Daily payment trends (amount collected per day)
        $dailyTrendsQuery = DocumentRequest::where('payment_status', 'paid');
        if ($start && $end) {
            $dailyTrendsQuery->whereBetween('paid_at', [$start, $end]);
        }
        $dailyTrends = $dailyTrendsQuery
            ->select(DB::raw('DATE(paid_at) as date'), DB::raw('SUM(amount_paid) as total'))
            ->groupBy(DB::raw('DATE(paid_at)'))
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();

        // Payment status distribution
        $statusDistribution = [
            'paid' => (clone $requestsQuery)->where('payment_status', 'paid')->count(),
            'unpaid' => (clone $requestsQuery)->where('payment_status', 'unpaid')->count(),
            'completed' => (clone $requestsQuery)->where('status', 'completed')->count(),
        ];

        // Revenue by document type
        $fees = Config::get('services.document_fees', []);
        $revenueQuery = RequestedDocument::join('document_requests', 'requested_documents.request_id', '=', 'document_requests.id')
            ->where('document_requests.payment_status', 'paid');
        
        if ($start && $end) {
            $revenueQuery->whereBetween('document_requests.created_at', [$start, $end]);
        }

        $revenueByType = $revenueQuery
            ->select('requested_documents.document_type', 
                DB::raw('SUM(requested_documents.quantity * ' . 
                    DB::getPdo()->quote($fees['Official Transcript'] ?? 250) . ') as revenue'))
            ->groupBy('requested_documents.document_type')
            ->get()
            ->mapWithKeys(function($item) use ($fees) {
                $fee = $fees[$item->document_type] ?? 250;
                return [$item->document_type => $item->revenue];
            })
            ->toArray();

        return response()->json([
            'total_collected' => $totalCollected,
            'total_requests' => $totalRequests,
            'completed' => $completed,
            'pending' => $pending,
            'by_document_type' => $byDocumentType,
            'daily_trends' => $dailyTrends,
            'status_distribution' => $statusDistribution,
            'revenue_by_type' => $revenueByType,
        ]);
    }

    public function testExport()
    {
        try {
            $data = collect([
                ['Name' => 'Test', 'Value' => '123'],
                ['Name' => 'Test2', 'Value' => '456']
            ]);
            
            return $this->exportToCsv($data, 'test');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Test failed: ' . $e->getMessage()], 500);
        }
    }

    public function exportRecords(Request $request)
    {
        try {
            $request->validate([
                'report_type' => 'required|string|in:transaction_records,document_requests,payment_summary,daily_totals',
                'file_format' => 'required|string|in:xlsx,csv',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
            ]);

            $reportType = $request->report_type;
            $fileFormat = $request->file_format;
            $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
            $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null;

            // Build base query
            $query = DocumentRequest::with('requestedDocuments');
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            $data = collect();

            switch ($reportType) {
                case 'transaction_records':
                    $data = $query->where('payment_status', 'paid')->get()->map(function($request) {
                        $amount = 0;
                        foreach ($request->requestedDocuments as $doc) {
                            $fees = config('services.document_fees', []);
                            $amount += ($fees[$doc->document_type] ?? 250) * $doc->quantity;
                        }
                        return [
                            'Reference Number' => $request->reference_number ?? '',
                            'Student Name' => ($request->first_name ?? '') . ' ' . ($request->last_name ?? ''),
                            'Student ID' => $request->student_id ?? '',
                            'Document Types' => $request->requestedDocuments->pluck('document_type')->implode(', '),
                            'Amount' => $amount,
                            'Payment Date' => $this->formatDateTime($request->paid_at),
                            'Status' => ucfirst($request->payment_status ?? ''),
                        ];
                    });
                    break;

                case 'document_requests':
                    $data = $query->get()->map(function($request) {
                        return [
                            'Reference Number' => $request->reference_number ?? '',
                            'Student Name' => ($request->first_name ?? '') . ' ' . ($request->last_name ?? ''),
                            'Student ID' => $request->student_id ?? '',
                            'Email' => $request->email ?? '',
                            'Document Types' => $request->requestedDocuments->pluck('document_type')->implode(', '),
                            'Quantities' => $request->requestedDocuments->pluck('quantity')->implode(', '),
                            'Status' => ucfirst($request->status ?? ''),
                            'Payment Status' => ucfirst($request->payment_status ?? ''),
                            'Request Date' => $this->formatDateTime($request->created_at),
                        ];
                    });
                    break;

                case 'payment_summary':
                    $paidRequests = $query->where('payment_status', 'paid')->get();
                    $totalAmount = $paidRequests->sum('amount_paid') ?? 0;
                    $totalRequests = $paidRequests->count();
                    
                    $data = collect([
                        [
                            'Metric' => 'Total Revenue',
                            'Value' => '₱' . number_format($totalAmount, 2),
                        ],
                        [
                            'Metric' => 'Total Paid Requests',
                            'Value' => $totalRequests,
                        ],
                        [
                            'Metric' => 'Average Payment',
                            'Value' => $totalRequests > 0 ? '₱' . number_format($totalAmount / $totalRequests, 2) : '₱0.00',
                        ],
                        [
                            'Metric' => 'Date Range',
                            'Value' => $startDate && $endDate ? 
                                $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d') : 
                                'All Time',
                        ],
                    ]);
                    break;

                case 'daily_totals':
                    $dailyData = DocumentRequest::where('payment_status', 'paid');
                    if ($startDate && $endDate) {
                        $dailyData->whereBetween('paid_at', [$startDate, $endDate]);
                    }
                    
                    $dailyTotals = $dailyData->select(DB::raw('DATE(paid_at) as date'), DB::raw('SUM(amount_paid) as total'), DB::raw('COUNT(*) as count'))
                        ->groupBy(DB::raw('DATE(paid_at)'))
                        ->orderBy('date')
                        ->get();

                    $data = $dailyTotals->map(function($day) {
                        return [
                            'Date' => $day->date ?? '',
                            'Total Amount' => '₱' . number_format($day->total ?? 0, 2),
                            'Number of Payments' => $day->count ?? 0,
                        ];
                    });
                    break;
            }

            return $this->exportToCsv($data, $reportType);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Export failed: ' . $e->getMessage()], 500);
        }
    }

    private function exportToCsv($data, $reportType)
    {
        $filename = $reportType . '_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            if ($data->isNotEmpty()) {
                // Write headers
                fputcsv($file, array_keys($data->first()));
                
                // Write data
                foreach ($data as $row) {
                    fputcsv($file, $row);
                }
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportToExcel($data, $reportType)
    {
        // For Excel export, we'll use CSV format as a simple solution
        // In a real application, you'd use a library like PhpSpreadsheet
        return $this->exportToCsv($data, $reportType);
    }

    private function exportToPdf($data, $reportType)
    {
        // For PDF export, we'll use CSV format as a simple solution
        // In a real application, you'd use a library like DomPDF or TCPDF
        return $this->exportToCsv($data, $reportType);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|max:2048',
        ]);

        // If attempting to change password, verify current_password
        if ($request->filled('new_password')) {
            if (!$request->filled('current_password') || !Hash::check($request->input('current_password'), $user->password)) {
                return response()->json(['success' => false, 'message' => 'Current password is incorrect.'], 422);
            }
            $user->password = Hash::make($request->input('new_password'));
        }

        // Update username
        $user->username = $request->input('username');

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $path = $file->store('avatars', 'public');
            // Optionally delete old avatar
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $path;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'user' => [
                'username' => $user->username,
                'avatar_url' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                'role' => $user->role,
            ]
        ]);
    }
}
