<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ThaiWijit</title>
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
<body class="flex items-center justify-center h-screen bg-[#8B9DF9]">

    <div class="text-center">
        <!-- โลโก้ - Updated styling -->
        <h1 class="logo-text mb-6">ThaiWijit</h1>

        <!-- กล่องล็อกอิน -->
        <div class="bg-white p-8 rounded-lg shadow-lg w-80">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Username -->
                <div class="mb-4">
                    <label for="username" class="block text-sm font-semibold text-gray-700">Username</label>
                    <input id="username" type="text" name="username" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400" required autofocus>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                    <input id="password" type="password" name="password" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
                </div>

                <!-- ปุ่ม Login -->
                <button type="submit" class="w-full bg-black text-white py-2 rounded-md font-semibold hover:bg-gray-800 transition">
                    Login
                </button>
            </form>

            <!-- ลิงก์สมัครสมาชิก -->
            <p class="mt-4 text-sm text-gray-600">
                <a href="{{ route('register') }}" class="text-indigo-500 hover:underline">Sign Up</a>
            </p>
        </div>
    </div>

</body>
</html>