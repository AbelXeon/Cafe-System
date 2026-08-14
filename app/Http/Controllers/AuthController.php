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
            return $this->redirectByRole(Auth::user());
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
            throw ValidationException::withMessages([
                'username' => 'The provided credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();

        return $this->redirectByRole(Auth::user());
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'fullname'              => 'required|string|max:255',
            'phone'                 => 'required|string|max:20|unique:users,phone',
            'email'                 => 'required|email|unique:users,email',
            'username'              => 'required|string|max:255|unique:users,username',
            'password'              => 'required|string|min:6|confirmed',
        ]);

        $userRole = Role::where('name', 'user')->first();

        $user = User::create([
            'fullname' => $validated['fullname'],
            'phone'    => $validated['phone'],
            'email'    => $validated['email'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role_id'  => $userRole->id,
        ]);

        Auth::login($user);

        return redirect()->route('user.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectByRole(User $user)
    {
        return match ($user->role->name) {
            'admin'    => redirect()->route('admin.dashboard'),
            'staff'    => redirect()->route('staff.dashboard'),
            'delivery' => redirect()->route('delivery.dashboard'),
            default    => redirect()->route('user.dashboard'),
        };
    }
}
