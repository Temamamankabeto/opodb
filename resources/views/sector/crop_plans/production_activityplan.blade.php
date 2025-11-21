<x-layout>
    <div class="container-fluid py-3 py-md-4 production-plan-container">
        <!-- Header Section -->
        <div class="row justify-content-center mb-4 mb-md-5">
            <div class="col-12 col-lg-10">
                <div class="page-header text-center">
                    <div class="header-icon mb-2 mb-md-3">
                        <i class="mdi mdi-sprout display-4 text-success"></i>
                    </div>
                    <h1 class="display-6 fw-bold text-success mb-2">Production Planning</h1>
                    <p class="lead text-muted d-none d-md-block">Manage production schedule and yield distribution for {{ Auth::user()->kebele->name }} Kebele</p>
                    <p class="text-muted d-md-none">Manage production schedule and yield distribution</p>
                    <div class="header-divider">
                        <div class="divider-line"></div>
                        <div class="divider-icon">
                            <i class="mdi mdi-barley"></i>
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
                    <div class="card-header bg-gradient-success text-white py-3 py-md-4">
                        <h4 class="mb-0 fs-5 fs-md-4">
                            <i class="mdi mdi-calendar-multiple-check me-2"></i>
                            Production Planning & Yield Distribution
                        </h4>
                    </div>
                    <div class="card-body p-3 p-md-5">
                        <form action="{{ route('sector.crop-plans.update', $plan->id) }}" method="POST" id="productionPlanForm">
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
                            <input type="hidden" name="productionplan_details" id="productionplanDetails"
                                   value="{{ json_encode($plan->productionplan_details ?? []) }}">

                            <!-- Basic Information Display (Read-only) -->
                            <div class="section-header mb-3 mb-md-4">
                                <h5 class="section-title fs-6 fs-md-5">
                                    <i class="mdi mdi-information-outline me-2 text-primary"></i>
                                    Crop Production Information
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

                            <!-- Production Information Display -->
                            <div class="row g-2 g-md-4 mb-3 mb-md-4">
                                <div class="col-6 col-md-3">
                                    <div class="info-card p-2 p-md-3 bg-light rounded">
                                        <small class="text-muted">Productivity</small>
                                        <div class="fw-bold text-primary fs-6 fs-md-normal">{{ $plan->productivity }} qt/ha</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="info-card p-2 p-md-3 bg-light rounded">
                                        <small class="text-muted">Expected Yield</small>
                                        <div class="fw-bold text-primary fs-6 fs-md-normal">{{ $plan->expected_yield }} qt</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="info-card p-2 p-md-3 bg-light rounded">
                                        <small class="text-muted">Seed Required</small>
                                        <div class="fw-bold text-primary fs-6 fs-md-normal">{{ $plan->seed_required }} kg</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="info-card p-2 p-md-3 bg-light rounded">
                                        <small class="text-muted">Status</small>
                                        <div class="fw-bold text-primary fs-6 fs-md-normal">{{ $plan->status ?? 'Active' }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Production Planning Section -->
                            <div class="section-header mt-4 mt-md-5 mb-3 mb-md-4">
                                <h5 class="section-title fs-6 fs-md-5">
                                    <i class="mdi mdi-calendar-multiple me-2 text-info"></i>
                                    Production Planning (Production Year: <span id="productionYear">{{ $plan->year }}</span>)
                                </h5>
                                <div class="section-divider"></div>
                                <p class="text-muted mt-2 d-none d-md-block">
                                    Select multiple quarters, months, and distribute expected yield across weeks for production activities in the production year ({{ $plan->year }}).
                                </p>
                                <p class="text-muted mt-1 d-md-none small">
                                    Select quarters, months, and distribute yield across weeks for production year.
                                </p>
                                <div class="yield-summary-card mt-2 mt-md-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body py-2 py-md-3">
                                            <div class="row text-center">
                                                <div class="col-4">
                                                    <small class="text-muted">Total Expected Yield</small>
                                                    <div class="fw-bold fs-6 text-primary" id="totalYieldDisplay">{{ $plan->expected_yield }} qt</div>
                                                </div>
                                                <div class="col-4">
                                                    <small class="text-muted">Distributed Yield</small>
                                                    <div class="fw-bold fs-6 text-success" id="distributedYieldDisplay">0 qt</div>
                                                </div>
                                                <div class="col-4">
                                                    <small class="text-muted">Remaining Yield</small>
                                                    <div class="fw-bold fs-6 text-warning" id="remainingYieldDisplay">{{ $plan->expected_yield }} qt</div>
                                                </div>
                                            </div>
                                            <div class="progress mt-2" style="height: 6px;">
                                                <div class="progress-bar bg-success" id="distributionProgress" style="width: 0%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="production-planning-container">
                                <!-- Production Year Section -->
                                <div class="card mb-3 mb-md-4 border-0 shadow-sm">
                                    <div class="card-header bg-light py-2 py-md-3">
                                        <div class="d-flex justify-content-between align-items-center flex-column flex-md-row">
                                            <h6 class="mb-1 mb-md-0 fs-6">
                                                <i class="mdi mdi-calendar-range me-2 text-success"></i>
                                                Production Year: {{ $plan->year }}
                                            </h6>
                                            <div class="mt-1 mt-md-0">
                                                <span class="badge bg-success fs-6">Production Activities</span>
                                            </div>
                                        </div>
                                        <small class="text-muted d-none d-md-block">All production activities occur in the planting year ({{ $plan->year }})</small>
                                        <small class="text-muted d-md-none small">Production year: {{ $plan->year }}</small>
                                    </div>
                                </div>

                                <!-- Quarters Section -->
                                <div class="card mb-3 mb-md-4 border-0 shadow-sm">
                                    <div class="card-header bg-light py-2 py-md-3">
                                        <div class="d-flex justify-content-between align-items-center flex-column flex-md-row">
                                            <h6 class="mb-1 mb-md-0 fs-6">
                                                <i class="mdi mdi-chart-pie me-2 text-primary"></i>
                                                Select Quarters ({{ $plan->year }})
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
                                        <small class="text-muted d-none d-md-block">Click on quarter cards to select/deselect multiple quarters for production year {{ $plan->year }}</small>
                                        <small class="text-muted d-md-none small">Tap quarter cards to select</small>
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
                                                Select Months ({{ $plan->year }})
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
                                        <small class="text-muted d-none d-md-block">Select months from the chosen quarters for production year {{ $plan->year }}</small>
                                        <small class="text-muted d-md-none small">Select months from chosen quarters</small>
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
                                                Distribute Yield ({{ $plan->year }})
                                            </h6>
                                            <div class="mt-1 mt-md-0 text-center text-md-end">
                                                <small class="text-muted me-2 d-none d-md-inline">Total Distributed: </small>
                                                <span class="badge bg-primary fs-6" id="totalDistributedBadge">0 qt</span>
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
                                                        Enter expected yield in quintals for each week in production year {{ $plan->year }}.
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
                                                Yield Distribution Summary (Production Year: {{ $plan->year }})
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
                                                    <th>Yield (qt)</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody id="summaryTable">
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted py-4">
                                                        <i class="mdi mdi-calendar-blank me-2"></i>
                                                        No distribution yet. Start by selecting quarters above.
                                                    </td>
                                                </tr>
                                                </tbody>
                                                <tfoot class="table-secondary">
                                                <tr>
                                                    <td colspan="5" class="text-end fw-bold d-none d-md-table-cell">Total Distributed:</td>
                                                    <td colspan="2" class="text-center fw-bold d-md-none">Total Distributed:</td>
                                                    <td class="fw-bold text-success" id="summaryTotal">0 qt</td>
                                                    <td class="d-md-none"></td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

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
                                        <button type="submit" class="btn btn-success btn-lg flex-fill" id="submitBtn">
                                            <i class="mdi mdi-content-save-outline me-2"></i>
                                            Update Production Plan
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
        let totalYield = parseFloat({{ $plan->expected_yield }});
        let selectedQuarters = new Set();
        let selectedMonths = new Set();
        let summaryRows = [];
        let currentDisplayedMonths = new Set();
        const productionYear = {{ $plan->year }};

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

        // Initialize form
        $(document).ready(function() {
            console.log('Document ready - initializing production planning for production year:', productionYear);

            // Update the production year display
            $('#productionYear').text(productionYear);

            // Form submission validation
            $('#productionPlanForm').on('submit', function(e) {
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
            updateYieldSummary();

            // Mobile touch improvements
            improveMobileTouch();
        });

        /* -------------------------------
           MOBILE TOUCH IMPROVEMENTS
        ------------------------------- */
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
           QUARTERS MANAGEMENT - MULTI SELECTION
        ------------------------------- */
        function renderQuarters() {
            $("#quartersContainer").empty();

            Object.keys(EC_QUARTERS).forEach(q => {
                const quarterYield = calculateQuarterDistributedYield(parseInt(q));
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
                            <small class="text-success">${quarterYield.toFixed(2)} qt</small>
                        </div>
                        <div class="mt-1">
                            <small class="text-info">Year: ${productionYear}</small>
                        </div>
                    </div>
                </div>
            `);
            });

            // Add event listeners for quarter checkboxes
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
                // Don't trigger if clicking on checkbox
                if (!$(e.target).is('input') && !$(e.target).is('label')) {
                    const checkbox = $(this).find('.quarter-checkbox');
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    checkbox.trigger('change');
                }
            });
        }

        function selectAllQuarters() {
            Object.keys(EC_QUARTERS).forEach(q => {
                selectedQuarters.add(parseInt(q));
            });
            renderQuarters();
            renderMonths();
            updateBadges();
            showAlert('All quarters selected for production year ' + productionYear, 'success');
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
           MONTHS MANAGEMENT - MULTI SELECTION
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
                const monthYield = calculateMonthDistributedYield(monthNum);
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
                            <small class="text-success">${monthYield.toFixed(2)} qt</small>
                        </div>
                        <div class="mt-1">
                            <small class="text-info">Year: ${productionYear}</small>
                        </div>
                    </div>
                </div>
            `);
            });

            // Add event listeners for month checkboxes
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
                // Don't trigger if clicking on checkbox
                if (!$(e.target).is('input') && !$(e.target).is('label')) {
                    const checkbox = $(this).find('.month-checkbox');
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    checkbox.trigger('change');
                }
            });
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
            showAlert('All months in selected quarters selected for production year ' + productionYear, 'success');
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
           WEEKS MANAGEMENT - MULTI MONTH
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
                const monthYield = calculateMonthDistributedYield(monthNum);

                // Month header
                $("#weeksContainer").append(`
                <div class="month-section mb-3 mb-md-4 p-2 p-md-3 border rounded bg-light">
                    <div class="d-flex justify-content-between align-items-center flex-column flex-md-row mb-2 mb-md-3">
                        <h6 class="mb-1 mb-md-0 text-primary fs-6">
                            <i class="mdi mdi-calendar-month me-2"></i>
                            ${month.name} (Q${findQuarter(monthNum)}) - ${productionYear}
                        </h6>
                        <div class="text-center text-md-end mt-1 mt-md-0">
                            <span class="badge bg-info me-1 me-md-2 mb-1 mb-md-0">${monthYield.toFixed(2)} qt</span>
                            <button type="button" class="btn btn-outline-primary btn-xs btn-sm me-1" onclick="distributeEqually(${monthNum})">
                                <i class="mdi mdi-autorenew me-1"></i> Equal
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-xs btn-sm" onclick="clearMonthDistribution(${monthNum})">
                                <i class="mdi mdi-refresh me-1"></i> Clear
                            </button>
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

            // Add enhanced input event listeners with live validation
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
                const existingWeek = summaryRows.find(row =>
                    row.month === month.name && row.week === `Week ${i+1}`
                );
                const weekNumber = i + 1;
                const remainingYield = totalYield - calculateTotalDistributedYield() + (existingWeek ? existingWeek.yield : 0);
                const maxYield = Math.min(totalYield, remainingYield + (existingWeek ? existingWeek.yield : 0));

                weeksGrid.append(`
                <div class="row mb-2 align-items-center week-row">
                    <div class="col-12 col-md-3 mb-1 mb-md-0">
                        <strong class="fs-6">Week ${weekNumber}</strong><br>
                        <small class="text-muted">Days ${w.start}-${w.end}</small>
                        <br>
                        <small class="text-info">Year: ${productionYear}</small>
                    </div>
                    <div class="col-8 col-md-5">
                        <div class="input-group input-group-sm">
                            <input type="number" step="0.01" min="0" max="${maxYield}"
                                   class="form-control week-input"
                                   data-month="${monthNum}"
                                   data-week="${weekNumber}"
                                   data-range="${w.start}-${w.end}"
                                   value="${existingWeek ? existingWeek.yield : ''}"
                                   placeholder="0.00"
                                   oninput="validateWeekInput(this)">
                            <span class="input-group-text">qt</span>
                        </div>
                        <div class="validation-feedback mt-1"></div>
                    </div>
                    <div class="col-4 col-md-4 text-center text-md-start">
                        ${existingWeek ?
                    '<span class="badge bg-success"><i class="mdi mdi-check me-1"></i></span>' :
                    '<span class="badge bg-secondary"><i class="mdi mdi-clock me-1"></i></span>'
                }
                    </div>
                </div>
            `);
            });
        }

        /* -------------------------------
           ENHANCED LIVE VALIDATION
        ------------------------------- */
        function enhanceWeekInputValidation() {
            $('.week-input').off('input').on('input', function() {
                validateWeekInput(this);
                validateTotalDistribution();
                updateYieldSummary();
                updateTotalDistributedBadge();
            });
        }

        function validateWeekInput(input) {
            const value = parseFloat($(input).val()) || 0;
            const max = parseFloat($(input).attr('max')) || totalYield;
            const feedback = $(input).siblings('.validation-feedback');

            // Clear previous validation
            $(input).removeClass('is-valid is-invalid');
            feedback.empty();

            if (value < 0) {
                $(input).addClass('is-invalid');
                feedback.html('<small class="text-danger"><i class="mdi mdi-alert-circle me-1"></i>Value cannot be negative</small>');
                return false;
            }

            if (value > max) {
                $(input).addClass('is-invalid');
                feedback.html(`<small class="text-danger"><i class="mdi mdi-alert-circle me-1"></i>Exceeds available yield (${max.toFixed(2)} qt)</small>`);
                return false;
            }

            if (value > 0) {
                $(input).addClass('is-valid');
                feedback.html('<small class="text-success"><i class="mdi mdi-check-circle me-1"></i>Valid</small>');
            }

            return true;
        }

        function distributeEqually(monthNum) {
            const month = EC_MONTHS[monthNum - 1];
            const monthWeeks = getWeeksForMonth(monthNum);
            const remainingYield = totalYield - calculateTotalDistributedYield();
            const currentMonthYield = calculateMonthDistributedYield(monthNum);
            const availableYield = remainingYield + currentMonthYield;

            if (monthWeeks.length === 0 || availableYield <= 0) {
                showAlert('No yield available to distribute for this month', 'warning');
                return;
            }

            const equalYield = availableYield / monthWeeks.length;

            monthWeeks.forEach(week => {
                const input = $(`.week-input[data-month="${monthNum}"][data-week="${week.weekNumber}"]`);
                input.val(equalYield.toFixed(2));
                updateWeekDistribution(monthNum, week.weekNumber, equalYield);
            });

            updateSummaryTable();
            updateYieldSummary();
            updateTotalDistributedBadge();
            enhanceWeekInputValidation();
            showAlert(`Distributed ${availableYield.toFixed(2)} qt equally across ${monthWeeks.length} weeks in ${month.name}`, 'success');
        }

        function clearMonthDistribution(monthNum) {
            const month = EC_MONTHS[monthNum - 1];
            summaryRows = summaryRows.filter(row => row.month !== month.name);

            // Clear all inputs for this month
            $(`.week-input[data-month="${monthNum}"]`).val('');

            updateSummaryTable();
            saveJSON();
            updateYieldSummary();
            updateTotalDistributedBadge();
            renderWeeksForMonth(monthNum);
            enhanceWeekInputValidation();

            showAlert(`All distributions cleared for ${month.name}`, 'info');
        }

        function updateWeekDistribution(monthNum, weekNumber, yieldValue) {
            const month = EC_MONTHS[monthNum - 1];
            const quarter = `Q${findQuarter(monthNum)}`;

            // Find or create distribution entry
            let distribution = summaryRows.find(d =>
                d.month === month.name && d.week === `Week ${weekNumber}`
            );

            if (distribution) {
                distribution.yield = yieldValue;
            } else {
                const weekData = getWeekData(monthNum, weekNumber);
                distribution = {
                    year: productionYear,
                    quarter: quarter,
                    month: month.name,
                    week: `Week ${weekNumber}`,
                    range: weekData.range,
                    yield: yieldValue
                };
                summaryRows.push(distribution);
            }

            // Remove if yield is 0
            if (yieldValue === 0) {
                summaryRows = summaryRows.filter(d =>
                    !(d.month === month.name && d.week === `Week ${weekNumber}`)
                );
            }
        }

        function getWeekData(monthNum, weekNumber) {
            const month = EC_MONTHS[monthNum - 1];
            let start = 1 + (weekNumber - 1) * 7;
            let end = Math.min(start + 6, month.days);
            return { range: `${start}-${end}` };
        }

        function getWeeksForMonth(monthNum) {
            const month = EC_MONTHS[monthNum - 1];
            let weeks = [];
            let start = 1;
            let weekNumber = 1;

            while (start <= month.days) {
                let end = Math.min(start + 6, month.days);
                weeks.push({ weekNumber, start, end });
                start += 7;
                weekNumber++;
            }

            return weeks;
        }

        function saveAllDistributions() {
            let hasErrors = false;

            // Validate all inputs first
            $('.week-input').each(function() {
                if (!validateWeekInput(this)) {
                    hasErrors = true;
                }
            });

            if (hasErrors) {
                showAlert('Please fix validation errors before saving', 'error');
                return;
            }

            // Update all week distributions from inputs
            $('.week-input').each(function() {
                const monthNum = parseInt($(this).data('month'));
                const weekNumber = parseInt($(this).data('week'));
                const yieldValue = parseFloat($(this).val()) || 0;

                updateWeekDistribution(monthNum, weekNumber, yieldValue);
            });

            updateSummaryTable();
            saveJSON();
            updateYieldSummary();
            showAlert('All distributions saved successfully for production year ' + productionYear + '!', 'success');
        }

        // Update week distribution when input changes
        $(document).on('input', '.week-input', function() {
            const monthNum = parseInt($(this).data('month'));
            const weekNumber = parseInt($(this).data('week'));
            const yieldValue = parseFloat($(this).val()) || 0;

            updateWeekDistribution(monthNum, weekNumber, yieldValue);
            updateSummaryTable();
            updateYieldSummary();
        });

        /* -------------------------------
           UTILITY FUNCTIONS
        ------------------------------- */
        function updateTotalDistributedBadge() {
            const totalDistributed = calculateTotalDistributedYield();
            $('#totalDistributedBadge').text(totalDistributed.toFixed(2) + ' qt');
        }

        function validateForm() {
            let isValid = true;
            const errors = [];

            // Enhanced distribution validation
            const distributedYield = calculateTotalDistributedYield();

            if (distributedYield === 0) {
                const proceed = confirm('No production distribution has been planned for production year ' + productionYear + '. Do you want to proceed without distribution?');
                if (!proceed) {
                    errors.push('Please add production distribution or confirm to proceed without it');
                    isValid = false;
                }
            } else if (distributedYield > totalYield) {
                errors.push(`Distributed yield (${distributedYield.toFixed(2)} qt) exceeds expected yield (${totalYield.toFixed(2)} qt)`);
                isValid = false;
            }

            // Check for individual input errors
            $('.week-input').each(function() {
                if ($(this).hasClass('is-invalid')) {
                    errors.push('Some week distributions have validation errors');
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

        function validateTotalDistribution() {
            const distributedYield = calculateTotalDistributedYield();
            const expectedYield = parseFloat({{ $plan->expected_yield }});

            if (distributedYield > expectedYield) {
                $('.distribution-warning').remove();
                $('#totalYieldDisplay').closest('.yield-summary-card').after(`
                <div class="distribution-warning alert alert-warning mt-2">
                    <i class="mdi mdi-alert-circle-outline me-2"></i>
                    Distributed yield (${distributedYield.toFixed(2)} qt) exceeds expected yield. Please adjust your distribution.
                </div>
            `);
                // Disable submit button
                $('#submitBtn').prop('disabled', true).addClass('btn-secondary').removeClass('btn-success');
                return false;
            } else {
                $('.distribution-warning').remove();
                // Enable submit button
                $('#submitBtn').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                return true;
            }
        }

        function calculateTotalDistributedYield() {
            let total = 0;
            summaryRows.forEach(row => {
                total += row.yield;
            });
            return total;
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

        function updateSummaryTable() {
            $("#summaryTable").empty();
            let total = 0;

            if (summaryRows.length === 0) {
                $("#summaryTable").append(`
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="mdi mdi-calendar-blank me-2"></i>
                        No distribution yet. Start by selecting quarters above.
                    </td>
                </tr>
            `);
            } else {
                summaryRows.forEach((row, i) => {
                    total += row.yield;
                    $("#summaryTable").append(`
                    <tr>
                        <td class="d-none d-md-table-cell">${row.year || productionYear}</td>
                        <td class="d-none d-md-table-cell">${row.quarter}</td>
                        <td>${row.month}</td>
                        <td>${row.week}</td>
                        <td class="d-none d-md-table-cell">${row.range}</td>
                        <td class="fw-bold text-success">${row.yield.toFixed(2)} qt</td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger" onclick="removeRow(${i})">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </td>
                    </tr>
                `);
                });
            }

            $("#summaryTotal").text(total.toFixed(2) + " qt");
        }

        function removeRow(i) {
            summaryRows.splice(i, 1);
            updateSummaryTable();
            saveJSON();
            updateYieldSummary();
            showAlert('Distribution removed successfully!', 'info');
        }

        function saveJSON() {
            const productionData = {
                production_year: productionYear,
                weekly_production: summaryRows,
                total_distributed_yield: calculateTotalDistributedYield(),
                remaining_yield: totalYield - calculateTotalDistributedYield(),
                updated_at: new Date().toISOString()
            };

            $("#productionplanDetails").val(JSON.stringify(productionData));
            console.log('Saved productionplan_details for production year ' + productionYear + ':', productionData);
        }

        function loadExistingDistribution() {
            let existing = {!! json_encode($plan->productionplan_details ?? '[]') !!};

            console.log('Loading existing production distribution:', existing);

            if (existing && typeof existing === 'object') {
                // Handle both array and object formats
                if (Array.isArray(existing)) {
                    summaryRows = existing.map(row => ({
                        year: row.year || productionYear,
                        quarter: row.quarter,
                        month: row.month,
                        week: row.week,
                        range: row.range || row.dates || '',
                        yield: parseFloat(row.yield) || 0
                    }));
                } else if (existing.weekly_production) {
                    summaryRows = existing.weekly_production.map(row => ({
                        year: row.year || productionYear,
                        quarter: row.quarter,
                        month: row.month,
                        week: row.week,
                        range: row.range || row.dates || '',
                        yield: parseFloat(row.yield) || 0
                    }));
                }

                // Restore selections
                summaryRows.forEach(row => {
                    const quarterNum = parseInt(row.quarter.replace('Q', ''));
                    selectedQuarters.add(quarterNum);

                    const monthNum = EC_MONTHS.findIndex(m => m.name === row.month) + 1;
                    if (monthNum > 0) selectedMonths.add(monthNum);
                });

                updateSummaryTable();
                updateBadges();

                if (selectedQuarters.size > 0) {
                    $("#monthsSection").show();
                    renderMonths();
                }

                if (selectedMonths.size > 0) {
                    $("#weeksSection").show();
                    renderWeeks();
                }

                updateYieldSummary();
            }
        }

        function updateBadges() {
            $("#selectedQuartersBadge").text(selectedQuarters.size + " quarters");
            $("#selectedMonthsBadge").text(selectedMonths.size + " months");
            $("#selectedWeeksBadge").text(summaryRows.length + " weeks");
        }

        function updateYieldSummary() {
            const distributed = calculateTotalDistributedYield();
            const remaining = totalYield - distributed;

            $("#distributedYieldDisplay").text(distributed.toFixed(2) + " qt");
            $("#remainingYieldDisplay").text(remaining.toFixed(2) + " qt");

            const progress = totalYield > 0 ? (distributed / totalYield * 100) : 0;
            $("#distributionProgress").css('width', progress + '%');
        }

        function calculateQuarterDistributedYield(quarter) {
            const quarterName = `Q${quarter}`;
            return summaryRows
                .filter(row => row.quarter === quarterName)
                .reduce((total, row) => total + row.yield, 0);
        }

        function calculateMonthDistributedYield(month) {
            const monthName = EC_MONTHS[month - 1].name;
            return summaryRows
                .filter(row => row.month === monthName)
                .reduce((total, row) => total + row.yield, 0);
        }

        function findQuarter(monthId) {
            return Object.keys(EC_QUARTERS).find(q => EC_QUARTERS[q].includes(monthId));
        }
    </script>

    <style>
        .production-plan-container {
            background: linear-gradient(135deg, #f0f9f0 0%, #c8e6c9 100%);
            min-height: 100vh;
        }

        .form-card {
            border-radius: 15px;
            overflow: hidden;
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%) !important;
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
            color: #66bb6a;
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
            background: linear-gradient(90deg, #66bb6a, #4caf50);
            border-radius: 2px;
            width: 60px;
        }

        .info-card {
            border-left: 4px solid #007bff;
            min-height: 80px;
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

        .week-input:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        .month-section {
            border-left: 4px solid #007bff !important;
        }

        .week-row {
            border-bottom: 1px solid #e9ecef;
            padding: 0.5rem 0;
        }

        .week-row:last-child {
            border-bottom: none;
        }

        .btn-xs {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

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

            .production-planning-container {
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

            .production-planning-container {
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

        .week-input.is-valid {
            border-color: #28a745;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
        }

        .week-input.is-invalid {
            border-color: #dc3545;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6.4.4.4-.4'/%3e%3cpath d='M6 7v2.5'/%3e%3c/svg%3e");
        }
    </style>
</x-layout>