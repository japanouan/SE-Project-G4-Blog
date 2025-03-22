@extends('layouts.shopowner-layout')

@section('title', 'รายละเอียดปัญหา')

@section('content')
<div class="container mx-auto">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 border-b pb-2">รายละเอียดปัญหา</h2>

        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-700">หัวข้อ:</h3>
                <p class="mt-1">{{ $issue->title }}</p>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-700">รายละเอียด:</h3>
                <p class="mt-1 whitespace-pre-line">{{ $issue->description }}</p>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-700">สถานะ:</h3>
                <p class="mt-1">
                    <span class="px-3 py-1 rounded-full 
                        @if($issue->status == 'reported') bg-yellow-100 text-yellow-800
                        @elseif($issue->status == 'in_progress') bg-blue-100 text-blue-800
                        @elseif($issue->status == 'fixed') bg-green-100 text-green-800
                        @endif">
                        {{ $issue->status }}
                    </span>
                </p>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-700">วันที่แจ้ง:</h3>
                <p class="mt-1">{{ $issue->created_at->format('d/m/Y H:i') }}</p>
            </div>

            @if($issue->reply)
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-700">การตอบกลับจากผู้ดูแลระบบ:</h3>
                <p class="mt-2 whitespace-pre-line">{{ $issue->reply }}</p>
                <p class="text-sm text-gray-500 mt-2">ตอบกลับเมื่อ: {{ $issue->updated_at->format('d/m/Y H:i') }}</p>
            </div>
            @endif
        </div>

        <div class="mt-8">
            <a href="{{ route('shopowner.issue.index') }}" 
               class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i> กลับ
            </a>
        </div>
    </div>
</div>
@endsection
