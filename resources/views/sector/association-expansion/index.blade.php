<x-layout>
    @section('title', 'Farmers Cooperative Plan')
    
    <div class="content-header">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="text-primary">
                            <i class="fas fa-chart-line mr-2"></i>Farmers Cooperative Plan
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
                {{-- Flash message --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> Success!</h5>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Summary Card --}}
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="font-weight-bold text-primary mb-1">Total Farmers Cooperative Plan</h5>
                                <p class="mb-0 text-muted">{{ $plans->count() }} Records across
                                    {{ $plans->groupBy('year')->count() }} years</p>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="{{ route('sector.association-expansion-plans.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus-circle mr-1"></i>Create New Expansion Plan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Main Table --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list-check mr-2"></i>Association Expansion Plans Summary (Grouped by Year)
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover m-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center" style="width: 5%">#</th>
                                        <th class="text-center" style="width: 10%">Year (EC)</th>
                                        <th style="width: 70%">Association Expansion Data</th>                             
                                        <th class="text-center" style="width: 15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $groupedPlans = $plans->groupBy('year');
                                        $counter = 1;
                                    @endphp

                                    @forelse ($groupedPlans as $year => $yearPlans)
                                        @foreach ($yearPlans as $index => $plan)
                                            <tr>
                                                {{-- Row Number --}}
                                                @if ($index === 0)
                                                    <td class="text-center font-weight-bold align-middle bg-light" 
                                                        rowspan="{{ $yearPlans->count() }}">
                                                        {{ $counter }}
                                                    </td>
                                                @endif

                                                {{-- Year with Rowspan --}}
                                                @if ($index === 0)
                                                    <td class="font-weight-semibold text-center align-middle bg-lightblue" 
                                                        rowspan="{{ $yearPlans->count() }}">
                                                        <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                                            <span class="h4 font-weight-bold text-primary">{{ $year }}</span>
                                                            <small class="text-muted mt-1">EC</small>
                                                            <div class="badge badge-primary mt-2">
                                                                {{ $yearPlans->count() }}
                                                                plan{{ $yearPlans->count() > 1 ? 's' : '' }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                @endif

                                                {{-- Association Expansion Data --}}
                                                <td>
                                                    @if (!empty($plan->expansion_data) && count($plan->expansion_data) > 0)
                                                        @foreach ($plan->expansion_data as $association)
                                                            <div class="mb-2 p-2 border-left border-success bg-light rounded">
                                                                <div class="font-weight-semibold text-success mb-1">
                                                                    {{ $association['association_name'] ?? 'N/A' }}
                                                                </div>
                                                                <div class="row small">
                                                                    <div class="col-md-6">
                                                                        <strong>Number:</strong> {{ number_format($association['number_of_number'] ?? 0) }}
                                                                    </div>
                                                                    @if(isset($association['association_type_id']) && $association['association_type_id'] == 1)
                                                                        <div class="col-md-6">
                                                                            <strong>Members:</strong> {{ number_format($association['number_of_members'] ?? 0) }}
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong>Capital:</strong> {{ number_format($association['total_capital'] ?? 0, 2) }} ETB
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong>Loans Taken:</strong> {{ number_format($association['fertilizer_loans_taken'] ?? 0, 2) }} ETB
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong>Loan Repayment:</strong> {{ number_format($association['fertilizer_loan_repayment'] ?? 0, 2) }} ETB
                                                                        </div>
                                                                    @else
                                                                        <div class="col-md-6">
                                                                            <strong>Type:</strong> {{ $association['association_type'] ?? 'Other' }}
                                                                        </div>
                                                                        <div class="col-md-12 mt-1">
                                                                            <em class="text-muted">Number data available for this type</em>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">No expansion data</span>
                                                    @endif
                                                </td>

                                                {{-- Actions --}}
                                                <td class="text-center align-middle">
    <div class="btn-group">
        <button type="button" 
                class="btn btn-sm btn-default dropdown-toggle"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
            <i class="fas fa-cog"></i>
        </button>

        <div class="dropdown-menu dropdown-menu-right shadow">

            {{-- Edit Plan --}}
            <a class="dropdown-item" 
               href="{{ route('sector.association-expansion-plans.edit', $plan->id) }}">
                <i class="fas fa-edit text-warning mr-2"></i>
                Edit Plan
            </a>

            <div class="dropdown-divider"></div>

            {{-- Delete --}}
            <form action="{{ route('sector.association-expansion-plans.destroy', $plan->id) }}"
                  method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this association expansion plan?');"
                  style="display: inline;">
                @csrf
                @method('DELETE')
                <button class="dropdown-item text-danger" type="submit">
                    <i class="fas fa-trash mr-2"></i>
                    Delete
                </button>
            </form>

        </div>
    </div>
</td>

                                            </tr>
                                        @endforeach
                                        @php $counter++; @endphp
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="fas fa-info-circle mr-1"></i>No association expansion plans found.
                                                <div class="mt-2">
                                                    <a href="{{ route('sector.association-expansion-plans.create') }}" class="btn btn-sm btn-primary">
                                                        Create your first association expansion plan
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .bg-lightblue {
            background-color: #e3f2fd !important;
        }

        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .border-left {
            border-left-width: 4px !important;
        }

        .btn-group .dropdown-menu {
            min-width: 180px;
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .table > tbody > tr > td {
            vertical-align: middle !important;
        }

        /* Ensure proper border for rowspan cells */
        .table-bordered > tbody > tr > td,
        .table-bordered > tbody > tr > th {
            border: 1px solid #dee2e6;
        }

        /* Hover effect for table rows */
        .table-hover > tbody > tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }

        /* Badge styling */
        .badge-primary {
            background-color: #007bff;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.85rem;
            }
            
            .btn-group .dropdown-menu {
                min-width: 160px;
            }
            
            .btn-group .btn-sm {
                padding: 0.25rem 0.5rem;
            }
        }

        /* Card header styling */
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .card-title {
            color: #495057;
            font-weight: 600;
        }
    </style>

    <script>
        $(function () {
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
            
            // Add hover effect to action buttons
            $('.btn-group').hover(
                function() {
                    $(this).addClass('show');
                },
                function() {
                    $(this).removeClass('show');
                }
            );
        });
    </script>
</x-layout>