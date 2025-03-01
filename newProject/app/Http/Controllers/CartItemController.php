<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ThaiOutfit;
class CartItemController extends Controller
{
    //
    function index(){
        $user = Auth::user();
        return view('cartItem/index',compact('user'));
    }

    function addToCart($idOutfit){
        $user = Auth::user();
        $outfit = ThaiOutfit::find($idOutfit);

        $data=[
            
        ];
    }
}
