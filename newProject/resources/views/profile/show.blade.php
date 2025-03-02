
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>
        
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Profile Header with Name and Edit Button -->
                <div class="profile-header flex items-center justify-between">
                    <div class="flex items-center">
                        <!-- Profile Pic -->
                        <img src="{{ asset($user->profilePicture) }}" alt="Profile Picture" class="profile-picture rounded-full h-32 w-32 object-cover mr-6">
                        <div>
                            <h3 class="text-xl font-bold">{{ $user->name }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="edit-profile-button text-blue-500 hover:text-blue-700">Edit Profile</a>
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

</body>
</html>
