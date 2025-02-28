@extends('layouts.shopowner-layout')

@section('title', 'จัดการชุด')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">จัดการชุด</h2>
        <a href="{{ route('shopowner.shop.new-form') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            <i class="fa fa-plus mr-2"></i> เพิ่มชุดใหม่
        </a>
    </div>

    <div class="bg-white p-6 shadow rounded-lg">
        <!-- ตารางแสดงรายการชุด -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-3 px-4 text-left">รูปภาพ</th>
                        <th class="py-3 px-4 text-left">ชื่อชุด</th>
                        <th class="py-3 px-4 text-left">ประเภท</th>
                        <th class="py-3 px-4 text-left">ขนาด</th>
                        <th class="py-3 px-4 text-left">ราคาต่อวัน</th>
                        <th class="py-3 px-4 text-left">สถานะ</th>
                        <th class="py-3 px-4 text-left">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <!-- ตัวอย่างข้อมูล - จะถูกแทนที่ด้วยข้อมูลจริง -->
                    <tr>
                        <td class="py-3 px-4">
                            <img src="https://via.placeholder.com/50" alt="ชุดไทย" class="h-12 w-12 object-cover rounded">
                        </td>
                        <td class="py-3 px-4">ชุดไทยจักรี</td>
                        <td class="py-3 px-4">ชุดไทย</td>
                        <td class="py-3 px-4">M</td>
                        <td class="py-3 px-4">1,500 บาท</td>
                        <td class="py-3 px-4"><span class="px-2 py-1 bg-green-100 text-green-800 rounded-full">ว่าง</span></td>
                        <td class="py-3 px-4">
                            <div class="flex space-x-2">
                                <a href="#" class="text-blue-500 hover:text-blue-700"><i class="fa fa-edit"></i></a>
                                <a href="#" class="text-red-500 hover:text-red-700"><i class="fa fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <!-- จะมีแถวเพิ่มเติมตามข้อมูล -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
