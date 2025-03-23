@extends('layouts.shopowner-layout')

@section('title', 'จัดการชุด')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">จัดการชุด</h2>
        <a href="{{ route('shopowner.outfits.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            <i class="fa fa-plus mr-2"></i> เพิ่มชุดใหม่
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search and Filter Card -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">ค้นหาและกรอง</h3>
            <button type="button" id="toggle-filters" class="text-blue-600 hover:text-blue-800">
                <i class="fa fa-filter mr-1"></i> แสดง/ซ่อน ตัวกรอง
            </button>
        </div>

        <form action="{{ route('shopowner.outfits.index') }}" method="GET">
            <!-- Search Bar -->
            <div class="mb-4">
                <div class="flex">
                    <input type="text" name="search" placeholder="ค้นหาตามชื่อชุด..." value="{{ request('search') }}"
                        class="w-full rounded-l-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r-md hover:bg-blue-700">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- Advanced Filters (Initially Hidden) -->
            <div id="advanced-filters" class="{{ request()->anyFilled(['categories', 'sizes', 'colors', 'status']) ? '' : 'hidden' }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">สถานะ</label>
                        <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">ทั้งหมด</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>พร้อมใช้งาน</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>ไม่พร้อมใช้งาน</option>
                        </select>
                    </div>
                    
                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">หมวดหมู่</label>
                        <select name="categories[]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">ทั้งหมด</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->category_id }}" {{ in_array($category->category_id, (array)request('categories')) ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Size Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ขนาด</label>
                        <select name="sizes[]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">ทั้งหมด</option>
                            @foreach($sizes as $size)
                                <option value="{{ $size->size_id }}" {{ in_array($size->size_id, (array)request('sizes')) ? 'selected' : '' }}>
                                    {{ $size->size }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Color Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">สี</label>
                        <select name="colors[]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">ทั้งหมด</option>
                            @foreach($colors as $color)
                                <option value="{{ $color->color_id }}" {{ in_array($color->color_id, (array)request('colors')) ? 'selected' : '' }}>
                                    {{ $color->color }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Filter Buttons -->
            <div class="flex items-center justify-between">
                <div class="flex space-x-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i class="fa fa-filter mr-2"></i> กรอง
                    </button>
                    <a href="{{ route('shopowner.outfits.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        <i class="fa fa-times mr-2"></i> ล้างตัวกรอง
                    </a>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600 mr-2">เรียงตาม:</label>
                    <select name="orderBy" onchange="this.form.submit()" 
                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="outfit_id" {{ request('orderBy') == 'outfit_id' ? 'selected' : '' }}>ID</option>
                        <option value="name" {{ request('orderBy') == 'name' ? 'selected' : '' }}>ชื่อชุด</option>
                        <option value="price" {{ request('orderBy') == 'price' ? 'selected' : '' }}>ราคา</option>
                        <option value="created_at" {{ request('orderBy') == 'created_at' ? 'selected' : '' }}>วันที่เพิ่ม</option>
                    </select>
                    
                    <select name="direction" onchange="this.form.submit()" 
                        class="ml-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>น้อยไปมาก</option>
                        <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>มากไปน้อย</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        @if(count($outfits) > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">รูปภาพ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่อชุด</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ราคา</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">มัดจำ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ค่าปรับ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">คงเหลือ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ขนาดและสี</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สถานะ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($outfits as $outfit)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($outfit->image && file_exists(public_path($outfit->image)))
                                <img src="{{ asset($outfit->image) }}" alt="{{ $outfit->name }}" class="h-16 w-16 object-cover rounded">
                            @else
                                <div class="h-16 w-16 bg-gray-200 flex items-center justify-center rounded">
                                    <i class="fa fa-image text-gray-400"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $outfit->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ number_format($outfit->price, 2) }} บาท</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ number_format($outfit->depositfee, 2) }} บาท</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ number_format($outfit->penaltyfee, 2) }} บาท</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $outfit->sizeAndColors->sum('amount') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                <button type="button" class="text-blue-600 hover:text-blue-800" 
                                        onclick="toggleVariants('variants-{{ $outfit->outfit_id }}')">
                                    แสดงรายละเอียด <i class="fa fa-chevron-down"></i>
                                </button>
                                <div id="variants-{{ $outfit->outfit_id }}" class="hidden mt-2">
                                    @if($outfit->sizeAndColors->count() > 0)
                                        <div class="text-xs p-2 bg-gray-50 rounded">
                                            @foreach($outfit->sizeAndColors as $variant)
                                                <div class="mb-1">
                                                    <span class="font-medium">{{ $variant->size->size }}</span> - 
                                                    <span class="font-medium">{{ $variant->color->color }}</span>: 
                                                    <span>{{ $variant->amount }} ชิ้น</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-xs text-gray-500">ไม่มีข้อมูลขนาดและสี</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($outfit->status == 'active')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">พร้อมใช้งาน</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">ไม่พร้อมใช้งาน</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('shopowner.outfits.edit', $outfit->outfit_id) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fa fa-edit"></i> แก้ไข
                                </a>
                                <form action="{{ route('shopowner.outfits.destroy', $outfit->outfit_id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบชุดนี้?')">
                                        <i class="fa fa-trash"></i> ลบ
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4">
                {{ $outfits->links() }}
            </div>
        @else
            <div class="p-6 text-center text-gray-500">
                <p>ไม่พบข้อมูลชุดตามเงื่อนไขที่ค้นหา</p>
                <div class="mt-4">
                    @if(request()->anyFilled(['search', 'categories', 'sizes', 'colors', 'status']))
                        <a href="{{ route('shopowner.outfits.index') }}" class="inline-block px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 mr-2">
                            <i class="fa fa-times mr-2"></i> ล้างตัวกรอง
                        </a>
                    @endif
                    <a href="{{ route('shopowner.outfits.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i class="fa fa-plus mr-2"></i> เพิ่มชุดใหม่
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    function toggleVariants(id) {
        const variantsElement = document.getElementById(id);
        if (variantsElement.classList.contains('hidden')) {
            variantsElement.classList.remove('hidden');
        } else {
            variantsElement.classList.add('hidden');
        }
    }

    // Toggle advanced filters visibility
    document.addEventListener('DOMContentLoaded', function() {
        const toggleFiltersBtn = document.getElementById('toggle-filters');
        const advancedFilters = document.getElementById('advanced-filters');
        
        toggleFiltersBtn.addEventListener('click', function() {
            advancedFilters.classList.toggle('hidden');
        });
    });
</script>
@endsection