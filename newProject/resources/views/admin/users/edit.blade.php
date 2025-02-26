<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('admin.users.update', $user->user_id) }}">
        @csrf
        @method('PUT')  <!-- เพิ่ม @method('PUT') เพื่อให้รองรับการอัปเดตข้อมูล --> 

        <div>
            <x-input-label for="name" :value="__('Fullname')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="phone" :value="__('Phone')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $user->phone)" required autocomplete="phone" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username', $user->username)" required readonly />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="userType" :value="__('Staff Role')" />
            <select id="userTypeSelect" name="userType" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="make-up artist" {{ $user->userType == 'make-up artist' ? 'selected' : '' }}>Make-Up Artist</option>
                <option value="photographer" {{ $user->userType == 'photographer' ? 'selected' : '' }}>Photographer</option>
                <option value="admin" {{ $user->userType == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="shop owner" {{ $user->userType == 'shop owner' ? 'selected' : '' }}>Shop Owner</option>
            </select>
            <x-input-error :messages="$errors->get('userType')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="status" :value="__('Status')" />
            <select id="statusSelect" name="status" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-3">
                {{ __('Update User') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
