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

    public function deleteItem($cart_id){
        CartItem::findOrFail($cart_id)->delete();
        return redirect()->back()->with('success', "ลบ Item ออกจากร้านค้าสำเร็จ");
    }

    public function updateItem(Request $request)
{
    $user = Auth::user();
    $outfit_id = $request->input('outfit_id');
    $quantity = $request->input('quantity', 1);

    // ตรวจสอบว่ามี outfit_id หรือไม่
    if (!$outfit_id) {
        return response()->json(['message' => 'กรุณาระบุชุดที่ต้องการอัปเดต'], 400);
    }

    // ค้นหาไอเท็มในตะกร้าของผู้ใช้
    $item = CartItem::where('outfit_id', $outfit_id)
        ->where('userId', $user->user_id)
        ->first();

    if (!$item) {
        return response()->json(['message' => 'ไม่พบสินค้าในตะกร้า'], 404);
    }

    // ตรวจสอบว่าจำนวนต้องเป็นค่าบวก
    if ($quantity < 1) {
        return response()->json(['message' => 'จำนวนต้องมากกว่าหรือเท่ากับ 1'], 400);
    }

    // อัปเดตจำนวนสินค้า
    $item->quantity = $quantity;
    $item->save();

    return redirect()->back()->with('success', "update Item ร้านค้าสำเร็จ");
}

}
