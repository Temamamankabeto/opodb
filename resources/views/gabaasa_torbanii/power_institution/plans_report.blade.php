<x-layout>
<div class="content-header">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <div class="bg-white border rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:60px;height:60px;">
                        <i class="fas fa-bolt fa-lg text-danger"></i>
                    </div>
                    <div>
                        <h1 class="m-0 text-dark">{{ __('Kebele Level Power Institution Report') }}</h1>
                        <small class="text-dark">{{ __('Power infrastructure construction and improvement analysis') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @php
                // Prepare power data for charts
                $newPowerKeys = array_keys($newPowerData['current'] ?? []);
                $newPowerPublicCosts = [];
                $newPowerPrivateCosts = [];
                $newPowerTotalCosts = [];
                
                foreach ($newPowerKeys as $key) {
                    $publicCost = $newPowerData['current'][$key]['public_cost'] ?? 0;
                    $privateCost = $newPowerData['current'][$key]['private_cost'] ?? 0;
                    $newPowerPublicCosts[] = $publicCost;
                    $newPowerPrivateCosts[] = $privateCost;
                    $newPowerTotalCosts[] = $publicCost + $privateCost;
                }
                
                // Improvement data
                $improvementPowerKeys = array_keys($improvementPowerData['current'] ?? []);
                $improvementPublicCosts = [];
                $improvementPrivateCosts = [];
                $improvementTotalCosts = [];
                
                foreach ($improvementPowerKeys as $key) {
                    $publicCost = $improvementPowerData['current'][$key]['public_cost'] ?? 0;
                    $privateCost = $improvementPowerData['current'][$key]['private_cost'] ?? 0;
                    $improvementPublicCosts[] = $publicCost;
                    $improvementPrivateCosts[] = $privateCost;
                    $improvementTotalCosts[] = $publicCost + $privateCost;
                }
                
                // Summary data from reportData
                $currentNewPublicCount = 0;
                $currentNewPrivateCount = 0;
                $currentNewPublicCost = 0;
                $currentNewPrivateCost = 0;
                $currentNewTotalCount = 0;
                $currentNewTotalCost = 0;
                
                $currentImprovePublicCount = 0;
                $currentImprovePrivateCount = 0;
                $currentImprovePublicCost = 0;
                $currentImprovePrivateCost = 0;
                $currentImproveTotalCount = 0;
                $currentImproveTotalCost = 0;
                
                $previousNewPublicCount = 0;
                $previousNewPrivateCount = 0;
                $previousNewPublicCost = 0;
                $previousNewPrivateCost = 0;
                $previousNewTotalCount = 0;
                $previousNewTotalCost = 0;
                
                $previousImprovePublicCount = 0;
                $previousImprovePrivateCount = 0;
                $previousImprovePublicCost = 0;
                $previousImprovePrivateCost = 0;
                $previousImproveTotalCount = 0;
                $previousImproveTotalCost = 0;
                
                foreach ($reportData as $data) {
                    // Current year new power
                    $currentNewPublicCount += $data['current']['new_power']['public_count'] ?? 0;
                    $currentNewPrivateCount += $data['current']['new_power']['private_count'] ?? 0;
                    $currentNewPublicCost += $data['current']['new_power']['public_cost'] ?? 0;
                    $currentNewPrivateCost += $data['current']['new_power']['private_cost'] ?? 0;
                    $currentNewTotalCount += $data['current']['new_power']['total_count'] ?? 0;
                    $currentNewTotalCost += $data['current']['new_power']['total_cost'] ?? 0;
                    
                    // Current year improvements
                    $currentImprovePublicCount += $data['current']['improvements']['public_count'] ?? 0;
                    $currentImprovePrivateCount += $data['current']['improvements']['private_count'] ?? 0;
                    $currentImprovePublicCost += $data['current']['improvements']['public_cost'] ?? 0;
                    $currentImprovePrivateCost += $data['current']['improvements']['private_cost'] ?? 0;
                    $currentImproveTotalCount += $data['current']['improvements']['total_count'] ?? 0;
                    $currentImproveTotalCost += $data['current']['improvements']['total_cost'] ?? 0;
                    
                    // Previous year new power
                    $previousNewPublicCount += $data['previous']['new_power']['public_count'] ?? 0;
                    $previousNewPrivateCount += $data['previous']['new_power']['private_count'] ?? 0;
                    $previousNewPublicCost += $data['previous']['new_power']['public_cost'] ?? 0;
                    $previousNewPrivateCost += $data['previous']['new_power']['private_cost'] ?? 0;
                    $previousNewTotalCount += $data['previous']['new_power']['total_count'] ?? 0;
                    $previousNewTotalCost += $data['previous']['new_power']['total_cost'] ?? 0;
                    
                    // Previous year improvements
                    $previousImprovePublicCount += $data['previous']['improvements']['public_count'] ?? 0;
                    $previousImprovePrivateCount += $data['previous']['improvements']['private_count'] ?? 0;
                    $previousImprovePublicCost += $data['previous']['improvements']['public_cost'] ?? 0;
                    $previousImprovePrivateCost += $data['previous']['improvements']['private_cost'] ?? 0;
                    $previousImproveTotalCount += $data['previous']['improvements']['total_count'] ?? 0;
                    $previousImproveTotalCost += $data['previous']['improvements']['total_cost'] ?? 0;
                }
                
                $totalCurrentCount = $currentNewTotalCount + $currentImproveTotalCount;
                $totalCurrentCost = $currentNewTotalCost + $currentImproveTotalCost;
                $totalPreviousCount = $previousNewTotalCount + $previousImproveTotalCount;
                $totalPreviousCost = $previousNewTotalCost + $previousImproveTotalCost;
                
                $totalCountChange = $totalPreviousCount > 0 ? 
                    (($totalCurrentCount - $totalPreviousCount) / $totalPreviousCount) * 100 : 0;
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
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ number_format($totalCurrentCount) }}</h3>
                            <p>{{ __('Total Power Projects') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ number_format($currentNewTotalCount) }}</h3>
                            <p>{{ __('New Power Projects') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-plus-square"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($currentImproveTotalCount) }}</h3>
                            <p>{{ __('Power Improvements') }}</p>
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
                            <p>{{ __('Total Investment') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row mb-4">
                <!-- New Power Projects Cost Distribution -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-danger text-white">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('New Power Projects Investment') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($newPowerKeys) > 0)
                                <canvas id="newPowerChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-plus-square fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No new power project data available') }}</h5>
                                    <p class="text-muted">{{ __('Try selecting a different year') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Power Improvements Cost Distribution -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Power Improvements Investment') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($improvementPowerKeys) > 0)
                                <canvas id="improvementsPowerChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No power improvement data available') }}</h5>
                                    <p class="text-muted">{{ __('Try selecting a different year') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Year Comparison Charts -->
            <div class="row mb-4">
                <!-- Project Count Comparison Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Project Count Comparison') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="countComparisonChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Investment Distribution Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                {{ __('Investment Distribution') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($currentNewTotalCost > 0 || $currentImproveTotalCost > 0)
                                <canvas id="investmentDistributionChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No investment data available') }}</h5>
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
                        {{ __('Detailed Power Institution Report') }}
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
                                    <th colspan="8" class="text-center">{{ $selectedYear }} ({{ __('New Power Projects') }})</th>
                                    <th colspan="8" class="text-center">{{ $selectedYear }} ({{ __('Power Improvements') }})</th>
                                </tr>
                                <tr>
                                    <!-- New Power Projects Headers -->
                                    <th colspan="2">{{ __('Public') }}</th>
                                    <th colspan="2">{{ __('Private') }}</th>
                                    <th colspan="2">{{ __('Total') }}</th>
                                    <th>{{ __('Institutions') }}</th>
                                    <th>{{ __('Projects') }}</th>
                                    
                                    <!-- Power Improvements Headers -->
                                    <th colspan="2">{{ __('Public') }}</th>
                                    <th colspan="2">{{ __('Private') }}</th>
                                    <th colspan="2">{{ __('Total') }}</th>
                                    <th>{{ __('Institutions') }}</th>
                                    <th>{{ __('Projects') }}</th>
                                </tr>
                                <tr>
                                    <!-- New Power Projects Sub-headers -->
                                    <th>{{ __('Count') }}</th>
                                    <th>{{ __('Cost') }}</th>
                                    <th>{{ __('Count') }}</th>
                                    <th>{{ __('Cost') }}</th>
                                    <th>{{ __('Count') }}</th>
                                    <th>{{ __('Cost') }}</th>
                                    <th></th>
                                    <th></th>
                                    
                                    <!-- Power Improvements Sub-headers -->
                                    <th>{{ __('Count') }}</th>
                                    <th>{{ __('Cost') }}</th>
                                    <th>{{ __('Count') }}</th>
                                    <th>{{ __('Cost') }}</th>
                                    <th>{{ __('Count') }}</th>
                                    <th>{{ __('Cost') }}</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reportData as $data)
                                    <tr>
                                        <td class="fw-bold">{{ $data['kebele_name'] }}</td>

                                        <!-- Current Year New Power Projects -->
                                        <td class="text-center">{{ number_format($data['current']['new_power']['public_count'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['new_power']['public_cost'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['new_power']['private_count'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['new_power']['private_cost'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['new_power']['total_count'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['new_power']['total_cost'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['new_power']['institutions'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['new_power']['projects'] ?? 0) }}</td>

                                        <!-- Current Year Power Improvements -->
                                        <td class="text-center">{{ number_format($data['current']['improvements']['public_count'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['improvements']['public_cost'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['improvements']['private_count'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['improvements']['private_cost'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['improvements']['total_count'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['improvements']['total_cost'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['improvements']['institutions'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['improvements']['projects'] ?? 0) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="17" class="text-center text-muted py-4">
                                            <i class="fas fa-info-circle me-2"></i>{{ __('No data found for selected year') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if(count($reportData) > 0)
                            <tfoot class="bg-light">
                                <tr>
                                    <td class="fw-bold">{{ __('TOTAL') }}</td>
                                    
                                    <!-- Current Year New Power Projects Totals -->
                                    <td class="text-center fw-bold">{{ number_format($currentNewPublicCount) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentNewPublicCost) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentNewPrivateCount) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentNewPrivateCost) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentNewTotalCount) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentNewTotalCost) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'current'), 'new_power_institutions')) ?? 0) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'current'), 'new_power_projects')) ?? 0) }}</td>
                                    
                                    <!-- Current Year Power Improvements Totals -->
                                    <td class="text-center fw-bold">{{ number_format($currentImprovePublicCount) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentImprovePublicCost) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentImprovePrivateCount) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentImprovePrivateCost) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentImproveTotalCount) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentImproveTotalCost) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'current'), 'improvements_institutions')) ?? 0) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'current'), 'improvements_projects')) ?? 0) }}</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- New Power Projects Details Table -->
            @if(count($newPowerData['current']) > 0)
            <div class="card mt-4">
                <div class="card-header bg-danger text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-plus-square mr-1"></i>
                        {{ __('New Power Projects Details') }} ({{ $selectedYear }})
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Project / Institution') }}</th>
                                    <th class="text-center" colspan="2">{{ __('Public') }}</th>
                                    <th class="text-center" colspan="2">{{ __('Private') }}</th>
                                    <th class="text-center" colspan="2">{{ __('Total') }}</th>
                                    <th class="text-right">{{ __('Avg Cost') }}</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th class="text-right">{{ __('Count') }}</th>
                                    <th class="text-right">{{ __('Cost') }}</th>
                                    <th class="text-right">{{ __('Count') }}</th>
                                    <th class="text-right">{{ __('Cost') }}</th>
                                    <th class="text-right">{{ __('Count') }}</th>
                                    <th class="text-right">{{ __('Cost') }}</th>
                                    <th class="text-right">{{ __('Per Unit') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($newPowerKeys as $index => $key)
                                    @php
                                        $publicCount = $newPowerData['current'][$key]['public_count'] ?? 0;
                                        $publicCost = $newPowerData['current'][$key]['public_cost'] ?? 0;
                                        $privateCount = $newPowerData['current'][$key]['private_count'] ?? 0;
                                        $privateCost = $newPowerData['current'][$key]['private_cost'] ?? 0;
                                        $totalCount = $publicCount + $privateCount;
                                        $totalCost = $publicCost + $privateCost;
                                        
                                        $avgPublicCost = $publicCount > 0 ? $publicCost / $publicCount : 0;
                                        $avgPrivateCost = $privateCount > 0 ? $privateCost / $privateCount : 0;
                                        $avgTotalCost = $totalCount > 0 ? $totalCost / $totalCount : 0;
                                        
                                        $countPercent = $currentNewTotalCount > 0 ? ($totalCount / $currentNewTotalCount) * 100 : 0;
                                        $costPercent = $currentNewTotalCost > 0 ? ($totalCost / $currentNewTotalCost) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $key }}</td>
                                        <td class="text-right">{{ number_format($publicCount) }}</td>
                                        <td class="text-right">{{ number_format($publicCost) }}</td>
                                        <td class="text-right">{{ number_format($privateCount) }}</td>
                                        <td class="text-right">{{ number_format($privateCost) }}</td>
                                        <td class="text-right">
                                            {{ number_format($totalCount) }}
                                            <small class="text-muted">({{ number_format($countPercent, 1) }}%)</small>
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($totalCost) }}
                                            <small class="text-muted">({{ number_format($costPercent, 1) }}%)</small>
                                        </td>
                                        <td class="text-right">{{ number_format($avgTotalCost, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="2">{{ __('TOTAL NEW POWER PROJECTS') }}</td>
                                    <td class="text-right">{{ number_format($currentNewPublicCount) }}</td>
                                    <td class="text-right">{{ number_format($currentNewPublicCost) }}</td>
                                    <td class="text-right">{{ number_format($currentNewPrivateCount) }}</td>
                                    <td class="text-right">{{ number_format($currentNewPrivateCost) }}</td>
                                    <td class="text-right">{{ number_format($currentNewTotalCount) }}</td>
                                    <td class="text-right">{{ number_format($currentNewTotalCost) }}</td>
                                    <td class="text-right">{{ number_format($currentNewTotalCount > 0 ? $currentNewTotalCost / $currentNewTotalCount : 0, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Power Improvements Details Table -->
            @if(count($improvementPowerData['current']) > 0)
            <div class="card mt-4">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-tools mr-1"></i>
                        {{ __('Power Improvements Details') }} ({{ $selectedYear }})
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Project / Institution') }}</th>
                                    <th class="text-center" colspan="2">{{ __('Public') }}</th>
                                    <th class="text-center" colspan="2">{{ __('Private') }}</th>
                                    <th class="text-center" colspan="2">{{ __('Total') }}</th>
                                    <th class="text-right">{{ __('Avg Cost') }}</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th class="text-right">{{ __('Count') }}</th>
                                    <th class="text-right">{{ __('Cost') }}</th>
                                    <th class="text-right">{{ __('Count') }}</th>
                                    <th class="text-right">{{ __('Cost') }}</th>
                                    <th class="text-right">{{ __('Count') }}</th>
                                    <th class="text-right">{{ __('Cost') }}</th>
                                    <th class="text-right">{{ __('Per Unit') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($improvementPowerKeys as $index => $key)
                                    @php
                                        $publicCount = $improvementPowerData['current'][$key]['public_count'] ?? 0;
                                        $publicCost = $improvementPowerData['current'][$key]['public_cost'] ?? 0;
                                        $privateCount = $improvementPowerData['current'][$key]['private_count'] ?? 0;
                                        $privateCost = $improvementPowerData['current'][$key]['private_cost'] ?? 0;
                                        $totalCount = $publicCount + $privateCount;
                                        $totalCost = $publicCost + $privateCost;
                                        
                                        $avgPublicCost = $publicCount > 0 ? $publicCost / $publicCount : 0;
                                        $avgPrivateCost = $privateCount > 0 ? $privateCost / $privateCount : 0;
                                        $avgTotalCost = $totalCount > 0 ? $totalCost / $totalCount : 0;
                                        
                                        $countPercent = $currentImproveTotalCount > 0 ? ($totalCount / $currentImproveTotalCount) * 100 : 0;
                                        $costPercent = $currentImproveTotalCost > 0 ? ($totalCost / $currentImproveTotalCost) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $key }}</td>
                                        <td class="text-right">{{ number_format($publicCount) }}</td>
                                        <td class="text-right">{{ number_format($publicCost) }}</td>
                                        <td class="text-right">{{ number_format($privateCount) }}</td>
                                        <td class="text-right">{{ number_format($privateCost) }}</td>
                                        <td class="text-right">
                                            {{ number_format($totalCount) }}
                                            <small class="text-muted">({{ number_format($countPercent, 1) }}%)</small>
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($totalCost) }}
                                            <small class="text-muted">({{ number_format($costPercent, 1) }}%)</small>
                                        </td>
                                        <td class="text-right">{{ number_format($avgTotalCost, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="2">{{ __('TOTAL POWER IMPROVEMENTS') }}</td>
                                    <td class="text-right">{{ number_format($currentImprovePublicCount) }}</td>
                                    <td class="text-right">{{ number_format($currentImprovePublicCost) }}</td>
                                    <td class="text-right">{{ number_format($currentImprovePrivateCount) }}</td>
                                    <td class="text-right">{{ number_format($currentImprovePrivateCost) }}</td>
                                    <td class="text-right">{{ number_format($currentImproveTotalCount) }}</td>
                                    <td class="text-right">{{ number_format($currentImproveTotalCost) }}</td>
                                    <td class="text-right">{{ number_format($currentImproveTotalCount > 0 ? $currentImproveTotalCost / $currentImproveTotalCount : 0, 2) }}</td>
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
    const currentNewPublicCount = @json($currentNewPublicCount);
    const currentNewPrivateCount = @json($currentNewPrivateCount);
    const currentNewPublicCost = @json($currentNewPublicCost);
    const currentNewPrivateCost = @json($currentNewPrivateCost);
    const currentNewTotalCount = @json($currentNewTotalCount);
    const currentNewTotalCost = @json($currentNewTotalCost);
    
    const currentImprovePublicCount = @json($currentImprovePublicCount);
    const currentImprovePrivateCount = @json($currentImprovePrivateCount);
    const currentImprovePublicCost = @json($currentImprovePublicCost);
    const currentImprovePrivateCost = @json($currentImprovePrivateCost);
    const currentImproveTotalCount = @json($currentImproveTotalCount);
    const currentImproveTotalCost = @json($currentImproveTotalCost);
    
    const previousNewPublicCount = @json($previousNewPublicCount);
    const previousNewPrivateCount = @json($previousNewPrivateCount);
    const previousNewPublicCost = @json($previousNewPublicCost);
    const previousNewPrivateCost = @json($previousNewPrivateCost);
    const previousNewTotalCount = @json($previousNewTotalCount);
    const previousNewTotalCost = @json($previousNewTotalCost);
    
    const previousImprovePublicCount = @json($previousImprovePublicCount);
    const previousImprovePrivateCount = @json($previousImprovePrivateCount);
    const previousImprovePublicCost = @json($previousImprovePublicCost);
    const previousImprovePrivateCost = @json($previousImprovePrivateCost);
    const previousImproveTotalCount = @json($previousImproveTotalCount);
    const previousImproveTotalCost = @json($previousImproveTotalCost);
    
    // Building data
    const newPowerKeys = @json($newPowerKeys);
    const newPowerTotalCosts = @json($newPowerTotalCosts);
    const improvementPowerKeys = @json($improvementPowerKeys);
    const improvementTotalCosts = @json($improvementTotalCosts);

    // Color palette
    const powerColors = [
        'rgba(220, 53, 69, 0.7)',    // Red
        'rgba(40, 167, 69, 0.7)',    // Green
        'rgba(0, 123, 255, 0.7)',    // Blue
        'rgba(255, 193, 7, 0.7)',    // Yellow
        'rgba(23, 162, 184, 0.7)',   // Teal
        'rgba(108, 117, 125, 0.7)',  // Gray
        'rgba(111, 66, 193, 0.7)',   // Purple
        'rgba(253, 126, 20, 0.7)',   // Orange
        'rgba(32, 201, 151, 0.7)',   // Mint
        'rgba(233, 30, 99, 0.7)',    // Pink
    ];

    // Chart 1: New Power Projects Investment
    if (newPowerKeys.length > 0) {
        const newPowerCtx = document.getElementById('newPowerChart').getContext('2d');
        new Chart(newPowerCtx, {
            type: 'bar',
            data: {
                labels: newPowerKeys,
                datasets: [{
                    label: `{{ __('Total Investment') }} (${selectedYear})`,
                    data: newPowerTotalCosts,
                    backgroundColor: 'rgba(220, 53, 69, 0.7)',
                    borderColor: 'rgba(220, 53, 69, 1)',
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
                            text: '{{ __('Investment') }}'
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
                                const total = newPowerTotalCosts.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed.y / total) * 100).toFixed(1) : 0;
                                return `${context.dataset.label}: ${context.parsed.y.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Chart 2: Power Improvements Investment
    if (improvementPowerKeys.length > 0) {
        const improvementsPowerCtx = document.getElementById('improvementsPowerChart').getContext('2d');
        new Chart(improvementsPowerCtx, {
            type: 'bar',
            data: {
                labels: improvementPowerKeys,
                datasets: [{
                    label: `{{ __('Total Investment') }} (${selectedYear})`,
                    data: improvementTotalCosts,
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
                            text: '{{ __('Investment') }}'
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
                                const total = improvementTotalCosts.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed.y / total) * 100).toFixed(1) : 0;
                                return `${context.dataset.label}: ${context.parsed.y.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Chart 3: Count Comparison Chart
    const countComparisonCtx = document.getElementById('countComparisonChart').getContext('2d');
    new Chart(countComparisonCtx, {
        type: 'bar',
        data: {
            labels: ['{{ __('New Public') }}', '{{ __('New Private') }}', '{{ __('Improve Public') }}', '{{ __('Improve Private') }}'],
            datasets: [
                {
                    label: `{{ __('Current Year') }} (${selectedYear})`,
                    data: [currentNewPublicCount, currentNewPrivateCount, currentImprovePublicCount, currentImprovePrivateCount],
                    backgroundColor: [
                        'rgba(220, 53, 69, 0.7)',
                        'rgba(255, 193, 7, 0.7)',
                        'rgba(40, 167, 69, 0.7)',
                        'rgba(23, 162, 184, 0.7)'
                    ],
                    borderColor: [
                        'rgba(220, 53, 69, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(40, 167, 69, 1)',
                        'rgba(23, 162, 184, 1)'
                    ],
                    borderWidth: 1
                },
                {
                    label: `{{ __('Previous Year') }} (${previousYear})`,
                    data: [previousNewPublicCount, previousNewPrivateCount, previousImprovePublicCount, previousImprovePrivateCount],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(255, 205, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(201, 203, 207, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(201, 203, 207, 1)'
                    ],
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

    // Chart 4: Investment Distribution Pie Chart
    if (currentNewTotalCost > 0 || currentImproveTotalCost > 0) {
        const investmentDistributionCtx = document.getElementById('investmentDistributionChart').getContext('2d');
        new Chart(investmentDistributionCtx, {
            type: 'pie',
            data: {
                labels: [
                    '{{ __('New Public Projects') }}',
                    '{{ __('New Private Projects') }}',
                    '{{ __('Improve Public') }}',
                    '{{ __('Improve Private') }}'
                ],
                datasets: [{
                    data: [currentNewPublicCost, currentNewPrivateCost, currentImprovePublicCost, currentImprovePrivateCost],
                    backgroundColor: [
                        'rgba(220, 53, 69, 0.7)',
                        'rgba(255, 193, 7, 0.7)',
                        'rgba(40, 167, 69, 0.7)',
                        'rgba(23, 162, 184, 0.7)'
                    ],
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
    .bg-danger { background-color: #dc3545 !important; }
    .bg-success { background-color: #28a745 !important; }
    .bg-warning { background-color: #ffc107 !important; }
    
    .card-header.bg-danger .card-tools .btn-tool,
    .card-header.bg-success .card-tools .btn-tool {
        color: rgba(255,255,255,0.8) !important;
    }
    
    .card-header.bg-danger .card-tools .btn-tool:hover,
    .card-header.bg-success .card-tools .btn-tool:hover {
        color: white !important;
    }
    
    table th, table td {
        vertical-align: middle !important;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(220, 53, 69, 0.05);
    }
</style>

</x-layout>