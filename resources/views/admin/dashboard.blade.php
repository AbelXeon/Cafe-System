<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard | CraveDash</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts & Lucide Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #2a2731; border-radius: 9999px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #3a3741; }
        .nav-link { color: #a8a29e; transition: all 0.15s ease-in-out; }
        .nav-link:hover { color: #f5f5f4; background: #1e1c25; }
        .nav-link.active { background: #b08d57; color: #0f0e13; font-weight: 700; }
        .cd-input:focus { border-color: #b08d57; box-shadow: 0 0 0 3px rgba(176, 141, 87, 0.18); }
        .cd-input { background-color: #0f0e13; border: 1px solid #2a2731; }
        .cd-input::placeholder { color: #57534e; }
    </style>
</head>
<body class="bg-[#0f0e13] text-stone-200 h-screen overflow-hidden selection:bg-[#b08d57] selection:text-[#0f0e13]">

<!-- Mobile Top Navigation Bar (Visible on mobile/tablet < lg) -->
<header class="lg:hidden bg-[#0f0e13] border-b border-[#1e1c25] px-4 py-3.5 flex items-center justify-between z-30 shrink-0">
    <div class="flex items-center gap-3">
        <button id="open-sidebar-btn" type="button" class="p-2 -ml-2 rounded-xl text-stone-400 hover:text-white hover:bg-[#1e1c25] transition" aria-label="Open Menu">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-[#b08d57] flex items-center justify-center text-[#0f0e13]">
                <i data-lucide="utensils" class="w-4 h-4 stroke-[2.5]"></i>
            </div>
            <div>
                <span class="text-white font-bold text-sm block leading-tight">Crave<span class="text-[#b08d57]">Dash</span></span>
                <span class="text-[10px] text-stone-500 font-medium">Admin Panel</span>
            </div>
        </div>
    </div>
</header>

<div class="flex h-[calc(100vh-57px)] lg:h-full w-full relative">

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

        <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto custom-scroll">
            <button data-target="overview" class="nav-link w-full text-left px-3 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-3">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i><span>Overview</span>
            </button>
            <button data-target="products" class="nav-link w-full text-left px-3 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-3">
                <i data-lucide="package" class="w-4 h-4"></i><span>Products</span>
            </button>
            <button data-target="staff" class="nav-link w-full text-left px-3 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-3">
                <i data-lucide="users" class="w-4 h-4"></i><span>Staff</span>
            </button>
            <button data-target="extras" class="nav-link w-full text-left px-3 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-3">
                <i data-lucide="sparkles" class="w-4 h-4"></i><span>Extras</span>
            </button>
        </nav>

        <form method="POST" action="{{ route('logout') }}" class="p-3 border-t border-[#1e1c25]">
            @csrf
            <button class="w-full text-left px-3 py-2.5 rounded-xl text-sm font-semibold text-rose-400 hover:bg-rose-500/10 transition flex items-center gap-3">
                <i data-lucide="log-out" class="w-4 h-4"></i><span>Logout</span>
            </button>
        </form>
    </aside>

    {{-- Main Content Area --}}
    <main class="flex-1 p-4 sm:p-6 lg:p-10 overflow-y-auto custom-scroll">

        {{-- ================= OVERVIEW SECTION ================= --}}
        <section id="section-overview" class="page-section">
            <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Store Overview &amp; Analytics</h2>
                    <p class="text-stone-500 text-xs sm:text-sm mt-1">Real-time performance, revenue in ETB, and store metrics</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Live Store Status
                    </span>
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

        {{-- ================= PRODUCTS SECTION ================= --}}
        <section id="section-products" class="page-section hidden">
            <div class="mb-6 sm:mb-8">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Products</h2>
                <p class="text-stone-500 text-xs sm:text-sm mt-1">Add and manage your menu items</p>
            </div>

            <form id="product-form" class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 mb-8 shadow-lg">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Category</label>
                    
                    <div class="relative custom-select-wrapper" id="product-category-wrapper">
                        <select name="category_id" required class="custom-native-select opacity-0 absolute pointer-events-none h-0 w-0">
                            <option value="">Select category</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        
                        <button type="button" class="custom-select-trigger cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-left flex items-center justify-between transition group hover:border-[#b08d57]/70">
                            <div class="flex items-center gap-2.5 truncate pr-2">
                                <div class="w-2 h-2 rounded-full bg-stone-600 transition trigger-dot"></div>
                                <span class="custom-select-label text-stone-500 font-medium truncate" data-placeholder="Select category">Select category</span>
                            </div>
                            <div class="w-6 h-6 rounded-lg bg-[#1e1c25] group-hover:bg-[#2a2731] flex items-center justify-center text-stone-400 group-hover:text-stone-200 shrink-0 transition">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-300 chevron-icon"></i>
                            </div>
                        </button>

                        <div class="custom-select-menu hidden absolute left-0 right-0 top-[calc(100%+6px)] bg-[#14131a]/95 backdrop-blur-xl border border-[#2a2731] rounded-xl p-1.5 shadow-2xl z-50 max-h-56 overflow-y-auto custom-scroll space-y-1">
                            @foreach ($categories as $cat)
                                <div class="custom-option px-3 py-2.5 rounded-lg text-sm text-stone-300 hover:text-white hover:bg-[#1e1c25] cursor-pointer flex items-center justify-between transition group/opt" data-value="{{ $cat->id }}" data-text="{{ $cat->name }}">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-2 h-2 rounded-full bg-stone-600 group-hover/opt:bg-[#b08d57] transition dot-indicator"></div>
                                        <span class="font-medium">{{ $cat->name }}</span>
                                    </div>
                                    <i data-lucide="check" class="w-4 h-4 text-[#b08d57] hidden check-icon"></i>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Name</label>
                    <input type="text" name="name" required class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Price (ETB)</label>
                    <input type="number" step="0.01" name="price" placeholder="0.00" required class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
                </div>
                
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Product Image</label>
                    <input type="file" id="product-image-input" name="image" accept="image/*" required class="hidden">
                    
                    <div id="image-dropzone" class="relative group cursor-pointer border-2 border-dashed border-[#2a2731] hover:border-[#b08d57]/70 bg-[#0f0e13]/80 hover:bg-[#14131a] rounded-xl p-3 sm:p-4 transition flex flex-col items-center justify-center min-h-[105px]">
                        <div id="image-placeholder" class="flex flex-col items-center justify-center text-center py-1.5 space-y-1.5 pointer-events-none">
                            <div class="w-9 h-9 rounded-xl bg-[#1e1c25] group-hover:bg-[#b08d57]/20 flex items-center justify-center text-stone-400 group-hover:text-[#b08d57] transition">
                                <i data-lucide="image-plus" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-stone-300 group-hover:text-white transition">
                                    <span class="text-[#b08d57]">Click to upload</span> or drag image
                                </p>
                                <p class="text-[10px] text-stone-500">PNG, JPG, WEBP up to 4MB</p>
                            </div>
                        </div>

                        <div id="image-preview-container" class="hidden w-full relative flex items-center gap-3">
                            <div class="relative w-16 h-16 rounded-xl overflow-hidden bg-[#0f0e13] border border-[#2a2731] shrink-0 shadow-md">
                                <img id="image-preview-img" src="" alt="Preview" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0 pr-1">
                                <p id="image-preview-name" class="text-xs font-bold text-white truncate"></p>
                                <span class="inline-flex items-center gap-1 text-[10px] text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded-full font-semibold mt-1">
                                    <i data-lucide="check-circle-2" class="w-3 h-3"></i> Ready to upload
                                </span>
                                <p class="text-[11px] text-stone-500 mt-1">Click to replace</p>
                            </div>
                            <button type="button" id="remove-image-btn" class="p-2 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 hover:bg-rose-500/20 hover:text-rose-300 transition shrink-0" title="Remove image">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Description</label>
                    <textarea name="description" rows="2" class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition"></textarea>
                </div>
                <div class="sm:col-span-2 flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
                    <p class="text-rose-400 text-xs sm:text-sm form-error" data-form="product"></p>
                    <button type="submit" class="bg-[#b08d57] hover:bg-[#c9a36b] text-[#0f0e13] font-bold rounded-xl px-5 py-2.5 text-sm transition flex items-center justify-center gap-2">
                        <i data-lucide="plus" class="w-4 h-4"></i><span>Create Product</span>
                    </button>
                </div>
            </form>

            <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl overflow-hidden shadow-lg">
                <div class="p-4 sm:p-5 border-b border-[#2a2731] flex flex-col md:flex-row md:items-center justify-between gap-4 bg-[#0f0e13]/40">
                    <div class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-500">
                            <i data-lucide="search" class="w-4 h-4 search-icon"></i>
                            <div class="w-4 h-4 border-2 border-[#b08d57] border-t-transparent rounded-full animate-spin hidden buffer-spinner"></div>
                        </div>
                        <input type="text" id="products-search-input" placeholder="Search products by name, category, or price..." class="cd-input w-full rounded-xl pl-10 pr-9 py-2.5 text-sm text-white focus:outline-none transition">
                        <button type="button" class="clear-search-btn absolute inset-y-0 right-0 pr-3 flex items-center text-stone-500 hover:text-stone-300 hidden">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <div class="relative custom-select-wrapper min-w-[150px]">
                            <select id="products-filter-category" class="custom-native-select opacity-0 absolute pointer-events-none h-0 w-0">
                                <option value="all">All Categories</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="custom-select-trigger cd-input w-full rounded-xl px-3 py-2 text-xs text-left flex items-center justify-between transition group hover:border-[#b08d57]/70">
                                <span class="custom-select-label text-stone-300 font-semibold truncate" data-placeholder="All Categories">All Categories</span>
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-stone-400 group-hover:text-stone-200 transition-transform duration-300 chevron-icon shrink-0 ml-2"></i>
                            </button>
                            <div class="custom-select-menu hidden absolute right-0 left-0 top-[calc(100%+6px)] bg-[#14131a]/95 backdrop-blur-xl border border-[#2a2731] rounded-xl p-1.5 shadow-2xl z-50 max-h-52 overflow-y-auto custom-scroll space-y-1">
                                <div class="custom-option px-2.5 py-1.5 rounded-lg text-xs text-stone-300 hover:text-white hover:bg-[#1e1c25] cursor-pointer flex items-center justify-between transition" data-value="all" data-text="All Categories">
                                    <span>All Categories</span>
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-[#b08d57] hidden check-icon"></i>
                                </div>
                                @foreach ($categories as $cat)
                                    <div class="custom-option px-2.5 py-1.5 rounded-lg text-xs text-stone-300 hover:text-white hover:bg-[#1e1c25] cursor-pointer flex items-center justify-between transition" data-value="{{ $cat->name }}" data-text="{{ $cat->name }}">
                                        <span>{{ $cat->name }}</span>
                                        <i data-lucide="check" class="w-3.5 h-3.5 text-[#b08d57] hidden check-icon"></i>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="relative custom-select-wrapper min-w-[130px]">
                            <select id="products-filter-status" class="custom-native-select opacity-0 absolute pointer-events-none h-0 w-0">
                                <option value="all">All Status</option>
                                <option value="available">Available</option>
                                <option value="unavailable">Unavailable</option>
                            </select>
                            <button type="button" class="custom-select-trigger cd-input w-full rounded-xl px-3 py-2 text-xs text-left flex items-center justify-between transition group hover:border-[#b08d57]/70">
                                <span class="custom-select-label text-stone-300 font-semibold truncate" data-placeholder="All Status">All Status</span>
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-stone-400 group-hover:text-stone-200 transition-transform duration-300 chevron-icon shrink-0 ml-2"></i>
                            </button>
                            <div class="custom-select-menu hidden absolute right-0 left-0 top-[calc(100%+6px)] bg-[#14131a]/95 backdrop-blur-xl border border-[#2a2731] rounded-xl p-1.5 shadow-2xl z-50 max-h-52 overflow-y-auto custom-scroll space-y-1">
                                <div class="custom-option px-2.5 py-1.5 rounded-lg text-xs text-stone-300 hover:text-white hover:bg-[#1e1c25] cursor-pointer flex items-center justify-between transition" data-value="all" data-text="All Status">
                                    <span>All Status</span>
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-[#b08d57] hidden check-icon"></i>
                                </div>
                                <div class="custom-option px-2.5 py-1.5 rounded-lg text-xs text-stone-300 hover:text-white hover:bg-[#1e1c25] cursor-pointer flex items-center justify-between transition" data-value="available" data-text="Available">
                                    <span>Available</span>
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-[#b08d57] hidden check-icon"></i>
                                </div>
                                <div class="custom-option px-2.5 py-1.5 rounded-lg text-xs text-stone-300 hover:text-white hover:bg-[#1e1c25] cursor-pointer flex items-center justify-between transition" data-value="unavailable" data-text="Unavailable">
                                    <span>Unavailable</span>
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-[#b08d57] hidden check-icon"></i>
                                </div>
                            </div>
                        </div>

                        <span id="products-count-badge" class="px-3 py-1.5 rounded-xl bg-[#1e1c25] border border-[#2a2731] text-xs font-semibold text-stone-300 whitespace-nowrap">
                            Showing {{ $products->count() }} of {{ $products->count() }}
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scroll">
                    <table class="w-full text-sm text-left" id="products-table">
                        <thead>
                            <tr class="text-stone-400 border-b border-[#2a2731] bg-[#0f0e13]/50">
                                <th class="py-3 px-4 sm:px-5 font-semibold">Image</th>
                                <th class="px-4 sm:px-5 font-semibold">Name</th>
                                <th class="px-4 sm:px-5 font-semibold">Category</th>
                                <th class="px-4 sm:px-5 font-semibold">Price</th>
                                <th class="px-4 sm:px-5 font-semibold">Available</th>
                            </tr>
                        </thead>
                        <tbody id="products-table-body">
                            @foreach ($products as $p)
                                <tr class="data-row border-b border-[#2a2731]/60 hover:bg-[#1e1c25]/40 transition" 
                                    data-name="{{ strtolower($p->name) }}" 
                                    data-category="{{ strtolower($p->category->name) }}" 
                                    data-price="{{ $p->price }}" 
                                    data-status="{{ $p->is_available ? 'available' : 'unavailable' }}">
                                    <td class="py-3 px-4 sm:px-5"><img src="{{ asset('storage/' . $p->image) }}" class="w-10 h-10 object-cover rounded-lg bg-[#0f0e13]"></td>
                                    <td class="px-4 sm:px-5 text-white font-medium whitespace-nowrap product-name-cell">{{ $p->name }}</td>
                                    <td class="px-4 sm:px-5 text-stone-400 whitespace-nowrap product-cat-cell">{{ $p->category->name }}</td>
                                    <td class="px-4 sm:px-5 text-[#b08d57] font-bold whitespace-nowrap">{{ number_format($p->price, 2) }} ETB</td>
                                    <td class="px-4 sm:px-5 whitespace-nowrap">
                                        @if ($p->is_available)
                                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full"><i data-lucide="check" class="w-3 h-3"></i>Yes</span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2.5 py-1 rounded-full"><i data-lucide="x" class="w-3 h-3"></i>No</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="products-empty-state" class="hidden p-8 sm:p-12 text-center flex-col items-center justify-center space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-[#1e1c25] flex items-center justify-center text-stone-500 mx-auto">
                        <i data-lucide="package-search" class="w-6 h-6"></i>
                    </div>
                    <p class="text-white font-bold text-sm">No products found</p>
                    <p class="text-stone-500 text-xs max-w-sm mx-auto">No products matched your search query or filter selection.</p>
                </div>
            </div>
        </section>

        {{-- ================= STAFF SECTION ================= --}}
        <section id="section-staff" class="page-section hidden">
            <div class="mb-6 sm:mb-8">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Staff &amp; Delivery</h2>
                <p class="text-stone-500 text-xs sm:text-sm mt-1">Manage your team members and drivers</p>
            </div>

            <form id="staff-form" class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 mb-8 shadow-lg">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Role</label>
                    <div class="relative custom-select-wrapper" id="staff-role-wrapper">
                        <select name="role_id" required class="custom-native-select opacity-0 absolute pointer-events-none h-0 w-0">
                            <option value="">Select role</option>
                            @foreach ($roles as $r)
                                <option value="{{ $r->id }}">{{ ucfirst($r->name) }}</option>
                            @endforeach
                        </select>
                        
                        <button type="button" class="custom-select-trigger cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-left flex items-center justify-between transition group hover:border-[#b08d57]/70">
                            <div class="flex items-center gap-2.5 truncate pr-2">
                                <div class="w-2 h-2 rounded-full bg-stone-600 transition trigger-dot"></div>
                                <span class="custom-select-label text-stone-500 font-medium truncate" data-placeholder="Select role">Select role</span>
                            </div>
                            <div class="w-6 h-6 rounded-lg bg-[#1e1c25] group-hover:bg-[#2a2731] flex items-center justify-center text-stone-400 group-hover:text-stone-200 shrink-0 transition">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-300 chevron-icon"></i>
                            </div>
                        </button>

                        <div class="custom-select-menu hidden absolute left-0 right-0 top-[calc(100%+6px)] bg-[#14131a]/95 backdrop-blur-xl border border-[#2a2731] rounded-xl p-1.5 shadow-2xl z-50 max-h-56 overflow-y-auto custom-scroll space-y-1">
                            @foreach ($roles as $r)
                                <div class="custom-option px-3 py-2.5 rounded-lg text-sm text-stone-300 hover:text-white hover:bg-[#1e1c25] cursor-pointer flex items-center justify-between transition group/opt" data-value="{{ $r->id }}" data-text="{{ ucfirst($r->name) }}">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-2 h-2 rounded-full bg-stone-600 group-hover/opt:bg-[#b08d57] transition dot-indicator"></div>
                                        <span class="font-medium">{{ ucfirst($r->name) }}</span>
                                    </div>
                                    <i data-lucide="check" class="w-4 h-4 text-[#b08d57] hidden check-icon"></i>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Full name</label>
                    <input type="text" name="fullname" required class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Username</label>
                    <input type="text" name="username" required class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Email</label>
                    <input type="email" name="email" class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Phone</label>
                    <input type="text" name="phone" class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Password</label>
                    <input type="password" name="password" required class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
                </div>
                <div class="sm:col-span-2 flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
                    <p class="text-rose-400 text-xs sm:text-sm form-error" data-form="staff"></p>
                    <button type="submit" class="bg-[#b08d57] hover:bg-[#c9a36b] text-[#0f0e13] font-bold rounded-xl px-5 py-2.5 text-sm transition flex items-center justify-center gap-2">
                        <i data-lucide="user-plus" class="w-4 h-4"></i><span>Create Staff</span>
                    </button>
                </div>
            </form>

            <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl overflow-hidden shadow-lg">
                <div class="p-4 sm:p-5 border-b border-[#2a2731] flex flex-col md:flex-row md:items-center justify-between gap-4 bg-[#0f0e13]/40">
                    <div class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-500">
                            <i data-lucide="search" class="w-4 h-4 search-icon"></i>
                            <div class="w-4 h-4 border-2 border-[#b08d57] border-t-transparent rounded-full animate-spin hidden buffer-spinner"></div>
                        </div>
                        <input type="text" id="staff-search-input" placeholder="Search staff by name, username, or phone..." class="cd-input w-full rounded-xl pl-10 pr-9 py-2.5 text-sm text-white focus:outline-none transition">
                        <button type="button" class="clear-search-btn absolute inset-y-0 right-0 pr-3 flex items-center text-stone-500 hover:text-stone-300 hidden">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <div class="relative custom-select-wrapper min-w-[150px]">
                            <select id="staff-filter-role" class="custom-native-select opacity-0 absolute pointer-events-none h-0 w-0">
                                <option value="all">All Roles</option>
                                @foreach ($roles as $r)
                                    <option value="{{ strtolower($r->name) }}">{{ ucfirst($r->name) }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="custom-select-trigger cd-input w-full rounded-xl px-3 py-2 text-xs text-left flex items-center justify-between transition group hover:border-[#b08d57]/70">
                                <span class="custom-select-label text-stone-300 font-semibold truncate" data-placeholder="All Roles">All Roles</span>
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-stone-400 group-hover:text-stone-200 transition-transform duration-300 chevron-icon shrink-0 ml-2"></i>
                            </button>
                            <div class="custom-select-menu hidden absolute right-0 left-0 top-[calc(100%+6px)] bg-[#14131a]/95 backdrop-blur-xl border border-[#2a2731] rounded-xl p-1.5 shadow-2xl z-50 max-h-52 overflow-y-auto custom-scroll space-y-1">
                                <div class="custom-option px-2.5 py-1.5 rounded-lg text-xs text-stone-300 hover:text-white hover:bg-[#1e1c25] cursor-pointer flex items-center justify-between transition" data-value="all" data-text="All Roles">
                                    <span>All Roles</span>
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-[#b08d57] hidden check-icon"></i>
                                </div>
                                @foreach ($roles as $r)
                                    <div class="custom-option px-2.5 py-1.5 rounded-lg text-xs text-stone-300 hover:text-white hover:bg-[#1e1c25] cursor-pointer flex items-center justify-between transition" data-value="{{ strtolower($r->name) }}" data-text="{{ ucfirst($r->name) }}">
                                        <span>{{ ucfirst($r->name) }}</span>
                                        <i data-lucide="check" class="w-3.5 h-3.5 text-[#b08d57] hidden check-icon"></i>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <span id="staff-count-badge" class="px-3 py-1.5 rounded-xl bg-[#1e1c25] border border-[#2a2731] text-xs font-semibold text-stone-300 whitespace-nowrap">
                            Showing {{ $staff->count() }} of {{ $staff->count() }}
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scroll">
                    <table class="w-full text-sm text-left" id="staff-table">
                        <thead>
                            <tr class="text-stone-400 border-b border-[#2a2731] bg-[#0f0e13]/50">
                                <th class="py-3 px-4 sm:px-5 font-semibold">Name</th>
                                <th class="px-4 sm:px-5 font-semibold">Username</th>
                                <th class="px-4 sm:px-5 font-semibold">Role</th>
                                <th class="px-4 sm:px-5 font-semibold">Phone</th>
                            </tr>
                        </thead>
                        <tbody id="staff-table-body">
                            @foreach ($staff as $s)
                                <tr class="data-row border-b border-[#2a2731]/60 hover:bg-[#1e1c25]/40 transition"
                                    data-fullname="{{ strtolower($s->fullname) }}"
                                    data-username="{{ strtolower($s->username) }}"
                                    data-role="{{ strtolower($s->role->name) }}"
                                    data-phone="{{ strtolower($s->phone ?? '') }}">
                                    <td class="py-3 px-4 sm:px-5 text-white font-medium whitespace-nowrap staff-name-cell">{{ $s->fullname }}</td>
                                    <td class="px-4 sm:px-5 text-stone-400 whitespace-nowrap staff-username-cell">{{ $s->username }}</td>
                                    <td class="px-4 sm:px-5 whitespace-nowrap"><span class="inline-flex items-center text-xs font-semibold text-[#b08d57] bg-[#b08d57]/10 border border-[#b08d57]/20 px-2.5 py-1 rounded-full staff-role-cell">{{ ucfirst($s->role->name) }}</span></td>
                                    <td class="px-4 sm:px-5 text-stone-400 whitespace-nowrap staff-phone-cell">{{ $s->phone }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="staff-empty-state" class="hidden p-8 sm:p-12 text-center flex-col items-center justify-center space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-[#1e1c25] flex items-center justify-center text-stone-500 mx-auto">
                        <i data-lucide="user-x" class="w-6 h-6"></i>
                    </div>
                    <p class="text-white font-bold text-sm">No staff members found</p>
                    <p class="text-stone-500 text-xs max-w-sm mx-auto">No members matched your search query or role filter.</p>
                </div>
            </div>
        </section>

        {{-- ================= EXTRAS SECTION ================= --}}
        <section id="section-extras" class="page-section hidden">
            <div class="mb-6 sm:mb-8">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Extras</h2>
                <p class="text-stone-500 text-xs sm:text-sm mt-1">Add-ons and side options</p>
            </div>

            <form id="extra-form" class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 mb-8 shadow-lg">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Name</label>
                    <input type="text" name="name" required class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Price (ETB)</label>
                    <input type="number" step="0.01" name="price" placeholder="0.00" required class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
                </div>
                <div class="sm:col-span-2 flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
                    <p class="text-rose-400 text-xs sm:text-sm form-error" data-form="extra"></p>
                    <button type="submit" class="bg-[#b08d57] hover:bg-[#c9a36b] text-[#0f0e13] font-bold rounded-xl px-5 py-2.5 text-sm transition flex items-center justify-center gap-2">
                        <i data-lucide="plus" class="w-4 h-4"></i><span>Create Extra</span>
                    </button>
                </div>
            </form>

            <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl overflow-hidden shadow-lg">
                <div class="p-4 sm:p-5 border-b border-[#2a2731] flex flex-col md:flex-row md:items-center justify-between gap-4 bg-[#0f0e13]/40">
                    <div class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-500">
                            <i data-lucide="search" class="w-4 h-4 search-icon"></i>
                            <div class="w-4 h-4 border-2 border-[#b08d57] border-t-transparent rounded-full animate-spin hidden buffer-spinner"></div>
                        </div>
                        <input type="text" id="extras-search-input" placeholder="Search extras by name or price..." class="cd-input w-full rounded-xl pl-10 pr-9 py-2.5 text-sm text-white focus:outline-none transition">
                        <button type="button" class="clear-search-btn absolute inset-y-0 right-0 pr-3 flex items-center text-stone-500 hover:text-stone-300 hidden">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <div class="relative custom-select-wrapper min-w-[140px]">
                            <select id="extras-filter-status" class="custom-native-select opacity-0 absolute pointer-events-none h-0 w-0">
                                <option value="all">All Status</option>
                                <option value="available">Available</option>
                                <option value="unavailable">Unavailable</option>
                            </select>
                            <button type="button" class="custom-select-trigger cd-input w-full rounded-xl px-3 py-2 text-xs text-left flex items-center justify-between transition group hover:border-[#b08d57]/70">
                                <span class="custom-select-label text-stone-300 font-semibold truncate" data-placeholder="All Status">All Status</span>
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-stone-400 group-hover:text-stone-200 transition-transform duration-300 chevron-icon shrink-0 ml-2"></i>
                            </button>
                            <div class="custom-select-menu hidden absolute right-0 left-0 top-[calc(100%+6px)] bg-[#14131a]/95 backdrop-blur-xl border border-[#2a2731] rounded-xl p-1.5 shadow-2xl z-50 max-h-52 overflow-y-auto custom-scroll space-y-1">
                                <div class="custom-option px-2.5 py-1.5 rounded-lg text-xs text-stone-300 hover:text-white hover:bg-[#1e1c25] cursor-pointer flex items-center justify-between transition" data-value="all" data-text="All Status">
                                    <span>All Status</span>
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-[#b08d57] hidden check-icon"></i>
                                </div>
                                <div class="custom-option px-2.5 py-1.5 rounded-lg text-xs text-stone-300 hover:text-white hover:bg-[#1e1c25] cursor-pointer flex items-center justify-between transition" data-value="available" data-text="Available">
                                    <span>Available</span>
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-[#b08d57] hidden check-icon"></i>
                                </div>
                                <div class="custom-option px-2.5 py-1.5 rounded-lg text-xs text-stone-300 hover:text-white hover:bg-[#1e1c25] cursor-pointer flex items-center justify-between transition" data-value="unavailable" data-text="Unavailable">
                                    <span>Unavailable</span>
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-[#b08d57] hidden check-icon"></i>
                                </div>
                            </div>
                        </div>

                        <span id="extras-count-badge" class="px-3 py-1.5 rounded-xl bg-[#1e1c25] border border-[#2a2731] text-xs font-semibold text-stone-300 whitespace-nowrap">
                            Showing {{ $extras->count() }} of {{ $extras->count() }}
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scroll">
                    <table class="w-full text-sm text-left" id="extras-table">
                        <thead>
                            <tr class="text-stone-400 border-b border-[#2a2731] bg-[#0f0e13]/50">
                                <th class="py-3 px-4 sm:px-5 font-semibold">Name</th>
                                <th class="px-4 sm:px-5 font-semibold">Price</th>
                                <th class="px-4 sm:px-5 font-semibold">Available</th>
                            </tr>
                        </thead>
                        <tbody id="extras-table-body">
                            @foreach ($extras as $e)
                                <tr class="data-row border-b border-[#2a2731]/60 hover:bg-[#1e1c25]/40 transition"
                                    data-name="{{ strtolower($e->name) }}"
                                    data-price="{{ $e->price }}"
                                    data-status="{{ $e->is_available ? 'available' : 'unavailable' }}">
                                    <td class="py-3 px-4 sm:px-5 text-white font-medium whitespace-nowrap extra-name-cell">{{ $e->name }}</td>
                                    <td class="px-4 sm:px-5 text-[#b08d57] font-bold whitespace-nowrap">{{ number_format($e->price, 2) }} ETB</td>
                                    <td class="px-4 sm:px-5 whitespace-nowrap">
                                        @if ($e->is_available)
                                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full"><i data-lucide="check" class="w-3 h-3"></i>Yes</span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2.5 py-1 rounded-full"><i data-lucide="x" class="w-3 h-3"></i>No</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="extras-empty-state" class="hidden p-8 sm:p-12 text-center flex-col items-center justify-center space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-[#1e1c25] flex items-center justify-center text-stone-500 mx-auto">
                        <i data-lucide="sparkles" class="w-6 h-6"></i>
                    </div>
                    <p class="text-white font-bold text-sm">No extras found</p>
                    <p class="text-stone-500 text-xs max-w-sm mx-auto">No extras matched your search query or filter selection.</p>
                </div>
            </div>
        </section>

    </main>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// ---- Charts Initialization ----
let revenueChartInstance = null;
let categoryChartInstance = null;

function initCharts() {
    const chartLabels = @json($chartLabels);
    const chartRevenue = @json($chartRevenue);
    const chartOrders = @json($chartOrders);
    const categoryLabels = @json($categoryLabels);
    const categoryCounts = @json($categoryCounts);

    // 1. Revenue & Orders Trend Chart
    const revCtx = document.getElementById('revenueTrendChart')?.getContext('2d');
    if (revCtx) {
        if (revenueChartInstance) revenueChartInstance.destroy();

        const goldGradient = revCtx.createLinearGradient(0, 0, 0, 300);
        goldGradient.addColorStop(0, 'rgba(176, 141, 87, 0.45)');
        goldGradient.addColorStop(1, 'rgba(176, 141, 87, 0.0)');

        revenueChartInstance = new Chart(revCtx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [
                    {
                        label: 'Revenue (ETB)',
                        data: chartRevenue,
                        borderColor: '#b08d57',
                        backgroundColor: goldGradient,
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#b08d57',
                        pointBorderColor: '#0f0e13',
                        pointHoverRadius: 6,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Orders Count',
                        data: chartOrders,
                        borderColor: '#a855f7',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointBackgroundColor: '#a855f7',
                        pointHoverRadius: 6,
                        tension: 0.35,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        labels: { color: '#a8a29e', font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' } }
                    },
                    tooltip: {
                        backgroundColor: '#14131a',
                        titleColor: '#fff',
                        bodyColor: '#a8a29e',
                        borderColor: '#2a2731',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 10,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.datasetIndex === 0) {
                                    label += Number(context.parsed.y).toLocaleString() + ' ETB';
                                } else {
                                    label += context.parsed.y;
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: '#1e1c25' },
                        ticks: { color: '#78716c', font: { size: 11 } }
                    },
                    y: {
                        position: 'left',
                        grid: { color: '#1e1c25' },
                        ticks: {
                            color: '#78716c',
                            font: { size: 11 },
                            callback: (v) => v.toLocaleString() + ' ETB'
                        }
                    },
                    y1: {
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: { color: '#a855f7', font: { size: 11 }, precision: 0 }
                    }
                }
            }
        });
    }

    // 2. Category Distribution Donut Chart
    const catCtx = document.getElementById('categoryDonutChart')?.getContext('2d');
    if (catCtx) {
        if (categoryChartInstance) categoryChartInstance.destroy();

        const defaultColors = ['#b08d57', '#3b82f6', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6', '#64748b'];

        categoryChartInstance = new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: categoryLabels.length > 0 ? categoryLabels : ['No Categories'],
                datasets: [{
                    data: categoryCounts.length > 0 ? categoryCounts : [1],
                    backgroundColor: defaultColors.slice(0, Math.max(categoryLabels.length, 1)),
                    borderColor: '#14131a',
                    borderWidth: 3,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#a8a29e',
                            boxWidth: 10,
                            padding: 12,
                            font: { family: 'Plus Jakarta Sans', size: 11 }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#14131a',
                        titleColor: '#fff',
                        bodyColor: '#a8a29e',
                        borderColor: '#2a2731',
                        borderWidth: 1,
                        cornerRadius: 10
                    }
                }
            }
        });
    }
}

// ---- Mobile Drawer Controls ----
const sidebar = document.getElementById('sidebar');
const sidebarBackdrop = document.getElementById('sidebar-backdrop');
const openSidebarBtn = document.getElementById('open-sidebar-btn');
const closeSidebarBtn = document.getElementById('close-sidebar-btn');

function openMobileSidebar() {
    sidebar.classList.remove('-translate-x-full');
    sidebarBackdrop.classList.remove('hidden');
    setTimeout(() => lucide.createIcons(), 50);
}

function closeMobileSidebar() {
    sidebar.classList.add('-translate-x-full');
    sidebarBackdrop.classList.add('hidden');
}

if (openSidebarBtn) openSidebarBtn.addEventListener('click', openMobileSidebar);
if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeMobileSidebar);
if (sidebarBackdrop) sidebarBackdrop.addEventListener('click', closeMobileSidebar);

// ---- Sidebar Navigation Switching ----
const navLinks = document.querySelectorAll('.nav-link');
const sections = document.querySelectorAll('.page-section');

function showSection(target) {
    sections.forEach(s => s.classList.add('hidden'));
    document.getElementById('section-' + target).classList.remove('hidden');
    navLinks.forEach(l => l.classList.remove('active'));
    document.querySelectorAll(`.nav-link[data-target="${target}"]`).forEach(l => l.classList.add('active'));
    
    closeMobileSidebar();
    setTimeout(() => {
        lucide.createIcons();
        if (target === 'overview') {
            initCharts();
        }
    }, 50);
}

navLinks.forEach(link => {
    link.addEventListener('click', () => showSection(link.dataset.target));
});
showSection('overview');

// ---- Image Picker & Preview Handling ----
const imageInput = document.getElementById('product-image-input');
const dropzone = document.getElementById('image-dropzone');
const placeholder = document.getElementById('image-placeholder');
const previewContainer = document.getElementById('image-preview-container');
const previewImg = document.getElementById('image-preview-img');
const previewName = document.getElementById('image-preview-name');
const removeImageBtn = document.getElementById('remove-image-btn');

function showImagePreview(file) {
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src = e.target.result;
            previewName.textContent = file.name;
            placeholder.classList.add('hidden');
            previewContainer.classList.remove('hidden');
            setTimeout(() => lucide.createIcons(), 50);
        };
        reader.readAsDataURL(file);
    }
}

function resetImagePreview() {
    imageInput.value = '';
    previewImg.src = '';
    previewName.textContent = '';
    previewContainer.classList.add('hidden');
    placeholder.classList.remove('hidden');
    setTimeout(() => lucide.createIcons(), 50);
}

if (imageInput) {
    imageInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            showImagePreview(this.files[0]);
        } else {
            resetImagePreview();
        }
    });
}

if (dropzone) {
    dropzone.addEventListener('click', (e) => {
        if (!e.target.closest('#remove-image-btn')) {
            imageInput.click();
        }
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropzone.classList.add('border-[#b08d57]', 'bg-[#1e1c25]/80');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-[#b08d57]', 'bg-[#1e1c25]/80');
        });
    });

    dropzone.addEventListener('drop', (e) => {
        const files = e.dataTransfer.files;
        if (files && files.length > 0) {
            imageInput.files = files;
            showImagePreview(files[0]);
        }
    });
}

if (removeImageBtn) {
    removeImageBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        resetImagePreview();
    });
}

// ---- Customized Dropdowns Management ----
function initCustomSelects() {
    document.querySelectorAll('.custom-select-wrapper').forEach(wrapper => {
        if (wrapper.dataset.initialized) return;
        wrapper.dataset.initialized = "true";

        const trigger = wrapper.querySelector('.custom-select-trigger');
        const menu = wrapper.querySelector('.custom-select-menu');
        const label = wrapper.querySelector('.custom-select-label');
        const triggerDot = wrapper.querySelector('.trigger-dot');
        const chevron = wrapper.querySelector('.chevron-icon');
        const nativeSelect = wrapper.querySelector('.custom-native-select');
        const options = wrapper.querySelectorAll('.custom-option');
        const placeholderText = label.getAttribute('data-placeholder') || label.textContent;

        function toggleMenu(show) {
            const isOpen = show !== undefined ? show : menu.classList.contains('hidden');
            if (isOpen) {
                document.querySelectorAll('.custom-select-menu').forEach(m => {
                    if (m !== menu) {
                        m.classList.add('hidden');
                        const otherChevron = m.closest('.custom-select-wrapper')?.querySelector('.chevron-icon');
                        if (otherChevron) otherChevron.classList.remove('rotate-180');
                    }
                });
                menu.classList.remove('hidden');
                if (chevron) chevron.classList.add('rotate-180');
                trigger.classList.add('border-[#b08d57]', 'ring-2', 'ring-[#b08d57]/20');
            } else {
                menu.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
                trigger.classList.remove('border-[#b08d57]', 'ring-2', 'ring-[#b08d57]/20');
            }
        }

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleMenu();
        });

        options.forEach(opt => {
            opt.addEventListener('click', (e) => {
                e.stopPropagation();
                const val = opt.getAttribute('data-value');
                const text = opt.getAttribute('data-text');

                nativeSelect.value = val;
                nativeSelect.dispatchEvent(new Event('change', { bubbles: true }));

                label.textContent = text;
                label.classList.remove('text-stone-500');
                label.classList.add('text-white', 'font-semibold');
                if (triggerDot) triggerDot.classList.replace('bg-stone-600', 'bg-[#b08d57]');

                options.forEach(o => {
                    const check = o.querySelector('.check-icon');
                    const dot = o.querySelector('.dot-indicator');
                    if (o === opt) {
                        o.classList.add('bg-[#1e1c25]', 'text-white');
                        if (check) check.classList.remove('hidden');
                        if (dot) dot.classList.replace('bg-stone-600', 'bg-[#b08d57]');
                    } else {
                        o.classList.remove('bg-[#1e1c25]', 'text-white');
                        if (check) check.classList.add('hidden');
                        if (dot) dot.classList.replace('bg-[#b08d57]', 'bg-stone-600');
                    }
                });

                toggleMenu(false);
            });
        });

        const parentForm = wrapper.closest('form');
        if (parentForm) {
            parentForm.addEventListener('reset', () => {
                setTimeout(() => {
                    nativeSelect.value = '';
                    label.textContent = placeholderText;
                    label.classList.add('text-stone-500');
                    label.classList.remove('text-white', 'font-semibold');
                    if (triggerDot) triggerDot.classList.replace('bg-[#b08d57]', 'bg-stone-600');
                    options.forEach(o => {
                        o.classList.remove('bg-[#1e1c25]', 'text-white');
                        const check = o.querySelector('.check-icon');
                        const dot = o.querySelector('.dot-indicator');
                        if (check) check.classList.add('hidden');
                        if (dot) dot.classList.replace('bg-[#b08d57]', 'bg-stone-600');
                    });
                }, 10);
            });
        }
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.custom-select-menu').forEach(m => m.classList.add('hidden'));
        document.querySelectorAll('.chevron-icon').forEach(c => c.classList.remove('rotate-180'));
        document.querySelectorAll('.custom-select-trigger').forEach(t => t.classList.remove('border-[#b08d57]', 'ring-2', 'ring-[#b08d57]/20'));
    });
}

// ---- Reusable DataTable Filter & Buffer Engine ----
function setupDataTable({ searchInputId, clearBtnClass, tableBodyId, emptyStateId, countBadgeId, getFilters, rowMatcher }) {
    const searchInput = document.getElementById(searchInputId);
    const tableBody = document.getElementById(tableBodyId);
    const emptyState = document.getElementById(emptyStateId);
    const countBadge = document.getElementById(countBadgeId);
    const container = searchInput?.closest('.relative');
    const searchIcon = container?.querySelector('.search-icon');
    const spinner = container?.querySelector('.buffer-spinner');
    const clearBtn = container?.querySelector('.clear-search-btn');

    let debounceTimer = null;

    function applyFilter() {
        const query = searchInput ? searchInput.value.trim().toLowerCase() : '';
        const filters = getFilters ? getFilters() : {};
        const rows = tableBody.querySelectorAll('tr.data-row');
        let visibleCount = 0;
        const totalCount = rows.length;

        rows.forEach(row => {
            const matches = rowMatcher(row, query, filters);
            if (matches) {
                row.classList.remove('hidden');
                visibleCount++;
            } else {
                row.classList.add('hidden');
            }
        });

        if (countBadge) countBadge.textContent = `Showing ${visibleCount} of ${totalCount}`;

        if (emptyState) {
            if (visibleCount === 0 && totalCount > 0) {
                emptyState.classList.remove('hidden');
                emptyState.classList.add('flex');
            } else {
                emptyState.classList.add('hidden');
                emptyState.classList.remove('flex');
            }
        }

        if (spinner && searchIcon) {
            spinner.classList.add('hidden');
            searchIcon.classList.remove('hidden');
        }

        if (clearBtn) {
            if (query.length > 0) clearBtn.classList.remove('hidden');
            else clearBtn.classList.add('hidden');
        }
    }

    function triggerBufferedFilter() {
        if (spinner && searchIcon) {
            searchIcon.classList.add('hidden');
            spinner.classList.remove('hidden');
        }
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            applyFilter();
        }, 180);
    }

    if (searchInput) searchInput.addEventListener('input', triggerBufferedFilter);

    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            triggerBufferedFilter();
            searchInput.focus();
        });
    }

    return { refresh: applyFilter };
}

// ---- Initialize DataTables ----
let productsDataTable, staffDataTable, extrasDataTable;

function initAllDataTables() {
    const prodCategorySelect = document.getElementById('products-filter-category');
    const prodStatusSelect = document.getElementById('products-filter-status');

    productsDataTable = setupDataTable({
        searchInputId: 'products-search-input',
        tableBodyId: 'products-table-body',
        emptyStateId: 'products-empty-state',
        countBadgeId: 'products-count-badge',
        getFilters: () => ({
            category: prodCategorySelect ? prodCategorySelect.value.toLowerCase() : 'all',
            status: prodStatusSelect ? prodStatusSelect.value.toLowerCase() : 'all'
        }),
        rowMatcher: (row, query, filters) => {
            const name = row.getAttribute('data-name') || '';
            const category = row.getAttribute('data-category') || '';
            const price = row.getAttribute('data-price') || '';
            const status = row.getAttribute('data-status') || '';

            const matchesQuery = !query || name.includes(query) || category.includes(query) || price.includes(query);
            const matchesCategory = filters.category === 'all' || category === filters.category;
            const matchesStatus = filters.status === 'all' || status === filters.status;

            return matchesQuery && matchesCategory && matchesStatus;
        }
    });

    if (prodCategorySelect) prodCategorySelect.addEventListener('change', () => productsDataTable.refresh());
    if (prodStatusSelect) prodStatusSelect.addEventListener('change', () => productsDataTable.refresh());

    const staffRoleSelect = document.getElementById('staff-filter-role');

    staffDataTable = setupDataTable({
        searchInputId: 'staff-search-input',
        tableBodyId: 'staff-table-body',
        emptyStateId: 'staff-empty-state',
        countBadgeId: 'staff-count-badge',
        getFilters: () => ({
            role: staffRoleSelect ? staffRoleSelect.value.toLowerCase() : 'all'
        }),
        rowMatcher: (row, query, filters) => {
            const fullname = row.getAttribute('data-fullname') || '';
            const username = row.getAttribute('data-username') || '';
            const phone = row.getAttribute('data-phone') || '';
            const role = row.getAttribute('data-role') || '';

            const matchesQuery = !query || fullname.includes(query) || username.includes(query) || phone.includes(query) || role.includes(query);
            const matchesRole = filters.role === 'all' || role === filters.role;

            return matchesQuery && matchesRole;
        }
    });

    if (staffRoleSelect) staffRoleSelect.addEventListener('change', () => staffDataTable.refresh());

    const extrasStatusSelect = document.getElementById('extras-filter-status');

    extrasDataTable = setupDataTable({
        searchInputId: 'extras-search-input',
        tableBodyId: 'extras-table-body',
        emptyStateId: 'extras-empty-state',
        countBadgeId: 'extras-count-badge',
        getFilters: () => ({
            status: extrasStatusSelect ? extrasStatusSelect.value.toLowerCase() : 'all'
        }),
        rowMatcher: (row, query, filters) => {
            const name = row.getAttribute('data-name') || '';
            const price = row.getAttribute('data-price') || '';
            const status = row.getAttribute('data-status') || '';

            const matchesQuery = !query || name.includes(query) || price.includes(query);
            const matchesStatus = filters.status === 'all' || status === filters.status;

            return matchesQuery && matchesStatus;
        }
    });

    if (extrasStatusSelect) extrasStatusSelect.addEventListener('change', () => extrasDataTable.refresh());
}

// ---- AJAX Form Submissions ----
async function submitForm(formEl, url, onSuccess) {
    const errorEl = formEl.querySelector('.form-error');
    errorEl.textContent = '';

    let hasSelectError = false;
    formEl.querySelectorAll('.custom-native-select[required]').forEach(select => {
        if (!select.value) {
            hasSelectError = true;
            const trigger = select.closest('.custom-select-wrapper')?.querySelector('.custom-select-trigger');
            if (trigger) {
                trigger.classList.add('border-rose-500', 'ring-2', 'ring-rose-500/20');
                setTimeout(() => trigger.classList.remove('border-rose-500', 'ring-2', 'ring-rose-500/20'), 2500);
            }
        }
    });

    if (hasSelectError) {
        errorEl.textContent = 'Please fill out all required fields.';
        return;
    }

    const formData = new FormData(formEl);

    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: formData,
        });
        const data = await res.json();

        if (!res.ok) {
            const firstError = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Something went wrong.');
            errorEl.textContent = firstError;
            return;
        }

        onSuccess(data);
        formEl.reset();
        resetImagePreview();
    } catch (err) {
        errorEl.textContent = 'Network error. Try again.';
    }
}

// ---- Product Form Submit ----
document.getElementById('product-form').addEventListener('submit', function (e) {
    e.preventDefault();
    submitForm(this, "{{ route('admin.products.store') }}", (data) => {
        const p = data.product;
        const row = document.createElement('tr');
        row.className = 'data-row border-b border-[#2a2731]/60 hover:bg-[#1e1c25]/40 transition';
        row.setAttribute('data-name', (p.name || '').toLowerCase());
        row.setAttribute('data-category', (p.category?.name || '').toLowerCase());
        row.setAttribute('data-price', p.price);
        row.setAttribute('data-status', p.is_available ? 'available' : 'unavailable');
        
        row.innerHTML = `
            <td class="py-3 px-4 sm:px-5"><img src="/storage/${p.image}" class="w-10 h-10 object-cover rounded-lg bg-[#0f0e13]"></td>
            <td class="px-4 sm:px-5 text-white font-medium whitespace-nowrap">${p.name}</td>
            <td class="px-4 sm:px-5 text-stone-400 whitespace-nowrap">${p.category.name}</td>
            <td class="px-4 sm:px-5 text-[#b08d57] font-bold whitespace-nowrap">${Number(p.price).toFixed(2)} ETB</td>
            <td class="px-4 sm:px-5 whitespace-nowrap">${p.is_available ? '<span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full"><i data-lucide="check" class="w-3 h-3"></i>Yes</span>' : '<span class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2.5 py-1 rounded-full"><i data-lucide="x" class="w-3 h-3"></i>No</span>'}</td>
        `;
        document.getElementById('products-table-body').prepend(row);
        if (productsDataTable) productsDataTable.refresh();
        setTimeout(() => lucide.createIcons(), 50);
    });
});

// ---- Staff Form Submit ----
document.getElementById('staff-form').addEventListener('submit', function (e) {
    e.preventDefault();
    submitForm(this, "{{ route('admin.staff.store') }}", (data) => {
        const s = data.staff;
        const row = document.createElement('tr');
        row.className = 'data-row border-b border-[#2a2731]/60 hover:bg-[#1e1c25]/40 transition';
        row.setAttribute('data-fullname', (s.fullname || '').toLowerCase());
        row.setAttribute('data-username', (s.username || '').toLowerCase());
        row.setAttribute('data-role', (s.role?.name || '').toLowerCase());
        row.setAttribute('data-phone', (s.phone || '').toLowerCase());

        row.innerHTML = `
            <td class="py-3 px-4 sm:px-5 text-white font-medium whitespace-nowrap">${s.fullname}</td>
            <td class="px-4 sm:px-5 text-stone-400 whitespace-nowrap">${s.username}</td>
            <td class="px-4 sm:px-5 whitespace-nowrap"><span class="inline-flex items-center text-xs font-semibold text-[#b08d57] bg-[#b08d57]/10 border border-[#b08d57]/20 px-2.5 py-1 rounded-full">${s.role.name.charAt(0).toUpperCase() + s.role.name.slice(1)}</span></td>
            <td class="px-4 sm:px-5 text-stone-400 whitespace-nowrap">${s.phone ?? ''}</td>
        `;
        document.getElementById('staff-table-body').prepend(row);
        if (staffDataTable) staffDataTable.refresh();
        setTimeout(() => lucide.createIcons(), 50);
    });
});

// ---- Extra Form Submit ----
document.getElementById('extra-form').addEventListener('submit', function (e) {
    e.preventDefault();
    submitForm(this, "{{ route('admin.extras.store') }}", (data) => {
        const ex = data.extra;
        const row = document.createElement('tr');
        row.className = 'data-row border-b border-[#2a2731]/60 hover:bg-[#1e1c25]/40 transition';
        row.setAttribute('data-name', (ex.name || '').toLowerCase());
        row.setAttribute('data-price', ex.price);
        row.setAttribute('data-status', ex.is_available ? 'available' : 'unavailable');

        row.innerHTML = `
            <td class="py-3 px-4 sm:px-5 text-white font-medium whitespace-nowrap">${ex.name}</td>
            <td class="px-4 sm:px-5 text-[#b08d57] font-bold whitespace-nowrap">${Number(ex.price).toFixed(2)} ETB</td>
            <td class="px-4 sm:px-5 whitespace-nowrap">${ex.is_available ? '<span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full"><i data-lucide="check" class="w-3 h-3"></i>Yes</span>' : '<span class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2.5 py-1 rounded-full"><i data-lucide="x" class="w-3 h-3"></i>No</span>'}</td>
        `;
        document.getElementById('extras-table-body').prepend(row);
        if (extrasDataTable) extrasDataTable.refresh();
        setTimeout(() => lucide.createIcons(), 50);
    });
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    initCustomSelects();
    initAllDataTables();
    initCharts();
    lucide.createIcons();
});
</script>
</body>
</html>