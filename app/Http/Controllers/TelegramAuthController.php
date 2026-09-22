<?php

namespace App\Http\Controllers;

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

        // Check if this Telegram ID actually exists in the database
        $link = TelegramAccount::where('telegram_user_id', $telegramId)->first();

        // Not in database -> Clear any stale session cookie and return unlinked
        // Not in database -> Clear any stale session cookie
        if (!$link) {
            Auth::logout();
            return response()->json(['linked' => false]);
        }

        // Linked in database -> Ensure the user is logged into Laravel session
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

    /**
     * Link the verified Telegram identity to an existing Laravel user account.
     */
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

        // Find user by username or email
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

        // Create database link
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
}