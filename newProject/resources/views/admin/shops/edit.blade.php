<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('admin.shops.update', $shop->shop_id) }}">
        @csrf
        @method('PUT')  <!-- เพิ่ม @method('PUT') เพื่อให้รองรับการอัปเดตข้อมูล --> 

        <div>
            <x-input-label for="shop_owner_id" :value="__('shop_owner_id')" />
            <x-text-input id="shop_owner_id" class="block mt-1 w-full" type="text" name="shop_owner_id" :value="old('shop_owner_id', $shop->shop_owner_id)" required readonly />
            <x-input-error :messages="$errors->get('shop_owner_id')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="shop_name" :value="__('Shop Name')" />
            <x-text-input id="shop_name" class="block mt-1 w-full" type="text" name="shop_name" :value="old('shop_name', $shop->shop_name)" required autofocus autocomplete="shop_name" />
            <x-input-error :messages="$errors->get('shop_name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="shop_description" :value="__('shop_description')" />
            <x-text-input id="shop_description" class="block mt-1 w-full" type="text" name="shop_description" :value="old('shop_description', $shop->shop_description)" required autocomplete="shop_description" />
            <x-input-error :messages="$errors->get('shop_description')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="shop_location" :value="__('shop_location')" />
            <x-text-input id="shop_location" class="block mt-1 w-full" type="text" name="shop_location" :value="old('shop_location', $shop->shop_location)" required autocomplete="shop_location" />
            <x-input-error :messages="$errors->get('shop_location')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="rental_terms" :value="__('rental_terms')" />
            <x-text-input id="rental_terms" class="block mt-1 w-full" type="text" name="rental_terms" :value="old('rental_terms', $shop->rental_terms)" required />
            <x-input-error :messages="$errors->get('rental_terms')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="depositfee" :value="__('depositfee')" />
            <x-text-input id="depositfee" class="block mt-1 w-full" type="nunber" name="depositfee" :value="old('depositfee', $shop->depositfee)" required  />
            <x-input-error :messages="$errors->get('depositfee')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="penaltyfee" :value="__('penaltyfee')" />
            <x-text-input id="penaltyfee" class="block mt-1 w-full" type="nunber" name="penaltyfee" :value="old('penaltyfee', $shop->penaltyfee)" required  />
            <x-input-error :messages="$errors->get('penaltyfee')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="status" :value="__('Status')" />
            <select id="statusSelect" name="status" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="active" {{ $shop->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $shop->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-3">
                {{ __('Update shop') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
