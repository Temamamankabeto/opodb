<x-layout>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">User Kebele admin List</h4>
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    + Add User
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center table-bordered">
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
                                <td> jj
                                    <a href="{{ route('zone.user.edit', $user->uuid) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    {{-- <form action="{{ route('users.destroy', $user->uuid) }}" method="POST"
                                          class="d-inline" onsubmit="return confirm('Are you sure to delete?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash3"></i> Delete
                                        </button>
                                    </form> --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-muted">No users found.</td>
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

    <!-- Modal: Create User -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createUserModalLabel">Create New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('users.kebele.admin.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" required class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female
                                    </option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Woreda</label>
                                <select name="woreda_id" id="woreda" class="form-select">
                                    <option value="">Select Woreda</option>
                                    @foreach ($woredas as $woreda)
                                        <option value="{{ $woreda->id }}">{{ $woreda->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kebele</label>
                                <select name="kebele_id" id="kebele_id" class="form-select">
                                    <option value="">Select Kebele</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <select name="role_id" class="form-select">
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $role)
                                        <option
                                            value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" required class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" required class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Save User
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Load kebeles based on zone
        $('#zone_id').on('change', function () {
            const zoneId = $(this).val();
            $('#woreda_id').html('<option value="">Loading...</option>');
            $('#kebele_id').html('<option value="">Select Kebele</option>'); // clear kebeles

            if (zoneId) {
                $.ajax({
                    url: '/zones/get-woredas/' + zoneId,
                    type: 'GET',
                    success: function (data) {
                        $('#woreda_id').empty().append('<option value="">Select Woreda</option>');
                        data.forEach(woreda => {
                            $('#woreda_id').append(`<option value="${woreda.id}">${woreda.name}</option>`);
                        });
                    }
                });
            } else {
                $('#woreda_id').html('<option value="">Select Woreda</option>');
            }
        });
    </script>
    <script>
        $('#woreda').on('change', function () {
            const woredaId = $(this).val();
            $('#kebele_id').html('<option value="">Loading...</option>');

            if (woredaId) {
                $.ajax({
                    url: '/zones/get-kebeles/' + woredaId,
                    type: 'GET',
                    success: function (data) {
                        $('#kebele_id').empty().append('<option value="">Select Kebele</option>');
                        data.forEach(kebele => {
                            $('#kebele_id').append(`<option value="${kebele.id}">${kebele.name}</option>`);
                        });
                    },
                    error: function () {
                        $('#kebele_id').html('<option value="">Error loading kebeles</option>');
                    }
                });
            } else {
                $('#kebele_id').html('<option value="">Select Kebele</option>');
            }
        });
    </script>

</x-layout>
