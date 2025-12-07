<x-layout>
    <div class="content-header">
        <section class="content">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-12">
                        <h2 class="mb-0">
                            <i class="fas fa-building text-primary mr-2"></i>
                            Create Building Economic Institution Plan
                        </h2>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> Validation Error!</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('sector.building-economic-institution-plans.store') }}" method="POST" id="mainForm">
                    @csrf

                    <!-- Year Selection -->
                    <div class="card card-primary mb-3">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-calendar-alt mr-2"></i>Year Selection</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Year <span class="text-danger">*</span></label>
                                        <select name="year" class="form-control" id="yearSelect" required>
                                            <option value="">-- Select Year --</option>
                                            @foreach ($years as $year)
                                                <option value="{{ $year }}" {{ old('year') == $year ? 'selected' : '' }}>
                                                    {{ $year }} EC
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('year')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Existing Plans Alert -->
                    <div class="alert alert-warning alert-dismissible" id="existingPlansAlert" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Existing Plans Found</h5>
                        <p class="mb-1" id="existingPlansMessage"></p>
                        <a href="{{ route('sector.building-economic-institution-plans.index') }}" class="font-weight-bold">
                            View existing plans
                        </a>
                    </div>

                    <!-- New Building Data -->
                    <div class="card card-info mb-3" id="newBuildingCard">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-plus-circle mr-2"></i>New Building Construction
                                <span class="badge bg-warning ml-2" id="newBuildingStatus" style="display: none;">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <span id="newBuildingStatusText"></span>
                                </span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info py-2 mb-3">
                                <i class="fas fa-info-circle mr-2"></i>
                                Select institutions for new building construction. Institutions that already have data for the selected year are disabled.
                            </div>

                            <div class="row" id="newBuildingsContainer">
                                @foreach ($institutions as $institution)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border new-building-item">
                                            <div class="card-body">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input new-building-checkbox" type="checkbox"
                                                        value="{{ $institution->id }}" id="new-building-{{ $institution->id }}"
                                                        data-institution-name="{{ $institution->name }}">
                                                    <label class="form-check-label font-weight-bold"
                                                        for="new-building-{{ $institution->id }}">
                                                        {{ $institution->name }}
                                                    </label>
                                                    @if ($institution->description)
                                                        <div class="form-text small text-muted">{{ $institution->description }}</div>
                                                    @endif
                                                </div>

                                                <div class="new-building-fields" style="display: none;">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <label class="small font-weight-bold text-primary">
                                                                <i class="fas fa-building mr-1"></i>
                                                                Building: {{ $institution->name }}
                                                            </label>
                                                        </div>

                                                        <!-- Public Section -->
                                                        <div class="col-12 border-bottom pb-2 mb-2">
                                                            <h6 class="text-success mb-2">Public</h6>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group mb-2">
                                                                        <label class="small">Number of Buildings <span class="text-danger">*</span></label>
                                                                        <input type="number" class="form-control new-building-public-number" placeholder="0" min="0" disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group mb-2">
                                                                        <label class="small">Cost per Building (ETB) <span class="text-danger">*</span></label>
                                                                        <input type="number" step="0.01" class="form-control new-building-public-cost" placeholder="0.00" min="0" disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group mb-2">
                                                                        <label class="small">Total Cost (ETB)</label>
                                                                        <input type="text" class="form-control new-building-public-total-cost" placeholder="0.00" readonly style="background-color: #f8f9fa;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Private Section -->
                                                        <div class="col-12 border-bottom pb-2 mb-2">
                                                            <h6 class="text-warning mb-2">Private</h6>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group mb-2">
                                                                        <label class="small">Number of Buildings <span class="text-danger">*</span></label>
                                                                        <input type="number" class="form-control new-building-private-number" placeholder="0" min="0" disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group mb-2">
                                                                        <label class="small">Cost per Building (ETB) <span class="text-danger">*</span></label>
                                                                        <input type="number" step="0.01" class="form-control new-building-private-cost" placeholder="0.00" min="0" disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group mb-2">
                                                                        <label class="small">Total Cost (ETB)</label>
                                                                        <input type="text" class="form-control new-building-private-total-cost" placeholder="0.00" readonly style="background-color: #f8f9fa;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Association Section -->
                                                        <div class="col-12 border-bottom pb-2 mb-2">
                                                            <h6 class="text-info mb-2">Association</h6>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group mb-2">
                                                                        <label class="small">Number of Buildings <span class="text-danger">*</span></label>
                                                                        <input type="number" class="form-control new-building-association-number" placeholder="0" min="0" disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group mb-2">
                                                                        <label class="small">Cost per Building (ETB) <span class="text-danger">*</span></label>
                                                                        <input type="number" step="0.01" class="form-control new-building-association-cost" placeholder="0.00" min="0" disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group mb-2">
                                                                        <label class="small">Total Cost (ETB)</label>
                                                                        <input type="text" class="form-control new-building-association-total-cost" placeholder="0.00" readonly style="background-color: #f8f9fa;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Totals Section -->
                                                        <div class="col-12 bg-light p-2 rounded mb-2">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="form-group mb-0">
                                                                        <label class="small font-weight-bold">Total Buildings</label>
                                                                        <input type="text" class="form-control new-building-total-number" placeholder="0" readonly style="background-color: #e9ecef;">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group mb-0">
                                                                        <label class="small font-weight-bold">Average Cost (ETB)</label>
                                                                        <input type="text" class="form-control new-building-average-cost" placeholder="0.00" readonly style="background-color: #e9ecef;">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group mb-0">
                                                                        <label class="small font-weight-bold">Grand Total (ETB)</label>
                                                                        <input type="text" class="form-control new-building-grand-total" placeholder="0.00" readonly style="background-color: #e9ecef;">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group mb-0">
                                                                        <label class="small font-weight-bold">Total Cost (ETB)</label>
                                                                        <input type="text" class="form-control new-building-total-cost" placeholder="0.00" readonly style="background-color: #e9ecef;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label class="small">Project Description <span class="text-danger">*</span></label>
                                                                <textarea class="form-control new-building-description" rows="2" placeholder="Describe the construction project..." disabled></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if ($institutions->isEmpty())
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    No institutions found.
                                </div>
                            @endif

                            @error('new_building_data')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                            <input type="hidden" name="new_building_data" id="new_building_data">
                        </div>
                    </div>

                    <!-- Summary Card -->
                    <div class="card card-secondary mb-3">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-calculator mr-2"></i>Financial Summary</h3>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="border rounded p-3 bg-light">
                                        <h6 class="text-success mb-1">Public Total</h6>
                                        <h4 class="text-success mb-0" id="publicTotal">0.00 ETB</h4>
                                        <small class="text-muted" id="publicCount">0 projects</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border rounded p-3 bg-light">
                                        <h6 class="text-warning mb-1">Private Total</h6>
                                        <h4 class="text-warning mb-0" id="privateTotal">0.00 ETB</h4>
                                        <small class="text-muted" id="privateCount">0 projects</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border rounded p-3 bg-light">
                                        <h6 class="text-info mb-1">Association Total</h6>
                                        <h4 class="text-info mb-0" id="associationTotal">0.00 ETB</h4>
                                        <small class="text-muted" id="associationCount">0 projects</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border rounded p-3 bg-light">
                                        <h6 class="text-primary mb-1">New Construction</h6>
                                        <h4 class="text-primary mb-0" id="newBuildingsTotal">0.00 ETB</h4>
                                        <small class="text-muted" id="newBuildingsCount">0 buildings</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="border rounded p-3 bg-success text-white text-center">
                                        <h5 class="mb-1">Grand Total</h5>
                                        <h2 class="mb-0" id="grandTotal">0.00 ETB</h2>
                                        <small id="totalProjects">0 total projects</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-footer clearfix">
                                    <a href="{{ route('sector.building-economic-institution-plans.index') }}" class="btn btn-default float-left">
                                        <i class="fas fa-arrow-left mr-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary float-right" id="submitBtn" disabled>
                                        <i class="fas fa-save mr-2"></i>Save Building Plan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <!-- KEEP ALL ORIGINAL JAVASCRIPT EXACTLY AS IS -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const yearSelect = document.getElementById('yearSelect');
            const submitBtn = document.getElementById('submitBtn');
            const newBuildingDataInput = document.getElementById('new_building_data');
            const existingPlansAlert = document.getElementById('existingPlansAlert');
            const existingPlansMessage = document.getElementById('existingPlansMessage');
            const newBuildingStatus = document.getElementById('newBuildingStatus');
            const newBuildingStatusText = document.getElementById('newBuildingStatusText');

            // Store data for new buildings
            const newBuildingsData = new Map();

            // Debug function to log available institutions
            function debugInstitutions() {
                console.log('Available institutions:');
                document.querySelectorAll('.new-building-checkbox').forEach(cb => {
                    console.log(`- ${cb.getAttribute('data-institution-name')} (ID: ${cb.value})`);
                });
            }

            // Call debug on page load
            debugInstitutions();

            // Check for existing plans when year changes
            yearSelect.addEventListener('change', async function() {
                const year = this.value;

                if (!year) {
                    hideExistingPlansAlert();
                    enableAllInstitutions();
                    updateFormState();
                    return;
                }

                try {
                    const response = await fetch(
                        `/sector/building-economic-institution-plans/check-existing?year=${year}`);

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();
                    console.log('Check existing response:', data);

                    if (data.exists) {
                        showExistingPlansAlert(data.plans);
                        disableInstitutionsWithExistingData(data.plans);
                    } else {
                        hideExistingPlansAlert();
                        enableAllInstitutions();
                    }
                } catch (error) {
                    console.error('Error checking existing plans:', error);
                    hideExistingPlansAlert();
                    enableAllInstitutions();

                    // Show error to user
                    existingPlansMessage.textContent =
                        'Error checking existing plans. Please try again.';
                    existingPlansAlert.className = 'alert alert-danger';
                    existingPlansAlert.style.display = 'block';
                }

                updateFormState();
            });

            function showExistingPlansAlert(plans) {
                const institutionNames = plans.map(plan => plan.institution.name).join(', ');
                existingPlansMessage.textContent =
                    `Plans already exist for ${yearSelect.value} EC for institutions: ${institutionNames}. These institutions are disabled. You can only add data for institutions that don't have plans yet.`;
                existingPlansAlert.style.display = 'block';
            }

            function hideExistingPlansAlert() {
                existingPlansAlert.style.display = 'none';
                existingPlansAlert.className = 'alert alert-warning';
            }

            function disableInstitutionsWithExistingData(plans) {
                console.log('Existing plans:', plans);

                // Get all institution IDs that have existing plans
                const existingInstitutionIds = plans.map(plan => {
                    console.log('Plan institution ID:', plan.institution_id, 'Type:', typeof plan
                        .institution_id);
                    return plan.institution_id.toString();
                });

                console.log('Existing institution IDs:', existingInstitutionIds);

                // Disable checkboxes for institutions that already have plans
                document.querySelectorAll('.new-building-checkbox').forEach(checkbox => {
                    const institutionId = checkbox.value;
                    console.log('Checking institution:', institutionId, 'Exists:', existingInstitutionIds
                        .includes(institutionId));

                    if (existingInstitutionIds.includes(institutionId)) {
                        checkbox.disabled = true;
                        checkbox.checked = false;
                        checkbox.parentElement.style.opacity = '0.5';

                        // Hide fields if they were shown
                        const fields = checkbox.closest('.new-building-item')
                            .querySelector('.new-building-fields');
                        if (fields) {
                            fields.style.display = 'none';
                        }

                        // Remove from data stores
                        newBuildingsData.delete(institutionId);

                        // Add disabled styling to the card
                        const card = checkbox.closest('.new-building-item');
                        card.classList.add('disabled');
                        card.style.cursor = 'not-allowed';

                        // Add disabled badge
                        const label = checkbox.closest('.form-check').querySelector('.form-check-label');
                        const institutionPlan = plans.find(p => p.institution_id.toString() ===
                            institutionId);
                        const hasNewBuildingData = institutionPlan?.new_building_data?.length > 0;

                        let badgeText = 'Data Exists';
                        if (hasNewBuildingData) {
                            badgeText = 'Has Building Data';
                        }

                        // Store original text and add badge
                        if (!label.hasAttribute('data-original-text')) {
                            label.setAttribute('data-original-text', label.textContent);
                        }
                        label.innerHTML =
                            `${label.getAttribute('data-original-text')} <span class="badge bg-secondary ml-1" style="font-size: 0.6rem;">${badgeText}</span>`;
                    } else {
                        checkbox.disabled = false;
                        checkbox.parentElement.style.opacity = '1';

                        // Remove disabled styling
                        const card = checkbox.closest('.new-building-item');
                        card.classList.remove('disabled');
                        card.style.cursor = 'pointer';

                        // Remove disabled badge and restore original text
                        const label = checkbox.closest('.form-check').querySelector('.form-check-label');
                        if (label.hasAttribute('data-original-text')) {
                            label.textContent = label.getAttribute('data-original-text');
                            label.removeAttribute('data-original-text');
                        }
                    }
                });

                // Show status badges on card headers
                const newBuildingCount = plans.filter(plan => plan.new_building_data && plan.new_building_data
                    .length > 0).length;

                if (newBuildingCount > 0) {
                    newBuildingStatus.style.display = 'inline-block';
                    newBuildingStatusText.textContent = `${newBuildingCount} institutions disabled`;
                } else {
                    newBuildingStatus.style.display = 'none';
                }

                updateFormState();
            }

            function enableAllInstitutions() {
                document.querySelectorAll('.new-building-checkbox').forEach(checkbox => {
                    checkbox.disabled = false;
                    checkbox.checked = false;
                    checkbox.parentElement.style.opacity = '1';

                    // Remove disabled styling
                    const card = checkbox.closest('.new-building-item');
                    card.classList.remove('disabled');
                    card.style.cursor = 'pointer';

                    // Hide fields
                    const fields = card.querySelector('.new-building-fields');
                    if (fields) {
                        fields.style.display = 'none';
                    }

                    // Remove disabled badge and restore original text
                    const label = checkbox.closest('.form-check').querySelector('.form-check-label');
                    if (label.hasAttribute('data-original-text')) {
                        label.textContent = label.getAttribute('data-original-text');
                        label.removeAttribute('data-original-text');
                    } else {
                        // Ensure we have the original text
                        const institutionName = checkbox.getAttribute('data-institution-name');
                        label.textContent = institutionName;
                    }
                });

                // Clear data stores
                newBuildingsData.clear();

                // Hide status badges
                newBuildingStatus.style.display = 'none';

                // Reset financial summary
                updateFinancialSummary();
            }

            // Initialize New Building Checkboxes
            document.querySelectorAll('.new-building-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    if (this.disabled) return;

                    const parent = this.closest('.new-building-item');
                    const institutionId = this.value;
                    const institutionName = this.getAttribute('data-institution-name');
                    const fields = parent.querySelector('.new-building-fields');

                    if (this.checked) {
                        fields.style.display = 'block';

                        // Initialize data structure for this new building
                        newBuildingsData.set(institutionId, {
                            name: institutionName,
                            public_number: '',
                            private_number: '',
                            association_number: '',
                            public_cost: '',
                            private_cost: '',
                            association_cost: '',
                            description: ''
                        });

                        // Enable inputs
                        enableNewBuildingInputs(parent, true);
                    } else {
                        fields.style.display = 'none';

                        // Disable inputs and clear values
                        enableNewBuildingInputs(parent, false);

                        // Remove data
                        newBuildingsData.delete(institutionId);
                    }
                    updateFormState();
                    updateFinancialSummary();
                });
            });

            function enableNewBuildingInputs(buildingItem, enable) {
                const inputs = buildingItem.querySelectorAll(
                    '.new-building-public-number, .new-building-public-cost, .new-building-private-number, .new-building-private-cost, .new-building-association-number, .new-building-association-cost, .new-building-description'
                );
                inputs.forEach(input => {
                    input.disabled = !enable;
                    if (!enable) {
                        input.value = '';
                        input.classList.remove('is-valid', 'is-invalid');
                    }
                });

                // Update total cost display
                if (!enable) {
                    buildingItem.querySelector('.new-building-public-total-cost').value = '0.00 ETB';
                    buildingItem.querySelector('.new-building-private-total-cost').value = '0.00 ETB';
                    buildingItem.querySelector('.new-building-association-total-cost').value = '0.00 ETB';
                    buildingItem.querySelector('.new-building-total-number').value = '0';
                    buildingItem.querySelector('.new-building-average-cost').value = '0.00 ETB';
                    buildingItem.querySelector('.new-building-grand-total').value = '0.00 ETB';
                    buildingItem.querySelector('.new-building-total-cost').value = '0.00 ETB';
                } else {
                    updateNewBuildingCalculations(buildingItem);
                }
            }

            function updateNewBuildingCalculations(buildingItem) {
                const publicNumber = parseFloat(buildingItem.querySelector('.new-building-public-number').value) || 0;
                const publicCost = parseFloat(buildingItem.querySelector('.new-building-public-cost').value) || 0;
                const privateNumber = parseFloat(buildingItem.querySelector('.new-building-private-number').value) || 0;
                const privateCost = parseFloat(buildingItem.querySelector('.new-building-private-cost').value) || 0;
                const associationNumber = parseFloat(buildingItem.querySelector('.new-building-association-number').value) || 0;
                const associationCost = parseFloat(buildingItem.querySelector('.new-building-association-cost').value) || 0;

                const publicTotal = publicNumber * publicCost;
                const privateTotal = privateNumber * privateCost;
                const associationTotal = associationNumber * associationCost;
                const totalNumber = publicNumber + privateNumber + associationNumber;
                const grandTotal = publicTotal + privateTotal + associationTotal;
                const averageCost = totalNumber > 0 ? grandTotal / totalNumber : 0;
                const totalCost = publicTotal + privateTotal + associationTotal;

                buildingItem.querySelector('.new-building-public-total-cost').value = publicTotal.toLocaleString(
                    'en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }) + ' ETB';

                buildingItem.querySelector('.new-building-private-total-cost').value = privateTotal.toLocaleString(
                    'en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }) + ' ETB';

                buildingItem.querySelector('.new-building-association-total-cost').value = associationTotal.toLocaleString(
                    'en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }) + ' ETB';

                buildingItem.querySelector('.new-building-total-number').value = totalNumber;
                buildingItem.querySelector('.new-building-average-cost').value = averageCost.toLocaleString(
                    'en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }) + ' ETB';

                buildingItem.querySelector('.new-building-grand-total').value = grandTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';

                buildingItem.querySelector('.new-building-total-cost').value = totalCost.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';
            }

            // Update new building data when inputs change
            document.addEventListener('input', (e) => {
                if (e.target.classList.contains('new-building-public-number') ||
                    e.target.classList.contains('new-building-public-cost') ||
                    e.target.classList.contains('new-building-private-number') ||
                    e.target.classList.contains('new-building-private-cost') ||
                    e.target.classList.contains('new-building-association-number') ||
                    e.target.classList.contains('new-building-association-cost') ||
                    e.target.classList.contains('new-building-description')) {

                    const buildingItem = e.target.closest('.new-building-item');
                    const institutionId = buildingItem.querySelector('.new-building-checkbox').value;
                    const institutionName = buildingItem.querySelector('.new-building-checkbox')
                        .getAttribute('data-institution-name');

                    if (newBuildingsData.has(institutionId)) {
                        const publicNumber = buildingItem.querySelector('.new-building-public-number').value;
                        const publicCost = buildingItem.querySelector('.new-building-public-cost').value;
                        const privateNumber = buildingItem.querySelector('.new-building-private-number').value;
                        const privateCost = buildingItem.querySelector('.new-building-private-cost').value;
                        const associationNumber = buildingItem.querySelector('.new-building-association-number').value;
                        const associationCost = buildingItem.querySelector('.new-building-association-cost').value;
                        const description = buildingItem.querySelector('.new-building-description').value;

                        newBuildingsData.set(institutionId, {
                            name: institutionName,
                            public_number: publicNumber,
                            private_number: privateNumber,
                            association_number: associationNumber,
                            public_cost: publicCost,
                            private_cost: privateCost,
                            association_cost: associationCost,
                            description: description
                        });

                        // Update calculations
                        updateNewBuildingCalculations(buildingItem);

                        // Validation styling
                        validateNewBuildingInputs(buildingItem, publicNumber, publicCost, privateNumber,
                            privateCost, associationNumber, associationCost, description);
                    }
                    updateFormState();
                    updateFinancialSummary();
                }
            });

            function validateNewBuildingInputs(buildingItem, publicNumber, publicCost, privateNumber, privateCost,
                associationNumber, associationCost, description) {
                const publicNumberInput = buildingItem.querySelector('.new-building-public-number');
                const publicCostInput = buildingItem.querySelector('.new-building-public-cost');
                const privateNumberInput = buildingItem.querySelector('.new-building-private-number');
                const privateCostInput = buildingItem.querySelector('.new-building-private-cost');
                const associationNumberInput = buildingItem.querySelector('.new-building-association-number');
                const associationCostInput = buildingItem.querySelector('.new-building-association-cost');
                const descriptionInput = buildingItem.querySelector('.new-building-description');

                const isValid = publicNumber && publicCost && privateNumber && privateCost && 
                              associationNumber && associationCost && description;

                [publicNumberInput, publicCostInput, privateNumberInput, privateCostInput, 
                 associationNumberInput, associationCostInput, descriptionInput].forEach(input => {
                    if (isValid) {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    } else {
                        input.classList.remove('is-valid');
                        if (!input.value) {
                            input.classList.add('is-invalid');
                        }
                    }
                });
            }

            function updateFinancialSummary() {
                let publicTotal = 0;
                let privateTotal = 0;
                let associationTotal = 0;
                let newBuildingsTotal = 0;
                let validNewBuildingsCount = 0;
                let publicProjectsCount = 0;
                let privateProjectsCount = 0;
                let associationProjectsCount = 0;

                // Calculate new buildings totals
                newBuildingsData.forEach((building, institutionId) => {
                    if (building.public_number && building.public_cost && building.private_number &&
                        building.private_cost && building.association_number && building.association_cost && 
                        building.description) {
                        const publicSubtotal = parseFloat(building.public_number) * parseFloat(building.public_cost);
                        const privateSubtotal = parseFloat(building.private_number) * parseFloat(building.private_cost);
                        const associationSubtotal = parseFloat(building.association_number) * parseFloat(building.association_cost);

                        publicTotal += publicSubtotal;
                        privateTotal += privateSubtotal;
                        associationTotal += associationSubtotal;
                        newBuildingsTotal += publicSubtotal + privateSubtotal + associationSubtotal;
                        validNewBuildingsCount++;

                        if (parseFloat(building.public_number) > 0) publicProjectsCount++;
                        if (parseFloat(building.private_number) > 0) privateProjectsCount++;
                        if (parseFloat(building.association_number) > 0) associationProjectsCount++;
                    }
                });

                const grandTotal = publicTotal + privateTotal + associationTotal;
                const totalProjects = validNewBuildingsCount;

                // Update displays
                document.getElementById('publicTotal').textContent = publicTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';

                document.getElementById('privateTotal').textContent = privateTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';

                document.getElementById('associationTotal').textContent = associationTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';

                document.getElementById('newBuildingsTotal').textContent = newBuildingsTotal.toLocaleString(
                    'en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }) + ' ETB';

                document.getElementById('grandTotal').textContent = grandTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';

                document.getElementById('publicCount').textContent =
                    `${publicProjectsCount} project${publicProjectsCount !== 1 ? 's' : ''}`;
                document.getElementById('privateCount').textContent =
                    `${privateProjectsCount} project${privateProjectsCount !== 1 ? 's' : ''}`;
                document.getElementById('associationCount').textContent =
                    `${associationProjectsCount} project${associationProjectsCount !== 1 ? 's' : ''}`;
                document.getElementById('newBuildingsCount').textContent =
                    `${validNewBuildingsCount} building${validNewBuildingsCount !== 1 ? 's' : ''}`;
                document.getElementById('totalProjects').textContent =
                    `${totalProjects} total project${totalProjects !== 1 ? 's' : ''}`;
            }

            function updateFormState() {
                const hasYear = yearSelect.value;

                // Check if at least one new building has all required fields
                let hasValidNewBuildings = false;

                newBuildingsData.forEach(building => {
                    if (building.public_number && building.public_cost ) {
                        hasValidNewBuildings = true;
                    }
                });

                const hasData = hasValidNewBuildings;

                submitBtn.disabled = !(hasYear && hasData);

                // Visual feedback
                if (submitBtn.disabled) {
                    submitBtn.classList.remove('btn-primary');
                    submitBtn.classList.add('btn-secondary');
                } else {
                    submitBtn.classList.remove('btn-secondary');
                    submitBtn.classList.add('btn-primary');
                }
            }

            // Update hidden inputs before form submission
            document.getElementById('mainForm').addEventListener('submit', () => {
                // Prepare new buildings data
                const allNewBuildings = [];
                newBuildingsData.forEach((building, institutionId) => {
                    if (building.public_number && building.public_cost && building.private_number &&
                        building.private_cost && building.association_number && building.association_cost && 
                        building.description) {
                        allNewBuildings.push({
                            name: building.name,
                            public_number: parseInt(building.public_number),
                            private_number: parseInt(building.private_number),
                            association_number: parseInt(building.association_number),
                            public_cost: parseFloat(building.public_cost),
                            private_cost: parseFloat(building.private_cost),
                            association_cost: parseFloat(building.association_cost),
                            description: building.description
                        });
                    }
                });

                newBuildingDataInput.value = JSON.stringify(allNewBuildings);
            });

            // Initialize with old values if validation failed
            function initializeOldValues() {
                @if (old('new_building_data'))
                    try {
                        const oldNewBuildings = @json(json_decode(old('new_building_data'), true) ?? []);
                        oldNewBuildings.forEach(building => {
                            const checkboxes = document.querySelectorAll('.new-building-checkbox');
                            checkboxes.forEach(checkbox => {
                                if (checkbox.getAttribute('data-institution-name') === building
                                    .name) {
                                    checkbox.checked = true;
                                    checkbox.dispatchEvent(new Event('change'));

                                    const buildingItem = checkbox.closest('.new-building-item');
                                    buildingItem.querySelector('.new-building-public-number')
                                        .value = building.public_number || '';
                                    buildingItem.querySelector('.new-building-public-cost').value =
                                        building.public_cost || '';
                                    buildingItem.querySelector('.new-building-private-number')
                                        .value = building.private_number || '';
                                    buildingItem.querySelector('.new-building-private-cost').value =
                                        building.private_cost || '';
                                    buildingItem.querySelector('.new-building-association-number')
                                        .value = building.association_number || '';
                                    buildingItem.querySelector('.new-building-association-cost').value =
                                        building.association_cost || '';
                                    buildingItem.querySelector('.new-building-description').value =
                                        building.description || '';

                                    newBuildingsData.set(checkbox.value, {
                                        name: building.name,
                                        public_number: building.public_number || '',
                                        private_number: building.private_number || '',
                                        association_number: building.association_number || '',
                                        public_cost: building.public_cost || '',
                                        private_cost: building.private_cost || '',
                                        association_cost: building.association_cost || '',
                                        description: building.description || ''
                                    });
                                }
                            });
                        });
                    } catch (e) {
                        console.error('Error initializing old new building values:', e);
                    }
                @endif

                updateFormState();
                updateFinancialSummary();
            }

            initializeOldValues();
        });
    </script>

    <style>
        .new-building-item {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .new-building-item:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
        }

        .new-building-item.checked {
            border-color: #17a2b8;
            background-color: #f0f9ff;
        }

        .form-check-input:checked ~ .form-check-label {
            font-weight: bold;
        }

        .new-building-checkbox:checked ~ .form-check-label {
            color: #17a2b8;
        }

        .is-valid {
            border-color: #28a745 !important;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        .btn:disabled {
            cursor: not-allowed;
        }

        .new-building-fields {
            border-left: 3px solid #17a2b8;
            padding-left: 12px;
        }

        /* Style for disabled institutions */
        .new-building-item.disabled {
            opacity: 0.6;
            background-color: #f8f9fa;
            cursor: not-allowed;
        }

        .new-building-item.disabled:hover {
            transform: none;
            background-color: #f8f9fa;
        }

        .form-check-input:disabled {
            cursor: not-allowed;
        }

        .form-check-input:disabled ~ .form-check-label {
            color: #6c757d;
            cursor: not-allowed;
        }

        .badge.bg-secondary {
            font-size: 0.6rem;
            padding: 0.25em 0.4em;
        }

        .border-bottom {
            border-bottom: 2px solid #dee2e6 !important;
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }

        .text-info {
            color: #17a2b8 !important;
        }

        .text-primary {
            color: #007bff !important;
        }

        .text-success {
            color: #28a745 !important;
        }

        .text-warning {
            color: #ffc107 !important;
        }

        /* AdminLTE specific styles */
        .card-body {
            padding: 1.25rem;
        }

        .card-footer {
            padding: 0.75rem 1.25rem;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .card {
            margin-bottom: 1rem;
        }
    </style>
</x-layout>