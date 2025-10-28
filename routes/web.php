<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DocumentRequestController;
use App\Http\Controllers\DocumentTrackingController;
use App\Http\Controllers\LandingpageController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RegistrarController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\GoogleDriveBackupController;

use App\Http\Controllers\AlumniController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
// ✅ Cashier System Logs
use App\Http\Controllers\CashierLogController;
Route::middleware('auth')->group(function () {
    Route::get('/cashier/system-logs', [CashierLogController::class, 'index'])->name('cashier.system_logs');
});

use Illuminate\Support\Facades\Storage;


Route::post('/students/backup-google-drive', [GoogleDriveBackupController::class, 'backupStudentRecords']);

Route::get('/backup', [GoogleDriveBackupController::class, 'backupStudentRecords'])->name('backupStudentRecords');
Route::get('/oauth/callback', [GoogleDriveBackupController::class, 'oauthCallback'])->name('oauthCallback');

// AJAX endpoint for main reports table
    Route::get('/registrar/report/main-reports', [RegistrarController::class, 'reportMainReports']);
// Export report endpoint
    Route::get('/registrar/report/export', [RegistrarController::class, 'exportReport']);
// ✅ **Landing Page** (Loads when the project starts)
Route::get('/', function () {
    return view('landingpage');
})->name('landingpage');

// ✅ **Document Request Routes**
Route::get('/request', [DocumentRequestController::class, 'index'])->name('request.form');
Route::post('/submit-request', [DocumentRequestController::class, 'store'])->name('submit-request');
Route::post('/submit-alumni-request', [DocumentRequestController::class, 'storeAlumni'])->name('submit-alumni-request');
Route::post('/check-available-documents', [DocumentRequestController::class, 'checkAvailableDocumentTypes'])->name('check-available-documents');
Route::get('/verify-document-request/{token}', [DocumentRequestController::class, 'verifyRequest'])->name('verify.document-request');

// Registrar Approval Routes
Route::get('/registrar/dashboard', [RegistrarController::class, 'registrarDashboard'])->name('registrar.dashboard');
Route::post('/registrar/approve-request', [DocumentRequestController::class, 'approveRequest'])->name('registrar.approve-request');
Route::post('/registrar/reject-request', [DocumentRequestController::class, 'rejectRequest'])->name('registrar.reject-request');
Route::get('/registrar/pending-requests', [DocumentRequestController::class, 'getPendingApprovalRequests'])->name('registrar.pending-requests');
Route::get('/registrar/dashboard-stats', [DocumentRequestController::class, 'getDashboardStats'])->name('registrar.dashboard-stats');
Route::get('/registrar/all-requests', [DocumentRequestController::class, 'getAllRequests'])->name('registrar.all-requests');
Route::get('/registrar/progress-data', [DocumentRequestController::class, 'getProgressData'])->name('registrar.progress-data');
//Route::post('/request', [DocumentRequestController::class, 'store'])->name('request.submit');
Route::get('/request/success/{reference}', [DocumentRequestController::class, 'success'])->name('request.success');

// ✅ **Document Tracking Routes**
//Route::get('/track', function () {
  //  return view('track');
//})->name('track.form');
// Route::post('/track', [DocumentRequestController::class, 'track'])->name('track.submit'); // Uncomment if tracking logic exists

// Alumni resource routes
Route::resource('alumni', AlumniController::class);

// ✅ **Authentication Routes (Only for Guests)**
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    // Password Reset
    Route::get('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
    
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
});

// ✅ **Authenticated Routes (Only for Logged-in Users)**
Route::middleware('auth')->group(function () {
    //Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


Route::post('/track', [DocumentTrackingController::class, 'trackDocument'])->name('track.document');
Route::post('/process-payment', [DocumentTrackingController::class, 'processPayment'])->name('process.payment');
Route::get('/requester/dashboard/{reference_number}', [DocumentTrackingController::class, 'showRequesterDashboard'])
    ->name('requester.dashboard');
Route::get('/requester/status/{reference_number}', [DocumentTrackingController::class, 'status'])->name('requester.status');
// Chat endpoints (requester <-> registrar)
Route::post('/chat/send', [\App\Http\Controllers\ChatController::class, 'send'])->name('chat.send');
Route::get('/chat/fetch', [\App\Http\Controllers\ChatController::class, 'fetch'])->name('chat.fetch');
   
   // Fix Logout Route
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login'); // Redirect to login page
})->name('logout');
    
Route::middleware(['auth'])->group(function () {
    Route::get('/registrar/verify-modal/{id}', [RegistrarController::class, 'verifyModal']);
    Route::post('/registrar/verify/{id}', [RegistrarController::class, 'verify'])->name('registrar.verify');
    Route::post('/registrar/profile/update', [RegistrarController::class, 'updateProfile'])->name('registrar.profile.update');
    Route::post('/registrar/approve/{id}', [RegistrarController::class, 'approve'])->name('registrar.approve');
    Route::post('/registrar/reject/{id}', [RegistrarController::class, 'reject'])->name('registrar.reject');
    Route::post('/registrar/backup', [RegistrarController::class, 'backup'])->name('registrar.backup');
    Route::post('/registrar/complete/{id}', [RegistrarController::class, 'complete'])->name('registrar.complete');

    // Student routes
    Route::post('/students/store', [RegistrarController::class, 'storeStudent'])->name('students.store');
    Route::post('/students/{id}/update', [RegistrarController::class, 'updateStudent'])->name('students.update');

    // Alumni routes
    Route::post('/alumni/store', [RegistrarController::class, 'storeAlumni'])->name('alumni.store');
    Route::post('/alumni/{id}/update', [RegistrarController::class, 'updateAlumni'])->name('alumni.update');

    // Admin Routes - Only accessible by admin users
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Admin User Management
        Route::get('/admin/users', [AdminController::class, 'getUsers'])->name('admin.users');
        Route::get('/admin/users/{id}', [AdminController::class, 'getUser'])->name('admin.users.show');
        Route::post('/admin/users', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
        Route::post('/admin/users/{id}/reset-password', [AdminController::class, 'resetPassword'])->name('admin.users.reset-password');
        Route::get('/admin/users/export', [AdminController::class, 'exportUsers'])->name('admin.users.export');
        
            // Admin Document Management
            Route::get('/admin/documents', [AdminController::class, 'getDocumentTypes'])->name('admin.documents');
            Route::get('/admin/documents/{id}', [AdminController::class, 'getDocumentType'])->name('admin.documents.show');
            Route::post('/admin/documents', [AdminController::class, 'createDocumentType'])->name('admin.documents.create');
            Route::put('/admin/documents/{id}', [AdminController::class, 'updateDocumentType'])->name('admin.documents.update');
            Route::delete('/admin/documents/{id}', [AdminController::class, 'deleteDocumentType'])->name('admin.documents.delete');
        
        // Admin System Logs
        Route::get('/admin/system-logs', [AdminController::class, 'getSystemLogs'])->name('admin.system-logs');
        Route::get('/admin/cashier-logs', [AdminController::class, 'getCashierLogs'])->name('admin.cashier-logs');
        Route::get('/admin/system-logs/export', [AdminController::class, 'exportLogs'])->name('admin.system-logs.export');
        
        // Admin Reports
        Route::get('/admin/reports', [AdminController::class, 'getReportsData'])->name('admin.reports');
        Route::get('/admin/reports/cashier-detailed', [AdminController::class, 'getCashierDetailedReport'])->name('admin.reports.cashier-detailed');
        Route::get('/admin/reports/registrar-detailed', [AdminController::class, 'getRegistrarDetailedReport'])->name('admin.reports.registrar-detailed');
        Route::get('/admin/reports/document-breakdown', [AdminController::class, 'getDocumentBreakdownReport'])->name('admin.reports.document-breakdown');
        
        // Admin Report Exports
        Route::get('/admin/reports/cashier-detailed/export', [AdminController::class, 'exportCashierDetailedReport'])->name('admin.reports.cashier-detailed.export');
        Route::get('/admin/reports/registrar-detailed/export', [AdminController::class, 'exportRegistrarDetailedReport'])->name('admin.reports.registrar-detailed.export');
        Route::get('/admin/reports/document-breakdown/export', [AdminController::class, 'exportDocumentBreakdownReport'])->name('admin.reports.document-breakdown.export');
        
        // Admin Profile
        Route::post('/admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
        
        // Admin Test (temporary for debugging)
        Route::get('/admin/test-password', [AdminController::class, 'testPasswordHashing'])->name('admin.test-password');
    });

    Route::get('/cashier/dashboard', [CashierController::class, 'dashboard'])->name('cashier.dashboard');
    Route::get('/cashier/reports', [CashierController::class, 'reportsData'])->name('cashier.reports');
    Route::post('/cashier/export', [CashierController::class, 'exportRecords'])->name('cashier.export');
    Route::get('/cashier/export', [CashierController::class, 'exportRecords'])->name('cashier.export.get'); // Temporary GET route
    Route::get('/cashier/test-export', [CashierController::class, 'testExport'])->name('cashier.test-export');
    Route::post('/cashier/profile', [CashierController::class, 'updateProfile'])->name('cashier.profile.update');


    // AJAX endpoints for report-tabs
    Route::get('/registrar/report/document-requests', [RegistrarController::class, 'reportDocumentRequests']);
    Route::get('/registrar/report/student-records', [RegistrarController::class, 'reportStudentRecords']);
    Route::get('/registrar/report/user-activity', [RegistrarController::class, 'reportUserActivity']);

    // Registrar live chat endpoints (session/web auth ensures Auth::user() is available)
    Route::get('/registrar/conversations', [\App\Http\Controllers\ChatController::class, 'conversationsForRegistrar']);
    Route::get('/registrar/conversations/{id}/messages', [\App\Http\Controllers\ChatController::class, 'index']);

    Route::get('/registrar/pending-count', [RegistrarController::class, 'pendingCount']);

    Route::post('/registrar/department-logo', [App\Http\Controllers\RegistrarController::class, 'updateLogo'])->name('registrar.updateLogo');

    Route::post('/cashier/process-payment', [CashierController::class, 'processPayment'])->name('cashier.processPayment');

});