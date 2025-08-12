<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentRequest;
use App\Models\RequestedDocument;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CashierController extends Controller
{
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

        // Document type counts (from requested_documents joined with document_requests, filtered by approved status)
        $document_type_counts = RequestedDocument::join('document_requests', 'requested_documents.request_id', '=', 'document_requests.id')
            ->where('document_requests.status', 'approved')
            ->select('requested_documents.document_type', DB::raw('SUM(requested_documents.quantity) as total'))
            ->groupBy('requested_documents.document_type')
            ->pluck('total', 'requested_documents.document_type')
            ->toArray();

        // Fetch all approved document requests with their requested documents
        $approved_requests = DocumentRequest::with('requestedDocuments')
            ->where('status', 'approved')
            ->orderByDesc('created_at')
            ->get();

        // Pass approved requests as document_requests for the management section
        $document_requests = $approved_requests;

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
}
