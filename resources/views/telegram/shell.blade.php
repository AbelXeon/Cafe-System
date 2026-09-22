<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neba Cafe Hawassa</title>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-[#0f0e13] text-stone-200 min-h-screen">

    <div id="telegram-app-root">
        <div class="flex items-center justify-center h-screen text-stone-400 text-sm">
            Loading...
        </div>
    </div>

    <script>
        const tg = window.Telegram.WebApp;
        tg.ready();
        tg.expand();

        const initData = tg.initData;
        const tgUser = tg.initDataUnsafe?.user || {};

        async function bootstrap() {
            try {
                const meRes = await fetch("{{ route('telegram.me', [], false) }}", {
                    headers: { 
                        'Accept': 'application/json',
                        'X-Telegram-Init-Data': initData 
                    }
                });

                if (!meRes.ok) {
                    renderAuthForms('link');
                    return;
                }

                const me = await meRes.json();

                if (me.linked) {
                    window.location.href = roleToDashboardUrl(me.user.role);
                    return;
                }

                renderAuthForms('link');
            } catch (err) {
                console.error('Bootstrap error:', err);
                renderAuthForms('link');
            }
        }

        function roleToDashboardUrl(role) {
            const map = {
                customer: "{{ route('user.dashboard') }}",
                delivery: "{{ route('delivery.dashboard') }}",
                staff:    "{{ route('staff.dashboard') }}",
                admin:    "{{ route('admin.dashboard') }}",
            };
            return map[role] || '/';
        }

        function renderAuthForms(activeTab = 'link') {
            const defaultFullname = [tgUser.first_name, tgUser.last_name].filter(Boolean).join(' ');
            const defaultUsername = tgUser.username || '';

            document.getElementById('telegram-app-root').innerHTML = `
                <div class="max-w-sm mx-auto pt-6 pb-12 px-4">
                    <!-- Brand -->
                    <div class="text-center mb-6">
                        <span class="text-2xl font-black text-white">Neba Cafe <span class="text-[#b08d57]">Hawassa</span></span>
                        <p class="text-xs text-stone-400 mt-1">Food &amp; Cafe Delivery</p>
                    </div>

                    <!-- Tabs -->
                    <div class="flex bg-[#14131a] p-1 rounded-xl mb-6 border border-[#2a2731]">
                        <button id="tab-btn-link" onclick="switchTab('link')"
                            class="flex-1 py-2 text-xs font-bold rounded-lg transition ${activeTab === 'link' ? 'bg-[#b08d57] text-[#0f0e13]' : 'text-stone-400 hover:text-white'}">
                            Sign In / Link
                        </button>
                        <button id="tab-btn-register" onclick="switchTab('register')"
                            class="flex-1 py-2 text-xs font-bold rounded-lg transition ${activeTab === 'register' ? 'bg-[#b08d57] text-[#0f0e13]' : 'text-stone-400 hover:text-white'}">
                            Create Account
                        </button>
                    </div>

                    <!-- ERROR CONTAINER -->
                    <div id="tg-auth-error" class="text-rose-400 text-xs mb-5 hidden p-3 bg-rose-500/10 border border-rose-500/20 rounded-xl"></div>

                    <!-- 1. LINK FORM -->
                    <div id="form-link-container" class="${activeTab === 'link' ? '' : 'hidden'} space-y-4">
                        <p class="text-stone-400 text-xs mb-3">Connect an existing Neba Cafe account.</p>
                        
                        <!-- Floating Username -->
                        <div class="relative">
                            <input id="tg-link-username" type="text" placeholder=" " value="${defaultUsername}"
                                class="peer w-full bg-[#14131a] border border-[#2a2731] rounded-xl px-4 py-3 text-sm text-white outline-none transition focus:border-[#b08d57]">
                            <label for="tg-link-username"
                                class="absolute left-3.5 -top-2.5 bg-[#0f0e13] px-1.5 text-xs text-stone-400 font-semibold transition-all pointer-events-none
                                       peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-placeholder-shown:text-stone-500 peer-placeholder-shown:font-normal peer-placeholder-shown:bg-transparent
                                       peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-[#b08d57] peer-focus:font-semibold peer-focus:bg-[#0f0e13]">
                                Username or Email
                            </label>
                        </div>

                        <!-- Floating Password -->
                        <div class="relative">
                            <input id="tg-link-password" type="password" placeholder=" "
                                class="peer w-full bg-[#14131a] border border-[#2a2731] rounded-xl px-4 py-3 text-sm text-white outline-none transition focus:border-[#b08d57]">
                            <label for="tg-link-password"
                                class="absolute left-3.5 -top-2.5 bg-[#0f0e13] px-1.5 text-xs text-stone-400 font-semibold transition-all pointer-events-none
                                       peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-placeholder-shown:text-stone-500 peer-placeholder-shown:font-normal peer-placeholder-shown:bg-transparent
                                       peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-[#b08d57] peer-focus:font-semibold peer-focus:bg-[#0f0e13]">
                                Password
                            </label>
                        </div>
                        
                        <button id="tg-link-btn" onclick="submitLink()"
                            class="w-full mt-2 bg-[#b08d57] text-[#0f0e13] font-bold py-3 rounded-xl text-sm transition hover:bg-[#c9a36b] cursor-pointer">
                            Connect Account
                        </button>
                    </div>

                    <!-- 2. REGISTER FORM -->
                    <div id="form-register-container" class="${activeTab === 'register' ? '' : 'hidden'} space-y-4">
                        <p class="text-stone-400 text-xs mb-3">New here? Create a customer account to start ordering.</p>
                        
                        <!-- Floating Full Name -->
                        <div class="relative">
                            <input id="tg-reg-fullname" type="text" placeholder=" " value="${defaultFullname}"
                                class="peer w-full bg-[#14131a] border border-[#2a2731] rounded-xl px-4 py-3 text-sm text-white outline-none transition focus:border-[#b08d57]">
                            <label for="tg-reg-fullname"
                                class="absolute left-3.5 -top-2.5 bg-[#0f0e13] px-1.5 text-xs text-stone-400 font-semibold transition-all pointer-events-none
                                       peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-placeholder-shown:text-stone-500 peer-placeholder-shown:font-normal peer-placeholder-shown:bg-transparent
                                       peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-[#b08d57] peer-focus:font-semibold peer-focus:bg-[#0f0e13]">
                                Full Name
                            </label>
                        </div>
                        
                        <!-- Floating Username -->
                        <div class="relative">
                            <input id="tg-reg-username" type="text" placeholder=" " value="${defaultUsername}"
                                class="peer w-full bg-[#14131a] border border-[#2a2731] rounded-xl px-4 py-3 text-sm text-white outline-none transition focus:border-[#b08d57]">
                            <label for="tg-reg-username"
                                class="absolute left-3.5 -top-2.5 bg-[#0f0e13] px-1.5 text-xs text-stone-400 font-semibold transition-all pointer-events-none
                                       peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-placeholder-shown:text-stone-500 peer-placeholder-shown:font-normal peer-placeholder-shown:bg-transparent
                                       peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-[#b08d57] peer-focus:font-semibold peer-focus:bg-[#0f0e13]">
                                Username
                            </label>
                        </div>

                        <!-- Floating Email -->
                        <div class="relative">
                            <input id="tg-reg-email" type="email" placeholder=" "
                                class="peer w-full bg-[#14131a] border border-[#2a2731] rounded-xl px-4 py-3 text-sm text-white outline-none transition focus:border-[#b08d57]">
                            <label for="tg-reg-email"
                                class="absolute left-3.5 -top-2.5 bg-[#0f0e13] px-1.5 text-xs text-stone-400 font-semibold transition-all pointer-events-none
                                       peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-placeholder-shown:text-stone-500 peer-placeholder-shown:font-normal peer-placeholder-shown:bg-transparent
                                       peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-[#b08d57] peer-focus:font-semibold peer-focus:bg-[#0f0e13]">
                                Email (optional)
                            </label>
                        </div>

                        <!-- Floating Phone -->
                        <div class="relative">
                            <input id="tg-reg-phone" type="text" placeholder=" "
                                class="peer w-full bg-[#14131a] border border-[#2a2731] rounded-xl px-4 py-3 text-sm text-white outline-none transition focus:border-[#b08d57]">
                            <label for="tg-reg-phone"
                                class="absolute left-3.5 -top-2.5 bg-[#0f0e13] px-1.5 text-xs text-stone-400 font-semibold transition-all pointer-events-none
                                       peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-placeholder-shown:text-stone-500 peer-placeholder-shown:font-normal peer-placeholder-shown:bg-transparent
                                       peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-[#b08d57] peer-focus:font-semibold peer-focus:bg-[#0f0e13]">
                                Phone 
                            </label>
                        </div>

                        <!-- Floating Password -->
                        <div class="relative">
                            <input id="tg-reg-password" type="password" placeholder=" "
                                class="peer w-full bg-[#14131a] border border-[#2a2731] rounded-xl px-4 py-3 text-sm text-white outline-none transition focus:border-[#b08d57]">
                            <label for="tg-reg-password"
                                class="absolute left-3.5 -top-2.5 bg-[#0f0e13] px-1.5 text-xs text-stone-400 font-semibold transition-all pointer-events-none
                                       peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-placeholder-shown:text-stone-500 peer-placeholder-shown:font-normal peer-placeholder-shown:bg-transparent
                                       peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-[#b08d57] peer-focus:font-semibold peer-focus:bg-[#0f0e13]">
                                Password
                            </label>
                        </div>

                        <!-- Floating Confirm Password -->
                        <div class="relative">
                            <input id="tg-reg-password-confirmation" type="password" placeholder=" "
                                class="peer w-full bg-[#14131a] border border-[#2a2731] rounded-xl px-4 py-3 text-sm text-white outline-none transition focus:border-[#b08d57]">
                            <label for="tg-reg-password-confirmation"
                                class="absolute left-3.5 -top-2.5 bg-[#0f0e13] px-1.5 text-xs text-stone-400 font-semibold transition-all pointer-events-none
                                       peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-placeholder-shown:text-stone-500 peer-placeholder-shown:font-normal peer-placeholder-shown:bg-transparent
                                       peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-[#b08d57] peer-focus:font-semibold peer-focus:bg-[#0f0e13]">
                                Confirm Password
                            </label>
                        </div>

                        <button id="tg-reg-btn" onclick="submitRegister()"
                            class="w-full mt-2 bg-[#b08d57] text-[#0f0e13] font-bold py-3 rounded-xl text-sm transition hover:bg-[#c9a36b] cursor-pointer">
                            Create Account &amp; Order
                        </button>
                    </div>
                </div>
            `;
        }

        window.switchTab = function(tab) {
            document.getElementById('tg-auth-error').classList.add('hidden');
            renderAuthForms(tab);
        };

        window.submitLink = async function() {
            const btn = document.getElementById('tg-link-btn');
            const username = document.getElementById('tg-link-username').value;
            const password = document.getElementById('tg-link-password').value;
            const errorEl = document.getElementById('tg-auth-error');

            errorEl.classList.add('hidden');
            btn.textContent = 'Connecting...';
            btn.disabled = true;

            try {
                const res = await fetch("{{ route('telegram.link', [], false) }}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Telegram-Init-Data': initData,
                    },
                    body: JSON.stringify({ username, password })
                });

                const data = await res.json();

                if (res.ok) {
                    window.location.href = roleToDashboardUrl(data.role);
                } else {
                    errorEl.textContent = data.message || 'Could not link account.';
                    errorEl.classList.remove('hidden');
                    btn.textContent = 'Connect Account';
                    btn.disabled = false;
                }
            } catch (err) {
                console.error(err);
                errorEl.textContent = 'Connection error. Please try again.';
                errorEl.classList.remove('hidden');
                btn.textContent = 'Connect Account';
                btn.disabled = false;
            }
        };

        window.submitRegister = async function() {
            const btn = document.getElementById('tg-reg-btn');
            const errorEl = document.getElementById('tg-auth-error');

            const payload = {
                fullname: document.getElementById('tg-reg-fullname').value,
                username: document.getElementById('tg-reg-username').value,
                email: document.getElementById('tg-reg-email').value || null,
                phone: document.getElementById('tg-reg-phone').value || null,
                password: document.getElementById('tg-reg-password').value,
                password_confirmation: document.getElementById('tg-reg-password-confirmation').value,
            };

            errorEl.classList.add('hidden');
            btn.textContent = 'Creating Account...';
            btn.disabled = true;

            try {
                const res = await fetch("{{ route('telegram.register', [], false) }}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Telegram-Init-Data': initData,
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();

                if (res.ok) {
                    window.location.href = roleToDashboardUrl(data.role);
                } else {
                    let errorMsg = data.message;
                    if (data.errors) {
                        const firstKey = Object.keys(data.errors)[0];
                        errorMsg = data.errors[firstKey][0];
                    }
                    errorEl.textContent = errorMsg || 'Could not create account.';
                    errorEl.classList.remove('hidden');
                    btn.textContent = 'Create Account & Order';
                    btn.disabled = false;
                }
            } catch (err) {
                console.error(err);
                errorEl.textContent = 'Connection error. Please try again.';
                errorEl.classList.remove('hidden');
                btn.textContent = 'Create Account & Order';
                btn.disabled = false;
            }
        };

        bootstrap();
    </script>
</body>
</html>