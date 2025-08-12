<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use App\Models\PersonalInformation;
use App\Models\ContactInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentRequestSubmitted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\RequestedDocument;
use Illuminate\Support\Facades\Log;
use App\Mail\PaymentConfirmationMail;

class DocumentRequestController extends Controller
{
    public function index()
    {
        $requests = DocumentRequest::with(['personalInfo', 'contactInfo'])
            ->where('status', 'Pending')
            ->get();
        return view('request-form', compact('requests'));
    }

    public function store(Request $request)
    {
        // Prevent duplicate student_id
        if (\App\Models\DocumentRequest::where('student_id', $request->student_id)->exists()) {
            $message = 'A request for this Student ID already exists.';
            if ($request->expectsJson() || $request->isJson()) {
                return response()->json(['success' => false, 'message' => $message], 409);
            }
            return back()->with('error', $message);
        }
        Log::info('DocumentRequestController@store called', $request->all()); // Debug log
        $validated = $request->validate([
            'student_id' => 'required|string|max:50',
            'course' => 'required|string|max:100',
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'last_name' => 'required|string|max:50',
            'province' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'barangay' => 'required|string|max:50',
            'mobile' => 'nullable|string|max:20',
            'email' => 'required|email|max:50',
            'purpose' => 'nullable|string',
            'special_instructions' => 'nullable|string',
            'document_types' => 'required|array|min:1',
            'document_types.*.type' => 'required|string',
            'document_types.*.quantity' => 'required|integer|min:1',
            'year_level' => 'required|string|max:20',
            'school_years' => 'nullable|array',
        ]);
        
        $docRequest = null;
        try {
            DB::transaction(function() use ($validated, &$docRequest) {
                $docRequest = DocumentRequest::create([
                    'student_id' => $validated['student_id'],
                    'first_name' => $validated['first_name'],
                    'middle_name' => $validated['middle_name'],
                    'last_name' => $validated['last_name'],
                    'course' => $validated['course'],
                    'province' => $validated['province'],
                    'city' => $validated['city'],
                    'barangay' => $validated['barangay'],
                    'mobile_number' => $validated['mobile'],
                    'email' => $validated['email'],
                    'purpose' => $validated['purpose'],
                    'special_instructions' => $validated['special_instructions'],
                    'reference_number' => 'REQ-' . strtoupper(uniqid()),
                    'status' => 'pending',
                    'payment_status' => 'unpaid',
                    'request_date' => now(),
                    'year_level' => $validated['year_level'],
                    'school_years' => $validated['school_years'] ?? [],
                ]);
                foreach ($validated['document_types'] as $doc) {
                    RequestedDocument::create([
                        'request_id' => $docRequest->id,
                        'document_type' => $doc['type'],
                        'quantity' => $doc['quantity'],
                    ]);
                }
            });
        } catch (\Exception $e) {
            Log::error('Failed to save document request: ' . $e->getMessage());
            if ($request->expectsJson() || $request->isJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to save request.'], 500);
            }
            return back()->with('error', 'Failed to save request.');
        }

        if (!$docRequest) {
            if ($request->expectsJson() || $request->isJson()) {
                return response()->json(['success' => false, 'message' => 'Request could not be saved.'], 500);
            }
            abort(500, 'Request could not be saved.');
        }

        // Send email to the user
       // try {
       //     Mail::to($validated['email'])->send(new DocumentRequestSubmitted($docRequest));
       //     Log::info("Successfully sent document submission email to {$validated['email']}");
       // } catch (\Exception $e) {
       //     Log::error("Failed to send email: " . $e->getMessage());
      //  }

        if ($request->expectsJson() || $request->isJson()) {
            return response()->json(['success' => true, 'reference' => $docRequest->reference_number]);
        }
        return redirect()->route('request.success', ['reference' => $docRequest->reference_number]);
    }

    public function storeAlumni(Request $request)
    {
        Log::info('DocumentRequestController@storeAlumni called', $request->all()); // Debug log
        Log::info('Alumni ID value:', ['alumni_id' => $request->input('alumni_id')]); // Debug alumni_id specifically
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'last_name' => 'required|string|max:50',
            'course' => 'required|string|max:100',
            'graduation_year' => 'required|string|max:20',
            'province' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'barangay' => 'required|string|max:50',
            'email' => 'required|email|max:50',
            'mobile' => 'required|string|max:20',
            'alumni_id' => 'nullable|string|max:50',
            'purpose' => 'required|string',
            'special_instructions' => 'nullable|string',
            'document_types' => 'required|array|min:1',
            'document_types.*.type' => 'required|string',
            'document_types.*.quantity' => 'required|integer|min:1',
        ]);
        
        $docRequest = null;
        try {
            DB::transaction(function() use ($validated, &$docRequest) {
                $docRequest = DocumentRequest::create([
                    'student_id' => 'Alumni',
                    'first_name' => $validated['first_name'],
                    'middle_name' => $validated['middle_name'],
                    'last_name' => $validated['last_name'],
                    'course' => $validated['course'],
                    'province' => $validated['province'],
                    'city' => $validated['city'],
                    'barangay' => $validated['barangay'],
                    'mobile_number' => $validated['mobile'],
                    'email' => $validated['email'],
                    'purpose' => $validated['purpose'],
                    'special_instructions' => $validated['special_instructions'],
                    'reference_number' => 'REQ-' . strtoupper(uniqid()),
                    'status' => 'pending',
                    'payment_status' => 'unpaid',
                    'request_date' => now(),
                    'year_level' => 'Alumni',
                    'school_years' => null,
                    'alumni_id' => $validated['alumni_id'] ? $validated['alumni_id'] : null,
                    'graduation_year' => $validated['graduation_year'],
                ]);
                
                foreach ($validated['document_types'] as $doc) {
                    RequestedDocument::create([
                        'request_id' => $docRequest->id,
                        'document_type' => $doc['type'],
                        'quantity' => $doc['quantity'],
                    ]);
                }
            });
        } catch (\Exception $e) {
            Log::error('Failed to save alumni document request: ' . $e->getMessage());
            if ($request->expectsJson() || $request->isJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to save alumni request.'], 500);
            }
            return back()->with('error', 'Failed to save alumni request.');
        }

        if (!$docRequest) {
            if ($request->expectsJson() || $request->isJson()) {
                return response()->json(['success' => false, 'message' => 'Alumni request could not be saved.'], 500);
            }
            abort(500, 'Alumni request could not be saved.');
        }

        if ($request->expectsJson() || $request->isJson()) {
            return response()->json(['success' => true, 'reference' => $docRequest->reference_number]);
        }
        return redirect()->route('request.success', ['reference' => $docRequest->reference_number]);
    }

    public function success($reference)
    {
        return view('request-success', [
            'reference' => $reference,
        ]);
    }

    public function track(Request $request)
    {
        $request->validate([
            'reference_number' => 'required|string',
        ]);

        $document = DocumentRequest::with(['personalInfo', 'contactInfo'])
            ->where('reference_number', $request->reference_number)
            ->first();

        if (!$document) {
            return back()->with('error', 'No document found with that reference number');
        }

        return view('track-result', [
            'document' => $document,
        ]);
    }

    public function dashboard()
    {
        $requests = DocumentRequest::select('request_id', 'first_name', 'last_name', 'document_type', 'date_requested', 'status')
            ->get();

        $analytics = DocumentRequest::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $defaultAnalytics = ['pending' => 0, 'processing' => 0, 'completed' => 0, 'rejected' => 0];
        $analytics = array_merge($defaultAnalytics, $analytics);

        return view('registrar.dashboard', compact('requests', 'analytics'));
    }

    public function processPayment(Request $request)
    {
        $validated = $request->validate([
            'reference_number' => 'required|string',
            'amount_received' => 'required|numeric|min:0',
        ]);

        $documentRequest = DocumentRequest::where('reference_number', $validated['reference_number'])->with('requestedDocuments')->first();

        if (!$documentRequest) {
            return response()->json(['success' => false, 'message' => 'Document request not found.'], 404);
        }

        // Calculate amount due
        $fees = config('services.document_fees', []);
        $amountDue = 0;
        foreach ($documentRequest->requestedDocuments as $doc) {
            $amountDue += ($fees[$doc->document_type] ?? 250) * $doc->quantity;
        }
        // Update payment status
        $documentRequest->payment_status = 'paid';
        $documentRequest->amount_paid = $amountDue;
        $documentRequest->save();

        // Send payment confirmation email
        Mail::to($documentRequest->email)->send(new PaymentConfirmationMail($documentRequest->reference_number));

        return response()->json(['success' => true, 'message' => 'Payment processed and email sent.']);
    }
}