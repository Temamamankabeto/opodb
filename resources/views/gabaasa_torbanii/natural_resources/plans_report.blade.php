<x-layout>
<div class="content-header">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <div class="bg-white border rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:60px;height:60px;">
                        <i class="fas fa-mountain fa-lg text-success"></i>
                    </div>
                    <div>
                        <h1 class="m-0 text-dark">{{ __('Kebele Level Natural Resources Report') }}</h1>
                        <small class="text-dark">{{ __('Natural resources planning and analysis') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @php
                // Prepare resource data for charts
                $resourceTypes = array_keys($resourceData['current'] ?? []);
                $resourceAmounts = array_values($resourceData['current'] ?? []);
                $previousResourceAmounts = [];
                
                foreach ($resourceTypes as $type) {
                    $previousResourceAmounts[] = $resourceData['previous'][$type] ?? 0;
                }
            @endphp

            <!-- Year Filter -->
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
                            <button type="submit" class="btn btn-success btn-sm">
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
                            <h3>{{ number_format($currentTotal) }}</h3>
                            <p>{{ __('Total Resources') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-list"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ count($resourceTypes) }}</h3>
                            <p>{{ __('Resource Types') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-list-ol"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ number_format($current['items'] ?? 0) }}</h3>
                            <p>{{ __('Total Items') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-cube"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ number_format($totalChange, 1) }}%</h3>
                            <p>{{ __('Year-over-Year Change') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row mb-4">
                <!-- Resource Distribution Chart -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Natural Resources Distribution') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($resourceTypes) > 0)
                                <canvas id="resourceChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-mountain fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No natural resource data available') }}</h5>
                                    <p class="text-muted">{{ __('Try selecting a different year') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Top Resources Pie Chart -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                {{ __('Top Resources Distribution') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($resourceTypes) > 0)
                                <canvas id="topResourcesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No data for pie chart') }}</h5>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Year Comparison Charts -->
            <div class="row mb-4">
                <!-- Year Comparison Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Year Comparison') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="yearComparisonChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Resource Type Year Comparison -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-1"></i>
                                {{ __('Resource Type Year Comparison') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($resourceTypes) > 0)
                                <canvas id="resourceComparisonChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No comparison data available') }}</h5>
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
                        {{ __('Detailed Natural Resources Report') }}
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
                                    <th>{{ __('Kebele') }}</th>
                                    <th colspan="2" class="text-center">{{ $selectedYear }} ({{ __('Current Year') }})</th>
                                    <th colspan="2" class="text-center">{{ $previousYear }} ({{ __('Previous Year') }})</th>
                                    <th colspan="2" class="text-center">{{ __('Year-over-Year Change') }}</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th>{{ __('Total Amount') }}</th>
                                    <th>{{ __('Number of Items') }}</th>
                                    <th>{{ __('Total Amount') }}</th>
                                    <th>{{ __('Number of Items') }}</th>
                                    <th>{{ __('Amount Change') }}</th>
                                    <th>{{ __('Change %') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reportData as $data)
                                    @php
                                        $amountChange = ($data['current']['total_amount'] ?? 0) - ($data['previous']['total_amount'] ?? 0);
                                        $amountChangePercent = ($data['previous']['total_amount'] ?? 0) > 0 ? 
                                            ($amountChange / ($data['previous']['total_amount'] ?? 1)) * 100 : 0;
                                        $itemsChange = ($data['current']['items'] ?? 0) - ($data['previous']['items'] ?? 0);
                                    @endphp
                                    <tr>
                                        <td class="fw-bold">{{ $data['kebele_name'] }}</td>

                                        <!-- Current Year -->
                                        <td class="text-center">{{ number_format($data['current']['total_amount'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['items'] ?? 0) }}</td>

                                        <!-- Previous Year -->
                                        <td class="text-center">{{ number_format($data['previous']['total_amount'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['previous']['items'] ?? 0) }}</td>

                                        <!-- Year-over-Year Change -->
                                        <td class="text-center {{ $amountChange >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $amountChange >= 0 ? '+' : '' }}{{ number_format($amountChange) }}
                                        </td>
                                        <td class="text-center {{ $amountChangePercent >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $amountChangePercent >= 0 ? '+' : '' }}{{ number_format($amountChangePercent, 1) }}%
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="fas fa-info-circle me-2"></i>{{ __('No data found for selected year') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if(count($reportData) > 0)
                            <tfoot class="bg-light">
                                <tr>
                                    <td class="fw-bold">{{ __('TOTAL') }}</td>
                                    
                                    <!-- Current Year Totals -->
                                    <td class="text-center fw-bold">{{ number_format($currentTotal) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'current'), 'items')) ?? 0) }}</td>
                                    
                                    <!-- Previous Year Totals -->
                                    <td class="text-center fw-bold">{{ number_format($previousTotal) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'previous'), 'items')) ?? 0) }}</td>
                                    
                                    <!-- Year-over-Year Change Totals -->
                                    @php
                                        $totalAmountChange = $currentTotal - $previousTotal;
                                        $totalAmountChangePercent = $previousTotal > 0 ? ($totalAmountChange / $previousTotal) * 100 : 0;
                                    @endphp
                                    <td class="text-center fw-bold {{ $totalAmountChange >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $totalAmountChange >= 0 ? '+' : '' }}{{ number_format($totalAmountChange) }}
                                    </td>
                                    <td class="text-center fw-bold {{ $totalAmountChangePercent >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $totalAmountChangePercent >= 0 ? '+' : '' }}{{ number_format($totalAmountChangePercent, 1) }}%
                                    </td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- Resource Details Table -->
            @if(count($resourceData['current']) > 0)
            <div class="card mt-4">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-list mr-1"></i>
                        {{ __('Resource Details') }} ({{ $selectedYear }})
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Resource') }}</th>
                                    <th class="text-right">{{ __('Current Year') }} ({{ $selectedYear }})</th>
                                    <th class="text-right">{{ __('Previous Year') }} ({{ $previousYear }})</th>
                                    <th class="text-right">{{ __('Change') }}</th>
                                    <th class="text-right">{{ __('Change %') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalCurrentResources = array_sum($resourceAmounts);
                                    $totalPreviousResources = array_sum($previousResourceAmounts);
                                @endphp
                                @foreach($resourceTypes as $index => $type)
                                    @php
                                        $currentAmount = $resourceAmounts[$index] ?? 0;
                                        $previousAmount = $previousResourceAmounts[$index] ?? 0;
                                        $change = $currentAmount - $previousAmount;
                                        $changePercent = $previousAmount > 0 ? ($change / $previousAmount) * 100 : ($currentAmount > 0 ? 100 : 0);
                                        $currentPercent = $totalCurrentResources > 0 ? ($currentAmount / $totalCurrentResources) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $type }}</td>
                                        <td class="text-right">
                                            {{ number_format($currentAmount) }}
                                            <small class="text-muted">({{ number_format($currentPercent, 1) }}%)</small>
                                        </td>
                                        <td class="text-right">{{ number_format($previousAmount) }}</td>
                                        <td class="text-right {{ $change >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $change >= 0 ? '+' : '' }}{{ number_format($change) }}
                                        </td>
                                        <td class="text-right {{ $changePercent >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $changePercent >= 0 ? '+' : '' }}{{ number_format($changePercent, 1) }}%
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="2">{{ __('TOTAL') }}</td>
                                    <td class="text-right">{{ number_format($totalCurrentResources) }}</td>
                                    <td class="text-right">{{ number_format($totalPreviousResources) }}</td>
                                    @php
                                        $totalChangeAmount = $totalCurrentResources - $totalPreviousResources;
                                        $totalChangePercent = $totalPreviousResources > 0 ? ($totalChangeAmount / $totalPreviousResources) * 100 : ($totalCurrentResources > 0 ? 100 : 0);
                                    @endphp
                                    <td class="text-right {{ $totalChangeAmount >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $totalChangeAmount >= 0 ? '+' : '' }}{{ number_format($totalChangeAmount) }}
                                    </td>
                                    <td class="text-right {{ $totalChangePercent >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $totalChangePercent >= 0 ? '+' : '' }}{{ number_format($totalChangePercent, 1) }}%
                                    </td>
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
    
    const currentTotal = @json($currentTotal);
    const previousTotal = @json($previousTotal);
    const totalChange = @json($totalChange);
    
    // Resource data
    const resourceTypes = @json($resourceTypes);
    const resourceAmounts = @json($resourceAmounts);
    const previousResourceAmounts = @json($previousResourceAmounts);

    // Color palette for resource types
    const resourceColors = [
        'rgba(46, 125, 50, 0.7)',    // Dark Green
        'rgba(121, 85, 72, 0.7)',    // Brown
        'rgba(93, 64, 55, 0.7)',     // Dark Brown
        'rgba(62, 39, 35, 0.7)',     // Very Dark Brown
        'rgba(56, 142, 60, 0.7)',    // Green
        'rgba(67, 160, 71, 0.7)',    // Light Green
        'rgba(27, 94, 32, 0.7)',     // Forest Green
        'rgba(76, 175, 80, 0.7)',    // Material Green
        'rgba(139, 195, 74, 0.7)',   // Lime Green
        'rgba(205, 220, 57, 0.7)',   // Yellow Green
    ];

    // Chart 1: Resource Distribution Chart
    if (resourceTypes.length > 0) {
        const resourceCtx = document.getElementById('resourceChart').getContext('2d');
        new Chart(resourceCtx, {
            type: 'bar',
            data: {
                labels: resourceTypes,
                datasets: [{
                    label: `{{ __('Resource Amount') }} (${selectedYear})`,
                    data: resourceAmounts,
                    backgroundColor: resourceColors.slice(0, resourceTypes.length),
                    borderColor: resourceColors.slice(0, resourceTypes.length).map(color => color.replace('0.7', '1')),
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
                                const total = resourceAmounts.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed.y / total) * 100).toFixed(1) : 0;
                                return `${context.dataset.label}: ${context.parsed.y.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Chart 2: Top Resources Pie Chart
    if (resourceTypes.length > 0) {
        // Take top resources for pie chart (max 7)
        const topCount = Math.min(resourceTypes.length, 7);
        const topLabels = resourceTypes.slice(0, topCount);
        const topData = resourceAmounts.slice(0, topCount);
        
        // If there are more types, group the rest as "Others"
        if (resourceTypes.length > topCount) {
            const othersSum = resourceAmounts.slice(topCount).reduce((a, b) => a + b, 0);
            if (othersSum > 0) {
                topLabels.push('{{ __('Others') }}');
                topData.push(othersSum);
            }
        }

        const topResourcesCtx = document.getElementById('topResourcesChart').getContext('2d');
        new Chart(topResourcesCtx, {
            type: 'pie',
            data: {
                labels: topLabels,
                datasets: [{
                    data: topData,
                    backgroundColor: resourceColors.slice(0, topLabels.length),
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

    // Chart 3: Year Comparison Chart
    const yearComparisonCtx = document.getElementById('yearComparisonChart').getContext('2d');
    new Chart(yearComparisonCtx, {
        type: 'bar',
        data: {
            labels: ['{{ __('Total Amount') }}', '{{ __('Number of Items') }}'],
            datasets: [
                {
                    label: `{{ __('Current Year') }} (${selectedYear})`,
                    data: [currentTotal, @json($current['items'] ?? 0)],
                    backgroundColor: ['rgba(46, 125, 50, 0.7)', 'rgba(56, 142, 60, 0.7)'],
                    borderColor: ['rgba(46, 125, 50, 1)', 'rgba(56, 142, 60, 1)'],
                    borderWidth: 1
                },
                {
                    label: `{{ __('Previous Year') }} (${previousYear})`,
                    data: [previousTotal, @json($previous['items'] ?? 0)],
                    backgroundColor: ['rgba(121, 85, 72, 0.7)', 'rgba(139, 195, 74, 0.7)'],
                    borderColor: ['rgba(121, 85, 72, 1)', 'rgba(139, 195, 74, 1)'],
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

    // Chart 4: Resource Type Year Comparison (for top 5 types)
    if (resourceTypes.length > 0) {
        // Take top 5 types for comparison chart
        const compareCount = Math.min(resourceTypes.length, 5);
        const compareLabels = resourceTypes.slice(0, compareCount);
        const compareCurrentData = resourceAmounts.slice(0, compareCount);
        const comparePreviousData = previousResourceAmounts.slice(0, compareCount);
        
        const resourceComparisonCtx = document.getElementById('resourceComparisonChart').getContext('2d');
        new Chart(resourceComparisonCtx, {
            type: 'bar',
            data: {
                labels: compareLabels,
                datasets: [
                    {
                        label: `{{ __('Current Year') }} (${selectedYear})`,
                        data: compareCurrentData,
                        backgroundColor: 'rgba(46, 125, 50, 0.7)',
                        borderColor: 'rgba(46, 125, 50, 1)',
                        borderWidth: 1
                    },
                    {
                        label: `{{ __('Previous Year') }} (${previousYear})`,
                        data: comparePreviousData,
                        backgroundColor: 'rgba(121, 85, 72, 0.7)',
                        borderColor: 'rgba(121, 85, 72, 1)',
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
    .bg-primary { background-color: #007bff !important; }
    
    .card-header.bg-success .card-tools .btn-tool {
        color: rgba(255,255,255,0.8) !important;
    }
    
    .card-header.bg-success .card-tools .btn-tool:hover {
        color: white !important;
    }
    
    table th, table td {
        vertical-align: middle !important;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(40, 167, 69, 0.05);
    }
</style>

</x-layout>