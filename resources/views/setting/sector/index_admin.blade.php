<x-layout>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold"><i class="bi bi-people"></i> Sector Admins</h3>
            <a href="{{ route('sectoradmin.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Add New Sector Admin
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="card shadow border-0">
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Gender</th>
                            <th>Sector</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($sectorAdmins as $index => $admin)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $admin->name }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>{{ $admin->phone }}</td>
                            <td class="text-capitalize">{{ $admin->gender ?? '-' }}</td>
                            <td>{{ $admin->sector->name ?? 'N/A' }}</td>
                            <td>{{ $admin->created_at->format('Y-m-d') }}</td>
                            <td class="text-center">
                                <a href="{{ route('sectoradmin.edit', $admin->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ route('sectoradmin.destroy', $admin->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this Sector Admin?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-info-circle"></i> No Sector Admins found for your kebele.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>