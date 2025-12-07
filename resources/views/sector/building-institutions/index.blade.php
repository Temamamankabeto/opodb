<x-layout>
    @section('title', 'Building Community Institution Plans')
    
    <div class="content-header">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="text-primary">
                            <i class="fas fa-building mr-2"></i>
                            Building Community Institution Plans
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
                                <h5 class="font-weight-bold text-primary mb-1">Total Building Community Institution Plans</h5>
                                <p class="mb-0 text-muted">{{ $plans->count() }} Records across
                                    {{ $plans->groupBy('year')->count() }} years</p>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="{{ route('sector.building-community-institution-plans.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus-circle mr-1"></i>Create New Building Plan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Main Table --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list-check mr-2"></i>
                            Building Community Institution Plans Summary (Grouped by Year)
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover m-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center" style="width: 5%">#</th>
                                        <th class="text-center" style="width: 10%">Year (EC)</th>
                                        <th style="width: 15%">Institution</th>
                                        <th style="width: 25%">New Building Data</th>
                                        <th style="width: 25%">Improvement Data</th>
                                        <th class="text-center" style="width: 20%">Actions</th>
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

                                                {{-- Institution Name --}}
                                                <td class="font-weight-semibold align-middle">
                                                    {{ $plan->institution->name ?? 'N/A' }}
                                                </td>

                                                {{-- New Building Data --}}
                                                <td>
                                                    @if (!empty($plan->new_building_data) && count($plan->new_building_data) > 0)
                                                        @foreach ($plan->new_building_data as $building)
                                                            <div class="mb-2 p-2 border-left border-primary bg-light rounded">
                                                                <div class="font-weight-semibold text-primary mb-1">
                                                                    {{ $building['name'] ?? 'N/A' }}
                                                                </div>
                                                                <div class="row small">
                                                                    <div class="col-6">
                                                                        <strong>Number:</strong> {{ number_format($building['number'] ?? 0) }}
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <strong>Cost:</strong> {{ number_format($building['cost'] ?? 0, 2) }} ETB
                                                                    </div>
                                                                    <div class="col-12 mt-1">
                                                                        <strong>Description:</strong> 
                                                                        <span class="text-muted">{{ Str::limit($building['description'] ?? 'No description', 50) }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">No new building data</span>
                                                    @endif
                                                </td>

                                                {{-- Improvement Data --}}
                                                <td>
                                                    @if (!empty($plan->improvement_data) && count($plan->improvement_data) > 0)
                                                        @foreach ($plan->improvement_data as $improvement)
                                                            <div class="mb-2 p-2 border-left border-success bg-light rounded">
                                                                <div class="font-weight-semibold text-success mb-1">
                                                                    {{ $improvement['name'] ?? 'N/A' }}
                                                                </div>
                                                                <div class="row small">
                                                                    <div class="col-6">
                                                                        <strong>Number:</strong> {{ number_format($improvement['number'] ?? 0) }}
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <strong>Cost:</strong> {{ number_format($improvement['cost'] ?? 0, 2) }} ETB
                                                                    </div>
                                                                    <div class="col-12 mt-1">
                                                                        <strong>Description:</strong> 
                                                                        <span class="text-muted">{{ Str::limit($improvement['description'] ?? 'No description', 50) }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">No improvement data</span>
                                                    @endif
                                                </td>

                                                {{-- Actions --}}
                                                <td class="text-center align-middle">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-sm btn-default dropdown-toggle" 
                                                                data-toggle="dropdown" 
                                                                aria-haspopup="true" 
                                                                aria-expanded="false">
                                                            <i class="fas fa-cog"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right shadow">
                                                            {{-- Edit Plan --}}
                                                            <a class="dropdown-item" 
                                                               href="{{ route('sector.building-community-institution-plans.edit', $plan->id) }}">
                                                                <i class="fas fa-edit text-warning mr-2"></i>
                                                                Edit Plan
                                                            </a>
                                                            {{-- View Details --}}
                                                            <a class="dropdown-item" 
                                                               href="#" data-toggle="modal" 
                                                               data-target="#detailsModal{{ $plan->id }}">
                                                                <i class="fas fa-eye text-info mr-2"></i>
                                                                View Details
                                                            </a>
                                                            <div class="dropdown-divider"></div>
                                                            {{-- Delete --}}
                                                            <form action="{{ route('sector.building-community-institution-plans.destroy', $plan->id) }}"
                                                                  method="POST"
                                                                  onsubmit="return confirm('Are you sure you want to delete this building community institution plan?');"
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
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="fas fa-info-circle mr-1"></i>No building community institution plans found.
                                                <div class="mt-2">
                                                    <a href="{{ route('sector.building-community-institution-plans.create') }}" class="btn btn-sm btn-primary">
                                                        Create your first building community institution plan
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

    {{-- Details Modals --}}
    @foreach ($plans as $plan)
        <div class="modal fade" id="detailsModal{{ $plan->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-building mr-2"></i>
                            Building Plan Details - {{ $plan->year }} EC
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="text-white">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Institution:</strong> {{ $plan->institution->name ?? 'N/A' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Year:</strong> {{ $plan->year }} EC
                            </div>
                        </div>

                        {{-- New Building Data --}}
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">New Building Data</h6>
                            </div>
                            <div class="card-body">
                                @if (!empty($plan->new_building_data) && count($plan->new_building_data) > 0)
                                    @foreach ($plan->new_building_data as $index => $building)
                                        <div class="border-bottom pb-2 mb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                            <h6 class="text-primary">#{{ $index + 1 }} - {{ $building['name'] }}</h6>
                                            <div class="row small">
                                                <div class="col-md-3"><strong>Number:</strong> {{ number_format($building['number']) }}</div>
                                                <div class="col-md-3"><strong>Cost:</strong> {{ number_format($building['cost'], 2) }} ETB</div>
                                                <div class="col-md-6"><strong>Total:</strong> {{ number_format($building['number'] * $building['cost'], 2) }} ETB</div>
                                                <div class="col-12 mt-1">
                                                    <strong>Description:</strong> {{ $building['description'] }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted mb-0">No new building data</p>
                                @endif
                            </div>
                        </div>

                        {{-- Improvement Data --}}
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Improvement Data</h6>
                            </div>
                            <div class="card-body">
                                @if (!empty($plan->improvement_data) && count($plan->improvement_data) > 0)
                                    @foreach ($plan->improvement_data as $index => $improvement)
                                        <div class="border-bottom pb-2 mb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                            <h6 class="text-success">#{{ $index + 1 }} - {{ $improvement['name'] }}</h6>
                                            <div class="row small">
                                                <div class="col-md-3"><strong>Number:</strong> {{ number_format($improvement['number']) }}</div>
                                                <div class="col-md-3"><strong>Cost:</strong> {{ number_format($improvement['cost'], 2) }} ETB</div>
                                                <div class="col-md-6"><strong>Total:</strong> {{ number_format($improvement['number'] * $improvement['cost'], 2) }} ETB</div>
                                                <div class="col-12 mt-1">
                                                    <strong>Description:</strong> {{ $improvement['description'] }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted mb-0">No improvement data</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <a href="{{ route('sector.building-community-institution-plans.edit', $plan->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit mr-1"></i>Edit Plan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

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

        /* Modal styling */
        .modal-header {
            background-color: #007bff;
        }

        .modal-header .close {
            color: white;
            opacity: 0.8;
        }

        .modal-header .close:hover {
            opacity: 1;
        }

        /* Small text styling */
        .small {
            font-size: 0.875rem;
        }

        /* Text truncation */
        .text-truncate-container {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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

            // Auto-show modal if there's a modal ID in the URL hash
            const hash = window.location.hash;
            if (hash && hash.startsWith('#detailsModal')) {
                $(hash).modal('show');
            }
        });
    </script>
</x-layout>