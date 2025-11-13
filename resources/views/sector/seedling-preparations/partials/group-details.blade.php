<div class="group-details">
    <div class="row mb-4">
        <div class="col-md-6">
            <h6 class="fw-semibold text-primary">Group Summary</h6>
            <div class="small">
                <div><strong>Year:</strong> {{ $year }} EC</div>
                <div><strong>Seedling Type:</strong> {{ $seedlingTypeName }}</div>
                <div><strong>Total Records:</strong> {{ $preparations->count() }}</div>
                <div><strong>Completed:</strong> {{ $preparations->where('status', 'completed')->count() }}</div>
                <div><strong>Prepared Only:</strong> {{ $preparations->where('status', 'prepared')->count() }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <h6 class="fw-semibold text-primary">Seedlings Summary</h6>
            <div class="small">
                @php
                    $totalSeedlings = $preparations->sum(function ($prep) {
                        return count($prep->preparation_data['selected_seedlings'] ?? []);
                    });
                    $uniqueSeedlings = collect();
                    foreach ($preparations as $prep) {
                        $seedlings = $prep->preparation_data['selected_seedlings'] ?? [];
                        foreach ($seedlings as $seedling) {
                            $uniqueSeedlings->put($seedling['id'], $seedling['name']);
                        }
                    }
                @endphp
                <div><strong>Total Seedling Entries:</strong> {{ $totalSeedlings }}</div>
                <div><strong>Unique Seedlings:</strong> {{ $uniqueSeedlings->count() }}</div>
            </div>
        </div>
    </div>

    <h6 class="fw-semibold text-primary mb-3">Individual Records</h6>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Created</th>
                    <th>Status</th>
                    <th>Seedlings Count</th>
                    <th>Nursery Info</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($preparations as $preparation)
                    <tr>
                        <td>{{ $preparation->created_at->format('M j, Y g:i A') }}</td>
                        <td>
                            @if ($preparation->status === 'completed')
                                <span class="badge bg-success">Completed</span>
                            @else
                                <span class="badge bg-warning">Prepared Only</span>
                            @endif
                        </td>
                        <td>{{ count($preparation->preparation_data['selected_seedlings'] ?? []) }}</td>
                        <td>
                            @if ($preparation->baayyina_nersery || $preparation->ballina_nersery)
                                <div class="small">
                                    @if ($preparation->baayyina_nersery)
                                        <div>Seedlings: {{ number_format($preparation->baayyina_nersery) }}</div>
                                    @endif
                                    @if ($preparation->ballina_nersery)
                                        <div>Area: {{ number_format($preparation->ballina_nersery, 2) }} ha</div>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted">No data</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('sector.seedling-preparations.edit', $preparation->id) }}"
                                class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye me-1"></i>View
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
