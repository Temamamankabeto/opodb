<x-layout>
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-success"><i class="bi bi-diagram-3"></i> Associations</h2>
        <a href="{{ route('associations.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Add New
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Search -->
    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                   placeholder="Search by association name...">
            <button class="btn btn-outline-success">Search</button>
        </div>
    </form>

    <!-- Associations Table -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-success">
                    <tr>
                        <th>#</th>
                        <th>Association Name</th>
                        <th>Federation</th>
                        <th>Union</th>
                        <th>Zone</th>
                        <th>Woreda</th>
                        <th>Kebele</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($associations as $association)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $association->name }}</td>
                            <td>{{ $association->federation->name ?? '-' }}</td>
                            <td>{{ $association->union->name ?? '-' }}</td>
                            <td>{{ $association->zone->name ?? '-' }}</td>
                            <td>{{ $association->woreda->name ?? '-' }}</td>
                            <td>{{ $association->kebele->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('associations.edit', $association->id) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('associations.show', $association->id) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="{{ route('associations.destroy', $association->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete this association?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-3">No associations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($associations->hasPages())
            <div class="card-footer bg-light border-top text-center">
                {{ $associations->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</x-layout>
