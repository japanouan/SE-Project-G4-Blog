@extends('layouts.admin-layout')

@section('title', 'Edit User')

@section('content')
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-lg font-semibold text-sm text-gray-700 uppercase tracking-wide hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-400 transition duration-150">
            <i class="fas fa-arrow-left mr-2"></i> Back to Users
        </a>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user->user_id) }}" id="user-edit-form" class="bg-white p-6 rounded-lg shadow-lg">
        @csrf
        @method('PUT')

        <!-- Include all original search parameters -->
        @foreach(request()->except(['_token', '_method']) as $key => $value)
            @if(is_array($value))
                @foreach($value as $item)
                    <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                @endforeach
            @else
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
        @endforeach

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Fullname</label>
            <input id="name" type="text" name="name" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                   value="{{ old('name', $user->name) }}" required autofocus>
            @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" type="email" name="email" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                   value="{{ old('email', $user->email) }}" required>
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
            <input id="phone" type="text" name="phone" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                   value="{{ old('phone', $user->phone) }}" required>
            @error('phone')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
            <input id="username" type="text" name="username" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-100 cursor-not-allowed" 
                   value="{{ old('username', $user->username) }}" readonly>
            @error('username')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="userType" class="block text-sm font-medium text-gray-700">Staff Role</label>
            <select id="userTypeSelect" name="userType" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="make-up artist" {{ $user->userType == 'make-up artist' ? 'selected' : '' }}>Make-Up Artist</option>
                <option value="photographer" {{ $user->userType == 'photographer' ? 'selected' : '' }}>Photographer</option>
                <option value="admin" {{ $user->userType == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="shop owner" {{ $user->userType == 'shop owner' ? 'selected' : '' }}>Shop Owner</option>
            </select>
            @error('userType')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select id="statusSelect" name="status" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('admin.users.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-lg font-semibold text-sm text-gray-700 uppercase tracking-wide hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-400 transition duration-150">
                <i class="fas fa-times mr-2"></i> Cancel
            </a>

            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wide hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150">
                <i class="fas fa-save mr-2"></i> Update User
            </button>
        </div>
    </form>
@endsection
