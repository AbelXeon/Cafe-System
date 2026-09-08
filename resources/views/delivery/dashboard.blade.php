<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CraveDash | Delivery Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        .custom-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #2a2731; border-radius: 9999px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #3a3741; }
        .side-link { color: #a8a29e; transition: all 0.15s ease-in-out; }
        .side-link:hover { color: #f5f5f4; background: #1e1c25; }
        .side-link.active { background: #b08d57; color: #0f0e13; font-weight: 700; }
        .cd-input:focus { border-color: #b08d57; box-shadow: 0 0 0 3px rgba(176, 141, 87, 0.18); }
    </style>
</head>
<body class="bg-[#0f0e13] text-stone-200 h-screen overflow-hidden selection:bg-[#b08d57] selection:text-[#0f0e13]" x-data="deliveryApp()" x-init="init()">

<!-- Toast Notification -->
<div x-show="toast.visible"
     x-cloak
     x-transition:enter="transform ease-out duration-300 transition"
     x-transition:enter-start="translate-y-6 opacity-0 scale-90 sm:translate-x-8 sm:translate-y-0"
     x-transition:enter-end="translate-y-0 opacity-100 scale-100 sm:translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 scale-100 sm:translate-x-0"
     x-transition:leave-end="opacity-0 scale-90 sm:translate-x-8"
     class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-[60] max-w-sm w-[calc(100%-2rem)] sm:w-96 bg-[#14131a]/95 border border-[#b08d57]/60 rounded-2xl shadow-2xl overflow-hidden backdrop-blur-xl">
    <div class="p-4 flex items-start gap-3.5">
        <div class="w-10 h-10 rounded-xl bg-[#b08d57]/15 border border-[#b08d57]/30 flex items-center justify-center text-[#b08d57] shrink-0 shadow-lg shadow-[#b08d57]/20">
            <i data-lucide="bike" class="w-5 h-5"></i>
        </div>
        <div class="flex-1 min-w-0 pr-1">
            <h4 class="text-sm font-bold text-white tracking-tight" x-text="toast.title"></h4>
            <p class="text-xs text-stone-400 mt-1 leading-relaxed break-all sm:break-words" x-text="toast.message"></p>
        </div>
        <button @click="toast.visible = false" class="text-stone-500 hover:text-white transition p-1 rounded-lg hover:bg-[#1e1c25]">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>
</div>

<!-- Mobile Header -->
<header class="lg:hidden bg-[#0f0e13] border-b border-[#1e1c25] px-4 py-3 flex items-center justify-between z-30 shrink-0">
    <div class="flex items-center gap-3">
        <button @click="mobileNavOpen = true; $nextTick(() => lucide.createIcons())" class="p-2 -ml-2 rounded-xl text-stone-400 hover:text-white hover:bg-[#1e1c25] transition" aria-label="Open Navigation">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-[#b08d57] flex items-center justify-center text-[#0f0e13]">
                <i data-lucide="bike" class="w-4 h-4 stroke-[2.5]"></i>
            </div>
            <span class="text-base font-black tracking-tight text-white">Delivery<span class="text-[#b08d57]">Dash</span></span>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <span class="w-2.5 h-2.5 rounded-full" :class="online ? 'bg-emerald-500' : 'bg-stone-600'"></span>
        <span class="text-xs font-bold text-stone-300" x-text="online ? 'Online' : 'Offline'"></span>
    </div>
</header>

<div class="flex h-[calc(100vh-57px)] lg:h-full w-full relative">

    <!-- 1. Include Driver Sidebar -->
    @include('delivery.partials.sidebar')

    <!-- 2. Include Dispatch Queue -->
    @include('delivery.partials.section-orders')

    <!-- 3. Include Driver Chat Support -->
    @include('delivery.partials.section-chat')

</div>

<script>
    const INITIAL_ORDERS = @json($orders);
    const INITIAL_ONLINE = @json($online);
    const LIVE_ORDERS_URL = "{{ route('delivery.orders.live') }}";
    const ACCEPT_URL = (id) => `{{ url('/delivery/orders') }}/${id}/accept`;
    const DECLINE_URL = (id) => `{{ url('/delivery/orders') }}/${id}/decline`;
    const DELIVERED_URL = (id) => `{{ url('/delivery/orders') }}/${id}/delivered`;
    const TOGGLE_ONLINE_URL = "{{ route('delivery.online.toggle') }}";
    const CHAT_LIST_URL = "{{ route('delivery.chats.index') }}";
    const CHAT_BASE_URL = "{{ url('/delivery/chats') }}";
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
    const CURRENT_USER_ID = {{ Auth::id() }};

    function deliveryApp() {
        return {
            mobileNavOpen: false,
            showChat: false,
            orders: INITIAL_ORDERS || [],
            activeTab: 'all',
            actionId: null,
            online: INITIAL_ONLINE || false,
            soundEnabled: true,
            counts: { ready: 0, out_for_delivery: 0, delivered: 0 },
            toast: { visible: false, title: '', message: '' },

            init() {
                this.updateCounts();
                setInterval(() => this.fetchLiveOrders(), 6000);
                setTimeout(() => lucide.createIcons(), 50);
            },

            get hasActiveDelivery() {
                return this.orders.some(o => o.status === 'out_for_delivery');
            },

            get filteredOrders() {
                if (this.activeTab === 'all') return this.orders;
                return this.orders.filter(o => o.status === this.activeTab);
            },

            statusLabel(status) {
                return {
                    ready: 'Ready for Pickup',
                    out_for_delivery: 'Out for Delivery',
                    delivered: 'Delivered',
                    cancelled: 'Cancelled'
                }[status] || status.replace('_', ' ');
            },

            normalizedExtras(item) {
                let e = item.extras;
                if (!e) return [];
                if (Array.isArray(e)) return e;
                if (typeof e === 'string') {
                    try {
                        const parsed = JSON.parse(e);
                        if (Array.isArray(parsed)) return parsed;
                    } catch (err) {}
                    return e.split(',').map(s => s.trim()).filter(Boolean);
                }
                if (typeof e === 'object') return Object.values(e);
                return [];
            },

            hasExtras(item) {
                return this.normalizedExtras(item).length > 0;
            },

            navigateTo(order) {
                const destination = `${order.latitude},${order.longitude}`;

                if (!navigator.geolocation) {
                    window.open(`https://www.google.com/maps/dir/?api=1&destination=${destination}&travelmode=driving`, '_blank');
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        const origin = `${pos.coords.latitude},${pos.coords.longitude}`;
                        const url = `https://www.google.com/maps/dir/?api=1&origin=${origin}&destination=${destination}&travelmode=driving`;
                        window.open(url, '_blank');
                    },
                    () => {
                        this.showToast('Location Unavailable', 'Could not get current position. Opening drop-off location.');
                        window.open(`https://www.google.com/maps/dir/?api=1&destination=${destination}&travelmode=driving`, '_blank');
                    },
                    { enableHighAccuracy: true, timeout: 8000 }
                );
            },

            updateCounts() {
                this.counts = {
                    ready: this.orders.filter(o => o.status === 'ready').length,
                    out_for_delivery: this.orders.filter(o => o.status === 'out_for_delivery').length,
                    delivered: this.orders.filter(o => o.status === 'delivered').length,
                };
            },

            async fetchLiveOrders() {
                try {
                    const res = await fetch(LIVE_ORDERS_URL, { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) return;
                    const data = await res.json();

                    const prevReady = this.counts.ready;
                    this.orders = data.orders;
                    this.counts = data.counts;
                    if (typeof data.online !== 'undefined') this.online = data.online;

                    if (this.online && data.counts.ready > prevReady && this.soundEnabled) {
                        const newest = data.orders.find(o => o.status === 'ready');
                        this.playNotificationSound();
                        this.showToast('New Order Ready!', newest ? `Order #${newest.id} is ready for pickup.` : 'A new order is ready for pickup.');
                    }
                    this.$nextTick(() => lucide.createIcons());
                } catch (e) {
                    console.error('Failed fetching live delivery orders:', e);
                }
            },

            async toggleOnline() {
                try {
                    const res = await fetch(TOGGLE_ONLINE_URL, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    this.online = data.online;
                    this.showToast(this.online ? 'You are now Online' : 'You are now Offline', this.online ? 'Receiving incoming ready orders.' : 'Stopped receiving orders.');
                    this.$nextTick(() => lucide.createIcons());
                } catch (e) {
                    alert('Network error while toggling online status.');
                }
            },

            async acceptOrder(order) {
                if (this.hasActiveDelivery) {
                    this.showToast('Delivery in Progress', 'Please mark your current delivery complete before accepting a new one.');
                    return;
                }

                this.actionId = order.id;
                try {
                    const res = await fetch(ACCEPT_URL(order.id), {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    if (res.ok) {
                        if (data.order) {
                            const idx = this.orders.findIndex(o => o.id === order.id);
                            if (idx !== -1) this.orders[idx] = data.order;
                        } else {
                            order.status = 'out_for_delivery';
                        }
                        this.updateCounts();
                        this.showToast('Order Accepted', `Order #${order.id} is now your active delivery.`);
                    } else {
                        alert(data.message || 'Could not accept this order.');
                        await this.fetchLiveOrders();
                    }
                } catch (e) {
                    alert('Network error while accepting order.');
                } finally {
                    this.actionId = null;
                    this.$nextTick(() => lucide.createIcons());
                }
            },

            async declineOrder(order) {
                if (this.hasActiveDelivery) return;
                this.orders = this.orders.filter(o => o.id !== order.id);
                this.updateCounts();
                this.showToast('Order Skipped', `Order #${order.id} declined.`);
            },

            async markDelivered(order) {
                this.actionId = order.id;
                try {
                    const res = await fetch(DELIVERED_URL(order.id), {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    if (res.ok) {
                        order.status = 'delivered';
                        this.updateCounts();
                        this.showToast('Delivery Complete', `Order #${order.id} marked as delivered.`);
                    } else {
                        alert(data.message || 'Could not mark as delivered.');
                    }
                } catch (e) {
                    alert('Network error while updating delivery.');
                } finally {
                    this.actionId = null;
                    this.$nextTick(() => lucide.createIcons());
                }
            },

            showToast(title, message) {
                this.toast.title = title;
                this.toast.message = message;
                this.toast.visible = true;
                this.$nextTick(() => lucide.createIcons());
                setTimeout(() => { this.toast.visible = false; }, 4000);
            },

            toggleSound() { this.soundEnabled = !this.soundEnabled; },

            playNotificationSound() {
                try {
                    const ctx = new (window.AudioContext || window.webkitAudioContext)();
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(587.33, ctx.currentTime);
                    osc.frequency.setValueAtTime(880, ctx.currentTime + 0.15);
                    gain.gain.setValueAtTime(0.15, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.4);
                    osc.connect(gain); gain.connect(ctx.destination);
                    osc.start(); osc.stop(ctx.currentTime + 0.4);
                } catch (e) {}
            }
        };
    }

    function chatApp(role) {
        return {
            role,
            conversations: [],
            activeOrderId: null,
            activeOrderTitle: '',
            messages: [],
            canSend: false,
            draft: '',
            loading: false,
            mobileView: 'list',
            currentChannelName: null,
            pollTimer: null,

            init() {
                this.$watch('showChat', (value) => {
                    if (value) {
                        this.startLive();
                    } else {
                        this.stopLive();
                    }
                });

                if (this.showChat) {
                    this.startLive();
                }
            },

            startLive() {
                if (this.pollTimer) return; 
                this.loadConversations();
                this.pollTimer = setInterval(() => this.loadConversations(), 10000);
                if (this.activeOrderId) {
                    this.subscribeToChannel(this.activeOrderId);
                }
            },

            stopLive() {
                if (this.pollTimer) {
                    clearInterval(this.pollTimer);
                    this.pollTimer = null;
                }
                if (window.Echo && this.currentChannelName) {
                    window.Echo.leave(this.currentChannelName);
                    this.currentChannelName = null;
                }
            },

            async loadConversations() {
                try {
                    const res = await fetch(CHAT_LIST_URL, { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) return;
                    const data = await res.json();
                    this.conversations = data.conversations || [];

                    if (this.activeOrderId) {
                        const current = this.conversations.find(c => c.order_id === this.activeOrderId);
                        if (current) {
                            this.canSend = current.can_chat;
                            this.activeOrderTitle = `Order #${current.order_id} · ${current.other_party}`;
                        }
                    }
                } catch (e) {
                    console.error('Failed loading conversations', e);
                }
            },

            async openConversation(conv) {
                this.activeOrderId = conv.order_id;
                this.activeOrderTitle = `Order #${conv.order_id} · ${conv.other_party}`;
                this.canSend = conv.can_chat;
                this.mobileView = 'thread';
                this.loading = true;
                this.messages = [];

                this.subscribeToChannel(conv.order_id);

                try {
                    const res = await fetch(`${CHAT_BASE_URL}/${conv.order_id}/messages`, { headers: { 'Accept': 'application/json' } });
                    const data = await res.json();
                    if (res.ok) {
                        this.messages = data.messages || [];
                        this.canSend = data.can_send;
                    }
                } catch (e) {
                    console.error('Failed loading messages', e);
                } finally {
                    this.loading = false;
                    this.$nextTick(() => { 
                        this.scrollToBottom(); 
                        lucide.createIcons(); 
                    });
                }
            },

            backToList() {
                this.mobileView = 'list';
                setTimeout(() => lucide.createIcons(), 50);
            },

            subscribeToChannel(orderId) {
                if (!window.Echo) return;

                const newChannelName = `order.${orderId}.chat`;

                if (this.currentChannelName && this.currentChannelName !== newChannelName) {
                    window.Echo.leave(this.currentChannelName);
                }

                this.currentChannelName = newChannelName;

                window.Echo.private(newChannelName)
                    .listen('.message.sent', (e) => {
                        // Instantly update sidebar snippet without network lag
                        const conv = this.conversations.find(c => c.order_id === e.order_id);
                        if (conv) {
                            conv.last_message = e.message;
                            conv.last_at = e.created_at;
                        }

                        if (e.order_id !== this.activeOrderId) return;
                        
                        const incoming = { ...e, is_me: e.sender_id === CURRENT_USER_ID };

                        const exists = this.messages.some(m => (m.id && m.id === incoming.id) || (m.temp_id && m.message === incoming.message));
                        if (!exists) {
                            this.messages.push(incoming);
                            this.$nextTick(() => {
                                this.scrollToBottom();
                                lucide.createIcons();
                            });
                        }
                    });
            },

            async send() {
                const text = this.draft.trim();
                if (!text || !this.canSend) return;

                const tempId = 'temp_' + Date.now();
                const now = new Date();
                const timeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');

                // ⚡ 1. INSTANT 0ms Optimistic UI update for Chat Bubble
                const optimisticMsg = {
                    temp_id: tempId,
                    message: text,
                    is_me: true,
                    created_at: timeStr,
                    sending: true
                };

                this.messages.push(optimisticMsg);
                this.draft = '';

                // ⚡ 2. Instant Local update for left conversation snippet
                const activeConv = this.conversations.find(c => c.order_id === this.activeOrderId);
                if (activeConv) {
                    activeConv.last_message = text;
                    activeConv.last_at = timeStr;
                }

                this.$nextTick(() => {
                    this.scrollToBottom();
                    lucide.createIcons();
                });

                try {
                    const res = await fetch(`${CHAT_BASE_URL}/${this.activeOrderId}/messages`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ message: text }),
                    });
                    const data = await res.json();

                    if (res.ok && data.sent) {
                        const idx = this.messages.findIndex(m => m.temp_id === tempId);
                        if (idx !== -1) {
                            this.messages.splice(idx, 1, data.sent);
                        }
                    } else {
                        optimisticMsg.sending = false;
                        alert(data.message || 'Could not send message.');
                    }
                } catch (e) {
                    optimisticMsg.sending = false;
                    alert('Network error while sending message.');
                }
            },

            scrollToBottom() {
                const el = this.$refs.messageList;
                if (el) el.scrollTop = el.scrollHeight;
            },
        };
    }

    document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
</body>
</html>