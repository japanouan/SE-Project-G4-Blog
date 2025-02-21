<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Profile Picture (Optional) -->
                    <div class="mt-4">
                        <x-input-label for="profile_picture" :value="__('Profile Picture')" />
                        <x-text-input id="profile_picture" name="profile_picture" type="file" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('profile_picture')" class="mt-2" />
                    </div>

                    <!-- Name -->
                    <div class="mt-4">
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Mobile Number -->
                    <div class="mt-4">
                        <x-input-label for="phone" :value="__('Mobile Number')" />
                        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <!-- Gender -->
                    <div class="mt-4">
                        <x-input-label for="gender" :value="__('Gender')" />
                        <div class="flex items-center">
                            <label for="gender_male" class="mr-4">
                                <input type="radio" name="gender" value="male" id="gender_male" {{ old('gender', $user->gender) == 'male' ? 'checked' : '' }} />
                                Male 
                            </label>
                            <label for="gender_female" class="mr-4">
                                <input type="radio" name="gender" value="female" id="gender_female" {{ old('gender', $user->gender) == 'female' ? 'checked' : '' }} />
                                Female 
                            </label>
                            <label for="gender_others">
                                <input type="radio" name="gender" value="others" id="gender_others" {{ old('gender', $user->gender) == 'others' ? 'checked' : '' }} />
                                Others 
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                    </div>

                    <!-- Save Button -->
                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button>{{ __('Save') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- อัปโหลดรูปโปรไฟล์ -->
    <div class="mt-4">
        <x-input-label for="profile_picture" :value="__('Profile Picture')" />
        <x-text-input id="profile_picture" name="profile_picture" type="file" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('profile_picture')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">
        <x-primary-button>{{ __('Save') }}</x-primary-button>
    </div>
</form>
