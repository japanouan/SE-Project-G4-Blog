@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- รูปภาพสินค้า -->
        <div class="col-md-5">
            <img src="{{ asset('storage/images/outfit.jpg') }}" class="img-fluid rounded" alt="ชุดงานแต่ง">
        </div>

        <!-- รายละเอียดสินค้า -->
        <div class="col-md-7">
            <h3>ชื่อชุด: ชุดงานแต่ง</h3>
            <p><strong>รหัสชุด:</strong> A001</p>
            <p><strong>ประเภทชุด:</strong> สีบล</p>
            <p><strong>ประเภทผ้า:</strong> ผ้าไหม</p>
            <p><strong>ราคาซื้อ:</strong> 20,000.00 บาท</p>
            <p><strong>ราคาขาย:</strong> 2,000.00 บาท</p>
            <p><strong>ราคามัดจำ:</strong> 2,000.00 บาท</p>

            <!-- สีของชุด -->
            <p><strong>สีชุด:</strong></p>
            <button class="btn btn-light">ขาว</button>
            <button class="btn btn-light">สีบล</button>
            <button class="btn btn-light">สีทอง</button>
            <button class="btn btn-light">เงิน</button>

            <!-- ขนาดชุด -->
            <p class="mt-3"><strong>ขนาดชุด:</strong></p>
            <button class="btn btn-outline-dark">S</button>
            <button class="btn btn-outline-dark">M</button>
            <button class="btn btn-outline-dark">L</button>
            <button class="btn btn-outline-dark">XL</button>

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
