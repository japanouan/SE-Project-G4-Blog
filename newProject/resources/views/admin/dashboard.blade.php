@extends('layouts.admin-layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
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
                    <h3 class="text-lg font-semibold text-gray-700">Total Users</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ \App\Models\User::count() }}</p>
                </div>
            </div>
            <div class="bg-blue-50 px-6 py-2">
                <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    <i class="fas fa-arrow-right mr-1"></i> View All Users
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
                    <h3 class="text-lg font-semibold text-gray-700">Total Shops</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ \App\Models\Shop::count() }}</p>
                </div>
            </div>
            <div class="bg-purple-50 px-6 py-2">
                <a href="{{ route('admin.shops.index') }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                    <i class="fas fa-arrow-right mr-1"></i> View All Shops
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
                    <h3 class="text-lg font-semibold text-gray-700">Total Outfits</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ \App\Models\ThaiOutfit::count() }}</p>
                </div>
            </div>
            <div class="bg-green-50 px-6 py-2">
                <a href="{{ route('admin.outfits.adminindex') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                    <i class="fas fa-arrow-right mr-1"></i> View All Outfits
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
                    <h3 class="text-lg font-semibold text-gray-700">Total Bookings</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ \App\Models\Booking::count() }}</p>
                </div>
            </div>
            <div class="bg-yellow-50 px-6 py-2">
                <a href="{{ route('admin.booking.index') }}" class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">
                    <i class="fas fa-arrow-right mr-1"></i> View All Bookings
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
                    <h3 class="text-lg font-semibold text-gray-700">Total Reports</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ \App\Models\Issue::count() }}</p>
                </div>
            </div>
            <div class="bg-red-50 px-6 py-2">
                <a href="{{ route('admin.issue.show') }}" class="text-red-600 hover:text-red-800 text-sm font-medium">
                    <i class="fas fa-arrow-right mr-1"></i> View All Reports
                </a>
            </div>
        </div>
    </div>

    <div class="card bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="card-header">
            <div class="flex items-center">
                <i class="fas fa-chart-line card-header-icon"></i>
                <h2 class="card-title">System Overview</h2>
            </div>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Active vs Inactive Users -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">User Status</h3>
                    <div class="flex items-center justify-between bg-gray-100 p-4 rounded-lg">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ \App\Models\User::where('status', 'active')->count() }}</div>
                            <div class="text-sm text-gray-600">Active Users</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600">{{ \App\Models\User::where('status', 'inactive')->count() }}</div>
                            <div class="text-sm text-gray-600">Inactive Users</div>
                        </div>
                    </div>
                </div>

                <!-- Active vs Inactive Shops -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Shop Status</h3>
                    <div class="flex items-center justify-between bg-gray-100 p-4 rounded-lg">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ \App\Models\Shop::where('status', 'active')->count() }}</div>
                            <div class="text-sm text-gray-600">Active Shops</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600">{{ \App\Models\Shop::where('status', 'inactive')->count() }}</div>
                            <div class="text-sm text-gray-600">Inactive Shops</div>
                        </div>
                    </div>
                </div>

                <!-- Booking Status -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Booking Status</h3>
                    <div class="grid grid-cols-3 gap-2 bg-gray-100 p-4 rounded-lg">
                        <div class="text-center">
                            <div class="text-xl font-bold text-yellow-600">{{ \App\Models\Booking::where('status', 'pending')->count() }}</div>
                            <div class="text-xs text-gray-600">Pending</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-green-600">{{ \App\Models\Booking::where('status', 'confirmed')->count() }}</div>
                            <div class="text-xs text-gray-600">Confirmed</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-red-600">{{ \App\Models\Booking::where('status', 'cancelled')->count() }}</div>
                            <div class="text-xs text-gray-600">Cancelled</div>
                        </div>
                    </div>
                </div>

                <!-- Report Status -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Report Status</h3>
                    <div class="grid grid-cols-3 gap-2 bg-gray-100 p-4 rounded-lg">
                        <div class="text-center">
                            <div class="text-xl font-bold text-yellow-600">{{ \App\Models\Issue::where('status', 'reported')->count() }}</div>
                            <div class="text-xs text-gray-600">Reported</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-blue-600">{{ \App\Models\Issue::where('status', 'in_progress')->count() }}</div>
                            <div class="text-xs text-gray-600">In Progress</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-green-600">{{ \App\Models\Issue::where('status', 'fixed')->count() }}</div>
                            <div class="text-xs text-gray-600">Fixed</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
