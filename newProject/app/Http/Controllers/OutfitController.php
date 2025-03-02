<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ThaiOutfit;
use App\Models\OutfitCategory;
use App\Models\ThaiOutfitCategory;
use App\Models\Shop;
use Illuminate\Support\Facades\Storage;

class OutfitController extends Controller
{
    public function index()
    {
        $outfits = ThaiOutfit::paginate(10);
        return view('main', compact('outfits'));
    }
    
    // SHOP OWNER METHODS
    
    public function shopOwnerIndex(Request $request)
    {
        // Check if user has a shop first
        $shop = Shop::where('shop_owner_id', auth()->id())->first();
        
        if (!$shop) {
            return redirect()->route('shopowner.shops.my-shop')
                ->with('error', 'คุณยังไม่มีร้านค้า กรุณาลงทะเบียนร้านค้าก่อนจัดการชุด');
        }
        
        $outfits = ThaiOutfit::where('shop_id', $shop->shop_id)->paginate(10);
        return view('shopowner.outfits.index', compact('outfits'));
    }
    
    public function create()
    {
        // Check if user has a shop first
        $shop = Shop::where('shop_owner_id', auth()->id())->first();
        
        if (!$shop) {
            return redirect()->route('shopowner.shops.my-shop')
                ->with('error', 'คุณยังไม่มีร้านค้า กรุณาลงทะเบียนร้านค้าก่อนจัดการชุด');
        }
        
        $categories = OutfitCategory::all();
        return view('shopowner.outfits.create', compact('categories', 'shop'));
    }    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
            'shop_id' => 'required|exists:Shops,shop_id',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:OutfitCategories,category_id'
        ]);
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $validated['image'] = $imagePath;
        }
        
        // Create outfit (will only include fields in $validated)
        $outfit = ThaiOutfit::create($validated);
        
        // Attach categories - FIX HERE
        if ($outfit) {
            foreach ($request->categories as $categoryId) {
                // Don't set outfit_cate_id manually, let it auto-increment
                $outfitCategory = new ThaiOutfitCategory();
                $outfitCategory->outfit_id = $outfit->outfit_id;
                $outfitCategory->category_id = $categoryId;
                $outfitCategory->save();
            }
        }
        
        return redirect()->route('shopowner.outfits.index')
            ->with('success', 'ชุดถูกเพิ่มเรียบร้อยแล้ว');
    }
    
    public function edit($id)
    {
        $outfit = ThaiOutfit::findOrFail($id);
        $categories = OutfitCategory::all();
        
        // Get current categories
        $outfitCategories = ThaiOutfitCategory::where('outfit_id', $id)
            ->pluck('category_id')
            ->toArray();
            
        return view('shopowner.outfits.edit', compact('outfit', 'categories', 'outfitCategories'));
    }
    
    public function update(Request $request, $id)
    {
        $outfit = ThaiOutfit::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:OutfitCategories,category_id'
        ]);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($outfit->image && Storage::disk('public')->exists($outfit->image)) {
                Storage::disk('public')->delete($outfit->image);
            }
            
            $imagePath = $request->file('image')->store('outfit_images', 'public');
            $validated['image'] = $imagePath;
        }
        
        // Update outfit
        $outfit->update($validated);
        
        // Update categories - FIX HERE
        ThaiOutfitCategory::where('outfit_id', $id)->delete();
        foreach ($request->categories as $categoryId) {
            // Don't set outfit_cate_id manually, let it auto-increment
            $outfitCategory = new ThaiOutfitCategory();
            $outfitCategory->outfit_id = $id;
            $outfitCategory->category_id = $categoryId;
            $outfitCategory->save();
        }
        
        return redirect()->route('shopowner.outfits.index')
            ->with('success', 'ชุดถูกอัปเดตเรียบร้อยแล้ว');
    }
    
    public function destroy($id)
    {
        $outfit = ThaiOutfit::findOrFail($id);
        
        // Delete image if exists
        if ($outfit->image && Storage::disk('public')->exists($outfit->image)) {
            Storage::disk('public')->delete($outfit->image);
        }
        
        // Delete category relationships
        ThaiOutfitCategory::where('outfit_id', $id)->delete();
        
        // Delete outfit
        $outfit->delete();
        
        return redirect()->route('shopowner.outfits.index')
            ->with('success', 'ชุดถูกลบเรียบร้อยแล้ว');
    }

    public function AdminIndex(Request $request)
    {
        $query = ThaiOutfit::query();

        // ค้นหา shop_id, outfit_id หรือชื่อชุด
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('shop_id', 'like', "%{$search}%")
                ->orWhere('outfit_id', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%");
        }

        // ดึงข้อมูลชุดทั้งหมด + ร้านค้า
        $outfits = $query->with('shop')->paginate(10);

        return view('admin.shops.outfits', compact('outfits'));
    }
}
