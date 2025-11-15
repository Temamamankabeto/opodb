<x-layout>
    <div class="container-fluid py-4 crop-plans-container">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="page-header">
                <h2 class="mb-0"><i class="mdi mdi-sprout text-success me-2"></i> Regional Crop Plans Summary</h2>
                <p class="text-muted mb-0 mt-1">Aggregated crop plans from all zones, woredas, and kebeles under this region</p>
            </div>
            <div class="regional-badge bg-primary text-white px-3 py-2 rounded-pill">
                <i class="mdi mdi-earth me-1"></i>
                {{ Auth::user()->region->name ?? 'Region' }} Administration
            </div>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
                <div class="d-flex align-items-center">
                    <i class="mdi mdi-check-circle-outline me-2 fs-5"></i>
                    <span class="flex-grow-1">{{ session('success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card summary-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-muted mb-2">Total Zones</h6>
                                <h3 class="mb-0 text-primary">{{ count($zoneTotals) }}</h3>
                                <small class="text-muted">Under this region</small>
                            </div>
                            <div class="icon-circle bg-primary text-white">
                                <i class="mdi mdi-map-marker-radius fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card summary-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-muted mb-2">Total Woredas</h6>
                                <h3 class="mb-0 text-info">{{ count($woredaTotals) }}</h3>
                                <small class="text-muted">Participating</small>
                            </div>
                            <div class="icon-circle bg-info text-white">
                                <i class="mdi mdi-office-building fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card summary-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-muted mb-2">Total Kebeles</h6>
                                <h3 class="mb-0 text-success">{{ count($kebeleTotals) }}</h3>
                                <small class="text-muted">Across region</small>
                            </div>
                            <div class="icon-circle bg-success text-white">
                                <i class="mdi mdi-home-variant fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card summary-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-muted mb-2">Total Area (ha)</h6>
                                <h3 class="mb-0 text-warning">{{ number_format($grandTotalArea, 2) }}</h3>
                                <small class="text-muted">Planted area</small>
                            </div>
                            <div class="icon-circle bg-warning text-white">
                                <i class="mdi mdi-earth fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card summary-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-muted mb-2">Total Yield (qt)</h6>
                                <h3 class="mb-0 text-danger">{{ number_format($grandTotalYield, 2) }}</h3>
                                <small class="text-muted">Expected production</small>
                            </div>
                            <div class="icon-circle bg-danger text-white">
                                <i class="mdi mdi-weight fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Zone Performance Summary -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 text-dark">
                            <i class="mdi mdi-chart-bar me-2 text-primary"></i>
                            Zone Performance Summary
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Zone</th>
                                        <th class="text-center">Woredas</th>
                                        <th class="text-center">Kebeles</th>
                                        <th class="text-center">Crop Plans</th>
                                        <th class="text-end">Total Area (ha)</th>
                                        <th class="text-end">Total Yield (qt)</th>
                                        <th class="text-end">Avg Yield/Ha</th>
                                        <th class="text-center">Performance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($zoneTotals as $zoneId => $data)
                                        @php
                                            $avgYieldPerHa = $data['total_area'] > 0 ? 
                                                $data['total_yield'] / $data['total_area'] : 0;
                                            $performance = $avgYieldPerHa > 50 ? 'high' : ($avgYieldPerHa > 25 ? 'medium' : 'low');
                                        @endphp
                                        <tr>
                                            <td class="ps-4 fw-semibold">
                                                <div class="d-flex align-items-center">
                                                    <div class="zone-icon me-2">
                                                        <i class="mdi mdi-map-marker-radius text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <span>{{ $data['zone_name'] }}</span>
                                                        <small class="text-muted d-block">{{ $data['woreda_count'] }} woredas, {{ $data['kebele_count'] }} kebeles</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info text-white">{{ $data['woreda_count'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success text-white">{{ $data['kebele_count'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark">{{ $data['plan_count'] }}</span>
                                            </td>
                                            <td class="text-end fw-bold text-warning">
                                                {{ number_format($data['total_area'], 2) }}
                                            </td>
                                            <td class="text-end fw-bold text-danger">
                                                {{ number_format($data['total_yield'], 2) }}
                                            </td>
                                            <td class="text-end">
                                                <span class="fw-semibold {{ $performance == 'high' ? 'text-success' : ($performance == 'medium' ? 'text-warning' : 'text-danger') }}">
                                                    {{ number_format($avgYieldPerHa, 2) }} qt/ha
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($performance == 'high')
                                                    <i class="mdi mdi-trending-up text-success fs-5" title="High Performance"></i>
                                                @elseif($performance == 'medium')
                                                    <i class="mdi mdi-trending-neutral text-warning fs-5" title="Medium Performance"></i>
                                                @else
                                                    <i class="mdi mdi-trending-down text-danger fs-5" title="Low Performance"></i>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Section -->
        <div class="search-container mb-4">
            <div class="input-group search-input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="mdi mdi-magnify text-muted"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-start-0"
                    placeholder="Search by zone, woreda, kebele, crop, crop type, season, or year...">
            </div>
        </div>

        @if ($cropPlans->isEmpty())
            <!-- Empty State -->
            <div class="empty-state text-center py-5">
                <i class="mdi mdi-information-outline display-4 text-muted mb-3"></i>
                <h4 class="text-muted">No crop plans found</h4>
                <p class="text-muted mb-4">No zones under this region have submitted crop plans yet</p>
            </div>
        @else
            @php
                $currentYear = max($cropPlans->pluck('year')->toArray());
                $previousYear = $currentYear - 1;
                
                $currentYearData = $cropPlans->where('year', $currentYear)->groupBy('crop_id');
                $previousYearData = $cropPlans->where('year', $previousYear)->groupBy('crop_id');
                
                // Calculate fertilizer data
                $currentYearPlans = $cropPlans->where('year', $currentYear);
                
                // Per Crop Fertilizer
                $cropFertilizers = [];
                foreach($currentYearPlans as $plan) {
                    $cropName = $plan->crop->name ?? 'Unknown';
                    $fertilizerDetails = $plan->fertilizer_details ?? [];
                    
                    if(!isset($cropFertilizers[$cropName])) {
                        $cropFertilizers[$cropName] = [
                            'total_fertilizer' => 0,
                            'total_area' => 0,
                            'plans' => 0
                        ];
                    }
                    
                    $totalFertilizer = 0;
                    if(is_array($fertilizerDetails)) {
                        foreach($fertilizerDetails as $fert) {
                            $totalFertilizer += ($fert['amount'] ?? 0);
                        }
                    }
                    
                    $cropFertilizers[$cropName]['total_fertilizer'] += $totalFertilizer;
                    $cropFertilizers[$cropName]['total_area'] += $plan->area_to_plant;
                    $cropFertilizers[$cropName]['plans']++;
                }
                
                // Per Crop Type Fertilizer
                $typeFertilizers = [];
                foreach($currentYearPlans as $plan) {
                    $typeName = $plan->cropType->name ?? 'Unknown';
                    $fertilizerDetails = $plan->fertilizer_details ?? [];
                    
                    if(!isset($typeFertilizers[$typeName])) {
                        $typeFertilizers[$typeName] = [
                            'total_fertilizer' => 0,
                            'total_area' => 0,
                            'plans' => 0
                        ];
                    }
                    
                    $totalFertilizer = 0;
                    if(is_array($fertilizerDetails)) {
                        foreach($fertilizerDetails as $fert) {
                            $totalFertilizer += ($fert['amount'] ?? 0);
                        }
                    }
                    
                    $typeFertilizers[$typeName]['total_fertilizer'] += $totalFertilizer;
                    $typeFertilizers[$typeName]['total_area'] += $plan->area_to_plant;
                    $typeFertilizers[$typeName]['plans']++;
                }
            @endphp

            <!-- Data Table -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 text-dark">
                        <i class="mdi mdi-table me-2 text-primary"></i>
                        Regional Crop Plans Overview (Aggregated from all Zones, Woredas and Kebeles)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="cropPlansTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Zone</th>
                                    <th>Woreda</th>
                                    <th>Kebele</th>
                                    <th>Year</th>
                                    <th>Crop Type</th>
                                    <th>Season</th>
                                    <th>Crop</th>
                                    <th class="text-end">Area (ha)</th>
                                    <th class="text-center">Productivity</th>
                                    <th class="text-end">Expected Yield (qt)</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cropPlans->groupBy('kebele.woreda.zone_id') as $zoneId => $plansByZone)
                                    @php
                                        $zone = $plansByZone->first()->kebele->woreda->zone;
                                        $zoneTotalArea = 0;
                                        $zoneTotalYield = 0;
                                        $zoneDataRows = $plansByZone->count();
                                        $zoneDisplayed = false;
                                    @endphp

                                    @foreach ($plansByZone->groupBy('kebele.woreda_id') as $woredaId => $plansByWoreda)
                                        @php
                                            $woreda = $plansByWoreda->first()->kebele->woreda;
                                            $woredaTotalArea = 0;
                                            $woredaTotalYield = 0;
                                            $woredaDataRows = $plansByWoreda->count();
                                            $woredaDisplayed = false;
                                        @endphp

                                        @foreach ($plansByWoreda->groupBy('kebele_id') as $kebeleId => $plansByKebele)
                                            @php
                                                $kebele = $plansByKebele->first()->kebele;
                                                $kebeleTotalArea = 0;
                                                $kebeleTotalYield = 0;
                                                $kebeleDataRows = $plansByKebele->count();
                                                $kebeleDisplayed = false;
                                            @endphp

                                            @foreach ($plansByKebele->groupBy('year') as $year => $plansByYear)
                                                @php
                                                    $yearTotalArea = 0;
                                                    $yearTotalYield = 0;
                                                    $yearDataRows = $plansByYear->count();
                                                    $yearDisplayed = false;
                                                @endphp

                                                @foreach ($plansByYear->groupBy('croptype_id') as $typeId => $plansByType)
                                                    @php
                                                        $cropType = $plansByType->first()->cropType;
                                                        $cropTypeTotalArea = 0;
                                                        $cropTypeTotalYield = 0;
                                                        $cropTypeDataRows = $plansByType->count();
                                                        $cropTypeDisplayed = false;
                                                    @endphp

                                                    @foreach ($plansByType->groupBy('season') as $season => $plansBySeason)
                                                        @php
                                                            $seasonTotalArea = 0;
                                                            $seasonTotalYield = 0;
                                                            $seasonFirstRow = true;
                                                        @endphp

                                                        @foreach ($plansBySeason as $index => $plan)
                                                            <tr class="data-row {{ $index % 2 == 0 ? 'table-row-light' : '' }}">
                                                                {{-- Zone Column --}}
                                                                @if (!$zoneDisplayed)
                                                                    <td rowspan="{{ $zoneDataRows }}" class="fw-bold zone-cell ps-4">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="zone-icon me-2">
                                                                                <i class="mdi mdi-map-marker-radius text-primary"></i>
                                                                            </div>
                                                                            <div>
                                                                                <div class="fw-bold">{{ $zone->name }}</div>
                                                                                <small class="text-muted">{{ $zone->zone_code ?? 'Zone' }}</small>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    @php $zoneDisplayed = true; @endphp
                                                                @endif

                                                                {{-- Woreda Column --}}
                                                                @if (!$woredaDisplayed)
                                                                    <td class="fw-bold woreda-cell">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="woreda-icon me-2">
                                                                                <i class="mdi mdi-office-building text-info"></i>
                                                                            </div>
                                                                            <div>
                                                                                <div class="fw-bold">{{ $woreda->name }}</div>
                                                                                <small class="text-muted">{{ $woreda->woreda_code ?? 'Woreda' }}</small>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    @php $woredaDisplayed = true; @endphp
                                                                @else
                                                                    <td></td>
                                                                @endif

                                                                {{-- Kebele Column --}}
                                                                @if (!$kebeleDisplayed)
                                                                    <td class="fw-bold kebele-cell">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="kebele-icon me-2">
                                                                                <i class="mdi mdi-home-variant text-success"></i>
                                                                            </div>
                                                                            <div>
                                                                                <div class="fw-bold">{{ $kebele->name }}</div>
                                                                                <small class="text-muted">{{ $kebele->kebele_code ?? 'Kebele' }}</small>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    @php $kebeleDisplayed = true; @endphp
                                                                @else
                                                                    <td></td>
                                                                @endif

                                                                {{-- Year Column --}}
                                                                @if (!$yearDisplayed)
                                                                    <td class="fw-bold text-center year-cell">
                                                                        <div class="year-badge">{{ $year }}</div>
                                                                    </td>
                                                                    @php $yearDisplayed = true; @endphp
                                                                @else
                                                                    <td></td>
                                                                @endif

                                                                {{-- Crop Type Column --}}
                                                                @if (!$cropTypeDisplayed && $seasonFirstRow)
                                                                    <td class="fw-bold crop-type-cell">
                                                                        <div class="crop-type-indicator"></div>
                                                                        {{ $cropType->name ?? '-' }}
                                                                    </td>
                                                                    @php $cropTypeDisplayed = true; @endphp
                                                                @else
                                                                    <td></td>
                                                                @endif

                                                                {{-- Season Column --}}
                                                                @if ($seasonFirstRow)
                                                                    <td>
                                                                        <span class="season-badge season-{{ strtolower($season) }}">
                                                                            <i class="mdi mdi-weather-sunny me-1"></i>
                                                                            {{ ucfirst($season) }}
                                                                        </span>
                                                                    </td>
                                                                    @php $seasonFirstRow = false; @endphp
                                                                @else
                                                                    <td></td>
                                                                @endif

                                                                {{-- Crop Details --}}
                                                                <td class="crop-name">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="crop-icon me-2">
                                                                            <i class="mdi mdi-sprout text-success"></i>
                                                                        </div>
                                                                        <span>{{ $plan->crop->name ?? '-' }}</span>
                                                                    </div>
                                                                </td>
                                                                <td class="text-end fw-semibold">{{ number_format($plan->area_to_plant, 2) }}</td>
                                                                <td class="text-center">
                                                                    <span class="productivity-badge">{{ $plan->productivity ?? '-' }}</span>
                                                                </td>
                                                                <td class="text-end fw-semibold text-success">{{ number_format($plan->expected_yield, 2) }}</td>
                                                                <td class="text-center">
                                                                    <div class="dropdown action-dropdown">
                                                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button"
                                                                            id="dropdownMenuButton{{ $plan->id }}"
                                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <i class="mdi mdi-cog"></i>
                                                                        </button>
                                                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                                                                            aria-labelledby="dropdownMenuButton{{ $plan->id }}">
                                                                            <li>
                                                                                <a class="dropdown-item text-primary" href="#">
                                                                                    <i class="mdi mdi-eye-outline me-2"></i> View Details
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a class="dropdown-item text-info" href="#">
                                                                                    <i class="mdi mdi-information-outline me-2"></i> Location Info
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            @php
                                                                $seasonTotalArea += $plan->area_to_plant;
                                                                $seasonTotalYield += $plan->expected_yield;
                                                                $cropTypeTotalArea += $plan->area_to_plant;
                                                                $cropTypeTotalYield += $plan->expected_yield;
                                                                $yearTotalArea += $plan->area_to_plant;
                                                                $yearTotalYield += $plan->expected_yield;
                                                                $kebeleTotalArea += $plan->area_to_plant;
                                                                $kebeleTotalYield += $plan->expected_yield;
                                                                $woredaTotalArea += $plan->area_to_plant;
                                                                $woredaTotalYield += $plan->expected_yield;
                                                                $zoneTotalArea += $plan->area_to_plant;
                                                                $zoneTotalYield += $plan->expected_yield;
                                                            @endphp
                                                        @endforeach
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Grand Total Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card grand-total-card border-0 shadow-sm">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0 text-white">
                                        <i class="mdi mdi-chart-bar me-2"></i>
                                        Regional Grand Totals
                                    </h5>
                                    <small class="text-white-50">
                                        Aggregated from {{ count($zoneTotals) }} zones, {{ count($woredaTotals) }} woredas, and {{ count($kebeleTotals) }} kebeles
                                    </small>
                                </div>
                                <div class="d-flex gap-4">
                                    <div class="text-center">
                                        <div class="text-white-50 small">Total Area</div>
                                        <div class="h4 mb-0 text-white">{{ number_format($grandTotalArea, 2) }} ha</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-white-50 small">Total Expected Yield</div>
                                        <div class="h4 mb-0 text-white">{{ number_format($grandTotalYield, 2) }} qt</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-white-50 small">Avg per Zone</div>
                                        <div class="h4 mb-0 text-white">
                                            {{ count($zoneTotals) > 0 ? number_format($grandTotalArea / count($zoneTotals), 2) : 0 }} ha
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row">
                <!-- Bar Chart - Crop Area by Year -->
                <div class="col-xl-8 col-lg-7 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="mb-0 text-dark">
                                <i class="mdi mdi-chart-bar me-2 text-primary"></i>
                                Regional Crop Area Distribution by Year (Hectares)
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="cropAreaBarChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pie Chart - Zone Distribution -->
                <div class="col-xl-4 col-lg-5 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="mb-0 text-dark">
                                <i class="mdi mdi-chart-pie me-2 text-success"></i>
                                Area Distribution by Zone
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="zoneDistributionChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Charts Row -->
            <div class="row">
                <!-- Yield by Crop Type -->
                <div class="col-xl-6 col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="mb-0 text-dark">
                                <i class="mdi mdi-weight me-2 text-warning"></i>
                                Expected Yield by Crop Type (Quintal)
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="yieldByCropTypeChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seasonal Distribution -->
                <div class="col-xl-6 col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="mb-0 text-dark">
                                <i class="mdi mdi-seasons me-2 text-info"></i>
                                Seasonal Distribution
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="seasonalDistributionChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Productivity Comparison Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-gradient-info text-white py-3">
                            <h5 class="mb-0">
                                <i class="mdi mdi-trending-up me-2"></i>
                                Regional Productivity Comparison - Current Year vs Previous Year (Per Crop)
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Crop</th>
                                            <th class="text-center">Crop Type</th>
                                            <th class="text-center">Current Year ({{ $currentYear }})</th>
                                            <th class="text-center">Previous Year ({{ $previousYear }})</th>
                                            <th class="text-center">Change</th>
                                            <th class="text-center">Trend</th>
                                            <th class="text-center">Zones</th>
                                            <th class="text-center">Woredas</th>
                                            <th class="text-center">Kebeles</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // Get unique crops from current year data across all zones, woredas and kebeles
                                            $cropsToDisplay = [];
                                            
                                            // Process current year crops
                                            foreach($currentYearData as $cropId => $plans) {
                                                $crop = $plans->first()->crop;
                                                if ($crop) {
                                                    $cropsToDisplay[$cropId] = [
                                                        'crop' => $crop,
                                                        'crop_type' => $plans->first()->cropType,
                                                        'current_avg' => $plans->avg('productivity'),
                                                        'current_area' => $plans->sum('area_to_plant'),
                                                        'current_yield' => $plans->sum('expected_yield'),
                                                        'zone_count' => $plans->groupBy('kebele.woreda.zone_id')->count(),
                                                        'woreda_count' => $plans->groupBy('kebele.woreda_id')->count(),
                                                        'kebele_count' => $plans->groupBy('kebele_id')->count(),
                                                        'zones' => $plans->pluck('kebele.woreda.zone.name')->unique()->toArray(),
                                                        'woredas' => $plans->pluck('kebele.woreda.name')->unique()->toArray(),
                                                        'kebeles' => $plans->pluck('kebele.name')->unique()->toArray()
                                                    ];
                                                }
                                            }
                                            
                                            // Process previous year crops and add to display
                                            foreach($previousYearData as $cropId => $plans) {
                                                $crop = $plans->first()->crop;
                                                if ($crop && !isset($cropsToDisplay[$cropId])) {
                                                    $cropsToDisplay[$cropId] = [
                                                        'crop' => $crop,
                                                        'crop_type' => $plans->first()->cropType,
                                                        'current_avg' => 0,
                                                        'current_area' => 0,
                                                        'current_yield' => 0,
                                                        'previous_avg' => $plans->avg('productivity'),
                                                        'previous_area' => $plans->sum('area_to_plant'),
                                                        'previous_yield' => $plans->sum('expected_yield'),
                                                        'zone_count' => $plans->groupBy('kebele.woreda.zone_id')->count(),
                                                        'woreda_count' => $plans->groupBy('kebele.woreda_id')->count(),
                                                        'kebele_count' => $plans->groupBy('kebele_id')->count(),
                                                        'zones' => $plans->pluck('kebele.woreda.zone.name')->unique()->toArray(),
                                                        'woredas' => $plans->pluck('kebele.woreda.name')->unique()->toArray(),
                                                        'kebeles' => $plans->pluck('kebele.name')->unique()->toArray()
                                                    ];
                                                } elseif (isset($cropsToDisplay[$cropId])) {
                                                    $cropsToDisplay[$cropId]['previous_avg'] = $plans->avg('productivity');
                                                    $cropsToDisplay[$cropId]['previous_area'] = $plans->sum('area_to_plant');
                                                    $cropsToDisplay[$cropId]['previous_yield'] = $plans->sum('expected_yield');
                                                    $cropsToDisplay[$cropId]['zone_count'] += $plans->groupBy('kebele.woreda.zone_id')->count();
                                                    $cropsToDisplay[$cropId]['woreda_count'] += $plans->groupBy('kebele.woreda_id')->count();
                                                    $cropsToDisplay[$cropId]['kebele_count'] += $plans->groupBy('kebele_id')->count();
                                                    $cropsToDisplay[$cropId]['zones'] = array_unique(array_merge(
                                                        $cropsToDisplay[$cropId]['zones'] ?? [],
                                                        $plans->pluck('kebele.woreda.zone.name')->unique()->toArray()
                                                    ));
                                                    $cropsToDisplay[$cropId]['woredas'] = array_unique(array_merge(
                                                        $cropsToDisplay[$cropId]['woredas'] ?? [],
                                                        $plans->pluck('kebele.woreda.name')->unique()->toArray()
                                                    ));
                                                    $cropsToDisplay[$cropId]['kebeles'] = array_unique(array_merge(
                                                        $cropsToDisplay[$cropId]['kebeles'] ?? [],
                                                        $plans->pluck('kebele.name')->unique()->toArray()
                                                    ));
                                                }
                                            }
                                        @endphp
                                        
                                        @forelse($cropsToDisplay as $cropData)
                                            @php
                                                $crop = $cropData['crop'];
                                                $cropType = $cropData['crop_type'];
                                                $currentAvg = $cropData['current_avg'] ?? 0;
                                                $previousAvg = $cropData['previous_avg'] ?? 0;
                                                $currentArea = $cropData['current_area'] ?? 0;
                                                $previousArea = $cropData['previous_area'] ?? 0;
                                                $zoneCount = $cropData['zone_count'] ?? 0;
                                                $woredaCount = $cropData['woreda_count'] ?? 0;
                                                $kebeleCount = $cropData['kebele_count'] ?? 0;
                                                $zones = $cropData['zones'] ?? [];
                                                $woredas = $cropData['woredas'] ?? [];
                                                $kebeles = $cropData['kebeles'] ?? [];
                                                
                                                $change = $previousAvg > 0 ? (($currentAvg - $previousAvg) / $previousAvg) * 100 : 0;
                                                $hasPreviousYear = $previousAvg > 0;
                                                $hasCurrentYear = $currentAvg > 0;
                                            @endphp
                                            <tr>
                                                <td class="ps-4 fw-semibold">
                                                    <div class="d-flex align-items-center">
                                                        <div class="crop-icon me-2">
                                                            <i class="mdi mdi-sprout text-success"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold">{{ $crop->name ?? 'Unknown Crop' }}</div>
                                                            @if($hasCurrentYear && $hasPreviousYear)
                                                                <small class="text-muted">
                                                                    Area: {{ number_format($currentArea, 1) }}ha 
                                                                    ({{ $currentArea > $previousArea ? '↑' : '↓' }}
                                                                    {{ number_format(abs($currentArea - $previousArea), 1) }}ha)
                                                                </small>
                                                            @elseif($hasCurrentYear)
                                                                <small class="text-muted">Area: {{ number_format($currentArea, 1) }}ha (New)</small>
                                                            @elseif($hasPreviousYear)
                                                                <small class="text-muted">Area: {{ number_format($previousArea, 1) }}ha (Not planted)</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-light text-dark">
                                                        {{ $cropType->name ?? 'Unknown Type' }}
                                                    </span>
                                                </td>
                                                <td class="text-center fw-bold {{ $hasCurrentYear ? 'text-primary' : 'text-muted' }}">
                                                    {{ $hasCurrentYear ? number_format($currentAvg, 2) . ' qt/ha' : 'N/A' }}
                                                </td>
                                                <td class="text-center {{ $hasPreviousYear ? 'text-dark' : 'text-muted' }}">
                                                    {{ $hasPreviousYear ? number_format($previousAvg, 2) . ' qt/ha' : 'N/A' }}
                                                </td>
                                                <td class="text-center">
                                                    @if($hasPreviousYear && $hasCurrentYear)
                                                        <span class="badge {{ $change >= 0 ? 'bg-success' : 'bg-danger' }} fs-6">
                                                            {{ $change >= 0 ? '+' : '' }}{{ number_format($change, 1) }}%
                                                        </span>
                                                    @elseif($hasCurrentYear)
                                                        <span class="badge bg-warning fs-6">New Crop</span>
                                                    @else
                                                        <span class="badge bg-secondary fs-6">No Data</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($hasPreviousYear && $hasCurrentYear)
                                                        @if($change > 10)
                                                            <i class="mdi mdi-trending-up text-success fs-5" title="Excellent Growth"></i>
                                                        @elseif($change > 5)
                                                            <i class="mdi mdi-trending-up text-success fs-5" title="Strong Growth"></i>
                                                        @elseif($change > 0)
                                                            <i class="mdi mdi-trending-up text-success fs-5" title="Moderate Growth"></i>
                                                        @elseif($change < -10)
                                                            <i class="mdi mdi-trending-down text-danger fs-5" title="Severe Decline"></i>
                                                        @elseif($change < -5)
                                                            <i class="mdi mdi-trending-down text-danger fs-5" title="Significant Decline"></i>
                                                        @elseif($change < 0)
                                                            <i class="mdi mdi-trending-down text-warning fs-5" title="Slight Decline"></i>
                                                        @else
                                                            <i class="mdi mdi-trending-neutral text-secondary fs-5" title="Stable"></i>
                                                        @endif
                                                    @elseif($hasCurrentYear)
                                                        <i class="mdi mdi-star text-warning fs-5" title="New Crop This Year"></i>
                                                    @elseif($hasPreviousYear)
                                                        <i class="mdi mdi-alert-circle text-muted fs-5" title="Not Planted This Year"></i>
                                                    @else
                                                        <i class="mdi mdi-help-circle text-muted fs-5" title="No Data"></i>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <span class="badge bg-primary me-2">{{ $zoneCount }}</span>
                                                        <div class="zone-tooltip">
                                                            <i class="mdi mdi-information-outline text-muted" 
                                                               title="Zones: {{ implode(', ', array_slice($zones, 0, 3)) }}{{ count($zones) > 3 ? '...' : '' }}"></i>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <span class="badge bg-info me-2">{{ $woredaCount }}</span>
                                                        <div class="woreda-tooltip">
                                                            <i class="mdi mdi-information-outline text-muted" 
                                                               title="Woredas: {{ implode(', ', array_slice($woredas, 0, 3)) }}{{ count($woredas) > 3 ? '...' : '' }}"></i>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <span class="badge bg-success me-2">{{ $kebeleCount }}</span>
                                                        <div class="kebele-tooltip">
                                                            <i class="mdi mdi-information-outline text-muted" 
                                                               title="Kebeles: {{ implode(', ', array_slice($kebeles, 0, 3)) }}{{ count($kebeles) > 3 ? '...' : '' }}"></i>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-4">
                                                    <i class="mdi mdi-information-outline text-muted display-6 mb-3"></i>
                                                    <p class="text-muted">No crop data available for comparison.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Summary Statistics -->
                            @if(count($cropsToDisplay) > 0)
                            <div class="card-footer bg-light">
                                <div class="row text-center">
                                    @php
                                        $cropsWithGrowth = 0;
                                        $cropsWithDecline = 0;
                                        $cropsStable = 0;
                                        $newCrops = 0;
                                        $totalZonesInvolved = 0;
                                        $totalWoredasInvolved = 0;
                                        $totalKebelesInvolved = 0;
                                        
                                        foreach($cropsToDisplay as $cropData) {
                                            $currentAvg = $cropData['current_avg'] ?? 0;
                                            $previousAvg = $cropData['previous_avg'] ?? 0;
                                            
                                            if ($currentAvg > 0 && $previousAvg > 0) {
                                                $change = (($currentAvg - $previousAvg) / $previousAvg) * 100;
                                                if ($change > 0) $cropsWithGrowth++;
                                                elseif ($change < 0) $cropsWithDecline++;
                                                else $cropsStable++;
                                            } elseif ($currentAvg > 0) {
                                                $newCrops++;
                                            }
                                            
                                            $totalZonesInvolved += $cropData['zone_count'] ?? 0;
                                            $totalWoredasInvolved += $cropData['woreda_count'] ?? 0;
                                            $totalKebelesInvolved += $cropData['kebele_count'] ?? 0;
                                        }
                                        
                                        $totalComparable = $cropsWithGrowth + $cropsWithDecline + $cropsStable;
                                        $avgZonesPerCrop = count($cropsToDisplay) > 0 ? $totalZonesInvolved / count($cropsToDisplay) : 0;
                                        $avgWoredasPerCrop = count($cropsToDisplay) > 0 ? $totalWoredasInvolved / count($cropsToDisplay) : 0;
                                        $avgKebelesPerCrop = count($cropsToDisplay) > 0 ? $totalKebelesInvolved / count($cropsToDisplay) : 0;
                                    @endphp
                                    <div class="col-md-2">
                                        <div class="text-success">
                                            <i class="mdi mdi-trending-up fs-2"></i>
                                            <div class="fw-bold fs-5">{{ $cropsWithGrowth }}</div>
                                            <small>Growing</small>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="text-danger">
                                            <i class="mdi mdi-trending-down fs-2"></i>
                                            <div class="fw-bold fs-5">{{ $cropsWithDecline }}</div>
                                            <small>Declining</small>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="text-warning">
                                            <i class="mdi mdi-star fs-2"></i>
                                            <div class="fw-bold fs-5">{{ $newCrops }}</div>
                                            <small>New Crops</small>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="text-info">
                                            <i class="mdi mdi-chart-bar fs-2"></i>
                                            <div class="fw-bold fs-5">{{ count($cropsToDisplay) }}</div>
                                            <small>Total Crops</small>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="text-primary">
                                            <i class="mdi mdi-map-marker-radius fs-2"></i>
                                            <div class="fw-bold fs-5">{{ count($zoneTotals) }}</div>
                                            <small>Total Zones</small>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="text-secondary">
                                            <i class="mdi mdi-office-building fs-2"></i>
                                            <div class="fw-bold fs-5">{{ count($woredaTotals) }}</div>
                                            <small>Total Woredas</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fertilizer Analytics Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-gradient-warning text-white py-3">
                            <h5 class="mb-0">
                                <i class="mdi mdi-chemical-weapon me-2"></i>
                                Regional Fertilizer Usage Analytics (Aggregated from all Zones, Woredas and Kebeles)
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Per Crop Fertilizer -->
                                <div class="col-lg-6 mb-4">
                                    <div class="card border-0 h-100">
                                        <div class="card-header bg-light py-3">
                                            <h6 class="mb-0 text-dark">
                                                <i class="mdi mdi-sprout me-2 text-success"></i>
                                                Fertilizer per Crop (Current Year)
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Crop</th>
                                                            <th class="text-end">Total Fertilizer</th>
                                                            <th class="text-end">per Hectare</th>
                                                            <th class="text-center">Zones</th>
                                                            <th class="text-center">Woredas</th>
                                                            <th class="text-center">Kebeles</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($cropFertilizers as $cropName => $data)
                                                            @php
                                                                $perHectare = $data['total_area'] > 0 ? 
                                                                    $data['total_fertilizer'] / $data['total_area'] : 0;
                                                                // Count unique zones, woredas and kebeles for this crop
                                                                $cropZones = $currentYearPlans
                                                                    ->where('crop.name', $cropName)
                                                                    ->groupBy('kebele.woreda.zone_id')
                                                                    ->count();
                                                                $cropWoredas = $currentYearPlans
                                                                    ->where('crop.name', $cropName)
                                                                    ->groupBy('kebele.woreda_id')
                                                                    ->count();
                                                                $cropKebeles = $currentYearPlans
                                                                    ->where('crop.name', $cropName)
                                                                    ->groupBy('kebele_id')
                                                                    ->count();
                                                            @endphp
                                                            <tr>
                                                                <td class="fw-semibold">{{ $cropName }}</td>
                                                                <td class="text-end fw-bold text-primary">
                                                                    {{ number_format($data['total_fertilizer'], 2) }} kg
                                                                </td>
                                                                <td class="text-end">
                                                                    {{ number_format($perHectare, 2) }} kg/ha
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-primary">{{ $cropZones }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-info">{{ $cropWoredas }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-success">{{ $cropKebeles }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Per Crop Type Fertilizer -->
                                <div class="col-lg-6 mb-4">
                                    <div class="card border-0 h-100">
                                        <div class="card-header bg-light py-3">
                                            <h6 class="mb-0 text-dark">
                                                <i class="mdi mdi-seed-outline me-2 text-info"></i>
                                                Fertilizer per Crop Type
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Crop Type</th>
                                                            <th class="text-end">Total Fertilizer</th>
                                                            <th class="text-end">Avg per Hectare</th>
                                                            <th class="text-center">Zones</th>
                                                            <th class="text-center">Woredas</th>
                                                            <th class="text-center">Kebeles</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($typeFertilizers as $typeName => $data)
                                                            @php
                                                                $avgPerHectare = $data['total_area'] > 0 ? 
                                                                    $data['total_fertilizer'] / $data['total_area'] : 0;
                                                                // Count unique zones, woredas and kebeles for this crop type
                                                                $typeZones = $currentYearPlans
                                                                    ->where('cropType.name', $typeName)
                                                                    ->groupBy('kebele.woreda.zone_id')
                                                                    ->count();
                                                                $typeWoredas = $currentYearPlans
                                                                    ->where('cropType.name', $typeName)
                                                                    ->groupBy('kebele.woreda_id')
                                                                    ->count();
                                                                $typeKebeles = $currentYearPlans
                                                                    ->where('cropType.name', $typeName)
                                                                    ->groupBy('kebele_id')
                                                                    ->count();
                                                            @endphp
                                                            <tr>
                                                                <td class="fw-semibold">{{ $typeName }}</td>
                                                                <td class="text-end fw-bold text-warning">
                                                                    {{ number_format($data['total_fertilizer'], 2) }} kg
                                                                </td>
                                                                <td class="text-end">
                                                                    {{ number_format($avgPerHectare, 2) }} kg/ha
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-primary">{{ $typeZones }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-info">{{ $typeWoredas }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-success">{{ $typeKebeles }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Zone-wise Fertilizer Usage -->
                                <div class="col-lg-6 mb-4">
                                    <div class="card border-0 h-100">
                                        <div class="card-header bg-light py-3">
                                            <h6 class="mb-0 text-dark">
                                                <i class="mdi mdi-map-marker-radius me-2 text-primary"></i>
                                                Fertilizer Usage by Zone
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Zone</th>
                                                            <th class="text-end">Total Fertilizer</th>
                                                            <th class="text-end">Avg per Hectare</th>
                                                            <th class="text-center">Woredas</th>
                                                            <th class="text-center">Kebeles</th>
                                                            <th class="text-center">Crops</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $zoneFertilizers = [];
                                                            foreach($currentYearPlans as $plan) {
                                                                $zoneName = $plan->kebele->woreda->zone->name ?? 'Unknown';
                                                                $fertilizerDetails = $plan->fertilizer_details ?? [];
                                                                
                                                                if(!isset($zoneFertilizers[$zoneName])) {
                                                                    $zoneFertilizers[$zoneName] = [
                                                                        'total_fertilizer' => 0,
                                                                        'total_area' => 0,
                                                                        'woredas' => [],
                                                                        'kebeles' => [],
                                                                        'crops' => []
                                                                    ];
                                                                }
                                                                
                                                                $totalFertilizer = 0;
                                                                if(is_array($fertilizerDetails)) {
                                                                    foreach($fertilizerDetails as $fert) {
                                                                        $totalFertilizer += ($fert['amount'] ?? 0);
                                                                    }
                                                                }
                                                                
                                                                $zoneFertilizers[$zoneName]['total_fertilizer'] += $totalFertilizer;
                                                                $zoneFertilizers[$zoneName]['total_area'] += $plan->area_to_plant;
                                                                $zoneFertilizers[$zoneName]['woredas'][$plan->kebele->woreda_id] = true;
                                                                $zoneFertilizers[$zoneName]['kebeles'][$plan->kebele_id] = true;
                                                                $zoneFertilizers[$zoneName]['crops'][$plan->crop_id] = true;
                                                            }
                                                        @endphp
                                                        @foreach($zoneFertilizers as $zoneName => $data)
                                                            @php
                                                                $avgPerHectare = $data['total_area'] > 0 ? 
                                                                    $data['total_fertilizer'] / $data['total_area'] : 0;
                                                                $woredaCount = count($data['woredas']);
                                                                $kebeleCount = count($data['kebeles']);
                                                                $cropCount = count($data['crops']);
                                                            @endphp
                                                            <tr>
                                                                <td class="fw-semibold">
                                                                    <div class="d-flex align-items-center">
                                                                        <i class="mdi mdi-map-marker-radius text-primary me-2"></i>
                                                                        {{ $zoneName }}
                                                                    </div>
                                                                </td>
                                                                <td class="text-end fw-bold text-success">
                                                                    {{ number_format($data['total_fertilizer'], 2) }} kg
                                                                </td>
                                                                <td class="text-end">
                                                                    {{ number_format($avgPerHectare, 2) }} kg/ha
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-info">{{ $woredaCount }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-success">{{ $kebeleCount }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-warning">{{ $cropCount }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Seasonal Fertilizer Distribution -->
                                <div class="col-lg-6 mb-4">
                                    <div class="card border-0 h-100">
                                        <div class="card-header bg-light py-3">
                                            <h6 class="mb-0 text-dark">
                                                <i class="mdi mdi-weather-sunny me-2 text-success"></i>
                                                Fertilizer by Season
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Season</th>
                                                            <th class="text-end">Total Fertilizer</th>
                                                            <th class="text-end">% of Total</th>
                                                            <th class="text-center">Zones</th>
                                                            <th class="text-center">Woredas</th>
                                                            <th class="text-center">Kebeles</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $totalFertilizerAll = $currentYearPlans->sum(function($plan) {
                                                                $fertilizerDetails = $plan->fertilizer_details ?? [];
                                                                $totalFertilizer = 0;
                                                                if(is_array($fertilizerDetails)) {
                                                                    foreach($fertilizerDetails as $fert) {
                                                                        $totalFertilizer += ($fert['amount'] ?? 0);
                                                                    }
                                                                }
                                                                return $totalFertilizer;
                                                            });
                                                            
                                                            $seasonalFertilizers = [];
                                                            foreach($currentYearPlans as $plan) {
                                                                $season = $plan->season;
                                                                $fertilizerDetails = $plan->fertilizer_details ?? [];
                                                                
                                                                if(!isset($seasonalFertilizers[$season])) {
                                                                    $seasonalFertilizers[$season] = [
                                                                        'total_fertilizer' => 0,
                                                                        'zones' => [],
                                                                        'woredas' => [],
                                                                        'kebeles' => []
                                                                    ];
                                                                }
                                                                
                                                                $totalFertilizer = 0;
                                                                if(is_array($fertilizerDetails)) {
                                                                    foreach($fertilizerDetails as $fert) {
                                                                        $totalFertilizer += ($fert['amount'] ?? 0);
                                                                    }
                                                                }
                                                                
                                                                $seasonalFertilizers[$season]['total_fertilizer'] += $totalFertilizer;
                                                                $seasonalFertilizers[$season]['zones'][$plan->kebele->woreda->zone_id] = true;
                                                                $seasonalFertilizers[$season]['woredas'][$plan->kebele->woreda_id] = true;
                                                                $seasonalFertilizers[$season]['kebeles'][$plan->kebele_id] = true;
                                                            }
                                                        @endphp
                                                        @foreach($seasonalFertilizers as $season => $data)
                                                            @php
                                                                $percentage = $totalFertilizerAll > 0 ? 
                                                                    ($data['total_fertilizer'] / $totalFertilizerAll) * 100 : 0;
                                                                $zoneCount = count($data['zones']);
                                                                $woredaCount = count($data['woredas']);
                                                                $kebeleCount = count($data['kebeles']);
                                                            @endphp
                                                            <tr>
                                                                <td class="fw-semibold">
                                                                    <span class="season-badge season-{{ strtolower($season) }}">
                                                                        {{ $season }}
                                                                    </span>
                                                                </td>
                                                                <td class="text-end fw-bold text-info">
                                                                    {{ number_format($data['total_fertilizer'], 2) }} kg
                                                                </td>
                                                                <td class="text-end">
                                                                    <div class="progress" style="height: 20px;">
                                                                        <div class="progress-bar bg-info" 
                                                                             style="width: {{ $percentage }}%">
                                                                            {{ number_format($percentage, 1) }}%
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-primary">{{ $zoneCount }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-info">{{ $woredaCount }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-success">{{ $kebeleCount }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fertilizer Summary Statistics -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body">
                                            <h6 class="mb-3 text-dark">Regional Fertilizer Usage Summary</h6>
                                            <div class="row text-center">
                                                @php
                                                    $totalFertilizer = $currentYearPlans->sum(function($plan) {
                                                        $fertilizerDetails = $plan->fertilizer_details ?? [];
                                                        $totalFertilizer = 0;
                                                        if(is_array($fertilizerDetails)) {
                                                            foreach($fertilizerDetails as $fert) {
                                                                $totalFertilizer += ($fert['amount'] ?? 0);
                                                            }
                                                        }
                                                        return $totalFertilizer;
                                                    });
                                                    
                                                    $totalArea = $currentYearPlans->sum('area_to_plant');
                                                    $avgFertilizerPerHectare = $totalArea > 0 ? $totalFertilizer / $totalArea : 0;
                                                    $zonesUsingFertilizer = $currentYearPlans
                                                        ->filter(function($plan) {
                                                            $fertilizerDetails = $plan->fertilizer_details ?? [];
                                                            return !empty($fertilizerDetails);
                                                        })
                                                        ->groupBy('kebele.woreda.zone_id')
                                                        ->count();
                                                    $woredasUsingFertilizer = $currentYearPlans
                                                        ->filter(function($plan) {
                                                            $fertilizerDetails = $plan->fertilizer_details ?? [];
                                                            return !empty($fertilizerDetails);
                                                        })
                                                        ->groupBy('kebele.woreda_id')
                                                        ->count();
                                                    $kebelesUsingFertilizer = $currentYearPlans
                                                        ->filter(function($plan) {
                                                            $fertilizerDetails = $plan->fertilizer_details ?? [];
                                                            return !empty($fertilizerDetails);
                                                        })
                                                        ->groupBy('kebele_id')
                                                        ->count();
                                                @endphp
                                                <div class="col-md-3">
                                                    <div class="text-primary">
                                                        <i class="mdi mdi-chemical-weapon fs-2"></i>
                                                        <div class="fw-bold fs-5">{{ number_format($totalFertilizer, 0) }} kg</div>
                                                        <small>Total Fertilizer</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="text-success">
                                                        <i class="mdi mdi-earth fs-2"></i>
                                                        <div class="fw-bold fs-5">{{ number_format($totalArea, 1) }} ha</div>
                                                        <small>Total Area</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="text-warning">
                                                        <i class="mdi mdi-calculator fs-2"></i>
                                                        <div class="fw-bold fs-5">{{ number_format($avgFertilizerPerHectare, 2) }} kg/ha</div>
                                                        <small>Avg per Hectare</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="text-info">
                                                        <i class="mdi mdi-account-group fs-2"></i>
                                                        <div class="fw-bold fs-5">{{ $zonesUsingFertilizer }}/{{ count($zoneTotals) }}</div>
                                                        <small>Zones Using</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const value = this.value.toLowerCase();
            document.querySelectorAll('#cropPlansTable tbody tr.data-row').forEach(function(row) {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(value) ? '' : 'none';
            });
        });

        // Initialize Charts when page loads
        document.addEventListener('DOMContentLoaded', function() {
            @if (!$cropPlans->isEmpty())
                initializeRegionalCharts();
            @endif
        });

        function initializeRegionalCharts() {
            // Prepare chart data from PHP
            const cropData = @json($cropPlans);
            const zoneTotals = @json($zoneTotals);
            
            // Process data for charts
            const yearData = processYearData(cropData);
            const zoneData = processZoneData(zoneTotals);
            const cropTypeData = processCropTypeData(cropData);
            const seasonalData = processSeasonalData(cropData);

            // Create charts
            createBarChart(yearData);
            createZonePieChart(zoneData);
            createYieldChart(cropTypeData);
            createSeasonalChart(seasonalData);
        }

        function processYearData(data) {
            const years = {};
            data.forEach(plan => {
                if (!years[plan.year]) {
                    years[plan.year] = {
                        area: 0,
                        yield: 0,
                        zone_count: new Set(),
                        woreda_count: new Set(),
                        kebele_count: new Set()
                    };
                }
                years[plan.year].area += parseFloat(plan.area_to_plant);
                years[plan.year].yield += parseFloat(plan.expected_yield);
                years[plan.year].zone_count.add(plan.kebele.woreda.zone_id);
                years[plan.year].woreda_count.add(plan.kebele.woreda_id);
                years[plan.year].kebele_count.add(plan.kebele_id);
            });

            return {
                labels: Object.keys(years),
                areaData: Object.values(years).map(item => item.area),
                yieldData: Object.values(years).map(item => item.yield),
                zoneCount: Object.values(years).map(item => item.zone_count.size),
                woredaCount: Object.values(years).map(item => item.woreda_count.size),
                kebeleCount: Object.values(years).map(item => item.kebele_count.size)
            };
        }

        function processZoneData(zoneTotals) {
            return {
                labels: Object.values(zoneTotals).map(item => item.zone_name),
                areaData: Object.values(zoneTotals).map(item => item.total_area),
                yieldData: Object.values(zoneTotals).map(item => item.total_yield),
                woredaCount: Object.values(zoneTotals).map(item => item.woreda_count),
                kebeleCount: Object.values(zoneTotals).map(item => item.kebele_count)
            };
        }

        function processCropTypeData(data) {
            const cropTypes = {};
            data.forEach(plan => {
                const cropTypeName = plan.crop_type?.name || 'Unknown';
                if (!cropTypes[cropTypeName]) {
                    cropTypes[cropTypeName] = {
                        area: 0,
                        yield: 0,
                        count: 0
                    };
                }
                cropTypes[cropTypeName].area += parseFloat(plan.area_to_plant);
                cropTypes[cropTypeName].yield += parseFloat(plan.expected_yield);
                cropTypes[cropTypeName].count++;
            });

            return {
                labels: Object.keys(cropTypes),
                areaData: Object.values(cropTypes).map(item => item.area),
                yieldData: Object.values(cropTypes).map(item => item.yield),
                countData: Object.values(cropTypes).map(item => item.count)
            };
        }

        function processSeasonalData(data) {
            const seasons = {};
            data.forEach(plan => {
                const season = plan.season || 'Unknown';
                if (!seasons[season]) {
                    seasons[season] = {
                        area: 0,
                        yield: 0,
                        count: 0
                    };
                }
                seasons[season].area += parseFloat(plan.area_to_plant);
                seasons[season].yield += parseFloat(plan.expected_yield);
                seasons[season].count++;
            });

            return {
                labels: Object.keys(seasons),
                areaData: Object.values(seasons).map(item => item.area),
                yieldData: Object.values(seasons).map(item => item.yield),
                countData: Object.values(seasons).map(item => item.count)
            };
        }

        function createBarChart(yearData) {
            const ctx = document.getElementById('cropAreaBarChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: yearData.labels,
                    datasets: [
                        {
                            label: 'Planted Area (ha)',
                            data: yearData.areaData,
                            backgroundColor: 'rgba(54, 162, 235, 0.8)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            borderRadius: 8,
                            borderSkipped: false,
                        },
                        {
                            label: 'Expected Yield (qt)',
                            data: yearData.yieldData,
                            backgroundColor: 'rgba(255, 159, 64, 0.8)',
                            borderColor: 'rgba(255, 159, 64, 1)',
                            borderWidth: 1,
                            borderRadius: 8,
                            borderSkipped: false,
                            yAxisID: 'y1'
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
                                text: 'Area (Hectares)'
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Yield (Quintal)'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                }
            });
        }

        function createZonePieChart(zoneData) {
            const ctx = document.getElementById('zoneDistributionChart').getContext('2d');
            
            // Generate colors for pie chart
            const backgroundColors = [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)',
                'rgba(255, 159, 64, 0.8)',
                'rgba(199, 199, 199, 0.8)',
                'rgba(83, 102, 255, 0.8)',
                'rgba(40, 159, 64, 0.8)'
            ];

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: zoneData.labels,
                    datasets: [{
                        data: zoneData.areaData,
                        backgroundColor: backgroundColors,
                        borderColor: backgroundColors.map(color => color.replace('0.8', '1')),
                        borderWidth: 2,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value.toFixed(2)} ha (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '50%'
                }
            });
        }

        function createYieldChart(cropTypeData) {
            const ctx = document.getElementById('yieldByCropTypeChart').getContext('2d');
            
            const backgroundColors = cropTypeData.labels.map((_, index) => 
                `hsl(${index * 360 / cropTypeData.labels.length}, 70%, 60%)`
            );

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: cropTypeData.labels,
                    datasets: [{
                        label: 'Expected Yield (qt)',
                        data: cropTypeData.yieldData,
                        backgroundColor: backgroundColors,
                        borderColor: backgroundColors.map(color => color.replace('0.8', '1')),
                        borderWidth: 1,
                        borderRadius: 6
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
                                text: 'Yield (Quintal)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        function createSeasonalChart(seasonalData) {
            const ctx = document.getElementById('seasonalDistributionChart').getContext('2d');
            
            const seasonColors = {
                'Spring': 'rgba(76, 175, 80, 0.8)',
                'Summer': 'rgba(255, 152, 0, 0.8)',
                'Autumn': 'rgba(121, 85, 72, 0.8)',
                'Winter': 'rgba(33, 150, 243, 0.8)',
                'Unknown': 'rgba(158, 158, 158, 0.8)'
            };

            const backgroundColors = seasonalData.labels.map(label => 
                seasonColors[label] || 'rgba(158, 158, 158, 0.8)'
            );

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: seasonalData.labels,
                    datasets: [{
                        data: seasonalData.areaData,
                        backgroundColor: backgroundColors,
                        borderColor: backgroundColors.map(color => color.replace('0.8', '1')),
                        borderWidth: 2,
                        hoverOffset: 12
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value.toFixed(2)} ha (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>

    <style>
        .crop-plans-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .regional-badge {
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .zone-cell {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white;
            font-weight: 600;
        }

        .zone-icon {
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .woreda-cell {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
            color: white;
            font-weight: 600;
        }

        .woreda-icon {
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .kebele-cell {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
            color: white;
            font-weight: 600;
        }

        .kebele-icon {
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        /* Include all other CSS styles from previous implementations */
        .page-header h2 {
            font-weight: 700;
            color: #2c3e50;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .summary-card {
            border-radius: 15px;
            overflow: hidden;
            border: none;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }

        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        /* Add all other existing CSS styles */



        .page-header h2 {
            font-weight: 700;
            color: #2c3e50;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .summary-card {
            border-radius: 15px;
            overflow: hidden;
            border: none;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }

        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .crop-plans-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .page-header h2 {
            font-weight: 700;
            color: #2c3e50;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .btn-add-plan {
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(46, 204, 113, 0.3);
            transition: all 0.3s ease;
            border: none;
        }

        .btn-add-plan:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(46, 204, 113, 0.4);
        }

        .summary-card {
            border-radius: 15px;
            overflow: hidden;
            border: none;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }

        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .custom-alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 15px rgba(46, 204, 113, 0.2);
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        }

        .search-input-group {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .search-input-group .form-control {
            padding: 15px 20px;
            border-radius: 0 12px 12px 0;
            border: none;
            background: #ffffff;
        }

        .search-input-group .input-group-text {
            padding: 15px 20px;
            border-radius: 12px 0 0 12px;
            border: none;
            background: #ffffff;
        }

        .empty-state {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 20px;
            padding: 80px 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .table {
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 0;
        }

        .table th {
            font-weight: 700;
            padding: 20px 15px;
            border-bottom: 2px solid #e9ecef;
            background: #f8f9fa;
            color: #495057;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .table td {
            padding: 18px 15px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }

        .table-row-light {
            background-color: #fafbfc;
        }

        .year-cell {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white;
            font-size: 1.2rem;
            position: relative;
            overflow: hidden;
        }

        .year-badge {
            background: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }

        .crop-type-cell {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
            color: white;
            position: relative;
        }

        .crop-type-indicator {
            width: 8px;
            height: 8px;
            background: rgba(255,255,255,0.8);
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }

        .season-badge {
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .season-spring {
            background: linear-gradient(135deg, #a8e6cf 0%, #dcedc1 100%);
            color: #2e7d32;
        }

        .season-summer {
            background: linear-gradient(135deg, #ffd3b6 0%, #ffaaa5 100%);
            color: #d84315;
        }

        .season-autumn {
            background: linear-gradient(135deg, #ffcc80 0%, #ffb74d 100%);
            color: #ef6c00;
        }

        .season-winter {
            background: linear-gradient(135deg, #bbdefb 0%, #90caf9 100%);
            color: #1565c0;
        }

        .crop-name {
            font-weight: 600;
            color: #2c3e50;
        }

        .crop-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .productivity-badge {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            color: #1976d2;
            padding: 6px 12px;
            border-radius: 15px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .action-dropdown .btn {
            border-radius: 8px;
            padding: 8px 12px;
            transition: all 0.3s ease;
        }

        .action-dropdown .btn:hover {
            background: #e9ecef;
            transform: scale(1.05);
        }

        .dropdown-menu {
            border-radius: 12px;
            border: none;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .dropdown-item {
            padding: 10px 15px;
            border-radius: 8px;
            margin: 2px 8px;
            width: auto;
        }

        .dropdown-item:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            transform: translateX(5px);
            transition: all 0.3s ease;
        }

        .grand-total-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            overflow: hidden;
        }

        .grand-total-card .card-body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%) !important;
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%) !important;
        }

        .progress {
            border-radius: 10px;
            background-color: #e9ecef;
        }

        .progress-bar {
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .table-sm td, .table-sm th {
            padding: 0.75rem 0.5rem;
        }

        @media (max-width: 768px) {
            .page-header h2 {
                font-size: 1.5rem;
            }

            .btn-add-plan {
                padding: 10px 18px;
                font-size: 0.9rem;
            }

            .table-responsive {
                font-size: 0.85rem;
            }

            .summary-card .card-body {
                padding: 15px;
            }

            .grand-total-card .card-body {
                text-align: center;
            }

            .grand-total-card .d-flex {
                flex-direction: column;
                gap: 15px;
            }

            .chart-container {
                height: 250px;
            }

            .progress {
                height: 15px;
            }
            
            .progress-bar {
                font-size: 0.65rem;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .data-row {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>
</x-layout>