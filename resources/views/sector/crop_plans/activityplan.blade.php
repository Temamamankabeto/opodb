<x-layout> 
    <div class="content-header">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ __('Land Preparation Planning') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('sector.crop-plans.index') }}">{{ __('Crop Plans') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('Land Preparation') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h5><i class="icon fas fa-ban"></i> {{ __('Validation Error!') }}</h5>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

               

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-warning">
                            
                             
                            <!-- /.card-header -->
                            <!-- form start -->
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

                                <div class="card-body">
                                    <!-- Modern Progress Stepper -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <div class="stepper">
                                                <div class="step active" data-step="1">
                                                    <div class="step-icon">
                                                        <i class="fas fa-calendar-quarter"></i>
                                                    </div>
                                                    <div class="step-label">{{ __('Select Quarters') }}</div>
                                                    <span class="step-badge" id="selectedQuartersBadge">0</span>
                                                </div>
                                                <div class="step" data-step="2">
                                                    <div class="step-icon">
                                                        <i class="fas fa-calendar-month"></i>
                                                    </div>
                                                    <div class="step-label">{{ __('Plan Distribution') }}</div>
                                                    <span class="step-badge" id="selectedWeeksBadge">0</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Instructions -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <div class="callout callout-info">
                                                <h6><i class="fas fa-info-circle mr-2"></i>{{ __('Instructions') }}</h6>
                                                <p class="mb-0">{{ __('Click on quarter cards to expand and see months. Click on month cards to expand and plan weekly distribution. Total area available:') }} <strong>{{ number_format($plan->area_to_plant, 2) }} {{ __('ha') }}</strong>.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Main Planning Container - Expandable Cards -->
                                    <div class="planning-container" id="planningContainer">
                                        <!-- Quarters with expandable months will be rendered here -->
                                    </div>

                                    <!-- Summary Section -->
                                    <div class="card card-outline card-success mt-4">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <i class="fas fa-list-alt mr-2"></i>
                                                {{ __('Distribution Summary') }}
                                            </h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-success btn-sm" onclick="saveAllDistributions()">
                                                    <i class="fas fa-save mr-2"></i> {{ __('Save All') }}
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Week') }}</th>
                                                            <th>{{ __('Month') }}</th>
                                                            <th>{{ __('Quarter') }}</th>
                                                            <th>{{ __('Date Range') }}</th>
                                                            <th>{{ __('Planned') }} ({{ __('ha') }})</th>
                                                            <th>{{ __('Status') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="summaryTable">
                                                        <tr>
                                                            <td colspan="6" class="text-center text-muted py-4">
                                                                <i class="fas fa-calendar-alt mr-2"></i>
                                                                {{ __('No distribution yet. Start by selecting quarters above.') }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="4" class="text-right font-weight-bold">{{ __('Total') }}:</td>
                                                            <td class="font-weight-bold text-primary" id="summaryTotal">0 {{ __('ha') }}</td>
                                                            <td class="font-weight-bold text-info" id="summaryCompletion">0%</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hidden field to store structured planning data -->
                                    <input type="hidden" name="plandivision_details" id="plandivisionDetails"
                                           value="{{ json_encode($plan->plandivision_details ?? []) }}">
                                </div>
                                <!-- /.card-body -->
                                
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="{{ route('sector.crop-plans.index') }}" class="btn btn-default">
                                                <i class="fas fa-arrow-left mr-2"></i> {{ __('Back to Plans') }}
                                            </a>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <button type="button" class="btn btn-danger" onclick="window.history.back()">
                                                <i class="fas fa-times mr-2"></i> {{ __('Cancel') }}
                                            </button>
                                            <button type="submit" class="btn btn-warning ml-2" id="submitBtn">
                                                <i class="fas fa-save mr-2"></i> {{ __('Update Plan') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>

    {{-- JavaScript Section --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Update JavaScript messages with translations
        const translations = {
            not_planned: "{{ __('Not Planned') }}",
            planned: "{{ __('Planned') }}",
            partially_planned: "{{ __('Partially Planned') }}",
            select_quarters_first: "{{ __('Please select quarters first') }}",
            fix_validation_errors: "{{ __('Please fix the validation errors before submitting.') }}",
            fix_validation_errors_save: "{{ __('Please fix validation errors before saving') }}",
            all_distributions_saved: "{{ __('All distributions saved successfully!') }}",
            distribution_removed: "{{ __('Distribution removed successfully!') }}",
            all_quarters_selected: "{{ __('All quarters selected for preparation year') }}",
            all_quarters_cleared: "{{ __('All quarters cleared') }}",
            all_months_selected: "{{ __('All months in selected quarters selected for preparation year') }}",
            all_months_cleared: "{{ __('All months cleared') }}",
            no_months_available: "{{ __('No months available in selected quarters.') }}",
            negative_value: "{{ __('Negative value') }}",
            exceeds_total_area: "{{ __('Exceeds total area') }}",
            ha: "{{ __('ha') }}",
            q: "{{ __('Q') }}",
            m: "{{ __('M') }}",
            w: "{{ __('W') }}",
            expand: "{{ __('Expand') }}",
            collapse: "{{ __('Collapse') }}",
            show_weeks: "{{ __('Show Weeks') }}",
            hide_weeks: "{{ __('Hide Weeks') }}"
        };

        let totalArea = parseFloat({{ $plan->area_to_plant }});
        let selectedQuarters = new Set();
        let selectedMonths = new Set();
        let weeklyDistributions = [];
        const preparationYear = {{ $plan->year - 1 }};

        // Ethiopian Calendar Data
        const EC_MONTHS = [
            { id: 1, name: "{{ __('Meskerem') }}", days: 30 },
            { id: 2, name: "{{ __('Tikimt') }}", days: 30 },
            { id: 3, name: "{{ __('Hidar') }}", days: 30 },
            { id: 4, name: "{{ __('Tahsas') }}", days: 30 },
            { id: 5, name: "{{ __('Tir') }}", days: 30 },
            { id: 6, name: "{{ __('Yekatit') }}", days: 30 },
            { id: 7, name: "{{ __('Megabit') }}", days: 30 },
            { id: 8, name: "{{ __('Miyazia') }}", days: 30 },
            { id: 9, name: "{{ __('Ginbot') }}", days: 30 },
            { id: 10, name: "{{ __('Sene') }}", days: 30 },
            { id: 11, name: "{{ __('Hamle') }}", days: 30 },
            { id: 12, name: "{{ __('Nehase') }}", days: 30 },
            { id: 13, name: "{{ __('Pagumen') }}", days: 5 }
        ];

        const EC_QUARTERS = {
            1: [1, 2, 3],
            2: [4, 5, 6],
            3: [7, 8, 9],
            4: [10, 11, 12, 13]
        };

        // Status calculation function
        function calculateStatus(planned) {
            if (planned === 0) return translations.not_planned;
            return translations.planned;
        }

        function getStatusClass(status) {
            switch(status) {
                case translations.not_planned: return 'badge-secondary';
                case translations.planned: return 'badge-success';
                default: return 'badge-secondary';
            }
        }

        // Initialize form
        $(document).ready(function() {
            console.log('Document ready - initializing land preparation planning for preparation year:', preparationYear);

            // Load existing distribution FIRST to populate selectedQuarters
            loadExistingDistribution();
            
            // Then render the planning container
            renderPlanningContainer();
            
            // Update summary
            updateSummaryCards();

            // Form submission validation
            $('#cropPlanForm').on('submit', function(e) {
                console.log('Form submitted, validating...');
                if (!validateForm()) {
                    e.preventDefault();
                    showAlert(translations.fix_validation_errors, 'error');
                } else {
                    console.log('Form validation passed, submitting...');
                    saveJSON();
                }
            });
        });

        /* -------------------------------
           RENDER PLANNING CONTAINER
        ------------------------------- */
        function renderPlanningContainer() {
            $("#planningContainer").empty();

            Object.keys(EC_QUARTERS).forEach(q => {
                const quarterNum = parseInt(q);
                const quarterData = calculateQuarterData(quarterNum);
                const isSelected = selectedQuarters.has(quarterNum);
                const quarterName = `Q${q}`;
                const quarterMonths = EC_QUARTERS[q];

                // Calculate quarter statistics
                const monthStats = quarterMonths.map(monthNum => {
                    const monthData = calculateMonthData(monthNum);
                    return {
                        monthNum,
                        planned: monthData.planned
                    };
                });
                
                const quarterPlanned = monthStats.reduce((sum, stat) => sum + stat.planned, 0);
                const quarterPercentage = totalArea > 0 ? (quarterPlanned / totalArea * 100).toFixed(1) : 0;

                $("#planningContainer").append(`
                    <div class="card quarter-card mb-3 ${isSelected ? 'card-primary' : 'card-secondary'}">
                        <div class="card-header d-flex justify-content-between align-items-center" style="cursor: pointer;">
                            <div class="d-flex align-items-center">
                                <div class="custom-control custom-checkbox mr-3">
                                    <input class="custom-control-input quarter-checkbox" type="checkbox"
                                           id="quarter${q}" ${isSelected ? 'checked' : ''}>
                                    <label for="quarter${q}" class="custom-control-label font-weight-bold h5 mb-0">
                                        ${quarterName} ({{ __('Year') }} ${preparationYear})
                                    </label>
                                </div>
                                <div class="ml-3">
                                    <span class="badge ${quarterPlanned > 0 ? 'badge-success' : 'badge-light'}">
                                        ${quarterPlanned.toFixed(1)} {{ __('ha') }}
                                    </span>
                                    <span class="badge badge-info ml-1">${quarterPercentage}%</span>
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-light toggle-quarter" data-quarter="${q}">
                                    <i class="fas fa-chevron-down"></i> ${translations.expand}
                                </button>
                            </div>
                        </div>
                        <div class="quarter-months collapse" id="quarterMonths${q}" data-quarter="${q}">
                            <div class="card-body p-0">
                                <!-- Months will be loaded here when quarter is expanded -->
                            </div>
                        </div>
                    </div>
                `);

                // Add event listeners for quarter
                $(`#quarter${q}`).on('change', function() {
                    handleQuarterChange(quarterNum, $(this).is(':checked'));
                });

                // Toggle quarter expansion
                $(`.quarter-card .card-header`).off('click').on('click', function(e) {
                    if (!$(e.target).is('input') && !$(e.target).is('label') && !$(e.target).is('button') && !$(e.target).closest('button').length) {
                        const quarterCard = $(this).closest('.quarter-card');
                        const quarterId = quarterCard.find('.toggle-quarter').data('quarter');
                        const monthsContainer = $(`#quarterMonths${quarterId}`);
                        
                        if (monthsContainer.hasClass('show')) {
                            collapseQuarter(quarterId);
                        } else {
                            expandQuarter(quarterId);
                        }
                    }
                });

                // Toggle button click
                $(`.toggle-quarter[data-quarter="${q}"]`).off('click').on('click', function(e) {
                    e.stopPropagation();
                    const quarterId = $(this).data('quarter');
                    const monthsContainer = $(`#quarterMonths${quarterId}`);
                    
                    if (monthsContainer.hasClass('show')) {
                        collapseQuarter(quarterId);
                    } else {
                        expandQuarter(quarterId);
                    }
                });

                // If quarter is selected and has months, expand it
                if (isSelected && quarterMonths.length > 0) {
                    setTimeout(() => {
                        expandQuarter(q);
                    }, 100);
                }
            });
            
            updateBadges();
            updateStepper();
        }

        /* -------------------------------
           QUARTER FUNCTIONS
        ------------------------------- */
        function expandQuarter(quarterId) {
            const monthsContainer = $(`#quarterMonths${quarterId}`);
            const toggleBtn = $(`.toggle-quarter[data-quarter="${quarterId}"]`);
            const quarterNum = parseInt(quarterId);
            
            // If container is already loaded, just show it
            if (monthsContainer.find('.month-card').length > 0) {
                monthsContainer.collapse('show');
                toggleBtn.html(`<i class="fas fa-chevron-up"></i> ${translations.collapse}`);
                return;
            }
            
            // Show loading state
            monthsContainer.html(`
                <div class="card-body">
                    <div class="text-center py-3">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </div>
                        <p class="text-muted mt-2">{{ __('Loading months...') }}</p>
                    </div>
                </div>
            `);
            monthsContainer.collapse('show');
            
            // Load months after a short delay for animation
            setTimeout(() => {
                renderMonthsForQuarter(quarterNum, quarterId);
                toggleBtn.html(`<i class="fas fa-chevron-up"></i> ${translations.collapse}`);
            }, 300);
        }

        function collapseQuarter(quarterId) {
            const monthsContainer = $(`#quarterMonths${quarterId}`);
            const toggleBtn = $(`.toggle-quarter[data-quarter="${quarterId}"]`);
            
            monthsContainer.collapse('hide');
            toggleBtn.html(`<i class="fas fa-chevron-down"></i> ${translations.expand}`);
        }

        function renderMonthsForQuarter(quarterNum, quarterId) {
            const monthsContainer = $(`#quarterMonths${quarterId} .card-body`);
            const quarterMonths = EC_QUARTERS[quarterNum];
            
            monthsContainer.empty();
            
            if (quarterMonths.length === 0) {
                monthsContainer.html(`
                    <div class="alert alert-info m-3">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ __('No months available in this quarter.') }}
                    </div>
                `);
                return;
            }

            // Create a row for the months
            monthsContainer.html('<div class="row p-3"></div>');
            const monthsRow = monthsContainer.find('.row');
            
            quarterMonths.forEach(monthNum => {
                const month = EC_MONTHS[monthNum - 1];
                const monthData = calculateMonthData(monthNum);
                const isSelected = selectedMonths.has(monthNum);
                const monthPlanned = monthData.planned;
                const monthPercentage = totalArea > 0 ? (monthPlanned / totalArea * 100).toFixed(1) : 0;
                
                // Calculate weeks for this month
                let weeks = [];
                let start = 1;
                while (start <= month.days) {
                    let end = Math.min(start + 6, month.days);
                    weeks.push({ start, end, weekNum: weeks.length + 1 });
                    start += 7;
                }

                monthsRow.append(`
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="card month-card h-100 ${isSelected ? 'border-primary' : ''}">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center" style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <div class="custom-control custom-checkbox mr-2">
                                        <input class="custom-control-input month-checkbox" type="checkbox"
                                               id="month${monthNum}_q${quarterId}" ${isSelected ? 'checked' : ''}
                                               data-month="${monthNum}" data-quarter="${quarterId}">
                                        <label for="month${monthNum}_q${quarterId}" class="custom-control-label font-weight-bold">
                                            ${month.name}
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge ${monthPlanned > 0 ? 'badge-success' : 'badge-light'}">
                                        ${monthPlanned.toFixed(1)} {{ __('ha') }}
                                    </span>
                                    <span class="badge badge-info ml-1">${monthPercentage}%</span>
                                    <button type="button" class="btn btn-sm btn-outline-secondary ml-2 toggle-weeks" 
                                            data-month="${monthNum}" data-quarter="${quarterId}">
                                        <i class="fas fa-calendar-week"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="month-weeks collapse" id="weeks${monthNum}_q${quarterId}">
                                <div class="card-body p-3">
                                    <h6 class="text-muted mb-3">
                                        <i class="fas fa-calendar-week mr-1"></i>
                                        {{ __('Weekly Planning for') }} ${month.name}
                                    </h6>
                                    
                                    <div class="weeks-container" id="weeksContainer${monthNum}_q${quarterId}">
                                        <!-- Weeks will be loaded here when month is expanded -->
                                    </div>
                                    
                                    <div class="month-summary mt-3 pt-3 border-top">
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted">{{ __('Month Total') }}:</small>
                                                <div class="font-weight-bold text-primary" id="monthTotal${monthNum}_q${quarterId}">
                                                    ${monthPlanned.toFixed(2)} {{ __('ha') }}
                                                </div>
                                            </div>
                                            <div class="col-6 text-right">
                                                <small class="text-muted">{{ __('Of Total Area') }}:</small>
                                                <div class="font-weight-bold text-info" id="monthPercentage${monthNum}_q${quarterId}">
                                                    ${monthPercentage}%
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            });

            // Add event listeners for months
            $(`.month-checkbox[data-quarter="${quarterId}"]`).off('change').on('change', function() {
                const monthNum = parseInt($(this).data('month'));
                const isChecked = $(this).is(':checked');
                handleMonthChange(monthNum, isChecked);
            });

            // Toggle month expansion
            $(`.month-card .card-header`).off('click').on('click', function(e) {
                if (!$(e.target).is('input') && !$(e.target).is('label') && !$(e.target).is('button') && !$(e.target).closest('button').length) {
                    const monthCard = $(this).closest('.month-card');
                    const monthNum = monthCard.find('.month-checkbox').data('month');
                    const quarterId = monthCard.find('.month-checkbox').data('quarter');
                    const weeksContainer = $(`#weeks${monthNum}_q${quarterId}`);
                    
                    if (weeksContainer.hasClass('show')) {
                        collapseMonthWeeks(monthNum, quarterId);
                    } else {
                        expandMonthWeeks(monthNum, quarterId);
                    }
                }
            });

            // Toggle weeks button click
            $(`.toggle-weeks[data-quarter="${quarterId}"]`).off('click').on('click', function(e) {
                e.stopPropagation();
                const monthNum = $(this).data('month');
                const quarterId = $(this).data('quarter');
                const weeksContainer = $(`#weeks${monthNum}_q${quarterId}`);
                
                if (weeksContainer.hasClass('show')) {
                    collapseMonthWeeks(monthNum, quarterId);
                } else {
                    expandMonthWeeks(monthNum, quarterId);
                }
            });

            // Expand months that are already selected
            quarterMonths.forEach(monthNum => {
                if (selectedMonths.has(monthNum)) {
                    setTimeout(() => {
                        expandMonthWeeks(monthNum, quarterId);
                    }, 200);
                }
            });
        }

        /* -------------------------------
           MONTH FUNCTIONS
        ------------------------------- */
        function expandMonthWeeks(monthNum, quarterId) {
            const weeksContainer = $(`#weeks${monthNum}_q${quarterId}`);
            const toggleBtn = $(`.toggle-weeks[data-month="${monthNum}"][data-quarter="${quarterId}"]`);
            const month = EC_MONTHS[monthNum - 1];
            
            // If weeks are already loaded, just show them
            if ($(`#weeksContainer${monthNum}_q${quarterId} .week-input-group`).length > 0) {
                weeksContainer.collapse('show');
                toggleBtn.html(`<i class="fas fa-calendar-times"></i>`);
                toggleBtn.removeClass('btn-outline-secondary').addClass('btn-outline-primary');
                return;
            }
            
            weeksContainer.collapse('show');
            
            // Calculate weeks for this month
            let weeks = [];
            let start = 1;
            while (start <= month.days) {
                let end = Math.min(start + 6, month.days);
                weeks.push({ start, end, weekNum: weeks.length + 1 });
                start += 7;
            }
            
            // Render weeks
            const weeksHtml = weeks.map(week => {
                const existingDistribution = weeklyDistributions.find(dist =>
                    dist.month === month.name && dist.week === `{{ __('Week') }} ${week.weekNum}`
                );
                
                const plannedArea = existingDistribution ? existingDistribution.planned_area : 0;
                const status = calculateStatus(plannedArea);
                const statusClass = getStatusClass(status);
                const percentage = totalArea > 0 ? (plannedArea / totalArea * 100).toFixed(1) : 0;
                
                return `
                    <div class="week-input-group mb-2" data-month="${monthNum}" data-week="${week.weekNum}">
                        <div class="d-flex align-items-center">
                            <div class="mr-2" style="width: 100px;">
                                <span class="badge badge-light">
                                    {{ __('Week') }} ${week.weekNum}
                                </span>
                                <small class="text-muted d-block">${week.start}-${week.end}</small>
                            </div>
                            <div class="flex-grow-1 mr-2">
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.01" min="0" max="${totalArea}"
                                           class="form-control form-control-sm planned-input"
                                           data-month="${monthNum}"
                                           data-week="${week.weekNum}"
                                           value="${plannedArea.toFixed(2)}"
                                           placeholder="0.00">
                                    <div class="input-group-append">
                                        <span class="input-group-text">{{ __('ha') }}</span>
                                    </div>
                                </div>
                                <div class="validation-feedback" style="font-size: 0.75rem; min-height: 18px;"></div>
                            </div>
                            <div class="text-center" style="width: 80px;">
                                <span class="badge ${statusClass}">${status}</span>
                                <div class="text-info small">${percentage}%</div>
                            </div>
                        </div>
                        <div class="progress mt-1" style="height: 3px;">
                            <div class="progress-bar ${plannedArea > 0 ? 'bg-success' : 'bg-secondary'}" 
                                 style="width: ${percentage}%"></div>
                        </div>
                    </div>
                `;
            }).join('');
            
            $(`#weeksContainer${monthNum}_q${quarterId}`).html(weeksHtml);
            
            // Add input validation
            $(`#weeksContainer${monthNum}_q${quarterId} .planned-input`).off('input').on('input', function() {
                validatePlannedInput(this);
            });
            
            toggleBtn.html(`<i class="fas fa-calendar-times"></i>`);
            toggleBtn.removeClass('btn-outline-secondary').addClass('btn-outline-primary');
        }

        function collapseMonthWeeks(monthNum, quarterId) {
            const weeksContainer = $(`#weeks${monthNum}_q${quarterId}`);
            const toggleBtn = $(`.toggle-weeks[data-month="${monthNum}"][data-quarter="${quarterId}"]`);
            
            weeksContainer.collapse('hide');
            toggleBtn.html(`<i class="fas fa-calendar-week"></i>`);
            toggleBtn.removeClass('btn-outline-primary').addClass('btn-outline-secondary');
        }

        /* -------------------------------
           QUARTER AND MONTH HANDLERS
        ------------------------------- */
        function handleQuarterChange(quarterNum, isChecked) {
            if (isChecked) {
                selectedQuarters.add(quarterNum);
                $(`.quarter-card .quarter-checkbox#quarter${quarterNum}`).closest('.card').removeClass('card-secondary').addClass('card-primary');
                
                // Expand the quarter to show months
                expandQuarter(quarterNum);
            } else {
                selectedQuarters.delete(quarterNum);
                $(`.quarter-card .quarter-checkbox#quarter${quarterNum}`).closest('.card').removeClass('card-primary').addClass('card-secondary');
                
                // Remove months from this quarter from selected months
                const quarterMonths = EC_QUARTERS[quarterNum];
                quarterMonths.forEach(month => {
                    selectedMonths.delete(month);
                    // Update month checkboxes
                    $(`#month${month}_q${quarterNum}`).prop('checked', false);
                    $(`#month${month}_q${quarterNum}`).closest('.month-card').removeClass('border-primary');
                });
                
                // Collapse the quarter
                collapseQuarter(quarterNum);
            }

            updateBadges();
            updateStepper();
            updateSummaryTable();
        }

        function handleMonthChange(monthNum, isChecked) {
            if (isChecked) {
                selectedMonths.add(monthNum);
                $(`#month${monthNum}_q${findQuarterId(monthNum)}`).closest('.month-card').addClass('border-primary');
                
                // Find which quarter this month belongs to and expand weeks
                const quarterId = findQuarterId(monthNum);
                if (quarterId && $(`#quarter${quarterId}`).is(':checked')) {
                    expandMonthWeeks(monthNum, quarterId);
                }
            } else {
                selectedMonths.delete(monthNum);
                $(`#month${monthNum}_q${findQuarterId(monthNum)}`).closest('.month-card').removeClass('border-primary');
                
                // Collapse weeks for this month
                const quarterId = findQuarterId(monthNum);
                if (quarterId) {
                    collapseMonthWeeks(monthNum, quarterId);
                }
            }

            updateBadges();
            updateStepper();
        }

        function findQuarterId(monthNum) {
            for (const [q, months] of Object.entries(EC_QUARTERS)) {
                if (months.includes(monthNum)) {
                    return q;
                }
            }
            return null;
        }

        /* -------------------------------
           INPUT VALIDATION
        ------------------------------- */
        function validatePlannedInput(input) {
            const value = parseFloat($(input).val()) || 0;
            const monthNum = parseInt($(input).data('month'));
            const weekNumber = parseInt($(input).data('week'));
            const feedback = $(input).closest('.week-input-group').find('.validation-feedback');

            $(input).removeClass('is-valid is-invalid border-success border-danger');
            feedback.empty();

            if (value < 0) {
                $(input).addClass('is-invalid border-danger');
                feedback.html('<small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>' + translations.negative_value + '</small>');
                return false;
            }

            if (value > totalArea) {
                $(input).addClass('is-invalid border-danger');
                feedback.html(`<small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>${translations.exceeds_total_area} (${totalArea.toFixed(2)} {{ __('ha') }})</small>`);
                return false;
            }

            if (value > 0) {
                $(input).addClass('is-valid border-success');
            }

            updateWeekDistribution(input);
            return true;
        }

        function updateWeekDistribution(input) {
            const monthNum = parseInt($(input).data('month'));
            const weekNumber = parseInt($(input).data('week'));
            const month = EC_MONTHS[monthNum - 1];
            const quarter = `Q${findQuarter(monthNum)}`;
            
            const plannedArea = parseFloat($(input).val()) || 0;
            
            // Find or create distribution
            let distribution = weeklyDistributions.find(dist =>
                dist.month === month.name && dist.week === `{{ __('Week') }} ${weekNumber}`
            );

            if (distribution) {
                distribution.planned_area = plannedArea;
            } else {
                const weekData = getWeekData(monthNum, weekNumber);
                distribution = {
                    year: preparationYear,
                    quarter: quarter,
                    month: month.name,
                    week: `{{ __('Week') }} ${weekNumber}`,
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
                    !(dist.month === month.name && dist.week === `{{ __('Week') }} ${weekNumber}`)
                );
            }

            // Update UI
            updateWeekUI(monthNum, weekNumber, plannedArea);
            updateMonthSummary(monthNum);
            updateSummaryTable();
            updateSummaryCards();
            updateBadges();
        }

        function updateWeekUI(monthNum, weekNumber, plannedArea) {
            const status = calculateStatus(plannedArea);
            const statusClass = getStatusClass(status);
            const percentage = totalArea > 0 ? (plannedArea / totalArea * 100) : 0;
            
            const weekInputGroup = $(`.week-input-group[data-month="${monthNum}"][data-week="${weekNumber}"]`);
            
            // Update status badge
            weekInputGroup.find('.badge').removeClass('badge-secondary badge-success badge-warning');
            weekInputGroup.find('.badge').addClass(statusClass).text(status);
            
            // Update percentage
            weekInputGroup.find('.text-info').text(percentage.toFixed(1) + '%');
            
            // Update progress bar
            const progressBar = weekInputGroup.find('.progress-bar');
            progressBar.removeClass('bg-secondary bg-success bg-warning');
            progressBar.addClass(plannedArea > 0 ? 'bg-success' : 'bg-secondary');
            progressBar.css('width', percentage + '%');
        }

        function updateMonthSummary(monthNum) {
            const monthData = calculateMonthData(monthNum);
            const monthPlanned = monthData.planned;
            const monthPercentage = totalArea > 0 ? (monthPlanned / totalArea * 100).toFixed(1) : 0;
            
            // Find all instances of this month in different quarters
            $(`[id^="month${monthNum}_q"]`).each(function() {
                const quarterId = $(this).data('quarter');
                if (quarterId) {
                    $(`#monthTotal${monthNum}_q${quarterId}`).text(monthPlanned.toFixed(2) + ' {{ __('ha') }}');
                    $(`#monthPercentage${monthNum}_q${quarterId}`).text(monthPercentage + '%');
                    
                    // Update month badge
                    const monthBadge = $(this).closest('.month-card').find('.badge-success, .badge-light');
                    monthBadge.removeClass('badge-success badge-light');
                    monthBadge.addClass(monthPlanned > 0 ? 'badge-success' : 'badge-light');
                    monthBadge.text(monthPlanned.toFixed(1) + ' {{ __('ha') }}');
                }
            });
            
            // Update quarter summaries
            const quarterNum = findQuarter(monthNum);
            if (quarterNum) {
                updateQuarterSummary(quarterNum);
            }
        }

        function updateQuarterSummary(quarterNum) {
            const quarterData = calculateQuarterData(quarterNum);
            const quarterPlanned = quarterData.planned;
            const quarterPercentage = totalArea > 0 ? (quarterPlanned / totalArea * 100).toFixed(1) : 0;
            
            // Update quarter badge
            $(`#quarter${quarterNum}`).closest('.quarter-card').find('.badge-success, .badge-light').each(function() {
                $(this).removeClass('badge-success badge-light');
                $(this).addClass(quarterPlanned > 0 ? 'badge-success' : 'badge-light');
                $(this).text(quarterPlanned.toFixed(1) + ' {{ __('ha') }}');
            });
            
            // Update quarter percentage
            $(`#quarter${quarterNum}`).closest('.quarter-card').find('.badge-info').text(quarterPercentage + '%');
        }

        /* -------------------------------
           LOAD EXISTING DISTRIBUTION
        -------------------------------- */
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
                updateStepper();
            }
        }

        /* -------------------------------
           UTILITY FUNCTIONS
        ------------------------------- */
        function calculateQuarterData(quarter) {
            const quarterName = `Q${quarter}`;
            const quarterDistributions = weeklyDistributions.filter(dist => dist.quarter === quarterName);
            
            return {
                planned: quarterDistributions.reduce((total, dist) => total + (dist.planned_area || 0), 0)
            };
        }

        function calculateMonthData(monthNum) {
            const monthName = EC_MONTHS[monthNum - 1].name;
            const monthDistributions = weeklyDistributions.filter(dist => dist.month === monthName);
            
            return {
                planned: monthDistributions.reduce((total, dist) => total + (dist.planned_area || 0), 0)
            };
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

        function updateBadges() {
            $("#selectedQuartersBadge").text(selectedQuarters.size);
            $("#selectedWeeksBadge").text(weeklyDistributions.length);
        }

        function updateStepper() {
            // Reset all steps
            $('.step').removeClass('active completed');
            
            // Activate steps based on progress
            if (selectedQuarters.size > 0) {
                $('.step[data-step="1"]').addClass('completed');
                $('.step[data-step="2"]').addClass('active');
            } else {
                $('.step[data-step="1"]').addClass('active');
            }
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
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        {{ __('No distribution yet. Start by selecting quarters above.') }}
                    </td>
                </tr>
            `);
            } else {
                weeklyDistributions.forEach((dist, i) => {
                    totalPlanned += dist.planned_area;
                    const statusClass = getStatusClass(dist.status);

                    $("#summaryTable").append(`
                    <tr>
                        <td>${dist.week}</td>
                        <td>${dist.month}</td>
                        <td>${dist.quarter}</td>
                        <td>${dist.range}</td>
                        <td class="font-weight-bold text-primary">${dist.planned_area.toFixed(2)} {{ __('ha') }}</td>
                        <td><span class="badge ${statusClass}">${dist.status}</span></td>
                    </tr>
                `);
                });
            }

            const overallCompletion = totalArea > 0 ? (totalPlanned / totalArea * 100) : 0;
            $("#summaryTotal").text(totalPlanned.toFixed(2) + " {{ __('ha') }}");
            $("#summaryCompletion").text(overallCompletion.toFixed(1) + "%");
        }

        function updateSummaryCards() {
            const totalPlanned = weeklyDistributions.reduce((total, dist) => total + dist.planned_area, 0);
            const completion = totalArea > 0 ? (totalPlanned / totalArea * 100) : 0;

            $('#plannedAreaSummary').text(totalPlanned.toFixed(2) + ' {{ __('ha') }}');
            $('#completionPercentage').text(completion.toFixed(1) + '%');
            $('#plannedProgress').css('width', completion + '%');
        }

        function saveAllDistributions() {
            let hasErrors = false;

            // Validate all inputs first
            $('.planned-input').each(function() {
                if (!validatePlannedInput(this)) hasErrors = true;
            });

            if (hasErrors) {
                showAlert(translations.fix_validation_errors_save, 'error');
                return;
            }

            saveJSON();
            showAlert(translations.all_distributions_saved, 'success');
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

        /* -------------------------------
           FORM VALIDATION & ALERTS
        ------------------------------- */
        function validateForm() {
            let isValid = true;
            const errors = [];

            // Check for individual input errors
            $('.planned-input').each(function() {
                if ($(this).hasClass('is-invalid')) {
                    errors.push('{{ __('Some distributions have validation errors') }}');
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
            <div class="row mb-3 validation-error-alert">
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> {{ __('Validation Error!') }}</h5>
                        <ul class="mb-0">
                            ${errors.map(error => `<li>${error}</li>`).join('')}
                        </ul>
                    </div>
                </div>
            </div>
        `;

            $('.content-header').after(errorHtml);
        }

        function showAlert(message, type = 'info') {
            // Create toast notification
            const toastId = 'toast-' + Date.now();
            const toastHtml = `
                <div id="${toastId}" class="toast align-items-center text-white bg-${type === 'error' ? 'danger' : type === 'warning' ? 'warning' : 'success'} border-0 position-fixed bottom-0 end-0 m-3" role="alert" style="z-index: 9999; min-width: 300px;">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} mr-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" style="opacity: 1;"></button>
                    </div>
                </div>
            `;

            $('body').append(toastHtml);
            
            // Show toast
            $(`#${toastId}`).fadeIn();
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                $(`#${toastId}`).fadeOut(400, function() {
                    $(this).remove();
                });
            }, 3000);
            
            // Close button
            $(`#${toastId} .btn-close`).click(function() {
                $(`#${toastId}`).fadeOut(400, function() {
                    $(this).remove();
                });
            });
        }
    </script>

  <style>
    /* Modern Stepper */
    .stepper {
        display: flex;
        justify-content: space-between;
        margin: 2rem 0;
        position: relative;
    }
    
    .stepper::before {
        content: '';
        position: absolute;
        top: 24px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #dee2e6;
        z-index: 1;
    }
    
    .step {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1;
    }
    
    .step-icon {
        width: 50px;
        height: 50px;
        background-color: white;
        border: 3px solid #dee2e6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.5rem;
        color: #6c757d;
        font-size: 1.25rem;
        transition: all 0.3s ease;
    }
    
    .step.active .step-icon {
        border-color: #17a2b8;
        background-color: #17a2b8;
        color: white;
    }
    
    .step.completed .step-icon {
        border-color: #28a745;
        background-color: #28a745;
        color: white;
    }
    
    .step-label {
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }
    
    .step.active .step-label {
        color: #17a2b8;
    }
    
    .step.completed .step-label {
        color: #28a745;
    }
    
    .step-badge {
        display: inline-block;
        padding: 0.25em 0.6em;
        font-size: 0.75rem;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 10rem;
        background-color: #6c757d;
        color: white;
    }
    
    .step.active .step-badge {
        background-color: #17a2b8;
    }
    
    .step.completed .step-badge {
        background-color: #28a745;
    }
    
    /* Expandable Cards */
    .quarter-card, .month-card {
        transition: all 0.3s ease;
        border-width: 2px;
    }
    
    .quarter-card:hover, .month-card:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .quarter-card .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    .quarter-card.card-primary .card-header {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
    }
    
    .quarter-card.card-primary .badge-light {
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: white !important;
    }
    
    .month-card .card-header {
        background-color: #f8f9fa;
        transition: background-color 0.3s ease;
    }
    
    .month-card.border-primary .card-header {
        background-color: rgba(0, 123, 255, 0.1);
        border-bottom: 2px solid #007bff;
    }
    
    /* Week Input Groups */
    .week-input-group {
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 5px;
        border-left: 3px solid #6c757d;
        transition: all 0.3s ease;
    }
    
    .week-input-group:hover {
        background-color: #e9ecef;
        border-left-color: #007bff;
    }
    
    /* Form Control Validation */
    .planned-input.is-valid {
        border-color: #28a745 !important;
    }
    
    .planned-input.is-invalid {
        border-color: #dc3545 !important;
    }
    
    /* Collapse Animation */
    .collapse {
        transition: all 0.3s ease;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .stepper {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .stepper::before {
            display: none;
        }
        
        .step {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            width: 100%;
        }
        
        .step-icon {
            margin: 0 1rem 0 0;
            flex-shrink: 0;
        }
        
        .step-label {
            text-align: left;
        }
        
        .week-input-group .d-flex {
            flex-direction: column;
        }
        
        .week-input-group .d-flex > div {
            width: 100% !important;
            margin-bottom: 5px;
        }
        
        .week-input-group .text-center {
            text-align: left !important;
        }
    }
    
    /* Animation for updates */
    @keyframes highlightUpdate {
        0% { background-color: rgba(40, 167, 69, 0.1); }
        100% { background-color: transparent; }
    }
    
    .updated {
        animation: highlightUpdate 1s ease;
    }
    
    /* Custom scrollbar for containers */
    .month-weeks .card-body {
        max-height: 500px;
        overflow-y: auto;
    }
    
    .month-weeks .card-body::-webkit-scrollbar {
        width: 6px;
    }
    
    .month-weeks .card-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .month-weeks .card-body::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }
    
    .month-weeks .card-body::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    /* Toast styling */
    .toast {
        display: none;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    /* Better spacing */
    .planning-container {
        padding: 10px 0;
    }
    
    .month-summary {
        background-color: rgba(0, 0, 0, 0.02);
        border-radius: 5px;
    }
    
    /* Progress bars */
    .progress {
        border-radius: 2px;
        overflow: hidden;
    }
    
    .progress-bar {
        border-radius: 2px;
        transition: width 0.3s ease;
    }
    
    /* Hover effects */
    .quarter-card .card-header:hover,
    .month-card .card-header:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }
    
    .quarter-card.card-primary .card-header:hover {
        background-color: rgba(0, 0, 0, 0.1);
    }
  </style>
</x-layout>