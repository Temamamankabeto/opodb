<x-layout>
    <!-- Add CSRF token meta tag in the head section -->
    @csrf
    <div class="container-fluid py-4 bg-light min-vh-100">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">

                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1 text-success fw-bold">
                            Create Crop Plan for {{ $farmer->farmer_name }}
                        </h4>
                        <p class="text-muted mb-0">
                            Plan based on crop configurations with editable fields and auto-calculation
                        </p>
                    </div>
                    <div class="bg-success bg-opacity-10 p-2 rounded">
                        <i class="bi bi-clipboard-data text-success fs-4"></i>
                    </div>

                    <!-- Add this after the success alert section -->
                    @if ($errors->has('duplicate'))
                        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                            <div class="fw-medium">{{ $errors->first('duplicate') }}</div>
                        </div>
                    @endif
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('parcel-crop-plans.store', $farmer->id) }}"
                    class="card shadow border-0">
                    @csrf
                    <input type="hidden" name="farmer_id" value="{{ $farmer->id }}">
                    <input type="hidden" name="fertilizer_data" id="fertilizer_data">

                    <div class="card-body p-4">
                        <!-- Progress Indicator -->
                        <div class="alert alert-info border-0 bg-info bg-opacity-10 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle me-2"></i>
                                <div>
                                    <strong>Auto-calculation Formulas:</strong><br>
                                    • Expected Yield = Area × Productivity<br>
                                    • Fertilizer Required = Rate × Area<br>
                                    • Values update automatically when you change any field
                                </div>
                            </div>
                        </div>

                        <!-- Year & Parcel -->
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Year <span class="text-danger">*</span></label>
                                <select id="year" name="year" class="form-select" required>
                                    <option value="">-- Select Year --</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}">{{ $year }} EC</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Select year to load available crop types</small>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Parcel <span class="text-danger">*</span></label>
                                <select name="parcel_id" id="parcel_id" class="form-select" required>
                                    <option value="">-- Select Parcel --</option>
                                    @foreach ($parcels as $parcel)
                                        <option value="{{ $parcel['id'] }}" data-area="{{ $parcel['area'] }}"
                                            data-soil-id="{{ $parcel['soil_type_id'] }}"
                                            data-soil-name="{{ $parcel['soil_type'] }}"
                                            data-land-type="{{ $parcel['land_type'] }}"
                                            data-kebele-id="{{ $parcel['kebele_id'] }}">
                                            {{ $parcel['land_type'] }} | {{ $parcel['soil_type'] }} |
                                            {{ $parcel['area'] }} ha
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Parcel selection provides area and soil information</small>
                            </div>
                        </div>

                        <!-- Area Display -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                                <i class="bi bi-rulers text-primary"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-1 fw-bold text-dark" id="area-display">0.00 ha</h5>
                                                <small class="text-muted">Parcel Area</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                                <i class="bi bi-droplet text-warning"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-1 fw-bold text-dark" id="soil-display">—</h5>
                                                <small class="text-muted">Soil Type</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Crop Type & Crop -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Crop Type <span
                                        class="text-danger">*</span></label>
                                <select id="croptype_id" name="croptype_id" class="form-select" required disabled>
                                    <option value="">-- Select Year First --</option>
                                </select>
                                <small class="text-muted" id="croptype-help">Select year to enable crop types</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Crop <span class="text-danger">*</span></label>
                                <select id="crop_id" name="crop_id" class="form-select" required disabled>
                                    <option value="">-- Select Crop Type First --</option>
                                </select>
                                <small class="text-muted" id="crop-help">Select crop to load configuration data</small>
                            </div>
                        </div>

                        <!-- Auto-loaded Configuration Data -->
                        <div id="configuration-data" style="display: none;">
                            <div class="card bg-success bg-opacity-10 border-success mb-4">
                                <div class="card-header bg-success bg-opacity-25 border-success">
                                    <h6 class="mb-0 text-success fw-bold">
                                        <i class="bi bi-gear me-2"></i>
                                        Crop Configuration Data
                                        <small class="text-muted ms-2">(All fields are editable)</small>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <!-- Area, Productivity, Seed Rate -->
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Area (ha) <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" id="area" name="area"
                                                    class="form-control editable-field" required
                                                    data-formula="yield,fertilizers">
                                                <span class="input-group-text">ha</span>
                                            </div>
                                            <small class="text-muted">
                                                <i class="bi bi-lightning-charge text-warning me-1"></i>
                                                Affects: Yield & Fertilizers
                                            </small>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Productivity (qt/ha) <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" id="productivity"
                                                    name="productivity_per_ha" class="form-control editable-field"
                                                    required data-formula="yield">
                                                <span class="input-group-text">qt/ha</span>
                                            </div>
                                            <small class="text-muted">
                                                <i class="bi bi-lightning-charge text-warning me-1"></i>
                                                Affects: Yield
                                            </small>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Seed Rate (kg/ha) <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" id="seed_rate" name="seed_rate"
                                                    class="form-control editable-field" required>
                                                <span class="input-group-text">kg/ha</span>
                                            </div>
                                            <small class="text-muted">From crop configuration</small>
                                        </div>
                                    </div>

                                    <!-- Expected Yield & Season -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Expected Yield (qt) <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" id="expected_yield"
                                                    name="expected_yield" class="form-control calculated-field"
                                                    required readonly>
                                                <span class="input-group-text">qt</span>
                                            </div>
                                            <small class="text-muted">
                                                <i class="bi bi-calculator text-info me-1"></i>
                                                Calculated: Area × Productivity
                                            </small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Season <span
                                                    class="text-danger">*</span></label>
                                            <select name="season" class="form-select" required>
                                                <option value="">-- Select Season --</option>
                                                <option value="Ganna">Ganna</option>
                                                <option value="Bona">Bona</option>
                                                <option value="Arfaasaa">Arfaasaa</option>
                                            </select>
                                            <small class="text-muted">Select planting season</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fertilizer Recommendation -->
                            <div class="card bg-warning bg-opacity-10 border-warning">
                                <div class="card-header bg-warning bg-opacity-25 border-warning">
                                    <h6 class="mb-0 text-warning fw-bold">
                                        <i class="bi bi-flower1 me-2"></i>
                                        Fertilizer Recommendations
                                        <small class="text-muted ms-2">(All rates are editable)</small>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Fertilizer</th>
                                                    <th>Rate (kg/ha)</th>
                                                    <th>Required (kg)</th>
                                                    <th width="120" class="text-center">Formula</th>
                                                </tr>
                                            </thead>
                                            <tbody id="fertilizer-list">
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">No data
                                                        available</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Required fertilizer = Rate × Area (<span
                                                id="current-area-calculation">0.00</span> ha)
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-outline-secondary rounded-pill me-2"
                                id="reset-btn">
                                <i class="bi bi-arrow-clockwise me-1"></i> Reset to Default
                            </button>
                            <button type="button" class="btn btn-outline-primary rounded-pill me-2"
                                id="recalculate-all-btn">
                                <i class="bi bi-calculator me-1"></i> Recalculate All
                            </button>
                            <button type="submit" class="btn btn-success px-4 rounded-pill" id="submit-btn"
                                disabled>
                                <i class="bi bi-save me-1"></i> Save Plan
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <script>
        // DOM Elements
        const yearSelect = document.getElementById('year');
        const cropTypeSelect = document.getElementById('croptype_id');
        const cropSelect = document.getElementById('crop_id');
        const parcelSelect = document.getElementById('parcel_id');
        const configurationData = document.getElementById('configuration-data');
        const submitBtn = document.getElementById('submit-btn');

        // Input fields
        const productivityInput = document.getElementById('productivity');
        const seedRateInput = document.getElementById('seed_rate');
        const areaInput = document.getElementById('area');
        const expectedYieldInput = document.getElementById('expected_yield');
        const fertilizerList = document.getElementById('fertilizer-list');
        const fertilizerDataInput = document.getElementById('fertilizer_data');

        // Display elements
        const areaDisplay = document.getElementById('area-display');
        const soilDisplay = document.getElementById('soil-display');
        const currentAreaCalculation = document.getElementById('current-area-calculation');

        // Action buttons
        const resetBtn = document.getElementById('reset-btn');
        const recalculateAllBtn = document.getElementById('recalculate-all-btn');

        // Current state
        let currentSoilId = null;
        let currentKebeleId = null;
        let currentArea = 0;
        let currentSoilName = '—';
        let currentFertilizers = [];
        let originalConfigurationData = null;
        let calculationTimeout = null;

        // 1. When Parcel changes → Update area and soil information
        parcelSelect.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            currentArea = parseFloat(opt.dataset.area || 0);
            currentSoilId = opt.dataset.soilId;
            currentKebeleId = opt.dataset.kebeleId;
            currentSoilName = opt.dataset.soilName || '—';

            console.log('Parcel selected:', {
                currentArea,
                currentSoilId,
                currentKebeleId,
                currentSoilName
            });

            // Update displays
            areaDisplay.textContent = currentArea.toFixed(2) + ' ha';
            soilDisplay.textContent = currentSoilName;
            currentAreaCalculation.textContent = currentArea.toFixed(2);

            // Update form field
            areaInput.value = currentArea;

            // Reload configuration if crop is already selected
            if (cropSelect.value) {
                loadCropConfiguration();
            }
        });

        // 2. When Year changes → Load Crop Types
        yearSelect.addEventListener('change', async function() {
            const year = this.value;

            if (!year) {
                resetCropTypeSelect();
                resetCropSelect();
                hideConfigurationData();
                return;
            }

            // Show loading state
            cropTypeSelect.innerHTML = '<option value="">Loading crop types...</option>';
            cropTypeSelect.disabled = true;

            try {
                console.log('Fetching crop types for year:', year);
                const res = await fetch(`/api/crop-types-by-year/${year}`);

                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }

                const cropTypes = await res.json();
                console.log('Received crop types:', cropTypes);

                cropTypeSelect.innerHTML = '<option value="">-- Select Crop Type --</option>';
                cropTypes.forEach(type => {
                    cropTypeSelect.innerHTML += `<option value="${type.id}">${type.name}</option>`;
                });

                cropTypeSelect.disabled = false;
                document.getElementById('croptype-help').textContent =
                    'Select crop type to load available crops';

                // Reset crop selection
                resetCropSelect();
                hideConfigurationData();

            } catch (error) {
                console.error('Error loading crop types:', error);
                cropTypeSelect.innerHTML = '<option value="">Error loading crop types</option>';
            }
        });

        // 3. When Crop Type changes → Load Crops
        cropTypeSelect.addEventListener('change', async function() {
            const cropTypeId = this.value;
            const year = yearSelect.value;

            if (!cropTypeId || !year) {
                resetCropSelect();
                hideConfigurationData();
                return;
            }

            // Show loading state
            cropSelect.innerHTML = '<option value="">Loading crops...</option>';
            cropSelect.disabled = true;

            try {
                console.log('Fetching crops for crop type:', cropTypeId, 'year:', year);
                const res = await fetch(`/api/crops-by-type-and-year/${cropTypeId}/${year}`);

                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }

                const crops = await res.json();
                console.log('Received crops:', crops);

                cropSelect.innerHTML = '<option value="">-- Select Crop --</option>';
                crops.forEach(crop => {
                    cropSelect.innerHTML += `<option value="${crop.id}">${crop.name}</option>`;
                });

                cropSelect.disabled = false;
                document.getElementById('crop-help').textContent = 'Select crop to load configuration data';

                hideConfigurationData();

            } catch (error) {
                console.error('Error loading crops:', error);
                cropSelect.innerHTML = '<option value="">Error loading crops</option>';
            }
        });

        // 4. When Crop changes → Load Configuration Data
        cropSelect.addEventListener('change', async function() {
            const cropId = this.value;
            const year = yearSelect.value;

            if (!cropId || !year) {
                hideConfigurationData();
                return;
            }

            // Check if area is available
            if (!currentArea || currentArea <= 0) {
                alert('Please select a parcel first to get the area information.');
                cropSelect.value = '';
                return;
            }

            await loadCropConfiguration();
        });

        // Load crop configuration data
        async function loadCropConfiguration() {
            const cropId = cropSelect.value;
            const cropTypeId = cropTypeSelect.value;
            const year = yearSelect.value;

            if (!cropId || !cropTypeId || !year || !currentArea) return;

            // Show loading state
            configurationData.style.display = 'block';
            fertilizerList.innerHTML =
                '<tr><td colspan="4" class="text-center"><div class="spinner-border spinner-border-sm me-2"></div>Loading configuration data...</td></tr>';

            // Clear previous values
            productivityInput.value = '';
            seedRateInput.value = '';
            expectedYieldInput.value = '';
            submitBtn.disabled = true;

            try {
                // Get CSRF token from meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const formData = new FormData();
                formData.append('croptype_id', cropTypeId);
                formData.append('crop_id', cropId);
                formData.append('year', year);
                formData.append('soil_type_id', currentSoilId || '');
                formData.append('kebele_id', currentKebeleId || '');
                formData.append('area', currentArea);

                console.log('Sending configuration request:', {
                    cropTypeId,
                    cropId,
                    year,
                    currentSoilId,
                    currentKebeleId,
                    currentArea
                });

                const res = await fetch('/api/crop-configuration', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                console.log('Response status:', res.status);

                // Check if response is JSON
                const contentType = res.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await res.text();
                    console.error('Non-JSON response:', text.substring(0, 500));
                    throw new Error('Server returned HTML instead of JSON. Check server logs.');
                }

                const data = await res.json();
                console.log('Configuration API Response:', data);

                if (!data.success) {
                    fertilizerList.innerHTML = `<tr><td colspan="4" class="text-center text-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>${data.message}
            </td></tr>`;
                    resetConfigurationData();
                    submitBtn.disabled = true;
                    return;
                }

                // Store original configuration data
                originalConfigurationData = {
                    ...data
                };

                // Fill configuration data
                productivityInput.value = data.productivity_per_ha ?? '';
                seedRateInput.value = data.seed_rate_kg_per_ha ?? '';

                // Set area from parcel selection
                areaInput.value = currentArea;

                // Calculate and set initial expected yield
                calculateExpectedYield();

                console.log('Filled data:', {
                    productivity: productivityInput.value,
                    seedRate: seedRateInput.value,
                    expectedYield: expectedYieldInput.value
                });

                // Fill fertilizer list
                updateFertilizerTable(data.fertilizers || []);

                // Enable submit button
                submitBtn.disabled = false;
                console.log('✅ Configuration data loaded successfully');

            } catch (error) {
                console.error('Error loading configuration:', error);
                fertilizerList.innerHTML = `<tr><td colspan="4" class="text-center text-danger">
            <i class="bi bi-x-circle me-2"></i>Error loading configuration data: ${error.message}
        </td></tr>`;
                submitBtn.disabled = true;
            }
        }

        // Update fertilizer table with editable inputs
        function updateFertilizerTable(fertilizers) {
            currentFertilizers = fertilizers;

            if (fertilizers.length > 0) {
                fertilizerList.innerHTML = '';
                fertilizers.forEach((f, index) => {
                    fertilizerList.innerHTML += `
                <tr>
                    <td class="fw-semibold">${f.fertilizer}</td>
                    <td>
                        <input type="number" step="0.01" class="form-control form-control-sm fertilizer-rate editable-field" 
                               value="${f.rate_kg_per_ha}" data-index="${index}" 
                               data-formula="fertilizers">
                    </td>
                    <td class="fw-bold text-primary fertilizer-required calculated-field" data-index="${index}">
                        ${f.required_kg} kg
                    </td>
                    <td class="text-center">
                        <small class="text-muted">Rate × Area</small>
                    </td>
                </tr>`;
                });

                // Store fertilizer data for form submission
                updateFertilizerData();

                // Add event listeners to fertilizer rate inputs
                document.querySelectorAll('.fertilizer-rate').forEach(input => {
                    input.addEventListener('input', function() {
                        debouncedCalculate('fertilizers');
                    });
                });
            } else {
                fertilizerList.innerHTML = `<tr><td colspan="4" class="text-center text-muted">
            <i class="bi bi-info-circle me-2"></i>No fertilizer recommendations available
        </td></tr>`;
                fertilizerDataInput.value = '';
            }
        }

        // Calculate expected yield
        function calculateExpectedYield() {
            const area = parseFloat(areaInput.value) || 0;
            const productivity = parseFloat(productivityInput.value) || 0;
            const expectedYield = area * productivity;

            expectedYieldInput.value = expectedYield.toFixed(2);
            highlightField(expectedYieldInput);
        }

        // Calculate all fertilizers
        function calculateAllFertilizers() {
            const area = parseFloat(areaInput.value) || 0;

            currentFertilizers.forEach((fert, index) => {
                const rateInput = document.querySelector(`.fertilizer-rate[data-index="${index}"]`);
                const requiredCell = document.querySelector(`.fertilizer-required[data-index="${index}"]`);

                if (rateInput && requiredCell) {
                    const rate = parseFloat(rateInput.value) || 0;
                    const requiredKg = rate * area;

                    requiredCell.textContent = requiredKg.toFixed(2) + ' kg';
                    highlightField(requiredCell);

                    // Update data
                    currentFertilizers[index].rate_kg_per_ha = rate;
                    currentFertilizers[index].required_kg = requiredKg;
                }
            });

            updateFertilizerData();
        }

        // Update fertilizer data for form submission
        function updateFertilizerData() {
            fertilizerDataInput.value = JSON.stringify(currentFertilizers);
        }

        // Perform calculations based on field type
        function performCalculations(fieldType = 'all') {
            if (fieldType === 'all' || fieldType === 'yield') {
                calculateExpectedYield();
            }

            if (fieldType === 'all' || fieldType === 'fertilizers') {
                calculateAllFertilizers();
            }
        }

        // Debounced calculation to prevent too many rapid calculations
        function debouncedCalculate(fieldType = 'all') {
            clearTimeout(calculationTimeout);
            calculationTimeout = setTimeout(() => {
                performCalculations(fieldType);
            }, 300);
        }

        // Highlight field when value changes
        function highlightField(field) {
            field.classList.add('calculation-highlight');
            setTimeout(() => {
                field.classList.remove('calculation-highlight');
            }, 1000);
        }

        // Reset all fields to original values
        resetBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to reset all values to their original configuration?')) {
                if (originalConfigurationData) {
                    // Reset main fields
                    areaInput.value = currentArea;
                    productivityInput.value = originalConfigurationData.productivity_per_ha ?? '';
                    seedRateInput.value = originalConfigurationData.seed_rate_kg_per_ha ?? '';

                    // Reset fertilizers
                    updateFertilizerTable(originalConfigurationData.fertilizers || []);

                    // Recalculate everything
                    performCalculations('all');
                }
            }
        });

        // Recalculate all button
        recalculateAllBtn.addEventListener('click', function() {
            performCalculations('all');
        });

        // Add event listeners to all editable fields
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('editable-field')) {
                const formula = e.target.dataset.formula;
                debouncedCalculate(formula);
            }
        });

        // Helper functions
        function resetCropTypeSelect() {
            cropTypeSelect.innerHTML = '<option value="">-- Select Year First --</option>';
            cropTypeSelect.disabled = true;
            document.getElementById('croptype-help').textContent = 'Select year to enable crop types';
        }

        function resetCropSelect() {
            cropSelect.innerHTML = '<option value="">-- Select Crop Type First --</option>';
            cropSelect.disabled = true;
            document.getElementById('crop-help').textContent = 'Select crop type to enable crops';
        }

        function resetConfigurationData() {
            productivityInput.value = '';
            seedRateInput.value = '';
            expectedYieldInput.value = '';
            fertilizerDataInput.value = '';
            originalConfigurationData = null;
        }

        function hideConfigurationData() {
            configurationData.style.display = 'none';
            submitBtn.disabled = true;
            resetConfigurationData();
        }

        // Initialize displays
        areaDisplay.textContent = '0.00 ha';
        soilDisplay.textContent = '—';
        currentAreaCalculation.textContent = '0.00';
    </script>

    <style>
        .editable-field {
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            background-color: white;
        }

        .editable-field:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
            background-color: #f8fff9;
        }

        .calculated-field {
            background-color: #f8f9fa;
            border: 2px solid #dee2e6;
        }

        .calculation-highlight {
            animation: highlight-pulse 1s ease-in-out;
            border-color: #0d6efd !important;
            background-color: #e7f1ff !important;
        }

        @keyframes highlight-pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.02);
            }

            100% {
                transform: scale(1);
            }
        }

        .fertilizer-rate {
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .fertilizer-rate:focus {
            border-color: #fd7e14;
            box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: 2px solid #e9ecef;
            border-left: none;
        }

        .form-control:focus+.input-group-text {
            border-color: #198754;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
        }

        .card-header {
            border-bottom: 2px solid rgba(0, 0, 0, 0.05);
        }

        .btn {
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }
    </style>
</x-layout>
