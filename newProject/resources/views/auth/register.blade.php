<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ThaiWijit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Jomhuria&display=swap">
    <style>
        .logo-text {
            color: #FFFAFA;
            font-family: 'Jomhuria', sans-serif;
            font-size: 128px;
            font-style: normal;
            font-weight: 400;
            line-height: normal;
            margin-bottom: 0.5rem; /* Reduce spacing */
        }
    </style>
</head>
<body class="flex items-center justify-center h-screen bg-[#8B9DF9]">

    <div class="text-center">
        <!-- โลโก้ - Updated with reduced spacing -->
        <h1 class="logo-text">ThaiWijit</h1>

        <!-- กล่องลงทะเบียน -->
        <div class="bg-white p-8 rounded-lg shadow-lg w-96">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-semibold text-gray-700">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400" required autofocus>
                    @error('name')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
                    @error('email')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Username -->
                <div class="mb-4">
                    <label for="username" class="block text-sm font-semibold text-gray-700">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
                    @error('username')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-semibold text-gray-700">Phone</label>
                    <input id="phone" type="number" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
                    @error('phone')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                    <input id="password" type="password" name="password" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
                    @error('password')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
                </div>

                <!-- ปุ่ม Register -->
                <button type="submit" class="w-full bg-black text-white py-2 rounded-md font-semibold hover:bg-gray-800 transition">
                    Register
                </button>
            </form>

            <!-- ลิงก์ Login -->
            <p class="mt-4 text-sm text-gray-600">
                Already have an account? <a href="{{ route('login') }}" class="text-indigo-500 hover:underline">Login</a>
            </p>
        </div>
    </div>

</body>
</html>
