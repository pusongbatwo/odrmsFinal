<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Check if user exists first to provide a clearer error
        $user = User::where('username', $request->username)->first();

        if (! $user) {
            return back()->withErrors(['username' => 'No account found with that username.'])->withInput($request->only('username'));
        }

        // Debug password check
        \Log::info('Login attempt', [
            'username' => $request->username,
            'user_id' => $user->id,
            'stored_password_length' => strlen($user->password),
            'stored_password_starts_with' => substr($user->password, 0, 10),
            'provided_password_length' => strlen($request->password),
        ]);
        
        if (! Hash::check($request->password, $user->password)) {
            \Log::warning('Password check failed', [
                'username' => $request->username,
                'user_id' => $user->id,
            ]);
            return back()->withErrors(['password' => 'The provided password is incorrect.'])->withInput($request->only('username'));
        }
        
        \Log::info('Password check successful', [
            'username' => $request->username,
            'user_id' => $user->id,
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            
            // Redirect based on role
            $user = Auth::user();
            // Log registrar login
            if ($user->role === 'registrar') {
                \App\Helpers\SystemLogHelper::log('login', 'Registrar Admin logged in.');
                return redirect()->route('registrar.dashboard');
            } elseif ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'cashier') {
                \App\Models\CashierLog::create([
                    'type' => 'login',
                    'message' => 'Cashier logged in.'
                ]);
                return redirect()->route('cashier.dashboard');
            }
            return redirect('/');
        }

        return back()->withErrors(['username' => 'Unable to authenticate with provided credentials.'])->withInput($request->only('username'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}