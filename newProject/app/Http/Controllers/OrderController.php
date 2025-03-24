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
        $cartItems = CartItem::with(['outfit', 'outfit.sizeAndColors.size', 'outfit.sizeAndColors.color'])
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
            Log::debug('เริ่ม store()');
    
            // ✅ ตรวจสอบประเภทที่อยู่: 'customer' หรือ 'custom'
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
    
            // ✅ ดึงรายการสินค้าในตะกร้า
            $cartItemIds = $request->cart_item_ids;
            $cartItems = CartItem::with('outfit')->whereIn('cart_item_id', $cartItemIds)->get();
    
            // ✅ แยกชุด
            $normalItems = $cartItems->where('overent', 0);
            $overentItems = $cartItems->where('overent', 1);
    
            // ✅ ดึง shop_id
            $shop_id = $cartItems->first()?->outfit?->shop_id ?? 1;
    
            // ✅ ค่าบริการเสริม
            $services = $request->input('selected_services', []);
            $staffTotal = collect($services)->sum(fn($s) => $s['count']) * 2000;
    
            // ✅ ตรวจสอบโปรโมชั่น
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
    
            // ✅ ยอดรวมสินค้า
            $productTotal = $normalItems->sum(fn($item) => $item->quantity * $item->outfit->price);
            $totalWithDiscount = max($productTotal + $staffTotal - $discountAmount, 0);
    
            // ✅ สถานะ
            $bookingStatus = $overentItems->isNotEmpty() ? 'partial paid' : 'confirmed';
    
            // ✅ สร้าง Booking
            $booking = Booking::create([
                'purchase_date' => now(),
                'total_price' => $totalWithDiscount,
                'pickup_date' => $request->pickup_date ?? now(),
                'status' => $bookingStatus,
                'shop_id' => $shop_id,
                'user_id' => $user->user_id,
                'promotion_id' => $promotion?->promotion_id,
                'AddressID' => $customerAddressId,
            ]);
    
            // ✅ บันทึก OrderDetail
            foreach ($cartItems as $item) {
                $cycle = $item->overent == 1 ? 2 : 1;
    
                OrderDetail::create([
                    'quantity' => $item->quantity,
                    'total' => $item->quantity * $item->outfit->price,
                    'booking_cycle' => $cycle,
                    'booking_id' => $booking->booking_id,
                    'cart_item_id' => $item->cart_item_id,
                    'deliveryOptions' => 'default',
                ]);
    
                $item->status = 'REMOVED';
                $item->purchased_at = now();
                $item->save();
            }
    
            // ✅ บันทึกบริการเสริม
            foreach ($services as $s) {
                if ($s['count'] > 0) {
                    SelectService::create([
                        'service_type' => $s['type'],
                        'customer_count' => $s['count'],
                        'reservation_date' => now(),
                        'booking_id' => $booking->booking_id,
                        'AddressID' => $staffAddressId,
                    ]);
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