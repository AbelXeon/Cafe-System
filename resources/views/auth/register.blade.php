<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 min-h-screen flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-sm bg-slate-900 border border-slate-800 rounded-xl p-8">
        <h1 class="text-2xl font-bold text-white mb-1">Create account</h1>
        <p class="text-slate-400 text-sm mb-6">Sign up to start ordering</p>

        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-sm rounded-lg px-4 py-3 mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.submit') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm text-slate-300 mb-1">Full name</label>
                <input type="text" name="fullname" value="{{ old('fullname') }}" required autofocus
                    class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm text-slate-300 mb-1">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required
                    class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm text-slate-300 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm text-slate-300 mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm text-slate-300 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm text-slate-300 mb-1">Confirm password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
            </div>
            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-lg py-2 transition">
                Create account
            </button>
        </form>

        <p class="text-slate-400 text-sm mt-5 text-center">
            Already have an account?
            <a href="{{ route('login') }}" class="text-indigo-400 hover:underline">Log in</a>
        </p>
    </div>
</body>
</html>