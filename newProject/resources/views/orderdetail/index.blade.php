@extends('layouts.main')

@section('content')
<div class="container mx-auto mt-8">
    <div class="flex flex-wrap lg:flex-nowrap gap-8">
        <!-- รูปภาพสินค้า -->
        <div class="w-full lg:w-2/5">
            <img src="{{ asset($outfit->image) }}" class="w-full h-auto rounded-lg shadow-md" alt="{{ $outfit->name }}">
        </div>

        <!-- รายละเอียดสินค้า -->
        <div class="w-full lg:w-3/5 bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold text-gray-800">{{ $outfit->name }}</h2>
            <p class="text-gray-600 mt-1"><strong>รหัสชุด:</strong> {{ $outfit->outfit_id }}</p>
            <p><strong>หมวดหมู่:</strong>
                @foreach($outfit->categories as $category)
                    {{ $category->category_name }}{{ !$loop->last ? ', ' : '' }}
                @endforeach
            </p>

            <p class="text-gray-600"><strong>ประเภทผ้า:</strong> {{ $outfit->fabric }}</p>

            <!-- ราคา -->
            <div class="mt-4">
                <p class="text-lg font-semibold">ราคาขาย: <span class="text-red-500">{{ number_format($outfit->price, 2) }} บาท</span></p>
                <p class="text-gray-600">ราคามัดจำ: {{ number_format($outfit->deposit_price, 2) }} บาท</p>
            </div>

            <!-- สีของชุด -->
            <p class="mt-4 font-semibold">สีชุด:</p>
            <div class="flex gap-2 mt-2">
                @foreach(explode(',', $outfit->colors) as $color)
                    <button class="px-3 py-1 border rounded-md bg-gray-200 text-gray-700">{{ trim($color) }}</button>
                @endforeach
            </div>

            <!-- ขนาดชุด -->
            <p class="mt-4 font-semibold">ขนาดชุด:</p>
            <div class="flex gap-2 mt-2">
                @foreach(explode(',', $outfit->sizes) as $size)
                    <button class="px-3 py-1 border rounded-md bg-white text-gray-700 shadow-md">{{ trim($size) }}</button>
                @endforeach
            </div>

            <!-- จำนวนชุด -->
            <form action="{{ url('cartItem/addToCart') }}" method="POST">
                @csrf
                <input type="hidden" name="outfit_id" value="{{ $outfit->outfit_id }}">
                <input type="hidden" id="quantityInput" name="quantity" value="1">

                <!-- จำนวนชุด -->
                <p class="mt-4 font-semibold">จำนวนชุด:</p>
                <div class="flex items-center gap-2">
                    <button type="button" class="px-3 py-2 border rounded-md bg-gray-200 text-gray-700" onclick="decreaseQty()">-</button>
                    <input type="text" id="quantity" value="1" class="w-12 text-center border rounded-md" readonly>
                    <button type="button" class="px-3 py-2 border rounded-md bg-gray-200 text-gray-700" onclick="increaseQty()">+</button>
                </div>

                <!-- ปุ่มเช่า, ซื้อ, เพิ่มลงตะกร้า -->
                <div class="mt-6 flex gap-4">
                    <a href="{{ url('rental/create/' . $outfit->outfit_id) }}" 
                        class="px-6 py-2 border border-green-500 text-green-500 rounded-md hover:bg-green-500 hover:text-white transition">
                        เช่า
                    </a>

                    <a href="{{ url('purchase/create/' . $outfit->outfit_id) }}" 
                        class="px-6 py-2 border border-blue-500 text-blue-500 rounded-md hover:bg-blue-500 hover:text-white transition">
                        ซื้อ
                    </a>

                    <button type="submit" 
                        class="px-6 py-2 border border-green-500 text-green-500 rounded-md hover:bg-green-500 hover:text-white transition">
                        เพิ่มลงตะกร้า
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript เพิ่ม-ลดจำนวนสินค้า -->
<script>
    function increaseQty() {
        let qty = document.getElementById('quantity');
        let qtyInput = document.getElementById('quantityInput');
        let newQty = parseInt(qty.value) + 1;
        qty.value = newQty;
        qtyInput.value = newQty;
    }

    function decreaseQty() {
        let qty = document.getElementById('quantity');
        let qtyInput = document.getElementById('quantityInput');
        if (qty.value > 1) {
            let newQty = parseInt(qty.value) - 1;
            qty.value = newQty;
            qtyInput.value = newQty;
        }
    }
</script>
@endsection
