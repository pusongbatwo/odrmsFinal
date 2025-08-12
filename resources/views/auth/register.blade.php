@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="logo">
        <i class="fas fa-gem"></i>
        <h2>LUXE ACCESS</h2>
    </div>
    
    <form method="POST" action="{{ route('register') }}">
        @csrf
        
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        
        <div class="input-group">
            <label for="name">Full Name</label>
            <i class="fas fa-user"></i>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Enter your full name">
        </div>
        
        <div class="input-group">
            <label for="email">Email</label>
            <i class="fas fa-envelope"></i>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter your email">
        </div>
        
        <div class="input-group">
            <label for="password">Password</label>
            <i class="fas fa-lock"></i>
            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Enter your password">
        </div>
        
        <div class="input-group">
            <label for="password-confirm">Confirm Password</label>
            <i class="fas fa-lock"></i>
            <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password">
        </div>
        
        <button type="submit" class="auth-btn">Register</button>
        
        <div class="auth-link">
            Already have an account? <a href="{{ route('login') }}">Login</a>
        </div>
    </form>
</div>
@endsection