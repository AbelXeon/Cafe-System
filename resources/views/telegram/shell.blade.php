<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CraveDash</title>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0f0e13]">

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

        async function bootstrap() {
            try {
                // Step 1: ask backend if this Telegram identity is already linked
                const meRes = await fetch("{{ route('telegram.me') }}", {
                    headers: { 'X-Telegram-Init-Data': initData }
                });
                const me = await meRes.json();

                if (me.linked) {
                    window.location.href = roleToDashboardUrl(me.user.role);
                    return;
                }

                // Not linked yet — show the link form
                renderLinkForm();
            } catch (err) {
                console.error(err);
                renderLinkForm();
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

        function renderLinkForm() {
            document.getElementById('telegram-app-root').innerHTML = `
                <div class="max-w-sm mx-auto pt-16 px-4">
                    <h1 class="text-white text-xl font-bold mb-1">Link your account</h1>
                    <p class="text-stone-400 text-sm mb-6">Enter your CraveDash username and password to connect Telegram.</p>
                    <input id="tg-username" placeholder="Username"
                        class="w-full bg-[#14131a] border border-[#2a2731] rounded-xl px-4 py-2.5 text-white mb-3 outline-none">
                    <input id="tg-password" type="password" placeholder="Password"
                        class="w-full bg-[#14131a] border border-[#2a2731] rounded-xl px-4 py-2.5 text-white mb-4 outline-none">
                    <div id="tg-link-error" class="text-rose-400 text-xs mb-3 hidden"></div>
                    <button id="tg-link-btn"
                        class="w-full bg-[#b08d57] text-[#0f0e13] font-bold py-2.5 rounded-xl cursor-pointer">
                        Connect
                    </button>
                </div>
            `;

            document.getElementById('tg-link-btn').addEventListener('click', submitLink);
        }

        async function submitLink() {
            const username = document.getElementById('tg-username').value;
            const password = document.getElementById('tg-password').value;
            const errorEl = document.getElementById('tg-link-error');

            const res = await fetch("{{ route('telegram.link') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Telegram-Init-Data': initData,
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ username, password })
            });

            const data = await res.json();

            if (res.ok) {
                window.location.href = roleToDashboardUrl(data.role);
            } else {
                errorEl.textContent = data.message || 'Could not link account.';
                errorEl.classList.remove('hidden');
            }
        }

        bootstrap();
    </script>
</body>
</html>