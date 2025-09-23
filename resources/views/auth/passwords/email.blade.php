@extends('layouts.app')

@section('content')
<div class="login-container">
    <!-- Left Column - Info / Illustration -->
    <div class="welcome-column">
        <div class="welcome-content">
            <h1>Forgot your <span>Password?</span></h1>
            <p class="tagline">We'll send a secure link to reset it</p>
            <div class="logo-animation">
                <img src="{{ asset('images/logo.png') }}" alt="iRequest Logo" class="logo-img">
            </div>
            <div class="features">
                <div class="feature-item"><i class="fas fa-envelope"></i><span>Check your inbox/spam</span></div>
                <div class="feature-item"><i class="fas fa-shield-alt"></i><span>Link expires for your safety</span></div>
                <div class="feature-item"><i class="fas fa-user-lock"></i><span>Use your registered email</span></div>
            </div>
        </div>
    </div>

    <!-- Right Column - Email Form -->
    <div class="form-column">
        <form method="POST" action="{{ route('password.email') }}" class="login-form" autocomplete="off">
            @csrf
            <h2 class="form-title" style="display:flex;align-items:center;gap:10px;"><i class="fas fa-paper-plane"></i> Reset Link</h2>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-with-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" required placeholder="Enter your email" autocomplete="email">
                </div>
            </div>

            <button type="submit" class="login-btn" style="display:flex;align-items:center;gap:10px;justify-content:center;">
                <i class="fas fa-link"></i> Email Password Reset Link
            </button>

            <div style="text-align:center;margin-top:12px;">
                <a href="{{ route('login') }}" style="color:#8B0000;font-weight:600;text-decoration:none;">
                    <i class="fas fa-arrow-left"></i> Back to login
                </a>
            </div>
        </form>
    </div>
</div>
@endsection


