{{-- resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Cafe System</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
</head>
<body class="bg-slate-50 font-body text-slate-800">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col fixed h-full">
            <div class="px-6 py-5 border-b border-slate-800">
                <h1 class="font-display text-xl font-bold text-white">☕ CafeAdmin</h1>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                          {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                          {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 100-8 4 4 0 000 8zm6 3v-1a4 4 0 00-3-3.87" />
                    </svg>
                    Staff & Delivery
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium hover:bg-slate-800 text-slate-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Categories & Menu
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium hover:bg-slate-800 text-slate-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Orders
                </a>
            </nav>
            <div class="px-4 py-4 border-t border-slate-800">
                <div class="flex items-center gap-3 px-2">
                    <div class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold text-sm">
                        {{ strtoupper(substr(auth()->user()->fullname, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->fullname }}</p>
                        <p class="text-xs text-slate-500">Administrator</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main content --}}
        <main class="flex-1 ml-64 p-8">

            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="font-display text-2xl font-bold text-slate-900">Dashboard Overview</h2>
                    <p class="text-sm text-slate-500 mt-1">Welcome back, {{ auth()->user()->fullname }}. Here's what's happening today.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 px-4 py-3 rounded-lg bg-emerald-50 text-emerald-700 text-sm font-medium border border-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Stats grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">

                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">Total Orders</p>
                        <span class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </span>
                    </div>
                    <p class="font-display text-3xl font-bold text-slate-900 mt-3">{{ $stats['total_orders'] }}</p>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">Pending Orders</p>
                        <span class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </div>
                    <p class="font-display text-3xl font-bold text-slate-900 mt-3">{{ $stats['pending_orders'] }}</p>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">Menu Items</p>
                        <span class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </span>
                    </div>
                    <p class="font-display text-3xl font-bold text-slate-900 mt-3">{{ $stats['total_products'] }}</p>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">Staff Members</p>
                        <span class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </span>
                    </div>
                    <p class="font-display text-3xl font-bold text-slate-900 mt-3">{{ $stats['total_staff'] }}</p>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">Delivery Riders</p>
                        <span class="w-9 h-9 rounded-lg bg-rose-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m6 0a2 2 0 104 0" />
                            </svg>
                        </span>
                    </div>
                    <p class="font-display text-3xl font-bold text-slate-900 mt-3">{{ $stats['total_delivery'] }}</p>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">Customers</p>
                        <span class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 100-8 4 4 0 000 8zm6 3v-1a4 4 0 00-3-3.87" />
                            </svg>
                        </span>
                    </div>
                    <p class="font-display text-3xl font-bold text-slate-900 mt-3">{{ $stats['total_customers'] }}</p>
                </div>
            </div>

            {{-- Two-column: recent orders + recent users --}}
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
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        @empty
                            <p class="px-6 py-6 text-sm text-slate-400 text-center">No orders yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                        <h3 class="font-display font-semibold text-slate-900">Recently Added Users</h3>
                        <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">View all →</a>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($recentUsers as $user)
                            <div class="px-6 py-3 flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-800">{{ $user->fullname }}</p>
                                    <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                </div>
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">
                                    {{ ucfirst($user->role->name) }}
                                </span>
                            </div>
                        @empty
                            <p class="px-6 py-6 text-sm text-slate-400 text-center">No users yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </main>
    </div>

</body>
</html>