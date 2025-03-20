<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Earnings | ThaiWijit</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Earnings</h1>

        <div class="flex mb-6">
            <form method="GET" action="{{ route(str_replace(' ', '', Auth::user()->userType) . '.work.earning') }}" class="flex gap-4">
                <button type="submit" name="period" value="daily" class="bg-blue-500 text-white py-2 px-4 rounded-md {{ $period == 'daily' ? 'bg-blue-700' : '' }}">
                    Daily
                </button>
                <button type="submit" name="period" value="weekly" class="bg-blue-500 text-white py-2 px-4 rounded-md {{ $period == 'weekly' ? 'bg-blue-700' : '' }}">
                    Weekly
                </button>
                <button type="submit" name="period" value="monthly" class="bg-blue-500 text-white py-2 px-4 rounded-md {{ $period == 'monthly' ? 'bg-blue-700' : '' }}">
                    Monthly
                </button>
            </form>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">You have earned</h2>
                <p class="text-3xl text-green-600 font-bold">{{ number_format($totalEarnings, 2) }} ฿</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">You have done</h2>
                <p class="text-3xl text-blue-600 font-bold">{{ $tasks }} Tasks</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md mt-8">
            @if($period == 'daily')
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Daily</h2>
            @elseif($period == 'monthly')
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Weekly</h2>
            @else
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Monthly</h2>
            @endif
            <canvas id="earningsChart" height="100"></canvas>
        </div>

    </div>

    <script>
        const earningsData = @json($earningsPerHour); // ส่งข้อมูลจาก PHP ไปยัง JavaScript

        const labels = Object.keys(earningsData); // ดึง key (วันที่)
        const data = Object.values(earningsData); // ดึง value (รายได้)
        const period = @json($period); // ดึง value (รายได้)

        if (period == 'weekly') {

            // แปลง labels เป็นชื่อวันในสัปดาห์
            const dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

            // เปลี่ยน labels ที่เป็นตัวเลข (1, 2, 3, ...) ให้เป็นชื่อวัน (Monday, Tuesday, Wednesday, ...)
            const updatedLabels = labels.map(label => {
                const dayIndex = parseInt(label) - 1; // ลด 1 เพื่อให้ตรงกับ index ของ `dayNames`
                return dayNames[dayIndex];
            });


            const chartData = {
                labels: dayNames,
                datasets: [{
                    label: 'Earnings (฿)',
                    data: data,
                    backgroundColor: 'rgba(66, 153, 225, 0.5)',
                    borderColor: 'rgba(66, 153, 225, 1)',
                    borderWidth: 1
                }]
            };

            const earningsChart = new Chart(document.getElementById('earningsChart').getContext('2d'), {
                type: 'bar',
                data: chartData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 200
                            }
                        }
                    }
                }
            });
        } else {
            const chartData = {
                labels: labels,
                datasets: [{
                    label: 'Earnings (฿)',
                    data: data,
                    backgroundColor: 'rgba(66, 153, 225, 0.5)',
                    borderColor: 'rgba(66, 153, 225, 1)',
                    borderWidth: 1
                }]
            };

            const earningsChart = new Chart(document.getElementById('earningsChart').getContext('2d'), {
                type: 'bar',
                data: chartData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 200
                            }
                        }
                    }
                }
            });
        }
    </script>

</body>

</html>