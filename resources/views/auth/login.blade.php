<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | FoodieDash</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white rounded-[2rem] shadow-2xl overflow-hidden flex w-full max-w-4xl h-[600px]">
        <!-- Left Side: Image/Branding -->
        <div class="hidden md:flex w-1/2 bg-gradient-to-br from-orange-400 to-red-600 items-center justify-center p-12 text-white relative">
            <div class="z-10">
                <h2 class="text-5xl font-black mb-4">Hungry?</h2>
                <p class="text-lg opacity-90">Login and get your favorite meals delivered in minutes.</p>
            </div>
            <div class="absolute inset-0 bg-black opacity-10"></div>
        </div>

        <!-- Right Side: Form -->
        <div class="w-full md:w-1/2 p-12 flex flex-col justify-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Welcome Back!</h2>
            <p class="text-gray-500 mb-8">Please enter your details.</p>

            <!-- Success Alert -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Alerts (Including Lockout Message) -->
            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm font-bold">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition" required>
                </div>

                <button type="submit" class="w-full bg-orange-600 text-white font-bold py-4 rounded-xl hover:bg-orange-700 shadow-lg transition duration-300">
                    Sign In
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-gray-500">
                New here? <a href="{{ route('register') }}" class="text-orange-600 font-bold hover:underline">Create an account</a>
            </p>
        </div>
    </div>
</body>
</html>