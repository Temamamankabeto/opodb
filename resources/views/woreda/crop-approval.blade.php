<x-layout>
    <div class="container-fluid py-4">
        <h2 class="mb-4"><i class="fas fa-check-circle"></i> Woreda Crop Sector Plans Approval</h2>

        <div id="alert-area"></div>

        {{-- Pending Plans --}}
        @php
        $pendingGrouped = $pendingCropPlans->groupBy('kebele.name')->map(function($kebeleGroup) {
        return $kebeleGroup->groupBy('year')->map(function($yearGroup) {
        return $yearGroup->groupBy(fn($plan) => $plan->cropType->name ?? '-');
        });
        });
        @endphp

        @foreach($pendingGrouped as $kebeleName => $years)
        <h5 class="mt-4">Kebele: {{ $kebeleName }}</h5>
        @foreach($years as $year => $cropTypes)
        <h6 class="ms-3">Year: {{ $year }}</h6>
        @foreach($cropTypes as $cropTypeName => $plans)
        <h6 class="ms-4">Crop Type: {{ $cropTypeName }}</h6>
        <table class="table table-bordered align-middle ms-5">
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
            </tbody>
        </table>
        @endforeach
        @endforeach
        @endforeach

        {{-- Approval Modal --}}
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

        {{-- jQuery + AJAX --}}
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            let selectedSector, selectedId, selectedStatus;

            $(document).on('click', '.openModal', function() {
                selectedSector = $(this).data('sector');
                selectedId = $(this).data('id');
                selectedStatus = $(this).data('status');
                let actionText = selectedStatus === 'approved' ? 'approve' : 'reject';
                $('#modalText').text(`Are you sure you want to ${actionText} this ${selectedSector} plan?`);
                $('#modalNotes').val('');
                $('#approvalModal').modal('show');
            });

            $('#confirmAction').click(function() {
                $.ajax({
                    url: "{{ route('woreda.update.status') }}", // Make separate route for woreda approval
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
        </script>
</x-layout>