<x-layout>
    <title>Karoora Call Guddiftuu </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
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
        .filter-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .table {
            font-size: 14px;
        }
        .table th {
            background-color: #3498db;
            color: white;
            text-align: center;
            vertical-align: middle;
        }
        .table td {
            vertical-align: middle;
        }
        .table-primary {
            background-color: #e3f2fd;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.1);
        }
        .total-row {
            background-color: #f1f8e9;
            font-weight: bold;
        }
        .comparison-section {
            margin-top: 40px;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 8px;
        }
        .chart-container {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .positive-diff {
            color: #2ecc71;
            font-weight: bold;
        }
        .negative-diff {
            color: #e74c3c;
            font-weight: bold;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
        }
        .download-btn {
            background-color: #27ae60;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .download-btn:hover {
            background-color: #2ecc71;
            color: white;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
                margin: 0;
            }
            .report-header {
                padding: 10px 0;
            }
        }
    </style>
 
    <div class="container">
        <div class="report-header">
            <h1 class="report-title">Karoora Calla Guddiftuu </h1>
            <p class="text-center mb-0">Karoora Guutuu Waggaa {{Auth::user()->kebele->name}}</p>
        </div>

        <button onclick="window.print()" class="download-btn no-print">
            <i class="fas fa-download me-2"></i>karoora kee print godhit
        </button>

        <!-- Filter Form -->
        <div class="filter-section no-print">
            <h4 class="mb-4"><i class="fas fa-filter me-2"></i>Filannoo Karoora kee</h4>
            <form method="GET" action="{{ route('kebele.report') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="year" class="form-label">Bara</label>
                    <select name="year" id="year" class="form-select">
                        <option value="">-- Bara hunda --</option>
                        @foreach ($years as $year)
                            <option value="{{ $year }}" {{ request('year', $currentYear) == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="season" class="form-label">Waqtii</label>
                    <select name="season" id="season" class="form-select">
                        <option value="">-- Waqtii Hunda --</option>
                        @foreach ($seasons as $season)
                            <option value="{{ $season }}" {{ request('season') == $season ? 'selected' : '' }}>{{ $season }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="type" class="form-label">Gosa CallaGuddiftuu</label>
                    <select name="type" id="type" class="form-select">
                        <option value="">-- Hunda --</option>
                        @foreach ($types as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i> Calali
                    </button>
                </div>
            </form>
        </div>

        @php
            // Group data by year, season, and type
            $grouped = [];
            $grandTotals = [];
            $currentTotals = [];
            $previousTotals = [];

            foreach ($reports as $r) {
                $grouped[$r->year][$r->season][$r->type][] = $r;
                $currentTotals[$r->type] = ($currentTotals[$r->type] ?? 0) + $r->quantity;
            }

            foreach ($previousReports as $r) {
                $previousTotals[$r->type] = ($previousTotals[$r->type] ?? 0) + $r->quantity;
            }

            // Merge all unique types
            $allTypes = collect(array_merge(array_keys($currentTotals), array_keys($previousTotals)))->unique();

            // Function to calculate the rowspan count for year group
            function rowspanCount($nestedArray) {
                $count = 0;
                foreach ($nestedArray as $season) {
                    foreach ($season as $typeGroup) {
                        $count += count($typeGroup);
                    }
                }
                return $count;
            }

            // Function to calculate the rowspan count for season group
            function seasonRowspanCount($seasonGroup) {
                $count = 0;
                foreach ($seasonGroup as $typeGroup) {
                    $count += count($typeGroup);
                }
                return $count;
            }
        @endphp

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Bara</th>
                        <th>Waqtii</th>
                        <th>Gosa</th>
                        <th>Hamma</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($grouped as $year => $seasons)
                        @php $yearRowspan = rowspanCount($seasons); $yearPrinted = false; @endphp
                        @foreach ($seasons as $season => $types)
                            @php $seasonRowspan = seasonRowspanCount($types); $seasonPrinted = false; @endphp
                            @foreach ($types as $type => $items)
                                @foreach ($items as $index => $report)
                                    <tr>
                                        @if (!$yearPrinted)
                                            <td rowspan="{{ $yearRowspan }}" class="align-middle fw-bold">{{ $year }}</td>
                                            @php $yearPrinted = true; @endphp
                                        @endif

                                        @if (!$seasonPrinted)
                                            <td rowspan="{{ $seasonRowspan }}" class="align-middle fw-bold">{{ $season }}</td>
                                            @php $seasonPrinted = true; @endphp
                                        @endif

                                        @if ($index === 0)
                                            <td rowspan="{{ count($items) }}" class="align-middle fw-bold">{{ $type }}</td>
                                        @endif

                                        <td class="text-center">{{ number_format($report->quantity, 2) }} {{ $report->unit }}</td> 
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="8" class="no-data">
                                <i class="fas fa-database fa-3x mb-3"></i><br>
                                Daataan hin argamne. Filter kee sirreessuu yaali.
                            </td>
                        </tr>
                    @endforelse

                    <!-- Grand Totals -->
                    @if (count($grouped) > 0)
                        @foreach ($grandTotals as $type => $total)
                            <tr class="total-row">
                                <td colspan="2" class="text-end fw-bold">Ida'ama Woliigalaa "{{ $type }}" tiif</td>
                                <td colspan="2" class="text-end fw-bold">{{ number_format($total, 2) }}</td>
                                <td colspan="3"></td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Year Comparison Section -->
        @if (count($previousReports) > 0)
            <div class="comparison-section">
                <h4 class="mb-4"><i class="fas fa-chart-line me-2"></i>Wolbira Qabiinsa Baranaa Fi Bara Darbee: {{ $previousYear }} vs {{ $currentYear }}</h4>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Gosa</th>
                                <th>{{ $previousYear }} Woliigala</th>
                                <th>{{ $currentYear }} Woliigala</th>
                                <th>Difference</th>
                                <th>Change</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allTypes as $type)
                                @php
                                    $prev = $previousTotals[$type] ?? 0;
                                    $curr = $currentTotals[$type] ?? 0;
                                    $diff = $curr - $prev;
                                    $change = ($prev != 0) ? round(($diff / $prev) * 100, 2) : 0;
                                @endphp
                                <tr>
                                    <td>{{ $type }}</td>
                                    <td>{{ number_format($prev, 2) }}</td>
                                    <td>{{ number_format($curr, 2) }}</td>
                                    <td class="{{ $diff > 0 ? 'positive-diff' : ($diff < 0 ? 'negative-diff' : '') }}">
                                        {{ number_format($diff, 2) }}
                                    </td>
                                    <td class="{{ $change > 0 ? 'positive-diff' : ($change < 0 ? 'negative-diff' : '') }}">
                                        {{ $change }}%
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Charts -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="chart-container">
                            <h5 class="text-center mb-4">Wolbira Qabiinsaan  (Bar Chart) dhan</h5>
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="chart-container">
                            <h5 class="text-center mb-4">Wolbira Qabiinsaan (Line Chart) dhan</h5>
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                </div>

                <script>
                    // Bar Chart Data
                    const barCtx = document.getElementById('barChart').getContext('2d');
                    const barChart = new Chart(barCtx, {
                        type: 'bar',
                        data: {
                            labels: @json($allTypes),
                            datasets: [
                                {
                                    label: '{{ $previousYear }} Total',
                                    data: @json(array_values($previousTotals)),
                                    backgroundColor: 'rgba(231, 76, 60, 0.7)',
                                    borderColor: 'rgba(231, 76, 60, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: '{{ $currentYear }} Total',
                                    data: @json(array_values($currentTotals)),
                                    backgroundColor: 'rgba(46, 204, 113, 0.7)',
                                    borderColor: 'rgba(46, 204, 113, 1)',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return context.dataset.label + ': ' + context.raw.toLocaleString();
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return value.toLocaleString();
                                        }
                                    }
                                }
                            }
                        }
                    });

                    // Line Chart Data
                    const lineCtx = document.getElementById('lineChart').getContext('2d');
                    const lineChart = new Chart(lineCtx, {
                        type: 'line',
                        data: {
                            labels: @json($allTypes),
                            datasets: [
                                {
                                    label: '{{ $previousYear }} Total',
                                    data: @json(array_values($previousTotals)),
                                    borderColor: 'rgba(231, 76, 60, 1)',
                                    backgroundColor: 'rgba(231, 76, 60, 0.1)',
                                    tension: 0.3,
                                    fill: true
                                },
                                {
                                    label: '{{ $currentYear }} Total',
                                    data: @json(array_values($currentTotals)),
                                    borderColor: 'rgba(46, 204, 113, 1)',
                                    backgroundColor: 'rgba(46, 204, 113, 0.1)',
                                    tension: 0.3,
                                    fill: true
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return context.dataset.label + ': ' + context.raw.toLocaleString();
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return value.toLocaleString();
                                        }
                                    }
                                }
                            }
                        }
                    });
                </script>
            </div>
        @endif

        <div class="mt-5 text-center text-muted no-print">
            <p>Report generated on {{ now()->format('F j, Y \a\t h:i A') }}</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </x-layout>