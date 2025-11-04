<x-layout>
    <div class="container-fluid py-4">
        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div class="flex-grow-1">{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center mb-2">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <h6 class="mb-0">Please fix the following errors:</h6>
            </div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div class="mb-3 mb-md-0">
                        <h1 class="h3 fw-bold text-primary mb-1">Zone User Management</h1>
                        <p class="text-muted">Manage users within your zone administration</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary d-flex align-items-center shadow-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="bi bi-plus-circle me-2"></i> Add New User
                        </button>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-download me-2"></i> Export
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-excel me-2"></i> Excel</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-pdf me-2"></i> PDF</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-text me-2"></i> CSV</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 col-6 mb-3">
                <div class="card bg-primary text-white shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title text-white-50">Total Users</h6>
                                <h3 class="fw-bold">{{ $users->total() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-people display-6 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="card bg-success text-white shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title text-white-50">Active</h6>
                                <h3 class="fw-bold">{{ $users->where('status', 'active')->count() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-check-circle display-6 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="card bg-warning text-white shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title text-white-50">Inactive</h6>
                                <h3 class="fw-bold">{{ $users->where('status', 'inactive')->count() }}</h3>
                            <small class="text-white-50">+2 this week</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-pause-circle display-6 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="card bg-info text-white shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title text-white-50">This Month</h6>
                                <h3 class="fw-bold">8</h3>
                                <small class="text-white-50">New users</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-calendar-month display-6 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Search users...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Role</label>
                        <select class="form-select">
                            <option value="">All Roles</option>
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Zone</label>
                        <select class="form-select">
                            <option value="">All Zones</option>
                            @foreach ($zones as $zone)
                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-outline-primary me-2">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>
                        <button class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Users Table -->
        <div class="card shadow-lg border-0">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="card-title mb-0 d-flex align-items-center">
                    <i class="bi bi-list-ul text-primary me-2"></i>
                    User List
                </h5>
            </div>
            <div class="card-body p-0">
                @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>User</th>
                                <th>Contact</th>
                                <th>Gender</th>
                                <th>Zone</th>
                                <th>Status</th>
                                <th>Role</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td class="ps-4 fw-bold">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <span class="text-white fw-bold">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $user->name }}</div>
                                            <small class="text-muted">ID: {{ $user->uuid }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <i class="bi bi-envelope text-muted me-2"></i>
                                        <span>{{ $user->email }}</span>
                                    </div>
                                    @if($user->phone)
                                    <div>
                                        <i class="bi bi-telephone text-muted me-2"></i>
                                        <span>{{ $user->phone }}</span>
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    @if ($user->gender == 'male')
                                    <span class="badge bg-primary bg-opacity-10 ">
                                        <i class="bi bi-gender-male me-1"></i> Male
                                    </span>
                                    @elseif ($user->gender == 'female')
                                    <span class="badge bg-pink bg-opacity-10">
                                        <i class="bi bi-gender-female me-1"></i> Female
                                    </span>
                                    @else
                                    <span class="badge bg-secondary bg-opacity-10 ">
                                        <i class="bi bi-gender-ambiguous me-1"></i> Other
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10">
                                        {{ optional($user->zone)->name ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($user->status == 'active')
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i> Active
                                    </span>
                                    @elseif ($user->status == 'inactive')
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-pause-circle me-1"></i> Inactive
                                    </span>
                                    @else
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle me-1"></i> Suspended
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ optional($user->role)->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.users.edit', $user->uuid) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit User">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete User" onclick="return confirm('Are you sure you want to delete this user?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-people display-1 text-muted"></i>
                    </div>
                    <h4 class="text-muted">No users found</h4>
                    <p class="text-muted mb-4">Get started by adding your first user</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="bi bi-plus-circle me-2"></i> Add New User
                    </button>
                </div>
                @endif
            </div>
            @if($users->count() > 0)
            <div class="card-footer bg-white py-3 border-top">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="text-muted mb-2 mb-md-0">
                        Showing <span class="fw-bold">{{ $users->firstItem() }} - {{ $users->lastItem() }}</span> of <span class="fw-bold">{{ $users->total() }}</span> users
                    </div>
                    <div>
                        <nav aria-label="User pagination">
                            <ul class="pagination mb-0">
                                <!-- Previous Page Link -->
                                @if ($users->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="bi bi-chevron-left"></i>
                                    </span>
                                </li>
                                @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $users->previousPageUrl() }}" rel="prev">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>
                                @endif

                                <!-- Pagination Elements -->
                                @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                                    @if ($page == $users->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                    @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                    @endif
                                @endforeach

                                <!-- Next Page Link -->
                                @if ($users->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $users->nextPageUrl() }}" rel="next">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                                @else
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="bi bi-chevron-right"></i>
                                    </span>
                                </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="bi bi-plus-circle me-2"></i> Create New User
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.user.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" name="name" id="name" required class="form-control" value="{{ old('name') }}" placeholder="Enter full name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email" name="email" id="email" required class="form-control" value="{{ old('email') }}" placeholder="Enter email address">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-telephone"></i>
                                    </span>
                                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" required placeholder="Enter phone number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="gender" class="form-label fw-semibold">Gender</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-gender-ambiguous"></i>
                                    </span>
                                    <select name="gender" id="gender" class="form-select">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="zone_id" class="form-label fw-semibold">Zone</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-geo-alt"></i>
                                    </span>
                                    <select name="zone_id" id="zone_id" class="form-select">
                                        <option value="">Select Zone</option>
                                        @foreach ($zones as $zone)
                                        <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="role_id" class="form-label fw-semibold">Role</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-person-badge"></i>
                                    </span>
                                    <select name="role_id" id="role_id" class="form-select">
                                        <option value="">Select Role</option>
                                        @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" name="password" id="password" class="form-control" required placeholder="Enter password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required placeholder="Confirm password">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 0.75rem;
            transition: transform 0.2s ease-in-out;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }
        
        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
        }
        
        .alert {
            border-radius: 0.75rem;
            border: none;
        }
        
        .table > :not(caption) > * > * {
            padding: 1rem 0.75rem;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }
        
        .badge {
            border-radius: 0.375rem;
            font-weight: 500;
        }
        
        .bg-pink {
            background-color: #e83e8c !important;
        }
        
        .text-pink {
            color: #e83e8c !important;
        }
        
        .input-group-text {
            border-radius: 0.5rem 0 0 0.5rem;
        }
        
        .form-control, .form-select {
            border-radius: 0 0.5rem 0.5rem 0;
        }
        
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
            border-color: #86b7fe;
        }
        
        .page-link {
            border-radius: 0.375rem;
            margin: 0 0.125rem;
            border: 1px solid #dee2e6;
            color: #0d6efd;
        }
        
        .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        
        .page-item.disabled .page-link {
            color: #6c757d;
        }
        
        @media (max-width: 768px) {
            .btn-group {
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
            }
            
            .btn-group .btn {
                border-radius: 0.375rem;
            }
            
            .table-responsive {
                font-size: 0.875rem;
            }
            
            .card-footer .d-flex {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>

    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
</x-layout>