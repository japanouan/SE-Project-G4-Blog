@extends('layouts.main')

@section('title', 'Profile')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <!-- Profile Header with Name and Edit Button -->
            <div class="profile-header flex items-center justify-between">
                <div class="flex items-center">
                    <!-- Profile Pic -->
                    <img src="{{ $user->profilePicture ? asset($user->profilePicture) : asset('images/default-profile.png') }}" 
                         alt="Profile Picture" 
                         class="profile-picture rounded-full h-32 w-32 object-cover mr-6">
                    <div>
                        <h3 class="text-xl font-bold">{{ $user->name }}</h3>
                    </div>
                </div>
                <a href="{{ route('profile.editCus') }}" class="text-blue-500 hover:text-blue-700">Edit Profile</a>
            </div>

            <!-- Profile Information -->
            <div class="mt-6">
                <h3 class="font-medium text-gray-700">Personal Information</h3>
                <div class="space-y-4 mt-4">
                    <p><strong>Name:</strong> {{ $user->name }}</p>
                    <p><strong>Email account:</strong> {{ $user->email }}</p>
                    <p><strong>Mobile number:</strong> {{ $user->phone ?? 'N/A' }}</p>
                    <p><strong>Gender:</strong> {{ $user->gender ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
