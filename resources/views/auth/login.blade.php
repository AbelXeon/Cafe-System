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
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen selection:bg-amber-500 selection:text-slate-950">

    <!-- Full Screen Edge-to-Edge Grid -->
    <main class="min-h-screen w-full grid grid-cols-1 lg:grid-cols-12">

        <!-- ================= LEFT SIDE: FORM SECTION ================= -->
        <section class="lg:col-span-5 xl:col-span-4 bg-slate-950 p-8 sm:p-12 lg:p-14 flex flex-col justify-between border-r border-slate-900 z-10">
            
            <!-- Brand / Logo -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center text-slate-950">
                    <i data-lucide="utensils" class="w-5 h-5 stroke-[2.5]"></i>
                </div>
                <div>
                    <span class="text-xl font-bold tracking-tight text-white block">Crave<span class="text-amber-500">Dash</span></span>
                    <span class="text-xs text-slate-400 font-medium">Food & Cafe Management</span>
                </div>
            </div>

            <!-- Main Form Block -->
            <div class="w-full my-auto py-8 max-w-sm mx-auto">
                <div class="mb-8">
                    <h1 class="text-3xl font-extrabold text-white tracking-tight">Sign in</h1>
                    <p class="text-slate-400 text-sm mt-1.5">Enter your credentials to access your account</p>
                </div>

                <!-- Session Alert Messages -->
                @if (session('status'))
                    <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm rounded-xl px-4 py-3 mb-5 flex items-center gap-3">
                        <i data-lucide="check-circle-2" class="w-4 h-4 flex-shrink-0"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 text-sm rounded-xl px-4 py-3 mb-5 flex items-center gap-3">
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
                            <i data-lucide="user" class="w-5 h-5 text-slate-500 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input 
                                type="text" 
                                name="username" 
                                value="{{ old('username') }}" 
                                required 
                                autofocus 
                                placeholder="e.g. alex_chef"
                                class="w-full bg-slate-900 border border-slate-800 rounded-xl pl-11 pr-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition duration-150"
                            >
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Password</label>
                        <div class="relative">
                            <i data-lucide="lock" class="w-5 h-5 text-slate-500 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input 
                                type="password" 
                                id="password"
                                name="password" 
                                required 
                                placeholder="••••••••"
                                class="w-full bg-slate-900 border border-slate-800 rounded-xl pl-11 pr-11 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition duration-150"
                            >
                            <button 
                                type="button" 
                                onclick="togglePasswordVisibility()" 
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition focus:outline-none">
                                <i data-lucide="eye" id="togglePasswordIcon" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me Option -->
                    <div class="flex items-center justify-between py-1">
                        <label class="flex items-center gap-2.5 text-sm text-slate-300 cursor-pointer select-none">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                class="w-4 h-4 rounded bg-slate-900 border-slate-700 text-amber-500 focus:ring-amber-500 focus:ring-offset-slate-950 focus:ring-offset-1"
                            >
                            <span>Remember me</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full mt-2 bg-amber-500 hover:bg-amber-400 active:bg-amber-600 text-slate-950 font-bold rounded-xl py-3 shadow transition duration-150 flex items-center justify-center gap-2">
                        <span>Sign In</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </form>

                <!-- Footer / Register Link -->
                <p class="text-slate-400 text-sm mt-6 text-center">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-amber-400 font-semibold hover:text-amber-300 hover:underline transition">
                        Register
                    </a>
                </p>
            </div>

            <!-- Copyright Line -->
            <div class="text-xs text-slate-600 text-center sm:text-left">
                &copy; {{ date('Y') }} CraveDash System. All rights reserved.
            </div>
        </section>

        <!-- ================= RIGHT SIDE: FULL-PAGE FOOD PRESENTATION ================= -->
        <section class="hidden lg:flex lg:col-span-7 xl:col-span-8 bg-slate-900 p-12 xl:p-16 flex-col justify-between relative overflow-hidden">
            
            <div class="max-w-xl">
                <span class="text-xs font-bold uppercase tracking-widest text-amber-400 bg-amber-500/10 border border-amber-500/20 px-3 py-1 rounded-full">
                    Operations & Management
                </span>
                <h2 class="text-3xl xl:text-4xl font-black text-white tracking-tight mt-4">
                    Fast, synchronized food orders and kitchen delivery control.
                </h2>
            </div>

            <!-- Food Visual Composition (Static, Clean Grid & Overlay) -->
            <div class="grid grid-cols-3 gap-5 my-8">
                
                <!-- 1. Burger Card -->
                <div class="group relative rounded-2xl overflow-hidden bg-slate-950 border border-slate-800 h-80 flex flex-col justify-end p-5">
                    <img 
                        src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=600&q=80" 
                        alt="Artisan Burger" 
                        class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:scale-105 transition duration-500 ease-out"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                    <div class="relative z-10">
                        <span class="text-xs font-semibold text-amber-400 uppercase tracking-wider">Fast Service</span>
                        <h3 class="text-lg font-bold text-white leading-tight mt-0.5">Gourmet Burgers</h3>
                    </div>
                </div>

                <div class="group relative rounded-2xl overflow-hidden bg-slate-950 border border-slate-800 h-80 flex flex-col justify-end p-5">
                    <img 
                        src="https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=600&q=80" 
                        alt="Woodfired Pizza" 
                        class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:scale-105 transition duration-500 ease-out"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                    <div class="relative z-10">
                        <span class="text-xs font-semibold text-amber-400 uppercase tracking-wider">Hot Kitchen</span>
                        <h3 class="text-lg font-bold text-white leading-tight mt-0.5">Stone-Oven Pizza</h3>
                    </div>
                </div>

                <div class="group relative rounded-2xl overflow-hidden bg-slate-950 border border-slate-800 h-80 flex flex-col justify-end p-5">
                    <img 
                        src="https://images.unsplash.com/photo-1551024709-8f23befc6f87?auto=format&fit=crop&w=600&q=80" 
                        alt="Beverages" 
                        class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:scale-105 transition duration-500 ease-out"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                    <div class="relative z-10">
                        <span class="text-xs font-semibold text-amber-400 uppercase tracking-wider">Bar & Drinks</span>
                        <h3 class="text-lg font-bold text-white leading-tight mt-0.5">Chilled Drinks</h3>
                    </div>
                </div>

            </div>

            <div class="flex items-center justify-between border-t border-slate-800/80 pt-6 text-slate-400 text-xs font-medium">
                <span>Real-Time Kitchen Dispatch</span>
                <span class="flex items-center gap-1.5 text-slate-300">
                    <i data-lucide="check" class="w-4 h-4 text-amber-400"></i> High-Speed Delivery Network
                </span>
            </div>

        </section>

    </main>

    <script>
        lucide.createIcons();

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                passwordInput.type = 'password';
                toggleIcon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }
    </script>
</body>
</html>