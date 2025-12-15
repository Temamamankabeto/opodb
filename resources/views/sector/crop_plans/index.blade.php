<x-layout>
    @push('styles')
<script src="{{ asset('assets/css/customside.js') }}"></script>
   
    @endpush

    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h6><i class="fas fa-seedling text-success mr-2"></i>Kebele Crop Plans Summary</h6>
                </div>
                 <div class="col-md-6 text-right">
                    <a href="{{ route('sector.crop-plans.create') }}" class="btn btn-success btn-add-plan">
                        <i class="fas fa-plus-circle mr-2"></i>Add New Crop Plan
                    </a>
                    <button class="btn btn-outline-secondary" onclick="window.print()">
                        <i class="fas fa-print mr-2"></i>Print Report
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Success Alert -->
            @if (session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Success!</h5>
                {{ session('success') }}
            </div>
            @endif

          
            </div>

            @if ($cropPlans->isEmpty())
                <!-- Empty State -->
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No crop plans found</h4>
                        <p class="text-muted mb-4">Get started by creating your first crop plan</p>
                        <a href="{{ route('sector.crop-plans.create') }}" class="btn btn-success btn-lg">
                            <i class="fas fa-plus-circle mr-2"></i>Create Crop Plan
                        </a>
                    </div>
                </div>
            @else
                @php
                    $currentYear = max($cropPlans->pluck('year')->toArray());
                    $previousYear = $currentYear - 1;
                    
                    // Prepare productivity comparison data
                    $currentYearData = $cropPlans->where('year', $currentYear)->groupBy('crop_id');
                    $previousYearData = $cropPlans->where('year', $previousYear)->groupBy('crop_id');
                    
                    $productivityData = [];
                    foreach($currentYearData as $cropId => $plans) {
                        $crop = $plans->first()->crop;
                        if ($crop) {
                            $productivityData[$cropId] = [
                                'current_avg' => $plans->avg('productivity'),
                                'previous_avg' => 0
                            ];
                        }
                    }
                    foreach($previousYearData as $cropId => $plans) {
                        $crop = $plans->first()->crop;
                        if ($crop) {
                            if (!isset($productivityData[$cropId])) {
                                $productivityData[$cropId] = [
                                    'current_avg' => 0,
                                    'previous_avg' => $plans->avg('productivity')
                                ];
                            } else {
                                $productivityData[$cropId]['previous_avg'] = $plans->avg('productivity');
                            }
                        }
                    }

                    // Group data properly for hierarchical display
                    $groupedData = [];
                    foreach ($cropPlans->groupBy('year') as $year => $plansByYear) {
                        foreach ($plansByYear->groupBy('croptype_id') as $typeId => $plansByType) {
                            $cropType = $plansByType->first()->cropType;
                            foreach ($plansByType->groupBy('season') as $season => $plansBySeason) {
                                foreach ($plansBySeason as $plan) {
                                    $groupedData[] = [
                                        'year' => $year,
                                        'type_id' => $typeId,
                                        'crop_type' => $cropType,
                                        'season' => $season,
                                        'plan' => $plan
                                    ];
                                }
                            }
                        }
                    }
                @endphp
 

                <!-- Main Data Table -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <div class="btn-group">
                                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" role="menu">
                                    <a class="dropdown-item" href="#" onclick="exportToExcel()">
                                        <i class="fas fa-file-excel mr-2 text-success"></i>Export to Excel
                                    </a>
                                    <a class="dropdown-item" href="#" onclick="exportToPDF()">
                                        <i class="fas fa-file-pdf mr-2 text-danger"></i>Export to PDF
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" onclick="printTable()">
                                        <i class="fas fa-print mr-2"></i>Print Table
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered mb-0" id="cropPlansTable">
                                <thead>
                                    <tr class="bg-gradient-dark">
                                        <th style="width: 8%">Year</th>
                                        <th style="width: 12%">Crop Type</th>
                                        <th style="width: 8%">Season</th>
                                        <th style="width: 15%">Crop</th>
                                        <th style="width: 8%">Area (ha)</th>
                                        <th style="width: 10%">Productivity</th>
                                        <th style="width: 12%">Expected Yield (qt)</th>
                                        <th style="width: 10%">Prev Year</th>
                                        <th style="width: 8%">Change</th>
                                        <th style="width: 5%">Trend</th>
                                        <th style="width: 4%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $currentYearIndex = null;
                                        $currentTypeIndex = null;
                                        $currentSeasonIndex = null;
                                        $yearRowspan = 0;
                                        $typeRowspan = 0;
                                        $seasonRowspan = 0;
                                        $yearCounter = 0;
                                        $typeCounter = 0;
                                        $seasonCounter = 0;
                                    @endphp

                                    @foreach($groupedData as $index => $data)
                                        @php
                                            $year = $data['year'];
                                            $typeId = $data['type_id'];
                                            $cropType = $data['crop_type'];
                                            $season = $data['season'];
                                            $plan = $data['plan'];
                                            
                                            $cropId = $plan->crop_id;
                                            $currentProductivity = $plan->productivity ?? 0;
                                            $previousProductivity = $productivityData[$cropId]['previous_avg'] ?? 0;
                                            $hasPreviousYear = $previousProductivity > 0;
                                            $change = $hasPreviousYear ? (($currentProductivity - $previousProductivity) / $previousProductivity) * 100 : 0;

                                            // Calculate rowspans dynamically
                                            if ($currentYearIndex !== $year) {
                                                $currentYearIndex = $year;
                                                $yearRowspan = array_reduce($groupedData, function($count, $item) use ($year) {
                                                    return $item['year'] == $year ? $count + 1 : $count;
                                                }, 0);
                                                $yearCounter = 0;
                                            }

                                            if ($currentTypeIndex !== $year . '_' . $typeId) {
                                                $currentTypeIndex = $year . '_' . $typeId;
                                                $typeRowspan = array_reduce($groupedData, function($count, $item) use ($year, $typeId) {
                                                    return $item['year'] == $year && $item['type_id'] == $typeId ? $count + 1 : $count;
                                                }, 0);
                                                $typeCounter = 0;
                                            }

                                            if ($currentSeasonIndex !== $year . '_' . $typeId . '_' . $season) {
                                                $currentSeasonIndex = $year . '_' . $typeId . '_' . $season;
                                                $seasonRowspan = array_reduce($groupedData, function($count, $item) use ($year, $typeId, $season) {
                                                    return $item['year'] == $year && $item['type_id'] == $typeId && $item['season'] == $season ? $count + 1 : $count;
                                                }, 0);
                                                $seasonCounter = 0;
                                            }

                                            $yearCounter++;
                                            $typeCounter++;
                                            $seasonCounter++;
                                        @endphp
                                        
                                        <tr class="hover-row" 
                                            data-year="{{ $year }}"
                                            data-type="{{ $cropType->name ?? '' }}"
                                            data-season="{{ $season }}"
                                            data-crop="{{ $plan->crop->name ?? '' }}">
                                            
                                            <!-- Year Column -->
                                            @if ($yearCounter === 1)
                                                <td class="year-group text-center align-middle" rowspan="{{ $yearRowspan }}">
                                                    <div class="d-flex flex-column">
                                                        <strong class="text-primary mb-1">{{ $year }}</strong>
                                                        @if($year == date('Y'))
                                                            <span class="badge badge-success">Current</span>
                                                        @endif
                                                        <small class="text-muted mt-2">Total Area:</small>
                                                        <strong class="text-success">
                                                            {{ number_format($cropPlans->where('year', $year)->sum('area_to_plant'), 2) }} ha
                                                        </strong>
                                                    </div>
                                                </td>
                                            @endif

                                            <!-- Crop Type Column -->
                                            @if ($typeCounter === 1)
                                                <td class="type-group align-middle" rowspan="{{ $typeRowspan }}">
                                                    <strong class="d-block">{{ $cropType->name ?? '-' }}</strong>
                                                    <small class="text-muted">Area Total:</small>
                                                    <div class="text-success">
                                                        {{ number_format($cropPlans->where('year', $year)->where('croptype_id', $typeId)->sum('area_to_plant'), 2) }} ha
                                                    </div>
                                                </td>
                                            @endif

                                            <!-- Season Column -->
                                            @if ($seasonCounter === 1)
                                                <td class="season-group text-center align-middle" rowspan="{{ $seasonRowspan }}">
                                                    <span class="badge bg-warning text-dark">
                                                        {{ ucfirst($season) }}
                                                    </span>
                                                    <div class="mt-2">
                                                        <small class="text-muted d-block">Area:</small>
                                                        <strong class="text-success">
                                                            {{ number_format($cropPlans->where('year', $year)->where('croptype_id', $typeId)->where('season', $season)->sum('area_to_plant'), 2) }} ha
                                                        </strong>
                                                    </div>
                                                </td>
                                            @endif

                                            <!-- Crop Details -->
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="crop-icon mr-2">
                                                        <i class="fas fa-seedling text-success"></i>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $plan->crop->name ?? '-' }}</strong>
                                                        @if($year == $currentYear && $hasPreviousYear)
                                                            <div>
                                                                <small class="{{ $change >= 0 ? 'text-success' : 'text-danger' }}">
                                                                    {{ $change >= 0 ? '↗' : '↘' }}
                                                                    {{ number_format(abs($change), 1) }}%
                                                                </small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td class="text-right">
                                                <strong class="text-primary">{{ number_format($plan->area_to_plant, 2) }}</strong>
                                            </td>
                                            
                                            <td class="text-center">
                                                <span class="badge {{ $year == $currentYear ? 'bg-primary' : 'bg-secondary' }}">
                                                    {{ $currentProductivity ? number_format($currentProductivity, 2) . ' qt/ha' : '-' }}
                                                </span>
                                            </td>
                                            
                                            <td class="text-right">
                                                <strong class="text-success">{{ number_format($plan->expected_yield, 2) }}</strong>
                                            </td>

                                            <!-- Previous Year -->
                                            <td class="text-center">
                                                @if($hasPreviousYear && $year == $currentYear)
                                                    <span class="text-muted">
                                                        {{ number_format($previousProductivity, 2) }} qt/ha
                                                    </span>
                                                @elseif($year == $previousYear)
                                                    <span class="text-dark">
                                                        {{ number_format($currentProductivity, 2) }} qt/ha
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>

                                            <!-- Change -->
                                            <td class="text-center">
                                                @if($hasPreviousYear && $year == $currentYear)
                                                    <span class="badge {{ $change >= 0 ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $change >= 0 ? '+' : '' }}{{ number_format($change, 1) }}%
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>

                                            <!-- Trend -->
                                            <td class="text-center">
                                                @if($hasPreviousYear && $year == $currentYear)
                                                    @if($change > 10)
                                                        <i class="fas fa-arrow-up text-success" title="Excellent Growth"></i>
                                                    @elseif($change > 5)
                                                        <i class="fas fa-arrow-up text-success" title="Strong Growth"></i>
                                                    @elseif($change > 0)
                                                        <i class="fas fa-arrow-up text-success" title="Moderate Growth"></i>
                                                    @elseif($change < -10)
                                                        <i class="fas fa-arrow-down text-danger" title="Severe Decline"></i>
                                                    @elseif($change < -5)
                                                        <i class="fas fa-arrow-down text-danger" title="Significant Decline"></i>
                                                    @elseif($change < 0)
                                                        <i class="fas fa-arrow-down text-warning" title="Slight Decline"></i>
                                                    @else
                                                        <i class="fas fa-minus text-secondary" title="Stable"></i>
                                                    @endif
                                                @elseif($year == $currentYear && !$hasPreviousYear)
                                                    <i class="fas fa-star text-warning" title="New Crop"></i>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            
                                            <!-- Actions -->
                                            <td class="action-group">
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button"
                                                            class="btn btn-warning disabled"
                                                            title="Edit (Disabled)"
                                                            disabled>
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </div>
                                           </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light">
                                        <td colspan="4" class="text-right"><strong>Grand Totals:</strong></td>
                                        <td class="text-right">
                                            <strong class="text-primary">
                                                {{ number_format($cropPlans->sum('area_to_plant'), 2) }} ha
                                            </strong>
                                        </td>
                                        <td></td>
                                        <td class="text-right">
                                            <strong class="text-success">
                                                {{ number_format($cropPlans->sum('expected_yield'), 2) }} qt
                                            </strong>
                                        </td>
                                        <td colspan="4"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        <div class="float-left">
                            <small class="text-muted">Showing {{ count($groupedData) }} plans</small>
                        </div>
                        <div class="float-right">
                            <button type="button" class="btn btn-default btn-sm" onclick="scrollToTop()">
                                <i class="fas fa-arrow-up mr-1"></i>Back to Top
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    @push('scripts')
 
    
<script src="{{ asset('assets/js/cropindex.js') }}"></script>
   
    @endpush
</x-layout>