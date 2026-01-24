<x-layout>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Woreda Admin List</h4>
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <i class="bi bi-plus-circle me-1"></i> Add Woreda Admin
                </button>
            </div>
            <div class="card-body">
                <!-- Success Alert -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
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
                            <th>Status</th>
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
                                <td>
                                    <span class="badge bg-info">{{ $user->phone ?? '-' }}</span>
                                </td>
                                <td>
                                    @if ($user->gender == 'male')
                                        <span class="badge bg-primary">Male</span>
                                    @elseif ($user->gender == 'female')
                                        <span class="badge bg-pink">Female</span>
                                    @else
                                        <span class="badge bg-secondary">Other</span>
                                    @endif
                                </td>
                                <td>{{ optional($user->zone)->name ?? '-' }}</td>
                                <td>{{ optional($user->woreda)->name ?? '-' }}</td>
                                <td>
                                    @if ($user->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif ($user->status == 'inactive')
                                        <span class="badge bg-secondary">Inactive</span>
                                    @else
                                        <span class="badge bg-danger">Suspended</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark">{{ $user->employee_id ?? '-' }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('zone.users.edit', $user->uuid) }}" class="btn btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        @if(auth()->user()->role_id == 1) {{-- Only super admin can delete --}}
                                        <button type="button" class="btn btn-outline-danger" title="Delete" onclick="confirmDelete('{{ $user->id }}', '{{ $user->name }}')">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-muted py-4">No woreda admins found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                <div class="mt-3">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal: Create Woreda Admin -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createUserModalLabel">Create New Woreda Admin</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createUserForm" action="{{ route('woreda.admin.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- Auto-generated Info -->
                        <div class="alert alert-info mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Zone:</strong> {{ Auth::user()->zone->name ?? 'Not assigned' }}
                                </div>
                                <div class="col-md-6">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Role:</strong> Woreda Admin (Auto-assigned)
                                </div>
                            </div>
                        </div>

                        <!-- Error Messages -->
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row g-3">
                            <!-- Personal Information -->
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" required 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       placeholder="Enter full name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required 
                                       class="form-control @error('email') is-invalid @enderror"
                                       placeholder="example@domain.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone') }}" required 
                                       class="form-control @error('phone') is-invalid @enderror"
                                       placeholder="+2519XXXXXXXX or 09XXXXXXXX">
                                <small class="form-text text-muted">Ethiopian format: +2519XXXXXXXX or 09XXXXXXXX</small>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Woreda Selection -->
                            <div class="col-md-6">
                                <label class="form-label">Woreda <span class="text-danger">*</span></label>
                                @if(isset($woredas) && $woredas->count() > 0)
                                <select name="woreda_id" id="woreda_id" required
                                        class="form-select @error('woreda_id') is-invalid @enderror">
                                    <option value="">Select Woreda</option>
                                    @foreach($woredas as $woreda)
                                        <option value="{{ $woreda->id }}" {{ old('woreda_id') == $woreda->id ? 'selected' : '' }}>
                                            {{ $woreda->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @else
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    No woredas available for this zone.
                                </div>
                                <input type="hidden" name="woreda_id" value="">
                                @endif
                                @error('woreda_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Status -->
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : 'selected' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Hidden Fields -->
                            <input type="hidden" name="zone_id" value="{{ Auth::user()->zone_id }}">
                            <input type="hidden" name="role_id" value="3"> <!-- Fixed to role ID 3 -->
                            
                            <!-- Password Fields -->
                            <div class="col-md-6">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" required 
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Minimum 8 characters">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" required 
                                       class="form-control"
                                       placeholder="Re-enter password">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-success" {{ !isset($woredas) || $woredas->count() == 0 ? 'disabled' : '' }}>
                            <i class="bi bi-check-circle me-1"></i> Create Woreda Admin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="deleteUserName"></strong>?</p>
                    <p class="text-danger"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <style>
        .bg-pink {
            background-color: #e83e8c !important;
        }
        .bg-pink:hover {
            background-color: #d81b60 !important;
        }
    </style>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Auto-open modal if there are errors
            @if($errors->any())
                var createModal = new bootstrap.Modal(document.getElementById('createUserModal'));
                createModal.show();
            @endif
            
            // Phone number formatting
            $('input[name="phone"]').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                
                // Convert 09 to +2519
                if (value.startsWith('09') && value.length === 10) {
                    value = '+2519' + value.substring(2);
                }
                // Convert 9 to +2519
                else if (value.startsWith('9') && value.length === 9) {
                    value = '+2519' + value.substring(1);
                }
                // Convert 7 to +2517
                else if (value.startsWith('7') && value.length === 9) {
                    value = '+2517' + value.substring(1);
                }
                
                $(this).val(value);
            });
            
            // Password strength indicator
            $('input[name="password"]').on('keyup', function() {
                let password = $(this).val();
                let strength = 0;
                
                if (password.length >= 8) strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^A-Za-z0-9]/.test(password)) strength++;
                
                // Update strength indicator
                let indicator = $(this).next('.password-strength');
                if (!indicator.length) {
                    $(this).after('<div class="password-strength mt-1"></div>');
                    indicator = $(this).next('.password-strength');
                }
                
                let strengthText = '';
                let strengthClass = '';
                
                switch(strength) {
                    case 0:
                    case 1:
                        strengthText = 'Weak';
                        strengthClass = 'text-danger';
                        break;
                    case 2:
                        strengthText = 'Fair';
                        strengthClass = 'text-warning';
                        break;
                    case 3:
                        strengthText = 'Good';
                        strengthClass = 'text-info';
                        break;
                    case 4:
                        strengthText = 'Strong';
                        strengthClass = 'text-success';
                        break;
                }
                
                indicator.html(`<small class="${strengthClass}">Password strength: ${strengthText}</small>`);
            });
        });
        
        // Delete confirmation function
        function confirmDelete(userId, userName) {
            document.getElementById('deleteUserName').textContent = userName;
            document.getElementById('deleteForm').action = '/zone/users/' + userId;
            
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
    </script>
</x-layout>