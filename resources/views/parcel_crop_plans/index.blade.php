<x-layout>
<div class="container-fluid py-4 bg-light min-vh-100">
    <div class="row justify-content-center">
        <div class="col-12">
            
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1 text-success fw-bold">Crop Plans for {{ $farmer->farmer_name }}</h4>
                    <p class="text-muted mb-0">Manage crop planting plans and fertilizer recommendations</p>
                </div>
                <div>
                    <a href="{{ route('farmers.show', $farmer->id) }}" class="btn btn-outline-secondary rounded-pill me-2">
                        <i class="bi bi-arrow-left me-1"></i> Back to Farmer
                    </a>
                    <a href="{{ route('parcel-crop-plans.create', $farmer->id) }}" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-plus-circle me-2"></i>Create New Plan
                    </a>
                </div>
            </div>

            <!-- Success Alert -->
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div class="fw-medium">{{ session('success') }}</div>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="bi bi-clipboard-data text-success fs-4"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0 fw-bold text-dark">{{ $totalPlans }}</h4>
                                    <small class="text-muted">Total Plans</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="bi bi-rulers text-primary fs-4"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0 fw-bold text-dark">{{ number_format($totalArea, 2) }} ha</h4>
                                    <small class="text-muted">Total Area</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="bi bi-graph-up text-warning fs-4"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0 fw-bold text-dark">{{ number_format($totalExpectedYield, 2) }} qt</h4>
                                    <small class="text-muted">Expected Yield</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="bi bi-seedling text-info fs-4"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0 fw-bold text-dark">{{ $uniqueCrops }}</h4>
                                    <small class="text-muted">Different Crops</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Plans Table -->
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark">
                            <i class="bi bi-table text-success me-2"></i>
                            All Crop Plans
                            <span class="badge bg-success ms-2">{{ $plans->total() }}</span>
                        </h5>
                        <div class="d-flex align-items-center">
                            <small class="text-muted me-3">
                                Showing {{ $plans->firstItem() ?? 0 }} to {{ $plans->lastItem() ?? 0 }} of {{ $plans->total() }} entries
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 ps-4">#</th>
                                    <th class="border-0">Year</th>
                                    <th class="border-0">Crop Type</th>
                                    <th class="border-0">Crop</th>
                                    <th class="border-0">Parcel</th>
                                    <th class="border-0 text-center">Area (ha)</th>
                                    <th class="border-0 text-center">Productivity (qt/ha)</th>
                                    <th class="border-0 text-center">Seed Rate (kg/ha)</th>
                                    <th class="border-0 text-center">Expected Yield (qt)</th>
                                    <th class="border-0">Season</th>
                                    <th class="border-0 text-center">Fertilizers</th>
                                    <th class="border-0 text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($plans as $index => $plan)
                                    @php
                                        $fertilizerCount = is_array($plan->fertilizer_recommendations) ? count($plan->fertilizer_recommendations) : 0;
                                    @endphp
                                    <tr class="border-bottom">
                                        <td class="ps-4 fw-semibold text-muted">{{ $plans->firstItem() + $index }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $plan->year }} EC</span>
                                        </td>
                                        <td>
                                            <div class="fw-medium text-dark">{{ $plan->cropType->name ?? 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-success bg-opacity-10 rounded-circle p-2 me-2">
                                                    <i class="bi bi-seedling text-success"></i>
                                                </div>
                                                <div class="fw-semibold text-dark">{{ $plan->crop->name ?? 'N/A' }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $plan->parcel_id }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="fw-medium text-dark">{{ number_format($plan->area, 2) }}</div>
                                        </td>
                                        <td class="text-center">
                                            <div class="fw-medium text-dark">{{ number_format($plan->productivity_per_ha, 2) }}</div>
                                        </td>
                                        <td class="text-center">
                                            <div class="fw-medium text-dark">{{ number_format($plan->seed_rate, 2) }}</div>
                                        </td>
                                        <td class="text-center">
                                            <div class="fw-bold text-success">{{ number_format($plan->expected_yield, 2) }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-dark">{{ $plan->season }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($fertilizerCount > 0)
                                                <span class="badge bg-info">{{ $fertilizerCount }} fertilizers</span>
                                            @else
                                                <span class="text-muted">None</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('parcel-crop-plans.edit', $plan->id) }}" 
                                                   class="btn btn-sm btn-outline-warning rounded-pill me-2"
                                                   data-bs-toggle="tooltip" title="Edit Plan">
                                                    <i class="bi bi-pencil"></i>
                                                    <span class="d-none d-md-inline">Edit</span>
                                                </a>
                                                <form action="{{ route('parcel-crop-plans.destroy', $plan->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger rounded-pill"
                                                            onclick="return confirm('Are you sure you want to delete this crop plan?')"
                                                            data-bs-toggle="tooltip" title="Delete Plan">
                                                        <i class="bi bi-trash"></i>
                                                        <span class="d-none d-md-inline">Delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center py-5">
                                            <div class="py-4">
                                                <i class="bi bi-inbox display-1 text-muted"></i>
                                                <h5 class="text-muted mt-3">No Crop Plans Found</h5>
                                                <p class="text-muted mb-4">Get started by creating your first crop plan</p>
                                                <a href="{{ route('parcel-crop-plans.create', $farmer->id) }}" class="btn btn-success rounded-pill px-4">
                                                    <i class="bi bi-plus-circle me-2"></i>Create Plan
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Table Footer with Pagination -->
                @if($plans->hasPages())
                    <div class="card-footer bg-white py-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing {{ $plans->firstItem() }} to {{ $plans->lastItem() }} of {{ $plans->total() }} entries
                            </div>
                            <div>
                                {{ $plans->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<style>
.card {
    border-radius: 12px;
}

.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6b7280;
}

.table td {
    vertical-align: middle;
    border-color: #f8f9fa;
}

.table-hover tbody tr:hover {
    background-color: rgba(25, 135, 84, 0.02);
}

.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}
</style>

<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
</x-layout>