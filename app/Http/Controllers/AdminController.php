<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DocumentRequest;
use App\Models\RequestedDocument;
use App\Models\SystemLog;
use App\Models\CashierLog;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard with real data
     */
    public function dashboard()
    {
        // Get dashboard statistics
        $stats = $this->getDashboardStats();
        
        // Get recent users
        $recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();
        
        // Get recent document requests
        $recentRequests = DocumentRequest::with('requestedDocuments')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get recent system logs
        $recentLogs = SystemLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get cashier logs
        $recentCashierLogs = CashierLog::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.dashboard', compact(
            'stats', 
            'recentUsers', 
            'recentRequests', 
            'recentLogs', 
            'recentCashierLogs'
        ));
    }
    
    /**
     * Get dashboard statistics
     */
    private function getDashboardStats()
    {
        return [
            'total_users' => User::count(),
            'total_document_requests' => DocumentRequest::count(),
            'pending_requests' => DocumentRequest::where('status', 'pending')->count(),
            'completed_requests' => DocumentRequest::where('status', 'completed')->count(),
            'paid_requests' => DocumentRequest::where('payment_status', 'paid')->count(),
            'unpaid_requests' => DocumentRequest::where('payment_status', 'unpaid')->count(),
            'registrar_users' => User::where('role', 'registrar')->count(),
            'cashier_users' => User::where('role', 'cashier')->count(),
            'admin_users' => User::where('role', 'admin')->count(),
        ];
    }
    
    /**
     * Get all users for user management
     */
    public function getUsers(Request $request)
    {
        $query = User::query();
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }
        
        // Role filter
        if ($request->has('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }
        
        $users = $query->orderBy('created_at', 'desc')->get();
        return response()->json($users);
    }
    
    /**
     * Get a single user
     */
    public function getUser($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
    }
    
    /**
     * Create a new user
     */
    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users',
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,registrar,cashier',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        try {
            $user = User::create([
                'full_name' => $request->full_name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);
            
            // Log the user creation
            SystemLog::create([
                'type' => 'info',
                'message' => "Admin created new user: {$user->full_name} ({$user->role})",
                'user_id' => Auth::id(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update an existing user
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $id,
            'email' => 'required|email|max:100|unique:users,email,' . $id,
            'role' => 'required|in:admin,registrar,cashier',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        try {
            $user->update([
                'full_name' => $request->full_name,
                'username' => $request->username,
                'email' => $request->email,
                'role' => $request->role,
            ]);
            
            // Log the user update
            SystemLog::create([
                'type' => 'info',
                'message' => "Admin updated user: {$user->full_name} ({$user->role})",
                'user_id' => Auth::id(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete a user
     */
    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $userName = $user->full_name;
            $userRole = $user->role;
            
            // Prevent admin from deleting themselves
            if ($user->id === Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot delete your own account'
                ], 400);
            }
            
            $user->delete();
            
            // Log the user deletion
            SystemLog::create([
                'type' => 'warning',
                'message' => "Admin deleted user: {$userName} ({$userRole})",
                'user_id' => Auth::id(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Reset user password
     */
    public function resetPassword(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:8',
            ]);
            
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            
            $user->update([
                'password' => Hash::make($request->password),
            ]);
            
            // Log the password reset
            SystemLog::create([
                'type' => 'warning',
                'message' => "Admin reset password for user: {$user->full_name}",
                'user_id' => Auth::id(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Export users data
     */
    public function exportUsers()
    {
        $users = User::all();
        
        $csvData = "ID,Full Name,Username,Email,Role,Created At\n";
        foreach ($users as $user) {
            $csvData .= "{$user->id},{$user->full_name},{$user->username},{$user->email},{$user->role},{$user->created_at}\n";
        }
        
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="users_export_' . date('Y-m-d') . '.csv"');
    }
    
    /**
     * Export system logs
     */
    public function exportLogs(Request $request)
    {
        $query = SystemLog::with('user');
        
        // Apply same filters as getSystemLogs
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }
        
        if ($request->has('search') && $request->search) {
            $query->where('message', 'like', '%' . $request->search . '%');
        }
        
        $logs = $query->orderBy('created_at', 'desc')->get();
        
        $csvData = "Timestamp,Type,User,Message\n";
        foreach ($logs as $log) {
            $userName = $log->user ? $log->user->full_name : 'System';
            $csvData .= "{$log->created_at},{$log->type},{$userName},\"{$log->message}\"\n";
        }
        
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="system_logs_export_' . date('Y-m-d') . '.csv"');
    }
    
    /**
     * Get document types with statistics
     */
    public function getDocumentTypes(Request $request)
    {
        $query = DocumentType::query();
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('type', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $documentTypes = $query->orderBy('type')->get();
        
        // Get statistics for each document type
        $documentStats = [];
        foreach ($documentTypes as $docType) {
            $count = RequestedDocument::where('document_type', $docType->type)->count();
            
            $documentStats[] = [
                'id' => $docType->id,
                'type' => $docType->type,
                'description' => $docType->description,
                'price' => $docType->price,
                'processing_time' => $docType->processing_time,
                'total_requests' => $count,
                'is_active' => $docType->is_active
            ];
        }
        
        return response()->json($documentStats);
    }
    
    /**
     * Get document description
     */
    private function getDocumentDescription($type)
    {
        $descriptions = [
            'TRANSCRIPT OF RECORDS' => 'Official academic transcript showing all courses and grades',
            'TRANSCRIPT OF RECORDS FOR EVALUATION' => 'Transcript specifically for evaluation purposes',
            'FORM 137A' => 'Secondary school record for incoming students',
            'FORM 138' => 'Secondary school record for outgoing students',
            'HONORABLE DISMISSAL' => 'Certificate for students transferring to another school',
            'DIPLOMA' => 'Official graduation certificate',
            'CERTIFICATE OF NO OBJECTION' => 'Certificate stating no objection to student activities',
            'CERTIFICATE OF ENGLISH AS MEDIUM' => 'Certificate confirming English as medium of instruction',
            'CERTIFICATE OF GOOD MORAL' => 'Certificate of good moral character',
            'CERTIFICATE OF REGISTRATION' => 'Current enrollment status certificate',
            'CERTIFICATE OF COMPLETION' => 'Certificate of course completion',
            'CERTIFICATE OF GRADES' => 'Official grade certificate',
            'STATEMENT OF ACCOUNT' => 'Financial statement of student account',
            'SERVICE RECORD' => 'Employment service record',
            'EMPLOYMENT' => 'Employment certificate',
            'PERFORMANCE RATING' => 'Employee performance evaluation',
            'GWA CERTIFICATE' => 'General Weighted Average certificate',
            'CAV ENDORSEMENT' => 'Certification, Authentication, and Verification endorsement',
        ];
        
        return $descriptions[$type] ?? 'Document certificate';
    }
    
    /**
     * Get system logs with filtering
     */
    public function getSystemLogs(Request $request)
    {
        $query = SystemLog::with('user');
        
        // Filter by type
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        
        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }
        
        // Search in message
        if ($request->has('search') && $request->search) {
            $query->where('message', 'like', '%' . $request->search . '%');
        }
        
        $logs = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return response()->json($logs);
    }
    
    /**
     * Get cashier logs
     */
    public function getCashierLogs(Request $request)
    {
        $query = CashierLog::query();
        
        // Filter by type
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        
        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }
        
        // Search in message
        if ($request->has('search') && $request->search) {
            $query->where('message', 'like', '%' . $request->search . '%');
        }
        
        $logs = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return response()->json($logs);
    }
    
    /**
     * Get reports data
     */
    public function getReportsData()
    {
        // Cashier reports (matching cashier dashboard)
        $today = now()->today();
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        
        $cashierStats = [
            // Cashier dashboard statistics
            'total_approved' => DocumentRequest::where('status', 'approved')->count(),
            'pending_payments' => DocumentRequest::where('status', 'approved')->where('payment_status', 'unpaid')->count(),
            'paid_today' => DocumentRequest::where('payment_status', 'paid')
                ->whereDate('paid_at', $today)
                ->sum('amount_paid'),
            'total_collected_month' => DocumentRequest::where('payment_status', 'paid')
                ->whereBetween('paid_at', [$monthStart, $monthEnd])
                ->sum('amount_paid'),
            
            // Additional statistics for compatibility
            'total_payments' => DocumentRequest::where('payment_status', 'paid')->count(),
            'total_amount' => DocumentRequest::where('payment_status', 'paid')
                ->with('requestedDocuments')
                ->get()
                ->sum(function($request) {
                    return $request->requestedDocuments->sum(function($doc) {
                        return $this->getDocumentPrice($doc->document_type) * $doc->quantity;
                    });
                }),
            'monthly_payments' => DocumentRequest::where('payment_status', 'paid')
                ->whereMonth('paid_at', now()->month)
                ->count(),
        ];
        
        // Registrar reports
        $registrarStats = [
            'new_requests' => DocumentRequest::where('status', 'pending')->count(),
            'completed_requests' => DocumentRequest::where('status', 'completed')->count(),
            'approved_requests' => DocumentRequest::where('status', 'approved')->count(),
            'rejected_requests' => DocumentRequest::where('status', 'rejected')->count(),
        ];
        
        // Document type breakdown
        $documentBreakdown = RequestedDocument::select('document_type', DB::raw('count(*) as count'))
            ->groupBy('document_type')
            ->orderBy('count', 'desc')
            ->get();
        
        return response()->json([
            'cashier' => $cashierStats,
            'registrar' => $registrarStats,
            'document_breakdown' => $documentBreakdown
        ]);
    }
    
    /**
     * Get document price
     */
    private function getDocumentPrice($type)
    {
        $prices = [
            'TRANSCRIPT OF RECORDS' => 250,
            'TRANSCRIPT OF RECORDS FOR EVALUATION' => 300,
            'FORM 137A' => 200,
            'FORM 138' => 200,
            'HONORABLE DISMISSAL' => 150,
            'DIPLOMA' => 350,
            'CERTIFICATE OF NO OBJECTION' => 100,
            'CERTIFICATE OF ENGLISH AS MEDIUM' => 100,
            'CERTIFICATE OF GOOD MORAL' => 100,
            'CERTIFICATE OF REGISTRATION' => 100,
            'CERTIFICATE OF COMPLETION' => 150,
            'CERTIFICATE OF GRADES' => 150,
            'STATEMENT OF ACCOUNT' => 100,
            'SERVICE RECORD' => 200,
            'EMPLOYMENT' => 200,
            'PERFORMANCE RATING' => 150,
            'GWA CERTIFICATE' => 100,
            'CAV ENDORSEMENT' => 200,
        ];
        
        return $prices[$type] ?? 100;
    }
    
    /**
     * Get a single document type
     */
    public function getDocumentType($id)
    {
        try {
            $documentType = DocumentType::findOrFail($id);
            return response()->json([
                'success' => true,
                'document_type' => $documentType
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Document type not found'
            ], 404);
        }
    }
    
    /**
     * Create a new document type
     */
    public function createDocumentType(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required|string|max:255|unique:document_types,type',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'processing_time' => 'required|string|max:255',
                'is_active' => 'boolean'
            ]);
            
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            
            $documentType = DocumentType::create($request->all());
            
            // Log the creation
            SystemLog::create([
                'type' => 'info',
                'message' => "Admin created new document type: {$documentType->type}",
                'user_id' => Auth::id(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Document type created successfully',
                'document_type' => $documentType
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create document type: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update a document type
     */
    public function updateDocumentType(Request $request, $id)
    {
        try {
            $documentType = DocumentType::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'type' => 'required|string|max:255|unique:document_types,type,' . $id,
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'processing_time' => 'required|string|max:255',
                'is_active' => 'boolean'
            ]);
            
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            
            $documentType->update($request->all());
            
            // Log the update
            SystemLog::create([
                'type' => 'info',
                'message' => "Admin updated document type: {$documentType->type}",
                'user_id' => Auth::id(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Document type updated successfully',
                'document_type' => $documentType
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update document type: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete a document type
     */
    public function deleteDocumentType($id)
    {
        try {
            $documentType = DocumentType::findOrFail($id);
            $typeName = $documentType->type;
            
            $documentType->delete();
            
            // Log the deletion
            SystemLog::create([
                'type' => 'warning',
                'message' => "Admin deleted document type: {$typeName}",
                'user_id' => Auth::id(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Document type deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete document type: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update admin profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        // Debug: Log the incoming request data
        \Log::info('Admin profile update request', [
            'user_id' => $user->id,
            'has_new_password' => $request->has('new_password'),
            'has_current_password' => $request->has('current_password'),
            'has_confirm_password' => $request->has('confirm_password'),
            'new_password_filled' => $request->filled('new_password'),
            'current_password_filled' => $request->filled('current_password'),
            'confirm_password_filled' => $request->filled('confirm_password'),
        ]);

        // Build validation rules dynamically
        $rules = [
            'full_name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'email' => 'required|email|max:100|unique:users,email,' . $user->id,
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:8',
            'confirm_password' => 'nullable|string|min:8',
            'profile_picture' => 'nullable|image|max:2048',
        ];

        // If new password is provided, make confirm_password required and same as new_password
        if ($request->filled('new_password')) {
            $rules['confirm_password'] = 'required|string|min:8|same:new_password';
            $rules['current_password'] = 'required|string';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = [];
            
            foreach ($errors->all() as $error) {
                $errorMessages[] = $error;
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $errorMessages),
                'errors' => $errors
            ], 422);
        }

        try {
            // Update basic profile information
            $user->full_name = $request->input('full_name');
            $user->username = $request->input('username');
            $user->email = $request->input('email');

            // Handle password change
            if ($request->filled('new_password')) {
                \Log::info('Password change requested', [
                    'user_id' => $user->id,
                    'has_current_password' => $request->filled('current_password'),
                ]);
                
                if (!$request->filled('current_password') || !Hash::check($request->input('current_password'), $user->password)) {
                    \Log::warning('Password change failed: current password incorrect', [
                        'user_id' => $user->id,
                        'has_current_password' => $request->filled('current_password'),
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Current password is incorrect.'
                    ], 422);
                }
                
                $newPasswordHash = Hash::make($request->input('new_password'));
                $user->password = $newPasswordHash;
                
                \Log::info('Password updated successfully', [
                    'user_id' => $user->id,
                    'new_password_length' => strlen($request->input('new_password')),
                    'hash_length' => strlen($newPasswordHash),
                    'hash_starts_with' => substr($newPasswordHash, 0, 10),
                ]);
            } else {
                \Log::info('No password change requested', ['user_id' => $user->id]);
            }

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');
                $path = $file->store('avatars', 'public');
                
                // Delete old avatar if exists
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
                
                $user->avatar = $path;
            }

            $user->save();
            
            \Log::info('User profile saved', [
                'user_id' => $user->id,
                'password_updated' => $request->filled('new_password'),
            ]);

            // Log the profile update
            SystemLog::create([
                'type' => 'info',
                'message' => "Admin updated their profile" . ($request->filled('new_password') ? ' (including password)' : ''),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully.',
                'user' => [
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                    'profile_picture' => $user->avatar ? asset('storage/' . $user->avatar) : null, // Keep both for compatibility
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Test password hashing (temporary method for debugging)
     */
    public function testPasswordHashing(Request $request)
    {
        $testPassword = $request->input('password', 'test123');
        $hash = Hash::make($testPassword);
        $check = Hash::check($testPassword, $hash);
        
        return response()->json([
            'original_password' => $testPassword,
            'hash' => $hash,
            'hash_check' => $check,
            'hash_length' => strlen($hash),
        ]);
    }
    
    /**
     * Get detailed cashier report
     */
    public function getCashierDetailedReport()
    {
        try {
            \Log::info('Cashier detailed report requested');
            
            // Test database connection
            \Log::info('Testing database connection...');
            $testCount = DocumentRequest::count();
            \Log::info('Total document requests: ' . $testCount);
            
            // Get cashier dashboard statistics (matching cashier dashboard)
            $today = now()->today();
            $monthStart = now()->startOfMonth();
            $monthEnd = now()->endOfMonth();
            
            // Total approved requests
            $totalApproved = DocumentRequest::where('status', 'approved')->count();
            
            // Pending payments (approved but unpaid)
            $pendingPayments = DocumentRequest::where('status', 'approved')->where('payment_status', 'unpaid')->count();
            
            // Paid today (sum of amount_paid)
            $paidToday = DocumentRequest::where('payment_status', 'paid')
                ->whereDate('paid_at', $today)
                ->sum('amount_paid');
            
            // Total collected in current month (sum of amount_paid)
            $totalCollectedMonth = DocumentRequest::where('payment_status', 'paid')
                ->whereBetween('paid_at', [$monthStart, $monthEnd])
                ->sum('amount_paid');
            
            // Calculate total amount from document prices (for compatibility)
            $totalPayments = DocumentRequest::where('payment_status', 'paid')->count();
            $totalAmount = DocumentRequest::where('payment_status', 'paid')
                ->with('requestedDocuments')
                ->get()
                ->sum(function($request) {
                    return $request->requestedDocuments->sum(function($doc) {
                        return $this->getDocumentPrice($doc->document_type) * $doc->quantity;
                    });
                });
            
            $averagePayment = $totalPayments > 0 ? $totalAmount / $totalPayments : 0;
            $monthlyPayments = DocumentRequest::where('payment_status', 'paid')
                ->whereMonth('request_date', now()->month)
                ->whereYear('request_date', now()->year)
                ->count();
            
            // Get recent payments
            $recentPayments = DocumentRequest::where('payment_status', 'paid')
                ->with('requestedDocuments')
                ->orderBy('request_date', 'desc')
                ->limit(10)
                ->get()
                ->map(function($request) {
                    $amount = $request->requestedDocuments->sum(function($doc) {
                        return $this->getDocumentPrice($doc->document_type) * $doc->quantity;
                    });
                    
                    // Handle date formatting safely
                    $date = 'N/A';
                    if ($request->request_date) {
                        try {
                            $date = $request->request_date->format('Y-m-d H:i:s');
                        } catch (\Exception $e) {
                            $date = $request->request_date;
                        }
                    }
                    
                    return [
                        'date' => $date,
                        'student_name' => $request->first_name . ' ' . $request->last_name,
                        'amount' => $amount,
                        'document_count' => $request->requestedDocuments->count(),
                        'status' => 'Paid'
                    ];
                });
            
            // Get daily payment data for last 7 days
            $dailyPayments = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $dailyAmount = DocumentRequest::where('payment_status', 'paid')
                    ->whereDate('request_date', $date->toDateString())
                    ->with('requestedDocuments')
                    ->get()
                    ->sum(function($request) {
                        return $request->requestedDocuments->sum(function($doc) {
                            return $this->getDocumentPrice($doc->document_type) * $doc->quantity;
                        });
                    });
                
                $dailyPayments[] = [
                    'date' => $date->toDateString(),
                    'amount' => $dailyAmount
                ];
            }

            // Get monthly payment counts for current year
            $monthlyData = [];
            for ($i = 1; $i <= 12; $i++) {
                $count = DocumentRequest::where('payment_status', 'paid')
                    ->whereMonth('request_date', $i)
                    ->whereYear('request_date', now()->year)
                    ->count();
                $monthlyData[] = $count;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    // Cashier dashboard statistics
                    'total_approved' => $totalApproved,
                    'pending_payments' => $pendingPayments,
                    'paid_today' => $paidToday,
                    'total_collected_month' => $totalCollectedMonth,
                    
                    // Additional statistics for compatibility
                    'total_payments' => $totalPayments,
                    'total_amount' => $totalAmount,
                    'average_payment' => $averagePayment,
                    'monthly_payments' => $monthlyPayments,
                    'recent_payments' => $recentPayments,
                    'daily_payments' => $dailyPayments,
                    'monthly_data' => $monthlyData
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Cashier report error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to load cashier report: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get detailed registrar report
     */
    public function getRegistrarDetailedReport()
    {
        try {
            $totalRequests = DocumentRequest::count();
            $completedRequests = DocumentRequest::where('status', 'completed')->count();
            $pendingRequests = DocumentRequest::where('status', 'pending')->count();
            $approvedRequests = DocumentRequest::where('status', 'approved')->count();
            $rejectedRequests = DocumentRequest::where('status', 'rejected')->count();
            
            // Calculate average processing time
            $completedWithDates = DocumentRequest::where('status', 'completed')
                ->whereNotNull('approved_at')
                ->get();
            
            $totalProcessingDays = $completedWithDates->sum(function($request) {
                try {
                    return $request->request_date->diffInDays($request->approved_at);
                } catch (\Exception $e) {
                    return 0;
                }
            });
            
            $averageProcessingDays = $completedWithDates->count() > 0 
                ? round($totalProcessingDays / $completedWithDates->count(), 1) 
                : 0;
            
            // Get recent requests
            $recentRequests = DocumentRequest::with('requestedDocuments')
                ->orderBy('request_date', 'desc')
                ->limit(10)
                ->get()
                ->map(function($request) {
                    $processingDays = null;
                    if ($request->status === 'completed' && $request->approved_at) {
                        try {
                            $processingDays = $request->request_date->diffInDays($request->approved_at);
                        } catch (\Exception $e) {
                            $processingDays = null;
                        }
                    }
                    
                    // Handle date formatting safely
                    $date = 'N/A';
                    if ($request->request_date) {
                        try {
                            $date = $request->request_date->format('Y-m-d H:i:s');
                        } catch (\Exception $e) {
                            $date = $request->request_date;
                        }
                    }
                    
                    return [
                        'date' => $date,
                        'student_name' => $request->first_name . ' ' . $request->last_name,
                        'document_count' => $request->requestedDocuments->count(),
                        'status' => ucfirst($request->status),
                        'processing_days' => $processingDays
                    ];
                });
            
            // Get processing time distribution
            $processingTimeDistribution = [0, 0, 0, 0, 0]; // 0-1, 2-3, 4-7, 8-14, 15+ days
            $completedWithDates->each(function($request) use (&$processingTimeDistribution) {
                try {
                    $processingDays = $request->request_date->diffInDays($request->approved_at);
                    if ($processingDays <= 1) {
                        $processingTimeDistribution[0]++;
                    } elseif ($processingDays <= 3) {
                        $processingTimeDistribution[1]++;
                    } elseif ($processingDays <= 7) {
                        $processingTimeDistribution[2]++;
                    } elseif ($processingDays <= 14) {
                        $processingTimeDistribution[3]++;
                    } else {
                        $processingTimeDistribution[4]++;
                    }
                } catch (\Exception $e) {
                    // Skip this request if date calculation fails
                }
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'total_requests' => $totalRequests,
                    'completed_requests' => $completedRequests,
                    'pending_requests' => $pendingRequests,
                    'approved_requests' => $approvedRequests,
                    'rejected_requests' => $rejectedRequests,
                    'average_processing_days' => $averageProcessingDays,
                    'recent_requests' => $recentRequests,
                    'processing_time_distribution' => $processingTimeDistribution
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load registrar report: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get document breakdown report
     */
    public function getDocumentBreakdownReport()
    {
        try {
            $documentTypes = DocumentType::all();
            $totalDocumentTypes = $documentTypes->count();
            
            // Get document request statistics
            $documentStats = [];
            $totalRequests = 0;
            $totalRevenue = 0;
            
            foreach ($documentTypes as $docType) {
                $count = RequestedDocument::where('document_type', $docType->type)->count();
                $revenue = $count * $docType->price;
                
                $documentStats[] = [
                    'type' => $docType->type,
                    'count' => $count,
                    'price' => $docType->price,
                    'revenue' => $revenue
                ];
                
                $totalRequests += $count;
                $totalRevenue += $revenue;
            }
            
            // Sort by count to get most requested
            $sortedStats = collect($documentStats)->sortByDesc('count');
            $mostRequested = $sortedStats->first();
            
            // Calculate percentages
            $documentBreakdown = $sortedStats->map(function($doc) use ($totalRequests) {
                $percentage = $totalRequests > 0 ? ($doc['count'] / $totalRequests) * 100 : 0;
                return array_merge($doc, ['percentage' => $percentage]);
            })->values();
            
            // Get top documents
            $topDocuments = $sortedStats->take(5)->values();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'total_document_types' => $totalDocumentTypes,
                    'total_requests' => $totalRequests,
                    'total_revenue' => $totalRevenue,
                    'most_requested' => $mostRequested ?: ['type' => 'N/A', 'count' => 0],
                    'document_breakdown' => $documentBreakdown,
                    'top_documents' => $topDocuments
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load document breakdown: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Export cashier detailed report
     */
    public function exportCashierDetailedReport()
    {
        try {
            // Get the same data as the detailed report
            $totalPayments = DocumentRequest::where('payment_status', 'paid')->count();
            $totalAmount = DocumentRequest::where('payment_status', 'paid')
                ->with('requestedDocuments')
                ->get()
                ->sum(function($request) {
                    return $request->requestedDocuments->sum(function($doc) {
                        return $this->getDocumentPrice($doc->document_type) * $doc->quantity;
                    });
                });
            
            $averagePayment = $totalPayments > 0 ? $totalAmount / $totalPayments : 0;
            $monthlyPayments = DocumentRequest::where('payment_status', 'paid')
                ->whereMonth('request_date', now()->month)
                ->whereYear('request_date', now()->year)
                ->count();
            
            // Get all payments for export (not just recent 10)
            $allPayments = DocumentRequest::where('payment_status', 'paid')
                ->with('requestedDocuments')
                ->orderBy('request_date', 'desc')
                ->get()
                ->map(function($request) {
                    $amount = $request->requestedDocuments->sum(function($doc) {
                        return $this->getDocumentPrice($doc->document_type) * $doc->quantity;
                    });
                    
                    // Handle date formatting safely
                    $date = 'N/A';
                    if ($request->request_date) {
                        try {
                            $date = $request->request_date->format('Y-m-d H:i:s');
                        } catch (\Exception $e) {
                            $date = $request->request_date;
                        }
                    }
                    
                    return [
                        'date' => $date,
                        'student_name' => $request->first_name . ' ' . $request->last_name,
                        'amount' => $amount,
                        'document_count' => $request->requestedDocuments->count(),
                        'status' => 'Paid'
                    ];
                });
            
            // Generate CSV content
            $csvData = "Cashier Detailed Report\n";
            $csvData .= "Generated on: " . now()->format('Y-m-d H:i:s') . "\n\n";
            $csvData .= "SUMMARY\n";
            $csvData .= "Total Revenue,₱" . number_format($totalAmount, 2) . "\n";
            $csvData .= "Total Payments," . $totalPayments . "\n";
            $csvData .= "Average Payment,₱" . number_format($averagePayment, 2) . "\n";
            $csvData .= "This Month," . $monthlyPayments . "\n\n";
            $csvData .= "PAYMENT DETAILS\n";
            $csvData .= "Date,Student Name,Amount,Document Count,Status\n";
            
            foreach ($allPayments as $payment) {
                $csvData .= $payment['date'] . "," . 
                           '"' . $payment['student_name'] . '",' . 
                           "₱" . number_format($payment['amount'], 2) . "," . 
                           $payment['document_count'] . "," . 
                           $payment['status'] . "\n";
            }
            
            return response($csvData)
                ->header('Content-Type', 'text/csv; charset=UTF-8')
                ->header('Content-Disposition', 'attachment; filename="cashier_detailed_report_' . date('Y-m-d') . '.csv"')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export cashier report: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Export registrar detailed report
     */
    public function exportRegistrarDetailedReport()
    {
        try {
            $totalRequests = DocumentRequest::count();
            $completedRequests = DocumentRequest::where('status', 'completed')->count();
            $pendingRequests = DocumentRequest::where('status', 'pending')->count();
            $approvedRequests = DocumentRequest::where('status', 'approved')->count();
            $rejectedRequests = DocumentRequest::where('status', 'rejected')->count();
            
            // Calculate average processing time
            $completedWithDates = DocumentRequest::where('status', 'completed')
                ->whereNotNull('approved_at')
                ->get();
            
            $totalProcessingDays = $completedWithDates->sum(function($request) {
                try {
                    return $request->request_date->diffInDays($request->approved_at);
                } catch (\Exception $e) {
                    return 0;
                }
            });
            
            $averageProcessingDays = $completedWithDates->count() > 0 
                ? round($totalProcessingDays / $completedWithDates->count(), 1) 
                : 0;
            
            // Get all requests for export
            $allRequests = DocumentRequest::with('requestedDocuments')
                ->orderBy('request_date', 'desc')
                ->get()
                ->map(function($request) {
                    $processingDays = null;
                    if ($request->status === 'completed' && $request->approved_at) {
                        try {
                            $processingDays = $request->request_date->diffInDays($request->approved_at);
                        } catch (\Exception $e) {
                            $processingDays = null;
                        }
                    }
                    
                    // Handle date formatting safely
                    $date = 'N/A';
                    if ($request->request_date) {
                        try {
                            $date = $request->request_date->format('Y-m-d H:i:s');
                        } catch (\Exception $e) {
                            $date = $request->request_date;
                        }
                    }
                    
                    return [
                        'date' => $date,
                        'student_name' => $request->first_name . ' ' . $request->last_name,
                        'document_count' => $request->requestedDocuments->count(),
                        'status' => ucfirst($request->status),
                        'processing_days' => $processingDays ?: 'N/A'
                    ];
                });
            
            // Generate CSV content
            $csvData = "Registrar Detailed Report\n";
            $csvData .= "Generated on: " . now()->format('Y-m-d H:i:s') . "\n\n";
            $csvData .= "SUMMARY\n";
            $csvData .= "Total Requests," . $totalRequests . "\n";
            $csvData .= "Completed Requests," . $completedRequests . "\n";
            $csvData .= "Pending Requests," . $pendingRequests . "\n";
            $csvData .= "Approved Requests," . $approvedRequests . "\n";
            $csvData .= "Rejected Requests," . $rejectedRequests . "\n";
            $csvData .= "Average Processing Days," . $averageProcessingDays . "\n\n";
            $csvData .= "REQUEST DETAILS\n";
            $csvData .= "Date,Student Name,Document Count,Status,Processing Days\n";
            
            foreach ($allRequests as $request) {
                $csvData .= $request['date'] . "," . 
                           '"' . $request['student_name'] . '",' . 
                           $request['document_count'] . "," . 
                           $request['status'] . "," . 
                           $request['processing_days'] . "\n";
            }
            
            return response($csvData)
                ->header('Content-Type', 'text/csv; charset=UTF-8')
                ->header('Content-Disposition', 'attachment; filename="registrar_detailed_report_' . date('Y-m-d') . '.csv"')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export registrar report: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Export document breakdown report
     */
    public function exportDocumentBreakdownReport()
    {
        try {
            $documentTypes = DocumentType::all();
            $totalDocumentTypes = $documentTypes->count();
            
            // Get document request statistics
            $documentStats = [];
            $totalRequests = 0;
            $totalRevenue = 0;
            
            foreach ($documentTypes as $docType) {
                $count = RequestedDocument::where('document_type', $docType->type)->count();
                $revenue = $count * $docType->price;
                
                $documentStats[] = [
                    'type' => $docType->type,
                    'count' => $count,
                    'price' => $docType->price,
                    'revenue' => $revenue
                ];
                
                $totalRequests += $count;
                $totalRevenue += $revenue;
            }
            
            // Sort by count to get most requested
            $sortedStats = collect($documentStats)->sortByDesc('count');
            $mostRequested = $sortedStats->first();
            
            // Calculate percentages
            $documentBreakdown = $sortedStats->map(function($doc) use ($totalRequests) {
                $percentage = $totalRequests > 0 ? ($doc['count'] / $totalRequests) * 100 : 0;
                return array_merge($doc, ['percentage' => $percentage]);
            })->values();
            
            // Generate CSV content
            $csvData = "Document Breakdown Report\n";
            $csvData .= "Generated on: " . now()->format('Y-m-d H:i:s') . "\n\n";
            $csvData .= "SUMMARY\n";
            $csvData .= "Total Document Types," . $totalDocumentTypes . "\n";
            $csvData .= "Total Requests," . $totalRequests . "\n";
            $csvData .= "Most Requested," . ($mostRequested ? $mostRequested['type'] : 'N/A') . "\n";
            $csvData .= "Total Revenue,₱" . number_format($totalRevenue, 2) . "\n\n";
            $csvData .= "DOCUMENT BREAKDOWN\n";
            $csvData .= "Document Type,Requests,Price,Revenue,Percentage\n";
            
            foreach ($documentBreakdown as $doc) {
                $csvData .= '"' . $doc['type'] . '",' . 
                           $doc['count'] . "," . 
                           "₱" . number_format($doc['price'], 2) . "," . 
                           "₱" . number_format($doc['revenue'], 2) . "," . 
                           number_format($doc['percentage'], 1) . "%\n";
            }
            
            return response($csvData)
                ->header('Content-Type', 'text/csv; charset=UTF-8')
                ->header('Content-Disposition', 'attachment; filename="document_breakdown_report_' . date('Y-m-d') . '.csv"')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export document breakdown: ' . $e->getMessage()
            ], 500);
        }
    }
}
