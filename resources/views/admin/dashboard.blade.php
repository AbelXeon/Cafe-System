<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal | FoodieDash Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        amber: { 500: '#f59e0b', 600: '#d97706', 700: '#b45309' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-neutral-950 text-stone-100 font-sans antialiased" x-data="{ tab: 'overview', openProductModal: false, openStaffModal: false, openCategoryModal: false, openExtraModal: false }">

    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR -->
        <aside class="w-64 bg-stone-900/90 border-r border-stone-800 flex flex-col justify-between p-4 flex-shrink-0">
            <div>
                <!-- Brand -->
                <div class="flex items-center gap-3 px-3 py-4 mb-6 border-b border-stone-800">
                    <div class="w-10 h-10 bg-amber-500/20 text-amber-500 rounded-xl flex items-center justify-center border border-amber-500/30">
                        <i class="fa-solid fa-mug-hot text-xl"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-white tracking-wide">FoodieDash</h2>
                        <span class="text-[11px] text-amber-500 font-semibold tracking-wider uppercase">Admin Control</span>
                    </div>
                </div>

                <!-- Navigation Tabs (No Page Reload) -->
                <nav class="space-y-1.5">
                    <button @click="tab = 'overview'" :class="tab === 'overview' ? 'bg-amber-500 text-stone-950 font-bold' : 'text-stone-400 hover:bg-stone-800 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all">
                        <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                        <span>Overview</span>
                    </button>

                    <button @click="tab = 'products'" :class="tab === 'products' ? 'bg-amber-500 text-stone-950 font-bold' : 'text-stone-400 hover:bg-stone-800 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all">
                        <i class="fa-solid fa-utensils w-5 text-center"></i>
                        <span>Food & Drinks</span>
                    </button>

                    <button @click="tab = 'staff'" :class="tab === 'staff' ? 'bg-amber-500 text-stone-950 font-bold' : 'text-stone-400 hover:bg-stone-800 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all">
                        <i class="fa-solid fa-users-gear w-5 text-center"></i>
                        <span>Staff & Drivers</span>
                    </button>

                    <button @click="tab = 'categories'" :class="tab === 'categories' ? 'bg-amber-500 text-stone-950 font-bold' : 'text-stone-400 hover:bg-stone-800 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all">
                        <i class="fa-solid fa-tags w-5 text-center"></i>
                        <span>Categories</span>
                    </button>

                    <button @click="tab = 'extras'" :class="tab === 'extras' ? 'bg-amber-500 text-stone-950 font-bold' : 'text-stone-400 hover:bg-stone-800 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all">
                        <i class="fa-solid fa-plus-circle w-5 text-center"></i>
                        <span>Extras & Add-ons</span>
                    </button>

                    <button @click="tab = 'orders'" :class="tab === 'orders' ? 'bg-amber-500 text-stone-950 font-bold' : 'text-stone-400 hover:bg-stone-800 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all">
                        <i class="fa-solid fa-receipt w-5 text-center"></i>
                        <span>Orders</span>
                    </button>
                </nav>
            </div>

            <!-- Current User & Logout -->
            <div class="border-t border-stone-800 pt-4">
                <div class="flex items-center gap-3 px-2 mb-3">
                    <div class="w-9 h-9 rounded-full bg-stone-800 flex items-center justify-center font-bold text-amber-500 text-sm">
                        {{ strtoupper(substr(auth()->user()->fullname ?? 'A', 0, 2)) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-semibold truncate">{{ auth()->user()->fullname }}</p>
                        <p class="text-xs text-stone-400 truncate">@<span>{{ auth()->user()->username }}</span></p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-3 bg-stone-800 hover:bg-rose-500/20 text-stone-300 hover:text-rose-400 rounded-xl text-xs font-semibold flex items-center justify-center gap-2 transition">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 overflow-y-auto p-8">
            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-2xl flex items-center gap-3 text-sm">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/30 text-rose-400 rounded-2xl text-sm">
                    <div class="font-bold mb-1">Please fix the following issues:</div>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- TAB 1: OVERVIEW -->
            <section x-show="tab === 'overview'" class="space-y-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-black tracking-tight text-white">Dashboard Overview</h1>
                        <p class="text-stone-400 text-sm">Real-time statistics & cafe summary</p>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div class="bg-stone-900 border border-stone-800 p-5 rounded-2xl">
                        <div class="flex items-center justify-between text-stone-400 text-sm mb-3">
                            <span>Total Users</span>
                            <i class="fa-solid fa-users text-amber-500"></i>
                        </div>
                        <div class="text-3xl font-extrabold">{{ $stats['total_users'] }}</div>
                    </div>
                    <div class="bg-stone-900 border border-stone-800 p-5 rounded-2xl">
                        <div class="flex items-center justify-between text-stone-400 text-sm mb-3">
                            <span>Products</span>
                            <i class="fa-solid fa-burger text-amber-500"></i>
                        </div>
                        <div class="text-3xl font-extrabold">{{ $stats['total_products'] }}</div>
                    </div>
                    <div class="bg-stone-900 border border-stone-800 p-5 rounded-2xl">
                        <div class="flex items-center justify-between text-stone-400 text-sm mb-3">
                            <span>Total Orders</span>
                            <i class="fa-solid fa-receipt text-amber-500"></i>
                        </div>
                        <div class="text-3xl font-extrabold">{{ $stats['total_orders'] }}</div>
                    </div>
                    <div class="bg-stone-900 border border-stone-800 p-5 rounded-2xl">
                        <div class="flex items-center justify-between text-stone-400 text-sm mb-3">
                            <span>Pending Orders</span>
                            <i class="fa-solid fa-clock text-amber-500"></i>
                        </div>
                        <div class="text-3xl font-extrabold text-amber-500">{{ $stats['pending_orders'] }}</div>
                    </div>
                </div>

                <!-- Quick Activity Table -->
                <div class="bg-stone-900 border border-stone-800 rounded-2xl p-6">
                    <h3 class="text-lg font-bold mb-4">Recent Orders</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-stone-300">
                            <thead class="bg-stone-950 text-stone-400 uppercase text-xs">
                                <tr>
                                    <th class="p-3">Order #</th>
                                    <th class="p-3">Customer</th>
                                    <th class="p-3">Type</th>
                                    <th class="p-3">Total</th>
                                    <th class="p-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-800">
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td class="p-3 font-mono">#{{ $order->id }}</td>
                                        <td class="p-3">{{ $order->user->fullname ?? 'Guest' }}</td>
                                        <td class="p-3 capitalize">{{ $order->order_type }}</td>
                                        <td class="p-3 font-semibold text-amber-500">${{ number_format($order->total_amount, 2) }}</td>
                                        <td class="p-3">
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase bg-stone-800 text-stone-300">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-4 text-center text-stone-500">No orders placed yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- TAB 2: FOOD & DRINKS (PRODUCTS) -->
            <section x-show="tab === 'products'" class="space-y-6" style="display: none;">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-black">Food & Drinks Menu</h1>
                        <p class="text-stone-400 text-sm">Add items with up to 3 images and pricing</p>
                    </div>
                    <button @click="openProductModal = true" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-stone-950 font-bold rounded-xl text-sm flex items-center gap-2 transition shadow-lg shadow-amber-500/20">
                        <i class="fa-solid fa-plus"></i>
                        <span>Add New Item</span>
                    </button>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @forelse($products as $prod)
                        @php
                            $imgs = json_decode($prod->image, true);
                            $firstImg = (!empty($imgs) && is_array($imgs)) ? asset('storage/' . $imgs[0]) : null;
                        @endphp
                        <div class="bg-stone-900 border border-stone-800 rounded-2xl overflow-hidden flex flex-col justify-between group">
                            <div class="relative h-44 bg-stone-950 overflow-hidden">
                                @if($firstImg)
                                    <img src="{{ $firstImg }}" alt="{{ $prod->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-stone-700 text-3xl">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif
                                <span class="absolute top-3 right-3 bg-stone-950/80 backdrop-blur-md px-2.5 py-1 rounded-lg text-xs font-bold {{ $prod->is_available ? 'text-emerald-400' : 'text-rose-400' }}">
                                    {{ $prod->is_available ? 'Available' : 'Out of Stock' }}
                                </span>
                            </div>
                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="text-xs text-amber-500 font-semibold uppercase mb-1">{{ $prod->category->name ?? 'Uncategorized' }}</div>
                                    <h4 class="font-bold text-base text-white truncate">{{ $prod->name }}</h4>
                                    <p class="text-stone-400 text-xs mt-1 line-clamp-2">{{ $prod->description ?? 'No description.' }}</p>
                                </div>
                                <div class="mt-4 pt-3 border-t border-stone-800 flex items-center justify-between">
                                    <span class="text-lg font-black text-amber-500">${{ number_format($prod->price, 2) }}</span>
                                    <form action="{{ route('admin.products.delete', $prod->id) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-stone-500 hover:text-rose-500 transition text-sm">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center text-stone-500 bg-stone-900 border border-stone-800 rounded-2xl">
                            No menu items added yet. Click "Add New Item" to create one.
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- TAB 3: STAFF & DRIVERS -->
            <section x-show="tab === 'staff'" class="space-y-6" style="display: none;">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-black">Staff & Delivery Drivers</h1>
                        <p class="text-stone-400 text-sm">Create accounts for staff, kitchen, or drivers</p>
                    </div>
                    <button @click="openStaffModal = true" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-stone-950 font-bold rounded-xl text-sm flex items-center gap-2 transition shadow-lg shadow-amber-500/20">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>Register Staff/Driver</span>
                    </button>
                </div>

                <div class="bg-stone-900 border border-stone-800 rounded-2xl overflow-hidden">
                    <table class="w-full text-left text-sm text-stone-300">
                        <thead class="bg-stone-950 text-stone-400 uppercase text-xs border-b border-stone-800">
                            <tr>
                                <th class="p-4">Name</th>
                                <th class="p-4">Role</th>
                                <th class="p-4">Username</th>
                                <th class="p-4">Email</th>
                                <th class="p-4">Phone</th>
                                <th class="p-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-800">
                            @foreach($staffMembers as $member)
                                <tr>
                                    <td class="p-4 font-semibold text-white">{{ $member->fullname }}</td>
                                    <td class="p-4">
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase {{ $member->role->name === 'admin' ? 'bg-amber-500/20 text-amber-400' : ($member->role->name === 'delivery' ? 'bg-blue-500/20 text-blue-400' : 'bg-emerald-500/20 text-emerald-400') }}">
                                            {{ $member->role->name ?? 'User' }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-stone-400">@<span>{{ $member->username }}</span></td>
                                    <td class="p-4 text-stone-400">{{ $member->email }}</td>
                                    <td class="p-4 text-stone-400">{{ $member->phone }}</td>
                                    <td class="p-4 text-right">
                                        @if($member->id !== auth()->id())
                                            <form action="{{ route('admin.users.delete', $member->id) }}" method="POST" onsubmit="return confirm('Delete this member?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-stone-500 hover:text-rose-500 transition">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-stone-600">Current</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- TAB 4: CATEGORIES -->
            <section x-show="tab === 'categories'" class="space-y-6" style="display: none;">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-black">Food Categories</h1>
                        <p class="text-stone-400 text-sm">Organize items into Drinks, Meals, Desserts, etc.</p>
                    </div>
                    <button @click="openCategoryModal = true" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-stone-950 font-bold rounded-xl text-sm flex items-center gap-2 transition">
                        <i class="fa-solid fa-plus"></i>
                        <span>New Category</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    @forelse($categories as $cat)
                        <div class="bg-stone-900 border border-stone-800 p-5 rounded-2xl">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-bold text-white text-lg">{{ $cat->name }}</h3>
                                <span class="text-xs px-2.5 py-1 rounded bg-stone-800 text-amber-500 uppercase font-bold">{{ $cat->type }}</span>
                            </div>
                            <p class="text-stone-400 text-xs">{{ $cat->description ?? 'No description provided.' }}</p>
                        </div>
                    @empty
                        <div class="col-span-full py-8 text-center text-stone-500 bg-stone-900 border border-stone-800 rounded-2xl">
                            No categories yet. Click "New Category" to add.
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- TAB 5: EXTRAS -->
            <section x-show="tab === 'extras'" class="space-y-6" style="display: none;">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-black">Extras & Add-ons</h1>
                        <p class="text-stone-400 text-sm">Optional toppings, extra shots, milk types, sauces</p>
                    </div>
                    <button @click="openExtraModal = true" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-stone-950 font-bold rounded-xl text-sm flex items-center gap-2 transition">
                        <i class="fa-solid fa-plus"></i>
                        <span>New Extra</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @forelse($extras as $extra)
                        <div class="bg-stone-900 border border-stone-800 p-4 rounded-2xl flex items-center justify-between">
                            <div>
                                <h4 class="font-bold text-sm text-white">{{ $extra->name }}</h4>
                                <span class="text-xs text-amber-500 font-bold">+${{ number_format($extra->price, 2) }}</span>
                            </div>
                            <span class="text-xs px-2 py-0.5 rounded {{ $extra->is_available ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' }}">
                                {{ $extra->is_available ? 'Active' : 'Off' }}
                            </span>
                        </div>
                    @empty
                        <div class="col-span-full py-8 text-center text-stone-500 bg-stone-900 border border-stone-800 rounded-2xl">
                            No extras configured.
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- TAB 6: ORDERS -->
            <section x-show="tab === 'orders'" class="space-y-6" style="display: none;">
                <div>
                    <h1 class="text-2xl font-black">Order Management</h1>
                    <p class="text-stone-400 text-sm">All incoming cafe orders and deliveries</p>
                </div>

                <div class="bg-stone-900 border border-stone-800 rounded-2xl overflow-hidden">
                    <table class="w-full text-left text-sm text-stone-300">
                        <thead class="bg-stone-950 text-stone-400 uppercase text-xs border-b border-stone-800">
                            <tr>
                                <th class="p-4">Order ID</th>
                                <th class="p-4">Customer</th>
                                <th class="p-4">Type</th>
                                <th class="p-4">Subtotal</th>
                                <th class="p-4">Fee</th>
                                <th class="p-4">Total</th>
                                <th class="p-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-800">
                            @forelse($recentOrders as $ord)
                                <tr>
                                    <td class="p-4 font-mono font-bold text-white">#{{ $ord->id }}</td>
                                    <td class="p-4">{{ $ord->user->fullname ?? 'N/A' }}</td>
                                    <td class="p-4 capitalize">{{ $ord->order_type }}</td>
                                    <td class="p-4">${{ number_format($ord->subtotal, 2) }}</td>
                                    <td class="p-4">${{ number_format($ord->delivery_fee, 2) }}</td>
                                    <td class="p-4 font-bold text-amber-500">${{ number_format($ord->total_amount, 2) }}</td>
                                    <td class="p-4">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase bg-stone-800 text-amber-400">
                                            {{ $ord->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-6 text-center text-stone-500">No orders yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <!-- MODAL: ADD PRODUCT -->
    <div x-show="openProductModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm" style="display: none;">
        <div @click.away="openProductModal = false" class="bg-stone-900 border border-stone-800 w-full max-w-xl rounded-3xl p-6 shadow-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-stone-800">
                <h3 class="text-lg font-bold text-white">Create Food / Drink Item</h3>
                <button @click="openProductModal = false" class="text-stone-500 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold uppercase text-stone-400 mb-1">Category</label>
                    <select name="category_id" required class="w-full bg-stone-950 border border-stone-800 rounded-xl p-3 text-sm text-white focus:border-amber-500">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->type }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-stone-400 mb-1">Item Name</label>
                    <input type="text" name="name" required class="w-full bg-stone-950 border border-stone-800 rounded-xl p-3 text-sm text-white focus:border-amber-500" placeholder="e.g. Caramel Macchiato">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-stone-400 mb-1">Price ($)</label>
                    <input type="number" step="0.01" name="price" required class="w-full bg-stone-950 border border-stone-800 rounded-xl p-3 text-sm text-white focus:border-amber-500" placeholder="4.50">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-stone-400 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full bg-stone-950 border border-stone-800 rounded-xl p-3 text-sm text-white focus:border-amber-500" placeholder="Short description..."></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-stone-400 mb-1">Item Images (Select 1 to 3)</label>
                    <input type="file" name="images[]" multiple accept="image/*" class="w-full bg-stone-950 border border-stone-800 rounded-xl p-2.5 text-xs text-stone-400 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-500 file:text-stone-950 hover:file:bg-amber-600">
                    <p class="text-[11px] text-stone-500 mt-1">PNG, JPG, WEBP up to 2MB each.</p>
                </div>

                @if($extras->count() > 0)
                <div>
                    <label class="block text-xs font-semibold uppercase text-stone-400 mb-2">Available Extras / Add-ons</label>
                    <div class="grid grid-cols-2 gap-2 bg-stone-950 p-3 rounded-xl border border-stone-800 max-h-36 overflow-y-auto">
                        @foreach($extras as $extra)
                            <label class="flex items-center gap-2 text-xs text-stone-300">
                                <input type="checkbox" name="extras[]" value="{{ $extra->id }}" class="accent-amber-500">
                                <span>{{ $extra->name }} (+${{ $extra->price }})</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" name="is_available" value="1" id="is_available" checked class="accent-amber-500">
                    <label for="is_available" class="text-sm font-semibold text-stone-300">Item is Available in Cafe</label>
                </div>

                <div class="pt-4 border-t border-stone-800 flex justify-end gap-3">
                    <button type="button" @click="openProductModal = false" class="px-4 py-2.5 bg-stone-800 text-stone-300 rounded-xl text-sm font-bold">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-stone-950 rounded-xl text-sm font-bold shadow-lg shadow-amber-500/20">Save Product</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: ADD STAFF / DRIVER -->
    <div x-show="openStaffModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm" style="display: none;">
        <div @click.away="openStaffModal = false" class="bg-stone-900 border border-stone-800 w-full max-w-lg rounded-3xl p-6 shadow-2xl">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-stone-800">
                <h3 class="text-lg font-bold text-white">Create Staff or Driver Account</h3>
                <button @click="openStaffModal = false" class="text-stone-500 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold uppercase text-stone-400 mb-1">Assign Role</label>
                    <select name="role_id" required class="w-full bg-stone-950 border border-stone-800 rounded-xl p-3 text-sm text-white focus:border-amber-500">
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-stone-400 mb-1">Full Name</label>
                    <input type="text" name="fullname" required class="w-full bg-stone-950 border border-stone-800 rounded-xl p-3 text-sm text-white focus:border-amber-500" placeholder="John Doe">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase text-stone-400 mb-1">Username</label>
                        <input type="text" name="username" required class="w-full bg-stone-950 border border-stone-800 rounded-xl p-3 text-sm text-white focus:border-amber-500" placeholder="johndoe">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-stone-400 mb-1">Phone</label>
                        <input type="text" name="phone" required class="w-full bg-stone-950 border border-stone-800 rounded-xl p-3 text-sm text-white focus:border-amber-500" placeholder="0911223344">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-stone-400 mb-1">Email</label>
                    <input type="email" name="email" required class="w-full bg-stone-950 border border-stone-800 rounded-xl p-3 text-sm text-white focus:border-amber-500" placeholder="john@foodiedash.com">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-stone-400 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full bg-stone-950 border border-stone-800 rounded-xl p-3 text-sm text-white focus:border-amber-500" placeholder="Minimum 6 characters">
                </div>

                <div class="pt-4 border-t border-stone-800 flex justify-end gap-3">
                    <button type="button" @click="openStaffModal = false" class="px-4 py-2.5 bg-stone-800 text-stone-300 rounded-xl text-sm font-bold">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-stone-950 rounded-xl text-sm font-bold shadow-lg shadow-amber-500/20">Create Member</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: ADD CATEGORY -->
    <div x-show="openCategoryModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm" style="display: none;">
        <div @click.away="openCategoryModal = false" class="bg-stone-900 border border-stone-800 w-full max-w-md rounded-3xl p-6 shadow-2xl">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-stone-800">
                <h3 class="text-lg font-bold text-white">Create Category</h3>
                <button @click="openCategoryModal = false" class="text-stone-500 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold uppercase text-stone-400 mb-1">Category Name</label>
                    <input type="text" name="name" required class="w-full bg-stone-950 border border-stone-800 rounded-xl p-3 text-sm text-white focus:border-amber-500" placeholder="Hot Coffee">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-stone-400 mb-1">Type</label>
                    <select name="type" required class="w-full bg-stone-950 border border-stone-800 rounded-xl p-3 text-sm text-white focus:border-amber-500">
                        <option value="drink">Drink</option>
                        <option value="food">Food</option>
                        <option value="dessert">Dessert</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-stone-400 mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full bg-stone-950 border border-stone-800 rounded-xl p-3 text-sm text-white focus:border-amber-500" placeholder="Category details..."></textarea>
                </div>

                <div class="pt-4 border-t border-stone-800 flex justify-end gap-3">
                    <button type="button" @click="openCategoryModal = false" class="px-4 py-2.5 bg-stone-800 text-stone-300 rounded-xl text-sm font-bold">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-stone-950 rounded-xl text-sm font-bold shadow-lg shadow-amber-500/20">Save Category</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: ADD EXTRA -->
    <div x-show="openExtraModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm" style="display: none;">
        <div @click.away="openExtraModal = false" class="bg-stone-900 border border-stone-800 w-full max-w-md rounded-3xl p-6 shadow-2xl">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-stone-800">
                <h3 class="text-lg font-bold text-white">Create Extra Add-on</h3>
                <button @click="openExtraModal = false" class="text-stone-500 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form action="{{ route('admin.extras.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold uppercase text-stone-400 mb-1">Extra Name</label>
                    <input type="text" name="name" required class="w-full bg-stone-950 border border-stone-800 rounded-xl p-3 text-sm text-white focus:border-amber-500" placeholder="Extra Shot / Oat Milk">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-stone-400 mb-1">Price ($)</label>
                    <input type="number" step="0.01" name="price" required class="w-full bg-stone-950 border border-stone-800 rounded-xl p-3 text-sm text-white focus:border-amber-500" placeholder="0.75">
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" name="is_available" value="1" id="extra_available" checked class="accent-amber-500">
                    <label for="extra_available" class="text-sm font-semibold text-stone-300">Active / Available</label>
                </div>

                <div class="pt-4 border-t border-stone-800 flex justify-end gap-3">
                    <button type="button" @click="openExtraModal = false" class="px-4 py-2.5 bg-stone-800 text-stone-300 rounded-xl text-sm font-bold">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-stone-950 rounded-xl text-sm font-bold shadow-lg shadow-amber-500/20">Save Extra</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>