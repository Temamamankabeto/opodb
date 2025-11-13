<x-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0"><i class="bi bi-tree text-success me-2"></i>Add Planting Data</h3>
            <div class="badge bg-light text-dark fs-6">
                <i class="bi bi-info-circle me-1"></i>
                All fields marked with <span class="text-danger">*</span> are required
            </div>
        </div>

        <form action="{{ route('sector.seedling-preparations.update', $preparation->id) }}" method="POST" class="needs-validation" novalidate id="mainForm">
            @csrf
            @method('PUT')
            
            <!-- Year and Type Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Preparation Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Year</label>
                            <div class="form-control bg-light">
                                {{ $preparation->year }} EC
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Seedling Type</label>
                            <div class="form-control bg-light">
                                {{ $preparation->preparation_data['seedling_type']['name'] ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Planting Section -->
            <div id="plantingContainer">
                <!-- Planting Details -->
                <div class="card border-0 shadow-sm mb-4" id="plantingSection">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-tree me-2"></i>
                            Planting Details (Dhaabbii)
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row" id="plantingDetailsContainer">
                            @foreach($preparation->preparation_data['selected_seedlings'] ?? [] as $seedling)
                                <div class="col-md-6 mb-3 seedling-row" data-seedling-id="{{ $seedling['id'] }}">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6 class="fw-semibold seedling-name mb-3">{{ $seedling['name'] }}</h6>
                                            
                                            <!-- Planting Details -->
                                            <div class="planting-details">
                                                <h6 class="text-success mb-2">
                                                    <i class="bi bi-tree me-1"></i>Planting (Dhaabbii)
                                                </h6>
                                                <div class="row g-2">
                                                    <div class="col-12">
                                                        <label class="form-label small">Hanga Dhaabbii <span class="text-danger">*</span></label>
                                                        <input type="number" step="0.01" 
                                                               name="hanga_dhaabbii[{{ $seedling['id'] }}]" 
                                                               class="form-control hanga-dhaabbii" 
                                                               placeholder="0.00" required min="0"
                                                               value="{{ old('hanga_dhaabbii.'.$seedling['id'], $preparation->planting_data['selected_seedlings'][$loop->index]['hanga_dhaabbii'] ?? '') }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small">Nama Hirmaate <span class="text-danger">*</span></label>
                                                        <input type="number" step="0.01" 
                                                               name="nama_hirmaate_dhaabbii[{{ $seedling['id'] }}]" 
                                                               class="form-control nama-hirmaate-dhaabbii" 
                                                               placeholder="0.00" required min="0"
                                                               value="{{ old('nama_hirmaate_dhaabbii.'.$seedling['id'], $preparation->planting_data['selected_seedlings'][$loop->index]['nama_hirmaate_dhaabbii'] ?? '') }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small">Baayina Guyyaa <span class="text-danger">*</span></label>
                                                        <input type="number" step="0.01" 
                                                               name="baayina_guyyaa_dhaabbii[{{ $seedling['id'] }}]" 
                                                               class="form-control baayina-guyyaa-dhaabbii" 
                                                               placeholder="0.00" required min="0"
                                                               value="{{ old('baayina_guyyaa_dhaabbii.'.$seedling['id'], $preparation->planting_data['selected_seedlings'][$loop->index]['baayina_guyyaa_dhaabbii'] ?? '') }}">
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
            <div class="d-flex gap-3 justify-content-end border-top pt-4">
                <a href="{{ route('sector.seedling-preparations.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-success px-4" id="submitBtn">
                    <i class="bi bi-check-circle me-2"></i>Update Planting Data
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const submitBtn = document.getElementById('submitBtn');

            // Add input event listeners to all planting inputs
            document.querySelectorAll('.planting-details input[type="number"]').forEach(input => {
                input.addEventListener('input', function() {
                    validateInput(this);
                    updateSubmitButton();
                });
            });

            function validateInput(input) {
                const value = parseFloat(input.value);
                const isValid = !isNaN(value) && value >= 0;
                
                if (isValid && input.value !== '') {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                } else if (input.value === '') {
                    input.classList.remove('is-valid', 'is-invalid');
                } else {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                }
                
                return isValid;
            }

            function validateAllInputs() {
                let allValid = true;
                
                document.querySelectorAll('.planting-details input[type="number"][required]').forEach(input => {
                    if (!validateInput(input)) {
                        allValid = false;
                    }
                });

                return allValid;
            }

            function updateSubmitButton() {
                const allValid = validateAllInputs();
                submitBtn.disabled = !allValid;
                
                // Visual feedback
                if (submitBtn.disabled) {
                    submitBtn.classList.remove('btn-success');
                    submitBtn.classList.add('btn-secondary');
                } else {
                    submitBtn.classList.remove('btn-secondary');
                    submitBtn.classList.add('btn-success');
                }
            }

            // Handle form submission
            document.getElementById('mainForm').addEventListener('submit', function(e) {
                console.log('Form submission started');
                
                if (!validateAllInputs()) {
                    console.log('Validation failed');
                    e.preventDefault();
                    alert('Please fill all required fields correctly before submitting.');
                    return;
                }

                console.log('Validation passed, preparing data...');

                const seedlingTypeId = {{ $preparation->preparation_data['seedling_type']['id'] }};
                
                // Prepare planting data
                const plantingData = {
                    seedling_type_id: parseInt(seedlingTypeId),
                    selected_seedlings: []
                };

                // Collect data from all seedling rows
                document.querySelectorAll('.seedling-row').forEach(row => {
                    const seedlingId = row.getAttribute('data-seedling-id');
                    
                    // Planting data
                    plantingData.selected_seedlings.push({
                        seedling_id: parseInt(seedlingId),
                        hanga_dhaabbii: parseFloat(row.querySelector('.hanga-dhaabbii').value) || 0,
                        nama_hirmaate_dhaabbii: parseFloat(row.querySelector('.nama-hirmaate-dhaabbii').value) || 0,
                        baayina_guyyaa_dhaabbii: parseFloat(row.querySelector('.baayina-guyyaa-dhaabbii').value) || 0,
                    });
                });

                console.log('Planting data:', plantingData);

                // Remove any existing hidden inputs
                const existingPlantInput = document.querySelector('input[name="planting_data"]');
                if (existingPlantInput) existingPlantInput.remove();

                // Create hidden inputs
                const plantInput = document.createElement('input');
                plantInput.type = 'hidden';
                plantInput.name = 'planting_data';
                plantInput.value = JSON.stringify(plantingData);
                this.appendChild(plantInput);
                
                console.log('Form prepared for submission');
            });

            // Initialize validation on page load
            updateSubmitButton();

            // Form validation
            (function () {
                'use strict'
                var forms = document.querySelectorAll('.needs-validation')
                Array.prototype.slice.call(forms)
                    .forEach(function (form) {
                        form.addEventListener('submit', function (event) {
                            if (!form.checkValidity()) {
                                event.preventDefault()
                                event.stopPropagation()
                            }
                            form.classList.add('was-validated')
                        }, false)
                    })
            })()
        });
    </script>

    <style>
        .seedling-row.border-success {
            border-color: #198754 !important;
        }
        .seedling-row.border-danger {
            border-color: #dc3545 !important;
        }
        .is-valid {
            border-color: #198754 !important;
        }
        .is-invalid {
            border-color: #dc3545 !important;
        }
        .planting-details {
            border-left: 3px solid #198754;
            padding-left: 15px;
        }
    </style>
</x-layout>