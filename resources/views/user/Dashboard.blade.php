<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow p-4 flex justify-between">
        <h1 class="text-xl font-bold">FoodieDash</h1>
        <div class="flex items-center gap-4">
            <span>Welcome, <strong>{{ Auth::user()->fullname }}</strong></span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-red-500 text-sm">Logout</button>
            </form>
        </div>
    </nav>

    <div class="p-8">
        <h2 class="text-3xl font-semibold mb-6">Main Menu</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Order Food Section -->
            <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                <h3 class="font-bold text-lg">Order Food</h3>
                <p class="text-gray-600 mb-4">Browse our menu and pick your favorite meals.</p>
                <a href="#" class="bg-orange-500 text-white px-4 py-2 rounded">Go to Menu</a>
            </div>

            <!-- Saved Locations Section -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-bold text-lg">My Locations</h3>
                <p class="text-gray-600 mb-4">Manage your 3 saved delivery places.</p>
                <a href="#" class="border border-gray-300 px-4 py-2 rounded">Manage</a>
            </div>

            <!-- Active Orders Section -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-bold text-lg">Order Status</h3>
                <p class="text-gray-600 mb-4">See where your food is right now.</p>
                <span class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded">No active orders</span>
            </div>
        </div>
    </div>
</body>
</html>