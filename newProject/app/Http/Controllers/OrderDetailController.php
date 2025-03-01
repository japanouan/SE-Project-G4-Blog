<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\ThaiOutfit;
class OrderDetailController extends Controller
{
    //
    function index($idOutfit){
        $outfit = ThaiOutfit::find($idOutfit);
        return view('orderdetail/index',compact('outfit'));
    }
}
