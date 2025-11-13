<x-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0"><i class="bi bi-seedling text-success me-2"></i>Add Seedling Preparation</h3>
            <div class="badge bg-light text-dark fs-6">
                <i class="bi bi-info-circle me-1"></i>
                All fields marked with <span class="text-danger">*</span> are required
            </div>
        </div>
          @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form action="{{ route('sector.seedling-preparations.store') }}" method="POST" class="needs-validation" novalidate id="mainForm">
            @csrf
            
            <!-- Year Selection -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar3 me-2"></i>
                        Year Selection
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Year <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-calendar3 text-muted"></i>
                                </span>
                                <select name="year" class="form-select border-start-0 ps-0" id="yearSelect" required>
                                    <option value="">-- Select Year --</option>
                                    @php
                                        $currentEthiopianYear = \App\Helpers\EthiopianYear::now();
                                        $years = range($currentEthiopianYear - 2, $currentEthiopianYear + 3);
                                    @endphp
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}" {{ old('year') == $year ? 'selected' : '' }}>
                                            {{ $year }} EC
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('year')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seedling Types List -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-tags me-2"></i>
                        Select Seedling Type
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-3">Select a seedling type to prepare seedlings.</p>
                    <div class="row" id="seedlingTypesContainer">
                        @foreach($seedlingTypes as $type)
                            <div class="col-md-6 mb-3">
                                <div class="form-check card border seedling-type-card">
                                    <div class="card-body p-3">
                                        <input class="form-check-input seedling-type-radio" 
                                               type="radio" 
                                               name="seedling_type_id" 
                                               value="{{ $type->id }}" 
                                               id="type-{{ $type->id }}"
                                               data-type-name="{{ $type->name }}">
                                        <label class="form-check-label fw-semibold" for="type-{{ $type->id }}">
                                            {{ $type->name }}
                                        </label>
                                        <small class="text-muted d-block mt-1">
                                            <i class="bi bi-tree me-1"></i>
                                            {{ $type->seedlings_count ?? $type->seedlings->count() ?? 0 }} seedlings available
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('seedling_type_id')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Preparation Section (Will be populated dynamically) -->
            <div id="preparationContainer" style="display: none;">
                <!-- Seedlings Selection -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-info text-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-list-check me-2"></i>
                            Select Seedlings for: <span id="selectedTypeName"></span>
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-info py-2 mb-3">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Note:</strong> You need to fill preparation details for <strong>at least one seedling</strong>. Other seedlings are optional.
                        </div>
                        <div class="row" id="seedlingsContainer">
                            <!-- Seedlings will be loaded here -->
                        </div>
                        <div class="text-danger small mt-2 seedlings-error" style="display: none;">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Please fill preparation details for at least one seedling
                        </div>
                    </div>
                </div>

                <!-- Preparation Details -->
                <div class="card border-0 shadow-sm mb-4" id="preparationSection">
                    <div class="card-header bg-warning text-dark py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-gear me-2"></i>
                            Preparation Details (Qophii)
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row" id="preparationDetailsContainer">
                            <!-- Preparation details will be populated here -->
                        </div>
                    </div>
                </div>

                <!-- Nursery Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-house me-2"></i>
                            Nursery Information (Optional)
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Baayyina Nursery (Number of seedlings produced)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-123 text-muted"></i>
                                    </span>
                                    <input type="number" step="0.01" name="baayyina_nersery" class="form-control" 
                                           placeholder="0.00" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Ballina Nursery (Total area in hectares)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-arrows-fullscreen text-muted"></i>
                                    </span>
                                    <input type="number" step="0.01" name="ballina_nersery" class="form-control" 
                                           placeholder="0.00" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nerserif Nama Hirmaate (Number of participants)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-people text-muted"></i>
                                    </span>
                                    <input type="number" step="0.01" name="nerserif_nama_hirmaate" class="form-control" 
                                           placeholder="0.00" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nerserif Baayina Guyyaa (Number of days)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-calendar-day text-muted"></i>
                                    </span>
                                    <input type="number" step="0.01" name="nerserif_baayina_guyyaa" class="form-control" 
                                           placeholder="0.00" min="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex gap-3 justify-content-end border-top pt-4">
                <a href="{{ route('sector.seedling-preparations.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-success px-4" id="submitBtn" disabled>
                    <i class="bi bi-check-circle me-2"></i>Save Preparation Data
                </button>
            </div>
        </form>
    </div>

    <!-- Template for Seedling Row -->
    <template id="seedlingRowTemplate">
        <div class="col-md-6 mb-3 seedling-row" data-seedling-id="">
            <div class="card border">
                <div class="card-body">
                    <h6 class="fw-semibold seedling-name mb-3"></h6>
                    
                    <!-- Preparation Details -->
                    <div class="preparation-details mb-3">
                        <h6 class="text-info mb-2">
                            <i class="bi bi-gear me-1"></i>Preparation (Qophii)
                        </h6>
                        <div class="row g-2">
                            <div class="col-12">
                                <label class="form-label small">Hanga Qophii</label>
                                <input type="number" step="0.01" class="form-control hanga-qophii" placeholder="0.00" min="0">
                            </div>
                            <div class="col-12">
                                <label class="form-label small">Nama Hirmaate</label>
                                <input type="number" step="0.01" class="form-control nama-hirmaate-qophii" placeholder="0.00" min="0">
                            </div>
                            <div class="col-12">
                                <label class="form-label small">Baayina Guyyaa</label>
                                <input type="number" step="0.01" class="form-control baayina-guyyaa-qophii" placeholder="0.00" min="0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const seedlingTypeRadios = document.querySelectorAll('.seedling-type-radio');
            const preparationContainer = document.getElementById('preparationContainer');
            const seedlingsContainer = document.getElementById('seedlingsContainer');
            const selectedTypeName = document.getElementById('selectedTypeName');
            const submitBtn = document.getElementById('submitBtn');
            const template = document.getElementById('seedlingRowTemplate');
            const yearSelect = document.getElementById('yearSelect');
            const seedlingsError = document.querySelector('.seedlings-error');

            let selectedSeedlings = new Map();

            seedlingTypeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const typeId = this.value;
                    const typeName = this.getAttribute('data-type-name');

                    if (this.checked) {
                        selectedTypeName.textContent = typeName;
                        preparationContainer.style.display = 'block';
                        loadSeedlings(typeId);
                    } else {
                        preparationContainer.style.display = 'none';
                        seedlingsContainer.innerHTML = '';
                        selectedSeedlings.clear();
                    }
                    updateSubmitButton();
                });
            });

            function loadSeedlings(typeId) {
                seedlingsContainer.innerHTML = `
                    <div class="col-12">
                        <div class="text-center py-3">
                            <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                            Loading seedlings...
                        </div>
                    </div>
                `;

                fetch(`/sector/seedling-preparations/seedlings-by-type/${typeId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(seedlings => {
                        seedlingsContainer.innerHTML = '';

                        if (seedlings.length > 0) {
                            seedlings.forEach(seedling => {
                                const clone = template.content.cloneNode(true);
                                const row = clone.querySelector('.seedling-row');
                                row.setAttribute('data-seedling-id', seedling.id);
                                row.querySelector('.seedling-name').textContent = seedling.name;

                                seedlingsContainer.appendChild(row);

                                // Add input event listeners
                                addInputListeners(row, seedling.id);
                            });
                        } else {
                            seedlingsContainer.innerHTML = `
                                <div class="col-12">
                                    <div class="alert alert-warning py-2">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        No seedlings found for this type.
                                    </div>
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Error loading seedlings:', error);
                        seedlingsContainer.innerHTML = `
                            <div class="col-12">
                                <div class="alert alert-danger py-2">
                                    <i class="bi bi-x-circle me-2"></i>
                                    Error loading seedlings. Please try again.
                                </div>
                            </div>
                        `;
                    });
            }

            function addInputListeners(row, seedlingId) {
                const inputs = row.querySelectorAll('input[type="number"]');
                inputs.forEach(input => {
                    input.addEventListener('input', function() {
                        validateSeedling(row, seedlingId);
                        updateSubmitButton();
                    });
                });
            }

            function validateSeedling(row, seedlingId) {
                const inputs = row.querySelectorAll('input[type="number"]');
                let hasData = false;
                let allValid = true;

                // Check if at least one field has data
                inputs.forEach(input => {
                    const value = parseFloat(input.value);
                    if (!isNaN(value) && value > 0) {
                        hasData = true;
                    }
                });

                // If has data, validate all fields
                if (hasData) {
                    inputs.forEach(input => {
                        const value = parseFloat(input.value);
                        const isValid = !isNaN(value) && value >= 0;
                        
                        if (input.value === '') {
                            input.classList.remove('is-valid', 'is-invalid');
                        } else if (isValid) {
                            input.classList.remove('is-invalid');
                            input.classList.add('is-valid');
                        } else {
                            input.classList.remove('is-valid');
                            input.classList.add('is-invalid');
                            allValid = false;
                        }
                    });
                } else {
                    // No data, reset validation styles
                    inputs.forEach(input => {
                        input.classList.remove('is-valid', 'is-invalid');
                    });
                    allValid = true; // No data is acceptable for optional seedlings
                }

                selectedSeedlings.set(seedlingId, {
                    hasData: hasData,
                    allValid: allValid
                });

                // Update row border color
                if (hasData && allValid) {
                    row.classList.remove('border-warning', 'border-danger');
                    row.classList.add('border-success');
                } else if (hasData && !allValid) {
                    row.classList.remove('border-success', 'border-warning');
                    row.classList.add('border-danger');
                } else {
                    row.classList.remove('border-success', 'border-danger');
                    row.classList.add('border-warning');
                }

                return { hasData, allValid };
            }

            function updateSubmitButton() {
                const typeSelected = document.querySelector('.seedling-type-radio:checked') !== null;
                const yearSelected = yearSelect.value !== '';
                
                // Check if at least one seedling has data
                let atLeastOneHasData = false;
                let allWithDataAreValid = true;
                let hasSeedlings = false;

                selectedSeedlings.forEach((validation, seedlingId) => {
                    hasSeedlings = true;
                    if (validation.hasData) {
                        atLeastOneHasData = true;
                        if (!validation.allValid) {
                            allWithDataAreValid = false;
                        }
                    }
                });

                const canSubmit = typeSelected && yearSelected && hasSeedlings && atLeastOneHasData && allWithDataAreValid;
                
                submitBtn.disabled = !canSubmit;
                
                // Show/hide error message
                if (typeSelected && yearSelected && hasSeedlings && !atLeastOneHasData) {
                    seedlingsError.style.display = 'block';
                } else {
                    seedlingsError.style.display = 'none';
                }

                // Visual feedback
                if (submitBtn.disabled) {
                    submitBtn.classList.remove('btn-success');
                    submitBtn.classList.add('btn-secondary');
                } else {
                    submitBtn.classList.remove('btn-secondary');
                    submitBtn.classList.add('btn-success');
                }
            }

            yearSelect.addEventListener('change', updateSubmitButton);

            // Handle form submission
            document.getElementById('mainForm').addEventListener('submit', function(e) {
                console.log('Form submission started');
                
                if (!validateForm()) {
                    console.log('Validation failed');
                    e.preventDefault();
                    alert('Please fill preparation details for at least one seedling correctly before submitting.');
                    return;
                }

                console.log('Validation passed, preparing data...');

                const seedlingTypeId = document.querySelector('.seedling-type-radio:checked').value;
                
                // Prepare preparation data
                const preparationData = {
                    seedling_type_id: parseInt(seedlingTypeId),
                    selected_seedlings: []
                };

                // Collect data from all seedling rows
                document.querySelectorAll('.seedling-row').forEach(row => {
                    const seedlingId = row.getAttribute('data-seedling-id');
                    const hangaQophii = parseFloat(row.querySelector('.hanga-qophii').value) || 0;
                    const namaHirmaate = parseFloat(row.querySelector('.nama-hirmaate-qophii').value) || 0;
                    const baayinaGuyyaa = parseFloat(row.querySelector('.baayina-guyyaa-qophii').value) || 0;

                    // Only include seedlings that have at least one field filled
                    if (hangaQophii > 0 || namaHirmaate > 0 || baayinaGuyyaa > 0) {
                        preparationData.selected_seedlings.push({
                            seedling_id: parseInt(seedlingId),
                            hanga_qophii: hangaQophii,
                            nama_hirmaate_qophii: namaHirmaate,
                            baayina_guyyaa_qophii: baayinaGuyyaa,
                        });
                    }
                });

                console.log('Preparation data:', preparationData);

                // Remove any existing hidden inputs
                const existingPrepInput = document.querySelector('input[name="preparation_data"]');
                if (existingPrepInput) existingPrepInput.remove();

                // Create hidden inputs
                const prepInput = document.createElement('input');
                prepInput.type = 'hidden';
                prepInput.name = 'preparation_data';
                prepInput.value = JSON.stringify(preparationData);
                this.appendChild(prepInput);
                
                console.log('Form prepared for submission');
            });

            function validateForm() {
                let atLeastOneHasData = false;
                let allWithDataAreValid = true;
                
                document.querySelectorAll('.seedling-row').forEach(row => {
                    const seedlingId = row.getAttribute('data-seedling-id');
                    const validation = validateSeedling(row, seedlingId);
                    
                    if (validation.hasData) {
                        atLeastOneHasData = true;
                        if (!validation.allValid) {
                            allWithDataAreValid = false;
                        }
                    }
                });

                const typeSelected = document.querySelector('.seedling-type-radio:checked') !== null;
                const yearSelected = yearSelect.value !== '';
                const hasSeedlings = document.querySelectorAll('.seedling-row').length > 0;

                return typeSelected && yearSelected && hasSeedlings && atLeastOneHasData && allWithDataAreValid;
            }

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
        .seedling-type-card {
            transition: all 0.3s ease;
        }
        .seedling-type-card:hover {
            background-color: #f8f9fa;
            cursor: pointer;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .seedling-type-card .form-check-input:checked ~ .form-check-label {
            color: #198754;
            font-weight: bold;
        }
        .seedling-row.border-success {
            border-color: #198754 !important;
        }
        .seedling-row.border-warning {
            border-color: #ffc107 !important;
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
        .preparation-details {
            border-left: 3px solid #0dcaf0;
            padding-left: 15px;
        }
    </style>
</x-layout>