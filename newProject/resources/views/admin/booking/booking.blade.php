@extends('layouts.admin-layout')

@section('title', 'Booking')

@section('content')

<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="mb-6">
            <form method="GET" action="{{ route('admin.booking.index') }}" class="flex gap-2">
                <input type="text" name="search" placeholder="ค้นหา Booking ID, User Name,Shop Name"
                    value="{{ request('search') }}"
                    class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                    ค้นหา
                </button>
            </form>
        </div>
        @foreach ($bookings as $booking)
        <a href="{{ route('admin.booking.detail', ['id' => $booking->booking_id]) }}" class="block">
            <div class="bg-white p-6 rounded-lg shadow-md mb-4 hover:shadow-lg transition">
                <div class="flex justify-between items-center mb-2">
                    <h4 class="text-lg font-semibold text-indigo-600">
                        🧾 Booking #{{ $booking->booking_id }}
                    </h4>
                    <span class="text-sm font-bold
                        @if($booking->status === 'confirmed') text-green-600
                        @elseif($booking->status === 'pending') text-yellow-600
                        @elseif($booking->status === 'cancelled') text-red-600
                        @else text-gray-600 @endif
                    ">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>

                <p><strong>📅 วันที่จอง:</strong> {{ \Carbon\Carbon::parse($booking->purchase_date)->format('d/m/Y') }}</p>
                <p><strong>💰 ยอดรวม:</strong> {{ number_format($booking->total_price, 2) }} บาท</p>
                @if ($booking->shop)
                    <p><strong>🏪 ร้านค้า:</strong> {{ $booking->shop->shop_name }}</p>
                @endif

                @php
                    $firstUser = optional($booking->orderDetails->first()->cartItem->user ?? null);
                @endphp

                @if ($firstUser)
                    <p><strong>👤 ผู้ใช้:</strong> {{ $firstUser->name }}</p>
                @endif

                @if ($booking->SelectService)
                @foreach ($booking->SelectService as $service)
                @if ($service->service_type == 'photographer')
                <p><strong>📸 ถ่ายรูป:</strong> {{ $service->customer_count }} คน</p>
                @else
                <p><strong>💄 แต่งหน้า:</strong> {{ $service->customer_count }} คน</p>
                @endif
                @endforeach
                @endif

                @if ($booking->hasOverrented)
                <p class="text-sm text-red-500 mt-2">⚠ มีการจองเกินจำนวน!</p>
                @endif
            </div>
        </a>
        @endforeach
    </div>
@endsection