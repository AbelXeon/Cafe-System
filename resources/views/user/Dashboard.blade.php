<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CraveDash | Dashboard & Menu</title>

    <!-- Laravel Vite Bundled Tailwind CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Leaflet Maps CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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
        
        /* High-speed Dark-Mode Map Filter */
        .leaflet-container { background: #0f0e13 !important; }
        .leaflet-tile {
            filter: brightness(0.6) invert(1) contrast(3) hue-rotate(200deg) saturate(0.3) brightness(0.75) !important;
            will-change: transform;
        }
        .leaflet-control-attribution { display: none !important; }
        .custom-gold-marker { cursor: grab; }
        .custom-gold-marker:active { cursor: grabbing; }

        @keyframes pulse-gold {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.08); opacity: 0.8; }
        }
        .pulse-gold { animation: pulse-gold 2s infinite ease-in-out; }
    </style>
</head>
<body class="bg-[#0f0e13] text-stone-200 h-screen overflow-hidden selection:bg-[#b08d57] selection:text-[#0f0e13]" x-data="{ mobileNavOpen: false, mobileCartOpen: false }">

<!-- Animated Success Toast Notification -->
<div x-show="$store.toast.visible"
     x-cloak
     x-transition:enter="transform ease-out duration-300 transition"
     x-transition:enter-start="translate-y-6 opacity-0 scale-90 sm:translate-x-8 sm:translate-y-0"
     x-transition:enter-end="translate-y-0 opacity-100 scale-100 sm:translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 scale-100 sm:translate-x-0"
     x-transition:leave-end="opacity-0 scale-90 sm:translate-x-8"
     class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-50 max-w-sm w-[calc(100%-2rem)] sm:w-96 bg-[#14131a]/95 border border-[#b08d57]/60 rounded-2xl shadow-2xl overflow-hidden backdrop-blur-xl">
     
    <div class="p-4 flex items-start gap-3.5 relative">
        <div class="w-10 h-10 rounded-xl bg-[#b08d57]/15 border border-[#b08d57]/30 flex items-center justify-center text-[#b08d57] shrink-0 shadow-lg shadow-[#b08d57]/20 relative">
            <span class="absolute -top-1 -right-1 flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#b08d57] opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-[#b08d57]"></span>
            </span>
            <i data-lucide="sparkles" class="w-5 h-5"></i>
        </div>

        <div class="flex-1 min-w-0 pr-1">
            <div class="flex items-center gap-2">
                <h4 class="text-sm font-bold text-white tracking-tight" x-text="$store.toast.title"></h4>
                <span x-show="$store.toast.orderId" class="text-[10px] font-black bg-[#b08d57] text-[#0f0e13] px-2 py-0.5 rounded-full" x-text="$store.toast.orderId"></span>
            </div>
            <p class="text-xs text-stone-400 mt-1 leading-relaxed" x-text="$store.toast.message"></p>
        </div>

        <button @click="$store.toast.hide()" class="text-stone-500 hover:text-white transition p-1 rounded-lg hover:bg-[#1e1c25]">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>

    <div class="w-full bg-[#1e1c25] h-1 overflow-hidden">
        <div class="bg-gradient-to-r from-[#b08d57] to-[#e4cb9d] h-full transition-all duration-75 ease-linear"
             :style="`width: ${$store.toast.progress}%`"></div>
    </div>
</div>

<!-- Mobile Top Navigation Bar -->
<header class="lg:hidden bg-[#0f0e13] border-b border-[#1e1c25] px-4 py-3 flex items-center justify-between z-30 shrink-0">
    <div class="flex items-center gap-3">
        <button @click="mobileNavOpen = true; $nextTick(() => lucide.createIcons())" class="p-2 -ml-2 rounded-xl text-stone-400 hover:text-white hover:bg-[#1e1c25] transition" aria-label="Open Navigation">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>

        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-[#b08d57] flex items-center justify-center text-[#0f0e13]">
                <i data-lucide="utensils" class="w-4 h-4 stroke-[2.5]"></i>
            </div>
            <span class="text-base font-black tracking-tight text-white">Crave<span class="text-[#b08d57]">Dash</span></span>
        </div>
    </div>

    <button @click="mobileCartOpen = true; $nextTick(() => lucide.createIcons())" class="relative p-2 rounded-xl text-stone-300 hover:text-white hover:bg-[#1e1c25] transition flex items-center gap-1.5" aria-label="Open Cart">
        <i data-lucide="shopping-bag" class="w-5 h-5 text-[#b08d57]"></i>
        <span x-show="$store.cart.count > 0" class="bg-[#b08d57] text-[#0f0e13] text-[11px] font-black rounded-full px-1.5 py-0.2" x-text="$store.cart.count"></span>
    </button>
</header>

<div class="flex h-[calc(100vh-57px)] lg:h-full w-full relative">

    <!-- 1. Include Sidebar -->
    @include('user.partials.sidebar')

    <!-- 2. Include Main Sections -->
    @include('user.partials.section-menu')
    @include('user.partials.section-orders')
    @include('user.partials.section-chat')
    @include('user.partials.section-address')
    @include('user.partials.section-profile')

</div>

<script>
    window.MENU_DATA = @json($menuData);
    window.ADDRESSES_DATA = @json($addresses);
    window.INITIAL_ORDERS = @json($userOrders ?? []);
    
    const ADDRESS_STORE_URL = "{{ route('user.addresses.store') }}";
    const ADDRESS_DESTROY_BASE = "{{ url('/user/addresses') }}";
    const ORDER_STORE_URL = "{{ route('user.orders.store') }}";
    const ORDERS_LIVE_URL = "{{ route('user.orders.live') }}";
    const CHAT_LIST_URL = "{{ route('user.chats.index') }}";
    const CHAT_BASE_URL = "{{ url('/user/chats') }}";
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

    document.addEventListener('alpine:init', () => {
        Alpine.store('toast', {
            visible: false,
            title: '',
            message: '',
            orderId: '',
            progress: 100,
            timer: null,
            interval: null,

            trigger(title, message, orderId) {
                this.title = title || 'Success!';
                this.message = message || 'Action completed successfully.';
                this.orderId = orderId ? '#' + orderId : '';
                this.progress = 100;
                this.visible = true;

                clearInterval(this.interval);
                clearTimeout(this.timer);

                const duration = 4500;
                const step = 50;
                const stepPercent = 100 / (duration / step);

                this.interval = setInterval(() => {
                    this.progress -= stepPercent;
                    if (this.progress <= 0) {
                        clearInterval(this.interval);
                    }
                }, step);

                this.timer = setTimeout(() => {
                    this.hide();
                }, duration);

                setTimeout(() => lucide.createIcons(), 50);
            },

            hide() {
                this.visible = false;
                clearInterval(this.interval);
                clearTimeout(this.timer);
            }
        });

        Alpine.store('cart', {
            items: JSON.parse(localStorage.getItem('cafe_cart') || '[]'),

            add(product, qty, note, customPrice, extrasText) {
                const effectivePrice = customPrice !== undefined ? customPrice : product.price;
                const existing = this.items.find(i => 
                    i.id === product.id && 
                    i.note === note && 
                    (i.extrasText || '') === (extrasText || '') &&
                    Math.abs(i.price - effectivePrice) < 0.001
                );

                if (existing) {
                    existing.qty += qty;
                } else {
                    this.items.push({ 
                        id: product.id, 
                        name: product.name, 
                        price: effectivePrice, 
                        basePrice: product.price,
                        image: product.image, 
                        qty, 
                        note: note || '',
                        extrasText: extrasText || ''
                    });
                }
                this.persist();
                this.refreshIcons();
            },
            increment(item) {
                item.qty++;
                this.persist();
            },
            decrement(item) {
                item.qty--;
                if (item.qty <= 0) {
                    this.remove(item);
                } else {
                    this.persist();
                }
            },
            remove(item) {
                this.items = this.items.filter(i => i !== item);
                this.persist();
                this.refreshIcons();
            },
            clear() {
                this.items = [];
                this.persist();
                this.refreshIcons();
            },
            persist() {
                localStorage.setItem('cafe_cart', JSON.stringify(this.items));
            },
            refreshIcons() {
                setTimeout(() => lucide.createIcons(), 50);
            },
            get total() {
                return this.items.reduce((sum, i) => sum + i.price * i.qty, 0);
            },
            get count() {
                return this.items.reduce((sum, i) => sum + i.qty, 0);
            },
        });

        Alpine.store('orders', {
            list: window.INITIAL_ORDERS || [],

            get active() {
                return this.list.filter(o => o.status_step >= 1 && o.status_step < 5);
            },
            get completed() {
                return this.list.filter(o => o.status_step === 5 || o.status_step === 0);
            },
            get activeCount() {
                return this.active.length;
            },
            addOrder(order) {
                this.list.unshift(order);
                setTimeout(() => lucide.createIcons(), 50);
            },
            setList(newList) {
                this.list = newList;
                setTimeout(() => lucide.createIcons(), 50);
            }
        });

        Alpine.store('addresses', {
            list: window.ADDRESSES_DATA || [],
            selectedId: null,

            async add(payload) {
                const res = await fetch(ADDRESS_STORE_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();
                if (res.ok) {
                    this.list.push(data.location);
                    setTimeout(() => lucide.createIcons(), 50);
                }
                return { ok: res.ok, data };
            },

            async remove(id) {
                const res = await fetch(`${ADDRESS_DESTROY_BASE}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                    },
                });
                if (res.ok) {
                    this.list = this.list.filter(l => l.id !== id);
                    if (this.selectedId === id) this.selectedId = null;
                    setTimeout(() => lucide.createIcons(), 50);
                }
            },
        });
    });

    // Navigation switching
    const sideLinks = document.querySelectorAll('.side-link');
    const sections = document.querySelectorAll('.page-section');

    function showSection(target) {
        sections.forEach(s => s.classList.add('hidden'));
        const targetSec = document.getElementById('section-' + target);
        if (targetSec) targetSec.classList.remove('hidden');
        
        sideLinks.forEach(l => l.classList.remove('active'));
        document.querySelectorAll(`.side-link[data-target="${target}"]`).forEach(l => l.classList.add('active'));
        
        if (target === 'address') {
            setTimeout(() => {
                window.dispatchEvent(new Event('resize'));
            }, 80);
        }
        setTimeout(() => lucide.createIcons(), 50);
    }

    sideLinks.forEach(link => {
        link.addEventListener('click', () => showSection(link.dataset.target));
    });
    showSection('menu');

    function ordersApp() {
        return {
            refreshing: false,
            pollTimer: null,

            init() {
                this.pollTimer = setInterval(() => {
                    this.fetchLiveOrders(false);
                }, 4000);
            },

            get activeOrders() {
                return Alpine.store('orders').active;
            },

            get completedOrders() {
                return Alpine.store('orders').completed;
            },

            async refreshOrders() {
                this.refreshing = true;
                await this.fetchLiveOrders(true);
                this.refreshing = false;
            },

            async fetchLiveOrders(showToast = false) {
                try {
                    const res = await fetch(ORDERS_LIVE_URL, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        }
                    });
                    if (res.ok) {
                        const data = await res.json();
                        if (data && data.orders) {
                            Alpine.store('orders').setList(data.orders);
                        }
                    }
                } catch (e) {
                    console.error('Failed to poll live orders', e);
                }
            },

            getStatusBadge(status) {
                switch(status?.toLowerCase()) {
                    case 'pending': return 'Order Placed';
                    case 'preparing': 
                    case 'accepted': 
                    case 'in_kitchen': return 'Kitchen Cooking';
                    case 'ready': return 'Ready for Courier';
                    case 'out_for_delivery': return 'Out for Delivery';
                    case 'delivered': return 'Delivered';
                    default: return status || 'Processing';
                }
            }
        };
    }

    function menuApp() {
        return {
            categories: [],
            activeCategory: 'All',
            products: [],
            extras: [],
            modalProduct: null,
            modalQty: 1,
            modalNote: '',
            selectedExtras: {},
            showConfirm: false,
            placingOrder: false,
            orderError: '',

            init() {
                this.categories = window.MENU_DATA.categories || [];
                this.products = window.MENU_DATA.products || [];
                this.extras = window.MENU_DATA.extras || [];

                if (this.categories.includes('All')) {
                    this.activeCategory = 'All';
                } else if (this.categories.length > 0) {
                    this.activeCategory = this.categories[0];
                }

                document.addEventListener('open-confirm', () => {
                    this.showConfirm = true;
                    setTimeout(() => lucide.createIcons(), 50);
                });
                this.$watch('activeCategory', () => {
                    setTimeout(() => lucide.createIcons(), 50);
                });
            },

            get visibleProducts() {
                if (this.activeCategory === 'All') {
                    return this.products;
                }
                return this.products.filter(p => p.category === this.activeCategory);
            },

            openModal(product) {
                this.modalProduct = product;
                this.modalQty = 1;
                this.modalNote = '';
                this.selectedExtras = {};
                this.extras.forEach(e => {
                    this.selectedExtras[e.id] = 0;
                });
                setTimeout(() => lucide.createIcons(), 50);
            },
            closeModal() {
                this.modalProduct = null;
            },

            incrementExtra(id) {
                this.selectedExtras[id] = (this.selectedExtras[id] || 0) + 1;
            },
            decrementExtra(id) {
                if (this.selectedExtras[id] && this.selectedExtras[id] > 0) {
                    this.selectedExtras[id]--;
                }
            },
            getExtraQty(id) {
                return this.selectedExtras[id] || 0;
            },

            get modalExtrasUnitTotal() {
                return this.extras.reduce((sum, extra) => {
                    return sum + (extra.price * (this.selectedExtras[extra.id] || 0));
                }, 0);
            },

            get modalTotalPrice() {
                if (!this.modalProduct) return 0;
                return (this.modalProduct.price + this.modalExtrasUnitTotal) * this.modalQty;
            },

            addToCartFromModal() {
                const chosenExtras = this.extras.filter(e => (this.selectedExtras[e.id] || 0) > 0);
                let extrasText = '';
                if (chosenExtras.length > 0) {
                    extrasText = 'Extras: ' + chosenExtras.map(e => `${e.name} (x${this.selectedExtras[e.id]})`).join(', ');
                }

                const calculatedUnitPrice = this.modalProduct.price + this.modalExtrasUnitTotal;

                this.$store.cart.add(
                    this.modalProduct,
                    this.modalQty,
                    this.modalNote,
                    calculatedUnitPrice,
                    extrasText
                );
                this.closeModal();
            },

            async placeOrder() {
                this.placingOrder = true;
                this.orderError = '';
                try {
                    const res = await fetch(ORDER_STORE_URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            items: this.$store.cart.items.map(c => {
                                let noteCombined = c.note || '';
                                if (c.extrasText) {
                                    noteCombined = noteCombined ? `${c.extrasText} | Note: ${noteCombined}` : c.extrasText;
                                }
                                return {
                                    product_id: c.id,
                                    quantity: c.qty,
                                    special_note: noteCombined,
                                    custom_price: c.price,
                                };
                            }),
                            saved_location_id: this.$store.addresses.selectedId,
                        }),
                    });
                    const data = await res.json();

                    if (!res.ok) {
                        this.orderError = data.message || 'Could not place order.';
                        this.placingOrder = false;
                        return;
                    }

                    if (data.order) {
                        Alpine.store('orders').addOrder(data.order);
                    }

                    this.$store.cart.clear();
                    this.showConfirm = false;
                    this.placingOrder = false;
                    
                    this.$store.toast.trigger(
                        'Order Placed Successfully!',
                        'Your meal is confirmed and has been sent to our kitchen team.',
                        data.order.id
                    );

                    setTimeout(() => {
                        showSection('orders');
                    }, 400);

                } catch (e) {
                    this.orderError = 'Network error. Try again.';
                    this.placingOrder = false;
                }
            },
        };
    }

    /**
     * Ultra-Fast Real-Time Chat (Instant Optimistic UI + WebSockets)
     */
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
                this.loadConversations();
                this.pollTimer = setInterval(() => this.loadConversations(), 10000);
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
                        if (e.order_id !== this.activeOrderId) return;
                        
                        const exists = this.messages.some(m => m.id && m.id === e.id);
                        if (!exists) {
                            this.messages.push(e);
                            this.$nextTick(() => {
                                this.scrollToBottom();
                                lucide.createIcons();
                            });
                        }
                        this.loadConversations();
                    });
            },

            async send() {
                const text = this.draft.trim();
                if (!text || !this.canSend) return;

                const tempId = 'temp_' + Date.now();
                const now = new Date();
                const timeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');

                // ⚡ INSTANT 0ms Optimistic UI update
                const optimisticMsg = {
                    temp_id: tempId,
                    message: text,
                    is_me: true,
                    created_at: timeStr,
                    sending: true
                };

                this.messages.push(optimisticMsg);
                this.draft = '';
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
                        this.loadConversations();
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

    function addressApp() {
        return {
            form: { name: '', address: '', latitude: null, longitude: null },
            capturing: false,
            saving: false,
            resolvingAddress: false,
            accuracy: null,
            gpsStatusText: '',
            error: '',
            detectMap: null,
            detectMarker: null,
            accuracyCircle: null,

            init() {},

            initDetectMap(lat, lng, accuracy = null) {
                this.$nextTick(() => {
                    const container = document.getElementById('detect-preview-map');
                    if (!container) return;

                    const goldIcon = L.divIcon({
                        className: 'custom-gold-marker',
                        html: `<div style="background-color:#b08d57; width:20px; height:20px; border-radius:50%; border:3px solid #0f0e13; box-shadow:0 0 18px rgba(176,141,87,1); display:flex; align-items:center; justify-content:center;"><div style="width:6px; height:6px; background-color:#ffffff; border-radius:50%;"></div></div>`,
                        iconSize: [20, 20],
                        iconAnchor: [10, 10]
                    });

                    if (!this.detectMap) {
                        this.detectMap = L.map(container, {
                            zoomControl: true,
                            attributionControl: false,
                            fadeAnimation: false,
                            preferCanvas: true
                        }).setView([lat, lng], 18);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            subdomains: ['a', 'b', 'c'],
                            maxZoom: 19
                        }).addTo(this.detectMap);

                        if (accuracy) {
                            this.accuracyCircle = L.circle([lat, lng], {
                                radius: accuracy,
                                color: '#b08d57',
                                fillColor: '#b08d57',
                                fillOpacity: 0.15,
                                weight: 1.5,
                                dashArray: '4, 4'
                            }).addTo(this.detectMap);
                        }

                        this.detectMarker = L.marker([lat, lng], {
                            icon: goldIcon,
                            draggable: true,
                            autoPan: true
                        }).addTo(this.detectMap);

                        this.detectMarker.on('dragend', (e) => {
                            const pos = e.target.getLatLng();
                            if (this.accuracyCircle) {
                                this.detectMap.removeLayer(this.accuracyCircle);
                                this.accuracyCircle = null;
                            }
                            this.accuracy = null;
                            this.updateLocationByCoordinates(pos.lat, pos.lng, false);
                        });

                        this.detectMap.on('click', (e) => {
                            this.detectMarker.setLatLng(e.latlng);
                            if (this.accuracyCircle) {
                                this.detectMap.removeLayer(this.accuracyCircle);
                                this.accuracyCircle = null;
                            }
                            this.accuracy = null;
                            this.updateLocationByCoordinates(e.latlng.lat, e.latlng.lng, false);
                        });
                    } else {
                        this.detectMap.setView([lat, lng], 18, { animate: true });
                        this.detectMarker.setLatLng([lat, lng]);

                        if (accuracy) {
                            if (this.accuracyCircle) {
                                this.accuracyCircle.setLatLng([lat, lng]);
                                this.accuracyCircle.setRadius(accuracy);
                            } else {
                                this.accuracyCircle = L.circle([lat, lng], {
                                    radius: accuracy,
                                    color: '#b08d57',
                                    fillColor: '#b08d57',
                                    fillOpacity: 0.15,
                                    weight: 1.5,
                                    dashArray: '4, 4'
                                }).addTo(this.detectMap);
                            }
                        }

                        this.detectMap.invalidateSize();
                    }
                });
            },

            async updateLocationByCoordinates(lat, lng, centerMap = true) {
                this.form.latitude = lat;
                this.form.longitude = lng;
                this.resolvingAddress = true;
                this.error = '';

                if (centerMap && this.detectMap) {
                    this.detectMap.setView([lat, lng], 18);
                    this.detectMarker.setLatLng([lat, lng]);
                }

                try {
                    const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    
                    if (data && data.address) {
                        const a = data.address;
                        const road = a.road || a.pedestrian || a.street || a.neighbourhood || '';
                        const houseNumber = a.house_number || '';
                        const suburb = a.suburb || a.district || a.city_district || '';
                        const city = a.city || a.town || a.village || a.county || '';
                        
                        const parts = [];
                        if (houseNumber && road) {
                            parts.push(`${houseNumber} ${road}`);
                        } else if (road) {
                            parts.push(road);
                        }
                        if (suburb && suburb !== road) parts.push(suburb);
                        if (city && city !== suburb) parts.push(city);

                        this.form.address = parts.length > 0 ? parts.join(', ') : (data.display_name.split(',').slice(0, 3).join(', ').trim());
                    } else if (data && data.display_name) {
                        this.form.address = data.display_name.split(',').slice(0, 3).join(', ').trim();
                    } else {
                        this.form.address = `GPS (${Number(lat).toFixed(6)}, ${Number(lng).toFixed(6)})`;
                    }
                } catch (e) {
                    if (!this.form.address) {
                        this.form.address = `GPS (${Number(lat).toFixed(6)}, ${Number(lng).toFixed(6)})`;
                    }
                } finally {
                    this.resolvingAddress = false;
                    setTimeout(() => lucide.createIcons(), 50);
                }
            },

            openManualPicker() {
                if (this.form.latitude && this.form.longitude) {
                    this.initDetectMap(this.form.latitude, this.form.longitude);
                    return;
                }

                if (this.$store.addresses.list.length > 0 && this.$store.addresses.list[0].latitude) {
                    const lat = parseFloat(this.$store.addresses.list[0].latitude);
                    const lng = parseFloat(this.$store.addresses.list[0].longitude);
                    this.form.latitude = lat;
                    this.form.longitude = lng;
                    this.initDetectMap(lat, lng);
                    this.updateLocationByCoordinates(lat, lng, false);
                    return;
                }

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (pos) => {
                            const lat = pos.coords.latitude;
                            const lng = pos.coords.longitude;
                            this.form.latitude = lat;
                            this.form.longitude = lng;
                            this.initDetectMap(lat, lng, pos.coords.accuracy);
                            this.updateLocationByCoordinates(lat, lng, false);
                        },
                        () => {
                            const defaultLat = 40.7128;
                            const defaultLng = -74.0060;
                            this.form.latitude = defaultLat;
                            this.form.longitude = defaultLng;
                            this.initDetectMap(defaultLat, defaultLng);
                            this.updateLocationByCoordinates(defaultLat, defaultLng, false);
                        },
                        { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }
                    );
                } else {
                    const defaultLat = 40.7128;
                    const defaultLng = -74.0060;
                    this.form.latitude = defaultLat;
                    this.form.longitude = defaultLng;
                    this.initDetectMap(defaultLat, defaultLng);
                    this.updateLocationByCoordinates(defaultLat, defaultLng, false);
                }
            },

            async useCurrentLocation() {
                if (!navigator.geolocation) {
                    this.error = 'Location is not supported on this browser or device.';
                    return;
                }

                this.capturing = true;
                this.error = '';
                this.gpsStatusText = 'Locking GPS satellites...';

                let bestCoords = null;
                let watchId = null;
                let sampleCount = 0;

                const finishTracking = () => {
                    if (watchId !== null) {
                        navigator.geolocation.clearWatch(watchId);
                        watchId = null;
                    }
                    this.capturing = false;
                    this.gpsStatusText = '';

                    if (bestCoords) {
                        this.accuracy = bestCoords.accuracy;
                        this.initDetectMap(bestCoords.latitude, bestCoords.longitude, bestCoords.accuracy);
                        this.updateLocationByCoordinates(bestCoords.latitude, bestCoords.longitude, true);
                    }
                };

                const fallbackTimeout = setTimeout(() => {
                    finishTracking();
                }, 7500);

                try {
                    watchId = navigator.geolocation.watchPosition(
                        (pos) => {
                            sampleCount++;
                            const acc = pos.coords.accuracy;

                            if (!bestCoords || acc < bestCoords.accuracy) {
                                bestCoords = {
                                    latitude: pos.coords.latitude,
                                    longitude: pos.coords.longitude,
                                    accuracy: acc
                                };
                                this.accuracy = acc;
                                this.gpsStatusText = `Calibrating GPS (±${Math.round(acc)}m)...`;
                                
                                this.initDetectMap(bestCoords.latitude, bestCoords.longitude, acc);
                                this.updateLocationByCoordinates(bestCoords.latitude, bestCoords.longitude, false);
                            }

                            if (acc <= 15 || sampleCount >= 4) {
                                clearTimeout(fallbackTimeout);
                                finishTracking();
                            }
                        },
                        (err) => {
                            clearTimeout(fallbackTimeout);
                            if (watchId !== null) {
                                navigator.geolocation.clearWatch(watchId);
                                watchId = null;
                            }

                            if (bestCoords) {
                                finishTracking();
                                return;
                            }

                            this.capturing = false;
                            this.gpsStatusText = '';
                            if (err.code === 1) {
                                this.error = 'Location access was denied. Please allow location permissions in your browser settings.';
                            } else if (err.code === 2) {
                                this.error = 'Position unavailable. Make sure your device location / GPS is turned on.';
                            } else {
                                this.error = 'Could not acquire exact GPS location: ' + err.message;
                            }
                        },
                        {
                            enableHighAccuracy: true,
                            maximumAge: 0,
                            timeout: 10000
                        }
                    );
                } catch (e) {
                    clearTimeout(fallbackTimeout);
                    this.capturing = false;
                    this.gpsStatusText = '';
                    this.error = 'GPS detection failed: ' + e.message;
                }
            },

            async save() {
                if (!this.form.name || !this.form.name.trim()) {
                    this.error = 'Please provide an address label (e.g. Home, Office).';
                    return;
                }

                let addressText = this.form.address ? this.form.address.trim() : '';
                if (!addressText) {
                    if (this.form.latitude && this.form.longitude) {
                        addressText = `GPS Location (${Number(this.form.latitude).toFixed(6)}, ${Number(this.form.longitude).toFixed(6)})`;
                    } else {
                        addressText = this.form.name.trim();
                    }
                }

                this.saving = true;
                this.error = '';

                const payload = {
                    name: this.form.name.trim(),
                    address: addressText,
                    latitude: this.form.latitude,
                    longitude: this.form.longitude
                };

                const result = await this.$store.addresses.add(payload);
                this.saving = false;

                if (!result.ok) {
                    this.error = result.data.message || 'Could not save address.';
                    return;
                }

                this.form = { name: '', address: '', latitude: null, longitude: null };
                this.accuracy = null;
                if (this.detectMap) {
                    this.detectMap.remove();
                    this.detectMap = null;
                }

                this.$store.toast.trigger(
                    'Address Saved!',
                    'Your address has been saved successfully for quick checkout.'
                );
            },

            async remove(id) {
                await this.$store.addresses.remove(id);
            },

            initSavedMiniMap(el, lat, lng) {
                if (!lat || !lng || el._leaflet_id) return;
                try {
                    const miniMap = L.map(el, {
                        zoomControl: false,
                        attributionControl: false,
                        dragging: false,
                        scrollWheelZoom: false,
                        doubleClickZoom: false,
                        touchZoom: false,
                        fadeAnimation: false,
                        preferCanvas: true
                    }).setView([lat, lng], 16);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        subdomains: ['a', 'b', 'c'],
                        maxZoom: 19
                    }).addTo(miniMap);

                    const goldIcon = L.divIcon({
                        className: 'custom-gold-pin',
                        html: `<div style="background-color:#b08d57; width:12px; height:12px; border-radius:50%; border:2px solid #0f0e13; box-shadow:0 0 10px rgba(176,141,87,1);"></div>`,
                        iconSize: [12, 12],
                        iconAnchor: [6, 6]
                    });

                    L.marker([lat, lng], { icon: goldIcon }).addTo(miniMap);
                } catch (e) {}
            }
        };
    }

    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>

</body>
</html>