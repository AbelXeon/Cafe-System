{{-- resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Cafe System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['Space Grotesk', 'sans-serif'],
                        body: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        indigo: {
                            50: '#eef2ff', 100: '#e0e7ff', 500: '#6366f1',
                            600: '#4f46e5', 700: '#4338ca', 900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 font-body text-slate-800"
      x-data="{ tab: new URLSearchParams(window.location.search).get('tab') || 'dashboard' }">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col fixed h-full">
            <div class="px-6 py-5 border-b border-slate-800">
                <h1 class="font-display text-xl font-bold text-white">☕ CafeAdmin</h1>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-1">
                <button @click="tab = 'dashboard'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition"
                    :class="tab === 'dashboard' ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300'">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    Dashboard
                </button>
                <button @click="tab = 'staff'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition"
                    :class="tab === 'staff' ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300'">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 100-8 4 4 0 000 8zm6 3v-1a4 4 0 00-3-3.87" /></svg>
                    Staff & Delivery
                </button>
                <button @click="tab = 'menu'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition"
                    :class="tab === 'menu' ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300'">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    Categories & Menu
                </button>
                <button @click="tab = 'orders'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition"
                    :class="tab === 'orders' ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300'">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    Orders
                </button>
            </nav>
            <div class="px-4 py-4 border-t border-slate-800">
                <div class="flex items-center gap-3 px-2">
                    <div class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold text-sm shrink-0">
                        {{ strtoupper(substr(auth()->user()->fullname, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->fullname }}</p>
                        <p class="text-xs text-slate-500">Administrator</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full text-left text-xs text-slate-400 hover:text-rose-400 transition px-2">← Sign out</button>
                </form>
            </div>
        </aside>

        {{-- Main content --}}
        <main class="flex-1 ml-64 p-8">

            @if(session('success'))
                <div class="mb-6 px-4 py-3 rounded-lg bg-emerald-50 text-emerald-700 text-sm font-medium border border-emerald-200">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 px-4 py-3 rounded-lg bg-rose-50 text-rose-700 text-sm font-medium border border-rose-200">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 px-4 py-3 rounded-lg bg-rose-50 text-rose-700 text-sm border border-rose-200">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- ===================== DASHBOARD TAB ===================== --}}
            <div x-show="tab === 'dashboard'" x-cloak>
                <div class="mb-8">
                    <h2 class="font-display text-2xl font-bold text-slate-900">Dashboard Overview</h2>
                    <p class="text-sm text-slate-500 mt-1">Welcome back, {{ auth()->user()->fullname }}. Here's what's happening today.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
                    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Total Orders</p>
                        <p class="font-display text-3xl font-bold text-slate-900 mt-3">{{ $stats['total_orders'] }}</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Pending Orders</p>
                        <p class="font-display text-3xl font-bold text-slate-900 mt-3">{{ $stats['pending_orders'] }}</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Menu Items</p>
                        <p class="font-display text-3xl font-bold text-slate-900 mt-3">{{ $stats['total_products'] }}</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Staff Members</p>
                        <p class="font-display text-3xl font-bold text-slate-900 mt-3">{{ $stats['total_staff'] }}</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Delivery Riders</p>
                        <p class="font-display text-3xl font-bold text-slate-900 mt-3">{{ $stats['total_delivery'] }}</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Customers</p>
                        <p class="font-display text-3xl font-bold text-slate-900 mt-3">{{ $stats['total_customers'] }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200">
                            <h3 class="font-display font-semibold text-slate-900">Recent Orders</h3>
                        </div>
                        <div class="divide-y divide-slate-100">
                            @forelse($recentOrders as $order)
                                <div class="px-6 py-3 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-slate-800">#{{ $order->id }} — {{ $order->user->fullname ?? 'Guest' }}</p>
                                        <p class="text-xs text-slate-500">{{ ucfirst($order->order_type) }} · {{ $order->created_at->diffForHumans() }}</p>
                                    </div>
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700">{{ ucfirst($order->status) }}</span>
                                </div>
                            @empty
                                <p class="px-6 py-6 text-sm text-slate-400 text-center">No orders yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                            <h3 class="font-display font-semibold text-slate-900">Recently Added Accounts</h3>
                            <button @click="tab = 'staff'" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">View all →</button>
                        </div>
                        <div class="divide-y divide-slate-100">
                            @forelse($recentUsers as $user)
                                <div class="px-6 py-3 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-slate-800">{{ $user->fullname }}</p>
                                        <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                    </div>
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">{{ ucfirst($user->role->name ?? 'N/A') }}</span>
                                </div>
                            @empty
                                <p class="px-6 py-6 text-sm text-slate-400 text-center">No accounts yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================== STAFF & DELIVERY TAB ===================== --}}
            <div x-show="tab === 'staff'" x-cloak x-data="staffManager()">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="font-display text-2xl font-bold text-slate-900">Staff & Delivery</h2>
                        <p class="text-sm text-slate-500 mt-1">Create and manage staff and delivery accounts.</p>
                    </div>
                    <button @click="openCreate()" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
                        + Add Account
                    </button>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                <th class="px-6 py-3">Name</th>
                                <th class="px-6 py-3">Username</th>
                                <th class="px-6 py-3">Phone</th>
                                <th class="px-6 py-3">Role</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($staffAndDelivery as $user)
                                <tr>
                                    <td class="px-6 py-3 font-medium text-slate-800">{{ $user->fullname }}</td>
                                    <td class="px-6 py-3 text-slate-500">{{ $user->username }}</td>
                                    <td class="px-6 py-3 text-slate-500">{{ $user->phone }}</td>
                                    <td class="px-6 py-3">
                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                            {{ $user->role->name === 'staff' ? 'bg-indigo-50 text-indigo-700' : 'bg-rose-50 text-rose-700' }}">
                                            {{ ucfirst($user->role->name) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-right space-x-2">
                                        <button @click="openEdit({{ Illuminate\Support\Js::from([
                                            'id' => $user->id,
                                            'fullname' => $user->fullname,
                                            'phone' => $user->phone,
                                            'email' => $user->email,
                                            'username' => $user->username,
                                            'role' => $user->role->name,
                                        ]) }})" class="text-indigo-600 hover:text-indigo-800 text-xs font-semibold">Edit</button>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Delete {{ $user->fullname }}? This cannot be undone.');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 text-xs font-semibold">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">No staff or delivery accounts yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Modal --}}
                <div x-show="modalOpen" x-cloak class="fixed inset-0 bg-slate-900/50 flex items-center justify-center z-50 p-4"
                     @keydown.escape.window="close()">
                    <div @click.outside="close()" class="bg-white rounded-2xl w-full max-w-md p-6 shadow-xl">
                        <h3 class="font-display text-lg font-bold text-slate-900 mb-4" x-text="mode === 'create' ? 'Add Staff / Delivery Account' : 'Edit Account'"></h3>

                        <form :action="mode === 'create' ? '{{ route('admin.users.store') }}' : `/admin/users/${form.id}`" method="POST" class="space-y-3">
                            @csrf
                            <template x-if="mode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>

                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Full name</label>
                                <input type="text" name="fullname" x-model="form.fullname" required
                                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Phone</label>
                                <input type="text" name="phone" x-model="form.phone" required
                                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Email</label>
                                <input type="email" name="email" x-model="form.email" required
                                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Username</label>
                                <input type="text" name="username" x-model="form.username" required
                                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Role</label>
                                <select name="role" x-model="form.role" required
                                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="staff">Staff</option>
                                    <option value="delivery">Delivery</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">
                                    Password <span x-show="mode === 'edit'" class="text-slate-400">(leave blank to keep current)</span>
                                </label>
                                <input type="password" name="password" x-model="form.password" :required="mode === 'create'"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                            <div class="flex gap-2 pt-2">
                                <button type="button" @click="close()" class="flex-1 border border-slate-300 text-slate-700 text-sm font-medium py-2 rounded-lg hover:bg-slate-50">Cancel</button>
                                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2 rounded-lg" x-text="mode === 'create' ? 'Create' : 'Save changes'"></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ===================== CATEGORIES & MENU TAB ===================== --}}
            <div x-show="tab === 'menu'" x-cloak x-data="menuManager()">
                <div class="mb-6">
                    <h2 class="font-display text-2xl font-bold text-slate-900">Categories & Menu</h2>
                    <p class="text-sm text-slate-500 mt-1">Manage categories, products, and extras.</p>
                </div>

                {{-- sub-tabs --}}
                <div class="flex gap-2 mb-6 border-b border-slate-200">
                    <button @click="sub = 'categories'" class="px-4 py-2 text-sm font-medium border-b-2 -mb-px"
                        :class="sub === 'categories' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'">Categories</button>
                    <button @click="sub = 'products'" class="px-4 py-2 text-sm font-medium border-b-2 -mb-px"
                        :class="sub === 'products' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'">Products</button>
                    <button @click="sub = 'extras'" class="px-4 py-2 text-sm font-medium border-b-2 -mb-px"
                        :class="sub === 'extras' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'">Extras</button>
                </div>

                {{-- CATEGORIES --}}
                <div x-show="sub === 'categories'" x-cloak>
                    <div class="flex justify-end mb-4">
                        <button @click="openCatCreate()" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg">+ Add Category</button>
                    </div>
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                    <th class="px-6 py-3">Name</th>
                                    <th class="px-6 py-3">Type</th>
                                    <th class="px-6 py-3">Products</th>
                                    <th class="px-6 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($categories as $category)
                                    <tr>
                                        <td class="px-6 py-3 font-medium text-slate-800">{{ $category->name }}</td>
                                        <td class="px-6 py-3 text-slate-500">{{ $category->type ?? '—' }}</td>
                                        <td class="px-6 py-3 text-slate-500">{{ $category->products_count }}</td>
                                        <td class="px-6 py-3 text-right space-x-2">
                                            <button @click="openCatEdit({{ Illuminate\Support\Js::from([
                                                'id' => $category->id,
                                                'name' => $category->name,
                                                'type' => $category->type,
                                                'discribation' => $category->discribation,
                                            ]) }})" class="text-indigo-600 hover:text-indigo-800 text-xs font-semibold">Edit</button>
                                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Delete {{ $category->name }}?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-rose-600 hover:text-rose-800 text-xs font-semibold">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-8 text-center text-slate-400">No categories yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div x-show="catModalOpen" x-cloak class="fixed inset-0 bg-slate-900/50 flex items-center justify-center z-50 p-4">
                        <div @click.outside="catModalOpen = false" class="bg-white rounded-2xl w-full max-w-md p-6 shadow-xl">
                            <h3 class="font-display text-lg font-bold text-slate-900 mb-4" x-text="catMode === 'create' ? 'Add Category' : 'Edit Category'"></h3>
                            <form :action="catMode === 'create' ? '{{ route('admin.categories.store') }}' : `/admin/categories/${catForm.id}`" method="POST" class="space-y-3">
                                @csrf
                                <template x-if="catMode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Name</label>
                                    <input type="text" name="name" x-model="catForm.name" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Type (e.g. food / drink)</label>
                                    <input type="text" name="type" x-model="catForm.type" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Description</label>
                                    <textarea name="discribation" x-model="catForm.discribation" rows="3" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                                </div>
                                <div class="flex gap-2 pt-2">
                                    <button type="button" @click="catModalOpen = false" class="flex-1 border border-slate-300 text-slate-700 text-sm font-medium py-2 rounded-lg hover:bg-slate-50">Cancel</button>
                                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2 rounded-lg" x-text="catMode === 'create' ? 'Create' : 'Save changes'"></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- PRODUCTS --}}
                <div x-show="sub === 'products'" x-cloak>
                    <div class="flex justify-end mb-4">
                        <button @click="openProdCreate()" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg">+ Add Product</button>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @forelse($products as $product)
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                                <div class="h-36 bg-slate-100 flex items-center justify-center">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-slate-300 text-sm">No image</span>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <div class="flex justify-between items-start">
                                        <p class="font-medium text-slate-800 text-sm">{{ $product->name }}</p>
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $product->is_available ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $product->is_available ? 'Available' : 'Hidden' }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $product->category->name ?? 'Uncategorized' }}</p>
                                    <p class="text-sm font-semibold text-indigo-600 mt-2">${{ number_format($product->price, 2) }}</p>
                                    <div class="flex gap-2 mt-3">
                                        <button @click="openProdEdit({{ Illuminate\Support\Js::from([
                                            'id' => $product->id,
                                            'name' => $product->name,
                                            'catagory_id' => $product->catagory_id,
                                            'discribtion' => $product->discribtion,
                                            'price' => $product->price,
                                            'is_available' => (bool) $product->is_available,
                                            'extras' => $product->extras->pluck('id'),
                                        ]) }})" class="text-indigo-600 hover:text-indigo-800 text-xs font-semibold">Edit</button>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete {{ $product->name }}?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 text-xs font-semibold">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="col-span-full text-center text-slate-400 py-10">No products yet.</p>
                        @endforelse
                    </div>

                    <div x-show="prodModalOpen" x-cloak class="fixed inset-0 bg-slate-900/50 flex items-center justify-center z-50 p-4 overflow-y-auto">
                        <div @click.outside="prodModalOpen = false" class="bg-white rounded-2xl w-full max-w-lg p-6 shadow-xl my-8">
                            <h3 class="font-display text-lg font-bold text-slate-900 mb-4" x-text="prodMode === 'create' ? 'Add Product' : 'Edit Product'"></h3>
                            <form :action="prodMode === 'create' ? '{{ route('admin.products.store') }}' : `/admin/products/${prodForm.id}`" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <template x-if="prodMode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>

                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Name</label>
                                    <input type="text" name="name" x-model="prodForm.name" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Category</label>
                                    <select name="catagory_id" x-model="prodForm.catagory_id" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="">Select category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Description</label>
                                    <textarea name="discribtion" x-model="prodForm.discribtion" rows="2" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Price</label>
                                    <input type="number" step="0.01" min="0" name="price" x-model="prodForm.price" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Image <span class="text-slate-400" x-show="prodMode === 'edit'">(leave blank to keep current)</span></label>
                                    <input type="file" name="image" accept="image/*" class="w-full text-sm">
                                </div>
                                @if($extras->count())
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Available Extras</label>
                                    <div class="grid grid-cols-2 gap-1 max-h-32 overflow-y-auto border border-slate-200 rounded-lg p-2">
                                        @foreach($extras as $extra)
                                            <label class="flex items-center gap-2 text-xs text-slate-600">
                                                <input type="checkbox" name="extras[]" value="{{ $extra->id }}"
                                                    :checked="prodForm.extras.includes({{ $extra->id }})"
                                                    class="rounded border-slate-300 text-indigo-600">
                                                {{ $extra->name }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                <label class="flex items-center gap-2 text-sm text-slate-600">
                                    <input type="checkbox" name="is_available" value="1" x-model="prodForm.is_available" class="rounded border-slate-300 text-indigo-600">
                                    Available for ordering
                                </label>

                                <div class="flex gap-2 pt-2">
                                    <button type="button" @click="prodModalOpen = false" class="flex-1 border border-slate-300 text-slate-700 text-sm font-medium py-2 rounded-lg hover:bg-slate-50">Cancel</button>
                                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2 rounded-lg" x-text="prodMode === 'create' ? 'Create' : 'Save changes'"></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- EXTRAS --}}
                <div x-show="sub === 'extras'" x-cloak>
                    <div class="flex justify-end mb-4">
                        <button @click="openExtraCreate()" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg">+ Add Extra</button>
                    </div>
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                    <th class="px-6 py-3">Name</th>
                                    <th class="px-6 py-3">Price</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($extras as $extra)
                                    <tr>
                                        <td class="px-6 py-3 font-medium text-slate-800">{{ $extra->name }}</td>
                                        <td class="px-6 py-3 text-slate-500">${{ number_format($extra->price, 2) }}</td>
                                        <td class="px-6 py-3">
                                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $extra->is_available ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                                {{ $extra->is_available ? 'Available' : 'Hidden' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3 text-right space-x-2">
                                            <button @click="openExtraEdit({{ Illuminate\Support\Js::from([
                                                'id' => $extra->id,
                                                'name' => $extra->name,
                                                'price' => $extra->price,
                                                'is_available' => (bool) $extra->is_available,
                                            ]) }})" class="text-indigo-600 hover:text-indigo-800 text-xs font-semibold">Edit</button>
                                            <form action="{{ route('admin.extras.destroy', $extra->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete {{ $extra->name }}?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-rose-600 hover:text-rose-800 text-xs font-semibold">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-8 text-center text-slate-400">No extras yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div x-show="extraModalOpen" x-cloak class="fixed inset-0 bg-slate-900/50 flex items-center justify-center z-50 p-4">
                        <div @click.outside="extraModalOpen = false" class="bg-white rounded-2xl w-full max-w-sm p-6 shadow-xl">
                            <h3 class="font-display text-lg font-bold text-slate-900 mb-4" x-text="extraMode === 'create' ? 'Add Extra' : 'Edit Extra'"></h3>
                            <form :action="extraMode === 'create' ? '{{ route('admin.extras.store') }}' : `/admin/extras/${extraForm.id}`" method="POST" class="space-y-3">
                                @csrf
                                <template x-if="extraMode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Name</label>
                                    <input type="text" name="name" x-model="extraForm.name" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Price</label>
                                    <input type="number" step="0.01" min="0" name="price" x-model="extraForm.price" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <label class="flex items-center gap-2 text-sm text-slate-600">
                                    <input type="checkbox" name="is_available" value="1" x-model="extraForm.is_available" class="rounded border-slate-300 text-indigo-600">
                                    Available
                                </label>
                                <div class="flex gap-2 pt-2">
                                    <button type="button" @click="extraModalOpen = false" class="flex-1 border border-slate-300 text-slate-700 text-sm font-medium py-2 rounded-lg hover:bg-slate-50">Cancel</button>
                                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2 rounded-lg" x-text="extraMode === 'create' ? 'Create' : 'Save changes'"></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================== ORDERS TAB ===================== --}}
            <div x-show="tab === 'orders'" x-cloak>
                <div class="mb-8">
                    <h2 class="font-display text-2xl font-bold text-slate-900">Orders</h2>
                    <p class="text-sm text-slate-500 mt-1">Monitor and manage all orders.</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-10 text-center">
                    <p class="text-sm text-slate-400">Orders overview goes here — coming next.</p>
                </div>
            </div>

        </main>
    </div>

    <script>
        function staffManager() {
            return {
                modalOpen: false,
                mode: 'create',
                form: { id: null, fullname: '', phone: '', email: '', username: '', role: 'staff', password: '' },
                openCreate() {
                    this.mode = 'create';
                    this.form = { id: null, fullname: '', phone: '', email: '', username: '', role: 'staff', password: '' };
                    this.modalOpen = true;
                },
                openEdit(user) {
                    this.mode = 'edit';
                    this.form = { ...user, password: '' };
                    this.modalOpen = true;
                },
                close() { this.modalOpen = false; }
            }
        }

        function menuManager() {
            return {
                sub: 'categories',

                catModalOpen: false, catMode: 'create',
                catForm: { id: null, name: '', type: '', description: '' },
                openCatCreate() { this.catMode = 'create'; this.catForm = { id: null, name: '', type: '', description: '' }; this.catModalOpen = true; },
                openCatEdit(cat) { this.catMode = 'edit'; this.catForm = { ...cat }; this.catModalOpen = true; },

                prodModalOpen: false, prodMode: 'create',
                prodForm: { id: null, name: '', category_id: '', description: '', price: '', is_available: true, extras: [] },
                openProdCreate() { this.prodMode = 'create'; this.prodForm = { id: null, name: '', catagory_id: '', description: '', price: '', is_available: true, extras: [] }; this.prodModalOpen = true; },
                openProdEdit(product) { this.prodMode = 'edit'; this.prodForm = { ...product }; this.prodModalOpen = true; },

                extraModalOpen: false, extraMode: 'create',
                extraForm: { id: null, name: '', price: '', is_available: true },
                openExtraCreate() { this.extraMode = 'create'; this.extraForm = { id: null, name: '', price: '', is_available: true }; this.extraModalOpen = true; },
                openExtraEdit(extra) { this.extraMode = 'edit'; this.extraForm = { ...extra }; this.extraModalOpen = true; },
            }
        }
    </script>

</body>
</html>