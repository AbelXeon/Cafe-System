<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl font-bold mb-4">Create Account</h2>
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <input type="text" name="fullname" placeholder="Full Name" class="w-full border p-2 mb-2 rounded" required>
            <input type="text" name="phone" placeholder="Phone Number" class="w-full border p-2 mb-2 rounded" required>
            <input type="email" name="email" placeholder="Email" class="w-full border p-2 mb-2 rounded" required>
            <input type="text" name="username" placeholder="Username" class="w-full border p-2 mb-2 rounded" required>
            <input type="password" name="password" placeholder="Password" class="w-full border p-2 mb-2 rounded" required>
            <input type="password" name="password_confirmation" placeholder="Confirm Password" class="w-full border p-2 mb-4 rounded" required>
            <button type="submit" class="w-full bg-orange-500 text-white p-2 rounded hover:bg-orange-600">Register</button>
        </form>
        <p class="mt-4 text-sm text-center">Already have an account? <a href="/login" class="text-blue-500">Login</a></p>
    </div>
</body>
</html>