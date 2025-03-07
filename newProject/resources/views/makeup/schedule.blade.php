<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Schedule</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .schedule-table {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            margin: 15px 0;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-finish-job {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-finish-job:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Rider Schedule</h2>
        <a href="{{ route(str_replace(' ', '', $works[0]->selectService->service_type) . '.work-list') }}"><strong>Go To Work List</strong></a>


        

        @foreach ($works as $work)
        <div class="schedule-table">
            <h4>{{ \Carbon\Carbon::parse($work->selectService->reservation_date)->format('d M Y') }}</h4>
            <p><strong>Location:</strong> {{ $work->selectService->address->Street }}, {{ $work->selectService->address->District }}, {{ $work->selectService->address->Province }}</p>
            <p><strong>Appointment Time:</strong> {{ \Carbon\Carbon::parse($work->selectService->reservation_date)->format('H:i') }}</p>
            <p><strong>Earning:</strong> {{ $work->earning }} ฿</p>
            <p><strong>{{ strtoupper($work->selectService->service_type) }}</strong></p>

            <!-- ตรวจสอบเวลา และแสดงปุ่มถ้าเวลาผ่านไปแล้ว -->
            <button class="btn-finish-job" id="finish-job-{{ $work->select_staff_detail_id }}"
                @if (\Carbon\Carbon::now()->gte(\Carbon\Carbon::parse($work->selectService->reservation_date)))
                style="display:block;" <!-- แสดงปุ่มเมื่อถึงเวลา -->
                @else
                style="display:none;" <!-- ซ่อนปุ่มถ้ายังไม่ถึงเวลา -->
                @endif
                >
                Finish Job
            </button>
        </div>

        @endforeach
    </div>

    <script>
        // Javascript สำหรับแสดงปุ่ม "Finish Job" เมื่อเวลาผ่านไปแล้ว
        window.onload = function() {
            let currentTime = new Date();
            let finishButtons = document.querySelectorAll('.btn-finish-job');

            finishButtons.forEach(function(button) {
                let appointmentTime = new Date(button.getAttribute('data-appointment-time'));

                if (currentTime >= appointmentTime) {
                    button.style.display = 'block'; // แสดงปุ่ม
                } else {
                    button.style.display = 'none'; // ซ่อนปุ่ม
                }
            });
        };
    </script>

</body>

</html>