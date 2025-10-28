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
                        <form method="GET" action="{{ route('reports.kebele-crop-analysis') }}">
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
                        <i class="fas fa-file-pdf"></i> Download Full Report as PDF
                    </button>
                    <button onclick="printReport()" class="btn btn-outline-primary ms-2">
                        <i class="fas fa-print"></i> Print Report
                    </button>
                </div>

                <!-- Report Content -->
                <div id="reportContent">
                    <!-- Report Summary -->
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h4 class="mb-0">Report Summary ({{ $selectedYear - 1 }} vs {{ $selectedYear }})</h4>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <!-- Land Area -->
                                <div class="col-md-4">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Total Land Area (ha)</h5>
                                            <p class="mb-1"><strong>Ballina Lafaa:</strong> {{$totallandarea->total_area}} heck</p>
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
                                        @php $kebeleRowCount = collect($cropTypes)->flatten(1)->count(); @endphp
                                        @php $kebelePrinted = false; @endphp

                                        @foreach($cropTypes as $cropTypeId => $crops)
                                        @php $cropTypeRowCount = count($crops); @endphp
                                        @php $cropTypePrinted = false; @endphp

                                        @foreach($crops as $cropId => $records)
                                        @php
                                        $totalLand = $records->sum('land_hectares');
                                        $totalProduction = $records->sum('production');
                                        $productivity = $totalLand > 0 ? $totalProduction / $totalLand : 0;

                                        // Determine bar color
                                        if ($productivity < 30) {
                                            $barColor='bg-danger' ;
                                            } elseif ($productivity < 50) {
                                            $barColor='bg-warning' ;
                                            } else {
                                            $barColor='bg-success' ;
                                            }

                                            // Limit width to 100%
                                            $barWidth=min(100, $productivity);
                                            @endphp
                                            <tr>
                                            @if(!$kebelePrinted)
                                            <td rowspan="{{ $kebeleRowCount }}">
                                                {{ $kebeles->find($kebeleId)->name }}
                                            </td>
                                            @php $kebelePrinted = true; @endphp
                                            @endif

                                            @if(!$cropTypePrinted)
                                            <td rowspan="{{ $cropTypeRowCount }}">
                                                {{ $records->first()->cropType?->name }}
                                            </td>
                                            @php $cropTypePrinted = true; @endphp
                                            @endif

                                            <td>{{ $records->first()->crop?->name }}</td>
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
                                        $textClass = $difference > 0 ? 'text-success' : ($difference < 0 ? 'text-danger' : 'text-muted' );
                                            $arrowIcon=$difference> 0 ? 'fa-arrow-up' : ($difference < 0 ? 'fa-arrow-down' : 'fa-equals' );
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
                                                    $statusClass='text-danger' ;
                                                    $statusIcon='fa-arrow-down' ;
                                                    $statusText='Decreased' ;
                                                    } else {
                                                    $statusClass='text-muted' ;
                                                    $statusIcon='fa-equals' ;
                                                    $statusText='No Change' ;
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
                                                        $totalStatusClass='text-danger' ;
                                                        $totalStatusIcon='fa-arrow-down' ;
                                                        $totalStatusText='Decreased' ;
                                                        } else {
                                                        $totalStatusClass='text-muted' ;
                                                        $totalStatusIcon='fa-equals' ;
                                                        $totalStatusText='No Change' ;
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
                                                    $statusClass='text-danger' ;
                                                    $statusIcon='fa-arrow-down' ;
                                                    $statusText='Decreased' ;
                                                    } else {
                                                    $statusClass='text-muted' ;
                                                    $statusIcon='fa-equals' ;
                                                    $statusText='No Change' ;
                                                    }

                                                    // Add to totals
                                                    $baselineTotals['common'] +=$baseline['common'];
                                                    $baselineTotals['individual'] +=$baseline['individual'];
                                                    $baselineTotals['total'] +=$baseline['total'];
                                                    $baselineTotals['land_area'] +=$baseline['land_area'];

                                                    $currentTotals['common'] +=$current['common'];
                                                    $currentTotals['individual'] +=$current['individual'];
                                                    $currentTotals['total'] +=$current['total'];
                                                    $currentTotals['land_area'] +=$current['land_area'];
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
                                                        $grandStatusClass='text-danger' ;
                                                        $grandStatusIcon='fa-arrow-down' ;
                                                        } else {
                                                        $grandStatusClass='text-muted' ;
                                                        $grandStatusIcon='fa-equals' ;
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
                </div>
            </div>
        </div>
    </div>

    <!-- Load libraries with proper error handling -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        // Improved PDF download function with better error handling
        async function downloadFullReportAsPDF() {
            try {
                // Show loading indicator
                const btn = event.target;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating PDF...';
                btn.disabled = true;

                // Check if libraries are loaded
                if (typeof html2canvas === 'undefined') {
                    throw new Error('HTML2Canvas library failed to load. Please check your internet connection.');
                }

                if (typeof window.jspdf === 'undefined') {
                    throw new Error('jsPDF library failed to load. Please check your internet connection.');
                }

                const {
                    jsPDF
                } = window.jspdf;

                // Get the report content
                const element = document.getElementById('reportContent');
                if (!element) {
                    throw new Error('Report content not found');
                }
                // Create a temporary container for better PDF rendering
                const tempContainer = document.createElement('div');
                tempContainer.style.position = 'fixed';
                tempContainer.style.left = '-9999px';
                tempContainer.style.top = '0';
                tempContainer.style.width = '800px';
                tempContainer.style.backgroundColor = 'white';
                tempContainer.style.padding = '20px';
                tempContainer.innerHTML = element.innerHTML;
                document.body.appendChild(tempContainer);
                // Add print styles for better PDF appearance
                const style = document.createElement('style');
                style.innerHTML = `
                    body { 
                        font-family: Arial, sans-serif; 
                        font-size: 12px; 
                        line-height: 1.3;
                    }
                    .card { 
                        margin-bottom: 15px; 
                        border: 1px solid #ddd;
                        page-break-inside: avoid;
                    }
                    .card-header { 
                        padding: 8px 12px; 
                        font-weight: bold;
                    }
                    .table { 
                        font-size: 10px; 
                    }
                    .table th, .table td { 
                        padding: 4px 6px; 
                    }
                    .progress-bar { 
                        font-size: 9px; 
                        font-weight: bold;
                    }
                    h4 { font-size: 14px; }
                    h2 { font-size: 16px; }
                `;
                tempContainer.appendChild(style);

                // Use html2canvas to capture the content
                const canvas = await html2canvas(tempContainer, {
                    scale: 2,
                    useCORS: true,
                    logging: true,
                    width: tempContainer.scrollWidth,
                    height: tempContainer.scrollHeight,
                    scrollX: 0,
                    scrollY: 0,
                    windowWidth: tempContainer.scrollWidth,
                    windowHeight: tempContainer.scrollHeight
                });

                // Clean up temporary container
                document.body.removeChild(tempContainer);

                const imgData = canvas.toDataURL('image/png');
                const pdf = new jsPDF('p', 'mm', 'a4');

                const pageWidth = pdf.internal.pageSize.getWidth();
                const pageHeight = pdf.internal.pageSize.getHeight();

                const margin = 10;
                const imgWidth = pageWidth - (2 * margin);
                const imgHeight = (canvas.height * imgWidth) / canvas.width;

                let heightLeft = imgHeight;
                let position = margin;

                // Add first page
                pdf.addImage(imgData, 'PNG', margin, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;

                // Add additional pages if needed
                while (heightLeft > 0) {
                    position = heightLeft - imgHeight + margin;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', margin, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }

                // Save the PDF with descriptive filename
                const fileName = `Kebele_Crop_Report_{{ $selectedYear }}_{{ $selectedSeason ?? 'AllSeasons' }}_${new Date().toISOString().split('T')[0]}.pdf`;
                pdf.save(fileName);

            } catch (error) {
                console.error('PDF Generation Error:', error);
                alert('Error generating PDF: ' + error.message + '\n\nPlease try the Print Report function instead.');
            } finally {
                // Restore button state
                const btn = event.target;
                btn.innerHTML = '<i class="fas fa-file-pdf"></i> Download Full Report as PDF';
                btn.disabled = false;
            }
        }

        // Reliable print function as fallback
        function printReport() {
            const originalContent = document.getElementById('reportContent').innerHTML;

            // Create a print-friendly version
            const printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Kebele Crop Production Analysis Report</title>
                    <meta charset="utf-8">
                    <style>
                        body { 
                            font-family: Arial, sans-serif; 
                            margin: 15px; 
                            line-height: 1.4;
                            font-size: 12px;
                            color: #000;
                        }
                        .card { 
                            border: 1px solid #ccc; 
                            margin: 15px 0; 
                            padding: 15px; 
                            border-radius: 5px;
                            page-break-inside: avoid;
                        }
                        .card-header { 
                            background-color: #f8f9fa !important; 
                            color: #000 !important; 
                            padding: 10px; 
                            border-radius: 3px; 
                            margin: -15px -15px 15px -15px; 
                            border-bottom: 1px solid #ccc;
                        }
                        table { 
                            width: 100%; 
                            border-collapse: collapse; 
                            margin: 10px 0;
                            page-break-inside: avoid;
                            font-size: 11px;
                        }
                        th, td { 
                            border: 1px solid #ddd; 
                            padding: 6px; 
                            text-align: left; 
                        }
                        th { 
                            background-color: #f8f9fa; 
                            font-weight: bold;
                        }
                        .text-center { text-align: center; }
                        .bg-light { background-color: #f8f9fa !important; }
                        .progress { 
                            height: 20px; 
                            background-color: #e9ecef; 
                            border-radius: 4px; 
                            overflow: hidden; 
                        }
                        .progress-bar { 
                            height: 100%; 
                            line-height: 20px; 
                            color: black; 
                            text-align: center;
                            font-size: 10px;
                            font-weight: bold;
                        }
                        .bg-success { background-color: #d4edda !important; color: #000 !important; }
                        .bg-warning { background-color: #fff3cd !important; color: #000 !important; }
                        .bg-danger { background-color: #f8d7da !important; color: #000 !important; }
                        .text-success { color: #155724 !important; }
                        .text-danger { color: #721c24 !important; }
                        .text-muted { color: #6c757d !important; }
                        h1, h2, h3, h4 { 
                            color: #000; 
                            page-break-after: avoid;
                        }
                        @media print {
                            body { margin: 0.5cm; }
                            .card { break-inside: avoid; margin-bottom: 20px; }
                            table { break-inside: avoid; }
                            h1, h2, h3, h4 { break-after: avoid; }
                            .no-print { display: none !important; }
                        }
                        .report-header {
                            text-align: center;
                            border-bottom: 2px solid #333;
                            padding-bottom: 10px;
                            margin-bottom: 20px;
                        }
                        .btn { display: none; }
                    </style>
                </head>
                <body>
                    <div class="report-header">
                        <h1>Kebele Crop Production Analysis Report</h1>
                        <p><strong>Generated on:</strong> ${new Date().toLocaleDateString()}</p>
                        <p>
                            <strong>Year:</strong> {{ $selectedYear ?? 'All' }}, 
                            <strong>Season:</strong> {{ $selectedSeason ?? 'All' }}, 
                            <strong>Crop:</strong> {{ $selectedCrop ? ($availableCrops->find($selectedCrop)->name ?? 'All') : 'All' }}
                        </p>
                    </div>
                    ${originalContent}
                    <div class="no-print" style="margin-top: 20px; text-align: center;">
                        <p><em>Report generated from Kebele Crop Analysis System</em></p>
                    </div>
                </body>
                </html>
            `;

            try {
                const printWindow = window.open('', '_blank', 'width=800,height=600');
                if (!printWindow) {
                    throw new Error('Popup window blocked. Please allow popups for this site.');
                }

                printWindow.document.write(printContent);
                printWindow.document.close();

                // Wait for content to load before printing
                printWindow.onload = function() {
                    setTimeout(() => {
                        printWindow.focus();
                        printWindow.print();
                    }, 1000);
                };

            } catch (error) {
                console.error('Print Error:', error);
                alert('Error opening print window: ' + error.message + '\n\nPlease use Ctrl+P to print this page.');
            }
        }

        // Simple alternative PDF function for smaller reports
        function downloadSimplePDF() {
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
            btn.disabled = true;

            html2canvas(document.getElementById('reportContent'), {
                scale: 2,
                useCORS: true,
                scrollY: -window.scrollY
            }).then(canvas => {
                const {
                    jsPDF
                } = window.jspdf;
                const pdf = new jsPDF('p', 'mm', 'a4');

                const imgData = canvas.toDataURL('image/png');
                const imgWidth = 190;
                const pageHeight = 270;
                const imgHeight = (canvas.height * imgWidth) / canvas.width;
                let heightLeft = imgHeight;
                let position = 10;

                pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;

                while (heightLeft > 0) {
                    position = heightLeft - imgHeight + 10;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }

                pdf.save('kebele_crop_report.pdf');
            }).catch(error => {
                console.error('Simple PDF Error:', error);
                alert('Error generating simple PDF. Please try the main download button.');
            }).finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }
    </script>
</x-layout>