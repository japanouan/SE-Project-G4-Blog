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
            font-family: 'Inter', sans-serif;
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
            background-color: #f3f4f6;
            min-height: calc(100vh - 71px);
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

        /* Card Styles */
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
        .card-header-icon {
            font-size: 1.25rem;
            color: #8B9DF9;
            margin-right: 0.75rem;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
        }
        .card-body {
            padding: 1.5rem;
        }

        /* Badge Styles */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .badge-status-active {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-status-inactive {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .badge-role-admin {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-role-customer {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .badge-role-shopowner {
            background-color: #e0e7ff;
            color: #4338ca;
        }
        .badge-role-makeup {
            background-color: #fbcfe8;
            color: #9d174d;
        }
        .badge-role-photographer {
            background-color: #d1fae5;
            color: #065f46;
        }

        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            font-weight: 500;
            font-size: 0.875rem;
            border-radius: 0.375rem;
            transition: all 0.2s;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #8B9DF9;
            color: white;
        }
        .btn-primary:hover {
            background-color: #7a8ce8;
            transform: translateY(-1px);
        }
        .btn-success {
            background-color: #10b981;
            color: white;
        }
        .btn-success:hover {
            background-color: #059669;
        }
        .btn-danger {
            background-color: #ef4444;
            color: white;
        }
        .btn-danger:hover {
            background-color: #dc2626;
        }
        .btn-info {
            background-color: #3b82f6;
            color: white;
        }
        .btn-info:hover {
            background-color: #2563eb;
        }
        .btn-outline {
            background-color: transparent;
            border: 1px solid #d1d5db;
            color: #4b5563;
        }
        .btn-outline:hover {
            background-color: #f3f4f6;
        }
        .btn i {
            margin-right: 0.375rem;
        }

        /* Table Styles */
        .table-container {
            overflow-x: auto;
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 1000px;
        }
        thead th {
            background-color: #f9fafb;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            padding: 0.75rem 1rem;
            text-align: left;
            white-space: nowrap;
        }
        tbody tr {
            transition: background-color 0.2s;
        }
        tbody tr:hover {
            background-color: #f9fafb;
        }
        tbody td {
            padding: 1rem;
            border-top: 1px solid #e5e7eb;
        }
        td:last-child {
            white-space: nowrap;
        }
        .sort-btn {
            background: transparent;
            border: none;
            font-weight: 600;
            color: #6b7280;
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        .sort-btn:hover {
            color: #4b5563;
        }
        .sort-btn i {
            margin-left: 0.375rem;
            font-size: 0.75rem;
        }
        .action-btns {
            display: flex;
            gap: 0.5rem;
            white-space: nowrap;
        }
        .action-btn {
            padding: 0.35rem 0.5rem;
        }

        /* Filter Styles */
        .filters-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        .filter-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 0.75rem;
            background-color: #f3f4f6;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            color: #4b5563;
            cursor: pointer;
        }
        .filter-chip:hover {
            background-color: #e5e7eb;
        }
        .filter-chip.active {
            background-color: #e0e7ff;
            color: #4f46e5;
        }
        .filter-chip input {
            margin-right: 0.5rem;
        }
        .filter-chip i {
            margin-right: 0.375rem;
        }

        /* Search Styles */
        .search-container {
            margin-bottom: 1.5rem;
        }
        .search-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }
        .search-input:focus {
            outline: none;
            border-color: #8B9DF9;
            box-shadow: 0 0 0 3px rgba(139, 157, 249, 0.2);
        }

        /* Loading indicator */
        .loading {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 1000;
        }

        /* Pagination Styles */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 1.5rem;
            list-style: none;
            padding: 0;
        }

        .pagination li {
            margin: 0 0.25rem;
        }

        .pagination li a, 
        .pagination li span {
            display: inline-block;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            background-color: white;
            border: 1px solid #d1d5db;
            color: #4b5563;
            text-decoration: none;
            cursor: pointer;
        }

        .pagination li.active span {
            background-color: #8B9DF9;
            color: white;
            border-color: #8B9DF9;
        }

        .pagination li a:hover:not(.active) {
            background-color: #f3f4f6;
        }

        .pagination li.disabled span {
            color: #9ca3af;
            pointer-events: none;
            cursor: default;
        }

        /* Status Badge Styles for Shops */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .status-active {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-inactive {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            display: none;
        }
        .modal-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
        }
        .modal-header {
            padding: 1.25rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
        }
        .modal-close {
            background: transparent;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6b7280;
        }
        .modal-body {
            padding: 1.5rem;
        }
        .modal-footer {
            padding: 1.25rem;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }
        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }
        .form-input:focus {
            outline: none;
            border-color: #8B9DF9;
            box-shadow: 0 0 0 3px rgba(139, 157, 249, 0.2);
        }
        .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            background-color: white;
        }
        .form-select:focus {
            outline: none;
            border-color: #8B9DF9;
            box-shadow: 0 0 0 3px rgba(139, 157, 249, 0.2);
        }
        .form-error {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        /* Responsive adjustments */
        @media (max-width: 1200px) {
            .table-container {
                overflow-x: auto;
            }
            table {
                min-width: 1000px;
            }
        }
        @media (max-width: 768px) {
            .content {
                margin-left: 0;
            }
            .sidebar {
                transform: translateX(-100%);
            }
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
            <span class="text-white font-medium">{{ Auth::user()->name ?? 'Admin' }}</span>
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
            <div class="menu-item" id="dashboardMenuItem">
                <a href="#" data-section="dashboard-home">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="menu-item" id="usersMenuItem">
                <a href="#" data-section="users-section">
                    <i class="fas fa-users mr-3"></i>
                    <span>Users</span>
                </a>
            </div>
            <div class="menu-item" id="shopsMenuItem">
                <a href="#" data-section="shops-section">
                    <i class="fas fa-store mr-3"></i>
                    <span>Shops</span>
                </a>
            </div>
            <div class="menu-item" id="outfitMenuItem">
                <a href="#" data-section="outfits-section">
                    <i class="fas fa-tshirt mr-3"></i>
                    <span>Outfits</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Content -->
    <div class="content" id="mainContent">
        <div class="max-w-full mx-auto">
            <!-- Dashboard Home Content -->
            <div id="dashboard-home" class="content-section">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>
                        <p class="mb-4">{{ __("You're logged in!") }}</p>
                        
                        <!-- Dashboard content -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                            <div class="bg-gray-100 p-4 rounded-lg">
                                <h2 class="text-lg font-semibold mb-2">User Management</h2>
                                <p>Manage all users in the system</p>
                                <button class="section-btn mt-2 inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" 
                                        data-section="users-section">
                                    View Users
                                </button>
                            </div>
                            <div class="bg-gray-100 p-4 rounded-lg">
                                <h2 class="text-lg font-semibold mb-2">Shop Management</h2>
                                <p>Manage all shops in the platform</p>
                                <button class="section-btn mt-2 inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" 
                                        data-section="shops-section">
                                    View Shops
                                </button>
                            </div>
                            <div class="bg-gray-100 p-4 rounded-lg">
                                <h2 class="text-lg font-semibold mb-2">Outfit Management</h2>
                                <p>Manage all outfits in the platform</p>
                                <button class="section-btn mt-2 inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" 
                                        data-section="outfits-section">
                                    View Outfits
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Users Section -->
            <div id="users-section" class="content-section hidden">
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center">
                            <i class="fas fa-users card-header-icon"></i>
                            <h2 class="card-title">User Management</h2>
                        </div>
                        <button class="btn btn-primary" id="refreshUsers">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Search and Filters -->
                        <div class="mb-6">
                            <div class="search-container">
                                <form id="userSearchForm" class="flex gap-2">
                                    <input type="text" name="search" placeholder="Search users by name, email, or phone..." 
                                           class="search-input flex-grow" id="userSearchInput">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                </form>
                            </div>
                            
                            <!-- User Type Filters -->
                            <div class="mt-4">
                                <h3 class="text-sm font-semibold text-gray-700 mb-2">Filter by User Type:</h3>
                                <div class="filters-container" id="userTypeFilters">
                                    <label class="filter-chip">
                                        <input type="checkbox" name="userType[]" value="admin" class="user-type-filter"> 
                                        <i class="fas fa-user-shield"></i> Admin
                                    </label>
                                    <label class="filter-chip">
                                        <input type="checkbox" name="userType[]" value="customer" class="user-type-filter"> 
                                        <i class="fas fa-user"></i> Customer
                                    </label>
                                    <label class="filter-chip">
                                        <input type="checkbox" name="userType[]" value="shop owner" class="user-type-filter"> 
                                        <i class="fas fa-store"></i> Shop Owner
                                    </label>
                                    <label class="filter-chip">
                                        <input type="checkbox" name="userType[]" value="make-up artist" class="user-type-filter"> 
                                        <i class="fas fa-paint-brush"></i> Make-up Artist
                                    </label>
                                    <label class="filter-chip">
                                        <input type="checkbox" name="userType[]" value="photographer" class="user-type-filter"> 
                                        <i class="fas fa-camera"></i> Photographer
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Status Filters -->
                            <div class="mt-4">
                                <h3 class="text-sm font-semibold text-gray-700 mb-2">Filter by Status:</h3>
                                <div class="filters-container" id="userStatusFilters">
                                    <label class="filter-chip">
                                        <input type="checkbox" name="status[]" value="active" class="user-status-filter"> 
                                        <i class="fas fa-check-circle text-green-600"></i> Active
                                    </label>
                                    <label class="filter-chip">
                                        <input type="checkbox" name="status[]" value="inactive" class="user-status-filter"> 
                                        <i class="fas fa-times-circle text-red-600"></i> Inactive
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Users Table -->
                        <div class="table-container" id="usersTableContainer">
                            <!-- Table will be loaded here via AJAX -->
                            <div class="text-center py-10">
                                <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                                <p>Loading users...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Shops Section -->
            <div id="shops-section" class="content-section hidden">
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center">
                            <i class="fas fa-store card-header-icon"></i>
                            <h2 class="card-title">Shop Management</h2>
                        </div>
                        <button class="btn btn-primary" id="refreshShops">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Search and Filters -->
                        <div class="mb-6">
                            <div class="search-container">
                                <form id="shopSearchForm" class="flex gap-2">
                                    <input type="text" name="search" placeholder="Search shops by name or description..." 
                                           class="search-input flex-grow" id="shopSearchInput">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                </form>
                            </div>
                            
                            <!-- Shop Status Filters -->
                            <div class="mt-4">
                                <h3 class="text-sm font-semibold text-gray-700 mb-2">Filter by Status:</h3>
                                <div class="filters-container" id="shopStatusFilters">
                                    <label class="filter-chip">
                                        <input type="checkbox" name="shopStatus[]" value="active" class="shop-status-filter"> 
                                        <i class="fas fa-check-circle text-green-600"></i> Active
                                    </label>
                                    <label class="filter-chip">
                                        <input type="checkbox" name="shopStatus[]" value="inactive" class="shop-status-filter"> 
                                        <i class="fas fa-times-circle text-red-600"></i> Inactive
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Shops Table -->
                        <div class="table-container" id="shopsTableContainer">
                            <!-- Table will be loaded here via AJAX -->
                            <div class="text-center py-10">
                                <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                                <p>Loading shops...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Outfits Section -->
            <div id="outfits-section" class="content-section hidden">
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center">
                            <i class="fas fa-tshirt card-header-icon"></i>
                            <h2 class="card-title">Outfit Management</h2>
                        </div>
                        <button class="btn btn-primary" id="refreshOutfits">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Search and Filters -->
                        <div class="mb-6">
                            <div class="search-container">
                                <form id="outfitSearchForm" class="flex gap-2">
                                    <input type="text" name="search" placeholder="Search outfits by name or description..." 
                                           class="search-input flex-grow" id="outfitSearchInput">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Outfits Table -->
                        <div class="table-container" id="outfitsTableContainer">
                            <!-- Table will be loaded here via AJAX -->
                            <div class="text-center py-10">
                            <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                                <p>Loading outfits...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- User Edit Modal -->
            <div class="modal-overlay" id="userEditModal">
                <div class="modal-container">
                    <div class="modal-header">
                        <h3 class="modal-title">Edit User</h3>
                        <button class="modal-close" id="closeUserEditModal">&times;</button>
                    </div>
                    <div class="modal-body" id="userEditForm">
                        <!-- User edit form will be loaded here -->
                    </div>
                </div>
            </div>
            
            <!-- Shop Edit Modal -->
            <div class="modal-overlay" id="shopEditModal">
                <div class="modal-container">
                    <div class="modal-header">
                        <h3 class="modal-title">Edit Shop</h3>
                        <button class="modal-close" id="closeShopEditModal">&times;</button>
                    </div>
                    <div class="modal-body" id="shopEditForm">
                        <!-- Shop edit form will be loaded here -->
                    </div>
                </div>
            </div>
            
            <!-- Outfit Edit Modal -->
            <div class="modal-overlay" id="outfitEditModal">
                <div class="modal-container">
                    <div class="modal-header">
                        <h3 class="modal-title">Edit Outfit</h3>
                        <button class="modal-close" id="closeOutfitEditModal">&times;</button>
                    </div>
                    <div class="modal-body" id="outfitEditForm">
                        <!-- Outfit edit form will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // ===== SIDEBAR & MENU FUNCTIONS =====
            
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
            
            // ===== CONTENT SECTION NAVIGATION =====
            
            // Show dashboard by default
            showSection('dashboard-home');
            
            // Handle menu item clicks
            $('.menu-item a, .section-btn').click(function(e) {
                e.preventDefault();
                const section = $(this).data('section');
                showSection(section);
                
                // If clicking on a section that needs data loading
                if (section === 'users-section') {
                    loadUsers();
                } else if (section === 'shops-section') {
                    loadShops();
                } else if (section === 'outfits-section') {
                    loadOutfits();
                }
            });
            
            // Function to show a specific section and hide others
            function showSection(sectionId) {
                $('.content-section').hide();
                $('#' + sectionId).show();
                
                // Update active menu item
                $('.menu-item').removeClass('active');
                $('.menu-item a[data-section="' + sectionId + '"]').closest('.menu-item').addClass('active');
            }
            
            // ===== DATA LOADING FUNCTIONS =====
            
            // Load Users Data - Updated to properly handle filters and pagination
            function loadUsers(params = {}) {
                $('#usersTableContainer').html('<div class="text-center py-10"><i class="fas fa-spinner fa-spin fa-2x mb-3"></i><p>Loading users...</p></div>');
                
                $.ajax({
                    url: "{{ route('admin.users.index') }}",
                    type: 'GET',
                    data: params,
                    success: function(response) {
                        // Create a temporary div to parse the HTML response
                        const $temp = $('<div>').html(response);
                        
                        // Extract the table
                        const $table = $temp.find('table');
                        
                        // Extract pagination
                        const $pagination = $temp.find('nav:has(.pagination)');
                        
                        if ($table.length) {
                            let html = '<div class="table-wrapper">' + $table.prop('outerHTML') + '</div>';
                            
                            // Add pagination if found
                            if ($pagination.length) {
                                html += '<div class="mt-4">' + $pagination.prop('outerHTML') + '</div>';
                            }
                            
                            // Update the container
                            $('#usersTableContainer').html(html);
                            
                            // Fix role display to ensure consistent badge styling
                            fixRoleDisplay();
                        } else {
                            $('#usersTableContainer').html('<p class="text-center py-5">No users found or error loading data.</p>');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading users:', xhr.responseText);
                        $('#usersTableContainer').html('<p class="text-center py-5 text-red-500">Error loading users. Please try again.</p>');
                    }
                });
            }
            
            // Function to fix role display in user table
            function fixRoleDisplay() {
                // Find all user type cells and apply consistent badge styling
                $('#usersTableContainer table tbody tr').each(function() {
                    const $row = $(this);
                    // Find the cell with the user type
                    const $userTypeCell = $row.find('td:contains("admin"), td:contains("customer"), td:contains("shop owner"), td:contains("make-up artist"), td:contains("photographer")').filter(function() {
                        const text = $(this).clone().children().remove().end().text().trim().toLowerCase();
                        return ['admin', 'customer', 'shop owner', 'make-up artist', 'photographer'].includes(text);
                    });
                    
                    if ($userTypeCell.length) {
                        const userType = $userTypeCell.text().trim().toLowerCase();
                        let badgeClass = '';
                        let icon = '';
                        
                        // Determine badge class and icon based on user type
                        switch(userType) {
                            case 'admin':
                                badgeClass = 'badge-role-admin';
                                icon = 'user-shield';
                                break;
                            case 'customer':
                                badgeClass = 'badge-role-customer';
                                icon = 'user';
                                break;
                            case 'shop owner':
                                badgeClass = 'badge-role-shopowner';
                                icon = 'store';
                                break;
                            case 'make-up artist':
                                badgeClass = 'badge-role-makeup';
                                icon = 'paint-brush';
                                break;
                            case 'photographer':
                                badgeClass = 'badge-role-photographer';
                                icon = 'camera';
                                break;
                            default:
                                badgeClass = 'badge-role-customer';
                                icon = 'user';
                        }
                        
                        // Replace cell content with properly styled badge
                        $userTypeCell.html(`<span class="badge ${badgeClass}"><i class="fas fa-${icon} mr-1"></i> ${userType}</span>`);
                    }
                });
            }
            
            // Load Shops Data
            function loadShops(params = {}) {
                $('#shopsTableContainer').html('<div class="text-center py-10"><i class="fas fa-spinner fa-spin fa-2x mb-3"></i><p>Loading shops...</p></div>');
                
                $.ajax({
                    url: "{{ route('admin.shops.index') }}",
                    type: 'GET',
                    data: params,
                    success: function(response) {
                        // Create a temporary div to parse the HTML response
                        const $temp = $('<div>').html(response);
                        
                        // Extract the table
                        const $table = $temp.find('table');
                        
                        // Extract pagination
                        const $pagination = $temp.find('nav:has(.pagination)');
                        
                        if ($table.length) {
                            let html = '<div class="table-wrapper">' + $table.prop('outerHTML') + '</div>';
                            
                            // Add pagination if found
                            if ($pagination.length) {
                                html += '<div class="mt-4">' + $pagination.prop('outerHTML') + '</div>';
                            }
                            
                            // Update the container
                            $('#shopsTableContainer').html(html);
                            
                            // Fix status display to ensure consistent badge styling
                            fixShopStatusDisplay();
                        } else {
                            $('#shopsTableContainer').html('<p class="text-center py-5">No shops found or error loading data.</p>');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading shops:', xhr.responseText);
                        $('#shopsTableContainer').html('<p class="text-center py-5 text-red-500">Error loading shops. Please try again.</p>');
                    }
                });
            }
            
            // Function to fix status display in shop table
            function fixShopStatusDisplay() {
                // Find all status cells and apply consistent badge styling
                $('#shopsTableContainer table tbody tr').each(function() {
                    const $row = $(this);
                    // Find the cell with the status
                    const $statusCell = $row.find('td:contains("active"), td:contains("inactive")').filter(function() {
                        const text = $(this).clone().children().remove().end().text().trim().toLowerCase();
                        return ['active', 'inactive'].includes(text);
                    });
                    
                    if ($statusCell.length) {
                        const status = $statusCell.text().trim().toLowerCase();
                        
                        // Replace cell content with properly styled badge
                        if (status === 'active') {
                            $statusCell.html('<span class="status-badge status-active"><i class="fas fa-check-circle mr-1"></i> active</span>');
                        } else {
                            $statusCell.html('<span class="status-badge status-inactive"><i class="fas fa-times-circle mr-1"></i> inactive</span>');
                        }
                    }
                });
            }
            
            // Load Outfits Data
            function loadOutfits(params = {}) {
                $('#outfitsTableContainer').html('<div class="text-center py-10"><i class="fas fa-spinner fa-spin fa-2x mb-3"></i><p>Loading outfits...</p></div>');
                
                $.ajax({
                    url: "{{ route('admin.outfits.adminindex') }}",
                    type: 'GET',
                    data: params,
                    success: function(response) {
                        // Create a temporary div to parse the HTML response
                        const $temp = $('<div>').html(response);
                        
                        // Extract the table
                        const $table = $temp.find('table');
                        
                        // Extract pagination
                        const $pagination = $temp.find('nav:has(.pagination)');
                        
                        if ($table.length) {
                            let html = '<div class="table-wrapper">' + $table.prop('outerHTML') + '</div>';
                            
                            // Add pagination if found
                            if ($pagination.length) {
                                html += '<div class="mt-4">' + $pagination.prop('outerHTML') + '</div>';
                            }
                            
                            // Update the container
                            $('#outfitsTableContainer').html(html);
                        } else {
                            $('#outfitsTableContainer').html('<p class="text-center py-5">No outfits found or error loading data.</p>');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading outfits:', xhr.responseText);
                        $('#outfitsTableContainer').html('<p class="text-center py-5 text-red-500">Error loading outfits. Please try again.</p>');
                    }
                });
            }
            
            // ===== SEARCH & FILTER FUNCTIONS =====
            
            // User Search Form Submission
            $('#userSearchForm').submit(function(e) {
                e.preventDefault();
                const searchTerm = $('#userSearchInput').val();
                
                // Collect all filter values
                const params = {};
                if (searchTerm) params.search = searchTerm;
                
                // Add any active filters
                addActiveFilters(params);
                
                // Load users with filters
                loadUsers(params);
            });
            
            // User Type Filter Change
            $(document).on('change', '.user-type-filter', function() {
                const params = {};
                
                // Add search term if present
                const searchTerm = $('#userSearchInput').val();
                if (searchTerm) params.search = searchTerm;
                
                // Add any active filters
                addActiveFilters(params);
                
                // Load users with filters
                loadUsers(params);
            });
            
            // User Status Filter Change
            $(document).on('change', '.user-status-filter', function() {
                const params = {};
                
                // Add search term if present
                const searchTerm = $('#userSearchInput').val();
                if (searchTerm) params.search = searchTerm;
                
                // Add any active filters
                addActiveFilters(params);
                
                // Load users with filters
                loadUsers(params);
            });
            
            // Helper function to add active filters to params
            function addActiveFilters(params) {
                // Add user type filters
                const userTypes = [];
                $('.user-type-filter:checked').each(function() {
                    userTypes.push($(this).val());
                });
                if (userTypes.length > 0) params.userType = userTypes;
                
                // Add status filters
                const statuses = [];
                $('.user-status-filter:checked').each(function() {
                    statuses.push($(this).val());
                });
                if (statuses.length > 0) params.status = statuses;
            }
            
            // Shop Search Form Submission
            $('#shopSearchForm').submit(function(e) {
                e.preventDefault();
                const searchTerm = $('#shopSearchInput').val();
                
                // Collect all filter values
                const params = {};
                if (searchTerm) params.search = searchTerm;
                
                // Add shop status filters
                const shopStatuses = [];
                $('.shop-status-filter:checked').each(function() {
                    shopStatuses.push($(this).val());
                });
                if (shopStatuses.length > 0) params.status = shopStatuses;
                                // Load shops with filters
                                loadShops(params);
            });
            
            // Shop Status Filter Change
            $(document).on('change', '.shop-status-filter', function() {
                const params = {};
                
                // Add search term if present
                const searchTerm = $('#shopSearchInput').val();
                if (searchTerm) params.search = searchTerm;
                
                // Add shop status filters
                const shopStatuses = [];
                $('.shop-status-filter:checked').each(function() {
                    shopStatuses.push($(this).val());
                });
                if (shopStatuses.length > 0) params.status = shopStatuses;
                
                // Load shops with filters
                loadShops(params);
            });
            
            // Outfit Search Form Submission
            $('#outfitSearchForm').submit(function(e) {
                e.preventDefault();
                const searchTerm = $('#outfitSearchInput').val();
                
                // Collect all filter values
                const params = {};
                if (searchTerm) params.search = searchTerm;
                
                // Load outfits with filters
                loadOutfits(params);
            });
            
            // ===== TABLE SORTING =====
            
            // Handle table sorting clicks (delegated event)
            $(document).on('click', '.sort-btn, th button', function(e) {
                e.preventDefault();
                
                // Find the closest form and get sorting parameters
                const $form = $(this).closest('form');
                if (!$form.length) return;
                
                const orderBy = $form.find('input[name="orderBy"]').val();
                const direction = $form.find('input[name="direction"]').val();
                
                // Determine which section we're in
                let loadFunction;
                let params = {
                    orderBy: orderBy,
                    direction: direction
                };
                
                if ($('#users-section').is(':visible')) {
                    loadFunction = loadUsers;
                    
                    // Add search term if present
                    const searchTerm = $('#userSearchInput').val();
                    if (searchTerm) params.search = searchTerm;
                    
                    // Add any active filters
                    addActiveFilters(params);
                } else if ($('#shops-section').is(':visible')) {
                    loadFunction = loadShops;
                    
                    // Add search term if present
                    const searchTerm = $('#shopSearchInput').val();
                    if (searchTerm) params.search = searchTerm;
                    
                    // Add shop status filters
                    const shopStatuses = [];
                    $('.shop-status-filter:checked').each(function() {
                        shopStatuses.push($(this).val());
                    });
                    if (shopStatuses.length > 0) params.status = shopStatuses;
                } else if ($('#outfits-section').is(':visible')) {
                    loadFunction = loadOutfits;
                    
                    // Add search term if present
                    const searchTerm = $('#outfitSearchInput').val();
                    if (searchTerm) params.search = searchTerm;
                } else {
                    return;
                }
                
                // Load data with sorting parameters
                loadFunction(params);
            });
            
            // ===== PAGINATION HANDLING =====
            
            // Handle pagination clicks (delegated event)
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                
                const url = $(this).attr('href');
                if (!url) return;
                
                // Extract query parameters from URL
                const queryString = url.split('?')[1];
                if (!queryString) return;
                
                // Parse query parameters
                const urlParams = new URLSearchParams(queryString);
                const params = {};
                
                // Convert URLSearchParams to plain object
                for (const [key, value] of urlParams.entries()) {
                    params[key] = value;
                }
                
                // Determine which section we're in and load appropriate data
                if ($('#users-section').is(':visible')) {
                    // Add any active filters
                    addActiveFilters(params);
                    loadUsers(params);
                } else if ($('#shops-section').is(':visible')) {
                    // Add shop status filters
                    const shopStatuses = [];
                    $('.shop-status-filter:checked').each(function() {
                        shopStatuses.push($(this).val());
                    });
                    if (shopStatuses.length > 0) params.status = shopStatuses;
                    loadShops(params);
                } else if ($('#outfits-section').is(':visible')) {
                    loadOutfits(params);
                }
            });
            
            // ===== STATUS TOGGLE FUNCTIONS =====
            
            // Handle status toggle for users and shops (delegated event)
            $(document).on('submit', 'form[action*="toggleStatus"]', function(e) {
                e.preventDefault();
                
                const $form = $(this);
                const actionUrl = $form.attr('action');
                const isShop = actionUrl.includes('shops');
                
                // Show loading state on the button
                const $button = $form.find('button');
                const originalButtonHtml = $button.html();
                $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
                
                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    data: $form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Determine which section to refresh
                            if (isShop) {
                                loadShops();
                            } else {
                                loadUsers();
                            }
                        } else {
                            console.error("Status toggle failed:", response);
                            alert("Failed to update status. Please try again.");
                            $button.prop('disabled', false).html(originalButtonHtml);
                        }
                    },
                    error: function(xhr) {
                        console.error("Error toggling status:", xhr.responseText);
                        alert("Failed to update status. Please try again.");
                        $button.prop('disabled', false).html(originalButtonHtml);
                    }
                });
            });
            
            // ===== EDIT MODAL FUNCTIONS =====
            
            // Open User Edit Modal
            $(document).on('click', 'a[href*="admin/users"][href*="edit"]', function(e) {
                e.preventDefault();
                
                const url = $(this).attr('href');
                
                // Show loading indicator
                $('#loadingIndicator').show();
                
                // Load edit form via AJAX
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'html',
                    success: function(response) {
                        // Extract just the form content
                        const formContent = $(response).find('form').first();
                        
                        // Show edit form in modal
                        $('#userEditForm').html(formContent);
                        $('#userEditModal').show();
                        
                        // Hide loading indicator
                        $('#loadingIndicator').hide();
                    },
                    error: function(xhr) {
                        console.error('Error loading edit form:', xhr.responseText);
                        $('#loadingIndicator').hide();
                        alert('Error loading edit form. Please try again.');
                    }
                });
            });
            
            // Close User Edit Modal
            $('#closeUserEditModal').click(function() {
                $('#userEditModal').hide();
            });
            
            // Handle User Edit Form Submission
            $(document).on('submit', '#userEditForm form', function(e) {
                e.preventDefault();
                
                const formData = $(this).serialize();
                const formAction = $(this).attr('action');
                
                // Show loading indicator
                $('#loadingIndicator').show();
                
                // Submit form via AJAX
                $.ajax({
                    url: formAction,
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Hide loading indicator
                        $('#loadingIndicator').hide();
                        
                        // Close modal
                        $('#userEditModal').hide();
                        
                        // Show success message
                        alert('User updated successfully');
                        
                        // Reload users table
                        loadUsers();
                    },
                    error: function(xhr) {
                        // Hide loading indicator
                        $('#loadingIndicator').hide();
                        
                        // Show error message
                        alert('Error updating user: ' + xhr.responseText);
                    }
                });
            });
            
            // Open Shop Edit Modal
            $(document).on('click', '#shops-section form[action*="edit"] button, #shops-section .btn-info', function(e) {
                e.preventDefault();
                
                // Get the parent form
                const $form = $(this).closest('form');
                const url = $form.attr('action');
                
                // Show loading indicator
                $('#loadingIndicator').show();
                
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: $form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Process the full HTML response
                        const $response = $(response);
                        
                        // Find the form
                        const $form = $response.find('form');
                        
                        if ($form.length) {
                            // Show edit form in modal
                            $('#shopEditForm').html($form);
                            $('#shopEditModal').show();
                        } else {
                            alert("Error: Could not find the shop edit form in the response");
                        }
                        
                        // Hide loading indicator
                        $('#loadingIndicator').hide();
                    },
                    error: function(xhr) {
                        console.error('Error loading shop edit form:', xhr.responseText);
                        alert('Error loading shop edit form. See console for details.');
                        $('#loadingIndicator').hide();
                    }
                });
            });
            
            // Close Shop Edit Modal
            $('#closeShopEditModal').click(function() {
                $('#shopEditModal').hide();
            });
            
            // Handle Shop Edit Form Submission
            $(document).on('submit', '#shopEditForm form', function(e) {
                e.preventDefault();
                
                const formData = $(this).serialize();
                const formAction = $(this).attr('action');
                
                // Show loading indicator
                $('#loadingIndicator').show();
                
                // Submit form via AJAX
                $.ajax({
                    url: formAction,
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Hide loading indicator
                        $('#loadingIndicator').hide();
                        
                        // Close modal
                        $('#shopEditModal').hide();
                        
                        // Show success message
                        alert('Shop updated successfully');
                        
                        // Reload shops table
                        loadShops();
                    },
                    error: function(xhr) {
                        // Hide loading indicator
                        $('#loadingIndicator').hide();
                        
                        // Show error message
                        alert('Error updating shop: ' + xhr.responseText);
                    }
                });
            });
            
            // Open Outfit Edit Modal
            $(document).on('click', 'a[href*="admin/outfits"][href*="edit"]', function(e) {
                e.preventDefault();
                
                const url = $(this).attr('href');
                
                // Show loading indicator
                $('#loadingIndicator').show();
                
                // Load edit form via AJAX
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'html',
                    success: function(response) {
                        // Extract just the form content
                        const formContent = $(response).find('form').first();
                        
                        // Show edit form in modal
                        $('#outfitEditForm').html(formContent);
                        $('#outfitEditModal').show();
                        
                        // Hide loading indicator
                        $('#loadingIndicator').hide();
                    },
                    error: function(xhr) {
                        console.error('Error loading outfit edit form:', xhr.responseText);
                        $('#loadingIndicator').hide();
                        alert('Error loading outfit edit form. Please try again.');
                    }
                });
            });
            
            // Close Outfit Edit Modal
            $('#closeOutfitEditModal').click(function() {
                $('#outfitEditModal').hide();
            });
            
            // Handle Outfit Edit Form Submission
            $(document).on('submit', '#outfitEditForm form', function(e) {
                e.preventDefault();
                
                // For file uploads, we need FormData
                const formData = new FormData(this);
                const formAction = $(this).attr('action');
                
                // Show loading indicator
                $('#loadingIndicator').show();
                
                // Submit form via AJAX
                $.ajax({
                    url: formAction,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Hide loading indicator
                        $('#loadingIndicator').hide();
                        
                        // Close modal
                        $('#outfitEditModal').hide();
                        
                        // Show success message
                        alert('Outfit updated successfully');
                        
                        // Reload outfits table
                        loadOutfits();
                    },
                    error: function(xhr) {
                        // Hide loading indicator
                        $('#loadingIndicator').hide();
                        
                        // Show error message
                        alert('Error updating outfit: ' + xhr.responseText);
                    }
                });
            });
            
            // ===== REFRESH BUTTONS =====
            
            // Refresh Users Button
            $('#refreshUsers').click(function() {
                loadUsers();
            });
            
            // Refresh Shops Button
            $('#refreshShops').click(function() {
                loadShops();
            });
            
            // Refresh Outfits Button
            $('#refreshOutfits').click(function() {
                loadOutfits();
            });
            
            // ===== CLICK OUTSIDE MODAL TO CLOSE =====
            
            // Close modals when clicking outside
            $('.modal-overlay').click(function(e) {
                if (e.target === this) {
                    $(this).hide();
                }
            });
            
            // ===== ESCAPE KEY TO CLOSE MODALS =====
            
            // Close active modal with Escape key
            $(document).keydown(function(e) {
                if (e.key === "Escape") {
                    $('.modal-overlay:visible').hide();
                }
            });
        });
    </script>
</body>
</html>


