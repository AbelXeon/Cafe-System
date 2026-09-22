<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CraveDash</title>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
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
                <div class="max-w-sm mx-auto pt-8 pb-12 px-4">
                    <!-- Brand -->
                    <div class="text-center mb-6">
                        <span class="text-2xl font-black text-white">Crave<span class="text-[#b08d57]">Dash</span></span>
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
                    <div id="tg-auth-error" class="text-rose-400 text-xs mb-4 hidden p-3 bg-rose-500/10 border border-rose-500/20 rounded-xl"></div>

                    <!-- 1. LINK FORM -->
                    <div id="form-link-container" class="${activeTab === 'link' ? '' : 'hidden'}">
                        <p class="text-stone-400 text-xs mb-4">Connect an existing CraveDash account (Admin, Staff, Driver, or Customer).</p>
                        
                        <input id="tg-link-username" placeholder="Username or Email" value="${defaultUsername}"
                            class="w-full bg-[#14131a] border border-[#2a2731] rounded-xl px-4 py-2.5 text-sm text-white mb-3 outline-none focus:border-[#b08d57]">
                        <input id="tg-link-password" type="password" placeholder="Password"
                            class="w-full bg-[#14131a] border border-[#2a2731] rounded-xl px-4 py-2.5 text-sm text-white mb-4 outline-none focus:border-[#b08d57]">
                        
                        <button id="tg-link-btn" onclick="submitLink()"
                            class="w-full bg-[#b08d57] text-[#0f0e13] font-bold py-2.5 rounded-xl text-sm transition hover:bg-[#c9a36b]">
                            Connect Account
                        </button>
                    </div>

                    <!-- 2. REGISTER FORM -->
                    <div id="form-register-container" class="${activeTab === 'register' ? '' : 'hidden'}">
                        <p class="text-stone-400 text-xs mb-4">New here? Create a customer account to start ordering.</p>
                        
                        <input id="tg-reg-fullname" placeholder="Full Name" value="${defaultFullname}"
                            class="w-full bg-[#14131a] border border-[#2a2731] rounded-xl px-4 py-2.5 text-sm text-white mb-3 outline-none focus:border-[#b08d57]">
                        
                        <input id="tg-reg-username" placeholder="Username" value="${defaultUsername}"
                            class="w-full bg-[#14131a] border border-[#2a2731] rounded-xl px-4 py-2.5 text-sm text-white mb-3 outline-none focus:border-[#b08d57]">

                        <input id="tg-reg-email" type="email" placeholder="Email (optional)"
                            class="w-full bg-[#14131a] border border-[#2a2731] rounded-xl px-4 py-2.5 text-sm text-white mb-3 outline-none focus:border-[#b08d57]">

                        <input id="tg-reg-phone" type="text" placeholder="Phone (optional)"
                            class="w-full bg-[#14131a] border border-[#2a2731] rounded-xl px-4 py-2.5 text-sm text-white mb-3 outline-none focus:border-[#b08d57]">

                        <input id="tg-reg-password" type="password" placeholder="Password (min 6 characters)"
                            class="w-full bg-[#14131a] border border-[#2a2731] rounded-xl px-4 py-2.5 text-sm text-white mb-3 outline-none focus:border-[#b08d57]">

                        <input id="tg-reg-password-confirmation" type="password" placeholder="Confirm Password"
                            class="w-full bg-[#14131a] border border-[#2a2731] rounded-xl px-4 py-2.5 text-sm text-white mb-4 outline-none focus:border-[#b08d57]">

                        <button id="tg-reg-btn" onclick="submitRegister()"
                            class="w-full bg-[#b08d57] text-[#0f0e13] font-bold py-2.5 rounded-xl text-sm transition hover:bg-[#c9a36b]">
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
                    // Extract first validation error if present
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