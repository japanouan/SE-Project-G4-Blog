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
        $outfits = ThaiOutfit::where('userId', $user->user_id)->get();
        return view('cartItem/index', compact('user'));
    }

    function addToCart($idOutfit)
    {
        $user = Auth::user();
        $outfit = ThaiOutfit::findOrFail($idOutfit); // ใช้ findOrFail() เพื่อให้ error ถ้าหาไม่เจอ

        // ตรวจสอบก่อนว่ามีไอเท็มอยู่ในตะกร้าแล้วหรือไม่
        $data=[
            'userId' => $user->user_id,
            'outfit_id' => $outfit->outfit_id,
        ];
        CartItem::insert($data);

        return redirect("/orderdetail/outfit/{$outfit->outfit_id}")->with('success', 'เพิ่มลงตะกร้าเรียบร้อยแล้ว');
    }
}
