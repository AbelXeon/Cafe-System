<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign in | CraveDash Delivery</title>

    <!-- Laravel Vite Bundled Tailwind CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Font & Lucide Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=M+PLUS+1p&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Bebas Neue', sans-serif; }

        /* Refined custom checkbox */
        .cd-check {
            appearance: none;
            -webkit-appearance: none;
            width: 1.15rem;
            height: 1.15rem;
            border: 1.5px solid #4b4851;
            border-radius: 0.375rem;
            background-color: #14131a;
            cursor: pointer;
            position: relative;
            transition: all .18s ease;
            flex-shrink: 0;
        }
        .cd-check:hover { border-color: #b08d57; }
        .cd-check:checked {
            background-color: #b08d57;
            border-color: #b08d57;
        }
        .cd-check:checked::after {
            content: "";
            position: absolute;
            left: 4px;
            top: 1px;
            width: 5px;
            height: 9px;
            border: solid #14131a;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        .cd-check:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px rgba(176, 141, 87, 0.25);
        }

        /* Smooth input focus */
        .cd-input:focus { border-color: #b08d57; box-shadow: 0 0 0 3px rgba(176, 141, 87, 0.18); }
        .cd-input:focus + .cd-icon { color: #b08d57; }
    </style>
</head>
<body class="bg-[#0f0e13] text-stone-200 min-h-screen selection:bg-[#b08d57] selection:text-[#0f0e13]">

    <main class="min-h-screen w-full grid grid-cols-1 lg:grid-cols-12">

        <!-- ================= LEFT: FORM ================= -->
        <section class="lg:col-span-5 xl:col-span-4 bg-[#0f0e13] p-8 sm:p-12 lg:p-14 flex flex-col justify-between border-r border-[#1e1c25] z-10">

            <!-- Brand -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#b08d57] flex items-center justify-center text-[#0f0e13]">
                    <i data-lucide="utensils" class="w-5 h-5 stroke-[2.5]"></i>
                </div>
                <div>
                    <span class="text-xl font-bold tracking-tight text-white block">Crave<span class="text-[#b08d57]">Dash</span></span>
                    <span class="text-xs text-stone-500 font-medium">Food &amp; Cafe Management</span>
                </div>
            </div>

            <!-- Form Block -->
            <div class="w-full my-auto py-8 max-w-sm mx-auto">
                <div class="mb-8">
                    <h1 class="text-3xl font-extrabold text-white tracking-tight">Sign in</h1>
                    <p class="text-stone-500 text-sm mt-1.5">Enter your credentials to access your account</p>
                </div>

                <!-- Session Alert -->
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
                <form method="POST" action="{{ route('login.submit') }}" class="space-y-5">
                    @csrf

                    <!-- Username -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Username</label>
                        <div class="relative">
                            <i data-lucide="user" class="cd-icon w-5 h-5 text-stone-600 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none transition-colors"></i>
                            <input
                                type="text"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                autofocus
                                placeholder="e.g. Abebe Bekele"
                                class="cd-input w-full bg-[#14131a] border border-[#2a2731] rounded-xl pl-11 pr-4 py-3 text-sm text-white placeholder-stone-600 focus:outline-none transition duration-150"
                            >
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Password</label>
                        <div class="relative">
                            <i data-lucide="lock" class="cd-icon w-5 h-5 text-stone-600 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none transition-colors"></i>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                placeholder="••••••••"
                                class="cd-input w-full bg-[#14131a] border border-[#2a2731] rounded-xl pl-11 pr-11 py-3 text-sm text-white placeholder-stone-600 focus:outline-none transition duration-150"
                            >
                            <button
                                type="button"
                                onclick="togglePasswordVisibility()"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-stone-600 hover:text-stone-300 transition focus:outline-none"
                                aria-label="Show or hide password">
                                <i data-lucide="eye" id="togglePasswordIcon" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2.5 text-sm text-stone-300 cursor-pointer select-none group">
                            <input type="checkbox" name="remember" class="cd-check">
                            <span class="group-hover:text-white transition">Remember me</span>
                        </label>
                        <a href="#" class="text-sm text-[#b08d57] font-medium hover:text-[#c9a36b] transition">
                            Forgot password?
                        </a>
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        class="w-full mt-2 bg-[#b08d57] hover:bg-[#c9a36b] active:bg-[#9a7a4c] text-[#0f0e13] font-bold rounded-xl py-3 transition duration-150 flex items-center justify-center gap-2">
                        <span>Sign In</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </form>

                <!-- Register Link -->
                <p class="text-stone-500 text-sm mt-6 text-center">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-[#b08d57] font-semibold hover:text-[#c9a36b] hover:underline transition">
                        Register
                    </a>
                </p>
            </div>

            <!-- Copyright -->
            <div class="text-xs text-stone-600 text-center sm:text-left">
                &copy; {{ date('Y') }} CraveDash System. All rights reserved.
            </div>
        </section>

        <!-- ================= RIGHT: VISUAL ================= -->
        <section class="hidden lg:flex lg:col-span-7 xl:col-span-8 bg-[#14131a] p-12 xl:p-16 flex-col justify-between relative overflow-hidden">

            <div class="max-w-xl">
                <h2 class="text-3xl xl:text-4xl font-black text-white tracking-tight mt-5">
                    Fast, synchronized food orders and kitchen delivery control.
                </h2>
            </div>

            <!-- Food Grid -->
            <div class="grid grid-cols-3 gap-5 my-8">

                <!-- Burger -->
                <div class="group relative rounded-2xl overflow-hidden bg-[#0f0e13] border border-[#1e1c25] h-80 flex flex-col justify-end p-5">
                    <img
                        src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=600&q=80"
                        alt=""
                        class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:scale-105 transition duration-500 ease-out"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0f0e13] via-[#0f0e13]/40 to-transparent"></div>
                    <div class="relative z-10">
                        <span class="text-xs font-semibold text-[#b08d57] uppercase tracking-wider">Fast Service</span>
                        <h3 class="text-lg font-bold text-white leading-tight mt-0.5">Gourmet Burgers</h3>
                    </div>
                </div>

                <!-- Pizza -->
                <div class="group relative rounded-2xl overflow-hidden bg-[#0f0e13] border border-[#1e1c25] h-80 flex flex-col justify-end p-5">
                    <img
                        src="https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=600&q=80"
                        alt=""
                        class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:scale-105 transition duration-500 ease-out"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0f0e13] via-[#0f0e13]/40 to-transparent"></div>
                    <div class="relative z-10">
                        <span class="text-xs font-semibold text-[#b08d57] uppercase tracking-wider">Hot Kitchen</span>
                        <h3 class="text-lg font-bold text-white leading-tight mt-0.5">Stone-Oven Pizza</h3>
                    </div>
                </div>

                <!-- Beverage -->
                <div class="group relative rounded-2xl overflow-hidden bg-[#0f0e13] border border-[#1e1c25] h-80 flex flex-col justify-end p-5">
                    <img
                        src="https://images.unsplash.com/photo-1551024709-8f23befc6f87?auto=format&fit=crop&w=600&q=80"
                        alt=""
                        class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:scale-105 transition duration-500 ease-out"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0f0e13] via-[#0f0e13]/40 to-transparent"></div>
                    <div class="relative z-10">
                        <span class="text-xs font-semibold text-[#b08d57] uppercase tracking-wider">Bar &amp; Drinks</span>
                        <h3 class="text-lg font-bold text-white leading-tight mt-0.5">Chilled Drinks</h3>
                    </div>
                </div>

            </div>

            <!-- Bottom Line -->
            <div class="flex items-center justify-between border-t border-[#1e1c25] pt-6 text-stone-500 text-xs font-medium">
                <span>Real-Time Kitchen Dispatch</span>
                <span class="flex items-center gap-1.5 text-stone-300">
                    <i data-lucide="check" class="w-4 h-4 text-[#b08d57]"></i> High-Speed Delivery Network
                </span>
            </div>

        </section>

    </main>

    <!-- Script -->
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