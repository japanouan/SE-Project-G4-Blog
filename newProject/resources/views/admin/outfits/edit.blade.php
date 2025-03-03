<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>แก้ไขชุดโง่วๆ</title>
</head>
<body>
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">แก้ไขชุด: {{ $outfit->name }}</h2>
        <a href="{{ route('admin.outfits.adminindex') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            <i class="fa fa-arrow-left mr-2"></i> กลับ
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <form action="{{ route('admin.outfits.update', $outfit->outfit_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อชุด *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $outfit->name) }}" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">ราคา (บาท) *</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $outfit->price) }}" min="0" step="0.01" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('price')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">จำนวนคงเหลือ *</label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', $outfit->stock) }}" min="0" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('stock')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">สถานะ *</label>
                    <select name="status" id="status" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="active" {{ old('status', $outfit->status) == 'active' ? 'selected' : '' }}>พร้อมใช้งาน</option>
                        <option value="inactive" {{ old('status', $outfit->status) == 'inactive' ? 'selected' : '' }}>ไม่พร้อมใช้งาน</option>
                    </select>
                    @error('status')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">รายละเอียด *</label>
                    <textarea name="description" id="description" rows="4" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description', $outfit->description) }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">รูปภาพ</label>
                    @if($outfit->image)
                    <div class="mb-3">
                        <img src="{{ asset($outfit->image) }}" alt="{{ $outfit->name }}" class="h-32 w-auto object-cover rounded">
                        <p class="text-xs mt-1 text-gray-500">รูปภาพปัจจุบัน (อัปโหลดรูปใหม่เพื่อเปลี่ยนแปลง)</p>
                    </div>
                    @endif
                    <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg,image/gif"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <p class="text-xs text-gray-500 mt-1">รองรับไฟล์ .jpeg, .png, .jpg, .gif ขนาดสูงสุด 2MB</p>
                    @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">หมวดหมู่ *</label>
                    <div class="mt-2 border rounded-md p-3 max-h-60 overflow-y-auto">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($categories as $category)
                            <div class="flex items-center p-2 hover:bg-gray-50 rounded">
                                <input type="radio" name="categories[]" id="category_{{ $category->category_id }}"
                                    value="{{ $category->category_id }}"
                                    {{ in_array($category->category_id, old('categories', $outfitCategories)) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <label for="category_{{ $category->category_id }}" class="ml-2 text-sm text-gray-700 cursor-pointer">{{ $category->category_name }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">เลื่อนดูหมวดหมู่ทั้งหมด</p>
                    @error('categories')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fa fa-save mr-2"></i> บันทึกการแก้ไข
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>