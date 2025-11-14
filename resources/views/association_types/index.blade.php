<x-layout>
<div class="container py-5">
    <h3 class="mb-4 fw-bold">Association Types</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('sector.association_types.create') }}" class="btn btn-success mb-3">
        <i class="bi bi-plus-circle"></i> Add New
    </a>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-success">
                    <tr>
                        <th width="10%">#</th>
                        <th>Name</th>
                        <th width="20%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($types as $i => $type)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $type->name }}</td>
                            <td>
                                <a href="{{ route('sector.association_types.edit', $type->id) }}" class="btn btn-warning btn-sm">
                                    Edit
                                </a>
                                <form action="{{ route('sector.association_types.destroy', $type->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Delete this type?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted">No association types found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-layout>
