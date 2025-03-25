@extends('layouts.main')

@section('title', 'ประวัติการสั่งของ')

@section('content')
    <div class="container mx-auto my-5 flex flex-col md:flex-row gap-5 min-h-screen">
        <!-- Sidebar -->
    <div class="w-full md:w-1/4 bg-white rounded-lg shadow sticky top-5 h-fit">
        <div class="p-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Account Settings</h3>
        </div>
        <ul class="p-4 space-y-2 text-sm">
            <!-- Profile -->
            <a href="{{ route('profile.index') }}" class="flex items-center py-2 px-3 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-md transition-colors cursor-pointer">
                <i class="fas fa-user mr-3 w-4 text-center"></i> Profile
            </a>

            <!-- Address -->
            <a href="{{ route('profile.customer.address.index') }}" class="flex items-center py-2 px-3 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-md transition-colors cursor-pointer">
                <i class="fas fa-map-marker-alt mr-3 w-4 text-center"></i> Address
            </a>

            <!-- Payment -->
            <a href="{{ route('payment.index') }}" class="flex items-center py-2 px-3 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-md transition-colors cursor-pointer">
                <i class="fas fa-credit-card mr-3 w-4 text-center"></i> Payment
            </a>

            <!-- History -->
            <a href="{{ route('profile.customer.orderHistory') }}" class="flex items-center py-2 px-3 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-md transition-colors cursor-pointer">
                <i class="fas fa-history mr-3 w-4 text-center"></i> History
            </a>
        </ul>
    </div>


        {{-- Main Content (Booking List) --}}
        <div class="w-full md:flex-1">
            <h2 class="text-2xl font-bold mb-5 text-gray-800">ประวัติการสั่งของ</h2>
            <div class="space-y-4">
                @forelse ($bookings as $booking)
                    <a href="{{ route('profile.customer.orderDetail', ['bookingId' => $booking->booking_id]) }}" class="block border border-gray-200 p-5 rounded-lg shadow-md hover:shadow-lg transition-shadow bg-white">
                        <div class="flex items-center justify-between mb-4">
                            <!-- ร้านค้า -->
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-store text-purple-600"></i>
                                <div>
                                    <strong class="text-gray-700">ร้านค้า:</strong> 
                                    <span class="text-gray-900 ml-1">{{ $booking->orderDetails->first()->cartItem->thaioutfit_sizeandcolor->outfit->shop->shop_name }}</span>
                                </div>
                            </div>

                            <!-- สถานะ -->
                            <div class="status">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                    {{ $booking->status == 'confirmed' ? 'bg-green-100 text-green-600' : 
                                       ($booking->status == 'pending' ? 'bg-orange-100 text-orange-600' : 
                                       ($booking->status == 'partial paid' ? 'bg-blue-100 text-blue-600' : 
                                       'bg-red-100 text-red-600')) }}">
                                    {{ $booking->status }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <!-- รูปสินค้า -->
                            <div class="w-24 h-24 mr-4">
                                <img src="{{ asset($booking->orderDetails->first()->cartItem->thaioutfit_sizeandcolor->outfit->image) }}" alt="Product Image" class="w-full h-full object-cover rounded-md">
                            </div>

                            <!-- ชื่อสินค้า -->
                            <div class="flex-1">
                                <div class="text-lg font-semibold text-gray-800">{{ $booking->orderDetails->first()->cartItem->thaioutfit_sizeandcolor->outfit->name }}</div>
                                <div class="text-gray-500 text-sm mt-1">{{ $booking->orderDetails->first()->cartItem->thaioutfit_sizeandcolor->outfit->description ?? 'ไม่มีรายละเอียด' }}</div>
                            </div>
                        </div>

                        <div class="mt-4 space-y-2">
                            <!-- บริการ -->
                            @if ($booking->selectService) 
                                @foreach ($booking->selectService as $service)
                                    <p class="text-sm text-gray-700 flex items-center space-x-2">
                                        @if ($service->service_type == 'photographer')
                                            <i class="fas fa-camera text-purple-600"></i>
                                            <strong>ถ่ายรูป:</strong>
                                            <span>{{ $service->customer_count }} คน</span>
                                        @else
                                            <i class="fas fa-paint-brush text-purple-600"></i>
                                            <strong>แต่งหน้า:</strong>
                                            <span>{{ $service->customer_count }} คน</span>
                                        @endif
                                    </p>
                                @endforeach
                            @endif
                        </div>

                        <!-- ราคารวม -->
                        <div class="mt-4 text-lg font-semibold text-purple-600 flex items-center space-x-2">
                            <i class="fas fa-wallet"></i>
                            <strong>ราคารวม:</strong>
                            <span>฿{{ number_format($booking->total_price, 2) }}</span>
                        </div>
                    </a>
                @empty
                    <p class="text-gray-500 text-center py-5">คุณยังไม่มีประวัติการสั่งของ</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection