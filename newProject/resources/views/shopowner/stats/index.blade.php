@extends('layouts.shopowner-layout')

@section('title', 'สถิติร้านค้า')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">สถิติร้านค้าของฉัน</h2>
    </div>

    <div class="bg-white p-6 shadow rounded-lg">
        <div class="text-center py-12">
            <div class="mb-6">
                <i class="fa fa-chart-bar text-5xl text-gray-400"></i>
                <p class="mt-4 text-lg">ระบบสถิติร้านค้ากำลังอยู่ในระหว่างการพัฒนา</p>
                <p class="text-gray-500 mb-6">ฟีเจอร์นี้จะเปิดให้บริการเร็วๆ นี้</p>
            </div>
            <div class="p-4 bg-green-50 rounded-lg max-w-xl mx-auto">
                <h3 class="font-semibold text-green-700 mb-2">ฟีเจอร์ที่กำลังจะมา:</h3>
                <ul class="text-left text-green-600 space-y-2 mx-auto max-w-md">
                    <li><i class="fa fa-chart-line mr-2"></i> กราฟแสดงยอดขายรายวัน/รายเดือน</li>
                    <li><i class="fa fa-tshirt mr-2"></i> สถิติชุดที่ได้รับความนิยม</li>
                    <li><i class="fa fa-users mr-2"></i> รายงานลูกค้า</li>
                    <li><i class="fa fa-download mr-2"></i> การส่งออกข้อมูลสถิติ</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
