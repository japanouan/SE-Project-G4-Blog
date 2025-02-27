<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ลงทะเบียนร้านค้าใหม่') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('shopowner.shops.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="shop_name" class="block text-gray-700">ชื่อร้านค้า</label>
                            <input type="text" name="shop_name" id="shop_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <div class="mb-4">
                            <label for="shop_description" class="block text-gray-700">คำอธิบายร้านค้า</label>
                            <textarea name="shop_description" id="shop_description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="shop_location" class="block text-gray-700">ที่ตั้งร้านค้า</label>
                            <textarea name="shop_location" id="shop_location" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="rental_terms" class="block text-gray-700">เงื่อนไขการเช่า</label>
                            <textarea name="rental_terms" id="rental_terms" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="depositfee" class="block text-gray-700">ค่ามัดจำ (บาท)</label>
                            <input type="number" name="depositfee" id="depositfee" min="0" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <div class="mb-4">
                            <label for="penaltyfee" class="block text-gray-700">ค่าปรับ (บาท)</label>
                            <input type="number" name="penaltyfee" id="penaltyfee" min="0" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-black rounded-md hover:bg-blue-700">
                                ลงทะเบียนร้านค้า
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
