<?php

namespace App\Http\Middleware;

use App\Models\TelegramAccount;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyTelegramInitData
{
    // How old an initData payload can be before we reject it as stale/replayed
    protected int $maxAgeSeconds = 86400; // 24h

    public function handle(Request $request, Closure $next)
    {
        $initData = $request->header('X-Telegram-Init-Data') ?? $request->input('initData');

        if (!$initData) {
            return response()->json(['message' => 'Missing Telegram init data.'], 401);
        }

        $parsed = $this->verify($initData);

        if (!$parsed) {
            return response()->json(['message' => 'Invalid or expired Telegram session.'], 401);
        }

        $telegramUserId = $parsed['user']['id'] ?? null;

        if (!$telegramUserId) {
            return response()->json(['message' => 'Telegram user data missing.'], 401);
        }

        $account = TelegramAccount::where('telegram_user_id', $telegramUserId)->first();

        if (!$account) {
            // Not linked yet — attach the raw Telegram profile to the request
            // so the linking controller can use it, but do NOT authenticate.
            $request->attributes->set('telegram_profile', $parsed['user']);
            return $next($request);
        }

        $account->update(['last_login_at' => now()]);

        Auth::login($account->user);

        return $next($request);
    }

    protected function verify(string $initData): ?array
    {
        parse_str($initData, $data);

        if (!isset($data['hash'])) {
            return null;
        }

        $receivedHash = $data['hash'];
        unset($data['hash']);

        // Reject stale payloads
        if (isset($data['auth_date']) && (time() - (int) $data['auth_date']) > $this->maxAgeSeconds) {
            return null;
        }

        ksort($data);
        $pairs = [];
        foreach ($data as $key => $value) {
            $pairs[] = "{$key}={$value}";
        }
        $dataCheckString = implode("\n", $pairs);

        $botToken = config('services.telegram.bot_token');

        $secretKey = hash_hmac('sha256', $botToken, 'WebAppData', true);
        $computedHash = hash_hmac('sha256', $dataCheckString, $secretKey);

        if (!hash_equals($computedHash, $receivedHash)) {
            return null;
        }

        if (isset($data['user'])) {
            $data['user'] = json_decode($data['user'], true);
        }

        return $data;
    }
}