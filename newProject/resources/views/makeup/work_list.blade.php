<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการงานที่เปิดรับ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .job-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin: 15px 0;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-accept-job {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-accept-job:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>

    <div class="container mt-4">
        <h2 class="text-center mb-4">📋 รายการงานที่เปิดรับ</h2>

        @if ($services->isEmpty())
        <div class="alert alert-warning text-center">
            ไม่มีงานที่เปิดรับในขณะนี้
        </div>
        @endif

        @foreach ($services as $service)
        <div class="job-card">
            <h3>Booking ID: {{ $service->booking_id }}</h3>
            <p><strong>ประเภทงาน:</strong> {{ $service->service_type }}</p>
            <p><strong>จำนวนลูกค้า:</strong> {{ $service->customer_count }}</p>
            <p><strong>ช่างที่รับแล้ว:</strong> {{ $service->staff_count }} / {{ $service->required_staff }}</p>

            @if ($service->address)
            <p><strong>ที่อยู่:</strong> {{ $service->address->Street }}, {{ $service->address->District }}, {{ $service->address->Province }}</p>
            @else
            <p><strong>ที่อยู่:</strong> ไม่ระบุ</p>
            @endif

            @if (Auth::user()->userType === $service->service_type)
            <button class="btn-accept-job" onclick="acceptJob({{$service->select_service_id}})">รับงาน</button>
            @endif
        </div>
        @endforeach

    </div>

    <script>
        function acceptJob(serviceId) {
            alert("คุณกำลังรับงาน ID: " + serviceId);
            // คุณสามารถเปลี่ยน alert ให้เป็น AJAX Request เพื่อให้ช่างกดรับงานได้จริง
        }
    </script>

</body>

</html>