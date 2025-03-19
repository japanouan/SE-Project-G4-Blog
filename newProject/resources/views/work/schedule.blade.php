<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📅 ตารางงานของคุณ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h2 class="text-center text-2xl font-bold mb-6">📅 ตารางงานของคุณ</h2>

        <div class="text-center mb-4">
            <a href="{{ route(str_replace(' ', '', Auth::user()->userType) . '.work-list') }}" 
               class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition m-2">
                🔍 ไปหน้ารับงาน
            </a>

            <a href="{{ route(str_replace(' ', '', Auth::user()->userType) . '.work.earning') }}" 
               class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition m-2">
                earning
            </a>
        </div>
        @if ($works->isEmpty())
        <div class="text-center bg-yellow-100 text-yellow-800 p-4 rounded-lg">
            ❌ ยังไม่มีงานที่ได้รับ
        </div>
        @else
        @foreach ($works as $work)
        <a href="{{ route(str_replace(' ', '', $work->selectService->service_type) . '.work.details', ['id' => encrypt($work->select_staff_detail_id)]) }}" class="block">
            <div class="bg-white p-6 rounded-lg shadow-md mb-4">
                <h4 class="text-lg font-semibold">
                    {{ \Carbon\Carbon::parse($work->selectService->reservation_date)->format('d M Y') }}
                </h4>
                <p><strong>Location:</strong> {{ e($work->selectService->address->Street) }}, 
                    {{ e($work->selectService->address->District) }}, 
                    {{ e($work->selectService->address->Province) }}</p>
                <p><strong>Appointment Time:</strong> 
                    {{ \Carbon\Carbon::parse($work->selectService->reservation_date)->format('H:i') }}</p>
                <p><strong>จำนวนลูกค้าที่ต้องให้บริการ:</strong> {{ e($work->customer_count) }} คน</p>
                <p><strong>Earning:</strong> 
                    <span class="text-green-500 font-semibold">{{ e($work->earning) }} ฿</span></p>
            </div>
        </a>
        @endforeach
        @endif
    </div>
</body>
</html>
