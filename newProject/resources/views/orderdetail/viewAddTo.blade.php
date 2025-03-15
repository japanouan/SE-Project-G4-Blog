@extends('layouts.main')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6 text-start">ยืนยันการสั่งซื้อ</h2>

    <div class="card max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6">
        <div class="card-body">
            @foreach ($cartItems as $cartItem)
                <div class="order-item mb-4 border-b pb-4">
                    <h4 class="text-xl font-semibold">ชุด: {{ $cartItem->outfit->name }}</h4>
                    <p class="text-lg">สี: {{ $cartItem->outfit->sizeAndColors->where('color_id', $cartItem->color_id)->first()->color->color_name ?? 'ไม่ระบุ' }}</p>
                    <p class="text-lg">ขนาด: {{ $cartItem->outfit->sizeAndColors->where('size_id', $cartItem->size_id)->first()->size->size_name ?? 'ไม่ระบุ' }}</p>
                    <p class="text-lg">จำนวน: {{ $cartItem->quantity }}</p>
                    <p class="text-lg">ราคาต่อหน่วย: {{ number_format($cartItem->outfit->price, 2) }} บาท</p>
                    <p class="text-lg font-semibold">รวมทั้งหมด: {{ number_format($cartItem->quantity * $cartItem->outfit->price, 2) }} บาท</p>
                </div>
            @endforeach

            <form action="{{ route('orderdetail.addTo') }}" method="POST" class="mt-6">
                @csrf
                @foreach ($cartItems as $cartItem)
                    <input type="hidden" name="cart_item_ids[]" value="{{ $cartItem->id }}">
                @endforeach

                <div class="mb-4">
                    <label for="booking_cycle" class="block text-lg font-medium">รอบการจอง:</label>
                    <select name="booking_cycle" class="w-full p-2 border rounded-md" required>
                        <option value="1">1 วัน</option>
                        <option value="2">2 วัน</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="deliveryOptions" class="block text-lg font-medium">วิธีการรับสินค้า:</label>
                    <select name="deliveryOptions" class="w-full p-2 border rounded-md" required>
                        <option value="self pick-up">รับเอง</option>
                        <option value="delivery">จัดส่ง</option>
                    </select>
                </div>

                <div class="mt-6 text-center">
                    <button type="submit" class="px-6 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition duration-300">ยืนยันการสั่งซื้อ</button>
                </div>
            </form>
        </div>
    </div>
</div>   
@endsection
