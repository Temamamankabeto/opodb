<x-layout>
<div class="container mx-auto p-4">
    <h1 class="text-xl font-semibold mb-4">Cuunfaa Gabaasa Karoora Oomisha Woggaa</h1>
   <!-- Data Table -->
   <div class="overflow-x-auto">
        <table class="min-w-full bg-white border text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="border px-2 py-1">Ganda </th>
                    <th class="border px-2 py-1">Gosa Midhaanii</th>
                    <th class="border px-2 py-1">Midhaan</th>
                    <th class="border px-2 py-1">Karoora Woggaar</th>
                    <th class="border px-2 py-1">Karoora Lafaa (Ganna)</th>
                    <th class="border px-2 py-1">Karoora Oomishaa (Ganna)</th>
                    <th class="border px-2 py-1">oomistummaa (Ganna)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                    <tr class="text-center">
                        <td class="border px-2 py-1">{{ $report->kebele_name }}</td>
                        <td class="border px-2 py-1">{{ $report->crop_type_name }}</td>
                        <td class="border px-2 py-1">{{ $report->crop_name }}</td>
                        <td class="border px-2 py-1">{{ $report->plan_year }}</td>
                        <td class="border px-2 py-1">{{ $report->total_land_ganna }}</td>
                        <td class="border px-2 py-1">{{ $report->total_production_ganna }}</td>
                        <td class="border px-2 py-1">{{ $report->total_productivity_ganna }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Compact Chart Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
        <div class="bg-white p-2 rounded-lg shadow">
            <h2 class="text-sm font-semibold mb-1">Bal'ina Lafaa Gandaan (Ganna)</h2>
            <div class="h-48">
                <canvas id="landByKebeleChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-2 rounded-lg shadow">
            <h2 class="text-sm font-semibold mb-1">Yaainsa Gosa Midhaanii</h2>
            <div class="h-48">
                <canvas id="cropTypeDistributionChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-2 rounded-lg shadow">
            <h2 class="text-sm font-semibold mb-1">Haala Baramaa Oomishaa Waggaadhaan (Ganna)</h2>
            <div class="h-48">
                <canvas id="yearlyProductionChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-2 rounded-lg shadow">
            <h2 class="text-sm font-semibold mb-1">Garaagarummaa Oomishtummaa</h2>
            <div class="h-48">
                <canvas id="productivityComparisonChart"></canvas>
            </div>
        </div>
    </div>

 
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const reports = @json($reports);

    const smallChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { labels: { font: { size: 10 } }, position: 'top' }
        },
        scales: {
            x: { ticks: { font: { size: 10 } }, stacked: false },
            y: { ticks: { font: { size: 10 } }, beginAtZero: true, stacked: false }
        }
    };

    // Bar Chart - Total Land by Kebele
    const landByKebele = reports.reduce((acc, r) => {
        acc[r.kebele_name] = (acc[r.kebele_name] || 0) + parseFloat(r.total_land_ganna);
        return acc;
    }, {});
    new Chart(document.getElementById('landByKebeleChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(landByKebele),
            datasets: [{
                label: 'Land (ha)',
                data: Object.values(landByKebele),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: smallChartOptions
    });

    // Pie Chart - Crop Type Distribution
    const cropTypeData = reports.reduce((acc, r) => {
        acc[r.crop_type_name] = (acc[r.crop_type_name] || 0) + parseFloat(r.total_land_ganna);
        return acc;
    }, {});
    new Chart(document.getElementById('cropTypeDistributionChart'), {
        type: 'pie',
        data: {
            labels: Object.keys(cropTypeData),
            datasets: [{
                data: Object.values(cropTypeData),
                backgroundColor: ['#f87171', '#60a5fa', '#facc15', '#4ade80', '#a78bfa', '#fb923c']
            }]
        },
        options: smallChartOptions
    });

    // Line Chart - Yearly Production Trends
    const productionTrends = reports.reduce((acc, r) => {
        acc[r.plan_year] = (acc[r.plan_year] || 0) + parseFloat(r.total_production_ganna);
        return acc;
    }, {});
    const years = Object.keys(productionTrends).sort();
    new Chart(document.getElementById('yearlyProductionChart'), {
        type: 'line',
        data: {
            labels: years,
            datasets: [{
                label: 'Production (qt)',
                data: years.map(y => productionTrends[y]),
                fill: false,
                backgroundColor: '#10b981',
                borderColor: '#10b981',
                tension: 0.3
            }]
        },
        options: smallChartOptions
    });

    // Stacked Bar Chart - Productivity Comparison
    const productivityData = reports.reduce((acc, r) => {
        if (!acc[r.kebele_name]) acc[r.kebele_name] = { land: 0, production: 0, productivity: 0 };
        acc[r.kebele_name].land += parseFloat(r.total_land_ganna);
        acc[r.kebele_name].production += parseFloat(r.total_production_ganna);
        acc[r.kebele_name].productivity += parseFloat(r.total_productivity_ganna);
        return acc;
    }, {});
    const kebeles = Object.keys(productivityData);
    new Chart(document.getElementById('productivityComparisonChart'), {
        type: 'bar',
        data: {
            labels: kebeles,
            datasets: [
                {
                    label: 'Land',
                    data: kebeles.map(k => productivityData[k].land),
                    backgroundColor: '#f87171'
                },
                {
                    label: 'Production',
                    data: kebeles.map(k => productivityData[k].production),
                    backgroundColor: '#60a5fa'
                },
                {
                    label: 'Productivity',
                    data: kebeles.map(k => productivityData[k].productivity),
                    backgroundColor: '#facc15'
                }
            ]
        },
        options: {
            ...smallChartOptions,
            scales: {
                x: { stacked: true },
                y: { stacked: true, beginAtZero: true }
            }
        }
    });
});
</script>
</x-layout>
