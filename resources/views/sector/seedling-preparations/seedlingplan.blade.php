<x-layout>
    <div class="container-fluid py-3 py-md-4 seedling-plan-container">
        <!-- Header Section -->
        <div class="row justify-content-center mb-4 mb-md-5">
            <div class="col-12 col-lg-10">
                <div class="page-header text-center">
                    <div class="header-icon mb-2 mb-md-3">
                        <i class="mdi mdi-tree display-4 text-success"></i>
                    </div>
                    <h1 class="display-6 fw-bold text-success mb-2">Edit Seedling Planting Schedule</h1>
                    <p class="lead text-muted d-none d-md-block">Update seedling planting schedule for {{ $seedlingPreparation->preparation_data['seedling_type']['name'] ?? 'Seedlings' }}</p>
                    <p class="text-muted d-md-none">Update seedling planting schedule</p>
                    <div class="header-divider">
                        <div class="divider-line"></div>
                        <div class="divider-icon">
                            <i class="mdi mdi-tree"></i>
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
                            <i class="mdi mdi-calendar-edit me-2"></i>
                            Edit Seedling Planting Schedule
                        </h4>
                    </div>
                    <div class="card-body p-3 p-md-5">
                        <form action="{{ route('sector.seedling-plans.update', $seedlingPreparation->id) }}" method="POST" id="seedlingPlanForm">
                            @csrf
                            @method('PUT')

                            <!-- Seedling Information Display (Read-only) -->
                            <div class="section-header mb-3 mb-md-4">
                                <h5 class="section-title fs-6 fs-md-5">
                                    <i class="mdi mdi-information-outline me-2 text-success"></i>
                                    Seedling Information
                                </h5>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row g-2 g-md-4 mb-3 mb-md-4">
                                <div class="col-6 col-md-4">
                                    <div class="info-card p-2 p-md-3 bg-light rounded">
                                        <small class="text-muted">Seedling Type</small>
                                        <div class="fw-bold text-success fs-6 fs-md-normal">
                                            {{ $seedlingPreparation->preparation_data['seedling_type']['name'] ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="info-card p-2 p-md-3 bg-light rounded">
                                        <small class="text-muted">Year</small>
                                        <div class="fw-bold text-success fs-6 fs-md-normal">{{ $seedlingPreparation->year }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="info-card p-2 p-md-3 bg-light rounded">
                                        <small class="text-muted">Planting Year</small>
                                        <div class="fw-bold text-success fs-6 fs-md-normal">{{ $seedlingPreparation->year }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quantity Information Display -->
                            <div class="row g-2 g-md-4 mb-3 mb-md-4">
                                <div class="col-6 col-md-4">
                                    <div class="info-card p-2 p-md-3 bg-light rounded">
                                        <small class="text-muted">Total Prepared Seedlings</small>
                                        <div class="fw-bold text-success fs-6 fs-md-normal" id="totalPreparedQuantity">
                                            {{ number_format($transformedSeedlingPlans[0]['total_prepared_quantity'] ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="info-card p-2 p-md-3 bg-light rounded">
                                        <small class="text-muted">Already Planted</small>
                                        <div class="fw-bold text-warning fs-6 fs-md-normal" id="totalPlantedQuantity">
                                            {{ number_format($transformedSeedlingPlans[0]['total_planted_quantity'] ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="info-card p-2 p-md-3 bg-light rounded">
                                        <small class="text-muted">Remaining to Plant</small>
                                        <div class="fw-bold text-info fs-6 fs-md-normal" id="remainingQuantity">
                                            {{ number_format($transformedSeedlingPlans[0]['remaining_quantity'] ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Seedlings List -->
                            <div class="section-header mt-4 mt-md-5 mb-3 mb-md-4">
                                <h5 class="section-title fs-6 fs-md-5">
                                    <i class="mdi mdi-sprout me-2 text-success"></i>
                                    Available Seedlings for Planting
                                </h5>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row g-2 g-md-4 mb-4 mb-md-5">
                                @foreach($seedlingPreparation->preparation_data['selected_seedlings'] ?? [] as $seedling)
                                <div class="col-12 col-md-6">
                                    <div class="seedling-card p-3 border rounded bg-light">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="fw-bold text-success mb-1">{{ $seedling['name'] }}</h6>
                                                <small class="text-muted">Prepared: {{ number_format($seedling['hanga_qophii'] ?? 0, 2) }}</small>
                                            </div>
                                            <span class="badge bg-success">{{ number_format($seedling['hanga_qophii'] ?? 0, 2) }} available</span>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-info">
                                                Participants: {{ number_format($seedling['nama_hirmaate_qophii'] ?? 0, 2) }} | 
                                                Days: {{ number_format($seedling['baayina_guyyaa_qophii'] ?? 0, 2) }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Seedling Scheduling Section -->
                            <div class="section-header mt-4 mt-md-5 mb-3 mb-md-4">
                                <h5 class="section-title fs-6 fs-md-5">
                                    <i class="mdi mdi-calendar-multiple me-2 text-info"></i>
                                    Seedling Planting Schedule (Planting Year: <span id="plantingYear">{{ $seedlingPreparation->year }}</span>)
                                </h5>
                                <div class="section-divider"></div>
                                <p class="text-muted mt-2 d-none d-md-block">
                                    Schedule seedling planting across quarters, months, and weeks for the planting year ({{ $seedlingPreparation->year }}).
                                </p>
                                <p class="text-muted mt-1 d-md-none small">
                                    Schedule seedling planting for planting year.
                                </p>
                            </div>

                            <div class="seedling-scheduling-container">
                                <!-- Planting Year Section -->
                                <div class="card mb-3 mb-md-4 border-0 shadow-sm">
                                    <div class="card-header bg-light py-2 py-md-3">
                                        <div class="d-flex justify-content-between align-items-center flex-column flex-md-row">
                                            <h6 class="mb-1 mb-md-0 fs-6">
                                                <i class="mdi mdi-calendar-range me-2 text-success"></i>
                                                Planting Year: {{ $seedlingPreparation->year }}
                                            </h6>
                                            <div class="mt-1 mt-md-0">
                                                <span class="badge bg-success fs-6">Seedling Planting Schedule</span>
                                            </div>
                                        </div>
                                        <small class="text-muted d-none d-md-block">Schedule seedling planting for the planting year ({{ $seedlingPreparation->year }})</small>
                                        <small class="text-muted d-md-none small">Planting year: {{ $seedlingPreparation->year }}</small>
                                    </div>
                                </div>

                                <!-- Quarters Section -->
                                <div class="card mb-3 mb-md-4 border-0 shadow-sm">
                                    <div class="card-header bg-light py-2 py-md-3">
                                        <div class="d-flex justify-content-between align-items-center flex-column flex-md-row">
                                            <h6 class="mb-1 mb-md-0 fs-6">
                                                <i class="mdi mdi-chart-pie me-2 text-success"></i>
                                                Select Quarters ({{ $seedlingPreparation->year }})
                                            </h6>
                                            <div class="mt-1 mt-md-0">
                                                <button type="button" class="btn btn-outline-success btn-xs btn-sm" onclick="selectAllQuarters()">
                                                    <i class="mdi mdi-select-all me-1"></i> Select All
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary btn-xs btn-sm ms-1" onclick="clearAllQuarters()">
                                                    <i class="mdi mdi-select-off me-1"></i> Clear All
                                                </button>
                                            </div>
                                        </div>
                                        <small class="text-muted d-none d-md-block">Click on quarter cards to select/deselect multiple quarters for planting year {{ $seedlingPreparation->year }}</small>
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
                                                Select Months ({{ $seedlingPreparation->year }})
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
                                        <small class="text-muted d-none d-md-block">Select months from the chosen quarters for planting year {{ $seedlingPreparation->year }}</small>
                                        <small class="text-muted d-md-none small">Select months from chosen quarters</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2 g-md-3" id="monthsContainer">
                                            <!-- Months will be populated here -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Weeks & Planting Scheduling Section -->
                                <div class="card mb-3 mb-md-4 border-0 shadow-sm" id="schedulingSection" style="display: none;">
                                    <div class="card-header bg-light py-2 py-md-3">
                                        <div class="d-flex justify-content-between align-items-center flex-column flex-md-row">
                                            <h6 class="mb-1 mb-md-0 fs-6">
                                                <i class="mdi mdi-calendar-week me-2 text-warning"></i>
                                                Schedule Planting Details ({{ $seedlingPreparation->year }})
                                            </h6>
                                            <div class="mt-1 mt-md-0 text-center text-md-end">
                                                <small class="text-muted me-2 d-none d-md-inline">Total Scheduled: </small>
                                                <span class="badge bg-success fs-6" id="totalScheduledBadge">0 seedlings</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="schedulingContainer">
                                            <!-- Scheduling will be populated here -->
                                        </div>
                                        <div class="mt-2 mt-md-3 p-2 p-md-3 bg-light rounded">
                                            <div class="row align-items-center">
                                                <div class="col-md-8 mb-2 mb-md-0">
                                                    <small class="text-muted">
                                                        <i class="mdi mdi-information-outline me-1"></i>
                                                        Enter seedling planting details for each week in planting year {{ $seedlingPreparation->year }}. 
                                                        Quantities will be validated against available seedlings.
                                                    </small>
                                                </div>
                                                <div class="col-md-4 text-center text-md-end">
                                                    <button type="button" class="btn btn-success btn-sm w-100 w-md-auto" onclick="saveAllSchedules()">
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
                                                Seedling Planting Summary (Planting Year: {{ $seedlingPreparation->year }})
                                            </h6>
                                            <div class="selected-summary mt-1 mt-md-0 text-center text-md-end">
                                                <small class="text-muted me-2">Selected: </small>
                                                <span class="badge bg-success" id="selectedQuartersBadge">0 quarters</span>
                                                <span class="badge bg-info mx-1" id="selectedMonthsBadge">0 months</span>
                                                <span class="badge bg-warning" id="selectedWeeksBadge">0 schedules</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="d-none d-md-table-cell">Year</th>
                                                        <th>Seedling</th>
                                                        <th class="d-none d-md-table-cell">Quarter</th>
                                                        <th>Month</th>
                                                        <th>Week</th>
                                                        <th class="d-none d-md-table-cell">Date Range</th>
                                                        <th>Seedlings</th>
                                                        <th>Participants</th>
                                                        <th>Days</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="summaryTable">
                                                    <tr>
                                                        <td colspan="10" class="text-center text-muted py-4">
                                                            <i class="mdi mdi-tree me-2"></i>
                                                            No seedling planting scheduled yet. Start by selecting quarters above.
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot class="table-secondary">
                                                    <tr>
                                                        <td colspan="6" class="text-end fw-bold d-none d-md-table-cell">Total Scheduled:</td>
                                                        <td colspan="4" class="text-center fw-bold d-md-none">Total Scheduled:</td>
                                                        <td class="fw-bold text-success" id="summaryTotalSeedlings">0</td>
                                                        <td class="fw-bold text-info" id="summaryTotalParticipants">0</td>
                                                        <td class="fw-bold text-warning" id="summaryTotalDays">0</td>
                                                        <td class="d-md-none"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden field to store structured planting data -->
                            <input type="hidden" name="plantingdata_detail" id="plantingData" value="{{ json_encode($seedlingPreparation->plantingdata_detail ?? []) }}">

                            <!-- Form Actions -->
                            <div class="form-actions mt-4 mt-md-5 pt-3 pt-md-4 border-top">
                                <div class="d-flex justify-content-between align-items-center flex-column flex-md-row gap-3">
                                    <a href="{{ route('sector.seedling-preparations.index') }}" class="btn btn-outline-secondary btn-lg w-100 w-md-auto order-2 order-md-1">
                                        <i class="mdi mdi-arrow-left me-2"></i>
                                        Back to Seedling Preparations
                                    </a>
                                    <div class="d-flex gap-2 gap-md-3 w-100 w-md-auto order-1 order-md-2">
                                        <a href="{{ route('sector.seedling-preparations.index') }}" class="btn btn-outline-danger btn-lg flex-fill">
                                            <i class="mdi mdi-cancel me-2"></i>
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-success btn-lg flex-fill" id="submitBtn">
                                            <i class="mdi mdi-content-save-outline me-2"></i>
                                            Update Planting Schedule
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
        let selectedQuarters = new Set();
        let selectedMonths = new Set();
        let plantingSummaryRows = [];
        const plantingYear = {{ $seedlingPreparation->year }};
        const availableSeedlings = {{ $transformedSeedlingPlans[0]['total_prepared_quantity'] ?? 0 }};
        const alreadyPlanted = {{ $transformedSeedlingPlans[0]['total_planted_quantity'] ?? 0 }};
        const remainingSeedlings = {{ $transformedSeedlingPlans[0]['remaining_quantity'] ?? 0 }};

        // Available seedlings data
        const availableSeedlingsData = {!! json_encode($seedlingPreparation->preparation_data['selected_seedlings'] ?? []) !!};

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

        $(document).ready(function() {
            console.log('Document ready - initializing seedling planting scheduling for planting year:', plantingYear);
            console.log('Available seedlings:', availableSeedlings);
            console.log('Already planted:', alreadyPlanted);
            console.log('Remaining seedlings:', remainingSeedlings);

            // Update the planting year display
            $('#plantingYear').text(plantingYear);
            updateRemainingQuantity();

            // Form submission validation
            $('#seedlingPlanForm').on('submit', function(e) {
                console.log('Form submitted, validating...');
                if (!validateForm()) {
                    e.preventDefault();
                    showAlert('Please fix the validation errors before submitting.', 'error');
                } else {
                    console.log('Form validation passed, submitting...');
                    savePlantingJSON();
                }
            });

            renderQuarters();
            loadExistingPlantingDistribution();
            updateTotalScheduledBadge();

            // Mobile touch improvements
            improveMobileTouch();
        });

        /* -------------------------------
           SEEDLING QUANTITY VALIDATION
        ------------------------------- */
        function getCurrentScheduledQuantity() {
            return plantingSummaryRows.reduce((total, row) => total + row.quantity, 0);
        }

        function getCurrentScheduledParticipants() {
            return plantingSummaryRows.reduce((total, row) => total + row.participants, 0);
        }

        function getCurrentScheduledDays() {
            return plantingSummaryRows.reduce((total, row) => total + row.days, 0);
        }

        function getRemainingSeedlings() {
            return Math.max(0, availableSeedlings - alreadyPlanted - getCurrentScheduledQuantity());
        }

        function updateRemainingQuantity() {
            const remaining = getRemainingSeedlings();
            $('#remainingQuantity').text(remaining.toFixed(2));
            
            // Update color based on remaining quantity
            if (remaining === 0) {
                $('#remainingQuantity').removeClass('text-info').addClass('text-danger');
            } else if (remaining < availableSeedlings * 0.2) {
                $('#remainingQuantity').removeClass('text-info').addClass('text-warning');
            } else {
                $('#remainingQuantity').removeClass('text-danger text-warning').addClass('text-info');
            }
        }

        function validatePlantingInput(input, type) {
            const value = parseFloat($(input).val()) || 0;
            const feedback = $(input).closest('.planting-input-group').find('.validation-feedback');

            // Clear previous validation
            $(input).removeClass('is-valid is-invalid');
            feedback.empty();

            if (value < 0) {
                $(input).addClass('is-invalid');
                feedback.html('<small class="text-danger"><i class="mdi mdi-alert-circle me-1"></i>Value cannot be negative</small>');
                return false;
            }

            // For seedling quantity validation
            if (type === 'quantity') {
                const scheduledWithoutCurrent = plantingSummaryRows
                    .filter(row => !(row.month === $(input).data('month-name') && row.week === `Week ${$(input).data('week')}` && row.seedling === $(input).data('seedling-name')))
                    .reduce((total, row) => total + row.quantity, 0);

                const maxAllowed = Math.max(0, availableSeedlings - alreadyPlanted - scheduledWithoutCurrent);

                if (value > maxAllowed) {
                    $(input).addClass('is-invalid');
                    feedback.html(`<small class="text-danger"><i class="mdi mdi-alert-circle me-1"></i>Exceeds available seedlings (max: ${maxAllowed.toFixed(2)})</small>`);
                    return false;
                }
            }

            if (value > 0) {
                $(input).addClass('is-valid');
                feedback.html('<small class="text-success"><i class="mdi mdi-check-circle me-1"></i>Valid</small>');
            }

            return true;
        }

        function validateAllPlantingSchedules() {
            let isValid = true;
            const errors = [];

            const totalScheduled = getCurrentScheduledQuantity();
            const maxAllowed = availableSeedlings - alreadyPlanted;
            
            if (totalScheduled > maxAllowed) {
                isValid = false;
                errors.push(`Total scheduled seedlings (${totalScheduled.toFixed(2)}) exceeds available seedlings (${maxAllowed.toFixed(2)})`);
            }

            // Check individual inputs
            $('.seedling-quantity, .participants-input, .days-input').each(function() {
                const type = $(this).hasClass('seedling-quantity') ? 'quantity' : 
                           $(this).hasClass('participants-input') ? 'participants' : 'days';
                if (!validatePlantingInput(this, type)) {
                    isValid = false;
                }
            });

            return { isValid, errors };
        }

        /* -------------------------------
           QUARTERS MANAGEMENT
        ------------------------------- */
        function renderQuarters() {
            $("#quartersContainer").empty();

            Object.keys(EC_QUARTERS).forEach(q => {
                const quarterData = calculateQuarterScheduledData(parseInt(q));
                const isSelected = selectedQuarters.has(parseInt(q));
                const quarterName = `Q${q}`;
                const monthNames = EC_QUARTERS[q].map(m => EC_MONTHS[m - 1].name).join(", ");

                $("#quartersContainer").append(`
                <div class="col-6 col-md-3 mb-2 mb-md-3">
                    <div class="card quarter-card p-2 p-md-3 text-center ${isSelected ? 'card-selected' : ''}" data-q="${q}">
                        <div class="form-check mb-1 mb-md-2">
                            <input class="form-check-input quarter-checkbox" type="checkbox" id="quarter${q}" ${isSelected ? 'checked' : ''}>
                            <label class="form-check-label fw-bold fs-6 fs-md-5" for="quarter${q}">${quarterName}</label>
                        </div>
                        <small class="text-muted d-none d-md-block">${monthNames}</small>
                        <small class="text-muted d-md-none">${EC_QUARTERS[q].length} months</small>
                        <div class="mt-1 mt-md-2">
                            <small class="text-success">${quarterData.quantity.toFixed(2)} seedlings</small>
                        </div>
                        <div class="mt-1">
                            <small class="text-info">Year: ${plantingYear}</small>
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

        function selectAllQuarters() {
            Object.keys(EC_QUARTERS).forEach(q => {
                selectedQuarters.add(parseInt(q));
            });
            renderQuarters();
            renderMonths();
            updateBadges();
            showAlert('All quarters selected for planting year ' + plantingYear, 'success');
        }

        function clearAllQuarters() {
            selectedQuarters.clear();
            selectedMonths.clear();
            renderQuarters();
            renderMonths();
            $('#schedulingSection').hide();
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
                $("#schedulingSection").hide();
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
                const monthData = calculateMonthScheduledData(monthNum);
                const isSelected = selectedMonths.has(monthNum);

                $("#monthsContainer").append(`
                <div class="col-6 col-md-4 mb-2 mb-md-3">
                    <div class="card month-card p-2 p-md-3 text-center ${isSelected ? 'card-selected' : ''}" data-month="${monthNum}">
                        <div class="form-check mb-1 mb-md-2">
                            <input class="form-check-input month-checkbox" type="checkbox" id="month${monthNum}" ${isSelected ? 'checked' : ''}>
                            <label class="form-check-label fw-bold fs-6" for="month${monthNum}">${month.name}</label>
                        </div>
                        <small class="text-muted">Q${findQuarter(monthNum)}</small>
                        <div class="mt-1 mt-md-2">
                            <small class="text-success">${monthData.quantity.toFixed(2)} seedlings</small>
                        </div>
                        <div class="mt-1">
                            <small class="text-info">Year: ${plantingYear}</small>
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
                }

                renderScheduling();
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
            renderScheduling();
            updateBadges();
            showAlert('All months in selected quarters selected for planting year ' + plantingYear, 'success');
        }

        function clearAllMonths() {
            selectedMonths.clear();
            renderMonths();
            $('#schedulingSection').hide();
            updateBadges();
            showAlert('All months cleared', 'info');
        }

        /* -------------------------------
           SCHEDULING MANAGEMENT
        ------------------------------- */
        function renderScheduling() {
            $("#schedulingContainer").empty();

            if (selectedMonths.size === 0) {
                $("#schedulingSection").hide();
                return;
            }

            $("#schedulingSection").show();

            const monthsArray = Array.from(selectedMonths).sort((a, b) => a - b);

            monthsArray.forEach(monthNum => {
                const month = EC_MONTHS[monthNum - 1];
                const weeks = getWeeksForMonth(monthNum);
                const monthData = calculateMonthScheduledData(monthNum);

                $("#schedulingContainer").append(`
                <div class="month-scheduling-section mb-4 p-3 border rounded bg-light">
                    <div class="d-flex justify-content-between align-items-center flex-column flex-md-row mb-2 mb-md-3">
                        <h6 class="mb-1 mb-md-0 text-success fs-6">
                            <i class="mdi mdi-calendar-month me-2"></i>
                            ${month.name} (Q${findQuarter(monthNum)}) - ${plantingYear}
                        </h6>
                        <div class="text-center text-md-end mt-1 mt-md-0">
                            <span class="badge bg-info me-1 me-md-2 mb-1 mb-md-0">${monthData.quantity.toFixed(2)} seedlings</span>
                            <button type="button" class="btn btn-outline-success btn-xs btn-sm me-1" onclick="distributeEqually(${monthNum})">
                                <i class="mdi mdi-autorenew me-1"></i> Equal
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-xs btn-sm" onclick="clearMonthScheduling(${monthNum})">
                                <i class="mdi mdi-refresh me-1"></i> Clear
                            </button>
                        </div>
                    </div>
                    
                    <div class="weeks-scheduling">
                        ${weeks.map(week => `
                                <div class="week-scheduling mb-3 p-3 border rounded bg-white">
                                    <div class="row align-items-center mb-2">
                                        <div class="col-12 col-md-2 mb-2 mb-md-0">
                                            <strong class="fs-6">Week ${week.weekNumber}</strong><br>
                                            <small class="text-muted">Days ${week.start}-${week.end}</small>
                                            <br>
                                            <small class="text-info">Year: ${plantingYear}</small>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <div class="seedling-inputs">
                                                ${availableSeedlingsData.map(seedling => `
                                                    <div class="planting-input-group mb-3 p-2 border rounded bg-light">
                                                        ${renderSeedlingInputs(monthNum, week.weekNumber, month.name, seedling)}
                                                    </div>
                                                `).join('')}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                    </div>
                </div>
            `);
            });

            updateTotalScheduledBadge();
            enhancePlantingInputValidation();
        }

        function renderSeedlingInputs(monthNum, weekNumber, monthName, seedling) {
            const existing = plantingSummaryRows.find(row =>
                row.month === monthName && 
                row.week === `Week ${weekNumber}` && 
                row.seedling === seedling.name
            );

            const scheduled = getCurrentScheduledQuantity();
            const remaining = getRemainingSeedlings();

            // Calculate max allowed for this input
            const scheduledWithoutCurrent = plantingSummaryRows
                .filter(row => !(row.month === monthName && row.week === `Week ${weekNumber}` && row.seedling === seedling.name))
                .reduce((total, row) => total + row.quantity, 0);

            const maxAllowed = Math.max(0, availableSeedlings - alreadyPlanted - scheduledWithoutCurrent);

            return `
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-3">
                    <small class="fw-bold d-block text-success">${seedling.name}</small>
                    <small class="text-muted">Available: ${seedling.hanga_qophii}</small>
                    <div class="quantity-info mt-1">
                        <small class="text-success">Used: ${scheduled.toFixed(2)}/${availableSeedlings.toFixed(2)}</small><br>
                        <small class="${remaining === 0 ? 'text-danger' : 'text-warning'}">Available: ${remaining.toFixed(2)}</small>
                    </div>
                </div>
                <div class="col-4 col-md-3">
                    <label class="form-label small mb-1">Seedlings</label>
                    <div class="input-group input-group-sm">
                        <input type="number" 
                               step="0.01" 
                               min="0" 
                               max="${maxAllowed}"
                               class="form-control seedling-quantity"
                               data-month="${monthNum}"
                               data-week="${weekNumber}"
                               data-month-name="${monthName}"
                               data-seedling-name="${seedling.name}"
                               value="${existing ? existing.quantity : ''}"
                               placeholder="0.00"
                               ${remaining === 0 && !existing?.quantity ? 'disabled' : ''}>
                    </div>
                </div>
                <div class="col-4 col-md-3">
                    <label class="form-label small mb-1">Participants</label>
                    <div class="input-group input-group-sm">
                        <input type="number" 
                               step="0.01" 
                               min="0" 
                               class="form-control participants-input"
                               data-month="${monthNum}"
                               data-week="${weekNumber}"
                               data-month-name="${monthName}"
                               data-seedling-name="${seedling.name}"
                               value="${existing ? existing.participants : ''}"
                               placeholder="0.00">
                    </div>
                </div>
                <div class="col-4 col-md-3">
                    <label class="form-label small mb-1">Days</label>
                    <div class="input-group input-group-sm">
                        <input type="number" 
                               step="0.01" 
                               min="0" 
                               class="form-control days-input"
                               data-month="${monthNum}"
                               data-week="${weekNumber}"
                               data-month-name="${monthName}"
                               data-seedling-name="${seedling.name}"
                               value="${existing ? existing.days : ''}"
                               placeholder="0.00">
                    </div>
                </div>
            </div>
            <div class="validation-feedback mt-1"></div>
            ${remaining === 0 && !existing?.quantity ? '<small class="text-danger">No seedlings available</small>' : ''}
        `;
        }

        function enhancePlantingInputValidation() {
            $('.seedling-quantity, .participants-input, .days-input').off('input').on('input', function() {
                const type = $(this).hasClass('seedling-quantity') ? 'quantity' : 
                           $(this).hasClass('participants-input') ? 'participants' : 'days';
                validatePlantingInput(this, type);
                updateTotalScheduledBadge();
                updateRemainingQuantity();
            });
        }

        // Update planting scheduling when input changes
        $(document).on('input', '.seedling-quantity, .participants-input, .days-input', function() {
            const monthNum = parseInt($(this).data('month'));
            const weekNumber = parseInt($(this).data('week'));
            const seedlingName = $(this).data('seedling-name');
            const value = parseFloat($(this).val()) || 0;

            const month = EC_MONTHS[monthNum - 1];
            const type = $(this).hasClass('seedling-quantity') ? 'quantity' : 
                        $(this).hasClass('participants-input') ? 'participants' : 'days';

            // Validate before updating
            if (validatePlantingInput(this, type)) {
                updatePlantingScheduling(month.name, weekNumber, seedlingName, type, value);
                updateSummaryTable();
                updateTotalScheduledBadge();
                updateRemainingQuantity();
            }
        });

        function updatePlantingScheduling(monthName, weekNumber, seedlingName, type, value) {
            // Find or create scheduling entry
            let scheduling = plantingSummaryRows.find(d =>
                d.month === monthName && 
                d.week === `Week ${weekNumber}` && 
                d.seedling === seedlingName
            );

            if (!scheduling) {
                const weekData = getWeekData(EC_MONTHS.findIndex(m => m.name === monthName) + 1, weekNumber);
                scheduling = {
                    year: plantingYear,
                    quarter: `Q${findQuarter(EC_MONTHS.findIndex(m => m.name === monthName) + 1)}`,
                    month: monthName,
                    week: `Week ${weekNumber}`,
                    range: weekData.range,
                    seedling: seedlingName,
                    quantity: 0,
                    participants: 0,
                    days: 0
                };
                plantingSummaryRows.push(scheduling);
            }

            // Update the specific field
            scheduling[type] = value;

            // Remove if all values are 0
            if (scheduling.quantity === 0 && scheduling.participants === 0 && scheduling.days === 0) {
                plantingSummaryRows = plantingSummaryRows.filter(d =>
                    !(d.month === monthName && d.week === `Week ${weekNumber}` && d.seedling === seedlingName)
                );
            }
        }

        function distributeEqually(monthNum) {
            const month = EC_MONTHS[monthNum - 1];
            const remaining = getRemainingSeedlings();
            
            if (remaining <= 0) {
                showAlert(`No remaining seedlings available for scheduling`, 'warning');
                return;
            }

            const weeks = getWeeksForMonth(monthNum);
            if (weeks.length === 0) return;

            const equalQuantity = remaining / weeks.length;

            weeks.forEach(week => {
                availableSeedlingsData.forEach(seedling => {
                    const input = $(`.seedling-quantity[data-month="${monthNum}"][data-week="${week.weekNumber}"][data-seedling-name="${seedling.name}"]`);
                    if (input.length) {
                        input.val(equalQuantity.toFixed(2));
                        updatePlantingScheduling(month.name, week.weekNumber, seedling.name, 'quantity', equalQuantity);
                    }
                });
            });

            updateSummaryTable();
            updateTotalScheduledBadge();
            updateRemainingQuantity();
            enhancePlantingInputValidation();
            showAlert(`Distributed remaining seedlings equally across all weeks in ${month.name}`, 'success');
        }

        function clearMonthScheduling(monthNum) {
            const month = EC_MONTHS[monthNum - 1];
            plantingSummaryRows = plantingSummaryRows.filter(row => row.month !== month.name);

            // Clear all inputs for this month
            $(`.seedling-quantity[data-month="${monthNum}"], .participants-input[data-month="${monthNum}"], .days-input[data-month="${monthNum}"]`).val('');

            updateSummaryTable();
            savePlantingJSON();
            updateTotalScheduledBadge();
            updateRemainingQuantity();
            renderScheduling();
            enhancePlantingInputValidation();

            showAlert(`All schedules cleared for ${month.name}`, 'info');
        }

        function saveAllSchedules() {
            const validation = validateAllPlantingSchedules();

            if (!validation.isValid) {
                showAlert('Please fix validation errors before saving', 'error');
                validation.errors.forEach(error => showAlert(error, 'warning'));
                return;
            }

            // Update all planting schedules from inputs
            $('.seedling-quantity, .participants-input, .days-input').each(function() {
                const monthNum = parseInt($(this).data('month'));
                const weekNumber = parseInt($(this).data('week'));
                const seedlingName = $(this).data('seedling-name');
                const value = parseFloat($(this).val()) || 0;

                const month = EC_MONTHS[monthNum - 1];
                const type = $(this).hasClass('seedling-quantity') ? 'quantity' : 
                            $(this).hasClass('participants-input') ? 'participants' : 'days';

                updatePlantingScheduling(month.name, weekNumber, seedlingName, type, value);
            });

            updateSummaryTable();
            savePlantingJSON();
            updateTotalScheduledBadge();
            updateRemainingQuantity();
            showAlert('All planting schedules saved successfully for planting year ' + plantingYear + '!', 'success');
        }

        /* -------------------------------
           SUMMARY TABLE
        ------------------------------- */
        function updateSummaryTable() {
            $("#summaryTable").empty();
            let totalSeedlings = 0;
            let totalParticipants = 0;
            let totalDays = 0;

            if (plantingSummaryRows.length === 0) {
                $("#summaryTable").append(`
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">
                        <i class="mdi mdi-tree me-2"></i>
                        No seedling planting scheduled yet. Start by selecting quarters above.
                    </td>
                </tr>
            `);
            } else {
                plantingSummaryRows.forEach((row, i) => {
                    $("#summaryTable").append(`
                    <tr>
                        <td class="d-none d-md-table-cell">${row.year || plantingYear}</td>
                        <td>${row.seedling}</td>
                        <td class="d-none d-md-table-cell">${row.quarter}</td>
                        <td>${row.month}</td>
                        <td>${row.week}</td>
                        <td class="d-none d-md-table-cell">${row.range || ''}</td>
                        <td class="fw-bold text-success">${row.quantity.toFixed(2)}</td>
                        <td class="fw-bold text-info">${row.participants.toFixed(2)}</td>
                        <td class="fw-bold text-warning">${row.days.toFixed(2)}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger" onclick="removePlantingRow(${i})">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </td>
                    </tr>
                `);
                    totalSeedlings += row.quantity;
                    totalParticipants += row.participants;
                    totalDays += row.days;
                });
            }

            $("#summaryTotalSeedlings").text(totalSeedlings.toFixed(2));
            $("#summaryTotalParticipants").text(totalParticipants.toFixed(2));
            $("#summaryTotalDays").text(totalDays.toFixed(2));
        }

        function removePlantingRow(i) {
            plantingSummaryRows.splice(i, 1);
            updateSummaryTable();
            savePlantingJSON();
            updateTotalScheduledBadge();
            updateRemainingQuantity();
            renderScheduling(); // Refresh scheduling inputs
            showAlert('Planting schedule removed successfully!', 'info');
        }

        /* -------------------------------
           UTILITY FUNCTIONS
        ------------------------------- */
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

        function findQuarter(monthId) {
            return Object.keys(EC_QUARTERS).find(q => EC_QUARTERS[q].includes(monthId));
        }

        function calculateTotalScheduledQuantity() {
            return plantingSummaryRows.reduce((total, row) => total + row.quantity, 0);
        }

        function calculateQuarterScheduledData(quarter) {
            const quarterName = `Q${quarter}`;
            const quarterRows = plantingSummaryRows.filter(row => row.quarter === quarterName);
            
            return {
                quantity: quarterRows.reduce((total, row) => total + row.quantity, 0),
                participants: quarterRows.reduce((total, row) => total + row.participants, 0),
                days: quarterRows.reduce((total, row) => total + row.days, 0)
            };
        }

        function calculateMonthScheduledData(month) {
            const monthName = EC_MONTHS[month - 1].name;
            const monthRows = plantingSummaryRows.filter(row => row.month === monthName);
            
            return {
                quantity: monthRows.reduce((total, row) => total + row.quantity, 0),
                participants: monthRows.reduce((total, row) => total + row.participants, 0),
                days: monthRows.reduce((total, row) => total + row.days, 0)
            };
        }

        function updateTotalScheduledBadge() {
            const totalScheduled = calculateTotalScheduledQuantity();
            $('#totalScheduledBadge').text(totalScheduled.toFixed(2) + ' seedlings');
        }

        function updateBadges() {
            $("#selectedQuartersBadge").text(selectedQuarters.size + " quarters");
            $("#selectedMonthsBadge").text(selectedMonths.size + " months");
            $("#selectedWeeksBadge").text(plantingSummaryRows.length + " schedules");
        }

        function loadExistingPlantingDistribution() {
            let existing = {!! json_encode($seedlingPreparation->plantingdata_detail ?? '[]') !!};

            console.log('Loading existing planting distribution:', existing);

            if (existing && typeof existing === 'object') {
                if (existing.selected_seedlings && Array.isArray(existing.selected_seedlings)) {
                    plantingSummaryRows = [];
                    existing.selected_seedlings.forEach(seedling => {
                        if (seedling.weekly_planting && Array.isArray(seedling.weekly_planting)) {
                            seedling.weekly_planting.forEach(planting => {
                                if (planting.quantity > 0 || planting.participants > 0 || planting.days > 0) {
                                    plantingSummaryRows.push({
                                        year: planting.year || plantingYear,
                                        quarter: planting.quarter,
                                        month: planting.month,
                                        week: planting.week,
                                        range: planting.range || '',
                                        seedling: seedling.name,
                                        quantity: parseFloat(planting.hanga_dhaabbii) || 0,
                                        participants: parseFloat(planting.nama_hirmaate_dhaabbii) || 0,
                                        days: parseFloat(planting.baayina_guyyaa_dhaabbii) || 0
                                    });
                                }
                            });
                        }
                    });
                }

                // Restore selections
                plantingSummaryRows.forEach(row => {
                    const quarterNum = parseInt(row.quarter.replace('Q', ''));
                    selectedQuarters.add(quarterNum);

                    const monthNum = EC_MONTHS.findIndex(m => m.name === row.month) + 1;
                    if (monthNum > 0) selectedMonths.add(monthNum);
                });

                updateSummaryTable();
                updateBadges();

                if (selectedQuarters.size > 0) {
                    renderQuarters();
                    renderMonths();
                }

                if (selectedMonths.size > 0) {
                    renderScheduling();
                }

                updateTotalScheduledBadge();
                updateRemainingQuantity();
            }
        }

        function savePlantingJSON() {
            // Group by seedling
            const seedlingGroups = {};
            plantingSummaryRows.forEach(row => {
                if (!seedlingGroups[row.seedling]) {
                    seedlingGroups[row.seedling] = [];
                }
                seedlingGroups[row.seedling].push({
                    year: row.year,
                    quarter: row.quarter,
                    month: row.month,
                    week: row.week,
                    range: row.range,
                    hanga_dhaabbii: row.quantity,
                    nama_hirmaate_dhaabbii: row.participants,
                    baayina_guyyaa_dhaabbii: row.days
                });
            });

            const plantingData = {
                seedling_type: {
                    id: {{ $seedlingPreparation->preparation_data['seedling_type']['id'] ?? 0 }},
                    name: '{{ $seedlingPreparation->preparation_data['seedling_type']['name'] ?? 'N/A' }}'
                },
                selected_seedlings: Object.keys(seedlingGroups).map(seedlingName => ({
                    name: seedlingName,
                    weekly_planting: seedlingGroups[seedlingName]
                })),
                total_scheduled: calculateTotalScheduledQuantity(),
                total_participants: getCurrentScheduledParticipants(),
                total_days: getCurrentScheduledDays(),
                available_seedlings: availableSeedlings,
                already_planted: alreadyPlanted,
                remaining_seedlings: getRemainingSeedlings(),
                updated_at: new Date().toISOString()
            };

            $("#plantingData").val(JSON.stringify(plantingData));
            console.log('Saved plantingdata_detail for planting year ' + plantingYear + ':', plantingData);
        }

        function validateForm() {
            let isValid = true;
            const errors = [];

            // Enhanced distribution validation
            const scheduledQuantity = calculateTotalScheduledQuantity();

            if (scheduledQuantity === 0) {
                const proceed = confirm('No seedling planting has been scheduled for planting year ' +
                    plantingYear + '. Do you want to proceed without scheduling?');
                if (!proceed) {
                    errors.push('Please add planting scheduling or confirm to proceed without it');
                    isValid = false;
                }
            }

            // Validate planting data
            const plantingValidation = validateAllPlantingSchedules();
            if (!plantingValidation.isValid) {
                errors.push(...plantingValidation.errors);
                isValid = false;
            }

            // Check for individual input errors
            $('.seedling-quantity, .participants-input, .days-input').each(function() {
                if ($(this).hasClass('is-invalid')) {
                    errors.push('Some planting schedules have validation errors');
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

            document.getElementById(toastId).addEventListener('hidden.bs.toast', function() {
                this.remove();
            });
        }

        function improveMobileTouch() {
            // Prevent zoom on input focus for better mobile experience
            $('input, select, textarea').on('focus', function() {
                $('meta[name=viewport]').attr('content',
                    'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0');
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
    </script>

    <style>
        .seedling-plan-container {
            background: linear-gradient(135deg, #f0fff4 0%, #e6ffee 100%);
            min-height: 100vh;
        }

        .form-card {
            border-radius: 15px;
            overflow: hidden;
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #388e3c 0%, #2e7d32 100%) !important;
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
            color: #388e3c;
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
            background: linear-gradient(90deg, #388e3c, #2e7d32);
            border-radius: 2px;
            width: 60px;
        }

        .info-card {
            border-left: 4px solid #388e3c;
            min-height: 80px;
        }

        .seedling-card {
            border-left: 4px solid #4caf50;
            transition: all 0.3s ease;
        }

        .seedling-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* Quarter and Month Card Styles */
        .quarter-card,
        .month-card {
            transition: all 0.3s ease;
            border: 2px solid #e9ecef;
            cursor: pointer;
            min-height: 120px;
        }

        .quarter-card:hover,
        .month-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-color: #388e3c;
        }

        .card-selected {
            border-color: #388e3c !important;
            background-color: #f0fff4 !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(56, 142, 60, 0.15) !important;
        }

        .quarter-checkbox,
        .month-checkbox {
            transform: scale(1.2);
        }

        /* Scheduling Section Styles */
        .month-scheduling-section {
            border-left: 4px solid #388e3c !important;
        }

        .week-scheduling {
            border-left: 3px solid #2e7d32;
        }

        .planting-input-group {
            border-left: 3px solid #4caf50;
        }

        .seedling-quantity:focus,
        .participants-input:focus,
        .days-input:focus {
            border-color: #388e3c;
            box-shadow: 0 0 0 0.2rem rgba(56, 142, 60, 0.25);
        }

        .quantity-info {
            font-size: 0.75rem;
        }

        .quantity-info small {
            display: block;
            line-height: 1.2;
        }

        .btn-xs {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        /* Validation styles */
        .validation-feedback {
            min-height: 20px;
        }

        .seedling-quantity.is-valid,
        .participants-input.is-valid,
        .days-input.is-valid {
            border-color: #28a745;
        }

        .seedling-quantity.is-invalid,
        .participants-input.is-invalid,
        .days-input.is-invalid {
            border-color: #dc3545;
        }

        .table-success td {
            background-color: #e8f5e8 !important;
            border-bottom: 2px solid #c8e6c9;
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

            .seedling-scheduling-container {
                padding: 10px;
            }

            .info-card {
                min-height: 70px;
            }

            .quarter-card,
            .month-card {
                min-height: 100px;
            }

            .btn-lg {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }

            .planting-input-group {
                padding: 0.75rem !important;
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

            .seedling-scheduling-container {
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

            .row.g-2>[class*="col-"] {
                padding: 5px;
            }

            .week-scheduling {
                padding: 1rem !important;
            }
        }

        /* Custom scrollbar for better UX */
        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #388e3c;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #2e7d32;
        }

        .form-label.small {
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
    </style>
</x-layout>