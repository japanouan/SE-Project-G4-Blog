@php
// Check if we just toggled a user's status
$justToggled = request('just_toggled') == 1;
$toggledUserId = request('toggled_user_id');

// If we just toggled, get the user that was toggled
$toggledUser = null;
if ($justToggled && $toggledUserId) {
    $toggledUser = \App\Models\User::find($toggledUserId);
}
@endphp

@extends('layouts.admin-layout')

@section('title', 'จัดการผู้ใช้งาน')

@section('content')
    <div class="page-container">
        <div class="page-header flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-users mr-2 text-[#8B9DF9]"></i>
                จัดการผู้ใช้งาน
            </h1>

            <form action="{{ route('admin.users.acceptance') }}" method="GET">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check-circle mr-2"></i>รายการอนุมัติ
                </button>
            </form>
        </div>

        <div class="card mt-8">
            <div class="card-header flex items-center">
                <i class="fas fa-filter mr-2 text-[#8B9DF9]"></i>
                <h2 class="card-title text-gray-800 font-semibold">กรองผู้ใช้งาน</h2>
            </div>

            <div class="card-body">
                <form id="filter-form" action="{{ route('admin.users.index') }}" method="GET" class="mb-4">
                    <input type="hidden" name="orderBy" value="{{ request('orderBy') }}">
                    <input type="hidden" name="direction" value="{{ request('direction') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">

                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-2 pl-2">กรองตามบทบาท:</h3>
                        <div class="filters-container">
                            <label class="filter-chip {{ is_array(request('userType')) && in_array('admin', request('userType')) ? 'active' : '' }}" 
                                style="{{ is_array(request('userType')) && in_array('admin', request('userType')) ? 'background-color: #c084fc;' : '' }}">
                                <input type="checkbox" name="userType[]" value="admin" class="filter-checkbox" {{ is_array(request('userType')) && in_array('admin', request('userType')) ? 'checked' : '' }}>
                                <i class="fas fa-user-shield"></i> Admin
                            </label>

                            <label class="filter-chip {{ is_array(request('userType')) && in_array('customer', request('userType')) ? 'active' : '' }}"
                                style="{{ is_array(request('userType')) && in_array('customer', request('userType')) ? 'background-color: #93c5fd;' : '' }}">
                                <input type="checkbox" name="userType[]" value="customer" class="filter-checkbox" {{ is_array(request('userType')) && in_array('customer', request('userType')) ? 'checked' : '' }}>
                                <i class="fas fa-user"></i> Customer
                            </label>

                            <label class="filter-chip {{ is_array(request('userType')) && in_array('shop owner', request('userType')) ? 'active' : '' }}"
                                style="{{ is_array(request('userType')) && in_array('shop owner', request('userType')) ? 'background-color: #86efac;' : '' }}">
                                <input type="checkbox" name="userType[]" value="shop owner" class="filter-checkbox" {{ is_array(request('userType')) && in_array('shop owner', request('userType')) ? 'checked' : '' }}>
                                <i class="fas fa-store"></i> Shop Owner
                            </label>

                            <label class="filter-chip {{ is_array(request('userType')) && in_array('photographer', request('userType')) ? 'active' : '' }}"
                                style="{{ is_array(request('userType')) && in_array('photographer', request('userType')) ? 'background-color: #fde047;' : '' }}">
                                <input type="checkbox" name="userType[]" value="photographer" class="filter-checkbox" {{ is_array(request('userType')) && in_array('photographer', request('userType')) ? 'checked' : '' }}>
                                <i class="fas fa-camera"></i> Photographer
                            </label>

                            <label class="filter-chip {{ is_array(request('userType')) && in_array('make-up artist', request('userType')) ? 'active' : '' }}"
                                style="{{ is_array(request('userType')) && in_array('make-up artist', request('userType')) ? 'background-color: #f9a8d4;' : '' }}">
                                <input type="checkbox" name="userType[]" value="make-up artist" class="filter-checkbox" {{ is_array(request('userType')) && in_array('make-up artist', request('userType')) ? 'checked' : '' }}>
                                <i class="fas fa-paint-brush"></i> Make-up Artist
                            </label>
                        </div>
                    </div>

                </form>

                <!-- Search Bar -->
                <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4 flex p-4" id="search-form">
                    <input type="hidden" id="search-orderBy" name="orderBy" value="{{ request('orderBy') }}">
                    <input type="hidden" id="search-direction" name="direction" value="{{ request('direction') }}">
                    
                    @if(request()->has('userType'))
                        @foreach(request('userType') as $type)
                        <input type="hidden" name="userType[]" value="{{ $type }}" class="search-userType">
                        @endforeach
                    @endif
                    
                    @if(request()->has('status'))
                        @foreach(request('status') as $status)
                        <input type="hidden" name="status[]" value="{{ $status }}" class="search-status">
                        @endforeach
                    @endif
                    
                    <input type="text" name="search" placeholder="ค้นหา User ID, Name, Username, Phone"
                        class="border p-2 w-full rounded-l-md"
                        value="{{ request('search') }}">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r-md">ค้นหา</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header flex items-center">
                <i class="fas fa-table mr-2 text-[#8B9DF9]"></i>
                <h2 class="card-title text-gray-800 font-semibold">User List</h2>
            </div>

            <!-- Improved table styling -->
            <div class="table-container">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 p-3 text-left">
                                <form action="{{ route('admin.users.index') }}" method="GET">
                                    <input type="hidden" name="orderBy" value="user_id">
                                    <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                    @if(request()->has('userType'))
                                    @foreach(request('userType') as $type)
                                    <input type="hidden" name="userType[]" value="{{ $type }}">
                                    @endforeach
                                    @endif
                                    @if(request()->has('status'))
                                    @foreach(request('status') as $status)
                                    <input type="hidden" name="status[]" value="{{ $status }}">
                                    @endforeach
                                    @endif
                                    @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                    @endif
                                    <button type="submit" class="sort-btn font-bold">
                                        ID
                                        <i class="fas fa-{{ request('orderBy') == 'user_id' ? (request('direction') == 'asc' ? 'sort-up' : 'sort-down') : 'sort' }}"></i>
                                    </button>
                                </form>
                            </th>
                            <th class="border border-gray-300 p-3 text-left">
                                <form action="{{ route('admin.users.index') }}" method="GET">
                                    <input type="hidden" name="orderBy" value="name">
                                    <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                    @if(request()->has('userType'))
                                    @foreach(request('userType') as $type)
                                    <input type="hidden" name="userType[]" value="{{ $type }}">
                                    @endforeach
                                    @endif
                                    @if(request()->has('status'))
                                    @foreach(request('status') as $status)
                                    <input type="hidden" name="status[]" value="{{ $status }}">
                                    @endforeach
                                    @endif
                                    @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                    @endif
                                    <button type="submit" class="sort-btn font-bold">
                                        Name
                                        <i class="fas fa-{{ request('orderBy') == 'name' ? (request('direction') == 'asc' ? 'sort-up' : 'sort-down') : 'sort' }}"></i>
                                    </button>
                                </form>
                            </th>
                            <th class="border border-gray-300 p-3 text-left">
                                <form action="{{ route('admin.users.index') }}" method="GET">
                                    <input type="hidden" name="orderBy" value="email">
                                    <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                    @if(request()->has('userType'))
                                    @foreach(request('userType') as $type)
                                    <input type="hidden" name="userType[]" value="{{ $type }}">
                                    @endforeach
                                    @endif
                                    @if(request()->has('status'))
                                    @foreach(request('status') as $status)
                                    <input type="hidden" name="status[]" value="{{ $status }}">
                                    @endforeach
                                    @endif
                                    @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                    @endif
                                    <button type="submit" class="sort-btn font-bold">
                                        Email
                                        <i class="fas fa-{{ request('orderBy') == 'email' ? (request('direction') == 'asc' ? 'sort-up' : 'sort-down') : 'sort' }}"></i>
                                    </button>
                                </form>
                            </th>
                            <th class="border border-gray-300 p-3 text-left">
                                <form action="{{ route('admin.users.index') }}" method="GET">
                                    <input type="hidden" name="orderBy" value="phone">
                                    <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                    @if(request()->has('userType'))
                                    @foreach(request('userType') as $type)
                                    <input type="hidden" name="userType[]" value="{{ $type }}">
                                    @endforeach
                                    @endif
                                    @if(request()->has('status'))
                                    @foreach(request('status') as $status)
                                    <input type="hidden" name="status[]" value="{{ $status }}">
                                    @endforeach
                                    @endif
                                    @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                    @endif
                                    <button type="submit" class="sort-btn font-bold">
                                        Phone
                                        <i class="fas fa-{{ request('orderBy') == 'phone' ? (request('direction') == 'asc' ? 'sort-up' : 'sort-down') : 'sort' }}"></i>
                                    </button>
                                </form>
                            </th>
                            <th class="border border-gray-300 p-3 text-left">
                                <form action="{{ route('admin.users.index') }}" method="GET">
                                    <input type="hidden" name="orderBy" value="username">
                                    <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                    @if(request()->has('userType'))
                                    @foreach(request('userType') as $type)
                                    <input type="hidden" name="userType[]" value="{{ $type }}">
                                    @endforeach
                                    @endif
                                    @if(request()->has('status'))
                                    @foreach(request('status') as $status)
                                    <input type="hidden" name="status[]" value="{{ $status }}">
                                    @endforeach
                                    @endif
                                    @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                    @endif
                                    <button type="submit" class="sort-btn font-bold">
                                        Username
                                        <i class="fas fa-{{ request('orderBy') == 'username' ? (request('direction') == 'asc' ? 'sort-up' : 'sort-down') : 'sort' }}"></i>
                                    </button>
                                </form>
                            </th>
                            <th class="border border-gray-300 p-3 text-left">
                                <form action="{{ route('admin.users.index') }}" method="GET">
                                    <input type="hidden" name="orderBy" value="userType">
                                    <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                    @if(request()->has('userType'))
                                    @foreach(request('userType') as $type)
                                    <input type="hidden" name="userType[]" value="{{ $type }}">
                                    @endforeach
                                    @endif
                                    @if(request()->has('status'))
                                    @foreach(request('status') as $status)
                                    <input type="hidden" name="status[]" value="{{ $status }}">
                                    @endforeach
                                    @endif
                                    @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                    @endif
                                    <button type="submit" class="sort-btn font-bold">
                                        Role
                                        <i class="fas fa-{{ request('orderBy') == 'userType' ? (request('direction') == 'asc' ? 'sort-up' : 'sort-down') : 'sort' }}"></i>
                                    </button>
                                </form>
                            </th>
                            <th class="border border-gray-300 p-3 text-left">
                                <form action="{{ route('admin.users.index') }}" method="GET">
                                    <input type="hidden" name="orderBy" value="status">
                                    <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                    @if(request()->has('userType'))
                                    @foreach(request('userType') as $type)
                                    <input type="hidden" name="userType[]" value="{{ $type }}">
                                    @endforeach
                                    @endif
                                    @if(request()->has('status'))
                                    @foreach(request('status') as $status)
                                    <input type="hidden" name="status[]" value="{{ $status }}">
                                    @endforeach
                                    @endif
                                    @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                    @endif
                                    <button type="submit" class="sort-btn font-bold">
                                        Status
                                        <i class="fas fa-{{ request('orderBy') == 'status' ? (request('direction') == 'asc' ? 'sort-up' : 'sort-down') : 'sort' }}"></i>
                                    </button>
                                </form>
                            </th>
                            <th class="border border-gray-300 p-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 p-3">{{ $user->user_id }}</td>
                            <td class="border border-gray-300 p-3">
                                <div class="user-info flex items-center">
                                    <div class="user-avatar h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                        @if($user->profilePicture)
                                        <img src="{{ asset($user->profilePicture) }}" alt="Profile Picture" class="h-10 w-10 rounded-full object-cover">
                                        @else
                                        <span class="text-gray-700 font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="border border-gray-300 p-3">
                                <div class="flex items-center">
                                    <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                    <span class="text-sm">{{ $user->email }}</span>
                                </div>
                            </td>
                            <td class="border border-gray-300 p-3">
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-gray-400 mr-2"></i>
                                    <span class="text-sm">{{ $user->phone }}</span>
                                </div>
                            </td>
                            <td class="border border-gray-300 p-3">{{ $user->username }}</td>
                            <td class="border border-gray-300 p-3">
                                @php
                                $roleClass = '';
                                $roleIcon = 'user';

                                switch($user->userType) {
                                case 'admin':
                                $roleClass = 'bg-purple-100 text-purple-800';
                                $roleIcon = 'user-shield';
                                break;
                                case 'customer':
                                $roleClass = 'bg-blue-100 text-blue-800';
                                $roleIcon = 'user';
                                break;
                                case 'shop owner':
                                $roleClass = 'bg-green-100 text-green-800';
                                $roleIcon = 'store';
                                break;
                                case 'photographer':
                                $roleClass = 'bg-yellow-100 text-yellow-800';
                                $roleIcon = 'camera';
                                break;
                                case 'make-up artist':
                                $roleClass = 'bg-pink-100 text-pink-800';
                                $roleIcon = 'paint-brush';
                                break;
                                }
                                @endphp

                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $roleClass }}">
                                    <i class="fas fa-{{ $roleIcon }} mr-1"></i>
                                    {{ $user->userType }}
                                </span>
                            </td>
                            <td class="border border-gray-300 p-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $user->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas fa-{{ $user->status == 'active' ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                    {{ $user->status }}
                                </span>
                            </td>
                            <td class="border border-gray-300 p-3">
                                <div class="flex justify-center space-x-2">
                                <a href="{{ route('admin.users.edit', $user->user_id) }}?{{ http_build_query(request()->except(['page'])) }}" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm flex items-center whitespace-nowrap">
                                    <i class="fas fa-edit mr-1"></i> แก้ไข
                                </a>
                                <form action="{{ route('admin.users.toggleStatus', $user->user_id) }}" method="POST" class="inline-block">
        @csrf
        <input type="hidden" name="status" value="{{ $user->status == 'active' ? 'inactive' : 'active' }}">
        <input type="hidden" name="orderBy" value="{{ request('orderBy') }}">
        <input type="hidden" name="direction" value="{{ request('direction') }}">
        
        <!-- Add these two new hidden fields -->
        <input type="hidden" name="just_toggled" value="1">
        <input type="hidden" name="toggled_user_id" value="{{ $user->user_id }}">
        
        @if(request()->has('userType'))
            @foreach(request('userType') as $type)
                <input type="hidden" name="userType[]" value="{{ $type }}">
            @endforeach
        @endif
        
        @if(request()->has('status'))
            @foreach(request('status') as $status)
                <input type="hidden" name="status[]" value="{{ $status }}">
            @endforeach
        @endif
        
        @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
        @endif
        
        <button type="submit" 
                class="{{ $user->status == 'active' ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white px-3 py-1 rounded text-sm flex items-center whitespace-nowrap">
            <i class="fas fa-{{ $user->status == 'active' ? 'ban' : 'check' }} mr-1"></i>
            {{ $user->status == 'active' ? 'ปิดใช้งาน' : 'เปิดใช้งาน' }}
        </button>
    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        @if($justToggled && $toggledUser && !$users->contains('user_id', $toggledUser->user_id))
<tr class="hover:bg-gray-50 bg-yellow-50">
    <td class="border border-gray-300 p-3">{{ $toggledUser->user_id }}</td>
    <td class="border border-gray-300 p-3">
        <div class="user-info flex items-center">
            <div class="user-avatar h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                @if($toggledUser->profilePicture)
                <img src="{{ asset($toggledUser->profilePicture) }}" alt="รูปโปรไฟล์" class="h-10 w-10 rounded-full object-cover">
                @else
                <span class="text-gray-700 font-bold">{{ strtoupper(substr($toggledUser->name, 0, 1)) }}</span>
                @endif
            </div>
            <div>
                <div class="font-medium">{{ $toggledUser->name }}</div>
            </div>
        </div>
    </td>
    <td class="border border-gray-300 p-3">
        <div class="flex items-center">
            <i class="fas fa-envelope text-gray-400 mr-2"></i>
            <span class="text-sm">{{ $toggledUser->email }}</span>
        </div>
    </td>
    <td class="border border-gray-300 p-3">
        <div class="flex items-center">
            <i class="fas fa-phone text-gray-400 mr-2"></i>
            <span class="text-sm">{{ $toggledUser->phone }}</span>
        </div>
    </td>
    <td class="border border-gray-300 p-3">{{ $toggledUser->username }}</td>
    <td class="border border-gray-300 p-3">
        @php
        $roleClass = '';
        $roleIcon = 'user';

        switch($toggledUser->userType) {
        case 'admin':
        $roleClass = 'bg-purple-100 text-purple-800';
        $roleIcon = 'user-shield';
        break;
        case 'customer':
        $roleClass = 'bg-blue-100 text-blue-800';
        $roleIcon = 'user';
        break;
        case 'shop owner':
        $roleClass = 'bg-green-100 text-green-800';
        $roleIcon = 'store';
        break;
        case 'photographer':
        $roleClass = 'bg-yellow-100 text-yellow-800';
        $roleIcon = 'camera';
        break;
        case 'make-up artist':
        $roleClass = 'bg-pink-100 text-pink-800';
        $roleIcon = 'paint-brush';
        break;
        }
        @endphp

        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $roleClass }}">
            <i class="fas fa-{{ $roleIcon }} mr-1"></i>
            {{ $toggledUser->userType }}
        </span>
    </td>
    <td class="border border-gray-300 p-3">
        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $toggledUser->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            <i class="fas fa-{{ $toggledUser->status == 'active' ? 'check-circle' : 'times-circle' }} mr-1"></i>
            {{ $toggledUser->status }}
        </span>
    </td>
    <td class="border border-gray-300 p-3">
        <div class="flex justify-center space-x-2">
        <a href="{{ route('admin.users.edit', $toggledUser->user_id) }}?{{ http_build_query(request()->except(['page', 'just_toggled', 'toggled_user_id'])) }}" 
            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm flex items-center whitespace-nowrap">
            <i class="fas fa-edit mr-1"></i> แก้ไข
        </a>
            <form action="{{ route('admin.users.toggleStatus', $toggledUser->user_id) }}" method="POST" class="inline-block">
                @csrf
                <input type="hidden" name="status" value="{{ $toggledUser->status == 'active' ? 'inactive' : 'active' }}">
                <input type="hidden" name="orderBy" value="{{ request('orderBy') }}">
                <input type="hidden" name="direction" value="{{ request('direction') }}">
                
                <input type="hidden" name="just_toggled" value="1">
                <input type="hidden" name="toggled_user_id" value="{{ $toggledUser->user_id }}">
                
                @if(request()->has('userType'))
                    @foreach(request('userType') as $type)
                        <input type="hidden" name="userType[]" value="{{ $type }}">
                    @endforeach
                @endif
                
                @if(request()->has('status'))
                    @foreach(request('status') as $status)
                        <input type="hidden" name="status[]" value="{{ $status }}">
                    @endforeach
                @endif
                
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                
                <button type="submit" 
                        class="{{ $toggledUser->status == 'active' ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white px-3 py-1 rounded text-sm flex items-center whitespace-nowrap">
                    <i class="fas fa-{{ $toggledUser->status == 'active' ? 'ban' : 'check' }} mr-1"></i>
                    {{ $toggledUser->status == 'active' ? 'ปิดใช้งาน' : 'เปิดใช้งาน' }}
                </button>
            </form>
        </div>
    </td>
</tr>
@endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-4 px-4">
        {{ $users->appends(request()->except('page'))->links() }}
    </div>

    <style>
        /* Additional custom styles */
        .table-container {
            overflow-x: auto;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-radius: 0.5rem;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            position: sticky;
            top: 0;
            background-color: #f3f4f6;
            z-index: 10;
        }
        
        .sort-btn {
            display: flex;
            align-items: center;
            background: transparent;
            border: none;
            cursor: pointer;
            font-weight: 600;
            color: #374151;
        }
        
        .sort-btn i {
            margin-left: 0.5rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
        }
        
        .user-avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            background-color: #e5e7eb;
            overflow: hidden;
        }
        
        .action-btns {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }
        
        .action-btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            border-radius: 0.25rem;
            display: inline-flex;
            align-items: center;
        }
        
        .action-btn i {
            margin-right: 0.25rem;
        }
        
        /* Filter chips styling */
        .filters-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .filter-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 0.75rem;
            background-color: #f3f4f6;
            border-radius: 9999px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .filter-chip input {
            display: none;
        }
        
        .filter-chip i {
            margin-right: 0.5rem;
        }
    </style>

    <script>
// JavaScript for real-time filtering
document.addEventListener('DOMContentLoaded', function() {
    // Get all filter checkboxes
    const filterCheckboxes = document.querySelectorAll('.filter-checkbox');
    
    // Define background colors for each filter type (more vibrant colors)
    const bgColors = {
        'admin': '#c084fc',      // Vibrant purple
        'customer': '#93c5fd',   // Vibrant blue
        'shop owner': '#86efac',  // Vibrant green
        'photographer': '#fde047', // Vibrant yellow
        'make-up artist': '#f9a8d4', // Vibrant pink
        'active': '#86efac',     // Vibrant green
        'inactive': '#fca5a5'    // Vibrant red
    };
    
    // Add click event listener to each filter chip (label)
    document.querySelectorAll('.filter-chip').forEach(label => {
        label.addEventListener('click', function(e) {
            // Prevent default behavior to handle it manually
            e.preventDefault();
            
            // Get the checkbox inside this label
            const checkbox = this.querySelector('input[type="checkbox"]');
            
            // Toggle the checked state
            checkbox.checked = !checkbox.checked;
            
            // Toggle active class on the label
            this.classList.toggle('active', checkbox.checked);
            
            // Apply or remove background color
            if (checkbox.checked) {
                const bgColor = bgColors[checkbox.value];
                if (bgColor) {
                    this.style.backgroundColor = bgColor;
                }
            } else {
                this.style.backgroundColor = '';
            }
            
            // Submit the filter form
            document.getElementById('filter-form').submit();
        });
    });
    
    // Update search form with current filter values when submitting
    document.getElementById('search-form').addEventListener('submit', function() {
        // Remove existing userType hidden inputs
        document.querySelectorAll('.search-userType').forEach(el => el.remove());
        
        // Remove existing status hidden inputs
        document.querySelectorAll('.search-status').forEach(el => el.remove());
        
        // Add current checked filters to search form
        filterCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = checkbox.name;
                input.value = checkbox.value;
                input.className = checkbox.name.includes('userType') ? 'search-userType' : 'search-status';
                this.appendChild(input);
            }
        });
    });
});


    </script>
@endsection

