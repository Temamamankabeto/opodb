<x-layout>
    <div class="container-fluid py-4">
        <h2 class="mb-4 text-primary fw-bold">
            <i class="bi bi-building me-2"></i>Building Economic Institution Plans
        </h2>

        {{-- Flash message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Summary Card --}}
        <div class="card mb-4 shadow-sm border-0 rounded-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold text-primary mb-1">Total Building Economic Institution Plans</h5>
                    <p class="mb-0 text-muted">{{ $plans->count() }} Records across
                        {{ $plans->groupBy('year')->count() }} years</p>
                </div>
                <div>
                    <a href="{{ route('sector.building-economic-institution-plans.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Create New Building Plan
                    </a>
                </div>
            </div>
        </div>

        {{-- Main Table --}}
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-light fw-bold">
                <i class="bi bi-list-check me-2"></i>Building Economic Institution Plans Summary (Grouped by Year)
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>#</th>
                            <th>Year (EC)</th>
                            <th>Institution</th>
                            <th>New Building Data</th>
                            <th>Improvement Data</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $groupedPlans = $plans->groupBy('year');
                            $counter = 1;
                        @endphp

                        @forelse ($groupedPlans as $year => $yearPlans)
                            @foreach ($yearPlans as $index => $plan)
                                <tr
                                    class="@if ($index === 0) year-group-first @else year-group-subsequent @endif">
                                    {{-- Row Number --}}
                                    @if ($index === 0)
                                        <td class="text-center fw-bold" rowspan="{{ $yearPlans->count() }}">
                                            {{ $counter }}
                                        </td>
                                    @endif

                                    {{-- Year with Rowspan --}}
                                    @if ($index === 0)
                                        <td class="fw-semibold text-center year-header"
                                            rowspan="{{ $yearPlans->count() }}">
                                            <div
                                                class="d-flex flex-column align-items-center justify-content-center h-100">
                                                <span class="fs-5 fw-bold text-primary">{{ $year }}</span>
                                                <small class="text-muted mt-1">EC</small>
                                                <div class="badge bg-primary mt-2">
                                                    {{ $yearPlans->count() }}
                                                    plan{{ $yearPlans->count() > 1 ? 's' : '' }}
                                                </div>
                                            </div>
                                        </td>
                                    @endif

                                    {{-- Institution Name --}}
                                    <td class="fw-semibold">
                                        {{ $plan->institution->name ?? 'N/A' }}
                                    </td>

                                    {{-- New Building Data --}}
                                    <td class="small">
                                        @if (!empty($plan->new_building_data) && count($plan->new_building_data) > 0)
                                            @foreach ($plan->new_building_data as $building)
                                                <div
                                                    class="mb-2 p-2 border-start border-primary border-3 ps-2 bg-light rounded">
                                                    <div class="fw-semibold text-primary mb-2">
                                                        {{ $building['name'] ?? 'N/A' }}
                                                    </div>
                                                    
                                                    {{-- Public Section --}}
                                                    <div class="border-bottom pb-2 mb-2">
                                                        <div class="fw-semibold text-success small">Public</div>
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
                                                    <div class="border-bottom pb-2 mb-2">
                                                        <div class="fw-semibold text-warning small">Private</div>
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
                                                    <div class="border-bottom pb-2 mb-2">
                                                        <div class="fw-semibold text-info small">Association</div>
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
                                                    <div class="bg-primary text-white p-2 rounded">
                                                        @php
                                                            $publicTotal = ($building['public_number'] ?? 0) * ($building['public_cost'] ?? 0);
                                                            $privateTotal = ($building['private_number'] ?? 0) * ($building['private_cost'] ?? 0);
                                                            $associationTotal = ($building['association_number'] ?? 0) * ($building['association_cost'] ?? 0);
                                                            $totalBuildings = ($building['public_number'] ?? 0) + ($building['private_number'] ?? 0) + ($building['association_number'] ?? 0);
                                                            $grandTotal = $publicTotal + $privateTotal + $associationTotal;
                                                        @endphp
                                                        <div class="row small text-white">
                                                            <div class="col-4">
                                                                <strong>Total Buildings:</strong>
                                                                {{ number_format($totalBuildings) }}
                                                            </div>
                                                            <div class="col-8">
                                                                <strong>Grand Total:</strong>
                                                                {{ number_format($grandTotal, 2) }} ETB
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-2">
                                                        <strong>Description:</strong>
                                                        <span class="text-muted">{{ Str::limit($building['description'] ?? 'No description', 50) }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-muted">No new building data</span>
                                        @endif
                                    </td>

                                    {{-- Improvement Data --}}
                                    <td class="small">
                                        @if (!empty($plan->improvement_data) && count($plan->improvement_data) > 0)
                                            @foreach ($plan->improvement_data as $improvement)
                                                <div
                                                    class="mb-2 p-2 border-start border-success border-3 ps-2 bg-light rounded">
                                                    <div class="fw-semibold text-success mb-2">
                                                        {{ $improvement['name'] ?? 'N/A' }}
                                                    </div>
                                                    
                                                    {{-- Public Section --}}
                                                    <div class="border-bottom pb-2 mb-2">
                                                        <div class="fw-semibold text-success small">Public</div>
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
                                                    <div class="border-bottom pb-2 mb-2">
                                                        <div class="fw-semibold text-warning small">Private</div>
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
                                                    <div class="border-bottom pb-2 mb-2">
                                                        <div class="fw-semibold text-info small">Association</div>
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
                                                    <div class="bg-success text-white p-2 rounded">
                                                        @php
                                                            $publicTotal = ($improvement['public_number'] ?? 0) * ($improvement['public_cost'] ?? 0);
                                                            $privateTotal = ($improvement['private_number'] ?? 0) * ($improvement['private_cost'] ?? 0);
                                                            $associationTotal = ($improvement['association_number'] ?? 0) * ($improvement['association_cost'] ?? 0);
                                                            $totalImprovements = ($improvement['public_number'] ?? 0) + ($improvement['private_number'] ?? 0) + ($improvement['association_number'] ?? 0);
                                                            $grandTotal = $publicTotal + $privateTotal + $associationTotal;
                                                        @endphp
                                                        <div class="row small text-white">
                                                            <div class="col-4">
                                                                <strong>Total Improvements:</strong>
                                                                {{ number_format($totalImprovements) }}
                                                            </div>
                                                            <div class="col-8">
                                                                <strong>Grand Total:</strong>
                                                                {{ number_format($grandTotal, 2) }} ETB
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-2">
                                                        <strong>Description:</strong>
                                                        <span class="text-muted">{{ Str::limit($improvement['description'] ?? 'No description', 50) }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-muted">No improvement data</span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button
                                                class="btn btn-sm btn-outline-primary dropdown-toggle action-menu-toggle"
                                                type="button" id="actionMenu{{ $plan->id }}"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-gear"></i>
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 overflow-hidden"
                                                aria-labelledby="actionMenu{{ $plan->id }}">
                                                {{-- Edit Plan --}}
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center py-2 px-3 action-item"
                                                        href="{{ route('sector.building-economic-institution-plans.edit', $plan->id) }}">
                                                        <i class="bi bi-pencil-square me-2 text-warning fs-6"></i>
                                                        <span class="fw-medium">Edit Plan</span>
                                                    </a>
                                                </li>

                                                {{-- View Details --}}
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center py-2 px-3 action-item"
                                                        href="#" data-bs-toggle="modal"
                                                        data-bs-target="#detailsModal{{ $plan->id }}">
                                                        <i class="bi bi-eye me-2 text-info fs-6"></i>
                                                        <span class="fw-medium">View Details</span>
                                                    </a>
                                                </li>

                                                {{-- Delete --}}
                                                <li>
                                                    <form
                                                        action="{{ route('sector.building-economic-institution-plans.destroy', $plan->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this building economic institution plan?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            class="dropdown-item d-flex align-items-center text-danger py-2 px-3 action-item"
                                                            type="submit">
                                                            <i class="bi bi-trash me-2 fs-6"></i>
                                                            <span class="fw-medium">Delete</span>
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>

                                        {{-- Details Modal --}}
                                        <div class="modal fade" id="detailsModal{{ $plan->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title">
                                                            <i class="bi bi-building me-2"></i>
                                                            Building Plan Details - {{ $plan->year }} EC
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal"></button>
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
                                                        <div class="card mb-3">
                                                            <div class="card-header bg-primary text-white">
                                                                <h6 class="mb-0">New Building Data</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                @if (!empty($plan->new_building_data) && count($plan->new_building_data) > 0)
                                                                    @foreach ($plan->new_building_data as $index => $building)
                                                                        <div
                                                                            class="border-bottom pb-3 mb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                                                            <h6 class="text-primary mb-3">
                                                                                #{{ $index + 1 }} -
                                                                                {{ $building['name'] }}
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
                                                                            <div class="row small bg-light p-2 rounded mb-2">
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

                                                                            <div class="mt-2">
                                                                                <strong>Description:</strong>
                                                                                {{ $building['description'] }}
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
                                                                        <div
                                                                            class="border-bottom pb-3 mb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                                                            <h6 class="text-success mb-3">
                                                                                #{{ $index + 1 }} -
                                                                                {{ $improvement['name'] }}
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
                                                                            <div class="row small bg-light p-2 rounded mb-2">
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

                                                                            <div class="mt-2">
                                                                                <strong>Description:</strong>
                                                                                {{ $improvement['description'] }}
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
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <a href="{{ route('sector.building-economic-institution-plans.edit', $plan->id) }}"
                                                            class="btn btn-primary">
                                                            <i class="bi bi-pencil-square me-1"></i>Edit Plan
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @php $counter++; @endphp
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-info-circle me-1"></i>No building economic institution plans
                                    found.
                                    <a href="{{ route('sector.building-economic-institution-plans.create') }}"
                                        class="d-block mt-2">
                                        Create your first building economic institution plan
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
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
            min-width: 180px;
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

        .action-item:active {
            background-color: #e2edff;
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

        .table th {
            font-weight: 600;
            background-color: #f8f9fa;
        }

        .border-primary {
            border-left-color: #0d6efd !important;
        }

        .border-success {
            border-left-color: #198754 !important;
        }

        .text-primary {
            color: #0d6efd !important;
        }

        .text-success {
            color: #198754 !important;
        }

        .text-warning {
            color: #fd7e14 !important;
        }

        .text-info {
            color: #0dcaf0 !important;
        }

        .table tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.03) !important;
        }

        /* Subtle alternating background for better readability */
        .year-group-first {
            background-color: rgba(0, 0, 0, 0.01);
        }

        .year-group-subsequent {
            background-color: rgba(0, 0, 0, 0.005);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.875rem;
            }

            .dropdown-menu {
                min-width: 160px;
            }

            .modal-dialog {
                margin: 0.5rem;
            }
            
            .col-4, .col-3, .col-5, .col-6, .col-8 {
                margin-bottom: 0.25rem;
            }
        }

        /* Badge styling */
        .badge.bg-primary {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }

        /* Modal styling */
        .modal-header {
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        }

        .card-header h6 {
            font-size: 0.9rem;
        }

        /* Enhanced card styling for better visual hierarchy */
        .bg-light.rounded {
            border: 1px solid #e9ecef;
        }

        .border-bottom {
            border-bottom: 1px solid #dee2e6 !important;
        }

        /* Color coding for public/private/association sections */
        .text-success {
            color: #198754 !important;
        }

        .text-warning {
            color: #fd7e14 !important;
        }

        .text-info {
            color: #0dcaf0 !important;
        }

        .bg-primary {
            background-color: #0d6efd !important;
        }

        .bg-success {
            background-color: #198754 !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips if using Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Auto-show modal if there's a modal ID in the URL hash
            const hash = window.location.hash;
            if (hash && hash.startsWith('#detailsModal')) {
                const modal = new bootstrap.Modal(document.querySelector(hash));
                modal.show();
            }
        });
    </script>
</x-layout>