<x-layout>
    <div class="container-fluid py-4 crop-plans-container">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="page-header">
                <h2 class="mb-0"><i class="mdi mdi-sprout text-success me-2"></i> Kebele Crop Plans Summary</h2>
                <p class="text-muted mb-0 mt-1">Track and manage agricultural production plans with productivity trends</p>
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

            <!-- Data Table with Integrated Productivity Comparison -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark">
                        <i class="mdi mdi-table me-2 text-primary"></i>
                        Crop Plans Overview with Productivity Trends
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
                                    <th class="ps-4" style="width: 10%">Year</th>
                                    <th style="width: 12%">Crop Type</th>
                                    <th style="width: 8%">Season</th>
                                    <th style="width: 15%">Crop</th>
                                    <th class="text-end" style="width: 8%">Area (ha)</th>
                                    <th class="text-center" style="width: 10%">Productivity</th>
                                    <th class="text-end" style="width: 12%">Expected Yield (qt)</th>
                                    <!-- Productivity Comparison Columns -->
                                    <th class="text-center" style="width: 10%">Prev Year</th>
                                    <th class="text-center" style="width: 8%">Change</th>
                                    <th class="text-center" style="width: 5%">Trend</th>
                                    <th class="text-center" style="width: 2%">Actions</th>
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
                                    
                                    <tr class="data-row hover-row" 
                                        data-year="{{ $year }}"
                                        data-type="{{ $cropType->name ?? '' }}"
                                        data-season="{{ $season }}"
                                        data-crop="{{ $plan->crop->name ?? '' }}">
                                        
                                        <!-- Year Column -->
                                        @if ($yearCounter === 1)
                                            <td class="year-cell align-middle text-center fw-bold" 
                                                rowspan="{{ $yearRowspan }}"
                                                style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                                <div class="year-content">
                                                    <div class="year-value fs-5 text-primary mb-1">
                                                        {{ $year }}
                                                    </div>
                                                    @if($year == date('Y'))
                                                        <span class="badge bg-success bg-opacity-20 text-success border border-success">
                                                            Current
                                                        </span>
                                                    @endif
                                                    <div class="year-total mt-2">
                                                        <small class="text-muted d-block">Year Total:</small>
                                                        <div class="fw-bold text-success">
                                                            {{ number_format($cropPlans->where('year', $year)->sum('area_to_plant'), 2) }} ha
                                                        </div>
                                                        <div class="fw-bold text-primary">
                                                            {{ number_format($cropPlans->where('year', $year)->sum('expected_yield'), 2) }} qt
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif

                                        <!-- Crop Type Column -->
                                        @if ($typeCounter === 1)
                                            <td class="crop-type-cell align-middle" 
                                                rowspan="{{ $typeRowspan }}"
                                                style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);">
                                                <div class="crop-type-content">
                                                    <div class="crop-type-name fw-bold text-dark mb-2">
                                                        {{ $cropType->name ?? '-' }}
                                                    </div>
                                                    <div class="crop-type-total">
                                                        <small class="text-muted d-block">Type Total:</small>
                                                        <div class="fw-bold text-success">
                                                            {{ number_format($cropPlans->where('year', $year)->where('croptype_id', $typeId)->sum('area_to_plant'), 2) }} ha
                                                        </div>
                                                        <div class="fw-bold text-primary">
                                                            {{ number_format($cropPlans->where('year', $year)->where('croptype_id', $typeId)->sum('expected_yield'), 2) }} qt
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif

                                        <!-- Season Column -->
                                        @if ($seasonCounter === 1)
                                            <td class="season-cell align-middle text-center"
                                                rowspan="{{ $seasonRowspan }}"
                                                style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);">
                                                <div class="season-content">
                                                    <span class="season-badge fw-semibold text-dark">
                                                        <i class="mdi mdi-weather-sunny me-1 text-warning"></i>
                                                        {{ ucfirst($season) }}
                                                    </span>
                                                    <div class="season-total mt-1">
                                                        <small class="text-muted d-block">Season Total:</small>
                                                        <div class="fw-bold text-success">
                                                            {{ number_format($cropPlans->where('year', $year)->where('croptype_id', $typeId)->where('season', $season)->sum('area_to_plant'), 2) }} ha
                                                        </div>
                                                        <div class="fw-bold text-primary">
                                                            {{ number_format($cropPlans->where('year', $year)->where('croptype_id', $typeId)->where('season', $season)->sum('expected_yield'), 2) }} qt
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif

                                        <!-- Crop Details -->
                                        <td class="crop-details-cell">
                                            <div class="crop-details">
                                                <div class="d-flex align-items-center">
                                                    <div class="crop-icon me-2">
                                                        <i class="mdi mdi-sprout text-success"></i>
                                                    </div>
                                                    <div>
                                                        <span class="fw-semibold text-dark">{{ $plan->crop->name ?? '-' }}</span>
                                                        @if($year == $currentYear && $hasPreviousYear)
                                                            <div class="trend-indicator-small">
                                                                <small class="{{ $change >= 0 ? 'text-success' : 'text-danger' }}">
                                                                    {{ $change >= 0 ? '↗' : '↘' }}
                                                                    {{ number_format(abs($change), 1) }}%
                                                                </small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="text-end fw-semibold text-primary">
                                            {{ number_format($plan->area_to_plant, 2) }}
                                        </td>
                                        
                                        <td class="text-center">
                                            <span class="productivity-badge fw-bold {{ $year == $currentYear ? 'text-primary' : 'text-dark' }}">
                                                {{ $currentProductivity ? number_format($currentProductivity, 2) . ' qt/ha' : '-' }}
                                            </span>
                                        </td>
                                        
                                        <td class="text-end fw-semibold text-success">
                                            {{ number_format($plan->expected_yield, 2) }}
                                        </td>

                                        <!-- Productivity Comparison Columns -->
                                        <td class="text-center">
                                            @if($hasPreviousYear && $year == $currentYear)
                                                <span class="previous-productivity text-muted">
                                                    {{ number_format($previousProductivity, 2) }} qt/ha
                                                </span>
                                            @elseif($year == $previousYear)
                                                <span class="previous-productivity text-dark">
                                                    {{ number_format($currentProductivity, 2) }} qt/ha
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            @if($hasPreviousYear && $year == $currentYear)
                                                <span class="badge {{ $change >= 0 ? 'bg-success' : 'bg-danger' }} change-badge">
                                                    {{ $change >= 0 ? '+' : '' }}{{ number_format($change, 1) }}%
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            @if($hasPreviousYear && $year == $currentYear)
                                                @if($change > 10)
                                                    <i class="mdi mdi-trending-up text-success fs-6" title="Excellent Growth"></i>
                                                @elseif($change > 5)
                                                    <i class="mdi mdi-trending-up text-success fs-6" title="Strong Growth"></i>
                                                @elseif($change > 0)
                                                    <i class="mdi mdi-trending-up text-success fs-6" title="Moderate Growth"></i>
                                                @elseif($change < -10)
                                                    <i class="mdi mdi-trending-down text-danger fs-6" title="Severe Decline"></i>
                                                @elseif($change < -5)
                                                    <i class="mdi mdi-trending-down text-danger fs-6" title="Significant Decline"></i>
                                                @elseif($change < 0)
                                                    <i class="mdi mdi-trending-down text-warning fs-6" title="Slight Decline"></i>
                                                @else
                                                    <i class="mdi mdi-trending-neutral text-secondary fs-6" title="Stable"></i>
                                                @endif
                                            @elseif($year == $currentYear && !$hasPreviousYear)
                                                <i class="mdi mdi-star text-warning fs-6" title="New Crop"></i>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        
                                        <td class="actions-cell text-center align-middle">
                                            <div class="action-buttons">
                                                <a href="{{ route('sector.crop-plans.editcrop', $plan->id) }}" 
                                                   class="btn btn-warning btn-sm action-btn mb-1"
                                                   title="Edit Crop Plan"
                                                   data-plan-id="{{ $plan->id }}">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <a href="{{ route('sector.crop-plans.activityplan', $plan->id ) }}" 
                                                   class="btn btn-info btn-sm action-btn"
                                                   title="Manage implementation schedule"
                                                   data-plan-id="{{ $plan->id }}">
                                                    <i class="mdi mdi-chemical-weapon"></i>
                                                </a>
                                                 <a href="{{ route('sector.crop-plans.productionactivityplan', $plan->id ) }}" 
                                                   class="btn btn-info btn-sm action-btn"
                                                   title="Manage production schedule"
                                                   data-plan-id="{{ $plan->id }}">
                                                    <i class="mdi mdi-chemical-weapon"></i>
                                                </a>
                                               
                                                 <a href="{{ route('sector.fertilizer-plans.edit', $plan->id ) }}" 
                                                   class="btn btn-info btn-sm action-btn"
                                                   title="Manage fertilizer schedule"
                                                   data-plan-id="{{ $plan->id }}">
                                                    <i class="mdi mdi-chemical-weapon"></i>
                                                </a>
                                               
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Productivity Summary Statistics -->
            @php
                $cropsWithGrowth = 0;
                $cropsWithDecline = 0;
                $cropsStable = 0;
                $newCrops = 0;
                
                foreach($productivityData as $cropData) {
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
            @endphp

           
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
                        const cropName = row.querySelector('.crop-details-cell').textContent.toLowerCase();
                        const cropType = row.getAttribute('data-type').toLowerCase();
                        const season = row.getAttribute('data-season').toLowerCase();
                        const year = row.getAttribute('data-year').toLowerCase();
                        
                        const show = cropName.includes(value) || cropType.includes(value) || season.includes(value) || year.includes(value);
                        row.style.display = show ? '' : 'none';
                    });
                });
            }

            // Add hover effects to table rows
            document.querySelectorAll('.hover-row').forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.background = 'linear-gradient(135deg, #f0f8ff 0%, #e7f3ff 100%)';
                    this.style.borderLeft = '3px solid #007bff';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.background = '';
                    this.style.borderLeft = '';
                });
            });

            // Add loading states to action buttons
            document.querySelectorAll('.action-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    const originalHtml = this.innerHTML;
                    this.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i>';
                    this.disabled = true;
                    
                    setTimeout(() => {
                        if (this.disabled) {
                            this.innerHTML = originalHtml;
                            this.disabled = false;
                        }
                    }, 2000);
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
                    if (this.scrollTop > 100) {
                        this.classList.add('scrolled');
                    } else {
                        this.classList.remove('scrolled');
                    }
                });
            });
        }
    </script>

    <style>
        .crop-plans-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        /* Table Styles */
        .table-container {
            position: relative;
            max-height: 70vh;
            overflow: auto;
            border: 1px solid #e9ecef;
            border-radius: 8px;
        }

        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 10;
            background: white;
        }

        .sticky-header th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #495057;
            vertical-align: middle;
            white-space: nowrap;
        }

        /* Cell Styles */
        .year-cell, .season-cell, .crop-type-cell {
            border-right: 2px solid #dee2e6;
            vertical-align: middle !important;
        }

        .year-content, .crop-type-content, .season-content {
            padding: 0.5rem;
        }

        .season-badge {
            font-size: 0.8rem;
        }

        .crop-icon {
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Total sections within cells */
        .year-total, .crop-type-total, .season-total {
            margin-top: 0.5rem;
            padding-top: 0.5rem;
            border-top: 1px dashed #dee2e6;
        }

        .year-total small, .crop-type-total small, .season-total small {
            font-size: 0.7rem;
        }

        /* Table row styling */
        .table td {
            padding: 0.6rem;
            border-bottom: 1px solid #e9ecef;
            font-size: 0.85rem;
        }

        /* Trend indicators */
        .trend-indicator-small {
            margin-top: 2px;
        }

        .change-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
        }

        /* Performance Cards */
        .performance-card {
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .performance-card:hover {
            transform: translateY(-5px);
        }

        .performance-growth {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 1px solid #c3e6cb;
        }

        .performance-decline {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border: 1px solid #f5c6cb;
        }

        .performance-new {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 1px solid #ffeaa7;
        }

        .performance-total {
            background: linear-gradient(135deg, #cce7ff 0%, #b3d9ff 100%);
            border: 1px solid #b3d9ff;
        }

        .performance-value {
            margin: 0.5rem 0;
        }

        .performance-label {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }

        .action-btn {
            font-size: 0.7rem;
            padding: 0.3rem;
            border-radius: 4px;
            transition: all 0.2s ease;
            width: 100%;
            min-width: 30px;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Scrollbar */
        .table-container::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .table-container {
                font-size: 0.75rem;
            }
            
            .action-btn {
                font-size: 0.65rem;
                padding: 0.25rem;
            }
            
            .year-value {
                font-size: 0.9rem !important;
            }
            
            .performance-card {
                padding: 1rem;
            }
            
            .performance-value {
                font-size: 1.5rem !important;
            }
        }

        /* Animations */
        .mdi-loading {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Hover effects */
        .hover-row {
            transition: all 0.3s ease;
        }

        /* Table Controls */
        .table-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Button styles */
        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
            color: white;
            border: none;
        }

        .btn-info {
            background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
            color: white;
            border: none;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #ff8c00 0%, #ff5722 100%);
        }

        .btn-info:hover {
            background: linear-gradient(135deg, #138496 0%, #1e7e34 100%);
        }

        /* Ensure proper vertical alignment for rowspan cells */
        .year-cell, .season-cell, .crop-type-cell {
            vertical-align: middle !important;
        }

        /* Fix for rowspan borders */
        .table > tbody > tr > td {
            border: 1px solid #dee2e6;
        }
    </style>
</x-layout>