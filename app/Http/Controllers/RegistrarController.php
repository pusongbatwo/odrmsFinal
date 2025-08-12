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

class RegistrarController extends Controller
{
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

        $analytics = DocumentRequest::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $defaultAnalytics = [
            'pending' => 0,
            'approved' => 0,
            'completed' => 0,
            'rejected' => 0
        ];
        $analytics = array_merge($defaultAnalytics, $analytics);

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
        $request = DocumentRequest::findOrFail($id);

        // Example: Check if student info exists (customize as needed)
        // $studentExists = Student::where('student_id', $request->student_id)->exists();
        // if (!$studentExists) {
        //     return back()->with('error', 'Student info not found.');
        // }

        $request->status = 'approved';
        $request->save();
        \App\Models\CashierLog::create([
            'type' => 'document_approved',
            'message' => 'Document request #' . $request->reference_number . ' approved by registrar.'
        ]);

        // Send email
        Mail::to($request->email)->send(new RequestApprovedMail($request));

        // Log approval
        SystemLogHelper::log('approved', 'Request #' . $request->id . ' approved by registrar.');

        return back()->with('success', 'Request approved and email sent.');
    }

    public function reject($id)
    {
        $request = DocumentRequest::findOrFail($id);
        $request->status = 'rejected';
        $request->save();
        // Send rejection email
        Mail::to($request->email)->send(new RequestRejectedMail($request));

        // Log rejection
        SystemLogHelper::log('rejected', 'Request #' . $request->id . ' rejected by registrar.');
        return back()->with('success', 'Request rejected and email sent.');
    }

    public function pendingCount()
    {
        $pending = \App\Models\DocumentRequest::where('status', 'pending')->count();
        return response()->json(['pending' => $pending]);
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
