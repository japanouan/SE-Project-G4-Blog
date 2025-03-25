@extends('layouts.admin-layout')

@section('title', 'User Management')

@section('content')
<div class="container">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-store mr-2 text-[#8B9DF9]"></i>Shop Management
        </h1>

        <form action="{{ route('admin.shops.acceptance') }}" method="GET">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-check-circle mr-2"></i>Acceptance Queue
            </button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-[#8B9DF9] text-xl mr-3"></i>
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Manage Shop Listings</h2>
                    <p class="text-gray-600">View and manage all registered shops in the system. Click column headers to sort.</p>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('admin.shops.index') }}" class="mb-4 flex p-4">
            <input type="text" name="search" placeholder="ค้นหา Shop ID, Shop name"
                class="border p-2 w-full rounded-l-md"
                value="{{ request('search') }}">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r-md">ค้นหา</button>
        </form>

        <div class="table-container">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="header-cell">
                            <form action="{{ route('admin.shops.index') }}" method="GET">
                                @csrf
                                <input type="hidden" name="orderBy" value="shop_id">
                                <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                <button type="submit" class="w-full text-left">
                                    ID
                                    <i class="fas fa-{{ request('orderBy') == 'shop_id' ? (request('direction') == 'asc' ? 'sort-up' : 'sort-down') : 'sort' }}"></i>
                                </button>
                            </form>
                        </th>
                        <th class="header-cell">
                            <form action="{{ route('admin.shops.index') }}" method="GET">
                                @csrf
                                <input type="hidden" name="orderBy" value="shop_name">
                                <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                <button type="submit" class="w-full text-left">
                                    Shop Name
                                    <i class="fas fa-{{ request('orderBy') == 'shop_name' ? (request('direction') == 'asc' ? 'sort-up' : 'sort-down') : 'sort' }}"></i>
                                </button>
                            </form>
                        </th>
                        <th class="header-cell">
                            <form action="{{ route('admin.shops.index') }}" method="GET">
                                @csrf
                                <input type="hidden" name="orderBy" value="shop_description">
                                <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                <button type="submit" class="w-full text-left">
                                    Description
                                    <i class="fas fa-{{ request('orderBy') == 'shop_description' ? (request('direction') == 'asc' ? 'sort-up' : 'sort-down') : 'sort' }}"></i>
                                </button>
                            </form>
                        </th>
                        <th class="header-cell">
                            <form action="{{ route('admin.shops.index') }}" method="GET">
                                @csrf
                                <input type="hidden" name="orderBy" value="shop_location">
                                <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                <button type="submit" class="w-full text-left">
                                    Location
                                    <i class="fas fa-{{ request('orderBy') == 'shop_location' ? (request('direction') == 'asc' ? 'sort-up' : 'sort-down') : 'sort' }}"></i>
                                </button>
                            </form>
                        </th>
                        <th class="header-cell">
                            <form action="{{ route('admin.shops.index') }}" method="GET">
                                @csrf
                                <input type="hidden" name="orderBy" value="status">
                                <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                <button type="submit" class="w-full text-left">
                                    Status
                                    <i class="fas fa-{{ request('orderBy') == 'status' ? (request('direction') == 'asc' ? 'sort-up' : 'sort-down') : 'sort' }}"></i>
                                </button>
                            </form>
                        </th>
                        <th class="header-cell">
                            <form action="{{ route('admin.shops.index') }}" method="GET">
                                @csrf
                                <input type="hidden" name="orderBy" value="created_at">
                                <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                <button type="submit" class="w-full text-left">
                                    Created
                                    <i class="fas fa-{{ request('orderBy') == 'created_at' ? (request('direction') == 'asc' ? 'sort-up' : 'sort-down') : 'sort' }}"></i>
                                </button>
                            </form>
                        </th>
                        <th class="header-cell">
                            <form action="{{ route('admin.shops.index') }}" method="GET">
                                @csrf
                                <input type="hidden" name="orderBy" value="shop_owner_id">
                                <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                <button type="submit" class="w-full text-left">
                                    Owner ID
                                    <i class="fas fa-{{ request('orderBy') == 'shop_owner_id' ? (request('direction') == 'asc' ? 'sort-up' : 'sort-down') : 'sort' }}"></i>
                                </button>
                            </form>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($shops as $shop)
                    <tr class="table-row" data-shop-id="{{ $shop->shop_id }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $shop->shop_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium">{{ $shop->shop_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <div class="truncate-text" title="{{ $shop->shop_description }}">
                                {{ $shop->shop_description }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                                {{ $shop->shop_location }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="status-badge {{ $shop->status == 'active' ? 'status-active' : 'status-inactive' }}">
                                {{ $shop->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($shop->created_at)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $shop->shop_owner_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <form action="{{ route('admin.shops.edit', $shop->shop_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-info">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </button>
                                </form>
                                <form action="{{ route('admin.shops.toggleStatus', $shop->shop_id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="{{ $shop->status == 'active' ? 'inactive' : 'active' }}">
                                    <input type="hidden" name="orderBy" value="{{ request('orderBy') }}">
                                    <input type="hidden" name="direction" value="{{ request('direction') }}">

                                    <button type="submit" class="btn {{ $shop->status == 'active' ? 'btn-danger' : 'btn-success' }}">
                                        @if($shop->status == 'active')
                                        <i class="fas fa-ban mr-1"></i> Deactivate
                                        @else
                                        <i class="fas fa-check-circle mr-1"></i> Activate
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .transition-all {
        transition: all 0.3s ease;
    }

    .table-container {
        overflow-x: auto;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

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

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        text-align: center;
        transition: all 0.2s;
        display: inline-block;
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

    .header-cell button {
        background: transparent;
        border: none;
        font-weight: 600;
        color: #111827;
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        cursor: pointer;
    }

    .header-cell button:hover {
        color: #8B9DF9;
    }

    .header-cell button i {
        margin-left: 0.5rem;
        font-size: 0.75rem;
    }

    .table-row:hover {
        background-color: #F9FAFB;
    }

    .truncate-text {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<script>
// This script will be automatically included in the dashboard when loaded
// We need to enhance the dashboard.blade.php to handle shops
$(document).ready(function() {
    // This ensures the shop-specific script is loaded once
    if (window.shopScriptsLoaded) return;
    window.shopScriptsLoaded = true;
    
    // Handle status toggle
    $(document).on('submit', 'form[action*="toggleStatus"]', function(e) {
        // Check if this is a shop toggle (for dashboard shared scripts)
        if (!$(this).closest('tr').data('shop-id')) return;
        
        e.preventDefault();
        
        var $form = $(this);
        var shopId = $form.closest('tr').data('shop-id');
        var newStatus = $form.find('input[name="status"]').val();
        
        // Show loading state
        $form.find('button').prop('disabled', true).css('opacity', '0.7');
        
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Find the shop row
                    var $shopRow = $('tr[data-shop-id="' + shopId + '"]');
                    
                    if ($shopRow.length) {
                        // Update the status badge
                        var $statusCell = $shopRow.find('td:has(.status-badge)');
                        
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
                        
                        // Re-enable the button
                        $form.find('button').prop('disabled', false).css('opacity', '1');
                    } else {
                        console.log("Shop row not found, ID:", shopId);
                        $form.find('button').prop('disabled', false).css('opacity', '1');
                    }
                } else {
                    alert("Failed to update shop status.");
                    $form.find('button').prop('disabled', false).css('opacity', '1');
                }
            },
            error: function(xhr) {
                console.error("Error toggling status:", xhr.responseText);
                $form.find('button').prop('disabled', false).css('opacity', '1');
                alert("Failed to update shop status. Please try again.");
            }
        });
    });
    
    // Handle sort button clicks using event delegation
    $(document).on('click', '.header-cell button', function(e) {
        e.preventDefault();
        
        var form = $(this).closest('form');
        var orderBy = form.find('input[name="orderBy"]').val();
        var direction = form.find('input[name="direction"]').val();
        var search = $('input[name="search"]').val();
        
        $.ajax({
            url: "{{ route('admin.shops.index') }}",
            type: 'GET',
            data: {
                orderBy: orderBy,
                direction: direction,
                search: search
            },
            beforeSend: function() {
                $('#shops-content').css('opacity', '0.5');
            },
            success: function(response) {
                $('#shops-content').html(response).css('opacity', '1');
                
                // Update URL without reloading
                var queryParams = $.param({
                    orderBy: orderBy,
                    direction: direction,
                    search: search
                });
                var newUrl = window.location.pathname + '?' + queryParams;
                history.pushState(null, '', newUrl);
            },
            error: function(xhr) {
                console.error('Error sorting:', xhr.responseText);
                $('#shops-content').css('opacity', '1');
                alert('An error occurred while sorting the data.');
            }
        });
    });
    
    // Handle search form submission
    $(document).on('submit', 'form[action="{{ route("admin.shops.index") }}"]', function(e) {
        // Only if this is the search form (not a sort form)
        if (!$(this).find('input[name="search"]').length) return;
        
        e.preventDefault();
        
        var $form = $(this);
        var search = $form.find('input[name="search"]').val();
        
        $.ajax({
            url: $form.attr('action'),
            type: 'GET',
            data: {
                search: search,
                orderBy: $('input[name="orderBy"]:first').val(),
                direction: $('input[name="direction"]:first').val()
            },
            beforeSend: function() {
                $('#shops-content').css('opacity', '0.5');
            },
            success: function(response) {
                $('#shops-content').html(response).css('opacity', '1');
                
                // Update URL
                var queryParams = 'search=' + encodeURIComponent(search);
                var newUrl = window.location.pathname + '?' + queryParams;
                history.pushState(null, '', newUrl);
            },
            error: function(xhr) {
                console.error('Error searching:', xhr.responseText);
                $('#shops-content').css('opacity', '1');
                alert('An error occurred while searching.');
            }
        });
    });
});
</script>
@endsection