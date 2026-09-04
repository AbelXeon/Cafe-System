<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Role;

class AuthController extends Controller
{
     public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['username' => 'Invalid username or password.'])->onlyInput('username');
        }

        $request->session()->regenerate();

        return $this->redirectByRole();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectByRole()
    {
        $role = Auth::user()->role->name;

        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'staff'    => redirect()->route('staff.dashboard'),
            'customer' => redirect()->route('user.dashboard'),
            default => redirect()->route('login')->withErrors(['username' => 'No dashboard built for this role yet.']),
        };
    }


    public function showRegister()
{
    if (Auth::check()) {
        return $this->redirectByRole();
    }

    return view('auth.register');
}

public function register(Request $request)
{
    $data = $request->validate([
        'fullname' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username',
        'email'    => 'nullable|email|unique:users,email',
        'phone'    => 'nullable|string|max:20',
        'password' => 'required|string|min:6|confirmed',
    ]);

    $customerRole = Role::where('name', 'customer')->first();

    if (!$customerRole) {
        return back()->withErrors(['username' => 'Registration is not available right now.'])->withInput();
    }

    User::create([
        'role_id'  => $customerRole->id,
        'fullname' => $data['fullname'],
        'username' => $data['username'],
        'email'    => $data['email'] ?? null,
        'phone'    => $data['phone'] ?? null,
        'password' => Hash::make($data['password']),
    ]);

    return redirect()->route('login')->with('status', 'Account created. Log in to continue.');
}

}
