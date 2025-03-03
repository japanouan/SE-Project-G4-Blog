<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\ThaiOutfit;
use App\Models\ThaiOutfitCategories;
use App\Models\OutfitCategories; // เรียกใช้ Model OutfitCategories

class OrderDetailController extends Controller
{
    public function index($idOutfit)
    {
        // Load outfit with its category names
        $outfit = ThaiOutfit::with('categories')->findOrFail($idOutfit);

        return view('orderdetail.index', compact('outfit'));
    }
}


