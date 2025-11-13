<x-layout>
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1"><i class="bi bi-tractor text-success me-2"></i>Mechanizations</h2>
                <p class="text-muted mb-0">Manage all mechanization equipment and loans</p>
            </div>
            <a href="{{ route('sector.mechanizations.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i> Add Mechanization
            </a>
        </div>

        <!-- Success Message -->
        @if (session('success'))
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
                                <h6 class="text-muted mb-1">Total Mechanizations</h6>
                                <h4 class="mb-0">{{ $mechanizations->total() }}</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="bi bi-tractor text-primary fs-2"></i>
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
                                <h6 class="text-muted mb-1">With Loans</h6>
                                <h4 class="mb-0">{{ $mechanizations->whereNotNull('loan_name')->count() }}</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="bi bi-cash-coin text-success fs-2"></i>
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
                                <h6 class="text-muted mb-1">Total Loans</h6>
                                <h4 class="mb-0">
                                    @php
                                        $totalLoans = 0;
                                        foreach ($mechanizations as $m) {
                                            if ($m->loan_details) {
                                                $loans = json_decode($m->loan_details, true);
                                                $totalLoans += count($loans);
                                            } elseif ($m->loan_name) {
                                                $totalLoans++;
                                            }
                                        }
                                    @endphp
                                    {{ $totalLoans }}
                                </h4>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="bi bi-bank text-info fs-2"></i>
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
                                <h6 class="text-muted mb-1">Types</h6>
                                <h4 class="mb-0">{{ $mechanizations->unique('name')->count() }}</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="bi bi-tags text-warning fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-success">
                            <tr>
                                <th class="ps-4">
                                    <i class="bi bi-tag me-1"></i>Name
                                </th>
                                <th>
                                    <i class="bi bi-gear me-1"></i>Type
                                </th>
                                <th class="text-end">
                                    <i class="bi bi-123 me-1"></i>Amount
                                </th>
                                <th>
                                    <i class="bi bi-rulers me-1"></i>Unit
                                </th>
                                <th class="text-center">
                                    <i class="bi bi-calendar3 me-1"></i>Year
                                </th>
                                <th>
                                    <i class="bi bi-cash-coin me-1"></i>Loan Information
                                </th>
                                <th class="text-center pe-4">
                                    <i class="bi bi-lightning-charge me-1"></i>Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mechanizations as $m)
                                <tr>
                                    <td class="ps-4 fw-semibold">{{ $m->name }}</td>
                                    <td>
                                        @if ($m->mechanization_type)
                                            <span class="badge bg-primary">{{ $m->mechanization_type }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if ($m->amount)
                                            <span class="fw-semibold">{{ number_format($m->amount, 2) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($m->unit)
                                            <span class="badge bg-light text-dark">{{ $m->unit }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark">{{ $m->year }} EC</span>
                                    </td>
                                    <td>
                                        @php
                                            $loans = [];
                                            // Check if we have loan_details JSON
                                            if ($m->loan_details) {
                                                $loans = json_decode($m->loan_details, true);
                                            }
                                            // If no loan_details but we have individual loan columns, use those
                                            elseif ($m->loan_name || $m->loan_type || $m->loan_amount) {
                                                $loans = [
                                                    [
                                                        'loan_name' => $m->loan_name,
                                                        'loan_type' => $m->loan_type,
                                                        'loan_amount' => $m->loan_amount,
                                                        'loan_duration' => $m->loan_duration,
                                                        'loan_interest' => $m->loan_interest,
                                                    ],
                                                ];
                                            }
                                        @endphp

                                        @if (!empty($loans))
                                            <div class="loan-info">
                                                @foreach ($loans as $loan)
                                                    @if (!empty($loan['loan_type']) || !empty($loan['loan_name']))
                                                        <div class="loan-item mb-1">
                                                            <div class="d-flex align-items-center">
                                                                <small class="fw-semibold text-dark">
                                                                    {{ $loan['loan_name'] ?: $loan['loan_type'] }}
                                                                </small>
                                                                @if ($loan['loan_amount'])
                                                                    <span class="badge bg-success ms-2">
                                                                        ETB
                                                                        {{ number_format($loan['loan_amount'], 2) }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            @if ($loan['loan_duration'] || $loan['loan_interest'])
                                                                <div class="d-flex gap-2 mt-1">
                                                                    @if ($loan['loan_duration'])
                                                                        <small class="text-muted">
                                                                            <i
                                                                                class="bi bi-clock me-1"></i>{{ $loan['loan_duration'] }}mo
                                                                        </small>
                                                                    @endif
                                                                    @if ($loan['loan_interest'])
                                                                        <small class="text-muted">
                                                                            <i
                                                                                class="bi bi-percent me-1"></i>{{ $loan['loan_interest'] }}%
                                                                        </small>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">No loan</span>
                                        @endif
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('sector.mechanizations.edit', $m->id) }}"
                                                class="btn btn-outline-dark" title="Edit">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form action="{{ route('sector.mechanizations.destroy', $m->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this mechanization record? This action cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="bi bi-inbox display-4 text-muted"></i>
                                            <h5 class="text-muted mt-3">No mechanizations found</h5>
                                            <p class="text-muted">Get started by adding your first mechanization
                                                record.</p>
                                            <a href="{{ route('sector.mechanizations.create') }}"
                                                class="btn btn-success mt-2">
                                                <i class="bi bi-plus-circle me-1"></i> Add Mechanization
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

        <!-- Pagination -->
        @if ($mechanizations->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $mechanizations->firstItem() ?? 0 }} to {{ $mechanizations->lastItem() ?? 0 }}
                    of {{ $mechanizations->total() }} entries
                </div>
                <nav>
                    {{ $mechanizations->links() }}
                </nav>
            </div>
        @endif
    </div>

    <style>
        .table> :not(caption)>*>* {
            padding: 0.75rem 0.5rem;
        }

        .btn-group .btn {
            border-radius: 0.375rem !important;
            margin: 0 2px;
        }

        .loan-info .loan-item {
            border-left: 3px solid #198754;
            padding-left: 8px;
        }

        .loan-info .loan-item:not(:last-child) {
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e9ecef;
        }
    </style>
</x-layout>
