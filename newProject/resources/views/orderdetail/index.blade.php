@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- รูปภาพสินค้า -->
        <div class="col-md-5">
            <img src="{{ asset('storage/' . $outfit->image) }}" class="img-fluid rounded" alt="{{ $outfit->name }}">
        </div>

        <!-- รายละเอียดสินค้า -->
        <div class="col-md-7">
            <h3>ชื่อชุด: {{ $outfit->name }}</h3>
            <p><strong>รหัสชุด:</strong> {{ $outfit->outfit_id }}</p>
            <p><strong>ประเภทชุด:</strong> {{ $outfit->category }}</p>
            <p><strong>ประเภทผ้า:</strong> {{ $outfit->fabric }}</p>
            <p><strong>ราคาซื้อ:</strong> {{ number_format($outfit->price, 2) }} บาท</p>
            <p><strong>ราคาขาย:</strong> {{ number_format($outfit->price, 2) }} บาท</p>
            <p><strong>ราคามัดจำ:</strong> {{ number_format($outfit->price, 2) }} บาท</p>

            <!-- สีของชุด -->
            <p><strong>สีชุด:</strong></p>
            @foreach(explode(',', $outfit->colors) as $color)
                <button class="btn btn-light">{{ trim($color) }}</button>
            @endforeach

            <!-- ขนาดชุด -->
            <p class="mt-3"><strong>ขนาดชุด:</strong></p>
            @foreach(explode(',', $outfit->sizes) as $size)
                <button class="btn btn-outline-dark">{{ trim($size) }}</button>
            @endforeach

            <!-- จำนวนชุด -->
            <p class="mt-3"><strong>จำนวนชุด:</strong></p>
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-secondary" onclick="decreaseQty()">-</button>
                <input type="text" id="quantity" value="1" class="form-control text-center mx-2" style="width: 50px;">
                <button class="btn btn-outline-secondary" onclick="increaseQty()">+</button>
            </div>

            <!-- ปุ่มเช่า ซื้อ และ เพิ่มลงตะกร้า -->
            <div class="mt-4">
                <button class="btn btn-success">เช่า</button>
                <button class="btn btn-primary">ซื้อ</button>
                <button class="btn btn-outline-success">เพิ่มลงตะกร้า</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript เพิ่ม-ลดจำนวนสินค้า -->
<script>
    function increaseQty() {
        let qty = document.getElementById('quantity');
        qty.value = parseInt(qty.value) + 1;
    }

    function decreaseQty() {
        let qty = document.getElementById('quantity');
        if (qty.value > 1) {
            qty.value = parseInt(qty.value) - 1;
        }
    }
</script>  
@endsection
