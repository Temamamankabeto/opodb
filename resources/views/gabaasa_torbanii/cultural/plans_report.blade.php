<x-layout>
<div class="content-header">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <div class="bg-white border rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:60px;height:60px;">
                        <i class="fas fa-theater-masks fa-lg text-purple"></i>
                    </div>
                    <div>
                        <h1 class="m-0 text-dark">{{ __('Kebele Level Cultural Report') }}</h1>
                        <small class="text-dark">{{ __('Cultural awareness and youth cultural activities analysis') }}</small>
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
                
                $previousActivityKeys = array_keys($activityData['previous'] ?? []);
                $previousActivityAmounts = array_values($activityData['previous'] ?? []);
                
                // Prepare data for comparison chart
                $categoryLabels = ['Culture Awareness', 'Youth Cultural Activities'];
                $currentCategoryData = [
                    $reportData['current']['culture_awareness']['quantity'] ?? 0,
                    $reportData['current']['youth_culture']['quantity'] ?? 0
                ];
                $previousCategoryData = [
                    $reportData['previous']['culture_awareness']['quantity'] ?? 0,
                    $reportData['previous']['youth_culture']['quantity'] ?? 0
                ];
                
                // Activity participation trend
                $trendLabels = [$previousYear, $selectedYear];
                $awarenessTrend = [
                    $reportData['previous']['culture_awareness']['quantity'] ?? 0,
                    $reportData['current']['culture_awareness']['quantity'] ?? 0
                ];
                $youthTrend = [
                    $reportData['previous']['youth_culture']['quantity'] ?? 0,
                    $reportData['current']['youth_culture']['quantity'] ?? 0
                ];
            @endphp

            <!-- Year Filter -->
            <div class="card card-outline card-purple mb-4">
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
                            <button type="submit" class="btn btn-purple btn-sm">
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
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{{ number_format($reportData['current']['total_participants'] ?? 0) }}</h3>
                            <p>{{ __('Total Participants') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        @if($participantChange != 0)
                            <small class="small-box-footer">
                                {{ $participantChange > 0 ? '↑' : '↓' }} 
                                {{ number_format(abs($participantChange), 1) }}% 
                                {{ __('from last year') }}
                            </small>
                        @endif
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ number_format($reportData['current']['culture_awareness']['quantity'] ?? 0) }}</h3>
                            <p>{{ __('Culture Awareness') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($reportData['current']['youth_culture']['quantity'] ?? 0) }}</h3>
                            <p>{{ __('Youth Cultural Activities') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-child"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ number_format($reportData['current']['total_activities'] ?? 0) }}</h3>
                            <p>{{ __('Cultural Activities') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row mb-4">
                <!-- Cultural Activities Distribution -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-purple text-white">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Cultural Activities Distribution') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($activityKeys) > 0)
                                <canvas id="culturalActivitiesChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-theater-masks fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No cultural activity data available') }}</h5>
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
                                {{ __('Activity Categories') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($reportData['current']['total_participants'] > 0)
                                <canvas id="categoryChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No category data available') }}</h5>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Year Comparison Charts -->
            <div class="row mb-4">
                <!-- Year-over-Year Comparison -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-1"></i>
                                {{ __('Year-over-Year Comparison') }}
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
                
                <!-- Activity Participation Trend -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Participation Trend') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="trendChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Report Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-table mr-1"></i>
                        {{ __('Detailed Cultural Report') }}
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
                                    <th colspan="8" class="text-center">{{ __('Cultural Activities Summary') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-center bg-purple text-white">{{ __('Culture Awareness') }}</th>
                                    <th colspan="4" class="text-center bg-success text-white">{{ __('Youth Cultural Activities') }}</th>
                                </tr>
                                <tr>
                                    <!-- Culture Awareness Sub-headers -->
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Unit') }}</th>
                                    <th>{{ __('Year') }}</th>
                                    
                                    <!-- Youth Cultural Activities Sub-headers -->
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Unit') }}</th>
                                    <th>{{ __('Year') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold">{{ $reportData['kebele_name'] }}</td>

                                    <!-- Culture Awareness Data -->
                                    <td>
                                        <small>{{ $reportData['current']['culture_awareness']['description'] ?? 'Haaromsa aadaa irratti ummata hubannoon kennameef' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <strong>{{ number_format($reportData['current']['culture_awareness']['quantity'] ?? 0) }}</strong><br>
                                        <small class="text-muted">{{ __('Previous:') }} {{ number_format($reportData['previous']['culture_awareness']['quantity'] ?? 0) }}</small>
                                    </td>
                                    <td class="text-center">
                                        {{ $reportData['current']['culture_awareness']['unit'] ?? 'Lakk' }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-purple">{{ $selectedYear }}</span><br>
                                        <span class="badge bg-secondary">{{ $previousYear }}</span>
                                    </td>
                                    
                                    <!-- Youth Cultural Activities Data -->
                                    <td>
                                        <small>{{ $reportData['current']['youth_culture']['description'] ?? 'Dargaggoota aadaa fi aartii keessatti gurmaa\'anii socho\'an' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <strong>{{ number_format($reportData['current']['youth_culture']['quantity'] ?? 0) }}</strong><br>
                                        <small class="text-muted">{{ __('Previous:') }} {{ number_format($reportData['previous']['youth_culture']['quantity'] ?? 0) }}</small>
                                    </td>
                                    <td class="text-center">
                                        {{ $reportData['current']['youth_culture']['unit'] ?? 'Lakk' }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $selectedYear }}</span><br>
                                        <span class="badge bg-secondary">{{ $previousYear }}</span>
                                    </td>
                                </tr>
                                
                                <!-- Total Row -->
                                <tr class="table-secondary">
                                    <td class="fw-bold">{{ __('TOTAL PARTICIPANTS') }}</td>
                                    <td colspan="3" class="text-center fw-bold">
                                        {{ number_format($reportData['current']['culture_awareness']['quantity'] ?? 0) }}<br>
                                        <small class="text-muted">{{ __('Previous:') }} {{ number_format($reportData['previous']['culture_awareness']['quantity'] ?? 0) }}</small>
                                    </td>
                                    <td colspan="4" class="text-center fw-bold">
                                        {{ number_format($reportData['current']['youth_culture']['quantity'] ?? 0) }}<br>
                                        <small class="text-muted">{{ __('Previous:') }} {{ number_format($reportData['previous']['youth_culture']['quantity'] ?? 0) }}</small>
                                    </td>
                                    <td class="text-center fw-bold">
                                        {{ number_format($reportData['current']['total_participants'] ?? 0) }}<br>
                                        <small class="text-muted">{{ __('Previous:') }} {{ number_format($reportData['previous']['total_participants'] ?? 0) }}</small>
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
                        <div class="card-header bg-purple text-white">
                            <h3 class="card-title">
                                <i class="fas fa-list-alt mr-1"></i>
                                {{ __('Cultural Activity Details') }}
                            </h3>
                            <div class="card-tools">
                                <ul class="nav nav-pills ml-auto">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#current-activities" data-toggle="tab">
                                            {{ $selectedYear }}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#previous-activities" data-toggle="tab">
                                            {{ $previousYear }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- Current Year Activities -->
                                <div class="tab-pane active" id="current-activities">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead>
                                                <tr class="bg-light">
                                                    <th>{{ __('#') }}</th>
                                                    <th>{{ __('Activity Name') }}</th>
                                                    <th class="text-right">{{ __('Participants') }}</th>
                                                    <th>{{ __('Unit') }}</th>
                                                    <th>{{ __('Category') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($activityData['current'] as $key => $quantity)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $key }}</td>
                                                        <td class="text-right">{{ number_format($quantity) }}</td>
                                                        <td>{{ $reportData['current']['culture_awareness']['unit'] ?? 'Lakk' }}</td>
                                                        <td>
                                                            @php
                                                                $category = strpos($key, 'Haaromsa aadaa') !== false ? 'Culture Awareness' : 'Youth Cultural Activities';
                                                            @endphp
                                                            <span class="badge 
                                                                {{ $category == 'Culture Awareness' ? 'bg-purple' : 'bg-success' }}">
                                                                {{ $category }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center py-3 text-muted">
                                                            {{ __('No cultural activity data available for') }} {{ $selectedYear }}
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                            @if(count($activityData['current']) > 0)
                                                <tfoot class="table-secondary">
                                                    <tr>
                                                        <td colspan="2" class="text-right"><strong>{{ __('Total:') }}</strong></td>
                                                        <td class="text-right"><strong>{{ number_format($reportData['current']['total_participants'] ?? 0) }}</strong></td>
                                                        <td colspan="2"></td>
                                                    </tr>
                                                </tfoot>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Previous Year Activities -->
                                <div class="tab-pane" id="previous-activities">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead>
                                                <tr class="bg-light">
                                                    <th>{{ __('#') }}</th>
                                                    <th>{{ __('Activity Name') }}</th>
                                                    <th class="text-right">{{ __('Participants') }}</th>
                                                    <th>{{ __('Unit') }}</th>
                                                    <th>{{ __('Category') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($activityData['previous'] as $key => $quantity)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $key }}</td>
                                                        <td class="text-right">{{ number_format($quantity) }}</td>
                                                        <td>{{ $reportData['previous']['culture_awareness']['unit'] ?? 'Lakk' }}</td>
                                                        <td>
                                                            @php
                                                                $category = strpos($key, 'Haaromsa aadaa') !== false ? 'Culture Awareness' : 'Youth Cultural Activities';
                                                            @endphp
                                                            <span class="badge 
                                                                {{ $category == 'Culture Awareness' ? 'bg-purple' : 'bg-success' }}">
                                                                {{ $category }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center py-3 text-muted">
                                                            {{ __('No cultural activity data available for') }} {{ $previousYear }}
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                            @if(count($activityData['previous']) > 0)
                                                <tfoot class="table-secondary">
                                                    <tr>
                                                        <td colspan="2" class="text-right"><strong>{{ __('Total:') }}</strong></td>
                                                        <td class="text-right"><strong>{{ number_format($reportData['previous']['total_participants'] ?? 0) }}</strong></td>
                                                        <td colspan="2"></td>
                                                    </tr>
                                                </tfoot>
                                            @endif
                                        </table>
                                    </div>
                                </div>
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
            console.log('Initializing cultural charts...');
            
            // Cultural Activities Chart
            const culturalActivitiesCtx = document.getElementById('culturalActivitiesChart');
            if (culturalActivitiesCtx) {
                console.log('Creating cultural activities chart');
                try {
                    new Chart(culturalActivitiesCtx, {
                        type: 'bar',
                        data: {
                            labels: @json($activityKeys),
                            datasets: [{
                                label: '{{ __("Number of Participants") }}',
                                data: @json($activityAmounts),
                                backgroundColor: 'rgba(111, 66, 193, 0.7)',
                                borderColor: 'rgba(111, 66, 193, 1)',
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
                                        text: '{{ __("Number of Participants") }}'
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
                    console.error('Error creating cultural activities chart:', error);
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
                                    'rgba(111, 66, 193, 0.7)',
                                    'rgba(40, 167, 69, 0.7)'
                                ],
                                borderColor: [
                                    'rgba(111, 66, 193, 1)',
                                    'rgba(40, 167, 69, 1)'
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

            // Year Comparison Chart
            const yearComparisonCtx = document.getElementById('yearComparisonChart');
            if (yearComparisonCtx) {
                console.log('Creating year comparison chart');
                try {
                    new Chart(yearComparisonCtx, {
                        type: 'bar',
                        data: {
                            labels: @json($categoryLabels),
                            datasets: [
                                {
                                    label: '{{ $selectedYear }}',
                                    data: @json($currentCategoryData),
                                    backgroundColor: 'rgba(111, 66, 193, 0.7)',
                                    borderColor: 'rgba(111, 66, 193, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: '{{ $previousYear }}',
                                    data: @json($previousCategoryData),
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
                                        text: '{{ __("Number of Participants") }}'
                                    }
                                }
                            }
                        }
                    });
                } catch (error) {
                    console.error('Error creating year comparison chart:', error);
                }
            }

            // Trend Chart
            const trendCtx = document.getElementById('trendChart');
            if (trendCtx) {
                console.log('Creating trend chart');
                try {
                    new Chart(trendCtx, {
                        type: 'line',
                        data: {
                            labels: @json($trendLabels),
                            datasets: [
                                {
                                    label: '{{ __("Culture Awareness") }}',
                                    data: @json($awarenessTrend),
                                    borderColor: 'rgba(111, 66, 193, 1)',
                                    backgroundColor: 'rgba(111, 66, 193, 0.1)',
                                    borderWidth: 2,
                                    tension: 0.4,
                                    fill: true
                                },
                                {
                                    label: '{{ __("Youth Cultural Activities") }}',
                                    data: @json($youthTrend),
                                    borderColor: 'rgba(40, 167, 69, 1)',
                                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                                    borderWidth: 2,
                                    tension: 0.4,
                                    fill: true
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
                                        text: '{{ __("Number of Participants") }}'
                                    }
                                }
                            }
                        }
                    });
                } catch (error) {
                    console.error('Error creating trend chart:', error);
                }
            }
            
            console.log('All cultural charts initialized');
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
    .bg-purple {
        background-color: #6f42c1 !important;
    }
    .btn-purple {
        background-color: #6f42c1;
        border-color: #6f42c1;
        color: white;
    }
    .btn-purple:hover {
        background-color: #5a32a3;
        border-color: #5a32a3;
    }
    .nav-pills .nav-link.active {
        background-color: #6f42c1;
    }
    .nav-pills .nav-link {
        color: #6f42c1;
    }
</style>

</x-layout>