@extends('layouts.shopowner-layout')

@section('title', 'เสนอชุดทดแทน')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('shopowner.bookings.insufficient-stock') }}" class="text-blue-600 hover:text-blue-800">
                <i class="fa fa-arrow-left mr-2"></i>กลับไปยังรายการจองที่มีชุดไม่เพียงพอ
            </a>
            <h2 class="text-2xl font-bold mt-2">เสนอชุดทดแทน</h2>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Original Outfit Card -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 border-b">
                <h3 class="text-lg font-semibold">ชุดที่ลูกค้าต้องการ (จำนวนไม่พอ)</h3>
            </div>
            <div class="p-4">
                <div class="flex mb-4">
                    @if($originalOutfit->image)
                        <img src="{{ asset($originalOutfit->image) }}" alt="{{ $originalOutfit->name }}" class="h-40 w-40 object-cover rounded mr-4">
                    @else
                        <div class="h-40 w-40 bg-gray-200 flex items-center justify-center rounded mr-4">
                            <i class="fa fa-image text-gray-400 text-4xl"></i>
                        </div>
                    @endif
                    <div>
                        <h4 class="text-xl font-semibold mb-2">{{ $originalOutfit->name }}</h4>
                        <p class="text-gray-600 mb-2">{{ Str::limit($originalOutfit->description, 100) }}</p>
                        <p class="font-medium mb-1">ขนาด: {{ $cartItem->size->size ?? 'ไม่ระบุ' }}</p>
                        <p class="font-medium mb-1">สี: {{ $cartItem->color->color ?? 'ไม่ระบุ' }}</p>
                        <p class="font-medium mb-1">จำนวนที่ต้องการ: <span class="text-red-600">{{ $orderDetail->quantity }} ชุด</span></p>
                        <p class="font-medium">ราคาต่อชุด: {{ number_format($originalOutfit->price, 2) }} ฿</p>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t">
                    <h4 class="font-semibold mb-2">หมวดหมู่:</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($originalOutfit->categories as $category)
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">{{ $category->category_name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Customer Information Card -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 border-b">
                <h3 class="text-lg font-semibold">ข้อมูลลูกค้าและการจอง</h3>
            </div>
            <div class="p-4">
                <div class="mb-4">
                    <p class="text-gray-600 mb-1">ชื่อ-นามสกุล:</p>
                    <p class="font-medium">{{ $booking->user->name ?? 'ไม่ระบุ' }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-600 mb-1">อีเมล:</p>
                    <p class="font-medium">{{ $booking->user->email ?? 'ไม่ระบุ' }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-600 mb-1">เบอร์โทรศัพท์:</p>
                    <p class="font-medium">{{ $booking->user->phone ?? 'ไม่ระบุ' }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-600 mb-1">เลขที่การจอง:</p>
                    <p class="font-medium">{{ $booking->booking_id }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-600 mb-1">วันที่สั่งซื้อ:</p>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($booking->purchase_date)->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alternative Outfits Section -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div class="bg-gray-50 px-4 py-3 border-b">
            <h3 class="text-lg font-semibold">ชุดทดแทนที่มีหมวดหมู่เดียวกัน ({{ $alternativeOutfits->count() }} ชุด)</h3>
        </div>
        
        @if($alternativeOutfits->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                @foreach($alternativeOutfits as $outfit)
                    <div class="border rounded-lg overflow-hidden hover:shadow-md transition duration-300">
                        @if($outfit->image)
                            <img src="{{ asset($outfit->image) }}" alt="{{ $outfit->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <i class="fa fa-image text-gray-400 text-4xl"></i>
                            </div>
                        @endif
                        
                        <div class="p-4">
                            <h4 class="text-lg font-semibold mb-2">{{ $outfit->name }}</h4>
                            <p class="text-gray-600 text-sm mb-3">{{ Str::limit($outfit->description, 100) }}</p>
                            
                            <div class="mb-3">
                                <p class="text-sm">ขนาด: {{ $cartItem->size->size ?? 'ไม่ระบุ' }}</p>
                                <p class="text-sm">สี: {{ $cartItem->color->color ?? 'ไม่ระบุ' }}</p>
                                @foreach($outfit->sizeAndColors as $sizeColor)
                                    @if($sizeColor->size_id == $cartItem->size_id && $sizeColor->color_id == $cartItem->color_id)
                                        <p class="text-sm">จำนวนคงเหลือ: <span class="font-medium">{{ $sizeColor->amount }} ชุด</span></p>
                                    @endif
                                @endforeach
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold">{{ number_format($outfit->price, 2) }} ฿</span>
                                
                                <form action="{{ route('shopowner.bookings.save-selection') }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะเลือกชุดนี้เป็นชุดทดแทน?');">
                                    @csrf
                                    <input type="hidden" name="booking_id" value="{{ $booking->booking_id }}">
                                    <input type="hidden" name="orderDetail_id" value="{{ $orderDetail->orderDetail_id }}">
                                    <input type="hidden" name="outfit_id" value="{{ $outfit->outfit_id }}">
                                    
                                    @foreach($outfit->sizeAndColors as $sizeColor)
                                        @if($sizeColor->size_id == $cartItem->size_id && $sizeColor->color_id == $cartItem->color_id)
                                            <input type="hidden" name="sizeDetail_id" value="{{ $sizeColor->sizeDetail_id }}">
                                        @endif
                                    @endforeach
                                    
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                        เลือกชุดนี้
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-10">
                <i class="fa fa-search text-4xl text-gray-400 mb-4"></i>
                <p class="text-lg text-gray-600">ไม่พบชุดทดแทนที่มีหมวดหมู่เดียวกัน</p>
                <p class="text-sm text-gray-500 mt-1">ลองเพิ่มสินค้าใหม่หรือปรับสต็อกสินค้าของคุณ</p>
            </div>
        @endif
    </div>
</div>
@endsection
