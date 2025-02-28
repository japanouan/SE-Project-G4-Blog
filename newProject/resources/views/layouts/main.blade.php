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
        <div class="text-white text-2xl font-bold">ThaiWijit</div>

        <!-- Menu -->
        <nav class="flex space-x-6 text-white">
            <a href="#" class="hover:underline">Product</a>
            <a href="#" class="hover:underline">Outfit Set</a>
            <a href="#" class="hover:underline">Jewelry</a>
        </nav>

        <!-- Search Bar -->
        <div class="relative w-1/3">
            <input type="text" class="w-full p-2 pl-10 rounded-full border border-gray-300 text-black" placeholder="search">
            <i class="fa fa-search absolute left-3 top-2.5 text-gray-400"></i>
        </div>

        <!-- Icons -->
        <div class="flex items-center space-x-4 text-white">
            <i class="fa fa-shopping-cart text-xl"></i>
            @guest
                <a href="{{ route('login') }}" class="hover:underline">Login</a>
                <a href="{{ route('register') }}" class="hover:underline">Register</a>
            @else
                <a href="#" class="hover:underline">Profile</a>
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
