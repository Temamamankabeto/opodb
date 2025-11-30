<x-layout>
    <div class="container-fluid py-3 py-md-4 production-plan-container">
        <!-- Header Section -->
        <div class="row justify-content-center mb-4 mb-md-5">
            <div class="col-12 col-lg-10">
                <div class="page-header text-center">
                    <div class="header-icon mb-2 mb-md-3">
                        <i class="mdi mdi-sprout display-4 text-success"></i>
                    </div>
                    <h1 class="display-6 fw-bold text-success mb-2">Production Achievement Tracking</h1>
                    <p class="lead text-muted d-none d-md-block">Track production achievements for {{ Auth::user()->kebele->name }} Kebele</p>
                    <p class="text-muted d-md-none">Track production achievements</p>
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
                            Production Achievement Tracking
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

                            <!-- Achievement Summary -->
                            <div class="section-header mt-4 mt-md-5 mb-3 mb-md-4">
                                <h5 class="section-title fs-6 fs-md-5">
                                    <i class="mdi mdi-chart-bar me-2 text-info"></i>
                                    Achievement Overview (Production Year: <span id="productionYear">{{ $plan->year }}</span>)
                                </h5>
                                <div class="section-divider"></div>
                            </div>

                            <!-- Summary Cards -->
                            <div class="row g-2 g-md-3 mb-4">
                                <div class="col-6 col-md-4">
                                    <div class="card summary-card border-0 shadow-sm">
                                        <div class="card-body text-center">
                                            <i class="mdi mdi-target display-6 text-primary mb-2"></i>
                                            <div class="summary-value fs-4 fw-bold text-primary" id="totalYieldDisplay">{{ $plan->expected_yield }}</div>
                                            <div class="summary-label text-muted">Expected Yield (qt)</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="card summary-card border-0 shadow-sm">
                                        <div class="card-body text-center">
                                            <i class="mdi mdi-calendar-check display-6 text-warning mb-2"></i>
                                            <div class="summary-value fs-4 fw-bold text-warning" id="plannedYieldSummary">0</div>
                                            <div class="summary-label text-muted">Planned (qt)</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="card summary-card border-0 shadow-sm">
                                        <div class="card-body text-center">
                                            <i class="mdi mdi-check-circle display-6 text-success mb-2"></i>
                                            <div class="summary-value fs-4 fw-bold text-success" id="achievedYieldSummary">0</div>
                                            <div class="summary-label text-muted">Achieved (qt)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Production Achievement Section -->
                            <div class="section-header mt-4 mt-md-5 mb-3 mb-md-4">
                                <h5 class="section-title fs-6 fs-md-5">
                                    <i class="mdi mdi-calendar-multiple me-2 text-info"></i>
                                    Production Achievement Tracking
                                </h5>
                                <div class="section-divider"></div>
                                <p class="text-muted mt-2 d-none d-md-block">
                                    Track actual production achievements for each week in the production year ({{ $plan->year }}).
                                </p>
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
                                    </div>
                                </div>

                                <!-- Weeks Section - Achievement Only -->
                                <div class="card mb-3 mb-md-4 border-0 shadow-sm" id="weeksSection">
                                    <div class="card-header bg-light py-2 py-md-3">
                                        <div class="d-flex justify-content-between align-items-center flex-column flex-md-row">
                                            <h6 class="mb-1 mb-md-0 fs-6">
                                                <i class="mdi mdi-calendar-week me-2 text-warning"></i>
                                                Track Production Achievements ({{ $plan->year }})
                                            </h6>
                                            <div class="mt-1 mt-md-0 text-center text-md-end">
                                                <small class="text-muted me-2 d-none d-md-inline">Total: </small>
                                                <span class="badge bg-primary fs-6" id="totalDistributedBadge">0 qt / 0 qt</span>
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
                                                        Enter actual achieved yield for each week. Performance will be calculated automatically.
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
                                                Achievement Summary (Production Year: {{ $plan->year }})
                                            </h6>
                                            <div class="selected-summary mt-1 mt-md-0 text-center text-md-end">
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
                                                    <th>Planned (qt)</th>
                                                    <th>Achieved (qt)</th>
                                                    <th>Status</th>
                                                    <th>Performance</th>
                                                </tr>
                                                </thead>
                                                <tbody id="summaryTable">
                                                <tr>
                                                    <td colspan="9" class="text-center text-muted py-4">
                                                        <i class="mdi mdi-calendar-blank me-2"></i>
                                                        No achievement data yet. Start by entering achievements above.
                                                    </td>
                                                </tr>
                                                </tbody>
                                                <tfoot class="table-secondary">
                                                <tr>
                                                    <td colspan="5" class="text-end fw-bold d-none d-md-table-cell">Total:</td>
                                                    <td colspan="4" class="text-center fw-bold d-md-none">Total:</td>
                                                    <td class="fw-bold text-primary" id="summaryPlanned">0 qt</td>
                                                    <td class="fw-bold text-success" id="summaryAchieved">0 qt</td>
                                                    <td colspan="2" class="fw-bold text-info" id="summaryCompletion">0%</td>
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
                                            Update Achievements
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
        let weeklyDistributions = [];
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

        // Performance calculation functions
        function calculateStatus(planned, achieved) {
            if (achieved === 0) return 'Not Started';
            if (achieved < planned * 0.5) return 'In Progress';
            if (achieved < planned) return 'Nearly Done';
            if (achieved === planned) return 'Completed';
            return 'Over Achieved';
        }

        function calculatePerformance(planned, achieved) {
            const percentage = planned > 0 ? (achieved / planned * 100) : 0;
            
            if (percentage === 0) return 'Not Started';
            if (percentage < 50) return 'Poor';
            if (percentage < 80) return 'Below Target';
            if (percentage < 100) return 'Near Target';
            if (percentage === 100) return 'On Target';
            if (percentage <= 120) return 'Exceeded Target';
            return 'Outstanding';
        }

        function getPerformanceClass(performance) {
            switch(performance) {
                case 'Not Started': return 'bg-secondary';
                case 'Poor': return 'bg-danger';
                case 'Below Target': return 'bg-warning';
                case 'Near Target': return 'bg-info';
                case 'On Target': return 'bg-success';
                case 'Exceeded Target': return 'bg-primary';
                case 'Outstanding': return 'bg-purple';
                default: return 'bg-secondary';
            }
        }

        function getStatusClass(status) {
            switch(status) {
                case 'Not Started': return 'bg-secondary';
                case 'In Progress': return 'bg-warning';
                case 'Nearly Done': return 'bg-info';
                case 'Completed': return 'bg-success';
                case 'Over Achieved': return 'bg-primary';
                default: return 'bg-secondary';
            }
        }

        // Initialize form
        $(document).ready(function() {
            console.log('Document ready - initializing production achievement tracking for production year:', productionYear);

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

            loadExistingDistribution();
            updateSummaryCards();
            renderWeeks();

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
           WEEKS MANAGEMENT - ACHIEVEMENT ONLY
        ------------------------------- */
        function renderWeeks() {
            $("#weeksContainer").empty();

            // Group weeks by month for better organization
            const monthsWithData = [...new Set(weeklyDistributions.map(dist => dist.month))];
            
            if (monthsWithData.length === 0) {
                $("#weeksContainer").html(`
                    <div class="text-center text-muted py-4">
                        <i class="mdi mdi-calendar-blank me-2"></i>
                        No planned production distribution found. Please ensure the production plan has been properly set up.
                    </div>
                `);
                return;
            }

            monthsWithData.forEach(monthName => {
                const monthDistributions = weeklyDistributions.filter(dist => dist.month === monthName);
                const monthData = calculateMonthData(monthName);

                // Month header
                $("#weeksContainer").append(`
                <div class="month-section mb-3 mb-md-4 p-2 p-md-3 border rounded bg-light">
                    <div class="d-flex justify-content-between align-items-center flex-column flex-md-row mb-2 mb-md-3">
                        <h6 class="mb-1 mb-md-0 text-primary fs-6">
                            <i class="mdi mdi-calendar-month me-2"></i>
                            ${monthName} - ${productionYear}
                        </h6>
                        <div class="text-center text-md-end mt-1 mt-md-0">
                            <span class="badge bg-primary me-1 me-md-2 mb-1 mb-md-0">
                                Planned: ${monthData.planned.toFixed(2)} qt
                            </span>
                            <span class="badge bg-success me-1 me-md-2 mb-1 mb-md-0">
                                Achieved: ${monthData.achieved.toFixed(2)} qt
                            </span>
                            <span class="badge bg-info">
                                ${monthData.planned > 0 ? ((monthData.achieved / monthData.planned * 100).toFixed(1)) : 0}%
                            </span>
                        </div>
                    </div>
                    <div class="weeks-grid" id="weeksGrid${monthName.replace(/\s+/g, '')}">
                        <!-- Weeks for this month will be populated here -->
                    </div>
                </div>
            `);

                // Render weeks for this month
                renderWeeksForMonth(monthName, monthDistributions);
            });

            updateTotalDistributedBadge();
            enhanceAchievementInputValidation();
            updateBadges();
        }

        function renderWeeksForMonth(monthName, monthDistributions) {
            const weeksGrid = $(`#weeksGrid${monthName.replace(/\s+/g, '')}`);
            weeksGrid.empty();

            monthDistributions.forEach((dist, index) => {
                const plannedYield = dist.planned_yield || 0;
                const achievedYield = dist.achieved_yield || 0;
                const status = calculateStatus(plannedYield, achievedYield);
                const performance = calculatePerformance(plannedYield, achievedYield);
                const statusClass = getStatusClass(status);
                const performanceClass = getPerformanceClass(performance);

                weeksGrid.append(`
                <div class="week-combined-row mb-3 p-2 border rounded bg-white">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-2 mb-2 mb-md-0">
                            <strong class="fs-6">${dist.week}</strong><br>
                            <small class="text-muted">${dist.range}</small><br>
                            <small class="text-info">Year: ${productionYear}</small>
                        </div>
                        
                        <!-- Planning Section - Read Only -->
                        <div class="col-12 col-md-3 mb-2 mb-md-0">
                            <label class="form-label small fw-bold text-primary">Planned Yield</label>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01"
                                       class="form-control planned-input"
                                       value="${plannedYield}"
                                       readonly
                                       style="background-color: #f8f9fa; cursor: not-allowed;">
                                <span class="input-group-text">qt</span>
                            </div>
                            <small class="text-muted"><i class="mdi mdi-lock me-1"></i>Read only</small>
                        </div>
                        
                        <!-- Achievement Section - Editable -->
                        <div class="col-12 col-md-3 mb-2 mb-md-0">
                            <label class="form-label small fw-bold text-success">Achieved Yield</label>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01" min="0" max="${plannedYield}"
                                       class="form-control achievement-input"
                                       data-index="${index}"
                                       value="${achievedYield}"
                                       placeholder="0.00"
                                       oninput="validateAchievementInput(this)">
                                <span class="input-group-text">qt</span>
                            </div>
                            <div class="validation-feedback mt-1"></div>
                        </div>
                        
                        <!-- Status & Performance -->
                        <div class="col-12 col-md-4">
                            <div class="row text-center">
                                <div class="col-6">
                                    <small class="text-muted d-block">Status</small>
                                    <span class="badge ${statusClass} fs-6">${status}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Performance</small>
                                    <span class="badge ${performanceClass} fs-6">${performance}</span>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar ${performanceClass}" 
                                     style="width: ${plannedYield > 0 ? Math.min((achievedYield / plannedYield * 100), 100) : 0}%">
                                </div>
                            </div>
                            <small class="text-muted">
                                ${plannedYield > 0 ? ((achievedYield / plannedYield * 100).toFixed(1)) : 0}%
                            </small>
                        </div>
                    </div>
                </div>
            `);
            });
        }

        /* -------------------------------
           INPUT VALIDATION - ACHIEVEMENT ONLY
        ------------------------------- */
        function validateAchievementInput(input) {
            const index = parseInt($(input).data('index'));
            const value = parseFloat($(input).val()) || 0;
            const feedback = $(input).siblings('.validation-feedback');
            const plannedYield = weeklyDistributions[index].planned_yield || 0;

            $(input).removeClass('is-valid is-invalid');
            feedback.empty();

            if (value < 0) {
                $(input).addClass('is-invalid');
                feedback.html('<small class="text-danger"><i class="mdi mdi-alert-circle me-1"></i>Value cannot be negative</small>');
                return false;
            }

            if (value > plannedYield) {
                $(input).addClass('is-invalid');
                feedback.html(`<small class="text-danger"><i class="mdi mdi-alert-circle me-1"></i>Exceeds planned yield (${plannedYield.toFixed(2)} qt)</small>`);
                return false;
            }

            if (value > 0) {
                $(input).addClass('is-valid');
            }

            updateAchievementDistribution(input);
            return true;
        }

        function updateAchievementDistribution(input) {
            const index = parseInt($(input).data('index'));
            const achievedYield = parseFloat($(input).val()) || 0;
            
            // Update distribution
            weeklyDistributions[index].achieved_yield = achievedYield;

            // Update status and performance
            const plannedYield = weeklyDistributions[index].planned_yield || 0;
            weeklyDistributions[index].status = calculateStatus(plannedYield, achievedYield);
            weeklyDistributions[index].performance = calculatePerformance(plannedYield, achievedYield);
            weeklyDistributions[index].completion_percentage = plannedYield > 0 ? (achievedYield / plannedYield * 100) : 0;

            // Update UI
            updateWeekUI(index, plannedYield, achievedYield);
            updateSummaryTable();
            updateSummaryCards();
            updateTotalDistributedBadge();
        }

        function updateWeekUI(index, plannedYield, achievedYield) {
            const status = calculateStatus(plannedYield, achievedYield);
            const performance = calculatePerformance(plannedYield, achievedYield);
            const statusClass = getStatusClass(status);
            const performanceClass = getPerformanceClass(performance);
            
            const achievementInput = $(`.achievement-input[data-index="${index}"]`);
            const weekRow = achievementInput.closest('.week-combined-row');
            
            weekRow.find('.badge').removeClass('bg-secondary bg-warning bg-info bg-success bg-primary bg-danger bg-purple');
            weekRow.find('.badge:first').addClass(statusClass).text(status);
            weekRow.find('.badge:last').addClass(performanceClass).text(performance);
            
            weekRow.find('.progress-bar')
                .removeClass('bg-secondary bg-warning bg-info bg-success bg-primary bg-danger bg-purple')
                .addClass(performanceClass)
                .css('width', plannedYield > 0 ? Math.min((achievedYield / plannedYield * 100), 100) + '%' : '0%');
            
            weekRow.find('.text-muted').last().text(plannedYield > 0 ? ((achievedYield / plannedYield * 100).toFixed(1)) + '%' : '0%');
        }

        function enhanceAchievementInputValidation() {
            $('.achievement-input').off('input').on('input', function() {
                validateAchievementInput(this);
            });
        }

        function saveAllDistributions() {
            let hasErrors = false;

            // Validate all inputs first
            $('.achievement-input').each(function() {
                if (!validateAchievementInput(this)) hasErrors = true;
            });

            if (hasErrors) {
                showAlert('Please fix validation errors before saving', 'error');
                return;
            }

            saveJSON();
            showAlert('All achievements saved successfully!', 'success');
        }

        /* -------------------------------
           SUMMARY & DATA MANAGEMENT
        ------------------------------- */
        function updateSummaryTable() {
            $("#summaryTable").empty();
            let totalPlanned = 0;
            let totalAchieved = 0;

            if (weeklyDistributions.length === 0) {
                $("#summaryTable").append(`
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        <i class="mdi mdi-calendar-blank me-2"></i>
                        No achievement data yet. Start by entering achievements above.
                    </td>
                </tr>
            `);
            } else {
                weeklyDistributions.forEach((dist, i) => {
                    totalPlanned += dist.planned_yield;
                    totalAchieved += dist.achieved_yield;
                    const performanceClass = getPerformanceClass(dist.performance);
                    const statusClass = getStatusClass(dist.status);

                    $("#summaryTable").append(`
                    <tr>
                        <td class="d-none d-md-table-cell">${dist.year || productionYear}</td>
                        <td class="d-none d-md-table-cell">${dist.quarter}</td>
                        <td>${dist.month}</td>
                        <td>${dist.week}</td>
                        <td class="d-none d-md-table-cell">${dist.range}</td>
                        <td class="fw-bold text-primary">${dist.planned_yield.toFixed(2)} qt</td>
                        <td class="fw-bold text-success">${dist.achieved_yield.toFixed(2)} qt</td>
                        <td><span class="badge ${statusClass}">${dist.status}</span></td>
                        <td><span class="badge ${performanceClass}">${dist.performance}</span></td>
                    </tr>
                `);
                });
            }

            const overallCompletion = totalPlanned > 0 ? (totalAchieved / totalPlanned * 100) : 0;
            $("#summaryPlanned").text(totalPlanned.toFixed(2) + " qt");
            $("#summaryAchieved").text(totalAchieved.toFixed(2) + " qt");
            $("#summaryCompletion").text(overallCompletion.toFixed(1) + "%");
        }

        function updateSummaryCards() {
            const totalPlanned = weeklyDistributions.reduce((total, dist) => total + dist.planned_yield, 0);
            const totalAchieved = weeklyDistributions.reduce((total, dist) => total + dist.achieved_yield, 0);

            $('#plannedYieldSummary').text(totalPlanned.toFixed(2));
            $('#achievedYieldSummary').text(totalAchieved.toFixed(2));
        }

        function calculateMonthData(monthName) {
            const monthDistributions = weeklyDistributions.filter(dist => dist.month === monthName);
            
            return {
                planned: monthDistributions.reduce((total, dist) => total + (dist.planned_yield || 0), 0),
                achieved: monthDistributions.reduce((total, dist) => total + (dist.achieved_yield || 0), 0)
            };
        }

        function saveJSON() {
            const totalPlanned = weeklyDistributions.reduce((total, dist) => total + dist.planned_yield, 0);
            const totalAchieved = weeklyDistributions.reduce((total, dist) => total + dist.achieved_yield, 0);
            const overallCompletion = totalPlanned > 0 ? (totalAchieved / totalPlanned * 100) : 0;

            // Calculate overall performance
            let performanceCounts = {
                'Not Started': 0,
                'Poor': 0,
                'Below Target': 0,
                'Near Target': 0,
                'On Target': 0,
                'Exceeded Target': 0,
                'Outstanding': 0
            };

            weeklyDistributions.forEach(dist => {
                performanceCounts[dist.performance]++;
            });

            const maxPerformance = Object.keys(performanceCounts).reduce((a, b) => 
                performanceCounts[a] > performanceCounts[b] ? a : b
            );

            const productionData = {
                production_year: productionYear,
                weekly_production: weeklyDistributions,
                summary: {
                    total_planned: totalPlanned,
                    total_achieved: totalAchieved,
                    overall_completion: overallCompletion,
                    average_performance: maxPerformance,
                    updated_at: new Date().toISOString()
                }
            };

            $("#productionplanDetails").val(JSON.stringify(productionData));
            console.log('Saved production achievement data:', productionData);
            
            // Update summary cards
            updateSummaryCards();
        }

        function loadExistingDistribution() {
            let existing = {!! json_encode($plan->productionplan_details ?? '[]') !!};
            console.log('Loading existing production distribution:', existing);

            if (existing && typeof existing === 'object') {
                if (existing.weekly_production && Array.isArray(existing.weekly_production)) {
                    weeklyDistributions = existing.weekly_production.map(dist => ({
                        year: dist.year || productionYear,
                        quarter: dist.quarter,
                        month: dist.month,
                        week: dist.week,
                        range: dist.range,
                        planned_yield: parseFloat(dist.planned_yield || dist.yield || 0),
                        achieved_yield: parseFloat(dist.achieved_yield || 0),
                        status: dist.status || calculateStatus(parseFloat(dist.planned_yield || dist.yield || 0), parseFloat(dist.achieved_yield || 0)),
                        performance: dist.performance || calculatePerformance(parseFloat(dist.planned_yield || dist.yield || 0), parseFloat(dist.achieved_yield || 0)),
                        completion_percentage: dist.completion_percentage || (parseFloat(dist.planned_yield || dist.yield || 0) > 0 ? 
                            (parseFloat(dist.achieved_yield || 0) / parseFloat(dist.planned_yield || dist.yield || 0) * 100) : 0)
                    }));
                }

                updateSummaryTable();
                updateBadges();
                updateSummaryCards();
            }
        }

        /* -------------------------------
           UTILITY FUNCTIONS
        ------------------------------- */
        function updateTotalDistributedBadge() {
            const totalPlanned = weeklyDistributions.reduce((total, dist) => total + dist.planned_yield, 0);
            const totalAchieved = weeklyDistributions.reduce((total, dist) => total + dist.achieved_yield, 0);
            $('#totalDistributedBadge').text(totalPlanned.toFixed(2) + ' qt / ' + totalAchieved.toFixed(2) + ' qt');
        }

        function updateBadges() {
            $("#selectedWeeksBadge").text(weeklyDistributions.length + " weeks");
        }

        function validateForm() {
            let isValid = true;
            const errors = [];

            // Check for individual input errors
            $('.achievement-input').each(function() {
                if ($(this).hasClass('is-invalid')) {
                    errors.push('Some achievements have validation errors');
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

        /* Combined week row styling */
        .week-combined-row {
            transition: all 0.3s ease;
            border-left: 4px solid #007bff;
        }

        .week-combined-row:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .planned-input {
            background-color: #f8f9fa !important;
            cursor: not-allowed !important;
        }

        .achievement-input:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40,167,69,0.25);
        }

        .month-section {
            border-left: 4px solid #007bff !important;
        }

        /* Performance badge colors */
        .badge.bg-purple {
            background-color: #6f42c1 !important;
        }

        .badge.bg-primary {
            background-color: #007bff !important;
        }

        .badge.bg-success {
            background-color: #198754 !important;
        }

        .badge.bg-info {
            background-color: #0dcaf0 !important;
        }

        .badge.bg-warning {
            background-color: #ffc107 !important;
            color: #000 !important;
        }

        .badge.bg-danger {
            background-color: #dc3545 !important;
        }

        .badge.bg-secondary {
            background-color: #6c757d !important;
        }

        /* Progress bar colors */
        .progress-bar.bg-purple { background-color: #6f42c1 !important; }
        .progress-bar.bg-primary { background-color: #007bff !important; }
        .progress-bar.bg-success { background-color: #198754 !important; }
        .progress-bar.bg-info { background-color: #0dcaf0 !important; }
        .progress-bar.bg-warning { background-color: #ffc107 !important; }
        .progress-bar.bg-danger { background-color: #dc3545 !important; }
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

            .production-planning-container {
                padding: 10px;
            }

            .info-card {
                min-height: 70px;
            }

            .btn-lg {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }

            .week-combined-row .col-md-2,
            .week-combined-row .col-md-3,
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

            .production-planning-container {
                padding: 15px;
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

        .achievement-input.is-valid {
            border-color: #28a745;
        }

        .achievement-input.is-invalid {
            border-color: #dc3545;
        }
    </style>
</x-layout>