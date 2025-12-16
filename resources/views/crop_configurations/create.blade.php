<x-layout>
    <div class="container-header py-4 min-vh-100">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <!-- Header Section -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1 text-success">Configure Crop by Kebele</h4>
                        <p class="text-muted mb-0">Set up crop-specific configurations including seed rates and productivity</p>
                    </div>
                    <div class="bg-success bg-opacity-10 p-2 rounded">
                        <i class="fas fa-cog text-success"></i>
                    </div>
                </div>

                <!-- Alerts -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->has('duplicate'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i> {{ $errors->first('duplicate') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Configuration Form -->
                <form method="POST" action="{{ route('crop-configurations.store') }}" class="card card-success card-outline">
                    @csrf

                    <!-- Card Header -->
                    <div class="card-header">
                        <h5 class="card-title"><i class="fas fa-edit me-2"></i>Basic Configuration</h5>
                    </div>

                    <div class="card-body">
                        <!-- Year Selection -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Year <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <select name="year" class="form-select @error('year') is-invalid @enderror" required>
                                        <option value="">-- Select Year --</option>
                                        @php
                                            $currentEthiopianYear = \App\Helpers\EthiopianYear::now();
                                            $years = range($currentEthiopianYear - 2, $currentEthiopianYear + 3);
                                        @endphp
                                        @foreach ($years as $year)
                                            <option value="{{ $year }}" {{ old('year', $currentEthiopianYear) == $year ? 'selected' : '' }}>
                                                {{ $year }} EC
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('year')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Current: {{ $currentEthiopianYear }} EC • Gregorian: {{ date('Y') }}</small>
                            </div>

                            <div class="col-md-6">
                                 
                            </div>
                        </div>

                        <!-- Crop Type & Crop Selection -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Select Crop Type <span class="text-danger">*</span></label>
                                <select name="croptype_id" id="croptype_id" class="form-select @error('croptype_id') is-invalid @enderror" required>
                                    <option value="">-- Select Crop Type --</option>
                                    @foreach ($croptypes as $croptype)
                                        <option value="{{ $croptype->id }}" {{ old('croptype_id') == $croptype->id ? 'selected' : '' }}>{{ $croptype->name }}</option>
                                    @endforeach
                                </select>
                                @error('croptype_id')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Select Crop <span class="text-danger">*</span></label>
                                <select name="crop_id" id="crop_id" class="form-select @error('crop_id') is-invalid @enderror" required disabled>
                                    <option value="">-- Select Crop Type First --</option>
                                </select>
                                @error('crop_id')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                                <small id="crop_loading" class="text-muted d-none">Loading crops...</small>
                            </div>
                        </div>

                        <!-- Seed Rate & Productivity -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Seed Rate (kg/ha) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="seed_rate_kg_per_ha" class="form-control @error('seed_rate_kg_per_ha') is-invalid @enderror" value="{{ old('seed_rate_kg_per_ha') }}" placeholder="e.g., 25.5" required>
                                    <span class="input-group-text">kg/ha</span>
                                </div>
                                @error('seed_rate_kg_per_ha')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Recommended seed amount per hectare</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Productivity (qt/ha) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="productivity_per_ha" class="form-control @error('productivity_per_ha') is-invalid @enderror" value="{{ old('productivity_per_ha') }}" placeholder="e.g., 35.2" required>
                                    <span class="input-group-text">qt/ha</span>
                                </div>
                                @error('productivity_per_ha')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Expected yield per hectare</small>
                            </div>
                        </div>

                        <!-- Notes Section -->
                        <div class="mb-3">
                            <label class="form-label">Additional Notes</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Add any additional notes or recommendations...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check me-1"></i> Save Configuration
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Crop selection
        document.getElementById('croptype_id').addEventListener('change', function() {
            const croptypeId = this.value;
            const cropSelect = document.getElementById('crop_id');
            const cropLoading = document.getElementById('crop_loading');

            if (croptypeId) {
                cropSelect.disabled = true;
                cropSelect.innerHTML = '<option>Loading crops...</option>';
                cropLoading.classList.remove('d-none');

                fetch(`/kebele/crop-configurations/get-crops-by-type/${croptypeId}`)
                    .then(res => res.json())
                    .then(data => {
                        cropSelect.innerHTML = '<option value="">-- Select Crop --</option>';
                        data.forEach(crop => {
                            cropSelect.innerHTML += `<option value="${crop.id}">${crop.name}</option>`;
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
                cropSelect.innerHTML = '<option>-- Select Crop Type First --</option>';
                cropSelect.disabled = true;
            }
        });

        // Ethiopian Date
        document.addEventListener('DOMContentLoaded', function() {
            const dateEl = document.getElementById('current-ethiopian-date');
            if(dateEl) dateEl.textContent = new Date().toLocaleDateString();
        });
    </script>

    <!-- Font Awesome for AdminLTE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</x-layout>
