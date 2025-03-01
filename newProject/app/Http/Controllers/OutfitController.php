<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ThaiOutfit;

class OutfitController extends Controller
{
    //
    
     function index(){
        $outfits=ThaiOutfit::paginate(10);
        return view('main',compact('outfits'));
    }

}
