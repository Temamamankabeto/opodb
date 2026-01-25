<x-layout>
    <div class="container-fluid">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">{{ __('User Kebele Admin List') }}</h3>
                <div class="card-tools">
                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#createUserModal">
                        <i class="fas fa-user-plus"></i> {{ __('Add User') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered text-center">
                        <thead class="thead-light">
                        <tr>
                            <th>{{ __('#') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Gender') }}</th>
                            <th>{{ __('Zone') }}</th>
                            <th>{{ __('Woreda') }}</th>
                            <th>{{ __('Kebele') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('Employee ID') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="font-weight-bold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? '-' }}</td>
                                <td>
                                    @if ($user->gender == 'male')
                                        <span class="badge badge-info">{{ __('Male') }}</span>
                                    @elseif ($user->gender == 'female')
                                        <span class="badge badge-warning">{{ __('Female') }}</span>
                                    @else
                                        <span class="badge badge-secondary">{{ __('Other') }}</span>
                                    @endif
                                </td>
                                <td>{{ optional($user->zone)->name ?? '-' }}</td>
                                <td>{{ optional($user->woreda)->name ?? '-' }}</td>
                                <td>{{ optional($user->kebele)->name ?? '-' }}</td>
                                <td>
                                    @if ($user->status == 'active')
                                        <span class="badge badge-success">{{ __('Active') }}</span>
                                    @elseif ($user->status == 'inactive')
                                        <span class="badge badge-secondary">{{ __('Inactive') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('Suspended') }}</span>
                                    @endif
                                </td>
                                <td>{{ optional($user->role)->name ?? '-' }}</td>
                                <td>{{ $user->employee_id ?? '-' }}</td>
                                <td>
    <!-- Edit button triggers modal -->
    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editUserModal{{ $user->uuid }}">
        <i class="fas fa-edit"></i>
    </button>
    
    <!-- Delete button triggers confirmation modal -->
    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteUserModal{{ $user->uuid }}">
        <i class="fas fa-trash"></i>
    </button>
</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-muted text-center">{{ __('No users found.') }}</td>
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
                    <h5 class="modal-title">{{ __('Create New User') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name') }}" required class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('Email') }} <span class="text-danger">*</span></label>
                                    <input type="email" name="email" value="{{ old('email') }}" required class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('Phone') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" required class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('Gender') }}</label>
                                    <select name="gender" class="form-control">
                                        <option value="">{{ __('Select Gender') }}</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('Woreda') }}</label>
                                    <input type="text" class="form-control" value="{{ $woreda->name }}" readonly>
                                    <input type="hidden" name="woreda_id" value="{{ $woreda->id }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('Kebele') }}</label>
                                    <select name="kebele_id" class="form-control" required>
                                        <option value="">{{ __('Select Kebele') }}</option>
                                        @foreach($kebeles as $kebele)
                                            <option value="{{ $kebele->id }}">{{ $kebele->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('Role') }}</label>
                                    <select name="role_id" class="form-control" required>
                                        <option value="">{{ __('Select Role') }}</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('Password') }} <span class="text-danger">*</span></label>
                                    <input type="password" name="password" required class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" required class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-circle mr-1"></i> {{ __('Save User') }}
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modals (placed outside the table for proper rendering) -->
    @foreach ($users as $user)
        <!-- Edit User Modal -->
        <div class="modal fade" id="editUserModal{{ $user->uuid }}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel{{ $user->uuid }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title">{{ __('Edit User') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
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

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Name') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Email') }} <span class="text-danger">*</span></label>
                                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Gender') }}</label>
                                        <select name="gender" class="form-control">
                                            <option value="">{{ __('Select Gender') }}</option>
                                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Woreda') }}</label>
                                        <input type="text" class="form-control" value="{{ $user->woreda->name }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Kebele') }}</label>
                                        <input type="text" class="form-control" value="{{ optional($user->kebele)->name }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer justify-content-between">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-check-circle mr-1"></i> {{ __('Update User') }}
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                {{ __('Cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Delete Confirmation Modals -->
@foreach ($users as $user)
    <div class="modal fade" id="deleteUserModal{{ $user->uuid }}" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel{{ $user->uuid }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ __('Confirm Deletion') }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="fas fa-user-slash fa-3x text-danger"></i>
                        </div>
                        <h5 class="font-weight-bold">{{ __('Are you sure?') }}</h5>
                        <p class="text-muted">
                            {{ __('You are about to delete user') }}: <strong>{{ $user->name }}</strong>
                        </p>
                        <div class="alert alert-warning small">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ __('This action cannot be undone. All data associated with this user will be permanently removed.') }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> {{ __('Cancel') }}
                    </button>
                    <form action="{{ route('kebele.admin.delete', $user->uuid) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash mr-1"></i> {{ __('Delete User') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

    <style>
        /* Custom modal transitions */
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
            transform: translate(0, -50px);
        }
        
        .modal.show .modal-dialog {
            transform: translate(0, 0);
        }
        
        .modal.fade .modal-content {
            transition: opacity 0.3s ease-out;
            opacity: 0;
        }
        
        .modal.show .modal-content {
            opacity: 1;
        }
        
        /* Smooth button hover effects */
        .btn {
            transition: all 0.2s ease-in-out;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        /* Table row hover effects */
        .table-hover tbody tr {
            transition: all 0.2s ease-in-out;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(0,0,0,0.02);
            transform: scale(1.002);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        /* Badge animations */
        .badge {
            transition: all 0.2s ease-in-out;
        }
        
        .badge:hover {
            transform: scale(1.1);
        }
        
        /* Form input focus effects */
        .form-control {
            transition: all 0.2s ease-in-out;
            border-width: 1px;
        }
        
        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
            transform: translateY(-1px);
        }
        
        /* Card animations */
        .card {
            transition: all 0.3s ease-in-out;
        }
        
        .card:hover {
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        
        /* Modal backdrop animation */
        .modal-backdrop {
            transition: opacity 0.3s ease-in-out;
        }
        
        /* Pagination transitions */
        .page-link {
            transition: all 0.2s ease-in-out;
        }
        
        .page-link:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>

    <script>
        // Add custom JavaScript for enhanced transitions
        document.addEventListener('DOMContentLoaded', function() {
            // Modal show/hide animations
            $('.modal').on('show.bs.modal', function (e) {
                $(this).css('display', 'block');
                setTimeout(() => {
                    $(this).addClass('show');
                }, 10);
            });
            
            $('.modal').on('hide.bs.modal', function (e) {
                $(this).removeClass('show');
                setTimeout(() => {
                    $(this).css('display', 'none');
                }, 300);
            });
            
            // Form submission with loading animation
            $('form').on('submit', function(e) {
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.html();
                
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="fas fa-spinner fa-spin mr-1"></i> {{ __("Processing...") }}');
                
                // Re-enable button after 5 seconds if still disabled (in case of error)
                setTimeout(() => {
                    if (submitBtn.prop('disabled')) {
                        submitBtn.prop('disabled', false);
                        submitBtn.html(originalText);
                    }
                }, 5000);
            });
            
            // Input field animations
            $('.form-control').on('focus', function() {
                $(this).parent().addClass('focus');
            }).on('blur', function() {
                $(this).parent().removeClass('focus');
            });
            
            // Auto-close alerts after 5 seconds
            setTimeout(() => {
                $('.alert').fadeOut(500, function() {
                    $(this).remove();
                });
            }, 5000);
            
            // Table row click animation
            $('.table-hover tbody tr').on('click', function(e) {
                if (!$(e.target).is('button') && !$(e.target).is('a')) {
                    $(this).toggleClass('active');
                }
            });
            
            // Tooltip initialization
            $('[data-toggle="tooltip"]').tooltip({
                animation: true,
                delay: { "show": 100, "hide": 100 }
            });
            
            // Add animation to badges on page load
            $('.badge').each(function(index) {
                $(this).css({
                    'animation': 'badgePulse 0.5s ease-in-out ' + (index * 0.1) + 's'
                });
            });
        });
        
        // Keyframe animation for badges
        const style = document.createElement('style');
        style.textContent = `
            @keyframes badgePulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.1); }
                100% { transform: scale(1); }
            }
            
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            .table-hover tbody tr {
                animation: fadeIn 0.5s ease-out;
                animation-fill-mode: both;
            }
            
            .table-hover tbody tr:nth-child(1) { animation-delay: 0.1s; }
            .table-hover tbody tr:nth-child(2) { animation-delay: 0.2s; }
            .table-hover tbody tr:nth-child(3) { animation-delay: 0.3s; }
            .table-hover tbody tr:nth-child(4) { animation-delay: 0.4s; }
            .table-hover tbody tr:nth-child(5) { animation-delay: 0.5s; }
            .table-hover tbody tr:nth-child(6) { animation-delay: 0.6s; }
            .table-hover tbody tr:nth-child(7) { animation-delay: 0.7s; }
            .table-hover tbody tr:nth-child(8) { animation-delay: 0.8s; }
            .table-hover tbody tr:nth-child(9) { animation-delay: 0.9s; }
            .table-hover tbody tr:nth-child(10) { animation-delay: 1s; }
            
            @keyframes slideInDown {
                from {
                    transform: translateY(-100%);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
            
            .card-header {
                animation: slideInDown 0.5s ease-out;
            }
        `;
        document.head.appendChild(style);
    </script>
</x-layout>