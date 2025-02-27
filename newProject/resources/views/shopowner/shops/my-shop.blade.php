<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ร้านค้าของฉัน') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(!$shop)
                        <p class="mb-4">คุณยังไม่มีร้านค้า</p>
                        <a href="{{ route('shopowner.shops.create') }}" 
                           class="px-4 py-2 bg-blue-600 text-black rounded-md hover:bg-blue-700">
                           ลงทะเบียนร้านค้าใหม่
                        </a>
                    @else
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold mb-2">ข้อมูลร้านค้า</h3>
                            <p><strong>สถานะ:</strong> 
                                @if($shop->status == 'active')
                                    <span class="text-green-600">ใช้งานได้</span>
                                @else
                                    <span class="text-red-600">ยังไม่ได้รับการอนุมัติ</span>
                                @endif
                            </p>
                            <p><strong>ชื่อร้าน:</strong> {{ $shop->shop_name }}</p>
                            <p><strong>คำอธิบาย:</strong> {{ $shop->shop_description }}</p>
                            <p><strong>ที่ตั้ง:</strong> {{ $shop->shop_location }}</p>
                            <p><strong>เงื่อนไขการเช่า:</strong> {{ $shop->rental_terms }}</p>
                            <p><strong>ค่ามัดจำ:</strong> {{ number_format($shop->depositfee, 2) }} บาท</p>
                            <p><strong>ค่าปรับ:</strong> {{ number_format($shop->penaltyfee, 2) }} บาท</p>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('shopowner.shops.edit-my-shop', $shop->shop_id) }}" 
                               class="px-4 py-2 bg-yellow-500 text-black rounded-md hover:bg-yellow-600">
                               แก้ไขข้อมูลร้านค้า
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
