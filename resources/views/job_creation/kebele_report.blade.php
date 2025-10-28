<x-layout>

<style>
     .report-header {
            background-color: #2c3e50;
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
            border-radius: 5px;
        }
        .report-title {
            font-weight: 700;
            text-align: center;
        }
</style>
<div id="print-area">
<div class="bg-white rounded-2xl shadow p-6"> <!-- Start of common card -->
   <div class="report-header">
            <h1 class="report-title">Gabaasa Karoora Carraa Hojii Uumuu </h1>
            <p class="text-center mb-0">  {{Auth::user()->kebele->name}}</p>
        </div>

    <!-- Filter Card -->
    <div class=" bg-white rounded-2xl shadow p-6 mb-6" style="width: 100;">
        <form method="GET" action="{{ route('reports.job_report') }}">
            <div class="flex flex-wrap gap-4">
                <input type="number" name="year" value="{{ request('year') }}" placeholder="Year" class="border border-gray-300 rounded-lg p-2 w-40">

                <select name="job_type_id" class="border border-gray-300 rounded-lg p-2 w-60">
                    <option value="">All Job Types</option>
                    @foreach($jobTypes as $type)
                        <option value="{{ $type->id }}" {{ request('job_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>

                <select name="enterprise_type" class="border border-gray-300 rounded-lg p-2 w-60">
                    <option value="">All Enterprise Types</option>
                    <option value="kan gurmaa’e" {{ request('enterprise_type') == 'kan gurmaa’e' ? 'selected' : '' }}>kan gurmaa’e</option>
                    <option value="kan hojii tti gale" {{ request('enterprise_type') == 'kan hojii tti gale' ? 'selected' : '' }}>kan hojii tti gale</option>
                </select>

                <button type="submit" class=" btn btn-sm btn-success">
                    Filter
                </button>
                <div class="text-center my-4">
    <button onclick="printCards()" class="btn btn-primary">🖨️ Print Report</button>
</div>
            </div>
        </form>
    </div>
    <br>

    @if($grouped->isNotEmpty())
        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow p-6 mb-6 overflow-x-auto">
            <table class="w-100 text-sm text-left border border-gray-200">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="border px-4 py-2">Year</th>
                        <th class="border px-4 py-2">Enterprise Type</th>
                        <th class="border px-4 py-2">Job Type</th>
                        <th class="border px-4 py-2">Male</th>
                        <th class="border px-4 py-2">Female</th>
                        <th class="border px-4 py-2">Total</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                    @foreach($grouped as $key => $data)
                        @php
                            list($yr, $job_type_id, $enterprise_type) = explode('|', $key);
                            $jobTypeName = $jobTypes->firstWhere('id', $job_type_id)->name ?? 'N/A';
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2">{{ $yr }}</td>
                            <td class="border px-4 py-2">{{ $enterprise_type }}</td>
                            <td class="border px-4 py-2">{{ $jobTypeName }}</td>
                            <td class="border px-4 py-2">{{ $data['male'] }}</td>
                            <td class="border px-4 py-2">{{ $data['female'] }}</td>
                            <td class="border px-4 py-2 font-semibold">{{ $data['total'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


<br>
 
<!-- Comparison Table with Change Highlight -->
<div class="bg-white rounded-2xl shadow p-6 mb-6 overflow-x-auto">
    <h3 class="text-xl font-semibold mb-4 text-center text-blue-600">📋 Comparison of {{ $year }} vs {{ $year - 1 }}</h3>
    <table class="w-full text-sm text-left border border-gray-200">
        <thead class="bg-gray-100 text-gray-700">
            <tr>
                <th class="border px-4 py-2">Metric</th>
                <th class="border px-4 py-2">{{ $year - 1 }}</th>
                <th class="border px-4 py-2">{{ $year }}</th>
                <th class="border px-4 py-2">Change</th>
                <th class="border px-4 py-2">% Change</th>
            </tr>
        </thead>
        <tbody class="text-gray-800">
            @php
                $dataN = $yearlyTotals[$year] ?? ['male' => 0, 'female' => 0, 'total' => 0];
                $dataN1 = $yearlyTotals[$year - 1] ?? ['male' => 0, 'female' => 0, 'total' => 0];

                function formatChange($current, $previous) {
                    $diff = $current - $previous;
                    $percentage = $previous != 0 ? ($diff / $previous) * 100 : 0;

                    $color = $diff > 0 ? 'text-green-600' : ($diff < 0 ? 'text-red-600' : 'text-gray-600');

                    return [
                        'diff' => $diff,
                        'percentage' => number_format($percentage, 1) . '%',
                        'color' => $color
                    ];
                }

                $maleChange = formatChange($dataN['male'], $dataN1['male']);
                $femaleChange = formatChange($dataN['female'], $dataN1['female']);
                $totalChange = formatChange($dataN['total'], $dataN1['total']);
            @endphp

            <tr>
                <td class="border px-4 py-2">Male</td>
                <td class="border px-4 py-2">{{ $dataN1['male'] }}</td>
                <td class="border px-4 py-2">{{ $dataN['male'] }}</td>
                <td class="border px-4 py-2 {{ $maleChange['color'] }}">{{ $maleChange['diff'] }}</td>
                <td class="border px-4 py-2 {{ $maleChange['color'] }}">{{ $maleChange['percentage'] }}</td>
            </tr>
            <tr>
                <td class="border px-4 py-2">Female</td>
                <td class="border px-4 py-2">{{ $dataN1['female'] }}</td>
                <td class="border px-4 py-2">{{ $dataN['female'] }}</td>
                <td class="border px-4 py-2 {{ $femaleChange['color'] }}">{{ $femaleChange['diff'] }}</td>
                <td class="border px-4 py-2 {{ $femaleChange['color'] }}">{{ $femaleChange['percentage'] }}</td>
            </tr>
            <tr>
                <td class="border px-4 py-2 font-semibold">Total</td>
                <td class="border px-4 py-2 font-semibold">{{ $dataN1['total'] }}</td>
                <td class="border px-4 py-2 font-semibold">{{ $dataN['total'] }}</td>
                <td class="border px-4 py-2 font-semibold {{ $totalChange['color'] }}">{{ $totalChange['diff'] }}</td>
                <td class="border px-4 py-2 font-semibold {{ $totalChange['color'] }}">{{ $totalChange['percentage'] }}</td>
            </tr>
        </tbody>
    </table>
</div>

<br>
        <!-- Chart Card -->
        <div class="bg-white rounded-2xl shadow p-6">
            <canvas id="comparisonChart" height="100"></canvas>
        </div>
    @else
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg">
            No records found for the selected filters.
        </div>
    @endif
</div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('comparisonChart').getContext('2d');

        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Male', 'Female', 'Total'],
                datasets: [
                    @if(request('year') && $yearlyTotals->has(request('year') - 1))
                    {
                        label: '{{ request('year') - 1 }}',
                        data: [
                            {{ $yearlyTotals[request('year') - 1]['male'] ?? 0 }},
                            {{ $yearlyTotals[request('year') - 1]['female'] ?? 0 }},
                            {{ $yearlyTotals[request('year') - 1]['total'] ?? 0 }}
                        ],
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    },
                    @endif
                    @if(request('year') && $yearlyTotals->has(request('year')))
                    {
                        label: '{{ request('year') }}',
                        data: [
                            {{ $yearlyTotals[request('year')]['male'] ?? 0 }},
                            {{ $yearlyTotals[request('year')]['female'] ?? 0 }},
                            {{ $yearlyTotals[request('year')]['total'] ?? 0 }}
                        ],
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    },
                    @endif
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: '📈 Yearly Job Creation Comparison',
                        font: {
                            size: 16
                        }
                    }
                }
            }
        });
    </script>
    <script>
    function printCards() {
        const printContents = document.getElementById('print-area').innerHTML;
        const originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload(); // Optional: reload the page after print to restore events/styles
    }
</script>
</x-layout>
