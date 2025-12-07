<x-layout>
    <div class="container-fluid py-4">
        <h2 class="mb-4 text-primary fw-bold">
            <i class="bi bi-clipboard-data me-2"></i>Natural Resource Plans
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
                    <h5 class="fw-bold text-primary mb-1">Total Natural Resource Plans</h5>
                    <p class="mb-0 text-muted">{{ $plans->count() }} Records across
                        {{ $plans->groupBy('year')->count() }} years</p>
                </div>
                <div>
                    <a href="{{ route('sector.natural-plans.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Create New Plan
                    </a>
                </div>
            </div>
        </div>

        {{-- Main Table --}}
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-light fw-bold">
                <i class="bi bi-list-check me-2"></i>Natural Resource Plans Summary (Grouped by Year)
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>#</th>
                            <th>Year (EC)</th>
                            <th>Natural Resource</th>                             
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

                                    {{-- NAtural Resource Supplies --}}
                                    <td class="small">
                                        @if (!empty($plan->natural_data) && count($plan->natural_data) > 0)
                                            @foreach ($plan->natural_data as $natural)
                                                <div
                                                    class="mb-2 p-2 border-start border-warning border-3 ps-2 bg-light rounded">
                                                    <div class="fw-semibold text-warning">
                                                        {{ $natural['name'] ?? 'N/A' }}</div>
                                                    <div><strong>Amount:</strong>
                                                        {{ number_format($natural['amount'] ?? 0, 2) }}
                                                        {{ $natural['unit'] ?? '' }}</div>
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-muted">No supplies data</span>
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
                                                        href="{{ route('sector.natural-plans.edit', $plan->id) }}">
                                                        <i class="bi bi-pencil-square me-2 text-warning fs-6"></i>
                                                        <span class="fw-medium">Edit Natural</span>
                                                    </a>
                                                </li>
                                                  

                                                

                                                {{-- Delete --}}
                                                <li>
                                                    <form
                                                        action="{{ route('sector.natural-plans.destroy', $plan->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this plan?');">
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
                                    </td>
                                </tr>
                            @endforeach
                            @php $counter++; @endphp
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-info-circle me-1"></i>No livestock plans found.
                                    <a href="{{ route('sector.livestock_plans.create') }}" class="d-block mt-2">
                                        Create your first livestock plan
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

        .border-warning {
            border-left-color: #ffc107 !important;
        }

        .border-success {
            border-left-color: #198754 !important;
        }

        .text-warning {
            color: #ffc107 !important;
        }

        .text-success {
            color: #198754 !important;
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
    </style>
</x-layout>
