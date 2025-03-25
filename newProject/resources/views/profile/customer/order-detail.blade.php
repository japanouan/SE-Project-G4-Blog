@extends('layouts.main')

@section('title', 'รายละเอียดการสั่งซื้อ')

@section('content')
    <div class="container mx-auto my-5 flex flex-col md:flex-row gap-5 min-h-screen">
        {{-- Sidebar (Sticky) --}}
        <div class="w-full md:w-1/4 bg-white rounded-lg shadow sticky top-5 max-h-screen overflow-y-auto">
            <div class="p-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Account Settings</h3>
            </div>
            <ul class="p-4 space-y-2 text-sm">
                <li class="flex items-center py-2 px-3 bg-purple-50 text-purple-600 font-semibold rounded-md">
                    <i class="fas fa-user mr-3 w-4 text-center"></i> Profile
                </li>
                <a href="{{ route('profile.customer.address.index') }}" class="flex items-center py-2 px-3 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-md transition-colors cursor-pointer">
                    <i class="fas fa-map-marker-alt mr-3 w-4 text-center"></i> Address
                </a>
                <li class="flex items-center py-2 px-3 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-md transition-colors cursor-pointer">
                    <i class="fas fa-credit-card mr-3 w-4 text-center"></i> Payment
                </li>
                <a href="{{ route('profile.customer.orderHistory') }}" class="flex items-center py-2 px-3 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-md transition-colors cursor-pointer">
                    <i class="fas fa-history mr-3 w-4 text-center"></i> History
                </a>
            </ul>
        </div>

        {{-- Main Content (Order Detail) --}}
        <div class="w-full md:flex-1">
            <h2 class="text-2xl font-bold mb-5 text-gray-800">รายละเอียดการสั่งซื้อ</h2>
            <div class="bg-white border border-gray-200 rounded-lg shadow-md p-5">
                <!-- ข้อมูลร้านค้าและสถานะ -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-store text-purple-600"></i>
                        <div>
                            <strong class="text-gray-700">ร้านค้า:</strong> 
                            <span class="text-gray-900 ml-1">
                                {{ $booking->orderDetails->first()->cartItem->thaioutfit_sizeandcolor ? $booking->orderDetails->first()->cartItem->thaioutfit_sizeandcolor->outfit->shop->shop_name : '-' }}
                            </span>
                        </div>
                    </div>
                    <div class="status">
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                            {{ $booking->status == 'confirmed' ? 'bg-red-100 text-red-600' : 
                               ($booking->status == 'pending' ? 'bg-orange-100 text-orange-600' : 
                               ($booking->status == 'partial paid' ? 'bg-blue-100 text-blue-600' : 
                               'bg-gray-100 text-gray-600')) }}">
                            {{ $booking->status }}
                        </span>
                    </div>
                </div>

                <!-- วันที่จอง -->
                <div class="mb-6 flex items-center space-x-2">
                    <i class="fas fa-calendar-alt text-purple-600"></i>
                    <strong class="text-gray-700">วันที่จอง:</strong>
                    <span class="text-gray-900">{{ $booking->purchase_date }}</span>
                </div>

                <!-- รายการสินค้า -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">รายการสินค้า</h3>
                    @foreach ($booking->orderDetails as $orderDetail)
                        <div class="flex items-center border-b border-gray-200 py-4">
                            <!-- รูปสินค้า -->
                            <div class="w-20 h-20 mr-4">
                                @if($orderDetail->cartItem->thaioutfit_sizeandcolor)
                                    <img src="{{ asset($orderDetail->cartItem->thaioutfit_sizeandcolor->outfit->image) }}" alt="Product Image" class="w-full h-full object-cover rounded-md">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-500">-</div>
                                @endif
                            </div>
                            <!-- รายละเอียดสินค้า -->
                            <div class="flex-1">
                                <div class="text-md font-semibold text-gray-800">
                                    {{ $orderDetail->cartItem->thaioutfit_sizeandcolor ? $orderDetail->cartItem->thaioutfit_sizeandcolor->outfit->name : '-' }}
                                </div>
                                <div class="text-sm text-gray-500 mt-1">
                                    ขนาด: {{ $orderDetail->cartItem->thaioutfit_sizeandcolor ? $orderDetail->cartItem->thaioutfit_sizeandcolor->size->size : '-' }} | 
                                    สี: {{ $orderDetail->cartItem->thaioutfit_sizeandcolor ? $orderDetail->cartItem->thaioutfit_sizeandcolor->color->color : '-' }}
                                </div>
                                <div class="text-sm text-gray-500 mt-1">จำนวน: {{ $orderDetail->cartItem->quantity }}</div>
                                <div class="text-sm text-purple-600 font-semibold mt-1">
                                    ฿{{ $orderDetail->cartItem->thaioutfit_sizeandcolor ? number_format($orderDetail->cartItem->thaioutfit_sizeandcolor->outfit->price * $orderDetail->cartItem->quantity, 2) : '-' }}
                                </div>
                                <!-- สถานะการชำระเงิน -->
                                <div class="mt-2">
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                        {{ $orderDetail->is_paid ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                        {{ $orderDetail->is_paid ? 'ชำระแล้ว' : 'ยังไม่ชำระ' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- บริการ -->
                @if ($booking->selectService && $booking->selectService->isNotEmpty())
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">บริการเพิ่มเติม</h3>
                        @foreach ($booking->selectService as $service)
                            <div class="flex items-center justify-between py-2">
                                <div class="flex items-center space-x-2">
                                    @if ($service->service_type == 'photographer')
                                        <i class="fas fa-camera text-purple-600"></i>
                                        <strong class="text-gray-700">ถ่ายรูป:</strong>
                                        <span class="text-gray-900">{{ $service->customer_count }} คน</span>
                                    @else
                                        <i class="fas fa-paint-brush text-purple-600"></i>
                                        <strong class="text-gray-700">แต่งหน้า:</strong>
                                        <span class="text-gray-900">{{ $service->customer_count }} คน</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-purple-600 font-semibold">
                                        ฿{{ number_format($service->customer_count * 2000, 2) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- ราคารวม -->
                <div class="flex items-center justify-end space-x-2 border-t border-gray-200 pt-4">
                    <i class="fas fa-wallet text-purple-600"></i>
                    <strong class="text-gray-700 text-lg">ราคารวม:</strong>
                    <span class="text-purple-600 text-lg font-semibold">฿{{ number_format($booking->total_price, 2) }}</span>
                </div>
            </div>

            <!-- ปุ่มย้อนกลับ -->
            <div class="mt-6">
                <a href="{{ route('profile.customer.orderHistory') }}" class="inline-block px-6 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                    กลับไปที่ประวัติการสั่งของ
                </a>
            </div>
        </div>
    </div>
        <!-- เพิ่มก่อนส่วนแสดงรายการสินค้า -->
        @php
                // ใช้ Auth::id() อย่างชัดเจน
                use Illuminate\Support\Facades\Auth;
                use App\Models\SelectOutfitDetail;
                
                // ตรวจสอบว่ามีรายการที่ไม่เพียงพอ
                $hasUnavailableItems = $booking->orderDetails->where('booking_cycle', 2)->count() > 0;
                
                // ตรวจสอบว่ามีข้อเสนอชุดทดแทนหรือไม่
                $hasSuggestions = \App\Models\SelectOutfitDetail::where('booking_id', $booking->booking_id)
                    ->where('customer_id', Auth::id())
                    ->exists();
                
                // ตรวจสอบว่ามีรายการที่รอการตอบรับหรือไม่
                $hasPendingSuggestions = \App\Models\SelectOutfitDetail::where('booking_id', $booking->booking_id)
                    ->where('customer_id', Auth::id())
                    ->where('status', 'Pending Selection')
                    ->exists();
                
                // ดีบัก - แสดงข้อมูลเพื่อตรวจสอบ
                // dd($hasUnavailableItems, $hasSuggestions, $hasPendingSuggestions, Auth::id(), $booking->booking_id);
            @endphp

                @if($hasUnavailableItems || $hasSuggestions)
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <strong>โปรดทราบ:</strong> 
                                    @if($hasUnavailableItems && !$hasSuggestions)
                                        การสั่งซื้อนี้มีชุดที่มีจำนวนไม่เพียงพอ กรุณารอการเสนอชุดทดแทนจากร้านค้า
                                    @elseif($hasSuggestions)
                                        มีชุดทดแทนที่แนะนำสำหรับการสั่งซื้อนี้
                                    @endif
                                </p>
                                @if($hasSuggestions)
                                    <div class="mt-2">
                                        <a href="{{ route('profile.customer.outfit-suggestions', ['bookingId' => $booking->booking_id]) }}" 
                                            class="inline-block bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600">
                                            ดูชุดทดแทนที่แนะนำ
                                            @if($hasPendingSuggestions)
                                                <span class="ml-1 px-2 py-0.5 bg-red-500 text-white rounded-full text-xs">รอตอบรับ</span>
                                            @endif
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

@endsection