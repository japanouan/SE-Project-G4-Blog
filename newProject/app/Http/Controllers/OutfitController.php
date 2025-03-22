<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ThaiOutfit;
use App\Models\OutfitCategory;
use App\Models\ThaiOutfitCategory;
use App\Models\ThaiOutfitSize;
use App\Models\ThaiOutfitColor;
use App\Models\ThaiOutfitSizeAndColor;
use App\Models\Shop;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
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
    
        $query = ThaiOutfit::where('shop_id', $shop->shop_id);
    
        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
    
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        // Filter by category
        if ($request->filled('categories')) {
            $categories = array_filter($request->categories, function($value) {
                return $value !== null && $value !== '';
            });
            
            if (!empty($categories)) {
                $query->whereHas('categories', function($q) use ($categories) {
                    $q->whereIn('OutfitCategories.category_id', $categories);
                });
            }
        }
    
        // Filter by size
        if ($request->filled('sizes')) {
            $sizes = array_filter($request->sizes, function($value) {
                return $value !== null && $value !== '';
            });
            
            if (!empty($sizes)) {
                $query->whereHas('sizeAndColors', function($q) use ($sizes) {
                    $q->whereIn('size_id', $sizes);
                });
            }
        }
    
        // Filter by color
        if ($request->filled('colors')) {
            $colors = array_filter($request->colors, function($value) {
                return $value !== null && $value !== '';
            });
            
            if (!empty($colors)) {
                $query->whereHas('sizeAndColors', function($q) use ($colors) {
                    $q->whereIn('color_id', $colors);
                });
            }
        }
    
        // Sort by column
        $orderBy = $request->input('orderBy', 'outfit_id');
        $direction = $request->input('direction', 'asc');
        $query->orderBy($orderBy, $direction);
    
        $outfits = $query->paginate(10)->withQueryString();
        $categories = OutfitCategory::all();
        $sizes = ThaiOutfitSize::all();
        $colors = ThaiOutfitColor::all();
    
        return view('shopowner.outfits.index', compact('outfits', 'categories', 'sizes', 'colors'));
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
        $sizes = ThaiOutfitSize::all();
        $colors = ThaiOutfitColor::all();
    
        return view('shopowner.outfits.create', compact('categories', 'shop', 'sizes', 'colors'));
    }    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'depositfee' => 'required|numeric|min:0',
            'penaltyfee' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',        
            'status' => 'required|in:active,inactive',
            'shop_id' => 'required|exists:Shops,shop_id',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:OutfitCategories,category_id',
            'sizes' => 'required|array|min:1',
            'sizes.*' => 'exists:Thaioutfit_Size,size_id',
            'colors' => 'required|array|min:1',
            'colors.*' => 'exists:Thaioutfit_Color,color_id',
            'amount' => 'required|array',
            'amount.*' => 'numeric|min:0'
        ]);
    
        // Remove image and size/color data from validated
        if (isset($validated['image'])) {
            unset($validated['image']);
        }
    
        unset($validated['sizes']);
        unset($validated['colors']);
        unset($validated['amount']);
    
        // Handle image upload
        if ($request->hasFile('image')) {
            $newFilename = Str::random(40) . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images/outfits'), $newFilename);
            $validated['image'] = 'images/outfits/' . $newFilename;
        }
    
        // Create outfit without sizes and colors
        $outfit = ThaiOutfit::create($validated);
    
        // Attach categories
        if ($outfit) {
            foreach ($request->categories as $categoryId) {
                $outfitCategory = new ThaiOutfitCategory();
                $outfitCategory->outfit_id = $outfit->outfit_id;
                $outfitCategory->category_id = $categoryId;
                $outfitCategory->save();
            }
        
            // Add size and color combinations
            if (isset($request->sizes) && isset($request->colors) && isset($request->amount)) {
                foreach ($request->sizes as $sizeIndex => $sizeId) {
                    foreach ($request->colors as $colorIndex => $colorId) {
                        $key = $sizeId . '_' . $colorId;
                        if (isset($request->amount[$key]) && $request->amount[$key] > 0) {
                            ThaiOutfitSizeAndColor::create([
                                'outfit_id' => $outfit->outfit_id,
                                'size_id' => $sizeId,
                                'color_id' => $colorId,
                                'amount' => $request->amount[$key]
                            ]);
                        }
                    }
                }
            }
        }
    
        return redirect()->route('shopowner.outfits.index')
            ->with('success', 'ชุดถูกเพิ่มเรียบร้อยแล้ว');
    }

    public function edit($id)
    {
        $outfit = ThaiOutfit::findOrFail($id);
        $categories = OutfitCategory::all();
        $sizes = ThaiOutfitSize::all();
        $colors = ThaiOutfitColor::all();
    
        // Get current categories
        $outfitCategories = ThaiOutfitCategory::where('outfit_id', $id)
            ->pluck('category_id')
            ->toArray();
        
        // Get current size and color combinations
        $sizeAndColors = ThaiOutfitSizeAndColor::where('outfit_id', $id)->get();
        $sizeColorAmounts = [];
    
        foreach ($sizeAndColors as $item) {
            $key = $item->size_id . '_' . $item->color_id;
            $sizeColorAmounts[$key] = $item->amount;
        }
        
        return view('shopowner.outfits.edit', compact('outfit', 'categories', 'outfitCategories', 'sizes', 'colors', 'sizeColorAmounts'));
    }

    public function update(Request $request, $id)
    {
        $outfit = ThaiOutfit::findOrFail($id);
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'depositfee' => 'required|numeric|min:0',
            'penaltyfee' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',        
            'status' => 'required|in:active,inactive',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:OutfitCategories,category_id',
            'sizes' => 'required|array|min:1',
            'sizes.*' => 'exists:Thaioutfit_Size,size_id',
            'colors' => 'required|array|min:1',
            'colors.*' => 'exists:Thaioutfit_Color,color_id',
            'amount' => 'required|array',
            'amount.*' => 'numeric|min:0'
        ]);
    
        // Remove image and size/color data from validated
        if (isset($validated['image'])) {
            unset($validated['image']);
        }
    
        unset($validated['sizes']);
        unset($validated['colors']);
        unset($validated['amount']);
    
        // Handle image upload separately
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($outfit->image && file_exists(public_path($outfit->image))) {
                unlink(public_path($outfit->image));
            }
        
            // Generate random filename
            $filename = Str::random(40) . '.' . $request->file('image')->getClientOriginalExtension();
        
            // Move file to public directory
            $request->file('image')->move(public_path('images/outfits'), $filename);
        
            // Update outfit with new image path
            $outfit->image = 'images/outfits/' . $filename;
        }
    
        // Update outfit with other validated data
        $outfit->fill($validated);
        $outfit->save();
    
        // Update categories
        ThaiOutfitCategory::where('outfit_id', $id)->delete();
        foreach ($request->categories as $categoryId) {
            $outfitCategory = new ThaiOutfitCategory();
            $outfitCategory->outfit_id = $id;
            $outfitCategory->category_id = $categoryId;
            $outfitCategory->save();
        }
    
        // Update size and color combinations
        ThaiOutfitSizeAndColor::where('outfit_id', $id)->delete();
    
        if (isset($request->sizes) && isset($request->colors) && isset($request->amount)) {
            foreach ($request->sizes as $sizeIndex => $sizeId) {
                foreach ($request->colors as $colorIndex => $colorId) {
                    $key = $sizeId . '_' . $colorId;
                    if (isset($request->amount[$key]) && $request->amount[$key] > 0) {
                        ThaiOutfitSizeAndColor::create([
                            'outfit_id' => $outfit->outfit_id,
                            'size_id' => $sizeId,
                            'color_id' => $colorId,
                            'amount' => $request->amount[$key]
                        ]);
                    }
                }
            }
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

        // Delete size and color relationships
        ThaiOutfitSizeAndColor::where('outfit_id', $id)->delete();
        
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
        $outfits = $query->join('Shops', 'ThaiOutfits.shop_id', '=', 'Shops.shop_id')
                 ->paginate(10);

        // dd($outfits);

        return view('admin.outfits.outfits', compact('outfits'));
    }

    public function AdminEdit($id)
    {
        $outfit = ThaiOutfit::findOrFail($id);
        $categories = OutfitCategory::all();

        // Get current categories
        $outfitCategories = ThaiOutfitCategory::where('outfit_id', $id)
            ->pluck('category_id')
            ->toArray();

        return view('admin.outfits.edit', compact('outfit', 'categories', 'outfitCategories'));
    }
    public function searchOutfits(Request $request)
    {
        $searchKey = $request->searchkey;

        // ตรวจสอบว่ามีคีย์ค้นหาหรือไม่
        if (!$searchKey) {
            return redirect()->back()->with('alert', 'กรุณาใส่คำค้นหา');
        }

        // ค้นหา Outfits โดยใช้ Model Outfit
        $outfits = ThaiOutfit::where('name', 'like', "%{$searchKey}%")
            ->orWhere('description', 'like', "%{$searchKey}%")
            ->get();

        // ตรวจสอบว่าพบข้อมูลหรือไม่
        if ($outfits->isEmpty()) {
            return redirect()->back()->with('alert', 'ไม่พบผลลัพธ์ที่ตรงกับการค้นหา');
        }

        return view('main', compact('outfits'));
    }


}
