<x-layout>
<div class="content-header">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <div class="bg-white border rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:60px;height:60px;">
                        <i class="fas fa-building fa-lg text-info"></i>
                    </div>
                    <div>
                        <h1 class="m-0 text-dark">{{ __('Kebele Level Building Institution Report') }}</h1>
                        <small class="text-dark">{{ __('Building construction and improvement analysis') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @php
                // Prepare building data for charts
                $newBuildingKeys = array_keys($newBuildingData['current'] ?? []);
                $newBuildingCounts = [];
                $newBuildingCosts = [];
                
                foreach ($newBuildingKeys as $key) {
                    $newBuildingCounts[] = $newBuildingData['current'][$key]['count'] ?? 0;
                    $newBuildingCosts[] = $newBuildingData['current'][$key]['cost'] ?? 0;
                }
                
                // Improvement data
                $improvementKeys = array_keys($improvementData['current'] ?? []);
                $improvementCounts = [];
                $improvementCosts = [];
                
                foreach ($improvementKeys as $key) {
                    $improvementCounts[] = $improvementData['current'][$key]['count'] ?? 0;
                    $improvementCosts[] = $improvementData['current'][$key]['cost'] ?? 0;
                }
                
                // Summary data from reportData
                $currentNewBuildingsCount = 0;
                $currentNewBuildingsCost = 0;
                $currentImprovementsCount = 0;
                $currentImprovementsCost = 0;
                $previousNewBuildingsCount = 0;
                $previousNewBuildingsCost = 0;
                $previousImprovementsCount = 0;
                $previousImprovementsCost = 0;
                
                foreach ($reportData as $data) {
                    $currentNewBuildingsCount += $data['current']['new_buildings']['count'] ?? 0;
                    $currentNewBuildingsCost += $data['current']['new_buildings']['cost'] ?? 0;
                    $currentImprovementsCount += $data['current']['improvements']['count'] ?? 0;
                    $currentImprovementsCost += $data['current']['improvements']['cost'] ?? 0;
                    $previousNewBuildingsCount += $data['previous']['new_buildings']['count'] ?? 0;
                    $previousNewBuildingsCost += $data['previous']['new_buildings']['cost'] ?? 0;
                    $previousImprovementsCount += $data['previous']['improvements']['count'] ?? 0;
                    $previousImprovementsCost += $data['previous']['improvements']['cost'] ?? 0;
                }
                
                $totalCurrentBuildings = $currentNewBuildingsCount + $currentImprovementsCount;
                $totalCurrentCost = $currentNewBuildingsCost + $currentImprovementsCost;
                $totalPreviousBuildings = $previousNewBuildingsCount + $previousImprovementsCount;
                $totalPreviousCost = $previousNewBuildingsCost + $previousImprovementsCost;
                
                $totalBuildingsChange = $totalPreviousBuildings > 0 ? 
                    (($totalCurrentBuildings - $totalPreviousBuildings) / $totalPreviousBuildings) * 100 : 0;
                $totalCostChange = $totalPreviousCost > 0 ? 
                    (($totalCurrentCost - $totalPreviousCost) / $totalPreviousCost) * 100 : 0;
            @endphp

            <!-- Year Filter -->
            <div class="card card-outline card-info mb-4">
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
                    <form method="GET" class="row align-items-center">
                        <div class="col-md-3">
                            <label class="mb-1">{{ __('Year') }}</label>
                            <select name="year" class="form-control form-control-sm">
                                @foreach($availableYears as $year)
                                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mt-4">
                            <button type="submit" class="btn btn-info btn-sm">
                                <i class="fas fa-filter mr-1"></i> {{ __('Apply Filter') }}
                            </button>
                            <a href="{{ url()->current() }}" class="btn btn-light btn-sm border">
                                <i class="fas fa-redo mr-1"></i> {{ __('Reset') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ number_format($totalCurrentBuildings) }}</h3>
                            <p>{{ __('Total Buildings') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ number_format($currentNewBuildingsCount) }}</h3>
                            <p>{{ __('New Buildings') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-plus-square"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($currentImprovementsCount) }}</h3>
                            <p>{{ __('Improvements') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tools"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ number_format($totalCurrentCost) }}</h3>
                            <p>{{ __('Total Cost') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row mb-4">
                <!-- New Buildings Cost Distribution -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('New Buildings Cost Distribution') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($newBuildingKeys) > 0)
                                <canvas id="newBuildingsChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-plus-square fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No new building data available') }}</h5>
                                    <p class="text-muted">{{ __('Try selecting a different year') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Improvements Cost Distribution -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Improvements Cost Distribution') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($improvementKeys) > 0)
                                <canvas id="improvementsChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No improvement data available') }}</h5>
                                    <p class="text-muted">{{ __('Try selecting a different year') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Year Comparison Charts -->
            <div class="row mb-4">
                <!-- Building Activity Comparison Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Building Activity Comparison') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="activityComparisonChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Cost Comparison Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                {{ __('Cost Distribution') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($currentNewBuildingsCost > 0 || $currentImprovementsCost > 0)
                                <canvas id="costDistributionChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No cost data available') }}</h5>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Report Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-table mr-1"></i>
                        {{ __('Detailed Building Institution Report') }}
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th rowspan="2">{{ __('Kebele') }}</th>
                                    <th colspan="3" class="text-center">{{ $selectedYear }} ({{ __('New Buildings') }})</th>
                                    <th colspan="3" class="text-center">{{ $selectedYear }} ({{ __('Improvements') }})</th>
                                    <th colspan="3" class="text-center">{{ $previousYear }} ({{ __('New Buildings') }})</th>
                                    <th colspan="3" class="text-center">{{ $previousYear }} ({{ __('Improvements') }})</th>
                                </tr>
                                <tr>
                                    <!-- Current Year New Buildings Headers -->
                                    <th>{{ __('Count') }}</th>
                                    <th>{{ __('Cost') }}</th>
                                    <th>{{ __('Institutions') }}</th>
                                    
                                    <!-- Current Year Improvements Headers -->
                                    <th>{{ __('Count') }}</th>
                                    <th>{{ __('Cost') }}</th>
                                    <th>{{ __('Institutions') }}</th>
                                    
                                    <!-- Previous Year New Buildings Headers -->
                                    <th>{{ __('Count') }}</th>
                                    <th>{{ __('Cost') }}</th>
                                    <th>{{ __('Institutions') }}</th>
                                    
                                    <!-- Previous Year Improvements Headers -->
                                    <th>{{ __('Count') }}</th>
                                    <th>{{ __('Cost') }}</th>
                                    <th>{{ __('Institutions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reportData as $data)
                                    <tr>
                                        <td class="fw-bold">{{ $data['kebele_name'] }}</td>

                                        <!-- Current Year New Buildings -->
                                        <td class="text-center">{{ number_format($data['current']['new_buildings']['count'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['new_buildings']['cost'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['new_buildings']['institutions'] ?? 0) }}</td>

                                        <!-- Current Year Improvements -->
                                        <td class="text-center">{{ number_format($data['current']['improvements']['count'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['improvements']['cost'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['improvements']['institutions'] ?? 0) }}</td>

                                        <!-- Previous Year New Buildings -->
                                        <td class="text-center">{{ number_format($data['previous']['new_buildings']['count'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['previous']['new_buildings']['cost'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['previous']['new_buildings']['institutions'] ?? 0) }}</td>

                                        <!-- Previous Year Improvements -->
                                        <td class="text-center">{{ number_format($data['previous']['improvements']['count'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['previous']['improvements']['cost'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['previous']['improvements']['institutions'] ?? 0) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center text-muted py-4">
                                            <i class="fas fa-info-circle me-2"></i>{{ __('No data found for selected year') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if(count($reportData) > 0)
                            <tfoot class="bg-light">
                                <tr>
                                    <td class="fw-bold">{{ __('TOTAL') }}</td>
                                    
                                    <!-- Current Year New Buildings Totals -->
                                    <td class="text-center fw-bold">{{ number_format($currentNewBuildingsCount) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentNewBuildingsCost) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'current'), 'new_buildings_institutions')) ?? 0) }}</td>
                                    
                                    <!-- Current Year Improvements Totals -->
                                    <td class="text-center fw-bold">{{ number_format($currentImprovementsCount) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentImprovementsCost) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'current'), 'improvements_institutions')) ?? 0) }}</td>
                                    
                                    <!-- Previous Year New Buildings Totals -->
                                    <td class="text-center fw-bold">{{ number_format($previousNewBuildingsCount) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($previousNewBuildingsCost) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'previous'), 'new_buildings_institutions')) ?? 0) }}</td>
                                    
                                    <!-- Previous Year Improvements Totals -->
                                    <td class="text-center fw-bold">{{ number_format($previousImprovementsCount) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($previousImprovementsCost) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'previous'), 'improvements_institutions')) ?? 0) }}</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- New Buildings Details Table -->
            @if(count($newBuildingData['current']) > 0)
            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-plus-square mr-1"></i>
                        {{ __('New Buildings Details') }} ({{ $selectedYear }})
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Building / Institution') }}</th>
                                    <th class="text-right">{{ __('Count') }}</th>
                                    <th class="text-right">{{ __('Cost') }}</th>
                                    <th class="text-right">{{ __('Avg Cost per Unit') }}</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($newBuildingKeys as $index => $key)
                                    @php
                                        $currentCount = $newBuildingData['current'][$key]['count'] ?? 0;
                                        $currentCost = $newBuildingData['current'][$key]['cost'] ?? 0;
                                        $previousCount = $newBuildingData['previous'][$key]['count'] ?? 0;
                                        $previousCost = $newBuildingData['previous'][$key]['cost'] ?? 0;
                                        
                                        $avgCost = $currentCount > 0 ? $currentCost / $currentCount : 0;
                                        $countChange = $previousCount > 0 ? 
                                            (($currentCount - $previousCount) / $previousCount) * 100 : 
                                            ($currentCount > 0 ? 100 : 0);
                                        $countPercent = $currentNewBuildingsCount > 0 ? 
                                            ($currentCount / $currentNewBuildingsCount) * 100 : 0;
                                        $costPercent = $currentNewBuildingsCost > 0 ? 
                                            ($currentCost / $currentNewBuildingsCost) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $key }}</td>
                                        <td class="text-right">
                                            {{ number_format($currentCount) }}
                                            <small class="text-muted">({{ number_format($countPercent, 1) }}%)</small>
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($currentCost) }}
                                            <small class="text-muted">({{ number_format($costPercent, 1) }}%)</small>
                                        </td>
                                        <td class="text-right">{{ number_format($avgCost, 2) }}</td>
                                        
                                    </tr>
                                @endforeach
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="2">{{ __('TOTAL NEW BUILDINGS') }}</td>
                                    <td class="text-right">{{ number_format($currentNewBuildingsCount) }}</td>
                                    <td class="text-right">{{ number_format($currentNewBuildingsCost) }}</td>
                                    <td class="text-right">{{ number_format($currentNewBuildingsCount > 0 ? $currentNewBuildingsCost / $currentNewBuildingsCount : 0, 2) }}</td>
                                     
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Improvements Details Table -->
            @if(count($improvementData['current']) > 0)
            <div class="card mt-4">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-tools mr-1"></i>
                        {{ __('Improvements Details') }} ({{ $selectedYear }})
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Improvement / Institution') }}</th>
                                    <th class="text-right">{{ __('Count') }}</th>
                                    <th class="text-right">{{ __('Cost') }}</th>
                                    <th class="text-right">{{ __('Avg Cost per Unit') }}</th>
                                     
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($improvementKeys as $index => $key)
                                    @php
                                        $currentCount = $improvementData['current'][$key]['count'] ?? 0;
                                        $currentCost = $improvementData['current'][$key]['cost'] ?? 0;
                                        $previousCount = $improvementData['previous'][$key]['count'] ?? 0;
                                        $previousCost = $improvementData['previous'][$key]['cost'] ?? 0;
                                        
                                        $avgCost = $currentCount > 0 ? $currentCost / $currentCount : 0;
                                        $countChange = $previousCount > 0 ? 
                                            (($currentCount - $previousCount) / $previousCount) * 100 : 
                                            ($currentCount > 0 ? 100 : 0);
                                        $countPercent = $currentImprovementsCount > 0 ? 
                                            ($currentCount / $currentImprovementsCount) * 100 : 0;
                                        $costPercent = $currentImprovementsCost > 0 ? 
                                            ($currentCost / $currentImprovementsCost) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $key }}</td>
                                        <td class="text-right">
                                            {{ number_format($currentCount) }}
                                            <small class="text-muted">({{ number_format($countPercent, 1) }}%)</small>
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($currentCost) }}
                                            <small class="text-muted">({{ number_format($costPercent, 1) }}%)</small>
                                        </td>
                                        <td class="text-right">{{ number_format($avgCost, 2) }}</td>
                                        
                                    </tr>
                                @endforeach
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="2">{{ __('TOTAL IMPROVEMENTS') }}</td>
                                    <td class="text-right">{{ number_format($currentImprovementsCount) }}</td>
                                    <td class="text-right">{{ number_format($currentImprovementsCost) }}</td>
                                    <td class="text-right">{{ number_format($currentImprovementsCount > 0 ? $currentImprovementsCost / $currentImprovementsCount : 0, 2) }}</td>
                                     
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </section>
</div>

<!-- Add Chart.js -->
<script src="{{ asset('vendor/adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Prepare data from PHP variables
    const selectedYear = @json($selectedYear);
    const previousYear = @json($previousYear);
    
    // Summary data
    const currentNewBuildingsCount = @json($currentNewBuildingsCount);
    const currentNewBuildingsCost = @json($currentNewBuildingsCost);
    const currentImprovementsCount = @json($currentImprovementsCount);
    const currentImprovementsCost = @json($currentImprovementsCost);
    const previousNewBuildingsCount = @json($previousNewBuildingsCount);
    const previousNewBuildingsCost = @json($previousNewBuildingsCost);
    const previousImprovementsCount = @json($previousImprovementsCount);
    const previousImprovementsCost = @json($previousImprovementsCost);
    
    // Building data
    const newBuildingKeys = @json($newBuildingKeys);
    const newBuildingCosts = @json($newBuildingCosts);
    const improvementKeys = @json($improvementKeys);
    const improvementCosts = @json($improvementCosts);

    // Color palette
    const buildingColors = [
        'rgba(23, 162, 184, 0.7)',   // Teal
        'rgba(0, 123, 255, 0.7)',    // Blue
        'rgba(40, 167, 69, 0.7)',    // Green
        'rgba(255, 193, 7, 0.7)',    // Yellow
        'rgba(220, 53, 69, 0.7)',    // Red
        'rgba(108, 117, 125, 0.7)',  // Gray
        'rgba(111, 66, 193, 0.7)',   // Purple
        'rgba(253, 126, 20, 0.7)',   // Orange
        'rgba(32, 201, 151, 0.7)',   // Mint
        'rgba(233, 30, 99, 0.7)',    // Pink
    ];

    // Chart 1: New Buildings Cost Distribution
    if (newBuildingKeys.length > 0) {
        const newBuildingsCtx = document.getElementById('newBuildingsChart').getContext('2d');
        new Chart(newBuildingsCtx, {
            type: 'bar',
            data: {
                labels: newBuildingKeys,
                datasets: [{
                    label: `{{ __('Cost') }} (${selectedYear})`,
                    data: newBuildingCosts,
                    backgroundColor: 'rgba(0, 123, 255, 0.7)',
                    borderColor: 'rgba(0, 123, 255, 1)',
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
                            text: '{{ __('Cost') }}'
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
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
                                const total = newBuildingCosts.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed.y / total) * 100).toFixed(1) : 0;
                                return `${context.dataset.label}: ${context.parsed.y.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Chart 2: Improvements Cost Distribution
    if (improvementKeys.length > 0) {
        const improvementsCtx = document.getElementById('improvementsChart').getContext('2d');
        new Chart(improvementsCtx, {
            type: 'bar',
            data: {
                labels: improvementKeys,
                datasets: [{
                    label: `{{ __('Cost') }} (${selectedYear})`,
                    data: improvementCosts,
                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                    borderColor: 'rgba(40, 167, 69, 1)',
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
                            text: '{{ __('Cost') }}'
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
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
                                const total = improvementCosts.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed.y / total) * 100).toFixed(1) : 0;
                                return `${context.dataset.label}: ${context.parsed.y.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Chart 3: Activity Comparison Chart
    const activityComparisonCtx = document.getElementById('activityComparisonChart').getContext('2d');
    new Chart(activityComparisonCtx, {
        type: 'bar',
        data: {
            labels: ['{{ __('New Buildings') }}', '{{ __('Improvements') }}'],
            datasets: [
                {
                    label: `{{ __('Current Year') }} (${selectedYear})`,
                    data: [currentNewBuildingsCount, currentImprovementsCount],
                    backgroundColor: ['rgba(0, 123, 255, 0.7)', 'rgba(40, 167, 69, 0.7)'],
                    borderColor: ['rgba(0, 123, 255, 1)', 'rgba(40, 167, 69, 1)'],
                    borderWidth: 1
                },
                {
                    label: `{{ __('Previous Year') }} (${previousYear})`,
                    data: [previousNewBuildingsCount, previousImprovementsCount],
                    backgroundColor: ['rgba(201, 203, 207, 0.7)', 'rgba(255, 205, 86, 0.7)'],
                    borderColor: ['rgba(201, 203, 207, 1)', 'rgba(255, 205, 86, 1)'],
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
                        text: '{{ __('Count') }}'
                    },
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString();
                        }
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

    // Chart 4: Cost Distribution Pie Chart
    if (currentNewBuildingsCost > 0 || currentImprovementsCost > 0) {
        const costDistributionCtx = document.getElementById('costDistributionChart').getContext('2d');
        new Chart(costDistributionCtx, {
            type: 'pie',
            data: {
                labels: ['{{ __('New Buildings') }}', '{{ __('Improvements') }}'],
                datasets: [{
                    data: [currentNewBuildingsCost, currentImprovementsCost],
                    backgroundColor: ['rgba(0, 123, 255, 0.7)', 'rgba(40, 167, 69, 0.7)'],
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
                                return `${label}: ${value.toLocaleString()} (${percentage}%)`;
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
    .bg-primary { background-color: #007bff !important; }
    .bg-success { background-color: #28a745 !important; }
    .bg-warning { background-color: #ffc107 !important; }
    
    .card-header.bg-primary .card-tools .btn-tool,
    .card-header.bg-success .card-tools .btn-tool {
        color: rgba(255,255,255,0.8) !important;
    }
    
    .card-header.bg-primary .card-tools .btn-tool:hover,
    .card-header.bg-success .card-tools .btn-tool:hover {
        color: white !important;
    }
    
    table th, table td {
        vertical-align: middle !important;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(23, 162, 184, 0.05);
    }
</style>

</x-layout>