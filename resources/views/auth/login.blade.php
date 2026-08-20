<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login · Cafe Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f7f8fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 40px;
            width: 380px;
        }
        .login-card h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 22px;
            color: #4338ca;
            margin-bottom: 4px;
        }
        .login-card p { color: #565c6b; font-size: 14px; margin-bottom: 28px; }
        .field { margin-bottom: 16px; }
        .field label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
        .field input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
        }
        .field-error { color: #dc2626; font-size: 12px; margin-top: 6px; }
        .btn-primary {
            width: 100%;
            padding: 11px;
            background: #4338ca;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
        }
        .btn-primary:hover { background: #3730a3; }
    </style>
</head>
<body>
    <div class="login-card">
        <h1>Welcome back</h1>
        <p>Sign in to manage the cafe.</p>

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="field">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" autofocus required>
                @error('username')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-primary">Log in</button>
        </form>
    </div>
</body>
</html>