<x-layout>
    <div class="content-header">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Building Economic Institution Plans</h1>
                    </div>
                    <div class="col-sm-6">
                         
                    </div>
                </div>
            </div><!-- /.container-fluid -->
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
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="card-title text-primary mb-1">
                                    <i class="fas fa-building mr-2"></i>Total Building Economic Institution Plans
                                </h5>
                                <p class="card-text">
                                    {{ $plans->count() }} Records across {{ $plans->groupBy('year')->count() }} years
                                </p>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="{{ route('sector.building-economic-institution-plans.create') }}" 
                                   class="btn btn-primary">
                                    <i class="fas fa-plus-circle mr-1"></i>Create New Building Plan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Main Table --}}
                <div class="card">
                    <div class="card-header bg-light">
                        <h3 class="card-title">
                            <i class="fas fa-list-check mr-2"></i>Building Economic Institution Plans Summary (Grouped by Year)
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 10%">Year (EC)</th>
                                        <th style="width: 15%">Institution</th>
                                        <th style="width: 35%">New Building Data</th>
                                        <th style="width: 35%">Improvement Data</th>
                                        <th style="width: 10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $groupedPlans = $plans->groupBy('year');
                                        $counter = 1;
                                    @endphp

                                    @forelse ($groupedPlans as $year => $yearPlans)
                                        @foreach ($yearPlans as $index => $plan)
                                            <tr class="@if ($index === 0) year-group-first @else year-group-subsequent @endif">
                                                {{-- Row Number --}}
                                                @if ($index === 0)
                                                    <td class="text-center align-middle" rowspan="{{ $yearPlans->count() }}">
                                                        <span class="badge bg-primary">{{ $counter }}</span>
                                                    </td>
                                                @endif

                                                {{-- Year with Rowspan --}}
                                                @if ($index === 0)
                                                    <td class="text-center align-middle year-header" rowspan="{{ $yearPlans->count() }}">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <span class="font-weight-bold text-primary" style="font-size: 1.1rem;">{{ $year }}</span>
                                                            <small class="text-muted">EC</small>
                                                            <span class="badge badge-info mt-1">
                                                                {{ $yearPlans->count() }} plan{{ $yearPlans->count() > 1 ? 's' : '' }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                @endif

                                                {{-- Institution Name --}}
                                                <td class="align-middle">
                                                    <strong>{{ $plan->institution->name ?? 'N/A' }}</strong>
                                                </td>

                                                {{-- New Building Data --}}
                                                <td>
                                                    @if (!empty($plan->new_building_data) && count($plan->new_building_data) > 0)
                                                        @foreach ($plan->new_building_data as $building)
                                                            <div class="border-left border-primary border-3 pl-2 mb-2">
                                                                <div class="font-weight-bold text-primary mb-1">
                                                                    <i class="fas fa-building mr-1"></i>{{ $building['name'] ?? 'N/A' }}
                                                                </div>
                                                                
                                                                {{-- Public Section --}}
                                                                <div class="border-bottom pb-1 mb-1">
                                                                    <div class="font-weight-bold text-success small">Public</div>
                                                                    <div class="row small mt-1">
                                                                        <div class="col-4">
                                                                            <strong>Number:</strong>
                                                                            {{ number_format($building['public_number'] ?? 0) }}
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <strong>Cost:</strong>
                                                                            {{ number_format($building['public_cost'] ?? 0, 2) }} ETB
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <strong>Total:</strong>
                                                                            {{ number_format(($building['public_number'] ?? 0) * ($building['public_cost'] ?? 0), 2) }} ETB
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{-- Private Section --}}
                                                                <div class="border-bottom pb-1 mb-1">
                                                                    <div class="font-weight-bold text-warning small">Private</div>
                                                                    <div class="row small mt-1">
                                                                        <div class="col-4">
                                                                            <strong>Number:</strong>
                                                                            {{ number_format($building['private_number'] ?? 0) }}
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <strong>Cost:</strong>
                                                                            {{ number_format($building['private_cost'] ?? 0, 2) }} ETB
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <strong>Total:</strong>
                                                                            {{ number_format(($building['private_number'] ?? 0) * ($building['private_cost'] ?? 0), 2) }} ETB
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{-- Association Section --}}
                                                                <div class="border-bottom pb-1 mb-1">
                                                                    <div class="font-weight-bold text-info small">Association</div>
                                                                    <div class="row small mt-1">
                                                                        <div class="col-4">
                                                                            <strong>Number:</strong>
                                                                            {{ number_format($building['association_number'] ?? 0) }}
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <strong>Cost:</strong>
                                                                            {{ number_format($building['association_cost'] ?? 0, 2) }} ETB
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <strong>Total:</strong>
                                                                            {{ number_format(($building['association_number'] ?? 0) * ($building['association_cost'] ?? 0), 2) }} ETB
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{-- Totals Section --}}
                                                                @php
                                                                    $publicTotal = ($building['public_number'] ?? 0) * ($building['public_cost'] ?? 0);
                                                                    $privateTotal = ($building['private_number'] ?? 0) * ($building['private_cost'] ?? 0);
                                                                    $associationTotal = ($building['association_number'] ?? 0) * ($building['association_cost'] ?? 0);
                                                                    $totalBuildings = ($building['public_number'] ?? 0) + ($building['private_number'] ?? 0) + ($building['association_number'] ?? 0);
                                                                    $grandTotal = $publicTotal + $privateTotal + $associationTotal;
                                                                @endphp
                                                                <div class="bg-primary text-white p-1 rounded small">
                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            <strong>Total Buildings:</strong>
                                                                            {{ number_format($totalBuildings) }}
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <strong>Grand Total:</strong>
                                                                            {{ number_format($grandTotal, 2) }} ETB
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                @if(!empty($building['description']))
                                                                    <div class="mt-1 small">
                                                                        <strong>Description:</strong>
                                                                        <span class="text-muted">{{ Str::limit($building['description'], 30) }}</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted small">No new building data</span>
                                                    @endif
                                                </td>

                                                {{-- Improvement Data --}}
                                                <td>
                                                    @if (!empty($plan->improvement_data) && count($plan->improvement_data) > 0)
                                                        @foreach ($plan->improvement_data as $improvement)
                                                            <div class="border-left border-success border-3 pl-2 mb-2">
                                                                <div class="font-weight-bold text-success mb-1">
                                                                    <i class="fas fa-tools mr-1"></i>{{ $improvement['name'] ?? 'N/A' }}
                                                                </div>
                                                                
                                                                {{-- Public Section --}}
                                                                <div class="border-bottom pb-1 mb-1">
                                                                    <div class="font-weight-bold text-success small">Public</div>
                                                                    <div class="row small mt-1">
                                                                        <div class="col-4">
                                                                            <strong>Number:</strong>
                                                                            {{ number_format($improvement['public_number'] ?? 0) }}
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <strong>Cost:</strong>
                                                                            {{ number_format($improvement['public_cost'] ?? 0, 2) }} ETB
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <strong>Total:</strong>
                                                                            {{ number_format(($improvement['public_number'] ?? 0) * ($improvement['public_cost'] ?? 0), 2) }} ETB
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{-- Private Section --}}
                                                                <div class="border-bottom pb-1 mb-1">
                                                                    <div class="font-weight-bold text-warning small">Private</div>
                                                                    <div class="row small mt-1">
                                                                        <div class="col-4">
                                                                            <strong>Number:</strong>
                                                                            {{ number_format($improvement['private_number'] ?? 0) }}
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <strong>Cost:</strong>
                                                                            {{ number_format($improvement['private_cost'] ?? 0, 2) }} ETB
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <strong>Total:</strong>
                                                                            {{ number_format(($improvement['private_number'] ?? 0) * ($improvement['private_cost'] ?? 0), 2) }} ETB
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{-- Association Section --}}
                                                                <div class="border-bottom pb-1 mb-1">
                                                                    <div class="font-weight-bold text-info small">Association</div>
                                                                    <div class="row small mt-1">
                                                                        <div class="col-4">
                                                                            <strong>Number:</strong>
                                                                            {{ number_format($improvement['association_number'] ?? 0) }}
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <strong>Cost:</strong>
                                                                            {{ number_format($improvement['association_cost'] ?? 0, 2) }} ETB
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <strong>Total:</strong>
                                                                            {{ number_format(($improvement['association_number'] ?? 0) * ($improvement['association_cost'] ?? 0), 2) }} ETB
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{-- Totals Section --}}
                                                                @php
                                                                    $publicTotal = ($improvement['public_number'] ?? 0) * ($improvement['public_cost'] ?? 0);
                                                                    $privateTotal = ($improvement['private_number'] ?? 0) * ($improvement['private_cost'] ?? 0);
                                                                    $associationTotal = ($improvement['association_number'] ?? 0) * ($improvement['association_cost'] ?? 0);
                                                                    $totalImprovements = ($improvement['public_number'] ?? 0) + ($improvement['private_number'] ?? 0) + ($improvement['association_number'] ?? 0);
                                                                    $grandTotal = $publicTotal + $privateTotal + $associationTotal;
                                                                @endphp
                                                                <div class="bg-success text-white p-1 rounded small">
                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            <strong>Total Improvements:</strong>
                                                                            {{ number_format($totalImprovements) }}
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <strong>Grand Total:</strong>
                                                                            {{ number_format($grandTotal, 2) }} ETB
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                @if(!empty($improvement['description']))
                                                                    <div class="mt-1 small">
                                                                        <strong>Description:</strong>
                                                                        <span class="text-muted">{{ Str::limit($improvement['description'], 30) }}</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted small">No improvement data</span>
                                                    @endif
                                                </td>

                                                {{-- Actions --}}
                                                <td class="text-center align-middle">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                            <i class="fas fa-cog"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            {{-- Edit Plan --}}
                                                            <a class="dropdown-item" href="{{ route('sector.building-economic-institution-plans.edit', $plan->id) }}">
                                                                <i class="fas fa-edit text-warning mr-2"></i>Edit Plan
                                                            </a>

                                                            {{-- View Details --}}
                                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#detailsModal{{ $plan->id }}">
                                                                <i class="fas fa-eye text-info mr-2"></i>View Details
                                                            </a>

                                                            {{-- Delete --}}
                                                            <div class="dropdown-divider"></div>
                                                            <form action="{{ route('sector.building-economic-institution-plans.destroy', $plan->id) }}" 
                                                                  method="POST" 
                                                                  class="delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this building economic institution plan?');">
                                                                    <i class="fas fa-trash mr-2"></i>Delete
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
                                            <td colspan="6" class="text-center py-4">
                                                <div class="alert alert-info m-0">
                                                    <i class="fas fa-info-circle mr-2"></i>
                                                    No building economic institution plans found.
                                                    <a href="{{ route('sector.building-economic-institution-plans.create') }}" class="d-block mt-2 btn btn-sm btn-primary">
                                                        Create your first building economic institution plan
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
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
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Institution:</strong>
                                {{ $plan->institution->name ?? 'N/A' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Year:</strong> {{ $plan->year }} EC
                            </div>
                        </div>

                        {{-- New Building Data --}}
                        <div class="card card-primary card-outline mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">New Building Data</h6>
                            </div>
                            <div class="card-body">
                                @if (!empty($plan->new_building_data) && count($plan->new_building_data) > 0)
                                    @foreach ($plan->new_building_data as $index => $building)
                                        <div class="border-bottom pb-3 mb-3">
                                            <h6 class="text-primary mb-2">
                                                <i class="fas fa-building mr-1"></i>
                                                #{{ $index + 1 }} - {{ $building['name'] }}
                                            </h6>
                                            
                                            {{-- Public Data --}}
                                            <div class="row small mb-2">
                                                <div class="col-12">
                                                    <strong class="text-success">Public Sector:</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Number:</strong>
                                                    {{ number_format($building['public_number']) }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Cost:</strong>
                                                    {{ number_format($building['public_cost'], 2) }} ETB
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Total:</strong>
                                                    {{ number_format($building['public_number'] * $building['public_cost'], 2) }} ETB
                                                </div>
                                            </div>

                                            {{-- Private Data --}}
                                            <div class="row small mb-2">
                                                <div class="col-12">
                                                    <strong class="text-warning">Private Sector:</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Number:</strong>
                                                    {{ number_format($building['private_number']) }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Cost:</strong>
                                                    {{ number_format($building['private_cost'], 2) }} ETB
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Total:</strong>
                                                    {{ number_format($building['private_number'] * $building['private_cost'], 2) }} ETB
                                                </div>
                                            </div>

                                            {{-- Association Data --}}
                                            <div class="row small mb-2">
                                                <div class="col-12">
                                                    <strong class="text-info">Association Sector:</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Number:</strong>
                                                    {{ number_format($building['association_number']) }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Cost:</strong>
                                                    {{ number_format($building['association_cost'], 2) }} ETB
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Total:</strong>
                                                    {{ number_format($building['association_number'] * $building['association_cost'], 2) }} ETB
                                                </div>
                                            </div>

                                            {{-- Totals --}}
                                            @php
                                                $publicTotal = $building['public_number'] * $building['public_cost'];
                                                $privateTotal = $building['private_number'] * $building['private_cost'];
                                                $associationTotal = $building['association_number'] * $building['association_cost'];
                                                $totalBuildings = $building['public_number'] + $building['private_number'] + $building['association_number'];
                                                $grandTotal = $publicTotal + $privateTotal + $associationTotal;
                                            @endphp
                                            <div class="bg-light p-2 rounded mb-2">
                                                <div class="row small">
                                                    <div class="col-12 mb-1">
                                                        <strong class="text-primary">Summary:</strong>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <strong>Total Buildings:</strong>
                                                        {{ number_format($totalBuildings) }}
                                                    </div>
                                                    <div class="col-md-8">
                                                        <strong>Grand Total:</strong>
                                                        {{ number_format($grandTotal, 2) }} ETB
                                                    </div>
                                                </div>
                                            </div>

                                            @if(!empty($building['description']))
                                                <div class="mt-2">
                                                    <strong>Description:</strong>
                                                    {{ $building['description'] }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted mb-0">No new building data</p>
                                @endif
                            </div>
                        </div>

                        {{-- Improvement Data --}}
                        <div class="card card-success card-outline">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Improvement Data</h6>
                            </div>
                            <div class="card-body">
                                @if (!empty($plan->improvement_data) && count($plan->improvement_data) > 0)
                                    @foreach ($plan->improvement_data as $index => $improvement)
                                        <div class="border-bottom pb-3 mb-3">
                                            <h6 class="text-success mb-2">
                                                <i class="fas fa-tools mr-1"></i>
                                                #{{ $index + 1 }} - {{ $improvement['name'] }}
                                            </h6>
                                            
                                            {{-- Public Data --}}
                                            <div class="row small mb-2">
                                                <div class="col-12">
                                                    <strong class="text-success">Public Sector:</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Number:</strong>
                                                    {{ number_format($improvement['public_number']) }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Cost:</strong>
                                                    {{ number_format($improvement['public_cost'], 2) }} ETB
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Total:</strong>
                                                    {{ number_format($improvement['public_number'] * $improvement['public_cost'], 2) }} ETB
                                                </div>
                                            </div>

                                            {{-- Private Data --}}
                                            <div class="row small mb-2">
                                                <div class="col-12">
                                                    <strong class="text-warning">Private Sector:</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Number:</strong>
                                                    {{ number_format($improvement['private_number']) }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Cost:</strong>
                                                    {{ number_format($improvement['private_cost'], 2) }} ETB
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Total:</strong>
                                                    {{ number_format($improvement['private_number'] * $improvement['private_cost'], 2) }} ETB
                                                </div>
                                            </div>

                                            {{-- Association Data --}}
                                            <div class="row small mb-2">
                                                <div class="col-12">
                                                    <strong class="text-info">Association Sector:</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Number:</strong>
                                                    {{ number_format($improvement['association_number']) }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Cost:</strong>
                                                    {{ number_format($improvement['association_cost'], 2) }} ETB
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Total:</strong>
                                                    {{ number_format($improvement['association_number'] * $improvement['association_cost'], 2) }} ETB
                                                </div>
                                            </div>

                                            {{-- Totals --}}
                                            @php
                                                $publicTotal = $improvement['public_number'] * $improvement['public_cost'];
                                                $privateTotal = $improvement['private_number'] * $improvement['private_cost'];
                                                $associationTotal = $improvement['association_number'] * $improvement['association_cost'];
                                                $totalImprovements = $improvement['public_number'] + $improvement['private_number'] + $improvement['association_number'];
                                                $grandTotal = $publicTotal + $privateTotal + $associationTotal;
                                            @endphp
                                            <div class="bg-light p-2 rounded mb-2">
                                                <div class="row small">
                                                    <div class="col-12 mb-1">
                                                        <strong class="text-success">Summary:</strong>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <strong>Total Improvements:</strong>
                                                        {{ number_format($totalImprovements) }}
                                                    </div>
                                                    <div class="col-md-8">
                                                        <strong>Grand Total:</strong>
                                                        {{ number_format($grandTotal, 2) }} ETB
                                                    </div>
                                                </div>
                                            </div>

                                            @if(!empty($improvement['description']))
                                                <div class="mt-2">
                                                    <strong>Description:</strong>
                                                    {{ $improvement['description'] }}
                                                </div>
                                            @endif
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
                        <a href="{{ route('sector.building-economic-institution-plans.edit', $plan->id) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-edit mr-1"></i>Edit Plan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <style>
        .year-header {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-right: 3px solid #1976d2;
        }

        .year-group-first {
            border-top: 2px solid #e0e0e0;
        }

        .year-group-subsequent {
            border-left: 2px dashed #e0e0e0;
        }

        .border-left {
            border-left: 3px solid !important;
        }

        .table th {
            font-weight: 600;
            background-color: #f8f9fa;
        }

        .table tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.03) !important;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.875rem;
            }
            
            .col-4, .col-3, .col-6, .col-8 {
                margin-bottom: 0.25rem;
            }
        }

        /* Badge styling */
        .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        /* Color coding */
        .text-success {
            color: #28a745 !important;
        }

        .text-warning {
            color: #ffc107 !important;
        }

        .text-info {
            color: #17a2b8 !important;
        }

        .text-primary {
            color: #007bff !important;
        }

        .bg-primary {
            background-color: #007bff !important;
        }

        .bg-success {
            background-color: #28a745 !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            // Auto-show modal if there's a modal ID in the URL hash
            const hash = window.location.hash;
            if (hash && hash.startsWith('#detailsModal')) {
                $(hash).modal('show');
            }
        });
    </script>
</x-layout>