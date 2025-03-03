@extends('layouts.main')

@section('title', 'Edit Profile')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
                {{ __('Edit Profile') }}
            </h2>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <!-- Profile Picture (Optional) -->
                <div class="mt-4">
                    <label for="profilePicture" class="block font-medium text-sm text-gray-700">
                        Profile Picture
                    </label>
                    <input id="profilePicture" name="profilePicture" type="file" class="mt-1 block w-full">
                    @error('profilePicture') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Name -->
                <div class="mt-4">
                    <label for="name" class="block font-medium text-sm text-gray-700">
                        Name
                    </label>
                    <input id="name" name="name" type="text" class="mt-1 block w-full border rounded-md p-2" 
                           value="{{ old('name', $user->name) }}" required autofocus>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div class="mt-4">
                    <label for="email" class="block font-medium text-sm text-gray-700">
                        Email
                    </label>
                    <input id="email" name="email" type="email" class="mt-1 block w-full border rounded-md p-2" 
                           value="{{ old('email', $user->email) }}" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label for="password" class="block font-medium text-sm text-gray-700">
                        New Password (Leave blank if not changing)
                    </label>
                    <input id="password" name="password" type="password" class="mt-1 block w-full border rounded-md p-2" 
                           autocomplete="new-password">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <label for="password_confirmation" class="block font-medium text-sm text-gray-700">
                        Confirm New Password
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full border rounded-md p-2" 
                           autocomplete="new-password">
                    @error('password_confirmation') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Mobile Number -->
                <div class="mt-4">
                    <label for="phone" class="block font-medium text-sm text-gray-700">
                        Mobile Number
                    </label>
                    <input id="phone" name="phone" type="text" class="mt-1 block w-full border rounded-md p-2" 
                           value="{{ old('phone', $user->phone) }}">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Gender -->
                <div class="mt-4">
                    <label for="gender" class="block font-medium text-sm text-gray-700">
                        Gender
                    </label>
                    <div class="flex items-center space-x-4">
                        <label>
                            <input type="radio" name="gender" value="male" 
                                   {{ old('gender', $user->gender) == 'male' ? 'checked' : '' }}>
                            Male
                        </label>
                        <label>
                            <input type="radio" name="gender" value="female" 
                                   {{ old('gender', $user->gender) == 'female' ? 'checked' : '' }}>
                            Female
                        </label>
                        <label>
                            <input type="radio" name="gender" value="others" 
                                   {{ old('gender', $user->gender) == 'others' ? 'checked' : '' }}>
                            Others
                        </label>
                    </div>
                    @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Save Button -->
                <div class="flex items-center justify-end mt-4">
                    <button type="submit" class="px-6 py-2 border border-blue-500 text-blue-500 rounded-md 
                                hover:bg-blue-500 hover:text-white transition">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
