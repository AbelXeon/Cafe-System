<section id="section-overview" class="page-section">
    <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Store Overview &amp; Analytics</h2>
            <p class="text-stone-500 text-xs sm:text-sm mt-1">Real-time performance, revenue in ETB, and store metrics</p>
        </div>
    </div>

    <!-- Top Metric KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-8">
        <!-- Total Revenue in ETB -->
        <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-5 shadow-lg relative overflow-hidden group hover:border-[#b08d57]/50 transition duration-300">
            <div class="flex items-center justify-between">
                <span class="text-stone-500 text-xs uppercase tracking-wider font-bold">Total Revenue</span>
                <div class="w-9 h-9 rounded-xl bg-[#b08d57]/10 text-[#b08d57] flex items-center justify-center font-bold text-xs">
                    ETB
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-black text-white mt-2">{{ number_format($totalRevenue, 2) }} <span class="text-sm font-bold text-[#b08d57]">ETB</span></p>
            <div class="flex items-center gap-1.5 mt-2 text-[11px] {{ $revenueGrowthPercent >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                <i data-lucide="{{ $revenueGrowthPercent >= 0 ? 'trending-up' : 'trending-down' }}" class="w-3.5 h-3.5"></i>
                <span>{{ $revenueGrowthPercent >= 0 ? '+' : '' }}{{ $revenueGrowthPercent }}% from last week</span>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-5 shadow-lg relative overflow-hidden group hover:border-[#b08d57]/50 transition duration-300">
            <div class="flex items-center justify-between">
                <span class="text-stone-500 text-xs uppercase tracking-wider font-bold">Total Orders</span>
                <div class="w-9 h-9 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-black text-white mt-2">{{ $totalOrders }}</p>
            <div class="flex items-center gap-1.5 mt-2 text-[11px] text-stone-400">
                <i data-lucide="activity" class="w-3.5 h-3.5 text-stone-500"></i>
                <span>Active store orders</span>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-5 shadow-lg relative overflow-hidden group hover:border-[#b08d57]/50 transition duration-300">
            <div class="flex items-center justify-between">
                <span class="text-stone-500 text-xs uppercase tracking-wider font-bold">Customers</span>
                <div class="w-9 h-9 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-black text-white mt-2">{{ $totalCustomers }}</p>
            <div class="flex items-center gap-1.5 mt-2 text-[11px] text-emerald-400">
                <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                <span>Registered users</span>
            </div>
        </div>

        <!-- Active Menu Items -->
        <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-5 shadow-lg relative overflow-hidden group hover:border-[#b08d57]/50 transition duration-300">
            <div class="flex items-center justify-between">
                <span class="text-stone-500 text-xs uppercase tracking-wider font-bold">Menu Products</span>
                <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center">
                    <i data-lucide="utensils-crossed" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-black text-white mt-2">{{ $products->count() }}</p>
            <div class="flex items-center gap-1.5 mt-2 text-[11px] text-stone-400">
                <span>{{ $categories->count() }} categories &bull; {{ $extras->count() }} extras</span>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Revenue (ETB) & Orders Trend Chart -->
        <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-5 sm:p-6 shadow-lg lg:col-span-2 flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                <div>
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <i data-lucide="trending-up" class="w-4 h-4 text-[#b08d57]"></i> Revenue (ETB) &amp; Orders Trend
                    </h3>
                    <p class="text-stone-500 text-xs">Past 7 days financial progression</p>
                </div>
                <span class="text-[11px] font-semibold text-[#b08d57] bg-[#b08d57]/10 border border-[#b08d57]/20 px-2.5 py-1 rounded-full self-start sm:self-auto">
                    Daily Breakdown
                </span>
            </div>
            <div class="relative h-64 sm:h-72 w-full">
                <canvas id="revenueTrendChart"></canvas>
            </div>
        </div>

        <!-- Category Distribution Donut Chart -->
        <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-5 sm:p-6 shadow-lg flex flex-col justify-between">
            <div class="mb-3">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <i data-lucide="pie-chart" class="w-4 h-4 text-[#b08d57]"></i> Category Distribution
                </h3>
                <p class="text-stone-500 text-xs">Menu item density by category</p>
            </div>
            <div class="relative h-56 sm:h-64 w-full flex items-center justify-center">
                <canvas id="categoryDonutChart"></canvas>
            </div>
            <div class="pt-3 border-t border-[#2a2731]/60 flex items-center justify-between text-xs text-stone-400">
                <span>Total Categories</span>
                <span class="font-bold text-white">{{ $categories->count() }}</span>
            </div>
        </div>
    </div>

    <!-- Bottom Row: Highlights & Recent Actions Feed -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Operations Summary -->
        <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-5 sm:p-6 shadow-lg space-y-4">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <i data-lucide="zap" class="w-4 h-4 text-[#b08d57]"></i> Operations Team
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 rounded-xl bg-[#0f0e13] border border-[#2a2731]">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                            <i data-lucide="truck" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">Delivery Fleet</p>
                            <p class="text-[10px] text-stone-500">Drivers registered</p>
                        </div>
                    </div>
                    <span class="text-sm font-bold text-white">{{ $staff->filter(fn($s) => $s->role->name === 'delivery')->count() }}</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-xl bg-[#0f0e13] border border-[#2a2731]">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-400 flex items-center justify-center">
                            <i data-lucide="shield-check" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">Kitchen &amp; Staff</p>
                            <p class="text-[10px] text-stone-500">Active personnel</p>
                        </div>
                    </div>
                    <span class="text-sm font-bold text-white">{{ $staff->filter(fn($s) => $s->role->name === 'staff')->count() }}</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-xl bg-[#0f0e13] border border-[#2a2731]">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-[#b08d57]/10 text-[#b08d57] flex items-center justify-center">
                            <i data-lucide="sparkles" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">Add-on Extras</p>
                            <p class="text-[10px] text-stone-500">Side options active</p>
                        </div>
                    </div>
                    <span class="text-sm font-bold text-white">{{ $extras->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Recent Admin Activity Stream -->
        <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-5 sm:p-6 shadow-lg lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <i data-lucide="history" class="w-4 h-4 text-[#b08d57]"></i> Recent Activity Log
                </h3>
                <span class="text-xs text-stone-500">Database audit trail</span>
            </div>

            <div class="space-y-3 overflow-y-auto max-h-64 custom-scroll pr-1">
                @forelse ($recentActions as $action)
                    <div class="flex items-start justify-between p-3 rounded-xl bg-[#0f0e13] border border-[#2a2731]/70 hover:border-[#b08d57]/40 transition">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-[#1e1c25] flex items-center justify-center text-[#b08d57] shrink-0 mt-0.5">
                                <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-white">{{ $action->description }}</p>
                                <p class="text-[10px] text-stone-500 mt-0.5">By {{ $action->admin->fullname ?? $action->admin->username ?? 'Admin' }}</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-medium text-stone-500 whitespace-nowrap ml-2">{{ $action->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <div class="p-6 text-center text-stone-500 text-xs">
                        <i data-lucide="clock" class="w-6 h-6 mx-auto mb-2 opacity-50"></i>
                        No recent activity logged yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>