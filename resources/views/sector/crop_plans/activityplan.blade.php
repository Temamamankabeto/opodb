<x-layout>
    <div class="container-fluid py-3 py-md-4 crop-plan-edit-container">
        <!-- Header Section -->
        <div class="row justify-content-center mb-4 mb-md-5">
            <div class="col-12 col-lg-10">
                <div class="page-header text-center">
                    <div class="header-icon mb-2 mb-md-3">
                        <i class="mdi mdi-sprout-edit display-4 text-warning"></i>
                    </div>
                    <h1 class="display-6 fw-bold text-warning mb-2">Edit Land Preparation Plan</h1>
                    <p class="lead text-muted d-none d-md-block">Update land preparation schedule for {{ Auth::user()->kebele->name }} Kebele</p>
                    <p class="text-muted d-md-none">Update land preparation schedule</p>
                    <div class="header-divider">
                        <div class="divider-line"></div>
                        <div class="divider-icon">
                            <i class="mdi mdi-leaf"></i>
                        </div>
                        <div class="divider-line"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="row justify-content-center mb-3 mb-md-4">
                <div class="col-12 col-lg-10">
                    <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-alert-circle-outline me-2 fs-5"></i>
                            <div class="flex-grow-1">
                                <h6 class="alert-heading mb-2">Please fix the following errors:</h6>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card form-card border-0 shadow-lg">
                    <div class="card-header bg-gradient-warning text-white py-3 py-md-4">
                        <h4 class="mb-0 fs-5 fs-md-4">
                            <i class="mdi mdi-calendar-edit me-2"></i>
                            Edit Land Preparation Planning
                        </h4>
                    </div>
                    <div class="card-body p-3 p-md-5">
                        <form action="{{ route('sector.crop-plans.update', $plan->id) }}" method="POST" id="cropPlanForm">
                            @csrf
                            @method('PUT')

                            <!-- Hidden fields for all required data -->
                            <input type="hidden" name="croptype_id" value="{{ $plan->croptype_id }}">
                            <input type="hidden" name="crop_id" value="{{ $plan->crop_id }}">
                            <input type="hidden" name="area_to_plant" value="{{ $plan->area_to_plant }}">
                            <input type="hidden" name="productivity" value="{{ $plan->productivity }}">
                            <input type="hidden" name="year" value="{{ $plan->year }}">
                            <input type="hidden" name="season" value="{{ $plan->season }}">
                            <input type="hidden" name="seed_required" value="{{ $plan->seed_required }}">
                            <input type="hidden" name="expected_yield" value="{{ $plan->expected_yield }}">

                            <!-- Basic Information Display (Read-only) -->
                            <div class="section-header mb-3 mb-md-4">
                                <h5 class="section-title fs-6 fs-md-5">
                                    <i class="mdi mdi-information-outline me-2 text-primary"></i>
                                    Crop Plan Information
                                </h5>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row g-2 g-md-4 mb-3 mb-md-4">
                                <div class="col-6 col-md-3">
                                    <div class="info-card p-2 p-md-3 bg-light rounded">
                                        <small class="text-muted">Crop Type</small>
                                        <div class="fw-bold text-primary fs-6 fs-md-normal">{{ $plan->cropType->name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="info-card p-2 p-md-3 bg-light rounded">
                                        <small class="text-muted">Crop</small>
                                        <div class="fw-bold text-primary fs-6 fs-md-normal">{{ $plan->crop->name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="info-card p-2 p-md-3 bg-light rounded">
                                        <small class="text-muted">Year</small>
                                        <div class="fw-bold text-primary fs-6 fs-md-normal">{{ $plan->year }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="info-card p-2 p-md-3 bg-light rounded">
                                        <small class="text-muted">Season</small>
                                        <div class="fw-bold text-primary fs-6 fs-md-normal">{{ $plan->season }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="info-card p-2 p-md-3 bg-light rounded">
                                        <small class="text-muted">Area</small>
                                        <div class="fw-bold text-primary fs-6 fs-md-normal">{{ $plan->area_to_plant }} ha</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Planning Summary -->
                            <div class="section-header mt-4 mt-md-5 mb-3 mb-md-4">
                                <h5 class="section-title fs-6 fs-md-5">
                                    <i class="mdi mdi-chart-bar me-2 text-info"></i>
                                    Planning Overview (Preparation Year: <span id="preparationYear">{{ $plan->year - 1 }}</span>)
                                </h5>
                                <div class="section-divider"></div>
                            </div>

                            <!-- Summary Cards -->
                            <div class="row g-2 g-md-3 mb-4">
                                <div class="col-6 col-md-4">
                                    <div class="card summary-card border-0 shadow-sm">
                                        <div class="card-body text-center">
                                            <i class="mdi mdi-target display-6 text-primary mb-2"></i>
                                            <div class="summary-value fs-4 fw-bold text-primary" id="totalAreaDisplay">{{ $plan->area_to_plant }}</div>
                                            <div class="summary-label text-muted">Total Area (ha)</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="card summary-card border-0 shadow-sm">
                                        <div class="card-body text-center">
                                            <i class="mdi mdi-calendar-check display-6 text-warning mb-2"></i>
                                            <div class="summary-value fs-4 fw-bold text-warning" id="plannedAreaSummary">0</div>
                                            <div class="summary-label text-muted">Planned (ha)</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="card summary-card border-0 shadow-sm">
                                        <div class="card-body text-center">
                                            <i class="mdi mdi-progress-check display-6 text-info mb-2"></i>
                                            <div class="summary-value fs-4 fw-bold text-info" id="completionPercentage">0%</div>
                                            <div class="summary-label text-muted">Allocation</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Land Preparation Planning Section -->
                            <div class="section-header mt-4 mt-md-5 mb-3 mb-md-4">
                                <h5 class="section-title fs-6 fs-md-5">
                                    <i class="mdi mdi-calendar-multiple me-2 text-info"></i>
                                    Land Preparation Planning
                                </h5>
                                <div class="section-divider"></div>
                                <p class="text-muted mt-2 d-none d-md-block">
                                    Plan land preparation activities for each week in the preparation year ({{ $plan->year - 1 }}).
                                </p>
                            </div>

                            <div class="activity-planning-container">
                                <!-- Preparation Year Section -->
                                <div class="card mb-3 mb-md-4 border-0 shadow-sm">
                                    <div class="card-header bg-light py-2 py-md-3">
                                        <div class="d-flex justify-content-between align-items-center flex-column flex-md-row">
                                            <h6 class="mb-1 mb-md-0 fs-6">
                                                <i class="mdi mdi-calendar-range me-2 text-success"></i>
                                                Preparation Year: {{ $plan->year - 1 }}
                                            </h6>
                                            <div class="mt-1 mt-md-0">
                                                <span class="badge bg-success fs-6">Land Preparation Activities</span>
                                            </div>
                                        </div>
                                        <small class="text-muted d-none d-md-block">All land preparation activities occur in the year before planting ({{ $plan->year - 1 }})</small>
                                    </div>
                                </div>

                                <!-- Quarters Section -->
                                <div class="card mb-3 mb-md-4 border-0 shadow-sm">
                                    <div class="card-header bg-light py-2 py-md-3">
                                        <div class="d-flex justify-content-between align-items-center flex-column flex-md-row">
                                            <h6 class="mb-1 mb-md-0 fs-6">
                                                <i class="mdi mdi-chart-pie me-2 text-primary"></i>
                                                Select Quarters ({{ $plan->year - 1 }})
                                            </h6>
                                            <div class="mt-1 mt-md-0">
                                                <button type="button" class="btn btn-outline-primary btn-xs btn-sm" onclick="selectAllQuarters()">
                                                    <i class="mdi mdi-select-all me-1"></i> Select All
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary btn-xs btn-sm ms-1" onclick="clearAllQuarters()">
                                                    <i class="mdi mdi-select-off me-1"></i> Clear All
                                                </button>
                                            </div>
                                        </div>
                                        <small class="text-muted">Click on quarter cards to select/deselect multiple quarters</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2 g-md-3" id="quartersContainer">
                                            <!-- Quarters will be populated here -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Months Section -->
                                <div class="card mb-3 mb-md-4 border-0 shadow-sm" id="monthsSection" style="display: none;">
                                    <div class="card-header bg-light py-2 py-md-3">
                                        <div class="d-flex justify-content-between align-items-center flex-column flex-md-row">
                                            <h6 class="mb-1 mb-md-0 fs-6">
                                                <i class="mdi mdi-calendar-month me-2 text-info"></i>
                                                Select Months ({{ $plan->year - 1 }})
                                            </h6>
                                            <div class="mt-1 mt-md-0">
                                                <button type="button" class="btn btn-outline-info btn-xs btn-sm" onclick="selectAllMonths()">
                                                    <i class="mdi mdi-select-all me-1"></i> Select All
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary btn-xs btn-sm ms-1" onclick="clearAllMonths()">
                                                    <i class="mdi mdi-select-off me-1"></i> Clear All
                                                </button>
                                            </div>
                                        </div>
                                        <small class="text-muted">Select months from the chosen quarters</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2 g-md-3" id="monthsContainer">
                                            <!-- Months will be populated here -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Weeks Section -->
                                <div class="card mb-3 mb-md-4 border-0 shadow-sm" id="weeksSection" style="display: none;">
                                    <div class="card-header bg-light py-2 py-md-3">
                                        <div class="d-flex justify-content-between align-items-center flex-column flex-md-row">
                                            <h6 class="mb-1 mb-md-0 fs-6">
                                                <i class="mdi mdi-calendar-week me-2 text-warning"></i>
                                                Plan Distribution ({{ $plan->year - 1 }})
                                            </h6>
                                            <div class="mt-1 mt-md-0 text-center text-md-end">
                                                <small class="text-muted me-2 d-none d-md-inline">Total: </small>
                                                <span class="badge bg-primary fs-6" id="totalDistributedBadge">0 ha</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="weeksContainer">
                                            <!-- Weeks will be populated here -->
                                        </div>
                                        <div class="mt-2 mt-md-3 p-2 p-md-3 bg-light rounded">
                                            <div class="row align-items-center">
                                                <div class="col-md-8 mb-2 mb-md-0">
                                                    <small class="text-muted">
                                                        <i class="mdi mdi-information-outline me-1"></i>
                                                        Enter planned area for each week. Total allocation will be calculated automatically.
                                                    </small>
                                                </div>
                                                <div class="col-md-4 text-center text-md-end">
                                                    <button type="button" class="btn btn-success btn-sm w-100 w-md-auto" onclick="saveAllDistributions()">
                                                        <i class="mdi mdi-content-save-all me-1"></i> Save All
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Summary Section -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light py-2 py-md-3">
                                        <div class="d-flex justify-content-between align-items-center flex-column flex-md-row">
                                            <h6 class="mb-1 mb-md-0 fs-6">
                                                <i class="mdi mdi-chart-bar me-2 text-success"></i>
                                                Distribution Summary (Preparation Year: {{ $plan->year - 1 }})
                                            </h6>
                                            <div class="selected-summary mt-1 mt-md-0 text-center text-md-end">
                                                <small class="text-muted me-2">Selected: </small>
                                                <span class="badge bg-primary" id="selectedQuartersBadge">0 quarters</span>
                                                <span class="badge bg-info mx-1" id="selectedMonthsBadge">0 months</span>
                                                <span class="badge bg-warning" id="selectedWeeksBadge">0 weeks</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover">
                                                <thead class="table-light">
                                                <tr>
                                                    <th class="d-none d-md-table-cell">Year</th>
                                                    <th class="d-none d-md-table-cell">Quarter</th>
                                                    <th>Month</th>
                                                    <th>Week</th>
                                                    <th class="d-none d-md-table-cell">Date Range</th>
                                                    <th>Planned (ha)</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody id="summaryTable">
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted py-4">
                                                        <i class="mdi mdi-calendar-blank me-2"></i>
                                                        No distribution yet. Start by selecting quarters above.
                                                    </td>
                                                </tr>
                                                </tbody>
                                                <tfoot class="table-secondary">
                                                <tr>
                                                    <td colspan="5" class="text-end fw-bold d-none d-md-table-cell">Total:</td>
                                                    <td colspan="3" class="text-center fw-bold d-md-none">Total:</td>
                                                    <td class="fw-bold text-primary" id="summaryTotal">0 ha</td>
                                                    <td colspan="2" class="fw-bold text-info" id="summaryCompletion">0%</td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden field to store structured planning data -->
                            <input type="hidden" name="plandivision_details" id="plandivisionDetails"
                                   value="{{ json_encode($plan->plandivision_details ?? []) }}">

                            <!-- Form Actions -->
                            <div class="form-actions mt-4 mt-md-5 pt-3 pt-md-4 border-top">
                                <div class="d-flex justify-content-between align-items-center flex-column flex-md-row gap-3">
                                    <a href="{{ route('sector.crop-plans.index') }}" class="btn btn-outline-secondary btn-lg w-100 w-md-auto order-2 order-md-1">
                                        <i class="mdi mdi-arrow-left me-2"></i>
                                        Back to Plans
                                    </a>
                                    <div class="d-flex gap-2 gap-md-3 w-100 w-md-auto order-1 order-md-2">
                                        <a href="{{ route('sector.crop-plans.index') }}" class="btn btn-outline-danger btn-lg flex-fill">
                                            <i class="mdi mdi-cancel me-2"></i>
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-warning btn-lg flex-fill" id="submitBtn">
                                            <i class="mdi mdi-content-save-outline me-2"></i>
                                            Update Plan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let totalArea = parseFloat({{ $plan->area_to_plant }});
        let selectedQuarters = new Set();
        let selectedMonths = new Set();
        let weeklyDistributions = [];
        let currentDisplayedMonths = new Set();
        const preparationYear = {{ $plan->year - 1 }};

        // Ethiopian Calendar Data
        const EC_MONTHS = [
            { id: 1, name: "Meskerem", days: 30 },
            { id: 2, name: "Tikimt", days: 30 },
            { id: 3, name: "Hidar", days: 30 },
            { id: 4, name: "Tahsas", days: 30 },
            { id: 5, name: "Tir", days: 30 },
            { id: 6, name: "Yekatit", days: 30 },
            { id: 7, name: "Megabit", days: 30 },
            { id: 8, name: "Miyazia", days: 30 },
            { id: 9, name: "Ginbot", days: 30 },
            { id: 10, name: "Sene", days: 30 },
            { id: 11, name: "Hamle", days: 30 },
            { id: 12, name: "Nehase", days: 30 },
            { id: 13, name: "Pagumen", days: 5 }
        ];

        const EC_QUARTERS = {
            1: [1, 2, 3],
            2: [4, 5, 6],
            3: [7, 8, 9],
            4: [10, 11, 12, 13]
        };

        // Status calculation function
        function calculateStatus(planned) {
            if (planned === 0) return 'Not Planned';
            return 'Planned';
        }

        function getStatusClass(status) {
            switch(status) {
                case 'Not Planned': return 'bg-secondary';
                case 'Planned': return 'bg-success';
                default: return 'bg-secondary';
            }
        }

        // Initialize form
        $(document).ready(function() {
            console.log('Document ready - initializing land preparation planning for preparation year:', preparationYear);

            // Update the preparation year display
            $('#preparationYear').text(preparationYear);

            // Form submission validation
            $('#cropPlanForm').on('submit', function(e) {
                console.log('Form submitted, validating...');
                if (!validateForm()) {
                    e.preventDefault();
                    showAlert('Please fix the validation errors before submitting.', 'error');
                } else {
                    console.log('Form validation passed, submitting...');
                    saveJSON();
                }
            });

            renderQuarters();
            loadExistingDistribution();
            updateSummaryCards();

            // Mobile touch improvements
            improveMobileTouch();
        });

        function improveMobileTouch() {
            // Prevent zoom on input focus for better mobile experience
            $('input, select, textarea').on('focus', function() {
                $('meta[name=viewport]').attr('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0');
            });

            $('input, select, textarea').on('blur', function() {
                $('meta[name=viewport]').attr('content', 'width=device-width, initial-scale=1.0');
            });

            // Better touch targets for mobile
            if (window.innerWidth < 768) {
                $('.btn').addClass('py-2');
                $('.form-control').addClass('py-2');
            }
        }

        /* -------------------------------
           QUARTERS MANAGEMENT
        ------------------------------- */
        function renderQuarters() {
            $("#quartersContainer").empty();

            Object.keys(EC_QUARTERS).forEach(q => {
                const quarterData = calculateQuarterData(parseInt(q));
                const isSelected = selectedQuarters.has(parseInt(q));
                const quarterName = `Q${q}`;
                const monthNames = EC_QUARTERS[q].map(m => EC_MONTHS[m-1].name).join(", ");

                $("#quartersContainer").append(`
                <div class="col-6 col-md-3 mb-2 mb-md-3">
                    <div class="card quarter-card p-2 p-md-3 text-center ${isSelected ? 'card-selected' : ''}" data-q="${q}">
                        <div class="form-check mb-1 mb-md-2">
                            <input class="form-check-input quarter-checkbox" type="checkbox"
                                   id="quarter${q}" ${isSelected ? 'checked' : ''}>
                            <label class="form-check-label fw-bold fs-6 fs-md-5" for="quarter${q}">
                                ${quarterName}
                            </label>
                        </div>
                        <small class="text-muted d-none d-md-block">${monthNames}</small>
                        <small class="text-muted d-md-none">${EC_QUARTERS[q].length} months</small>
                        <div class="mt-1 mt-md-2">
                            <small class="text-primary">Planned: ${quarterData.planned.toFixed(2)} ha</small>
                        </div>
                        <div class="mt-1">
                            <small class="text-info">Year: ${preparationYear}</small>
                        </div>
                    </div>
                </div>
            `);
            });

            // Add event listeners
            $('.quarter-checkbox').on('change', function() {
                const quarterNum = parseInt($(this).closest('.quarter-card').data('q'));
                const isChecked = $(this).is(':checked');

                if (isChecked) {
                    selectedQuarters.add(quarterNum);
                    $(this).closest('.quarter-card').addClass('card-selected');
                } else {
                    selectedQuarters.delete(quarterNum);
                    $(this).closest('.quarter-card').removeClass('card-selected');
                    // Remove months from this quarter from selected months
                    const quarterMonths = EC_QUARTERS[quarterNum];
                    quarterMonths.forEach(month => {
                        selectedMonths.delete(month);
                        currentDisplayedMonths.delete(month);
                    });
                }

                renderMonths();
                updateBadges();
            });

            $(".quarter-card").click(function(e) {
                if (!$(e.target).is('input') && !$(e.target).is('label')) {
                    const checkbox = $(this).find('.quarter-checkbox');
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    checkbox.trigger('change');
                }
            });
        }

        function calculateQuarterData(quarter) {
            const quarterName = `Q${quarter}`;
            const quarterDistributions = weeklyDistributions.filter(dist => dist.quarter === quarterName);
            
            return {
                planned: quarterDistributions.reduce((total, dist) => total + (dist.planned_area || 0), 0)
            };
        }

        function selectAllQuarters() {
            Object.keys(EC_QUARTERS).forEach(q => {
                selectedQuarters.add(parseInt(q));
            });
            renderQuarters();
            renderMonths();
            updateBadges();
            showAlert('All quarters selected for preparation year ' + preparationYear, 'success');
        }

        function clearAllQuarters() {
            selectedQuarters.clear();
            selectedMonths.clear();
            currentDisplayedMonths.clear();
            renderQuarters();
            renderMonths();
            $('#weeksSection').hide();
            updateBadges();
            showAlert('All quarters cleared', 'info');
        }

        /* -------------------------------
           MONTHS MANAGEMENT
        ------------------------------- */
        function renderMonths() {
            $("#monthsContainer").empty();

            if (selectedQuarters.size === 0) {
                $("#monthsSection").hide();
                $("#weeksSection").hide();
                return;
            }

            $("#monthsSection").show();

            // Get all months from selected quarters
            let allMonths = [];
            selectedQuarters.forEach(quarter => {
                allMonths = allMonths.concat(EC_QUARTERS[quarter]);
            });

            // Remove duplicates and sort
            allMonths = [...new Set(allMonths)].sort((a, b) => a - b);

            allMonths.forEach(monthNum => {
                const month = EC_MONTHS[monthNum - 1];
                const monthData = calculateMonthData(monthNum);
                const isSelected = selectedMonths.has(monthNum);

                $("#monthsContainer").append(`
                <div class="col-6 col-md-4 mb-2 mb-md-3">
                    <div class="card month-card p-2 p-md-3 text-center ${isSelected ? 'card-selected' : ''}" data-month="${monthNum}">
                        <div class="form-check mb-1 mb-md-2">
                            <input class="form-check-input month-checkbox" type="checkbox"
                                   id="month${monthNum}" ${isSelected ? 'checked' : ''}>
                            <label class="form-check-label fw-bold fs-6" for="month${monthNum}">
                                ${month.name}
                            </label>
                        </div>
                        <small class="text-muted">Q${findQuarter(monthNum)}</small>
                        <div class="mt-1 mt-md-2">
                            <small class="text-primary">Planned: ${monthData.planned.toFixed(2)} ha</small>
                        </div>
                        <div class="mt-1">
                            <small class="text-info">Year: ${preparationYear}</small>
                        </div>
                    </div>
                </div>
            `);
            });

            // Add event listeners
            $('.month-checkbox').on('change', function() {
                const monthNum = parseInt($(this).closest('.month-card').data('month'));
                const isChecked = $(this).is(':checked');

                if (isChecked) {
                    selectedMonths.add(monthNum);
                    $(this).closest('.month-card').addClass('card-selected');
                } else {
                    selectedMonths.delete(monthNum);
                    $(this).closest('.month-card').removeClass('card-selected');
                    currentDisplayedMonths.delete(monthNum);
                }

                renderWeeks();
                updateBadges();
            });

            $(".month-card").click(function(e) {
                if (!$(e.target).is('input') && !$(e.target).is('label')) {
                    const checkbox = $(this).find('.month-checkbox');
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    checkbox.trigger('change');
                }
            });
        }

        function calculateMonthData(monthNum) {
            const monthName = EC_MONTHS[monthNum - 1].name;
            const monthDistributions = weeklyDistributions.filter(dist => dist.month === monthName);
            
            return {
                planned: monthDistributions.reduce((total, dist) => total + (dist.planned_area || 0), 0)
            };
        }

        function selectAllMonths() {
            if (selectedQuarters.size === 0) {
                showAlert('Please select quarters first', 'warning');
                return;
            }

            // Get all months from selected quarters
            let allMonths = [];
            selectedQuarters.forEach(quarter => {
                allMonths = allMonths.concat(EC_QUARTERS[quarter]);
            });

            allMonths.forEach(month => {
                selectedMonths.add(month);
            });

            renderMonths();
            renderWeeks();
            updateBadges();
            showAlert('All months in selected quarters selected for preparation year ' + preparationYear, 'success');
        }

        function clearAllMonths() {
            selectedMonths.clear();
            currentDisplayedMonths.clear();
            renderMonths();
            $('#weeksSection').hide();
            updateBadges();
            showAlert('All months cleared', 'info');
        }

        /* -------------------------------
           WEEKS MANAGEMENT
        ------------------------------- */
        function renderWeeks() {
            $("#weeksContainer").empty();

            if (selectedMonths.size === 0) {
                $("#weeksSection").hide();
                return;
            }

            $("#weeksSection").show();
            currentDisplayedMonths = new Set([...selectedMonths]);

            // Group weeks by month for better organization
            const monthsArray = Array.from(selectedMonths).sort((a, b) => a - b);

            monthsArray.forEach(monthNum => {
                const month = EC_MONTHS[monthNum - 1];
                const monthData = calculateMonthData(monthNum);

                // Month header
                $("#weeksContainer").append(`
                <div class="month-section mb-3 mb-md-4 p-2 p-md-3 border rounded bg-light">
                    <div class="d-flex justify-content-between align-items-center flex-column flex-md-row mb-2 mb-md-3">
                        <h6 class="mb-1 mb-md-0 text-primary fs-6">
                            <i class="mdi mdi-calendar-month me-2"></i>
                            ${month.name} (Q${findQuarter(monthNum)}) - ${preparationYear}
                        </h6>
                        <div class="text-center text-md-end mt-1 mt-md-0">
                            <span class="badge bg-primary me-1 me-md-2 mb-1 mb-md-0">
                                Planned: ${monthData.planned.toFixed(2)} ha
                            </span>
                            <span class="badge bg-info">
                                ${totalArea > 0 ? ((monthData.planned / totalArea * 100).toFixed(1)) : 0}%
                            </span>
                        </div>
                    </div>
                    <div class="weeks-grid" id="weeksGrid${monthNum}">
                        <!-- Weeks for this month will be populated here -->
                    </div>
                </div>
            `);

                // Render weeks for this month
                renderWeeksForMonth(monthNum);
            });

            updateTotalDistributedBadge();
            enhanceWeekInputValidation();
        }

        function renderWeeksForMonth(monthNum) {
            const month = EC_MONTHS[monthNum - 1];
            const weeksGrid = $(`#weeksGrid${monthNum}`);
            weeksGrid.empty();

            let weeks = [];
            let start = 1;
            while (start <= month.days) {
                let end = Math.min(start + 6, month.days);
                weeks.push({ start, end });
                start += 7;
            }

            weeks.forEach((w, i) => {
                const weekNumber = i + 1;
                const existingDistribution = weeklyDistributions.find(dist =>
                    dist.month === month.name && dist.week === `Week ${weekNumber}`
                );
                
                const plannedArea = existingDistribution ? existingDistribution.planned_area : 0;
                const status = calculateStatus(plannedArea);
                const statusClass = getStatusClass(status);

                weeksGrid.append(`
                <div class="week-combined-row mb-3 p-2 border rounded bg-white">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-3 mb-2 mb-md-0">
                            <strong class="fs-6">Week ${weekNumber}</strong><br>
                            <small class="text-muted">Days ${w.start}-${w.end}</small><br>
                            <small class="text-info">Year: ${preparationYear}</small>
                        </div>
                        
                        <!-- Planning Section -->
                        <div class="col-12 col-md-5 mb-2 mb-md-0">
                            <label class="form-label small fw-bold text-primary">Planned Area</label>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01" min="0" max="${totalArea}"
                                       class="form-control planned-input"
                                       data-month="${monthNum}"
                                       data-week="${weekNumber}"
                                       value="${plannedArea}"
                                       placeholder="0.00"
                                       oninput="validatePlannedInput(this)">
                                <span class="input-group-text">ha</span>
                            </div>
                            <div class="validation-feedback mt-1"></div>
                        </div>
                        
                        <!-- Status -->
                        <div class="col-12 col-md-4">
                            <div class="row text-center">
                                <div class="col-12">
                                    <small class="text-muted d-block">Status</small>
                                    <span class="badge ${statusClass} fs-6">${status}</span>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar ${statusClass}" 
                                     style="width: ${totalArea > 0 ? Math.min((plannedArea / totalArea * 100), 100) : 0}%">
                                </div>
                            </div>
                            <small class="text-muted">
                                ${totalArea > 0 ? ((plannedArea / totalArea * 100).toFixed(1)) : 0}%
                            </small>
                        </div>
                    </div>
                </div>
            `);
            });
        }

        /* -------------------------------
           INPUT VALIDATION
        ------------------------------- */
        function validatePlannedInput(input) {
            const value = parseFloat($(input).val()) || 0;
            const feedback = $(input).siblings('.validation-feedback');

            $(input).removeClass('is-valid is-invalid');
            feedback.empty();

            if (value < 0) {
                $(input).addClass('is-invalid');
                feedback.html('<small class="text-danger"><i class="mdi mdi-alert-circle me-1"></i>Value cannot be negative</small>');
                return false;
            }

            if (value > totalArea) {
                $(input).addClass('is-invalid');
                feedback.html(`<small class="text-danger"><i class="mdi mdi-alert-circle me-1"></i>Exceeds total area (${totalArea.toFixed(2)} ha)</small>`);
                return false;
            }

            if (value > 0) {
                $(input).addClass('is-valid');
            }

            updateWeekDistribution(input);
            return true;
        }

        function updateWeekDistribution(input) {
            const monthNum = parseInt($(input).data('month'));
            const weekNumber = parseInt($(input).data('week'));
            const month = EC_MONTHS[monthNum - 1];
            const quarter = `Q${findQuarter(monthNum)}`;
            
            const plannedInput = $(`.planned-input[data-month="${monthNum}"][data-week="${weekNumber}"]`);
            const plannedArea = parseFloat(plannedInput.val()) || 0;
            
            // Find or create distribution
            let distribution = weeklyDistributions.find(dist =>
                dist.month === month.name && dist.week === `Week ${weekNumber}`
            );

            if (distribution) {
                distribution.planned_area = plannedArea;
            } else {
                const weekData = getWeekData(monthNum, weekNumber);
                distribution = {
                    year: preparationYear,
                    quarter: quarter,
                    month: month.name,
                    week: `Week ${weekNumber}`,
                    range: weekData.range,
                    planned_area: plannedArea
                };
                weeklyDistributions.push(distribution);
            }

            // Update status
            distribution.status = calculateStatus(plannedArea);

            // Remove if area is 0
            if (plannedArea === 0) {
                weeklyDistributions = weeklyDistributions.filter(dist =>
                    !(dist.month === month.name && dist.week === `Week ${weekNumber}`)
                );
            }

            // Update UI
            updateWeekUI(monthNum, weekNumber, plannedArea);
            updateSummaryTable();
            updateSummaryCards();
            updateTotalDistributedBadge();
        }

        function updateWeekUI(monthNum, weekNumber, plannedArea) {
            const status = calculateStatus(plannedArea);
            const statusClass = getStatusClass(status);
            
            const weekRow = $(`.planned-input[data-month="${monthNum}"][data-week="${weekNumber}"]`).closest('.week-combined-row');
            
            weekRow.find('.badge').removeClass('bg-secondary bg-success');
            weekRow.find('.badge').addClass(statusClass).text(status);
            
            weekRow.find('.progress-bar')
                .removeClass('bg-secondary bg-success')
                .addClass(statusClass)
                .css('width', totalArea > 0 ? Math.min((plannedArea / totalArea * 100), 100) + '%' : '0%');
            
            weekRow.find('.text-muted').last().text(totalArea > 0 ? ((plannedArea / totalArea * 100).toFixed(1)) + '%' : '0%');
        }

        function enhanceWeekInputValidation() {
            $('.planned-input').off('input').on('input', function() {
                validatePlannedInput(this);
            });
        }

        function saveAllDistributions() {
            let hasErrors = false;

            // Validate all inputs first
            $('.planned-input').each(function() {
                if (!validatePlannedInput(this)) hasErrors = true;
            });

            if (hasErrors) {
                showAlert('Please fix validation errors before saving', 'error');
                return;
            }

            saveJSON();
            showAlert('All distributions saved successfully!', 'success');
        }

        /* -------------------------------
           SUMMARY & DATA MANAGEMENT
        ------------------------------- */
        function updateSummaryTable() {
            $("#summaryTable").empty();
            let totalPlanned = 0;

            if (weeklyDistributions.length === 0) {
                $("#summaryTable").append(`
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="mdi mdi-calendar-blank me-2"></i>
                        No distribution yet. Start by selecting quarters above.
                    </td>
                </tr>
            `);
            } else {
                weeklyDistributions.forEach((dist, i) => {
                    totalPlanned += dist.planned_area;
                    const statusClass = getStatusClass(dist.status);

                    $("#summaryTable").append(`
                    <tr>
                        <td class="d-none d-md-table-cell">${dist.year || preparationYear}</td>
                        <td class="d-none d-md-table-cell">${dist.quarter}</td>
                        <td>${dist.month}</td>
                        <td>${dist.week}</td>
                        <td class="d-none d-md-table-cell">${dist.range}</td>
                        <td class="fw-bold text-primary">${dist.planned_area.toFixed(2)} ha</td>
                        <td><span class="badge ${statusClass}">${dist.status}</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger" onclick="removeDistribution(${i})">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </td>
                    </tr>
                `);
                });
            }

            const overallCompletion = totalArea > 0 ? (totalPlanned / totalArea * 100) : 0;
            $("#summaryTotal").text(totalPlanned.toFixed(2) + " ha");
            $("#summaryCompletion").text(overallCompletion.toFixed(1) + "%");
        }

        function updateSummaryCards() {
            const totalPlanned = weeklyDistributions.reduce((total, dist) => total + dist.planned_area, 0);
            const completion = totalArea > 0 ? (totalPlanned / totalArea * 100) : 0;

            $('#plannedAreaSummary').text(totalPlanned.toFixed(2));
            $('#completionPercentage').text(completion.toFixed(1) + '%');
        }

        function removeDistribution(index) {
            weeklyDistributions.splice(index, 1);
            updateSummaryTable();
            saveJSON();
            updateSummaryCards();
            renderWeeks();
            showAlert('Distribution removed successfully!', 'info');
        }

        function saveJSON() {
            const totalPlanned = weeklyDistributions.reduce((total, dist) => total + dist.planned_area, 0);
            const overallCompletion = totalArea > 0 ? (totalPlanned / totalArea * 100) : 0;

            const planData = {
                preparation_year: preparationYear,
                weekly_distribution: weeklyDistributions,
                summary: {
                    total_planned: totalPlanned,
                    overall_completion: overallCompletion,
                    updated_at: new Date().toISOString()
                }
            };

            $("#plandivisionDetails").val(JSON.stringify(planData));
            console.log('Saved plan data:', planData);
            
            // Update summary cards
            updateSummaryCards();
        }

        function loadExistingDistribution() {
            let existing = {!! json_encode($plan->plandivision_details ?? '[]') !!};
            console.log('Loading existing distribution:', existing);

            if (existing && typeof existing === 'object') {
                if (existing.weekly_distribution && Array.isArray(existing.weekly_distribution)) {
                    weeklyDistributions = existing.weekly_distribution.map(dist => ({
                        year: dist.year || preparationYear,
                        quarter: dist.quarter,
                        month: dist.month,
                        week: dist.week,
                        range: dist.range,
                        planned_area: parseFloat(dist.planned_area || dist.area || 0),
                        status: dist.status || calculateStatus(parseFloat(dist.planned_area || dist.area || 0))
                    }));
                }

                // Restore selections
                weeklyDistributions.forEach(dist => {
                    const quarterNum = parseInt(dist.quarter.replace('Q', ''));
                    selectedQuarters.add(quarterNum);

                    const monthNum = EC_MONTHS.findIndex(m => m.name === dist.month) + 1;
                    if (monthNum > 0) selectedMonths.add(monthNum);
                });

                updateSummaryTable();
                updateBadges();
                updateSummaryCards();

                if (selectedQuarters.size > 0) {
                    $("#monthsSection").show();
                    renderMonths();
                }

                if (selectedMonths.size > 0) {
                    $("#weeksSection").show();
                    renderWeeks();
                }
            }
        }

        /* -------------------------------
           UTILITY FUNCTIONS
        ------------------------------- */
        function updateTotalDistributedBadge() {
            const totalPlanned = weeklyDistributions.reduce((total, dist) => total + dist.planned_area, 0);
            $('#totalDistributedBadge').text(totalPlanned.toFixed(2) + ' ha');
        }

        function updateBadges() {
            $("#selectedQuartersBadge").text(selectedQuarters.size + " quarters");
            $("#selectedMonthsBadge").text(selectedMonths.size + " months");
            $("#selectedWeeksBadge").text(weeklyDistributions.length + " weeks");
        }

        function findQuarter(monthId) {
            return Object.keys(EC_QUARTERS).find(q => EC_QUARTERS[q].includes(monthId));
        }

        function getWeekData(monthNum, weekNumber) {
            const month = EC_MONTHS[monthNum - 1];
            let start = 1 + (weekNumber - 1) * 7;
            let end = Math.min(start + 6, month.days);
            return { range: `${start}-${end}` };
        }

        function validateForm() {
            let isValid = true;
            const errors = [];

            // Check for individual input errors
            $('.planned-input').each(function() {
                if ($(this).hasClass('is-invalid')) {
                    errors.push('Some distributions have validation errors');
                    isValid = false;
                    return false; // break the loop
                }
            });

            // Show errors
            if (errors.length > 0) {
                showValidationErrors(errors);
            }

            return isValid;
        }

        function showValidationErrors(errors) {
            // Remove existing error alerts
            $('.validation-error-alert').remove();

            const errorHtml = `
            <div class="row justify-content-center mb-3 mb-md-4 validation-error-alert">
                <div class="col-12 col-lg-10">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-alert-circle-outline me-2 fs-5"></i>
                            <div class="flex-grow-1">
                                <h6 class="alert-heading mb-2">Please fix the following errors:</h6>
                                <ul class="mb-0 ps-3">
                                    ${errors.map(error => `<li>${error}</li>`).join('')}
                                </ul>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            </div>
        `;

            $('.page-header').after(errorHtml);

            // Scroll to errors
            $('html, body').animate({
                scrollTop: $('.validation-error-alert').offset().top - 100
            }, 500);
        }

        function showAlert(message, type = 'info') {
            const alertClass = {
                'success': 'alert-success',
                'error': 'alert-danger',
                'warning': 'alert-warning',
                'info': 'alert-info'
            }[type] || 'alert-info';

            // Create toast notification
            const toastId = 'toast-' + Date.now();
            const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white bg-${type === 'error' ? 'danger' : type === 'warning' ? 'warning' : 'success'} border-0 position-fixed top-0 end-0 m-3" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="mdi mdi-${type === 'success' ? 'check-circle' : type === 'error' ? 'alert-circle' : 'information'} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;

            $('body').append(toastHtml);
            const toast = new bootstrap.Toast(document.getElementById(toastId));
            toast.show();

            // Remove toast after hide
            document.getElementById(toastId).addEventListener('hidden.bs.toast', function() {
                this.remove();
            });
        }
    </script>

    <style>
        .crop-plan-edit-container {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .form-card {
            border-radius: 15px;
            overflow: hidden;
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffa726 0%, #fb8c00 100%) !important;
        }

        .page-header .header-icon {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .header-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 1rem 0;
        }

        .divider-line {
            flex: 1;
            height: 2px;
            background: linear-gradient(90deg, transparent, #dee2e6, transparent);
        }

        .divider-icon {
            margin: 0 1rem;
            color: #ffa726;
            font-size: 1.5rem;
        }

        .section-header {
            position: relative;
        }

        .section-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .section-divider {
            height: 3px;
            background: linear-gradient(90deg, #ffa726, #fb8c00);
            border-radius: 2px;
            width: 60px;
        }

        .info-card {
            border-left: 4px solid #007bff;
            min-height: 80px;
        }

        .summary-card {
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .summary-card:hover {
            transform: translateY(-5px);
        }

        .summary-value {
            margin: 0.5rem 0;
        }

        .summary-label {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        /* Quarter and Month Card Styles */
        .quarter-card, .month-card {
            transition: all 0.3s ease;
            border: 2px solid #e9ecef;
            cursor: pointer;
            min-height: 120px;
        }

        .quarter-card:hover, .month-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-color: #007bff;
        }

        .card-selected {
            border-color: #007bff !important;
            background-color: #f8f9ff !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,123,255,0.15) !important;
        }

        .quarter-checkbox, .month-checkbox {
            transform: scale(1.2);
        }

        /* Combined week row styling */
        .week-combined-row {
            transition: all 0.3s ease;
            border-left: 4px solid #007bff;
        }

        .week-combined-row:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .planned-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }

        .month-section {
            border-left: 4px solid #007bff !important;
        }

        .btn-xs {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        /* Status badge colors */
        .badge.bg-success {
            background-color: #198754 !important;
        }

        .badge.bg-secondary {
            background-color: #6c757d !important;
        }

        /* Progress bar colors */
        .progress-bar.bg-success { background-color: #198754 !important; }
        .progress-bar.bg-secondary { background-color: #6c757d !important; }

        /* Mobile-first responsive adjustments */
        @media (max-width: 576px) {
            .container-fluid {
                padding-left: 10px;
                padding-right: 10px;
            }

            .card-body {
                padding: 1.5rem !important;
            }

            .form-actions {
                margin-left: -1.5rem;
                margin-right: -1.5rem;
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }

            .activity-planning-container {
                padding: 10px;
            }

            .info-card {
                min-height: 70px;
            }

            .quarter-card, .month-card {
                min-height: 100px;
            }

            .btn-lg {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }

            .week-combined-row .col-md-3,
            .week-combined-row .col-md-5,
            .week-combined-row .col-md-4 {
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 2rem !important;
            }

            .form-actions {
                margin-left: -2rem;
                margin-right: -2rem;
                padding-left: 2rem;
                padding-right: 2rem;
            }

            .form-actions .d-flex {
                flex-direction: column;
                gap: 1rem;
            }

            .form-actions .btn {
                width: 100%;
            }

            .activity-planning-container {
                padding: 15px;
            }

            .quarter-card .col-md-3,
            .month-card .col-md-4 {
                width: 100%;
            }

            /* Improve touch targets for mobile */
            .btn {
                padding: 0.75rem 1rem;
            }

            .form-control {
                padding: 0.75rem;
            }

            /* Better spacing for mobile */
            .row.g-2 {
                margin: -5px;
            }

            .row.g-2 > [class*="col-"] {
                padding: 5px;
            }
        }

        /* Validation styles */
        .validation-feedback {
            min-height: 20px;
        }

        .planned-input.is-valid {
            border-color: #28a745;
        }

        .planned-input.is-invalid {
            border-color: #dc3545;
        }
    </style>
</x-layout>