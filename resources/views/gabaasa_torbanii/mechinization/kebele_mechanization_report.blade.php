<x-layout>
<div class="content-header">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <div class="bg-white border rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:60px;height:60px;">
                        <i class="fas fa-cogs fa-lg text-dark"></i>
                    </div>
                    <div>
                        <h1 class="m-0 text-dark">{{ __('Kebele Mechanization Report') }}</h1>
                        <small class="text-dark">
                            {{ __('Mechanization analysis for') }} <strong>{{ $kebeleName }}</strong>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            @php
                // Calculate loan summary from current year data
                $loanSummary = [];
                $totalLoans = 0;
                foreach($currentYearData as $row) {
                    $loanDetails = json_decode($row->loan_details, true) ?? [];
                    foreach($loanDetails as $loan) {
                        $loanType = $loan['loan_type'] ?? 'N/A';
                        $loanAmount = floatval($loan['loan_amount'] ?? 0);
                        $totalLoans += $loanAmount;
                        if(!isset($loanSummary[$loanType])) {
                            $loanSummary[$loanType] = 0;
                        }
                        $loanSummary[$loanType] += $loanAmount;
                    }
                }
                
                // Group data for charts
                $groupedData = $currentYearData->groupBy('mechanization_type');
                $mechanizationTypes = [];
                $mechanizationAmounts = [];
                $comparisonLabels = [];
                $currentYearDataArray = [];
                $previousYearDataArray = [];
                
                foreach ($groupedData as $typeName => $typeRows) {
                    $mechanizationTypes[] = $typeName;
                    $mechanizationAmounts[] = $typeRows->sum('amount');
                    $comparisonLabels[] = $typeName;
                    $currentYearDataArray[] = $typeRows->sum('amount');
                    
                    // Calculate previous year totals for this type
                    $prevTotal = 0;
                    foreach ($typeRows as $row) {
                        $prevKey = $row->mechanization_type.'_'.$row->name;
                        $prevData = $previousYearData[$prevKey] ?? null;
                        $prevTotal += $prevData->amount ?? 0;
                    }
                    $previousYearDataArray[] = $prevTotal;
                }
                
                // Data for Loan Distribution Chart
                $loanTypes = array_keys($loanSummary);
                $loanAmounts = array_values($loanSummary);
            @endphp

            <!-- Year Filter -->
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('kebele-mechanization-report.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="text-dark">{{ __('Year') }}</label>
                                <select id="year" name="year" class="form-control form-control-sm border">
                                    <option value="">{{ __('Select Year') }}</option>
                                    @foreach($years as $y)
                                        <option value="{{ $y }}" {{ request('year')==$y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="text-right mt-2">
                            <button type="submit" class="btn btn-dark btn-sm"><i class="fas fa-filter mr-1"></i> {{ __('Apply Filter') }}</button>
                            <a href="{{ route('kebele-mechanization-report.index') }}" class="btn btn-light btn-sm border"><i class="fas fa-redo mr-1"></i> {{ __('Reset') }}</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ count($currentYearData) }}</h3>
                            <p>{{ __('Total Mechanizations') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $year }}</h3>
                            <p>{{ __('Current Year') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $prevYear }}</h3>
                            <p>{{ __('Previous Year') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-history"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ number_format($totalLoans) }}</h3>
                            <p>{{ __('Total Loans (ETB)') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row mb-4">
                <!-- Mechanization by Type Chart -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Mechanization Distribution by Type') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($mechanizationTypes) > 0)
                                <canvas id="mechanizationChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No mechanization data available') }}</h5>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Loan Distribution Chart -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                {{ __('Loan Distribution by Type') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($loanTypes) > 0)
                                <canvas id="loanChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No loan data available') }}</h5>
                                </div>
                            @endif
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
                                {{ __('Year-over-Year Comparison') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($comparisonLabels) > 0)
                                <canvas id="comparisonChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('Mechanization Type') }}</th>
                            <th>{{ __('Mechanization Name') }}</th>
                            <th colspan="2" class="text-center">{{ __('Current Year') }} ({{ $year }})</th>
                            <th colspan="2" class="text-center">{{ __('Previous Year') }} ({{ $prevYear }})</th>
                            <th>{{ __('Change %') }}</th>
                            <th>{{ __('Loans') }}</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="text-center">{{ __('Amount') }}</th>
                            <th class="text-center">{{ __('Unit') }}</th>
                            <th class="text-center">{{ __('Amount') }}</th>
                            <th class="text-center">{{ __('Unit') }}</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                            // Re-group data for table (in case it was modified)
                            $groupedData = $currentYearData->groupBy('mechanization_type');
                        @endphp

                        @foreach($groupedData as $typeName => $typeRows)
                            @php
                                $totalCurrent = $typeRows->sum('amount');
                                $totalPrev = 0;
                                $totalChange = 0;
                            @endphp

                            @foreach($typeRows->groupBy('name') as $mechanName => $mechRows)
                                @php
                                    $row = $mechRows->first();
                                    $prevKey = $row->mechanization_type.'_'.$row->name;
                                    $prevData = $previousYearData[$prevKey] ?? null;
                                    $prevAmount = $prevData->amount ?? 0;
                                    $prevUnit = $prevData->unit ?? '';
                                    $prodChange = $prevAmount > 0 ? (($row->amount - $prevAmount)/$prevAmount)*100 : 0;

                                    $totalPrev += $prevAmount;
                                    $totalChange += $prodChange;
                                @endphp

                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $row->mechanization_type ?? 'N/A' }}</td>
                                    <td>{{ $row->name }}</td>
                                    <td class="text-center">{{ number_format($row->amount,2) }}</td>
                                    <td class="text-center">{{ $row->unit ?? '' }}</td>
                                    <td class="text-center">{{ number_format($prevAmount,2) }}</td>
                                    <td class="text-center">{{ $prevUnit }}</td>
                                    <td class="text-center">{{ number_format($prodChange,1) }}%</td>
                                    <td class="text-center">
                                        @php
                                            $loanDetails = json_decode($row->loan_details,true) ?? [];
                                        @endphp
                                        @if(count($loanDetails))
                                            <button class="btn btn-sm btn-info" type="button" data-toggle="collapse" data-target="#loan-{{ $loop->parent->index }}-{{ $loop->index }}" aria-expanded="false">
                                                {{ __('View Loans') }}
                                            </button>
                                        @endif
                                    </td>
                                </tr>

                                @if(count($loanDetails))
                                <tr class="collapse bg-light" id="loan-{{ $loop->parent->index }}-{{ $loop->index }}">
                                    <td colspan="9">
                                        <ul class="mb-0">
                                            @foreach($loanDetails as $loan)
                                                <li>
                                                    {{ $loan['loan_type'] ?? 'N/A' }}, 
                                                    {{ $loan['loan_amount'] ?? 0 }} {{ __('ETB') }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                @endif

                            @endforeach

                            <!-- Total Row for Mechanization Type -->
                            <tr class="font-weight-bold bg-light">
                                <td colspan="3" class="text-center">{{ __('Total for') }} {{ $typeName }}</td>
                                <td class="text-center">{{ number_format($totalCurrent,2) }}</td>
                                <td></td>
                                <td class="text-center">{{ number_format($totalPrev,2) }}</td>
                                <td></td>
                                <td class="text-center">{{ number_format($totalChange,1) }}%</td>
                                <td></td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Loan Summary -->
            <div class="card mt-3">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">{{ __('Loan Summary by Loan Type') }}</h5>
                </div>
                <div class="card-body">
                    @if(count($loanSummary))
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>{{ __('Loan Type') }}</th>
                                    <th>{{ __('Total Amount (ETB)') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loanSummary as $type => $amount)
                                    <tr>
                                        <td>{{ $type }}</td>
                                        <td class="text-right">{{ number_format($amount,2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>{{ __('No loan data available for this year.') }}</p>
                    @endif
                </div>
            </div>

        </div>
    </section>
</div>

<!-- Add Chart.js -->
<script src="{{ asset('vendor/adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Prepare data for charts from PHP variables
    const mechanizationTypes = @json($mechanizationTypes);
    const mechanizationAmounts = @json($mechanizationAmounts);
    const loanTypes = @json($loanTypes);
    const loanAmounts = @json($loanAmounts);
    const comparisonLabels = @json($comparisonLabels);
    const currentYearDataChart = @json($currentYearDataArray);
    const previousYearDataChart = @json($previousYearDataArray);

    // Color palettes
    const barColors = [
        'rgba(54, 162, 235, 0.7)',
        'rgba(255, 99, 132, 0.7)',
        'rgba(75, 192, 192, 0.7)',
        'rgba(255, 159, 64, 0.7)',
        'rgba(153, 102, 255, 0.7)',
        'rgba(255, 205, 86, 0.7)',
        'rgba(201, 203, 207, 0.7)',
    ];

    const pieColors = [
        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
        '#9966FF', '#FF9F40', '#8AC926', '#1982C4',
        '#6A4C93', '#F15BB5'
    ];

    // Chart 1: Mechanization Distribution by Type
    if (mechanizationTypes.length > 0 && document.getElementById('mechanizationChart')) {
        const mechanizationCtx = document.getElementById('mechanizationChart').getContext('2d');
        new Chart(mechanizationCtx, {
            type: 'bar',
            data: {
                labels: mechanizationTypes,
                datasets: [{
                    label: '{{ __('Amount') }}',
                    data: mechanizationAmounts,
                    backgroundColor: barColors.slice(0, mechanizationTypes.length),
                    borderColor: barColors.slice(0, mechanizationTypes.length).map(color => color.replace('0.7', '1')),
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
                            text: '{{ __('Mechanization Type') }}'
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

    // Chart 2: Loan Distribution by Type
    if (loanTypes.length > 0 && document.getElementById('loanChart')) {
        const loanCtx = document.getElementById('loanChart').getContext('2d');
        new Chart(loanCtx, {
            type: 'pie',
            data: {
                labels: loanTypes,
                datasets: [{
                    data: loanAmounts,
                    backgroundColor: pieColors.slice(0, loanTypes.length),
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
                                return label + ': ' + value.toLocaleString() + ' ETB (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // Chart 3: Year-over-Year Comparison
    if (comparisonLabels.length > 0 && document.getElementById('comparisonChart')) {
        const comparisonCtx = document.getElementById('comparisonChart').getContext('2d');
        new Chart(comparisonCtx, {
            type: 'bar',
            data: {
                labels: comparisonLabels,
                datasets: [
                    {
                        label: '{{ __('Current Year') }} ({{ $year }})',
                        data: currentYearDataChart,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '{{ __('Previous Year') }} ({{ $prevYear }})',
                        data: previousYearDataChart,
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
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
                            text: '{{ __('Mechanization Type') }}'
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