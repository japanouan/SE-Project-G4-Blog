<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ThaiOutfit;
use App\Models\CartItem;
use App\Models\User;
use App\Models\ThaiOutfitSizeAndColor;

class CartItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $user = Auth::user();

        // ดึงข้อมูลรายการสินค้าที่อยู่ในตะกร้าของ User นั้นๆ พร้อมกับ Outfit, Size และ Color
        $cartItems = CartItem::with(['outfit', 'size', 'color']) // ✅ ดึงข้อมูลสัมพันธ์
                            ->where('userId', $user->user_id)
                            ->orderBy('outfit_id')
                            ->get();

        // ส่งข้อมูลไปที่หน้า View
        return view('cartItem.index', compact('cartItems'));
    }


    

    


    public function addToCart(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อนเพิ่มลงตะกร้า');
        }

        
    
        $user = Auth::user();
        $outfit_id = $request->input('outfit_id');
        $size_id = $request->input('size_id'); // รับค่าขนาดจากฟอร์ม
        $color_id = $request->input('color_id'); // รับค่าสีจากฟอร์ม
        $quantity = (int) $request->input('quantity', 1);
    
        // ตรวจสอบว่ามีสินค้านี้ (outfit_id, size_id, color_id) อยู่ในตะกร้าแล้วหรือไม่
        $item = CartItem::where('outfit_id', $outfit_id)
                        ->where('size_id', $size_id)
                        ->where('color_id', $color_id)
                        ->where('userId', $user->user_id)
                        ->first();
    
        if ($item) {
            // อัปเดตจำนวนสินค้าถ้ามีอยู่แล้ว
            $item->quantity += $quantity;
            $item->save();
        } else {
            // เพิ่มสินค้าใหม่ถ้ายังไม่มีรายการที่ตรงกัน
            CartItem::create([
                'userId' => $user->user_id,
                'outfit_id' => $outfit_id,
                'size_id' => $size_id,
                'color_id' => $color_id,
                'quantity' => $quantity,
            ]);
        }
    
        return redirect()->back()->with('success', 'เพิ่มสินค้าลงตะกร้าเรียบร้อย');
    }

    public function deleteItem(Request $request)
    {
       

        $cart_id = $request->input('cart_id'); // รับค่า cart_id จากฟอร์ม
        $cartItem = CartItem::find($cart_id);

        if (!$cartItem) {
            return redirect()->back()->with('error', 'ไม่พบสินค้าที่ต้องการลบ');
        }

        $cartItem->delete();

        return redirect()->back()->with('success', 'ลบสินค้าออกจากตะกร้าสำเร็จ');
    }


    public function updateItem(Request $request)
{
    $cartItem = CartItem::find($request->cart_id);

    if (!$cartItem) {
        return response()->json(['success' => false, 'message' => 'ไม่พบสินค้าที่ต้องการอัปเดต'], 404);
    }

    if ($request->quantity < 1) {
        return response()->json(['success' => false, 'message' => 'จำนวนสินค้าต้องไม่น้อยกว่า 1'], 400);
    }

    $cartItem->quantity = $request->quantity;
    $cartItem->save();

    return response()->json(['success' => true, 'message' => 'อัปเดตจำนวนสินค้าเรียบร้อย']);
}


}
