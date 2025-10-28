<x-layout>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-pencil-square me-2"></i> Edit Profile
                        </h5>

                    </div>
                     @php
    $rolePrefix = match (Auth::user()->role_id) {
        1 => 'admin',
        2 => 'zones',
        3 => 'woredas',
        4 => 'kebele',
        default => 'user'
    };
@endphp
<form method="POST" action="{{ url($rolePrefix . '/profile/'.$user->uuid) }}">
    @csrf 
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
                        @method('PUT')
                        <div class="card-body">
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
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                                </div>
                               
                                <div class="col-md-6">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Zone</label>
                                    <input type="text" class="form-control" value="{{ optional($user->zone)->name }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Woreda</label>
                                    <input type="text" class="form-control" value="{{ optional($user->woreda)->name }}" disabled>
                                </div>
     @php
    $currentUserRole = auth()->user()->role_id;
    @endphp
@if ($currentUserRole != 1 && $currentUserRole != 2)
    <div class="col-md-6">
        <label class="form-label">Role</label>
        <select name="role_id" class="form-select" readonly>
            <option value="">Select Role</option>
            @foreach ($roles as $role)
                @if ($role->id == $user->role_id || $role->id == $currentUserRole + 1)
                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endif
            @endforeach
        </select>
    </div>
    @else
    @php
    $currentUserRole = auth()->user()->role_id;
@endphp

<div class="col-md-6">
    <label class="form-label">Role</label>
    <select name="role_id" class="form-select" disabled>
        <option value="">Select Role</option>
        @foreach ($roles as $role)
            @if ($role->id > $currentUserRole) {{-- only allow higher roles --}}
                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endif
        @endforeach
    </select>
</div>

@endif

                                <div class="col-md-6">
                                    <label class="form-label">Kebele</label>
                                    <input type="text" class="form-control" value="{{ optional($user->kebele)->name }}" disabled>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="suspended" {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save me-1"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</x-layout>
