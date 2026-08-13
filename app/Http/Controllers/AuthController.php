<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Role;

class AuthController extends Controller
{
   public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'phone'    => 'required|string',
            'email'    => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $userRole = Role::where('name', 'user')->first();

        User::create([
            'role_id'  => $userRole->id,
            'fullname' => $request->fullname,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        // Redirect to login with success message
        return redirect()->route('login')->with('success', 'Account created! Please login.');
    }

    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Create a unique key for the user (username + IP)
        $throttleKey = Str::lower($request->input('username')) . '|' . $request->ip();

        // CHECK IF LOCKED OUT (5 attempts, 86400 seconds = 24 hours)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $hours = ceil($seconds / 3600);
            return back()->withErrors([
                'username' => "Too many attempts. You are banned for 24 hours. Try again in $hours hours."
            ]);
        }

        if (Auth::attempt($credentials)) {
            RateLimiter::clear($throttleKey); // Reset on success
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        // FAILURE: Increment the counter and lockout for 24 hours (86400 seconds)
        RateLimiter::hit($throttleKey, 86400);

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('username'));
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
