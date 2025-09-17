
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

use App\Http\Controllers\AlumniController;

Route::post('/students/store', [RegistrarController::class, 'storeStudent'])->name('students.store');

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
Route::get('/track', function () {
    return view('track');
})->name('track.form');
// Route::post('/track', [DocumentRequestController::class, 'track'])->name('track.submit'); // Uncomment if tracking logic exists

// Alumni resource routes
Route::resource('alumni', AlumniController::class);

// ✅ **Authentication Routes (Only for Guests)**
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
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
   
   // Fix Logout Route
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/'); // Redirect to landing page
})->name('logout');
    
Route::middleware(['auth'])->group(function () {
    Route::get('/registrar/verify-modal/{id}', [RegistrarController::class, 'verifyModal']);
    Route::post('/registrar/verify/{id}', [RegistrarController::class, 'verify'])->name('registrar.verify');
    Route::post('/registrar/profile/update', [RegistrarController::class, 'updateProfile'])->name('registrar.profile.update');
    Route::post('/registrar/approve/{id}', [RegistrarController::class, 'approve'])->name('registrar.approve');
    Route::post('/registrar/reject/{id}', [RegistrarController::class, 'reject'])->name('registrar.reject');
    Route::post('/registrar/backup', [RegistrarController::class, 'backup'])->name('registrar.backup');
    Route::post('/registrar/complete/{id}', [RegistrarController::class, 'complete'])->name('registrar.complete');

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/cashier/dashboard', [CashierController::class, 'dashboard'])->name('cashier.dashboard');

    Route::get('/registrar/pending-count', [RegistrarController::class, 'pendingCount']);

    Route::post('/registrar/department-logo', [App\Http\Controllers\RegistrarController::class, 'updateLogo'])->name('registrar.updateLogo');

    Route::post('/cashier/process-payment', [CashierController::class, 'processPayment'])->name('cashier.processPayment');

});