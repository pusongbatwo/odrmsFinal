@extends('layouts.app')

@section('content')
<div class="login-container">
    <!-- Left Column - Welcome Content -->
    <div class="welcome-column">
        <div class="welcome-content">
            <h1>Welcome to <span>iRequest</span></h1>
            <p class="tagline">Secure access to your request management portal</p>
            
            <!-- Animated Logo -->
            <div class="logo-animation">
                <img src="{{ asset('images/logo.png') }}" alt="iRequest Logo" class="logo-img">
            </div>
            
            <div class="features">
                <div class="feature-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>Advanced security</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-bolt"></i>
                    <span>Fast processing</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Real-time tracking</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right Column - Login Form -->
    <div class="form-column">
        <form method="POST" action="{{ route('login') }}" class="login-form">
            @csrf
            
            <h2 class="form-title">LOGIN</h2>
            
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-with-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" name="username" required placeholder="Enter your username">
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-with-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                </div>
            </div>
            
            <div class="form-options">
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>
              
            </div>
            
            <button type="submit" class="login-btn">Login</button>
            
            <div class="social-login">
                <p class="divider"></p>
                <a href="{{ route('password.request') }}" class="forgot-password">Forgot password?</a>
            </div>
        </form>
    </div>
</div>
@endsection