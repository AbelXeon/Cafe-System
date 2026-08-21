<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-sm bg-slate-900 border border-slate-800 rounded-xl p-8">
        <h1 class="text-2xl font-bold text-white mb-1">Sign in</h1>
        <p class="text-slate-400 text-sm mb-6">Cafe management system</p>

        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-sm rounded-lg px-4 py-3 mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm text-slate-300 mb-1">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required autofocus
                    class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm text-slate-300 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-400">
                <input type="checkbox" name="remember" class="rounded bg-slate-800 border-slate-700">
                Remember me
            </label>
            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-lg py-2 transition">
                Sign in
            </button>
        </form>
    </div>
</body>
</html>