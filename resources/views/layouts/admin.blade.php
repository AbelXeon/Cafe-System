<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex">
    <!-- Sidebar -->
    <div class="w-64 bg-slate-900 min-h-screen text-white p-6">
        <h1 class="text-2xl font-black text-orange-500 mb-10">ADMIN PANEL</h1>
        <nav class="space-y-4">
            <a href="{{ route('admin.dashboard') }}" class="block p-3 hover:bg-slate-800 rounded-lg"><i class="fa fa-home mr-3"></i> Dashboard</a>
            <a href="{{ route('admin.users') }}" class="block p-3 hover:bg-slate-800 rounded-lg"><i class="fa fa-users mr-3"></i> Staff & Delivery</a>
            <a href="{{ route('admin.products') }}" class="block p-3 hover:bg-slate-800 rounded-lg"><i class="fa fa-hamburger mr-3"></i> Products & Menu</a>
            <form action="{{ route('logout') }}" method="POST" class="pt-10">
                @csrf
                <button class="text-red-400 p-3 w-full text-left hover:bg-red-900/20 rounded-lg">Logout</button>
            </form>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-10">
        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded-lg mb-6 shadow-lg">{{ session('success') }}</div>
        @endif
        @yield('content')
    </div>
</body>
</html>