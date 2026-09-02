<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign in | CraveDash Delivery</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Font & Lucide Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen selection:bg-amber-500 selection:text-slate-950">

    <!-- Full-Screen Split Layout -->
    <div class="w-full min-h-screen grid grid-cols-1 lg:grid-cols-12 overflow-hidden">

        <!-- ================= LEFT SIDE: FORM ================= -->
        <div class="lg:col-span-5 xl:col-span-4 p-8 sm:p-12 xl:p-16 flex flex-col justify-between bg-slate-900 border-r border-slate-800/80 z-20 min-h-screen">
            <div>
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3.5 mb-12">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-500 flex items-center justify-center text-slate-950 font-black text-2xl shadow-lg ring-2 ring-amber-400/20">
                        🍔
                    </div>
                    <div>
                        <span class="text-2xl font-black tracking-tight text-white block">Crave<span class="text-amber-500">Dash</span></span>
                        <span class="text-xs font-semibold text-slate-400 tracking-wider uppercase">Partner Portal</span>
                    </div>
                </div>

                <!-- Header Title -->
                <div class="mb-8">
                    <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">Welcome back</h1>
                    <p class="text-slate-400 text-sm mt-2 font-medium">Please sign in to access your merchant dashboard.</p>
                </div>

                <!-- Session Alert Messages -->
                @if (session('status'))
                    <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm font-medium rounded-xl px-4 py-3.5 mb-6 flex items-center gap-3">
                        <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 text-sm font-medium rounded-xl px-4 py-3.5 mb-6 flex items-center gap-3">
                        <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login.submit') }}" class="space-y-6">
                    @csrf

                    <!-- Username Input -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-300 mb-2">Username</label>
                        <div class="relative group">
                            <i data-lucide="user" class="w-5 h-5 text-slate-500 group-focus-within:text-amber-500 absolute left-4 top-1/2 -translate-y-1/2 transition duration-200 pointer-events-none"></i>
                            <input type="text" name="username" value="{{ old('username') }}" required autofocus placeholder="Enter your username"
                                class="w-full bg-slate-950 border-2 border-slate-800 rounded-xl pl-12 pr-4 py-3.5 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-amber-500 transition duration-200 font-medium">
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-xs font-bold uppercase tracking-widest text-slate-300">Password</label>
                        </div>
                        <div class="relative group">
                            <i data-lucide="lock" class="w-5 h-5 text-slate-500 group-focus-within:text-amber-500 absolute left-4 top-1/2 -translate-y-1/2 transition duration-200 pointer-events-none"></i>
                            <input type="password" name="password" required placeholder="••••••••"
                                class="w-full bg-slate-950 border-2 border-slate-800 rounded-xl pl-12 pr-4 py-3.5 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-amber-500 transition duration-200 font-medium">
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-3 text-sm text-slate-300 font-medium cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-950 border-2 border-slate-700 text-amber-500 focus:ring-0 accent-amber-500">
                            Remember me
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full mt-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-slate-950 font-black rounded-xl py-4 transition duration-200 flex items-center justify-center gap-2 group tracking-wide uppercase text-sm shadow-xl shadow-orange-950/40 cursor-pointer">
                        <span>Sign In to Dashboard</span>
                        <i data-lucide="arrow-right" class="w-5 h-5 transition-transform group-hover:translate-x-1.5"></i>
                    </button>
                </form>
            </div>

            <!-- Footer / Register Link -->
            <p class="text-slate-400 text-sm mt-12 text-center lg:text-left border-t border-slate-800/80 pt-6 font-medium">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-amber-400 font-bold hover:text-amber-300 hover:underline transition ml-1">
                    Register here
                </a>
            </p>
        </div>

        <!-- ================= RIGHT SIDE: VISUAL SHOWCASE ================= -->
        <div class="lg:col-span-7 xl:col-span-8 bg-slate-950 p-8 lg:p-16 relative flex items-center justify-center min-h-[500px] lg:min-h-screen">
            
            <!-- Structural Accent Layer -->
            <div class="absolute inset-0 bg-gradient-to-tr from-orange-950/20 via-slate-950 to-amber-950/10 pointer-events-none"></div>

            <!-- Static Asymmetric Food Showcase -->
            <div class="relative w-full max-w-2xl grid grid-cols-12 gap-6 items-center">

                <!-- Main Large Burger Container -->
                <div class="col-span-7 sm:col-span-8 relative z-20">
                    <div class="p-3 bg-slate-900 border-2 border-slate-800 rounded-3xl shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80" 
                             alt="Gourmet Burger" 
                             class="w-full h-80 sm:h-96 object-cover rounded-2xl">
                    </div>
                </div>

                <!-- Secondary Side Stack (Pizza & Drink) -->
                <div class="col-span-5 sm:col-span-4 space-y-6 relative z-10 -ml-6 sm:-ml-10">
                    
                    <!-- Pizza Image Card -->
                    <div class="p-2.5 bg-slate-900 border-2 border-slate-800 rounded-2xl shadow-xl">
                        <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=400&q=80" 
                             alt="Delicious Pizza" 
                             class="w-full h-36 sm:h-40 object-cover rounded-xl">
                    </div>

                    <!-- Drink Image Card -->
                    <div class="p-2.5 bg-slate-900 border-2 border-slate-800 rounded-2xl shadow-xl">
                        <img src="https://images.unsplash.com/photo-1551024709-8f23befc6f87?auto=format&fit=crop&w=400&q=80" 
                             alt="Refreshing Drink" 
                             class="w-full h-36 sm:h-40 object-cover rounded-xl">
                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>