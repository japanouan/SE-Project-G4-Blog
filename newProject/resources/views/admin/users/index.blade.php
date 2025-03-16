<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management | ThaiWijit</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-color: #f9fafb;
            font-family: 'Inter', sans-serif;
            color: #374151;
        }

        .page-container {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            display: flex;
            align-items: center;
        }

        .page-title i {
            color: #8B9DF9;
            margin-right: 0.75rem;
        }

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

        .filters-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .filter-chip {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            background-color: #f3f4f6;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            user-select: none;
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

        .btn-outline {
            background-color: transparent;
            border: 1px solid #d1d5db;
            color: #4b5563;
        }

        .btn-outline:hover {
            background-color: #f3f4f6;
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

        .btn i {
            margin-right: 0.375rem;
        }

        /* Updated table container with better overflow handling */
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
            /* Ensure table has a minimum width */
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
            /* Prevent wrapping in header */
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

        /* Updated to prevent word wrapping in action buttons */
        td:last-child {
            white-space: nowrap;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
            /* Prevent badge text from wrapping */
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

        .badge-role-photographer {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-role-makeup {
            background-color: #fce7f3;
            color: #9d174d;
        }

        .sort-btn {
            background: transparent;
            border: none;
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            cursor: pointer;
            padding: 0;
            white-space: nowrap;
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
            /* Keep buttons on same line */
        }

        .action-btn {
            padding: 0.35rem 0.5rem;
            font-size: 0.7rem;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-avatar {
            width: 2.5rem;

            .table-container {
                width: 100%;
                max-width: 100%;
                margin: 0 auto;
                overflow-x: visible;
            }

            table {
                width: 100%;
                table-layout: auto;
                border-collapse: separate;
                border-spacing: 0;
                min-width: auto;
            }

            thead th:nth-child(1) {
                width: 5%;
            }

            thead th:nth-child(2) {
                width: 15%;
            }

            thead th:nth-child(3) {
                width: 15%;
            }

            thead th:nth-child(4) {
                width: 10%;
            }

            thead th:nth-child(5) {
                width: 10%;
            }

            thead th:nth-child(6) {
                width: 10%;
            }

            thead th:nth-child(7) {
                width: 10%;
            }

            thead th:nth-child(8) {
                width: 15%;
            }

            tbody td {
                padding: 0.75rem 0.5rem;
            }

            .page-container {
                padding: 1.5rem;

                /* Add this to your style section */
                @media (max-width: 1200px) {
                    .table-container {
                        overflow-x: auto;
                        /* Re-enable scrolling for smaller screens */
                    }

                    table {
                        min-width: 1000px;
                        /* Set minimum width for small screens */
                    }
                }
            }

            height: 2.5rem;
            border-radius: 9999px;
            background-color: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            font-weight: 600;
            color: #4b5563;
            flex-shrink: 0;
        }
    </style>
</head>

<body>
    <div class="page-container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-users"></i>
                User Management
            </h1>

            <form action="{{ route('admin.users.acceptance') }}" method="GET">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check-circle mr-2"></i>Acceptance Queue
                </button>
            </form>
        </div>

        <th>
            <form action="{{ route('admin.users.index') }}" method="GET">
                <input type="hidden" name="orderBy" value="user_id">
                <input type="hidden" name="direction" value="{{ request('orderBy') == 'user_id' && request('direction') == 'asc' ? 'desc' : 'asc' }}">
                @if(request()->has('userType'))
                @foreach(request('userType') as $type)
                <input type="hidden" name="userType[]" value="{{ $type }}">
                @endforeach
                @endif
                <button type="submit" class="sort-btn">
                    ID
                    <i class="fas fa-{{ request('orderBy') == 'user_id' ? (request('direction') == 'asc' ? 'sort-up' : 'sort-down') : 'sort' }}"></i>
                </button>
            </form>
        </th>
        <div class="card">
            <div class="card-header">
                <i class="fas fa-filter card-header-icon"></i>
                <h2 class="card-title">Filter Users</h2>
            </div>

            <div class="card-body">
                <form id="filter-form" action="javascript:void(0);" class="mb-4">
                    <input type="hidden" name="orderBy" value="{{ request('orderBy') }}">
                    <input type="hidden" name="direction" value="{{ request('direction') }}">

                    <div class="filters-container">
                        <label class="filter-chip {{ is_array(request('userType')) && in_array('admin', request('userType')) ? 'active' : '' }}">
                            <input type="checkbox" name="userType[]" value="admin" {{ is_array(request('userType')) && in_array('admin', request('userType')) ? 'checked' : '' }}>
                            <i class="fas fa-user-shield"></i> Admin
                        </label>

                        <label class="filter-chip {{ is_array(request('userType')) && in_array('customer', request('userType')) ? 'active' : '' }}">
                            <input type="checkbox" name="userType[]" value="customer" {{ is_array(request('userType')) && in_array('customer', request('userType')) ? 'checked' : '' }}>
                            <i class="fas fa-user"></i> Customer
                        </label>

                        <label class="filter-chip {{ is_array(request('userType')) && in_array('shop owner', request('userType')) ? 'active' : '' }}">
                            <input type="checkbox" name="userType[]" value="shop owner" {{ is_array(request('userType')) && in_array('shop owner', request('userType')) ? 'checked' : '' }}>
                            <i class="fas fa-store"></i> Shop Owner
                        </label>

                        <label class="filter-chip {{ is_array(request('userType')) && in_array('photographer', request('userType')) ? 'active' : '' }}">
                            <input type="checkbox" name="userType[]" value="photographer" {{ is_array(request('userType')) && in_array('photographer', request('userType')) ? 'checked' : '' }}>
                            <i class="fas fa-camera"></i> Photographer
                        </label>

                        <label class="filter-chip {{ is_array(request('userType')) && in_array('make-up artist', request('userType')) ? 'active' : '' }}">
                            <input type="checkbox" name="userType[]" value="make-up artist" {{ is_array(request('userType')) && in_array('make-up artist', request('userType')) ? 'checked' : '' }}>
                            <i class="fas fa-paint-brush"></i> Make-up Artist
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Apply Filter
                    </button>
                </form>

                <!-- Search Bar -->
                <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4 flex p-4">
                    <input type="text" name="search" placeholder="ค้นหา User ID, Name, Username, Phone"
                        class="border p-2 w-full rounded-l-md"
                        value="{{ request('search') }}">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r-md">ค้นหา</button>
                </form>
            </div>
        </div>



        <div class="card">
            <div class="card-header">
                <i class="fas fa-table card-header-icon"></i>
                <h2 class="card-title">User List</h2>
            </div>

            <!-- Fixed overflow handling for table -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>
                                <form action="{{ route('admin.users.index') }}" method="GET">
                                    <input type="hidden" name="orderBy" value="user_id">
                                    <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                    @if(request()->has('userType'))
                                    @foreach(request('userType') as $type)
                                    <input type="hidden" name="userType[]" value="{{ $type }}">
                                    @endforeach
                                    @endif
                                    <button type="submit" class="sort-btn">
                                        ID
                                        <i class="fas fa-{{ request('orderBy') == 'user_id' ? (request('direction') == 'asc' ? 'sort-up' : 'sort-down') : 'sort' }}"></i>
                                    </button>
                                </form>
                            </th>
                            <th>
                                <form action="{{ route('admin.users.index') }}" method="GET">
                                    <input type="hidden" name="orderBy" value="name">
                                    <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                    @if(request()->has('userType'))
                                    @foreach(request('userType') as $type)
                                    <input type="hidden" name="userType[]" value="{{ $type }}">
                                    @endforeach
                                    @endif
                                    <button type="submit" class="sort-btn">
                                        Name
                                        <i class="fas fa-{{ request('orderBy') == 'name' ? (request('direction') == 'asc' ? 'sort-up' : 'sort-down') : 'sort' }}"></i>
                                    </button>
                                </form>
                            </th>
                            <th>
                                <form action="{{ route('admin.users.index') }}" method="GET">
                                    <input type="hidden" name="orderBy" value="email">
                                    <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                    @if(request()->has('userType'))
                                    @foreach(request('userType') as $type)
                                    <input type="hidden" name="userType[]" value="{{ $type }}">
                                    @endforeach
                                    @endif
                                    <button type="submit" class="sort-btn">
                                        Email
                                        <i class="fas fa-{{ request('orderBy') == 'email' ? (request('direction') == 'asc' ? 'sort-up' : 'sort-down') : 'sort' }}"></i>
                                    </button>
                                </form>
                            </th>
                            <th>
                                <form action="{{ route('admin.users.index') }}" method="GET">
                                    <input type="hidden" name="orderBy" value="phone">
                                    <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                    @if(request()->has('userType'))
                                    @foreach(request('userType') as $type)
                                    <input type="hidden" name="userType[]" value="{{ $type }}">
                                    @endforeach
                                    @endif
                                    <button type="submit" class="sort-btn">
                                        Phone
                                        <i class="fas fa-{{ request('orderBy') == 'phone' ? (request('direction') == 'asc' ? 'sort-up' : 'sort-down') : 'sort' }}"></i>
                                    </button>
                                </form>
                            </th>
                            <th>
                                <form action="{{ route('admin.users.index') }}" method="GET">
                                    <input type="hidden" name="orderBy" value="username">
                                    <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                    @if(request()->has('userType'))
                                    @foreach(request('userType') as $type)
                                    <input type="hidden" name="userType[]" value="{{ $type }}">
                                    @endforeach
                                    @endif
                                    <button type="submit" class="sort-btn">
                                        Username
                                        <i class="fas fa-{{ request('orderBy') == 'username' ? (request('direction') == 'asc' ? 'sort-up' : 'sort-down') : 'sort' }}"></i>
                                    </button>
                                </form>
                            </th>
                            <th>
                                <form action="{{ route('admin.users.index') }}" method="GET">
                                    <input type="hidden" name="orderBy" value="userType">
                                    <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                    @if(request()->has('userType'))
                                    @foreach(request('userType') as $type)
                                    <input type="hidden" name="userType[]" value="{{ $type }}">
                                    @endforeach
                                    @endif
                                    <button type="submit" class="sort-btn">
                                        Role
                                        <i class="fas fa-{{ request('orderBy') == 'userType' ? (request('direction') == 'asc' ? 'sort-up' : 'sort-down') : 'sort' }}"></i>
                                    </button>
                                </form>
                            </th>
                            <th>
                                <form action="{{ route('admin.users.index') }}" method="GET">
                                    <input type="hidden" name="orderBy" value="status">
                                    <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                    @if(request()->has('userType'))
                                    @foreach(request('userType') as $type)
                                    <input type="hidden" name="userType[]" value="{{ $type }}">
                                    @endforeach
                                    @endif
                                    <button type="submit" class="sort-btn">
                                        Status
                                        <i class="fas fa-{{ request('orderBy') == 'status' ? (request('direction') == 'asc' ? 'sort-up' : 'sort-down') : 'sort' }}"></i>
                                    </button>
                                </form>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->user_id }}</td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">
                                        @if($user->profilePicture)
                                        <img src="{{ asset($user->profilePicture) }}" alt="Profile Picture" class="profile-picture rounded-full h-10 w-auto object-cover">
                                        @else
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                    {{ $user->email }}
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-gray-400 mr-2"></i>
                                    {{ $user->phone }}
                                </div>
                            </td>
                            <td>{{ $user->username }}</td>
                            <td>
                                @php
                                $roleClass = '';
                                $roleIcon = 'user';

                                switch($user->userType) {
                                case 'admin':
                                $roleClass = 'badge-role-admin';
                                $roleIcon = 'user-shield';
                                break;
                                case 'customer':
                                $roleClass = 'badge-role-customer';
                                $roleIcon = 'user';
                                break;
                                case 'shop owner':
                                $roleClass = 'badge-role-shopowner';
                                $roleIcon = 'store';
                                break;
                                case 'photographer':
                                $roleClass = 'badge-role-photographer';
                                $roleIcon = 'camera';
                                break;
                                case 'make-up artist':
                                $roleClass = 'badge-role-makeup';
                                $roleIcon = 'paint-brush';
                                break;
                                }
                                @endphp

                                <span class="badge {{ $roleClass }}">
                                    <i class="fas fa-{{ $roleIcon }} mr-1"></i>
                                    {{ $user->userType }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $user->status == 'active' ? 'badge-status-active' : 'badge-status-inactive' }}">
                                    <i class="fas fa-{{ $user->status == 'active' ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                    {{ $user->status }}
                                </span>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a href="{{ route('admin.users.edit', $user->user_id) }}" class="btn btn-info action-btn">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.users.toggleStatus', $user->user_id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="{{ $user->status == 'active' ? 'inactive' : 'active' }}">
                                        <input type="hidden" name="orderBy" value="{{ request('orderBy') }}">
                                        <input type="hidden" name="direction" value="{{ request('direction') }}">
                                        @if(request()->has('userType'))
                                        @foreach(request('userType') as $type)
                                        <input type="hidden" name="userType[]" value="{{ $type }}">
                                        @endforeach
                                        @endif
                                        <button type="submit" class="btn {{ $user->status == 'active' ? 'btn-danger' : 'btn-success' }} action-btn">
                                            <i class="fas fa-{{ $user->status == 'active' ? 'ban' : 'check' }}"></i>
                                            {{ $user->status == 'active' ? 'Deactivate' : 'Activate' }}
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
</body>

</html>