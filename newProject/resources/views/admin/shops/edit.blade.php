<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('admin.dashboard') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>
    </div>

    <form method="POST" action="{{ route('admin.shops.update', $shop->shop_id) }}" id="shop-edit-form">
        @csrf
        @method('PUT')

        <div>
            <x-input-label for="shop_owner_id" :value="__('Shop Owner ID')" />
            <x-text-input id="shop_owner_id" class="block mt-1 w-full" type="text" name="shop_owner_id" :value="old('shop_owner_id', $shop->shop_owner_id)" required readonly />
            <x-input-error :messages="$errors->get('shop_owner_id')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="shop_name" :value="__('Shop Name')" />
            <x-text-input id="shop_name" class="block mt-1 w-full" type="text" name="shop_name" :value="old('shop_name', $shop->shop_name)" required autofocus autocomplete="shop_name" />
            <x-input-error :messages="$errors->get('shop_name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="shop_description" :value="__('Shop Description')" />
            <textarea id="shop_description" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" name="shop_description" required autocomplete="shop_description">{{ old('shop_description', $shop->shop_description) }}</textarea>
            <x-input-error :messages="$errors->get('shop_description')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="shop_location" :value="__('Shop Location')" />
            <x-text-input id="shop_location" class="block mt-1 w-full" type="text" name="shop_location" :value="old('shop_location', $shop->shop_location)" required autocomplete="shop_location" />
            <x-input-error :messages="$errors->get('shop_location')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="rental_terms" :value="__('Rental Terms')" />
            <textarea id="rental_terms" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" name="rental_terms" required>{{ old('rental_terms', $shop->rental_terms) }}</textarea>
            <x-input-error :messages="$errors->get('rental_terms')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="status" :value="__('Status')" />
            <select id="statusSelect" name="status" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="active" {{ $shop->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $shop->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>

        <input type="hidden" name="is_ajax" value="1">

        <div class="flex items-center justify-between mt-4">
            <a href="{{ route('admin.dashboard') }}"
               class="cancel-edit inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <i class="fas fa-times mr-2"></i> Cancel
            </a>
            
            <x-primary-button class="ms-3">
                {{ __('Update Shop') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
