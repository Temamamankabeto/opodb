<x-layout>
    <div class="container py-4 py-lg-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">

                <!-- ✅ Success Message -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div class="flex-grow-1">{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- ❌ Error Messages -->
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

                <!-- Header -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                    <div class="mb-3 mb-md-0">
                        <h1 class="h3 fw-bold text-primary mb-1">Add New Sector</h1>
                        <p class="text-muted mb-0">Register a new sector under your kebele.</p>
                    </div>
                    <a href="{{ route('sectors.index') }}" class="btn btn-outline-primary d-flex align-items-center">
                        <i class="bi bi-arrow-left me-2"></i> Back to Sectors
                    </a>
                </div>

                <div class="row">
                    <!-- Main Form -->
                    <div class="col-lg-8 mb-4 mb-lg-0">
                        <div class="card shadow-lg border-0">
                            <div class="card-header bg-primary text-white py-3">
                                <h5 class="mb-0 d-flex align-items-center">
                                    <i class="bi bi-plus-circle me-2"></i> Sector Information
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <form method="POST" action="{{ url('admin/sectors/store') }}">
                                    @csrf

                                    <!-- Sector Name -->
                                    <div class="mb-4">
                                        <label for="name" class="form-label fw-semibold">Sector Name <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-building text-primary"></i>
                                            </span>
                                            <input type="text" name="name" id="name" class="form-control border-start-0 ps-0"
                                                placeholder="Enter sector name" value="{{ old('name') }}" required>
                                        </div>
                                    </div>

                                    <!-- Sector Level -->
                                    <div class="mb-4">
                                        <label for="level" class="form-label fw-semibold">Sector Level <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-diagram-3 text-primary"></i>
                                            </span>
                                            <select name="level" id="level" class="form-select border-start-0 ps-0" required>
                                                <option value="" disabled selected>Select sector level</option>
                                                <option value="Kebele" {{ old('level') == 'Kebele' ? 'selected' : '' }}>Kebele</option>
                                                <option value="Woreda" {{ old('level') == 'Woreda' ? 'selected' : '' }}>Woreda</option>
                                                <option value="Zone" {{ old('level') == 'Zone' ? 'selected' : '' }}>Zone</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Submit Buttons -->
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                        <button type="reset" class="btn btn-outline-secondary me-md-2">
                                            <i class="bi bi-arrow-clockwise me-2"></i> Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save me-2"></i> Save Sector
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light py-3">
                                <h6 class="mb-0 d-flex align-items-center">
                                    <i class="bi bi-geo-alt text-primary me-2"></i> Logged-in User
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <ul class="list-unstyled mb-0 small">
                                    <li><strong>Name:</strong> {{ Auth::user()->name }}</li>
                                    <li><strong>Role:</strong> {{ Auth::user()->role->name ?? 'N/A' }}</li>
                                    <li><strong>Kebele:</strong> {{ Auth::user()->kebele->name ?? 'N/A' }}</li>
                                </ul>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-light py-3">
                                <h6 class="mb-0 d-flex align-items-center">
                                    <i class="bi bi-info-circle text-primary me-2"></i> Tips
                                </h6>
                            </div>
                            <div class="card-body p-3 small">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2"><i class="bi bi-lightbulb text-warning me-2"></i> Use clear, descriptive sector names.</li>
                                    <li class="mb-2"><i class="bi bi-lightbulb text-warning me-2"></i> Avoid duplicate names in the same kebele.</li>
                                    <li><i class="bi bi-lightbulb text-warning me-2"></i> Ensure the correct level is selected.</li>
                                </ul>
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
        .card:hover { transform: translateY(-2px); }
        .btn { border-radius: 0.5rem; font-weight: 500; }
        .alert { border-radius: 0.75rem; border: none; }
        .input-group-text { border-radius: 0.5rem 0 0 0.5rem; }
        .form-control { border-radius: 0 0.5rem 0.5rem 0; }
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
            border-color: #86b7fe;
        }
    </style>
</x-layout>
