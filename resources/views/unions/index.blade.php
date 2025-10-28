<x-layout>
    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-success">
                <i class="bi bi-diagram-3"></i> Unions
            </h2>
            <a href="{{ route('unions.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Add New
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="Search by union name...">
                <button class="btn btn-outline-success">Search</button>
            </div>
        </form>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-success">
                        <tr>
                            <th>#</th>
                            <th>Union Name</th>
                            <th>Federation</th>
                            <th>Zone ID</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($unions as $union)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $union->name }}</td>
                                <td>{{ $union->federation->name ?? '-' }}</td>
                                <td>{{ $union->zone->name ?? '-' }}</td>

                                <td>
                                    <a href="{{ route('unions.edit', $union->id) }}"
                                        class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('unions.destroy', $union->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete this union?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">No unions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($unions->hasPages())
                <div class="card-footer bg-light border-top text-center">
                    {{ $unions->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</x-layout>
