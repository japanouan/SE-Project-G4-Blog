<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\ThaiOutfit;
use App\Models\ThaiOutfitCategories;
use App\Models\OutfitCategories; // เรียกใช้ Model OutfitCategories
use App\Models\ThaiOutfitSizeAndColor;

class OrderDetailController extends Controller
{
    public function index($idOutfit)
    {
        $outfit = ThaiOutfit::with(['categories', 'sizesAndColors'])->findOrFail($idOutfit);

        return view('orderdetail.index', compact('outfit'));
    }



    
}


