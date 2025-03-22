@extends('layouts.shopowner-layout')

@section('title', 'รายละเอียดการจอง')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('shopowner.bookings.index') }}" class="text-blue-600 hover:text-blue-800">
                <i class="fa fa-arrow-left mr-2"></i>กลับไปยังรายการจอง
            </a>
            <h2 class="text-2xl font-bold mt-2">รายละเอียดการจอง #{{ $booking->booking_id }}</h2>
        </div>
        <div>
            @if($booking->status == 'pending')
                <form action="{{ route('shopowner.bookings.updateStatus', $booking->booking_id) }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="status" value="confirmed">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 mr-2">
                        <i class="fa fa-check mr-2"></i>ยืนยันการจอง
                    </button>
                </form>
                <form action="{{ route('shopowner.bookings.updateStatus', $booking->booking_id) }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700" onclick="return confirm('คุณแน่ใจหรือไม่ที่ต้องการยกเลิกการจองนี้?')">
                        <i class="fa fa-times mr-2"></i>ยกเลิกการจอง
                    </button>
                </form>
            @elseif($booking->status == 'confirmed')
                <form action="{{ route('shopowner.bookings.updateStatus', $booking->booking_id) }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 mr-2">
                        <i class="fa fa-check-double mr-2"></i>เสร็จสิ้นการจอง
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Order Summary Card -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 border-b">
                <h3 class="text-lg font-semibold">สรุปการจอง</h3>
            </div>
            <div class="p-4">
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">สถานะ:</span>
                        <span class="font-medium">
                            @if($booking->status == 'pending')
                                <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold">
                                    รอการยืนยัน
                                </span>
                            @elseif($booking->status == 'confirmed')
                                <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-semibold">
                                    ยืนยันแล้ว
                                </span>
                            @elseif($booking->status == 'completed')
                                <span class="px-2 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">
                                    เสร็จสิ้นแล้ว
                                </span>
                            @elseif($booking->status == 'cancelled')
                                <span class="px-2 py-1 rounded-full bg-red-100 text-red-800 text-xs font-semibold">
                                    ยกเลิก
                                </span>
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">วันที่สั่งซื้อ:</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($booking->purchase_date)->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">จำนวนชุด:</span>
                        <span class="font-medium">{{ $booking->orderDetails->count() }} ชุด</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">ยอดรวม:</span>
                        <span class="font-bold text-lg">{{ number_format($booking->total_price, 2) }} ฿</span>
                    </div>
                </div>
                
                @if($booking->promotion)
                <div class="mt-4 pt-4 border-t">
                    <h4 class="font-semibold mb-2">โปรโมชั่นที่ใช้</h4>
                    <div class="bg-blue-50 p-3 rounded-md">
                        <p class="text-blue-700 font-medium">{{ $booking->promotion->promotion_name }}</p>
                        <p class="text-sm text-blue-600">รหัส: {{ $booking->promotion->promotion_code }}</p>
                        <p class="text-sm text-blue-600">ส่วนลด: {{ number_format($booking->promotion->discount_amount, 2) }} ฿</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Customer Information Card -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 border-b">
                <h3 class="text-lg font-semibold">ข้อมูลลูกค้า</h3>
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
                    <p class="font-medium">{{ $booking->user->tel ?? 'ไม่ระบุ' }}</p>
                </div>
            </div>
        </div>
        
        <!-- Delivery Information Card -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 border-b">
                <h3 class="text-lg font-semibold">ข้อมูลการจัดส่ง</h3>
            </div>
            <div class="p-4">
                @if($booking->orderDetails->first() && $booking->orderDetails->first()->deliveryOptions == 'delivery')
                    <div class="mb-4">
                        <p class="text-gray-600 mb-1">วิธีจัดส่ง:</p>
                        <p class="font-medium">จัดส่งถึงบ้าน</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-gray-600 mb-1">ที่อยู่:</p>
                        <p class="font-medium">{{ $booking->user->address ?? 'ไม่ระบุ' }}</p>
                    </div>
                @else
                    <div class="mb-4">
                        <p class="text-gray-600 mb-1">วิธีจัดส่ง:</p>
                        <p class="font-medium">รับที่ร้าน</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-gray-600 mb-1">ที่ตั้งร้าน:</p>
                        <p class="font-medium">{{ $booking->shop->shop_location ?? 'ไม่ระบุ' }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Order Items Card -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="bg-gray-50 px-4 py-3 border-b">
            <h3 class="text-lg font-semibold">รายการชุดที่สั่ง</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">รูปภาพ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่อชุด</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ขนาด/สี</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">จำนวน</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ราคาต่อชิ้น</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">รอบการเช่า</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ราคารวม</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($booking->orderDetails as $orderDetail)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($orderDetail->cartItem && $orderDetail->cartItem->outfit && $orderDetail->cartItem->outfit->image)
                                    <img src="{{ asset($orderDetail->cartItem->outfit->image) }}" alt="{{ $orderDetail->cartItem->outfit->name }}" class="h-16 w-16 object-cover rounded">
                                @else
                                    <div class="h-16 w-16 bg-gray-200 flex items-center justify-center rounded">
                                        <i class="fa fa-image text-gray-400"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $orderDetail->cartItem->outfit->name ?? 'ไม่ระบุ' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    ขนาด: {{ $orderDetail->cartItem->size ?? 'ไม่ระบุ' }}<br>
                                    สี: {{ $orderDetail->cartItem->color ?? 'ไม่ระบุ' }}
                                    </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $orderDetail->quantity }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ number_format($orderDetail->cartItem->outfit->price ?? 0, 2) }} ฿
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($orderDetail->booking_cycle == 1)
                                        รอบเช้า (8:00 - 13:00)
                                    @elseif($orderDetail->booking_cycle == 2)
                                        รอบบ่าย (13:00 - 18:00)
                                    @else
                                        ไม่ระบุ
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ number_format($orderDetail->total, 2) }} ฿
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection