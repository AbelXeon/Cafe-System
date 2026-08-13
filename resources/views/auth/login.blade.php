<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl font-bold mb-4">Login</h2>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <input type="text" name="username" placeholder="Username" class="w-full border p-2 mb-2 rounded" required>
            <input type="password" name="password" placeholder="Password" class="w-full border p-2 mb-4 rounded" required>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Login</button>
        </form>
        <p class="mt-4 text-sm text-center">Don't have an account? <a href="/register" class="text-blue-500">Register</a></p>
    </div>
</body>
</html>