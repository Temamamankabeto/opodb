<x-layout>
<div class="content-header">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <div class="bg-white border rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:60px;height:60px;">
                        <i class="fas fa-hospital fa-lg text-danger"></i>
                    </div>
                    <div>
                        <h1 class="m-0 text-dark">{{ __('Kebele Level Health Report') }}</h1>
                        <small class="text-dark">{{ __('Maternal, child health, and healthcare services analysis') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @php
                // Prepare data for charts
                $activityKeys = array_keys($activityData['current'] ?? []);
                $activityAmounts = array_values($activityData['current'] ?? []);
                
                // Limit data for charts to avoid overcrowding
                $maxChartItems = 10;
                $limitedKeys = array_slice($activityKeys, 0, $maxChartItems);
                $limitedAmounts = array_slice($activityAmounts, 0, $maxChartItems);
                
                // Prepare data for category comparison chart
                $categoryLabels = ['Pregnant Women', 'Children', 'Other Services'];
                $currentCategoryData = [
                    $reportData['current']['pregnant_women']['total'] ?? 0,
                    $reportData['current']['children']['total'] ?? 0,
                    $reportData['current']['other']['total'] ?? 0
                ];
                $previousCategoryData = [
                    $reportData['previous']['pregnant_women']['total'] ?? 0,
                    $reportData['previous']['children']['total'] ?? 0,
                    $reportData['previous']['other']['total'] ?? 0
                ];
                
                // Budget data
                $currentBudgetData = [
                    $reportData['current']['pregnant_women']['budget'] ?? 0,
                    $reportData['current']['children']['budget'] ?? 0,
                    $reportData['current']['other']['budget'] ?? 0
                ];
                
                // Prepare detailed maternal health data
                $maternalLabels = [
                    'First Visit',
                    'Delivery',
                    'Postnatal Care',
                    'Nutrition Support'
                ];
                $maternalCurrentData = [
                    $reportData['current']['pregnant_women']['first_visit'] ?? 0,
                    $reportData['current']['pregnant_women']['delivery'] ?? 0,
                    $reportData['current']['pregnant_women']['postnatal'] ?? 0,
                    $reportData['current']['pregnant_women']['nutrition_support'] ?? 0
                ];
                $maternalPreviousData = [
                    $reportData['previous']['pregnant_women']['first_visit'] ?? 0,
                    $reportData['previous']['pregnant_women']['delivery'] ?? 0,
                    $reportData['previous']['pregnant_women']['postnatal'] ?? 0,
                    $reportData['previous']['pregnant_women']['nutrition_support'] ?? 0
                ];
            @endphp

            <!-- Year Filter -->
            <div class="card card-outline card-danger mb-4">
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
                            <button type="submit" class="btn btn-danger btn-sm">
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
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ number_format($totalCurrentBeneficiaries) }}</h3>
                            <p>{{ __('Total Beneficiaries') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        @if($beneficiaryChange != 0)
                            <small class="small-box-footer">
                                {{ $beneficiaryChange > 0 ? '↑' : '↓' }} 
                                {{ number_format(abs($beneficiaryChange), 1) }}% 
                                {{ __('from last year') }}
                            </small>
                        @endif
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ number_format($reportData['current']['pregnant_women']['total'] ?? 0) }}</h3>
                            <p>{{ __('Pregnant Women') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-female"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($reportData['current']['children']['total'] ?? 0) }}</h3>
                            <p>{{ __('Children') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-child"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ number_format($reportData['current']['total_budget'] ?? 0) }}</h3>
                            <p>{{ __('Total Budget (Birr)') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row mb-4">
                <!-- Top Activities Distribution -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-danger text-white">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Top Health Activities') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($limitedKeys) > 0)
                                <canvas id="healthActivitiesChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-hospital fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No health activity data available') }}</h5>
                                    <p class="text-muted">{{ __('Try selecting a different year') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Category Comparison -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                {{ __('Beneficiary Categories') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($totalCurrentBeneficiaries > 0)
                                <canvas id="categoryChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No beneficiary data available') }}</h5>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Year Comparison Charts -->
            <div class="row mb-4">
                <!-- Maternal Health Comparison -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-1"></i>
                                {{ __('Maternal Health Services') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="maternalHealthChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Budget Distribution -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Budget Allocation') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($reportData['current']['total_budget'] > 0)
                                <canvas id="budgetChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No budget data available') }}</h5>
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
                        {{ __('Detailed Health Report') }}
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
                                    <th rowspan="3">{{ __('Kebele') }}</th>
                                    <th colspan="12" class="text-center">{{ __('Health Services Summary') }}</th>
                                </tr>
                                <tr>
                                    <!-- Pregnant Women -->
                                    <th colspan="4" class="text-center bg-danger text-white">{{ __('Pregnant Women') }}</th>
                                    <!-- Children -->
                                    <th colspan="4" class="text-center bg-success text-white">{{ __('Children') }}</th>
                                    <!-- Other Services -->
                                    <th colspan="4" class="text-center bg-info text-white">{{ __('Other Services') }}</th>
                                </tr>
                                <tr>
                                    <!-- Pregnant Women Sub-headers -->
                                    <th>{{ __('First Visit') }}</th>
                                    <th>{{ __('Delivery') }}</th>
                                    <th>{{ __('Postnatal') }}</th>
                                    <th>{{ __('Nutrition') }}</th>
                                    
                                    <!-- Children Sub-headers -->
                                    <th>{{ __('Newborn Followup') }}</th>
                                    <th>{{ __('Vaccinated') }}</th>
                                    <th>{{ __('Diarrhea Cases') }}</th>
                                    <th>{{ __('Development') }}</th>
                                    
                                    <!-- Other Services Sub-headers -->
                                    <th>{{ __('Clean Toilets') }}</th>
                                    <th>{{ __('Family Planning') }}</th>
                                    <th>{{ __('Insurance Members') }}</th>
                                    <th>{{ __('Insurance Collected') }}</th>
                                </tr>
                                <tr class="bg-light">
                                    <td></td>
                                    <!-- Year Labels -->
                                    <td colspan="4" class="text-center">
                                        <small>{{ $selectedYear }} / {{ $previousYear }}</small>
                                    </td>
                                    <td colspan="4" class="text-center">
                                        <small>{{ $selectedYear }} / {{ $previousYear }}</small>
                                    </td>
                                    <td colspan="4" class="text-center">
                                        <small>{{ $selectedYear }} / {{ $previousYear }}</small>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold">{{ $reportData['kebele_name'] }}</td>

                                    <!-- Pregnant Women Data -->
                                    <td class="text-center">
                                        {{ number_format($reportData['current']['pregnant_women']['first_visit'] ?? 0) }}<br>
                                        <small class="text-muted">{{ number_format($reportData['previous']['pregnant_women']['first_visit'] ?? 0) }}</small>
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($reportData['current']['pregnant_women']['delivery'] ?? 0) }}<br>
                                        <small class="text-muted">{{ number_format($reportData['previous']['pregnant_women']['delivery'] ?? 0) }}</small>
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($reportData['current']['pregnant_women']['postnatal'] ?? 0) }}<br>
                                        <small class="text-muted">{{ number_format($reportData['previous']['pregnant_women']['postnatal'] ?? 0) }}</small>
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($reportData['current']['pregnant_women']['nutrition_support'] ?? 0) }}<br>
                                        <small class="text-muted">{{ number_format($reportData['previous']['pregnant_women']['nutrition_support'] ?? 0) }}</small>
                                    </td>
                                    
                                    <!-- Children Data -->
                                    <td class="text-center">
                                        {{ number_format($reportData['current']['children']['newborn_followup'] ?? 0) }}<br>
                                        <small class="text-muted">{{ number_format($reportData['previous']['children']['newborn_followup'] ?? 0) }}</small>
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($reportData['current']['children']['vaccinated'] ?? 0) }}<br>
                                        <small class="text-muted">{{ number_format($reportData['previous']['children']['vaccinated'] ?? 0) }}</small>
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($reportData['current']['children']['diarrhea_cases'] ?? 0) }}<br>
                                        <small class="text-muted">{{ number_format($reportData['previous']['children']['diarrhea_cases'] ?? 0) }}</small>
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($reportData['current']['children']['child_development'] ?? 0) }}<br>
                                        <small class="text-muted">{{ number_format($reportData['previous']['children']['child_development'] ?? 0) }}</small>
                                    </td>
                                    
                                    <!-- Other Services Data -->
                                    <td class="text-center">
                                        {{ number_format($reportData['current']['other']['clean_toilets'] ?? 0) }}<br>
                                        <small class="text-muted">{{ number_format($reportData['previous']['other']['clean_toilets'] ?? 0) }}</small>
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($reportData['current']['other']['family_planning'] ?? 0) }}<br>
                                        <small class="text-muted">{{ number_format($reportData['previous']['other']['family_planning'] ?? 0) }}</small>
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($reportData['current']['other']['insurance_members'] ?? 0) }}<br>
                                        <small class="text-muted">{{ number_format($reportData['previous']['other']['insurance_members'] ?? 0) }}</small>
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($reportData['current']['other']['insurance_collected'] ?? 0) }}<br>
                                        <small class="text-muted">{{ number_format($reportData['previous']['other']['insurance_collected'] ?? 0) }}</small>
                                    </td>
                                </tr>
                                
                                <!-- Total Row -->
                                <tr class="table-secondary">
                                    <td class="fw-bold">{{ __('TOTAL') }}</td>
                                    <td colspan="4" class="text-center fw-bold">
                                        {{ number_format($reportData['current']['pregnant_women']['total'] ?? 0) }} / 
                                        {{ number_format($reportData['previous']['pregnant_women']['total'] ?? 0) }}
                                    </td>
                                    <td colspan="4" class="text-center fw-bold">
                                        {{ number_format($reportData['current']['children']['total'] ?? 0) }} / 
                                        {{ number_format($reportData['previous']['children']['total'] ?? 0) }}
                                    </td>
                                    <td colspan="4" class="text-center fw-bold">
                                        {{ number_format($reportData['current']['other']['total'] ?? 0) }} / 
                                        {{ number_format($reportData['previous']['other']['total'] ?? 0) }}
                                    </td>
                                </tr>
                                
                                <!-- Budget Row -->
                                <tr class="table-warning">
                                    <td class="fw-bold">{{ __('BUDGET (Birr)') }}</td>
                                    <td colspan="4" class="text-center fw-bold">
                                        {{ number_format($reportData['current']['pregnant_women']['budget'] ?? 0, 2) }}
                                    </td>
                                    <td colspan="4" class="text-center fw-bold">
                                        {{ number_format($reportData['current']['children']['budget'] ?? 0, 2) }}
                                    </td>
                                    <td colspan="4" class="text-center fw-bold">
                                        {{ number_format($reportData['current']['other']['budget'] ?? 0, 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Additional Data Tables -->
            <div class="row mt-4">
                <!-- Activity Details -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-danger text-white">
                            <h3 class="card-title">
                                <i class="fas fa-list-alt mr-1"></i>
                                {{ __('Health Activity Details') }} ({{ $selectedYear }})
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr class="bg-light">
                                            <th>{{ __('#') }}</th>
                                            <th>{{ __('Activity Name') }}</th>
                                            <th class="text-right">{{ __('Beneficiaries') }}</th>
                                            <th>{{ __('Type') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($activityData['current'] as $key => $count)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $key }}</td>
                                                <td class="text-right">{{ number_format($count) }}</td>
                                                <td>
                                                    @php
                                                        $type = 'other';
                                                        if (strpos($key, 'Haadholee') !== false) $type = 'Pregnant Women';
                                                        elseif (strpos($key, 'Daa\'imman') !== false) $type = 'Children';
                                                        elseif (strpos($key, 'reefuu') !== false) $type = 'Children';
                                                        elseif (strpos($key, 'talaallii') !== false) $type = 'Children';
                                                    @endphp
                                                    <span class="badge 
                                                        {{ $type == 'Pregnant Women' ? 'bg-danger' : 
                                                           ($type == 'Children' ? 'bg-success' : 'bg-info') }}">
                                                        {{ $type }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-3 text-muted">
                                                    {{ __('No health activity data available') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Make sure Chart is available
        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded');
            // Dynamically load Chart.js if not loaded
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
            script.onload = initializeCharts;
            document.head.appendChild(script);
        } else {
            initializeCharts();
        }

        function initializeCharts() {
            console.log('Initializing charts...');
            
            // Health Activities Chart
            const healthActivitiesCtx = document.getElementById('healthActivitiesChart');
            if (healthActivitiesCtx) {
                console.log('Creating health activities chart');
                try {
                    new Chart(healthActivitiesCtx, {
                        type: 'bar',
                        data: {
                            labels: @json($limitedKeys),
                            datasets: [{
                                label: '{{ __("Number of Beneficiaries") }}',
                                data: @json($limitedAmounts),
                                backgroundColor: 'rgba(220, 53, 69, 0.7)',
                                borderColor: 'rgba(220, 53, 69, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: '{{ __("Number of Beneficiaries") }}'
                                    }
                                },
                                x: {
                                    ticks: {
                                        autoSkip: false,
                                        maxRotation: 45,
                                        minRotation: 45
                                    }
                                }
                            }
                        }
                    });
                } catch (error) {
                    console.error('Error creating health activities chart:', error);
                }
            }

            // Category Chart
            const categoryCtx = document.getElementById('categoryChart');
            if (categoryCtx) {
                console.log('Creating category chart');
                try {
                    new Chart(categoryCtx, {
                        type: 'pie',
                        data: {
                            labels: @json($categoryLabels),
                            datasets: [{
                                data: @json($currentCategoryData),
                                backgroundColor: [
                                    'rgba(220, 53, 69, 0.7)',
                                    'rgba(40, 167, 69, 0.7)',
                                    'rgba(23, 162, 184, 0.7)'
                                ],
                                borderColor: [
                                    'rgba(220, 53, 69, 1)',
                                    'rgba(40, 167, 69, 1)',
                                    'rgba(23, 162, 184, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.raw || 0;
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                            return `${label}: ${value} (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                } catch (error) {
                    console.error('Error creating category chart:', error);
                }
            }

            // Maternal Health Chart
            const maternalHealthCtx = document.getElementById('maternalHealthChart');
            if (maternalHealthCtx) {
                console.log('Creating maternal health chart');
                try {
                    new Chart(maternalHealthCtx, {
                        type: 'bar',
                        data: {
                            labels: @json($maternalLabels),
                            datasets: [
                                {
                                    label: '{{ $selectedYear }}',
                                    data: @json($maternalCurrentData),
                                    backgroundColor: 'rgba(220, 53, 69, 0.7)',
                                    borderColor: 'rgba(220, 53, 69, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: '{{ $previousYear }}',
                                    data: @json($maternalPreviousData),
                                    backgroundColor: 'rgba(201, 203, 207, 0.7)',
                                    borderColor: 'rgba(201, 203, 207, 1)',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: '{{ __("Number of Women") }}'
                                    }
                                }
                            }
                        }
                    });
                } catch (error) {
                    console.error('Error creating maternal health chart:', error);
                }
            }

            // Budget Chart
            const budgetCtx = document.getElementById('budgetChart');
            if (budgetCtx) {
                console.log('Creating budget chart');
                try {
                    new Chart(budgetCtx, {
                        type: 'bar',
                        data: {
                            labels: @json($categoryLabels),
                            datasets: [{
                                label: '{{ __("Budget (Birr)") }}',
                                data: @json($currentBudgetData),
                                backgroundColor: [
                                    'rgba(255, 193, 7, 0.7)',
                                    'rgba(255, 193, 7, 0.5)',
                                    'rgba(255, 193, 7, 0.3)'
                                ],
                                borderColor: [
                                    'rgba(255, 193, 7, 1)',
                                    'rgba(255, 193, 7, 0.8)',
                                    'rgba(255, 193, 7, 0.6)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: '{{ __("Budget (Birr)") }}'
                                    },
                                    ticks: {
                                        callback: function(value) {
                                            return 'Birr ' + value.toLocaleString();
                                        }
                                    }
                                }
                            }
                        }
                    });
                } catch (error) {
                    console.error('Error creating budget chart:', error);
                }
            }
            
            console.log('All charts initialized');
        }
    });
</script>

<style>
    .table th {
        font-size: 0.85rem;
        vertical-align: middle;
    }
    .table td {
        font-size: 0.9rem;
        vertical-align: middle;
    }
    .small-box .icon {
        top: 10px;
    }
    .small-box .inner h3 {
        font-size: 2rem;
        margin-bottom: 0.2rem;
    }
    .small-box .inner p {
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }
    .small-box-footer {
        padding: 3px 0;
        background: rgba(0,0,0,0.1);
    }
    canvas {
        display: block !important;
    }
</style>
</x-layout>