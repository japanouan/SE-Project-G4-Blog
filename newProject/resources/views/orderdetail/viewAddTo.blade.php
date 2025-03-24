@extends('layouts.main')

@section('content')
<div class="container mx-auto p-6 bg-gray-50">
    <h2 class="text-3xl font-bold mb-8 text-gray-800 border-b pb-3">ทำการสั่งซื้อ</h2>

    <div class="flex flex-col lg:flex-row gap-8 max-w-7xl mx-auto">
        <!-- รายการสินค้าในตะกร้า -->
        <div class="lg:w-3/5 space-y-4">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">รายการสินค้า</h3>
            
            @foreach ($cartItems as $cartItem)
                <div class="bg-white shadow-md rounded-lg p-4 flex gap-6 hover:shadow-lg transition duration-300 border-l-4 {{ $cartItem->overent == 1 ? 'border-yellow-400' : 'border-green-500' }}">
                    <img src="{{ asset($cartItem->outfit->image) }}" class="w-28 h-28 object-cover rounded-lg border">

                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                                {{ $cartItem->outfit->name }}
                                @if($cartItem->overent == 1)
                                    <span class="text-xs bg-yellow-400 text-white px-2 py-1 rounded-full">สั่งเพิ่ม</span>
                                @endif
                            </h4>
                            <div class="flex flex-wrap gap-x-4 mt-1 text-gray-600 text-sm">
                                <p>สี: <span class="font-medium">{{ $cartItem->color->color ?? 'ไม่ระบุ' }}</span></p>
                                <p>ขนาด: <span class="font-medium">{{ $cartItem->size->size ?? 'ไม่ระบุ' }}</span></p>
                                <p>จำนวน: <span class="font-medium">{{ $cartItem->quantity }}</span></p>
                                @if(isset($cartItem->sizeAndColor))
                                    <p>คงเหลือ: <span class="font-medium">{{ $cartItem->overent == 1 ? '-' : $cartItem->sizeAndColor->amount }}</span></p>
                                @endif
                            </div>
                        </div>

                        <div class="flex justify-between items-end mt-2">
                            <p class="text-green-600 font-medium">{{ number_format($cartItem->outfit->price, 2) }} ฿</p>
                            <p class="font-bold text-lg text-gray-800">{{ number_format($cartItem->outfit->price * $cartItem->quantity, 2) }} ฿</p>
                        </div>
                    </div>
                </div>
            @endforeach


            <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-blue-500 mt-6">
                <p class="text-sm text-gray-600">* การให้บริการของเราอยู่ภายใต้เงื่อนไขและข้อตกลงการใช้บริการ</p>
            </div>
        </div>

        <!-- แบบฟอร์มการสั่งซื้อ -->
        <div class="lg:w-2/5">
            <div class="bg-white shadow-md rounded-lg p-6 sticky top-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-6 pb-3 border-b">รายละเอียดการสั่งซื้อ</h3>
                
                <form action="{{ route('order.store') }}" method="POST">
                    @csrf
                    @foreach ($cartItems as $item)
                        <input type="hidden" name="cart_item_ids[]" value="{{ $item->cart_item_id }}">
                    @endforeach

                    <!-- วันที่รับชุด -->
                    <div class="mb-5">
                        <label for="pickup_date" class="block text-gray-700 font-medium mb-2">วันที่ต้องการรับชุด:</label>
                        <input type="date" id="pickup_date" name="pickup_date" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>


                    <!-- บริการเสริม -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-5">
                        <h4 class="font-medium text-gray-800 mb-3">บริการเสริม</h4>
                        <!-- photographer -->
                        <div class="mb-4">
                            <label class="flex justify-between text-gray-700 mb-2">
                                <span>จำนวนช่างภาพ:</span> 
                                <span class="text-sm text-gray-500">2,000 ฿/คน</span>
                            </label>
                            <div class="flex border border-gray-300 rounded-lg overflow-hidden">
                                <button type="button" onclick="decrementCount('photographer_count')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold">-</button>
                                <input type="number" id="photographer_count" name="photographer_count" min="0" value="0" class="w-full text-center p-2 focus:outline-none" required>
                                <button type="button" onclick="incrementCount('photographer_count')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold">+</button>
                            </div>
                        </div>
                        <!-- makeup -->
                        <div class="mb-2">
                            <label class="flex justify-between text-gray-700 mb-2">
                                <span>จำนวนช่างแต่งหน้า:</span>
                                <span class="text-sm text-gray-500">2,000 ฿/คน</span>
                            </label>
                            <div class="flex border border-gray-300 rounded-lg overflow-hidden">
                                <button type="button" onclick="decrementCount('makeup_count')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold">-</button>
                                <input type="number" id="makeup_count" name="makeup_count" min="0" value="0" class="w-full text-center p-2 focus:outline-none" required>
                                <button type="button" onclick="incrementCount('makeup_count')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold">+</button>
                            </div>
                        </div>
                    </div>

                    <!-- ใช้โค้ดโปรโมชัน -->
                    <div class="mb-5">
                        <label class="block text-gray-700 font-medium mb-2">โค้ดส่วนลด (ถ้ามี):</label>
                        <div class="flex gap-2">
                            <input type="text" name="promotion_code" id="promotion_code" placeholder="ใส่โค้ดโปรโมชั่น" class="flex-1 border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition" onclick="applyPromotion()">ใช้โค้ด</button>
                        </div>
                    </div>

                    <!-- โค้ดส่วนลดร้านค้า -->
                    @foreach ($outfits->groupBy('shop_id') as $shopId => $shopOutfits)
                        <div class="mb-6 border p-4 rounded-lg">
                            <h2 class="text-lg font-bold mb-2">ร้าน: {{ $shopOutfits->first()->shop->shop_name }}</h2>

                            @if (isset($activePromotions[$shopId]))
                                @php $promo = $activePromotions[$shopId]; @endphp
                                <div class="bg-green-100 p-3 rounded text-green-800 mb-2">
                                    โปรโมชั่น: <strong>{{ $promo->promotion_name }}</strong><br>
                                    ส่วนลด: {{ $promo->discount_amount }} บาท<br>
                                    ใช้ได้ถึง: {{ $promo->end_date }}<br>
                                    โค้ดส่วนลด: {{ $promo->promotion_code }}
                                </div>
                                <input type="hidden" name="promotions[{{ $shopId }}]" value="{{ $promo->promotion_id }}">
                            @else
                                <div class="text-gray-500">ไม่มีโปรโมชั่นสำหรับร้านนี้</div>
                            @endif
                        </div>
                    @endforeach

                    <!-- สรุปราคารวม -->
                    <div class="border-t pt-4 mt-4 space-y-2 text-sm text-gray-700">
                        <div class="flex justify-between">
                            <span>ราคาสินค้ารวม:</span>
                            <span id="product_total">{{ number_format($cartItems->sum(fn($i) => $i->quantity * $i->outfit->price), 2) }} ฿</span>
                        </div>
                        <div class="flex justify-between">
                            <span>ค่าบริการช่างภาพและช่างแต่งหน้า:</span>
                            <span id="staff_total">0.00 ฿</span>
                        </div>
                        <div class="flex justify-between">
                            <span>ส่วนลด:</span>
                            <span id="discount_total" class="text-red-500">0.00 ฿</span>
                        </div>
                        <div class="flex justify-between border-t border-dashed pt-3 mt-3 text-base">
                            <span class="font-semibold">ยอดชำระทั้งสิ้น:</span>
                            <span id="grand_total" class="font-bold text-green-600">{{ number_format($cartItems->sum(fn($i) => $i->quantity * $i->outfit->price), 2) }} ฿</span>
                        </div>

                        <!-- เลือกประเภทที่อยู่บริการเสริม -->
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-2">ที่อยู่สำหรับบริการเสริม:</label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="address_type" value="customer" checked onchange="toggleAddressInput()">
                                    <span>ใช้ที่อยู่เดียวกับลูกค้า</span>
                                </label>

                                
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="address_type" value="custom" onchange="toggleAddressInput()">
                                    <span>กรอกที่อยู่ใหม่</span>
                                </label>
                            </div>
                        </div>


                        <!-- ที่อยู่สำหรับช่างภาพและช่างแต่งหน้า -->
                    <!-- ฟอร์มกรอกที่อยู่ใหม่ -->
                    <div id="custom-address-form" class="bg-gray-50 p-4 rounded-lg mb-5 hidden">
                        <h4 class="font-medium text-gray-800 mb-3">ที่อยู่สำหรับบริการเสริม</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">จังหวัด</label>
                                <select id="staff_province" name="staff_address[province]"
                                        class="w-full border border-gray-300 rounded-lg p-3">
                                    <option value="">-- เลือกจังหวัด --</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">อำเภอ</label>
                                <select id="staff_district" name="staff_address[district]"
                                        class="w-full border border-gray-300 rounded-lg p-3">
                                    <option value="">-- เลือกอำเภอ --</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">ตำบล</label>
                                <select id="staff_subdistrict" name="staff_address[subdistrict]"
                                        class="w-full border border-gray-300 rounded-lg p-3">
                                    <option value="">-- เลือกตำบล --</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">รหัสไปรษณีย์</label>
                                <input type="text" id="staff_postal_code" name="staff_address[postal_code]" readonly
                                    class="w-full border border-gray-300 bg-gray-100 rounded-lg p-3">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-gray-700 font-medium mb-1">บ้านเลขที่</label>
                                <input type="text" name="staff_address[detail]"
                                    class="w-full border border-gray-300 rounded-lg p-3" placeholder="เช่น 123/4">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-gray-700 font-medium mb-1">ถนน</label>
                                <input type="text" name="staff_address[street]"
                                    class="w-full border border-gray-300 rounded-lg p-3" placeholder="เช่น สุขุมวิท 24">
                            </div>
                        </div>
                    </div>


                    </div>

                   <!-- ปุ่มสั่งซื้อ -->
                    <div class="mt-6">
                        <button type="submit" class="w-full px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-medium flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            ยืนยันการสั่งซื้อ
                        </button>
                    </div>

                    <!-- ✅ Hidden inputs ที่จำเป็น -->
                    <input type="hidden" id="total_price" name="total_price" value="0">
                    <input type="hidden" id="amount_staff" name="amount_staff" value="0">
                    <input type="hidden" name="selected_services[0][type]" value="photographer">
                    <input type="hidden" id="selected_photographer" name="selected_services[0][count]" value="0">
                    <input type="hidden" name="selected_services[1][type]" value="make-up artist">
                    <input type="hidden" id="selected_makeup" name="selected_services[1][count]" value="0">
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let currentDiscount = 0;

    function incrementCount(id) {
        const input = document.getElementById(id);
        input.value = parseInt(input.value) + 1;
        updateTotal();
    }

    function decrementCount(id) {
        const input = document.getElementById(id);
        let val = parseInt(input.value);
        if (val > 0) {
            input.value = val - 1;
            updateTotal();
        }
    }

    function updateTotal() {
    const productTotal = {{ $cartItems->sum(fn($i) => $i->quantity * $i->outfit->price) }};
    const staffPrice = 2000;
    const photographerCount = parseInt(document.getElementById('photographer_count').value) || 0;
    const makeupCount = parseInt(document.getElementById('makeup_count').value) || 0;
    const staffTotal = (photographerCount + makeupCount) * staffPrice;
    const grandTotal = productTotal + staffTotal - currentDiscount;

    // ส่งค่าไป hidden input
    document.getElementById('total_price').value = grandTotal;
    document.getElementById('amount_staff').value = photographerCount + makeupCount;
    document.getElementById('selected_photographer').value = photographerCount;
    document.getElementById('selected_makeup').value = makeupCount;

    document.getElementById('staff_total').innerText = staffTotal.toLocaleString('en-US', { minimumFractionDigits: 2 }) + ' ฿';
    document.getElementById('discount_total').innerText = currentDiscount.toLocaleString('en-US', { minimumFractionDigits: 2 }) + ' ฿';
    document.getElementById('grand_total').innerText = grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2 }) + ' ฿';
}


    async function applyPromotion() {
        const code = document.getElementById('promotion_code').value.trim();
        if (!code) return;

        const shopId = {{ $cartItems->first()->outfit->shop_id ?? 0 }};

        try {
            const res = await fetch('/api/check-promotion', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    code: code,
                    shop_id: shopId
                })
            });

            const data = await res.json();
            if (data.valid) {
                currentDiscount = data.discount;
                updateTotal();
            } else {
                alert(data.message);
                currentDiscount = 0;
                updateTotal();
            }
        } catch (err) {
            alert("เกิดข้อผิดพลาดในการใช้โปรโมชั่น");
            console.error(err);
        }
    }

    document.addEventListener("DOMContentLoaded", updateTotal);

    const provinceSelect = document.getElementById('staff_province');
    const districtSelect = document.getElementById('staff_district');
    const subdistrictSelect = document.getElementById('staff_subdistrict');
    const postalCodeInput = document.getElementById('staff_postal_code');

    let addressData = [];

    async function loadThaiAddress() {
        try {
            const res = await fetch('https://raw.githubusercontent.com/kongvut/thai-province-data/master/api_province_with_amphure_tambon.json');
            addressData = await res.json();

            // Fill provinces
            addressData.forEach(province => {
                const option = document.createElement('option');
                option.value = province.name_th;
                option.textContent = province.name_th;
                option.dataset.id = province.id;
                provinceSelect.appendChild(option);
            });
        } catch (error) {
            alert('ไม่สามารถโหลดข้อมูลที่อยู่ได้');
        }
    }

    provinceSelect.addEventListener('change', () => {
        districtSelect.innerHTML = '<option value="">-- เลือกอำเภอ --</option>';
        subdistrictSelect.innerHTML = '<option value="">-- เลือกตำบล --</option>';
        postalCodeInput.value = '';

        const selectedProvince = addressData.find(p => p.name_th === provinceSelect.value);
        selectedProvince?.amphure?.forEach(district => {
            const option = document.createElement('option');
            option.value = district.name_th;
            option.textContent = district.name_th;
            option.dataset.id = district.id;
            districtSelect.appendChild(option);
        });
    });

    districtSelect.addEventListener('change', () => {
        subdistrictSelect.innerHTML = '<option value="">-- เลือกตำบล --</option>';
        postalCodeInput.value = '';

        const selectedProvince = addressData.find(p => p.name_th === provinceSelect.value);
        const selectedDistrict = selectedProvince?.amphure?.find(d => d.name_th === districtSelect.value);
        selectedDistrict?.tambon?.forEach(subdistrict => {
            const option = document.createElement('option');
            option.value = subdistrict.name_th;
            option.textContent = subdistrict.name_th;
            option.dataset.zip = subdistrict.zip_code;
            subdistrictSelect.appendChild(option);
        });
    });

    subdistrictSelect.addEventListener('change', () => {
        const zip = subdistrictSelect.options[subdistrictSelect.selectedIndex]?.dataset?.zip;
        postalCodeInput.value = zip || '';
    });

    window.addEventListener('DOMContentLoaded', () => {
        loadThaiAddress();
    });

    function toggleAddressInput() {
    const customAddressForm = document.getElementById('custom-address-form');
    const selected = document.querySelector('input[name="address_type"]:checked').value;
    customAddressForm.classList.toggle('hidden', selected !== 'custom');
}

</script>
@endsection