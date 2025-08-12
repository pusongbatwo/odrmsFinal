@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="logo">
        <i class="fas fa-gem"></i>
        <h2>RESET PASSWORD</h2>
    </div>
    
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        
        <div class="input-group">
            <label for="email">Email</label>
            <i class="fas fa-envelope"></i>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your email">
        </div>
        
        <button type="submit" class="auth-btn">Send Password Reset Link</button>
        
        <div class="auth-link">
            Remember your password? <a href="{{ route('login') }}">Login</a>
        </div>
    </form>
</div>
@endsection