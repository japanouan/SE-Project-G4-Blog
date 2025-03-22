<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Register Staff - ThaiWijit</title>
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
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body class="flex items-center justify-center h-screen bg-gray-400">

    <div class="text-center">
        <!-- โลโก้ -->
        <h1 class="logo-text">ThaiWijit</h1>

        <!-- กล่องฟอร์มลงทะเบียนพนักงาน -->
        <div class="bg-white p-8 rounded-lg shadow-lg w-96 text-left">
            <form method="POST" action="{{ route('register.staff') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-semibold text-gray-700">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                    @error('name')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                    @error('email')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Username -->
                <div class="mb-4">
                    <label for="username" class="block text-sm font-semibold text-gray-700">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                    @error('username')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-semibold text-gray-700">Phone</label>
                    <input id="phone" type="number" name="phone" value="{{ old('phone') }}" required
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                    @error('phone')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Staff Role -->
                <div class="mb-4">
                    <label for="userType" class="block text-sm font-semibold text-gray-700">Staff Role</label>
                    <select id="userType" name="userType" required
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="make-up artist" {{ old('userType') == 'make-up artist' ? 'selected' : '' }}>Make-Up Artist</option>
                        <option value="photographer" {{ old('userType') == 'photographer' ? 'selected' : '' }}>Photographer</option>
                        <option value="admin" {{ old('userType') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="shop owner" {{ old('userType') == 'shop owner' ? 'selected' : '' }}>Shop Owner</option>
                    </select>
                    @error('userType')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                    <input id="password" type="password" name="password" required
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                    @error('password')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full bg-black text-white py-2 rounded-md font-semibold hover:bg-gray-800 transition">
                    Register
                </button>
            </form>

            <!-- Login Link -->
            <p class="mt-4 text-sm text-gray-600 text-center">
                Already have an account?
                <a href="{{ route('login') }}" class="text-indigo-500 hover:underline">Login</a>
            </p>
        </div>
    </div>

</body>
</html>
