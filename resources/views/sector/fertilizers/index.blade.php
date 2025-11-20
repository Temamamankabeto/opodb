<x-layout>
    <div class="container-fluid py-4 fertilizers-container">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="page-header">
                <h2 class="mb-0"><i class="mdi mdi-chemical-weapon text-warning me-2"></i> Fertilizer Management</h2>
                <p class="text-muted mb-0 mt-1">Grouped fertilizer summary by Year, Crop Type, and Crop</p>
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

         

        @if (empty($groupedData))
            <!-- Empty State -->
            <div class="empty-state text-center py-5">
                <i class="mdi mdi-information-outline display-4 text-muted mb-3"></i>
                <h4 class="text-muted">No crop plans found</h4>
                <p class="text-muted mb-4">There are no crop plans available for fertilizer management.</p>
            </div>
        @else
            <!-- Grouped Fertilizer Summary -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark">
                        <i class="mdi mdi-table me-2 text-primary"></i>
                        Grouped Fertilizer Summary
                    </h5>
                    <div class="d-flex gap-2">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search year, crop type, or crop...">
                        <button class="btn btn-outline-primary" onclick="exportToExcel()">
                            <i class="mdi mdi-download me-1"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table table-hover align-middle mb-0" id="fertilizerTable">
                            <thead class="table-light sticky-header">
                                <tr>
                                    <th class="ps-4">Year</th>
                                    <th>Crop Type</th>
                                    <th>Crop</th>
                                    <th class="text-end">Area (ha)</th>
                                    <th class="text-end">Total Fertilizer</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groupedData as $year => $yearData)
                                    @php 
                                        $isCurrentYear = $year == date('Y');
                                        $yearRowspan = 0;
                                        foreach ($yearData['crop_types'] as $typeData) {
                                            $yearRowspan += count($typeData['crops']);
                                        }
                                    @endphp
                                    
                                    @foreach ($yearData['crop_types'] as $cropType => $typeData)
                                        @php $typeRowspan = count($typeData['crops']); @endphp
                                        
                                        @foreach ($typeData['crops'] as $crop => $cropData)
                                            <tr class="data-row" data-year="{{ $year }}" data-type="{{ $cropType }}" data-crop="{{ $crop }}">
                                                <!-- Year Column -->
                                                @if ($loop->parent->first && $loop->first)
                                                    <td class="fw-bold text-center year-cell ps-4 align-top {{ $isCurrentYear ? 'current-year' : '' }}" 
                                                        rowspan="{{ $yearRowspan }}">
                                                        <div class="year-badge">
                                                            {{ $year }}
                                                            @if($isCurrentYear)
                                                                <span class="current-badge">Current</span>
                                                            @endif
                                                        </div>
                                                         
                                                    </td>
                                                @endif
                                                
                                                <!-- Crop Type Column -->
                                                @if ($loop->first)
                                                    <td class="fw-semibold text-primary align-top" rowspan="{{ $typeRowspan }}">
                                                        <div class="crop-type-info">
                                                            <i class="mdi mdi-seed-outline me-1"></i>
                                                            {{ $cropType }}
                                                             
                                                        </div>
                                                    </td>
                                                @endif
                                                
                                                <!-- Crop Column -->
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="crop-icon me-2">
                                                            <i class="mdi mdi-sprout text-success"></i>
                                                        </div>
                                                        <div>
                                                            <strong>{{ $crop }}</strong>
                                                        </div>
                                                    </div>
                                                </td>
                                                
                                                <!-- Area Column -->
                                                <td class="text-end fw-semibold text-info">
                                                    {{ number_format($cropData['total_area'], 2) }}
                                                </td>
                                                
                                                <!-- Total Fertilizer Column -->
                                                <td>
                                                    @if (!empty($cropData['fertilizers']))
                                                        <div class="fertilizer-details">
                                                            @foreach ($cropData['fertilizers'] as $fertKey => $fertData)
                                                                <div class="fert-item mb-1">
                                                                    <span class="badge bg-light text-dark fert-badge {{ $fertData['unit'] === 'kg' ? 'border-warning' : 'border-secondary' }}" 
                                                                          data-bs-toggle="tooltip" 
                                                                          title="{{ $fertData['fertilizer_type_name'] }} - {{ $fertData['fertilizer_name'] }} ({{ $fertData['unit'] }})">
                                                                        <i class="mdi mdi-chemical-weapon me-1 text-warning"></i>
                                                                        {{ $fertData['fertilizer_name'] }}: 
                                                                        {{ number_format($fertData['amount'], 2) }} {{ $fertData['unit'] }}
                                                                        @if($fertData['unit'] === 'kg')
                                                                            <small class="text-success ms-1">✓</small>
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="text-muted small">
                                                            <i class="mdi mdi-alert-circle-outline me-1"></i>
                                                            No fertilizer data
                                                        </span>
                                                    @endif
                                                </td>
                                                
                                               
                                                
                                                <!-- Actions Column -->
                                                <td class="text-center">
                                                    @php
                                                        // Get the first plan ID for this crop group
                                                        $firstPlanId = !empty($cropData['plans']) ? $cropData['plans'][0] : null;
                                                    @endphp
                                                    @if($firstPlanId)
                                                        <a href="{{ route('sector.crop-plans.editfertilizer', $firstPlanId) }}" 
                                                           class="btn btn-sm btn-warning update-fertilizer-btn"
                                                           title="Update Fertilizer for {{ $crop }}"
                                                           data-plan-id="{{ $firstPlanId }}">
                                                            <i class="mdi mdi-pencil me-1"></i>
                                                            Update
                                                        </a>
                                                    @else
                                                        <span class="text-muted small">No plans</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const value = this.value.toLowerCase();
                    const rows = document.querySelectorAll('#fertilizerTable tbody tr.data-row');
                    
                    rows.forEach(function(row) {
                        const year = row.getAttribute('data-year').toLowerCase();
                        const type = row.getAttribute('data-type').toLowerCase();
                        const crop = row.getAttribute('data-crop').toLowerCase();
                        
                        const show = year.includes(value) || type.includes(value) || crop.includes(value);
                        row.style.display = show ? '' : 'none';
                    });
                });
            }

            // Add loading state to update fertilizer buttons
            document.querySelectorAll('.update-fertilizer-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    const planId = this.getAttribute('data-plan-id');
                    // Add loading state
                    const originalHtml = this.innerHTML;
                    this.innerHTML = '<i class="mdi mdi-loading mdi-spin me-1"></i> Loading...';
                    this.disabled = true;
                    
                    console.log('Navigating to update fertilizer for plan:', planId);
                });
            });

            // Make table rows clickable for better UX
            document.querySelectorAll('.data-row').forEach(row => {
                row.addEventListener('click', function(e) {
                    // Don't trigger if clicking on buttons or links
                    if (!e.target.closest('a') && !e.target.closest('button')) {
                        this.classList.toggle('table-active');
                    }
                });
            });
        });

        function exportToExcel() {
            try {
                const table = document.getElementById('fertilizerTable');
                const wb = XLSX.utils.table_to_book(table);
                XLSX.writeFile(wb, 'grouped_fertilizer_summary.xlsx');
            } catch (error) {
                console.error('Export error:', error);
                alert('Error exporting to Excel: ' + error.message);
            }
        }
    </script>

    <style>
        .fertilizers-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        .summary-card {
            transition: transform 0.2s ease;
        }

        .summary-card:hover {
            transform: translateY(-2px);
        }

        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
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

        /* Scrollable Table Styles */
        .table-container {
            position: relative;
            max-height: 70vh;
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

        /* Table row styling */
        .year-cell {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-right: 2px solid #dee2e6;
        }

        .current-year {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        }

        .current-badge {
            font-size: 0.7em;
            background: #28a745;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 8px;
        }

        .crop-type-info {
            padding: 8px 0;
        }

        .type-totals, .year-totals {
            font-size: 0.8em;
        }

        .fert-badge {
            border: 1px solid #dee2e6;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75em;
            cursor: help;
            transition: all 0.2s ease;
            display: inline-block;
            margin: 2px;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .fert-badge:hover {
            background: #f8f9fa !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: none;
            white-space: normal;
            z-index: 5;
        }

        .fertilizer-details {
            max-width: 250px;
            min-width: 200px;
        }

        .update-fertilizer-btn {
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .update-fertilizer-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .data-row {
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .data-row:hover {
            background-color: rgba(0, 123, 255, 0.05) !important;
        }

        .data-row.table-active {
            background-color: rgba(0, 123, 255, 0.1) !important;
        }

        /* Ensure table cells have proper spacing */
        .table td, .table th {
            vertical-align: middle;
            padding: 0.75rem;
        }

        /* Custom scrollbar for table container */
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
                max-height: 60vh;
                font-size: 0.875rem;
            }
            
            .fert-badge {
                font-size: 0.7em;
                padding: 3px 6px;
                max-width: 150px;
            }
            
            .update-fertilizer-btn {
                font-size: 0.8rem;
                padding: 0.25rem 0.5rem;
            }
            
            .crop-type-info, .year-totals, .type-totals {
                font-size: 0.75rem;
            }
        }

        @media (max-width: 576px) {
            .table-container {
                max-height: 50vh;
            }
            
            .card-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            .card-header .d-flex {
                width: 100%;
            }
            
            #searchInput {
                width: 100% !important;
            }
        }
    </style>
</x-layout>