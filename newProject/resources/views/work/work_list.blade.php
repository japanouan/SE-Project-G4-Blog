<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📋 รายการงานที่เปิดรับ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h2 class="text-center text-2xl font-bold mb-6">📋 รายการงานที่เปิดรับ</h2>
        <div class="text-center mb-4">
            <a href="{{ route(str_replace(' ', '', Auth::user()->userType) . '.dashboard') }}" 
               class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
               📅 ไปหน้าตารางงาน
            </a>
        </div>

        @if ($services->isEmpty())
        <div class="text-center bg-yellow-100 text-yellow-800 p-4 rounded-lg">
            ไม่มีงานที่เปิดรับในขณะนี้
        </div>
        @endif

        @foreach ($services as $service)
        @php
        $staff_count = count($service->work_distribution);
        $assigned_customers = $service->work_distribution[$service->staff_count] ?? 0;
        $earning = $assigned_customers * 2000;
        @endphp

        <div class="bg-white p-6 rounded-lg shadow-md mb-4" id="job-{{ $service->select_service_id }}">
            <h3 class="text-lg font-bold">Booking ID: {{ $service->booking_id }}</h3>
            <p><strong>ประเภทงาน:</strong> {{ e($service->service_type) }}</p>
            <p><strong>วันเวลาที่ต้องให้บริการ:</strong>
                {{ \Carbon\Carbon::parse($service->reservation_date)->format('d M Y, H:i') }}
            </p>
            <p><strong>จำนวนลูกค้าที่ต้องให้บริการ:</strong> {{ $assigned_customers }} คน</p>
            <p><strong>ช่างที่รับแล้ว:</strong> {{ $service->staff_count }} / {{ $staff_count }}</p>
            <p><strong>ค่าตอบแทน:</strong> <span class="text-green-600 font-semibold">{{ $earning }} ฿</span></p>

            @if ($service->address)
            <p><strong>ที่อยู่:</strong> {{ e($service->address->Street) }}, {{ e($service->address->District) }}, {{ e($service->address->Province) }}</p>
            @else
            <p><strong>ที่อยู่:</strong> ไม่ระบุ</p>
            @endif

            @if (Auth::user()->userType === $service->service_type && $assigned_customers > 0)
            <button class="bg-green-500 text-white px-4 py-2 rounded-md mt-3 hover:bg-green-600 transition"
                onclick="acceptJob({{$service->select_service_id}},'{{ $service->service_type }}')">
                ✅ รับงาน
            </button>
            <div id="response-{{ $service->select_service_id }}"></div>
            @endif
        </div>
        @endforeach
    </div>
</body>
<script>
    function acceptJob(serviceId, userType) {
        const button = document.querySelector(`#job-${serviceId} button`);
        const cleanedUserType = userType.replace(/\s+/g, '');
        const url = `/${cleanedUserType}/accept-job`;
        button.disabled = true;

        const data = {
            select_service_id: serviceId
        };

        fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) throw new Error('เกิดข้อผิดพลาดในการรับงาน');
                return response.json();
            })
            .then(result => {
                document.getElementById(`response-${serviceId}`).innerHTML =
                    '<span class="text-green-600">✅ รับงานสำเร็จ!</span>';

                const staffCountElement = document.querySelector(`#job-${serviceId} p:nth-child(5)`);
                staffCountElement.innerHTML = `<strong>ช่างที่รับแล้ว:</strong> ${result.staff_count} / ${result.required_staff}`;

                button.style.display = 'none';
            })
            .catch(error => {
                document.getElementById(`response-${serviceId}`).innerHTML =
                    `<span class="text-red-600">${error.message}</span>`;
                button.disabled = false;
            });
    }
</script>

</html>