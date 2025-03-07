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
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        <div class="job-card" id="job-{{ $service->select_service_id }}">
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
            <button class="btn-accept-job" onclick="acceptJob({{$service->select_service_id}},'{{ $service->service_type }}')">รับงาน</button>
            <div id="response-{{ $service->select_service_id }}"></div> <!-- Add response div -->
            @endif
        </div>
        @endforeach

    </div>

    <script>
        function acceptJob(serviceId,userType) {
            // ป้องกันการกดซ้ำโดยปิดปุ่มชั่วคราว
            const button = document.querySelector(`#job-${serviceId} .btn-accept-job`);
            const cleanedUserType = userType.replace(/\s+/g, ''); // ลบช่องว่างทั้งหมด
            const url = `/${cleanedUserType}/accept-job`;  // ดึง URL จาก route name
            button.disabled = true;

            // สร้างข้อมูลที่จะส่งไปยังเซิร์ฟเวอร์
            const data = {
                select_service_id: serviceId
            };

            // ส่ง AJAX request ด้วย fetch
            fetch(url, { // name or url in route
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content // สำหรับ Laravel CSRF protection
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('เกิดข้อผิดพลาดในการรับงาน');
                }
                return response.json();
            })
            .then(result => {
                // อัปเดตหน้าเว็บเมื่อสำเร็จ
                const responseDiv = document.getElementById(`response-${serviceId}`);
                responseDiv.innerHTML = '<span class="text-success">รับงานสำเร็จ!</span>';
                
                // อัปเดตจำนวนช่างที่รับงาน (ถ้าต้องการ)
                const staffCountElement = document.querySelector(`#job-${serviceId} p:nth-child(4)`);
                staffCountElement.innerHTML = `<strong>ช่างที่รับแล้ว:</strong> ${result.staff_count} / ${result.required_staff}`;

                // ถ้าต้องการซ่อนปุ่มหลังรับงาน
                button.style.display = 'none';
            })
            .catch(error => {
                // แสดงข้อผิดพลาด
                const responseDiv = document.getElementById(`response-${serviceId}`);
                responseDiv.innerHTML = `<span class="text-danger">${error.message}</span>`;
                button.disabled = false; // เปิดปุ่มกลับมาใช้งาน
            });
        }
    </script>

</body>

</html>