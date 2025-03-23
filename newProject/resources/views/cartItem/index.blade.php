@extends('layouts.main')

@section('title', 'Shopping Cart')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-xl font-bold mb-6">Shopping Cart</h2>

    @if($cartItems->isEmpty())
        <p class="text-gray-600">ไม่มีสินค้าในตะกร้า</p>
    @else

    @php
        // เรียงรายการใน Blade ให้ overent = 0 ขึ้นก่อน
        $sortedItems = $cartItems->sortBy('overent');
    @endphp

    <div class="space-y-4">
    @foreach($sortedItems as $cartItem)
        <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-md">
            
            <!-- Checkbox สำหรับเลือกสินค้า -->
            <input type="checkbox" name="cart_item_ids[]" value="{{ $cartItem->cart_item_id }}" class="w-5 h-5 border-gray-300 rounded mr-4">

            <!-- รูปสินค้า -->
            <div class="flex items-center">
                <img src="{{ $cartItem->outfit->image ? asset($cartItem->outfit->image) : asset('images/default-placeholder.png') }}" 
                class="w-24 h-24 rounded-lg object-cover">

                <div class="ml-4">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        {{ $cartItem->outfit->name }}
                        @if($cartItem->overent == 1)
                            <span class="text-xs bg-yellow-400 text-white px-2 py-1 rounded-full">สั่งเพิ่ม</span>
                        @endif
                    </h3>
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

            <!-- สต็อก -->
            <p class="text-sm text-gray-500">
                คงเหลือ: 
                {{ $cartItem->overent == 1 ? '-' : ($cartItem->sizeAndColor->amount ?? 'ไม่ระบุ') }}
            </p>

            <!-- จำนวนสินค้า -->
            <div class="flex items-center">
                <button class="px-2 py-1 border rounded-md bg-gray-200" 
                    onclick="updateQty('{{ $cartItem->cart_item_id }}', -1, '{{ $cartItem->overent == 1 ? 'null' : ($cartItem->sizeAndColor->amount ?? 0) }}')">-</button>

                <input type="text" id="qty-{{ $cartItem->cart_item_id }}" 
                    value="{{ $cartItem->quantity }}" 
                    class="w-12 text-center border rounded-md" readonly>

                <button class="px-2 py-1 border rounded-md bg-gray-200" 
                    onclick="updateQty('{{ $cartItem->cart_item_id }}', 1, '{{ $cartItem->overent == 1 ? 'null' : ($cartItem->sizeAndColor->amount ?? 0) }}')">+</button>
            </div>

            <!-- ปุ่มลบ -->
            <form action="{{ route('cartItem.deleteItem') }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" name="cart_id" value="{{ $cartItem->cart_item_id }}">
                <button type="submit" class="text-red-500 hover:text-red-700 text-xl">
                    ❌
                </button>
            </form>

        </div>
    @endforeach
</div>

<!-- ปุ่มสั่งจอง -->
<form id="checkout-form" action="{{ route('orderdetail.viewAddTo') }}" method="POST">
    @csrf
    <input type="hidden" name="cart_item_ids" id="selected-cart-items">
    <button type="button" onclick="submitSelectedItems()" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
        ดำเนินการสั่งซื้อ
    </button>
</form>

    @endif
</div>

<!-- JavaScript เพิ่ม-ลดจำนวน -->
<script>
    function updateQty(cartId, change, maxStock) {
        let qtyInput = document.getElementById('qty-' + cartId);
        let newQty = parseInt(qtyInput.value) + change;

        if (newQty < 1) {
            alert("จำนวนสินค้าต้องไม่น้อยกว่า 1");
            return;
        }

        if (maxStock !== 'null' && newQty > parseInt(maxStock)) {
            alert("จำนวนสินค้าเกินจากที่มีในสต็อก! คงเหลือ: " + maxStock);
            return;
        }

        fetch("{{ route('cartItem.updateItem') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
            body: JSON.stringify({
                cart_id: cartId,
                quantity: newQty
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                qtyInput.value = newQty;
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }

    function submitSelectedItems() {
        let selectedItems = [];
        document.querySelectorAll('input[name="cart_item_ids[]"]:checked').forEach((checkbox) => {
            selectedItems.push(checkbox.value);
        });

        if (selectedItems.length === 0) {
            alert("กรุณาเลือกสินค้าอย่างน้อย 1 รายการ");
            return;
        }

        document.getElementById('selected-cart-items').value = JSON.stringify(selectedItems);
        document.getElementById('checkout-form').submit();
    }
</script>
@endsection
