<x-layout>
    <div class="content-header py-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0 text-success"><i class="fas fa-tree me-2"></i>Add Planting Data</h3>
            <div class="badge bg-light text-dark fs-6">
                <i class="fas fa-info-circle me-1"></i>
                All fields marked with <span class="text-danger">*</span> are required
            </div>
        </div>

        <form action="{{ route('sector.seedling-preparations.update', $preparation->id) }}" method="POST" class="needs-validation" novalidate id="mainForm">
            @csrf
            @method('PUT')

            <!-- Preparation Info Card -->
            <div class="card card-outline card-primary mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Preparation Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Year</label>
                            <div class="form-control bg-light">{{ $preparation->year }} EC</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Seedling Type</label>
                            <div class="form-control bg-light">{{ $preparation->preparation_data['seedling_type']['name'] ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Planting Details Card -->
            <div id="plantingContainer">
                <div class="card card-outline card-success mb-4" id="plantingSection">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-tree me-2"></i>Planting Details (Dhaabbii)</h5>
                    </div>
                    <div class="card-body" id="plantingDetailsContainer">
                        <div class="row g-3">
                            @foreach($preparation->preparation_data['selected_seedlings'] ?? [] as $seedling)
                                <div class="col-md-6 seedling-row" data-seedling-id="{{ $seedling['id'] }}">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6 class="fw-semibold seedling-name mb-3">{{ $seedling['name'] }}</h6>

                                            <div class="planting-details">
                                                <h6 class="text-success mb-2"><i class="fas fa-tree me-1"></i>Planting (Dhaabbii)</h6>
                                                <div class="row g-2">
                                                    <div class="col-12">
                                                        <label class="form-label small">Hanga Dhaabbii <span class="text-danger">*</span></label>
                                                        <input type="number" step="0.01" name="hanga_dhaabbii[{{ $seedling['id'] }}]" class="form-control hanga-dhaabbii" placeholder="0.00" required min="0" value="{{ old('hanga_dhaabbii.'.$seedling['id'], $preparation->planting_data['selected_seedlings'][$loop->index]['hanga_dhaabbii'] ?? '') }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small">Nama Hirmaate <span class="text-danger">*</span></label>
                                                        <input type="number" step="0.01" name="nama_hirmaate_dhaabbii[{{ $seedling['id'] }}]" class="form-control nama-hirmaate-dhaabbii" placeholder="0.00" required min="0" value="{{ old('nama_hirmaate_dhaabbii.'.$seedling['id'], $preparation->planting_data['selected_seedlings'][$loop->index]['nama_hirmaate_dhaabbii'] ?? '') }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small">Baayina Guyyaa <span class="text-danger">*</span></label>
                                                        <input type="number" step="0.01" name="baayina_guyyaa_dhaabbii[{{ $seedling['id'] }}]" class="form-control baayina-guyyaa-dhaabbii" placeholder="0.00" required min="0" value="{{ old('baayina_guyyaa_dhaabbii.'.$seedling['id'], $preparation->planting_data['selected_seedlings'][$loop->index]['baayina_guyyaa_dhaabbii'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex gap-3 justify-content-end mt-4 pt-3 border-top">
                <a href="{{ route('sector.seedling-preparations.index') }}" class="btn btn-outline-secondary px-4"><i class="fas fa-arrow-left me-2"></i>Cancel</a>
                <button type="submit" class="btn btn-success px-4" id="submitBtn"><i class="fas fa-check-circle me-2"></i>Update Planting Data</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const submitBtn = document.getElementById('submitBtn');

            // Validate inputs dynamically
            function validateInput(input) {
                const value = parseFloat(input.value);
                const isValid = !isNaN(value) && value >= 0;
                input.classList.toggle('is-valid', isValid && input.value !== '');
                input.classList.toggle('is-invalid', !isValid && input.value !== '');
                return isValid;
            }

            function validateAllInputs() {
                let allValid = true;
                document.querySelectorAll('.planting-details input[type="number"][required]').forEach(input => {
                    if (!validateInput(input)) allValid = false;
                });
                return allValid;
            }

            function updateSubmitButton() {
                const allValid = validateAllInputs();
                submitBtn.disabled = !allValid;
                submitBtn.classList.toggle('btn-success', allValid);
                submitBtn.classList.toggle('btn-secondary', !allValid);
            }

            document.querySelectorAll('.planting-details input[type="number"]').forEach(input => {
                input.addEventListener('input', updateSubmitButton);
            });

            document.getElementById('mainForm').addEventListener('submit', function(e) {
                if (!validateAllInputs()) {
                    e.preventDefault();
                    alert('Please fill all required fields correctly before submitting.');
                    return;
                }

                const seedlingTypeId = {{ $preparation->preparation_data['seedling_type']['id'] }};
                const plantingData = { seedling_type_id: seedlingTypeId, selected_seedlings: [] };

                document.querySelectorAll('.seedling-row').forEach(row => {
                    const seedlingId = row.dataset.seedlingId;
                    plantingData.selected_seedlings.push({
                        seedling_id: parseInt(seedlingId),
                        hanga_dhaabbii: parseFloat(row.querySelector('.hanga-dhaabbii').value) || 0,
                        nama_hirmaate_dhaabbii: parseFloat(row.querySelector('.nama-hirmaate-dhaabbii').value) || 0,
                        baayina_guyyaa_dhaabbii: parseFloat(row.querySelector('.baayina-guyyaa-dhaabbii').value) || 0,
                    });
                });

                const existingPlantInput = document.querySelector('input[name="planting_data"]');
                if (existingPlantInput) existingPlantInput.remove();

                const plantInput = document.createElement('input');
                plantInput.type = 'hidden';
                plantInput.name = 'planting_data';
                plantInput.value = JSON.stringify(plantingData);
                this.appendChild(plantInput);
            });

            updateSubmitButton();
        });
    </script>

    <style>
        .planting-details {
            border-left: 3px solid #28a745;
            padding-left: 15px;
        }
        .is-valid { border-color: #28a745 !important; }
        .is-invalid { border-color: #dc3545 !important; }
    </style>
</x-layout>
