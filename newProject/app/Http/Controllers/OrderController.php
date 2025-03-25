<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log; // เพิ่มบนหัวไฟล์
use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\CartItem;
use App\Models\ThaiOutfit;
use App\Models\Promotion;
use App\Models\Booking;
use App\Models\Shop;
use App\Models\Payment;
use App\Models\SelectService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use App\Models\CustomerAddress; 



class OrderController extends Controller
{
    public function viewAddTo(Request $request)
    {
        // รับค่าจากฟอร์ม และตรวจสอบว่าเป็น Array จริงๆ
        $cartItemIds = json_decode($request->input('cart_item_ids'), true);
    
        if (!is_array($cartItemIds) || empty($cartItemIds)) {
            return redirect()->route('cartItem.allItem')->with('error', 'กรุณาเลือกสินค้าที่ต้องการสั่งซื้อ');
        }
    
        // ดึงข้อมูลสินค้าจากตะกร้าที่ถูกเลือก
        $cartItems = CartItem::with(['outfit.shop', 'outfit.sizeAndColors.size', 'outfit.sizeAndColors.color'])
                         ->whereIn('cart_item_id', $cartItemIds)
                         ->orderBy('outfit_id')
                         ->get();
    
        // ดึง outfit_id ทั้งหมดจาก cartItems
        $outfitIds = $cartItems->pluck('outfit_id')->unique();
    
        // ดึงข้อมูลชุดที่เกี่ยวข้อง
        $outfits = ThaiOutfit::with(['categories', 'sizeAndColors.size', 'sizeAndColors.color'])
                         ->whereIn('outfit_id', $outfitIds)
                         ->get();
        
        // ดึงโปรโมชั่นที่กำลังใช้งานได้
        $shop = null;
        $activePromotion = null;
        
        // หากสินค้าทั้งหมดมาจากร้านค้าเดียวกัน
        if ($outfits->isNotEmpty()) {
            $shop_id = $outfits->first()->shop_id ?? null;
            
            if ($shop_id) {
                $shop = Shop::find($shop_id);
                
                if ($shop) {
                    $activePromotion = Promotion::where('shop_id', $shop_id)
                                               ->where('is_active', true)
                                               ->where('start_date', '<=', now())
                                               ->where('end_date', '>=', now())
                                               ->first();
                }
            }
        }
    
        return view('order.viewAddTo', compact('cartItems', 'outfits', 'activePromotion', 'shop'));
    }



    public function store(Request $request)
    {
        $user = Auth::user();
        DB::beginTransaction();
    
        try {
            // dd($request->all());
            Log::debug('เริ่ม store()');
    
            // ✅ ตรวจสอบประเภทที่อยู่
            $addressType = $request->input('address_type');
            $staffAddressId = null;
            $customerAddressId = null;
    
            if ($addressType === 'custom') {
                $staffAddressData = $request->input('staff_address');
                $staffAddress = new Address();
                $staffAddress->Province = $staffAddressData['province'] ?? '';
                $staffAddress->District = $staffAddressData['district'] ?? '';
                $staffAddress->Subdistrict = $staffAddressData['subdistrict'] ?? '';
                $staffAddress->PostalCode = $staffAddressData['postal_code'] ?? '';
                $staffAddress->HouseNumber = $staffAddressData['detail'] ?? '';
                $staffAddress->Street = $staffAddressData['street'] ?? '';
                $staffAddress->CreatedAt = now();
                $staffAddress->save();
    
                $staffAddressId = $staffAddress->AddressID;
    
                $customerAddress = new CustomerAddress();
                $customerAddress->customer_id = $user->user_id;
                $customerAddress->AddressID = $staffAddressId;
                $customerAddress->AddressName = 'บริการเสริม';
                $customerAddress->save();
    
                $customerAddressId = $customerAddress->cus_address_id;
            } else {
                $user->load('customerAddress.address');
                $customerAddress = $user->customerAddress;
                if ($customerAddress && $customerAddress->address) {
                    $customerAddressId = $customerAddress->cus_address_id;
                    $staffAddressId = $customerAddress->address->AddressID;
                } else {
                    throw new \Exception('ไม่พบที่อยู่ลูกค้า กรุณาเพิ่มที่อยู่ก่อนทำรายการ');
                }
            }
    
            // ✅ ดึง cart items พร้อม overent
            $cartItemIds = $request->cart_item_ids;
            $cartItems = CartItem::with('outfit')
                ->select('*')
                ->whereIn('cart_item_id', $cartItemIds)
                ->get();
    
            // ✅ จัดกลุ่ม CartItem ตาม shop_id และ reservation_date
            $groupedItems = $cartItems->groupBy(function ($item) {
                return $item->outfit->shop_id . '|' . $item->reservation_date;
            });
    
            // ✅ วนลูปสร้าง Booking สำหรับแต่ละกลุ่ม (1 ร้าน 1 วัน = 1 Booking)
            $bookings = [];
            foreach ($groupedItems as $groupKey => $groupItems) {
                // แยก shop_id และ reservation_date จาก key
                [$shop_id, $reservation_date] = explode('|', $groupKey);
    
                // ✅ แยกชุดในกลุ่มนี้
                $normalItems = $groupItems->where('overent', 0);
                $overentItems = $groupItems->where('overent', 1);
    
                // ✅ คำนวณ hasOverrented สำหรับกลุ่มนี้
                $hasOverrented = $overentItems->isNotEmpty();
    
                // ✅ บริการเสริมสำหรับกลุ่มนี้
                $services = $request->input("selected_services.{$shop_id}.{$reservation_date}", []);
                $staffTotal = 0;
                if (!empty($services)) {
                    $staffTotal = (
                        (int)($services['photographer']['count'] ?? 0) +
                        (int)($services['makeup']['count'] ?? 0)
                    ) * 2000;
                }
    
                // ✅ โปรโมชั่น (แยกตามร้าน)
                $promotionCode = $request->input('promotion_code');
                $promotion = null;
                $discountAmount = 0;
    
                if ($promotionCode) {
                    $promotion = Promotion::where('promotion_code', $promotionCode)
                        ->where('shop_id', $shop_id)
                        ->where('is_active', true)
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now())
                        ->first();
    
                    if ($promotion) {
                        $discountAmount = $promotion->discount_amount;
                    }
                }
    
                // ✅ ยอดรวมสินค้าสำหรับกลุ่มนี้
                $productTotal = $normalItems->sum(fn($item) => $item->quantity * $item->outfit->price);
                $totalWithDiscount = max($productTotal + $staffTotal - $discountAmount, 0);
    
                // ✅ สถานะ
                $bookingStatus = $overentItems->isNotEmpty() ? 'partial paid' : 'confirmed';
    
                // ✅ สร้าง Booking สำหรับกลุ่มนี้
                $booking = Booking::create([
                    'purchase_date' => now(),
                    'total_price' => $totalWithDiscount,
                    'status' => $bookingStatus,
                    'shop_id' => $shop_id,
                    'user_id' => $user->user_id,
                    'promotion_id' => $promotion?->promotion_id,
                    'AddressID' => $customerAddressId,
                    'hasOverrented' => $hasOverrented,
                ]);
    
                $bookings[$groupKey] = $booking;
    
                // ✅ บันทึก OrderDetail & Payment สำหรับกลุ่มนี้
                foreach ($groupItems as $item) {
                    $cycle = $item->overent == 1 ? 2 : 1;
    
                    // ✅ กำหนด reservation_date จาก CartItem โดยตรง
                    $reservationDate = $item->reservation_date;
                    if ($item->overent == 1) {
                        // ค้นหา CartItem ที่มี outfit_id, size_id, color_id เหมือนกัน และ overent == 0
                        $matchingItem = $cartItems->first(function ($cartItem) use ($item) {
                            return $cartItem->outfit_id == $item->outfit_id &&
                                   $cartItem->size_id == $item->size_id &&
                                   $cartItem->color_id == $item->color_id &&
                                   $cartItem->overent == 0;
                        });
    
                        // ถ้าพบ CartItem ที่ตรงเงื่อนไข ให้ใช้ reservation_date ของมัน
                        if ($matchingItem) {
                            $reservationDate = $matchingItem->reservation_date;
                        }
                    }
    
                    // ตรวจสอบว่า reservation_date มีค่าหรือไม่
                    if (!$reservationDate) {
                        throw new \Exception("ไม่พบวันที่จองสำหรับ CartItem ID: {$item->cart_item_id}");
                    }
    
                    $orderDetailData = [
                        'quantity' => $item->quantity,
                        'booking_cycle' => $cycle,
                        'booking_id' => $booking->booking_id,
                        'cart_item_id' => $item->cart_item_id,
                        'reservation_date' => $reservationDate,
                        'deliveryOptions' => 'default',
                    ];
    
                    if ($item->overent == 0) {
                        $orderDetailData['total'] = $item->quantity * $item->outfit->price;
                    }
    
                    OrderDetail::create($orderDetailData);
    
                    if ($cycle == 1 && $item->overent != 2) {
                        Payment::create([
                            'payment_method' => 'paypal',
                            'total' => $item->quantity * $item->outfit->price,
                            'status' => 'paid',
                            'booking_cycle' => '1',
                            'booking_id' => $booking->booking_id,
                        ]);
                    }
    
                    $item->status = 'REMOVED';
                    $item->purchased_at = now();
                    $item->save();
                }
    
                // ✅ บันทึกบริการเสริมสำหรับกลุ่มนี้
                if (!empty($services)) {
                    if (isset($services['photographer']) && $services['photographer']['count'] > 0) {
                        $datetimeString = $reservation_date . ' ' . $services['photographer']['time'] . ':00';
                        $datetime = new \DateTime($datetimeString);
                        SelectService::create([
                            'service_type' => 'photographer',
                            'customer_count' => $services['photographer']['count'],
                            'reservation_date' => $datetime, // ใช้ reservation_date เดียวกับ Booking
                            'booking_id' => $booking->booking_id,
                            'AddressID' => $staffAddressId,
                        ]);
                    }
                    if (isset($services['makeup']) && $services['makeup']['count'] > 0) {
                        $datetimeString = $reservation_date . ' ' . $services['makeup']['time'] . ':00';
                        $datetime = new \DateTime($datetimeString);
                        SelectService::create([
                            'service_type' => 'make-up artist',
                            'customer_count' => $services['makeup']['count'],
                            'reservation_date' => $reservation_date, // ใช้ reservation_date เดียวกับ Booking
                            'reservation_time' => $services['makeup']['time'] ?? null, // เพิ่มเวลา
                            'booking_id' => $booking->booking_id,
                            'AddressID' => $staffAddressId,
                        ]);
                    }
                }
            }
    
            DB::commit();
            return redirect()->route('cartItem.allItem')->with('success', 'ทำรายการสำเร็จ');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('เกิดข้อผิดพลาด:', ['message' => $e->getMessage(), 'line' => $e->getLine()]);
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    



    
    
    
    


    

}