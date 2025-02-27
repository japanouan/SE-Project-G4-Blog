<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    

    public function index(Request $request)
    {
        // รับค่าการจัดเรียง
        $orderBy = $request->input('orderBy') ?: 'shop_id';
        $direction = $request->input('direction') ?: 'asc';


        $query = Shop::query();
        // dd($orderBy);

        // จัดเรียงตาม orderBy และ direction
        $query->orderBy($orderBy, $direction);
        // dd($query);

        $shops = $query->get();
        // dd($shop);

        return view('admin.shops.index', compact('shops'));
    }



    public function toggleStatus(Request $request, $shop_id)
    {
        $shop = Shop::findOrFail($shop_id);
        $shop->status = $request->status; // รับค่าจากฟอร์ม
        $shop->save();

        $orderBy = $request->input('orderBy', 'shop_id');
        $direction = $request->input('direction', 'asc');

        return redirect()->route('admin.shops.index')->with('success', 'Shop status updated successfully!');
    }


    public function edit($shop_id)
    {
        $shop = Shop::findOrFail($shop_id);

        return view('admin.shops.edit', compact('shop'));
    }

    public function update(Request $request, $shop_id)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'shop_description' => 'required|string',
            'shop_location' => 'required|string',
            'rental_terms' => 'required|string',
            'depositfee' => 'required|numeric|min:0',
            'penaltyfee' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);



        // ค้นหาผู้ใช้และอัปเดตข้อมูล
        $shop = shop::find($shop_id);
        $shop->update($request->all());

        return redirect()->route('admin.shops.index')->with('success', 'shop updated successfully!');
    }



    public function acceptance()
    {
        // ดึงข้อมูลผู้ใช้ทั้งหมด
        $shop = Shop::where(function ($query) {
            $query->where('status', 'inactive')
                ->where('is_newShop', true);
        })
            ->get();


        // ส่งข้อมูลไปยัง view
        return view('admin.shops.acceptance', compact('shop'));
    }


    public function updateStatus(Request $request, $shop_id)
    {
        $shop = Shop::where('shop_id', $shop_id)->firstOrFail();
        $shop->status = $request->status;
        $shop->is_newShop = false;
        $shop->save();

        return redirect()->route('admin.shops.acceptance');
    }


    // เพิ่มเมธอดสำหรับสร้างร้านค้าใหม่
public function create()
{
    return view('shopowner.shops.create');
}

// เมธอดสำหรับบันทึกร้านค้าใหม่
public function store(Request $request)
{
    $request->validate([
        'shop_name' => 'required|string|max:255',
        'shop_description' => 'required|string',
        'shop_location' => 'required|string',
        'rental_terms' => 'required|string',
        'depositfee' => 'required|numeric|min:0',
        'penaltyfee' => 'required|numeric|min:0',
    ]);

    $shop = new Shop();
    $shop->shop_name = $request->shop_name;
    $shop->shop_description = $request->shop_description;
    $shop->shop_location = $request->shop_location;
    $shop->rental_terms = $request->rental_terms;
    $shop->depositfee = $request->depositfee;
    $shop->penaltyfee = $request->penaltyfee;
    $shop->status = 'inactive'; // รอการอนุมัติจาก admin
    $shop->is_newShop = true;
    $shop->shop_owner_id = auth()->id();
    $shop->save();

    return redirect()->route('shopowner.shops.my-shop')
        ->with('success', 'ร้านค้าของคุณถูกส่งไปรอการอนุมัติแล้ว');
}

// เมธอดสำหรับดูร้านค้าของตัวเอง
public function myShop()
{
    $shop = Shop::where('shop_owner_id', auth()->id())->first();
    
    return view('shopowner.shops.my-shop', compact('shop'));
}

// เมธอดสำหรับแก้ไขร้านค้าของตัวเอง
public function editMyShop($shop_id)
{
    $shop = Shop::where('shop_id', $shop_id)
        ->where('shop_owner_id', auth()->id())
        ->firstOrFail();
        
    return view('shopowner.shops.edit-my-shop', compact('shop'));
}

// เมธอดสำหรับอัปเดตร้านค้าของตัวเอง
public function updateMyShop(Request $request, $shop_id)
{
    $shop = Shop::where('shop_id', $shop_id)
        ->where('shop_owner_id', auth()->id())
        ->firstOrFail();
        
    $request->validate([
        'shop_name' => 'required|string|max:255',
        'shop_description' => 'required|string',
        'shop_location' => 'required|string',
        'rental_terms' => 'required|string',
        'depositfee' => 'required|numeric|min:0',
        'penaltyfee' => 'required|numeric|min:0',
    ]);
    
    $shop->update($request->only([
        'shop_name', 
        'shop_description', 
        'shop_location', 
        'rental_terms', 
        'depositfee', 
        'penaltyfee'
    ]));
    
    return redirect()->route('shopowner.shops.my-shop')
        ->with('success', 'ข้อมูลร้านค้าอัปเดตเรียบร้อยแล้ว');
    }

}
