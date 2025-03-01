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
        $cartItems = CartItem::where('userId', $user->user_id)->get();
        $outfitIds = $cartItems->pluck('outfit_id')->toArray();
        $outfits = ThaiOutfit::whereIn('outfit_id', $outfitIds)->get();
        return view('cartItem/index', compact('outfits', 'cartItems'));
    }

    function addToCart($idOutfit)
{
    $user = Auth::user();
    $outfit = ThaiOutfit::findOrFail($idOutfit); // ใช้ findOrFail() เพื่อให้ error ถ้าหาไม่เจอ

    // ตรวจสอบก่อนว่ามีไอเท็มอยู่ในตะกร้าแล้วหรือไม่
    $item = CartItem::where('outfit_id', $idOutfit)->where('userId', $user->user_id)->first();

    if (!$item) {
        // ถ้ายังไม่มีไอเท็มในตะกร้า -> เพิ่มใหม่
        CartItem::create([
            'user_id' => $user->user_id, // ✅ ใช้ user_id ที่ถูกต้อง
            'outfit_id' => $outfit->outfit_id,
            'quantity' => 1,
        ]);
    } else {
        // ถ้ามีอยู่แล้ว -> เพิ่มจำนวนสินค้า
        CartItem::find($item->cart_item_id)->update([
            'quantity' => $item->quantity + 1,
        ]);
    }

    return redirect("/orderdetail/outfit/{$outfit->outfit_id}")->with('success', 'เพิ่มลงตะกร้าเรียบร้อยแล้ว');
}
}
