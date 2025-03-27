@extends('layouts.admin-layout')

@section('title', 'แดชบอร์ดผู้ดูแลระบบ')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            <i class="fas fa-tachometer-alt mr-2 text-[#8B9DF9]"></i>แดชบอร์ด
        </h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <!-- Users Card -->
        <div class="card bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6 flex items-center">
                <div class="rounded-full bg-blue-100 p-3 mr-4">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">ผู้ใช้ทั้งหมด</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ \App\Models\User::count() }}</p>
                </div>
            </div>
            <div class="bg-blue-50 px-6 py-2">
                <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    <i class="fas fa-arrow-right mr-1"></i> ดูผู้ใช้ทั้งหมด
                </a>
            </div>
        </div>

        <!-- Shops Card -->
        <div class="card bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6 flex items-center">
                <div class="rounded-full bg-purple-100 p-3 mr-4">
                    <i class="fas fa-store text-purple-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">ร้านค้าทั้งหมด</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ \App\Models\Shop::count() }}</p>
                </div>
            </div>
            <div class="bg-purple-50 px-6 py-2">
                <a href="{{ route('admin.shops.index') }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                    <i class="fas fa-arrow-right mr-1"></i> ดูร้านค้าทั้งหมด
                </a>
            </div>
        </div>

        <!-- Outfits Card -->
        <div class="card bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6 flex items-center">
                <div class="rounded-full bg-green-100 p-3 mr-4">
                    <i class="fas fa-tshirt text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">ชุดไทยทั้งหมด</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ \App\Models\ThaiOutfit::count() }}</p>
                </div>
            </div>
            <div class="bg-green-50 px-6 py-2">
                <a href="{{ route('admin.outfits.adminindex') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                    <i class="fas fa-arrow-right mr-1"></i> ดูชุดไทยทั้งหมด
                </a>
            </div>
        </div>

        <!-- Bookings Card -->
        <div class="card bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6 flex items-center">
                <div class="rounded-full bg-yellow-100 p-3 mr-4">
                    <i class="fas fa-calendar-check text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">การจองทั้งหมด</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ \App\Models\Booking::count() }}</p>
                </div>
            </div>
            <div class="bg-yellow-50 px-6 py-2">
                <a href="{{ route('admin.booking.index') }}" class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">
                    <i class="fas fa-arrow-right mr-1"></i> ดูการจองทั้งหมด
                </a>
            </div>
        </div>

        <!-- Reports Card -->
        <div class="card bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6 flex items-center">
                <div class="rounded-full bg-red-100 p-3 mr-4">
                    <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">รายงานปัญหาทั้งหมด</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ \App\Models\Issue::count() }}</p>
                </div>
            </div>
            <div class="bg-red-50 px-6 py-2">
                <a href="{{ route('admin.issue.show') }}" class="text-red-600 hover:text-red-800 text-sm font-medium">
                    <i class="fas fa-arrow-right mr-1"></i> ดูรายงานปัญหาทั้งหมด
                </a>
            </div>
        </div>
    </div>

    <div class="card bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="card-header">
            <div class="flex items-center">
                <i class="fas fa-chart-line card-header-icon"></i>
                <h2 class="card-title">ภาพรวมระบบ</h2>
            </div>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Active vs Inactive Users -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">สถานะผู้ใช้</h3>
                    <div class="flex items-center justify-between bg-gray-100 p-4 rounded-lg">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ \App\Models\User::where('status', 'active')->count() }}</div>
                            <div class="text-sm text-gray-600">ผู้ใช้ที่ใช้งานอยู่</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600">{{ \App\Models\User::where('status', 'inactive')->count() }}</div>
                            <div class="text-sm text-gray-600">ผู้ใช้ที่ไม่ได้ใช้งาน</div>
                        </div>
                    </div>
                </div>

                <!-- Active vs Inactive Shops -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">สถานะร้านค้า</h3>
                    <div class="flex items-center justify-between bg-gray-100 p-4 rounded-lg">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ \App\Models\Shop::where('status', 'active')->count() }}</div>
                            <div class="text-sm text-gray-600">ร้านค้าที่เปิดให้บริการ</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600">{{ \App\Models\Shop::where('status', 'inactive')->count() }}</div>
                            <div class="text-sm text-gray-600">ร้านค้าที่ปิดให้บริการ</div>
                        </div>
                    </div>
                </div>

                <!-- Booking Status -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">สถานะการจอง</h3>
                    <div class="grid grid-cols-3 gap-2 bg-gray-100 p-4 rounded-lg">
                        <div class="text-center">
                            <div class="text-xl font-bold text-yellow-600">{{ \App\Models\Booking::where('status', 'pending')->count() }}</div>
                            <div class="text-xs text-gray-600">รอดำเนินการ</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-green-600">{{ \App\Models\Booking::where('status', 'confirmed')->count() }}</div>
                            <div class="text-xs text-gray-600">ยืนยันแล้ว</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-red-600">{{ \App\Models\Booking::where('status', 'cancelled')->count() }}</div>
                            <div class="text-xs text-gray-600">ยกเลิกแล้ว</div>
                        </div>
                    </div>
                </div>

                <!-- Report Status -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">สถานะรายงานปัญหา</h3>
                    <div class="grid grid-cols-3 gap-2 bg-gray-100 p-4 rounded-lg">
                        <div class="text-center">
                            <div class="text-xl font-bold text-yellow-600">{{ \App\Models\Issue::where('status', 'reported')->count() }}</div>
                            <div class="text-xs text-gray-600">รายงานแล้ว</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-blue-600">{{ \App\Models\Issue::where('status', 'in_progress')->count() }}</div>
                            <div class="text-xs text-gray-600">กำลังดำเนินการ</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-green-600">{{ \App\Models\Issue::where('status', 'fixed')->count() }}</div>
                            <div class="text-xs text-gray-600">แก้ไขแล้ว</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
