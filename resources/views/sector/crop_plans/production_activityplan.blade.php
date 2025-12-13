<x-layout>
    <div class="content-header">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ __('Production Planning') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('sector.crop-plans.index') }}">{{ __('Crop Plans') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('Production Planning') }}</li>
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
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-seedling mr-2"></i>{{ __('Production Planning') }}</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
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

                                <div class="card-body">
                                    

                                   

                                    <!-- Production Planning Section -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h5 class="text-info mb-3">
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                {{ __('Production Planning') }}
                                            </h5>
                                            <hr class="mt-0">
                                            <div class="callout callout-info mt-2">
                                                <h6><i class="fas fa-info-circle mr-2"></i>{{ __('Yield Distribution Status') }}</h6>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <small class="text-muted">{{ __('Total Expected Yield') }}</small>
                                                        <div class="font-weight-bold text-primary" id="totalYieldDisplay2">{{ $plan->expected_yield }} {{ __('qt') }}</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small class="text-muted">{{ __('Distributed Yield') }}</small>
                                                        <div class="font-weight-bold text-success" id="distributedYieldDisplay">0 {{ __('qt') }}</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small class="text-muted">{{ __('Remaining Yield') }}</small>
                                                        <div class="font-weight-bold text-warning" id="remainingYieldDisplay">{{ $plan->expected_yield }} {{ __('qt') }}</div>
                                                    </div>
                                                </div>
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
                                                {{ __('Yield Distribution Summary') }}
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
                                                            <th>{{ __('Yield') }} ({{ __('qt') }})</th>
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
                                                            <td class="font-weight-bold text-primary" id="summaryTotal">0 {{ __('qt') }}</td>
                                                            <td class="font-weight-bold text-info" id="summaryCompletion">0%</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
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
            all_quarters_selected: "{{ __('All quarters selected for production year') }}",
            all_quarters_cleared: "{{ __('All quarters cleared') }}",
            all_months_selected: "{{ __('All months in selected quarters selected for production year') }}",
            all_months_cleared: "{{ __('All months cleared') }}",
            no_months_available: "{{ __('No months available in selected quarters.') }}",
            negative_value: "{{ __('Negative value') }}",
            exceeds_total_yield: "{{ __('Exceeds total yield') }}",
            qt: "{{ __('qt') }}",
            ha: "{{ __('ha') }}",
            q: "{{ __('Q') }}",
            m: "{{ __('M') }}",
            w: "{{ __('W') }}",
            expand: "{{ __('Expand') }}",
            collapse: "{{ __('Collapse') }}",
            show_weeks: "{{ __('Show Weeks') }}",
            hide_weeks: "{{ __('Hide Weeks') }}"
        };

        let totalYield = parseFloat({{ $plan->expected_yield }});
        let selectedQuarters = new Set();
        let selectedMonths = new Set();
        let weeklyDistributions = [];
        const productionYear = {{ $plan->year }};

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
        function calculateStatus(yieldValue) {
            if (yieldValue === 0) return translations.not_planned;
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
            console.log('Document ready - initializing production planning for production year:', productionYear);

            // Load existing distribution FIRST to populate selectedQuarters
            loadExistingDistribution();
            
            // Then render the planning container
            renderPlanningContainer();
            
            // Update summary
            updateYieldSummary();

            // Form submission validation
            $('#productionPlanForm').on('submit', function(e) {
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
                        yield: monthData.yield
                    };
                });
                
                const quarterYield = monthStats.reduce((sum, stat) => sum + stat.yield, 0);
                const quarterPercentage = totalYield > 0 ? (quarterYield / totalYield * 100).toFixed(1) : 0;

                $("#planningContainer").append(`
                    <div class="card quarter-card mb-3 ${isSelected ? 'card-primary' : 'card-secondary'}">
                        <div class="card-header d-flex justify-content-between align-items-center" style="cursor: pointer;">
                            <div class="d-flex align-items-center">
                                <div class="custom-control custom-checkbox mr-3">
                                    <input class="custom-control-input quarter-checkbox" type="checkbox"
                                           id="quarter${q}" ${isSelected ? 'checked' : ''}>
                                    <label for="quarter${q}" class="custom-control-label font-weight-bold h5 mb-0">
                                        ${quarterName} ({{ __('Year') }} ${productionYear})
                                    </label>
                                </div>
                                <div class="ml-3">
                                    <span class="badge ${quarterYield > 0 ? 'badge-success' : 'badge-light'}">
                                        ${quarterYield.toFixed(1)} {{ __('qt') }}
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
                const monthYield = monthData.yield;
                const monthPercentage = totalYield > 0 ? (monthYield / totalYield * 100).toFixed(1) : 0;
                
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
                                    <span class="badge ${monthYield > 0 ? 'badge-success' : 'badge-light'}">
                                        ${monthYield.toFixed(1)} {{ __('qt') }}
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
                                        {{ __('Yield Distribution for') }} ${month.name}
                                    </h6>
                                    
                                    <div class="weeks-container" id="weeksContainer${monthNum}_q${quarterId}">
                                        <!-- Weeks will be loaded here when month is expanded -->
                                    </div>
                                    
                                    <div class="month-summary mt-3 pt-3 border-top">
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted">{{ __('Month Total') }}:</small>
                                                <div class="font-weight-bold text-success" id="monthTotal${monthNum}_q${quarterId}">
                                                    ${monthYield.toFixed(2)} {{ __('qt') }}
                                                </div>
                                            </div>
                                            <div class="col-6 text-right">
                                                <small class="text-muted">{{ __('Of Total Yield') }}:</small>
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
                
                const yieldValue = existingDistribution ? existingDistribution.yield : 0;
                const status = calculateStatus(yieldValue);
                const statusClass = getStatusClass(status);
                const percentage = totalYield > 0 ? (yieldValue / totalYield * 100).toFixed(1) : 0;
                
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
                                    <input type="number" step="0.01" min="0" max="${totalYield}"
                                           class="form-control form-control-sm yield-input"
                                           data-month="${monthNum}"
                                           data-week="${week.weekNum}"
                                           value="${yieldValue.toFixed(2)}"
                                           placeholder="0.00">
                                    <div class="input-group-append">
                                        <span class="input-group-text">{{ __('qt') }}</span>
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
                            <div class="progress-bar ${yieldValue > 0 ? 'bg-success' : 'bg-secondary'}" 
                                 style="width: ${percentage}%"></div>
                        </div>
                    </div>
                `;
            }).join('');
            
            $(`#weeksContainer${monthNum}_q${quarterId}`).html(weeksHtml);
            
            // Add input validation
            $(`#weeksContainer${monthNum}_q${quarterId} .yield-input`).off('input').on('input', function() {
                validateYieldInput(this);
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

            updateSummaryTable();
            updateYieldSummary();
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
        function validateYieldInput(input) {
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

            if (value > totalYield) {
                $(input).addClass('is-invalid border-danger');
                feedback.html(`<small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>${translations.exceeds_total_yield} (${totalYield.toFixed(2)} {{ __('qt') }})</small>`);
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
            
            const yieldValue = parseFloat($(input).val()) || 0;
            
            // Find or create distribution
            let distribution = weeklyDistributions.find(dist =>
                dist.month === month.name && dist.week === `{{ __('Week') }} ${weekNumber}`
            );

            if (distribution) {
                distribution.yield = yieldValue;
            } else {
                const weekData = getWeekData(monthNum, weekNumber);
                distribution = {
                    year: productionYear,
                    quarter: quarter,
                    month: month.name,
                    week: `{{ __('Week') }} ${weekNumber}`,
                    range: weekData.range,
                    yield: yieldValue
                };
                weeklyDistributions.push(distribution);
            }

            // Update status
            distribution.status = calculateStatus(yieldValue);

            // Remove if yield is 0
            if (yieldValue === 0) {
                weeklyDistributions = weeklyDistributions.filter(dist =>
                    !(dist.month === month.name && dist.week === `{{ __('Week') }} ${weekNumber}`)
                );
            }

            // Update UI
            updateWeekUI(monthNum, weekNumber, yieldValue);
            updateMonthSummary(monthNum);
            updateSummaryTable();
            updateYieldSummary();
        }

        function updateWeekUI(monthNum, weekNumber, yieldValue) {
            const status = calculateStatus(yieldValue);
            const statusClass = getStatusClass(status);
            const percentage = totalYield > 0 ? (yieldValue / totalYield * 100) : 0;
            
            const weekInputGroup = $(`.week-input-group[data-month="${monthNum}"][data-week="${weekNumber}"]`);
            
            // Update status badge
            weekInputGroup.find('.badge').removeClass('badge-secondary badge-success badge-warning');
            weekInputGroup.find('.badge').addClass(statusClass).text(status);
            
            // Update percentage
            weekInputGroup.find('.text-info').text(percentage.toFixed(1) + '%');
            
            // Update progress bar
            const progressBar = weekInputGroup.find('.progress-bar');
            progressBar.removeClass('bg-secondary bg-success bg-warning');
            progressBar.addClass(yieldValue > 0 ? 'bg-success' : 'bg-secondary');
            progressBar.css('width', percentage + '%');
        }

        function updateMonthSummary(monthNum) {
            const monthData = calculateMonthData(monthNum);
            const monthYield = monthData.yield;
            const monthPercentage = totalYield > 0 ? (monthYield / totalYield * 100).toFixed(1) : 0;
            
            // Find all instances of this month in different quarters
            $(`[id^="month${monthNum}_q"]`).each(function() {
                const quarterId = $(this).data('quarter');
                if (quarterId) {
                    $(`#monthTotal${monthNum}_q${quarterId}`).text(monthYield.toFixed(2) + ' {{ __('qt') }}');
                    $(`#monthPercentage${monthNum}_q${quarterId}`).text(monthPercentage + '%');
                    
                    // Update month badge
                    const monthBadge = $(this).closest('.month-card').find('.badge-success, .badge-light');
                    monthBadge.removeClass('badge-success badge-light');
                    monthBadge.addClass(monthYield > 0 ? 'badge-success' : 'badge-light');
                    monthBadge.text(monthYield.toFixed(1) + ' {{ __('qt') }}');
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
            const quarterYield = quarterData.yield;
            const quarterPercentage = totalYield > 0 ? (quarterYield / totalYield * 100).toFixed(1) : 0;
            
            // Update quarter badge
            $(`#quarter${quarterNum}`).closest('.quarter-card').find('.badge-success, .badge-light').each(function() {
                $(this).removeClass('badge-success badge-light');
                $(this).addClass(quarterYield > 0 ? 'badge-success' : 'badge-light');
                $(this).text(quarterYield.toFixed(1) + ' {{ __('qt') }}');
            });
            
            // Update quarter percentage
            $(`#quarter${quarterNum}`).closest('.quarter-card').find('.badge-info').text(quarterPercentage + '%');
        }

        /* -------------------------------
           LOAD EXISTING DISTRIBUTION
        -------------------------------- */
        function loadExistingDistribution() {
            let existing = {!! json_encode($plan->productionplan_details ?? '[]') !!};
            console.log('Loading existing production distribution:', existing);

            if (existing && typeof existing === 'object') {
                if (existing.weekly_distribution && Array.isArray(existing.weekly_distribution)) {
                    weeklyDistributions = existing.weekly_distribution.map(dist => ({
                        year: dist.year || productionYear,
                        quarter: dist.quarter,
                        month: dist.month,
                        week: dist.week,
                        range: dist.range,
                        yield: parseFloat(dist.yield || 0),
                        status: dist.status || calculateStatus(parseFloat(dist.yield || 0))
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
                updateYieldSummary();
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
                yield: quarterDistributions.reduce((total, dist) => total + (dist.yield || 0), 0)
            };
        }

        function calculateMonthData(monthNum) {
            const monthName = EC_MONTHS[monthNum - 1].name;
            const monthDistributions = weeklyDistributions.filter(dist => dist.month === monthName);
            
            return {
                yield: monthDistributions.reduce((total, dist) => total + (dist.yield || 0), 0)
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
            let totalYieldDistributed = 0;

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
                    totalYieldDistributed += dist.yield;
                    const statusClass = getStatusClass(dist.status);

                    $("#summaryTable").append(`
                    <tr>
                        <td>${dist.week}</td>
                        <td>${dist.month}</td>
                        <td>${dist.quarter}</td>
                        <td>${dist.range}</td>
                        <td class="font-weight-bold text-success">${dist.yield.toFixed(2)} {{ __('qt') }}</td>
                        <td><span class="badge ${statusClass}">${dist.status}</span></td>
                    </tr>
                `);
                });
            }

            const overallCompletion = totalYield > 0 ? (totalYieldDistributed / totalYield * 100) : 0;
            $("#summaryTotal").text(totalYieldDistributed.toFixed(2) + " {{ __('qt') }}");
            $("#summaryCompletion").text(overallCompletion.toFixed(1) + "%");
        }

        function updateYieldSummary() {
            const distributed = weeklyDistributions.reduce((total, dist) => total + dist.yield, 0);
            const remaining = totalYield - distributed;

            $("#distributedYieldDisplay").text(distributed.toFixed(2) + " {{ __('qt') }}");
            $("#remainingYieldDisplay").text(remaining.toFixed(2) + " {{ __('qt') }}");

            const progress = totalYield > 0 ? (distributed / totalYield * 100) : 0;
            $("#distributionProgress").css('width', progress + '%');
        }

        function saveAllDistributions() {
            let hasErrors = false;

            // Validate all inputs first
            $('.yield-input').each(function() {
                if (!validateYieldInput(this)) hasErrors = true;
            });

            if (hasErrors) {
                showAlert(translations.fix_validation_errors_save, 'error');
                return;
            }

            saveJSON();
            showAlert(translations.all_distributions_saved, 'success');
        }

        function saveJSON() {
            const totalDistributed = weeklyDistributions.reduce((total, dist) => total + dist.yield, 0);
            const overallCompletion = totalYield > 0 ? (totalDistributed / totalYield * 100) : 0;

            const planData = {
                production_year: productionYear,
                weekly_distribution: weeklyDistributions,
                summary: {
                    total_distributed: totalDistributed,
                    overall_completion: overallCompletion,
                    updated_at: new Date().toISOString()
                }
            };

            $("#productionplanDetails").val(JSON.stringify(planData));
            console.log('Saved production plan data:', planData);
        }

        /* -------------------------------
           FORM VALIDATION & ALERTS
        ------------------------------- */
        function validateForm() {
            let isValid = true;
            const errors = [];

            // Check for individual input errors
            $('.yield-input').each(function() {
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
        /* Card selection styles */
        .card-selected {
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25) !important;
        }

        /* Progress bar colors */
        .bg-purple {
            background-color: #6f42c1 !important;
        }

        /* Info box hover effects */
        .info-box:hover {
            transform: translateY(-2px);
            transition: transform 0.3s ease;
        }

        /* Card hover effects */
        .card-outline:hover:not(.card-selected) {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Quarter and month card hover */
        .quarter-card, .month-card {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .quarter-card:hover:not(.card-selected) {
            border-color: #007bff !important;
        }

        /* Custom checkbox styling */
        .custom-control-input:checked ~ .custom-control-label::before {
            border-color: #007bff;
            background-color: #007bff;
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

        /* Validation feedback */
        .validation-feedback {
            min-height: 20px;
        }

        /* Toast positioning */
        .toast {
            display: none;
            min-width: 300px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-title {
                font-size: 0.9rem;
            }
            
            .info-box-number {
                font-size: 1.2rem;
            }
            
            .table-responsive {
                font-size: 0.85rem;
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
            
            .yield-input {
                font-size: 0.8rem !important;
            }
        }

        /* Table styling */
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }

        /* Input validation states */
        .yield-input.is-valid {
            border-color: #28a745 !important;
        }

        .yield-input.is-invalid {
            border-color: #dc3545 !important;
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

        /* Collapse Animation */
        .collapse {
            transition: all 0.3s ease;
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