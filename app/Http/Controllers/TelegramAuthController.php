<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\TelegramAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TelegramAuthController extends Controller
{
    public function me(Request $request)
    {
        $telegramProfile = $request->attributes->get('telegram_profile');
        $telegramId = $telegramProfile['id'] ?? null;

        if (!$telegramId) {
            Auth::logout();
            return response()->json(['linked' => false]);
        }

        $link = TelegramAccount::where('telegram_user_id', $telegramId)->first();

        if (!$link) {
            Auth::logout();
            return response()->json(['linked' => false]);
        }

        if (!Auth::check() || Auth::id() !== $link->user_id) {
            Auth::login($link->user);
        }

        $user = $link->user;
        $role = is_object($user->role) ? $user->role->name : $user->role;

        return response()->json([
            'linked' => true,
            'user'   => [
                'id'       => $user->id,
                'fullname' => $user->fullname ?? $user->name ?? 'User',
                'role'     => $role,
            ],
        ]);
    }

    public function link(Request $request)
    {
        $telegramProfile = $request->attributes->get('telegram_profile');

        if (!$telegramProfile || !isset($telegramProfile['id'])) {
            return response()->json([
                'message' => 'No verified Telegram session found.'
            ], 401);
        }

        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('username', $credentials['username'])
            ->orWhere('email', $credentials['username'])
            ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid username/email or password.'
            ], 422);
        }

        $existingLink = TelegramAccount::where('telegram_user_id', $telegramProfile['id'])->first();
        if ($existingLink) {
            return response()->json([
                'message' => 'This Telegram account is already linked to a user.'
            ], 422);
        }

        if (method_exists($user, 'telegramAccount') && $user->telegramAccount) {
            return response()->json([
                'message' => 'This account is already linked to a different Telegram user.'
            ], 422);
        }

        TelegramAccount::create([
            'user_id'              => $user->id,
            'telegram_user_id'     => $telegramProfile['id'],
            'telegram_username'    => $telegramProfile['username'] ?? null,
            'telegram_first_name'  => $telegramProfile['first_name'] ?? null,
            'linked_at'            => now(),
            'last_login_at'        => now(),
        ]);

        Auth::login($user);

        $role = is_object($user->role) ? $user->role->name : $user->role;

        return response()->json([
            'status'  => 'linked',
            'message' => 'Telegram account linked successfully.',
            'role'    => $role,
        ]);
    }

    public function register(Request $request)
    {
        $telegramProfile = $request->attributes->get('telegram_profile');

        if (!$telegramProfile || !isset($telegramProfile['id'])) {
            return response()->json([
                'message' => 'No verified Telegram session found.'
            ], 401);
        }

        $existingLink = TelegramAccount::where('telegram_user_id', $telegramProfile['id'])->first();
        if ($existingLink) {
            return response()->json([
                'message' => 'This Telegram account is already linked to another user.'
            ], 422);
        }

        $data = $request->validate([
            'fullname'              => ['required', 'string', 'max:255'],
            'username'              => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'                 => ['nullable', 'email', 'unique:users,email'],
            'phone'                 => ['nullable', 'string', 'max:20'],
            'password'              => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $customerRole = Role::where('name', 'customer')->first();

        if (!$customerRole) {
            return response()->json([
                'message' => 'Customer role not found in system.'
            ], 500);
        }

        $user = User::create([
            'role_id'  => $customerRole->id,
            'fullname' => $data['fullname'],
            'username' => $data['username'],
            'email'    => $data['email'] ?? null,
            'phone'    => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
        ]);

        TelegramAccount::create([
            'user_id'              => $user->id,
            'telegram_user_id'     => $telegramProfile['id'],
            'telegram_username'    => $telegramProfile['username'] ?? null,
            'telegram_first_name'  => $telegramProfile['first_name'] ?? null,
            'linked_at'            => now(),
            'last_login_at'        => now(),
        ]);

        Auth::login($user);

        $role = is_object($user->role) ? $user->role->name : $user->role;

        return response()->json([
            'status'  => 'registered',
            'message' => 'Account created and linked successfully.',
            'role'    => $role,
        ]);
    }
}