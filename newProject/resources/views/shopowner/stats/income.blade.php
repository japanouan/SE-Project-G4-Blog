@extends('layouts.shopowner-layout')

@section('title', 'รายได้')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="flex items-center">
            <i class="fas fa-money-bill-wave card-header-icon mr-2"></i>
            <h2 class="card-title text-xl font-bold">สถิติรายได้</h2>
        </div>
    </div>
    <div class="card-body">
        <div class="flex mb-6">
            <form method="GET" action="{{ route('shopowner.stats.income') }}" class="flex gap-4">
                <button type="submit" name="period" value="daily" class="btn {{ $period == 'daily' ? 'btn-primary' : 'btn-outline' }}">
                    รายวัน
                </button>
                <button type="submit" name="period" value="weekly" class="btn {{ $period == 'weekly' ? 'btn-primary' : 'btn-outline' }}">
                    รายสัปดาห์
                </button>
                <button type="submit" name="period" value="monthly" class="btn {{ $period == 'monthly' ? 'btn-primary' : 'btn-outline' }}">
                    รายเดือน
                </button>
                <button type="submit" name="period" value="yearly" class="btn {{ $period == 'yearly' ? 'btn-primary' : 'btn-outline' }}">
                    รายปี
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">รายได้ทั้งหมด</h2>
                <p class="text-3xl text-green-600 font-bold">{{ number_format($totalEarnings, 2) }} ฿</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">การจองทั้งหมด</h2>
                <p class="text-3xl text-blue-600 font-bold">{{ $totalBookings }} รายการ</p>
            </div>
        </div>

        <div class="chart-container bg-white p-6 rounded-lg shadow-sm">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                @if($period == 'daily')
                    สถิติรายได้วันนี้
                @elseif($period == 'weekly')
                    สถิติรายได้สัปดาห์นี้
                @elseif($period == 'monthly')
                    สถิติรายได้เดือนนี้
                @else
                    สถิติรายได้ปีนี้
                @endif
            </h2>
            <canvas id="earningsChart" height="100"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const earningsData = @json($earningsData);
    const labels = Object.keys(earningsData);
    const data = Object.values(earningsData);
    const period = @json($period);

    let chartLabels = labels;
    
    if (period === 'weekly') {
        // แปลง labels เป็นชื่อวันในสัปดาห์
        const dayNames = ['จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์', 'อาทิตย์'];
        
        // เปลี่ยน labels ที่เป็นตัวเลข (1, 2, 3, ...) ให้เป็นชื่อวัน
        chartLabels = labels.map(label => {
            const dayIndex = parseInt(label) - 1; // ลด 1 เพื่อให้ตรงกับ index ของ `dayNames`
            return dayNames[dayIndex];
        });
    } else if (period === 'yearly') {
        // แปลง labels เป็นชื่อเดือนในภาษาไทย
        const monthNames = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
        
        // เปลี่ยน labels ที่เป็นตัวเลข (01, 02, 03, ...) ให้เป็นชื่อเดือน
        chartLabels = labels.map(label => {
            const monthIndex = parseInt(label) - 1; // ลด 1 เพื่อให้ตรงกับ index ของ `monthNames`
            return monthNames[monthIndex];
        });
    }

    const chartData = {
        labels: chartLabels,
        datasets: [{
            label: 'รายได้ (฿)',
            data: data,
            backgroundColor: 'rgba(139, 157, 249, 0.5)',
            borderColor: 'rgba(139, 157, 249, 1)',
            borderWidth: 1
        }]
    };

    const earningsChart = new Chart(document.getElementById('earningsChart').getContext('2d'), {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'สถิติรายได้'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' ฿';
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
