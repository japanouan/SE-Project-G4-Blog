<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ThaiWijit - Staff Portal')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome (For Icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts - Jomhuria -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Jomhuria&display=swap">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .header {
            width: 100%;
            height: 71px;
            background-color: #000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            position: fixed;
            top: 0;
            z-index: 100;
        }
        .logo {
            color: #FFFAFA;
            font-family: 'Jomhuria', sans-serif;
            font-size: 64px;
            font-style: normal;
            font-weight: 400;
            line-height: normal;
        }
        .sidebar {
            width: 259px;
            height: 100vh;
            background-color: #292828;
            position: fixed;
            top: 71px;
            left: 0;
            color: white;
            transition: transform 0.3s ease-in-out;
            z-index: 90;
            overflow-y: auto;
        }
        .sidebar-collapsed {
            transform: translateX(-100%);
        }
        .content {
            margin-left: 259px;
            margin-top: 71px;
            padding: 20px;
            flex: 1;
            transition: margin-left 0.3s ease-in-out;
        }
        .content-expanded {
            margin-left: 0;
        }
        .menu-item {
            width: 259px;
            height: 65px;
            display: flex;
            align-items: center;
            padding: 0 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .menu-item:hover, .menu-item.active {
            background-color: #8B9DF9;
        }
        .menu-item a {
            color: white;
            text-decoration: none;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
        }
        .user-profile {
            display: flex;
            align-items: center;
            color: white;
            cursor: pointer;
            position: relative;
        }
        .dropdown-menu {
            position: absolute;
            top: 60px;
            right: 0;
            background-color: white;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: none;
            width: 150px;
            z-index: 101;
        }
        .dropdown-menu a {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
        }
        .dropdown-menu a:hover {
            background-color: #f5f5f5;
        }
        
        /* Button Styles */
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            text-align: center;
            transition: all 0.2s;
            display: inline-block;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #8B9DF9;
            color: white;
            border: none;
        }
        .btn-primary:hover {
            background-color: #7A8CE8;
            transform: translateY(-1px);
        }
        .btn-success {
            background-color: #10B981;
            color: white;
            border: none;
        }
        .btn-success:hover {
            background-color: #059669;
            transform: translateY(-1px);
        }
        .btn-danger {
            background-color: #EF4444;
            color: white;
            border: none;
        }
        .btn-danger:hover {
            background-color: #DC2626;
            transform: translateY(-1px);
        }
        .btn-info {
            background-color: #3B82F6;
            color: white;
            border: none;
        }
        .btn-info:hover {
            background-color: #2563EB;
            transform: translateY(-1px);
        }
        
        /* Loading indicator */
        .loading {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 1000;
        }
        
        /* Additional styles for work-related pages */
        .card {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-bottom: 2rem;
            overflow: hidden;
        }
        .card-header {
            padding: 1.25rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-body {
            padding: 1.5rem;
        }
    </style>

</head>
<body>
    <!-- Loading indicator -->
    <div class="loading" id="loadingIndicator">
        <i class="fas fa-spinner fa-spin fa-2x"></i>
        <span class="ml-2">Loading...</span>
    </div>

    <!-- Header -->
    <div class="header">
        <div class="flex items-center">
            <div class="logo">ThaiWijit</div>
            <div class="ml-4 cursor-pointer" id="menuToggle">
                <i class="fas fa-bars text-white text-2xl"></i>
            </div>
        </div>
        
        <!-- User profile on right side -->
        <div class="user-profile" id="userProfile">
            <i class="fas fa-user-circle text-white text-4xl mr-3"></i>
            <span class="text-white font-medium">{{ Auth::user()->name }}</span>
            <i class="fas fa-chevron-down text-white ml-2"></i>
            
            <!-- Dropdown menu -->
            <div class="dropdown-menu" id="userDropdown">
                <a href="{{ route('profile.show') }}">Profile</a>
                <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                        Logout
                    </a>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="py-6">
            @php
                // Get current route name
                $currentRouteName = Route::currentRouteName();
                $userType = str_replace(' ', '', Auth::user()->userType);
            @endphp
            
            <!-- Removed the "หน้าหลัก" menu item -->
            
            <div class="menu-item {{ Str::contains($currentRouteName, '.dashboard') ? 'active' : '' }}">
                <a href="{{ route($userType . '.dashboard') }}">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>แดชบอร์ด</span>
                </a>
            </div>
            
            <div class="menu-item {{ Str::contains($currentRouteName, '.work-list') ? 'active' : '' }}">
                <a href="{{ route($userType . '.work-list') }}">
                    <i class="fas fa-clipboard-list mr-3"></i>
                    <span>รายการงานที่เปิดรับ</span>
                </a>
            </div>
            
            <div class="menu-item {{ Str::contains($currentRouteName, '.dashboard') ? 'active' : '' }}">
                <a href="{{ route($userType . '.dashboard') }}">
                    <i class="fas fa-calendar-alt mr-3"></i>
                    <span>ตารางงานของคุณ</span>
                </a>
            </div>
            
            <div class="menu-item {{ Str::contains($currentRouteName, '.work.earning') ? 'active' : '' }}">
                <a href="{{ route($userType . '.work.earning') }}">
                    <i class="fas fa-money-bill-wave mr-3"></i>
                    <span>รายได้</span>
                </a>
            </div>
            
            <div class="menu-item {{ $currentRouteName == 'issue.show' ? 'active' : '' }}">
                <a href="{{ route('issue.show') }}">
                    <i class="fas fa-exclamation-circle mr-3"></i>
                    <span>รายงานปัญหา</span>
                </a>
            </div>
        </div>
    </div>
        <!-- Content -->
    <div class="content" id="mainContent">
        <!-- Page Content -->
        <div class="max-w-7xl mx-auto">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            
            @yield('content')
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Toggle sidebar visibility with animation
            $('#menuToggle').click(function() {
                $('#sidebar').toggleClass('sidebar-collapsed');
                $('#mainContent').toggleClass('content-expanded');
                
                // Change menu icon based on sidebar state
                if ($('#sidebar').hasClass('sidebar-collapsed')) {
                    $('#menuToggle i').removeClass('fa-bars').addClass('fa-bars-staggered');
                } else {
                    $('#menuToggle i').removeClass('fa-bars-staggered').addClass('fa-bars');
                }
            });
            
            // Toggle user dropdown
            $('#userProfile').click(function(e) {
                e.stopPropagation();
                $('#userDropdown').toggle();
            });
            
            // Close dropdown when clicking elsewhere
            $(document).click(function() {
                $('#userDropdown').hide();
            });
            
            // Prevent dropdown from closing when clicking inside it
            $('#userDropdown').click(function(e) {
                e.stopPropagation();
            });
            
            // Show loading indicator when navigating
            $('a:not([href^="#"]):not([target="_blank"]):not([onclick])').click(function() {
                if ($(this).attr('href')) {
                    $('#loadingIndicator').show();
                }
            });
            
            // Show loading indicator when submitting forms
            $('form:not(#logoutForm)').submit(function() {
                $('#loadingIndicator').show();
            });
            
            // Hide loading indicator when page is fully loaded
            $(window).on('load', function() {
                $('#loadingIndicator').hide();
            });
            
            @yield('scripts')
        });
    </script>
</body>
</html>
