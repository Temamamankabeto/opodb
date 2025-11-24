<x-layout>
    <div class="container-fluid py-4 livestock-plans-container">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="page-header">
                <h2 class="mb-0"><i class="mdi mdi-cow text-primary me-2"></i> Kebele Livestock Plans Summary</h2>
                <p class="text-muted mb-0 mt-1">Track and manage livestock production plans with supply and product tracking</p>
            </div>
            <a href="{{ route('sector.livestock_plans.create') }}" class="btn btn-primary btn-add-plan">
                <i class="mdi mdi-plus-circle-outline me-2"></i> Add New Livestock Plan
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

      

        @if ($plans->isEmpty())
            <!-- Empty State -->
            <div class="empty-state text-center py-5">
                <i class="mdi mdi-cow-off display-4 text-muted mb-3"></i>
                <h4 class="text-muted">No livestock plans found</h4>
                <p class="text-muted mb-4">Get started by creating your first livestock plan</p>
                <a href="{{ route('sector.livestock_plans.create') }}" class="btn btn-primary">
                    <i class="mdi mdi-plus-circle-outline me-2"></i> Create Livestock Plan
                </a>
            </div>
        @else
            @php
                $groupedPlans = $plans->groupBy('year');
                $currentYear = max($plans->pluck('year')->toArray());
            @endphp

            <!-- Data Table -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark">
                        <i class="mdi mdi-table me-2 text-primary"></i>
                        Livestock Plans Overview
                    </h5>
                    <div class="table-controls">
                        <small class="text-muted me-3">{{ $plans->count() }} plans found</small>
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
                                    <th style="width: 30%">Livestock Supplies</th>
                                    <th style="width: 30%">Livestock Products</th>
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
                                        @endphp
                                        
                                        <tr class="data-row hover-row" 
                                            data-year="{{ $year }}"
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
                                                        </div>
                                                    </div>
                                                </td>
                                            @endif

                                            <!-- Livestock Supplies -->
                                            <td class="supplies-cell">
                                                @if ($supplyCount > 0)
                                                    <div class="supplies-content">
                                                        @foreach($plan->supply_data as $supply)
                                                            <div class="supply-item mb-2 p-2 border-start border-warning border-3 bg-light rounded">
                                                                <div class="d-flex justify-content-between align-items-start">
                                                                    <div>
                                                                        <div class="fw-semibold text-warning">
                                                                            <i class="mdi mdi-package-variant-closed me-1"></i>
                                                                            {{ $supply['name'] ?? 'N/A' }}
                                                                        </div>
                                                                        <div class="text-muted small">
                                                                            <strong>Amount:</strong> 
                                                                            {{ number_format($supply['amount'] ?? 0, 2) }} 
                                                                            {{ $supply['unit'] ?? '' }}
                                                                        </div>
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
                                                                    <div>
                                                                        <div class="fw-semibold text-success">
                                                                            <i class="mdi mdi-food me-1"></i>
                                                                            {{ $product['name'] ?? 'N/A' }}
                                                                        </div>
                                                                        <div class="text-muted small">
                                                                            <strong>Amount:</strong> 
                                                                            {{ number_format($product['amount'] ?? 0, 2) }} 
                                                                            {{ $product['unit'] ?? '' }}
                                                                        </div>
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
                                                            <!-- Edit Supply -->
                                                            <li>
                                                                <a class="dropdown-item d-flex align-items-center py-2 action-item"
                                                                   href="{{ route('sector.livestock_plans.edit', $plan->id) }}">
                                                                    <i class="mdi mdi-pencil-box-outline text-warning me-2"></i>
                                                                    <span>Edit Supply</span>
                                                                </a>
                                                            </li>
                                                            <!-- Add/Edit Product -->
                                                            <li>
                                                                <a class="dropdown-item d-flex align-items-center py-2 action-item"
                                                                   href="{{ route('sector.livestock_plans.editproduct', $plan->id) }}">
                                                                    <i class="mdi mdi-food-outline text-success me-2"></i>
                                                                    <span>Manage Products</span>
                                                                </a>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <!-- Delete -->
                                                            <li>
                                                                <form action="{{ route('sector.livestock_plans.destroy', $plan->id) }}" 
                                                                      method="POST" 
                                                                      onsubmit="return confirm('Are you sure you want to delete this plan?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="dropdown-item d-flex align-items-center text-danger py-2 action-item" 
                                                                            type="submit">
                                                                        <i class="mdi mdi-delete-outline me-2"></i>
                                                                        <span>Delete Plan</span>
                                                                    </button>
                                                                </form>
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
            document.querySelectorAll('.action-item').forEach(button => {
                button.addEventListener('click', function(e) {
                    if (this.tagName === 'A') {
                        const originalHtml = this.innerHTML;
                        this.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Loading...';
                        this.style.pointerEvents = 'none';
                        
                        setTimeout(() => {
                            this.innerHTML = originalHtml;
                            this.style.pointerEvents = '';
                        }, 2000);
                    }
                });
            });

            // Initialize table scrolling
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
        .year-cell {
            border-right: 2px solid #dee2e6;
            vertical-align: middle !important;
        }

        .year-content {
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
        .year-cell {
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
    </style>
</x-layout>