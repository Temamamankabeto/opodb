<x-layout>
<div class="container py-5">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-success">
            <i class="bi bi-diagram-3-fill"></i> Federations
        </h2>
        <a href="{{ route('federations.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Add New
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Search Bar --}}
    <form method="GET" action="{{ route('federations.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                   placeholder="Search by federation name...">
            <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i> Search</button>
        </div>
    </form>

    {{-- Table Card --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-success">
                        <tr>
                            <th style="width: 5%">#</th>
                            <th>Name</th>
                            <th>Created At</th>
                            <th style="width: 20%" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($federations as $federation)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $federation->name }}</td>
                                <td>{{ $federation->created_at->format('Y-m-d') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('federations.show', $federation->id) }}" class="btn btn-sm btn-outline-info me-1">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('federations.edit', $federation->id) }}" class="btn btn-sm btn-outline-warning me-1">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('federations.destroy', $federation->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to delete this federation?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="bi bi-inboxes"></i> No federations found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($federations->hasPages())
            <div class="card-footer bg-light border-top text-center">
                {{ $federations->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

{{-- Bootstrap Icons CDN --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

</x-layout>
