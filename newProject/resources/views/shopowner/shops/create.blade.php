@extends('layouts.shopowner-layout')

@section('title', 'ลงทะเบียนร้านค้าใหม่')

@section('content')
<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-6">ลงทะเบียนร้านค้าใหม่</h2>

    <div class="bg-white p-6 shadow rounded-lg">
        <form method="POST" action="{{ route('shopowner.shops.store') }}" class="max-w-4xl mx-auto">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="mb-4">
                    <label for="shop_name" class="block text-gray-700 font-medium mb-2">ชื่อร้านค้า</label>
                    <input type="text" name="shop_name" id="shop_name" 
                           class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           required>
                </div>

                <div class="mb-4">
                    <label for="shop_location" class="block text-gray-700 font-medium mb-2">ที่ตั้งร้านค้า</label>
                    <textarea name="shop_location" id="shop_location" rows="3" 
                              class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                              required></textarea>
                </div>
            </div>

            <div class="mb-4">
                <label for="shop_description" class="block text-gray-700 font-medium mb-2">คำอธิบายร้านค้า</label>
                <textarea name="shop_description" id="shop_description" rows="4" 
                          class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                          required></textarea>
            </div>

            <div class="mb-4">
                <label for="rental_terms" class="block text-gray-700 font-medium mb-2">เงื่อนไขการเช่า</label>
                <textarea name="rental_terms" id="rental_terms" rows="4" 
                          class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                          required></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="mb-4">
                    <label for="depositfee" class="block text-gray-700 font-medium mb-2">ค่ามัดจำ (บาท)</label>
                        <input type="number" name="depositfee" id="depositfee" min="0" step="0.01" 
                                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                required>
                </div>

                                <div class="mb-4">
                                    <label for="penaltyfee" class="block text-gray-700 font-medium mb-2">ค่าปรับ (บาท)</label>
                                    <input type="number" name="penaltyfee" id="penaltyfee" min="0" step="0.01" 
                                           class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                           required>
                                </div>
                            </div>

                            <div class="mt-6 flex items-center gap-4">
                                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                                    <i class="fa fa-save mr-2"></i> ลงทะเบียนร้านค้า
                                </button>
                                <a href="{{ route('shopowner.shops.my-shop') }}" class="px-6 py-3 bg-gray-500 text-white rounded-md hover:bg-gray-600 font-medium">
                                    <i class="fa fa-times mr-2"></i> ยกเลิก
                                </a>
                            </div>
                        </form>
                    </div>
</div>
@endsection