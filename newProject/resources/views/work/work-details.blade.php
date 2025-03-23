<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100">
    <div class="mx-8 mt-8">
        <a href="{{ route(str_replace(' ', '', Auth::user()->userType) . '.dashboard') }}"
            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
            Back
        </a>
    </div>

    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">Work Details</h2>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h4 class="text-lg font-semibold">
                {{ \Carbon\Carbon::parse($work->selectService->reservation_date)->format('d M Y') }}
            </h4>
            <p><strong>Work ID:</strong>
                {{ str_pad(e($work->select_staff_detail_id), 6, '0', STR_PAD_LEFT) }}
            </p>
            <p><strong>Location:</strong>
    @if($work->selectService->address)
        {{ e($work->selectService->address->Street) }},
        {{ e($work->selectService->address->District) }},
        {{ e($work->selectService->address->Province) }}
    @else
        Not specified
    @endif
</p>

            <p><strong>Appointment Time:</strong>
                {{ \Carbon\Carbon::parse($work->selectService->reservation_date)->format('H:i') }}
            </p>
            <p><strong>จำนวนคนที่ต้องให้บริการ:</strong>
                {{ e($work->customer_count) }} คน
            </p>
            <p><strong>Makeup Fee:</strong>
                <span class="text-green-500 font-semibold">{{ e($work->earning) }} ฿</span>
            </p>

            <label for="service_info" class="block font-semibold mt-4">Service Information:</label>
            <textarea id="service_info" class="w-full p-2 border border-gray-300 rounded mt-2" placeholder="Describe what was done..."></textarea>

            <!-- ปุ่ม Finish Job -->
            <button id="finish-job-btn" class="bg-gray-500 text-white px-4 py-2 mt-4 rounded hidden">
                Ending work
            </button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let currentTime = new Date();
            let appointmentTime = new Date("{{ \Carbon\Carbon::parse($work->selectService->reservation_date)->toIso8601String() }}");
            let serviceInfo = "{{ e($work->service_info) }}";
            let textarea = document.getElementById('service_info');

            let finishJobBtn = document.getElementById('finish-job-btn');

            if (currentTime >= appointmentTime && serviceInfo == null) {
                finishJobBtn.classList.remove('hidden');
                finishJobBtn.classList.add('bg-green-500', 'hover:bg-green-700');
            }
            textarea.value = serviceInfo;
            if (serviceInfo != null && serviceInfo.trim() != '') {
                textarea.setAttribute('readonly', true); // ทำให้ไม่สามารถแก้ไขค่าใน textarea ได้
            }

            finishJobBtn.addEventListener('click', function() {
                let serviceInfo = document.getElementById('service_info').value;

                if (!serviceInfo.trim()) {
                    alert("Please fill in the Service Information before finishing the job.");
                    return;
                }

                fetch("{{ route(str_replace(' ', '', $work->selectService->service_type) . '.work.finish', ['id' => encrypt($work->select_staff_detail_id)]) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content
                        },
                        body: JSON.stringify({
                            service_info: serviceInfo
                        })
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Work finished successfully!");
                            finishJobBtn.classList.add('hidden');
                        }
                    }).catch(error => console.error("Error:", error));
            });
        });
    </script>

</body>

</html>