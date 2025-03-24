<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\CartItem;
use App\Models\ThaiOutfit;
use App\Models\ThaiOutfitCategories;
use App\Models\OutfitCategories;
use App\Models\ThaiOutfitSizeAndColor;
use App\Models\Promotion;
use App\Models\Booking;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;

class OrderDetailController extends Controller
{
    //Outfit details
    public function index($idOutfit)
    {
        $outfit = ThaiOutfit::with(['categories', 'sizeAndColors.size', 'sizeAndColors.color'])
                            ->findOrFail($idOutfit);
        $booking = CartItem::all();

        // dd($booking->first());
        
        return view('orderdetail.index', compact('outfit'));
    }

    public function viewAddTo(Request $request)
{
    $cartItemIds = json_decode($request->input('cart_item_ids'), true);

    if (!is_array($cartItemIds) || empty($cartItemIds)) {
        return redirect()->route('cartItem.allItem')->with('error', 'กรุณาเลือกสินค้าที่ต้องการสั่งซื้อ');
    }

    $cartItems = CartItem::with(['outfit', 'outfit.sizeAndColors.size', 'outfit.sizeAndColors.color'])
                    ->whereIn('cart_item_id', $cartItemIds)
                    ->orderBy('outfit_id')
                    ->get();

    $outfitIds = $cartItems->pluck('outfit_id')->unique();

    $outfits = ThaiOutfit::with(['categories', 'sizeAndColors.size', 'sizeAndColors.color'])
                    ->whereIn('outfit_id', $outfitIds)
                    ->get();

    // ✅ เก็บ promotion แยกร้าน (key => shop_id)
    $activePromotions = [];

    $shopIds = $outfits->pluck('shop_id')->unique();

    foreach ($shopIds as $shop_id) {
        $promotion = Promotion::where('shop_id', $shop_id)
            ->where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        if ($promotion) {
            $activePromotions[$shop_id] = $promotion;
        }
    }

    return view('orderdetail.viewAddTo', compact('cartItems', 'outfits', 'activePromotions'));
}

    
    public function addTo(Request $request)
    {
        $request->validate([
            'cart_item_ids' => 'required|array',
            'cart_item_ids.*' => 'exists:CartItems,cart_item_id',
            'booking_cycle' => 'required|in:1,2',
            'deliveryOptions' => 'required|in:self pick-up,delivery',
            'promotion_code' => 'nullable|string',
        ]);

        $cartItemIds = $request->cart_item_ids;
        $cartItems = CartItem::whereIn('cart_item_id', $cartItemIds)->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cartItem.allItem')->with('error', 'ไม่พบสินค้าที่ต้องการสั่งซื้อ');
        }
        
        // หาร้านค้าที่เป็นของสินค้าเหล่านี้
        $outfitIds = $cartItems->pluck('outfit_id')->unique();
        $firstOutfit = ThaiOutfit::find($outfitIds->first());
        $shop_id = $firstOutfit->shop_id ?? null;
        
        // คำนวณราคารวม
        $total_price = $cartItems->sum(function($item) {
            return $item->outfit->price * $item->quantity;
        });
        
        // ตรวจสอบโปรโมชั่น
        $promotion_id = null;
        if ($request->filled('promotion_code') && $shop_id) {
            $promotion = Promotion::where('promotion_code', $request->promotion_code)
                                 ->where('shop_id', $shop_id)
                                 ->where('is_active', true)
                                 ->where('start_date', '<=', now())
                                 ->where('end_date', '>=', now())
                                 ->first();
            
            if ($promotion) {
                $promotion_id = $promotion->promotion_id;
                // ลดราคาตามโปรโมชั่น
                $total_price = max(0, $total_price - $promotion->discount_amount);
            }
        }
        
        // สร้าง Booking
        $booking = Booking::create([
            'purchase_date' => now(),
            'total_price' => $total_price,
            'status' => 'pending', // สถานะเริ่มต้น
            'hasOverrented' => false,
            'created_at' => now(),
            'shop_id' => $shop_id,
            'promotion_id' => $promotion_id,
            'user_id' => Auth::id(), // เพิ่ม user_id เพื่อเชื่อมกับผู้ใช้
        ]);
        
        // สร้าง OrderDetail สำหรับทุกสินค้าในตะกร้า
        foreach ($cartItems as $cartItem) {
            OrderDetail::create([
                'quantity' => $cartItem->quantity,
                'total' => $cartItem->outfit->price * $cartItem->quantity,
                'booking_cycle' => $request->booking_cycle,
                'created_at' => now(),
                'booking_id' => $booking->booking_id,
                'cart_item_id' => $cartItem->cart_item_id,
                'deliveryOptions' => $request->deliveryOptions,
            ]);
            
            // อัพเดทสถานะ CartItem เป็น purchased
            $cartItem->purchased_at = now();
            $cartItem->save();
        }
        
        return redirect()->route('booking.confirmation', $booking->booking_id)
                         ->with('success', 'สั่งซื้อสำเร็จแล้ว!');
    }
}