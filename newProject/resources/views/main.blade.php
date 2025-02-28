@extends('layouts.main')

@section('title', 'Home')

@section('content')
    <!-- Hero Section -->
    <div class="w-full max-w-7xl mx-auto">
        <div class="relative">
            <img src="{{ asset('images/banner.jpg') }}" alt="Traditional Outfits" class="w-full rounded-lg shadow-lg">
        </div>
    </div>

    <!-- HOT Section -->
    <div class="container mx-auto mt-8 px-6">
        <h2 class="text-xl font-semibold">🔥 HOT</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mt-4">
            <!-- Card 1 -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <img src="{{ asset('images/dress1.jpg') }}" alt="Dress 1" class="w-full h-56 object-cover">
                <div class="p-4">
                    <h3 class="font-semibold">Phraya Nakhon Park</h3>
                    <p class="text-gray-600">Mr. Smite</p>
                    <p class="text-sm text-gray-500">in Prachuapkhirikhan</p>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <img src="{{ asset('images/dress2.jpg') }}" alt="Dress 2" class="w-full h-56 object-cover">
                <div class="p-4">
                    <h3 class="font-semibold">Doi Inthanon</h3>
                    <p class="text-gray-600">Mrs. Balmai</p>
                    <p class="text-sm text-gray-500">in Chiangmai</p>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <img src="{{ asset('images/dress3.jpg') }}" alt="Dress 3" class="w-full h-56 object-cover">
                <div class="p-4">
                    <h3 class="font-semibold">Ko Tapu</h3>
                    <p class="text-gray-600">Mr. Pun</p>
                    <p class="text-sm text-gray-500">in Phang Nga</p>
                </div>
            </div>
            <!-- Card 4 -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <img src="{{ asset('images/dress4.jpg') }}" alt="Dress 4" class="w-full h-56 object-cover">
                <div class="p-4">
                    <h3 class="font-semibold">Thi Lo Su Waterfall</h3>
                    <p class="text-gray-600">Mr. Jong</p>
                    <p class="text-sm text-gray-500">in Kanchanaburi</p>
                </div>
            </div>
        </div>
    </div>
@endsection
