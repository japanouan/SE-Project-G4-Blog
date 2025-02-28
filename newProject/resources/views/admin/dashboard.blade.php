<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - ThaiWijit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Jomhuria&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
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
            width: 100%; /* Full width of screen */
            height: 71px;
            background-color: #000;
            display: flex;
            align-items: center;
            justify-content: space-between; /* Space between logo and user info */
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
            height: 953px;
            background-color: #292828;
            position: fixed;
            top: 71px;
            left: 0;
            color: white;
        }
        .content {
            margin-left: 259px;
            margin-top: 71px;
            padding: 20px;
            flex: 1;
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
    </style>
</head>
<body>
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
            <span class="text-white font-medium">{{ Auth::user()->name ?? 'Admin' }}</span>
            <i class="fas fa-chevron-down text-white ml-2"></i>
            
            <!-- Dropdown menu -->
            <div class="dropdown-menu" id="userDropdown">
                <a href="{{ route('profile.edit') }}">Profile</a>
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
            <div class="menu-item" id="dashboardMenuItem">
                <a href="#" data-target="dashboard-home">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="menu-item" id="usersMenuItem">
                <a href="#" data-target="users-content" data-url="{{ route('admin.users.index') }}">
                    <i class="fas fa-users mr-3"></i>
                    <span>Users</span>
                </a>
            </div>
            <div class="menu-item" id="shopsMenuItem">
                <a href="#" data-target="shops-content" data-url="{{ route('admin.shops.index') }}">
                    <i class="fas fa-store mr-3"></i>
                    <span>Shops</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Content -->
    <div class="content">
        <div class="max-w-7xl mx-auto">
            <!-- Dashboard Home Content -->
            <div id="dashboard-home" class="content-section dashboard-home">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>
                        <p class="mb-4">{{ __("You're logged in!") }}</p>
                        
                        <!-- Dashboard content goes here -->
                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div class="bg-gray-100 p-4 rounded-lg">
                                <h2 class="text-lg font-semibold mb-2">User Management</h2>
                                <p>Manage all users in the system</p>
                                <button class="load-content mt-2 inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" 
                                        data-target="users-content" 
                                        data-url="{{ route('admin.users.index') }}">
                                    View Users
                                </button>
                            </div>
                            <div class="bg-gray-100 p-4 rounded-lg">
                                <h2 class="text-lg font-semibold mb-2">Shop Management</h2>
                                <p>Manage all shops in the platform</p>
                                <button class="load-content mt-2 inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" 
                                        data-target="shops-content" 
                                        data-url="{{ route('admin.shops.index') }}">
                                    View Shops
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Users Content Section (initially hidden) -->
            <div id="users-content" class="content-section hidden"></div>
            
            <!-- Shops Content Section (initially hidden) -->
            <div id="shops-content" class="content-section hidden"></div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Toggle sidebar visibility
            $('#menuToggle').click(function() {
                $('#sidebar').toggle();
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
            
            // Handle sidebar menu item clicks
            $('.sidebar .menu-item a').click(function(e) {
                e.preventDefault();
                
                const target = $(this).data('target');
                const url = $(this).data('url');
                
                // If this is dashboard, just show dashboard content
                if (target === 'dashboard-home') {
                    $('.content-section').hide();
                    $('#dashboard-home').show();
                    $('.menu-item').removeClass('active');
                    $(this).closest('.menu-item').addClass('active');
                    return;
                }
                
                // Load content from URL if AJAX is implemented
                if (url) {
                    // If you have AJAX content loading
                    loadContent(url, target);
                }
            });
            
            // Function to load content if you're using AJAX
            function loadContent(url, targetId) {
                // Show loading indicator if you have one
                
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'html',
                    success: function(response) {
                        // Hide all content sections
                        $('.content-section').hide();
                        
                        // Show target section and fill with response
                        $('#' + targetId).html(response).show();
                        
                        // Remove active class from all menu items
                        $('.menu-item').removeClass('active');
                        
                        // Add active class to clicked menu item
                        $('[data-target="' + targetId + '"]').closest('.menu-item').addClass('active');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading content:', error);
                        alert('Error loading content. Please try again.');
                    }
                });
            }
            
            // Set dashboard as active by default
            $('#dashboardMenuItem').addClass('active');
        });
    </script>
</body>
</html>