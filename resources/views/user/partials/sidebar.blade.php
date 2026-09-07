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

            <!-- GROUP: Orders & Communication -->
            <div class="space-y-1">
                <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-500 block">Orders</span>
                <button data-target="orders" @click="mobileNavOpen = false" class="side-link w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i data-lucide="receipt" class="w-4 h-4"></i>
                        <span>My Orders</span>
                    </div>
                    <span x-show="$store.orders.activeCount > 0" 
                          class="w-2 h-2 rounded-full bg-[#b08d57] animate-ping" title="Active order tracking"></span>
                </button>
                <button data-target="chat" @click="mobileNavOpen = false" class="side-link w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-3">
                    <i data-lucide="message-square" class="w-4 h-4"></i>
                    <span>Chat Support</span>
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