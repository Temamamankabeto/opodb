<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zone Agricultural Report</title>
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
            padding: 25px 0;
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
            background-color: #2980b9;
            color: white;
            text-align: center;
            vertical-align: middle;
        }
        .table td {
            vertical-align: middle;
        }
        .woreda-row {
            background-color: #e8f4f8;
            font-weight: bold;
        }
        .kebele-row {
            background-color: #f0f8e8;
        }
        .total-row {
            background-color: #e8f8f5;
            font-weight: bold;
        }
        .zone-total-row {
            background-color: #d5e8d5;
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
            color: #27ae60;
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
            background-color: #16a085;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .download-btn:hover {
            background-color: #1abc9c;
            color: white;
        }
        .woreda-badge {
            background-color: #3498db;
            color: white;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: bold;
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
                padding: 15px 0;
            }
            .table th {
                background-color: #2980b9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="report-header">
            <h1 class="report-title">Zone Agricultural Production Report</h1>
            <p class="text-center mb-0">Consolidated report for {{ $zone->name }} Zone</p>
        </div>

        <button onclick="window.print()" class="download-btn no-print">
            <i class="fas fa-download me-2"></i>Print Report
        </button>

        <!-- Filter Form -->
        <div class="filter-section no-print">
            <h4 class="mb-4"><i class="fas fa-filter me-2"></i>Report Filters</h4>
            <form method="GET" action="{{ route('zone.report') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="year" class="form-label">Year</label>
                    <select name="year" id="year" class="form-select">
                        <option value="">-- All Years --</option>
                        @foreach ($years as $year)
                            <option value="{{ $year }}" {{ request('year', $currentYear) == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="season" class="form-label">Season</label>
                    <select name="season" id="season" class="form-select">
                        <option value="">-- All Seasons --</option>
                        @foreach ($seasons as $season)
                            <option value="{{ $season }}" {{ request('season') == $season ? 'selected' : '' }}>{{ $season }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="type" class="form-label">Type</label>
                    <select name="type" id="type" class="form-select">
                        <option value="">-- All Types --</option>
                        @foreach ($types as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="woreda" class="form-label">Woreda</label>
                    <select name="woreda" id="woreda" class="form-select">
                        <option value="">-- All Woredas --</option>
                        @foreach ($woredas as $woreda)
                            <option value="{{ $woreda->id }}" {{ request('woreda') == $woreda->id ? 'selected' : '' }}>{{ $woreda->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="kebele" class="form-label">Kebele</label>
                    <select name="kebele" id="kebele" class="form-select">
                        <option value="">-- All Kebeles --</option>
                        @foreach ($allKebeles as $kebele)
                            <option value="{{ $kebele->id }}" {{ request('kebele') == $kebele->id ? 'selected' : '' }}>{{ $kebele->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12 d-flex justify-content-end mt-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-2"></i>Apply Filters
                    </button>
                    <a href="{{ route('zone.report') }}" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-sync-alt me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        @php
            // Group data by woreda, kebele, year, season, and type
            $grouped = [];
            $grandTotals = [];
            $currentTotals = [];
            $previousTotals = [];
            $woredaTotals = [];
            $kebeleTotals = [];

            foreach ($reports as $r) {
                $grouped[$r->kebele->woreda->name][$r->kebele->name][$r->year][$r->season][$r->type][] = $r;
                $currentTotals[$r->type] = ($currentTotals[$r->type] ?? 0) + $r->quantity;
                $woredaTotals[$r->kebele->woreda->name][$r->type] = ($woredaTotals[$r->kebele->woreda->name][$r->type] ?? 0) + $r->quantity;
                $kebeleTotals[$r->kebele->name][$r->type] = ($kebeleTotals[$r->kebele->name][$r->type] ?? 0) + $r->quantity;
            }

            foreach ($previousReports as $r) {
                $previousTotals[$r->type] = ($previousTotals[$r->type] ?? 0) + $r->quantity;
            }

            // Merge all unique types
            $allTypes = collect(array_merge(array_keys($currentTotals), array_keys($previousTotals)))->unique();
        @endphp

        <div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Woreda</th>
                <th>Kebele</th>
                <th>Year</th>
                <th>Season</th>
                <th>Type</th>
                <th class="text-end">Quantity</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($grouped as $woredaName => $kebeles)
                @php $woredaRowspan = 0; @endphp
                @foreach ($kebeles as $kebeleName => $years)
                    @php 
                        $kebeleRowspan = 0;
                        foreach ($years as $year => $seasons) {
                            foreach ($seasons as $season => $types) {
                                foreach ($types as $items) {
                                    $kebeleRowspan += count($items);
                                }
                            }
                        }
                        $woredaRowspan += $kebeleRowspan;
                    @endphp
                @endforeach

                @php $woredaPrinted = false; @endphp
                @foreach ($kebeles as $kebeleName => $years)
                    @php $kebelePrinted = false; @endphp
                    @foreach ($years as $year => $seasons)
                        @foreach ($seasons as $season => $types)
                            @foreach ($types as $type => $items)
                                @foreach ($items as $report)
                                    <tr>
                                        @if (!$woredaPrinted)
                                            <td rowspan="{{ $woredaRowspan }}" class="align-middle fw-bold woreda-row">
                                                <span class="woreda-badge">{{ $woredaName }}</span>
                                            </td>
                                            @php $woredaPrinted = true; @endphp
                                        @endif

                                        @if (!$kebelePrinted)
                                            <td rowspan="{{ $kebeleRowspan }}" class="align-middle kebele-row">
                                                {{ $kebeleName }}
                                            </td>
                                            @php $kebelePrinted = true; @endphp
                                        @endif

                                        <td>{{ $year }}</td>
                                        <td>{{ $season }}</td>
                                        <td>{{ $type }}</td>
                                        <td class="text-end">{{ number_format($report->quantity, 2) }} {{ $report->unit }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    @endforeach

                    {{-- Kebele Total --}}
                    @if (isset($kebeleTotals[$kebeleName]))
                        <tr class="total-row bg-light">
                            <td colspan="4" class="text-end fw-bold">Total for {{ $kebeleName }}</td>
                            <td colspan="2">
                                @foreach ($allTypes as $type)
                                    @if (isset($kebeleTotals[$kebeleName][$type]))
                                        <div class="d-flex justify-content-between">
                                            <span>{{ $type }}</span>
                                            <span class="fw-bold">{{ number_format($kebeleTotals[$kebeleName][$type], 2) }} {{ $items[0]->unit ?? '' }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                    @endif
                @endforeach

                {{-- Woreda Total --}}
                @if (isset($woredaTotals[$woredaName]))
                    <tr class="woreda-total-row bg-secondary text-white">
                        <td colspan="4" class="text-end fw-bold">Total for {{ $woredaName }} Woreda</td>
                        <td colspan="2">
                            @foreach ($allTypes as $type)
                                @if (isset($woredaTotals[$woredaName][$type]))
                                    <div class="d-flex justify-content-between">
                                        <span>{{ $type }}</span>
                                        <span class="fw-bold">{{ number_format($woredaTotals[$woredaName][$type], 2) }} {{ $items[0]->unit ?? '' }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        <i class="fas fa-database fa-2x mb-2"></i><br>
                        No data found. Try adjusting your filters.
                    </td>
                </tr>
            @endforelse

            {{-- Grand Total --}}
            @if (count($grouped) > 0)
                <tr class="zone-total-row table-dark text-white">
                    <td colspan="4" class="text-end fw-bold">Zone Total for {{ $zone->name }}</td>
                    <td colspan="2">
                        @foreach ($allTypes as $type)
                            @if (isset($currentTotals[$type]))
                                <div class="d-flex justify-content-between">
                                    <span>{{ $type }}</span>
                                    <span class="fw-bold">{{ number_format($currentTotals[$type], 2) }} {{ $items[0]->unit ?? '' }}</span>
                                </div>
                            @endif
                        @endforeach
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>


        <!-- Year Comparison Section -->
        @if (count($previousReports) > 0)
            <div class="comparison-section">
                <h4 class="mb-4"><i class="fas fa-chart-line me-2"></i>Zone Comparison: {{ $previousYear }} vs {{ $currentYear }}</h4>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th class="text-end">{{ $previousYear }} Total</th>
                                <th class="text-end">{{ $currentYear }} Total</th>
                                <th class="text-end">Difference</th>
                                <th class="text-end">Change (%)</th>
                                <th>Trend</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allTypes as $type)
                                @php
                                    $prev = $previousTotals[$type] ?? 0;
                                    $curr = $currentTotals[$type] ?? 0;
                                    $diff = $curr - $prev;
                                    $change = ($prev != 0) ? round(($diff / $prev) * 100, 2) : 0;
                                    $unit = $reports->firstWhere('type', $type)->unit ?? '';
                                @endphp
                                <tr>
                                    <td>{{ $type }}</td>
                                    <td class="text-end">{{ number_format($prev, 2) }} {{ $unit }}</td>
                                    <td class="text-end">{{ number_format($curr, 2) }} {{ $unit }}</td>
                                    <td class="text-end {{ $diff > 0 ? 'positive-diff' : ($diff < 0 ? 'negative-diff' : '') }}">
                                        {{ number_format($diff, 2) }} {{ $unit }}
                                    </td>
                                    <td class="text-end {{ $change > 0 ? 'positive-diff' : ($change < 0 ? 'negative-diff' : '') }}">
                                        {{ $change }}%
                                    </td>
                                    <td class="text-center">
                                        @if($change > 0)
                                            <i class="fas fa-arrow-up text-success"></i>
                                        @elseif($change < 0)
                                            <i class="fas fa-arrow-down text-danger"></i>
                                        @else
                                            <i class="fas fa-equals text-muted"></i>
                                        @endif
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
                            <h5 class="text-center mb-4">Production Comparison by Type (Bar Chart)</h5>
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="chart-container">
                            <h5 class="text-center mb-4">Production Trend by Type (Line Chart)</h5>
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Woreda Comparison Chart -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="chart-container">
                            <h5 class="text-center mb-4">Production by Woreda ({{ $currentYear }})</h5>
                            <canvas id="woredaChart"></canvas>
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

                    // Woreda Chart Data
                    const woredaCtx = document.getElementById('woredaChart').getContext('2d');
                    const woredaChart = new Chart(woredaCtx, {
                        type: 'bar',
                        data: {
                            labels: @json(array_keys($woredaTotals)),
                            datasets: [
                                @foreach($allTypes as $type)
                                {
                                    label: '{{ $type }}',
                                    data: [
                                        @foreach($woredaTotals as $woredaName => $types)
                                            {{ $types[$type] ?? 0 }},
                                        @endforeach
                                    ],
                                    backgroundColor: 'rgba({{ rand(50,200) }}, {{ rand(50,200) }}, {{ rand(50,200) }}, 0.7)',
                                    borderColor: 'rgba({{ rand(50,200) }}, {{ rand(50,200) }}, {{ rand(50,200) }}, 1)',
                                    borderWidth: 1
                                },
                                @endforeach
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
            <p>Report generated on {{ now()->format('F j, Y \a\t h:i A') }} for {{ $zone->name }} Zone</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>