<x-layout>
    <div class="container py-4 py-lg-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">

                <!-- Success Message -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div class="flex-grow-1">{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Header Section -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                    <div class="mb-3 mb-md-0">
                        <h1 class="h3 fw-bold text-primary mb-1">Edit Sector</h1>
                        <p class="text-muted">Update sector information for {{ $sector->name }}</p>
                    </div>
                    <a href="{{ route('sectors.index') }}" class="btn btn-outline-primary d-flex align-items-center">
                        <i class="bi bi-arrow-left me-2"></i> Back to Sectors
                    </a>
                </div>

                <div class="row">
                    <!-- Main Form Column -->
                    <div class="col-lg-8 mb-4 mb-lg-0">
                        <div class="card shadow-lg border-0 h-100">
                            <div class="card-header bg-primary text-white py-3">
                                <h5 class="mb-0 d-flex align-items-center">
                                    <i class="bi bi-pencil-square me-2"></i> Edit Sector Information
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('sectors.update', $sector->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="mb-4">
                                        <label for="name" class="form-label fw-semibold">Sector Name <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-building text-primary"></i>
                                            </span>
                                            <input type="text" class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name', $sector->name) }}" 
                                                   required maxlength="255" placeholder="Enter sector name">
                                        </div>
                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Update the name of your sector. Maximum 255 characters.</div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                        <a href="{{ route('sectors.index') }}" class="btn btn-outline-secondary me-md-2">
                                            <i class="bi bi-x-circle me-2"></i> Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle me-2"></i> Update Sector
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Column -->
                    <div class="col-lg-4">
                        <!-- Location Information Card -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light py-3">
                                <h6 class="mb-0 d-flex align-items-center">
                                    <i class="bi bi-geo-alt text-primary me-2"></i> Location Information
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">Region</small>
                                    <div class="d-flex align-items-center p-2 bg-light rounded">
                                        <i class="bi bi-pin-map text-muted me-2"></i>
                                        <span class="fw-semibold">{{ $sector->region_id ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">Zone</small>
                                    <div class="d-flex align-items-center p-2 bg-light rounded">
                                        <i class="bi bi-pin-map text-muted me-2"></i>
                                        <span class="fw-semibold">{{ $sector->zone_id ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">Woreda</small>
                                    <div class="d-flex align-items-center p-2 bg-light rounded">
                                        <i class="bi bi-pin-map text-muted me-2"></i>
                                        <span class="fw-semibold">{{ $sector->woreda_id ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div>
                                    <small class="text-muted d-block mb-1">Kebele</small>
                                    <div class="d-flex align-items-center p-2 bg-light rounded">
                                        <i class="bi bi-pin-map text-muted me-2"></i>
                                        <span class="fw-semibold">{{ $sector->kebele_id ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sector Details Card -->
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-light py-3">
                                <h6 class="mb-0 d-flex align-items-center">
                                    <i class="bi bi-info-circle text-primary me-2"></i> Sector Details
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">Created</small>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar-plus text-muted me-2"></i>
                                        <span>{{ $sector->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">Last Updated</small>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar-check text-muted me-2"></i>
                                        <span>{{ $sector->updated_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                <div>
                                    <small class="text-muted d-block mb-1">Sector ID</small>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-hash text-muted me-2"></i>
                                        <span class="font-monospace">{{ $sector->id }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions Card -->
                        <div class="card shadow-sm border-0 mt-4">
                            <div class="card-header bg-light py-3">
                                <h6 class="mb-0 d-flex align-items-center">
                                    <i class="bi bi-lightning text-primary me-2"></i> Quick Actions
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('sectors.index') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-list-ul me-1"></i> View All Sectors
                                    </a>
                                    <form action="{{ route('sectors.destroy', $sector->id) }}" method="POST" class="d-grid">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                onclick="return confirm('Are you sure you want to delete this sector?')">
                                            <i class="bi bi-trash me-1"></i> Delete Sector
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
        
        .input-group-text {
            border-radius: 0.5rem 0 0 0.5rem;
        }
        
        .form-control {
            border-radius: 0 0.5rem 0.5rem 0;
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
            border-color: #86b7fe;
        }
        
        .bg-light {
            background-color: #f8f9fa !important;
        }
        
        .font-monospace {
            font-family: 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', monospace;
        }
        
        @media (max-width: 768px) {
            .d-md-flex .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            
            .card-body .row > div {
                margin-bottom: 1rem;
            }
        }
    </style>
</x-layout>