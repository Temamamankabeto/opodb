<x-layout>
    <div class="container-fluid py-4">
        <h2 class="mb-4 text-success fw-bold">
            <i class="bi bi-tree-fill me-2"></i>Seedling Preparations (Qophii fi Dhaabbii)
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
                    <h5 class="fw-bold text-primary mb-1">Total Seedling Preparations</h5>
                    <p class="mb-0 text-muted">{{ $preparations->total() }} Records</p>
                </div>
                <div>
                    <a href="{{ route('sector.seedling-preparations.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-circle me-1"></i>Add New Preparation
                    </a>
                </div>
            </div>
        </div>

        {{-- Main Table --}}
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-light fw-bold">
                <i class="bi bi-list-check me-2"></i>Preparations and Plantings Summary
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-success text-center">
                        <tr>
                            <th>#</th>
                            <th>Year (EC)</th>
                            <th>Seedling Type</th>
                            <th>Seedlings</th>
                            <th>Preparation Data (Qophii)</th>
                            <th>Planting Data (Dhaabbii)</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($preparations as $index => $preparation)
                            @php
                                $prepData = $preparation->preparation_data ?? [];
                                $prepSeedlings = $prepData['selected_seedlings'] ?? [];

                                $plantData = $preparation->planting_data ?? [];
                                $plantSeedlings = $plantData['selected_seedlings'] ?? [];
                            @endphp

                            <tr>
                                <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $preparation->year }} EC</td>
                                <td class="fw-semibold text-primary">
                                    {{ $prepData['seedling_type']['name'] ?? ($plantData['seedling_type']['name'] ?? 'N/A') }}
                                </td>

                                {{-- Seedling Names --}}
                                <td class="small">
                                    @if (count($prepSeedlings))
                                        @foreach ($prepSeedlings as $s)
                                            <div><strong>{{ $s['name'] }}</strong></div>
                                        @endforeach
                                    @elseif(count($plantSeedlings))
                                        @foreach ($plantSeedlings as $s)
                                            <div><strong>{{ $s['name'] }}</strong></div>
                                        @endforeach
                                    @else
                                        <span class="text-muted">No seedlings</span>
                                    @endif
                                </td>

                                {{-- Preparation Data --}}
                                <td class="small">
                                    @forelse($prepSeedlings as $s)
                                        <div class="mb-2 p-2 border-start border-warning border-3 ps-2">
                                            <div class="fw-semibold text-warning">{{ $s['name'] }}</div>
                                            <div><strong>Hanga:</strong> {{ number_format($s['hanga_qophii'] ?? 0, 2) }}
                                            </div>
                                            <div><strong>Participants:</strong>
                                                {{ number_format($s['nama_hirmaate_qophii'] ?? 0, 2) }}</div>
                                            <div><strong>Days:</strong>
                                                {{ number_format($s['baayina_guyyaa_qophii'] ?? 0, 2) }}</div>
                                        </div>
                                    @empty
                                        <span class="text-muted">No preparation data</span>
                                    @endforelse
                                </td>

                                {{-- Planting Data --}}
                                <td class="small">
                                    @if (count($plantSeedlings))
                                        @foreach ($plantSeedlings as $s)
                                            <div class="mb-2 p-2 border-start border-success border-3 ps-2">
                                                <div class="fw-semibold text-success">{{ $s['name'] }}</div>
                                                <div><strong>Hanga:</strong>
                                                    {{ number_format($s['hanga_dhaabbii'] ?? 0, 2) }}</div>
                                                <div><strong>Participants:</strong>
                                                    {{ number_format($s['nama_hirmaate_dhaabbii'] ?? 0, 2) }}</div>
                                                <div><strong>Days:</strong>
                                                    {{ number_format($s['baayina_guyyaa_dhaabbii'] ?? 0, 2) }}</div>
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="text-muted">No planting data</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="text-center">
                                    @if ($preparation->status === 'completed')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Completed
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-clock me-1"></i>Pending
                                        </span>
                                    @endif
                                </td>
                                {{-- Total Seedlings   --}}
                                <td class="fw-semibold text-center">
                                    @php
                                        $typeName =
                                            $preparation->preparation_data['seedling_type']['name'] ?? 'Unknown';
                                        $total = $totalsByType[$typeName] ?? 0;
                                    @endphp
                                    {{ number_format($total, 2) }}
                                </td>

                                {{-- Actions --}}
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button
                                            class="btn btn-sm btn-outline-secondary dropdown-toggle action-menu-toggle"
                                            type="button" id="actionMenu{{ $preparation->id }}"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 overflow-hidden"
                                            aria-labelledby="actionMenu{{ $preparation->id }}">
                                            {{-- View / Plant Info --}}
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center py-2 px-3 action-item"
                                                    href="{{ route('sector.seedling-preparations.edit', $preparation->id) }}">
                                                    <i
                                                        class="bi {{ $preparation->status === 'completed' ? 'bi-eye' : 'bi-tree' }} me-2 text-primary fs-6"></i>
                                                    <span
                                                        class="fw-medium">{{ $preparation->status === 'completed' ? 'View Details' : 'Plant Info' }}</span>
                                                </a>
                                            </li>

                                            {{-- Edit Preparation --}}
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center py-2 px-3 action-item"
                                                    href="{{ route('sector.seedling-preparations.editpreparation', $preparation->id) }}">
                                                    <i class="bi bi-pencil-square me-2 text-warning fs-6"></i>
                                                    <span class="fw-medium">Edit Preparation</span>
                                                </a>
                                            </li>

                                            {{-- Optional: Delete --}}
                                            {{-- <li>
                <form action="{{ route('sector.seedling-preparations.destroy', $preparation->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this record?');">
                    @csrf
                    @method('DELETE')
                    <button class="dropdown-item d-flex align-items-center text-danger py-2 px-3 action-item" type="submit">
                        <i class="bi bi-trash me-2 fs-6"></i>
                        <span class="fw-medium">Delete</span>
                    </button>
                </form>
            </li> --}}
                                        </ul>
                                    </div>
                                </td>



                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-info-circle me-1"></i>No seedling preparations found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $preparations->links() }}
                </div>
            </div>
        </div>
    </div>
    <style>
        .action-menu-toggle {
            border-radius: 6px;
            transition: all 0.2s ease;
            padding: 0.35rem 0.5rem;
            border-color: #dee2e6;
        }

        .action-menu-toggle:hover {
            background-color: #f8f9fa;
            border-color: #adb5bd;
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

        .dropdown-divider {
            margin: 0.25rem 0;
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
    </style>
</x-layout>
