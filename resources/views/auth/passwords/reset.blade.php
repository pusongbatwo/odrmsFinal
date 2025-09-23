@extends('layouts.app')

@section('content')
<div class="login-container">
    <!-- Left Column - Welcome / Illustration -->
    <div class="welcome-column">
        <div class="welcome-content">
            <h1>Reset your <span>Password</span></h1>
            <p class="tagline">Choose a strong password to secure your account</p>
            <div class="logo-animation">
                <img src="{{ asset('images/logo.png') }}" alt="iRequest Logo" class="logo-img">
            </div>
            <div class="features">
                <div class="feature-item"><i class="fas fa-key"></i><span>Minimum 8 characters</span></div>
                <div class="feature-item"><i class="fas fa-shield-alt"></i><span>Use letters, numbers, symbols</span></div>
                <div class="feature-item"><i class="fas fa-check-circle"></i><span>Do not reuse old passwords</span></div>
            </div>
        </div>
    </div>

    <!-- Right Column - Reset Form -->
    <div class="form-column">
        <form method="POST" action="{{ route('password.update') }}" class="login-form" autocomplete="off">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <h2 class="form-title" style="display:flex;align-items:center;gap:10px;">
                <i class="fas fa-unlock-alt"></i> Reset Password
            </h2>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-with-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email">
                </div>
            </div>

            <div class="form-group">
                <label for="password">New Password</label>
                <div class="input-with-icon" style="position:relative;">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="Enter new password">
                    <button type="button" id="togglePwd" aria-label="Show password" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:#777;cursor:pointer;">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div id="pwdStrength" style="margin-top:8px;height:8px;background:#eee;border-radius:999px;overflow:hidden;">
                    <div id="pwdBar" style="height:100%;width:0;background:#dc2626;transition:width .2s, background .2s;"></div>
                </div>
                <small id="pwdHint" style="color:#666;display:block;margin-top:6px;">Use at least 8 characters with a mix of letters, numbers, and symbols.</small>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <div class="input-with-icon" style="position:relative;">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="Re-enter new password">
                    <button type="button" id="togglePwd2" aria-label="Show password" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:#777;cursor:pointer;">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="login-btn" style="display:flex;align-items:center;gap:10px;justify-content:center;">
                <i class="fas fa-sync"></i> Reset Password
            </button>

            <div style="text-align:center;margin-top:12px;">
                <a href="{{ route('login') }}" style="color:#8B0000;font-weight:600;text-decoration:none;">
                    <i class="fas fa-arrow-left"></i> Back to login
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    (function(){
        const pwd = document.getElementById('password');
        const bar = document.getElementById('pwdBar');
        const toggle = (btnId, inputId) => {
            const btn = document.getElementById(btnId);
            const input = document.getElementById(inputId);
            if (!btn || !input) return;
            btn.addEventListener('click', function(){
                const isText = input.type === 'text';
                input.type = isText ? 'password' : 'text';
                this.innerHTML = isText ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
        };
        toggle('togglePwd', 'password');
        toggle('togglePwd2', 'password_confirmation');

        function scorePassword(p){
            let s = 0; if(!p) return s;
            const letters = {}; for (let i=0; i<p.length; i++) letters[p[i]] = (letters[p[i]]||0) + 1;
            for (let l in letters) s += 5.0 / letters[l];
            const variations = {
                digits: /\d/.test(p),
                lower: /[a-z]/.test(p),
                upper: /[A-Z]/.test(p),
                nonWords: /\W/.test(p)
            };
            let variationCount = 0; for (let k in variations) variationCount += (variations[k]===true) ? 1 : 0;
            s += (variationCount - 1) * 10;
            s += Math.max(0, p.length - 8) * 2;
            return Math.min(100, Math.floor(s));
        }
        function updateBar(){
            const val = pwd.value; const score = scorePassword(val);
            bar.style.width = score + '%';
            bar.style.background = score > 75 ? '#16a34a' : (score > 45 ? '#f59e0b' : '#dc2626');
        }
        if (pwd && bar) pwd.addEventListener('input', updateBar);
        // Clear autofill for safety
        document.addEventListener('DOMContentLoaded', function(){
            const e = document.getElementById('email');
            if (e) e.autocomplete = 'email';
        });
    })();
</script>
@endsection


