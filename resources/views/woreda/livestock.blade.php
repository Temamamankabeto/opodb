<x-layout>
    <div class="container-fluid py-4 livestock-plans-container">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="page-header">
                <h2 class="mb-0"><i class="mdi mdi-cow text-primary me-2"></i> Woreda Livestock Plans Summary</h2>
                <p class="text-muted mb-0 mt-1">Aggregated livestock production plans from all kebeles in your woreda</p>
            </div>
            <div class="export-controls">
                <button class="btn btn-success me-2" onclick="exportToExcel()">
                    <i class="mdi mdi-download me-1"></i> Export Excel
                </button>
                <button class="btn btn-info" onclick="toggleKebeleBreakdown()">
                    <i class="mdi mdi-chart-bar me-1"></i> Toggle Kebele View
                </button>
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

        <!-- Summary Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card performance-card performance-total border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="mdi mdi-calendar-text display-6 text-primary mb-2"></i>
                        <div class="performance-value fs-3 fw-bold text-primary">
                            {{ $plans->count() }}
                        </div>
                        <div class="performance-label text-muted">Total Plans</div>
                        <small class="text-muted">{{ $kebelesCount }} Kebeles</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card performance-card performance-growth border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="mdi mdi-calendar-range display-6 text-success mb-2"></i>
                        <div class="performance-value fs-3 fw-bold text-success">
                            {{ $plans->groupBy('year')->count() }}
                        </div>
                        <div class="performance-label text-muted">Years Covered</div>
                        <small class="text-muted">Current: {{ $currentYear }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card performance-card performance-new border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="mdi mdi-package-variant display-6 text-warning mb-2"></i>
                        <div class="performance-value fs-3 fw-bold text-warning">
                            {{ $totalSupplies }}
                        </div>
                        <div class="performance-label text-muted">Total Supplies</div>
                        <small class="text-muted">{{ $uniqueSupplyTypes }} types</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card performance-card performance-decline border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="mdi mdi-food display-6 text-info mb-2"></i>
                        <div class="performance-value fs-3 fw-bold text-info">
                            {{ $totalProducts }}
                        </div>
                        <div class="performance-label text-muted">Total Products</div>
                        <small class="text-muted">{{ $uniqueProductTypes }} types</small>
                    </div>
                </div>
            </div>
        </div>

        @if ($plans->isEmpty())
            <!-- Empty State -->
            <div class="empty-state text-center py-5">
                <i class="mdi mdi-cow-off display-4 text-muted mb-3"></i>
                <h4 class="text-muted">No livestock plans found</h4>
                <p class="text-muted mb-4">No kebeles in your woreda have submitted livestock plans yet</p>
            </div>
        @else
            <!-- Kebele Filter -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h6 class="mb-0"><i class="mdi mdi-filter me-2"></i>Filter by Kebele</h6>
                <small class="text-muted">Showing kebeles from your woreda with active plans</small>
            </div>
            <div class="col-md-6">
                <div class="d-flex gap-2">
                    <select id="kebeleFilter" class="form-select">
                        <option value="">All Kebeles ({{ $kebelesWithPlans->count() }})</option>
                        @foreach($kebelesWithPlans as $kebele)
                            @php
                                $planCount = $kebele->livestockPlans->count(); // Now using the relationship
                            @endphp
                            <option value="{{ $kebele->id }}">
                                {{ $kebele->name }} 
                                ({{ $planCount }} plan{{ $planCount !== 1 ? 's' : '' }})
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-outline-secondary" onclick="clearKebeleFilter()" id="clearFilterBtn" style="display: none;">
                        <i class="mdi mdi-close"></i>
                    </button>
                </div>
                @if($allKebelesInWoreda->count() > $kebelesWithPlans->count())
                    <small class="text-warning mt-1 d-block">
                        <i class="mdi mdi-information-outline"></i>
                        {{ $allKebelesInWoreda->count() - $kebelesWithPlans->count() }} kebeles in your woreda don't have livestock plans yet
                    </small>
                @endif
            </div>
        </div>
    </div>
</div>

            <!-- Data Table -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark">
                        <i class="mdi mdi-table me-2 text-primary"></i>
                        Woreda Livestock Plans Overview
                        <small class="text-muted ms-2">(Aggregated from {{ $kebelesCount }} kebeles)</small>
                    </h5>
                    <div class="table-controls">
                        <small class="text-muted me-3" id="planCount">{{ $plans->count() }} plans</small>
                        <button class="btn btn-sm btn-outline-secondary" onclick="scrollToTop('livestockPlansTable')">
                            <i class="mdi mdi-arrow-up"></i> Top
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table table-hover align-middle mb-0" id="livestockPlansTable">
                            <thead class="table-light sticky-header">
                                <tr>
                                    <th class="text-center" style="width: 5%">#</th>
                                    <th class="text-center" style="width: 10%">Year (EC)</th>
                                    <th class="text-center" style="width: 15%">Kebele</th>
                                    <th style="width: 25%">Livestock Supplies</th>
                                    <th style="width: 25%">Livestock Products</th>
                                    <th class="text-center" style="width: 10%">Status</th>
                                    <th class="text-center" style="width: 10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $counter = 1;
                                    $currentYearIndex = null;
                                    $yearRowspan = 0;
                                    $yearCounter = 0;
                                @endphp

                                @foreach($groupedPlans as $year => $yearPlans)
                                    @php
                                        if ($currentYearIndex !== $year) {
                                            $currentYearIndex = $year;
                                            $yearRowspan = count($yearPlans);
                                            $yearCounter = 0;
                                        }
                                    @endphp

                                    @foreach($yearPlans as $index => $plan)
                                        @php
                                            $yearCounter++;
                                            $supplyCount = count($plan->supply_data ?? []);
                                            $productCount = count($plan->product_data ?? []);
                                            $isCurrentYear = $year == $currentYear;
                                            $kebeleName = $plan->kebele->name ?? 'Unknown Kebele';
                                            $kebeleId = $plan->kebele_id;
                                        @endphp
                                        
                                        <tr class="data-row hover-row kebele-row" 
                                            data-year="{{ $year }}"
                                            data-kebele="{{ $kebeleId }}"
                                            data-kebele-name="{{ $kebeleName }}"
                                            data-supplies="{{ $supplyCount }}"
                                            data-products="{{ $productCount }}">
                                            
                                            <!-- Row Number -->
                                            @if ($yearCounter === 1)
                                                <td class="text-center fw-bold align-middle" 
                                                    rowspan="{{ $yearRowspan }}"
                                                    style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                                    {{ $counter }}
                                                </td>
                                            @endif

                                            <!-- Year Column -->
                                            @if ($yearCounter === 1)
                                                <td class="year-cell align-middle text-center" 
                                                    rowspan="{{ $yearRowspan }}"
                                                    style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);">
                                                    <div class="year-content">
                                                        <div class="year-value fs-5 fw-bold text-primary mb-1">
                                                            {{ $year }}
                                                        </div>
                                                        @if($isCurrentYear)
                                                            <span class="badge bg-success bg-opacity-20 text-success border border-success">
                                                                Current
                                                            </span>
                                                        @endif
                                                        <div class="year-total mt-2">
                                                            <small class="text-muted d-block">Year Summary:</small>
                                                            <div class="fw-bold text-success">
                                                                {{ $yearPlans->sum(function($p) { return count($p->supply_data ?? []); }) }} supplies
                                                            </div>
                                                            <div class="fw-bold text-primary">
                                                                {{ $yearPlans->sum(function($p) { return count($p->product_data ?? []); }) }} products
                                                            </div>
                                                            <div class="fw-bold text-info small">
                                                                {{ $yearPlans->groupBy('kebele_id')->count() }} kebeles
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endif

                                            <!-- Kebele Column -->
                                            <td class="kebele-cell align-middle text-center"
                                                style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);">
                                                <div class="kebele-content">
                                                    <div class="kebele-name fw-semibold text-dark">
                                                        <i class="mdi mdi-map-marker me-1 text-success"></i>
                                                        {{ $kebeleName }}
                                                    </div>
                                                    <small class="text-muted">Kebele ID: {{ $kebeleId }}</small>
                                                </div>
                                            </td>

                                            <!-- Livestock Supplies -->
                                            <td class="supplies-cell">
                                                @if ($supplyCount > 0)
                                                    <div class="supplies-content">
                                                        @foreach($plan->supply_data as $supply)
                                                            <div class="supply-item mb-2 p-2 border-start border-warning border-3 bg-light rounded">
                                                                <div class="d-flex justify-content-between align-items-start">
                                                                    <div class="flex-grow-1">
                                                                        <div class="fw-semibold text-warning">
                                                                            <i class="mdi mdi-package-variant-closed me-1"></i>
                                                                            {{ $supply['name'] ?? 'N/A' }}
                                                                        </div>
                                                                        <div class="text-muted small">
                                                                            <strong>Amount:</strong> 
                                                                            {{ number_format($supply['amount'] ?? 0, 2) }} 
                                                                            {{ $supply['unit'] ?? '' }}
                                                                        </div>
                                                                        @if(isset($supply['source']))
                                                                            <div class="text-muted small">
                                                                                <strong>Source:</strong> {{ $supply['source'] }}
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    @if(isset($supply['status']))
                                                                        <span class="badge bg-{{ $supply['status'] == 'completed' ? 'success' : 'warning' }} ms-2">
                                                                            {{ $supply['status'] }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="text-center text-muted py-2">
                                                        <i class="mdi mdi-package-variant-remove"></i>
                                                        <div>No supplies data</div>
                                                    </div>
                                                @endif
                                            </td>

                                            <!-- Livestock Products -->
                                            <td class="products-cell">
                                                @if ($productCount > 0)
                                                    <div class="products-content">
                                                        @foreach($plan->product_data as $product)
                                                            <div class="product-item mb-2 p-2 border-start border-success border-3 bg-light rounded">
                                                                <div class="d-flex justify-content-between align-items-start">
                                                                    <div class="flex-grow-1">
                                                                        <div class="fw-semibold text-success">
                                                                            <i class="mdi mdi-food me-1"></i>
                                                                            {{ $product['name'] ?? 'N/A' }}
                                                                        </div>
                                                                        <div class="text-muted small">
                                                                            <strong>Amount:</strong> 
                                                                            {{ number_format($product['amount'] ?? 0, 2) }} 
                                                                            {{ $product['unit'] ?? '' }}
                                                                        </div>
                                                                        @if(isset($product['market']))
                                                                            <div class="text-muted small">
                                                                                <strong>Market:</strong> {{ $product['market'] }}
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    @if(isset($product['status']))
                                                                        <span class="badge bg-{{ $product['status'] == 'completed' ? 'success' : 'info' }} ms-2">
                                                                            {{ $product['status'] }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="text-center text-muted py-2">
                                                        <i class="mdi mdi-food-off"></i>
                                                        <div>No products data</div>
                                                    </div>
                                                @endif
                                            </td>

                                            <!-- Status Column -->
                                            <td class="text-center align-middle">
                                                <div class="status-indicator">
                                                    @if($supplyCount > 0 && $productCount > 0)
                                                        <span class="badge bg-success bg-opacity-20 text-success border border-success">
                                                            <i class="mdi mdi-check-circle me-1"></i>Complete
                                                        </span>
                                                        <small class="d-block text-muted mt-1">
                                                            {{ $supplyCount }}S / {{ $productCount }}P
                                                        </small>
                                                    @elseif($supplyCount > 0)
                                                        <span class="badge bg-warning bg-opacity-20 text-warning border border-warning">
                                                            <i class="mdi mdi-progress-clock me-1"></i>Supplies Only
                                                        </span>
                                                        <small class="d-block text-muted mt-1">
                                                            {{ $supplyCount }} supplies
                                                        </small>
                                                    @elseif($productCount > 0)
                                                        <span class="badge bg-info bg-opacity-20 text-info border border-info">
                                                            <i class="mdi mdi-progress-check me-1"></i>Products Only
                                                        </span>
                                                        <small class="d-block text-muted mt-1">
                                                            {{ $productCount }} products
                                                        </small>
                                                    @else
                                                        <span class="badge bg-secondary bg-opacity-20 text-secondary border border-secondary">
                                                            <i class="mdi mdi-alert-circle me-1"></i>No Data
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>

                                            <!-- Actions Column -->
                                            <td class="actions-cell text-center align-middle">
                                                <div class="action-buttons">
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle action-menu-toggle" 
                                                                type="button" 
                                                                data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                            <i class="mdi mdi-cog"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                                            <!-- View Details -->
                                                            <li>
                                                                <a class="dropdown-item d-flex align-items-center py-2 action-item view-details"
                                                                   href="javascript:void(0)"
                                                                   data-plan-id="{{ $plan->id }}">
                                                                    <i class="mdi mdi-eye-outline text-info me-2"></i>
                                                                    <span>View Details</span>
                                                                </a>
                                                            </li>
                                                            <!-- Kebele Profile -->
                                                            <li>
                                                                <a class="dropdown-item d-flex align-items-center py-2 action-item"
                                                                   href=" ">
                                                                    <i class="mdi mdi-account-group text-primary me-2"></i>
                                                                    <span>Kebele Profile</span>
                                                                </a>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <!-- Reports -->
                                                            <li>
                                                                <a class="dropdown-item d-flex align-items-center py-2 action-item"
                                                                   href=" ">
                                                                    <i class="mdi mdi-chart-bar text-success me-2"></i>
                                                                    <span>Generate Report</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @php $counter++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Summary Section -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="mb-0"><i class="mdi mdi-chart-pie me-2"></i>Supply Distribution</h6>
                        </div>
                        <div class="card-body">
                            <div id="supplyChart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="mb-0"><i class="mdi mdi-chart-bar me-2"></i>Kebele Performance</h6>
                        </div>
                        <div class="card-body">
                            <div id="kebeleChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Plan Details Modal -->
    <div class="modal fade" id="planDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Livestock Plan Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="planDetailsContent">
                    <!-- Details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Kebele filter functionality
        const kebeleFilter = document.getElementById('kebeleFilter');
        const clearFilterBtn = document.getElementById('clearFilterBtn');
        
        if (kebeleFilter) {
            kebeleFilter.addEventListener('change', function() {
                const kebeleId = this.value;
                filterTableByKebele(kebeleId);
                
                // Show/hide clear filter button
                if (kebeleId) {
                    clearFilterBtn.style.display = 'block';
                } else {
                    clearFilterBtn.style.display = 'none';
                }
            });
        }

        // Clear filter functionality
        window.clearKebeleFilter = function() {
            if (kebeleFilter) {
                kebeleFilter.value = '';
                filterTableByKebele('');
                clearFilterBtn.style.display = 'none';
            }
        }

        // Enhanced filter function
        function filterTableByKebele(kebeleId) {
            const rows = document.querySelectorAll('.kebele-row');
            let visibleCount = 0;
            let yearGroups = {};

            rows.forEach(row => {
                const rowKebeleId = row.getAttribute('data-kebele');
                const year = row.getAttribute('data-year');
                
                if (!kebeleId || rowKebeleId === kebeleId) {
                    row.style.display = '';
                    visibleCount++;
                    
                    // Track visible rows per year for rowspan calculations
                    if (!yearGroups[year]) {
                        yearGroups[year] = 0;
                    }
                    yearGroups[year]++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update rowspan for year cells
            updateYearRowSpans(yearGroups);
            
            // Update plan count display
            document.getElementById('planCount').textContent = visibleCount + ' plans';
            
            // Update URL with filter parameter (optional)
            updateUrlWithFilter(kebeleId);
        }

        // Update year row spans after filtering
        function updateYearRowSpans(yearGroups) {
            const yearCells = document.querySelectorAll('.year-cell');
            yearCells.forEach(cell => {
                const year = cell.closest('tr').getAttribute('data-year');
                const rowspan = yearGroups[year] || 0;
                cell.setAttribute('rowspan', rowspan);
                
                // Hide year cell if no rows visible for that year
                if (rowspan === 0) {
                    cell.style.display = 'none';
                } else {
                    cell.style.display = '';
                }
            });

            // Update row numbers
            updateRowNumbers();
        }

        // Update row numbers after filtering
        function updateRowNumbers() {
            const visibleRows = document.querySelectorAll('.kebele-row[style=""]');
            let counter = 1;
            let currentYear = null;
            let yearCounter = 0;

            visibleRows.forEach((row, index) => {
                const year = row.getAttribute('data-year');
                
                if (year !== currentYear) {
                    currentYear = year;
                    yearCounter = 0;
                    
                    // Update the first row of each year group with the counter
                    const firstRowInYear = document.querySelector(`.kebele-row[data-year="${year}"][style=""]`);
                    const counterCell = firstRowInYear.querySelector('td:first-child');
                    if (counterCell) {
                        counterCell.textContent = counter;
                        counterCell.setAttribute('rowspan', getVisibleRowsInYear(year));
                    }
                    counter++;
                }
            });
        }

        // Helper function to count visible rows in a year
        function getVisibleRowsInYear(year) {
            return document.querySelectorAll(`.kebele-row[data-year="${year}"][style=""]`).length;
        }

        // Update URL with filter parameter (optional)
        function updateUrlWithFilter(kebeleId) {
            const url = new URL(window.location);
            if (kebeleId) {
                url.searchParams.set('kebele', kebeleId);
            } else {
                url.searchParams.delete('kebele');
            }
            window.history.replaceState({}, '', url);
        }

        // Check for initial filter from URL
        function checkInitialFilter() {
            const urlParams = new URLSearchParams(window.location.search);
            const initialKebele = urlParams.get('kebele');
            if (initialKebele && kebeleFilter) {
                kebeleFilter.value = initialKebele;
                filterTableByKebele(initialKebele);
                clearFilterBtn.style.display = 'block';
            }
        }

        // Initialize
        checkInitialFilter();

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

        // Plan details modal
        document.querySelectorAll('.view-details').forEach(button => {
            button.addEventListener('click', function() {
                const planId = this.getAttribute('data-plan-id');
                showPlanDetails(planId);
            });
        });

        // Initialize table scrolling
        initializeTableScrolling();
    });

    function showPlanDetails(planId) {
        // In a real implementation, you would fetch plan details via AJAX
        const modal = new bootstrap.Modal(document.getElementById('planDetailsModal'));
        document.getElementById('planDetailsContent').innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading plan details...</p>
            </div>
        `;
        modal.show();

        // Simulate API call
        setTimeout(() => {
            document.getElementById('planDetailsContent').innerHTML = `
                <h6>Plan Information</h6>
                <p><strong>Plan ID:</strong> ${planId}</p>
                <p><strong>Kebele:</strong> Sample Kebele</p>
                <p><strong>Year:</strong> 2016 EC</p>
                <p><strong>Status:</strong> Active</p>
                <hr>
                <h6>Detailed breakdown would appear here</h6>
                <p>This would include comprehensive information about supplies, products, timelines, and performance metrics.</p>
            `;
        }, 1000);
    }

    function exportToExcel() {
        const kebeleFilter = document.getElementById('kebeleFilter');
        const kebeleId = kebeleFilter ? kebeleFilter.value : '';
        const kebeleName = kebeleFilter ? kebeleFilter.options[kebeleFilter.selectedIndex].text : 'All';
        
        if (kebeleId) {
            alert(`Exporting Excel file for: ${kebeleName}`);
        } else {
            alert('Exporting Excel file with all woreda livestock plan data');
        }
    }

    function toggleKebeleBreakdown() {
        alert('Kebele breakdown view would show aggregated data by kebele');
    }

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
        .livestock-plans-container {
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
        .year-cell, .kebele-cell {
            border-right: 2px solid #dee2e6;
            vertical-align: middle !important;
        }

        .year-content, .kebele-content {
            padding: 0.5rem;
        }

        .supply-item, .product-item {
            transition: all 0.2s ease;
        }

        .supply-item:hover, .product-item:hover {
            transform: translateX(2px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
            justify-content: center;
        }

        .action-menu-toggle {
            border-radius: 6px;
            transition: all 0.2s ease;
            padding: 0.35rem 0.5rem;
        }

        .action-menu-toggle:hover {
            background-color: #f8f9fa;
            transform: translateY(-1px);
        }

        .dropdown-menu {
            min-width: 200px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
            animation: fadeIn 0.2s ease-out;
        }

        .action-item {
            transition: all 0.15s ease;
            border-radius: 4px;
            margin: 0 4px;
            width: calc(100% - 8px);
        }

        .action-item:hover {
            background-color: #f0f8ff;
            transform: translateX(2px);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
            
            .performance-card {
                padding: 1rem;
            }
            
            .performance-value {
                font-size: 1.5rem !important;
            }
            
            .dropdown-menu {
                min-width: 180px;
            }
            
            .export-controls {
                flex-direction: column;
                gap: 10px;
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
        .year-cell, .kebele-cell {
            vertical-align: middle !important;
        }

        /* Fix for rowspan borders */
        .table > tbody > tr > td {
            border: 1px solid #dee2e6;
        }

        /* Status badges */
        .status-indicator .badge {
            font-size: 0.75rem;
            padding: 0.4rem 0.6rem;
        }

        /* Export controls */
        .export-controls {
            display: flex;
            gap: 10px;
        }

        /* Chart containers */
        #supplyChart, #kebeleChart {
            height: 250px;
            background: #f8f9fa;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-style: italic;
        }
    </style>
</x-layout>