<x-layout>
    <div class="container-fluid">
        <div class="card card-success card-outline shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">User Kebele Admin List</h4>
                <button class="btn btn-success btn-flat btn-sm" data-toggle="modal" data-target="#createUserModal">
                    <i class="fas fa-user-plus"></i> Add User
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered text-center">
                        <thead class="table-success">
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
                                    <!-- Edit button triggers modal -->
                                    <button class="btn btn-outline-primary btn-flat btn-sm" data-toggle="modal" data-target="#editUserModal{{ $user->uuid }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Edit User Modal -->
                            <div class="modal fade" id="editUserModal{{ $user->uuid }}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel{{ $user->uuid }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title text-white" id="editUserModalLabel{{ $user->uuid }}">Edit User</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('kebele.user.update', $user->uuid) }}" method="POST">
                                            @csrf
                                            @method('PUT')
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
                                                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="form-control form-control-sm">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="form-control form-control-sm">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Gender</label>
                                                        <select name="gender" class="form-select form-select-sm">
                                                            <option value="">Select Gender</option>
                                                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Woreda</label>
                                                        <input type="text" class="form-control form-control-sm" value="{{ $user->woreda->name }}" readonly>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Kebele</label>
                                                        <input type="text" class="form-control form-control-sm" value="{{ optional($user->kebele)->name }}" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer justify-content-between">
                                                <button type="submit" class="btn btn-warning btn-flat btn-sm">
                                                    <i class="fas fa-check-circle me-1"></i> Update User
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-flat btn-sm" data-dismiss="modal">
                                                    Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="12" class="text-muted">No users found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Create User -->
    <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white" id="createUserModalLabel">Create New User</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('kebele.admin.store') }}" method="POST">
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
                                <input type="text" name="name" value="{{ old('name') }}" required class="form-control form-control-sm">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required class="form-control form-control-sm">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone') }}" required class="form-control form-control-sm">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select form-select-sm">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Woreda</label>
                                <input type="text" class="form-control form-control-sm" value="{{ $woreda->name }}" readonly>
                                <input type="hidden" name="woreda_id" value="{{ $woreda->id }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kebele</label>
                                <select name="kebele_id" class="form-select form-select-sm" required>
                                    <option value="">Select Kebele</option>
                                    @foreach($kebeles as $kebele)
                                        <option value="{{ $kebele->id }}">{{ $kebele->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <select name="role_id" class="form-select form-select-sm" required>
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" required class="form-control form-control-sm">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" required class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-success btn-flat btn-sm">
                            <i class="fas fa-check-circle me-1"></i> Save User
                        </button>
                        <button type="button" class="btn btn-secondary btn-flat btn-sm" data-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</x-layout>
