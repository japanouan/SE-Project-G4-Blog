<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ThaiOutfit;
use App\Models\CartItem;

class CartItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function index()
    {
        $user = Auth::user();
        
        // ดึงข้อมูลรายการสินค้าที่อยู่ในตะกร้าของ User นั้นๆ
        $cartItems = CartItem::where('userId', $user->user_id)
                            ->orderBy('outfit_id') // จัดเรียงตาม outfit_id
                            ->get();

        // ดึงข้อมูลชุดไทยที่อยู่ในตะกร้าของ User
        $outfitIds = $cartItems->pluck('outfit_id')->toArray();
        $outfits = ThaiOutfit::whereIn('outfit_id', $outfitIds)
                            ->orderBy('outfit_id') // จัดเรียงให้ตรงกับ cartItems
                            ->get();

        // ส่งข้อมูลไปที่หน้า View
        return view('cartItem.index', compact('outfits', 'cartItems'));
    }


    public function addToCart(Request $request)
    {
        // ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อนเพิ่มลงตะกร้า');
        }

        $user = Auth::user();
        $outfit_id = $request->input('outfit_id');
        $quantity = $request->input('quantity', 1); // ถ้าไม่ได้ส่งมา ให้เป็น 1

        // ตรวจสอบว่ามีสินค้านี้อยู่ในตะกร้าแล้วหรือไม่
        $item = CartItem::where('outfit_id', $outfit_id)
                        ->where('userId', $user->user_id)
                        ->first();

        if ($item) {
            // ถ้ามีสินค้าอยู่แล้ว เพิ่มจำนวน
            $item->increment('quantity', $quantity);
        } else {
            // ถ้ายังไม่มี ให้เพิ่มใหม่
            CartItem::create([
                'userId' => $user->user_id,
                'outfit_id' => $outfit_id,
                'quantity' => $quantity,
            ]);
        }

        return redirect()->back()->with('success', "เพิ่มสินค้า ID: $outfit_id จำนวน: $quantity ลงตะกร้าแล้ว!");
    }
}
