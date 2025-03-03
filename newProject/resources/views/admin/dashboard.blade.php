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
            transition: transform 0.3s ease-in-out; /* Add transition for smooth animation */
            z-index: 90; /* Ensure sidebar appears above content */
        }
        
        /* New class for collapsed sidebar */
        .sidebar-collapsed {
            transform: translateX(-100%); /* Move sidebar off-screen */
        }
        
        .content {
            margin-left: 259px;
            margin-top: 71px;
            padding: 20px;
            flex: 1;
            transition: margin-left 0.3s ease-in-out; /* Add transition for content */
        }
        
        /* New class for content when sidebar is collapsed */
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
                    <i class="fas fa-store mr-3"></i>
                    <span>Outfits</span>
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
            <div id="outfit-content" class="content-section hidden"></div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Toggle sidebar visibility with animation
            $('#menuToggle').click(function() {
                $('#sidebar').toggleClass('sidebar-collapsed');
                $('.content').toggleClass('content-expanded');
                
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

<form id="filter-form" action="javascript:void(0);">
    <input type="hidden" name="orderBy" value="{{ request('orderBy') }}">
    <input type="hidden" name="direction" value="{{ request('direction') }}">

    <div class="filters-container">
        <label class="filter-chip {{ is_array(request('userType')) && in_array('admin', request('userType')) ? 'active' : '' }}">
            <input type="checkbox" name="userType[]" value="admin" {{ is_array(request('userType')) && in_array('admin', request('userType')) ? 'checked' : '' }}>
            <i class="fas fa-user-shield"></i> Admin
        </label>
        <!-- Other filters remain the same -->
    </div>

    <button type="submit" class="btn btn-primary">
        <i class="fas fa-filter"></i> Apply Filter
    </button>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Handle filter form submission
    $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        
        $.ajax({
            url: "{{ route('admin.users.index') }}",
            type: 'GET',
            data: formData,
            beforeSend: function() {
                // Optional loading indicator
                $('#users-table-container').css('opacity', '0.5');
            },
            success: function(response) {
                // Update just the table HTML
                $('#users-table-container').html(response);
                $('#users-table-container').css('opacity', '1');
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText);
                $('#users-table-container').css('opacity', '1');
            }
        });
    });
    
    // Updated status toggle handler
    $(document).on('submit', 'form[action*="toggleStatus"]', function(e) {
        e.preventDefault(); // Prevent normal form submission
        
        var $form = $(this);
        var userId = $form.attr('action').split('/').pop(); // Extract user ID from the URL
        
        // Show loading state
        $form.find('button').prop('disabled', true).css('opacity', '0.7');
        
        // Make the AJAX request to toggle status
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log("Status toggled successfully");
                
                // Instead of reloading the entire table with sorting parameters,
                // simply update the specific row or status indicator
                if (response.success) {
                    // Find the status cell for this user
                    var $userRow = $('tr[data-user-id="' + userId + '"]');
                    
                    if ($userRow.length) {
                        // Update the status badge
                        var $statusCell = $userRow.find('td:has(.badge-status-active, .badge-status-inactive)');
                        var newStatus = response.user.status;
                        
                        if (newStatus === 'active') {
                            $statusCell.html('<span class="badge badge-status-active"><i class="fas fa-check-circle mr-1"></i> active</span>');
                            // Update the button to be a deactivate button
                            $form.find('input[name="status"]').val('inactive');
                            $form.find('button')
                                .removeClass('btn-success')
                                .addClass('btn-danger')
                                .html('<i class="fas fa-ban"></i> Deactivate');
                        } else {
                            $statusCell.html('<span class="badge badge-status-inactive"><i class="fas fa-times-circle mr-1"></i> inactive</span>');
                            // Update the button to be an activate button
                            $form.find('input[name="status"]').val('active');
                            $form.find('button')
                                .removeClass('btn-danger')
                                .addClass('btn-success')
                                .html('<i class="fas fa-check"></i> Activate');
                        }
                        
                        // Re-enable the button
                        $form.find('button').prop('disabled', false).css('opacity', '1');
                    } else {
                        // If we can't find the row, refresh the whole table but maintain current state
                        refreshUserTable();
                    }
                } else {
                    // In case of error in the response
                    alert("Failed to update user status.");
                    $form.find('button').prop('disabled', false).css('opacity', '1');
                }
            },
            error: function(xhr) {
                console.error("Error toggling status:", xhr.responseText);
                $form.find('button').prop('disabled', false).css('opacity', '1');
                alert("Failed to update user status. Please try again.");
            }
        });
    });
    
    // Helper function to refresh the user table while preserving current state
    function refreshUserTable() {
        var contentSection = 'users-content';
        var refreshUrl = "{{ route('admin.users.index') }}";
        
        // Get the current URL parameters instead of extracting from form elements
        var currentUrl = new URL(window.location.href);
        var searchParams = currentUrl.searchParams;
        var refreshData = {};
        
        // Only include parameters that already exist in the URL
        if (searchParams.has('orderBy')) refreshData.orderBy = searchParams.get('orderBy');
        if (searchParams.has('direction')) refreshData.direction = searchParams.get('direction');
        
        // Handle multiple userType[] parameters
        var userTypes = searchParams.getAll('userType[]');
        if (userTypes.length > 0) {
            refreshData.userType = userTypes;
        }
        
        $.ajax({
            url: refreshUrl,
            type: 'GET',
            data: refreshData,
            success: function(response) {
                $('#' + contentSection).html(response);
            },
            error: function(xhr) {
                console.error("Error refreshing table:", xhr.responseText);
            }
        });
    }
});
</script>
<script>
$(document).ready(function() {
    // More specific selector for sort buttons in table headers
    $(document).on('click', 'th .sort-btn', function(e) {
        e.preventDefault(); // Prevent default form submission
        e.stopPropagation(); // Stop event bubbling
        
        console.log("Sort button clicked"); // Debug log
        
        // Get the parent form
        var form = $(this).closest('form');
        
        // Get the order parameters
        var orderBy = form.find('input[name="orderBy"]').val();
        var direction = form.find('input[name="direction"]').val();
        
        console.log("Sorting by:", orderBy, "Direction:", direction); // Debug log
        
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
        
        if (!contentSection || !targetUrl) {
            console.log("Content section not determined"); // Debug log
            return;
        }
        
        // Collect any active filters
        var data = {
            orderBy: orderBy,
            direction: direction
        };
        
        // If there are filter checkboxes, include them
        $('input[name="userType[]"]:checked').each(function() {
            if (!data.userType) {
                data.userType = [];
            }
            data.userType.push($(this).val());
        });
        
        console.log("AJAX data:", data); // Debug log
        
        // Make the AJAX request
        $.ajax({
            url: targetUrl,
            type: 'GET',
            data: data,
            beforeSend: function() {
                $('#' + contentSection).css('opacity', '0.5');
            },
            success: function(response) {
                console.log("AJAX success"); // Debug log
                $('#' + contentSection).html(response).css('opacity', '1');
                
                // Update URL without reloading
                var queryParams = $.param(data, true); // true enables traditional encoding for arrays
                var newUrl = window.location.pathname + '?' + queryParams;
                history.pushState(null, '', newUrl);
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", status, error); // Debug log
                $('#' + contentSection).css('opacity', '1');
                alert('An error occurred while sorting the data.');
            }
        });
    });
    
    // Also fix the form submission handlers for the forms in table headers
    $(document).on('submit', 'th form', function(e) {
        e.preventDefault(); // Prevent regular form submission
        $(this).find('.sort-btn').trigger('click'); // Trigger the click handler instead
    });
});
</script>
<script>
$(document).ready(function() {
    // Also update the filter form submission to use the same approach
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
            
            if (name.endsWith('[]')) {
                // Handle array inputs
                var baseName = name.slice(0, -2);
                if (!data[baseName]) {
                    data[baseName] = [];
                }
                data[baseName].push($input.val());
            } else {
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
        
        $.ajax({
            url: targetUrl,
            type: 'GET',
            data: data,
            beforeSend: function() {
                $('#' + contentSection).css('opacity', '0.5');
            },
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
                
                console.log('Filter applied successfully');
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText);
                $('#' + contentSection).css('opacity', '1');
                alert('An error occurred while filtering data.');
            }
        });
    });
    
    // Debug logging for click events on sort buttons
    $(document).on('click', '.sort-btn, .header-cell button', function() {
        console.log('Sort button clicked:', $(this).text().trim());
    });
});
</script>