@extends('layouts.shopowner-layout')

@section('title', 'จัดการการจอง')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">จัดการการจอง</h2>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div class="p-6 bg-gray-50 border-b">
            <h3 class="text-lg font-semibold mb-4">ค้นหาและกรอง</h3>
            <form action="{{ route('shopowner.bookings.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ค้นหา</label>
                        <input type="text" name="search" placeholder="ค้นหาตาม ID หรือชื่อลูกค้า" value="{{ request('search') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">สถานะ</label>
                        <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">ทั้งหมด</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>รอการยืนยัน</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>ยืนยันแล้ว</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>เสร็จสิ้นแล้ว</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ยกเลิก</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ช่วงวันที่</label>
                        <input type="text" name="date_range" id="date_range" placeholder="เลือกช่วงวันที่" value="{{ request('date_range') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        <i class="fa fa-search mr-2"></i>ค้นหา
                    </button>
                    <a href="{{ route('shopowner.bookings.index') }}" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                        <i class="fa fa-times mr-2"></i>ล้างตัวกรอง
                    </a>
                </div>
            </form>
        </div>
        
        @if(isset($bookings) && $bookings->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">เลขที่การจอง</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">วันที่สั่งซื้อ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ลูกค้า</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ยอดรวม</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สถานะ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">จำนวนชุด</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->booking_id }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($booking->purchase_date)->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->user->name ?? 'ไม่ระบุ' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($booking->total_price, 2) }} ฿</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($booking->status == 'pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        รอการยืนยัน
                                    </span>
                                @elseif($booking->status == 'confirmed')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        ยืนยันแล้ว
                                    </span>
                                @elseif($booking->status == 'completed')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        เสร็จสิ้นแล้ว
                                    </span>
                                @elseif($booking->status == 'cancelled')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        ยกเลิก
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $booking->orderDetails->count() }} ชุด</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('shopowner.bookings.show', $booking->booking_id) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fa fa-eye mr-1"></i> ดูรายละเอียด
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="text-center py-10">
            <i class="fa fa-calendar text-4xl text-gray-400 mb-4"></i>
                <p class="text-lg text-gray-600">ไม่พบข้อมูลการจอง</p>
                @if(request('status') || request('search') || request('date_range'))
                    <p class="text-sm text-gray-500 mt-1">ลองเปลี่ยนตัวกรองหรือคำค้นหา</p>
                    <a href="{{ route('shopowner.bookings.index') }}" class="mt-4 inline-block px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        ล้างตัวกรอง
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#date_range", {
            mode: "range",
            dateFormat: "d/m/Y",
            locale: "th",
            rangeSeparator: " - ",
            placeholder: "เลือกช่วงวันที่"
        });
    });
</script>
@endpush
@endsection