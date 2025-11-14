<x-layout>
<div class="container py-5">
    <h3 class="mb-4 fw-bold">Institutions</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('institutions.create') }}" class="btn btn-success mb-3">
        <i class="bi bi-plus-circle"></i> Add New
    </a>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-success">
                        <tr>
                            <th width="10%">#</th>
                            <th>Name</th>
                            <th>Institution Type</th>
                            <th width="20%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($institutions as $i => $institution)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $institution->name }}</td>
                                <td>{{ $institution->type->name ?? '—' }}</td>
                                <td>
                                    <a href="{{ route('institutions.edit', $institution->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('institutions.destroy', $institution->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this institution?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">No institutions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</x-layout>
