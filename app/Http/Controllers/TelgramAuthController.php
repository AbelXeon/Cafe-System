<?php

namespace App\Http\Controllers;

use App\Models\TelegramAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TelegramAuthController extends Controller
{
    /**
     * Link the verified Telegram identity (attached to the request by
     * VerifyTelegramInitData) to an existing Laravel user account.
     */
    public function link(Request $request)
    {
        // If already authenticated, nothing to link
        if (Auth::check()) {
            $currentUser = Auth::user();
            $role = is_object($currentUser->role) ? $currentUser->role->name : $currentUser->role;

            return response()->json([
                'status'  => 'already_linked',
                'message' => 'This Telegram account is already linked.',
                'role'    => $role,
            ]);
        }

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

        // Find user by username or email (supports whichever your app uses)
        $user = User::where('username', $credentials['username'])
            ->orWhere('email', $credentials['username'])
            ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid username/email or password.'
            ], 422);
        }

        // Guard against one Telegram account being linked twice
        $existingLink = TelegramAccount::where('telegram_user_id', $telegramProfile['id'])->first();
        if ($existingLink) {
            return response()->json([
                'message' => 'This Telegram account is already linked to a user.'
            ], 422);
        }

        // Guard against one Laravel user linking multiple Telegram accounts
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

        // Safe extraction whether role is a string ('customer') or relationship model
        $role = is_object($user->role) ? $user->role->name : $user->role;

        return response()->json([
            'status'  => 'linked',
            'message' => 'Telegram account linked successfully.',
            'role'    => $role,
        ]);
    }

    /**
     * Return the currently authenticated Telegram-linked user.
     * Used by the Mini App shell on load to decide: show dashboard, or show link screen.
     */
    public function me(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['linked' => false]);
        }

        $user = Auth::user();
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
}