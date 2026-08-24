<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-950 text-slate-200 h-screen overflow-hidden">

<div class="flex h-full">

    {{-- LEFT SIDEBAR --}}
    <aside class="w-56 bg-slate-900 border-r border-slate-800 flex flex-col shrink-0">
        <div class="px-6 py-5 border-b border-slate-800">
            <h1 class="text-white font-bold text-lg">Cafe</h1>
        </div>
        <nav class="flex-1 px-3 py-4 space-y-1">
            <button data-target="menu" class="side-link w-full text-left px-3 py-2 rounded-lg text-sm font-medium">Menu</button>
            <button data-target="address" class="side-link w-full text-left px-3 py-2 rounded-lg text-sm font-medium">My Address</button>
        </nav>
        <form method="POST" action="{{ route('logout') }}" class="p-3 border-t border-slate-800">
            @csrf
            <button class="w-full text-left px-3 py-2 rounded-lg text-sm text-red-400 hover:bg-red-500/10">Logout</button>
        </form>
    </aside>

    {{-- MENU SECTION --}}
    <div id="section-menu" class="page-section flex flex-1 min-h-0">

        <main class="flex-1 overflow-y-auto p-8" x-data="menuApp()" x-init="init()">

            {{-- category tabs --}}
            <div class="flex gap-2 mb-6 flex-wrap">
                <template x-for="cat in categories" :key="cat">
                    <button
                        @click="activeCategory = cat"
                        :class="activeCategory === cat ? 'bg-indigo-600 text-white' : 'bg-slate-900 text-slate-300 border border-slate-800'"
                        class="px-4 py-2 rounded-full text-sm font-medium transition"
                        x-text="cat">
                    </button>
                </template>
            </div>

            <h2 class="text-lg font-bold text-white mb-4" x-text="activeCategory + ' menu'"></h2>

            {{-- product grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                <template x-for="product in visibleProducts" :key="product.id">
                    <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden hover:border-indigo-500/50 transition cursor-pointer"
                         @click="openModal(product)">
                        <img :src="product.image" :alt="product.name" class="w-full h-36 object-cover">
                        <div class="p-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-white font-semibold" x-text="product.name"></h3>
                                <span class="text-indigo-400 font-bold text-sm" x-text="'$' + product.price.toFixed(2)"></span>
                            </div>
                            <p class="text-slate-400 text-sm mt-1 line-clamp-2" x-text="product.description"></p>
                            <button
                                @click.stop="$store.cart.add(product, 1, '')"
                                class="w-full mt-3 bg-slate-800 hover:bg-indigo-600 text-white text-sm font-medium rounded-lg py-2 transition">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </template>

                <p x-show="visibleProducts.length === 0" class="text-slate-500 col-span-full">No items in this category yet.</p>
            </div>

            {{-- PRODUCT MODAL --}}
            <div x-show="modalProduct"
                 x-cloak
                 class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-40 px-4"
                 @click.self="closeModal()">
                <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md overflow-hidden">
                    <template x-if="modalProduct">
                        <div>
                            <img :src="modalProduct.image" :alt="modalProduct.name" class="w-full h-48 object-cover">
                            <div class="p-6">
                                <div class="flex items-start justify-between">
                                    <h3 class="text-xl font-bold text-white" x-text="modalProduct.name"></h3>
                                    <span class="text-indigo-400 font-bold" x-text="'$' + modalProduct.price.toFixed(2)"></span>
                                </div>
                                <p class="text-slate-400 text-sm mt-2" x-text="modalProduct.description"></p>

                                <div class="flex items-center gap-3 mt-5">
                                    <button @click="modalQty = Math.max(1, modalQty - 1)"
                                        class="w-8 h-8 rounded-full bg-slate-800 text-white">-</button>
                                    <span class="text-white font-medium w-6 text-center" x-text="modalQty"></span>
                                    <button @click="modalQty++"
                                        class="w-8 h-8 rounded-full bg-slate-800 text-white">+</button>
                                </div>

                                <textarea x-model="modalNote" placeholder="Special note (optional)" rows="2"
                                    class="w-full mt-4 bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-sm text-white placeholder:text-slate-500"></textarea>

                                <div class="flex gap-2 mt-5">
                                    <button @click="closeModal()"
                                        class="flex-1 bg-slate-800 text-white text-sm font-medium rounded-lg py-2">Cancel</button>
                                    <button @click="addToCartFromModal()"
                                        class="flex-1 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg py-2">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- ORDER CONFIRM MODAL --}}
            <div x-show="showConfirm"
                 x-cloak
                 class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-40 px-4"
                 @click.self="showConfirm = false">
                <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md p-6">
                    <h3 class="text-xl font-bold text-white mb-4">Confirm order</h3>

                    <div class="space-y-3 max-h-52 overflow-y-auto">
                        <template x-for="item in $store.cart.items" :key="item.id + item.note">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-300" x-text="item.qty + '× ' + item.name"></span>
                                <span class="text-white font-medium" x-text="'$' + (item.price * item.qty).toFixed(2)"></span>
                            </div>
                        </template>
                    </div>

                    <div class="border-t border-slate-800 mt-4 pt-4 flex justify-between mb-4">
                        <span class="text-slate-300 font-medium">Total</span>
                        <span class="text-indigo-400 font-bold" x-text="'$' + $store.cart.total.toFixed(2)"></span>
                    </div>

                    <label class="block text-sm text-slate-300 mb-2">Deliver to</label>
                    <div class="space-y-2 max-h-32 overflow-y-auto mb-2">
                        <template x-for="addr in $store.addresses.list" :key="addr.id">
                            <label class="flex items-center gap-2 bg-slate-800/60 rounded-lg px-3 py-2 cursor-pointer text-sm">
                                <input type="radio" name="addr" :value="addr.id" x-model.number="$store.addresses.selectedId">
                                <span class="text-white" x-text="addr.name"></span>
                                <span class="text-slate-500 text-xs truncate" x-text="addr.address || ''"></span>
                            </label>
                        </template>
                        <p x-show="$store.addresses.list.length === 0" class="text-slate-500 text-xs">
                            No saved addresses — add one under "My Address", or leave blank for dine-in.
                        </p>
                    </div>

                    <p x-show="orderError" x-text="orderError" class="text-red-400 text-sm mt-2"></p>

                    <div class="flex gap-2 mt-5">
                        <button @click="showConfirm = false" :disabled="placingOrder"
                            class="flex-1 bg-slate-800 text-white text-sm font-medium rounded-lg py-2">Back</button>
                        <button @click="placeOrder()" :disabled="placingOrder"
                            class="flex-1 bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 text-white text-sm font-medium rounded-lg py-2">
                            <span x-show="!placingOrder">Confirm & Place</span>
                            <span x-show="placingOrder">Placing...</span>
                        </button>
                    </div>
                </div>
            </div>

        </main>

        {{-- RIGHT CART PANEL --}}
        <aside class="w-80 bg-slate-900 border-l border-slate-800 flex flex-col shrink-0" x-data>
            <div class="px-6 py-5 border-b border-slate-800">
                <h2 class="text-white font-bold">Cart</h2>
            </div>

            <div class="flex-1 overflow-y-auto px-4 py-4 space-y-4">
                <template x-for="item in $store.cart.items" :key="item.id + item.note">
                    <div class="bg-slate-800/60 rounded-xl p-3 flex gap-3">
                        <img :src="item.image" class="w-14 h-14 object-cover rounded-lg">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <p class="text-white text-sm font-medium truncate" x-text="item.name"></p>
                                <button @click="$store.cart.remove(item)" class="text-slate-500 hover:text-red-400 text-sm shrink-0">✕</button>
                            </div>
                            <p x-show="item.note" class="text-slate-500 text-xs truncate" x-text="item.note"></p>
                            <div class="flex items-center justify-between mt-2">
                                <div class="flex items-center gap-2">
                                    <button @click="$store.cart.decrement(item)" class="w-6 h-6 rounded-full bg-slate-700 text-white text-xs">-</button>
                                    <span class="text-white text-sm" x-text="item.qty"></span>
                                    <button @click="$store.cart.increment(item)" class="w-6 h-6 rounded-full bg-slate-700 text-white text-xs">+</button>
                                </div>
                                <span class="text-indigo-400 text-sm font-semibold" x-text="'$' + (item.price * item.qty).toFixed(2)"></span>
                            </div>
                        </div>
                    </div>
                </template>

                <p x-show="$store.cart.items.length === 0" class="text-slate-500 text-sm text-center mt-10">Cart is empty</p>
            </div>

            <div class="border-t border-slate-800 p-4">
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-slate-400">Items</span>
                    <span class="text-white" x-text="$store.cart.count"></span>
                </div>
                <div class="flex justify-between mb-4">
                    <span class="text-slate-300 font-medium">Total</span>
                    <span class="text-white font-bold" x-text="'$' + $store.cart.total.toFixed(2)"></span>
                </div>
                <button
                    @click="document.dispatchEvent(new CustomEvent('open-confirm'))"
                    :disabled="$store.cart.items.length === 0"
                    class="w-full bg-indigo-600 hover:bg-indigo-500 disabled:opacity-40 disabled:cursor-not-allowed text-white font-medium rounded-lg py-3">
                    Place an order
                </button>
            </div>
        </aside>
    </div>

    {{-- ADDRESS SECTION --}}
    <div id="section-address" class="page-section hidden flex-1 overflow-y-auto p-8" x-data="addressApp()">
        <h2 class="text-lg font-bold text-white mb-6">My Address</h2>

        <div class="bg-slate-900 border border-slate-800 rounded-xl p-6 mb-8 max-w-lg">
            <div class="mb-4">
                <label class="block text-sm text-slate-300 mb-1">Label</label>
                <input type="text" x-model="form.name" placeholder="Home, Work, ..."
                    class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white">
            </div>
            <div class="mb-4">
                <label class="block text-sm text-slate-300 mb-1">Address (optional if using current location)</label>
                <input type="text" x-model="form.address" placeholder="Street, area, landmark..."
                    class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white">
            </div>

            <button @click="useCurrentLocation()" :disabled="capturing"
                class="text-sm text-indigo-400 hover:underline mb-1 disabled:opacity-50">
                <span x-show="!capturing">Use my current location</span>
                <span x-show="capturing">Getting location...</span>
            </button>
            <p x-show="form.latitude" class="text-xs text-slate-500 mb-4">
                Captured: <span x-text="form.latitude?.toFixed(5)"></span>, <span x-text="form.longitude?.toFixed(5)"></span>
            </p>

            <p x-show="error" x-text="error" class="text-red-400 text-sm mb-3"></p>

            <button @click="save()" :disabled="saving"
                class="bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 text-white text-sm font-medium rounded-lg px-5 py-2">
                <span x-show="!saving">Save address</span>
                <span x-show="saving">Saving...</span>
            </button>
        </div>

        <div class="space-y-3 max-w-lg">
            <template x-for="addr in $store.addresses.list" :key="addr.id">
                <div class="bg-slate-900 border border-slate-800 rounded-xl p-4 flex items-start justify-between">
                    <div>
                        <p class="text-white font-medium" x-text="addr.name"></p>
                        <p class="text-slate-400 text-sm" x-text="addr.address || 'No text address'"></p>
                        <p x-show="addr.latitude" class="text-slate-600 text-xs">
                            <span x-text="Number(addr.latitude).toFixed(5)"></span>, <span x-text="Number(addr.longitude).toFixed(5)"></span>
                        </p>
                    </div>
                    <button @click="remove(addr.id)" class="text-red-400 hover:underline text-sm shrink-0">Remove</button>
                </div>
            </template>
            <p x-show="$store.addresses.list.length === 0" class="text-slate-500 text-sm">No saved addresses yet.</p>
        </div>
    </div>

</div>

<style>
    [x-cloak] { display: none !important; }
    .side-link { color: #94a3b8; }
    .side-link.active { background: #4f46e5; color: #fff; }
</style>

<script>
    window.MENU_DATA = @json($menuData);
    window.ADDRESSES_DATA = @json($addresses);
    const ADDRESS_STORE_URL = "{{ route('user.addresses.store') }}";
    const ADDRESS_DESTROY_BASE = "{{ url('/user/addresses') }}";
    const ORDER_STORE_URL = "{{ route('user.orders.store') }}";
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

    document.addEventListener('alpine:init', () => {
        Alpine.store('cart', {
            items: JSON.parse(localStorage.getItem('cafe_cart') || '[]'),

            add(product, qty, note) {
                const existing = this.items.find(i => i.id === product.id && i.note === note);
                if (existing) {
                    existing.qty += qty;
                } else {
                    this.items.push({ id: product.id, name: product.name, price: product.price, image: product.image, qty, note });
                }
                this.persist();
            },
            increment(item) { item.qty++; this.persist(); },
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
            },
            clear() {
                this.items = [];
                this.persist();
            },
            persist() {
                localStorage.setItem('cafe_cart', JSON.stringify(this.items));
            },
            get total() {
                return this.items.reduce((sum, i) => sum + i.price * i.qty, 0);
            },
            get count() {
                return this.items.reduce((sum, i) => sum + i.qty, 0);
            },
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
                }
            },
        });
    });

    // sidebar section switching, no navigation
    const sideLinks = document.querySelectorAll('.side-link');
    const sections = document.querySelectorAll('.page-section');

    function showSection(target) {
        sections.forEach(s => s.classList.add('hidden'));
        document.getElementById('section-' + target).classList.remove('hidden');
        sideLinks.forEach(l => l.classList.remove('active'));
        document.querySelector(`.side-link[data-target="${target}"]`).classList.add('active');
    }

    sideLinks.forEach(link => {
        link.addEventListener('click', () => showSection(link.dataset.target));
    });
    showSection('menu');

    function menuApp() {
        return {
            categories: [],
            activeCategory: 'Food',
            products: [],
            modalProduct: null,
            modalQty: 1,
            modalNote: '',
            showConfirm: false,
            placingOrder: false,
            orderError: '',

            init() {
                this.categories = window.MENU_DATA.categories;
                this.products = window.MENU_DATA.products;
                if (this.categories.length && !this.categories.includes('Food')) {
                    this.activeCategory = this.categories[0];
                }
                document.addEventListener('open-confirm', () => {
                    this.showConfirm = true;
                });
            },

            get visibleProducts() {
                return this.products.filter(p => p.category === this.activeCategory);
            },

            openModal(product) {
                this.modalProduct = product;
                this.modalQty = 1;
                this.modalNote = '';
            },
            closeModal() {
                this.modalProduct = null;
            },
            addToCartFromModal() {
                this.$store.cart.add(this.modalProduct, this.modalQty, this.modalNote);
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
                            items: this.$store.cart.items.map(c => ({
                                product_id: c.id,
                                quantity: c.qty,
                                special_note: c.note,
                            })),
                            saved_location_id: this.$store.addresses.selectedId,
                        }),
                    });
                    const data = await res.json();

                    if (!res.ok) {
                        this.orderError = data.message || 'Could not place order.';
                        this.placingOrder = false;
                        return;
                    }

                    this.$store.cart.clear();
                    this.showConfirm = false;
                    this.placingOrder = false;
                    alert('Order placed — #' + data.order.id);
                } catch (e) {
                    this.orderError = 'Network error. Try again.';
                    this.placingOrder = false;
                }
            },
        };
    }

    function addressApp() {
        return {
            form: { name: '', address: '', latitude: null, longitude: null },
            capturing: false,
            saving: false,
            error: '',

            useCurrentLocation() {
                if (!navigator.geolocation) {
                    this.error = 'Location not supported on this device.';
                    return;
                }
                this.capturing = true;
                this.error = '';
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        this.form.latitude = pos.coords.latitude;
                        this.form.longitude = pos.coords.longitude;
                        this.capturing = false;
                    },
                    (err) => {
                        this.error = 'Could not get your location: ' + err.message;
                        this.capturing = false;
                    }
                );
            },

            async save() {
                if (!this.form.name) {
                    this.error = 'Give this address a name.';
                    return;
                }
                this.saving = true;
                this.error = '';
                const result = await this.$store.addresses.add(this.form);
                this.saving = false;
                if (!result.ok) {
                    this.error = result.data.message || 'Could not save address.';
                    return;
                }
                this.form = { name: '', address: '', latitude: null, longitude: null };
            },

            async remove(id) {
                await this.$store.addresses.remove(id);
            },
        };
    }
</script>

</body>
</html>