<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentRequest;
use Illuminate\Support\Facades\DB;
use App\Models\DepartmentLogo;
use App\Mail\RequestApprovedMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\RequestRejectedMail;
use App\Helpers\SystemLogHelper;
use App\Models\SystemLog;
use App\Models\Student;
use App\Models\CashierLog;

class RegistrarController extends Controller
{
    // Profile update handler
    public function updateProfile(Request $request)
    {
        // TODO: Implement avatar, username, and password update logic
        // Validate input
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|max:2048',
        ]);

        // Get current user
        $user = auth()->user();

        // Update username
        $user->username = $request->input('username');

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
    // AJAX endpoint for Verify Modal
    public function verifyModal($id)
    {
        $request = \App\Models\DocumentRequest::with('requestedDocuments')->find($id);
        if (!$request) {
            return response()->json(['found' => false, 'request_id' => $id]);
        }

        // Try to match student by student_id or name
        $student = null;
        if ($request->student_id) {
            $student = \App\Models\Student::where('student_id', $request->student_id)->first();
        }
        if (!$student && $request->first_name && $request->last_name) {
            $student = \App\Models\Student::where('first_name', $request->first_name)
                ->where('last_name', $request->last_name)
                ->first();
        }

        if ($student) {
            // Get available documents for this student (simulate: all types allowed)
            $availableDocs = [];
            // If you have a real relation, fetch from there. For now, allow all requested docs.
            foreach ($request->requestedDocuments as $doc) {
                $availableDocs[] = $doc->document_type;
            }
            if (count($availableDocs) > 0) {
                return response()->json([
                    'found' => true,
                    'full_name' => trim($student->first_name . ' ' . ($student->middle_name ? $student->middle_name . ' ' : '') . $student->last_name),
                    'documents' => $availableDocs,
                    'request_id' => $id
                ]);
            }
        }
        // No student or no matching docs
        return response()->json(['found' => false, 'request_id' => $id]);
    }
    public function verify($id)
    {
        $request = DocumentRequest::findOrFail($id);
        $request->status = 'processing';
        $request->save();
        // Log verification
        SystemLogHelper::log('request_received', 'Document request #' . $request->id . ' verified by registrar.');
        return back()->with('success', 'Request verified.');
    }

    public function complete($id)
    {
        $request = DocumentRequest::findOrFail($id);
        $request->status = 'completed';
        $request->save();
        // Log completion
        SystemLogHelper::log('completed', 'Document request #' . $request->id . ' marked as completed by registrar.');
        return back()->with('success', 'Request marked as completed.');
    }
    public function storeStudent(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string|unique:students,student_id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'program' => 'required|string|max:255',
            'year_level' => 'required|string|max:50',
            'status' => 'required|string|max:50',
        ]);

        \App\Models\Student::create($validated);

        // Log student added
        SystemLogHelper::log('student_added', 'Student record for ' . $validated['first_name'] . ' ' . $validated['last_name'] . ' added by registrar.');

        return redirect()->back()->with('student_added', 'Student record added successfully!');
    }

    public function registrarDashboard(Request $request)
    {
        // Dashboard summary: top 5, not paginated
        $dashboardRequests = DocumentRequest::with('requestedDocuments')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Management section: paginated
        $requests = DocumentRequest::with('requestedDocuments')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get analytics with correct status mapping
        $analytics = [
            'pending' => DocumentRequest::where('status', 'pending_registrar_approval')->count(),
            'approved' => DocumentRequest::where('status', 'approved')->count(),
            'completed' => DocumentRequest::where('status', 'completed')->count(),
            'rejected' => DocumentRequest::where('status', 'rejected')->count(),
        ];

        // Load department logos from DB
        $departmentLogos = \App\Models\DepartmentLogo::all()->pluck('logo_path', 'department_name')->map(function($path) {
            return $path ? asset('storage/' . $path) : null;
        })->toArray();

        // Load students from students table, grouped by program and year_level
        $students = \App\Models\Student::all()->groupBy(['program', 'year_level']);

        // Load system logs
        $systemLogs = \App\Models\SystemLog::orderBy('created_at', 'desc')->take(50)->get();

        return view('registrar.dashboard', compact('dashboardRequests', 'requests', 'analytics', 'departmentLogos', 'students', 'systemLogs'));
    }

    public function approve($id)
    {
        $documentRequest = DocumentRequest::findOrFail($id);

        // Backend enforcement: requester must exist in Student records (by student_id or by name)
        $studentExists = false;
        if (!empty($documentRequest->student_id)) {
            $studentExists = Student::where('student_id', $documentRequest->student_id)->exists();
        }
        if (!$studentExists && !empty($documentRequest->first_name) && !empty($documentRequest->last_name)) {
            $studentExists = Student::where('first_name', $documentRequest->first_name)
                ->where('last_name', $documentRequest->last_name)
                ->exists();
        }
        if (!$studentExists) {
            return back()->with('error', 'This request cannot be approved because no matching student record was found in the database.');
        }

        // Optional registrar notes
        $notes = request()->input('registrar_notes');
        if (!empty($notes)) {
            $documentRequest->registrar_notes = $notes;
        }

        // Generate unique reference number if missing
        if (empty($documentRequest->reference_number)) {
            $documentRequest->reference_number = $this->generateUniqueReferenceNumber();
        }

        $documentRequest->status = 'approved';
        $documentRequest->approved_at = now();
        $documentRequest->approved_by = auth()->id();
        $documentRequest->save();

        CashierLog::create([
            'type' => 'document_approved',
            'message' => 'Document request #' . $documentRequest->reference_number . ' approved by registrar.'
        ]);

        // Send email with reference number
        try {
            Mail::to($documentRequest->email)->send(new RequestApprovedMail($documentRequest, $documentRequest->reference_number));
        } catch (\Throwable $e) {
            // Allow flow to continue even if email fails (logging only)
            \Log::error('Failed to send RequestApprovedMail: ' . $e->getMessage());
        }

        // Log approval
        SystemLogHelper::log('approved', 'Request #' . $documentRequest->id . ' approved by registrar.');

        return back()->with('success', 'Request approved. Email notification sent to requester.');
    }

    public function reject($id)
    {
        $documentRequest = DocumentRequest::findOrFail($id);

        // Optional reason/notes
        $reason = request()->input('registrar_notes');
        if (!empty($reason)) {
            $documentRequest->registrar_notes = $reason;
        }

        $documentRequest->status = 'rejected';
        $documentRequest->rejected_at = now();
        $documentRequest->rejected_by = auth()->id();
        $documentRequest->save();
        
        // Send rejection email with reason
        try {
            Mail::to($documentRequest->email)->send(new RequestRejectedMail($documentRequest, $reason ?? ''));
        } catch (\Throwable $e) {
            \Log::error('Failed to send RequestRejectedMail: ' . $e->getMessage());
        }

        // Log rejection
        SystemLogHelper::log('rejected', 'Request #' . $documentRequest->id . ' rejected by registrar.');
        return back()->with('success', 'Request rejected. Email notification sent to requester.');
    }

    private function generateUniqueReferenceNumber(): string
    {
        do {
            $candidate = 'REQ-' . now()->format('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        } while (DocumentRequest::where('reference_number', $candidate)->exists());
        return $candidate;
    }

    public function pendingCount()
    {
        $today = now()->startOfDay();
        
        $stats = [
            'pending' => \App\Models\DocumentRequest::where('status', 'pending_registrar_approval')->count(),
            'approved_today' => \App\Models\DocumentRequest::where('status', 'approved')
                ->whereDate('approved_at', $today)
                ->count(),
            'rejected_today' => \App\Models\DocumentRequest::where('status', 'rejected')
                ->whereDate('rejected_at', $today)
                ->count(),
            'completed_today' => \App\Models\DocumentRequest::where('status', 'completed')
                ->whereDate('updated_at', $today)
                ->count(),
        ];
        
        return response()->json($stats);
    }

    // Department logo upload/update
    public function updateLogo(Request $request)
    {
        $request->validate([
            'department' => 'required|string',
            'logo' => 'required|image|max:2048',
        ]);

        $department = $request->input('department');
        $file = $request->file('logo');
        $path = $file->store('department_logos', 'public');

        $logo = DepartmentLogo::updateOrCreate(
            ['department_name' => $department],
            ['logo_path' => $path]
        );

        return response()->json([
            'success' => true,
            'logo_url' => asset('storage/' . $path),
        ]);
    }
}
