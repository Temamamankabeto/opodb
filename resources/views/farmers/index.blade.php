<x-layout>
<div class="container py-5">
    <h2 class="fw-bold text-success mb-4">Farmers</h2>
    <a href="{{ route('farmers.create') }}" class="btn btn-success mb-3">
        <i class="bi bi-plus-circle"></i> Add Farmer
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0 rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-success">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Association</th>
                        <th>Zone</th>
                        <th>Woreda</th>
                        <th>Kebele</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($farmers as $farmer)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $farmer->farmer_name }}</td>
                            <td>{{ $farmer->gender }}</td>
                            <td>{{ $farmer->phone_number }}</td>
                            <td>{{ $farmer->association->name ?? '-' }}</td>
                            <td>{{ $farmer->zone->name ?? '-' }}</td>
                            <td>{{ $farmer->woreda->name ?? '-' }}</td>
                            <td>{{ $farmer->kebele->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('farmers.edit', $farmer->id) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('farmers.destroy', $farmer->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this farmer?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($farmers->hasPages())
            <div class="card-footer bg-light border-top text-center">
                {{ $farmers->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
</x-layout>
