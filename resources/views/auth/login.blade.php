<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign in | CraveDash Delivery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen selection:bg-amber-500 selection:text-white">

    <div class="w-full min-h-screen grid grid-cols-1 lg:grid-cols-12">

        <div class="lg:col-span-5 xl:col-span-4 p-8 sm:p-12 xl:p-16 flex flex-col justify-between bg-slate-900 border-r border-slate-800">
            <div>
                <div class="flex items-center gap-3 mb-10">
                    <div class="w-12 h-12 rounded-xl bg-amber-500 flex items-center justify-center text-white font-black text-2xl">
                        🍔
                    </div>
                    <div>
                        <span class="text-2xl font-bold tracking-tight text-white block">Crave<span class="text-amber-500">Dash</span></span>
                        <span class="text-xs text-slate-400">Food Delivery & Cafe Portal</span>
                    </div>
                </div>

                <div class="mb-8">
                    <h1 class="text-3xl font-extrabold text-white tracking-tight">Welcome Back!</h1>
                    <p class="text-slate-400 text-sm mt-1">Ready to manage fresh orders & tasty meals?</p>
                </div>

                @if (session('status'))
                    <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm rounded-xl px-4 py-3 mb-5 flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4 flex-shrink-0"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 text-sm rounded-xl px-4 py-3 mb-5 flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Username</label>
                        <div class="relative">
                            <i data-lucide="user" class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input type="text" name="username" value="{{ old('username') }}" required autofocus placeholder="Enter your username"
                                class="w-full bg-slate-950 border border-slate-700 rounded-xl pl-11 pr-4 py-3.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-amber-500 transition duration-200">
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300">Password</label>
                        </div>
                        <div class="relative">
                            <i data-lucide="lock" class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input type="password" name="password" required placeholder="••••••••"
                                class="w-full bg-slate-950 border border-slate-700 rounded-xl pl-11 pr-4 py-3.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-amber-500 transition duration-200">
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 text-sm text-slate-400 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-950 border-slate-700 text-amber-500 focus:ring-0 accent-amber-500">
                            Remember me
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full mt-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl py-3.5 transition duration-200 flex items-center justify-center gap-2 group">
                        <span>Sign In to Dashboard</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 transition-transform group-hover:translate-x-1"></i>
                    </button>
                </form>
            </div>

            <p class="text-slate-400 text-sm mt-12 text-center lg:text-left border-t border-slate-800 pt-6">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-amber-400 font-semibold hover:text-amber-300 hover:underline transition">
                    Register here
                </a>
            </p>
        </div>

        <div class="lg:col-span-7 xl:col-span-8 bg-slate-950 p-8 lg:p-12 relative flex items-center justify-center overflow-hidden min-h-[400px] lg:min-h-full">

            <div class="relative w-full max-w-lg h-80 sm:h-96 flex items-center justify-center">

                <div class="relative z-20">
                    <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=600&q=80" 
                         alt="Gourmet Burger" 
                         class="w-60 sm:w-72 h-60 sm:h-72 object-cover rounded-full border-4 border-amber-500/20 shadow-2xl">
                </div>

                <div class="absolute top-0 left-4 sm:left-8 z-10">
                    <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=400&q=80" 
                         alt="Delicious Pizza" 
                         class="w-32 sm:w-36 h-32 sm:h-36 object-cover rounded-full border-2 border-slate-800 shadow-xl">
                </div>

                <div class="absolute bottom-0 right-4 sm:right-8 z-20">
                    <img src="https://images.unsplash.com/photo-1551024709-8f23befc6f87?auto=format&fit=crop&w=400&q=80" 
                         alt="Refreshing Drink" 
                         class="w-32 sm:w-36 h-32 sm:h-36 object-cover rounded-full border-2 border-slate-800 shadow-xl">
                </div>

            </div>

        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>