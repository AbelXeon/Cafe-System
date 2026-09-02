<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register | CraveDash</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Font & Lucide Icons -->
    <link rel="preconnect" href__="https://fonts.googleapis.com">
    <link rel="preconnect" href__="https://fonts.gstatic.com" crossorigin>
    <link href__="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .cd-input:focus { border-color: #b08d57; box-shadow: 0 0 0 3px rgba(176, 141, 87, 0.18); }
        #strengthBar { transition: all .25s ease; }
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
                <div class="mb-7">
                    <h1 class="text-3xl font-extrabold text-white tracking-tight">Create account</h1>
                    <p class="text-stone-500 text-sm mt-1.5">Sign up to start ordering</p>
                </div>

                @if ($errors->any())
                    <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 text-sm rounded-xl px-4 py-3 mb-5 flex items-center gap-3">
                        <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.submit') }}" class="space-y-4">
                    @csrf

                    <!-- Full name -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Full name</label>
                        <div class="relative">
                            <i data-lucide="user" class="w-5 h-5 text-stone-600 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input type="text" name="fullname" value="{{ old('fullname') }}" required autofocus
                                placeholder="Abebe Bekele"
                                class="cd-input w-full bg-[#14131a] border border-[#2a2731] rounded-xl pl-11 pr-4 py-3 text-sm text-white placeholder-stone-600 focus:outline-none transition duration-150">
                        </div>
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Username</label>
                        <div class="relative">
                            <i data-lucide="at-sign" class="w-5 h-5 text-stone-600 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input type="text" name="username" value="{{ old('username') }}" required
                                placeholder="Abebe Bekele"
                                class="cd-input w-full bg-[#14131a] border border-[#2a2731] rounded-xl pl-11 pr-4 py-3 text-sm text-white placeholder-stone-600 focus:outline-none transition duration-150">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Email</label>
                        <div class="relative">
                            <i data-lucide="mail" class="w-5 h-5 text-stone-600 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input type="email" name="email" value="{{ old('email') }}"
                                placeholder="abebe@example.com"
                                class="cd-input w-full bg-[#14131a] border border-[#2a2731] rounded-xl pl-11 pr-4 py-3 text-sm text-white placeholder-stone-600 focus:outline-none transition duration-150">
                        </div>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Phone</label>
                        <div class="relative">
                            <i data-lucide="phone" class="w-5 h-5 text-stone-600 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                placeholder="+1 555 010 204"
                                class="cd-input w-full bg-[#14131a] border border-[#2a2731] rounded-xl pl-11 pr-4 py-3 text-sm text-white placeholder-stone-600 focus:outline-none transition duration-150">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Password</label>
                        <div class="relative">
                            <i data-lucide="lock" class="w-5 h-5 text-stone-600 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input type="password" id="password" name="password" required
                                placeholder="••••••••" oninput="checkStrength(this.value)"
                                class="cd-input w-full bg-[#14131a] border border-[#2a2731] rounded-xl pl-11 pr-11 py-3 text-sm text-white placeholder-stone-600 focus:outline-none transition duration-150">
                            <button type="button" onclick="togglePasswordVisibility('password','togglePwIcon')" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-stone-600 hover:text-stone-300 transition focus:outline-none" aria-label="Show or hide password">
                                <i data-lucide="eye" id="togglePwIcon" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <div class="mt-2.5 flex items-center gap-2">
                            <div class="h-1.5 flex-1 rounded-full bg-[#2a2731] overflow-hidden">
                                <div id="strengthBar" class="h-full w-0 rounded-full bg-[#2a2731]"></div>
                            </div>
                            <span id="strengthLabel" class="text-xs font-medium text-stone-600 w-16 text-right">—</span>
                        </div>
                    </div>

                    <!-- Confirm password -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Confirm password</label>
                        <div class="relative">
                            <i data-lucide="lock-keyhole" class="w-5 h-5 text-stone-600 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                placeholder="••••••••"
                                class="cd-input w-full bg-[#14131a] border border-[#2a2731] rounded-xl pl-11 pr-11 py-3 text-sm text-white placeholder-stone-600 focus:outline-none transition duration-150">
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation','togglePw2Icon')" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-stone-600 hover:text-stone-300 transition focus:outline-none" aria-label="Show or hide password">
                                <i data-lucide="eye" id="togglePw2Icon" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                        class="w-full mt-2 bg-[#b08d57] hover:bg-[#c9a36b] active:bg-[#9a7a4c] text-[#0f0e13] font-bold rounded-xl py-3 transition duration-150 flex items-center justify-center gap-2">
                        <span>Create account</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </form>

                <p class="text-stone-500 text-sm mt-6 text-center">
                    Already have an account?
                    <a href__="{{ route('login') }}" class="text-[#b08d57] font-semibold hover:text-[#c9a36b] hover:underline transition">Log in</a>
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
                <span class="text-xs font-semibold uppercase tracking-widest text-[#b08d57] border border-[#2a2731] bg-[#1e1c25] px-3 py-1 rounded-full">
                    Join the network
                </span>
                <h2 class="text-3xl xl:text-4xl font-black text-white tracking-tight mt-5">
                    Set up your kitchen and start serving in minutes.
                </h2>
            </div>

            <!-- Food Grid -->
            <div class="grid grid-cols-3 gap-5 my-8">

                <div class="group relative rounded-2xl overflow-hidden bg-[#0f0e13] border border-[#1e1c25] h-80 flex flex-col justify-end p-5">
                    <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=600&q=80" alt=""
                        class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:scale-105 transition duration-500 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0f0e13] via-[#0f0e13]/40 to-transparent"></div>
                    <div class="relative z-10">
                        <span class="text-xs font-semibold text-[#b08d57] uppercase tracking-wider">Fast Service</span>
                        <h3 class="text-lg font-bold text-white leading-tight mt-0.5">Gourmet Burgers</h3>
                    </div>
                </div>

                <div class="group relative rounded-2xl overflow-hidden bg-[#0f0e13] border border-[#1e1c25] h-80 flex flex-col justify-end p-5">
                    <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=600&q=80" alt=""
                        class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:scale-105 transition duration-500 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0f0e13] via-[#0f0e13]/40 to-transparent"></div>
                    <div class="relative z-10">
                        <span class="text-xs font-semibold text-[#b08d57] uppercase tracking-wider">Hot Kitchen</span>
                        <h3 class="text-lg font-bold text-white leading-tight mt-0.5">Stone-Oven Pizza</h3>
                    </div>
                </div>

                <div class="group relative rounded-2xl overflow-hidden bg-[#0f0e13] border border-[#1e1c25] h-80 flex flex-col justify-end p-5">
                    <img src="https://images.unsplash.com/photo-1551024709-8f23befc6f87?auto=format&fit=crop&w=600&q=80" alt=""
                        class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:scale-105 transition duration-500 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0f0e13] via-[#0f0e13]/40 to-transparent"></div>
                    <div class="relative z-10">
                        <span class="text-xs font-semibold text-[#b08d57] uppercase tracking-wider">Bar &amp; Drinks</span>
                        <h3 class="text-lg font-bold text-white leading-tight mt-0.5">Chilled Drinks</h3>
                    </div>
                </div>

            </div>

            <div class="flex items-center justify-between border-t border-[#1e1c25] pt-6 text-stone-500 text-xs font-medium">
                <span>Real-Time Kitchen Dispatch</span>
                <span class="flex items-center gap-1.5 text-stone-300">
                    <i data-lucide="check" class="w-4 h-4 text-[#b08d57]"></i> High-Speed Delivery Network
                </span>
            </div>

        </section>

    </main>

    <script>
        lucide.createIcons();

        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }

        function checkStrength(value) {
            const bar = document.getElementById('strengthBar');
            const label = document.getElementById('strengthLabel');
            let score = 0;
            if (value.length >= 8) score++;
            if (/[A-Z]/.test(value)) score++;
            if (/[0-9]/.test(value)) score++;
            if (/[^A-Za-z0-9]/.test(value)) score++;

            const levels = [
                { w: '0%',   c: '#2a2731', t: '—' },
                { w: '25%',  c: '#c2604a', t: 'Weak' },
                { w: '50%',  c: '#c9a36b', t: 'Fair' },
                { w: '75%',  c: '#8fae6a', t: 'Good' },
                { w: '100%', c: '#6a9e6a', t: 'Strong' }
            ];
            const lvl = levels[value.length === 0 ? 0 : score || 1];
            bar.style.width = lvl.w;
            bar.style.backgroundColor = lvl.c;
            label.textContent = lvl.t;
            label.style.color = value.length === 0 ? '#5c5a63' : lvl.c;
        }
    </script>
</body>
</html>