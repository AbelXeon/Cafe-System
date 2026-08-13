<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Join FoodieDash</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-orange-500 via-red-500 to-pink-600 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white/95 backdrop-blur-sm p-10 rounded-3xl shadow-2xl w-full max-w-md border border-white/20">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-red-600">FoodieDash</h1>
            <p class="text-gray-500 mt-2">Join the taste revolution</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <input type="text" name="fullname" placeholder="Full Name" value="{{ old('fullname') }}" class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition outline-none">
                @error('fullname') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="phone" placeholder="Phone" value="{{ old('phone') }}" class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-orange-500 transition outline-none">
                <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-orange-500 transition outline-none">
            </div>

            <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-orange-500 transition outline-none">
            
            <div class="space-y-2">
                <input type="password" name="password" placeholder="Create Password" class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-orange-500 transition outline-none">
                <input type="password" name="password_confirmation" placeholder="Confirm Password" class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-orange-500 transition outline-none">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-red-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-orange-200 hover:scale-[1.02] active:scale-[0.98] transition duration-200 uppercase tracking-wider">
                Create Account
            </button>
        </form>

        <div class="mt-8 text-center text-gray-600">
            <span>Already a member?</span>
            <a href="{{ route('login') }}" class="text-orange-600 font-bold hover:underline ml-1">Login Here</a>
        </div>
    </div>
</body>
</html>