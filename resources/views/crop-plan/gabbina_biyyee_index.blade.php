<x-layout>
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1"><i class="bi bi-recycle text-success me-2"></i>Hojii Gabbina Biyyoo</h2>
                <p class="text-muted mb-0">Manage all compost production plans</p>
            </div>
            <a href="{{ route('sector.gabbina_biyyee.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i> Add New Plan
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-1">Total Plans</h6>
                                <h4 class="mb-0">
                                    @php
                                        $totalPlans = 0;
                                        foreach($gabbinaBiyyee as $yearPlans) {
                                            $totalPlans += count($yearPlans);
                                        }
                                    @endphp
                                    {{ $totalPlans }}
                                </h4>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="bi bi-list-check text-primary fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-1">Years</h6>
                                <h4 class="mb-0">{{ count($gabbinaBiyyee) }}</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="bi bi-calendar3 text-success fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-1">Total Quantity</h6>
                                <h4 class="mb-0">
                                    @php
                                        $totalQuantity = 0;
                                        foreach($gabbinaBiyyee as $yearPlans) {
                                            foreach($yearPlans as $plan) {
                                                $totalQuantity += $plan->total;
                                            }
                                        }
                                    @endphp
                                    {{ number_format($totalQuantity, 2) }} Qt
                                </h4>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="bi bi-bar-chart text-warning fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-1">Compost Types</h6>
                                <h4 class="mb-0">
                                    @php
                                        $types = [];
                                        foreach($gabbinaBiyyee as $yearPlans) {
                                            foreach($yearPlans as $plan) {
                                                $types[$plan->compost_type] = true;
                                            }
                                        }
                                    @endphp
                                    {{ count($types) }}
                                </h4>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="bi bi-tags text-info fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        @if($gabbinaBiyyee->count() > 0)
            @foreach($gabbinaBiyyee as $year => $plans)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-calendar-check me-2"></i>
                            Year: {{ $year }} EC
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle mb-0">
                                <thead class="table-success">
                                    <tr>
                                        <th class="ps-4">Compost Type</th>
                                        <th class="text-center">Common (Qt)</th>
                                        <th class="text-center">Individual (Qt)</th>
                                        <th class="text-center">Total (Qt)</th>
                                        <th class="text-center">Land Area (ha)</th>
                                        <th class="text-center pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($plans as $plan)
                                        <tr>
                                            <td class="ps-4 fw-semibold">{{ $plan->compost_type }}</td>
                                            <td class="text-center">{{ number_format($plan->common, 2) }}</td>
                                            <td class="text-center">{{ number_format($plan->individual, 2) }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-primary">{{ number_format($plan->total, 2) }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if($plan->land_area)
                                                    <span class="badge bg-warning text-dark">{{ number_format($plan->land_area, 2) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center pe-4">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('sector.gabbina_biyyee.edit', $plan->id) }}" 
                                                       class="btn btn-outline-primary" title="Edit">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>
                                                    <form action="{{ route('sector.gabbina_biyyee.destroy', $plan->id) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this compost plan?')">
                                                        @csrf 
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                            <i class="bi bi-trash"></i> Delete
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
            @endforeach
        @else
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox display-4 text-muted"></i>
                    <h5 class="text-muted mt-3">No compost plans found</h5>
                    <p class="text-muted">Get started by adding your first compost production plan.</p>
                    <a href="{{ route('sector.gabbina_biyyee.create') }}" class="btn btn-success mt-2">
                        <i class="bi bi-plus-circle me-1"></i> Add Compost Plan
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-layout>