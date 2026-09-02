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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @keyframes float-slow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(2deg); }
        }
        @keyframes float-fast {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-14px) rotate(-3deg); }
        }
        .animate-float-1 { animation: float-slow 5s ease-in-out infinite; }
        .animate-float-2 { animation: float-fast 4s ease-in-out infinite; }
        .animate-float-3 { animation: float-slow 6s ease-in-out infinite 1s; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4 md:p-8 relative overflow-x-hidden selection:bg-amber-500 selection:text-white">

    <!-- Subtle Ambient Background Glows -->
    <div class="fixed top-1/4 -left-20 w-96 h-96 bg-orange-600/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="fixed bottom-10 -right-20 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Main Container Wrapper -->
    <div class="w-full max-w-5xl bg-slate-900/80 border border-slate-800/80 backdrop-blur-2xl rounded-3xl shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[640px]">
        
        <!-- ================= LEFT SIDE: FORM ================= -->
        <div class="lg:col-span-6 p-8 sm:p-12 flex flex-col justify-between relative z-10">
            <div>
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-amber-500 to-orange-500 flex items-center justify-center shadow-lg shadow-orange-500/20 text-white font-black text-xl">
                        🍔
                    </div>
                    <div>
                        <span class="text-xl font-bold tracking-tight text-white block">Crave<span class="text-amber-500">Dash</span></span>
                        <span class="text-xs text-slate-400">Food Delivery & Cafe Portal</span>
                    </div>
                </div>

                <!-- Header Title -->
                <div class="mb-6">
                    <h1 class="text-3xl font-extrabold text-white tracking-tight">Welcome Back!</h1>
                    <p class="text-slate-400 text-sm mt-1">Ready to manage fresh orders & tasty meals?</p>
                </div>

                <!-- Session Alert Messages -->
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

                <!-- Login Form -->
                <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
                    @csrf
                    
                    <!-- Username Input -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Username</label>
                        <div class="relative">
                            <i data-lucide="user" class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input type="text" name="username" value="{{ old('username') }}" required autofocus placeholder="Enter your username"
                                class="w-full bg-slate-950/60 border border-slate-700/80 rounded-xl pl-11 pr-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition duration-200">
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300">Password</label>
                        </div>
                        <div class="relative">
                            <i data-lucide="lock" class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input type="password" name="password" required placeholder="••••••••"
                                class="w-full bg-slate-950/60 border border-slate-700/80 rounded-xl pl-11 pr-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition duration-200">
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 text-sm text-slate-400 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-amber-500 focus:ring-amber-500 focus:ring-offset-slate-900">
                            Remember me
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full mt-2 bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 hover:from-amber-400 hover:to-orange-500 text-white font-semibold rounded-xl py-3 shadow-lg shadow-orange-500/25 transition duration-200 flex items-center justify-center gap-2 group">
                        <span>Sign In to Dashboard</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 transition-transform group-hover:translate-x-1"></i>
                    </button>
                </form>
            </div>

            <!-- Footer / Register Link -->
            <p class="text-slate-400 text-sm mt-8 text-center lg:text-left border-t border-slate-800/80 pt-5">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-amber-400 font-semibold hover:text-amber-300 hover:underline transition">
                    Register here
                </a>
            </p>
        </div>

        <!-- ================= RIGHT SIDE: FOOD SHOWCASE VISUALS ================= -->
        <div class="lg:col-span-6 bg-gradient-to-br from-amber-950/40 via-slate-900 to-orange-950/40 p-8 lg:p-12 relative flex flex-col items-center justify-center overflow-hidden border-t lg:border-t-0 lg:border-l border-slate-800">
            
            <!-- Food Glow backdrop -->
            <div class="absolute w-72 h-72 bg-gradient-to-tr from-amber-500/20 to-orange-500/30 rounded-full blur-3xl"></div>
            
            <!-- Food Composition Area -->
            <div class="relative w-full max-w-sm h-72 sm:h-80 flex items-center justify-center my-auto">
                
                <!-- 1. Center Main Burger -->
                <div class="relative z-20 animate-float-1 drop-shadow-[0_20px_35px_rgba(0,0,0,0.7)]">
                    <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=500&q=80" 
                         alt="Gourmet Burger" 
                         class="w-48 sm:w-56 h-48 sm:h-56 object-cover rounded-full border-4 border-amber-500/30 shadow-2xl ring-4 ring-black/40">
                </div>

                <!-- 2. Pizza Floating (Top Left) -->
                <div class="absolute -top-4 -left-4 z-10 animate-float-2 drop-shadow-xl">
                    <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=300&q=80" 
                         alt="Delicious Pizza" 
                         class="w-24 sm:w-28 h-24 sm:h-28 object-cover rounded-full border-2 border-orange-500/40 shadow-lg ring-2 ring-black/30">
                </div>

                <!-- 3. Cold Drink / Soda Floating (Bottom Right) -->
                <div class="absolute -bottom-2 -right-4 z-20 animate-float-3 drop-shadow-xl">
                    <img src="https://images.unsplash.com/photo-1551024709-8f23befc6f87?auto=format&fit=crop&w=300&q=80" 
                         alt="Refreshing Drink" 
                         class="w-24 sm:w-28 h-24 sm:h-28 object-cover rounded-full border-2 border-amber-400/40 shadow-lg ring-2 ring-black/30">
                </div>

                <!-- Floating Live Badge 1: Delivery Status -->
                <div class="absolute -left-2 bottom-6 z-30 bg-slate-900/90 backdrop-blur-md border border-slate-700 px-3.5 py-2 rounded-2xl shadow-xl flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-sm">
                        ⚡
                    </span>
                    <div>
                        <p class="text-[11px] text-slate-400 uppercase tracking-wider font-semibold">Delivery</p>
                        <p class="text-xs font-bold text-white">~ 20 Mins</p>
                    </div>
                </div>

                <!-- Floating Live Badge 2: Rating -->
                <div class="absolute -right-2 top-4 z-30 bg-slate-900/90 backdrop-blur-md border border-slate-700 px-3.5 py-2 rounded-2xl shadow-xl flex items-center gap-2">
                    <span class="text-amber-400 text-sm">★</span>
                    <span class="text-xs font-bold text-white">4.9</span>
                    <span class="text-[11px] text-slate-400 font-medium">(2.4k+ reviews)</span>
                </div>
            </div>

            <!-- Bottom Content text inside showcase -->
            <div class="text-center relative z-10 mt-6 max-w-xs">
                <h3 class="text-lg font-bold text-white">Hot, Fast & Fresh Food</h3>
                <p class="text-xs text-slate-400 mt-1">Manage incoming food orders, kitchen status, and deliveries all in real time.</p>
            </div>

        </div>

    </div>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>