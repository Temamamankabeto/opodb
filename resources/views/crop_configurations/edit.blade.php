<x-layout>
    <div class="container-fluid py-4 bg-light min-vh-100">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">

                <!-- Header Section -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1 text-warning fw-bold">Edit Crop Configuration</h4>
                        <p class="text-muted mb-0">Update crop-specific configurations including seed rates and productivity</p>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-2 rounded">
                        <i class="bi bi-pencil-square text-warning fs-4"></i>
                    </div>
                </div>

                <!-- Alerts -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Edit Form -->
                <form method="POST" action="{{ route('crop-configurations.update', $config->id) }}" class="card card-warning card-outline">
                    @csrf
                    @method('PUT')

                    <div class="card-header">
                        <h5 class="card-title"><i class="fas fa-edit me-2"></i>Configuration Details</h5>
                    </div>

                    <div class="card-body">
                        <!-- Year Selection -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Year <span class="text-danger">*</span></label>
                                <select name="year" class="form-select @error('year') is-invalid @enderror" required>
                                    <option value="">-- Select Year --</option>
                                    @php
                                        $currentEthiopianYear = \App\Helpers\EthiopianYear::now();
                                        $years = range($currentEthiopianYear - 2, $currentEthiopianYear + 3);
                                    @endphp
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}" {{ old('year', $config->year) == $year ? 'selected' : '' }}>
                                            {{ $year }} EC
                                        </option>
                                    @endforeach
                                </select>
                                @error('year') <span class="text-danger small">{{ $message }}</span> @enderror
                                <small class="text-muted">Current: {{ $currentEthiopianYear }} EC • Gregorian: {{ date('Y') }}</small>
                            </div>

                           
                        </div>

                        <!-- Crop Type & Crop -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Crop Type <span class="text-danger">*</span></label>
                                <select name="croptype_id" id="croptype_id" class="form-select @error('croptype_id') is-invalid @enderror" required>
                                    <option value="">-- Select Crop Type --</option>
                                    @foreach ($croptypes as $croptype)
                                        <option value="{{ $croptype->id }}" {{ old('croptype_id', $config->croptype_id) == $croptype->id ? 'selected' : '' }}>
                                            {{ $croptype->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('croptype_id') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Crop <span class="text-danger">*</span></label>
                                <select name="crop_id" id="crop_id" class="form-select @error('crop_id') is-invalid @enderror" required>
                                    <option value="">-- Select Crop --</option>
                                    @foreach ($crops as $crop)
                                        <option value="{{ $crop->id }}" {{ old('crop_id', $config->crop_id) == $crop->id ? 'selected' : '' }}>
                                            {{ $crop->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('crop_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                <small id="crop_loading" class="text-muted d-none">Loading crops...</small>
                            </div>
                        </div>

                        <!-- Kebele Selection -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Kebele <span class="text-danger">*</span></label>
                                <select name="kebele_id" class="form-select @error('kebele_id') is-invalid @enderror" required>
                                    <option value="">-- Select Kebele --</option>
                                    @foreach ($kebeles as $kebele)
                                        <option value="{{ $kebele->id }}" {{ old('kebele_id', $config->kebele_id) == $kebele->id ? 'selected' : '' }}>
                                            {{ $kebele->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kebele_id') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Seed Rate & Productivity -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Seed Rate (kg/ha)</label>
                                <input type="number" step="0.01" name="seed_rate_kg_per_ha" value="{{ old('seed_rate_kg_per_ha', $config->seed_rate_kg_per_ha) }}" class="form-control @error('seed_rate_kg_per_ha') is-invalid @enderror" placeholder="e.g., 25.5">
                                @error('seed_rate_kg_per_ha') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Productivity (qt/ha)</label>
                                <input type="number" step="0.01" name="productivity_per_ha" value="{{ old('productivity_per_ha', $config->productivity_per_ha) }}" class="form-control @error('productivity_per_ha') is-invalid @enderror" placeholder="e.g., 35.2">
                                @error('productivity_per_ha') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $config->notes) }}</textarea>
                            @error('notes') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('crop-configurations.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-check me-1"></i> Update Configuration
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Dynamic crop loading
        document.getElementById('croptype_id').addEventListener('change', function() {
            const croptypeId = this.value;
            const cropSelect = document.getElementById('crop_id');
            const cropLoading = document.getElementById('crop_loading');

            if (croptypeId) {
                cropSelect.disabled = true;
                cropSelect.innerHTML = '<option value="">Loading crops...</option>';
                cropLoading.classList.remove('d-none');

                fetch(`/crop-configurations/get-crops-by-type/${croptypeId}`)
                    .then(res => res.json())
                    .then(data => {
                        cropSelect.innerHTML = '<option value="">-- Select Crop --</option>';
                        data.forEach(crop => {
                            cropSelect.innerHTML += `<option value="${crop.id}" ${crop.id == {{ $config->crop_id }} ? 'selected' : ''}>${crop.name}</option>`;
                        });
                        cropSelect.disabled = false;
                        cropLoading.classList.add('d-none');
                    })
                    .catch(() => {
                        cropSelect.innerHTML = '<option>Error loading crops</option>';
                        cropSelect.disabled = false;
                        cropLoading.classList.add('d-none');
                    });
            } else {
                cropSelect.innerHTML = '<option value="">-- Select Crop Type First --</option>';
                cropSelect.disabled = true;
            }
        });
    </script>
</x-layout>
