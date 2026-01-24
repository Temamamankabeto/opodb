<x-layout>
<div class="content-header">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <div class="bg-white border rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:60px;height:60px;">
                        <i class="fas fa-handshake fa-lg text-warning"></i>
                    </div>
                    <div>
                        <h1 class="m-0 text-dark">{{ __('Kebele Level Association Expansion Report') }}</h1>
                        <small class="text-dark">{{ __('Association growth and development analysis') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @php
                // Prepare association data for charts
                $associationTypes = array_keys($associationData['current'] ?? []);
                $associationCounts = [];
                $associationMembers = [];
                $associationCapitals = [];
                $associationLoans = [];
                $associationRepayments = [];
                
                foreach ($associationTypes as $type) {
                    $associationCounts[] = $associationData['current'][$type]['associations'] ?? 0;
                    $associationMembers[] = $associationData['current'][$type]['members'] ?? 0;
                    $associationCapitals[] = $associationData['current'][$type]['capital'] ?? 0;
                    $associationLoans[] = $associationData['current'][$type]['loans'] ?? 0;
                    $associationRepayments[] = $associationData['current'][$type]['repayments'] ?? 0;
                }
                
                // Previous year data
                $previousAssociationCounts = [];
                $previousAssociationMembers = [];
                $previousAssociationCapitals = [];
                
                foreach ($associationTypes as $type) {
                    $previousAssociationCounts[] = $associationData['previous'][$type]['associations'] ?? 0;
                    $previousAssociationMembers[] = $associationData['previous'][$type]['members'] ?? 0;
                    $previousAssociationCapitals[] = $associationData['previous'][$type]['capital'] ?? 0;
                }
                
                // Summary data from reportData
                $currentTotalAssociations = 0;
                $currentTotalMembers = 0;
                $currentTotalCapital = 0;
                $currentTotalLoans = 0;
                $currentTotalRepayments = 0;
                $previousTotalAssociations = 0;
                $previousTotalMembers = 0;
                $previousTotalCapital = 0;
                
                foreach ($reportData as $data) {
                    $currentTotalAssociations += $data['current']['total_associations'] ?? 0;
                    $currentTotalMembers += $data['current']['total_members'] ?? 0;
                    $currentTotalCapital += $data['current']['total_capital'] ?? 0;
                    $currentTotalLoans += $data['current']['total_loans_taken'] ?? 0;
                    $currentTotalRepayments += $data['current']['total_loan_repayment'] ?? 0;
                    $previousTotalAssociations += $data['previous']['total_associations'] ?? 0;
                    $previousTotalMembers += $data['previous']['total_members'] ?? 0;
                    $previousTotalCapital += $data['previous']['total_capital'] ?? 0;
                }
                
                $totalAssociationsChange = $previousTotalAssociations > 0 ? 
                    (($currentTotalAssociations - $previousTotalAssociations) / $previousTotalAssociations) * 100 : 0;
                $totalMembersChange = $previousTotalMembers > 0 ? 
                    (($currentTotalMembers - $previousTotalMembers) / $previousTotalMembers) * 100 : 0;
                $totalCapitalChange = $previousTotalCapital > 0 ? 
                    (($currentTotalCapital - $previousTotalCapital) / $previousTotalCapital) * 100 : 0;
            @endphp

            <!-- Year Filter -->
            <div class="card card-outline card-warning mb-4">
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
                            <button type="submit" class="btn btn-warning btn-sm">
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
                            <h3>{{ number_format($currentTotalAssociations) }}</h3>
                            <p>{{ __('Total Associations') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($currentTotalMembers) }}</h3>
                            <p>{{ __('Total Members') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ number_format($currentTotalCapital) }}</h3>
                            <p>{{ __('Total Capital') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ count($associationTypes) }}</h3>
                            <p>{{ __('Association Types') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-list-ol"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row mb-4">
                <!-- Association Type Distribution Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-warning text-white">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Association Type Distribution') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($associationTypes) > 0)
                                <canvas id="associationTypeChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-handshake fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No association data available') }}</h5>
                                    <p class="text-muted">{{ __('Try selecting a different year') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Association Members Distribution Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Association Members Distribution') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($associationTypes) > 0)
                                <canvas id="associationMembersChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No members data available') }}</h5>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Year Comparison Charts -->
            <div class="row mb-4">
                <!-- Association Growth Comparison Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Association Growth Comparison') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="growthComparisonChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Top Associations Pie Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                {{ __('Top Associations by Type') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($associationTypes) > 0)
                                <canvas id="topAssociationsChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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

            <!-- Main Report Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-table mr-1"></i>
                        {{ __('Detailed Association Expansion Report') }}
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
                                    <th colspan="6" class="text-center">{{ $selectedYear }} ({{ __('Current Year') }})</th>
                                    <th colspan="6" class="text-center">{{ $previousYear }} ({{ __('Previous Year') }})</th>
                                </tr>
                                <tr>
                                    <!-- Current Year Headers -->
                                    <th>{{ __('Associations') }}</th>
                                    <th>{{ __('Members') }}</th>
                                    <th>{{ __('Capital') }}</th>
                                    <th>{{ __('Loans Taken') }}</th>
                                    <th>{{ __('Loan Repayment') }}</th>
                                    <th>{{ __('Types') }}</th>
                                    
                                    <!-- Previous Year Headers -->
                                    <th>{{ __('Associations') }}</th>
                                    <th>{{ __('Members') }}</th>
                                    <th>{{ __('Capital') }}</th>
                                    <th>{{ __('Loans Taken') }}</th>
                                    <th>{{ __('Loan Repayment') }}</th>
                                    <th>{{ __('Types') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reportData as $data)
                                    <tr>
                                        <td class="fw-bold">{{ $data['kebele_name'] }}</td>

                                        <!-- Current Year Data -->
                                        <td class="text-center">{{ number_format($data['current']['total_associations'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['total_members'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['total_capital'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['total_loans_taken'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['total_loan_repayment'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['types'] ?? 0) }}</td>

                                        <!-- Previous Year Data -->
                                        <td class="text-center">{{ number_format($data['previous']['total_associations'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['previous']['total_members'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['previous']['total_capital'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['previous']['total_loans_taken'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['previous']['total_loan_repayment'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['previous']['types'] ?? 0) }}</td>
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
                                    
                                    <!-- Current Year Totals -->
                                    <td class="text-center fw-bold">{{ number_format($currentTotalAssociations) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentTotalMembers) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentTotalCapital) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentTotalLoans) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentTotalRepayments) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'current'), 'types')) ?? 0) }}</td>
                                    
                                    <!-- Previous Year Totals -->
                                    <td class="text-center fw-bold">{{ number_format($previousTotalAssociations) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($previousTotalMembers) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($previousTotalCapital) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'previous'), 'total_loans_taken')) ?? 0) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'previous'), 'total_loan_repayment')) ?? 0) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'previous'), 'types')) ?? 0) }}</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- Association Details Table -->
            @if(count($associationData['current']) > 0)
            <div class="card mt-4">
                <div class="card-header bg-warning text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-list mr-1"></i>
                        {{ __('Association Details by Type') }} ({{ $selectedYear }})
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Association Type') }}</th>
                                    <th class="text-right">{{ __('Associations') }}</th>
                                    <th class="text-right">{{ __('Members') }}</th>
                                    <th class="text-right">{{ __('Capital') }}</th>
                                    <th class="text-right">{{ __('Loans Taken') }}</th>
                                    <th class="text-right">{{ __('Loan Repayment') }}</th>
                                    <th class="text-right">{{ __('Change %') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($associationTypes as $index => $type)
                                    @php
                                        $currentAssociations = $associationData['current'][$type]['associations'] ?? 0;
                                        $currentMembers = $associationData['current'][$type]['members'] ?? 0;
                                        $currentCapital = $associationData['current'][$type]['capital'] ?? 0;
                                        $currentLoans = $associationData['current'][$type]['loans'] ?? 0;
                                        $currentRepayments = $associationData['current'][$type]['repayments'] ?? 0;
                                        
                                        $previousAssociations = $associationData['previous'][$type]['associations'] ?? 0;
                                        $previousMembers = $associationData['previous'][$type]['members'] ?? 0;
                                        $previousCapital = $associationData['previous'][$type]['capital'] ?? 0;
                                        
                                        $associationChange = $previousAssociations > 0 ? 
                                            (($currentAssociations - $previousAssociations) / $previousAssociations) * 100 : 
                                            ($currentAssociations > 0 ? 100 : 0);
                                        
                                        $associationPercent = $currentTotalAssociations > 0 ? 
                                            ($currentAssociations / $currentTotalAssociations) * 100 : 0;
                                        $membersPercent = $currentTotalMembers > 0 ? 
                                            ($currentMembers / $currentTotalMembers) * 100 : 0;
                                        $capitalPercent = $currentTotalCapital > 0 ? 
                                            ($currentCapital / $currentTotalCapital) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $type }}</td>
                                        <td class="text-right">
                                            {{ number_format($currentAssociations) }}
                                            <small class="text-muted">({{ number_format($associationPercent, 1) }}%)</small>
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($currentMembers) }}
                                            <small class="text-muted">({{ number_format($membersPercent, 1) }}%)</small>
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($currentCapital) }}
                                            <small class="text-muted">({{ number_format($capitalPercent, 1) }}%)</small>
                                        </td>
                                        <td class="text-right">{{ number_format($currentLoans) }}</td>
                                        <td class="text-right">{{ number_format($currentRepayments) }}</td>
                                        <td class="text-right {{ $associationChange >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $associationChange >= 0 ? '+' : '' }}{{ number_format($associationChange, 1) }}%
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="2">{{ __('TOTAL') }}</td>
                                    <td class="text-right">{{ number_format($currentTotalAssociations) }}</td>
                                    <td class="text-right">{{ number_format($currentTotalMembers) }}</td>
                                    <td class="text-right">{{ number_format($currentTotalCapital) }}</td>
                                    <td class="text-right">{{ number_format($currentTotalLoans) }}</td>
                                    <td class="text-right">{{ number_format($currentTotalRepayments) }}</td>
                                    <td class="text-right {{ $totalAssociationsChange >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $totalAssociationsChange >= 0 ? '+' : '' }}{{ number_format($totalAssociationsChange, 1) }}%
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
    
    // Summary data
    const currentTotalAssociations = @json($currentTotalAssociations);
    const currentTotalMembers = @json($currentTotalMembers);
    const currentTotalCapital = @json($currentTotalCapital);
    const previousTotalAssociations = @json($previousTotalAssociations);
    const previousTotalMembers = @json($previousTotalMembers);
    const previousTotalCapital = @json($previousTotalCapital);
    
    // Association data
    const associationTypes = @json($associationTypes);
    const associationCounts = @json($associationCounts);
    const associationMembers = @json($associationMembers);
    const associationCapitals = @json($associationCapitals);
    const previousAssociationCounts = @json($previousAssociationCounts);
    const previousAssociationMembers = @json($previousAssociationMembers);
    const previousAssociationCapitals = @json($previousAssociationCapitals);

    // Color palette for association types
    const associationColors = [
        'rgba(255, 193, 7, 0.7)',    // Yellow
        'rgba(40, 167, 69, 0.7)',    // Green
        'rgba(0, 123, 255, 0.7)',    // Blue
        'rgba(23, 162, 184, 0.7)',   // Teal
        'rgba(108, 117, 125, 0.7)',  // Gray
        'rgba(220, 53, 69, 0.7)',    // Red
        'rgba(111, 66, 193, 0.7)',   // Purple
        'rgba(253, 126, 20, 0.7)',   // Orange
        'rgba(32, 201, 151, 0.7)',   // Mint
        'rgba(233, 30, 99, 0.7)',    // Pink
    ];

    // Chart 1: Association Type Distribution
    if (associationTypes.length > 0) {
        const associationTypeCtx = document.getElementById('associationTypeChart').getContext('2d');
        new Chart(associationTypeCtx, {
            type: 'bar',
            data: {
                labels: associationTypes,
                datasets: [{
                    label: `{{ __('Number of Associations') }} (${selectedYear})`,
                    data: associationCounts,
                    backgroundColor: 'rgba(255, 193, 7, 0.7)',
                    borderColor: 'rgba(255, 193, 7, 1)',
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
                            text: '{{ __('Number of Associations') }}'
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
                                const total = associationCounts.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed.y / total) * 100).toFixed(1) : 0;
                                return `${context.dataset.label}: ${context.parsed.y.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Chart 2: Association Members Distribution
    if (associationTypes.length > 0) {
        const associationMembersCtx = document.getElementById('associationMembersChart').getContext('2d');
        new Chart(associationMembersCtx, {
            type: 'bar',
            data: {
                labels: associationTypes,
                datasets: [{
                    label: `{{ __('Number of Members') }} (${selectedYear})`,
                    data: associationMembers,
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
                            text: '{{ __('Number of Members') }}'
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
                                const total = associationMembers.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed.y / total) * 100).toFixed(1) : 0;
                                return `${context.dataset.label}: ${context.parsed.y.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Chart 3: Growth Comparison Chart
    const growthComparisonCtx = document.getElementById('growthComparisonChart').getContext('2d');
    new Chart(growthComparisonCtx, {
        type: 'bar',
        data: {
            labels: ['{{ __('Associations') }}', '{{ __('Members') }}', '{{ __('Capital') }}'],
            datasets: [
                {
                    label: `{{ __('Current Year') }} (${selectedYear})`,
                    data: [currentTotalAssociations, currentTotalMembers, currentTotalCapital],
                    backgroundColor: ['rgba(255, 193, 7, 0.7)', 'rgba(40, 167, 69, 0.7)', 'rgba(0, 123, 255, 0.7)'],
                    borderColor: ['rgba(255, 193, 7, 1)', 'rgba(40, 167, 69, 1)', 'rgba(0, 123, 255, 1)'],
                    borderWidth: 1
                },
                {
                    label: `{{ __('Previous Year') }} (${previousYear})`,
                    data: [previousTotalAssociations, previousTotalMembers, previousTotalCapital],
                    backgroundColor: ['rgba(255, 205, 86, 0.7)', 'rgba(75, 192, 192, 0.7)', 'rgba(201, 203, 207, 0.7)'],
                    borderColor: ['rgba(255, 205, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(201, 203, 207, 1)'],
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

    // Chart 4: Top Associations Pie Chart
    if (associationTypes.length > 0) {
        // Take top associations for pie chart (max 6)
        const topCount = Math.min(associationTypes.length, 6);
        const topLabels = associationTypes.slice(0, topCount);
        const topData = associationCounts.slice(0, topCount);
        
        // If there are more types, group the rest as "Others"
        if (associationTypes.length > topCount) {
            const othersSum = associationCounts.slice(topCount).reduce((a, b) => a + b, 0);
            if (othersSum > 0) {
                topLabels.push('{{ __('Others') }}');
                topData.push(othersSum);
            }
        }

        const topAssociationsCtx = document.getElementById('topAssociationsChart').getContext('2d');
        new Chart(topAssociationsCtx, {
            type: 'pie',
            data: {
                labels: topLabels,
                datasets: [{
                    data: topData,
                    backgroundColor: associationColors.slice(0, topLabels.length),
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
    .bg-success { background-color: #28a745 !important; }
    .bg-warning { background-color: #ffc107 !important; }
    .bg-primary { background-color: #007bff !important; }
    
    .card-header.bg-warning .card-tools .btn-tool,
    .card-header.bg-success .card-tools .btn-tool {
        color: rgba(255,255,255,0.8) !important;
    }
    
    .card-header.bg-warning .card-tools .btn-tool:hover,
    .card-header.bg-success .card-tools .btn-tool:hover {
        color: white !important;
    }
    
    table th, table td {
        vertical-align: middle !important;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(255, 193, 7, 0.05);
    }
</style>

</x-layout>