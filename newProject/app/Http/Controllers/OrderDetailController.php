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
        // Load outfit with its category names and size/color data
        $outfit = ThaiOutfit::with(['categories', 'sizeAndColors.size', 'sizeAndColors.color'])
            ->findOrFail($idOutfit);
    
        // ดึงข้อมูลขนาดและสีของชุด
        return view('orderdetail.index', compact('outfit'));
    }
    
}


