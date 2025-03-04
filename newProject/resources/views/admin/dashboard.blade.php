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
            height: 953px;
            background-color: #292828;
            position: fixed;
            top: 71px;
            left: 0;
            color: white;
            transition: transform 0.3s ease-in-out;
            z-index: 90;
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

        /* Badge Styles for Users */
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

        /* Status Badge Styles for Shops */
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .status-active {
            background-color: #DEF7EC;
            color: #03543E;
        }
        .status-inactive {
            background-color: #FDE8E8;
            color: #9B1C1C;
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
            <div class="menu-item" id="outfitMenuItem">
                <a href="#" data-target="outfit-content" data-url="{{ route('admin.outfits.adminindex') }}">
                    <i class="fas fa-tshirt mr-3"></i>
                    <span>Outfits</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Content -->
    <div class="content" id="mainContent">
        <div class="max-w-7xl mx-auto">
            <!-- Dashboard Home Content -->
            <div id="dashboard-home" class="content-section dashboard-home">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>
                        <p class="mb-4">{{ __("You're logged in!") }}</p>
                        
                        <!-- Dashboard content -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
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
                            <div class="bg-gray-100 p-4 rounded-lg">
                                <h2 class="text-lg font-semibold mb-2">Outfit Management</h2>
                                <p>Manage all outfits in the platform</p>
                                <button class="load-content mt-2 inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" 
                                        data-target="outfit-content" 
                                        data-url="{{ route('admin.outfits.adminindex') }}">
                                    View Outfits
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
            
            <!-- Outfit Content Section (initially hidden) -->
            <div id="outfit-content" class="content-section hidden"></div>

            <!-- Add this after your other content sections (after users-content, shops-content, etc.) -->
            <div id="user-edit-content" class="content-section hidden bg-white overflow-hidden shadow-sm rounded-lg p-6"></div>

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
            
            // Handle dashboard menu item clicks to load content
            $('.sidebar .menu-item a, .load-content').click(function(e) {
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
                
                                // Load content via AJAX
                                if (url) {
                    loadContent(url, target);
                }
            });
            
            // ===== CONTENT LOADING FUNCTIONS =====
            
            // Function to load content via AJAX
            function loadContent(url, targetId) {
                // Show loading indicator
                $('#loadingIndicator').show();
                
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
                        
                        // Hide loading indicator
                        $('#loadingIndicator').hide();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading content:', error);
                        $('#loadingIndicator').hide();
                        alert('Error loading content. Please try again.');
                    }
                });
            }
            
            // ===== EVENT DELEGATION HANDLERS =====
            
            // Handle filter form submission
            $(document).on('submit', '#filter-form', function(e) {
                e.preventDefault();
                
                var data = {};
                var $form = $(this);
                
                // Get all form values including hidden inputs
                $form.find('input').each(function() {
                    var $input = $(this);
                    var name = $input.attr('name');
                    
                    if ($input.attr('type') === 'checkbox' && !$input.prop('checked')) {
                        return; // Skip unchecked checkboxes
                    }
                    
                    if (name && name.endsWith('[]')) {
                        // Handle array inputs
                        var baseName = name.slice(0, -2);
                        if (!data[baseName]) {
                            data[baseName] = [];
                        }
                        data[baseName].push($input.val());
                    } else if (name) {
                        data[name] = $input.val();
                    }
                });
                
                // Determine target URL based on visible content
                var contentSection;
                var targetUrl;
                
                if ($('#users-content').is(':visible')) {
                    contentSection = 'users-content';
                    targetUrl = "{{ route('admin.users.index') }}";
                } else if ($('#shops-content').is(':visible')) {
                    contentSection = 'shops-content';
                    targetUrl = "{{ route('admin.shops.index') }}";
                } else if ($('#outfit-content').is(':visible')) {
                    contentSection = 'outfit-content';
                    targetUrl = "{{ route('admin.outfits.adminindex') }}";
                }
                
                if (!contentSection || !targetUrl) return;
                
                // Show loading state
                $('#' + contentSection).css('opacity', '0.5');
                
                $.ajax({
                    url: targetUrl,
                    type: 'GET',
                    data: data,
                    success: function(response) {
                        $('#' + contentSection).html(response).css('opacity', '1');
                        
                        // Create a proper query string
                        var queryString = Object.keys(data).map(function(key) {
                            if (Array.isArray(data[key])) {
                                return data[key].map(function(value) {
                                    return key + '[]=' + encodeURIComponent(value);
                                }).join('&');
                            }
                            return key + '=' + encodeURIComponent(data[key]);
                        }).join('&');
                        
                        // Update URL without reloading
                        var newUrl = window.location.pathname + '?' + queryString;
                        window.history.pushState({}, '', newUrl);
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                        $('#' + contentSection).css('opacity', '1');
                        alert('An error occurred while filtering data.');
                    }
                });
            });
            
            // Handle table sort actions for both users and shops
            $(document).on('click', '.sort-btn, .header-cell button', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                var form = $(this).closest('form');
                var orderBy = form.find('input[name="orderBy"]').val();
                var direction = form.find('input[name="direction"]').val();
                
                // Determine the current content section
                var contentSection;
                var targetUrl;
                
                if ($('#users-content').is(':visible')) {
                    contentSection = 'users-content';
                    targetUrl = "{{ route('admin.users.index') }}";
                } else if ($('#shops-content').is(':visible')) {
                    contentSection = 'shops-content';
                    targetUrl = "{{ route('admin.shops.index') }}";
                } else if ($('#outfit-content').is(':visible')) {
                    contentSection = 'outfit-content';
                    targetUrl = "{{ route('admin.outfits.adminindex') }}";
                }
                
                if (!contentSection || !targetUrl) return;
                
                // Collect any active filters and search terms
                var data = {
                    orderBy: orderBy,
                    direction: direction
                };
                
                // Include search term if present
                var searchInput = $('input[name="search"]');
                if (searchInput.length && searchInput.val()) {
                    data.search = searchInput.val();
                }
                
                // Include any filter checkboxes
                $('input[name$="[]"]:checked').each(function() {
                    var name = $(this).attr('name');
                    var baseName = name.slice(0, -2);
                    
                    if (!data[baseName]) {
                        data[baseName] = [];
                    }
                    data[baseName].push($(this).val());
                });
                
                // Show loading state
                $('#' + contentSection).css('opacity', '0.5');
                
                $.ajax({
                    url: targetUrl,
                    type: 'GET',
                    data: data,
                    success: function(response) {
                        $('#' + contentSection).html(response).css('opacity', '1');
                        
                        // Update URL without reloading
                        var queryParams = $.param(data, true);
                        var newUrl = window.location.pathname + '?' + queryParams;
                        history.pushState(null, '', newUrl);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error:", status, error);
                        $('#' + contentSection).css('opacity', '1');
                        alert('An error occurred while sorting the data.');
                    }
                });
            });
            
            // Handle search form submission
            $(document).on('submit', 'form[action*="index"]', function(e) {
                // Only process search forms with search input
                if (!$(this).find('input[name="search"]').length) return;
                
                e.preventDefault();
                
                var $form = $(this);
                var search = $form.find('input[name="search"]').val();
                
                // Determine the current content section
                var contentSection;
                var targetUrl = $form.attr('action');
                
                if (targetUrl.includes('users')) {
                    contentSection = 'users-content';
                } else if (targetUrl.includes('shops')) {
                    contentSection = 'shops-content';
                } else if (targetUrl.includes('outfits')) {
                    contentSection = 'outfit-content';
                }
                
                if (!contentSection) return;
                
                // Collect existing sort parameters if any
                var data = { search: search };
                
                var orderByInput = $('input[name="orderBy"]:first');
                var directionInput = $('input[name="direction"]:first');
                
                if (orderByInput.length && directionInput.length) {
                    data.orderBy = orderByInput.val();
                    data.direction = directionInput.val();
                }
                
                // Show loading state
                $('#' + contentSection).css('opacity', '0.5');
                
                $.ajax({
                    url: targetUrl,
                    type: 'GET',
                    data: data,
                    success: function(response) {
                        $('#' + contentSection).html(response).css('opacity', '1');
                        
                        // Update URL
                        var queryParams = $.param(data);
                        var newUrl = window.location.pathname + '?' + queryParams;
                        history.pushState(null, '', newUrl);
                    },
                    error: function(xhr) {
                        console.error('Error searching:', xhr.responseText);
                        $('#' + contentSection).css('opacity', '1');
                        alert('An error occurred while searching.');
                    }
                });
            });
            
            // Handle status toggle submissions - updated version
$(document).on('submit', 'form[action*="toggleStatus"]', function(e) {
    e.preventDefault();
    
    var $form = $(this);
    var actionUrl = $form.attr('action');
    var isShop = actionUrl.includes('shops');
    
    // Extract ID from the URL - this is more reliable
    var id = actionUrl.split('/').pop();
    var newStatus = $form.find('input[name="status"]').val();
    
    // Show loading state on the button
    $form.find('button').prop('disabled', true).css('opacity', '0.7');
    
    $.ajax({
        url: actionUrl,
        type: 'POST',
        data: $form.serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // For shops, update the row status and button
                if (isShop) {
                    // First try to find the row by first cell content matching shop_id
                    var $row = $('tr td:first-child').filter(function() {
                        return $(this).text().trim() === id;
                    }).closest('tr');
                    
                    if ($row.length) {
                        // Find the status cell - it has the status-badge class
                        var $statusCell = $row.find('td span.status-badge').parent();
                        
                        if (newStatus === 'active') {
                            $statusCell.html('<span class="status-badge status-active">active</span>');
                            // Update button to be a deactivate button
                            $form.find('input[name="status"]').val('inactive');
                            $form.find('button')
                                .removeClass('btn-success')
                                .addClass('btn-danger')
                                .html('<i class="fas fa-ban mr-1"></i> Deactivate');
                        } else {
                            $statusCell.html('<span class="status-badge status-inactive">inactive</span>');
                            // Update button to be an activate button
                            $form.find('input[name="status"]').val('active');
                            $form.find('button')
                                .removeClass('btn-danger')
                                .addClass('btn-success')
                                .html('<i class="fas fa-check-circle mr-1"></i> Activate');
                        }
                    } else {
                        // If we can't find the row, just reload the content
                        var currentSection = isShop ? 'shops-content' : 'users-content';
                        var refreshUrl = isShop ? "{{ route('admin.shops.index') }}" : "{{ route('admin.users.index') }}";
                        loadContent(refreshUrl, currentSection);
                        return;
                    }
                } else {
                    // User status update
                    var $row = $('tr td:first-child').filter(function() {
                        return $(this).text().trim() === id;
                    }).closest('tr');
                    
                    if ($row.length) {
                        var $statusCell = $row.find('td:has(.badge-status-active, .badge-status-inactive)');
                        
                        if (newStatus === 'active') {
                            $statusCell.html('<span class="badge badge-status-active"><i class="fas fa-check-circle mr-1"></i> active</span>');
                            // Update button to be a deactivate button
                            $form.find('input[name="status"]').val('inactive');
                            $form.find('button')
                                .removeClass('btn-success')
                                .addClass('btn-danger')
                                .html('<i class="fas fa-ban"></i> Deactivate');
                        } else {
                            $statusCell.html('<span class="badge badge-status-inactive"><i class="fas fa-times-circle mr-1"></i> inactive</span>');
                            // Update button to be an activate button
                            $form.find('input[name="status"]').val('active');
                            $form.find('button')
                                .removeClass('btn-danger')
                                .addClass('btn-success')
                                .html('<i class="fas fa-check"></i> Activate');
                        }
                    } else {
                        // If we can't find the row, just reload the content
                        loadContent("{{ route('admin.users.index') }}", 'users-content');
                        return;
                    }
                }
            } else {
                console.error("Status toggle failed:", response);
                alert("Failed to update status. Please try again.");
            }
            
            // Always re-enable the button
            $form.find('button').prop('disabled', false).css('opacity', '1');
        },
        error: function(xhr) {
            console.error("Error toggling status:", xhr.responseText);
            $form.find('button').prop('disabled', false).css('opacity', '1');
            alert("Failed to update status. Please try again.");
        }
    });
});

// Add this to your existing JavaScript section in dashboard.blade.php
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
            // Hide all content sections
            $('.content-section').hide();
            
            // Extract just the form content from the response (we don't want the entire page)
            const formContent = $(response).find('form').first();
            
            // Show edit form section and fill with form content
            $('#user-edit-content').html(formContent).show();
            
            // Add custom submit handler for the form
            $('#user-edit-content form').on('submit', function(e) {
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
                        
                        // Show success message
                        alert('User updated successfully');
                        
                        // Reload users table
                        loadContent("{{ route('admin.users.index') }}", 'users-content');
                    },
                    error: function(xhr) {
                        // Hide loading indicator
                        $('#loadingIndicator').hide();
                        
                        // Show error message
                        alert('Error updating user: ' + xhr.responseText);
                    }
                });
            });
            
            // Add cancel button handler
            $('#user-edit-content').on('click', 'a[href*="admin/dashboard"]', function(e) {
                e.preventDefault();
                
                // Hide edit form and show users table
                $('#user-edit-content').hide();
                $('#users-content').show();
            });
            
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

            
            // Set dashboard as active by default
            $('#dashboardMenuItem').addClass('active');
        });
    </script>
</body>
</html>
