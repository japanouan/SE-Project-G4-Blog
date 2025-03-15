<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\CartItem;
use App\Models\ThaiOutfit;
use App\Models\ThaiOutfitCategories;
use App\Models\OutfitCategories; // เรียกใช้ Model OutfitCategories
use App\Models\ThaiOutfitSizeAndColor;

class OrderDetailController extends Controller
{
    public function index($idOutfit)
    {
        $outfit = ThaiOutfit::with(['categories', 'sizeAndColors.size', 'sizeAndColors.color'])
                            ->findOrFail($idOutfit);

        
        return view('orderdetail.index', compact('outfit'));
    }

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
    
        return view('orderdetail.viewAddTo', compact('cartItems', 'outfits'));
    }
    
    

    

    public function addTo(Request $request)
{
    $request->validate([
        'cart_item_id' => 'required|exists:cart_items,id', // ตรวจสอบว่าสินค้าอยู่ในตะกร้าหรือไม่
        'booking_cycle' => 'required|in:1,2', // ตรวจสอบค่าที่รับได้ (1 หรือ 2)
        'deliveryOptions' => 'required|in:self pick-up,delivery', // ตรวจสอบรูปแบบการจัดส่ง
    ]);

    // ดึงข้อมูลสินค้าจากตะกร้า
    $cartItem = CartItem::findOrFail($request->cart_item_id);
    
    // คำนวณ total (ราคา x จำนวน)
    $total = $cartItem->outfit->price * $cartItem->quantity;

    // สร้าง `OrderDetail`
    $orderDetail = OrderDetail::create([
        'cart_item_id' => $cartItem->id,
        'quantity' => $cartItem->quantity,
        'total' => $total,
        'booking_cycle' => $request->booking_cycle,
        'booking_id' => null, // กรณีที่ยังไม่มี `booking_id` ให้เป็น `null`
        'deliveryOptions' => $request->deliveryOptions,
        'created_at' => now(),
    ]);

    return redirect()->route('orderdetail.index', ['idOutfit' => $cartItem->outfit_id])
                     ->with('success', 'เพิ่มสินค้าเข้าสู่คำสั่งซื้อเรียบร้อย');
}




    
}


