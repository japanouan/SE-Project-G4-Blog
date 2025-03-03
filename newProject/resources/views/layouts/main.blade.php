<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ThaiWijit')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <header class="bg-indigo-300 p-4 flex items-center justify-between shadow-md">
    <a href="{{ route('outfits.index') }}" class="text-white text-2xl font-bold hover:underline">ThaiWijit</a>


        <!-- Menu -->
        <nav class="flex space-x-6 text-white">
            <a href="#" class="hover:underline">Product</a>
            <a href="#" class="hover:underline">Outfit Set</a>
            <a href="#" class="hover:underline">Jewelry</a>
        </nav>

       <!-- ✅ แก้ไข Form ค้นหา -->
       <form action="{{ route('outfits.search') }}" method="GET">
            <input type="text" name="searchkey" placeholder="ค้นหาชุดไทย..." class="border px-4 py-2 rounded-lg">
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg">ค้นหา</button>
        </form>

        <!-- Icons -->
        <div class="flex items-center space-x-4 text-white">
            <a href="{{ route('cartItem.allItem') }}" class="relative">
                <i class="fa fa-shopping-cart text-xl"></i>
            </a>
            @guest
                <a href="{{ route('login') }}" class="hover:underline">Login</a>
                <a href="{{ route('register') }}" class="hover:underline">Register</a>
            @else
                <a href="{{ route('profile.index') }}" class="hover:underline">Profile</a>
                <a href="{{ route('logout') }}" class="hover:underline"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            @endguest
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto p-6">
        @yield('content')
    </div>

</body>
</html>
