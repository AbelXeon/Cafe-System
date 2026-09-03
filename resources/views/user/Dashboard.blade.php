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
    </style>
</head>
<body class="bg-[#0f0e13] text-stone-200 h-screen overflow-hidden selection:bg-[#b08d57] selection:text-[#0f0e13]" x-data="{ mobileNavOpen: false, mobileCartOpen: false }">

<!-- Animated Success Toast Notification (Bottom-Right Corner) -->
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

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="mobileNavOpen"
         x-cloak
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileNavOpen = false"
         class="fixed inset-0 bg-black/80 backdrop-blur-sm z-40 lg:hidden">
    </div>

    <!-- Sidebar Navigation -->
    <aside
        :class="mobileNavOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed lg:static top-0 left-0 bottom-0 w-72 lg:w-64 bg-[#0f0e13] border-r border-[#1e1c25] flex flex-col shrink-0 justify-between z-50 lg:z-20 transition-transform duration-300 ease-in-out">
        
        <div class="flex flex-col min-h-0">
            <div class="p-5 sm:p-6 border-b border-[#1e1c25] flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#b08d57] flex items-center justify-center text-[#0f0e13]">
                        <i data-lucide="utensils" class="w-5 h-5 stroke-[2.5]"></i>
                    </div>
                    <div>
                        <span class="text-lg font-black tracking-tight text-white block">Crave<span class="text-[#b08d57]">Dash</span></span>
                        <span class="text-xs text-stone-500 font-medium">Customer Portal</span>
                    </div>
                </div>

                <button @click="mobileNavOpen = false" class="lg:hidden p-1.5 rounded-xl text-stone-400 hover:text-white hover:bg-[#1e1c25] transition" aria-label="Close Navigation">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Grouped Navigation Links -->
            <nav class="p-4 space-y-4 overflow-y-auto custom-scroll">
                
                <!-- GROUP: Menu -->
                <div class="space-y-1">
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-500 block">Menu</span>
                    <button data-target="menu" @click="mobileNavOpen = false" class="side-link w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-3">
                        <i data-lucide="layout-grid" class="w-4 h-4"></i>
                        <span>Menu Catalog</span>
                    </button>
                </div>

                <!-- GROUP: Orders -->
                <div class="space-y-1">
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-500 block">Orders</span>
                    <button data-target="orders" @click="mobileNavOpen = false" class="side-link w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-3">
                        <i data-lucide="receipt" class="w-4 h-4"></i>
                        <span>My Orders</span>
                    </button>
                    <button data-target="chat" @click="mobileNavOpen = false" class="side-link w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-3">
                        <i data-lucide="message-square" class="w-4 h-4"></i>
                        <span>Chat</span>
                    </button>
                </div>

                <!-- GROUP: Delivery -->
                <div class="space-y-1">
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-500 block">Delivery</span>
                    <button data-target="address" @click="mobileNavOpen = false" class="side-link w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-3">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                        <span>My Addresses</span>
                    </button>
                </div>

                <!-- GROUP: Settings -->
                <div class="space-y-1">
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-500 block">Settings</span>
                    <button data-target="profile" @click="mobileNavOpen = false" class="side-link w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-3">
                        <i data-lucide="user-cog" class="w-4 h-4"></i>
                        <span>Account Settings</span>
                    </button>
                </div>

            </nav>
        </div>

        <div class="p-4 border-t border-[#1e1c25] shrink-0">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-4 py-3 rounded-xl text-sm font-semibold text-rose-400 hover:bg-rose-500/10 transition flex items-center gap-3">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- SECTION: Menu Catalog -->
    <div id="section-menu" class="page-section flex flex-1 min-h-0 bg-[#14131a]/40 w-full">

        <main class="flex-1 overflow-y-auto custom-scroll pb-24 lg:pb-8 relative" x-data="menuApp()" x-init="init()">

            <!-- STICKY TOP: Browse Menu & Category Filters Banner (FLUSH ZERO GAP) -->
            <div class="sticky top-0 z-20 bg-[#0f0e13]/98 backdrop-blur-xl border-b border-[#2a2731] px-4 sm:px-6 lg:px-8 py-4 sm:py-5 shadow-2xl shadow-black/60">
                <div class="space-y-3 sm:space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Browse Menu</h1>
                        </div>
                        <div class="flex items-center gap-2">
                            <h2 class="hidden sm:inline-block text-xs uppercase font-bold text-[#b08d57] tracking-wider" x-text="activeCategory"></h2>
                            <span class="text-xs bg-[#1e1c25] text-stone-400 px-2.5 py-0.5 rounded-full font-medium" x-text="visibleProducts.length + ' items'"></span>
                        </div>
                    </div>

                    <!-- Horizontally scrollable on mobile, flex-wrap on desktop -->
                    <div class="flex items-center gap-2 overflow-x-auto custom-scroll pb-1 sm:flex-wrap">
                        <template x-for="cat in categories" :key="cat">
                            <button
                                @click="activeCategory = cat"
                                :class="activeCategory === cat
                                    ? 'bg-[#b08d57] text-[#0f0e13] font-bold shadow-md shadow-[#b08d57]/10'
                                    : 'bg-[#14131a] text-stone-400 hover:text-white border border-[#2a2731]'"
                                class="px-3.5 sm:px-4 py-2 rounded-xl text-xs sm:text-sm whitespace-nowrap transition cursor-pointer shrink-0"
                                x-text="cat">
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Products Content Area -->
            <div class="p-4 sm:p-6 lg:p-8">
                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-4 sm:gap-6">
                    <template x-for="product in visibleProducts" :key="product.id">
                        <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl overflow-hidden hover:border-[#b08d57]/60 transition duration-200 flex flex-col justify-between group cursor-pointer shadow-lg"
                             @click="openModal(product)">

                            <div class="relative w-full h-40 sm:h-44 bg-[#0f0e13] overflow-hidden">
                                <img :src="product.image" :alt="product.name" class="w-full h-full object-cover group-hover:scale-105 transition duration-300 ease-out">
                                <div class="absolute top-3 right-3 bg-[#0f0e13]/85 backdrop-blur-md px-2.5 py-1 rounded-lg border border-[#2a2731]">
                                    <span class="text-[#b08d57] font-extrabold text-sm" x-text="'$' + product.price.toFixed(2)"></span>
                                </div>
                            </div>

                            <div class="p-4 sm:p-5 flex flex-col flex-1 justify-between">
                                <div>
                                    <div class="flex items-center justify-between gap-2">
                                        <h3 class="text-white font-bold text-sm sm:text-base leading-snug group-hover:text-[#b08d57] transition" x-text="product.name"></h3>
                                        <span class="text-[10px] bg-[#1e1c25] text-[#b08d57] px-2 py-0.5 rounded-md font-semibold shrink-0" x-text="product.category"></span>
                                    </div>
                                    <p class="text-stone-500 text-xs mt-1.5 line-clamp-2 leading-relaxed" x-text="product.description"></p>
                                </div>

                                <button
                                    @click.stop="openModal(product)"
                                    class="w-full mt-4 bg-[#1e1c25] hover:bg-[#b08d57] hover:text-[#0f0e13] text-stone-200 text-xs font-bold rounded-xl py-2.5 transition flex items-center justify-center gap-2">
                                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                    <span>Add to Cart</span>
                                </button>
                            </div>
                        </div>
                    </template>

                    <div x-show="visibleProducts.length === 0" class="col-span-full py-16 text-center">
                        <div class="w-12 h-12 rounded-full bg-[#14131a] border border-[#2a2731] flex items-center justify-center mx-auto text-stone-500 mb-3">
                            <i data-lucide="package-open" class="w-6 h-6"></i>
                        </div>
                        <p class="text-stone-500 font-medium text-sm">No food items found in this category yet.</p>
                    </div>
                </div>
            </div>

            <!-- Product Details & Extras Modal -->
            <div x-show="modalProduct"
                 x-cloak
                 class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto"
                 @click.self="closeModal()">
                <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl my-auto">
                    <template x-if="modalProduct">
                        <div>
                            <div class="relative w-full h-48 sm:h-52 bg-[#0f0e13]">
                                <img :src="modalProduct.image" :alt="modalProduct.name" class="w-full h-full object-cover">
                                <button @click="closeModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-[#0f0e13]/80 text-stone-400 hover:text-white flex items-center justify-center border border-[#2a2731] transition">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>

                            <div class="p-5 sm:p-6 max-h-[70vh] overflow-y-auto custom-scroll">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h3 class="text-lg sm:text-xl font-bold text-white tracking-tight" x-text="modalProduct.name"></h3>
                                        <span class="text-xs text-stone-500 font-medium" x-text="modalProduct.category"></span>
                                    </div>
                                    <span class="text-[#b08d57] font-black text-base sm:text-lg" x-text="'$' + modalProduct.price.toFixed(2)"></span>
                                </div>
                                <p class="text-stone-400 text-xs sm:text-sm mt-2 leading-relaxed" x-text="modalProduct.description"></p>

                                <!-- Extras / Add-ons Section -->
                                <div class="mt-6 border-t border-[#2a2731] pt-4" x-show="extras.length > 0">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="sparkles" class="w-4 h-4 text-[#b08d57]"></i>
                                            <span class="text-xs uppercase font-bold text-white tracking-wider">Select Extras & Add-ons</span>
                                        </div>
                                        <span class="text-[11px] text-stone-500">Optional</span>
                                    </div>

                                    <div class="space-y-2.5">
                                        <template x-for="extra in extras" :key="extra.id">
                                            <div class="flex items-center justify-between p-3 rounded-xl bg-[#0f0e13] border border-[#2a2731] hover:border-[#b08d57]/40 transition">
                                                <div class="min-w-0 pr-2">
                                                    <p class="text-xs sm:text-sm font-semibold text-white truncate" x-text="extra.name"></p>
                                                    <p class="text-[11px] text-[#b08d57] font-bold mt-0.5" x-text="'+ $' + extra.price.toFixed(2) + ' each'"></p>
                                                </div>

                                                <div class="flex items-center gap-2 bg-[#14131a] border border-[#2a2731] rounded-lg p-1 shrink-0">
                                                    <button @click="decrementExtra(extra.id)"
                                                        class="w-6 h-6 rounded bg-[#1e1c25] hover:bg-[#2a2731] text-white font-bold flex items-center justify-center text-xs transition active:scale-95">-</button>
                                                    
                                                    <span class="text-white font-bold w-5 text-center text-xs" x-text="getExtraQty(extra.id)"></span>
                                                    
                                                    <button @click="incrementExtra(extra.id)"
                                                        class="w-6 h-6 rounded bg-[#1e1c25] hover:bg-[#b08d57] hover:text-[#0f0e13] text-white font-bold flex items-center justify-center text-xs transition active:scale-95">+</button>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Item Quantity Stepper -->
                                <div class="mt-5 flex items-center justify-between border-t border-[#2a2731] pt-4">
                                    <span class="text-xs uppercase font-semibold text-stone-400 tracking-wider">Order Quantity</span>
                                    <div class="flex items-center gap-3 bg-[#0f0e13] border border-[#2a2731] rounded-xl p-1">
                                        <button @click="modalQty = Math.max(1, modalQty - 1)"
                                            class="w-8 h-8 rounded-lg bg-[#1e1c25] hover:bg-[#2a2731] text-white font-bold flex items-center justify-center transition">-</button>
                                        <span class="text-white font-bold w-8 text-center text-sm" x-text="modalQty"></span>
                                        <button @click="modalQty++"
                                            class="w-8 h-8 rounded-lg bg-[#1e1c25] hover:bg-[#2a2731] text-white font-bold flex items-center justify-center transition">+</button>
                                    </div>
                                </div>

                                <!-- Special Instructions -->
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
                                        class="w-2/3 bg-[#b08d57] hover:bg-[#c9a36b] text-[#0f0e13] text-sm font-bold rounded-xl py-3 transition flex items-center justify-center gap-2 shadow-lg shadow-[#b08d57]/10">
                                        <span>Add to Cart</span>
                                        <span class="font-normal opacity-50">|</span>
                                        <span x-text="'$' + modalTotalPrice.toFixed(2)"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Confirmation Modal -->
            <div x-show="showConfirm"
                 x-cloak
                 class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto"
                 @click.self="showConfirm = false">
                <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl w-full max-w-lg p-5 sm:p-8 shadow-2xl my-auto">
                    <div class="flex items-center justify-between pb-4 border-b border-[#2a2731]">
                        <h3 class="text-lg sm:text-xl font-bold text-white tracking-tight">Confirm Order</h3>
                        <button @click="showConfirm = false" class="text-stone-500 hover:text-white transition">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <div class="space-y-3 max-h-40 sm:max-h-48 overflow-y-auto my-4 pr-1 custom-scroll">
                        <template x-for="item in $store.cart.items" :key="item.id + (item.note || '') + (item.extrasText || '')">
                            <div class="flex justify-between items-start text-sm py-1.5 border-b border-[#1e1c25]/60 last:border-0">
                                <div class="min-w-0 pr-4">
                                    <span class="text-white font-medium block truncate text-xs sm:text-sm" x-text="item.qty + 'x ' + item.name"></span>
                                    <span x-show="item.extrasText" class="text-[#b08d57] text-[11px] block mt-0.5" x-text="item.extrasText"></span>
                                    <span x-show="item.note" class="text-stone-500 text-[11px] truncate block" x-text="'Note: ' + item.note"></span>
                                </div>
                                <span class="text-stone-300 font-bold whitespace-nowrap text-xs sm:text-sm" x-text="'$' + (item.price * item.qty).toFixed(2)"></span>
                            </div>
                        </template>
                    </div>

                    <div class="border-t border-[#2a2731] pt-3 flex justify-between items-center mb-5">
                        <span class="text-stone-400 font-semibold text-sm">Total Due</span>
                        <span class="text-[#b08d57] font-black text-lg sm:text-xl" x-text="'$' + $store.cart.total.toFixed(2)"></span>
                    </div>

                    <div>
                        <label class="block text-xs uppercase font-semibold text-stone-400 tracking-wider mb-2">Delivery Destination</label>
                        <div class="space-y-2 max-h-36 overflow-y-auto pr-1 custom-scroll mb-2">
                            <template x-for="addr in $store.addresses.list" :key="addr.id">
                                <label class="flex items-center gap-3 bg-[#0f0e13] border border-[#2a2731] rounded-xl p-3 cursor-pointer text-sm hover:border-[#b08d57]/60 transition">
                                    <input type="radio" name="addr" :value="addr.id" x-model.number="$store.addresses.selectedId" class="text-[#b08d57] focus:ring-0">
                                    <div class="min-w-0">
                                        <span class="text-white font-bold block truncate text-xs sm:text-sm" x-text="addr.name"></span>
                                        <span class="text-stone-500 text-[11px] truncate block" x-text="addr.address || 'No street text'"></span>
                                    </div>
                                </label>
                            </template>
                            <p x-show="$store.addresses.list.length === 0" class="text-stone-500 text-xs p-2 bg-[#0f0e13] rounded-lg">
                                No saved addresses. Add one under "My Addresses" tab or continue for Dine-In.
                            </p>
                        </div>
                    </div>

                    <p x-show="orderError" x-text="orderError" class="text-rose-400 text-xs mt-3 bg-rose-500/10 border border-rose-500/20 p-2.5 rounded-xl"></p>

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

            <!-- Floating Mobile Cart Bar -->
            <div x-show="$store.cart.count > 0"
                 x-cloak
                 class="fixed bottom-4 left-4 right-4 lg:hidden z-30">
                <button
                    @click="mobileCartOpen = true; $nextTick(() => lucide.createIcons())"
                    class="w-full bg-[#b08d57] hover:bg-[#c9a36b] text-[#0f0e13] font-extrabold rounded-2xl p-4 shadow-2xl flex items-center justify-between transition active:scale-[0.98]">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-[#0f0e13]/20 flex items-center justify-center font-black text-xs" x-text="$store.cart.count"></div>
                        <span class="text-sm">View Cart</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <span x-text="'$' + $store.cart.total.toFixed(2)"></span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </div>
                </button>
            </div>

        </main>

        <!-- Cart Backdrop for Mobile Drawer -->
        <div x-show="mobileCartOpen"
             x-cloak
             x-transition:enter="transition-opacity ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileCartOpen = false"
             class="fixed inset-0 bg-black/80 backdrop-blur-sm z-40 lg:hidden">
        </div>

        <!-- Cart Sidebar -->
        <aside
            :class="mobileCartOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
            class="fixed lg:static top-0 right-0 bottom-0 w-80 sm:w-96 lg:w-80 xl:w-96 bg-[#0f0e13] border-l border-[#1e1c25] flex flex-col shrink-0 justify-between z-50 lg:z-10 transition-transform duration-300 ease-in-out">

            <div class="p-5 sm:p-6 border-b border-[#1e1c25] flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="shopping-bag" class="w-5 h-5 text-[#b08d57]"></i>
                    <h2 class="text-base font-bold text-white">Order Summary</h2>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs bg-[#1e1c25] text-stone-400 px-2.5 py-1 rounded-full font-bold" x-text="$store.cart.count + ' items'"></span>
                    <button @click="mobileCartOpen = false" class="lg:hidden p-1.5 rounded-xl text-stone-400 hover:text-white hover:bg-[#1e1c25] transition" aria-label="Close Cart">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scroll">
                <template x-for="item in $store.cart.items" :key="item.id + (item.note || '') + (item.extrasText || '')">
                    <div class="bg-[#14131a] border border-[#2a2731] rounded-xl p-3 flex gap-3 items-center">
                        <img :src="item.image" class="w-14 h-14 object-cover rounded-lg bg-[#0f0e13] shrink-0">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-1">
                                <p class="text-white text-xs font-bold truncate" x-text="item.name"></p>
                                <button @click="$store.cart.remove(item)" class="text-stone-500 hover:text-rose-400 transition text-xs shrink-0">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                            <p x-show="item.extrasText" class="text-[#b08d57] text-[11px] truncate mt-0.5" x-text="item.extrasText"></p>
                            <p x-show="item.note" class="text-stone-500 text-[11px] truncate mt-0.5" x-text="'Note: ' + item.note"></p>

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

                <div x-show="$store.cart.items.length === 0" class="h-64 flex flex-col items-center justify-center text-center p-4">
                    <div class="w-12 h-12 rounded-full bg-[#14131a] border border-[#2a2731] flex items-center justify-center text-stone-600 mb-3">
                        <i data-lucide="shopping-basket" class="w-6 h-6"></i>
                    </div>
                    <p class="text-stone-400 text-sm font-medium">Your basket is empty</p>
                    <p class="text-stone-600 text-xs mt-1">Select meals from the menu to start ordering</p>
                </div>
            </div>

            <div class="border-t border-[#1e1c25] p-4 sm:p-5 bg-[#0f0e13] space-y-3">
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
                    @click="mobileCartOpen = false; document.dispatchEvent(new CustomEvent('open-confirm'))"
                    :disabled="$store.cart.items.length === 0"
                    class="w-full bg-[#b08d57] hover:bg-[#c9a36b] disabled:opacity-30 disabled:cursor-not-allowed text-[#0f0e13] font-bold rounded-xl py-3 transition flex items-center justify-center gap-2">
                    <span>Checkout Now</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </div>
        </aside>
    </div>

    <!-- SECTION: My Orders (Coming Soon) -->
    <div id="section-orders" class="page-section hidden flex-1 overflow-y-auto p-4 sm:p-8 lg:p-12 custom-scroll bg-[#14131a]/40 w-full">
        <div class="max-w-3xl mx-auto pb-16">
            <div class="mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">My Orders</h1>
                <p class="text-stone-500 text-xs sm:text-sm mt-1">Track active deliveries and view your past food orders</p>
            </div>

            <div class="py-16 px-6 text-center bg-[#14131a] border border-[#2a2731] rounded-2xl shadow-xl flex flex-col items-center justify-center">
                <div class="w-16 h-16 rounded-2xl bg-[#b08d57]/10 border border-[#b08d57]/20 flex items-center justify-center text-[#b08d57] mb-4 shadow-lg shadow-[#b08d57]/10">
                    <i data-lucide="receipt" class="w-8 h-8"></i>
                </div>
                <span class="text-[11px] font-bold uppercase tracking-wider bg-[#b08d57]/20 text-[#b08d57] px-3 py-1 rounded-full border border-[#b08d57]/30 mb-3">Coming Soon</span>
                <h3 class="text-xl font-bold text-white mb-2">Order History & Live Tracking</h3>
                <p class="text-stone-400 text-xs sm:text-sm max-w-md mx-auto leading-relaxed">
                    We're building an interactive order status tracker so you can watch your food move from kitchen preparation to your door in real time!
                </p>
            </div>
        </div>
    </div>

    <!-- SECTION: Delivery Addresses -->
    <div id="section-address" class="page-section hidden flex-1 overflow-y-auto p-4 sm:p-8 lg:p-12 custom-scroll bg-[#14131a]/40 w-full" x-data="addressApp()" x-init="init()">
        <div class="max-w-3xl mx-auto pb-16">
            <div class="mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Delivery Addresses</h1>
                <p class="text-stone-500 text-xs sm:text-sm mt-1">Manage saved locations for quick, accurate delivery</p>
            </div>

            <!-- New Address Form -->
            <div class="mb-8 sm:mb-10 pb-6 sm:pb-8 border-b border-[#2a2731]">
                <h3 class="text-base font-bold text-white mb-4 flex items-center gap-2">
                    <i data-lucide="plus-circle" class="w-4 h-4 text-[#b08d57]"></i>
                    <span>Add New Address</span>
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Address Label <span class="text-[#b08d57]">*</span></label>
                        <input type="text" x-model="form.name" placeholder="e.g. Home, Office, Dormitory"
                            class="cd-input w-full bg-[#0f0e13] border border-[#2a2731] rounded-xl px-4 py-2.5 text-sm text-white placeholder-stone-600 focus:outline-none transition">
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400">Street Address / Landmark</label>
                            <span class="text-[11px] text-stone-500">Auto-filled if GPS detected</span>
                        </div>
                        <input type="text" x-model="form.address" placeholder="Street name, apartment, building no."
                            class="cd-input w-full bg-[#0f0e13] border border-[#2a2731] rounded-xl px-4 py-2.5 text-sm text-white placeholder-stone-600 focus:outline-none transition">
                    </div>

                    <div class="pt-1">
                        <button @click="useCurrentLocation()" :disabled="capturing"
                            type="button"
                            class="inline-flex items-center gap-2 text-xs font-bold text-[#b08d57] hover:text-[#c9a36b] bg-[#b08d57]/10 hover:bg-[#b08d57]/20 border border-[#b08d57]/30 px-3.5 py-2 rounded-xl disabled:opacity-50 transition">
                            <i data-lucide="crosshair" class="w-4 h-4"></i>
                            <span x-show="!capturing">Detect Current GPS Location</span>
                            <span x-show="capturing">Acquiring coordinates...</span>
                        </button>
                    </div>

                    <!-- Live Detected Location Map Preview -->
                    <div x-show="form.latitude" x-cloak class="mt-4 rounded-2xl overflow-hidden border border-[#b08d57]/40 bg-[#0f0e13] shadow-xl">
                        <div class="px-4 py-2.5 bg-[#14131a] border-b border-[#2a2731] flex items-center justify-between">
                            <div class="flex items-center gap-2 text-xs font-semibold text-white">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span>Detected Location Pin</span>
                            </div>
                            <span class="text-[11px] font-mono text-[#b08d57]" x-text="Number(form.latitude).toFixed(5) + ', ' + Number(form.longitude).toFixed(5)"></span>
                        </div>
                        <div id="detect-preview-map" class="w-full h-44 sm:h-52 z-10"></div>
                    </div>

                    <!-- Form Error -->
                    <p x-show="error" x-text="error" class="text-rose-400 text-xs bg-rose-500/10 border border-rose-500/20 p-2.5 rounded-xl"></p>

                    <div class="pt-2">
                        <button @click="save()" :disabled="saving"
                            class="bg-[#b08d57] hover:bg-[#c9a36b] disabled:opacity-50 text-[#0f0e13] text-sm font-bold rounded-xl px-6 py-2.5 transition flex items-center gap-2 shadow-lg shadow-[#b08d57]/10">
                            <i data-lucide="save" class="w-4 h-4"></i>
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
                        <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl overflow-hidden flex flex-col justify-between shadow-lg hover:border-[#b08d57]/50 transition">
                            
                            <!-- Saved Location Map Banner Preview -->
                            <div x-show="addr.latitude && addr.longitude" 
                                 x-init="$nextTick(() => initSavedMiniMap($el, addr.latitude, addr.longitude))"
                                 class="w-full h-32 bg-[#0f0e13] border-b border-[#2a2731] relative z-0">
                            </div>

                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-white font-bold text-base" x-text="addr.name"></h4>
                                        <span class="text-[10px] bg-[#1e1c25] text-stone-400 uppercase font-bold px-2 py-0.5 rounded">Saved</span>
                                    </div>
                                    <p class="text-stone-400 text-xs sm:text-sm mt-1.5 line-clamp-2" x-text="addr.address || 'GPS Coordinate Location'"></p>
                                    
                                    <div x-show="addr.latitude" class="flex items-center justify-between mt-3 pt-2 border-t border-[#2a2731]/60 text-[11px]">
                                        <span class="text-stone-500 font-mono">
                                            GPS: <span x-text="Number(addr.latitude).toFixed(4) + ', ' + Number(addr.longitude).toFixed(4)"></span>
                                        </span>
                                        <a :href="`https://www.google.com/maps?q=${addr.latitude},${addr.longitude}`" target="_blank" class="text-[#b08d57] hover:underline flex items-center gap-1 font-semibold">
                                            <span>Open Map</span>
                                            <i data-lucide="external-link" class="w-3 h-3"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-t border-[#2a2731]/80 flex justify-end">
                                    <button @click="remove(addr.id)" class="text-rose-400 hover:text-rose-300 text-xs font-semibold transition flex items-center gap-1">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        <span>Delete</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div x-show="$store.addresses.list.length === 0" class="col-span-full py-12 text-center bg-[#14131a]/40 border border-[#2a2731]/50 rounded-2xl">
                        <div class="w-12 h-12 rounded-full bg-[#14131a] border border-[#2a2731] flex items-center justify-center mx-auto text-stone-500 mb-3">
                            <i data-lucide="map-pin-off" class="w-6 h-6"></i>
                        </div>
                        <p class="text-stone-400 font-medium text-sm">No saved locations found.</p>
                        <p class="text-stone-600 text-xs mt-1">Use the form above to add your home or current GPS location.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION: Chat Support (Coming Soon) -->
    <div id="section-chat" class="page-section hidden flex-1 overflow-y-auto p-4 sm:p-8 lg:p-12 custom-scroll bg-[#14131a]/40 w-full">
        <div class="max-w-3xl mx-auto pb-16">
            <div class="mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Chat Support</h1>
                <p class="text-stone-500 text-xs sm:text-sm mt-1">Direct communication with our kitchen and delivery team</p>
            </div>

            <div class="py-16 px-6 text-center bg-[#14131a] border border-[#2a2731] rounded-2xl shadow-xl flex flex-col items-center justify-center">
                <div class="w-16 h-16 rounded-2xl bg-[#b08d57]/10 border border-[#b08d57]/20 flex items-center justify-center text-[#b08d57] mb-4 shadow-lg shadow-[#b08d57]/10">
                    <i data-lucide="message-square" class="w-8 h-8"></i>
                </div>
                <span class="text-[11px] font-bold uppercase tracking-wider bg-[#b08d57]/20 text-[#b08d57] px-3 py-1 rounded-full border border-[#b08d57]/30 mb-3">Coming Soon</span>
                <h3 class="text-xl font-bold text-white mb-2">Real-time Order Chat</h3>
                <p class="text-stone-400 text-xs sm:text-sm max-w-md mx-auto leading-relaxed">
                    Chat directly with your assigned delivery driver or kitchen staff for quick updates and meal instructions!
                </p>
            </div>
        </div>
    </div>

    <!-- SECTION: Account Settings (Coming Soon) -->
    <div id="section-profile" class="page-section hidden flex-1 overflow-y-auto p-4 sm:p-8 lg:p-12 custom-scroll bg-[#14131a]/40 w-full">
        <div class="max-w-3xl mx-auto pb-16">
            <div class="mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Account Settings</h1>
                <p class="text-stone-500 text-xs sm:text-sm mt-1">Manage your customer profile, preferences, and password</p>
            </div>

            <div class="py-16 px-6 text-center bg-[#14131a] border border-[#2a2731] rounded-2xl shadow-xl flex flex-col items-center justify-center">
                <div class="w-16 h-16 rounded-2xl bg-[#b08d57]/10 border border-[#b08d57]/20 flex items-center justify-center text-[#b08d57] mb-4 shadow-lg shadow-[#b08d57]/10">
                    <i data-lucide="user-cog" class="w-8 h-8"></i>
                </div>
                <span class="text-[11px] font-bold uppercase tracking-wider bg-[#b08d57]/20 text-[#b08d57] px-3 py-1 rounded-full border border-[#b08d57]/30 mb-3">Coming Soon</span>
                <h3 class="text-xl font-bold text-white mb-2">Profile & Security Settings</h3>
                <p class="text-stone-400 text-xs sm:text-sm max-w-md mx-auto leading-relaxed">
                    Soon you'll be able to edit your personal information, update your phone number, and customize your notification preferences here.
                </p>
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

                    this.$store.cart.clear();
                    this.showConfirm = false;
                    this.placingOrder = false;
                    
                    this.$store.toast.trigger(
                        'Order Placed Successfully!',
                        'Your meal is confirmed and has been sent to our kitchen team.',
                        data.order.id
                    );
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
            detectMap: null,
            detectMarker: null,

            init() {},

            initDetectMap(lat, lng) {
                this.$nextTick(() => {
                    const container = document.getElementById('detect-preview-map');
                    if (!container) return;

                    if (!this.detectMap) {
                        this.detectMap = L.map(container, {
                            zoomControl: true,
                            attributionControl: false,
                            fadeAnimation: false,
                            preferCanvas: true
                        }).setView([lat, lng], 16);

                        // Fast multi-shard tile layer
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            subdomains: ['a', 'b', 'c'],
                            maxZoom: 19
                        }).addTo(this.detectMap);

                        const goldIcon = L.divIcon({
                            className: 'custom-gold-marker',
                            html: `<div style="background-color:#b08d57; width:16px; height:16px; border-radius:50%; border:3px solid #0f0e13; box-shadow:0 0 14px rgba(176,141,87,1);"></div>`,
                            iconSize: [16, 16],
                            iconAnchor: [8, 8]
                        });

                        this.detectMarker = L.marker([lat, lng], { icon: goldIcon }).addTo(this.detectMap);
                    } else {
                        this.detectMap.setView([lat, lng], 16, { animate: false });
                        this.detectMarker.setLatLng([lat, lng]);
                        this.detectMap.invalidateSize();
                    }
                });
            },

            async useCurrentLocation() {
                if (!navigator.geolocation) {
                    this.error = 'Location is not supported on this browser or device.';
                    return;
                }
                this.capturing = true;
                this.error = '';

                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;
                        this.form.latitude = lat;
                        this.form.longitude = lng;
                        this.capturing = false;

                        // 1. Render Map INSTANTLY
                        this.initDetectMap(lat, lng);
                        if (!this.form.address) {
                            this.form.address = `GPS (${lat.toFixed(5)}, ${lng.toFixed(5)})`;
                        }
                        setTimeout(() => lucide.createIcons(), 50);

                        // 2. Asynchronous background reverse geocoding
                        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`, {
                            headers: { 'Accept': 'application/json' }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data && data.display_name) {
                                this.form.address = data.display_name.split(',').slice(0, 3).join(',').trim();
                            }
                        })
                        .catch(() => {});
                    },
                    (err) => {
                        this.error = 'Could not acquire GPS location: ' + err.message;
                        this.capturing = false;
                    },
                    { enableHighAccuracy: true, timeout: 8000, maximumAge: 60000 }
                );
            },

            async save() {
                if (!this.form.name || !this.form.name.trim()) {
                    this.error = 'Please provide an address label (e.g. Home, Office).';
                    return;
                }

                let addressText = this.form.address ? this.form.address.trim() : '';
                if (!addressText) {
                    if (this.form.latitude && this.form.longitude) {
                        addressText = `GPS Location (${Number(this.form.latitude).toFixed(5)}, ${Number(this.form.longitude).toFixed(5)})`;
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
                    }).setView([lat, lng], 15);

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

    // Initialize icons on mount
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>

</body>
</html>