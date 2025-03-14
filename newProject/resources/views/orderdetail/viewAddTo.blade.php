@extends('layouts.app')

@section('content')
<div class="container">
    <h2>ยืนยันการสั่งซื้อ</h2>

    <div class="card">
        <div class="card-body">
            @foreach ($cartItems as $cartItem)
                <div class="order-item">
                    <h4>ชุด: {{ $cartItem->outfit->name }}</h4>
                    <p>สี: {{ $cartItem->outfit->sizeAndColors->where('color_id', $cartItem->color_id)->first()->color->color_name ?? 'ไม่ระบุ' }}</p>
                    <p>ขนาด: {{ $cartItem->outfit->sizeAndColors->where('size_id', $cartItem->size_id)->first()->size->size_name ?? 'ไม่ระบุ' }}</p>
                    <p>จำนวน: {{ $cartItem->quantity }}</p>
                    <p>ราคาต่อหน่วย: {{ number_format($cartItem->outfit->price, 2) }} บาท</p>
                    <p>รวมทั้งหมด: {{ number_format($cartItem->quantity * $cartItem->outfit->price, 2) }} บาท</p>
                </div>
            @endforeach

            <form action="{{ route('orderdetail.addTo') }}" method="POST">
                @csrf
                @foreach ($cartItems as $cartItem)
                    <input type="hidden" name="cart_item_ids[]" value="{{ $cartItem->id }}">
                @endforeach

                <label for="booking_cycle">รอบการจอง:</label>
                <select name="booking_cycle" required>
                    <option value="1">1 วัน</option>
                    <option value="2">2 วัน</option>
                </select>

                <label for="deliveryOptions">วิธีการรับสินค้า:</label>
                <select name="deliveryOptions" required>
                    <option value="self pick-up">รับเอง</option>
                    <option value="delivery">จัดส่ง</option>
                </select>

                <button type="submit">ยืนยันการสั่งซื้อ</button>
            </form>
        </div>
    </div>
</div>
@endsection
