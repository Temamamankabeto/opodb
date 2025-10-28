<x-layout>
    <div class="container-fluid py-4">
        <h2 class="mb-4"><i class="fas fa-check-circle"></i> Kebele Crop Plans Approval</h2>

        <div id="alert-area"></div>

        {{-- Expand/Collapse All Buttons --}}
        <div class="mb-3 d-flex gap-2">
            <button class="btn btn-outline-primary" id="expandAll">Expand All</button>
            <button class="btn btn-outline-secondary" id="collapseAll">Collapse All</button>
        </div>

        {{-- 🌾 Pending Crop Plans --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Pending Crop Sector Plans</h5>
            </div>
            <div class="card-body">
                @if($pendingCropPlans->isEmpty())
                <p class="text-muted">No pending crop plans.</p>
                @else
                @php
                $pendingGrouped = $pendingCropPlans->groupBy('year')->map(function($yearGroup) {
                return $yearGroup->groupBy(function($plan) {
                return $plan->cropType->name ?? '-';
                });
                });
                @endphp

                @foreach($pendingGrouped as $year => $cropTypes)
                <div class="mb-3">
                    <button class="btn btn-outline-primary w-100 text-start mb-2 year-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#pending-year-{{ $year }}">
                        <i class="fas fa-calendar-alt me-2"></i> Year: {{ $year }}
                    </button>
                    <div class="collapse" id="pending-year-{{ $year }}">
                        @foreach($cropTypes as $cropTypeName => $plans)
                        @php
                        $totalArea = $plans->sum('area_to_plant');
                        $totalYield = $plans->sum('expected_yield');
                        @endphp
                        <button class="btn btn-outline-secondary w-100 text-start mb-1 type-toggle d-flex justify-content-between align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#pending-year-{{ $year }}-type-{{ Str::slug($cropTypeName) }}">
                            <span><i class="fas fa-seedling me-2"></i> Crop Type: {{ $cropTypeName }}</span>
                            <span>
                                <span class="badge bg-info me-1">Area: {{ $totalArea }} ha</span>
                                <span class="badge bg-warning text-dark">Expected Yield: {{ $totalYield }} qt</span>
                            </span>
                        </button>
                        <div class="collapse ms-3 mb-3" id="pending-year-{{ $year }}-type-{{ Str::slug($cropTypeName) }}">
                            <table class="table table-bordered align-middle mb-3">
                                <thead class="table-success">
                                    <tr>
                                        <th>#</th>
                                        <th>Crop</th>
                                        <th>Area (ha)</th>
                                        <th>Expected Yield (qt)</th>
                                        <th>Season</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($plans as $plan)
                                    <tr id="row-crop-{{ $plan->id }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $plan->crop->name ?? '-' }}</td>
                                        <td>{{ $plan->area_to_plant }}</td>
                                        <td>{{ $plan->expected_yield }}</td>
                                        <td>{{ $plan->season }}</td>
                                        <td>
                                            <button class="btn btn-success btn-sm openModal"
                                                data-sector="crop" data-id="{{ $plan->id }}" data-status="approved">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                            <button class="btn btn-danger btn-sm openModal"
                                                data-sector="crop" data-id="{{ $plan->id }}" data-status="rejected">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    {{-- Summary Row --}}
                                    <tr class="table-secondary fw-bold">
                                        <td colspan="2" class="text-end">Total</td>
                                        <td>{{ $totalArea }}</td>
                                        <td>{{ $totalYield }}</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>

        {{-- 📜 Approval History --}}
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Approval History</h5>
            </div>
            <div class="card-body">
                @if($historyCropPlans->isEmpty())
                <p class="text-muted">No history yet.</p>
                @else
                @php
                $historyGrouped = $historyCropPlans->groupBy('year')->map(function($yearGroup) {
                return $yearGroup->groupBy(function($plan) {
                return $plan->cropType->name ?? '-';
                });
                });
                @endphp

                @foreach($historyGrouped as $year => $cropTypes)
                <button class="btn btn-outline-primary w-100 text-start mb-2 history-year-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#history-year-{{ $year }}">
                    <i class="fas fa-calendar-alt me-2"></i> Year: {{ $year }}
                </button>
                <div class="collapse" id="history-year-{{ $year }}">
                    @foreach($cropTypes as $cropTypeName => $plans)
                    @php
                    $totalArea = $plans->sum('area_to_plant');
                    $totalYield = $plans->sum('expected_yield');
                    @endphp
                    <button class="btn btn-outline-secondary w-100 text-start mb-1 type-toggle d-flex justify-content-between align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#history-year-{{ $year }}-type-{{ Str::slug($cropTypeName) }}">
                        <span><i class="fas fa-seedling me-2"></i> Crop Type: {{ $cropTypeName }}</span>
                        <span>
                            <span class="badge bg-info me-1">Area: {{ $totalArea }} ha</span>
                            <span class="badge bg-warning text-dark">Expected Yield: {{ $totalYield }} qt</span>
                        </span>
                    </button>
                    <div class="collapse ms-3 mb-3" id="history-year-{{ $year }}-type-{{ Str::slug($cropTypeName) }}">
                        <table class="table table-striped table-bordered mb-3">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Crop</th>
                                    <th>Area (ha)</th>
                                    <th>Expected Yield (qt)</th>
                                    <th>Status</th>
                                    <th>Approved At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($plans as $plan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $plan->crop->name ?? '-' }}</td>
                                    <td>{{ $plan->area_to_plant }}</td>
                                    <td>{{ $plan->expected_yield }}</td>
                                    <td>
                                        <span class="badge bg-{{ $plan->status == 'approved' ? 'success' : 'danger' }}">
                                            {{ ucfirst($plan->status) }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($plan->approved_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                </tr>
                                @endforeach
                                <tr class="table-secondary fw-bold">
                                    <td colspan="2" class="text-end">Total</td>
                                    <td>{{ $totalArea }}</td>
                                    <td>{{ $totalYield }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endforeach
                </div>
                @endforeach
                @endif
            </div>
        </div>

        {{-- 🔹 Approval Modal --}}
        <div class="modal fade" id="approvalModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Confirm Action</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p id="modalText">Are you sure you want to proceed?</p>
                        <div class="mb-3">
                            <label for="modalNotes" class="form-label">Admin Notes (Optional)</label>
                            <textarea id="modalNotes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="confirmAction">Yes, Proceed</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- jQuery + AJAX --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let selectedSector, selectedId, selectedStatus;

        // Open Approval Modal
        $(document).on('click', '.openModal', function() {
            selectedSector = $(this).data('sector');
            selectedId = $(this).data('id');
            selectedStatus = $(this).data('status');
            let actionText = selectedStatus === 'approved' ? 'approve' : 'reject';
            $('#modalText').text(`Are you sure you want to ${actionText} this ${selectedSector} plan?`);
            $('#modalNotes').val('');
            $('#approvalModal').modal('show');
        });

        // Confirm Action AJAX
        $('#confirmAction').click(function() {
            $.ajax({
                url: "{{ route('kebele.update.status') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    sector: selectedSector,
                    id: selectedId,
                    status: selectedStatus,
                    notes: $('#modalNotes').val()
                },
                success: function(res) {
                    $('#approvalModal').modal('hide');
                    $('#row-' + selectedSector + '-' + selectedId).fadeOut(600, function() {
                        $(this).remove();
                    });
                    $('#alert-area').html(`<div class="alert alert-success mt-2">${res.message}</div>`);
                },
                error: function(xhr) {
                    $('#approvalModal').modal('hide');
                    $('#alert-area').html(`<div class="alert alert-danger mt-2">Error: ${xhr.responseJSON?.message ?? 'Failed to update status.'}</div>`);
                }
            });
        });

        // Expand/Collapse All
        $('#expandAll').click(function() {
            $('.collapse').collapse('show');
        });
        $('#collapseAll').click(function() {
            $('.collapse').collapse('hide');
        });
    </script>
</x-layout>