@extends('layouts.shopowner-layout')

@section('title', 'จัดการชุด')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">จัดการชุด</h2>
        <a href="{{ route('shopowner.outfits.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            <i class="fa fa-plus mr-2"></i> เพิ่มชุดใหม่
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        @if(count($outfits) > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">รูปภาพ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่อชุด</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ราคา</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">คงเหลือ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สถานะ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($outfits as $outfit)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($outfit->image && file_exists(public_path($outfit->image)))
                                <img src="{{ asset($outfit->image) }}" alt="{{ $outfit->name }}" class="h-16 w-16 object-cover rounded">
                            @else
                                <div class="h-16 w-16 bg-gray-200 flex items-center justify-center rounded">
                                    <i class="fa fa-image text-gray-400"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $outfit->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ number_format($outfit->price, 2) }} บาท</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $outfit->stock }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($outfit->status == 'active')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">พร้อมใช้งาน</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">ไม่พร้อมใช้งาน</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('shopowner.outfits.edit', $outfit->outfit_id) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fa fa-edit"></i> แก้ไข
                                </a>
                                <form action="{{ route('shopowner.outfits.destroy', $outfit->outfit_id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบชุดนี้?')">
                                        <i class="fa fa-trash"></i> ลบ
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4">
                {{ $outfits->links() }}
            </div>
        @else
            <div class="p-6 text-center text-gray-500">
                <p>ยังไม่มีชุดในร้านค้าของคุณ</p>
                <a href="{{ route('shopowner.outfits.create') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fa fa-plus mr-2"></i> เพิ่มชุดใหม่
                </a>
            </div>
        @endif
    </div>
</div>
@endsection