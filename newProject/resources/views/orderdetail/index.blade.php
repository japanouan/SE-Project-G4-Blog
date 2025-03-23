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
                {{ $outfit->categories->pluck('category_name')->join(', ') }}
            </p>

            <!-- ราคา -->
            <div class="mt-4">
                <p class="text-lg font-semibold">ราคาขาย: <span class="text-red-500">{{ number_format($outfit->price, 2) }} บาท</span></p>
                <p class="text-gray-600">ราคามัดจำ: {{ number_format($outfit->depositfee, 2) }} บาท</p>
                <p class="text-gray-600">ค่าปรับ: {{ number_format($outfit->penaltyfee, 2) }} บาท</p>
            </div>

            <!-- สีของชุด -->
            <p class="mt-4 font-semibold">สีชุด:</p>
            <div class="flex gap-2 mt-2">
                @foreach($outfit->sizeAndColors->unique('color_id') as $item)
                    <button type="button" class="color-option px-3 py-1 border rounded-md bg-gray-200 text-gray-700"
                        data-color-id="{{ $item->color_id }}">
                        {{ $item->color->color }}
                    </button>
                @endforeach
            </div>

            <!-- ขนาดของชุด -->
            <p class="mt-4 font-semibold">ขนาดชุด:</p>
            <div class="flex gap-2 mt-2">
                @foreach($outfit->sizeAndColors->unique('size_id') as $item)
                    <button type="button" class="size-option px-3 py-1 border rounded-md bg-gray-200 text-gray-700"
                        data-size-id="{{ $item->size_id }}">
                        {{ $item->size->size }}
                    </button>
                @endforeach
            </div>

            <!-- จำนวนชุด -->
            <p class="mt-4 font-semibold">จำนวนชุด: <span id="stockAmount">0</span></p>
            <div class="flex items-center gap-2">
                <button type="button" class="px-3 py-2 border rounded-md bg-gray-200 text-gray-700" onclick="decreaseQty()">-</button>
                <input type="text" id="quantity" value="1" class="w-12 text-center border rounded-md" readonly>
                <button type="button" class="px-3 py-2 border rounded-md bg-gray-200 text-gray-700" onclick="increaseQty()">+</button>
            </div>

            


            <!-- ปุ่มเช่า, ซื้อ, เพิ่มลงตะกร้า -->
            <!-- ฟอร์มเพิ่มสินค้าลงตะกร้า -->
            <form action="{{ url('cartItem/addToCart') }}" method="POST" onsubmit="return validateForm()">
                @csrf
                <input type="hidden" name="outfit_id" value="{{ $outfit->outfit_id }}">
                <input type="hidden" name="size_id" id="selectedSize" value="">
                <input type="hidden" name="color_id" id="selectedColor" value="">

                <input type="hidden" id="quantityInput" name="quantity" value="1">

                <div class="mt-6 flex gap-4">
                    <a href="{{ url('rental/create/' . $outfit->outfit_id) }}" 
                        class="px-6 py-2 border border-green-500 text-green-500 rounded-md hover:bg-green-500 hover:text-white transition">
                        เช่า
                    </a>
                    <button type="submit" 
                        class="px-6 py-2 border border-blue-500 text-blue-500 rounded-md hover:bg-blue-500 hover:text-white transition">
                        เพิ่มลงตะกร้า
                    </button>

                    <button type="button"
                        id="customOrderBtn"
                        class="hidden px-6 py-2 border border-yellow-500 text-yellow-500 rounded-md hover:bg-yellow-500 hover:text-white transition">
                        สั่งซื้อเพิ่มเติม
                    </button>

                </div>
            </form>

        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
    let stockData = @json($outfit->sizeAndColors);
    let selectedColor = null;
    let selectedSize = null;

    console.log("Stock Data Loaded:", stockData); // ✅ Debugging: ตรวจสอบข้อมูลสต็อก

    function updateStockDisplay() {
        console.log("Updating stock display..."); // ✅ Debugging

        if (selectedColor && selectedSize) {
            let stockItem = stockData.find(item => 
                item.color_id == selectedColor && item.size_id == selectedSize
            );

            let stockAmount = stockItem ? stockItem.amount : 0;
            console.log("Stock for selected size and color:", stockAmount); // ✅ Debugging

            document.getElementById("stockAmount").innerText = stockAmount;
        } else {
            document.getElementById("stockAmount").innerText = "0";
        }
    }

    // เมื่อกดปุ่มเลือกสี
    document.querySelectorAll(".color-option").forEach(button => {
        button.addEventListener("click", function() {
            selectedColor = this.getAttribute("data-color-id");
            document.getElementById("selectedColor").value = selectedColor; // ✅ อัปเดตค่า
            console.log("Selected Color ID:", selectedColor); // ✅ Debugging

            document.querySelectorAll(".color-option").forEach(btn => btn.classList.remove("bg-blue-500", "text-white"));
            this.classList.add("bg-blue-500", "text-white");

            updateStockDisplay();
        });
    });

    // เมื่อกดปุ่มเลือกขนาด
    document.querySelectorAll(".size-option").forEach(button => {
        button.addEventListener("click", function() {
            selectedSize = this.getAttribute("data-size-id");
            document.getElementById("selectedSize").value = selectedSize; // ✅ อัปเดตค่า
            console.log("Selected Size ID:", selectedSize); // ✅ Debugging

            document.querySelectorAll(".size-option").forEach(btn => btn.classList.remove("bg-blue-500", "text-white"));
            this.classList.add("bg-blue-500", "text-white");

            updateStockDisplay();
        });
    });


});

    


function increaseQty() {
    let qty = document.getElementById('quantity');
    let stock = parseInt(document.getElementById('stockAmount').innerText) || 0;
    let customOrderBtn = document.getElementById('customOrderBtn');

    if (stock > 0 && parseInt(qty.value) < stock) {
        qty.value = parseInt(qty.value) + 1;
        document.getElementById('quantityInput').value = qty.value;
        customOrderBtn.classList.add("hidden"); // ซ่อนปุ่มถ้ายังไม่เกิน
    } else {
        alert("จำนวนที่คุณต้องการมากกว่าจำนวนที่มีในร้าน หากต้องการสั่งเพิ่ม กรุณากดปุ่ม 'สั่งซื้อเพิ่มเติม'");
        customOrderBtn.classList.remove("hidden"); // แสดงปุ่มถ้าเกิน
    }
}



function decreaseQty() {
    let qty = document.getElementById('quantity');
    let customOrderBtn = document.getElementById('customOrderBtn');
    let stock = parseInt(document.getElementById('stockAmount').innerText) || 0;

    if (parseInt(qty.value) > 1) {
        qty.value = parseInt(qty.value) - 1;
        document.getElementById('quantityInput').value = qty.value;
    }

    if (parseInt(qty.value) <= stock) {
        customOrderBtn.classList.add("hidden");
    }
}


    function validateForm() {
        if (!selectedColor || !selectedSize) {
            alert("กรุณาเลือกสีและขนาดก่อนเพิ่มลงตะกร้า!");
            return false;
        }
        if (parseInt(document.getElementById('stockAmount').innerText) <= 0) {
            alert("สินค้าหมดสต็อก!");
            return false;
        }
        return true;
    }

    document.querySelectorAll(".color-option").forEach(button => {
    button.addEventListener("click", function() {
        selectedColor = this.getAttribute("data-color-id");
        document.getElementById("selectedColor").value = selectedColor;
    });
    });

    document.querySelectorAll(".size-option").forEach(button => {
        button.addEventListener("click", function() {
            selectedSize = this.getAttribute("data-size-id");
            document.getElementById("selectedSize").value = selectedSize;
        });
    });

    document.getElementById('customOrderBtn').addEventListener('click', function () {
    // สมมุติว่าคุณต้องการส่ง outfit_id ไปด้วย
        let outfitId = "{{ $outfit->outfit_id }}";
        window.location.href = "/custom-order/" + outfitId;
    });


</script>

@endsection
