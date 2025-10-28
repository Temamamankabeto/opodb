<x-layout>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-success">
                            <i class="bi bi-person-circle me-2"></i> User Profile
                        </h5>
                        @php
                                $currentRole=Auth::user()->role_id;
                                // echo $currentRole;
                             if ($currentRole === 1) { @endphp
                             <a href="{{ route('admin.users.edit' , $user->uuid) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-pencil-square me-1"></i> Edit Profile
                    </a>    
                                            @php     
                                            }
                                             elseif ($currentRole === 2) { 
                                                @endphp
                                                 <a href="{{ route('zone.users.edit' , $user->uuid) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-pencil-square me-1"></i> Edit Profile
                    </a>    
                    @php } 
                    elseif ($currentRole === 3)
                                             { @endphp
                             <a href="{{ route('woreda.users.edit' , $user->uuid) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-pencil-square me-1"></i> Edit Profile
                    </a>   @php                                             } 
                        elseif ($currentRole === 4) 
                        {@endphp
                            <a href="{{ route('kebele.users.edit' , $user->uuid) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-pencil-square me-1"></i> Edit Profile
                        </a> 
                        @php }
                     @endphp

{{-- 
<a href="{{ url($prefix . 'profile/users/' . $user->uuid) }}" class="btn btn-outline-primary btn-sm">
    <i class="bi bi-pencil-square me-1"></i> Edit Profile
</a> --}}
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center mb-4">
                            <div class="col-auto">
                                <div class="rounded-circle bg-light d-flex justify-content-center align-items-center" style="width: 80px; height: 80px;">
                                    <i class="bi bi-person-fill text-secondary" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h4 class="mb-1">{{ $user->name }}</h4>
                                <p class="mb-0 text-muted">{{ $user->email }}</p>
                            </div>
                        </div>

                        <hr>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Phone:</label>
                                <div>{{ $user->phone ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Gender:</label>
                                <div class="badge bg-{{ $user->gender == 'male' ? 'info' : ($user->gender == 'female' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($user->gender) ?? '-' }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Zone:</label>
                                <div>{{ $user->zone?->name  }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Woreda:</label>
                                <div>{{ optional($user->woreda)->name ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Kebele:</label>
                                <div>{{ optional($user->kebele)->name ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Role:</label>
                                <div class="badge bg-dark">{{ optional($user->role)->name ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Status:</label>
                                <div class="badge bg-{{ $user->status == 'active' ? 'success' : ($user->status == 'inactive' ? 'secondary' : 'danger') }}">
                                    {{ ucfirst($user->status) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Employee ID:</label>
                                <div>{{ $user->employee_id ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</x-layout>
