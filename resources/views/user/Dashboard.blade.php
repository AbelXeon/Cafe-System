<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CraveDash | Dashboard & Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    <link rel="preconnect" href__="https://fonts.googleapis.com">
    <link rel="preconnect" href__="https://fonts.gstatic.com" crossorigin>
    <link href__="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
<body class="bg-[#0f0e13] text-stone-200 h-screen overflow-hidden selection:bg-[#b08d57] selection:text-[#0f0e13]">

<div class="flex h-full w-full">

    <aside class="w-64 bg-[#0f0e13] border-r border-[#1e1c25] flex flex-col shrink-0 justify-between z-20">
        <div>
            <!-- Brand Header -->
            <div class="p-6 border-b border-[#1e1c25] flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#b08d57] flex items-center justify-center text-[#0f0e13]">
                    <i data-lucide="utensils" class="w-5 h-5 stroke-[2.5]"></i>
                </div>
                <div>
                    <span class="text-lg font-black tracking-tight text-white block">Crave<span class="text-[#b08d57]">Dash</span></span>
                    <span class="text-xs text-stone-500 font-medium">Customer Portal</span>
                </div>
            </div>

            <nav class="p-4 space-y-1.5">
                <button data-target="menu" class="side-link w-full text-left px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-3">
                    <i data-lucide="layout-grid" class="w-4 h-4"></i>
                    <span>Menu Catalog</span>
                </button>
                <button data-target="address" class="side-link w-full text-left px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-3">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                    <span>My Addresses</span>
                </button>
            </nav>
        </div>

        <div class="p-4 border-t border-[#1e1c25]">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-4 py-3 rounded-xl text-sm font-semibold text-rose-400 hover:bg-rose-500/10 transition flex items-center gap-3">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </aside>

    <div id="section-menu" class="page-section flex flex-1 min-h-0 bg-[#14131a]/40">

        <main class="flex-1 overflow-y-auto p-6 lg:p-8 custom-scroll" x-data="menuApp()" x-init="init()">

            <!-- Top Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-extrabold text-white tracking-tight">Browse Menu</h1>
                </div>

                <!-- Category Filters -->
                <div class="flex gap-2 overflow-x-auto pb-2 sm:pb-0 custom-scroll">
                    <template x-for="cat in categories" :key="cat">
                        <button
                            @click="activeCategory = cat"
                            :class="activeCategory === cat
                                ? 'bg-[#b08d57] text-[#0f0e13] font-bold'
                                : 'bg-[#14131a] text-stone-400 hover:text-white border border-[#2a2731]'"
                            class="px-4 py-2 rounded-xl text-xs sm:text-sm whitespace-nowrap transition cursor-pointer"
                            x-text="cat">
                        </button>
                    </template>
                </div>
            </div>

            <!-- Current Category Title Bar -->
            <div class="flex items-center justify-between border-b border-[#2a2731] pb-3 mb-6">
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-bold text-white uppercase tracking-wider" x-text="activeCategory"></h2>
                    <span class="text-xs bg-[#1e1c25] text-stone-400 px-2 py-0.5 rounded-full font-medium" x-text="visibleProducts.length + ' items'"></span>
                </div>
            </div>

            {{-- PRODUCT GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-6">
                <template x-for="product in visibleProducts" :key="product.id">
                    <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl overflow-hidden hover:border-[#b08d57]/60 transition duration-200 flex flex-col justify-between group cursor-pointer"
                         @click="openModal(product)">

                        <!-- Product Image Box -->
                        <div class="relative w-full h-44 bg-[#0f0e13] overflow-hidden">
                            <img :src="product.image" :alt="product.name" class="w-full h-full object-cover group-hover:scale-105 transition duration-300 ease-out">
                            <div class="absolute top-3 right-3 bg-[#0f0e13]/80 backdrop-blur-md px-2.5 py-1 rounded-lg border border-[#2a2731]">
                                <span class="text-[#b08d57] font-extrabold text-sm" x-text="'$' + product.price.toFixed(2)"></span>
                            </div>
                        </div>

                        <!-- Product Content & Add Button -->
                        <div class="p-5 flex flex-col flex-1 justify-between">
                            <div>
                                <h3 class="text-white font-bold text-base leading-snug group-hover:text-[#b08d57] transition" x-text="product.name"></h3>
                                <p class="text-stone-500 text-xs mt-2 line-clamp-2 leading-relaxed" x-text="product.description"></p>
                            </div>

                            <button
                                @click.stop="$store.cart.add(product, 1, '')"
                                class="w-full mt-4 bg-[#1e1c25] hover:bg-[#b08d57] hover:text-[#0f0e13] text-stone-200 text-xs font-bold rounded-xl py-2.5 transition flex items-center justify-center gap-2">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>Add to Cart</span>
                            </button>
                        </div>
                    </div>
                </template>

                <!-- Empty State -->
                <div x-show="visibleProducts.length === 0" class="col-span-full py-16 text-center">
                    <div class="w-12 h-12 rounded-full bg-[#14131a] border border-[#2a2731] flex items-center justify-center mx-auto text-stone-500 mb-3">
                        <i data-lucide="package-open" class="w-6 h-6"></i>
                    </div>
                    <p class="text-stone-500 font-medium text-sm">No food items found in this category yet.</p>
                </div>
            </div>

            {{-- ================= PRODUCT DETAIL MODAL ================= --}}
            <div x-show="modalProduct"
                 x-cloak
                 class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4"
                 @click.self="closeModal()">
                <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl">
                    <template x-if="modalProduct">
                        <div>
                            <div class="relative w-full h-56 bg-[#0f0e13]">
                                <img :src="modalProduct.image" :alt="modalProduct.name" class="w-full h-full object-cover">
                                <button @click="closeModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-[#0f0e13]/80 text-stone-400 hover:text-white flex items-center justify-center border border-[#2a2731] transition">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>

                            <div class="p-6">
                                <div class="flex items-start justify-between gap-4">
                                    <h3 class="text-xl font-bold text-white tracking-tight" x-text="modalProduct.name"></h3>
                                    <span class="text-[#b08d57] font-black text-lg" x-text="'$' + modalProduct.price.toFixed(2)"></span>
                                </div>
                                <p class="text-stone-500 text-sm mt-2 leading-relaxed" x-text="modalProduct.description"></p>

                                <!-- Quantity Controls -->
                                <div class="mt-6 flex items-center justify-between border-t border-[#2a2731] pt-5">
                                    <span class="text-xs uppercase font-semibold text-stone-400 tracking-wider">Quantity</span>
                                    <div class="flex items-center gap-3 bg-[#0f0e13] border border-[#2a2731] rounded-xl p-1">
                                        <button @click="modalQty = Math.max(1, modalQty - 1)"
                                            class="w-8 h-8 rounded-lg bg-[#1e1c25] hover:bg-[#2a2731] text-white font-bold flex items-center justify-center transition">-</button>
                                        <span class="text-white font-bold w-8 text-center text-sm" x-text="modalQty"></span>
                                        <button @click="modalQty++"
                                            class="w-8 h-8 rounded-lg bg-[#1e1c25] hover:bg-[#2a2731] text-white font-bold flex items-center justify-center transition">+</button>
                                    </div>
                                </div>

                                <!-- Note Textarea -->
                                <div class="mt-4">
                                    <label class="block text-xs uppercase font-semibold text-stone-400 tracking-wider mb-2">Special Instructions</label>
                                    <textarea x-model="modalNote" placeholder="e.g. No onions, sauce on the side..." rows="2"
                                        class="cd-input w-full bg-[#0f0e13] border border-[#2a2731] rounded-xl px-3.5 py-2.5 text-sm text-white placeholder:text-stone-600 focus:outline-none transition"></textarea>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-3 mt-6">
                                    <button @click="closeModal()"
                                        class="w-1/3 bg-[#1e1c25] hover:bg-[#2a2731] text-white text-sm font-semibold rounded-xl py-3 transition">Cancel</button>
                                    <button @click="addToCartFromModal()"
                                        class="w-2/3 bg-[#b08d57] hover:bg-[#c9a36b] text-[#0f0e13] text-sm font-bold rounded-xl py-3 transition flex items-center justify-center gap-2">
                                        <span>Add to Cart</span>
                                        <span class="font-normal opacity-50">|</span>
                                        <span x-text="'$' + (modalProduct.price * modalQty).toFixed(2)"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- ================= ORDER CONFIRM MODAL ================= --}}
            <div x-show="showConfirm"
                 x-cloak
                 class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4"
                 @click.self="showConfirm = false">
                <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl w-full max-w-lg p-6 sm:p-8 shadow-2xl">
                    <div class="flex items-center justify-between pb-4 border-b border-[#2a2731]">
                        <h3 class="text-xl font-bold text-white tracking-tight">Confirm Order</h3>
                        <button @click="showConfirm = false" class="text-stone-500 hover:text-white transition">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <!-- Items Summary -->
                    <div class="space-y-3 max-h-48 overflow-y-auto my-4 pr-1 custom-scroll">
                        <template x-for="item in $store.cart.items" :key="item.id + item.note">
                            <div class="flex justify-between items-center text-sm py-1">
                                <div class="min-w-0 pr-4">
                                    <span class="text-white font-medium block truncate" x-text="item.qty + 'x ' + item.name"></span>
                                    <span x-show="item.note" class="text-stone-500 text-xs truncate block" x-text="item.note"></span>
                                </div>
                                <span class="text-stone-300 font-bold whitespace-nowrap" x-text="'$' + (item.price * item.qty).toFixed(2)"></span>
                            </div>
                        </template>
                    </div>

                    <div class="border-t border-[#2a2731] pt-3 flex justify-between items-center mb-6">
                        <span class="text-stone-400 font-semibold text-sm">Total Due</span>
                        <span class="text-[#b08d57] font-black text-xl" x-text="'$' + $store.cart.total.toFixed(2)"></span>
                    </div>

                    <!-- Deliver To Selector -->
                    <div>
                        <label class="block text-xs uppercase font-semibold text-stone-400 tracking-wider mb-2">Delivery Destination</label>
                        <div class="space-y-2 max-h-36 overflow-y-auto pr-1 custom-scroll mb-2">
                            <template x-for="addr in $store.addresses.list" :key="addr.id">
                                <label class="flex items-center gap-3 bg-[#0f0e13] border border-[#2a2731] rounded-xl p-3 cursor-pointer text-sm hover:border-[#b08d57]/60 transition">
                                    <input type="radio" name="addr" :value="addr.id" x-model.number="$store.addresses.selectedId" class="text-[#b08d57] focus:ring-0">
                                    <div class="min-w-0">
                                        <span class="text-white font-bold block truncate" x-text="addr.name"></span>
                                        <span class="text-stone-500 text-xs truncate block" x-text="addr.address || 'No street text'"></span>
                                    </div>
                                </label>
                            </template>
                            <p x-show="$store.addresses.list.length === 0" class="text-stone-500 text-xs p-2 bg-[#0f0e13] rounded-lg">
                                No saved addresses. Add one under "My Addresses" tab or continue for Dine-In.
                            </p>
                        </div>
                    </div>

                    <!-- Error line -->
                    <p x-show="orderError" x-text="orderError" class="text-rose-400 text-xs mt-3 bg-rose-500/10 border border-rose-500/20 p-2.5 rounded-xl"></p>

                    <!-- Buttons -->
                    <div class="flex gap-3 mt-6">
                        <button @click="showConfirm = false" :disabled="placingOrder"
                            class="w-1/3 bg-[#1e1c25] hover:bg-[#2a2731] text-white text-sm font-semibold rounded-xl py-3 transition">Back</button>
                        <button @click="placeOrder()" :disabled="placingOrder"
                            class="w-2/3 bg-[#b08d57] hover:bg-[#c9a36b] disabled:opacity-50 text-[#0f0e13] text-sm font-bold rounded-xl py-3 transition flex items-center justify-center gap-2">
                            <span x-show="!placingOrder">Place Order Now</span>
                            <span x-show="placingOrder">Submitting...</span>
                        </button>
                    </div>
                </div>
            </div>

        </main>

        {{-- ================= RIGHT CART PANEL ================= --}}
        <aside class="w-80 lg:w-96 bg-[#0f0e13] border-l border-[#1e1c25] flex flex-col shrink-0 justify-between z-10" x-data>

            <!-- Cart Header -->
            <div class="p-6 border-b border-[#1e1c25] flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="shopping-bag" class="w-5 h-5 text-[#b08d57]"></i>
                    <h2 class="text-base font-bold text-white">Order Summary</h2>
                </div>
                <span class="text-xs bg-[#1e1c25] text-stone-400 px-2.5 py-1 rounded-full font-bold" x-text="$store.cart.count + ' items'"></span>
            </div>

            <!-- Cart Items Scroll List -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scroll">
                <template x-for="item in $store.cart.items" :key="item.id + item.note">
                    <div class="bg-[#14131a] border border-[#2a2731] rounded-xl p-3 flex gap-3 items-center">
                        <img :src="item.image" class="w-14 h-14 object-cover rounded-lg bg-[#0f0e13] shrink-0">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-1">
                                <p class="text-white text-xs font-bold truncate" x-text="item.name"></p>
                                <button @click="$store.cart.remove(item)" class="text-stone-500 hover:text-rose-400 transition text-xs shrink-0">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                            <p x-show="item.note" class="text-stone-500 text-[11px] truncate mt-0.5" x-text="item.note"></p>

                            <div class="flex items-center justify-between mt-2.5">
                                <div class="flex items-center gap-1.5 bg-[#0f0e13] border border-[#2a2731] rounded-lg px-1.5 py-0.5">
                                    <button @click="$store.cart.decrement(item)" class="text-stone-400 hover:text-white text-xs px-1">-</button>
                                    <span class="text-white text-xs font-bold px-1" x-text="item.qty"></span>
                                    <button @click="$store.cart.increment(item)" class="text-stone-400 hover:text-white text-xs px-1">+</button>
                                </div>
                                <span class="text-[#b08d57] text-xs font-bold" x-text="'$' + (item.price * item.qty).toFixed(2)"></span>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Empty Cart Notice -->
                <div x-show="$store.cart.items.length === 0" class="h-64 flex flex-col items-center justify-center text-center p-4">
                    <div class="w-12 h-12 rounded-full bg-[#14131a] border border-[#2a2731] flex items-center justify-center text-stone-600 mb-3">
                        <i data-lucide="shopping-basket" class="w-6 h-6"></i>
                    </div>
                    <p class="text-stone-400 text-sm font-medium">Your basket is empty</p>
                    <p class="text-stone-600 text-xs mt-1">Select meals from the menu to start ordering</p>
                </div>
            </div>

            <!-- Cart Footer Checkout Bar -->
            <div class="border-t border-[#1e1c25] p-5 bg-[#0f0e13] space-y-3">
                <div class="space-y-1.5 text-xs">
                    <div class="flex justify-between text-stone-400">
                        <span>Items Total</span>
                        <span class="text-stone-200 font-medium" x-text="'$' + $store.cart.total.toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between text-stone-400">
                        <span>Tax &amp; Fees</span>
                        <span class="text-stone-200 font-medium">$0.00</span>
                    </div>
                    <div class="flex justify-between text-sm pt-2 border-t border-[#1e1c25] font-bold">
                        <span class="text-white">Estimated Total</span>
                        <span class="text-[#b08d57] text-base" x-text="'$' + $store.cart.total.toFixed(2)"></span>
                    </div>
                </div>

                <button
                    @click="document.dispatchEvent(new CustomEvent('open-confirm'))"
                    :disabled="$store.cart.items.length === 0"
                    class="w-full bg-[#b08d57] hover:bg-[#c9a36b] disabled:opacity-30 disabled:cursor-not-allowed text-[#0f0e13] font-bold rounded-xl py-3 transition flex items-center justify-center gap-2">
                    <span>Checkout Now</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </div>
        </aside>
    </div>

    {{-- ================= ADDRESS SECTION ================= --}}
    <div id="section-address" class="page-section hidden flex-1 overflow-y-auto p-8 lg:p-12 custom-scroll bg-[#14131a]/40" x-data="addressApp()">
        <div class="max-w-3xl mx-auto">
            <div class="mb-8">
                <h1 class="text-2xl lg:text-3xl font-extrabold text-white tracking-tight">Delivery Addresses</h1>
                <p class="text-stone-500 text-sm mt-1">Manage saved locations for one-click checkout</p>
            </div>

            <!-- New Address Form Card -->
            <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-6 sm:p-8 mb-10 shadow-xl">
                <h3 class="text-base font-bold text-white mb-4 flex items-center gap-2">
                    <i data-lucide="plus-circle" class="w-4 h-4 text-[#b08d57]"></i>
                    <span>Add New Address</span>
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Address Label</label>
                        <input type="text" x-model="form.name" placeholder="e.g. Home, Office, Dorm"
                            class="cd-input w-full bg-[#0f0e13] border border-[#2a2731] rounded-xl px-4 py-2.5 text-sm text-white placeholder-stone-600 focus:outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Street Address / Landmark</label>
                        <input type="text" x-model="form.address" placeholder="Street name, apartment, building no."
                            class="cd-input w-full bg-[#0f0e13] border border-[#2a2731] rounded-xl px-4 py-2.5 text-sm text-white placeholder-stone-600 focus:outline-none transition">
                    </div>

                    <!-- Geolocation Capture -->
                    <div class="pt-2">
                        <button @click="useCurrentLocation()" :disabled="capturing"
                            type="button"
                            class="inline-flex items-center gap-2 text-xs font-semibold text-[#b08d57] hover:text-[#c9a36b] disabled:opacity-50 transition">
                            <i data-lucide="crosshair" class="w-4 h-4"></i>
                            <span x-show="!capturing">Detect Current GPS Location</span>
                            <span x-show="capturing">Acquiring coordinates...</span>
                        </button>
                        <p x-show="form.latitude" class="text-xs text-stone-400 mt-1">
                            Coordinates captured: <span class="text-stone-200 font-mono" x-text="form.latitude?.toFixed(5) + ', ' + form.longitude?.toFixed(5)"></span>
                        </p>
                    </div>

                    <!-- Form Error -->
                    <p x-show="error" x-text="error" class="text-rose-400 text-xs bg-rose-500/10 border border-rose-500/20 p-2.5 rounded-xl"></p>

                    <div class="pt-2">
                        <button @click="save()" :disabled="saving"
                            class="bg-[#b08d57] hover:bg-[#c9a36b] disabled:opacity-50 text-[#0f0e13] text-sm font-bold rounded-xl px-6 py-2.5 transition">
                            <span x-show="!saving">Save Address</span>
                            <span x-show="saving">Saving...</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Saved Addresses List -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-stone-400 mb-4">Saved Locations</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <template x-for="addr in $store.addresses.list" :key="addr.id">
                        <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-5 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between">
                                    <h4 class="text-white font-bold text-base" x-text="addr.name"></h4>
                                    <span class="text-[10px] bg-[#1e1c25] text-stone-400 uppercase font-bold px-2 py-0.5 rounded">Saved</span>
                                </div>
                                <p class="text-stone-400 text-sm mt-1" x-text="addr.address || 'No street text provided'"></p>
                                <p x-show="addr.latitude" class="text-stone-500 font-mono text-xs mt-2">
                                    GPS: <span x-text="Number(addr.latitude).toFixed(4)"></span>, <span x-text="Number(addr.longitude).toFixed(4)"></span>
                                </p>
                            </div>
                            <div class="mt-4 pt-4 border-t border-[#2a2731]/80 flex justify-end">
                                <button @click="remove(addr.id)" class="text-rose-400 hover:text-rose-300 text-xs font-semibold transition flex items-center gap-1">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    <span>Delete</span>
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- Empty Locations State -->
                    <div x-show="$store.addresses.list.length === 0" class="col-span-full py-10 text-center bg-[#14131a]/40 border border-[#2a2731]/50 rounded-2xl">
                        <p class="text-stone-500 text-sm">No saved locations found. Add your first address above.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

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
        document.getElementById('section-' + target).classList.remove('hidden');
        sideLinks.forEach(l => l.classList.remove('active'));
        document.querySelector(`.side-link[data-target="${target}"]`).classList.add('active');
        setTimeout(() => lucide.createIcons(), 50);
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
                    setTimeout(() => lucide.createIcons(), 50);
                });
                this.$watch('activeCategory', () => {
                    setTimeout(() => lucide.createIcons(), 50);
                });
            },

            get visibleProducts() {
                return this.products.filter(p => p.category === this.activeCategory);
            },

            openModal(product) {
                this.modalProduct = product;
                this.modalQty = 1;
                this.modalNote = '';
                setTimeout(() => lucide.createIcons(), 50);
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
                    alert('Order placed successfully — #' + data.order.id);
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

    // Initialize icons on mount
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>

</body>
</html>