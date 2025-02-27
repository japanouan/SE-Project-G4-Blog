<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('แก้ไขข้อมูลร้านค้า') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('shopowner.shops.update-my-shop', $shop->shop_id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="shop_name" class="block text-gray-700">ชื่อร้านค้า</label>
                            <input type="text" name="shop_name" id="shop_name" 
                                  value="{{ old('shop_name', $shop->shop_name) }}"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                            </div>

                                            <div class="mb-4">
                                                <label for="shop_description" class="block text-gray-700">คำอธิบายร้านค้า</label>
                                                <textarea name="shop_description" id="shop_description" rows="4" 
                                                         class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('shop_description', $shop->shop_description) }}</textarea>
                                            </div>

                                            <div class="mb-4">
                                                <label for="shop_location" class="block text-gray-700">ที่ตั้งร้านค้า</label>
                                                <textarea name="shop_location" id="shop_location" rows="3" 
                                                         class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('shop_location', $shop->shop_location) }}</textarea>
                                            </div>

                                            <div class="mb-4">
                                                <label for="rental_terms" class="block text-gray-700">เงื่อนไขการเช่า</label>
                                                <textarea name="rental_terms" id="rental_terms" rows="4" 
                                                         class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('rental_terms', $shop->rental_terms) }}</textarea>
                                            </div>

                                            <div class="mb-4">
                                                <label for="depositfee" class="block text-gray-700">ค่ามัดจำ (บาท)</label>
                                                <input type="number" name="depositfee" id="depositfee" min="0" step="0.01" 
                                                      value="{{ old('depositfee', $shop->depositfee) }}"
                                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                            </div>

                                            <div class="mb-4">
                                                <label for="penaltyfee" class="block text-gray-700">ค่าปรับ (บาท)</label>
                                                <input type="number" name="penaltyfee" id="penaltyfee" min="0" step="0.01" 
                                                      value="{{ old('penaltyfee', $shop->penaltyfee) }}"
                                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                            </div>

                                            <div class="mt-6 flex space-x-4">
                                                <button type="submit" class="px-4 py-2 bg-blue-600 text-black rounded-md hover:bg-blue-700">
                                                    บันทึกการเปลี่ยนแปลง
                                                </button>
                                                <a href="{{ route('shopowner.shops.my-shop') }}" class="px-4 py-2 bg-gray-500 text-black rounded-md hover:bg-gray-600">
                                                    ยกเลิก
                                                </a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
</x-app-layout>