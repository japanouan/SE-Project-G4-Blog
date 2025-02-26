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
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|email',
        //     'phone' => 'required|numeric',
        //     'shopname' => 'required|string|max:255|unique:shop,shopname,' . $shop_id . ',shop_id',
        //     'shopType' => 'required|string',
        //     'status' => 'required|in:active,inactive',
        // ]);



        // // ค้นหาผู้ใช้และอัปเดตข้อมูล
        // $shop = shop::find($shop_id);
        // $shop->update($request->all());

        // return redirect()->route('admin.shop.index')->with('success', 'shop updated successfully!');
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


}
