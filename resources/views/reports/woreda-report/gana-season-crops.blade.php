 <x-layout>
    <div class="container-fluid">
        <div class="row">
            <!-- Filter Card (now at the top) -->
            <div class="col-12 mb-4">
                <div class="card shadow-lg" style="border-radius: 15px; border: none;">
                    <div class="card-header bg-gradient-primary text-white" style="border-radius: 15px 15px 0 0 !important;">
                        <h4 class="mb-0"><i class="fas fa-sliders-h me-2"></i> Filter Data</h4>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('reports.woreda-crop-analysis') }}">
                            <div class="row">
                                <!-- Year Select -->
                                <div class="col-md-4 mb-3">
                                    <label for="year" class="form-label fw-bold">Year</label>
                                    <select class="form-select shadow-sm" name="year" id="year">
                                        <option value="">All Years</option>
                                        @foreach($availableYears as $year)
                                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Season Select -->
                                <div class="col-md-4 mb-3">
                                    <label for="season" class="form-label fw-bold">Season</label>
                                    <select class="form-select shadow-sm" name="season" id="season">
                                        <option value="">All Seasons</option>
                                        @foreach($availableSeasons as $season)
                                            <option value="{{ $season }}" {{ $selectedSeason == $season ? 'selected' : '' }}>
                                                {{ $season }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Crop Select -->
                                <div class="col-md-4 mb-3">
                                    <label for="crop_id" class="form-label fw-bold">Crop</label>
                                    <select class="form-select shadow-sm" name="crop_id" id="crop_id">
                                        <option value="">All Crops</option>
                                        @foreach($availableCrops as $crop)
                                            <option value="{{ $crop->id }}" {{ $selectedCrop == $crop->id ? 'selected' : '' }}>
                                                {{ $crop->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 d-flex justify-content-between">
                                    <!-- Filter Button -->
                                    <button type="submit" class="btn btn-primary shadow-sm py-2">
                                        <i class="fas fa-filter me-2"></i> Apply Filters
                                    </button>

                                    <!-- Reset Button -->
                                    <a href="{{ route('reports.kebele-crop-analysis') }}"
                                       class="btn btn-outline-secondary shadow-sm py-2">
                                        <i class="fas fa-sync-alt me-2"></i> Reset Filters
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="col-lg-12">
                <h2 class="my-4">Kebele Crop Production Analysis</h2>

                <!-- Download Full Report Button -->
                <div class="text-end mb-4">
                    <button onclick="downloadFullReportAsPDF()" class="btn btn-danger">
                        <i class="fas fa-file-pdf"></i>  Download Full Report as PDF
                    </button>
                </div>

                <!-- Report Content -->
                <div id="reportContent">
                    <!-- Report Summary -->
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h4 class="mb-0">

                            Report Summary ({{ $selectedYear - 1 }} vs {{ $selectedYear }})</h4>

                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <!-- Land Area -->
                                <div class="col-md-4">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Total Land Area (ha)</h5>


                                            <p class="mb-1"><strong>Ballina Lafaa:</strong> {{ $totallandarea }} heck</p>


                                            <p class="mb-1"><strong>{{ $selectedYear - 1 }}:</strong> {{ number_format($previousTotals['total_land'], 2) }} ha</p>
                                            <p class="mb-0"><strong>{{ $selectedYear }}:</strong> {{ number_format($totals['total_land'], 2) }} ha</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Total Production -->
                                <div class="col-md-4">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Total Production (Qt)</h5>

                                            <p class="mb-1"><strong>{{ $selectedYear - 1 }}:</strong> {{ number_format($previousTotals['total_production'], 2) }} Qt</p>
                                            <p class="mb-0"><strong>{{ $selectedYear }}:</strong> {{ number_format($totals['total_production'], 2) }} Qt</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Productivity -->
                                <div class="col-md-4">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Average Productivity (Qt/ha)</h5>
                                            <p class="mb-1"><strong>{{ $selectedYear - 1 }}:</strong> {{ number_format($previousTotals['avg_productivity'], 2) }} Qt/ha</p>
                                            <p class="mb-0"><strong>{{ $selectedYear }}:</strong> {{ number_format($totals['avg_productivity'], 2) }} Qt/ha</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Report -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-success text-white">
                            <h4 class="mb-2">Detailed Production Data</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mb-0 w-100">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>Kebele</th>
                                        <th>Crop Type</th>
                                        <th>Crop Name</th>
                                        <th>Land (ha)</th>
                                        <th>Productivity (Qt/ha)</th>
                                        <th>Status</th>
                                        <th>Production (Qt)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($reportData as $kebeleId => $cropTypes)
                                        @php
                                            $kebeleRowCount = collect($cropTypes)->flatten(1)->count();
                                            $kebelePrinted = false;
                                        @endphp

                                        @foreach($cropTypes as $cropTypeId => $crops)
                                            @php
                                                $cropTypeRowCount = collect($crops)->count();
                                                $cropTypePrinted = false;
                                            @endphp

                                            @foreach($crops as $cropId => $records)
                                                @php
                                                    $totalLand = $records->sum('land_hectares');
                                                    $totalProduction = $records->sum('production');
                                                    $productivity = $totalLand > 0 ? $totalProduction / $totalLand : 0;

                                                    $barColor = $productivity < 30 ? 'bg-danger' : ($productivity < 50 ? 'bg-warning' : 'bg-success');
                                                    $barWidth = min(100, $productivity);

                                                    $firstRecord = $records->first();
                                                @endphp
                                                <tr>
                                                    @if(!$kebelePrinted && $firstRecord && $firstRecord->kebele)
                                                        <td rowspan="{{ $kebeleRowCount }}">
                                                            {{ $firstRecord->kebele->name }}
                                                        </td>
                                                        @php $kebelePrinted = true; @endphp
                                                    @endif

                                                    @if(!$cropTypePrinted && $firstRecord && $firstRecord->cropType)
                                                        <td rowspan="{{ $cropTypeRowCount }}">
                                                            {{ $firstRecord->cropType->name }}
                                                        </td>
                                                        @php $cropTypePrinted = true; @endphp
                                                    @endif

                                                    <td>
                                                        {{ $firstRecord && $firstRecord->crop ? $firstRecord->crop->name : 'N/A' }}
                                                    </td>
                                                    <td>{{ number_format($totalLand, 2) }}</td>
                                                    <td>{{ number_format($productivity, 2) }}</td>
                                                    <td>
                                                        <div class="progress" style="height: 20px;">
                                                            <div class="progress-bar {{ $barColor }}" role="progressbar"
                                                                 style="width: {{ $barWidth }}%;" aria-valuenow="{{ $productivity }}"
                                                                 aria-valuemin="0" aria-valuemax="100">
                                                                {{ round($productivity) }} Qt/ha
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ number_format($totalProduction, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Year Comparison Table -->
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">Yearly Comparison Table ({{ $selectedYear - 1 }} vs {{ $selectedYear }})</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped w-100">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Metric</th>
                                            <th>{{ $selectedYear - 1 }}</th>
                                            <th>{{ $selectedYear }}</th>
                                            <th>Difference</th>
                                            <th>Change (%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $metrics = [
                                                'Total Land Area (ha)' => ['previous' => $previousTotals['total_land'], 'current' => $totals['total_land']],
                                                'Total Production (Qt)' => ['previous' => $previousTotals['total_production'], 'current' => $totals['total_production']],
                                                'Average Productivity (Qt/ha)' => ['previous' => $previousTotals['avg_productivity'], 'current' => $totals['avg_productivity']]
                                            ];
                                        @endphp

                                        @foreach($metrics as $metric => $values)
                                            @php
                                                $difference = $values['current'] - $values['previous'];
                                                $changePercentage = $values['previous'] != 0
                                                    ? ($difference / $values['previous']) * 100
                                                    : ($values['current'] != 0 ? 100 : 0);

                                                // Determine color based on change
                                                $textClass = $difference > 0 ? 'text-success' : ($difference < 0 ? 'text-danger' : 'text-muted');
                                                $arrowIcon = $difference > 0 ? 'fa-arrow-up' : ($difference < 0 ? 'fa-arrow-down' : 'fa-equals');
                                            @endphp
                                            <tr>
                                                <td><strong>{{ $metric }}</strong></td>
                                                <td>{{ number_format($values['previous'], 2) }}</td>
                                                <td>{{ number_format($values['current'], 2) }}</td>
                                                <td class="{{ $textClass }}">
                                                    {{ number_format($difference, 2) }}
                                                    <i class="fas {{ $arrowIcon }} ms-1"></i>
                                                </td>
                                                <td class="{{ $textClass }}">
                                                    {{ number_format(abs($changePercentage), 2) }}%
                                                    <i class="fas {{ $arrowIcon }} ms-1"></i>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>




    </div>
 <div class="row">
    <!-- Callaguddiftuu Comparison Table - Left Column -->
    <div class="col-md-6">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-warning text-white">
                <h4 class="mb-0">Callaguddiftuu Comparison ({{ $selectedYear - 1 }} vs {{ $selectedYear }})</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped w-100">
                        <thead class="thead-dark">
                            <tr>
                                <th>Type</th>
                                <th>Season</th>
                                <th>{{ $selectedYear - 1 }} Quantity</th>
                                <th>{{ $selectedYear }} Quantity</th>
                                <th>Difference</th>
                                <th>Change (%)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Group data by type and season for both years
                                $previousData = [];
                                foreach($baselineCallguddiftu as $record) {
                                    $key = $record->type . '_' . $record->season;
                                    $previousData[$key] = $record->quantity;
                                }

                                $currentData = [];
                                foreach($callguddiftu as $record) {
                                    $key = $record->type . '_' . $record->season;
                                    $currentData[$key] = $record->quantity;
                                }

                                $allKeys = array_unique(array_merge(array_keys($previousData), array_keys($currentData)));

                                // Collect types for subtotal
                                $typeTotals = [];
                            @endphp

                            @foreach($allKeys as $key)
                                @php
                                    list($type, $season) = explode('_', $key);
                                    $prevQty = $previousData[$key] ?? 0;
                                    $currQty = $currentData[$key] ?? 0;
                                    $difference = $currQty - $prevQty;
                                    $changePercentage = $prevQty != 0 ? ($difference / $prevQty) * 100 : ($currQty != 0 ? 100 : 0);

                                    // Track totals per type
                                    if (!isset($typeTotals[$type])) {
                                        $typeTotals[$type] = ['prev' => 0, 'curr' => 0];
                                    }
                                    $typeTotals[$type]['prev'] += $prevQty;
                                    $typeTotals[$type]['curr'] += $currQty;

                                    // Status display
                                    if ($difference > 0) {
                                        $statusClass = 'text-success';
                                        $statusIcon = 'fa-arrow-up';
                                        $statusText = 'Increased';
                                    } elseif ($difference < 0) {
                                        $statusClass = 'text-danger';
                                        $statusIcon = 'fa-arrow-down';
                                        $statusText = 'Decreased';
                                    } else {
                                        $statusClass = 'text-muted';
                                        $statusIcon = 'fa-equals';
                                        $statusText = 'No Change';
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $type }}</td>
                                    <td>{{ $season }}</td>
                                    <td>{{ number_format($prevQty, 2) }}</td>
                                    <td>{{ number_format($currQty, 2) }}</td>
                                    <td class="{{ $statusClass }}">
                                        {{ number_format($difference, 2) }}
                                    </td>
                                    <td class="{{ $statusClass }}">
                                        {{ number_format(abs($changePercentage), 2) }}%
                                    </td>
                                    <td class="{{ $statusClass }}">
                                        <i class="fas {{ $statusIcon }} me-1"></i> {{ $statusText }}
                                    </td>
                                </tr>
                            @endforeach

                            {{-- Totals per type --}}
                            @foreach($typeTotals as $type => $totals)
                                @php
                                    $prevTotal = $totals['prev'];
                                    $currTotal = $totals['curr'];
                                    $totalDiff = $currTotal - $prevTotal;
                                    $totalChangePercentage = $prevTotal != 0 ? ($totalDiff / $prevTotal) * 100 : ($currTotal != 0 ? 100 : 0);

                                    if ($totalDiff > 0) {
                                        $totalStatusClass = 'text-success';
                                        $totalStatusIcon = 'fa-arrow-up';
                                        $totalStatusText = 'Increased';
                                    } elseif ($totalDiff < 0) {
                                        $totalStatusClass = 'text-danger';
                                        $totalStatusIcon = 'fa-arrow-down';
                                        $totalStatusText = 'Decreased';
                                    } else {
                                        $totalStatusClass = 'text-muted';
                                        $totalStatusIcon = 'fa-equals';
                                        $totalStatusText = 'No Change';
                                    }
                                @endphp

                                <tr class="fw-bold bg-light">
                                    <td colspan="2">Total for {{ $type }}</td>
                                    <td>{{ number_format($prevTotal, 2) }}</td>
                                    <td>{{ number_format($currTotal, 2) }}</td>
                                    <td class="{{ $totalStatusClass }}">
                                        {{ number_format($totalDiff, 2) }}
                                    </td>
                                    <td class="{{ $totalStatusClass }}">
                                        {{ number_format(abs($totalChangePercentage), 2) }}%
                                    </td>
                                    <td class="{{ $totalStatusClass }}">
                                        <i class="fas {{ $totalStatusIcon }} me-1"></i> {{ $totalStatusText }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Gabbina Biyyoo Comparison Table - Right Column -->
    <div class="col-md-6">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-warning text-white">
                <h4 class="mb-0">Gabbina Biyyoo Comparison ({{ $selectedYear - 1 }} vs {{ $selectedYear }})</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped w-100">
                        <thead class="thead-dark">
                            <tr>
                                <th rowspan="2">Compost Type</th>
                                <th colspan="3" class="text-center">{{ $selectedYear - 1 }}</th>
                                <th colspan="3" class="text-center">{{ $selectedYear }}</th>
                                <th rowspan="2">Difference</th>
                                <th rowspan="2">Change (%)</th>
                            </tr>
                            <tr>
                                <!-- Previous Year Columns -->
                                <th>Common</th>
                                <th>Individual</th>
                                <th>Total</th>
                                <!-- Current Year Columns -->
                                <th>Common</th>
                                <th>Individual</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Group baseline data by compost_type
                                $baselineData = [];
                                foreach($gabinabiyoBaseline as $record) {
                                    $baselineData[$record->compost_type] = [
                                        'common' => $record->common,
                                        'individual' => $record->individual,
                                        'total' => $record->total,
                                        'land_area' => $record->land_area
                                    ];
                                }

                                // Group current data by compost_type
                                $currentData = [];
                                foreach($gabinabiyoCurrent as $record) {
                                    $currentData[$record->compost_type] = [
                                        'common' => $record->common,
                                        'individual' => $record->individual,
                                        'total' => $record->total,
                                        'land_area' => $record->land_area
                                    ];
                                }

                                // Get all unique compost types
                                $allCompostTypes = array_unique(array_merge(
                                    array_keys($baselineData),
                                    array_keys($currentData)
                                ));

                                // Calculate totals
                                $baselineTotals = [
                                    'common' => 0,
                                    'individual' => 0,
                                    'total' => 0,
                                    'land_area' => 0
                                ];

                                $currentTotals = [
                                    'common' => 0,
                                    'individual' => 0,
                                    'total' => 0,
                                    'land_area' => 0
                                ];
                            @endphp

                            @foreach($allCompostTypes as $compostType)
                                @php
                                    $baseline = $baselineData[$compostType] ?? [
                                        'common' => 0,
                                        'individual' => 0,
                                        'total' => 0,
                                        'land_area' => 0
                                    ];

                                    $current = $currentData[$compostType] ?? [
                                        'common' => 0,
                                        'individual' => 0,
                                        'total' => 0,
                                        'land_area' => 0
                                    ];

                                    $totalDiff = $current['total'] - $baseline['total'];
                                    $changePercentage = $baseline['total'] != 0
                                        ? ($totalDiff / $baseline['total']) * 100
                                        : ($current['total'] != 0 ? 100 : 0);

                                    // Status styling
                                    if ($totalDiff > 0) {
                                        $statusClass = 'text-success';
                                        $statusIcon = 'fa-arrow-up';
                                        $statusText = 'Increased';
                                    } elseif ($totalDiff < 0) {
                                        $statusClass = 'text-danger';
                                        $statusIcon = 'fa-arrow-down';
                                        $statusText = 'Decreased';
                                    } else {
                                        $statusClass = 'text-muted';
                                        $statusIcon = 'fa-equals';
                                        $statusText = 'No Change';
                                    }

                                    // Add to totals
                                    $baselineTotals['common'] += $baseline['common'];
                                    $baselineTotals['individual'] += $baseline['individual'];
                                    $baselineTotals['total'] += $baseline['total'];
                                    $baselineTotals['land_area'] += $baseline['land_area'];

                                    $currentTotals['common'] += $current['common'];
                                    $currentTotals['individual'] += $current['individual'];
                                    $currentTotals['total'] += $current['total'];
                                    $currentTotals['land_area'] += $current['land_area'];
                                @endphp
                                <tr>
                                    <td>
                                        {{ $compostType }}
                                        @if($compostType == 'Nooraa Hojiirra oole')
                                            <div class="text-muted small mt-1">
                                                Land Area: {{ number_format($baseline['land_area'], 2) }} ha ({{ $selectedYear - 1 }}) →
                                                {{ number_format($current['land_area'], 2) }} ha ({{ $selectedYear }})
                                            </div>
                                        @endif
                                    </td>
                                    <!-- Previous Year Data -->
                                    <td>{{ number_format($baseline['common'], 2) }}</td>
                                    <td>{{ number_format($baseline['individual'], 2) }}</td>
                                    <td>{{ number_format($baseline['total'], 2) }}</td>
                                    <!-- Current Year Data -->
                                    <td>{{ number_format($current['common'], 2) }}</td>
                                    <td>{{ number_format($current['individual'], 2) }}</td>
                                    <td>{{ number_format($current['total'], 2) }}</td>
                                    <!-- Difference -->
                                    <td class="{{ $statusClass }}">
                                        {{ number_format($totalDiff, 2) }}
                                        <i class="fas {{ $statusIcon }}"></i>
                                    </td>
                                    <td class="{{ $statusClass }}">
                                        {{ number_format(abs($changePercentage), 2) }}%
                                    </td>
                                </tr>
                            @endforeach

                            @php
                                // Calculate grand totals difference
                                $grandTotalDiff = $currentTotals['total'] - $baselineTotals['total'];
                                $grandChangePercentage = $baselineTotals['total'] != 0
                                    ? ($grandTotalDiff / $baselineTotals['total']) * 100
                                    : ($currentTotals['total'] != 0 ? 100 : 0);

                                if ($grandTotalDiff > 0) {
                                    $grandStatusClass = 'text-success';
                                    $grandStatusIcon = 'fa-arrow-up';
                                } elseif ($grandTotalDiff < 0) {
                                    $grandStatusClass = 'text-danger';
                                    $grandStatusIcon = 'fa-arrow-down';
                                } else {
                                    $grandStatusClass = 'text-muted';
                                    $grandStatusIcon = 'fa-equals';
                                }
                            @endphp

                            <tr class="fw-bold bg-light">
                                <td>Grand Total</td>
                                <!-- Previous Year Totals -->
                                <td>{{ number_format($baselineTotals['common'], 2) }}</td>
                                <td>{{ number_format($baselineTotals['individual'], 2) }}</td>
                                <td>{{ number_format($baselineTotals['total'], 2) }}</td>
                                <!-- Current Year Totals -->
                                <td>{{ number_format($currentTotals['common'], 2) }}</td>
                                <td>{{ number_format($currentTotals['individual'], 2) }}</td>
                                <td>{{ number_format($currentTotals['total'], 2) }}</td>
                                <!-- Difference -->
                                <td class="{{ $grandStatusClass }}">
                                    {{ number_format($grandTotalDiff, 2) }}
                                    <i class="fas {{ $grandStatusIcon }}"></i>
                                </td>
                                <td class="{{ $grandStatusClass }}">
                                    {{ number_format(abs($grandChangePercentage), 2) }}%
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Comparison Chart (Bar)
        const ctx = document.getElementById('comparisonChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Land (ha)', 'Total Production (Qt)', 'Avg Productivity (Qt/ha)'],
                datasets: [
                    {
                        label: '{{ $selectedYear - 1 }}',
                        data: [
                            {{ $previousTotals['total_land'] ?? 0 }},
                            {{ $previousTotals['total_production'] ?? 0 }},
                            {{ $previousTotals['avg_productivity'] ?? 0 }}
                        ],
                        backgroundColor: 'rgba(243, 156, 18, 0.8)',
                        borderColor: 'rgba(243, 156, 18, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '{{ $selectedYear }}',
                        data: [
                            {{ $totals['total_land'] ?? 0 }},
                            {{ $totals['total_production'] ?? 0 }},
                            {{ $totals['avg_productivity'] ?? 0 }}
                        ],
                        backgroundColor: 'rgba(52, 152, 219, 0.8)',
                        borderColor: 'rgba(52, 152, 219, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Comparison Between {{ $selectedYear - 1 }} and {{ $selectedYear }}'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Value'
                        }
                    }
                }
            }
        });

        // Line Chart
        const ctxLine = document.getElementById('lineComparisonChart').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: ['Total Land (ha)', 'Total Production (Qt)', 'Avg Productivity (Qt/ha)'],
                datasets: [
                    {
                        label: '{{ $selectedYear - 1 }}',
                        data: [
                            {{ $previousTotals['total_land'] ?? 0 }},
                            {{ $previousTotals['total_production'] ?? 0 }},
                            {{ $previousTotals['avg_productivity'] ?? 0 }}
                        ],
                        borderColor: 'rgba(243, 156, 18, 1)',
                        backgroundColor: 'rgba(243, 156, 18, 0.2)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: '{{ $selectedYear }}',
                        data: [
                            {{ $totals['total_land'] ?? 0 }},
                            {{ $totals['total_production'] ?? 0 }},
                            {{ $totals['avg_productivity'] ?? 0 }}
                        ],
                        borderColor: 'rgba(52, 152, 219, 1)',
                        backgroundColor: 'rgba(52, 152, 219, 0.2)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Line Chart Comparison: {{ $selectedYear - 1 }} vs {{ $selectedYear }}'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Value'
                        }
                    }
                }
            }
        });

        // Pie Chart
        const pieChartCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieChartCtx, {
            type: 'pie',
            data: {
                labels: @json($pieChartLabels),
                datasets: [{
                    label: 'Crop Production',
                    data: @json($pieChartData),
                    backgroundColor: ['#887339', '#33FF57', '#3357FF', '#FF33A8', '#A833FF'],
                    hoverBackgroundColor: ['#FF6F61', '#6FFF61', '#6179FF', '#FF61B3', '#9A61FF']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2) + ' Qt';
                            }
                        }
                    }
                }
            }
        });

        // Download chart as image
        function downloadChartAsImage(canvasId, filename) {
            const canvas = document.getElementById(canvasId);
            const url = canvas.toDataURL('image/png');
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            a.click();
        }

        // Download chart as PDF
        async function downloadChartAsPDF(canvasId, filename) {
            const {jsPDF} = window.jspdf;
            const canvas = document.getElementById(canvasId);
            const imgData = canvas.toDataURL('image/png');

            const pdf = new jsPDF();
            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();

            const imgProps = pdf.getImageProperties(imgData);
            const pdfWidth = pageWidth * 0.9;
            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

            const x = (pageWidth - pdfWidth) / 2;
            const y = (pageHeight - pdfHeight) / 2;

            pdf.addImage(imgData, 'PNG', x, y, pdfWidth, pdfHeight);
            pdf.save(filename);
        }

        // Download full report as PDF
        async function downloadFullReportAsPDF() {
            const report = document.getElementById('reportContent');
            const { jsPDF } = window.jspdf;

            const canvas = await html2canvas(report, {
                scale: 2,
                useCORS: true
            });

            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF('p', 'pt', 'a4');

            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();

            const imgProps = pdf.getImageProperties(imgData);
            const pdfWidth = pageWidth;
            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

            let position = 0;

            if (pdfHeight < pageHeight) {
                pdf.addImage(imgData, 'PNG', 0, position, pdfWidth, pdfHeight);
            } else {
                // For multiple pages
                let remainingHeight = pdfHeight;
                let imgY = 0;

                while (remainingHeight > 0) {
                    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight, '', 'FAST', 0, imgY);
                    remainingHeight -= pageHeight;
                    imgY += pageHeight * (imgProps.height / pdfHeight);

                    if (remainingHeight > 0) pdf.addPage();
                }
            }

            pdf.save("kebele_crop_report.pdf");
        }
    </script>
</x-layout>
