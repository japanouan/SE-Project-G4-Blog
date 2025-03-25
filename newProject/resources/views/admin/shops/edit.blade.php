@extends('layouts.admin-layout')

@section('title', 'Edit Shop')

@section('content')

    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('admin.dashboard') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-lg font-semibold text-sm text-gray-700 uppercase tracking-wide hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-400 transition duration-150">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>
    </div>

    <form method="POST" action="{{ route('admin.shops.update', $shop->shop_id) }}" id="shop-edit-form" class="bg-white p-6 rounded-lg shadow-lg">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="shop_owner_id" class="block text-sm font-medium text-gray-700">Shop Owner ID</label>
            <input id="shop_owner_id" type="text" name="shop_owner_id" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-100 cursor-not-allowed" 
                   value="{{ old('shop_owner_id', $shop->shop_owner_id) }}" required readonly>
            @error('shop_owner_id')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="shop_name" class="block text-sm font-medium text-gray-700">Shop Name</label>
            <input id="shop_name" type="text" name="shop_name" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                   value="{{ old('shop_name', $shop->shop_name) }}" required>
            @error('shop_name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="shop_description" class="block text-sm font-medium text-gray-700">Shop Description</label>
            <textarea id="shop_description" name="shop_description" 
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                      required>{{ old('shop_description', $shop->shop_description) }}</textarea>
            @error('shop_description')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="shop_location" class="block text-sm font-medium text-gray-700">Shop Location</label>
            <input id="shop_location" type="text" name="shop_location" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                   value="{{ old('shop_location', $shop->shop_location) }}" required>
            @error('shop_location')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="rental_terms" class="block text-sm font-medium text-gray-700">Rental Terms</label>
            <textarea id="rental_terms" name="rental_terms" 
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                      required>{{ old('rental_terms', $shop->rental_terms) }}</textarea>
            @error('rental_terms')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select id="statusSelect" name="status" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="active" {{ $shop->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $shop->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <input type="hidden" name="is_ajax" value="1">

        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-lg font-semibold text-sm text-gray-700 uppercase tracking-wide hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-400 transition duration-150">
                <i class="fas fa-times mr-2"></i> Cancel
            </a>

            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wide hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150">
                <i class="fas fa-save mr-2"></i> Update Shop
            </button>
        </div>
    </form>
@endsection
