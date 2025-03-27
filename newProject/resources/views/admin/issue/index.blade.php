@extends('layouts.admin-layout')

@section('title', 'Issue Reported')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            <i class="fas fa-exclamation-circle mr-2 text-[#8B9DF9]"></i>ปัญหาที่รายงาน
    </h1>

        @if ($notifications->isEmpty())
            <div class="text-center bg-yellow-100 text-yellow-800 p-4 rounded-lg">
                ❌ No notifications occurred.
            </div>
        @else
            @foreach ($notifications as $notification)
                @php
                    // กำหนดสีตามสถานะ
                    $statusColor = match($notification->status) {
                        'reported' => 'bg-red-100 text-red-800 border-red-500', 
                        'in_progress' => 'bg-yellow-100 text-yellow-800 border-yellow-500',
                        'fixed' => 'bg-green-100 text-green-800 border-green-500',
                        default => 'bg-gray-100 text-gray-800 border-gray-500'
                    };
                @endphp

                @if (Auth::user()->userType == 'admin')
                    @if($notification->issue_id)
                        <a href="{{ route('admin.issue.replyPage', ['id' => $notification->issue_id]) }}" class="block">
                            <div class="bg-white p-6 rounded-lg shadow-md mb-4 transition transform hover:scale-105 hover:shadow-lg">
                                <div class="flex justify-between items-center">
                                    <h4 class="text-lg font-semibold"> 
                                        <i class="fas fa-exclamation-circle text-xl mr-2"></i> 
                                        {{ e($notification->title) }} 
                                    </h4>
                                    <span class="px-3 py-1 text-sm font-semibold rounded-lg border {{ $statusColor }}">
                                        {{ ucfirst($notification->status) }}
                                    </span>
                                </div>
                                <p class="text-gray-600"><strong>Report At:</strong> {{ e($notification->created_at) }}</p>
                                <p class="text-gray-600"><strong>Report By:</strong> #{{ e($notification->user_id.' '.$notification->username) }}</p>
                                <p class="text-gray-600"><strong>Description:</strong> {{ Str::limit(e($notification->description),100) }}</p>
                            </div>
                        </a>
                    @else
                        <p class="text-center text-red-500">ข้อมูลไม่ถูกต้อง</p>
                    @endif

                @else
                    <a href="{{ route('issue.reported', ['id' => $notification->id]) }}" class="block">
                        <div class="bg-white p-6 rounded-lg shadow-md mb-4 transition transform hover:scale-105 hover:shadow-lg">
                            <div class="flex justify-between items-center">
                                <h4 class="text-lg font-semibold">
                                    <i class="fas fa-bell text-xl mr-2"></i> 
                                    {{ e($notification->title) }} 
                                </h4>
                                <span class="px-3 py-1 text-sm font-semibold rounded-lg border {{ $statusColor }}">
                                    {{ ucfirst($notification->status) }}
                                </span>
                            </div>
                            <p class="text-gray-600"><strong>Report ID:</strong> {{ e($notification->id) }}</p>
                            <p class="text-gray-600"><strong>Description:</strong> {{ Str::limit(e($notification->description),100) }}</p>
                        </div>
                    </a>
                @endif
            @endforeach
        @endif
    </div>
@endsection
