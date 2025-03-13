@extends('layouts.main')

@section('title', 'Shopping Cart')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-xl font-bold mb-6">Shopping Cart</h2>

    @if($cartItems->isEmpty())
        <p class="text-gray-600">ไม่มีสินค้าในตะกร้า</p>
    @else
        <div class="space-y-4">
            @foreach($cartItems as $cartItem)
                <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-md">
                    <!-- รูปสินค้า -->
                    <div class="flex items-center">
                        <img src="{{ $cartItem->outfit->image ? asset($cartItem->outfit->image) : asset('images/default-placeholder.png') }}" 
                        class="w-24 h-24 rounded-lg object-cover">

                        <div class="ml-4">
                            <h3 class="text-lg font-semibold">{{ $cartItem->outfit->name }}</h3>
                            <p class="text-green-600 font-bold">{{ number_format($cartItem->outfit->price, 0) }}฿ /1 days</p>
                        </div>
                    </div>

                    <!-- สีของชุด -->
                    <div class="ml-4">
                        <p class="text-md font-semibold">สี: 
                            <span class="text-gray-700">
                                {{ $cartItem->color->color ?? 'ไม่ระบุสี' }}
                            </span>
                        </p>
                    </div>


                    <!-- ขนาดของชุด -->
                    <div class="ml-4">
                        <p class="text-md font-semibold">ขนาด: 
                            <span class="text-gray-700">
                                {{ $cartItem->size->size ?? 'ไม่ระบุขนาด' }}
                            </span>
                        </p>
                    </div>

                    <!-- จำนวนสินค้า -->
                    <div class="flex items-center">
                        <button class="px-2 py-1 border rounded-md bg-gray-200" onclick="decreaseQty('{{ $cartItem->id }}')">-</button>
                        <input type="text" id="qty-{{ $cartItem->id }}" value="{{ $cartItem->quantity }}" class="w-12 text-center border rounded-md" readonly>
                        <button class="px-2 py-1 border rounded-md bg-gray-200" onclick="increaseQty('{{ $cartItem->id }}')">+</button>
                    </div>

                    <!-- Checkbox -->
                    <input type="checkbox" class="w-5 h-5 border-gray-300 rounded">

                    <!-- ปุ่มลบ -->
                    <form action="#" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-500 hover:text-red-700 text-xl">
                            ❌
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        <!-- ปุ่มสั่งจอง -->
        <div class="mt-6 text-right">
            <a href="#" class="px-6 py-2 border border-green-500 text-green-500 rounded-md hover:bg-green-500 hover:text-white transition">
                สั่งจอง
            </a>
        </div>
    @endif
</div>

<!-- JavaScript เพิ่ม-ลดจำนวน -->
<script>
    function increaseQty(id) {
        let qty = document.getElementById('qty-' + id);
        qty.value = parseInt(qty.value) + 1;
    }

    function decreaseQty(id) {
        let qty = document.getElementById('qty-' + id);
        if (qty.value > 1) {
            qty.value = parseInt(qty.value) - 1;
        }
    }
</script>
@endsection
