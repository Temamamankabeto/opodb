<x-layout>
    <div class="container py-5">
        <h2 class="mb-4">
            <i class="bi bi-building text-primary me-2"></i>
            Create Building Community Institution Plan
        </h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('sector.building-community-institution-plans.store') }}" method="POST" id="mainForm">
            @csrf

            <!-- Year Selection -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-calendar3 me-2"></i>Year Selection</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Year <span class="text-danger">*</span></label>
                            <select name="year" class="form-select" id="yearSelect" required>
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

            <!-- Existing Plans Alert -->
            <div class="alert alert-warning" id="existingPlansAlert" style="display: none;">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <span id="existingPlansMessage"></span>
                <a href="{{ route('sector.building-community-institution-plans.index') }}" class="alert-link ms-2">
                    View existing plans
                </a>
            </div>

            <!-- New Building Data -->
            <div class="card border-0 shadow-sm mb-4" id="newBuildingCard">
                <div class="card-header bg-info text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>New Building Construction</h5>
                    <span class="badge bg-warning" id="newBuildingStatus" style="display: none;">
                        <i class="bi bi-info-circle me-1"></i>
                        <span id="newBuildingStatusText"></span>
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info py-2 mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        Select institutions for new building construction. Institutions that already have data for the
                        selected year are disabled.
                    </div>

                    <div class="row g-3" id="newBuildingsContainer">
                        @foreach ($institutions as $institution)
                            <div class="col-md-6">
                                <div class="card border new-building-item">
                                    <div class="card-body">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input new-building-checkbox" type="checkbox"
                                                value="{{ $institution->id }}" id="new-building-{{ $institution->id }}"
                                                data-institution-name="{{ $institution->name }}">
                                            <label class="form-check-label fw-semibold"
                                                for="new-building-{{ $institution->id }}">
                                                {{ $institution->name }}
                                            </label>
                                            @if ($institution->description)
                                                <div class="form-text small text-muted">{{ $institution->description }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="new-building-fields" style="display: none;">
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <label class="form-label small fw-semibold text-primary">
                                                        <i class="bi bi-building me-1"></i>
                                                        Building: {{ $institution->name }}
                                                    </label>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">Number of Buildings <span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" class="form-control new-building-number"
                                                        placeholder="0" min="0" disabled>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">Cost per Building (ETB) <span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" step="0.01"
                                                        class="form-control new-building-cost" placeholder="0.00"
                                                        min="0" disabled>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">Total Cost (ETB)</label>
                                                    <input type="text" class="form-control new-building-total-cost"
                                                        placeholder="0.00" readonly
                                                        style="background-color: #f8f9fa; font-size: 0.8rem;">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label small">Project Description <span
                                                            class="text-danger">*</span></label>
                                                    <textarea class="form-control new-building-description" rows="2"
                                                        placeholder="Describe the construction project..." style="font-size: 0.8rem;" disabled></textarea>
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
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            No institutions found.
                        </div>
                    @endif

                    @error('new_building_data')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                    <input type="hidden" name="new_building_data" id="new_building_data">
                </div>
            </div>
            {{-- 
        <!-- Improvement Data -->
        <div class="card border-0 shadow-sm mb-4" id="improvementCard">
            <div class="card-header bg-warning text-dark py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-tools me-2"></i>Existing Building Improvements</h5>
                <span class="badge bg-warning" id="improvementStatus" style="display: none;">
                    <i class="bi bi-info-circle me-1"></i>
                    <span id="improvementStatusText"></span>
                </span>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info py-2 mb-3">
                    <i class="bi bi-info-circle me-2"></i>
                    Select institutions for improvement projects on existing buildings. Institutions that already have data for the selected year are disabled.
                </div>

                <div class="row g-3" id="improvementsContainer">
                    @foreach ($institutions as $institution)
                        <div class="col-md-6">
                            <div class="card border improvement-item">
                                <div class="card-body">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input improvement-checkbox" 
                                               type="checkbox"
                                               value="{{ $institution->id }}" 
                                               id="improvement-{{ $institution->id }}"
                                               data-institution-name="{{ $institution->name }}">
                                        <label class="form-check-label fw-semibold" for="improvement-{{ $institution->id }}">
                                            {{ $institution->name }}
                                        </label>
                                        @if ($institution->description)
                                            <div class="form-text small text-muted">{{ $institution->description }}</div>
                                        @endif
                                    </div>

                                    <div class="improvement-fields" style="display: none;">
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <label class="form-label small fw-semibold text-warning">
                                                    <i class="bi bi-tools me-1"></i>
                                                    Improvement for: {{ $institution->name }}
                                                </label>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small">Number of Improvements <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control improvement-number" 
                                                       placeholder="0" min="0" disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small">Cost per Improvement (ETB) <span class="text-danger">*</span></label>
                                                <input type="number" step="0.01" class="form-control improvement-cost" 
                                                       placeholder="0.00" min="0" disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small">Total Cost (ETB)</label>
                                                <input type="text" class="form-control improvement-total-cost" 
                                                       placeholder="0.00" readonly style="background-color: #f8f9fa; font-size: 0.8rem;">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small">Improvement Description <span class="text-danger">*</span></label>
                                                <textarea class="form-control improvement-description" 
                                                          rows="2" 
                                                          placeholder="Describe the improvement work..." 
                                                          style="font-size: 0.8rem;" disabled></textarea>
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
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        No institutions found.
                    </div>
                @endif

                @error('improvement_data')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
                <input type="hidden" name="improvement_data" id="improvement_data">
            </div>
        </div> --}}

            <!-- Summary Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-secondary text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-calculator me-2"></i>Financial Summary</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="border rounded p-3 bg-light">
                                <h6 class="text-info mb-1">New Construction Total</h6>
                                <h4 class="text-info mb-0" id="newBuildingsTotal">0.00 ETB</h4>
                                <small class="text-muted" id="newBuildingsCount">0 buildings</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 bg-light">
                                <h6 class="text-warning mb-1">Improvements Total</h6>
                                <h4 class="text-warning mb-0" id="improvementsTotal">0.00 ETB</h4>
                                <small class="text-muted" id="improvementsCount">0 improvements</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 bg-light">
                                <h6 class="text-success mb-1">Grand Total</h6>
                                <h4 class="text-success mb-0" id="grandTotal">0.00 ETB</h4>
                                <small class="text-muted" id="totalProjects">0 total projects</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 border-top pt-4">
                <a href="{{ route('sector.building-community-institution-plans.index') }}"
                    class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-primary px-4" id="submitBtn" disabled>
                    <i class="bi bi-save me-2"></i>Save Building Plan
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const yearSelect = document.getElementById('yearSelect');
            const submitBtn = document.getElementById('submitBtn');
            const newBuildingDataInput = document.getElementById('new_building_data');
            const improvementDataInput = document.getElementById('improvement_data');
            const existingPlansAlert = document.getElementById('existingPlansAlert');
            const existingPlansMessage = document.getElementById('existingPlansMessage');
            const newBuildingStatus = document.getElementById('newBuildingStatus');
            const improvementStatus = document.getElementById('improvementStatus');
            const newBuildingStatusText = document.getElementById('newBuildingStatusText');
            const improvementStatusText = document.getElementById('improvementStatusText');

            // Store data separately for new buildings and improvements
            const newBuildingsData = new Map();
            const improvementsData = new Map();

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
                        `/sector/building-community-institution-plans/check-existing?year=${year}`);
                    const data = await response.json();

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
            }

            function disableInstitutionsWithExistingData(plans) {
                // Get all institution IDs that have existing plans
                const existingInstitutionIds = plans.map(plan => plan.institution_id.toString());

                // Disable checkboxes for institutions that already have plans
                document.querySelectorAll('.new-building-checkbox, .improvement-checkbox').forEach(checkbox => {
                    if (existingInstitutionIds.includes(checkbox.value)) {
                        checkbox.disabled = true;
                        checkbox.checked = false;
                        checkbox.parentElement.style.opacity = '0.5';

                        // Hide fields if they were shown
                        const fields = checkbox.closest('.new-building-item, .improvement-item')
                            .querySelector('.new-building-fields, .improvement-fields');
                        if (fields) {
                            fields.style.display = 'none';
                        }

                        // Remove from data stores
                        newBuildingsData.delete(checkbox.value);
                        improvementsData.delete(checkbox.value);

                        // Add disabled styling to the card
                        const card = checkbox.closest('.new-building-item, .improvement-item');
                        card.classList.add('disabled');
                        card.style.cursor = 'not-allowed';

                        // Add disabled badge
                        const label = checkbox.closest('.form-check').querySelector('.form-check-label');
                        const hasNewBuildingData = plans.find(p => p.institution_id.toString() === checkbox
                            .value)?.new_building_data?.length > 0;
                        const hasImprovementData = plans.find(p => p.institution_id.toString() === checkbox
                            .value)?.improvement_data?.length > 0;

                        let badgeText = 'Data Exists';
                        if (hasNewBuildingData && hasImprovementData) {
                            badgeText = 'Has Building & Improvement Data';
                        } else if (hasNewBuildingData) {
                            badgeText = 'Has Building Data';
                        } else if (hasImprovementData) {
                            badgeText = 'Has Improvement Data';
                        }

                        label.innerHTML =
                            `${checkbox.getAttribute('data-institution-name')} <span class="badge bg-secondary ms-1" style="font-size: 0.6rem;">${badgeText}</span>`;
                    } else {
                        checkbox.disabled = false;
                        checkbox.parentElement.style.opacity = '1';

                        // Remove disabled styling
                        const card = checkbox.closest('.new-building-item, .improvement-item');
                        card.classList.remove('disabled');
                        card.style.cursor = 'pointer';

                        // Remove disabled badge
                        const label = checkbox.closest('.form-check').querySelector('.form-check-label');
                        label.textContent = checkbox.getAttribute('data-institution-name');
                    }
                });

                // Show status badges on card headers
                const newBuildingCount = plans.filter(plan => plan.new_building_data && plan.new_building_data
                    .length > 0).length;
                const improvementCount = plans.filter(plan => plan.improvement_data && plan.improvement_data
                    .length > 0).length;

                if (newBuildingCount > 0) {
                    newBuildingStatus.style.display = 'inline-block';
                    newBuildingStatusText.textContent = `${newBuildingCount} institutions disabled`;
                }
                if (improvementCount > 0) {
                    improvementStatus.style.display = 'inline-block';
                    improvementStatusText.textContent = `${improvementCount} institutions disabled`;
                }
            }

            function enableAllInstitutions() {
                document.querySelectorAll('.new-building-checkbox, .improvement-checkbox').forEach(checkbox => {
                    checkbox.disabled = false;
                    checkbox.parentElement.style.opacity = '1';

                    // Remove disabled styling
                    const card = checkbox.closest('.new-building-item, .improvement-item');
                    card.classList.remove('disabled');
                    card.style.cursor = 'pointer';

                    // Remove disabled badge
                    const label = checkbox.closest('.form-check').querySelector('.form-check-label');
                    label.textContent = checkbox.getAttribute('data-institution-name');
                });

                newBuildingStatus.style.display = 'none';
                improvementStatus.style.display = 'none';
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
                            name: institutionName, // Use institution name as building name
                            number: '',
                            cost: '',
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

            // Initialize Improvement Checkboxes
            document.querySelectorAll('.improvement-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    if (this.disabled) return;

                    const parent = this.closest('.improvement-item');
                    const institutionId = this.value;
                    const institutionName = this.getAttribute('data-institution-name');
                    const fields = parent.querySelector('.improvement-fields');

                    if (this.checked) {
                        fields.style.display = 'block';

                        // Initialize data structure for this improvement
                        improvementsData.set(institutionId, {
                            name: institutionName, // Use institution name as improvement context
                            number: '',
                            cost: '',
                            description: ''
                        });

                        // Enable inputs
                        enableImprovementInputs(parent, true);
                    } else {
                        fields.style.display = 'none';

                        // Disable inputs and clear values
                        enableImprovementInputs(parent, false);

                        // Remove data
                        improvementsData.delete(institutionId);
                    }
                    updateFormState();
                    updateFinancialSummary();
                });
            });

            function enableNewBuildingInputs(buildingItem, enable) {
                const inputs = buildingItem.querySelectorAll(
                    '.new-building-number, .new-building-cost, .new-building-description');
                inputs.forEach(input => {
                    input.disabled = !enable;
                    if (!enable) {
                        input.value = '';
                        input.classList.remove('is-valid', 'is-invalid');
                    }
                });

                // Update total cost display
                if (!enable) {
                    buildingItem.querySelector('.new-building-total-cost').value = '0.00 ETB';
                } else {
                    updateNewBuildingTotalCost(buildingItem);
                }
            }

            function enableImprovementInputs(improvementItem, enable) {
                const inputs = improvementItem.querySelectorAll(
                    '.improvement-number, .improvement-cost, .improvement-description');
                inputs.forEach(input => {
                    input.disabled = !enable;
                    if (!enable) {
                        input.value = '';
                        input.classList.remove('is-valid', 'is-invalid');
                    }
                });

                // Update total cost display
                if (!enable) {
                    improvementItem.querySelector('.improvement-total-cost').value = '0.00 ETB';
                } else {
                    updateImprovementTotalCost(improvementItem);
                }
            }

            function updateNewBuildingTotalCost(buildingItem) {
                const number = buildingItem.querySelector('.new-building-number').value;
                const cost = buildingItem.querySelector('.new-building-cost').value;
                const totalCost = number && cost ? (parseFloat(number) * parseFloat(cost)).toFixed(2) : '0.00';
                buildingItem.querySelector('.new-building-total-cost').value = totalCost + ' ETB';
            }

            function updateImprovementTotalCost(improvementItem) {
                const number = improvementItem.querySelector('.improvement-number').value;
                const cost = improvementItem.querySelector('.improvement-cost').value;
                const totalCost = number && cost ? (parseFloat(number) * parseFloat(cost)).toFixed(2) : '0.00';
                improvementItem.querySelector('.improvement-total-cost').value = totalCost + ' ETB';
            }

            // Update new building data when inputs change
            document.addEventListener('input', (e) => {
                if (e.target.classList.contains('new-building-number') ||
                    e.target.classList.contains('new-building-cost') ||
                    e.target.classList.contains('new-building-description')) {

                    const buildingItem = e.target.closest('.new-building-item');
                    const institutionId = buildingItem.querySelector('.new-building-checkbox').value;
                    const institutionName = buildingItem.querySelector('.new-building-checkbox')
                        .getAttribute('data-institution-name');

                    if (newBuildingsData.has(institutionId)) {
                        const number = buildingItem.querySelector('.new-building-number').value;
                        const cost = buildingItem.querySelector('.new-building-cost').value;
                        const description = buildingItem.querySelector('.new-building-description').value;

                        newBuildingsData.set(institutionId, {
                            name: institutionName,
                            number: number,
                            cost: cost,
                            description: description
                        });

                        // Update total cost
                        updateNewBuildingTotalCost(buildingItem);

                        // Validation styling
                        validateNewBuildingInputs(buildingItem, number, cost, description);
                    }
                    updateFormState();
                    updateFinancialSummary();
                }
            });

            // Update improvement data when inputs change
            document.addEventListener('input', (e) => {
                if (e.target.classList.contains('improvement-number') ||
                    e.target.classList.contains('improvement-cost') ||
                    e.target.classList.contains('improvement-description')) {

                    const improvementItem = e.target.closest('.improvement-item');
                    const institutionId = improvementItem.querySelector('.improvement-checkbox').value;
                    const institutionName = improvementItem.querySelector('.improvement-checkbox')
                        .getAttribute('data-institution-name');

                    if (improvementsData.has(institutionId)) {
                        const number = improvementItem.querySelector('.improvement-number').value;
                        const cost = improvementItem.querySelector('.improvement-cost').value;
                        const description = improvementItem.querySelector('.improvement-description').value;

                        improvementsData.set(institutionId, {
                            name: institutionName,
                            number: number,
                            cost: cost,
                            description: description
                        });

                        // Update total cost
                        updateImprovementTotalCost(improvementItem);

                        // Validation styling
                        validateImprovementInputs(improvementItem, number, cost, description);
                    }
                    updateFormState();
                    updateFinancialSummary();
                }
            });

            function validateNewBuildingInputs(buildingItem, number, cost, description) {
                const numberInput = buildingItem.querySelector('.new-building-number');
                const costInput = buildingItem.querySelector('.new-building-cost');
                const descriptionInput = buildingItem.querySelector('.new-building-description');

                if (number && cost && description) {
                    numberInput.classList.remove('is-invalid');
                    numberInput.classList.add('is-valid');
                    costInput.classList.remove('is-invalid');
                    costInput.classList.add('is-valid');
                    descriptionInput.classList.remove('is-invalid');
                    descriptionInput.classList.add('is-valid');
                } else {
                    if (!number) {
                        numberInput.classList.remove('is-valid');
                        numberInput.classList.add('is-invalid');
                    } else {
                        numberInput.classList.remove('is-invalid');
                        numberInput.classList.add('is-valid');
                    }

                    if (!cost) {
                        costInput.classList.remove('is-valid');
                        costInput.classList.add('is-invalid');
                    } else {
                        costInput.classList.remove('is-invalid');
                        costInput.classList.add('is-valid');
                    }

                    if (!description) {
                        descriptionInput.classList.remove('is-valid');
                        descriptionInput.classList.add('is-invalid');
                    } else {
                        descriptionInput.classList.remove('is-invalid');
                        descriptionInput.classList.add('is-valid');
                    }
                }
            }

            function validateImprovementInputs(improvementItem, number, cost, description) {
                const numberInput = improvementItem.querySelector('.improvement-number');
                const costInput = improvementItem.querySelector('.improvement-cost');
                const descriptionInput = improvementItem.querySelector('.improvement-description');

                if (number && cost && description) {
                    numberInput.classList.remove('is-invalid');
                    numberInput.classList.add('is-valid');
                    costInput.classList.remove('is-invalid');
                    costInput.classList.add('is-valid');
                    descriptionInput.classList.remove('is-invalid');
                    descriptionInput.classList.add('is-valid');
                } else {
                    if (!number) {
                        numberInput.classList.remove('is-valid');
                        numberInput.classList.add('is-invalid');
                    } else {
                        numberInput.classList.remove('is-invalid');
                        numberInput.classList.add('is-valid');
                    }

                    if (!cost) {
                        costInput.classList.remove('is-valid');
                        costInput.classList.add('is-invalid');
                    } else {
                        costInput.classList.remove('is-invalid');
                        costInput.classList.add('is-valid');
                    }

                    if (!description) {
                        descriptionInput.classList.remove('is-valid');
                        descriptionInput.classList.add('is-invalid');
                    } else {
                        descriptionInput.classList.remove('is-invalid');
                        descriptionInput.classList.add('is-valid');
                    }
                }
            }

            function updateFinancialSummary() {
                let newBuildingsTotal = 0;
                let improvementsTotal = 0;
                let validNewBuildingsCount = 0;
                let validImprovementsCount = 0;

                // Calculate new buildings totals
                newBuildingsData.forEach((building, institutionId) => {
                    if (building.number && building.cost && building.description) {
                        newBuildingsTotal += parseFloat(building.number) * parseFloat(building.cost);
                        validNewBuildingsCount++;
                    }
                });

                // Calculate improvements totals
                improvementsData.forEach((improvement, institutionId) => {
                    if (improvement.number && improvement.cost && improvement.description) {
                        improvementsTotal += parseFloat(improvement.number) * parseFloat(improvement.cost);
                        validImprovementsCount++;
                    }
                });

                const grandTotal = newBuildingsTotal + improvementsTotal;
                const totalProjects = validNewBuildingsCount + validImprovementsCount;

                // Update displays
                document.getElementById('newBuildingsTotal').textContent = newBuildingsTotal.toLocaleString(
                'en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';

                document.getElementById('improvementsTotal').textContent = improvementsTotal.toLocaleString(
                'en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';

                document.getElementById('grandTotal').textContent = grandTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';

                document.getElementById('newBuildingsCount').textContent =
                    `${validNewBuildingsCount} building${validNewBuildingsCount !== 1 ? 's' : ''}`;

                document.getElementById('improvementsCount').textContent =
                    `${validImprovementsCount} improvement${validImprovementsCount !== 1 ? 's' : ''}`;

                document.getElementById('totalProjects').textContent =
                    `${totalProjects} total project${totalProjects !== 1 ? 's' : ''}`;
            }

            function updateFormState() {
                const hasYear = yearSelect.value;

                // Check if at least one new building or improvement has all required fields
                let hasValidNewBuildings = false;
                let hasValidImprovements = false;

                newBuildingsData.forEach(building => {
                    if (building.number && building.cost && building.description) {
                        hasValidNewBuildings = true;
                    }
                });

                improvementsData.forEach(improvement => {
                    if (improvement.number && improvement.cost && improvement.description) {
                        hasValidImprovements = true;
                    }
                });

                const hasData = hasValidNewBuildings || hasValidImprovements;

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
                    if (building.number && building.cost && building.description) {
                        allNewBuildings.push({
                            name: building.name,
                            number: parseInt(building.number),
                            cost: parseFloat(building.cost),
                            description: building.description
                        });
                    }
                });

                // Prepare improvements data
                const allImprovements = [];
                improvementsData.forEach((improvement, institutionId) => {
                    if (improvement.number && improvement.cost && improvement.description) {
                        allImprovements.push({
                            name: improvement.name,
                            number: parseInt(improvement.number),
                            cost: parseFloat(improvement.cost),
                            description: improvement.description
                        });
                    }
                });

                newBuildingDataInput.value = JSON.stringify(allNewBuildings);
                improvementDataInput.value = JSON.stringify(allImprovements);
            });

            // Initialize with old values if validation failed
            function initializeOldValues() {
                @if (old('new_building_data'))
                    try {
                        const oldNewBuildings = @json(json_decode(old('new_building_data'), true) ?? []);
                        oldNewBuildings.forEach(building => {
                            // Find the checkbox for this institution by name
                            const checkboxes = document.querySelectorAll('.new-building-checkbox');
                            checkboxes.forEach(checkbox => {
                                if (checkbox.getAttribute('data-institution-name') === building
                                    .name) {
                                    checkbox.checked = true;
                                    checkbox.dispatchEvent(new Event('change'));

                                    const buildingItem = checkbox.closest('.new-building-item');
                                    buildingItem.querySelector('.new-building-number').value =
                                        building.number || '';
                                    buildingItem.querySelector('.new-building-cost').value =
                                        building.cost || '';
                                    buildingItem.querySelector('.new-building-description').value =
                                        building.description || '';

                                    // Update data store
                                    newBuildingsData.set(checkbox.value, {
                                        name: building.name,
                                        number: building.number || '',
                                        cost: building.cost || '',
                                        description: building.description || ''
                                    });
                                }
                            });
                        });
                    } catch (e) {
                        console.error('Error initializing old new building values:', e);
                    }
                @endif

                @if (old('improvement_data'))
                    try {
                        const oldImprovements = @json(json_decode(old('improvement_data'), true) ?? []);
                        oldImprovements.forEach(improvement => {
                            // Find the checkbox for this institution by name
                            const checkboxes = document.querySelectorAll('.improvement-checkbox');
                            checkboxes.forEach(checkbox => {
                                if (checkbox.getAttribute('data-institution-name') === improvement
                                    .name) {
                                    checkbox.checked = true;
                                    checkbox.dispatchEvent(new Event('change'));

                                    const improvementItem = checkbox.closest('.improvement-item');
                                    improvementItem.querySelector('.improvement-number').value =
                                        improvement.number || '';
                                    improvementItem.querySelector('.improvement-cost').value =
                                        improvement.cost || '';
                                    improvementItem.querySelector('.improvement-description')
                                        .value = improvement.description || '';

                                    // Update data store
                                    improvementsData.set(checkbox.value, {
                                        name: improvement.name,
                                        number: improvement.number || '',
                                        cost: improvement.cost || '',
                                        description: improvement.description || ''
                                    });
                                }
                            });
                        });
                    } catch (e) {
                        console.error('Error initializing old improvement values:', e);
                    }
                @endif

                updateFormState();
                updateFinancialSummary();
            }

            initializeOldValues();
        });
    </script>

    <style>
        .new-building-item,
        .improvement-item {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .new-building-item:hover,
        .improvement-item:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
        }

        .new-building-item.checked {
            border-color: #0dcaf0;
            background-color: #f0f9ff;
        }

        .improvement-item.checked {
            border-color: #ffc107;
            background-color: #fffbf0;
        }

        .form-check-input:checked~.form-check-label {
            font-weight: bold;
        }

        .new-building-checkbox:checked~.form-check-label {
            color: #0dcaf0;
        }

        .improvement-checkbox:checked~.form-check-label {
            color: #ffc107;
        }

        .is-valid {
            border-color: #198754 !important;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        .btn:disabled {
            cursor: not-allowed;
        }

        .card-header {
            border-bottom: none;
        }

        .alert {
            border: none;
            border-radius: 8px;
        }

        .new-building-fields,
        .improvement-fields {
            border-left: 3px solid;
            padding-left: 12px;
        }

        .new-building-fields {
            border-left-color: #0dcaf0 !important;
        }

        .improvement-fields {
            border-left-color: #ffc107 !important;
        }

        /* Style for disabled institutions */
        .new-building-item.disabled,
        .improvement-item.disabled {
            opacity: 0.6;
            background-color: #f8f9fa;
            cursor: not-allowed;
        }

        .new-building-item.disabled:hover,
        .improvement-item.disabled:hover {
            transform: none;
            background-color: #f8f9fa;
        }

        .form-check-input:disabled {
            cursor: not-allowed;
        }

        .form-check-input:disabled~.form-check-label {
            color: #6c757d;
            cursor: not-allowed;
        }

        .badge.bg-secondary {
            font-size: 0.6rem;
            padding: 0.25em 0.4em;
        }
    </style>
</x-layout>
