<x-layout>
<div class="content-header">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <div class="bg-white border rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:60px;height:60px;">
                        <i class="fas fa-seedling fa-lg text-success"></i>
                    </div>
                    <div>
                        <h1 class="m-0 text-dark">{{ __('Kebele Level Seedling Report') }}</h1>
                        <small class="text-dark">{{ __('Seedling preparation and planting analysis') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @php
                // Calculate summary data for charts and cards
                $currentPrepTotal = 0;
                $currentPlantTotal = 0;
                $previousPrepTotal = 0;
                $previousPlantTotal = 0;
                $currentTotalParticipants = 0;
                $previousTotalParticipants = 0;
                $currentTotalDays = 0;
                $previousTotalDays = 0;
                
                foreach($reportData as $data) {
                    $currentPrepTotal += $data['current']['preparation']['hanga'] ?? 0;
                    $currentPlantTotal += $data['current']['planting']['hanga'] ?? 0;
                    $previousPrepTotal += $data['previous']['preparation']['hanga'] ?? 0;
                    $previousPlantTotal += $data['previous']['planting']['hanga'] ?? 0;
                    $currentTotalParticipants += ($data['current']['preparation']['participants'] ?? 0) + ($data['current']['planting']['participants'] ?? 0);
                    $previousTotalParticipants += ($data['previous']['preparation']['participants'] ?? 0) + ($data['previous']['planting']['participants'] ?? 0);
                    $currentTotalDays += ($data['current']['preparation']['days'] ?? 0) + ($data['current']['planting']['days'] ?? 0);
                    $previousTotalDays += ($data['previous']['preparation']['days'] ?? 0) + ($data['previous']['planting']['days'] ?? 0);
                }
                
                $totalCurrent = $currentPrepTotal + $currentPlantTotal;
                $totalPrevious = $previousPrepTotal + $previousPlantTotal;
                $preparationChange = $previousPrepTotal > 0 ? (($currentPrepTotal - $previousPrepTotal) / $previousPrepTotal) * 100 : 0;
                $plantingChange = $previousPlantTotal > 0 ? (($currentPlantTotal - $previousPlantTotal) / $previousPlantTotal) * 100 : 0;
                $totalChange = $totalPrevious > 0 ? (($totalCurrent - $totalPrevious) / $totalPrevious) * 100 : 0;
                
                // Prepare seedling type data for charts - Separate preparation and planting
                $prepSeedlingTypes = array_keys($seedlingTypeData['current']['preparation'] ?? []);
                $prepSeedlingAmounts = array_values($seedlingTypeData['current']['preparation'] ?? []);
                $previousPrepAmounts = [];
                
                // Match previous year amounts with current year types for preparation
                foreach ($prepSeedlingTypes as $type) {
                    $previousPrepAmounts[] = $seedlingTypeData['previous']['preparation'][$type] ?? 0;
                }
                
                $plantSeedlingTypes = array_keys($seedlingTypeData['current']['planting'] ?? []);
                $plantSeedlingAmounts = array_values($seedlingTypeData['current']['planting'] ?? []);
                $previousPlantAmounts = [];
                
                // Match previous year amounts with current year types for planting
                foreach ($plantSeedlingTypes as $type) {
                    $previousPlantAmounts[] = $seedlingTypeData['previous']['planting'][$type] ?? 0;
                }
                
                // Combined seedling types for overall view (unique from both preparation and planting)
                $allSeedlingTypes = array_unique(array_merge($prepSeedlingTypes, $plantSeedlingTypes));
                $combinedCurrentAmounts = [];
                $combinedPreviousAmounts = [];
                
                foreach ($allSeedlingTypes as $type) {
                    $combinedCurrentAmounts[$type] = 
                        ($seedlingTypeData['current']['preparation'][$type] ?? 0) + 
                        ($seedlingTypeData['current']['planting'][$type] ?? 0);
                    $combinedPreviousAmounts[$type] = 
                        ($seedlingTypeData['previous']['preparation'][$type] ?? 0) + 
                        ($seedlingTypeData['previous']['planting'][$type] ?? 0);
                }
                
                // Sort combined types by total amount
                arsort($combinedCurrentAmounts);
                $combinedSeedlingTypes = array_keys($combinedCurrentAmounts);
                $combinedCurrentAmounts = array_values($combinedCurrentAmounts);
                $combinedPreviousAmounts = [];
                
                foreach ($combinedSeedlingTypes as $type) {
                    $combinedPreviousAmounts[] = $combinedPreviousAmounts[$type] ?? 0;
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
                            <h3>{{ number_format($totalCurrent) }}</h3>
                            <p>{{ __('Total Seedlings') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-seedling"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($currentPrepTotal) }}</h3>
                            <p>{{ __('Preparation') }} ({{ $selectedYear }})</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ number_format($currentPlantTotal) }}</h3>
                            <p>{{ __('Planting') }} ({{ $selectedYear }})</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tree"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
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
                <!-- Preparation Seedling Type Distribution Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Preparation Seedling Types') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($prepSeedlingTypes) > 0)
                                <canvas id="prepSeedlingTypeChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-seedling fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No preparation seedling data available') }}</h5>
                                    <p class="text-muted">{{ __('Try selecting a different year') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Planting Seedling Type Distribution Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Planting Seedling Types') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($plantSeedlingTypes) > 0)
                                <canvas id="plantSeedlingTypeChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-tree fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No planting seedling data available') }}</h5>
                                    <p class="text-muted">{{ __('Try selecting a different year') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Year Comparison Charts -->
            <div class="row mb-4">
                <!-- Activity Comparison Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Preparation vs Planting Comparison') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="activityChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Top Seedlings Overall Pie Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                {{ __('Top Seedlings Overall') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($combinedSeedlingTypes) > 0)
                                <canvas id="topSeedlingsChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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

            <!-- Report Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-table mr-1"></i>
                        {{ __('Detailed Seedling Report') }}
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
                                    <th colspan="3" class="text-center">{{ $selectedYear }} ({{ __('Preparation') }})</th>
                                    <th colspan="3" class="text-center">{{ $selectedYear }} ({{ __('Planting') }})</th>
                                    <th colspan="3" class="text-center">{{ $previousYear }} ({{ __('Preparation') }})</th>
                                    <th colspan="3" class="text-center">{{ $previousYear }} ({{ __('Planting') }})</th>
                                </tr>
                                <tr>
                                    @for ($i = 0; $i < 4; $i++)
                                        <th>{{ __('Hanga') }}</th>
                                        <th>{{ __('Participants') }}</th>
                                        <th>{{ __('Days') }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reportData as $data)
                                    <tr>
                                        <td class="fw-bold">{{ $data['kebele_name'] }}</td>

                                        <!-- Current Year Preparation -->
                                        <td class="text-center">{{ number_format($data['current']['preparation']['hanga'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['preparation']['participants'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['preparation']['days'] ?? 0) }}</td>

                                        <!-- Current Year Planting -->
                                        <td class="text-center">{{ number_format($data['current']['planting']['hanga'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['planting']['participants'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['planting']['days'] ?? 0) }}</td>

                                        <!-- Previous Year Preparation -->
                                        <td class="text-center">{{ number_format($data['previous']['preparation']['hanga'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['previous']['preparation']['participants'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['previous']['preparation']['days'] ?? 0) }}</td>

                                        <!-- Previous Year Planting -->
                                        <td class="text-center">{{ number_format($data['previous']['planting']['hanga'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['previous']['planting']['participants'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['previous']['planting']['days'] ?? 0) }}</td>
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
                                    
                                    <!-- Current Year Preparation Totals -->
                                    <td class="text-center fw-bold">{{ number_format($currentPrepTotal) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'current'), 'preparation')) ?? 0) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'current'), 'preparation_days')) ?? 0) }}</td>
                                    
                                    <!-- Current Year Planting Totals -->
                                    <td class="text-center fw-bold">{{ number_format($currentPlantTotal) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'current'), 'planting_participants')) ?? 0) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'current'), 'planting_days')) ?? 0) }}</td>
                                    
                                    <!-- Previous Year Preparation Totals -->
                                    <td class="text-center fw-bold">{{ number_format($previousPrepTotal) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'previous'), 'preparation_participants')) ?? 0) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'previous'), 'preparation_days')) ?? 0) }}</td>
                                    
                                    <!-- Previous Year Planting Totals -->
                                    <td class="text-center fw-bold">{{ number_format($previousPlantTotal) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'previous'), 'planting_participants')) ?? 0) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'previous'), 'planting_days')) ?? 0) }}</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- Seedling Type Details - Preparation -->
            @if(count($seedlingTypeData['current']['preparation']) > 0)
            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-seedling mr-1"></i>
                        {{ __('Seedling Type Details - Preparation') }} ({{ $selectedYear }})
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Seedling Type') }}</th>
                                    <th class="text-right">{{ __('Current Year') }} ({{ $selectedYear }})</th>
                                    <th class="text-right">{{ __('Previous Year') }} ({{ $previousYear }})</th>
                                    <th class="text-right">{{ __('Change') }}</th>
                                    <th class="text-right">{{ __('Change %') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $currentPrepTypes = array_keys($seedlingTypeData['current']['preparation']);
                                    $currentPrepAmounts = array_values($seedlingTypeData['current']['preparation']);
                                    $previousPrepAmounts = [];
                                    
                                    // Match previous year amounts with current year types
                                    foreach ($currentPrepTypes as $type) {
                                        $previousPrepAmounts[] = $seedlingTypeData['previous']['preparation'][$type] ?? 0;
                                    }
                                    
                                    $totalCurrentPrep = array_sum($currentPrepAmounts);
                                    $totalPreviousPrep = array_sum($previousPrepAmounts);
                                @endphp
                                @foreach($currentPrepTypes as $index => $type)
                                    @php
                                        $currentAmount = $currentPrepAmounts[$index] ?? 0;
                                        $previousAmount = $previousPrepAmounts[$index] ?? 0;
                                        $change = $currentAmount - $previousAmount;
                                        $changePercent = $previousAmount > 0 ? ($change / $previousAmount) * 100 : ($currentAmount > 0 ? 100 : 0);
                                        $currentPercent = $totalCurrentPrep > 0 ? ($currentAmount / $totalCurrentPrep) * 100 : 0;
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
                                    <td colspan="2">{{ __('TOTAL PREPARATION') }}</td>
                                    <td class="text-right">{{ number_format($totalCurrentPrep) }}</td>
                                    <td class="text-right">{{ number_format($totalPreviousPrep) }}</td>
                                    @php
                                        $totalPrepChange = $totalCurrentPrep - $totalPreviousPrep;
                                        $totalPrepChangePercent = $totalPreviousPrep > 0 ? ($totalPrepChange / $totalPreviousPrep) * 100 : ($totalCurrentPrep > 0 ? 100 : 0);
                                    @endphp
                                    <td class="text-right {{ $totalPrepChange >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $totalPrepChange >= 0 ? '+' : '' }}{{ number_format($totalPrepChange) }}
                                    </td>
                                    <td class="text-right {{ $totalPrepChangePercent >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $totalPrepChangePercent >= 0 ? '+' : '' }}{{ number_format($totalPrepChangePercent, 1) }}%
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Seedling Type Details - Planting -->
            @if(count($seedlingTypeData['current']['planting']) > 0)
            <div class="card mt-4">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-tree mr-1"></i>
                        {{ __('Seedling Type Details - Planting') }} ({{ $selectedYear }})
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Seedling Type') }}</th>
                                    <th class="text-right">{{ __('Current Year') }} ({{ $selectedYear }})</th>
                                    <th class="text-right">{{ __('Previous Year') }} ({{ $previousYear }})</th>
                                    <th class="text-right">{{ __('Change') }}</th>
                                    <th class="text-right">{{ __('Change %') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $currentPlantTypes = array_keys($seedlingTypeData['current']['planting']);
                                    $currentPlantAmounts = array_values($seedlingTypeData['current']['planting']);
                                    $previousPlantAmounts = [];
                                    
                                    // Match previous year amounts with current year types
                                    foreach ($currentPlantTypes as $type) {
                                        $previousPlantAmounts[] = $seedlingTypeData['previous']['planting'][$type] ?? 0;
                                    }
                                    
                                    $totalCurrentPlant = array_sum($currentPlantAmounts);
                                    $totalPreviousPlant = array_sum($previousPlantAmounts);
                                @endphp
                                @foreach($currentPlantTypes as $index => $type)
                                    @php
                                        $currentAmount = $currentPlantAmounts[$index] ?? 0;
                                        $previousAmount = $previousPlantAmounts[$index] ?? 0;
                                        $change = $currentAmount - $previousAmount;
                                        $changePercent = $previousAmount > 0 ? ($change / $previousAmount) * 100 : ($currentAmount > 0 ? 100 : 0);
                                        $currentPercent = $totalCurrentPlant > 0 ? ($currentAmount / $totalCurrentPlant) * 100 : 0;
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
                                    <td colspan="2">{{ __('TOTAL PLANTING') }}</td>
                                    <td class="text-right">{{ number_format($totalCurrentPlant) }}</td>
                                    <td class="text-right">{{ number_format($totalPreviousPlant) }}</td>
                                    @php
                                        $totalPlantChange = $totalCurrentPlant - $totalPreviousPlant;
                                        $totalPlantChangePercent = $totalPreviousPlant > 0 ? ($totalPlantChange / $totalPreviousPlant) * 100 : ($totalCurrentPlant > 0 ? 100 : 0);
                                    @endphp
                                    <td class="text-right {{ $totalPlantChange >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $totalPlantChange >= 0 ? '+' : '' }}{{ number_format($totalPlantChange) }}
                                    </td>
                                    <td class="text-right {{ $totalPlantChangePercent >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $totalPlantChangePercent >= 0 ? '+' : '' }}{{ number_format($totalPlantChangePercent, 1) }}%
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
    
    const currentPrepTotal = @json($currentPrepTotal);
    const currentPlantTotal = @json($currentPlantTotal);
    const previousPrepTotal = @json($previousPrepTotal);
    const previousPlantTotal = @json($previousPlantTotal);
    
    // Seedling type data - separate for preparation and planting
    const prepSeedlingTypes = @json($prepSeedlingTypes);
    const prepSeedlingAmounts = @json($prepSeedlingAmounts);
    const previousPrepAmounts = @json($previousPrepAmounts);
    
    const plantSeedlingTypes = @json($plantSeedlingTypes);
    const plantSeedlingAmounts = @json($plantSeedlingAmounts);
    const previousPlantAmounts = @json($previousPlantAmounts);
    
    const combinedSeedlingTypes = @json($combinedSeedlingTypes);
    const combinedCurrentAmounts = @json($combinedCurrentAmounts);
    const combinedPreviousAmounts = @json($combinedPreviousAmounts);

    // Color palette for seedling types
    const seedlingColors = [
        'rgba(40, 167, 69, 0.7)',   // Green
        'rgba(23, 162, 184, 0.7)',  // Teal
        'rgba(108, 117, 125, 0.7)', // Gray
        'rgba(255, 193, 7, 0.7)',   // Yellow
        'rgba(220, 53, 69, 0.7)',   // Red
        'rgba(111, 66, 193, 0.7)',  // Purple
        'rgba(253, 126, 20, 0.7)',  // Orange
        'rgba(32, 201, 151, 0.7)',  // Mint
        'rgba(233, 30, 99, 0.7)',   // Pink
        'rgba(0, 123, 255, 0.7)',   // Blue
    ];

    // Chart 1: Preparation Seedling Type Distribution
    if (prepSeedlingTypes.length > 0) {
        const prepSeedlingTypeCtx = document.getElementById('prepSeedlingTypeChart').getContext('2d');
        new Chart(prepSeedlingTypeCtx, {
            type: 'bar',
            data: {
                labels: prepSeedlingTypes,
                datasets: [{
                    label: `{{ __('Preparation') }} (${selectedYear})`,
                    data: prepSeedlingAmounts,
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
                            text: '{{ __('Hanga') }}'
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
                                const total = prepSeedlingAmounts.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed.y / total) * 100).toFixed(1) : 0;
                                return `${context.dataset.label}: ${context.parsed.y.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Chart 2: Planting Seedling Type Distribution
    if (plantSeedlingTypes.length > 0) {
        const plantSeedlingTypeCtx = document.getElementById('plantSeedlingTypeChart').getContext('2d');
        new Chart(plantSeedlingTypeCtx, {
            type: 'bar',
            data: {
                labels: plantSeedlingTypes,
                datasets: [{
                    label: `{{ __('Planting') }} (${selectedYear})`,
                    data: plantSeedlingAmounts,
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
                            text: '{{ __('Hanga') }}'
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
                                const total = plantSeedlingAmounts.reduce((a, b) => a + b, 0);
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
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    new Chart(activityCtx, {
        type: 'bar',
        data: {
            labels: ['{{ __('Preparation') }}', '{{ __('Planting') }}'],
            datasets: [
                {
                    label: `{{ __('Current Year') }} (${selectedYear})`,
                    data: [currentPrepTotal, currentPlantTotal],
                    backgroundColor: ['rgba(54, 162, 235, 0.7)', 'rgba(75, 192, 192, 0.7)'],
                    borderColor: ['rgba(54, 162, 235, 1)', 'rgba(75, 192, 192, 1)'],
                    borderWidth: 1
                },
                {
                    label: `{{ __('Previous Year') }} (${previousYear})`,
                    data: [previousPrepTotal, previousPlantTotal],
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
                        text: '{{ __('Hanga') }}'
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
                            return context.dataset.label + ': ' + context.parsed.y.toLocaleString() + ' {{ __("Hanga") }}';
                        }
                    }
                }
            }
        }
    });

    // Chart 4: Top Seedlings Overall Pie Chart
    if (combinedSeedlingTypes.length > 0) {
        // Take top seedlings for pie chart (max 7)
        const topCount = Math.min(combinedSeedlingTypes.length, 7);
        const topLabels = combinedSeedlingTypes.slice(0, topCount);
        const topData = combinedCurrentAmounts.slice(0, topCount);
        
        // If there are more types, group the rest as "Others"
        if (combinedSeedlingTypes.length > topCount) {
            const othersSum = combinedCurrentAmounts.slice(topCount).reduce((a, b) => a + b, 0);
            if (othersSum > 0) {
                topLabels.push('{{ __('Others') }}');
                topData.push(othersSum);
            }
        }

        const topSeedlingsCtx = document.getElementById('topSeedlingsChart').getContext('2d');
        new Chart(topSeedlingsCtx, {
            type: 'pie',
            data: {
                labels: topLabels,
                datasets: [{
                    data: topData,
                    backgroundColor: seedlingColors.slice(0, topLabels.length),
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
    .bg-danger { background-color: #dc3545 !important; }
    .bg-primary { background-color: #007bff !important; }
    
    .card-header.bg-primary .card-tools .btn-tool,
    .card-header.bg-success .card-tools .btn-tool {
        color: rgba(255,255,255,0.8) !important;
    }
    
    .card-header.bg-primary .card-tools .btn-tool:hover,
    .card-header.bg-success .card-tools .btn-tool:hover {
        color: white !important;
    }
</style>

</x-layout>