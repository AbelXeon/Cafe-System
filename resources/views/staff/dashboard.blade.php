<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CraveDash | Kitchen & Staff Orders Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@0.474.0/dist/umd/lucide.js" defer></script>

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
<body class="bg-[#0f0e13] text-stone-200 h-screen overflow-hidden selection:bg-[#b08d57] selection:text-[#0f0e13]" x-data="staffApp()" x-init="init()">
    
    <!-- Notification Toast -->
    <div x-show="toast.visible"
         x-cloak
         x-transition:enter="transform ease-out duration-300 transition"
         x-transition:enter-start="translate-y-6 opacity-0 scale-90 sm:translate-x-8 sm:translate-y-0"
         x-transition:enter-end="translate-y-0 opacity-100 scale-100 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 sm:translate-x-0"
         x-transition:leave-end="opacity-0 scale-90 sm:translate-x-8"
         class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-50 max-w-sm w-[calc(100%-2rem)] sm:w-96 bg-[#14131a]/95 border border-[#b08d57]/60 rounded-2xl shadow-2xl overflow-hidden backdrop-blur-xl">
         
        <div class="p-4 flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-[#b08d57]/15 border border-[#b08d57]/30 flex items-center justify-center text-[#b08d57] shrink-0 shadow-lg shadow-[#b08d57]/20">
                <i data-lucide="bell" class="w-5 h-5"></i>
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
                    <i data-lucide="chef-hat" class="w-4 h-4 stroke-[2.5]"></i>
                </div>
                <span class="text-base font-black tracking-tight text-white">Kitchen<span class="text-[#b08d57]">Hub</span></span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
            <span class="text-xs font-bold text-stone-300">Live</span>
        </div>
    </header>

    <div class="flex h-[calc(100vh-57px)] lg:h-full w-full relative">

        @include('staff.partials.sidebar')

        @include('staff.partials.section-orders')

        @include('staff.partials.section-stock')

        @include('staff.partials.section-profile')

    </div>

    <script>
        const INITIAL_ORDERS = @json($orders);
        const INITIAL_PRODUCTS = @json($products ?? []);
        const INITIAL_EXTRAS = @json($extras ?? []);
        const AUTH_USER = @json(['name' => Auth::user()->fullname ?? Auth::user()->name ?? '', 'email' => Auth::user()->email ?? '', 'phone' => Auth::user()->phone ?? '']);

        const STATUS_UPDATE_URL = "{{ url('/staff/orders') }}";
        const LIVE_ORDERS_URL = "{{ route('staff.orders.live') }}";
        const PROFILE_UPDATE_URL = "{{ route('staff.profile.update') }}";
        const PASSWORD_UPDATE_URL = "{{ route('staff.password.update') }}";
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

        function staffApp() {
            return {
                mobileNavOpen: false,
                currentView: 'orders', 
                orders: INITIAL_ORDERS || [],
                activeTab: 'all',
                updatingId: null,
                soundEnabled: true,
                stockSearch: '',
                stockFilter: 'all',
                stockTogglingId: null,

                savingProfile: false,
                editingProfile: false,
                _profileSnapshot: null,
                profileError: null,
                profileForm: {
                    name: AUTH_USER.name,
                    email: AUTH_USER.email,
                    phone: AUTH_USER.phone
                },

                // Password form states
                savingPassword: false,
                passwordError: null,
                passwordForm: {
                    current_password: '',
                    password: '',
                    password_confirmation: ''
                },

                products: (INITIAL_PRODUCTS || []).map(p => ({
                    id: p.id,
                    name: p.name,
                    type: 'Product',
                    category_name: p.category ? p.category.name : 'General',
                    price: p.price,
                    description: p.description,
                    image: p.image ? (p.image.startsWith('http') ? p.image : `/storage/${p.image}`) : null,
                    is_available: Boolean(p.is_available)
                })),
                
                extras: (INITIAL_EXTRAS || []).map(e => ({
                    id: e.id,
                    name: e.name,
                    type: 'Extra',
                    category_name: 'Modifier / Extra',
                    price: e.price,
                    description: 'Add-on Modifier',
                    image: null,
                    is_available: Boolean(e.is_available)
                })),

                counts: {
                    pending: 0,
                    preparing: 0,
                    ready: 0,
                    completed: 0
                },
                toast: {
                    visible: false,
                    title: '',
                    message: ''
                },

                init() {
                    this.updateCounts();
                    
                    setInterval(() => {
                        this.fetchLiveOrders();
                    }, 6000);

                    setTimeout(() => lucide.createIcons(), 60);
                },

                enableEdit() {
                    this._profileSnapshot = { ...this.profileForm };
                    this.editingProfile = true;
                    this.$nextTick(() => lucide.createIcons());
                },

                cancelEdit() {
                    this.profileForm = { ...this._profileSnapshot };
                    this.profileError = null;
                    this.editingProfile = false;
                    this.$nextTick(() => lucide.createIcons());
                },

                get outOfStockCount() {
                    const outProds = this.products.filter(p => !p.is_available).length;
                    const outExtras = this.extras.filter(e => !e.is_available).length;
                    return outProds + outExtras;
                },

                get allStockItems() {
                    return [...this.products, ...this.extras];
                },

                get filteredStockItems() {
                    let items = this.allStockItems;

                    if (this.stockFilter === 'products') {
                        items = items.filter(i => i.type === 'Product');
                    } else if (this.stockFilter === 'extras') {
                        items = items.filter(i => i.type === 'Extra');
                    } else if (this.stockFilter === 'out_of_stock') {
                        items = items.filter(i => !i.is_available);
                    }

                    if (this.stockSearch.trim()) {
                        const q = this.stockSearch.toLowerCase().trim();
                        items = items.filter(i => 
                            i.name.toLowerCase().includes(q) || 
                            (i.category_name && i.category_name.toLowerCase().includes(q))
                        );
                    }

                    return items;
                },

                get filteredOrders() {
                    if (this.activeTab === 'all') {
                        return this.orders;
                    }
                    return this.orders.filter(o => o.status === this.activeTab);
                },

                async toggleItemAvailability(item) {
                    const uniqueKey = item.type + '-' + item.id;
                    this.stockTogglingId = uniqueKey;

                    const url = item.type === 'Product' 
                        ? `/staff/products/${item.id}/toggle-availability`
                        : `/staff/extras/${item.id}/toggle-availability`;

                    try {
                        const res = await fetch(url, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await res.json();
                        if (res.ok) {
                            item.is_available = data.is_available;
                            this.showToast(
                                item.is_available ? 'Item Back In Stock' : 'Item 86\'d (Out of Stock)',
                                data.message
                            );
                        } else {
                            alert(data.message || 'Failed to update stock status.');
                        }
                    } catch (e) {
                        alert('Network error updating item stock status.');
                    } finally {
                        this.stockTogglingId = null;
                        this.$nextTick(() => lucide.createIcons());
                    }
                },

                async saveProfile() {
                    this.savingProfile = true;
                    this.profileError = null;

                    try {
                        const res = await fetch(PROFILE_UPDATE_URL, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.profileForm)
                        });

                        const data = await res.json();
                        if (res.ok) {
                            this.editingProfile = false;
                            this.showToast('Profile Updated', data.message || 'Your personal profile has been updated.');
                        } else {
                            this.profileError = data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : 'Failed to update profile.');
                        }
                    } catch (err) {
                        this.profileError = 'A network error occurred while updating your profile.';
                    } finally {
                        this.savingProfile = false;
                        this.$nextTick(() => lucide.createIcons());
                    }
                },

                async updatePassword() {
                    this.savingPassword = true;
                    this.passwordError = null;

                    try {
                        const res = await fetch(PASSWORD_UPDATE_URL, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.passwordForm)
                        });

                        const data = await res.json();
                        if (res.ok) {
                            this.showToast('Password Changed', data.message || 'Your password was updated successfully.');
                            this.passwordForm.current_password = '';
                            this.passwordForm.password = '';
                            this.passwordForm.password_confirmation = '';
                        } else {
                            this.passwordError = data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : 'Failed to change password.');
                        }
                    } catch (err) {
                        this.passwordError = 'A network error occurred while updating your password.';
                    } finally {
                        this.savingPassword = false;
                        this.$nextTick(() => lucide.createIcons());
                    }
                },

                getOrderImage(order) {
                    if (!order) return null;
                    const rawImg = order.image || order.image_url || order.photo || order.thumbnail || order.food_image;
                    if (rawImg && typeof rawImg === 'string' && rawImg.trim()) {
                        return (rawImg.startsWith('http://') || rawImg.startsWith('https://') || rawImg.startsWith('/') || rawImg.startsWith('data:')) 
                            ? rawImg 
                            : `/storage/${rawImg}`;
                    }
                    
                    if (order.items && Array.isArray(order.items) && order.items.length > 0) {
                        const itemWithImg = order.items.find(i => i.image || i.image_url || i.item_image || i.photo);
                        if (itemWithImg) {
                            const itemSrc = itemWithImg.image || itemWithImg.image_url || itemWithImg.item_image || itemWithImg.photo;
                            if (itemSrc && typeof itemSrc === 'string' && itemSrc.trim()) {
                                return (itemSrc.startsWith('http://') || itemSrc.startsWith('https://') || itemSrc.startsWith('/') || itemSrc.startsWith('data:')) 
                                    ? itemSrc 
                                    : `/storage/${itemSrc}`;
                            }
                        }
                    }
                    return null;
                },

                updateCounts() {
                    this.counts = {
                        pending: this.orders.filter(o => o.status === 'pending').length,
                        preparing: this.orders.filter(o => o.status === 'preparing').length,
                        ready: this.orders.filter(o => o.status === 'ready').length,
                        completed: this.orders.filter(o => o.status === 'completed').length,
                    };
                },

                getItemDetails(item) {
                    if (!item) return { hasContent: false, extras: [], notes: [] };

                    let extras = [];
                    let notes = [];

                    const processEntry = (entry) => {
                        if (!entry) return;
                        const str = String(entry).trim();
                        if (!str) return;

                        if (/^(notes?|instructions?|special note):\s*/i.test(str)) {
                            const cleaned = str.replace(/^(notes?|instructions?|special note):\s*/i, '').trim();
                            if (cleaned) notes.push(cleaned);
                        } else if (/^(extras?|add\-?ons?):\s*/i.test(str)) {
                            const cleaned = str.replace(/^(extras?|add\-?ons?):\s*/i, '').trim();
                            cleaned.split(/[,;|]+/).forEach(part => {
                                if (part.trim()) extras.push(part.trim());
                            });
                        } else if (/^[+•\-\*]\s*/.test(str)) {
                            extras.push(str.replace(/^[+•\-\*]\s*/, '').trim());
                        } else {
                            extras.push(str);
                        }
                    };

                    if (item.extras) {
                        if (Array.isArray(item.extras)) {
                            item.extras.forEach(e => {
                                const val = typeof e === 'object' ? (e.name || e.title || JSON.stringify(e)) : e;
                                if (val) processEntry(val);
                            });
                        } else if (typeof item.extras === 'string' && item.extras.trim()) {
                            item.extras.split(/[\r\n,]+/).forEach(e => {
                                if (e.trim()) processEntry(e);
                            });
                        }
                    }

                    const noteRaw = item.special_note || item.note || item.instructions || item.special_instructions || '';
                    if (noteRaw && typeof noteRaw === 'string' && noteRaw.trim()) {
                        const lines = noteRaw.split(/\r?\n/).map(l => l.trim()).filter(Boolean);
                        
                        lines.forEach(line => {
                            if (/^(notes?|instructions?|special note):\s*/i.test(line)) {
                                const cleaned = line.replace(/^(notes?|instructions?|special note):\s*/i, '').trim();
                                if (cleaned) notes.push(cleaned);
                            } else if (/^(extras?|add\-?ons?):\s*/i.test(line)) {
                                const cleaned = line.replace(/^(extras?|add\-?ons?):\s*/i, '').trim();
                                cleaned.split(/[,;|]+/).forEach(part => {
                                    if (part.trim()) extras.push(part.trim());
                                });
                            } else if (/^[+•\-\*]\s*/.test(line)) {
                                extras.push(line.replace(/^[+•\-\*]\s*/, '').trim());
                            } else {
                                notes.push(line);
                            }
                        });
                    }

                    return {
                        hasContent: extras.length > 0 || notes.length > 0,
                        extras: [...new Set(extras)],
                        notes: [...new Set(notes)]
                    };
                },

                async fetchLiveOrders() {
                    try {
                        const res = await fetch(LIVE_ORDERS_URL, {
                            headers: { 'Accept': 'application/json' }
                        });
                        if (res.ok) {
                            const data = await res.json();
                            const previousPendingCount = this.counts.pending;
                            this.orders = data.orders;
                            this.counts = data.counts;

                            if (data.counts.pending > previousPendingCount && this.soundEnabled) {
                                this.playNotificationSound();
                                this.showToast('New Order Incoming!', `Order #${data.orders[0]?.id} just arrived in the kitchen.`);
                            }

                            this.$nextTick(() => lucide.createIcons());
                        }
                    } catch (e) {
                        console.error('Failed fetching live staff orders:', e);
                    }
                },

                async updateStatus(order, newStatus) {
                    this.updatingId = order.id;
                    try {
                        const res = await fetch(`${STATUS_UPDATE_URL}/${order.id}/status`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ status: newStatus })
                        });

                        const data = await res.json();
                        if (res.ok) {
                            order.status = newStatus;
                            this.updateCounts();
                            this.showToast('Status Updated', `Order #${order.id} is now ${newStatus}.`);
                        } else {
                            alert(data.message || 'Could not update order status.');
                        }
                    } catch (e) {
                        alert('Network error while updating status.');
                    } finally {
                        this.updatingId = null;
                        this.$nextTick(() => lucide.createIcons());
                    }
                },

                showToast(title, message) {
                    this.toast.title = title;
                    this.toast.message = message;
                    this.toast.visible = true;
                    this.$nextTick(() => lucide.createIcons());
                    setTimeout(() => {
                        this.toast.visible = false;
                    }, 4000);
                },

                toggleSound() {
                    this.soundEnabled = !this.soundEnabled;
                },

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
                        osc.connect(gain);
                        gain.connect(ctx.destination);
                        osc.start();
                        osc.stop(ctx.currentTime + 0.4);
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