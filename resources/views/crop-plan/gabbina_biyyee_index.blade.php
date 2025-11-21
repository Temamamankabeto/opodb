<x-layout>
    <div class="container-fluid py-4 compost-plans-container">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="page-header">
                <h2 class="mb-0"><i class="mdi mdi-recycle text-success me-2"></i> Hojii Gabbina Biyyoo</h2>
                <p class="text-muted mb-0 mt-1">Track and manage compost production plans with comprehensive overview</p>
            </div>
            <a href="{{ route('sector.gabbina_biyyee.create') }}" class="btn btn-success btn-add-plan">
                <i class="mdi mdi-plus-circle-outline me-2"></i> Add New Compost Plan
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

       

        @if($gabbinaBiyyee->count() > 0)
            @php
                // Prepare data for hierarchical display
                $groupedData = [];
                foreach ($gabbinaBiyyee as $year => $plansByYear) {
                    foreach ($plansByYear as $plan) {
                        $groupedData[] = [
                            'year' => $year,
                            'plan' => $plan
                        ];
                    }
                }
                
                // Sort by year descending
                usort($groupedData, function($a, $b) {
                    return $b['year'] - $a['year'];
                });
            @endphp

            <!-- Data Table -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark">
                        <i class="mdi mdi-table me-2 text-primary"></i>
                        Compost Plans Overview
                    </h5>
                    
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table table-hover align-middle mb-0" id="compostPlansTable">
                            <thead class="table-light sticky-header">
                                <tr>
                                    <th class="ps-4" style="width: 15%">Year</th>
                                    <th style="width: 20%">Compost Type</th>
                                    <th class="text-end" style="width: 12%">Common (Qt)</th>
                                    <th class="text-end" style="width: 12%">Individual (Qt)</th>
                                    <th class="text-end" style="width: 12%">Total (Qt)</th>
                                    <th class="text-end" style="width: 12%">Land Area (ha)</th>
                                    <th class="text-center" style="width: 17%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $currentYearIndex = null;
                                    $yearRowspan = 0;
                                    $yearCounter = 0;
                                @endphp

                                @foreach($groupedData as $index => $data)
                                    @php
                                        $year = $data['year'];
                                        $plan = $data['plan'];
                                        
                                        // Calculate rowspans dynamically
                                        if ($currentYearIndex !== $year) {
                                            $currentYearIndex = $year;
                                            $yearRowspan = array_reduce($groupedData, function($count, $item) use ($year) {
                                                return $item['year'] == $year ? $count + 1 : $count;
                                            }, 0);
                                            $yearCounter = 0;
                                        }
                                        $yearCounter++;
                                    @endphp
                                    
                                    <tr class="data-row hover-row" 
                                        data-year="{{ $year }}"
                                        data-type="{{ $plan->compost_type }}">
                                        
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
                                                            @php
                                                                $yearTotal = 0;
                                                                foreach($gabbinaBiyyee[$year] as $yearPlan) {
                                                                    $yearTotal += $yearPlan->total;
                                                                }
                                                            @endphp
                                                            {{ number_format($yearTotal, 2) }} Qt
                                                        </div>
                                                        <div class="fw-bold text-primary">
                                                            {{ count($gabbinaBiyyee[$year]) }} Types
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif

                                        <!-- Compost Type -->
                                        <td class="compost-type-cell">
                                            <div class="compost-type-content">
                                                <div class="d-flex align-items-center">
                                                    <div class="compost-icon me-2">
                                                        <i class="mdi mdi-recycle text-success"></i>
                                                    </div>
                                                    <div>
                                                        <span class="fw-semibold text-dark">{{ $plan->compost_type }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <!-- Common Quantity -->
                                        <td class="text-end fw-semibold text-primary">
                                            {{ number_format($plan->common, 2) }}
                                        </td>
                                        
                                        <!-- Individual Quantity -->
                                        <td class="text-end fw-semibold text-info">
                                            {{ number_format($plan->individual, 2) }}
                                        </td>
                                        
                                        <!-- Total Quantity -->
                                        <td class="text-end">
                                            <span class="badge bg-primary productivity-badge">
                                                {{ number_format($plan->total, 2) }}
                                            </span>
                                        </td>

                                        <!-- Land Area -->
                                        <td class="text-end">
                                            @if($plan->land_area)
                                                <span class="badge bg-warning text-dark land-area-badge">
                                                    {{ number_format($plan->land_area, 2) }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        
                                        <!-- Actions -->
                                        <td class="actions-cell text-center align-middle">
                                            <div class="action-buttons">
                                                <a href="{{ route('sector.gabbina_biyyee.edit', $plan->id) }}" 
                                                   class="btn btn-warning btn-sm action-btn mb-1"
                                                   title="Edit Compost Plan"
                                                   data-plan-id="{{ $plan->id }}">
                                                    <i class="mdi mdi-pencil"></i> Edit
                                                </a>
                                                 <a href="{{ route('sector.gabbina_biyyee.plan', $plan->id) }}" 
                                                   class="btn btn-warning btn-sm action-btn mb-1"
                                                   title="Edit Compost Plan"
                                                   data-plan-id="{{ $plan->id }}">
                                                    <i class="mdi mdi-pencil"></i> schedule
                                                </a>
                                               
                                                <form action="{{ route('sector.gabbina_biyyee.destroy', $plan->id) }}" 
                                                      method="POST" 
                                                      class="d-inline delete-form"
                                                      onsubmit="return confirmDelete(this)">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm action-btn" title="Delete">
                                                        <i class="mdi mdi-delete"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

           
        @else
            <!-- Empty State -->
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="mdi mdi-inbox-outline display-4 text-muted"></i>
                    <h5 class="text-muted mt-3">No compost plans found</h5>
                    <p class="text-muted">Get started by adding your first compost production plan.</p>
                    <a href="{{ route('sector.gabbina_biyyee.create') }}" class="btn btn-success mt-2">
                        <i class="mdi mdi-plus-circle-outline me-1"></i> Add Compost Plan
                    </a>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                    if (!this.closest('form')) { // Only for non-form buttons (edit)
                        const originalHtml = this.innerHTML;
                        this.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Loading...';
                        this.disabled = true;
                        
                        setTimeout(() => {
                            if (this.disabled) {
                                this.innerHTML = originalHtml;
                                this.disabled = false;
                            }
                        }, 2000);
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
                    if (this.scrollTop > 100) {
                        this.classList.add('scrolled');
                    } else {
                        this.classList.remove('scrolled');
                    }
                });
            });
        }

        function confirmDelete(form) {
            const compostType = form.closest('tr').querySelector('.compost-type-content .fw-semibold').textContent;
            return confirm(`Are you sure you want to delete the compost plan for "${compostType.trim()}"?`);
        }
    </script>

    <style>
        .compost-plans-container {
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
        .year-cell {
            border-right: 2px solid #dee2e6;
            vertical-align: middle !important;
        }

        .year-content {
            padding: 0.5rem;
        }

        .compost-icon {
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Total sections within cells */
        .year-total {
            margin-top: 0.5rem;
            padding-top: 0.5rem;
            border-top: 1px dashed #dee2e6;
        }

        .year-total small {
            font-size: 0.7rem;
        }

        /* Table row styling */
        .table td {
            padding: 0.6rem;
            border-bottom: 1px solid #e9ecef;
            font-size: 0.85rem;
        }

        /* Badge Styles */
        .productivity-badge, .land-area-badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.6rem;
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
            font-size: 1.8rem;
            font-weight: 600;
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
            font-size: 0.75rem;
            padding: 0.4rem 0.6rem;
            border-radius: 4px;
            transition: all 0.2s ease;
            width: 100%;
            text-decoration: none;
            border: none;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Delete form styling */
        .delete-form {
            display: inline;
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
                padding: 0.3rem;
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
            
            .action-buttons {
                flex-direction: row;
                justify-content: center;
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

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border: none;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #ff8c00 0%, #ff5722 100%);
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #c82333 0%, #a71e2a 100%);
        }

        /* Ensure proper vertical alignment for rowspan cells */
        .year-cell {
            vertical-align: middle !important;
        }

        /* Fix for rowspan borders */
        .table > tbody > tr > td {
            border: 1px solid #dee2e6;
        }

        /* Custom alert */
        .custom-alert {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</x-layout>