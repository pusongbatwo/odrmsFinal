<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use App\Models\PersonalInformation;
use App\Models\ContactInformation;
use App\Models\DocumentRequestVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentRequestSubmitted;
use App\Mail\DocumentRequestVerificationMail;
use App\Mail\RequestApprovedMail;
use App\Mail\RequestRejectedMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\RequestedDocument;
use Illuminate\Support\Facades\Log;
use App\Mail\PaymentConfirmationMail;
use Carbon\Carbon;

class DocumentRequestController extends Controller
{
    /**
     * Get all available document types
     * @return array
     */
    private function getAllDocumentTypes()
    {
        return [
            'TRANSCRIPT OF RECORDS',
            'TRANSCRIPT OF RECORDS FOR EVALUATION',
            'HONORABLE DISMISSAL',
            'DIPLOMA',
            'CERTIFICATE OF NO OBJECTION',
            'CERTIFICATE OF ENGLISH AS MEDIUM',
            'CERTIFICATE OF GOOD MORAL',
            'CERTIFICATE OF REGISTRATION',
            'CERTIFICATE OF COMPLETION',
            'CERTIFICATE OF GRADES',
            'STATEMENT OF ACCOUNT',
            'SERVICE RECORD',
            'EMPLOYMENT',
            'PERFORMANCE RATING',
            'GWA CERTIFICATE'
        ];
    }

    /**
     * Check if a user is on cooldown for specific document types
     * @param string $identifier - Student ID or Alumni email
     * @param array $documentTypes - Array of document types to check
     * @param bool $isAlumni - Whether this is an alumni request
     * @return array - Array of cooldown violations
     */
    private function checkCooldown($identifier, $documentTypes, $isAlumni = false)
    {
        $cooldownDays = 40;
        $cooldownViolations = [];
        
        foreach ($documentTypes as $doc) {
            $documentType = $doc['type'];
            
            // Find the last request for this document type by this user
            $lastRequest = DocumentRequest::where(function($query) use ($identifier, $isAlumni) {
                if ($isAlumni) {
                    $query->where('email', $identifier);
                } else {
                    $query->where('student_id', $identifier);
                }
            })
            ->whereHas('requestedDocuments', function($query) use ($documentType) {
                $query->where('document_type', $documentType);
            })
            ->orderBy('request_date', 'desc')
            ->first();
            
            if ($lastRequest) {
                $lastRequestDate = Carbon::parse($lastRequest->request_date);
                $daysSinceLastRequest = Carbon::now()->diffInDays($lastRequestDate);
                
                if ($daysSinceLastRequest < $cooldownDays) {
                    $remainingDays = $cooldownDays - $daysSinceLastRequest;
                    $cooldownViolations[] = [
                        'document_type' => $documentType,
                        'last_request_date' => $lastRequestDate->format('M d, Y'),
                        'remaining_days' => $remainingDays,
                        'message' => "You can only request {$documentType} again after {$cooldownDays} days from your last request. Please try another document type or wait until your cooldown period ends. (Last request: {$lastRequestDate->format('M d, Y')}, {$remainingDays} days remaining)"
                    ];
                }
            }
        }
        
        return $cooldownViolations;
    }

    /**
     * Get available document types for a user (not on cooldown)
     * @param string $identifier - Student ID or Alumni email
     * @param bool $isAlumni - Whether this is an alumni request
     * @return array - Array of available document types
     */
    private function getAvailableDocumentTypes($identifier, $isAlumni = false)
    {
        $allDocumentTypes = $this->getAllDocumentTypes();
        
        $availableTypes = [];
        
        try {
            foreach ($allDocumentTypes as $docType) {
                // Find the last request for this document type by this user
                $lastRequest = DocumentRequest::where(function($query) use ($identifier, $isAlumni) {
                    if ($isAlumni) {
                        $query->where('email', $identifier);
                    } else {
                        $query->where('student_id', $identifier);
                    }
                })
                ->whereHas('requestedDocuments', function($query) use ($docType) {
                    $query->where('document_type', $docType);
                })
                ->orderBy('request_date', 'desc')
                ->first();
                
                if (!$lastRequest) {
                    // No previous request for this document type - available
                    $availableTypes[] = $docType;
                } else {
                    $lastRequestDate = Carbon::parse($lastRequest->request_date);
                    $daysSinceLastRequest = Carbon::now()->diffInDays($lastRequestDate);
                    
                    if ($daysSinceLastRequest >= 40) {
                        // Cooldown period has passed - available
                        $availableTypes[] = $docType;
                    }
                    // If daysSinceLastRequest < 40, document type is NOT added to available types
                }
            }
        } catch (\Exception $e) {
            Log::error('Database error in getAvailableDocumentTypes: ' . $e->getMessage());
            // If there's a database error, return all types as available
            return $allDocumentTypes;
        }
        
        return $availableTypes;
    }

    /**
     * Send verification email for document request
     * @param array $requestData - The validated request data
     * @param bool $isAlumni - Whether this is an alumni request
     * @return array - Response with success status and message
     */
    private function sendVerificationEmail($requestData, $isAlumni = false)
    {
        try {
            // Generate verification token
            $verificationToken = DocumentRequestVerification::generateToken();
            
            // Set expiration time (24 hours from now)
            $expiresAt = Carbon::now()->addHours(24);
            
            // Store verification record
            DocumentRequestVerification::create([
                'email' => $requestData['email'],
                'verification_token' => $verificationToken,
                'request_data' => $requestData,
                'expires_at' => $expiresAt,
                'is_verified' => false
            ]);
            
            // Send verification email
            try {
                Mail::to($requestData['email'])->send(new DocumentRequestVerificationMail(
                    $verificationToken,
                    $requestData,
                    $expiresAt
                ));
                
                Log::info("Verification email sent successfully to {$requestData['email']}");
            } catch (\Exception $e) {
                Log::error("Email sending failed: " . $e->getMessage());
                // For now, let's continue without email to test the workflow
                // In production, you would want to handle this differently
            }
            
            return [
                'success' => true,
                'message' => 'Verification email sent successfully. Please check your email and click the verification link to complete your request.',
                'verification_sent' => true
            ];
            
        } catch (\Exception $e) {
            Log::error("Failed to send verification email: " . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to send verification email. Please try again or contact support.',
                'verification_sent' => false
            ];
        }
    }

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
            'province' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:50',
            'barangay' => 'nullable|string|max:50',
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

        // Check cooldown for document types
        $cooldownViolations = $this->checkCooldown($validated['student_id'], $validated['document_types'], false);
        
        if (!empty($cooldownViolations)) {
            $errorMessage = "Cooldown period violation:\n\n";
            foreach ($cooldownViolations as $violation) {
                $errorMessage .= "• {$violation['message']}\n\n";
            }
            
            if ($request->expectsJson() || $request->isJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => $errorMessage,
                    'cooldown_violations' => $cooldownViolations
                ], 429); // Too Many Requests
            }
            return back()->with('error', $errorMessage);
        }

        // Send verification email instead of directly saving
        $verificationResult = $this->sendVerificationEmail($validated, false);
        
        if ($request->expectsJson() || $request->isJson()) {
            if ($verificationResult['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $verificationResult['message'],
                    'verification_sent' => true
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $verificationResult['message']
                ], 500);
            }
        }
        
        if ($verificationResult['success']) {
            return back()->with('success', $verificationResult['message']);
        } else {
            return back()->with('error', $verificationResult['message']);
        }
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
            'province' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:50',
            'barangay' => 'nullable|string|max:50',
            'email' => 'required|email|max:50',
            'mobile' => 'required|string|max:20',
            'alumni_id' => 'nullable|string|max:50',
            'purpose' => 'required|string',
            'special_instructions' => 'nullable|string',
            'document_types' => 'required|array|min:1',
            'document_types.*.type' => 'required|string',
            'document_types.*.quantity' => 'required|integer|min:1',
        ]);

        // Check cooldown for document types using email as identifier
        $cooldownViolations = $this->checkCooldown($validated['email'], $validated['document_types'], true);
        
        if (!empty($cooldownViolations)) {
            $errorMessage = "Cooldown period violation:\n\n";
            foreach ($cooldownViolations as $violation) {
                $errorMessage .= "• {$violation['message']}\n\n";
            }
            
            if ($request->expectsJson() || $request->isJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => $errorMessage,
                    'cooldown_violations' => $cooldownViolations
                ], 429); // Too Many Requests
            }
            return back()->with('error', $errorMessage);
        }

        // Send verification email instead of directly saving
        $verificationResult = $this->sendVerificationEmail($validated, true);
        
        if ($request->expectsJson() || $request->isJson()) {
            if ($verificationResult['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $verificationResult['message'],
                    'verification_sent' => true
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $verificationResult['message']
                ], 500);
            }
        }
        
        if ($verificationResult['success']) {
            return back()->with('success', $verificationResult['message']);
        } else {
            return back()->with('error', $verificationResult['message']);
        }
    }

    /**
     * Verify document request via email link
     * @param string $token - Verification token
     * @return \Illuminate\Http\Response
     */
    public function verifyRequest($token)
    {
        try {
            // Find the verification record
            $verification = DocumentRequestVerification::where('verification_token', $token)
                ->where('is_verified', false)
                ->first();

            if (!$verification) {
                return view('verification-failed', [
                    'message' => 'Invalid or expired verification link. Please submit a new request.'
                ]);
            }

            // Check if verification has expired
            if ($verification->isExpired()) {
                return view('verification-failed', [
                    'message' => 'Verification link has expired. Please submit a new request.'
                ]);
            }

            // Mark verification as verified
            $verification->markAsVerified();

            // Extract request data
            $requestData = $verification->request_data;

            // Now save the actual document request with pending registrar approval status
            $docRequest = null;
            DB::transaction(function() use ($requestData, &$docRequest) {
                $sanitizedProvince = $requestData['province'] ?? '';
                $sanitizedCity = $requestData['city'] ?? '';
                $sanitizedBarangay = $requestData['barangay'] ?? '';
                $sanitizedPurpose = $requestData['purpose'] ?? 'Not specified';
                $sanitizedMiddleName = $requestData['middle_name'] ?? null;
                $sanitizedSpecialInstructions = $requestData['special_instructions'] ?? null;
                $sanitizedMobile = $requestData['mobile'] ?? ($requestData['mobile_number'] ?? '');

                $docRequest = DocumentRequest::create([
                    'student_id' => $requestData['student_id'] ?? ($requestData['alumni_id'] ?? 'ALUMNI'),
                    'first_name' => $requestData['first_name'] ?? '',
                    'middle_name' => $sanitizedMiddleName,
                    'last_name' => $requestData['last_name'] ?? '',
                    'course' => $requestData['course'] ?? 'N/A',
                    'province' => $sanitizedProvince,
                    'city' => $sanitizedCity,
                    'barangay' => $sanitizedBarangay,
                    'mobile_number' => $sanitizedMobile,
                    'email' => $requestData['email'] ?? '',
                    'purpose' => $sanitizedPurpose,
                    'special_instructions' => $sanitizedSpecialInstructions,
                    'reference_number' => null, // No reference number until approved
                    'status' => 'pending_registrar_approval', // New status
                    'payment_status' => 'unpaid',
                    'request_date' => now(),
                    'year_level' => $requestData['year_level'] ?? 'Alumni',
                    'school_years' => $requestData['school_years'] ?? null,
                    'alumni_id' => $requestData['alumni_id'] ?? null,
                    'graduation_year' => $requestData['graduation_year'] ?? null,
                ]);

                foreach ($requestData['document_types'] as $doc) {
                    RequestedDocument::create([
                        'request_id' => $docRequest->id,
                        'document_type' => $doc['type'],
                        'quantity' => $doc['quantity'],
                    ]);
                }
            });

            if (!$docRequest) {
                throw new \Exception('Failed to create document request after verification');
            }

            Log::info("Document request verified and created successfully: {$docRequest->reference_number}");

            // Show success page
            return view('verification-success', [
                'reference_number' => $docRequest->reference_number,
                'request' => $docRequest
            ]);

        } catch (\Exception $e) {
            Log::error("Verification failed: " . $e->getMessage());
            
            return view('verification-failed', [
                'message' => 'An error occurred during verification. Please try again or contact support.'
            ]);
        }
    }

    public function success($reference)
    {
        return view('request-success', [
            'reference' => $reference,
        ]);
    }

    /**
     * Check available document types for a user (not on cooldown)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkAvailableDocumentTypes(Request $request)
    {
        try {
            $request->validate([
                'identifier' => 'required|string',
                'is_alumni' => 'required|boolean'
            ]);

            Log::info('Checking available documents', [
                'identifier' => $request->identifier,
                'is_alumni' => $request->is_alumni
            ]);

            $availableTypes = $this->getAvailableDocumentTypes(
                $request->identifier, 
                $request->is_alumni
            );

            // Get cooldown information for all document types
            $cooldownInfo = $this->getCooldownInformation(
                $request->identifier, 
                $request->is_alumni
            );

            Log::info('Available document types result', [
                'available_count' => count($availableTypes),
                'cooldown_count' => count($cooldownInfo),
                'available_types' => $availableTypes
            ]);

            return response()->json([
                'success' => true,
                'available_document_types' => $availableTypes,
                'cooldown_information' => $cooldownInfo
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking available documents: ' . $e->getMessage());
            
            // Return all document types as available if there's an error (like DB connection)
            $allDocumentTypes = $this->getAllDocumentTypes();
            
            $cooldownInfo = [];
            foreach ($allDocumentTypes as $docType) {
                $cooldownInfo[$docType] = [
                    'last_request_date' => null,
                    'remaining_days' => 0,
                    'is_on_cooldown' => false
                ];
            }
            
            return response()->json([
                'success' => true,
                'available_document_types' => $allDocumentTypes,
                'cooldown_information' => $cooldownInfo
            ]);
        }
    }

    /**
     * Get cooldown information for document types
     * @param string $identifier - Student ID or Alumni email
     * @param bool $isAlumni - Whether this is an alumni request
     * @return array - Array of cooldown information
     */
    private function getCooldownInformation($identifier, $isAlumni = false)
    {
        $allDocumentTypes = $this->getAllDocumentTypes();
        
        $cooldownInfo = [];
        
        try {
            foreach ($allDocumentTypes as $docType) {
                // Find the last request for this document type by this user
                $lastRequest = DocumentRequest::where(function($query) use ($identifier, $isAlumni) {
                    if ($isAlumni) {
                        $query->where('email', $identifier);
                    } else {
                        $query->where('student_id', $identifier);
                    }
                })
                ->whereHas('requestedDocuments', function($query) use ($docType) {
                    $query->where('document_type', $docType);
                })
                ->orderBy('request_date', 'desc')
                ->first();
                
                if ($lastRequest) {
                    $lastRequestDate = Carbon::parse($lastRequest->request_date);
                    $daysSinceLastRequest = Carbon::now()->diffInDays($lastRequestDate);
                    
                    if ($daysSinceLastRequest < 40) {
                        $remainingDays = 40 - $daysSinceLastRequest;
                        $cooldownInfo[$docType] = [
                            'last_request_date' => $lastRequestDate->format('M d, Y'),
                            'remaining_days' => $remainingDays,
                            'is_on_cooldown' => true
                        ];
                    } else {
                        $cooldownInfo[$docType] = [
                            'last_request_date' => $lastRequestDate->format('M d, Y'),
                            'remaining_days' => 0,
                            'is_on_cooldown' => false
                        ];
                    }
                } else {
                    // No previous request - not on cooldown
                    $cooldownInfo[$docType] = [
                        'last_request_date' => null,
                        'remaining_days' => 0,
                        'is_on_cooldown' => false
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error('Database error in getCooldownInformation: ' . $e->getMessage());
            // If there's a database error, return all types as not on cooldown
            foreach ($allDocumentTypes as $docType) {
                $cooldownInfo[$docType] = [
                    'last_request_date' => null,
                    'remaining_days' => 0,
                    'is_on_cooldown' => false
                ];
            }
        }
        
        return $cooldownInfo;
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

    /**
     * Approve a document request (Registrar function)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function approveRequest(Request $request)
    {
        $validated = $request->validate([
            'request_id' => 'required|exists:document_requests,id',
            'registrar_notes' => 'nullable|string'
        ]);

        try {
            $documentRequest = DocumentRequest::findOrFail($validated['request_id']);
            
            // Check if request is pending approval
            if ($documentRequest->status !== 'pending_registrar_approval') {
                return response()->json([
                    'success' => false, 
                    'message' => 'Request is not in pending approval status.'
                ], 400);
            }

            // Generate unique reference number
            $referenceNumber = 'REQ-' . strtoupper(uniqid());
            
            // Update request status
            $documentRequest->update([
                'status' => 'approved',
                'reference_number' => $referenceNumber,
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'registrar_notes' => $validated['registrar_notes'] ?? null
            ]);

            // Send approval email with reference number
            Mail::to($documentRequest->email)->send(new RequestApprovedMail($documentRequest, $referenceNumber));

            Log::info("Document request approved: {$documentRequest->id}, Reference: {$referenceNumber}");

            return response()->json([
                'success' => true, 
                'message' => 'Request approved successfully. Reference number generated and email sent.',
                'reference_number' => $referenceNumber
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to approve request: " . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Failed to approve request. Please try again.'
            ], 500);
        }
    }

    /**
     * Reject a document request (Registrar function)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function rejectRequest(Request $request)
    {
        $validated = $request->validate([
            'request_id' => 'required|exists:document_requests,id',
            'registrar_notes' => 'required|string|min:10'
        ]);

        try {
            $documentRequest = DocumentRequest::findOrFail($validated['request_id']);
            
            // Check if request is pending approval
            if ($documentRequest->status !== 'pending_registrar_approval') {
                return response()->json([
                    'success' => false, 
                    'message' => 'Request is not in pending approval status.'
                ], 400);
            }

            // Update request status
            $documentRequest->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
                'registrar_notes' => $validated['registrar_notes']
            ]);

            // Send rejection email
            Mail::to($documentRequest->email)->send(new RequestRejectedMail($documentRequest, $validated['registrar_notes']));

            Log::info("Document request rejected: {$documentRequest->id}");

            return response()->json([
                'success' => true, 
                'message' => 'Request rejected successfully. Email notification sent.'
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to reject request: " . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Failed to reject request. Please try again.'
            ], 500);
        }
    }

    /**
     * Get pending approval requests (Registrar function)
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPendingApprovalRequests()
    {
        try {
            $pendingRequests = DocumentRequest::with(['requestedDocuments'])
                ->where('status', 'pending_registrar_approval')
                ->orderBy('request_date', 'asc')
                ->get();

            // Get dashboard statistics
            $today = now()->startOfDay();
            $stats = [
                'pending' => DocumentRequest::where('status', 'pending_registrar_approval')->count(),
                'approved_today' => DocumentRequest::where('status', 'approved')
                    ->whereDate('approved_at', $today)
                    ->count(),
                'rejected_today' => DocumentRequest::where('status', 'rejected')
                    ->whereDate('rejected_at', $today)
                    ->count(),
            ];

            return response()->json([
                'success' => true,
                'requests' => $pendingRequests,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to get pending requests: " . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Failed to retrieve pending requests.'
            ], 500);
        }
    }

    /**
     * Get dashboard statistics (Registrar function)
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDashboardStats()
    {
        try {
            $today = now()->startOfDay();
            
            $stats = [
                'pending' => DocumentRequest::where('status', 'pending_registrar_approval')->count(),
                'approved_today' => DocumentRequest::where('status', 'approved')
                    ->whereDate('approved_at', $today)
                    ->count(),
                'rejected_today' => DocumentRequest::where('status', 'rejected')
                    ->whereDate('rejected_at', $today)
                    ->count(),
                'completed_today' => DocumentRequest::where('status', 'completed')
                    ->whereDate('updated_at', $today)
                    ->count(),
            ];
            
            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to get dashboard stats: " . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Failed to retrieve dashboard statistics.'
            ], 500);
        }
    }

    /**
     * Get all requests for dashboard (Registrar function)
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllRequests()
    {
        try {
            $requests = DocumentRequest::with(['requestedDocuments'])
                ->orderBy('created_at', 'desc')
                ->get();
                
            return response()->json([
                'success' => true,
                'requests' => $requests
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to get all requests: " . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Failed to retrieve requests.'
            ], 500);
        }
    }

    /**
     * Get progress data for charts (Registrar function)
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProgressData()
    {
        try {
            $progress = [
                'pending' => DocumentRequest::where('status', 'pending_registrar_approval')->count(),
                'approved' => DocumentRequest::where('status', 'approved')->count(),
                'rejected' => DocumentRequest::where('status', 'rejected')->count(),
                'completed' => DocumentRequest::where('status', 'completed')->count(),
            ];
            
            return response()->json([
                'success' => true,
                'progress' => $progress
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to get progress data: " . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Failed to retrieve progress data.'
            ], 500);
        }
    }
}