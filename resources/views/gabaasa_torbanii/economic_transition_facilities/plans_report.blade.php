<x-layout>
<div class="content-header">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <div class="bg-white border rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:60px;height:60px;">
                        <i class="fas fa-industry fa-lg text-purple"></i>
                    </div>
                    <div>
                        <h1 class="m-0 text-dark">{{ __('Kebele Level Economic Transition Facilities Report') }}</h1>
                        <small class="text-dark">{{ __('Economic facility construction and improvement analysis') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @php
                // Prepare facility data for charts
                $newFacilityKeys = array_keys($newFacilityData['current'] ?? []);
                $newFacilityPublicCosts = [];
                $newFacilityPrivateCosts = [];
                $newFacilityAssociationCosts = [];
                $newFacilityTotalCosts = [];
                
                foreach ($newFacilityKeys as $key) {
                    $publicCost = $newFacilityData['current'][$key]['public_cost'] ?? 0;
                    $privateCost = $newFacilityData['current'][$key]['private_cost'] ?? 0;
                    $associationCost = $newFacilityData['current'][$key]['association_cost'] ?? 0;
                    $newFacilityPublicCosts[] = $publicCost;
                    $newFacilityPrivateCosts[] = $privateCost;
                    $newFacilityAssociationCosts[] = $associationCost;
                    $newFacilityTotalCosts[] = $publicCost + $privateCost + $associationCost;
                }
                
                // Improvement data
                $improvementFacilityKeys = array_keys($improvementFacilityData['current'] ?? []);
                $improvementPublicCosts = [];
                $improvementPrivateCosts = [];
                $improvementAssociationCosts = [];
                $improvementTotalCosts = [];
                
                foreach ($improvementFacilityKeys as $key) {
                    $publicCost = $improvementFacilityData['current'][$key]['public_cost'] ?? 0;
                    $privateCost = $improvementFacilityData['current'][$key]['private_cost'] ?? 0;
                    $associationCost = $improvementFacilityData['current'][$key]['association_cost'] ?? 0;
                    $improvementPublicCosts[] = $publicCost;
                    $improvementPrivateCosts[] = $privateCost;
                    $improvementAssociationCosts[] = $associationCost;
                    $improvementTotalCosts[] = $publicCost + $privateCost + $associationCost;
                }
                
                // Summary data from reportData
                $currentNewPublicCount = 0;
                $currentNewPrivateCount = 0;
                $currentNewAssociationCount = 0;
                $currentNewPublicCost = 0;
                $currentNewPrivateCost = 0;
                $currentNewAssociationCost = 0;
                $currentNewTotalCount = 0;
                $currentNewTotalCost = 0;
                
                $currentImprovePublicCount = 0;
                $currentImprovePrivateCount = 0;
                $currentImproveAssociationCount = 0;
                $currentImprovePublicCost = 0;
                $currentImprovePrivateCost = 0;
                $currentImproveAssociationCost = 0;
                $currentImproveTotalCount = 0;
                $currentImproveTotalCost = 0;
                
                $previousNewPublicCount = 0;
                $previousNewPrivateCount = 0;
                $previousNewAssociationCount = 0;
                $previousNewPublicCost = 0;
                $previousNewPrivateCost = 0;
                $previousNewAssociationCost = 0;
                $previousNewTotalCount = 0;
                $previousNewTotalCost = 0;
                
                $previousImprovePublicCount = 0;
                $previousImprovePrivateCount = 0;
                $previousImproveAssociationCount = 0;
                $previousImprovePublicCost = 0;
                $previousImprovePrivateCost = 0;
                $previousImproveAssociationCost = 0;
                $previousImproveTotalCount = 0;
                $previousImproveTotalCost = 0;
                
                foreach ($reportData as $data) {
                    // Current year new facilities
                    $currentNewPublicCount += $data['current']['new_facilities']['public_count'] ?? 0;
                    $currentNewPrivateCount += $data['current']['new_facilities']['private_count'] ?? 0;
                    $currentNewAssociationCount += $data['current']['new_facilities']['association_count'] ?? 0;
                    $currentNewPublicCost += $data['current']['new_facilities']['public_cost'] ?? 0;
                    $currentNewPrivateCost += $data['current']['new_facilities']['private_cost'] ?? 0;
                    $currentNewAssociationCost += $data['current']['new_facilities']['association_cost'] ?? 0;
                    $currentNewTotalCount += $data['current']['new_facilities']['total_count'] ?? 0;
                    $currentNewTotalCost += $data['current']['new_facilities']['total_cost'] ?? 0;
                    
                    // Current year improvements
                    $currentImprovePublicCount += $data['current']['improvements']['public_count'] ?? 0;
                    $currentImprovePrivateCount += $data['current']['improvements']['private_count'] ?? 0;
                    $currentImproveAssociationCount += $data['current']['improvements']['association_count'] ?? 0;
                    $currentImprovePublicCost += $data['current']['improvements']['public_cost'] ?? 0;
                    $currentImprovePrivateCost += $data['current']['improvements']['private_cost'] ?? 0;
                    $currentImproveAssociationCost += $data['current']['improvements']['association_cost'] ?? 0;
                    $currentImproveTotalCount += $data['current']['improvements']['total_count'] ?? 0;
                    $currentImproveTotalCost += $data['current']['improvements']['total_cost'] ?? 0;
                    
                    // Previous year new facilities
                    $previousNewPublicCount += $data['previous']['new_facilities']['public_count'] ?? 0;
                    $previousNewPrivateCount += $data['previous']['new_facilities']['private_count'] ?? 0;
                    $previousNewAssociationCount += $data['previous']['new_facilities']['association_count'] ?? 0;
                    $previousNewPublicCost += $data['previous']['new_facilities']['public_cost'] ?? 0;
                    $previousNewPrivateCost += $data['previous']['new_facilities']['private_cost'] ?? 0;
                    $previousNewAssociationCost += $data['previous']['new_facilities']['association_cost'] ?? 0;
                    $previousNewTotalCount += $data['previous']['new_facilities']['total_count'] ?? 0;
                    $previousNewTotalCost += $data['previous']['new_facilities']['total_cost'] ?? 0;
                    
                    // Previous year improvements
                    $previousImprovePublicCount += $data['previous']['improvements']['public_count'] ?? 0;
                    $previousImprovePrivateCount += $data['previous']['improvements']['private_count'] ?? 0;
                    $previousImproveAssociationCount += $data['previous']['improvements']['association_count'] ?? 0;
                    $previousImprovePublicCost += $data['previous']['improvements']['public_cost'] ?? 0;
                    $previousImprovePrivateCost += $data['previous']['improvements']['private_cost'] ?? 0;
                    $previousImproveAssociationCost += $data['previous']['improvements']['association_cost'] ?? 0;
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
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ number_format($totalCurrentCount) }}</h3>
                            <p>{{ __('Total Facilities') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-industry"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{{ number_format($currentNewTotalCount) }}</h3>
                            <p>{{ __('New Facilities') }}</p>
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
                            <p>{{ __('Facility Improvements') }}</p>
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
                <!-- New Facilities Investment Distribution -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-purple text-white">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('New Facilities Investment') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($newFacilityKeys) > 0)
                                <canvas id="newFacilitiesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-plus-square fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No new facility data available') }}</h5>
                                    <p class="text-muted">{{ __('Try selecting a different year') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Facility Improvements Investment Distribution -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Facility Improvements Investment') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($improvementFacilityKeys) > 0)
                                <canvas id="improvementsFacilityChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No facility improvement data available') }}</h5>
                                    <p class="text-muted">{{ __('Try selecting a different year') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Year Comparison Charts -->
            <div class="row mb-4">
                <!-- Sector Distribution Comparison Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Sector Distribution Comparison') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="sectorComparisonChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Investment Distribution Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                {{ __('Investment by Sector') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($currentNewTotalCost > 0 || $currentImproveTotalCost > 0)
                                <canvas id="sectorInvestmentChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
                        {{ __('Detailed Economic Transition Facilities Report') }}
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
                                    <th colspan="10" class="text-center">{{ $selectedYear }} ({{ __('New Facilities') }})</th>
                                    <th colspan="10" class="text-center">{{ $selectedYear }} ({{ __('Facility Improvements') }})</th>
                                </tr>
                                <tr>
                                    <!-- New Facilities Headers -->
                                    <th colspan="2">{{ __('Public') }}</th>
                                    <th colspan="2">{{ __('Private') }}</th>
                                    <th colspan="2">{{ __('Association') }}</th>
                                    <th colspan="2">{{ __('Total') }}</th>
                                    <th>{{ __('Institutions') }}</th>
                                    <th>{{ __('Projects') }}</th>
                                    
                                    <!-- Facility Improvements Headers -->
                                    <th colspan="2">{{ __('Public') }}</th>
                                    <th colspan="2">{{ __('Private') }}</th>
                                    <th colspan="2">{{ __('Association') }}</th>
                                    <th colspan="2">{{ __('Total') }}</th>
                                    <th>{{ __('Institutions') }}</th>
                                    <th>{{ __('Projects') }}</th>
                                </tr>
                                <tr>
                                    <!-- New Facilities Sub-headers -->
                                    <th>{{ __('Count') }}</th>
                                    <th>{{ __('Cost') }}</th>
                                    <th>{{ __('Count') }}</th>
                                    <th>{{ __('Cost') }}</th>
                                    <th>{{ __('Count') }}</th>
                                    <th>{{ __('Cost') }}</th>
                                    <th>{{ __('Count') }}</th>
                                    <th>{{ __('Cost') }}</th>
                                    <th></th>
                                    <th></th>
                                    
                                    <!-- Facility Improvements Sub-headers -->
                                    <th>{{ __('Count') }}</th>
                                    <th>{{ __('Cost') }}</th>
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

                                        <!-- Current Year New Facilities -->
                                        <td class="text-center">{{ number_format($data['current']['new_facilities']['public_count'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['new_facilities']['public_cost'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['new_facilities']['private_count'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['new_facilities']['private_cost'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['new_facilities']['association_count'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['new_facilities']['association_cost'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['new_facilities']['total_count'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['new_facilities']['total_cost'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['new_facilities']['institutions'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['new_facilities']['projects'] ?? 0) }}</td>

                                        <!-- Current Year Facility Improvements -->
                                        <td class="text-center">{{ number_format($data['current']['improvements']['public_count'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['improvements']['public_cost'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['improvements']['private_count'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['improvements']['private_cost'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['improvements']['association_count'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['improvements']['association_cost'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['improvements']['total_count'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['improvements']['total_cost'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['improvements']['institutions'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['improvements']['projects'] ?? 0) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="21" class="text-center text-muted py-4">
                                            <i class="fas fa-info-circle me-2"></i>{{ __('No data found for selected year') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if(count($reportData) > 0)
                            <tfoot class="bg-light">
                                <tr>
                                    <td class="fw-bold">{{ __('TOTAL') }}</td>
                                    
                                    <!-- Current Year New Facilities Totals -->
                                    <td class="text-center fw-bold">{{ number_format($currentNewPublicCount) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentNewPublicCost) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentNewPrivateCount) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentNewPrivateCost) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentNewAssociationCount) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentNewAssociationCost) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentNewTotalCount) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentNewTotalCost) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'current'), 'new_facilities_institutions')) ?? 0) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'current'), 'new_facilities_projects')) ?? 0) }}</td>
                                    
                                    <!-- Current Year Facility Improvements Totals -->
                                    <td class="text-center fw-bold">{{ number_format($currentImprovePublicCount) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentImprovePublicCost) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentImprovePrivateCount) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentImprovePrivateCost) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentImproveAssociationCount) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($currentImproveAssociationCost) }}</td>
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

            <!-- New Facilities Details Table -->
            @if(count($newFacilityData['current']) > 0)
            <div class="card mt-4">
                <div class="card-header bg-purple text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-plus-square mr-1"></i>
                        {{ __('New Facilities Details') }} ({{ $selectedYear }})
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Facility / Institution') }}</th>
                                    <th class="text-center" colspan="2">{{ __('Public') }}</th>
                                    <th class="text-center" colspan="2">{{ __('Private') }}</th>
                                    <th class="text-center" colspan="2">{{ __('Association') }}</th>
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
                                    <th class="text-right">{{ __('Count') }}</th>
                                    <th class="text-right">{{ __('Cost') }}</th>
                                    <th class="text-right">{{ __('Per Unit') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($newFacilityKeys as $index => $key)
                                    @php
                                        $publicCount = $newFacilityData['current'][$key]['public_count'] ?? 0;
                                        $publicCost = $newFacilityData['current'][$key]['public_cost'] ?? 0;
                                        $privateCount = $newFacilityData['current'][$key]['private_count'] ?? 0;
                                        $privateCost = $newFacilityData['current'][$key]['private_cost'] ?? 0;
                                        $associationCount = $newFacilityData['current'][$key]['association_count'] ?? 0;
                                        $associationCost = $newFacilityData['current'][$key]['association_cost'] ?? 0;
                                        $totalCount = $publicCount + $privateCount + $associationCount;
                                        $totalCost = $publicCost + $privateCost + $associationCost;
                                        
                                        $avgPublicCost = $publicCount > 0 ? $publicCost / $publicCount : 0;
                                        $avgPrivateCost = $privateCount > 0 ? $privateCost / $privateCount : 0;
                                        $avgAssociationCost = $associationCount > 0 ? $associationCost / $associationCount : 0;
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
                                        <td class="text-right">{{ number_format($associationCount) }}</td>
                                        <td class="text-right">{{ number_format($associationCost) }}</td>
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
                                    <td colspan="2">{{ __('TOTAL NEW FACILITIES') }}</td>
                                    <td class="text-right">{{ number_format($currentNewPublicCount) }}</td>
                                    <td class="text-right">{{ number_format($currentNewPublicCost) }}</td>
                                    <td class="text-right">{{ number_format($currentNewPrivateCount) }}</td>
                                    <td class="text-right">{{ number_format($currentNewPrivateCost) }}</td>
                                    <td class="text-right">{{ number_format($currentNewAssociationCount) }}</td>
                                    <td class="text-right">{{ number_format($currentNewAssociationCost) }}</td>
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

            <!-- Facility Improvements Details Table -->
            @if(count($improvementFacilityData['current']) > 0)
            <div class="card mt-4">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-tools mr-1"></i>
                        {{ __('Facility Improvements Details') }} ({{ $selectedYear }})
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Facility / Institution') }}</th>
                                    <th class="text-center" colspan="2">{{ __('Public') }}</th>
                                    <th class="text-center" colspan="2">{{ __('Private') }}</th>
                                    <th class="text-center" colspan="2">{{ __('Association') }}</th>
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
                                    <th class="text-right">{{ __('Count') }}</th>
                                    <th class="text-right">{{ __('Cost') }}</th>
                                    <th class="text-right">{{ __('Per Unit') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($improvementFacilityKeys as $index => $key)
                                    @php
                                        $publicCount = $improvementFacilityData['current'][$key]['public_count'] ?? 0;
                                        $publicCost = $improvementFacilityData['current'][$key]['public_cost'] ?? 0;
                                        $privateCount = $improvementFacilityData['current'][$key]['private_count'] ?? 0;
                                        $privateCost = $improvementFacilityData['current'][$key]['private_cost'] ?? 0;
                                        $associationCount = $improvementFacilityData['current'][$key]['association_count'] ?? 0;
                                        $associationCost = $improvementFacilityData['current'][$key]['association_cost'] ?? 0;
                                        $totalCount = $publicCount + $privateCount + $associationCount;
                                        $totalCost = $publicCost + $privateCost + $associationCost;
                                        
                                        $avgPublicCost = $publicCount > 0 ? $publicCost / $publicCount : 0;
                                        $avgPrivateCost = $privateCount > 0 ? $privateCost / $privateCount : 0;
                                        $avgAssociationCost = $associationCount > 0 ? $associationCost / $associationCount : 0;
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
                                        <td class="text-right">{{ number_format($associationCount) }}</td>
                                        <td class="text-right">{{ number_format($associationCost) }}</td>
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
                                    <td colspan="2">{{ __('TOTAL FACILITY IMPROVEMENTS') }}</td>
                                    <td class="text-right">{{ number_format($currentImprovePublicCount) }}</td>
                                    <td class="text-right">{{ number_format($currentImprovePublicCost) }}</td>
                                    <td class="text-right">{{ number_format($currentImprovePrivateCount) }}</td>
                                    <td class="text-right">{{ number_format($currentImprovePrivateCost) }}</td>
                                    <td class="text-right">{{ number_format($currentImproveAssociationCount) }}</td>
                                    <td class="text-right">{{ number_format($currentImproveAssociationCost) }}</td>
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
    const currentNewAssociationCount = @json($currentNewAssociationCount);
    const currentNewPublicCost = @json($currentNewPublicCost);
    const currentNewPrivateCost = @json($currentNewPrivateCost);
    const currentNewAssociationCost = @json($currentNewAssociationCost);
    const currentNewTotalCount = @json($currentNewTotalCount);
    const currentNewTotalCost = @json($currentNewTotalCost);
    
    const currentImprovePublicCount = @json($currentImprovePublicCount);
    const currentImprovePrivateCount = @json($currentImprovePrivateCount);
    const currentImproveAssociationCount = @json($currentImproveAssociationCount);
    const currentImprovePublicCost = @json($currentImprovePublicCost);
    const currentImprovePrivateCost = @json($currentImprovePrivateCost);
    const currentImproveAssociationCost = @json($currentImproveAssociationCost);
    const currentImproveTotalCount = @json($currentImproveTotalCount);
    const currentImproveTotalCost = @json($currentImproveTotalCost);
    
    const previousNewPublicCount = @json($previousNewPublicCount);
    const previousNewPrivateCount = @json($previousNewPrivateCount);
    const previousNewAssociationCount = @json($previousNewAssociationCount);
    const previousNewPublicCost = @json($previousNewPublicCost);
    const previousNewPrivateCost = @json($previousNewPrivateCost);
    const previousNewAssociationCost = @json($previousNewAssociationCost);
    const previousNewTotalCount = @json($previousNewTotalCount);
    const previousNewTotalCost = @json($previousNewTotalCost);
    
    const previousImprovePublicCount = @json($previousImprovePublicCount);
    const previousImprovePrivateCount = @json($previousImprovePrivateCount);
    const previousImproveAssociationCount = @json($previousImproveAssociationCount);
    const previousImprovePublicCost = @json($previousImprovePublicCost);
    const previousImprovePrivateCost = @json($previousImprovePrivateCost);
    const previousImproveAssociationCost = @json($previousImproveAssociationCost);
    const previousImproveTotalCount = @json($previousImproveTotalCount);
    const previousImproveTotalCost = @json($previousImproveTotalCost);
    
    // Facility data
    const newFacilityKeys = @json($newFacilityKeys);
    const newFacilityTotalCosts = @json($newFacilityTotalCosts);
    const improvementFacilityKeys = @json($improvementFacilityKeys);
    const improvementTotalCosts = @json($improvementTotalCosts);

    // Color palette
    const facilityColors = [
        'rgba(111, 66, 193, 0.7)',   // Purple
        'rgba(40, 167, 69, 0.7)',    // Green
        'rgba(0, 123, 255, 0.7)',    // Blue
        'rgba(255, 193, 7, 0.7)',    // Yellow
        'rgba(220, 53, 69, 0.7)',    // Red
        'rgba(23, 162, 184, 0.7)',   // Teal
        'rgba(108, 117, 125, 0.7)',  // Gray
        'rgba(253, 126, 20, 0.7)',   // Orange
        'rgba(32, 201, 151, 0.7)',   // Mint
        'rgba(233, 30, 99, 0.7)',    // Pink
    ];

    // Chart 1: New Facilities Investment
    if (newFacilityKeys.length > 0) {
        const newFacilitiesCtx = document.getElementById('newFacilitiesChart').getContext('2d');
        new Chart(newFacilitiesCtx, {
            type: 'bar',
            data: {
                labels: newFacilityKeys,
                datasets: [{
                    label: `{{ __('Total Investment') }} (${selectedYear})`,
                    data: newFacilityTotalCosts,
                    backgroundColor: 'rgba(111, 66, 193, 0.7)',
                    borderColor: 'rgba(111, 66, 193, 1)',
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
                                const total = newFacilityTotalCosts.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed.y / total) * 100).toFixed(1) : 0;
                                return `${context.dataset.label}: ${context.parsed.y.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Chart 2: Facility Improvements Investment
    if (improvementFacilityKeys.length > 0) {
        const improvementsFacilityCtx = document.getElementById('improvementsFacilityChart').getContext('2d');
        new Chart(improvementsFacilityCtx, {
            type: 'bar',
            data: {
                labels: improvementFacilityKeys,
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

    // Chart 3: Sector Distribution Comparison Chart
    const sectorComparisonCtx = document.getElementById('sectorComparisonChart').getContext('2d');
    new Chart(sectorComparisonCtx, {
        type: 'bar',
        data: {
            labels: ['{{ __('New Public') }}', '{{ __('New Private') }}', '{{ __('New Association') }}', 
                     '{{ __('Improve Public') }}', '{{ __('Improve Private') }}', '{{ __('Improve Association') }}'],
            datasets: [
                {
                    label: `{{ __('Current Year') }} (${selectedYear})`,
                    data: [
                        currentNewPublicCount, currentNewPrivateCount, currentNewAssociationCount,
                        currentImprovePublicCount, currentImprovePrivateCount, currentImproveAssociationCount
                    ],
                    backgroundColor: [
                        'rgba(111, 66, 193, 0.7)',
                        'rgba(255, 193, 7, 0.7)',
                        'rgba(0, 123, 255, 0.7)',
                        'rgba(40, 167, 69, 0.7)',
                        'rgba(23, 162, 184, 0.7)',
                        'rgba(108, 117, 125, 0.7)'
                    ],
                    borderColor: [
                        'rgba(111, 66, 193, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(0, 123, 255, 1)',
                        'rgba(40, 167, 69, 1)',
                        'rgba(23, 162, 184, 1)',
                        'rgba(108, 117, 125, 1)'
                    ],
                    borderWidth: 1
                },
                {
                    label: `{{ __('Previous Year') }} (${previousYear})`,
                    data: [
                        previousNewPublicCount, previousNewPrivateCount, previousNewAssociationCount,
                        previousImprovePublicCount, previousImprovePrivateCount, previousImproveAssociationCount
                    ],
                    backgroundColor: [
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 205, 86, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(201, 203, 207, 0.7)',
                        'rgba(255, 99, 132, 0.7)'
                    ],
                    borderColor: [
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(201, 203, 207, 1)',
                        'rgba(255, 99, 132, 1)'
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

    // Chart 4: Sector Investment Pie Chart
    if (currentNewTotalCost > 0 || currentImproveTotalCost > 0) {
        const sectorInvestmentCtx = document.getElementById('sectorInvestmentChart').getContext('2d');
        new Chart(sectorInvestmentCtx, {
            type: 'pie',
            data: {
                labels: [
                    '{{ __('New Public') }}',
                    '{{ __('New Private') }}',
                    '{{ __('New Association') }}',
                    '{{ __('Improve Public') }}',
                    '{{ __('Improve Private') }}',
                    '{{ __('Improve Association') }}'
                ],
                datasets: [{
                    data: [
                        currentNewPublicCost, 
                        currentNewPrivateCost, 
                        currentNewAssociationCost,
                        currentImprovePublicCost, 
                        currentImprovePrivateCost, 
                        currentImproveAssociationCost
                    ],
                    backgroundColor: [
                        'rgba(111, 66, 193, 0.7)',
                        'rgba(255, 193, 7, 0.7)',
                        'rgba(0, 123, 255, 0.7)',
                        'rgba(40, 167, 69, 0.7)',
                        'rgba(23, 162, 184, 0.7)',
                        'rgba(108, 117, 125, 0.7)'
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
    .bg-purple { background-color: #6f42c1 !important; }
    .bg-success { background-color: #28a745 !important; }
    .bg-warning { background-color: #ffc107 !important; }
    
    .card-header.bg-purple .card-tools .btn-tool,
    .card-header.bg-success .card-tools .btn-tool {
        color: rgba(255,255,255,0.8) !important;
    }
    
    .card-header.bg-purple .card-tools .btn-tool:hover,
    .card-header.bg-success .card-tools .btn-tool:hover {
        color: white !important;
    }
    
    .btn-purple {
        background-color: #6f42c1 !important;
        border-color: #6f42c1 !important;
        color: white !important;
    }
    
    .btn-purple:hover {
        background-color: #5a32a3 !important;
        border-color: #5a32a3 !important;
    }
    
    table th, table td {
        vertical-align: middle !important;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(111, 66, 193, 0.05);
    }
</style>

</x-layout>