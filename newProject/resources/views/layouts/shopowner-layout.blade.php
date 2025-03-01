<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ThaiWijit - ร้านค้า')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome (For Icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-gray-100">
      <!-- Sidebar -->
      <div class="flex">
          <div class="w-64 bg-gray-900 min-h-screen text-white">
              <div class="p-5 text-lg font-bold">ThaiWijit - ร้านค้า</div>
              <nav class="mt-5">
                  <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-300 hover:bg-gray-700">
                      <i class="fa fa-home"></i> หน้าหลัก
                  </a>
                  <a href="{{ route('shopowner.shops.my-shop') }}" class="block px-4 py-2 text-gray-300 hover:bg-gray-700">
                      <i class="fa fa-store"></i> ร้านค้าของฉัน
                  </a>
                
                  @php
                      // Check if shop owner has registered any shop (regardless of status)
                      $hasRegisteredShop = \App\Models\Shop::where('shop_owner_id', Auth::id())->exists();
                      // Check for active shop (for other menu items)
                      $userShop = \App\Models\Shop::where('shop_owner_id', Auth::id())->where('status', 'active')->first();
                  @endphp
                
                  @if(!$hasRegisteredShop)
                      <a href="{{ route('shopowner.shops.create') }}" class="block px-4 py-2 text-gray-300 hover:bg-gray-700">
                          <i class="fa fa-plus-circle"></i> ลงทะเบียนร้านค้า
                      </a>
                  @endif
                
                  @if($userShop)
                      <a href="{{ route('shopowner.outfits.index') }}" class="block px-4 py-2 text-gray-300 hover:bg-gray-700">
                          <i class="fa fa-tshirt"></i> จัดการชุด
                      </a>
                      <a href="{{ route('shopowner.categories.index') }}" class="block px-4 py-2 text-gray-300 hover:bg-gray-700">
                          <i class="fa fa-tags"></i> จัดการหมวดหมู่
                      </a>
                  @else
                      <span class="block px-4 py-2 text-gray-500">
                          <i class="fa fa-tshirt"></i> จัดการชุด (ต้องมีร้านค้าก่อน)
                      </span>
                      <span class="block px-4 py-2 text-gray-500">
                          <i class="fa fa-tags"></i> จัดการหมวดหมู่ (ต้องมีร้านค้าก่อน)
                      </span>
                  @endif
                
                  <a href="#" class="block px-4 py-2 text-gray-300 hover:bg-gray-700">
                      <i class="fa fa-calendar"></i> การจอง
                  </a>
                  <a href="#" class="block px-4 py-2 text-gray-300 hover:bg-gray-700">
                      <i class="fa fa-comment"></i> รีวิว
                  </a>
                  <a href="#" class="block px-4 py-2 text-gray-300 hover:bg-gray-700">
                      <i class="fa fa-chart-bar"></i> สถิติ
                  </a>
                  <a href="#" class="block px-4 py-2 text-gray-300 hover:bg-gray-700">
                      <i class="fa fa-cog"></i> ตั้งค่า
                  </a>
                  <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <button type="submit" class="w-full text-left block px-4 py-2 text-gray-300 hover:bg-gray-700">
                          <i class="fa fa-sign-out-alt"></i> ออกจากระบบ
                      </button>
                  </form>
              </nav>
          </div>
        <!-- Main Content -->
        <div class="flex-1">
            <!-- Navbar -->
            <nav class="bg-black text-white flex justify-between px-6 py-3">
                <div>
                    <button class="text-white text-2xl"><i class="fa fa-bars"></i></button>
                </div>
                <div>
                    <span class="mr-2">{{ Auth::user()->name }}</span>
                    <i class="fa fa-user-circle text-xl"></i>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="p-6">
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>

</body>
</html>
