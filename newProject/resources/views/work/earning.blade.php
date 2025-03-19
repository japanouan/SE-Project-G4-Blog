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
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Earnings per hour</h2>
        <canvas id="earningsChart" height="400"></canvas>
    </div>

</div>

<script>
    const ctx = document.getElementById('earningsChart').getContext('2d');
    const earningsData = @json($earningsPerHour);

    const chartData = {
        labels: ['6:00', '7:00', '8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00'],
        datasets: [{
            label: 'Earnings (฿)',
            data: Object.values(earningsData),
            backgroundColor: [
                'rgba(66, 153, 225, 0.5)',
                'rgba(98, 204, 204, 0.5)',
                'rgba(139, 89, 249, 0.5)',
                'rgba(255, 99, 132, 0.5)',
                'rgba(255, 159, 64, 0.5)',
                'rgba(255, 205, 86, 0.5)',
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(153, 102, 255, 0.5)',
                'rgba(255, 159, 64, 0.5)',
                'rgba(75, 192, 192, 0.5)'
            ],
            borderColor: [
                'rgba(66, 153, 225, 1)',
                'rgba(98, 204, 204, 1)',
                'rgba(139, 89, 249, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255, 205, 86, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1
        }]
    };

    const earningsChart = new Chart(ctx, {
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
        });
</script>

</body>
</html>
