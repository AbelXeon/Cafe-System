{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Cafe System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['Space Grotesk', 'sans-serif'],
                        body: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        indigo: { 50:'#eef2ff',100:'#e0e7ff',500:'#6366f1',600:'#4f46e5',700:'#4338ca',900:'#312e81' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-body text-slate-800 min-h-screen flex items-center justify-center px-4 py-10">

    <div class="w-full max-w-sm">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-600 mb-4">
                <span class="text-2xl">☕</span>
            </div>
            <h1 class="font-display text-2xl font-bold text-slate-900">Create your account</h1>
            <p class="text-sm text-slate-500 mt-1">Order your favorite food & drinks in minutes</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-7">

            @if ($errors->any())
                <div class="mb-5 px-4 py-3 rounded-lg bg-rose-50 text-rose-700 text-sm border border-rose-200">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Full name</label>
                    <input type="text" name="fullname" value="{{ old('fullname') }}" required autofocus
                        class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="e.g. Abel Tesfaye">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Phone number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required
                        class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="e.g. 0912345678">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="you@example.com">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required
                        class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Choose a username">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="At least 6 characters">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Confirm password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Re-enter your password">
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2.5 rounded-lg transition">
                    Create account
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-slate-500 mt-6">
            Already have an account?
            <a href="{{ route('login') }}" class="text-indigo-600 font-medium hover:text-indigo-700">Sign in</a>
        </p>

    </div>

</body>
</html>