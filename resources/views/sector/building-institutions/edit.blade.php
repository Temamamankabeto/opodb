<x-layout>
    <div class="container py-5">
        <h2 class="mb-4">
            <i class="bi bi-pencil-square text-primary me-2"></i>
            Edit Building Community Institution Plan
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

        <form action="{{ route('sector.building-community-institution-plans.update', $plan->id) }}" method="POST"
            id="mainForm">
            @csrf
            @method('PUT')

            <!-- Hidden JSON fields -->
            <input type="hidden" name="new_building_data" id="newBuildingDataJson">
            <input type="hidden" name="improvement_data" id="improvementDataJson">

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
                                    <option value="{{ $year }}"
                                        {{ old('year', $plan->year) == $year ? 'selected' : '' }}>
                                        {{ $year }} EC
                                    </option>
                                @endforeach
                            </select>
                            @error('year')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Institution <span class="text-danger">*</span></label>
                            <select name="institution_id" class="form-select" id="institutionSelect" required>
                                <option value="">-- Select Institution --</option>
                                @foreach ($institutions as $institution)
                                    <option value="{{ $institution->id }}"
                                        {{ old('institution_id', $plan->institution_id) == $institution->id ? 'selected' : '' }}>
                                        {{ $institution->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('institution_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Building Data -->
            <div class="card border-0 shadow-sm mb-4" id="newBuildingCard">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>New Building Construction</h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info py-2 mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        Edit new building construction data for the selected institution.
                    </div>

                    <div id="newBuildingsContainer">
                        @if (!empty($plan->new_building_data) && count($plan->new_building_data) > 0)
                            @foreach ($plan->new_building_data as $index => $building)
                                <div class="card border mb-3 new-building-item" data-index="{{ $index }}">
                                    <div class="card-body">
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <label class="form-label small fw-semibold text-primary">
                                                    <i class="bi bi-building me-1"></i>
                                                    Building Entry #{{ $index + 1 }}
                                                </label>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small">Number of Buildings <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" class="form-control new-building-number"
                                                    value="{{ old("new_building_data.$index.number", $building['number'] ?? '') }}"
                                                    placeholder="0" min="0" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small">Cost per Building (ETB) <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" step="0.01"
                                                    class="form-control new-building-cost"
                                                    value="{{ old("new_building_data.$index.cost", $building['cost'] ?? '') }}"
                                                    placeholder="0.00" min="0" required>
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
                                                    placeholder="Describe the construction project..." style="font-size: 0.8rem;" required>{{ old("new_building_data.$index.description", $building['description'] ?? '') }}</textarea>
                                            </div>
                                            @if ($index > 0)
                                                <div class="col-12 text-end">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger remove-new-building">
                                                        <i class="bi bi-trash me-1"></i>Remove Building
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="card border mb-3 new-building-item" data-index="0">
                                <div class="card-body">
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <label class="form-label small fw-semibold text-primary">
                                                <i class="bi bi-building me-1"></i>
                                                Building Entry #1
                                            </label>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Number of Buildings <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control new-building-number"
                                                value="{{ old('new_building_data.0.number', '') }}" placeholder="0"
                                                min="0" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Cost per Building (ETB) <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" step="0.01"
                                                class="form-control new-building-cost"
                                                value="{{ old('new_building_data.0.cost', '') }}" placeholder="0.00"
                                                min="0" required>
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
                                                placeholder="Describe the construction project..." style="font-size: 0.8rem;" required>{{ old('new_building_data.0.description', '') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addNewBuilding">
                            <i class="bi bi-plus-circle me-1"></i>Add Another Building
                        </button>
                    </div>

                    @error('new_building_data')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Improvement Data -->
            <div class="card border-0 shadow-sm mb-4" id="improvementCard">
                <div class="card-header bg-warning text-dark py-3">
                    <h5 class="mb-0"><i class="bi bi-tools me-2"></i>Existing Building Improvements</h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info py-2 mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        Edit improvement projects on existing buildings for the selected institution.
                    </div>

                    <div id="improvementsContainer">
                        @if (!empty($plan->improvement_data) && count($plan->improvement_data) > 0)
                            @foreach ($plan->improvement_data as $index => $improvement)
                                <div class="card border mb-3 improvement-item" data-index="{{ $index }}">
                                    <div class="card-body">
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <label class="form-label small fw-semibold text-warning">
                                                    <i class="bi bi-tools me-1"></i>
                                                    Improvement Entry #{{ $index + 1 }}
                                                </label>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small">Number of Improvements <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" class="form-control improvement-number"
                                                    value="{{ old("improvement_data.$index.number", $improvement['number'] ?? '') }}"
                                                    placeholder="0" min="0" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small">Cost per Improvement (ETB) <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" step="0.01"
                                                    class="form-control improvement-cost"
                                                    value="{{ old("improvement_data.$index.cost", $improvement['cost'] ?? '') }}"
                                                    placeholder="0.00" min="0" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small">Total Cost (ETB)</label>
                                                <input type="text" class="form-control improvement-total-cost"
                                                    placeholder="0.00" readonly
                                                    style="background-color: #f8f9fa; font-size: 0.8rem;">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small">Improvement Description <span
                                                        class="text-danger">*</span></label>
                                                <textarea class="form-control improvement-description" rows="2" placeholder="Describe the improvement work..."
                                                    style="font-size: 0.8rem;" required>{{ old("improvement_data.$index.description", $improvement['description'] ?? '') }}</textarea>
                                            </div>
                                            @if ($index > 0)
                                                <div class="col-12 text-end">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger remove-improvement">
                                                        <i class="bi bi-trash me-1"></i>Remove Improvement
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="card border mb-3 improvement-item" data-index="0">
                                <div class="card-body">
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <label class="form-label small fw-semibold text-warning">
                                                <i class="bi bi-tools me-1"></i>
                                                Improvement Entry #1
                                            </label>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Number of Improvements <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control improvement-number"
                                                value="{{ old('improvement_data.0.number', '') }}" placeholder="0"
                                                min="0" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Cost per Improvement (ETB) <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" step="0.01"
                                                class="form-control improvement-cost"
                                                value="{{ old('improvement_data.0.cost', '') }}" placeholder="0.00"
                                                min="0" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Total Cost (ETB)</label>
                                            <input type="text" class="form-control improvement-total-cost"
                                                placeholder="0.00" readonly
                                                style="background-color: #f8f9fa; font-size: 0.8rem;">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small">Improvement Description <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control improvement-description" rows="2" placeholder="Describe the improvement work..."
                                                style="font-size: 0.8rem;" required>{{ old('improvement_data.0.description', '') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-sm btn-outline-warning" id="addImprovement">
                            <i class="bi bi-plus-circle me-1"></i>Add Another Improvement
                        </button>
                    </div>

                    @error('improvement_data')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

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
                <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                    <i class="bi bi-save me-2"></i>Update Building Plan
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let newBuildingIndex = {{ !empty($plan->new_building_data) ? count($plan->new_building_data) : 1 }};
            let improvementIndex = {{ !empty($plan->improvement_data) ? count($plan->improvement_data) : 1 }};

            // Add new building entry
            document.getElementById('addNewBuilding').addEventListener('click', () => {
                const container = document.getElementById('newBuildingsContainer');
                const newItem = document.createElement('div');
                newItem.className = 'card border mb-3 new-building-item';
                newItem.setAttribute('data-index', newBuildingIndex);

                newItem.innerHTML = `
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-12">
                        <label class="form-label small fw-semibold text-primary">
                            <i class="bi bi-building me-1"></i>
                            Building Entry #${newBuildingIndex + 1}
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Number of Buildings <span class="text-danger">*</span></label>
                        <input type="number" class="form-control new-building-number" 
                               placeholder="0" min="0" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Cost per Building (ETB) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control new-building-cost" 
                               placeholder="0.00" min="0" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Total Cost (ETB)</label>
                        <input type="text" class="form-control new-building-total-cost" 
                               placeholder="0.00" readonly style="background-color: #f8f9fa; font-size: 0.8rem;">
                    </div>
                    <div class="col-12">
                        <label class="form-label small">Project Description <span class="text-danger">*</span></label>
                        <textarea class="form-control new-building-description" 
                                  rows="2" 
                                  placeholder="Describe the construction project..." 
                                  style="font-size: 0.8rem;" required></textarea>
                    </div>
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-new-building">
                            <i class="bi bi-trash me-1"></i>Remove Building
                        </button>
                    </div>
                </div>
            </div>
        `;

                container.appendChild(newItem);
                newBuildingIndex++;
                attachEventListeners();
                updateFinancialSummary();
            });

            // Add new improvement entry
            document.getElementById('addImprovement').addEventListener('click', () => {
                const container = document.getElementById('improvementsContainer');
                const newItem = document.createElement('div');
                newItem.className = 'card border mb-3 improvement-item';
                newItem.setAttribute('data-index', improvementIndex);

                newItem.innerHTML = `
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-12">
                        <label class="form-label small fw-semibold text-warning">
                            <i class="bi bi-tools me-1"></i>
                            Improvement Entry #${improvementIndex + 1}
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Number of Improvements <span class="text-danger">*</span></label>
                        <input type="number" class="form-control improvement-number" 
                               placeholder="0" min="0" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Cost per Improvement (ETB) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control improvement-cost" 
                               placeholder="0.00" min="0" required>
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
                                  style="font-size: 0.8rem;" required></textarea>
                    </div>
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-improvement">
                            <i class="bi bi-trash me-1"></i>Remove Improvement
                        </button>
                    </div>
                </div>
            </div>
        `;

                container.appendChild(newItem);
                improvementIndex++;
                attachEventListeners();
                updateFinancialSummary();
            });

            // Remove building entry
            function attachEventListeners() {
                document.querySelectorAll('.remove-new-building').forEach(button => {
                    button.addEventListener('click', function() {
                        if (document.querySelectorAll('.new-building-item').length > 1) {
                            this.closest('.new-building-item').remove();
                            reindexBuildings();
                            updateFinancialSummary();
                        } else {
                            alert('At least one building entry is required.');
                        }
                    });
                });

                document.querySelectorAll('.remove-improvement').forEach(button => {
                    button.addEventListener('click', function() {
                        if (document.querySelectorAll('.improvement-item').length > 1) {
                            this.closest('.improvement-item').remove();
                            reindexImprovements();
                            updateFinancialSummary();
                        } else {
                            alert('At least one improvement entry is required.');
                        }
                    });
                });

                // Add input event listeners for calculations
                document.querySelectorAll('.new-building-number, .new-building-cost').forEach(input => {
                    input.addEventListener('input', updateBuildingTotalCost);
                });

                document.querySelectorAll('.improvement-number, .improvement-cost').forEach(input => {
                    input.addEventListener('input', updateImprovementTotalCost);
                });
            }

            function reindexBuildings() {
                document.querySelectorAll('.new-building-item').forEach((item, index) => {
                    item.setAttribute('data-index', index);
                    const label = item.querySelector('.form-label small');
                    label.innerHTML = `<i class="bi bi-building me-1"></i>Building Entry #${index + 1}`;
                });
                newBuildingIndex = document.querySelectorAll('.new-building-item').length;
            }

            function reindexImprovements() {
                document.querySelectorAll('.improvement-item').forEach((item, index) => {
                    item.setAttribute('data-index', index);
                    const label = item.querySelector('.form-label small');
                    label.innerHTML = `<i class="bi bi-tools me-1"></i>Improvement Entry #${index + 1}`;
                });
                improvementIndex = document.querySelectorAll('.improvement-item').length;
            }

            function updateBuildingTotalCost(e) {
                const buildingItem = e.target.closest('.new-building-item');
                const number = buildingItem.querySelector('.new-building-number').value;
                const cost = buildingItem.querySelector('.new-building-cost').value;
                const totalCost = number && cost ? (parseFloat(number) * parseFloat(cost)).toFixed(2) : '0.00';
                buildingItem.querySelector('.new-building-total-cost').value = totalCost + ' ETB';
                updateFinancialSummary();
            }

            function updateImprovementTotalCost(e) {
                const improvementItem = e.target.closest('.improvement-item');
                const number = improvementItem.querySelector('.improvement-number').value;
                const cost = improvementItem.querySelector('.improvement-cost').value;
                const totalCost = number && cost ? (parseFloat(number) * parseFloat(cost)).toFixed(2) : '0.00';
                improvementItem.querySelector('.improvement-total-cost').value = totalCost + ' ETB';
                updateFinancialSummary();
            }

            function updateFinancialSummary() {
                let newBuildingsTotal = 0;
                let improvementsTotal = 0;
                let validNewBuildingsCount = 0;
                let validImprovementsCount = 0;

                // Calculate new buildings totals
                document.querySelectorAll('.new-building-item').forEach(item => {
                    const number = item.querySelector('.new-building-number').value;
                    const cost = item.querySelector('.new-building-cost').value;
                    if (number && cost) {
                        newBuildingsTotal += parseFloat(number) * parseFloat(cost);
                        validNewBuildingsCount++;
                    }
                });

                // Calculate improvements totals
                document.querySelectorAll('.improvement-item').forEach(item => {
                    const number = item.querySelector('.improvement-number').value;
                    const cost = item.querySelector('.improvement-cost').value;
                    if (number && cost) {
                        improvementsTotal += parseFloat(number) * parseFloat(cost);
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

            // Form submission handler
            document.getElementById('mainForm').addEventListener('submit', function(e) {
                // Prevent default submission
                e.preventDefault();

                // Collect new building data
                const newBuildingData = [];
                document.querySelectorAll('.new-building-item').forEach((item, index) => {
                    const number = item.querySelector('.new-building-number').value;
                    const cost = item.querySelector('.new-building-cost').value;
                    const description = item.querySelector('.new-building-description').value;

                    if (number && cost && description) {
                        newBuildingData.push({
                            name: "{{ $plan->institution->name }}",
                            number: parseInt(number),
                            cost: parseFloat(cost),
                            description: description
                        });
                    }
                });

                // Collect improvement data
                const improvementData = [];
                document.querySelectorAll('.improvement-item').forEach((item, index) => {
                    const number = item.querySelector('.improvement-number').value;
                    const cost = item.querySelector('.improvement-cost').value;
                    const description = item.querySelector('.improvement-description').value;

                    if (number && cost && description) {
                        improvementData.push({
                            name: "{{ $plan->institution->name }}",
                            number: parseInt(number),
                            cost: parseFloat(cost),
                            description: description
                        });
                    }
                });

                // Validate at least one building entry
                if (newBuildingData.length === 0) {
                    alert('Please add at least one building entry.');
                    return;
                }

                // Set the JSON data to hidden fields
                document.getElementById('newBuildingDataJson').value = JSON.stringify(newBuildingData);
                document.getElementById('improvementDataJson').value = JSON.stringify(improvementData);

                // Now submit the form
                this.submit();
            });

            // Initialize calculations on page load
            function initializeCalculations() {
                document.querySelectorAll('.new-building-item').forEach(item => {
                    const number = item.querySelector('.new-building-number').value;
                    const cost = item.querySelector('.new-building-cost').value;
                    const totalCost = number && cost ? (parseFloat(number) * parseFloat(cost)).toFixed(2) :
                        '0.00';
                    item.querySelector('.new-building-total-cost').value = totalCost + ' ETB';
                });

                document.querySelectorAll('.improvement-item').forEach(item => {
                    const number = item.querySelector('.improvement-number').value;
                    const cost = item.querySelector('.improvement-cost').value;
                    const totalCost = number && cost ? (parseFloat(number) * parseFloat(cost)).toFixed(2) :
                        '0.00';
                    item.querySelector('.improvement-total-cost').value = totalCost + ' ETB';
                });

                updateFinancialSummary();
            }

            // Initialize everything
            attachEventListeners();
            initializeCalculations();
        });
    </script>

    <style>
        .new-building-item,
        .improvement-item {
            transition: all 0.3s ease;
        }

        .new-building-item:hover,
        .improvement-item:hover {
            background: #f8f9fa;
            transform: translateY(-1px);
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

        .is-valid {
            border-color: #198754 !important;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }
    </style>
</x-layout>
