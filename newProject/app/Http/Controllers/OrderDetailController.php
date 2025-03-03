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
    // Load outfit พร้อม category
    $outfit = ThaiOutfit::with(['categories'])->findOrFail($idOutfit);

    // ดึงข้อมูล size และ color ทั้งหมดที่เกี่ยวข้องกับ outfit นี้
    $sizeAndColor = ThaiOutfitSizeAndColor::with(['size', 'color'])
        ->where('outfit_id', $idOutfit)
        ->get();

    return view('orderdetail.index', compact('outfit', 'sizeAndColor'));
}

    
}


