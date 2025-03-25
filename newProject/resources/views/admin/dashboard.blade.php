@extends('layouts.admin-layout')

@section('title', 'Dashboard')

@section('content')
    
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
@endsection


