<x-layout>
    <div class="container-fluid py-4 crop-plans-container">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="page-header">
                <h2 class="mb-0"><i class="mdi mdi-sprout text-success me-2"></i> Raawwii Qophii Qonnaa </h2>
                <p class="text-muted mb-0 mt-1">Track and manage agricultural production achivement</p>
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
                // Group data properly for hierarchical display - SIMPLIFIED without crop type grouping
                $groupedData = [];
                foreach ($cropPlans->groupBy('year') as $year => $plansByYear) {
                    foreach ($plansByYear->groupBy('season') as $season => $plansBySeason) {
                        foreach ($plansBySeason as $plan) {
                            $groupedData[] = [
                                'year' => $year,
                                'season' => $season,
                                'plan' => $plan
                            ];
                        }
                    }
                }
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
                                    <th class="ps-4" style="width: 15%">Year</th>
                                    <th style="width: 15%">Season</th>
                                    <th style="width: 20%">Crop</th>
                                    <th class="text-end" style="width: 15%">Area (ha)</th>
                                    <th class="text-center" style="width: 35%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $currentYear = null;
                                    $currentSeason = null;
                                    $yearRowCount = 0;
                                    $seasonRowCount = 0;
                                @endphp

                                @foreach($groupedData as $index => $data)
                                    @php
                                        $year = $data['year'];
                                        $season = $data['season'];
                                        $plan = $data['plan'];
                                        
                                        // Calculate rowspan for year
                                        if ($currentYear !== $year) {
                                            $currentYear = $year;
                                            $yearRowCount = array_reduce($groupedData, function($count, $item) use ($year) {
                                                return $item['year'] == $year ? $count + 1 : $count;
                                            }, 0);
                                        }
                                        
                                        // Calculate rowspan for season
                                        if ($currentSeason !== $year . '_' . $season) {
                                            $currentSeason = $year . '_' . $season;
                                            $seasonRowCount = array_reduce($groupedData, function($count, $item) use ($year, $season) {
                                                return $item['year'] == $year && $item['season'] == $season ? $count + 1 : $count;
                                            }, 0);
                                        }
                                    @endphp
                                    
                                    <tr class="data-row hover-row" 
                                        data-year="{{ $year }}"
                                        data-season="{{ $season }}"
                                        data-crop="{{ $plan->crop->name ?? '' }}">
                                        
                                        <!-- Year Column - Only show for first row of each year -->
                                        @if ($loop->first || $groupedData[$index-1]['year'] !== $year)
                                            <td class="year-cell align-middle text-center fw-bold" 
                                                rowspan="{{ $yearRowCount }}"
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
                                                    </div>
                                                </div>
                                            </td>
                                        @endif

                                        <!-- Season Column - Only show for first row of each season within year -->
                                        @if ($loop->first || $groupedData[$index-1]['season'] !== $season || $groupedData[$index-1]['year'] !== $year)
                                            <td class="season-cell align-middle text-center"
                                                rowspan="{{ $seasonRowCount }}"
                                                style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);">
                                                <div class="season-content">
                                                    <span class="season-badge fw-semibold text-dark">
                                                        <i class="mdi mdi-weather-sunny me-1 text-warning"></i>
                                                        {{ ucfirst($season) }}
                                                    </span>
                                                    <div class="season-total mt-1">
                                                        <small class="text-muted d-block">Season Total:</small>
                                                        <div class="fw-bold text-success">
                                                            {{ number_format($cropPlans->where('year', $year)->where('season', $season)->sum('area_to_plant'), 2) }} ha
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
                                                        <br>
                                                        <small class="text-muted">{{ $plan->cropType->name ?? '-' }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="text-end fw-semibold text-primary">
                                            {{ number_format($plan->area_to_plant, 2) }} ha
                                        </td>
                                        
                                        <td class="actions-cell text-center align-middle">
                                            <div class="action-buttons">
                                                <a href="{{ route('sector.crop-plans.achivement', $plan->id) }}" 
                                                   class="btn btn-warning btn-sm action-btn mb-1"
                                                   title="Edit Crop Plan"
                                                   data-plan-id="{{ $plan->id }}">
                                                    <i class="mdi mdi-pencil"></i> Raawwii 
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
                        const season = row.getAttribute('data-season').toLowerCase();
                        const year = row.getAttribute('data-year').toLowerCase();
                        
                        const show = cropName.includes(value) || season.includes(value) || year.includes(value);
                        row.style.display = show ? '' : 'none';
                        
                        // Handle rowspan visibility for hidden rows
                        updateRowspanVisibility();
                    });
                });
            }

            function updateRowspanVisibility() {
                // This function would need to handle updating rowspan values when rows are hidden
                // For now, we'll keep it simple and let the browser handle it
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
                    this.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Loading...';
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
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #495057;
            vertical-align: middle;
            white-space: nowrap;
        }

        /* Cell Styles */
        .year-cell, .season-cell {
            border-right: 2px solid #dee2e6;
            vertical-align: middle !important;
        }

        .year-content, .season-content {
            padding: 0.5rem;
        }

        .season-badge {
            font-size: 0.9rem;
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
        .year-total, .season-total {
            margin-top: 0.5rem;
            padding-top: 0.5rem;
            border-top: 1px dashed #dee2e6;
        }

        .year-total small, .season-total small {
            font-size: 0.8rem;
        }

        /* Table row styling */
        .table td {
            padding: 0.8rem;
            border-bottom: 1px solid #e9ecef;
            font-size: 0.9rem;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .action-btn {
            font-size: 0.8rem;
            padding: 0.4rem 0.6rem;
            border-radius: 4px;
            transition: all 0.2s ease;
            width: 100%;
            min-width: 120px;
            text-align: center;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Button colors */
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
                font-size: 0.8rem;
            }
            
            .action-btn {
                font-size: 0.75rem;
                padding: 0.3rem 0.5rem;
                min-width: 100px;
            }
            
            .year-value {
                font-size: 1rem !important;
            }
            
            .action-buttons {
                gap: 0.2rem;
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

        /* Ensure proper vertical alignment for rowspan cells */
        .year-cell, .season-cell {
            vertical-align: middle !important;
        }

        /* Fix for rowspan borders */
        .table > tbody > tr > td {
            border: 1px solid #dee2e6;
        }

        /* Crop details styling */
        .crop-details-cell {
            vertical-align: middle;
        }

        .crop-details small {
            font-size: 0.8rem;
        }
    </style>
</x-layout>