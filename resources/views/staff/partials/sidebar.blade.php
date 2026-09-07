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
            
            <div class="space-y-1">
                <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-500 block">Order Queue</span>
                
                <button @click="activeTab = 'all'; mobileNavOpen = false" 
                        :class="activeTab === 'all' ? 'active' : ''" 
                        class="side-link w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold flex items-center justify-between cursor-pointer">
                    <div class="flex items-center gap-3">
                        <i data-lucide="layers" class="w-4 h-4"></i>
                        <span>All Orders</span>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full font-bold" 
                          :class="activeTab === 'all' ? 'bg-[#0f0e13] text-[#b08d57]' : 'bg-[#1e1c25] text-stone-400'" 
                          x-text="orders.length"></span>
                </button>

                <button @click="activeTab = 'pending'; mobileNavOpen = false" 
                        :class="activeTab === 'pending' ? 'active' : ''" 
                        class="side-link w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold flex items-center justify-between cursor-pointer">
                    <div class="flex items-center gap-3">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                        <span>New Incoming</span>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full font-bold" 
                          :class="activeTab === 'pending' ? 'bg-[#0f0e13] text-amber-500' : 'bg-amber-500/20 text-amber-400'" 
                          x-text="counts.pending"></span>
                </button>

                <button @click="activeTab = 'preparing'; mobileNavOpen = false" 
                        :class="activeTab === 'preparing' ? 'active' : ''" 
                        class="side-link w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold flex items-center justify-between cursor-pointer">
                    <div class="flex items-center gap-3">
                        <i data-lucide="flame" class="w-4 h-4"></i>
                        <span>In Preparation</span>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full font-bold" 
                          :class="activeTab === 'preparing' ? 'bg-[#0f0e13] text-sky-400' : 'bg-sky-500/20 text-sky-400'" 
                          x-text="counts.preparing"></span>
                </button>

                <button @click="activeTab = 'ready'; mobileNavOpen = false" 
                        :class="activeTab === 'ready' ? 'active' : ''" 
                        class="side-link w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold flex items-center justify-between cursor-pointer">
                    <div class="flex items-center gap-3">
                        <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                        <span>Ready / Done</span>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full font-bold" 
                          :class="activeTab === 'ready' ? 'bg-[#0f0e13] text-emerald-400' : 'bg-emerald-500/20 text-emerald-400'" 
                          x-text="counts.ready"></span>
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