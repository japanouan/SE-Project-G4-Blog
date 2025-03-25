@extends('layouts.admin-layout')

@section('title', 'User Management')

@section('content')
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
    <div class="mt-4">
    {{ $users->links() }}
    </div>
@endsection