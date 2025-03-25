@extends('layouts.main')

@section('content')
<div class="container mx-auto p-4 flex flex-col md:flex-row gap-6">

    <!-- Sidebar -->
    <div class="w-full md:w-1/4 bg-white rounded-lg shadow h-fit">
        <div class="p-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Account Settings</h3>
        </div>
        <ul class="p-4 space-y-2 text-sm">
            <!-- Profile -->
            <a href="{{ route('profile.index') }}" class="flex items-center py-2 px-3 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-md transition-colors">
                <i class="fas fa-user mr-3 w-4 text-center"></i> Profile
            </a>

            <!-- Address -->
            <a href="{{ route('profile.customer.address.index') }}" class="flex items-center py-2 px-3 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-md transition-colors">
                <i class="fas fa-map-marker-alt mr-3 w-4 text-center"></i> Address
            </a>

            <!-- Payment -->
            <a href="{{ route('payment.index') }}" class="flex items-center py-2 px-3 text-purple-600 bg-purple-50 rounded-md transition-colors font-semibold">
                <i class="fas fa-credit-card mr-3 w-4 text-center"></i> Payment
            </a>

            <!-- History -->
            <a href="{{ route('profile.customer.orderHistory') }}" class="flex items-center py-2 px-3 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-md transition-colors">
                <i class="fas fa-history mr-3 w-4 text-center"></i> History
            </a>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="w-full md:w-3/4">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">รายการการชำระเงิน</h2>

        @forelse ($bookings as $booking)
            <div class="bg-white p-4 rounded shadow mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-semibold text-gray-700">
                            Booking #{{ $booking->booking_id }}
                            <span class="text-sm text-gray-500">({{ $booking->purchase_date->format('Y-m-d') }})</span>
                        </p>
                        <p class="text-sm text-gray-600">สถานะ: {{ $booking->status }}</p>
                    </div>
                    <div class="text-right">
                        <p>ยอดรวม: ฿{{ number_format($booking->total_price, 2) }}</p>
                        <p>ชำระแล้ว: ฿{{ number_format($booking->paid, 2) }}</p>
                        <p class="{{ $booking->unpaid > 0 ? 'text-red-500' : 'text-green-600' }}">
                            ค้างชำระ: ฿{{ number_format($booking->unpaid, 2) }}
                        </p>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('profile.customer.orderDetail', $booking->booking_id) }}"
                        class="text-blue-500 hover:underline text-sm">ดูรายละเอียดคำสั่งซื้อ</a>
                </div>
            </div>
        @empty
            <div class="bg-white p-4 rounded shadow text-gray-500">
                ไม่พบข้อมูลการชำระเงิน
            </div>
        @endforelse
    </div>
</div>
@endsection
