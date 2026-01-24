<x-layout>
<div class="content-header">

    {{-- Header --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <div class="bg-white border rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:60px;height:60px;">
                        <i class="fas fa-seedling fa-lg text-success"></i>
                    </div>
                    <div>
                        <h1 class="m-0 text-dark">{{ __('Kebele Level Compost Report') }}</h1>
                        <small class="text-muted">{{ __('Compost distribution by type') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Content --}}
    <section class="content">
        <div class="container-fluid">
            
            @php
                // Calculate summary data for charts and cards
                $totalCompostTypes = 0;
                $currentTotal = 0;
                $prevTotal = 0;
                $compostTypes = [];
                $currentCompostData = [];
                $previousCompostData = [];
                $commonCurrentTotal = 0;
                $individualCurrentTotal = 0;
                $commonPrevTotal = 0;
                $individualPrevTotal = 0;
                
                foreach($kebelesData as $kebeleId => $rows) {
                    foreach($rows as $row) {
                        if(!in_array($row->compost_type, $compostTypes)) {
                            $compostTypes[] = $row->compost_type;
                        }
                        
                        $prevRow = $previousYearData[$kebeleId][$row->compost_type] ?? null;
                        $commonPrev = $prevRow->common ?? 0;
                        $individualPrev = $prevRow->individual ?? 0;
                        
                        $currentTotal += $row->common + $row->individual;
                        $prevTotal += $commonPrev + $individualPrev;
                        $commonCurrentTotal += $row->common;
                        $individualCurrentTotal += $row->individual;
                        $commonPrevTotal += $commonPrev;
                        $individualPrevTotal += $individualPrev;
                    }
                    $totalCompostTypes = count($compostTypes);
                }
                
                // Prepare data for compost type distribution chart
                $compostTypeData = [];
                foreach($compostTypes as $type) {
                    $typeTotalCurrent = 0;
                    $typeTotalPrev = 0;
                    foreach($kebelesData as $kebeleId => $rows) {
                        foreach($rows as $row) {
                            if($row->compost_type === $type) {
                                $typeTotalCurrent += $row->common + $row->individual;
                                $prevRow = $previousYearData[$kebeleId][$type] ?? null;
                                $typeTotalPrev += ($prevRow->common ?? 0) + ($prevRow->individual ?? 0);
                            }
                        }
                    }
                    $compostTypeData[$type] = [
                        'current' => $typeTotalCurrent,
                        'previous' => $typeTotalPrev
                    ];
                }
                
                // Prepare data for distribution type chart
                $distributionData = [
                    'common_current' => $commonCurrentTotal,
                    'individual_current' => $individualCurrentTotal,
                    'common_previous' => $commonPrevTotal,
                    'individual_previous' => $individualPrevTotal
                ];
            @endphp

            {{-- Filter --}}
            <div class="card card-outline card-success mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-filter mr-1"></i>
                        {{ __('Filter Options') }}
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('kebele-compost-report.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label>{{ __('Year') }}</label>
                                <select name="year" class="form-control form-control-sm">
                                    @foreach($years as $y)
                                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="text-right mt-2">
                            <button class="btn btn-success btn-sm"><i class="fas fa-filter"></i> {{ __('Apply') }}</button>
                            <a href="{{ route('kebele-compost-report.index') }}" class="btn btn-light btn-sm border">
                                <i class="fas fa-redo"></i> {{ __('Reset') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalCompostTypes }}</h3>
                            <p>{{ __('Compost Types') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-seedling"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($currentTotal, 1) }}</h3>
                            <p>{{ __('Current Year Total') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ number_format($prevTotal, 1) }}</h3>
                            <p>{{ __('Previous Year Total') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-history"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            @php
                                $changePercentage = $prevTotal > 0 ? (($currentTotal - $prevTotal) / $prevTotal) * 100 : 0;
                            @endphp
                            <h3>{{ number_format($changePercentage, 1) }}%</h3>
                            <p>{{ __('Year-over-Year Change') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-percentage"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts Section --}}
            <div class="row mb-4">
                <!-- Compost by Type Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Compost Distribution by Type') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="compostTypeChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Distribution Type Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                {{ __('Distribution by Category') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="distributionChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Year Comparison Chart -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-1"></i>
                                {{ __('Year-over-Year Comparison by Type') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="comparisonChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-table mr-1"></i>
                        {{ __('Detailed Compost Report') }}
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered table-hover text-nowrap">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('Compost Type') }}</th>
                                <th colspan="2" class="text-center">{{ __('Current') }} ({{ $selectedYear }})</th>
                                <th colspan="2" class="text-center">{{ __('Previous') }} ({{ $prevYear }})</th>
                                <th>{{ __('Change %') }}</th>
                                <th>{{ __('Land Area (ha)') }}</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>{{ __('Common') }}</th>
                                <th>{{ __('Individual') }}</th>
                                <th>{{ __('Common') }}</th>
                                <th>{{ __('Individual') }}</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                                $currentTotal = 0;
                                $prevTotal = 0;
                            @endphp
                            @foreach($kebelesData as $kebeleId => $rows)
                                @foreach($rows as $row)
                                    @php
                                        $prevRow = $previousYearData[$kebeleId][$row->compost_type] ?? null;
                                        $commonPrev = $prevRow->common ?? 0;
                                        $individualPrev = $prevRow->individual ?? 0;
                                        $change = ($commonPrev + $individualPrev) > 0
                                            ? (($row->common + $row->individual - ($commonPrev + $individualPrev)) / ($commonPrev + $individualPrev)) * 100
                                            : 0;
                                        $currentTotal += $row->common + $row->individual;
                                        $prevTotal += $commonPrev + $individualPrev;
                                    @endphp
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $row->compost_type }}</td>
                                        <td class="text-center">{{ number_format($row->common,2) }}</td>
                                        <td class="text-center">{{ number_format($row->individual,2) }}</td>
                                        <td class="text-center">{{ number_format($commonPrev,2) }}</td>
                                        <td class="text-center">{{ number_format($individualPrev,2) }}</td>
                                        <td class="text-center {{ $change >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($change,1) }}%
                                        </td>
                                        <td class="text-center">{{ number_format($row->land_area ?? 0,2) }}</td>
                                    </tr>
                                @endforeach
                            @endforeach

                            {{-- Grand Total --}}
                            <tr class="bg-success text-white font-weight-bold">
                                <td colspan="2" class="text-center">{{ __('TOTAL') }}</td>
                                <td class="text-center">{{ number_format($currentTotal,2) }}</td>
                                <td>-</td>
                                <td class="text-center">{{ number_format($prevTotal,2) }}</td>
                                <td>-</td>
                                <td class="text-center {{ ($currentTotal - $prevTotal) >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $prevTotal>0 ? number_format((($currentTotal-$prevTotal)/$prevTotal)*100,1) : 0 }}%
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</div>

<!-- Add Chart.js -->
<script src="{{ asset('vendor/adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Prepare data from PHP variables
    const compostTypes = @json($compostTypes);
    const compostTypeData = @json($compostTypeData);
    const distributionData = @json($distributionData);
    const selectedYear = @json($selectedYear);
    const prevYear = @json($prevYear);

    // Extract data for compost type chart
    const typeLabels = compostTypes;
    const currentTypeData = typeLabels.map(type => compostTypeData[type]?.current || 0);
    const previousTypeData = typeLabels.map(type => compostTypeData[type]?.previous || 0);

    // Extract data for distribution chart
    const distributionLabels = ['Common', 'Individual'];
    const currentDistributionData = [distributionData.common_current, distributionData.individual_current];
    const previousDistributionData = [distributionData.common_previous, distributionData.individual_previous];

    // Color palettes
    const compostColors = [
        'rgba(40, 167, 69, 0.7)',   // Green
        'rgba(23, 162, 184, 0.7)',  // Teal
        'rgba(108, 117, 125, 0.7)', // Gray
        'rgba(255, 193, 7, 0.7)',   // Yellow
        'rgba(220, 53, 69, 0.7)',   // Red
        'rgba(111, 66, 193, 0.7)',  // Purple
    ];

    const distributionColors = [
        'rgba(40, 167, 69, 0.7)',   // Green for Common
        'rgba(23, 162, 184, 0.7)',  // Teal for Individual
    ];

    // Chart 1: Compost Distribution by Type
    if (typeLabels.length > 0) {
        const compostTypeCtx = document.getElementById('compostTypeChart').getContext('2d');
        new Chart(compostTypeCtx, {
            type: 'bar',
            data: {
                labels: typeLabels,
                datasets: [{
                    label: `{{ __('Current Year') }} (${selectedYear})`,
                    data: currentTypeData,
                    backgroundColor: compostColors.slice(0, typeLabels.length),
                    borderColor: compostColors.slice(0, typeLabels.length).map(color => color.replace('0.7', '1')),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: '{{ __('Amount') }}'
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: '{{ __('Compost Type') }}'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    // Chart 2: Distribution by Category (Pie Chart)
    if (currentDistributionData.some(value => value > 0)) {
        const distributionCtx = document.getElementById('distributionChart').getContext('2d');
        new Chart(distributionCtx, {
            type: 'pie',
            data: {
                labels: distributionLabels,
                datasets: [{
                    data: currentDistributionData,
                    backgroundColor: distributionColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return label + ': ' + value.toLocaleString() + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // Chart 3: Year-over-Year Comparison
    if (typeLabels.length > 0) {
        const comparisonCtx = document.getElementById('comparisonChart').getContext('2d');
        new Chart(comparisonCtx, {
            type: 'bar',
            data: {
                labels: typeLabels,
                datasets: [
                    {
                        label: `{{ __('Current Year') }} (${selectedYear})`,
                        data: currentTypeData,
                        backgroundColor: 'rgba(40, 167, 69, 0.7)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1
                    },
                    {
                        label: `{{ __('Previous Year') }} (${prevYear})`,
                        data: previousTypeData,
                        backgroundColor: 'rgba(23, 162, 184, 0.7)',
                        borderColor: 'rgba(23, 162, 184, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: '{{ __('Amount') }}'
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: '{{ __('Compost Type') }}'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>

<style>
    .small-box {
        border-radius: 10px;
        position: relative;
        display: block;
        margin-bottom: 20px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        color: white !important;
    }
    .small-box > .inner {
        padding: 10px;
    }
    .small-box > .small-box-footer {
        position: relative;
        text-align: center;
        padding: 3px 0;
        color: #fff;
        color: rgba(255,255,255,0.8);
        display: block;
        z-index: 10;
        background: rgba(0,0,0,0.1);
        text-decoration: none;
    }
    .small-box > .small-box-footer:hover {
        color: #fff;
        background: rgba(0,0,0,0.15);
    }
    .small-box h3 {
        font-size: 38px;
        font-weight: bold;
        margin: 0 0 10px 0;
        white-space: nowrap;
        padding: 0;
        color: white;
    }
    .small-box p {
        font-size: 15px;
        color: rgba(255,255,255,0.9);
    }
    .small-box .icon {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 0;
        font-size: 70px;
        color: rgba(255,255,255,0.15);
    }
    .small-box:hover .icon {
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    .bg-info { background-color: #17a2b8 !important; }
    .bg-success { background-color: #28a745 !important; }
    .bg-warning { background-color: #ffc107 !important; }
    .bg-danger { background-color: #dc3545 !important; }
</style>

</x-layout>