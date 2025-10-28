<x-layout>
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">User List</h4>
                <a href="{{ route('users.create') }}" class="btn btn-light btn-sm">+ Add User</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Gender</th>
                            <th>Zone</th>
                            <th>Woreda</th>
                            <th>Kebele</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th>Employee ID</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-bold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? '-' }}</td>
                                <td>
                                    @if ($user->gender == 'male')
                                        <span class="badge bg-info">Male</span>
                                    @elseif ($user->gender == 'female')
                                        <span class="badge bg-warning">Female</span>
                                    @else
                                        <span class="badge bg-secondary">Other</span>
                                    @endif
                                </td>
                                <td>{{ optional($user->zone)->name ?? '-' }}</td>
                                <td>{{ optional($user->woreda)->name ?? '-' }}</td>
                                <td>{{ optional($user->kebele)->name ?? '-' }}</td>
                                <td>
                                    @if ($user->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif ($user->status == 'inactive')
                                        <span class="badge bg-secondary">Inactive</span>
                                    @else
                                        <span class="badge bg-danger">Suspended</span>
                                    @endif
                                </td>
                                <td>{{ optional($user->role)->name ?? '-' }}</td>
                                <td>{{ $user->employee_id ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('zone.users.edit', $user->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                          class="d-inline" onsubmit="return confirm('Are you sure to delete?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash3"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-muted">No users found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layout>
