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

    <!-- Mobile Backdrop -->
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

    <!-- Sidebar -->
    <aside
        :class="mobileNavOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed lg:static top-0 left-0 bottom-0 w-72 lg:w-64 bg-[#0f0e13] border-r border-[#1e1c25] flex flex-col shrink-0 justify-between z-50 lg:z-20 transition-transform duration-300 ease-in-out">

        <div class="flex flex-col min-h-0">
            <div class="p-5 sm:p-6 border-b border-[#1e1c25] flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#b08d57] flex items-center justify-center text-[#0f0e13]">
                        <i data-lucide="bike" class="w-5 h-5 stroke-[2.5]"></i>
                    </div>
                    <div>
                        <span class="text-lg font-black tracking-tight text-white block">Crave<span class="text-[#b08d57]">Dash</span></span>
                        <span class="text-xs text-stone-500 font-medium">Delivery Portal</span>
                    </div>
                </div>
                <button @click="mobileNavOpen = false" class="lg:hidden p-1.5 rounded-xl text-stone-400 hover:text-white hover:bg-[#1e1c25] transition" aria-label="Close Navigation">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Online / Offline status card -->
            <div class="p-4">
                <button @click="toggleOnline()"
                        class="w-full rounded-2xl p-4 flex items-center gap-3 transition border"
                        :class="online ? 'bg-emerald-500/15 border-emerald-500/40 text-emerald-400' : 'bg-[#14131a] border-[#2a2731] text-stone-400 hover:border-[#b08d57]/40'">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                         :class="online ? 'bg-emerald-500/20' : 'bg-[#1e1c25]'">
                        <i :data-lucide="online ? 'radio' : 'radio-off'" class="w-5 h-5"></i>
                    </div>
                    <div class="text-left flex-1 min-w-0">
                        <p class="text-sm font-bold" x-text="online ? 'You are Online' : 'You are Offline'"></p>
                        <p class="text-[11px] mt-0.5" x-text="online ? 'Receiving new orders' : 'Tap to start receiving orders'"></p>
                    </div>
                </button>
            </div>

            <nav class="px-4 pb-4 space-y-1 overflow-y-auto custom-scroll">
                <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-500 block mb-1">Delivery Queue</span>

                <button @click="activeTab = 'all'; mobileNavOpen = false" :class="activeTab === 'all' ? 'active' : ''" class="side-link w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i data-lucide="layers" class="w-4 h-4"></i><span>All Orders</span>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full font-bold" :class="activeTab === 'all' ? 'bg-[#0f0e13] text-[#b08d57]' : 'bg-[#1e1c25] text-stone-400'" x-text="orders.length"></span>
                </button>

                <button @click="activeTab = 'ready'; mobileNavOpen = false" :class="activeTab === 'ready' ? 'active' : ''" class="side-link w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i data-lucide="bell-ring" class="w-4 h-4"></i><span>Incoming</span>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full font-bold" :class="activeTab === 'ready' ? 'bg-[#0f0e13] text-amber-500' : 'bg-amber-500/20 text-amber-400'" x-text="counts.ready"></span>
                </button>

                <button @click="activeTab = 'out_for_delivery'; mobileNavOpen = false" :class="activeTab === 'out_for_delivery' ? 'active' : ''" class="side-link w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i data-lucide="bike" class="w-4 h-4"></i><span>Out for Delivery</span>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full font-bold" :class="activeTab === 'out_for_delivery' ? 'bg-[#0f0e13] text-sky-400' : 'bg-sky-500/20 text-sky-400'" x-text="counts.out_for_delivery"></span>
                </button>

                <button @click="activeTab = 'delivered'; mobileNavOpen = false" :class="activeTab === 'delivered' ? 'active' : ''" class="side-link w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i data-lucide="check-circle-2" class="w-4 h-4"></i><span>Delivered</span>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full font-bold" :class="activeTab === 'delivered' ? 'bg-[#0f0e13] text-emerald-400' : 'bg-emerald-500/20 text-emerald-400'" x-text="counts.delivered"></span>
                </button>

                <div class="pt-3 mt-3 border-t border-[#1e1c25]">
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-500 block mb-1">Sound Alert</span>
                    <button @click="toggleSound()" class="side-link w-full text-left px-3.5 py-2 rounded-xl text-xs font-semibold flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <i :data-lucide="soundEnabled ? 'volume-2' : 'volume-x'" class="w-4 h-4"></i><span>Audio Notification</span>
                        </div>
                        <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded" :class="soundEnabled ? 'bg-emerald-500/20 text-emerald-400' : 'bg-stone-800 text-stone-500'" x-text="soundEnabled ? 'ON' : 'OFF'"></span>
                    </button>
                </div>
            </nav>
        </div>

        <div class="p-4 border-t border-[#1e1c25] shrink-0">
            <div class="flex items-center gap-3 mb-3 px-2">
                <div class="w-8 h-8 rounded-full bg-[#1e1c25] border border-[#2a2731] flex items-center justify-center font-bold text-xs text-[#b08d57]">
                    {{ strtoupper(substr(Auth::user()->fullname ?? 'Driver', 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-white truncate">{{ Auth::user()->fullname ?? 'Driver' }}</p>
                    <p class="text-[10px] text-stone-500 capitalize">{{ Auth::user()->role->name ?? 'Delivery' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-4 py-2.5 rounded-xl text-xs font-semibold text-rose-400 hover:bg-rose-500/10 transition flex items-center gap-3">
                    <i data-lucide="log-out" class="w-4 h-4"></i><span>Sign Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <main class="flex-1 flex flex-col min-h-0 bg-[#14131a]/40 w-full overflow-hidden">

        <!-- Header Bar -->
        <div class="bg-[#0f0e13]/98 backdrop-blur-xl border-b border-[#2a2731] px-4 sm:px-6 lg:px-8 py-4 shrink-0 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight flex items-center gap-3">
                    <span>Delivery Dispatch</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#b08d57]/15 border border-[#b08d57]/30 text-[#b08d57]">
                        <span class="w-2 h-2 rounded-full bg-[#b08d57]"></span>Auto-refreshing (6s)
                    </span>
                </h1>
                <p class="text-stone-500 text-xs mt-0.5">Accept incoming orders, navigate to customers, and mark deliveries complete</p>
            </div>

            <div class="flex items-center gap-2 overflow-x-auto custom-scroll">
                <button @click="activeTab = 'all'"
                    :class="activeTab === 'all' ? 'bg-[#b08d57] text-[#0f0e13] font-bold' : 'bg-[#14131a] text-stone-400 border border-[#2a2731]'"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition shrink-0">All (<span x-text="orders.length"></span>)</button>
                <button @click="activeTab = 'ready'"
                    :class="activeTab === 'ready' ? 'bg-amber-500 text-black font-bold' : 'bg-[#14131a] text-amber-400/80 border border-[#2a2731]'"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition shrink-0 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>Incoming (<span x-text="counts.ready"></span>)
                </button>
                <button @click="activeTab = 'out_for_delivery'"
                    :class="activeTab === 'out_for_delivery' ? 'bg-sky-500 text-black font-bold' : 'bg-[#14131a] text-sky-400 border border-[#2a2731]'"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition shrink-0">Active (<span x-text="counts.out_for_delivery"></span>)</button>
                <button @click="activeTab = 'delivered'"
                    :class="activeTab === 'delivered' ? 'bg-emerald-500 text-black font-bold' : 'bg-[#14131a] text-emerald-400 border border-[#2a2731]'"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition shrink-0">Done (<span x-text="counts.delivered"></span>)</button>
            </div>
        </div>

        <!-- Offline banner -->
        <div x-show="!online" x-cloak
             class="bg-amber-500/10 border-b border-amber-500/30 px-4 sm:px-6 lg:px-8 py-2.5 flex items-center justify-between gap-3 shrink-0">
            <p class="text-xs text-amber-400 font-semibold flex items-center gap-2">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                You are offline — turn online to receive new incoming orders.
            </p>
            <button @click="toggleOnline()" class="text-xs font-bold text-amber-300 hover:text-amber-200 underline">Go Online</button>
        </div>

        <!-- Orders Grid -->
        <div class="flex-1 overflow-y-auto custom-scroll p-4 sm:p-6 lg:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 gap-5">

                <template x-for="order in filteredOrders" :key="order.id">
                    <div class="bg-[#14131a] border rounded-2xl overflow-hidden flex flex-col shadow-xl transition-all duration-200 min-w-0"
                         :class="{
                            'border-amber-500/60 ring-1 ring-amber-500/30': order.status === 'ready',
                            'border-sky-500/60 ring-1 ring-sky-500/30': order.status === 'out_for_delivery',
                            'border-emerald-500/60': order.status === 'delivered',
                            'border-[#2a2731] opacity-80': order.status === 'cancelled'
                         }">

                        <!-- Header -->
                        <div class="p-4 sm:p-5 border-b border-[#2a2731] bg-[#0f0e13]/60 flex items-start justify-between gap-3 min-w-0">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-base font-black text-white shrink-0" x-text="'Order #' + order.id"></span>
                                    <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-md shrink-0"
                                          :class="{
                                            'bg-amber-500/20 text-amber-400 border border-amber-500/40': order.status === 'ready',
                                            'bg-sky-500/20 text-sky-400 border border-sky-500/40': order.status === 'out_for_delivery',
                                            'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40': order.status === 'delivered'
                                          }"
                                          x-text="statusLabel(order.status)"></span>
                                </div>
                                <p class="text-xs text-stone-400 mt-1 flex items-center gap-2 flex-wrap min-w-0">
                                    <span class="font-bold text-white truncate" x-text="order.customer_name"></span>
                                    <span>&bull;</span>
                                    <span x-text="order.created_at"></span>
                                    <span>&bull;</span>
                                    <span class="text-[#b08d57]" x-text="order.time_ago"></span>
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-sm font-black text-[#b08d57]" x-text="'$' + (Number(order.total_amount) || 0).toFixed(2)"></span>
                                <span class="text-[10px] text-stone-500 block uppercase" x-text="order.order_type.replace('_', ' ')"></span>
                            </div>
                        </div>

                        <!-- Customer + Destination info (only relevant once accepted or for ready) -->
                        <div class="p-4 sm:p-5 space-y-3 border-b border-[#2a2731] bg-[#0f0e13]/40">

                            <!-- Customer contact -->
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl bg-[#1e1c25] border border-[#2a2731] flex items-center justify-center text-[#b08d57] shrink-0">
                                    <i data-lucide="user" class="w-4 h-4"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] uppercase font-bold tracking-wider text-stone-500">Customer</p>
                                    <p class="text-sm font-bold text-white truncate" x-text="order.customer_name"></p>
                                    <div class="flex items-center gap-2 mt-1" x-show="order.customer_phone">
                                        <i data-lucide="phone" class="w-3.5 h-3.5 text-stone-500"></i>
                                        <a :href__="'tel:' + (order.customer_phone || '')"
                                           class="text-xs font-semibold text-[#b08d57] hover:text-[#c9a36b] transition"
                                           x-text="order.customer_phone"></a>
                                    </div>
                                </div>
                                <a x-show="order.customer_phone" :href__="'tel:' + (order.customer_phone || '')"
                                   class="shrink-0 inline-flex items-center gap-1.5 text-xs font-bold bg-[#b08d57] text-[#0f0e13] px-3 py-2 rounded-xl hover:bg-[#c9a36b] transition">
                                    <i data-lucide="phone-call" class="w-3.5 h-3.5"></i><span>Call</span>
                                </a>
                            </div>

                            <!-- Delivery address -->
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl bg-[#1e1c25] border border-[#2a2731] flex items-center justify-center text-[#b08d57] shrink-0">
                                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] uppercase font-bold tracking-wider text-stone-500" x-text="order.address_name ? order.address_name : 'Drop-off'"></p>
                                    <p class="text-sm text-white leading-snug break-words" x-text="order.address_text || 'No street text provided'"></p>
                                    <p x-show="order.latitude && order.longitude" class="text-[11px] text-stone-500 font-mono mt-1">
                                        GPS: <span x-text="Number(order.latitude).toFixed(5)"></span>, <span x-text="Number(order.longitude).toFixed(5)"></span>
                                    </p>
                                </div>
                                <a x-show="order.latitude && order.longitude"
                                   :href__="mapsUrl(order)"
                                   target="_blank" rel="noopener"
                                   class="shrink-0 inline-flex items-center gap-1.5 text-xs font-bold bg-[#1e1c25] border border-[#2a2731] text-stone-200 px-3 py-2 rounded-xl hover:border-[#b08d57]/60 transition">
                                    <i data-lucide="navigation" class="w-3.5 h-3.5 text-[#b08d57]"></i><span>Navigate</span>
                                </a>
                            </div>
                        </div>

                        <!-- Special order note -->
                        <div x-show="order.special_note" class="px-4 py-3 bg-amber-500/10 border-b border-amber-500/30 text-xs min-w-0">
                            <div class="flex items-center gap-1.5 font-extrabold uppercase text-[11px] text-amber-400 tracking-wider mb-1.5">
                                <i data-lucide="alert-triangle" class="w-3.5 h-3.5 shrink-0"></i><span>Special Order Note</span>
                            </div>
                            <div class="bg-[#0f0e13]/90 text-white font-medium p-2.5 rounded-lg border border-amber-500/25 leading-relaxed whitespace-pre-line break-words" x-text="order.special_note"></div>
                        </div>

                        <!-- Items -->
                        <div class="p-4 sm:p-5 space-y-3 flex-1 overflow-y-auto max-h-64 custom-scroll min-w-0">
                            <template x-for="item in order.items" :key="item.id">
                                <div class="bg-[#0f0e13] border border-[#2a2731] rounded-xl p-3.5 min-w-0">
                                    <div class="flex items-start justify-between gap-3 min-w-0">
                                        <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                            <span class="w-6 h-6 rounded-lg bg-[#b08d57] text-[#0f0e13] font-black text-xs flex items-center justify-center shrink-0" x-text="item.quantity + 'x'"></span>
                                            <span class="text-sm font-bold text-white truncate min-w-0" x-text="item.name"></span>
                                        </div>
                                        <span class="text-xs font-bold text-stone-400 shrink-0" x-text="'$' + (Number(item.subtotal) || 0).toFixed(2)"></span>
                                    </div>
                                    <p x-show="item.special_note" class="text-xs text-amber-400/90 mt-2 pl-8 break-words" x-text="item.special_note"></p>
                                </div>
                            </template>
                        </div>

                        <!-- Actions -->
                        <div class="p-4 sm:p-5 border-t border-[#2a2731] bg-[#0f0e13]/70 flex items-center gap-2">

                            <!-- Ready: Accept / Decline -->
                            <template x-if="order.status === 'ready'">
                                <div class="w-full flex gap-2">
                                    <button @click="declineOrder(order)"
                                            class="w-1/3 bg-[#1e1c25] hover:bg-rose-500/15 hover:text-rose-400 text-stone-300 font-bold text-xs py-3 rounded-xl transition flex items-center justify-center gap-2 border border-[#2a2731]">
                                        <i data-lucide="x" class="w-4 h-4"></i><span>Decline</span>
                                    </button>
                                    <button @click="acceptOrder(order)"
                                            :disabled="actionId === order.id"
                                            class="flex-1 bg-[#b08d57] hover:bg-[#c9a36b] text-[#0f0e13] font-extrabold text-xs py-3 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-[#b08d57]/10 disabled:opacity-50">
                                        <i data-lucide="check" class="w-4 h-4 stroke-[3]"></i><span>Accept Order</span>
                                    </button>
                                </div>
                            </template>

                            <!-- Out for delivery: Mark Delivered -->
                            <template x-if="order.status === 'out_for_delivery'">
                                <button @click="markDelivered(order)"
                                        :disabled="actionId === order.id"
                                        class="w-full bg-emerald-500 hover:bg-emerald-400 text-black font-extrabold text-xs py-3 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/10 disabled:opacity-50">
                                    <i data-lucide="check-check" class="w-4 h-4 stroke-[3]"></i><span>Mark as Delivered</span>
                                </button>
                            </template>

                            <!-- Delivered -->
                            <template x-if="order.status === 'delivered'">
                                <div class="w-full py-2 text-center text-xs text-emerald-400 font-bold flex items-center justify-center gap-2">
                                    <i data-lucide="check-circle-2" class="w-4 h-4"></i><span>Delivery Completed</span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Empty State -->
                <div x-show="filteredOrders.length === 0" class="col-span-full py-24 text-center bg-[#14131a]/40 border border-[#2a2731]/50 rounded-2xl">
                    <div class="w-16 h-16 rounded-2xl bg-[#14131a] border border-[#2a2731] flex items-center justify-center mx-auto text-[#b08d57] mb-3">
                        <i data-lucide="package-check" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-base font-bold text-white">No Orders Right Now</h3>
                    <p class="text-stone-500 text-xs mt-1" x-text="online ? 'New ready orders from the kitchen will pop up here automatically.' : 'Go online to start receiving delivery orders.'"></p>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    const INITIAL_ORDERS = @json($orders);
    const INITIAL_ONLINE = @json($online);
    const LIVE_ORDERS_URL = "{{ route('delivery.orders.live') }}";
    const ACCEPT_URL = (id) => `{{ url('/delivery/orders') }}/${id}/accept`;
    const DECLINE_URL = (id) => `{{ url('/delivery/orders') }}/${id}/decline`;
    const DELIVERED_URL = (id) => `{{ url('/delivery/orders') }}/${id}/delivered`;
    const TOGGLE_ONLINE_URL = "{{ route('delivery.online.toggle') }}";
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

    function deliveryApp() {
        return {
            mobileNavOpen: false,
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

            mapsUrl(order) {
                return `https://www.google.com/maps/search/?api=1&query=${order.latitude},${order.longitude}`;
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

                    // Notify only when online and a new ready order appeared
                    if (this.online && data.counts.ready > prevReady && this.soundEnabled) {
                        const newest = data.orders.find(o => o.status === 'ready');
                        this.playNotificationSound();
                        this.showToast('New Order Ready!', newest ? `Order #${newest.id} from ${newest.customer_name} is ready for pickup.` : 'A new order is ready for pickup.');
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
                    this.showToast(this.online ? 'You are now Online' : 'You are now Offline', this.online ? 'You will receive new ready orders.' : 'You will stop receiving new orders.');
                    this.$nextTick(() => lucide.createIcons());
                } catch (e) {
                    alert('Network error while toggling online status.');
                }
            },

            async acceptOrder(order) {
                this.actionId = order.id;
                try {
                    const res = await fetch(ACCEPT_URL(order.id), {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    if (res.ok) {
                        order.status = 'out_for_delivery';
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
                // Just remove from the driver's view; order stays ready for others
                this.orders = this.orders.filter(o => o.id !== order.id);
                this.updateCounts();
                this.showToast('Order Declined', `Order #${order.id} skipped.`);
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

    document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
</body>
</html>