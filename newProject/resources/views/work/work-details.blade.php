@extends('layouts.staff-dashboard')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="flex items-center">
            <i class="fas fa-info-circle card-header-icon"></i>
            <h2 class="card-title">รายละเอียดงาน</h2>
        </div>
        <div>
            @php
                $routePrefix = str_replace(' ', '', Auth::user()->userType);
            @endphp
            <a href="{{ route($routePrefix.'.dashboard') }}"
                class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> กลับ
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="bg-white p-6 rounded-lg relative">
            <!-- Job status indicator -->
            @if($work->service_info)
            <div class="absolute top-4 right-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-1"></i> Completed
                </span>
            </div>
            @else
                @php
                    $appointmentTime = \Carbon\Carbon::parse($work->selectService->reservation_date);
                    $now = \Carbon\Carbon::now();
                @endphp
                
                @if($appointmentTime->isFuture())
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <i class="fas fa-clock mr-1"></i> Upcoming
                    </span>
                </div>
                @else
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                        <i class="fas fa-exclamation-circle mr-1"></i> Needs completion
                    </span>
                </div>
                @endif
            @endif

            <h4 class="text-lg font-semibold">
                {{ \Carbon\Carbon::parse($work->selectService->reservation_date)->format('d M Y') }}
            </h4>
            <p class="mb-2"><strong>Work ID:</strong>
                {{ str_pad(e($work->select_staff_detail_id), 6, '0', STR_PAD_LEFT) }}
            </p>
            <p class="mb-2"><strong>Location:</strong>
                @if($work->selectService->address)
                    {{ e($work->selectService->address->Street) }},
                    {{ e($work->selectService->address->District) }},
                    {{ e($work->selectService->address->Province) }}
                @else
                    Not specified
                @endif
            </p>
            <p class="mb-2"><strong>Appointment Time:</strong>
                {{ \Carbon\Carbon::parse($work->selectService->reservation_date)->format('H:i') }}
            </p>
            <p class="mb-2"><strong>จำนวนคนที่ต้องให้บริการ:</strong>
                {{ e($work->customer_count) }} คน
            </p>
            <p class="mb-4"><strong>Makeup Fee:</strong>
                <span class="text-green-500 font-semibold">{{ e($work->earning) }} ฿</span>
            </p>

            <div class="mt-6">
                <label for="service_info" class="block font-semibold mb-2">Service Information:</label>
                <textarea id="service_info" class="w-full p-3 border border-gray-300 rounded" rows="4" placeholder="Describe what was done...">{{ $work->service_info }}</textarea>
            </div>

            <!-- ปุ่ม Finish Job -->
            <button id="finish-job-btn" class="btn btn-success mt-4 {{ $work->service_info ? 'hidden' : '' }}">
                <i class="fas fa-check-circle"></i> Ending work
            </button>
        </div>
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
                        textarea.setAttribute('readonly', true);
                        
                        // Add completion indicator after successful submission
                        const statusIndicator = document.querySelector('.absolute.top-4.right-4');
                        if (statusIndicator) {
                            statusIndicator.innerHTML = `
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Completed
                                </span>
                            `;
                        }
                    }
                }).catch(error => console.error("Error:", error));
        });
    });
</script>
@endsection
