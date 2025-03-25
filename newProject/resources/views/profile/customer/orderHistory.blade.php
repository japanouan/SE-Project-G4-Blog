@extends('layouts.main')

@section('title', 'ประวัติการสั่งของ')

@section('content')
{{-- Import necessary classes at the top level of the template --}}
@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\SelectOutfitDetail;
    
    // ตรวจสอบว่ามีรายการที่มีการเสนอชุดทดแทนใหม่รอการตอบรับหรือไม่
    $pendingSuggestionsCount = 0;
    foreach ($bookings as $booking) {
        $suggestions = SelectOutfitDetail::where('booking_id', $booking->booking_id)
            ->where('customer_id', Auth::id())
            ->where('status', 'Pending Selection')
            ->count();
        
        if ($suggestions > 0) {
            $pendingSuggestionsCount++;
        }
    }
@endphp

<div class="container mx-auto my-5 flex flex-col md:flex-row gap-5 min-h-screen">
    <!-- Sidebar -->
    <div class="w-full md:w-1/4 bg-white rounded-lg shadow sticky top-5 h-fit">
        <div class="p-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Account Settings</h3>
        </div>
        <ul class="p-4 space-y-2 text-sm">
            <!-- Profile -->
            <a href="{{ route('profile.index') }}" class="flex items-center py-2 px-3 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-md transition-colors cursor-pointer">
                <i class="fas fa-user mr-3 w-4 text-center"></i> Profile
            </a>

            <!-- Address -->
            <a href="{{ route('profile.customer.address.index') }}" class="flex items-center py-2 px-3 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-md transition-colors cursor-pointer">
                <i class="fas fa-map-marker-alt mr-3 w-4 text-center"></i> Address
            </a>

            <!-- Payment -->
            <a href="{{ route('payment.index') }}" class="flex items-center py-2 px-3 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-md transition-colors cursor-pointer">
                <i class="fas fa-credit-card mr-3 w-4 text-center"></i> Payment
            </a>

            <!-- History -->
            <a href="{{ route('profile.customer.orderHistory') }}" class="flex items-center py-2 px-3 text-purple-600 bg-purple-50 rounded-md transition-colors cursor-pointer font-semibold">
                <i class="fas fa-history mr-3 w-4 text-center"></i> History
                @if($pendingSuggestionsCount > 0)
                    <span class="ml-2 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5 flex items-center justify-center min-w-[20px]">
                        {{ $pendingSuggestionsCount }}
                    </span>
                @endif
            </a>

            <!-- History -->
            <a href="{{ route('profile.customer.issue') }}" class="flex items-center py-2 px-3 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-md transition-colors cursor-pointer">
                <i class="fas fa-flag mr-3 w-4 text-center"></i> Report Issue
            </a>
        </ul>
    </div>

    {{-- Main Content (Booking List) --}}
    <div class="w-full md:flex-1">
        <h2 class="text-2xl font-bold mb-5 text-gray-800">ประวัติการสั่งของ</h2>
        
        <!-- Global notification and filter for new suggestions -->
        @if($pendingSuggestionsCount > 0)
            <div id="global-notification" class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-5 rounded-md shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mr-3"></i>
                        <div>
                            <p class="text-yellow-700 font-medium">
                                คุณมี {{ $pendingSuggestionsCount }} รายการที่มีชุดทดแทนใหม่รอการตอบรับ
                            </p>
                            <p class="text-yellow-600 text-sm mt-1">
                                กรุณาตรวจสอบและตอบรับหรือปฏิเสธชุดทดแทนเพื่อดำเนินการต่อ
                            </p>
                        </div>
                    </div>
                    <div>
                        <button id="show-all-orders" class="text-purple-600 hover:text-purple-800 mr-2 hidden">
                            แสดงทั้งหมด
                        </button>
                        <button id="filter-suggestions" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md text-sm transition-colors">
                            แสดงเฉพาะรายการที่มีชุดทดแทนใหม่
                        </button>
                    </div>
                </div>
            </div>
        @endif
        
        <div id="orders-container" class="space-y-4">
            @forelse ($bookings as $booking)
                @php
                    // Check if there are outfit suggestions for this booking
                    $suggestions = SelectOutfitDetail::where('booking_id', $booking->booking_id)
                        ->where('customer_id', Auth::id())
                        ->get();
                    
                    // Check if there are pending suggestions
                    $hasPendingSuggestions = $suggestions->where('status', 'Pending Selection')->count() > 0;
                    
                    // Check if status is partial paid
                    $isPartialPaid = $booking->status == 'partial paid';
                    
                    // Determine if we should show notification
                    $showNotification = $hasPendingSuggestions;
                @endphp
                
                <div class="order-item relative {{ $showNotification ? 'has-suggestions' : '' }}">
                    <!-- Notification badge for orders with pending suggestions -->
                    @if($showNotification)
                        <div class="absolute -top-2 -right-2 z-10">
                            <div class="bg-yellow-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg animate-pulse">
                                ชุดทดแทนใหม่
                            </div>
                        </div>
                    @endif
                    
                    <a href="{{ route('profile.customer.orderDetail', ['bookingId' => $booking->booking_id]) }}"
                       class="block border border-gray-200 p-5 rounded-lg shadow-md hover:shadow-lg transition-shadow bg-white">
                        <div class="flex items-center justify-between mb-4">
                            <!-- ร้านค้า -->
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-store text-purple-600"></i>
                                <div>
                                    <strong class="text-gray-700">ร้านค้า:</strong> 
                                    <span class="text-gray-900 ml-1">
                                        {{ optional(optional(optional(optional($booking->orderDetails->first())->cartItem)->thaioutfit_sizeandcolor)->outfit)->shop->shop_name ?? '-' }}
                                    </span>
                                </div>
                            </div>

                            <!-- สถานะ -->
                            <div class="status">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                    {{ $booking->status == 'confirmed' ? 'bg-green-100 text-green-600' : 
                                       ($booking->status == 'pending' ? 'bg-orange-100 text-orange-600' : 
                                       ($booking->status == 'partial paid' ? 'bg-blue-100 text-blue-600' : 
                                       'bg-red-100 text-red-600')) }}">
                                    {{ $booking->status }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <!-- รูปสินค้า -->
                            <div class="w-24 h-24 mr-4">
                                <img src="{{ asset(optional(optional(optional($booking->orderDetails->first())->cartItem)->thaioutfit_sizeandcolor)->outfit->image ?? 'images/default.png') }}"
                                     alt="Product Image" class="w-full h-full object-cover rounded-md">
                            </div>

                            <!-- ชื่อสินค้า -->
                            <div class="flex-1">
                                <div class="text-lg font-semibold text-gray-800">
                                    {{ optional(optional(optional($booking->orderDetails->first())->cartItem)->thaioutfit_sizeandcolor)->outfit->name ?? '-' }}
                                </div>
                                <div class="text-gray-500 text-sm mt-1">
                                    {{ optional(optional(optional($booking->orderDetails->first())->cartItem)->thaioutfit_sizeandcolor)->outfit->description ?? 'ไม่มีรายละเอียด' }}
                                </div>
                                
                                <!-- Add notification for orders with pending suggestions -->
                                @if($showNotification)
                                    <div class="mt-2 bg-yellow-50 border-l-4 border-yellow-400 p-2 text-sm">
                                        <div class="flex items-center">
                                            <i class="fas fa-exclamation-triangle text-yellow-400 mr-2"></i>
                                            <p class="text-yellow-700">มีชุดทดแทนใหม่ที่รอการตอบรับ</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 space-y-2">
                            <!-- บริการ -->
                            @if ($booking->selectService) 
                                @foreach ($booking->selectService as $service)
                                    <p class="text-sm text-gray-700 flex items-center space-x-2">
                                        @if ($service->service_type == 'photographer')
                                            <i class="fas fa-camera text-purple-600"></i>
                                            <strong>ถ่ายรูป:</strong>
                                            <span>{{ $service->customer_count }} คน</span>
                                        @else
                                            <i class="fas fa-paint-brush text-purple-600"></i>
                                            <strong>แต่งหน้า:</strong>
                                            <span>{{ $service->customer_count }} คน</span>
                                        @endif
                                    </p>
                                @endforeach
                            @endif
                        </div>

                        <!-- ราคารวม -->
                        <div class="mt-4 text-lg font-semibold text-purple-600 flex items-center space-x-2">
                            <i class="fas fa-wallet"></i>
                            <strong>ราคารวม:</strong>
                            <span>฿{{ number_format($booking->total_price, 2) }}</span>
                        </div>
                    </a>
                </div>
            @empty
                <p class="text-gray-500 text-center py-5">คุณยังไม่มีประวัติการสั่งของ</p>
            @endforelse
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterSuggestionsBtn = document.getElementById('filter-suggestions');
        const showAllOrdersBtn = document.getElementById('show-all-orders');
        const ordersContainer = document.getElementById('orders-container');
        const orderItems = document.querySelectorAll('.order-item');
        
        // ตรวจสอบว่ามีรายการที่มีชุดทดแทนหรือไม่
        const hasSuggestionItems = document.querySelectorAll('.order-item.has-suggestions').length > 0;
        
        // Function to filter orders and show only those with suggestions
        function filterOrders() {
            let hasVisibleOrders = false;
            
            orderItems.forEach(order => {
                if (order.classList.contains('has-suggestions')) {
                    order.style.display = 'block';
                    hasVisibleOrders = true;
                } else {
                    order.style.display = 'none';
                }
            });
            
            // Update button visibility
            if (filterSuggestionsBtn) filterSuggestionsBtn.classList.add('hidden');
            if (showAllOrdersBtn) showAllOrdersBtn.classList.remove('hidden');
            
            // Add a message if no suggestions are found after filtering
            const noSuggestionsMsg = document.querySelector('.no-suggestions-msg');
            if (!hasVisibleOrders) {
                if (!noSuggestionsMsg) {
                    const msgElement = document.createElement('p');
                    msgElement.className = 'text-gray-500 text-center py-5 no-suggestions-msg';
                    msgElement.textContent = 'ไม่มีรายการที่มีชุดทดแทนใหม่รอการตอบรับ';
                    ordersContainer.appendChild(msgElement);
                }
                
                // Auto-show all orders if there are no suggestions (after 2 seconds)
                setTimeout(showAllOrders, 2000);
            }
        }
        
        // Function to show all orders
        function showAllOrders() {
            orderItems.forEach(order => {
                order.style.display = 'block';
            });
            
            // Update button visibility
            if (filterSuggestionsBtn) filterSuggestionsBtn.classList.remove('hidden');
            if (showAllOrdersBtn) showAllOrdersBtn.classList.add('hidden');
            
            // Remove no suggestions message if it exists
            const noSuggestionsMsg = document.querySelector('.no-suggestions-msg');
            if (noSuggestionsMsg) {
                noSuggestionsMsg.remove();
            }
        }
        
        // Add event listeners to buttons
        if (filterSuggestionsBtn) {
            filterSuggestionsBtn.addEventListener('click', filterOrders);
        }
        
        if (showAllOrdersBtn) {
            showAllOrdersBtn.addEventListener('click', showAllOrders);
        }
        
        // ซ่อนปุ่มกรองถ้าไม่มีรายการที่มีชุดทดแทน
        if (!hasSuggestionItems && filterSuggestionsBtn) {
            filterSuggestionsBtn.classList.add('hidden');
        }
    });
</script>
@endsection
