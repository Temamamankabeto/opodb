<x-layout>
    <div class="container-fluid py-4">
        <h2 class="mb-4 text-primary fw-bold">
            <i class="bi bi-briefcase me-2"></i>Employment Opportunity Plans
        </h2>

        {{-- Flash message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-1"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Summary Card --}}
        <div class="card mb-4 shadow-sm border-0 rounded-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold text-primary mb-1">Total Employment Opportunity Plans</h5>
                    <p class="mb-0 text-muted">{{ $plans->count() }} Records across
                        {{ $plans->groupBy('year')->count() }} years</p>
                </div>
                <div>
                    <a href="{{ route('sector.employment-opportunity-plans.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Create New Employment Plan
                    </a>
                </div>
            </div>
        </div>

        {{-- Main Table --}}
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-light fw-bold">
                <i class="bi bi-list-check me-2"></i>Employment Opportunity Plans Summary (Grouped by Year)
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>#</th>
                            <th>Year (EC)</th>
                            <th>Job Opportunity</th>
                            <th>Enterprise Work</th>
                            <th>Employment Data</th>
                            <th>Status</th>
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

                                    {{-- Job Opportunity Name --}}
                                    <td class="fw-semibold">
                                        {{ $plan->jobOpportunity->name ?? 'N/A' }}
                                    </td>

                                    {{-- Enterprise Work Name --}}
                                    <td class="fw-semibold">
                                        {{ $plan->enterpriseWork->name ?? 'N/A' }}
                                    </td>

                                    {{-- Employment Data --}}
                                    <td class="small">
                                        @php
                                            $data = $plan->opportunity_data;
                                        @endphp
                                        <div class="mb-2 p-2 border-start border-info border-3 ps-2 bg-light rounded">
                                            <div class="fw-semibold text-info mb-2">
                                                Employment Details
                                            </div>
                                            <div class="row small text-center">
                                                <div class="col-3 border-end">
                                                    <strong class="d-block text-primary">Enterprises</strong>
                                                    <span class="fw-bold">{{ number_format($data['number_of_enterprises'] ?? 0) }}</span>
                                                </div>
                                                <div class="col-3 border-end">
                                                    <strong class="d-block text-success">Male</strong>
                                                    <span class="fw-bold">{{ number_format($data['male_members'] ?? 0) }}</span>
                                                </div>
                                                <div class="col-3 border-end">
                                                    <strong class="d-block text-warning">Female</strong>
                                                    <span class="fw-bold">{{ number_format($data['female_members'] ?? 0) }}</span>
                                                </div>
                                                <div class="col-3">
                                                    <strong class="d-block text-info">Total</strong>
                                                    <span class="fw-bold">{{ number_format($data['total_members'] ?? 0) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Status --}}
                                    <td class="text-center">
                                        @if($plan->status === 'approved')
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Approved
                                            </span>
                                            @if($plan->approved_at)
                                                <small class="d-block text-muted mt-1">
                                                    {{ $plan->approved_at->format('M d, Y') }}
                                                </small>
                                            @endif
                                        @elseif($plan->status === 'rejected')
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>Rejected
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-clock me-1"></i>Pending
                                            </span>
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
                                                        href="{{ route('sector.employment-opportunity-plans.edit', $plan->id) }}">
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
                                                @if($plan->status !== 'approved')
                                                <li>
                                                    <form
                                                        action="{{ route('sector.employment-opportunity-plans.destroy', $plan->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this employment opportunity plan?');">
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
                                                @endif
                                            </ul>
                                        </div>

                                        {{-- Details Modal --}}
                                        <div class="modal fade" id="detailsModal{{ $plan->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title">
                                                            <i class="bi bi-briefcase me-2"></i>
                                                            Employment Plan Details - {{ $plan->year }} EC
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <strong>Job Opportunity:</strong> {{ $plan->jobOpportunity->name ?? 'N/A' }}
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>Enterprise Work:</strong> {{ $plan->enterpriseWork->name ?? 'N/A' }}
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <strong>Year:</strong> {{ $plan->year }} EC
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>Created By:</strong> {{ $plan->createdBy->name ?? 'N/A' }}
                                                            </div>
                                                        </div>

                                                        {{-- Status Information --}}
                                                        <div class="row mb-3">
                                                            <div class="col-12">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <div class="row align-items-center">
                                                                            <div class="col-md-6">
                                                                                <strong>Current Status:</strong>
                                                                                @if($plan->status === 'approved')
                                                                                    <span class="badge bg-success ms-2">
                                                                                        <i class="bi bi-check-circle me-1"></i>Approved
                                                                                    </span>
                                                                                @elseif($plan->status === 'rejected')
                                                                                    <span class="badge bg-danger ms-2">
                                                                                        <i class="bi bi-x-circle me-1"></i>Rejected
                                                                                    </span>
                                                                                @else
                                                                                    <span class="badge bg-warning text-dark ms-2">
                                                                                        <i class="bi bi-clock me-1"></i>Pending Approval
                                                                                    </span>
                                                                                @endif
                                                                            </div>
                                                                            @if($plan->status === 'approved' && $plan->approved_by)
                                                                                <div class="col-md-6">
                                                                                    <strong>Approved By:</strong> {{ $plan->approvedBy->name ?? 'N/A' }}
                                                                                    <br>
                                                                                    <small class="text-muted">
                                                                                        on {{ $plan->approved_at->format('M d, Y H:i') }}
                                                                                    </small>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                        @if($plan->approval_description)
                                                                            <div class="mt-2">
                                                                                <strong>Approval Notes:</strong>
                                                                                <p class="mb-0 text-muted">{{ $plan->approval_description }}</p>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Employment Data --}}
                                                        <div class="card">
                                                            <div class="card-header bg-info text-white">
                                                                <h6 class="mb-0">Employment Data</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                @php
                                                                    $data = $plan->opportunity_data;
                                                                @endphp
                                                                <div class="row text-center">
                                                                    <div class="col-md-3">
                                                                        <div class="border rounded p-3 bg-light">
                                                                            <h6 class="text-primary mb-1">Enterprises</h6>
                                                                            <h4 class="text-primary mb-0">{{ number_format($data['number_of_enterprises'] ?? 0) }}</h4>
                                                                            <small class="text-muted">Total</small>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="border rounded p-3 bg-light">
                                                                            <h6 class="text-success mb-1">Male Members</h6>
                                                                            <h4 class="text-success mb-0">{{ number_format($data['male_members'] ?? 0) }}</h4>
                                                                            <small class="text-muted">Employed</small>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="border rounded p-3 bg-light">
                                                                            <h6 class="text-warning mb-1">Female Members</h6>
                                                                            <h4 class="text-warning mb-0">{{ number_format($data['female_members'] ?? 0) }}</h4>
                                                                            <small class="text-muted">Employed</small>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="border rounded p-3 bg-light">
                                                                            <h6 class="text-info mb-1">Total Members</h6>
                                                                            <h4 class="text-info mb-0">{{ number_format($data['total_members'] ?? 0) }}</h4>
                                                                            <small class="text-muted">Employed</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                {{-- Additional Information --}}
                                                                <div class="row mt-4">
                                                                    <div class="col-12">
                                                                        <div class="bg-light p-3 rounded">
                                                                            <strong>Job Opportunity Description:</strong>
                                                                            <p class="mb-0 text-muted">{{ $plan->jobOpportunity->description ?? 'No description available' }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-2">
                                                                    <div class="col-12">
                                                                        <div class="bg-light p-3 rounded">
                                                                            <strong>Enterprise Work Description:</strong>
                                                                            <p class="mb-0 text-muted">{{ $plan->enterpriseWork->description ?? 'No description available' }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        
                                                        {{-- Edit button for sector users --}}
                                                        @if(auth()->user()->role_id === 5)
                                                            <a href="{{ route('sector.employment-opportunity-plans.edit', $plan->id) }}" class="btn btn-primary">
                                                                <i class="bi bi-pencil-square me-1"></i>Edit Plan
                                                            </a>
                                                        @endif

                                                        {{-- Approve button for kebele admin only --}}
                                                        @if(auth()->user()->role_id === 4 && $plan->status !== 'approved')
                                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $plan->id }}">
                                                                <i class="bi bi-check-circle me-1"></i>Approve Plan
                                                            </button>
                                                        @endif

                                                        {{-- Show approved message if already approved --}}
                                                        @if($plan->status === 'approved')
                                                            <span class="text-success fw-bold">
                                                                <i class="bi bi-check-circle me-1"></i>Approved
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Approve Modal --}}
                                        @if(auth()->user()->role_id === 4 && $plan->status !== 'approved')
                                        <div class="modal fade" id="approveModal{{ $plan->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-success text-white">
                                                        <h5 class="modal-title">
                                                            <i class="bi bi-check-circle me-2"></i>
                                                            Approve Employment Plan
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('employment-opportunity-plans.approve', $plan->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="alert alert-info">
                                                                <i class="bi bi-info-circle me-2"></i>
                                                                You are about to approve this employment opportunity plan. Please provide any additional notes or comments below.
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label for="approvalDescription{{ $plan->id }}" class="form-label fw-semibold">Approval Description</label>
                                                                <textarea class="form-control" id="approvalDescription{{ $plan->id }}" 
                                                                          name="approval_description" rows="4" 
                                                                          placeholder="Enter any comments or notes regarding this approval..."></textarea>
                                                                <div class="form-text">This description will be recorded with the approval.</div>
                                                            </div>

                                                            {{-- Plan Summary --}}
                                                            <div class="card border-0 bg-light">
                                                                <div class="card-body">
                                                                    <h6 class="card-title">Plan Summary</h6>
                                                                    <div class="row small">
                                                                        <div class="col-6">
                                                                            <strong>Job Opportunity:</strong><br>
                                                                            {{ $plan->jobOpportunity->name }}
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <strong>Enterprise Work:</strong><br>
                                                                            {{ $plan->enterpriseWork->name }}
                                                                        </div>
                                                                        <div class="col-12 mt-2">
                                                                            <strong>Total Employment:</strong> 
                                                                            {{ number_format($plan->opportunity_data['total_members'] ?? 0) }} members
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-success">
                                                                <i class="bi bi-check-circle me-1"></i>Approve Plan
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @php $counter++; @endphp
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-info-circle me-1"></i>No employment opportunity plans found.
                                    <a href="{{ route('sector.employment-opportunity-plans.create') }}" class="d-block mt-2">
                                        Create your first employment opportunity plan
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

        .border-info {
            border-left-color: #0dcaf0 !important;
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
            
            .col-3 {
                margin-bottom: 0.5rem;
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips if using Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Auto-show modal if there's a modal ID in the URL hash
            const hash = window.location.hash;
            if (hash && hash.startsWith('#detailsModal')) {
                const modal = new bootstrap.Modal(document.querySelector(hash));
                modal.show();
            }

            // Auto-show approve modal if there's an approve modal ID in the URL hash
            if (hash && hash.startsWith('#approveModal')) {
                const modal = new bootstrap.Modal(document.querySelector(hash));
                modal.show();
            }
        });
    </script>
</x-layout>