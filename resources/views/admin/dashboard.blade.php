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
        <!-- Hamburger Menu Button -->
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

    {{-- Sidebar (Desktop static + Mobile slide-out) --}}
    <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-64 lg:w-60 bg-[#0f0e13] border-r border-[#1e1c25] flex flex-col shrink-0 -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
        
        <!-- Sidebar Header with Close 'X' Button on Mobile -->
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

            <!-- X Close Button for Mobile -->
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

        {{-- OVERVIEW --}}
        <section id="section-overview" class="page-section">
            <div class="mb-6 sm:mb-8">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Overview</h2>
                <p class="text-stone-500 text-xs sm:text-sm mt-1">A quick snapshot of your store</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-5 sm:p-6 shadow-lg">
                    <div class="w-10 h-10 rounded-xl bg-[#1e1c25] flex items-center justify-center text-[#b08d57] mb-4">
                        <i data-lucide="package" class="w-5 h-5"></i>
                    </div>
                    <p class="text-stone-500 text-xs uppercase tracking-wider font-semibold">Products</p>
                    <p class="text-2xl sm:text-3xl font-black text-white mt-1">{{ $products->count() }}</p>
                </div>
                <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-5 sm:p-6 shadow-lg">
                    <div class="w-10 h-10 rounded-xl bg-[#1e1c25] flex items-center justify-center text-[#b08d57] mb-4">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                    <p class="text-stone-500 text-xs uppercase tracking-wider font-semibold">Staff / Delivery</p>
                    <p class="text-2xl sm:text-3xl font-black text-white mt-1">{{ $staff->count() }}</p>
                </div>
                <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-5 sm:p-6 shadow-lg sm:col-span-2 lg:col-span-1">
                    <div class="w-10 h-10 rounded-xl bg-[#1e1c25] flex items-center justify-center text-[#b08d57] mb-4">
                        <i data-lucide="sparkles" class="w-5 h-5"></i>
                    </div>
                    <p class="text-stone-500 text-xs uppercase tracking-wider font-semibold">Extras</p>
                    <p class="text-2xl sm:text-3xl font-black text-white mt-1">{{ $extras->count() }}</p>
                </div>
            </div>
        </section>

        {{-- PRODUCTS --}}
        <section id="section-products" class="page-section hidden">
            <div class="mb-6 sm:mb-8">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Products</h2>
                <p class="text-stone-500 text-xs sm:text-sm mt-1">Add and manage your menu items</p>
            </div>

            <form id="product-form" class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 mb-8 shadow-lg">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Category</label>
                    <select name="category_id" required class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
                        <option value="">Select category</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Name</label>
                    <input type="text" name="name" required class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Price</label>
                    <input type="number" step="0.01" name="price" required class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
                </div>
                
                <!-- Interactive Image Picker with Live Preview -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Product Image</label>
                    
                    <input type="file" id="product-image-input" name="image" accept="image/*" required class="hidden">
                    
                    <div id="image-dropzone" class="relative group cursor-pointer border-2 border-dashed border-[#2a2731] hover:border-[#b08d57]/70 bg-[#0f0e13]/80 hover:bg-[#14131a] rounded-xl p-3 sm:p-4 transition flex flex-col items-center justify-center min-h-[105px]">
                        
                        <!-- Initial Placeholder State -->
                        <div id="image-placeholder" class="flex flex-col items-center justify-center text-center py-1.5 space-y-1.5 pointer-events-none">
                            <div class="w-9 h-9 rounded-xl bg-[#1e1c25] group-hover:bg-[#b08d57]/20 flex items-center justify-center text-stone-400 group-hover:text-[#b08d57] transition">
                                <i data-lucide="image-plus" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-stone-300 group-hover:text-white transition">
                                    <span class="text-[#b08d57]">Click to upload</span> or drag image
                                </p>
                                <p class="text-[10px] text-stone-500">PNG, JPG, WEBP up to 5MB</p>
                            </div>
                        </div>

                        <!-- Image Preview State (Shows up when image is chosen) -->
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
                <div class="overflow-x-auto custom-scroll">
                    <table class="w-full text-sm text-left">
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
                                <tr class="border-b border-[#2a2731]/60 hover:bg-[#1e1c25]/40 transition">
                                    <td class="py-3 px-4 sm:px-5"><img src="{{ asset('storage/' . $p->image) }}" class="w-10 h-10 object-cover rounded-lg bg-[#0f0e13]"></td>
                                    <td class="px-4 sm:px-5 text-white font-medium whitespace-nowrap">{{ $p->name }}</td>
                                    <td class="px-4 sm:px-5 text-stone-400 whitespace-nowrap">{{ $p->category->name }}</td>
                                    <td class="px-4 sm:px-5 text-[#b08d57] font-bold whitespace-nowrap">${{ number_format($p->price, 2) }}</td>
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
            </div>
        </section>

        {{-- STAFF --}}
        <section id="section-staff" class="page-section hidden">
            <div class="mb-6 sm:mb-8">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Staff &amp; Delivery</h2>
                <p class="text-stone-500 text-xs sm:text-sm mt-1">Manage your team members and drivers</p>
            </div>

            <form id="staff-form" class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 mb-8 shadow-lg">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Role</label>
                    <select name="role_id" required class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
                        <option value="">Select role</option>
                        @foreach ($roles as $r)
                            <option value="{{ $r->id }}">{{ ucfirst($r->name) }}</option>
                        @endforeach
                    </select>
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
                <div class="overflow-x-auto custom-scroll">
                    <table class="w-full text-sm text-left">
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
                                <tr class="border-b border-[#2a2731]/60 hover:bg-[#1e1c25]/40 transition">
                                    <td class="py-3 px-4 sm:px-5 text-white font-medium whitespace-nowrap">{{ $s->fullname }}</td>
                                    <td class="px-4 sm:px-5 text-stone-400 whitespace-nowrap">{{ $s->username }}</td>
                                    <td class="px-4 sm:px-5 whitespace-nowrap"><span class="inline-flex items-center text-xs font-semibold text-[#b08d57] bg-[#b08d57]/10 border border-[#b08d57]/20 px-2.5 py-1 rounded-full">{{ ucfirst($s->role->name) }}</span></td>
                                    <td class="px-4 sm:px-5 text-stone-400 whitespace-nowrap">{{ $s->phone }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        {{-- EXTRAS --}}
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
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Price</label>
                    <input type="number" step="0.01" name="price" required class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
                </div>
                <div class="sm:col-span-2 flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
                    <p class="text-rose-400 text-xs sm:text-sm form-error" data-form="extra"></p>
                    <button type="submit" class="bg-[#b08d57] hover:bg-[#c9a36b] text-[#0f0e13] font-bold rounded-xl px-5 py-2.5 text-sm transition flex items-center justify-center gap-2">
                        <i data-lucide="plus" class="w-4 h-4"></i><span>Create Extra</span>
                    </button>
                </div>
            </form>

            <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl overflow-hidden shadow-lg">
                <div class="overflow-x-auto custom-scroll">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="text-stone-400 border-b border-[#2a2731] bg-[#0f0e13]/50">
                                <th class="py-3 px-4 sm:px-5 font-semibold">Name</th>
                                <th class="px-4 sm:px-5 font-semibold">Price</th>
                                <th class="px-4 sm:px-5 font-semibold">Available</th>
                            </tr>
                        </thead>
                        <tbody id="extras-table-body">
                            @foreach ($extras as $e)
                                <tr class="border-b border-[#2a2731]/60 hover:bg-[#1e1c25]/40 transition">
                                    <td class="py-3 px-4 sm:px-5 text-white font-medium whitespace-nowrap">{{ $e->name }}</td>
                                    <td class="px-4 sm:px-5 text-[#b08d57] font-bold whitespace-nowrap">${{ number_format($e->price, 2) }}</td>
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
            </div>
        </section>

    </main>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

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

// ---- Sidebar navigation switching ----
const navLinks = document.querySelectorAll('.nav-link');
const sections = document.querySelectorAll('.page-section');

function showSection(target) {
    sections.forEach(s => s.classList.add('hidden'));
    document.getElementById('section-' + target).classList.remove('hidden');
    navLinks.forEach(l => l.classList.remove('active'));
    document.querySelectorAll(`.nav-link[data-target="${target}"]`).forEach(l => l.classList.add('active'));
    
    // Auto-close drawer on mobile when item selected
    closeMobileSidebar();
    
    setTimeout(() => lucide.createIcons(), 50);
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

// ---- generic AJAX submit helper ----
async function submitForm(formEl, url, onSuccess, isMultipart = false) {
    const formData = new FormData(formEl);
    const errorEl = formEl.querySelector('.form-error');
    errorEl.textContent = '';

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

// ---- product form ----
document.getElementById('product-form').addEventListener('submit', function (e) {
    e.preventDefault();
    submitForm(this, "{{ route('admin.products.store') }}", (data) => {
        const p = data.product;
        const row = document.createElement('tr');
        row.className = 'border-b border-[#2a2731]/60 hover:bg-[#1e1c25]/40 transition';
        row.innerHTML = `
            <td class="py-3 px-4 sm:px-5"><img src="/storage/${p.image}" class="w-10 h-10 object-cover rounded-lg bg-[#0f0e13]"></td>
            <td class="px-4 sm:px-5 text-white font-medium whitespace-nowrap">${p.name}</td>
            <td class="px-4 sm:px-5 text-stone-400 whitespace-nowrap">${p.category.name}</td>
            <td class="px-4 sm:px-5 text-[#b08d57] font-bold whitespace-nowrap">$${Number(p.price).toFixed(2)}</td>
            <td class="px-4 sm:px-5 whitespace-nowrap">${p.is_available ? '<span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full"><i data-lucide="check" class="w-3 h-3"></i>Yes</span>' : '<span class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2.5 py-1 rounded-full"><i data-lucide="x" class="w-3 h-3"></i>No</span>'}</td>
        `;
        document.getElementById('products-table-body').prepend(row);
        setTimeout(() => lucide.createIcons(), 50);
    });
});

// ---- staff form ----
document.getElementById('staff-form').addEventListener('submit', function (e) {
    e.preventDefault();
    submitForm(this, "{{ route('admin.staff.store') }}", (data) => {
        const s = data.staff;
        const row = document.createElement('tr');
        row.className = 'border-b border-[#2a2731]/60 hover:bg-[#1e1c25]/40 transition';
        row.innerHTML = `
            <td class="py-3 px-4 sm:px-5 text-white font-medium whitespace-nowrap">${s.fullname}</td>
            <td class="px-4 sm:px-5 text-stone-400 whitespace-nowrap">${s.username}</td>
            <td class="px-4 sm:px-5 whitespace-nowrap"><span class="inline-flex items-center text-xs font-semibold text-[#b08d57] bg-[#b08d57]/10 border border-[#b08d57]/20 px-2.5 py-1 rounded-full">${s.role.name.charAt(0).toUpperCase() + s.role.name.slice(1)}</span></td>
            <td class="px-4 sm:px-5 text-stone-400 whitespace-nowrap">${s.phone ?? ''}</td>
        `;
        document.getElementById('staff-table-body').prepend(row);
        setTimeout(() => lucide.createIcons(), 50);
    });
});

// ---- extra form ----
document.getElementById('extra-form').addEventListener('submit', function (e) {
    e.preventDefault();
    submitForm(this, "{{ route('admin.extras.store') }}", (data) => {
        const ex = data.extra;
        const row = document.createElement('tr');
        row.className = 'border-b border-[#2a2731]/60 hover:bg-[#1e1c25]/40 transition';
        row.innerHTML = `
            <td class="py-3 px-4 sm:px-5 text-white font-medium whitespace-nowrap">${ex.name}</td>
            <td class="px-4 sm:px-5 text-[#b08d57] font-bold whitespace-nowrap">$${Number(ex.price).toFixed(2)}</td>
            <td class="px-4 sm:px-5 whitespace-nowrap">${ex.is_available ? '<span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full"><i data-lucide="check" class="w-3 h-3"></i>Yes</span>' : '<span class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2.5 py-1 rounded-full"><i data-lucide="x" class="w-3 h-3"></i>No</span>'}</td>
        `;
        document.getElementById('extras-table-body').prepend(row);
        setTimeout(() => lucide.createIcons(), 50);
    });
});

// Initialize icons on mount
document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();
});
</script>
</body>
</html>