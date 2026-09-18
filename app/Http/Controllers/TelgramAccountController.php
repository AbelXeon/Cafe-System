<?php

namespace App\Http\Controllers;

use App\Models\TelegramAccount;
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
        // If VerifyTelegramInitData already authenticated them (returning user),
        // there's nothing to link — they're already in.
        if (Auth::check()) {
            return response()->json([
                'status'  => 'already_linked',
                'message' => 'This Telegram account is already linked.',
            ]);
        }

        $telegramProfile = $request->attributes->get('telegram_profile');

        if (!$telegramProfile || !isset($telegramProfile['id'])) {
            return response()->json(['message' => 'No verified Telegram session found.'], 401);
        }

        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = \App\Models\User::where('username', $credentials['username'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Invalid username or password.'], 422);
        }

        // Guard against one Telegram account somehow getting linked twice
        $existingLink = TelegramAccount::where('telegram_user_id', $telegramProfile['id'])->first();
        if ($existingLink) {
            return response()->json(['message' => 'This Telegram account is already linked to a user.'], 422);
        }

        // Guard against one Laravel user linking multiple Telegram accounts
        if ($user->telegramAccount) {
            return response()->json(['message' => 'This account is already linked to a different Telegram user.'], 422);
        }

        $account = TelegramAccount::create([
            'user_id'              => $user->id,
            'telegram_user_id'     => $telegramProfile['id'],
            'telegram_username'    => $telegramProfile['username'] ?? null,
            'telegram_first_name'  => $telegramProfile['first_name'] ?? null,
            'linked_at'            => now(),
            'last_login_at'        => now(),
        ]);

        Auth::login($user);

        return response()->json([
            'status'  => 'linked',
            'message' => 'Telegram account linked successfully.',
            'role'    => $user->role->name,
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

        return response()->json([
            'linked' => true,
            'user'   => [
                'id'       => $user->id,
                'fullname' => $user->fullname,
                'role'     => $user->role->name,
            ],
        ]);
    }
}