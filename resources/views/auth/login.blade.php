<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | FoodieDash Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        amber: { 500: '#f59e0b', 600: '#d97706', 700: '#b45309' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-neutral-900 via-stone-900 to-neutral-950 text-white min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-stone-900/80 backdrop-blur-xl border border-stone-800 rounded-3xl p-8 shadow-2xl">
        <!-- Logo / Title -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-amber-500/20 text-amber-500 rounded-2xl mb-4 border border-amber-500/30">
                <i class="fa-solid fa-mug-hot text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold tracking-wide">FoodieDash Cafe</h1>
            <p class="text-stone-400 text-sm mt-1">Sign in to your portal</p>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3 bg-rose-500/20 border border-rose-500/30 text-rose-300 rounded-xl text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.perform') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase text-stone-400 mb-2">Username or Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-stone-500">
                        <i class="fa-solid fa-user"></i>
                    </span>
                    <input type="text" name="login" value="{{ old('login') }}" required
                           class="w-full bg-stone-950/70 border border-stone-800 rounded-xl py-3 pl-10 pr-4 text-white text-sm focus:outline-none focus:border-amber-500 transition-colors"
                           placeholder="Enter username or email">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase text-stone-400 mb-2">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-stone-500">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" name="password" required
                           class="w-full bg-stone-950/70 border border-stone-800 rounded-xl py-3 pl-10 pr-4 text-white text-sm focus:outline-none focus:border-amber-500 transition-colors"
                           placeholder="••••••••">
                </div>
            </div>

            <button type="submit"
                    class="w-full py-3.5 px-4 bg-amber-500 hover:bg-amber-600 active:scale-[0.99] text-stone-950 font-bold rounded-xl shadow-lg shadow-amber-500/20 transition-all flex items-center justify-center gap-2">
                <span>Sign In</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </form>

        <p class="text-xs text-stone-500 text-center mt-8">
            &copy; {{ date('Y') }} FoodieDash Cafe Delivery & Order System.
        </p>
    </div>
</body>
</html>