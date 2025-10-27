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
    // Export main reports table as CSV
    public function exportReport(Request $request)
    {
        $type = $request->query('type', 'document_requests');
        $csvHeader = [];
        $rows = [];
        if ($type === 'document_requests') {
            $reports = DocumentRequest::select('id', 'created_at', 'status')
                ->orderBy('created_at', 'desc')
                ->get();
            $csvHeader = ['Request ID', 'Period', 'Generated', 'Status'];
            foreach ($reports as $report) {
                $rows[] = [
                    $report->id,
                    $report->created_at->format('Y-m'),
                    $report->created_at->format('Y-m-d H:i'),
                    ucfirst($report->status),
                ];
            }
        } elseif ($type === 'student_records') {
            $students = \App\Models\Student::select('student_id', 'first_name', 'last_name', 'program', 'year_level', 'school_year', 'status')
                ->orderBy('created_at', 'desc')
                ->get();
            $csvHeader = ['Student ID', 'Name', 'Program', 'Year Level', 'School Year', 'Status'];
            foreach ($students as $student) {
                $rows[] = [
                    $student->student_id,
                    $student->first_name . ' ' . $student->last_name,
                    $student->program,
                    $student->year_level,
                    $student->school_year,
                    ucfirst($student->status),
                ];
            }
        } elseif ($type === 'user_activity') {
            $logs = \App\Models\SystemLog::select('id', 'type', 'message', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get();
            $csvHeader = ['Log ID', 'Type', 'Message', 'Date'];
            foreach ($logs as $log) {
                $rows[] = [
                    $log->id,
                    $log->type,
                    $log->message,
                    $log->created_at ? $log->created_at->format('Y-m-d H:i') : '',
                ];
            }
        } else {
            // Default: empty
        }

        $output = fopen('php://temp', 'r+');
        if (!empty($csvHeader)) {
            fputcsv($output, $csvHeader);
        }
        foreach ($rows as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        $filename = $type . '_report.csv';
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    // Profile update handler
    public function updateProfile(Request $request)
    {
        \Log::info('Profile update request received', [
            'user_id' => auth()->id(),
            'input' => $request->all(),
        ]);

        try {
            $request->validate([
                'username' => 'required|string|max:255|unique:users,username,' . auth()->id(),
                'password' => 'nullable|string|min:8|confirmed',
                'avatar' => 'nullable|image|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Profile update validation failed', [
                'user_id' => auth()->id(),
                'errors' => $e->errors(),
            ]);
            return back()->withErrors($e->errors())->withInput();
        }

        $user = \App\Models\User::find(auth()->id());
        if (!$user) {
            \Log::error('Profile update failed: user not found for id ' . auth()->id());
            return back()->with('error', 'User not found.');
        }

        $oldUsername = $user->username;
        $user->username = $request->input('username');

        if ($request->filled('password')) {
            $newPassword = $request->input('password');
            if (strlen($newPassword) < 8) {
                \Log::error('Profile update failed: password too short', [
                    'user_id' => $user->id,
                    'password_length' => strlen($newPassword),
                ]);
                return back()->with('error', 'Password must be at least 8 characters.');
            }
            $user->password = bcrypt($newPassword);
        }

        if ($request->hasFile('avatar')) {
            try {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $avatarPath;
            } catch (\Exception $ex) {
                \Log::error('Avatar upload failed', [
                    'user_id' => $user->id,
                    'error' => $ex->getMessage(),
                ]);
                return back()->with('error', 'Avatar upload failed: ' . $ex->getMessage());
            }
        }

        $saved = $user->save();
        if (!$saved) {
            \Log::error('Profile update failed: user save returned false', [
                'user_id' => $user->id,
                'data' => $user->toArray(),
            ]);
            return back()->with('error', 'Profile update failed.');
        }

        \Log::info('Profile updated for user id ' . $user->id . ': username changed from ' . $oldUsername . ' to ' . $user->username . ', avatar: ' . ($user->avatar ?? 'none'));

        return back()->with('success', 'Profile updated successfully.');
        \Log::info('Profile update request received', [
            'user_id' => auth()->id(),
            'input' => $request->all(),
        ]);

        try {
            $request->validate([
                'username' => 'required|string|max:255|unique:users,username,' . auth()->id(),
                'password' => 'nullable|string|min:8|confirmed',
                'avatar' => 'nullable|image|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Profile update validation failed', [
                'user_id' => auth()->id(),
                'errors' => $e->errors(),
            ]);
            return back()->withErrors($e->errors())->withInput();
        }

        $user = \App\Models\User::find(auth()->id());
        if (!$user) {
            \Log::error('Profile update failed: user not found for id ' . auth()->id());
            return back()->with('error', 'User not found.');
        }

        $oldUsername = $user->username;
        $user->username = $request->input('username');

        if ($request->filled('password')) {
            $newPassword = $request->input('password');
            if (strlen($newPassword) < 8) {
                \Log::error('Profile update failed: password too short', [
                    'user_id' => $user->id,
                    'password_length' => strlen($newPassword),
                ]);
                return back()->with('error', 'Password must be at least 8 characters.');
            }
            $user->password = bcrypt($newPassword);
        }

        if ($request->hasFile('avatar')) {
            try {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $avatarPath;
            } catch (\Exception $ex) {
                \Log::error('Avatar upload failed', [
                    'user_id' => $user->id,
                    'error' => $ex->getMessage(),
                ]);
                return back()->with('error', 'Avatar upload failed: ' . $ex->getMessage());
            }
        }

        $saved = $user->save();
        if (!$saved) {
            \Log::error('Profile update failed: user save returned false', [
                'user_id' => $user->id,
                'data' => $user->toArray(),
            ]);
            return back()->with('error', 'Profile update failed.');
        }

        \Log::info('Profile updated for user id ' . $user->id . ': username changed from ' . $oldUsername . ' to ' . $user->username . ', avatar: ' . ($user->avatar ?? 'none'));

        return back()->with('success', 'Profile updated successfully.');
    }

    // AJAX endpoint: Document Requests report
    public function reportDocumentRequests(Request $request)
    {
        $query = DocumentRequest::with('requestedDocuments');
        // Filtering
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->has('document_type') && $request->document_type !== 'all') {
            $query->whereHas('requestedDocuments', function($q) use ($request) {
                $q->where('document_type', $request->document_type);
            });
        }
        // Sorting
        $sort = $request->get('sort', 'created_at');
        $dir = $request->get('dir', 'desc');
        $query->orderBy($sort, $dir);
        // Pagination
        $perPage = $request->get('per_page', 20);
        $page = $request->get('page', 1);
        $requests = $query->paginate($perPage, ['*'], 'page', $page);
        return response()->json($requests);
    }

    // AJAX endpoint: Student Records report
    public function reportStudentRecords(Request $request)
    {
        $query = Student::query();
        // Filtering
        if ($request->has('program') && $request->program !== 'all') {
            $query->where('program', $request->program);
        }
        if ($request->has('year_level') && $request->year_level !== 'all') {
            $query->where('year_level', $request->year_level);
        }
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        // Sorting
        $sort = $request->get('sort', 'created_at');
        $dir = $request->get('dir', 'desc');
        $query->orderBy($sort, $dir);
        // Pagination
        $perPage = $request->get('per_page', 20);
        $page = $request->get('page', 1);
        $students = $query->paginate($perPage, ['*'], 'page', $page);
        return response()->json($students);
    }

    // AJAX endpoint: User Activity (System Logs) report
    public function reportUserActivity(Request $request)
    {
        $query = SystemLog::query();
        // Filtering
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        if ($request->has('user_id') && $request->user_id !== 'all') {
            $query->where('user_id', $request->user_id);
        }
        // Sorting
        $sort = $request->get('sort', 'created_at');
        $dir = $request->get('dir', 'desc');
        $query->orderBy($sort, $dir);
        // Pagination
        $perPage = $request->get('per_page', 20);
        $page = $request->get('page', 1);
        $logs = $query->paginate($perPage, ['*'], 'page', $page);
        return response()->json($logs);
    }
    // AJAX endpoint for Verify Modal
    public function verifyModal($id)
    {
        \Log::info('verifyModal called', ['request_id' => $id]);
        $request = \App\Models\DocumentRequest::with('requestedDocuments')->find($id);
        if (!$request) {
            \Log::warning('verifyModal: DocumentRequest not found', ['request_id' => $id]);
            return response()->json(['found' => false, 'request_id' => $id]);
        }

        // Normalize input
        $reqStudentId = trim(strtolower($request->student_id));
        $reqFirstName = trim(strtolower($request->first_name));
        $reqLastName = trim(strtolower($request->last_name));
        $reqMiddleName = trim(strtolower($request->middle_name ?? ''));

        \Log::info('verifyModal normalized values', [
            'student_id' => $reqStudentId,
            'first_name' => $reqFirstName,
            'middle_name' => $reqMiddleName,
            'last_name' => $reqLastName
        ]);

        // Try to match student by student_id
        $student = null;
        if ($reqStudentId) {
            $student = \App\Models\Student::whereRaw('LOWER(TRIM(student_id)) = ?', [$reqStudentId])->first();
            \Log::info('verifyModal student_id match', ['found' => !!$student]);
        }
        // Try to match by first and last name (case/space insensitive)
        if (!$student && $reqFirstName && $reqLastName) {
            $student = \App\Models\Student::whereRaw('LOWER(TRIM(first_name)) = ?', [$reqFirstName])
                ->whereRaw('LOWER(TRIM(last_name)) = ?', [$reqLastName])
                ->first();
            \Log::info('verifyModal first+last name match', ['found' => !!$student]);
        }
        // Try to match by full name including middle name
        if (!$student && $reqFirstName && $reqMiddleName && $reqLastName) {
            $student = \App\Models\Student::whereRaw('LOWER(TRIM(first_name)) = ?', [$reqFirstName])
                ->whereRaw('LOWER(TRIM(middle_name)) = ?', [$reqMiddleName])
                ->whereRaw('LOWER(TRIM(last_name)) = ?', [$reqLastName])
                ->first();
            \Log::info('verifyModal full name match', ['found' => !!$student]);
        }

        if ($student) {
            \Log::info('verifyModal: student matched', ['student_id' => $student->student_id]);
            $availableDocs = [];
            foreach ($request->requestedDocuments as $doc) {
                $availableDocs[] = $doc->document_type;
            }
            return response()->json([
                'found' => true,
                'full_name' => trim($student->first_name . ' ' . ($student->middle_name ? $student->middle_name . ' ' : '') . $student->last_name),
                'documents' => $availableDocs,
                'request_id' => $id
            ]);
        }
        \Log::warning('verifyModal: no student matched', ['request_id' => $id]);
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

    public function updateStudent(Request $request, $id)
    {
        try {
            $student = Student::findOrFail($id);

            $validated = $request->validate([
                'student_id' => 'sometimes|string|unique:students,student_id,' . $id,
                'first_name' => 'sometimes|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'sometimes|string|max:255',
                'program' => 'sometimes|string|max:255',
                'year_level' => 'sometimes|string|max:50',
                'school_year' => 'sometimes|string|max:50',
                'status' => 'sometimes|string|max:50',
            ]);

            $student->update($validated);

            // Log student updated
            SystemLogHelper::log('student_updated', 'Student record for ' . $student->first_name . ' ' . $student->last_name . ' updated by registrar.');

            return response()->json([
                'success' => true,
                'message' => 'Student record updated successfully',
                'student' => $student->fresh()
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Student update failed: ' . $e->getMessage(), ['id' => $id, 'request' => $request->all()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update student record: ' . $e->getMessage()
            ], 500);
        }
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
        $studentsRaw = \App\Models\Student::select('id', 'student_id', 'first_name', 'middle_name', 'last_name', 'program', 'year_level', 'school_year', 'status')->get();
        $students = $studentsRaw->groupBy(['program', 'year_level']);

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
