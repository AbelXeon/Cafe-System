<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CraveDash | Kitchen & Staff Orders Dashboard</title>

    <!-- Laravel Vite Bundled Tailwind CSS & JS -->
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
<body class="bg-[#0f0e13] text-stone-200 h-screen overflow-hidden selection:bg-[#b08d57] selection:text-[#0f0e13]" x-data="staffApp()" x-init="init()">

<!-- Toast Notification -->
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
    <aside :class="mobileNavOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed lg:static top-0 left-0 bottom-0 w-72 lg:w-64 bg-[#0f0e13] border-r border-[#1e1c25] flex flex-col shrink-0 justify-between z-50 lg:z-20 transition-transform duration-300 ease-in-out">
        
        <div class="flex flex-col min-h-0">
            <div class="p-5 sm:p-6 border-b border-[#1e1c25] flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#b08d57] flex items-center justify-center text-[#0f0e13]">
                        <i data-lucide="chef-hat" class="w-5 h-5 stroke-[2.5]"></i>
                    </div>
                    <div>
                        <span class="text-lg font-black tracking-tight text-white block">Crave<span class="text-[#b08d57]">Dash</span></span>
                        <span class="text-xs text-stone-500 font-medium">Kitchen & Staff Portal</span>
                    </div>
                </div>

                <button @click="mobileNavOpen = false" class="lg:hidden p-1.5 rounded-xl text-stone-400 hover:text-white hover:bg-[#1e1c25] transition cursor-pointer" aria-label="Close Navigation">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Grouped Navigation Links -->
            <nav class="p-4 space-y-4 overflow-y-auto custom-scroll">
                
                <!-- Main Navigation Mode (Orders vs Stock) -->
                <div class="space-y-1">
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-500 block">Navigation</span>
                    
                    <button @click="currentView = 'orders'; mobileNavOpen = false" 
                            :class="currentView === 'orders' ? 'active' : ''" 
                            class="side-link w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold flex items-center justify-between cursor-pointer">
                        <div class="flex items-center gap-3">
                            <i data-lucide="ticket" class="w-4 h-4"></i>
                            <span>Kitchen Orders</span>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full font-bold bg-[#1e1c25] text-[#b08d57]" x-text="orders.length"></span>
                    </button>

                    <button @click="currentView = 'stock'; mobileNavOpen = false" 
                            :class="currentView === 'stock' ? 'active' : ''" 
                            class="side-link w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold flex items-center justify-between cursor-pointer">
                        <div class="flex items-center gap-3">
                            <i data-lucide="ban" class="w-4 h-4 text-rose-400"></i>
                            <span>Menu Stock & 86'd</span>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full font-bold bg-rose-500/20 text-rose-400" 
                              x-text="outOfStockCount"></span>
                    </button>
                </div>

                <!-- Order Status Filters (Only visible when currentView == 'orders') -->
                <div x-show="currentView === 'orders'" class="space-y-1 pt-2 border-t border-[#1e1c25]">
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-500 block">Order Queue</span>
                    
                    <button @click="activeTab = 'all'; mobileNavOpen = false" 
                            :class="activeTab === 'all' && currentView === 'orders' ? 'bg-[#1e1c25] text-white' : ''" 
                            class="side-link w-full text-left px-3.5 py-2 rounded-xl text-xs font-semibold flex items-center justify-between cursor-pointer">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                            <span>All Tickets</span>
                        </div>
                        <span class="text-xs font-bold" x-text="orders.length"></span>
                    </button>

                    <button @click="activeTab = 'pending'; mobileNavOpen = false" 
                            :class="activeTab === 'pending' && currentView === 'orders' ? 'bg-amber-500/20 text-amber-400' : ''" 
                            class="side-link w-full text-left px-3.5 py-2 rounded-xl text-xs font-semibold flex items-center justify-between cursor-pointer">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-400"></i>
                            <span>New Incoming</span>
                        </div>
                        <span class="text-xs font-bold text-amber-400" x-text="counts.pending"></span>
                    </button>

                    <button @click="activeTab = 'preparing'; mobileNavOpen = false" 
                            :class="activeTab === 'preparing' && currentView === 'orders' ? 'bg-sky-500/20 text-sky-400' : ''" 
                            class="side-link w-full text-left px-3.5 py-2 rounded-xl text-xs font-semibold flex items-center justify-between cursor-pointer">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="flame" class="w-3.5 h-3.5 text-sky-400"></i>
                            <span>Preparing</span>
                        </div>
                        <span class="text-xs font-bold text-sky-400" x-text="counts.preparing"></span>
                    </button>

                    <button @click="activeTab = 'ready'; mobileNavOpen = false" 
                            :class="activeTab === 'ready' && currentView === 'orders' ? 'bg-emerald-500/20 text-emerald-400' : ''" 
                            class="side-link w-full text-left px-3.5 py-2 rounded-xl text-xs font-semibold flex items-center justify-between cursor-pointer">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-400"></i>
                            <span>Ready / Done</span>
                        </div>
                        <span class="text-xs font-bold text-emerald-400" x-text="counts.ready"></span>
                    </button>
                </div>

                <div class="space-y-1 pt-2 border-t border-[#1e1c25]">
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-500 block">Sound Alert</span>
                    <button @click="toggleSound()" class="side-link w-full text-left px-3.5 py-2 rounded-xl text-xs font-semibold flex items-center justify-between cursor-pointer">
                        <div class="flex items-center gap-2.5">
                            <i :data-lucide="soundEnabled ? 'volume-2' : 'volume-x'" class="w-4 h-4"></i>
                            <span>Audio Notification</span>
                        </div>
                        <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded" 
                              :class="soundEnabled ? 'bg-emerald-500/20 text-emerald-400' : 'bg-stone-800 text-stone-500'" 
                              x-text="soundEnabled ? 'ON' : 'OFF'"></span>
                    </button>
                </div>

            </nav>
        </div>

        <!-- Staff Profile Card -->
        <div class="p-4 border-t border-[#1e1c25] shrink-0 bg-[#0f0e13]">
            <div class="flex items-center gap-3 mb-3 px-2">
                <div class="w-8 h-8 rounded-full bg-[#1e1c25] border border-[#2a2731] flex items-center justify-center font-bold text-xs text-[#b08d57]">
                    {{ strtoupper(substr(Auth::user()->fullname ?? Auth::user()->name ?? 'Staff', 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-white truncate">{{ Auth::user()->fullname ?? Auth::user()->name ?? 'Staff User' }}</p>
                    <p class="text-[10px] text-stone-500 capitalize">{{ Auth::user()->role->name ?? 'Kitchen Staff' }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-4 py-2.5 rounded-xl text-xs font-semibold text-rose-400 hover:bg-rose-500/10 transition flex items-center gap-3 cursor-pointer">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- VIEW 1: Orders Queue Feed -->
    <main x-show="currentView === 'orders'" class="flex-1 flex flex-col min-h-0 bg-[#14131a]/40 w-full overflow-hidden">
        <!-- Header Bar -->
        <div class="bg-[#0f0e13]/98 backdrop-blur-xl border-b border-[#2a2731] px-4 sm:px-6 lg:px-8 py-4 shrink-0 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight flex items-center gap-3">
                    <span>Live Kitchen Feed</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#b08d57]/15 border border-[#b08d57]/30 text-[#b08d57]">
                        <span class="w-2 h-2 rounded-full bg-[#b08d57] animate-pulse"></span>
                        Auto-refreshing (6s)
                    </span>
                </h1>
                <p class="text-stone-500 text-xs mt-0.5">Manage meal tickets, special requests, and order preparation statuses</p>
            </div>

            <!-- Category Tab Filters -->
            <div class="flex items-center gap-2 overflow-x-auto custom-scroll">
                <button @click="activeTab = 'all'"
                    :class="activeTab === 'all' ? 'bg-[#b08d57] text-[#0f0e13] font-bold shadow-md shadow-[#b08d57]/10' : 'bg-[#14131a] text-stone-400 border border-[#2a2731]'"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition shrink-0 cursor-pointer">
                    All (<span x-text="orders.length"></span>)
                </button>
                <button @click="activeTab = 'pending'"
                    :class="activeTab === 'pending' ? 'bg-amber-500 text-black font-bold' : 'bg-[#14131a] text-amber-400/80 border border-[#2a2731]'"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition shrink-0 flex items-center gap-1.5 cursor-pointer">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    Incoming (<span x-text="counts.pending"></span>)
                </button>
                <button @click="activeTab = 'preparing'"
                    :class="activeTab === 'preparing' ? 'bg-sky-500 text-black font-bold' : 'bg-[#14131a] text-sky-400 border border-[#2a2731]'"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition shrink-0 cursor-pointer">
                    Preparing (<span x-text="counts.preparing"></span>)
                </button>
                <button @click="activeTab = 'ready'"
                    :class="activeTab === 'ready' ? 'bg-emerald-500 text-black font-bold' : 'bg-[#14131a] text-emerald-400 border border-[#2a2731]'"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition shrink-0 cursor-pointer">
                    Ready (<span x-text="counts.ready"></span>)
                </button>
            </div>
        </div>

        <!-- Orders Grid Stream -->
        <div class="flex-1 overflow-y-auto custom-scroll p-4 sm:p-6 lg:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 gap-5">
                <template x-for="order in filteredOrders" :key="order.id">
                    <div class="bg-[#14131a] border rounded-2xl overflow-hidden flex flex-col justify-between shadow-xl transition-all duration-200 min-w-0"
                         :class="{
                            'border-amber-500/60 shadow-amber-500/5 ring-1 ring-amber-500/30': order.status === 'pending',
                            'border-sky-500/60 shadow-sky-500/5 ring-1 ring-sky-500/30': order.status === 'preparing',
                            'border-emerald-500/60 shadow-emerald-500/5': order.status === 'ready',
                            'border-[#2a2731] opacity-70': order.status === 'completed' || order.status === 'cancelled'
                         }">
                        
                        <!-- Order Ticket Header -->
                        <div class="p-4 sm:p-5 border-b border-[#2a2731] bg-[#0f0e13]/60 flex items-start justify-between gap-3 min-w-0">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-base font-black text-white shrink-0" x-text="'Order #' + order.id"></span>
                                    <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-md shrink-0"
                                          :class="{
                                            'bg-amber-500/20 text-amber-400 border border-amber-500/40': order.status === 'pending',
                                            'bg-sky-500/20 text-sky-400 border border-sky-500/40': order.status === 'preparing',
                                            'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40': order.status === 'ready',
                                            'bg-stone-800 text-stone-400': order.status === 'completed' || order.status === 'cancelled'
                                          }"
                                          x-text="order.status.replace('_', ' ')">
                                    </span>
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

                        <!-- Order Image Preview Display -->
                        <template x-if="getOrderImage(order)">
                            <div class="w-full h-40 bg-[#0f0e13] border-b border-[#2a2731] overflow-hidden relative group">
                                <img :src="getOrderImage(order)" 
                                     :alt="'Order #' + order.id" 
                                     class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105"
                                     loading="lazy">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#14131a] via-transparent to-transparent opacity-60 pointer-events-none"></div>
                            </div>
                        </template>

                        <!-- Special Order-level Note -->
                        <div x-show="order.special_note" class="px-4 py-3 bg-amber-500/10 border-b border-amber-500/30 text-xs min-w-0">
                            <div class="flex items-center gap-1.5 font-extrabold uppercase text-[11px] text-amber-400 tracking-wider mb-1.5">
                                <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-amber-400 shrink-0"></i>
                                <span>Special Order Note:</span>
                            </div>
                            <div class="bg-[#0f0e13]/90 text-white font-medium p-2.5 rounded-lg border border-amber-500/25 leading-relaxed whitespace-pre-line break-all sm:break-words min-w-0" x-text="order.special_note"></div>
                        </div>

                        <!-- Order Itemized List -->
                        <div class="p-4 sm:p-5 space-y-3 flex-1 overflow-y-auto max-h-80 custom-scroll min-w-0">
                            <template x-for="item in order.items" :key="item.id">
                                <div class="bg-[#0f0e13] border border-[#2a2731] rounded-xl p-3.5 flex flex-col justify-between gap-2.5 min-w-0 w-full overflow-hidden">
                                    <div class="flex items-start justify-between gap-3 min-w-0">
                                        <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                            <span class="w-6 h-6 rounded-lg bg-[#b08d57] text-[#0f0e13] font-black text-xs flex items-center justify-center shrink-0" x-text="item.quantity + 'x'"></span>
                                            <span class="text-sm font-bold text-white truncate min-w-0" x-text="item.name"></span>
                                        </div>
                                        <span class="text-xs font-bold text-stone-400 shrink-0" x-text="'$' + (Number(item.subtotal) || 0).toFixed(2)"></span>
                                    </div>

                                    <!-- Item-specific Special Instructions and Extras -->
                                    <div x-data="{ details: getItemDetails(item) }" 
                                         x-show="details.hasContent || item.special_note" 
                                         class="pt-2.5 border-t border-[#1e1c25] space-y-2.5 min-w-0 w-full overflow-hidden">
                                        
                                        <!-- Extras & Add-ons Section -->
                                        <template x-if="details.extras && details.extras.length > 0">
                                            <div class="space-y-1.5 min-w-0 w-full">
                                                <div class="flex items-center gap-1.5 font-black uppercase text-[10px] text-emerald-400 tracking-wider">
                                                    <i data-lucide="plus-circle" class="w-3.5 h-3.5 text-emerald-400 shrink-0"></i>
                                                    <span>Extras & Add-ons</span>
                                                </div>
                                                <div class="space-y-1 min-w-0 w-full">
                                                    <template x-for="(extra, idx) in details.extras" :key="idx">
                                                        <div class="flex items-start gap-2 text-xs font-medium text-white bg-[#191722] border border-[#2a2731] px-2.5 py-1.5 rounded-lg shadow-sm min-w-0 w-full overflow-hidden">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0 mt-1.5"></span>
                                                            <span class="leading-snug text-white font-medium break-all sm:break-words flex-1 min-w-0" x-text="extra"></span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Special Instructions & Notes Section -->
                                        <template x-if="details.notes && details.notes.length > 0">
                                            <div class="space-y-1.5 min-w-0 w-full">
                                                <div class="flex items-center gap-1.5 font-black uppercase text-[10px] text-amber-400 tracking-wider">
                                                    <i data-lucide="file-text" class="w-3.5 h-3.5 text-amber-400 shrink-0"></i>
                                                    <span>Special Instructions</span>
                                                </div>
                                                <div class="space-y-1 min-w-0 w-full">
                                                    <template x-for="(noteLine, nIdx) in details.notes" :key="nIdx">
                                                        <div class="flex items-start gap-2 text-xs font-medium text-white bg-amber-500/10 border border-amber-500/30 px-2.5 py-1.5 rounded-lg leading-relaxed shadow-sm min-w-0 w-full overflow-hidden">
                                                            <i data-lucide="corner-down-right" class="w-3.5 h-3.5 text-amber-400 shrink-0 mt-0.5"></i>
                                                            <span class="flex-1 min-w-0 text-white font-medium break-all sm:break-words leading-relaxed" x-text="noteLine"></span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Raw Text Fallback -->
                                        <template x-if="!details.hasContent && item.special_note">
                                            <div class="bg-amber-500/10 border border-amber-500/30 rounded-lg p-2.5 min-w-0 w-full overflow-hidden">
                                                <div class="flex items-center gap-1.5 font-bold uppercase text-[10px] text-amber-400 tracking-wider mb-1">
                                                    <i data-lucide="file-text" class="w-3.5 h-3.5 text-amber-400 shrink-0"></i>
                                                    <span>Item Note</span>
                                                </div>
                                                <p class="text-xs font-medium text-white bg-[#0f0e13]/80 p-2 rounded-md border border-amber-500/20 leading-relaxed whitespace-pre-line break-all sm:break-words min-w-0" x-text="item.special_note"></p>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Action Buttons Section -->
                        <div class="p-4 sm:p-5 border-t border-[#2a2731] bg-[#0f0e13]/70 flex items-center gap-2">
                            <template x-if="order.status === 'pending'">
                                <button @click="updateStatus(order, 'preparing')"
                                        :disabled="updatingId === order.id"
                                        class="w-full bg-amber-500 hover:bg-amber-400 text-black font-extrabold text-xs py-3 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-amber-500/10 cursor-pointer disabled:opacity-50">
                                    <i data-lucide="flame" class="w-4 h-4"></i>
                                    <span>Accept & Start Preparing</span>
                                </button>
                            </template>

                            <template x-if="order.status === 'preparing'">
                                <div class="w-full flex gap-2">
                                    <button @click="updateStatus(order, 'ready')"
                                            :disabled="updatingId === order.id"
                                            class="flex-1 bg-emerald-500 hover:bg-emerald-400 text-black font-extrabold text-xs py-3 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/10 cursor-pointer disabled:opacity-50">
                                        <i data-lucide="check" class="w-4 h-4 stroke-[3]"></i>
                                        <span>Mark Food as Done (Ready)</span>
                                    </button>
                                </div>
                            </template>

                            <template x-if="order.status === 'ready'">
                                <button @click="updateStatus(order, 'completed')"
                                        :disabled="updatingId === order.id"
                                        class="w-full bg-[#1e1c25] hover:bg-[#2a2731] text-stone-200 font-bold text-xs py-3 rounded-xl transition flex items-center justify-center gap-2 border border-[#2a2731] cursor-pointer disabled:opacity-50">
                                    <i data-lucide="check-check" class="w-4 h-4 text-[#b08d57]"></i>
                                    <span>Complete Ticket</span>
                                </button>
                            </template>

                            <template x-if="order.status === 'completed' || order.status === 'cancelled'">
                                <div class="w-full py-2 text-center text-xs text-stone-500 font-semibold">
                                    Ticket Closed
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Empty State -->
                <div x-show="filteredOrders.length === 0" class="col-span-full py-24 text-center bg-[#14131a]/40 border border-[#2a2731]/50 rounded-2xl">
                    <div class="w-16 h-16 rounded-2xl bg-[#14131a] border border-[#2a2731] flex items-center justify-center mx-auto text-[#b08d57] mb-3">
                        <i data-lucide="utensils-crossed" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-base font-bold text-white">No Orders in this Queue</h3>
                    <p class="text-stone-500 text-xs mt-1">New incoming orders from customers will appear here automatically.</p>
                </div>
            </div>
        </div>
    </main>

    <!-- VIEW 2: Menu Stock & Availability Data Table (86 List) -->
    <main x-show="currentView === 'stock'" class="flex-1 flex flex-col min-h-0 bg-[#14131a]/40 w-full overflow-hidden">
        
        <!-- Header Bar -->
        <div class="bg-[#0f0e13]/98 backdrop-blur-xl border-b border-[#2a2731] px-4 sm:px-6 lg:px-8 py-4 shrink-0 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight flex items-center gap-3">
                    <span>Kitchen Stock & 86'd Items</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold"
                          :class="outOfStockCount > 0 ? 'bg-rose-500/15 border border-rose-500/30 text-rose-400' : 'bg-emerald-500/15 border border-emerald-500/30 text-emerald-400'">
                        <span class="w-2 h-2 rounded-full" :class="outOfStockCount > 0 ? 'bg-rose-500 animate-ping' : 'bg-emerald-500'"></span>
                        <span x-text="outOfStockCount + ' Items Out of Stock'"></span>
                    </span>
                </h1>
                <p class="text-stone-500 text-xs mt-0.5">Toggle products or add-ons unavailable when you run out. Customers will immediately be prevented from ordering them.</p>
            </div>

            <!-- Fast Search & Category Filter Controls -->
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <div class="relative flex-1 sm:w-64">
                    <i data-lucide="search" class="w-4 h-4 text-stone-500 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input type="text" 
                           x-model="stockSearch" 
                           placeholder="Search dish, meat, fries..." 
                           class="w-full bg-[#14131a] border border-[#2a2731] rounded-xl pl-9 pr-4 py-2 text-xs text-white placeholder-stone-500 cd-input focus:outline-none">
                </div>

                <select x-model="stockFilter" class="bg-[#14131a] border border-[#2a2731] rounded-xl px-3 py-2 text-xs text-white cd-input focus:outline-none cursor-pointer">
                    <option value="all">All Items</option>
                    <option value="products">Dishes / Meals</option>
                    <option value="extras">Extras & Add-ons</option>
                    <option value="out_of_stock">Only 86'd (Out of Stock)</option>
                </select>
            </div>
        </div>

        <!-- Stock Data Table Area -->
        <div class="flex-1 overflow-y-auto custom-scroll p-4 sm:p-6 lg:p-8">
            <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#0f0e13]/90 border-b border-[#2a2731] text-[11px] uppercase tracking-wider text-stone-400 font-bold">
                                <th class="py-3.5 px-4 sm:px-6">Item</th>
                                <th class="py-3.5 px-4">Type</th>
                                <th class="py-3.5 px-4">Category</th>
                                <th class="py-3.5 px-4">Price</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                                <th class="py-3.5 px-4 sm:px-6 text-right">Quick Stock Toggle</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1e1c25] text-xs">
                            <template x-for="item in filteredStockItems" :key="item.type + '-' + item.id">
                                <tr class="hover:bg-[#181622]/60 transition-colors"
                                    :class="!item.is_available ? 'bg-rose-500/[0.03]' : ''">
                                    
                                    <!-- Item Name & Thumbnail -->
                                    <td class="py-3.5 px-4 sm:px-6">
                                        <div class="flex items-center gap-3">
                                            <template x-if="item.image">
                                                <img :src="item.image" :alt="item.name" class="w-10 h-10 rounded-xl object-cover border border-[#2a2731] shrink-0">
                                            </template>
                                            <template x-if="!item.image">
                                                <div class="w-10 h-10 rounded-xl bg-[#1e1c25] border border-[#2a2731] flex items-center justify-center text-stone-500 shrink-0">
                                                    <i :data-lucide="item.type === 'Product' ? 'utensils' : 'plus'" class="w-4 h-4"></i>
                                                </div>
                                            </template>
                                            <div class="min-w-0">
                                                <p class="font-bold text-white truncate" x-text="item.name"></p>
                                                <p class="text-[10px] text-stone-500 truncate" x-text="item.description || (item.type === 'Product' ? 'Meal Dish' : 'Add-on Modifier')"></p>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Type -->
                                    <td class="py-3.5 px-4">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold"
                                              :class="item.type === 'Product' ? 'bg-[#b08d57]/20 text-[#b08d57] border border-[#b08d57]/30' : 'bg-purple-500/20 text-purple-300 border border-purple-500/30'"
                                              x-text="item.type"></span>
                                    </td>

                                    <!-- Category -->
                                    <td class="py-3.5 px-4 text-stone-300" x-text="item.category_name"></td>

                                    <!-- Price -->
                                    <td class="py-3.5 px-4 font-bold text-[#b08d57]" x-text="'$' + Number(item.price).toFixed(2)"></td>

                                    <!-- Status Pill -->
                                    <td class="py-3.5 px-4 text-center">
                                        <template x-if="item.is_available">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                                Available
                                            </span>
                                        </template>
                                        <template x-if="!item.is_available">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-500/15 text-rose-400 border border-rose-500/30 animate-pulse">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                86 / Out of Stock
                                            </span>
                                        </template>
                                    </td>

                                    <!-- Availability Toggle Button -->
                                    <td class="py-3.5 px-4 sm:px-6 text-right">
                                        <button @click="toggleItemAvailability(item)"
                                                :disabled="stockTogglingId === (item.type + '-' + item.id)"
                                                :class="item.is_available 
                                                    ? 'bg-rose-500/10 hover:bg-rose-500 text-rose-400 hover:text-white border border-rose-500/30' 
                                                    : 'bg-emerald-500/10 hover:bg-emerald-500 text-emerald-400 hover:text-black border border-emerald-500/30'"
                                                class="px-3.5 py-2 rounded-xl text-xs font-bold transition inline-flex items-center gap-2 cursor-pointer disabled:opacity-50">
                                            <i :data-lucide="item.is_available ? 'slash' : 'check'" class="w-3.5 h-3.5"></i>
                                            <span x-text="item.is_available ? 'Mark as Out (86)' : 'Make Available'"></span>
                                        </button>
                                    </td>
                                </tr>
                            </template>

                            <tr x-show="filteredStockItems.length === 0">
                                <td colspan="6" class="py-12 text-center text-stone-500">
                                    <i data-lucide="search-x" class="w-8 h-8 mx-auto mb-2 opacity-60"></i>
                                    <p class="font-bold text-stone-400 text-sm">No items found matching filter.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

</div>

<script>
    const INITIAL_ORDERS = @json($orders);
    const INITIAL_PRODUCTS = @json($products ?? []);
    const INITIAL_EXTRAS = @json($extras ?? []);

    const STATUS_UPDATE_URL = "{{ url('/staff/orders') }}";
    const LIVE_ORDERS_URL = "{{ route('staff.orders.live') }}";
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

    function staffApp() {
        return {
            mobileNavOpen: false,
            currentView: 'orders', // 'orders' or 'stock'
            orders: INITIAL_ORDERS || [],
            activeTab: 'all',
            updatingId: null,
            soundEnabled: true,
            stockSearch: '',
            stockFilter: 'all',
            stockTogglingId: null,

            // Raw Stock items list
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
                
                // Polling for live orders every 6 seconds
                setInterval(() => {
                    this.fetchLiveOrders();
                }, 6000);

                setTimeout(() => lucide.createIcons(), 60);
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