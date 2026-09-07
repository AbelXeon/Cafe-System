<!-- Mobile Backdrop Overlay -->
<div id="sidebar-backdrop" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity"></div>

{{-- Sidebar --}}
<aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-64 lg:w-60 bg-[#0f0e13] border-r border-[#1e1c25] flex flex-col shrink-0 -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
    
    <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-[#1e1c25] flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-[#b08d57] flex items-center justify-center text-[#0f0e13]">
                <i data-lucide="utensils" class="w-4 h-4 stroke-[2.5]"></i>
            </div>
            <div>
                <span class="text-white font-bold text-base block">Crave<span class="text-[#b08d57]">Dash</span></span>
                <span class="text-xs text-stone-500 font-medium">Admin Panel</span>
            </div>
        </div>

        <button id="close-sidebar-btn" type="button" class="lg:hidden p-1.5 rounded-xl text-stone-400 hover:text-white hover:bg-[#1e1c25] transition" aria-label="Close Menu">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>

    <!-- Grouped Sidebar Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-4 overflow-y-auto custom-scroll">
        
        <!-- GROUP: Dashboard -->
        <div class="space-y-1">
            <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-500 block">Dashboard</span>
            <button data-target="overview" class="nav-link w-full text-left px-3 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-3">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i><span>Overview</span>
            </button>
        </div>

        <!-- GROUP: Restaurant -->
        <div class="space-y-1">
            <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-500 block">Restaurant</span>
            <button data-target="products" class="nav-link w-full text-left px-3 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-3">
                <i data-lucide="package" class="w-4 h-4"></i><span>Products</span>
            </button>
            <button data-target="extras" class="nav-link w-full text-left px-3 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-3">
                <i data-lucide="sparkles" class="w-4 h-4"></i><span>Extras</span>
            </button>
        </div>

        <!-- GROUP: Workforce -->
        <div class="space-y-1">
            <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-500 block">Workforce</span>
            <button data-target="staff" class="nav-link w-full text-left px-3 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-3">
                <i data-lucide="users" class="w-4 h-4"></i><span>Staff</span>
            </button>
        </div>

    </nav>

    <form method="POST" action="{{ route('logout') }}" class="p-3 border-t border-[#1e1c25]">
        @csrf
        <button class="w-full text-left px-3 py-2.5 rounded-xl text-sm font-semibold text-rose-400 hover:bg-rose-500/10 transition flex items-center gap-3">
            <i data-lucide="log-out" class="w-4 h-4"></i><span>Logout</span>
        </button>
    </form>
</aside>