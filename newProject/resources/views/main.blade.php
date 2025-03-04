@extends('layouts.main')

@section('title', 'Home')

@section('content')

    <img src="{{ url('images/outfits/main.png') }}" alt="Main Outfit" class="w-full h-auto rounded-lg">

    <div class="container mx-auto px-4 py-8">

        <h2 class="text-2xl font-bold mb-4">รายการชุดไทย</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            @if($outfits->isEmpty())
                <p class="text-red-500">❌ ไม่พบผลลัพธ์ที่ตรงกับ "{{ request('searchkey') }}"</p>
            @endif

            @foreach ($outfits as $dress)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ asset($dress->image) }}" class="w-full h-60 object-cover" alt="{{ $dress->name }}">
                    <div class="p-4">
                        <h5 class="text-xl font-semibold">{{ $dress->name }}</h5>
                        <p class="text-gray-600">{{ $dress->description }}</p>
                        <p class="text-lg font-bold text-green-600">฿{{ number_format($dress->price, 2) }}</p>
                        <a href="{{ url('orderdetail/outfit/' . $dress->outfit_id) }}" 
                        class="mt-3 inline-block px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                            ดูรายละเอียด
                        </a>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection
