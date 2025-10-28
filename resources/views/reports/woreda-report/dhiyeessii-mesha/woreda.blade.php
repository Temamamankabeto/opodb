<x-layout>
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-2xl font-bold mb-4">Woreda Resource Supply & Loan Mechanization Report</h2>

        {{-- Year Selection --}}
        <form method="GET" class="mb-6">
            <label for="year" class="block mb-2 font-semibold">Select Year:</label>
            <select name="year" id="year" class="form-select w-64" onchange="this.form.submit()">
                @for ($y = now()->year; $y >= now()->year - 10; $y--)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </form>

        {{-- Summary: Material Supplies --}}
        <h3 class="text-lg font-semibold mt-6 mb-2">Material Supplies (Woreda Total)</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-2">Material Name</th>
                        <th class="border p-2">{{ $prevYear }} (Total Qty)</th>
                        <th class="border p-2">{{ $year }} (Total Qty)</th>
                        <th class="border p-2">Change (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($materialData as $name => $data)
                        @php
                            $prev = $data['previous'] ?? 0;
                            $curr = $data['current'] ?? 0;
                            $change = $prev ? (($curr - $prev) / $prev) * 100 : 0;
                        @endphp
                        <tr>
                            <td class="border p-2">{{ $name }}</td>
                            <td class="border p-2">{{ number_format($prev, 2) }}</td>
                            <td class="border p-2">{{ number_format($curr, 2) }}</td>
                            <td class="border p-2">{{ number_format($change, 1) }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Summary: Loan Mechanizations --}}
        <h3 class="text-lg font-semibold mt-8 mb-2">Loan Mechanizations (Woreda Total)</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-2">Loan Type</th>
                        <th class="border p-2">{{ $prevYear }} (Total Qty)</th>
                        <th class="border p-2">{{ $year }} (Total Qty)</th>
                        <th class="border p-2">Change (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($loanData as $type => $data)
                        @php
                            $prev = $data['previous'] ?? 0;
                            $curr = $data['current'] ?? 0;
                            $change = $prev ? (($curr - $prev) / $prev) * 100 : 0;
                        @endphp
                        <tr>
                            <td class="border p-2">{{ $type }}</td>
                            <td class="border p-2">{{ number_format($prev, 2) }}</td>
                            <td class="border p-2">{{ number_format($curr, 2) }}</td>
                            <td class="border p-2">{{ number_format($change, 1) }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Chart Comparison --}}
        <h3 class="text-lg font-semibold mt-8 mb-4">Visual Comparison</h3>
        <div class="overflow-x-auto">
            <canvas id="comparisonChart" height="120"></canvas>
        </div>

        {{-- Detailed breakdown by kebele --}}
        <h3 class="text-lg font-semibold mt-10 mb-4">Detailed Breakdown by Kebele</h3>

        {{-- Material Supplies by Kebele --}}
        <h4 class="font-semibold mt-4 mb-2">Material Supplies</h4>
        <div class="overflow-x-auto mb-6">
            <table class="min-w-full border border-gray-300 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-2">Kebele</th>
                        <th class="border p-2">Material Name</th>
                        <th class="border p-2">{{ $year }} (Qty)</th>
                        <th class="border p-2">{{ $prevYear }} (Qty)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($materialByKebele as $record)
                        <tr>
                            <td class="border p-2">{{ $record->kebele_name }}</td>
                            <td class="border p-2">{{ $record->material_name }}</td>
                            <td class="border p-2">{{ number_format($record->current_qty, 2) }}</td>
                            <td class="border p-2">{{ number_format($record->prev_qty, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Loan Mechanizations by Kebele --}}
        <h4 class="font-semibold mt-4 mb-2">Loan Mechanizations</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-2">Kebele</th>
                        <th class="border p-2">Loan Type</th>
                        <th class="border p-2">{{ $year }} (Qty)</th>
                        <th class="border p-2">{{ $prevYear }} (Qty)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($loanByKebele as $record)
                        <tr>
                            <td class="border p-2">{{ $record->kebele_name }}</td>
                            <td class="border p-2">{{ $record->loan_type }}</td>
                            <td class="border p-2">{{ number_format($record->current_qty, 2) }}</td>
                            <td class="border p-2">{{ number_format($record->prev_qty, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    {{-- Chart.js + Datalabels --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>

    <script>
        const ctx = document.getElementById('comparisonChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [
                    {
                        label: '{{ $prevYear }}',
                        data: {!! json_encode($prevData) !!},
                        backgroundColor: '#60a5fa'
                    },
                    {
                        label: '{{ $year }}',
                        data: {!! json_encode($currData) !!},
                        backgroundColor: '#34d399'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        color: '#1f2937',
                        font: { size: 12, weight: 'bold' },
                        formatter: value => value.toLocaleString()
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                            font: { size: 12 }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { font: { size: 12 } }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    </script>
</x-layout>
