@extends('layouts.admin-layout')

@section('title', 'User Management')

@section('content')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-4">จัดการชุด</h2>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('admin.outfits.adminindex') }}" class="mb-4 flex">
            <input type="text" name="search" placeholder="ค้นหา Shop ID, Outfit ID หรือ ชื่อชุด"
                class="border p-2 w-full rounded-l-md"
                value="{{ request('search') }}">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r-md">ค้นหา</button>
        </form>

        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-3 text-left">Outfit ID</th>
                    <th class="p-3 text-left">รูปภาพ</th>
                    <th class="p-3 text-left">ชื่อชุด</th>
                    <th class="p-3 text-left">ราคา</th>
                    <th class="p-3 text-left">คงเหลือ</th>
                    <th class="p-3 text-left">สถานะ</th>
                    <th class="p-3 text-left">Shop ID</th>
                    <th class="p-3 text-left">การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($outfits as $outfit)
                <tr class="border-b">
                    <td class="p-3">{{ $outfit->outfit_id }}</td>
                    <td class="p-3">
                        @if ($outfit->image)
                        <img src="{{ asset($outfit->image) }}" alt="Outfit Image" class="w-12 h-12 rounded-md">
                        @else
                        <span class="text-gray-400">ไม่มีรูป</span>
                        @endif
                    </td>
                    <td class="p-3">{{ $outfit->name }}</td>
                    <td class="p-3">{{ number_format($outfit->price, 2) }} บาท</td>
                    <td class="p-3">{{ $outfit->stock }}</td>
                    <td class="p-3">
                        <span class="px-2 py-1 text-sm rounded-lg {{ $outfit->status == 'active' ? 'bg-green-200 text-green-700' : 'bg-red-200 text-red-700' }}">
                            {{ $outfit->status == 'active' ? 'พร้อมใช้งาน' : 'ไม่พร้อมใช้งาน' }}
                        </span>
                    </td>
                    <td class="p-3">{{ $outfit->shop_name }}</td>
                    <td class="p-3 flex space-x-2">
                        <a href="{{ route('admin.outfits.edit', $outfit->outfit_id) }}" class="text-blue-500 hover:underline">แก้ไข</a>
                        <form action="{{ route('admin.outfits.destroy', $outfit->outfit_id) }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบชุดนี้?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline">ลบ</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $outfits->links() }}
        </div>
    </div>
@endsection