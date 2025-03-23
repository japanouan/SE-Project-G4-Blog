<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-6">
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
</body>

</html>