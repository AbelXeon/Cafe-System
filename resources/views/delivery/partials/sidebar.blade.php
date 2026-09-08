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

<!-- Driver Sidebar -->
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

        <!-- Online / Offline Status Toggle Card -->
        <div class="p-4">
            <button @click="toggleOnline()"
                    class="w-full rounded-2xl p-4 flex items-center gap-3 transition border cursor-pointer"
                    :class="online ? 'bg-emerald-500/15 border-emerald-500/40 text-emerald-400 shadow-lg shadow-emerald-500/10' : 'bg-[#14131a] border-[#2a2731] text-stone-400 hover:border-[#b08d57]/40'">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                     :class="online ? 'bg-emerald-500/20' : 'bg-[#1e1c25]'">
                    <i :data-lucide="online ? 'radio' : 'radio-off'" class="w-5 h-5"></i>
                </div>
                <div class="text-left flex-1 min-w-0">
                    <p class="text-sm font-bold" x-text="online ? 'You are Online' : 'You are Offline'"></p>
                    <p class="text-[11px] mt-0.5" x-text="online ? 'Receiving ready orders' : 'Tap to go online'"></p>
                </div>
            </button>
        </div>

        <nav class="px-4 pb-4 space-y-1 overflow-y-auto custom-scroll">
            <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-500 block mb-1">Delivery Queue</span>

            <button @click="showSection('orders'); activeTab = 'all'; mobileNavOpen = false" 
                    :class="(activeView === 'orders' && activeTab === 'all') ? 'active' : ''" 
                    class="side-link w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold flex items-center justify-between cursor-pointer">
                <div class="flex items-center gap-3">
                    <i data-lucide="layers" class="w-4 h-4"></i><span>All Orders</span>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full font-bold" :class="(activeView === 'orders' && activeTab === 'all') ? 'bg-[#0f0e13] text-[#b08d57]' : 'bg-[#1e1c25] text-stone-400'" x-text="orders.length"></span>
            </button>

            <button @click="showSection('orders'); activeTab = 'ready'; mobileNavOpen = false" 
                    :class="(activeView === 'orders' && activeTab === 'ready') ? 'active' : ''" 
                    class="side-link w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold flex items-center justify-between cursor-pointer">
                <div class="flex items-center gap-3">
                    <i data-lucide="bell-ring" class="w-4 h-4"></i><span>Incoming</span>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full font-bold" :class="(activeView === 'orders' && activeTab === 'ready') ? 'bg-[#0f0e13] text-amber-500' : 'bg-amber-500/20 text-amber-400'" x-text="counts.ready"></span>
            </button>

            <button @click="showSection('orders'); activeTab = 'out_for_delivery'; mobileNavOpen = false" 
                    :class="(activeView === 'orders' && activeTab === 'out_for_delivery') ? 'active' : ''" 
                    class="side-link w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold flex items-center justify-between cursor-pointer">
                <div class="flex items-center gap-3">
                    <i data-lucide="bike" class="w-4 h-4"></i><span>Out for Delivery</span>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full font-bold" :class="(activeView === 'orders' && activeTab === 'out_for_delivery') ? 'bg-[#0f0e13] text-sky-400' : 'bg-sky-500/20 text-sky-400'" x-text="counts.out_for_delivery"></span>
            </button>

            <button @click="showSection('orders'); activeTab = 'delivered'; mobileNavOpen = false" 
                    :class="(activeView === 'orders' && activeTab === 'delivered') ? 'active' : ''" 
                    class="side-link w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold flex items-center justify-between cursor-pointer">
                <div class="flex items-center gap-3">
                    <i data-lucide="check-circle-2" class="w-4 h-4"></i><span>Delivered</span>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full font-bold" :class="(activeView === 'orders' && activeTab === 'delivered') ? 'bg-[#0f0e13] text-emerald-400' : 'bg-emerald-500/20 text-emerald-400'" x-text="counts.delivered"></span>
            </button>

            <div class="pt-3 mt-3 border-t border-[#1e1c25]">
                <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-500 block mb-1">Communication</span>
                <button @click="showSection('chat'); mobileNavOpen = false" 
                        :class="activeView === 'chat' ? 'active' : ''" 
                        class="side-link w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-3 cursor-pointer">
                    <i data-lucide="message-square" class="w-4 h-4"></i><span>Chat Support</span>
                </button>
            </div>

            <div class="pt-3 mt-3 border-t border-[#1e1c25]">
                <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-500 block mb-1">Settings & Profile</span>
                
                <!-- Account Settings Tab -->
                <button @click="showSection('profile'); mobileNavOpen = false" 
                        :class="activeView === 'profile' ? 'active' : ''" 
                        class="side-link w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-3 cursor-pointer">
                    <i data-lucide="user-cog" class="w-4 h-4"></i><span>Account Settings</span>
                </button>

                <button @click="toggleSound()" class="side-link w-full text-left px-3.5 py-2 rounded-xl text-xs font-semibold flex items-center justify-between cursor-pointer mt-1">
                    <div class="flex items-center gap-2.5">
                        <i :data-lucide="soundEnabled ? 'volume-2' : 'volume-x'" class="w-4 h-4"></i><span>Audio Alerts</span>
                    </div>
                    <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded" :class="soundEnabled ? 'bg-emerald-500/20 text-emerald-400' : 'bg-stone-800 text-stone-500'" x-text="soundEnabled ? 'ON' : 'OFF'"></span>
                </button>
            </div>
        </nav>
    </div>

    <div class="p-4 border-t border-[#1e1c25] shrink-0 bg-[#0f0e13]">
        <div class="flex items-center gap-3 mb-3 px-2">
            <div class="w-9 h-9 rounded-xl bg-[#1e1c25] border border-[#2a2731] flex items-center justify-center font-black text-xs text-[#b08d57]">
                {{ strtoupper(substr(Auth::user()->fullname ?? Auth::user()->name ?? 'Driver', 0, 2)) }}
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-white truncate">{{ Auth::user()->fullname ?? Auth::user()->name ?? 'Driver' }}</p>
                <p class="text-[10px] text-stone-500 capitalize">{{ Auth::user()->role->name ?? 'Delivery Courier' }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full text-left px-4 py-2.5 rounded-xl text-xs font-semibold text-rose-400 hover:bg-rose-500/10 transition flex items-center gap-3 cursor-pointer">
                <i data-lucide="log-out" class="w-4 h-4"></i><span>Sign Out</span>
            </button>
        </form>
    </div>
</aside>