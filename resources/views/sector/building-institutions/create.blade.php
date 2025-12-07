<x-layout>
    @section('title', 'Create Building Community Institution Plan')
    
    <div class="content-header">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="text-primary">
                            <i class="fas fa-building mr-2"></i>
                            Create Building Community Institution Plan
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
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

                <form action="{{ route('sector.building-community-institution-plans.store') }}" method="POST" id="mainForm">
                    @csrf

                    <!-- Year Selection -->
                    <div class="card card-success mb-4">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Year Selection
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label font-weight-semibold">Year <span class="text-danger">*</span></label>
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
                    <div class="alert alert-warning" id="existingPlansAlert" style="display: none;">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span id="existingPlansMessage"></span>
                        <a href="{{ route('sector.building-community-institution-plans.index') }}" class="alert-link ml-2">
                            View existing plans
                        </a>
                    </div>

                    <!-- New Building Data -->
                    <div class="card card-info mb-4" id="newBuildingCard">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">
                                <i class="fas fa-plus-circle mr-2"></i>New Building Construction
                            </h3>
                            <span class="badge badge-warning" id="newBuildingStatus" style="display: none;">
                                <i class="fas fa-info-circle mr-1"></i>
                                <span id="newBuildingStatusText"></span>
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="callout callout-info mb-4">
                                <h5><i class="fas fa-info-circle mr-2"></i> Required Information</h5>
                                <p class="mb-0">Select institutions for new building construction. Institutions that already have data for the
                                    selected year are disabled.</p>
                            </div>

                            <div class="row" id="newBuildingsContainer">
                                @foreach ($institutions as $institution)
                                    <div class="col-md-6 mb-3">
                                        <div class="card new-building-item">
                                            <div class="card-body">
                                                <div class="custom-control custom-checkbox mb-3">
                                                    <input class="custom-control-input new-building-checkbox" 
                                                           type="checkbox"
                                                           value="{{ $institution->id }}" 
                                                           id="new-building-{{ $institution->id }}"
                                                           data-institution-name="{{ $institution->name }}">
                                                    <label class="custom-control-label font-weight-semibold"
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
                                                            <label class="form-label small font-weight-semibold text-info">
                                                                <i class="fas fa-building mr-1"></i>
                                                                Building: {{ $institution->name }}
                                                            </label>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label small">Number of Buildings <span class="text-danger">*</span></label>
                                                                <input type="number" class="form-control new-building-number" 
                                                                       placeholder="0" min="0" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label small">Cost per Building (ETB) <span class="text-danger">*</span></label>
                                                                <input type="number" step="0.01" class="form-control new-building-cost" 
                                                                       placeholder="0.00" min="0" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label small">Total Cost (ETB)</label>
                                                                <input type="text" class="form-control new-building-total-cost" 
                                                                       placeholder="0.00" readonly style="background-color: #f8f9fa; font-size: 0.8rem;">
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label class="form-label small">Project Description <span class="text-danger">*</span></label>
                                                                <textarea class="form-control new-building-description" rows="2"
                                                                          placeholder="Describe the construction project..." 
                                                                          style="font-size: 0.8rem;" disabled></textarea>
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
                                <div class="alert alert-danger mt-3">{{ $message }}</div>
                            @enderror
                            <input type="hidden" name="new_building_data" id="new_building_data">
                        </div>
                    </div>

                    <!-- Summary Card -->
                    <div class="card card-secondary mb-4">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calculator mr-2"></i>
                                Financial Summary
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <div class="border rounded p-3 bg-light">
                                        <h6 class="text-info mb-1">New Construction Total</h6>
                                        <h4 class="text-info mb-0" id="newBuildingsTotal">0.00 ETB</h4>
                                        <small class="text-muted" id="newBuildingsCount">0 buildings</small>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
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

                    <!-- Buttons -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="float-right">
                                        <a href="{{ route('sector.building-community-institution-plans.index') }}" class="btn btn-default mr-2">
                                            <i class="fas fa-arrow-left mr-2"></i>Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary px-4" id="submitBtn" disabled>
                                            <i class="fas fa-save mr-2"></i>Save Building Plan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const yearSelect = document.getElementById('yearSelect');
            const submitBtn = document.getElementById('submitBtn');
            const newBuildingDataInput = document.getElementById('new_building_data');
            const existingPlansAlert = document.getElementById('existingPlansAlert');
            const existingPlansMessage = document.getElementById('existingPlansMessage');
            const newBuildingStatus = document.getElementById('newBuildingStatus');
            const newBuildingStatusText = document.getElementById('newBuildingStatusText');

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
                document.querySelectorAll('.new-building-checkbox').forEach(checkbox => {
                    if (existingInstitutionIds.includes(checkbox.value)) {
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
                        newBuildingsData.delete(checkbox.value);
                        improvementsData.delete(checkbox.value);

                        // Add disabled styling to the card
                        const card = checkbox.closest('.new-building-item');
                        card.classList.add('disabled');
                        card.style.cursor = 'not-allowed';

                        // Add disabled badge
                        const label = checkbox.closest('.custom-control').querySelector('.custom-control-label');
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
                            `${checkbox.getAttribute('data-institution-name')} <span class="badge badge-secondary ml-1" style="font-size: 0.6rem;">${badgeText}</span>`;
                    } else {
                        checkbox.disabled = false;
                        checkbox.parentElement.style.opacity = '1';

                        // Remove disabled styling
                        const card = checkbox.closest('.new-building-item');
                        card.classList.remove('disabled');
                        card.style.cursor = 'pointer';

                        // Remove disabled badge
                        const label = checkbox.closest('.custom-control').querySelector('.custom-control-label');
                        label.textContent = checkbox.getAttribute('data-institution-name');
                    }
                });

                // Show status badges on card headers
                const newBuildingCount = plans.filter(plan => plan.new_building_data && plan.new_building_data
                    .length > 0).length;

                if (newBuildingCount > 0) {
                    newBuildingStatus.style.display = 'inline-block';
                    newBuildingStatusText.textContent = `${newBuildingCount} institutions disabled`;
                }
            }

            function enableAllInstitutions() {
                document.querySelectorAll('.new-building-checkbox').forEach(checkbox => {
                    checkbox.disabled = false;
                    checkbox.parentElement.style.opacity = '1';

                    // Remove disabled styling
                    const card = checkbox.closest('.new-building-item');
                    card.classList.remove('disabled');
                    card.style.cursor = 'pointer';

                    // Remove disabled badge
                    const label = checkbox.closest('.custom-control').querySelector('.custom-control-label');
                    label.textContent = checkbox.getAttribute('data-institution-name');
                });

                newBuildingStatus.style.display = 'none';
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

            function updateNewBuildingTotalCost(buildingItem) {
                const number = buildingItem.querySelector('.new-building-number').value;
                const cost = buildingItem.querySelector('.new-building-cost').value;
                const totalCost = number && cost ? (parseFloat(number) * parseFloat(cost)).toFixed(2) : '0.00';
                buildingItem.querySelector('.new-building-total-cost').value = totalCost + ' ETB';
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

            function updateFinancialSummary() {
                let newBuildingsTotal = 0;
                let validNewBuildingsCount = 0;

                // Calculate new buildings totals
                newBuildingsData.forEach((building, institutionId) => {
                    if (building.number && building.cost && building.description) {
                        newBuildingsTotal += parseFloat(building.number) * parseFloat(building.cost);
                        validNewBuildingsCount++;
                    }
                });

                // Update displays
                document.getElementById('newBuildingsTotal').textContent = newBuildingsTotal.toLocaleString(
                'en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';

                document.getElementById('improvementsTotal').textContent = '0.00 ETB';
                document.getElementById('grandTotal').textContent = newBuildingsTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';

                document.getElementById('newBuildingsCount').textContent =
                    `${validNewBuildingsCount} building${validNewBuildingsCount !== 1 ? 's' : ''}`;

                document.getElementById('improvementsCount').textContent = '0 improvements';
                document.getElementById('totalProjects').textContent =
                    `${validNewBuildingsCount} total project${validNewBuildingsCount !== 1 ? 's' : ''}`;
            }

            function updateFormState() {
                const hasYear = yearSelect.value;

                // Check if at least one new building has all required fields
                let hasValidNewBuildings = false;

                newBuildingsData.forEach(building => {
                    if (building.number && building.cost && building.description) {
                        hasValidNewBuildings = true;
                    }
                });

                submitBtn.disabled = !(hasYear && hasValidNewBuildings);

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

                newBuildingDataInput.value = JSON.stringify(allNewBuildings);
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
            border: 1px solid #dee2e6;
        }

        .new-building-item:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .new-building-item.checked {
            border-color: #17a2b8;
            background-color: #f0f9ff;
        }

        .custom-control-input:checked ~ .custom-control-label {
            font-weight: bold;
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
            opacity: 0.65;
        }

        .card-header {
            background-color: #17a2b8;
            color: white;
        }

        .card-header .card-title {
            color: white;
        }

        .card-info:not(.card-outline) > .card-header {
            background-color: #17a2b8;
            color: white;
        }

        .card-secondary:not(.card-outline) > .card-header {
            background-color: #6c757d;
            color: white;
        }

        .new-building-fields {
            border-left: 3px solid #17a2b8;
            padding-left: 15px;
            margin-left: -5px;
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

        .custom-control-input:disabled {
            cursor: not-allowed;
        }

        .custom-control-input:disabled ~ .custom-control-label {
            color: #6c757d;
            cursor: not-allowed;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }

        .ml-1 {
            margin-left: 0.25rem !important;
        }

        .ml-2 {
            margin-left: 0.5rem !important;
        }

        .custom-checkbox .custom-control-label::before {
            border-radius: .25rem;
        }
        
        .custom-checkbox .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
    </style>
</x-layout>