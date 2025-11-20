<x-layout>
    <div class="container-fluid py-4 crop-plans-container">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="page-header">
                <h2 class="mb-0"><i class="mdi mdi-sprout text-success me-2"></i> Kebele Crop Plans Summary</h2>
                <p class="text-muted mb-0 mt-1">Track and manage agricultural production plans</p>
            </div>
            <a href="{{ route('sector.crop-plans.create') }}" class="btn btn-success btn-add-plan">
                <i class="mdi mdi-plus-circle-outline me-2"></i> Add New Crop Plan
            </a>
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

        <!-- Search Section -->
        <div class="search-container mb-4">
            <div class="input-group search-input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="mdi mdi-magnify text-muted"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-start-0"
                    placeholder="Search by crop, crop type, season, or year...">
            </div>
        </div>

        @if ($cropPlans->isEmpty())
            <!-- Empty State -->
            <div class="empty-state text-center py-5">
                <i class="mdi mdi-information-outline display-4 text-muted mb-3"></i>
                <h4 class="text-muted">No crop plans found</h4>
                <p class="text-muted mb-4">Get started by creating your first crop plan</p>
                <a href="{{ route('sector.crop-plans.create') }}" class="btn btn-success">
                    <i class="mdi mdi-plus-circle-outline me-2"></i> Create Crop Plan
                </a>
            </div>
        @else
            @php
                $currentYear = max($cropPlans->pluck('year')->toArray());
                $previousYear = $currentYear - 1;
                
                $currentYearData = $cropPlans->where('year', $currentYear)->groupBy('crop_id');
                $previousYearData = $cropPlans->where('year', $previousYear)->groupBy('crop_id');
            @endphp

            <!-- Data Table -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark">
                        <i class="mdi mdi-table me-2 text-primary"></i>
                        Crop Plans Overview
                    </h5>
                    <div class="table-controls">
                        <small class="text-muted me-3">{{ $cropPlans->count() }} plans found</small>
                        <button class="btn btn-sm btn-outline-secondary" onclick="scrollToTop('cropPlansTable')">
                            <i class="mdi mdi-arrow-up"></i> Top
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table table-hover align-middle mb-0" id="cropPlansTable">
                            <thead class="table-light sticky-header">
                                <tr>
                                    <th class="ps-4">Year</th>
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
                                @php
                                    $grandTotalArea = 0;
                                    $grandTotalYield = 0;
                                @endphp
                                
                                @foreach ($cropPlans->groupBy('year') as $year => $plansByYear)
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
                                                <tr class="data-row {{ $index % 2 == 0 ? 'table-row-light' : '' }}" data-plan-id="{{ $plan->id }}">
                                                    {{-- Year Column --}}
                                                    @if (!$yearDisplayed)
                                                        <td rowspan="{{ $yearDataRows }}" class="fw-bold text-center year-cell ps-4">
                                                            <div class="year-badge">{{ $year }}</div>
                                                        </td>
                                                        @php $yearDisplayed = true; @endphp
                                                    @endif

                                                    {{-- Crop Type Column --}}
                                                    @if (!$cropTypeDisplayed && $seasonFirstRow)
                                                        <td rowspan="{{ $cropTypeDataRows }}" class="fw-bold crop-type-cell">
                                                            <div class="crop-type-indicator"></div>
                                                            {{ $cropType->name ?? '-' }}
                                                        </td>
                                                        @php $cropTypeDisplayed = true; @endphp
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
                                                        <div class="action-buttons">
                                                            <a href="{{ route('sector.crop-plans.editcrop', $plan->id) }}" 
                                                               class="btn btn-sm btn-warning action-btn edit-btn"
                                                               title="Edit Crop Plan"
                                                               data-plan-id="{{ $plan->id }}">
                                                                <i class="mdi mdi-pencil"></i>
                                                            </a>
                                                            <a href="{{ route('sector.crop-plans.editfertilizer', $plan->id) }}" 
                                                               class="btn btn-sm btn-info action-btn fertilizer-btn"
                                                               title="Manage Fertilizer"
                                                               data-plan-id="{{ $plan->id }}">
                                                                <i class="mdi mdi-chemical-weapon"></i>
                                                            </a>
                                                            <form action="{{ route('sector.crop-plans.destroy', $plan->id) }}" 
                                                                  method="POST" 
                                                                  class="d-inline"
                                                                  onsubmit="return confirm('Are you sure you want to delete this crop plan?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="btn btn-sm btn-danger action-btn delete-btn"
                                                                        title="Delete Crop Plan">
                                                                    <i class="mdi mdi-delete"></i>
                                                                </button>
                                                            </form>
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
                                                    $grandTotalArea += $plan->area_to_plant;
                                                    $grandTotalYield += $plan->expected_yield;
                                                @endphp
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
                                        Grand Totals
                                    </h5>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Productivity Comparison Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-gradient-info text-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="mdi mdi-trending-up me-2"></i>
                                Productivity Comparison - Current Year vs Previous Year (Per Crop)
                            </h5>
                            <div class="table-controls">
                                <button class="btn btn-sm btn-light" onclick="scrollToTop('productivityTable')">
                                    <i class="mdi mdi-arrow-up"></i> Top
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-container">
                                <table class="table table-hover align-middle mb-0" id="productivityTable">
                                    <thead class="table-light sticky-header">
                                        <tr>
                                            <th class="ps-4">Crop</th>
                                            <th class="text-center">Crop Type</th>
                                            <th class="text-center">Current Year ({{ $currentYear }})</th>
                                            <th class="text-center">Previous Year ({{ $previousYear }})</th>
                                            <th class="text-center">Change</th>
                                            <th class="text-center">Trend</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // Get unique crops from current year data
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
                                                        'current_yield' => $plans->sum('expected_yield')
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
                                                        'previous_yield' => $plans->sum('expected_yield')
                                                    ];
                                                } elseif (isset($cropsToDisplay[$cropId])) {
                                                    $cropsToDisplay[$cropId]['previous_avg'] = $plans->avg('productivity');
                                                    $cropsToDisplay[$cropId]['previous_area'] = $plans->sum('area_to_plant');
                                                    $cropsToDisplay[$cropId]['previous_yield'] = $plans->sum('expected_yield');
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
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4">
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
                                        }
                                        
                                        $totalComparable = $cropsWithGrowth + $cropsWithDecline + $cropsStable;
                                    @endphp
                                    <div class="col-md-3">
                                        <div class="text-success">
                                            <i class="mdi mdi-trending-up fs-2"></i>
                                            <div class="fw-bold fs-5">{{ $cropsWithGrowth }}</div>
                                            <small>Growing</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-danger">
                                            <i class="mdi mdi-trending-down fs-2"></i>
                                            <div class="fw-bold fs-5">{{ $cropsWithDecline }}</div>
                                            <small>Declining</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-warning">
                                            <i class="mdi mdi-star fs-2"></i>
                                            <div class="fw-bold fs-5">{{ $newCrops }}</div>
                                            <small>New Crops</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-info">
                                            <i class="mdi mdi-chart-bar fs-2"></i>
                                            <div class="fw-bold fs-5">{{ count($cropsToDisplay) }}</div>
                                            <small>Total Crops</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const value = this.value.toLowerCase();
                    const rows = document.querySelectorAll('#cropPlansTable tbody tr.data-row');
                    
                    rows.forEach(function(row) {
                        const cropName = row.querySelector('.crop-name').textContent.toLowerCase();
                        const cropType = row.querySelector('.crop-type-cell')?.textContent.toLowerCase() || '';
                        const season = row.querySelector('.season-badge')?.textContent.toLowerCase() || '';
                        const year = row.querySelector('.year-badge')?.textContent.toLowerCase() || '';
                        
                        const show = cropName.includes(value) || cropType.includes(value) || season.includes(value) || year.includes(value);
                        row.style.display = show ? '' : 'none';
                    });
                });
            }

            // Add hover effects to table rows
            document.querySelectorAll('.data-row').forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
                    this.style.transition = 'all 0.3s ease';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = 'none';
                });
            });

            // Add loading states to action buttons
            document.querySelectorAll('.action-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    const originalHtml = this.innerHTML;
                    this.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i>';
                    this.disabled = true;
                    
                    // Revert after 2 seconds if still on same page (fallback)
                    setTimeout(() => {
                        if (this.disabled) {
                            this.innerHTML = originalHtml;
                            this.disabled = false;
                        }
                    }, 2000);
                });
            });

            // Delete confirmation enhancement
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('Are you sure you want to delete this crop plan? This action cannot be undone.')) {
                        e.preventDefault();
                    }
                });
            });

            // Initialize table scrolling indicators
            initializeTableScrolling();
        });

        function scrollToTop(tableId) {
            const tableContainer = document.querySelector(`#${tableId}`).closest('.table-container');
            tableContainer.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function initializeTableScrolling() {
            const tableContainers = document.querySelectorAll('.table-container');
            
            tableContainers.forEach(container => {
                container.addEventListener('scroll', function() {
                    const scrollTop = this.scrollTop;
                    const scrollHeight = this.scrollHeight;
                    const clientHeight = this.clientHeight;
                    
                    // You can add scroll indicators here if needed
                    if (scrollTop > 100) {
                        this.classList.add('scrolled');
                    } else {
                        this.classList.remove('scrolled');
                    }
                });
            });
        }
    </script>

    <style>
        /* Scrollable Table Styles */
        .table-container {
            position: relative;
            max-height: 60vh;
            overflow: auto;
            border: 1px solid #dee2e6;
        }

        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 10;
            background: white;
        }

        .sticky-header th {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            position: relative;
        }

        .sticky-header th::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: #dee2e6;
        }

        /* Table Controls */
        .table-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Custom scrollbar for table containers */
        .table-container::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Action Buttons Styles */
        .action-buttons {
            display: flex;
            gap: 6px;
            justify-content: center;
            align-items: center;
            flex-wrap: nowrap;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .action-btn:active {
            transform: translateY(0);
        }

        .edit-btn {
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
            color: white;
        }

        .edit-btn:hover {
            background: linear-gradient(135deg, #ff8c00 0%, #ff5722 100%);
        }

        .fertilizer-btn {
            background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
            color: white;
        }

        .fertilizer-btn:hover {
            background: linear-gradient(135deg, #138496 0%, #1e7e34 100%);
        }

        .delete-btn {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }

        .delete-btn:hover {
            background: linear-gradient(135deg, #c82333 0%, #a71e2a 100%);
        }

        /* Ensure proper spacing for action column */
        .table th:last-child,
        .table td:last-child {
            width: 150px;
            min-width: 150px;
            text-align: center;
        }

        /* Loading animation */
        .mdi-loading {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .table-container {
                max-height: 50vh;
                font-size: 0.85rem;
            }

            .action-buttons {
                gap: 4px;
            }

            .action-btn {
                width: 32px;
                height: 32px;
                font-size: 0.8rem;
            }

            .table th:last-child,
            .table td:last-child {
                width: 120px;
                min-width: 120px;
            }

            .table-controls {
                flex-direction: column;
                gap: 5px;
            }
        }

        @media (max-width: 576px) {
            .table-container {
                max-height: 40vh;
            }

            .action-buttons {
                flex-direction: column;
                gap: 3px;
            }

            .action-btn {
                width: 28px;
                height: 28px;
                font-size: 0.7rem;
            }

            .table th:last-child,
            .table td:last-child {
                width: 80px;
                min-width: 80px;
            }
        }

        /* Tooltip styles for buttons */
        .action-btn {
            position: relative;
        }

        .action-btn:hover::after {
            content: attr(title);
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            background: #333;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            white-space: nowrap;
            z-index: 1000;
        }

        .action-btn:hover::before {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-bottom-color: #333;
        }

        /* Scroll indicator */
        .table-container.scrolled::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #007bff, #6c757d);
            z-index: 11;
        }
    </style>
</x-layout>