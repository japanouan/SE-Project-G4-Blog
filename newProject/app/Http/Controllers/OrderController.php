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
        $addressIdFromUser = optional($user->address)->AddressID;

        try {
            $cartItemIds = $request->input('cart_item_ids', []);
            $cartItems = CartItem::with('outfit')->whereIn('cart_item_id', $cartItemIds)->get();

            // แยกสินค้าตาม overent
            $itemsReady = $cartItems->where('overent', 0);
            $itemsOver = $cartItems->where('overent', 1);

            $createdBookings = [];

            foreach ([
                ['items' => $itemsReady, 'status' => 'confirmed', 'cycle' => 1],
                ['items' => $itemsOver, 'status' => 'pending', 'cycle' => 2],
            ] as $group) {

                if ($group['items']->isEmpty()) continue;

                $firstItem = $group['items']->first();
                $shop_id = $firstItem->outfit->shop_id ?? 1;

                $booking = new Booking();
                $booking->purchase_date = now();
                $booking->total_price = $group['items']->sum(fn($i) => $i->quantity * $i->outfit->price);
                $booking->pickup_date = $request->pickup_date ?? now();
                $booking->status = $group['status'];
                $booking->shop_id = $shop_id;
                $booking->user_id = $user->user_id;
                $booking->promotion_id = $request->input('promotions')[$shop_id] ?? null;
                $booking->AddressID = $addressIdFromUser;
                $booking->save();

                // เก็บ OrderDetail
                foreach ($group['items'] as $item) {
                    $orderDetail = new OrderDetail();
                    $orderDetail->quantity = $item->quantity;
                    $orderDetail->total = $item->quantity * $item->outfit->price;
                    $orderDetail->booking_cycle = $group['cycle'];
                    $orderDetail->booking_id = $booking->booking_id;
                    $orderDetail->cart_item_id = $item->cart_item_id;
                    $orderDetail->deliveryOptions = 'default';
                    $orderDetail->save();

                    // เปลี่ยนสถานะ CartItem
                    $item->status = 'REMOVED';
                    $item->save();
                }

                // บันทึกบริการเสริม (แค่ให้ Booking แรกก็พอ)
                if (empty($createdBookings)) {
                    if ($request->has('selected_services')) {
                        foreach ($request->selected_services as $service) {
                            if (!isset($service['type']) || !isset($service['count'])) continue;
                            SelectService::create([
                                'service_type' => $service['type'],
                                'customer_count' => $service['count'],
                                'reservation_date' => now(),
                                'booking_id' => $booking->booking_id,
                                'AddressID' => $request->address_id ?? null
                            ]);
                        }
                    }
                    $createdBookings[] = $booking->booking_id;
                }
            }

            DB::commit();
            return redirect()->route('orderdetail.viewAddTo')->with('success', 'สั่งซื้อสำเร็จ');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('OrderStore Error:', ['msg' => $e->getMessage(), 'line' => $e->getLine()]);
            return back()->withErrors(['error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
        }
    }


    


    

}