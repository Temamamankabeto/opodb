<x-layout>
    @section('title', 'Kebele Livestock Plans Summary')
    
    <div class="content-header">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>
                            <i class="fas fa-cow nav-icon text-primary mr-2"></i>
                            Kebele Livestock Plans Summary
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        
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

                <!-- Header Section -->
                <div class="row mb-3">
                    <div class="col-md-8">
                         
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('sector.livestock_plans.create') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-plus-circle mr-2"></i> Add New Livestock Plan
                        </a>
                    </div>
                </div>

                @if ($plans->isEmpty())
                    <!-- Empty State -->
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-cow display-4 text-muted mb-3"></i>
                            <h4 class="text-muted">No livestock plans found</h4>
                            <p class="text-muted mb-4">Get started by creating your first livestock plan</p>
                            <a href="{{ route('sector.livestock_plans.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus-circle mr-2"></i> Create Livestock Plan
                            </a>
                        </div>
                    </div>
                @else
                    @php
                        $groupedPlans = $plans->groupBy('year');
                        $currentYear = max($plans->pluck('year')->toArray());
                    @endphp

                    

                    <!-- Data Table Card -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-table mr-2"></i>
                                Livestock Plans Overview
                            </h3>
                            <div class="card-tools">
                                <div class="input-group input-group-sm">
                                    <button class="btn btn-sm btn-default" onclick="scrollToTop('livestockPlansTable')">
                                        <i class="fas fa-arrow-up"></i> Top
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped align-middle" id="livestockPlansTable">
                                    <thead class="thead-light">
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
                                                
                                                <tr class="data-row" 
                                                    data-year="{{ $year }}"
                                                    data-supplies="{{ $supplyCount }}"
                                                    data-products="{{ $productCount }}">
                                                    
                                                    <!-- Row Number -->
                                                    @if ($yearCounter === 1)
                                                        <td class="text-center font-weight-bold align-middle bg-light" 
                                                            rowspan="{{ $yearRowspan }}">
                                                            {{ $counter }}
                                                        </td>
                                                    @endif

                                                    <!-- Year Column -->
                                                    @if ($yearCounter === 1)
                                                        <td class="year-cell align-middle text-center bg-lightblue" 
                                                            rowspan="{{ $yearRowspan }}">
                                                            <div class="year-content">
                                                                <div class="year-value h4 font-weight-bold text-primary mb-1">
                                                                    {{ $year }}
                                                                </div>
                                                                @if($isCurrentYear)
                                                                    <span class="badge badge-success">
                                                                        Current
                                                                    </span>
                                                                @endif
                                                                <div class="year-total mt-2">
                                                                    <small class="text-muted d-block">Year Summary:</small>
                                                                    <div class="font-weight-bold text-success">
                                                                        {{ $yearPlans->sum(function($p) { return count($p->supply_data ?? []); }) }} supplies
                                                                    </div>
                                                                    <div class="font-weight-bold text-primary">
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
                                                                    <div class="supply-item mb-2 p-2 border-left border-warning bg-light rounded">
                                                                        <div class="d-flex justify-content-between align-items-start">
                                                                            <div>
                                                                                <div class="font-weight-semibold text-warning">
                                                                                    <i class="fas fa-box mr-1"></i>
                                                                                    {{ $supply['name'] ?? 'N/A' }}
                                                                                </div>
                                                                                <div class="text-muted small">
                                                                                    <strong>Amount:</strong> 
                                                                                    {{ number_format($supply['amount'] ?? 0, 2) }} 
                                                                                    {{ $supply['unit'] ?? '' }}
                                                                                </div>
                                                                            </div>
                                                                            @if(isset($supply['status']))
                                                                                <span class="badge badge-{{ $supply['status'] == 'completed' ? 'success' : 'warning' }} ml-2">
                                                                                    {{ $supply['status'] }}
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <div class="text-center text-muted py-2">
                                                                <i class="fas fa-box-open"></i>
                                                                <div>No supplies data</div>
                                                            </div>
                                                        @endif
                                                    </td>

                                                    <!-- Livestock Products -->
                                                    <td class="products-cell">
                                                        @if ($productCount > 0)
                                                            <div class="products-content">
                                                                @foreach($plan->product_data as $product)
                                                                    <div class="product-item mb-2 p-2 border-left border-success bg-light rounded">
                                                                        <div class="d-flex justify-content-between align-items-start">
                                                                            <div>
                                                                                <div class="font-weight-semibold text-success">
                                                                                    <i class="fas fa-drumstick-bite mr-1"></i>
                                                                                    {{ $product['name'] ?? 'N/A' }}
                                                                                </div>
                                                                                <div class="text-muted small">
                                                                                    <strong>Amount:</strong> 
                                                                                    {{ number_format($product['amount'] ?? 0, 2) }} 
                                                                                    {{ $product['unit'] ?? '' }}
                                                                                </div>
                                                                            </div>
                                                                            @if(isset($product['status']))
                                                                                <span class="badge badge-{{ $product['status'] == 'completed' ? 'success' : 'info' }} ml-2">
                                                                                    {{ $product['status'] }}
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <div class="text-center text-muted py-2">
                                                                <i class="fas fa-times-circle"></i>
                                                                <div>No products data</div>
                                                            </div>
                                                        @endif
                                                    </td>

                                                    <!-- Actions Column -->
                                                    <td class="actions-cell text-center align-middle">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-sm btn-default dropdown-toggle" 
                                                                    data-toggle="dropdown" 
                                                                    aria-haspopup="true" 
                                                                    aria-expanded="false">
                                                                <i class="fas fa-cog"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <!-- Edit Supply -->
                                                                <a class="dropdown-item" 
                                                                   href="{{ route('sector.livestock_plans.edit', $plan->id) }}">
                                                                    <i class="fas fa-edit text-warning mr-2"></i>
                                                                    Edit Supply
                                                                </a>
                                                                <!-- Add/Edit Product -->
                                                                <a class="dropdown-item" 
                                                                   href="{{ route('sector.livestock_plans.editproduct', $plan->id) }}">
                                                                    <i class="fas fa-utensils text-success mr-2"></i>
                                                                    Manage Products
                                                                </a>
                                                                <div class="dropdown-divider"></div>
                                                                <!-- Delete -->
                                                                <form action="{{ route('sector.livestock_plans.destroy', $plan->id) }}" 
                                                                      method="POST" 
                                                                      onsubmit="return confirm('Are you sure you want to delete this plan?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="dropdown-item text-danger" 
                                                                            type="submit">
                                                                        <i class="fas fa-trash mr-2"></i>
                                                                        Delete Plan
                                                                    </button>
                                                                </form>
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
                        <div class="card-footer clearfix">
                            <div class="float-left">
                                <span class="text-muted">{{ $plans->count() }} plans found</span>
                            </div>
                            <div class="float-right">
                                <button class="btn btn-sm btn-secondary" onclick="scrollToTop('livestockPlansTable')">
                                    <i class="fas fa-arrow-up mr-1"></i> Back to Top
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>

    @section('scripts')
    <script>
        $(function () {
            // Add hover effects to table rows
            $('.data-row').hover(
                function() {
                    $(this).addClass('bg-lightblue');
                },
                function() {
                    $(this).removeClass('bg-lightblue');
                }
            );

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Initialize table scrolling
            initializeTableScrolling();
        });

        function scrollToTop(tableId) {
            const tableContainer = $(`#${tableId}`).closest('.table-responsive');
            tableContainer.animate({ scrollTop: 0 }, 'slow');
        }

        function initializeTableScrolling() {
            $('.table-responsive').on('scroll', function() {
                if ($(this).scrollTop() > 100) {
                    $(this).addClass('scrolled');
                } else {
                    $(this).removeClass('scrolled');
                }
            });
        }
    </script>

    <style>
        .bg-lightblue {
            background-color: #e3f2fd !important;
        }

        /* Table Styles */
        .table-responsive {
            max-height: 70vh;
            overflow: auto;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }

        .table thead th {
            position: sticky;
            top: 0;
            background: #f8f9fa;
            z-index: 10;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            color: #495057;
        }

        /* Cell Styles */
        .year-cell {
            border-right: 2px solid #dee2e6;
        }

        .supply-item, .product-item {
            transition: all 0.2s ease;
        }

        .supply-item:hover, .product-item:hover {
            transform: translateX(2px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Year content */
        .year-content {
            padding: 10px;
        }

        .year-total {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #dee2e6;
        }

        /* Status badges */
        .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        /* Custom scrollbar */
        .table-responsive::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.85rem;
            }
            
            .small-box .inner h3 {
                font-size: 1.8rem;
            }
            
            .card-title {
                font-size: 1.1rem;
            }
        }

        /* Animation for dropdown */
        .dropdown-menu {
            animation: fadeIn 0.2s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Row hover animation */
        .data-row {
            transition: background-color 0.3s ease;
        }

        /* Border left for supply and product items */
        .border-left {
            border-left-width: 4px !important;
        }
    </style>
    @endsection
</x-layout>