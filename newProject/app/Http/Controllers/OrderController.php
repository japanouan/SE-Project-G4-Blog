<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\CartItem;
use App\Models\ThaiOutfit;
use App\Models\Promotion;
use App\Models\Booking;
use App\Models\Shop;
use App\Models\SelectService;
use Illuminate\Support\Facades\DB;


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



<<<<<<< HEAD


public function store(Request $request)
{
    $user = Auth::user();
    DB::beginTransaction();

    try {
        // 1. สร้าง Booking
        $booking = new Booking();
        $booking->purchase_date = now();
        $booking->total_price = $request->total_price ?? 0; // ใส่ค่านี้ตามต้องการ หรือคำนวณอีกที
        $booking->amount_staff = $request->amount_staff ?? 0;
        $booking->status = 'pending';
        $booking->shop_id = $request->shop_id ?? 1; // ถ้ามีหลายร้าน เอาค่าจริง
        $booking->user_id = auth()->id(); // หรือ $request->user_id
        $booking->promotion_id = $request->promotion_id ?? null;
        $booking->AddressID = $request->address_id ?? null;
        $booking->save();

        // 2. สร้าง OrderDetail
        foreach ($request->cart_items as $item) {
            $orderDetail = new OrderDetail();
            $orderDetail->quantity = $item['quantity'];
            $orderDetail->total = $item['total'];
            $orderDetail->booking_cycle = $request->booking_cycle;
            $orderDetail->booking_id = $booking->booking_id;
            $orderDetail->cart_item_id = $item['cart_item_id'];
            $orderDetail->deliveryOptions = $request->delivery_option;
            $orderDetail->save();
=======
    public function store(Request $request)
    {
        $user = Auth::user();
        DB::beginTransaction();
    
        try {
            Log::debug('เริ่ม store()');
    
            $firstCartItem = CartItem::with('outfit')->find($request->cart_item_ids[0]);
            $shop_id = $firstCartItem?->outfit?->shop_id ?? 1;
            Log::debug('Shop ID:', ['shop_id' => $shop_id]);
    
            $promotions = $request->input('promotions', []);
            $promotionId = is_array($promotions) ? reset($promotions) : null;
            
            Log::debug('Promotion ID:', ['promotion_id' => $promotionId]);
    
            $booking = new Booking();
            $booking->purchase_date = now();
            $booking->total_price = $request->total_price ?? 0;
            $booking->amount_staff = $request->amount_staff ?? 0;
            $booking->status = 'pending';
            $booking->shop_id = $shop_id;
            $booking->user_id = $user->user_id;
            $booking->promotion_id = $promotionId;
            $booking->AddressID = $request->address_id ?? null;
            $booking->save();
    
            Log::debug('บันทึก Booking แล้ว:', $booking->toArray());
    
            foreach ($request->cart_item_ids as $cartItemId) {
                $cartItem = CartItem::with('outfit')->find($cartItemId);
                if (!$cartItem) {
                    Log::warning("CartItem ไม่พบ", ['id' => $cartItemId]);
                    continue;
                }
    
                $orderDetail = new OrderDetail();
                $orderDetail->quantity = $cartItem->quantity;
                $orderDetail->total = $cartItem->quantity * $cartItem->outfit->price;
                $orderDetail->booking_cycle = 1;
                $orderDetail->booking_id = $booking->booking_id;
                $orderDetail->cart_item_id = $cartItem->cart_item_id;
                $orderDetail->deliveryOptions = 'default';
                $orderDetail->save();
    
                Log::debug('บันทึก OrderDetail แล้ว:', $orderDetail->toArray());
            }
    
            if ($request->has('selected_services') && is_array($request->selected_services)) {
                foreach ($request->selected_services as $service) {
                    if (!isset($service['type']) || !isset($service['count'])) {
                        Log::warning('selected_service ข้อมูลไม่ครบ', ['service' => $service]);
                        continue;
                    }
    
                    $selectService = new SelectService();
                    $selectService->service_type = $service['type'];
                    $selectService->customer_count = $service['count'];
                    $selectService->reservation_date = now();
                    $selectService->booking_id = $booking->booking_id;
                    $selectService->AddressID = $request->address_id ?? null;
                    $selectService->save();
    
                    Log::debug('บันทึก SelectService แล้ว:', $selectService->toArray());
                }
            }
    
            DB::commit();
            Log::debug('จองสำเร็จ');
            return redirect()->back()->with('success', 'ลบสินค้าออกจากตะกร้าสำเร็จ');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('เกิดข้อผิดพลาด:', ['message' => $e->getMessage(), 'line' => $e->getLine()]);
            return back()->withErrors(['error' => $e->getMessage()]);
>>>>>>> c1f926b6776e86af2af537590bc9f880b4f8ff99
        }

        // 3. สร้าง SelectService
        if ($request->has('selected_services')) {
            foreach ($request->selected_services as $service) {
                $selectService = new SelectService();
                $selectService->service_type = $service['type']; // 'photographer' หรือ 'make-up artist'
                $selectService->customer_count = $service['count']; // เช่น 1
                $selectService->reservation_date = now(); // หรือรับจาก form ก็ได้
                $selectService->booking_id = $booking->booking_id;
                $selectService->AddressID = $request->address_id ?? null;
                $selectService->save();
            }
        }

        DB::commit();
        return redirect()->route('booking.success')->with('success', 'จองสำเร็จ');
        
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}

}
