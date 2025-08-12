<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}