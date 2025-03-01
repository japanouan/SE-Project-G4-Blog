<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ThaiOutfit;
use App\Models\CartItem;
class CartItemController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function index(){
        $user = Auth::user();
        return view('cartItem/index',compact('user'));
    }

    function addToCart($idOutfit){
        $user = Auth::user();
        $outfit = ThaiOutfit::find($idOutfit);

        $data=[
            'userId'=>$user->user_id,
            'outfit_id'=>$outfit->outfit_id,
        ];
        CartItem::insert($data);
        return redirect("/orderdetail/outfit/{$outfit->outfit_id}");
    }
}
