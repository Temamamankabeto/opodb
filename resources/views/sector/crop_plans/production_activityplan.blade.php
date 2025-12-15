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

                @if (session('success'))
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h5><i class="icon fas fa-check"></i> {{ __('Success!') }}</h5>
                                {{ session('success') }}
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-warning">
                            <div class="card-header" style="background-color: white; color: black;">
                                <h3 class="card-title" style="color: black;"><i class="fas fa-seedling mr-2"></i>{{ __('Production Planning') }}</h3>
                                <div class="card-tools">
                                    <span class="badge badge-light" style="color: black; background-color: white; border: 1px solid #ddd;">{{ $plan->crop->name ?? __('N/A') }} - {{ $plan->year }} {{ $plan->season }}</span>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form action="{{ route('sector.crop-plans.update-production-plan', $plan->id) }}" method="POST" id="productionPlanForm">
                                @csrf
                                @method('PUT')

                                <!-- Hidden field for production plan details -->
                                <input type="hidden" name="productionplan_details" id="productionplanDetails"
                                       value="{{ json_encode($plan->productionplan_details ?? ['year' => $plan->year, 'quarters' => []]) }}">

                                <div class="card-body" style="background-color: white; color: black;">
                                    <!-- Area Summary Boxes -->
                                    <div class="row mb-4">
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box" style="background-color: white; border: 1px solid #ddd; color: black;">
                                                <span class="info-box-icon" style="background-color: white; color: black;"><i class="fas fa-weight-hanging"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text" style="color: black;">{{ __('Expected Yield') }}</span>
                                                    <span class="info-box-number" style="color: black;" id="expectedYieldBox">{{ number_format($plan->expected_yield, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box" style="background-color: white; border: 1px solid #ddd; color: black;">
                                                <span class="info-box-icon" style="background-color: white; color: black;"><i class="fas fa-calendar-check"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text" style="color: black;">{{ __('Total Planned') }}</span>
                                                    <span class="info-box-number" style="color: black;" id="totalPlannedDisplay">0</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box" style="background-color: white; border: 1px solid #ddd; color: black;">
                                                <span class="info-box-icon" style="background-color: white; color: black;"><i class="fas fa-undo-alt"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text" style="color: black;">{{ __('Total Remaining') }}</span>
                                                    <span class="info-box-number" style="color: black;" id="totalRemainingDisplay">{{ number_format($plan->expected_yield, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box" style="background-color: white; border: 1px solid #ddd; color: black;">
                                                <span class="info-box-icon" style="background-color: white; color: black;"><i class="fas fa-chart-line"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text" style="color: black;">{{ __('Overall Progress') }}</span>
                                                    <span class="info-box-number" style="color: black;" id="overallProgressDisplay">0%</span>
                                                    <div class="progress">
                                                        <div class="progress-bar" style="background-color: #ddd;" id="overallProgressBar" style="width: 0%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Instructions -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <div class="callout callout-info" style="background-color: white; border: 1px solid #ddd; color: black;">
                                                <h6 style="color: black;"><i class="fas fa-info-circle mr-2"></i>{{ __('Instructions') }}</h6>
                                                <p class="mb-0" style="color: black;">
                                                    {{ __('Expand quarters to see months, then expand months to plan production for each week. Plan the yield (in quintals) to be produced each week. The system will track remaining yield and prevent over-allocation.') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Main Planning Container -->
                                    <div class="planning-container" id="planningContainer">
                                        <!-- Quarters with expandable months will be rendered here -->
                                    </div>

                                    <!-- Summary Section -->
                                    <div class="card mt-4" style="background-color: white; border: 1px solid #ddd; color: black;">
                                        <div class="card-header" style="background-color: white; color: black; border-bottom: 1px solid #ddd;">
                                            <h3 class="card-title" style="color: black;">
                                                <i class="fas fa-list-alt mr-2"></i>
                                                {{ __('Production Planning Summary') }}
                                            </h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-sm" onclick="saveAllDistributions()" style="background-color: white; border: 1px solid #ddd; color: black;">
                                                    <i class="fas fa-save mr-2"></i> {{ __('Save All') }}
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-sm" style="background-color: white; color: black;">
                                                    <thead>
                                                        <tr style="background-color: white; color: black;">
                                                            <th style="color: black;">{{ __('Week') }}</th>
                                                            <th style="color: black;">{{ __('Month') }}</th>
                                                            <th style="color: black;">{{ __('Quarter') }}</th>
                                                            <th style="color: black;">{{ __('Date Range') }}</th>
                                                            <th style="color: black;">{{ __('Planned Yield') }} ({{ __('qt') }})</th>
                                                            <th style="color: black;">{{ __('Status') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="summaryTable">
                                                        <tr>
                                                            <td colspan="6" class="text-center py-4" style="color: black;">
                                                                <i class="fas fa-calendar-alt mr-2"></i>
                                                                {{ __('No distribution yet. Start by selecting quarters above.') }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="4" class="text-right" style="color: black; font-weight: bold;">{{ __('Total Planned') }}:</td>
                                                            <td class="font-weight-bold" style="color: black;" id="summaryTotal">0</td>
                                                            <td class="font-weight-bold" style="color: black;" id="summaryCompletion">0%</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                
                                <div class="card-footer" style="background-color: white; color: black; border-top: 1px solid #ddd;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="{{ route('sector.crop-plans.index') }}" class="btn" style="background-color: white; border: 1px solid #ddd; color: black;">
                                                <i class="fas fa-arrow-left mr-2"></i> {{ __('Back to Plans') }}
                                            </a>
                                            <button type="button" class="btn ml-2" onclick="clearAllSelections()" style="background-color: white; border: 1px solid #ddd; color: black;">
                                                <i class="fas fa-undo mr-2"></i> {{ __('Clear All') }}
                                            </button>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <button type="button" class="btn" onclick="saveAllDistributions()" style="background-color: white; border: 1px solid #ddd; color: black;">
                                                <i class="fas fa-save mr-2"></i> {{ __('Save Distribution') }}
                                            </button>
                                            <button type="submit" class="btn ml-2" id="submitBtn" style="background-color: white; border: 1px solid #ddd; color: black;">
                                                <i class="fas fa-check-circle mr-2"></i> {{ __('Update Production Plan') }}
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
        // Translations
        const translations = {
            not_planned: "{{ __('Not Planned') }}",
            planned: "{{ __('Planned') }}",
            fix_validation_errors: "{{ __('Please fix the validation errors before submitting.') }}",
            fix_validation_errors_save: "{{ __('Please fix validation errors before saving') }}",
            all_distributions_saved: "{{ __('All distributions saved successfully!') }}",
            negative_value: "{{ __('Negative value') }}",
            exceeds_total_yield: "{{ __('Exceeds total yield') }}",
            qt: "{{ __('qt') }}",
            expand: "{{ __('Expand') }}",
            collapse: "{{ __('Collapse') }}"
        };

        // Global variables
        let weeklyDistributions = [];
        let selectedQuarters = new Set();
        let selectedMonths = new Set();
        const productionYear = {{ $plan->year }};
        const totalYield = parseFloat({{ $plan->expected_yield }});

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

        // Helper function to calculate week range
        function getWeekRange(monthNum, weekNumber) {
            const month = EC_MONTHS[monthNum - 1];
            const start = 1 + (weekNumber - 1) * 7;
            const end = Math.min(start + 6, month.days);
            return `${start}-${end}`;
        }

        // Initialize form
        $(document).ready(function() {
            console.log('Initializing production plan for production year:', productionYear);
            console.log('Total yield:', totalYield);
            
            // Update year display
            $('#expectedYieldBox').text(totalYield.toFixed(2));
            
            // Initialize yield totals display
            updateYieldTotals();
            
            // Load existing distribution
            loadExistingDistribution();
            
            // Render planning container
            renderPlanningContainer();
            
            // Update summary
            updateSummary();
            
            // Form submission
            $('#productionPlanForm').on('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    showAlert(translations.fix_validation_errors, 'error');
                }
            });
        });

        /* -------------------------------
           LOAD EXISTING DISTRIBUTION
        -------------------------------- */
        function loadExistingDistribution() {
            const existingData = {!! json_encode($plan->productionplan_details ?? ['year' => $plan->year, 'quarters' => []]) !!};
            console.log('Loading existing distribution:', existingData);
            
            weeklyDistributions = [];
            
            if (existingData.quarters && Array.isArray(existingData.quarters)) {
                existingData.quarters.forEach(quarter => {
                    const quarterNum = parseInt(quarter.quarter);
                    selectedQuarters.add(quarterNum);
                    
                    if (quarter.months && Array.isArray(quarter.months)) {
                        quarter.months.forEach(monthData => {
                            // Find month by name (matching your structure)
                            const monthObj = EC_MONTHS.find(m => m.name === monthData.month);
                            if (monthObj) {
                                const monthNum = monthObj.id;
                                selectedMonths.add(monthNum);
                                
                                if (monthData.weeks && Array.isArray(monthData.weeks)) {
                                    monthData.weeks.forEach(weekData => {
                                        weeklyDistributions.push({
                                            year: existingData.year,
                                            quarter: `Q${quarterNum}`,
                                            month: monthData.month,
                                            week: weekData.week,
                                            planned_yield: parseFloat(weekData.planned || 0),
                                            unit: 'qt'
                                        });
                                    });
                                }
                            }
                        });
                    }
                });
            }
            
            console.log('Loaded distributions:', weeklyDistributions);
            updateYieldTotals();
            updateSummary();
        }

        /* -------------------------------
           UPDATE YIELD TOTALS
        -------------------------------- */
        function updateYieldTotals() {
            const plannedYield = weeklyDistributions.reduce((total, dist) => total + dist.planned_yield, 0);
            const remainingYield = Math.max(0, totalYield - plannedYield);
            const overallProgress = totalYield > 0 ? (plannedYield / totalYield * 100) : 0;
            
            // Update displays
            $('#totalPlannedDisplay').text(plannedYield.toFixed(2));
            $('#totalRemainingDisplay').text(remainingYield.toFixed(2));
            $('#overallProgressDisplay').text(overallProgress.toFixed(1) + '%');
            $('#overallProgressBar').css('width', overallProgress + '%');
            
            // Update hidden field with current data
            saveJSON();
        }

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
                
                const quarterPlanned = quarterData.planned;
                const quarterPercentage = calculateQuarterPercentage(quarterPlanned);

                $("#planningContainer").append(`
                    <div class="card quarter-card mb-3 ${isSelected ? 'card-primary' : 'card-secondary'}" style="background-color: white; border: 1px solid #ddd;">
                        <div class="card-header d-flex justify-content-between align-items-center" style="cursor: pointer; background-color: white; color: black; border-bottom: 1px solid #ddd;">
                            <div class="d-flex align-items-center">
                                <div class="custom-control custom-checkbox mr-3">
                                    <input class="custom-control-input quarter-checkbox" type="checkbox"
                                           id="quarter${q}" ${isSelected ? 'checked' : ''}>
                                    <label for="quarter${q}" class="custom-control-label font-weight-bold h5 mb-0" style="color: black;">
                                        Q${q} ({{ __('Year') }} ${productionYear})
                                    </label>
                                </div>
                                <div class="ml-3">
                                    <span class="badge ${quarterPlanned > 0 ? 'badge-success' : 'badge-light'}" style="color: black; background-color: ${quarterPlanned > 0 ? '#d4edda' : 'white'}; border: 1px solid #ddd;">
                                        ${quarterPlanned.toFixed(1)} {{ __('qt') }}
                                    </span>
                                    <span class="badge ml-1" style="color: black; background-color: white; border: 1px solid #ddd;">${quarterPercentage.toFixed(1)}%</span>
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn btn-sm toggle-quarter" data-quarter="${q}" style="background-color: white; border: 1px solid #ddd; color: black;">
                                    <i class="fas fa-chevron-down"></i> ${translations.expand}
                                </button>
                            </div>
                        </div>
                        <div class="quarter-months collapse" id="quarterMonths${q}" data-quarter="${q}">
                            <div class="card-body p-0" style="background-color: white;">
                                <!-- Months will be loaded here -->
                            </div>
                        </div>
                    </div>
                `);

                // Event listeners
                $(`#quarter${q}`).on('change', function() {
                    handleQuarterChange(quarterNum, $(this).is(':checked'));
                });

                $(`.toggle-quarter[data-quarter="${q}"]`).on('click', function(e) {
                    e.stopPropagation();
                    const quarterId = $(this).data('quarter');
                    const monthsContainer = $(`#quarterMonths${quarterId}`);
                    
                    if (monthsContainer.hasClass('show')) {
                        collapseQuarter(quarterId);
                    } else {
                        expandQuarter(quarterId);
                    }
                });

                // Expand if selected
                if (isSelected) {
                    setTimeout(() => expandQuarter(q), 100);
                }
            });
        }

        /* -------------------------------
           QUARTER FUNCTIONS
        ------------------------------- */
        function expandQuarter(quarterId) {
            const monthsContainer = $(`#quarterMonths${quarterId}`);
            const toggleBtn = $(`.toggle-quarter[data-quarter="${quarterId}"]`);
            
            if (monthsContainer.find('.month-card').length > 0) {
                monthsContainer.collapse('show');
                toggleBtn.html(`<i class="fas fa-chevron-up"></i> ${translations.collapse}`);
                return;
            }
            
            monthsContainer.html(`
                <div class="card-body">
                    <div class="text-center py-3">
                        <div class="spinner-border spinner-border-sm" role="status" style="color: black;">
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </div>
                        <p class="text-muted mt-2" style="color: black;">{{ __('Loading months...') }}</p>
                    </div>
                </div>
            `);
            monthsContainer.collapse('show');
            
            setTimeout(() => {
                renderMonthsForQuarter(parseInt(quarterId), quarterId);
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
            monthsContainer.html('<div class="row p-3"></div>');
            const monthsRow = monthsContainer.find('.row');
            
            quarterMonths.forEach(monthNum => {
                const month = EC_MONTHS[monthNum - 1];
                const monthData = calculateMonthData(monthNum);
                const isSelected = selectedMonths.has(monthNum);
                const monthPlanned = monthData.planned;
                const monthPercentage = calculateMonthPercentage(monthPlanned);
                
                monthsRow.append(`
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="card month-card h-100 ${isSelected ? 'border-primary' : ''}" style="background-color: white; border: 1px solid #ddd;">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center" style="cursor: pointer; background-color: white !important; color: black; border-bottom: 1px solid #ddd;">
                                <div class="d-flex align-items-center">
                                    <div class="custom-control custom-checkbox mr-2">
                                        <input class="custom-control-input month-checkbox" type="checkbox"
                                               id="month${monthNum}_q${quarterId}" ${isSelected ? 'checked' : ''}
                                               data-month="${monthNum}" data-quarter="${quarterId}">
                                        <label for="month${monthNum}_q${quarterId}" class="custom-control-label font-weight-bold" style="color: black;">
                                            ${month.name}
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge ${monthPlanned > 0 ? 'badge-success' : 'badge-light'}" style="color: black; background-color: ${monthPlanned > 0 ? '#d4edda' : 'white'}; border: 1px solid #ddd;">
                                        ${monthPlanned.toFixed(1)} {{ __('qt') }}
                                    </span>
                                    <span class="badge ml-1" style="color: black; background-color: white; border: 1px solid #ddd;">${monthPercentage.toFixed(1)}%</span>
                                    <button type="button" class="btn btn-sm ml-2 toggle-distribution" 
                                            data-month="${monthNum}" data-quarter="${quarterId}" style="background-color: white; border: 1px solid #ddd; color: black;">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="month-distribution collapse" id="distribution${monthNum}_q${quarterId}">
                                <div class="card-body p-3" style="background-color: white;">
                                    <h6 class="mb-3" style="color: black;">
                                        <i class="fas fa-seedling mr-1"></i>
                                        {{ __('Production Planning for') }} ${month.name}
                                    </h6>
                                    
                                    <div class="distribution-container" id="distributionContainer${monthNum}_q${quarterId}">
                                        <!-- Distribution inputs will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);

                // Event listeners
                $(`#month${monthNum}_q${quarterId}`).on('change', function() {
                    handleMonthChange(monthNum, $(this).is(':checked'));
                });

                $(`.toggle-distribution[data-month="${monthNum}"][data-quarter="${quarterId}"]`).on('click', function(e) {
                    e.stopPropagation();
                    const distributionContainer = $(`#distribution${monthNum}_q${quarterId}`);
                    
                    if (distributionContainer.hasClass('show')) {
                        collapseMonthDistribution(monthNum, quarterId);
                    } else {
                        expandMonthDistribution(monthNum, quarterId);
                    }
                });

                // Add click handler for month card header
                $(`#month${monthNum}_q${quarterId}`).closest('.month-card').find('.card-header').on('click', function(e) {
                    if (!$(e.target).is('input') && !$(e.target).is('label') && !$(e.target).is('button') && !$(e.target).closest('button').length) {
                        const distributionContainer = $(`#distribution${monthNum}_q${quarterId}`);
                        
                        if (distributionContainer.hasClass('show')) {
                            collapseMonthDistribution(monthNum, quarterId);
                        } else {
                            expandMonthDistribution(monthNum, quarterId);
                        }
                    }
                });

                // Expand if selected
                if (isSelected) {
                    setTimeout(() => expandMonthDistribution(monthNum, quarterId), 200);
                }
            });
        }

        /* -------------------------------
           MONTH FUNCTIONS
        ------------------------------- */
        function expandMonthDistribution(monthNum, quarterId) {
            const distributionContainer = $(`#distribution${monthNum}_q${quarterId}`);
            const toggleBtn = $(`.toggle-distribution[data-month="${monthNum}"][data-quarter="${quarterId}"]`);
            const month = EC_MONTHS[monthNum - 1];
            
            distributionContainer.collapse('show');
            
            // Calculate weeks for this month (4 weeks for 30-day months, 1 week for Pagumen)
            let weeks = [];
            let start = 1;
            let weekNumber = 1;
            
            if (monthNum === 13) { // Pagumen (5 days)
                weeks.push({ start: 1, end: 5, weekNum: 1 });
            } else {
                while (start <= month.days) {
                    let end = Math.min(start + 6, month.days);
                    weeks.push({ start, end, weekNum: weekNumber });
                    start += 7;
                    weekNumber++;
                }
            }
            
            // Get existing distributions for this month and quarter
            const monthDistributions = weeklyDistributions.filter(dist => 
                dist.month === month.name && dist.quarter === `Q${quarterId}`
            );
            
            // Render distribution inputs for each week
            let distributionHtml = '';
            
            weeks.forEach(week => {
                const existingDistribution = monthDistributions.find(dist => dist.week === week.weekNum);
                const weekRange = getWeekRange(monthNum, week.weekNum);
                const remaining = getRemainingYield() + (existingDistribution ? existingDistribution.planned_yield : 0);
                
                distributionHtml += `
                    <div class="week-distribution-section mb-4 p-3" style="border: 1px solid #ddd; border-radius: 5px; background-color: white;">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge mr-2" style="color: black; background-color: white; border: 1px solid #ddd;">
                                {{ __('Week') }} ${week.weekNum}
                            </span>
                            <small class="text-muted" style="color: black;">${weekRange}</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="planned_${monthNum}_${week.weekNum}_${quarterId}" style="color: black;">
                                <i class="fas fa-tasks mr-1"></i> {{ __('Planned Yield') }} ({{ __('qt') }})
                            </label>
                            <div class="input-group">
                                <input type="number" 
                                       step="0.01" 
                                       min="0" 
                                       max="${remaining}"
                                       class="form-control form-control-sm yield-input"
                                       id="planned_${monthNum}_${week.weekNum}_${quarterId}"
                                       data-month="${monthNum}"
                                       data-week="${week.weekNum}"
                                       data-quarter="${quarterId}"
                                       data-field="planned_yield"
                                       value="${existingDistribution ? existingDistribution.planned_yield.toFixed(2) : '0.00'}"
                                       placeholder="0.00"
                                       style="color: black; background-color: white; border: 1px solid #ddd;">
                                <div class="input-group-append">
                                    <span class="input-group-text" style="color: black; background-color: white; border: 1px solid #ddd;">{{ __('qt') }}</span>
                                </div>
                            </div>
                            <div class="validation-feedback" style="font-size: 0.75rem; min-height: 18px;"></div>
                            <small class="text-muted" style="color: black;">{{ __('Remaining:') }} ${getRemainingYield().toFixed(2)} {{ __('qt') }}</small>
                        </div>
                    </div>
                `;
            });
            
            $(`#distributionContainer${monthNum}_q${quarterId}`).html(distributionHtml);
            
            // Add input validation for planned yields
            $(`#distributionContainer${monthNum}_q${quarterId} .yield-input`).each(function() {
                validateYieldInput(this);
                
                // Add input event listener
                $(this).on('input', function() {
                    validateYieldInput(this);
                });
            });
            
            toggleBtn.html(`<i class="fas fa-times"></i>`);
        }

        function collapseMonthDistribution(monthNum, quarterId) {
            const distributionContainer = $(`#distribution${monthNum}_q${quarterId}`);
            const toggleBtn = $(`.toggle-distribution[data-month="${monthNum}"][data-quarter="${quarterId}"]`);
            
            distributionContainer.collapse('hide');
            toggleBtn.html(`<i class="fas fa-edit"></i>`);
        }

        /* -------------------------------
           INPUT VALIDATION
        ------------------------------- */
        function validateYieldInput(input) {
            const value = parseFloat($(input).val()) || 0;
            const monthNum = parseInt($(input).data('month'));
            const weekNum = parseInt($(input).data('week'));
            const quarterId = parseInt($(input).data('quarter'));
            const feedback = $(input).closest('.form-group').find('.validation-feedback');
            const remainingText = $(input).closest('.form-group').find('small.text-muted');

            $(input).removeClass('is-valid is-invalid border-success border-danger');
            feedback.empty();

            if (value < 0) {
                $(input).addClass('is-invalid border-danger');
                feedback.html('<small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>' + translations.negative_value + '</small>');
                return false;
            }

            // Calculate remaining yield excluding current input
            const month = EC_MONTHS[monthNum - 1];
            const plannedWithoutCurrent = weeklyDistributions
                .filter(dist => !(dist.month === month.name &&
                      dist.quarter === `Q${quarterId}` &&
                      dist.week === weekNum))
                .reduce((total, dist) => total + dist.planned_yield, 0);

            const maxAllowed = Math.max(0, totalYield - plannedWithoutCurrent);

            if (value > maxAllowed) {
                $(input).addClass('is-invalid border-danger');
                feedback.html(
                    `<small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>${translations.exceeds_total_yield} (${maxAllowed.toFixed(2)} qt)</small>`
                );
                return false;
            }

            // Update the max attribute for future validation
            $(input).attr('max', maxAllowed + (weeklyDistributions.find(dist => 
                dist.month === month.name && 
                dist.quarter === `Q${quarterId}` && 
                dist.week === weekNum
            )?.planned_yield || 0));

            if (value > 0) {
                $(input).addClass('is-valid border-success');
            }

            updateDistributionFromInput(input);
            
            // Update remaining text
            remainingText.text(`{{ __('Remaining:') }} ${getRemainingYield().toFixed(2)} {{ __('qt') }}`);
            
            return true;
        }

        function updateDistributionFromInput(input) {
            const monthNum = parseInt($(input).data('month'));
            const weekNum = parseInt($(input).data('week'));
            const quarterId = parseInt($(input).data('quarter'));
            const value = parseFloat($(input).val()) || 0;
            
            const month = EC_MONTHS[monthNum - 1];
            const quarter = `Q${quarterId}`;
            
            // Find or create distribution
            let distribution = weeklyDistributions.find(dist =>
                dist.month === month.name &&
                dist.quarter === quarter &&
                dist.week === weekNum
            );

            if (!distribution && value > 0) {
                distribution = {
                    year: productionYear,
                    quarter: quarter,
                    month: month.name,
                    week: weekNum,
                    planned_yield: value,
                    unit: 'qt'
                };
                weeklyDistributions.push(distribution);
            } else if (distribution) {
                distribution.planned_yield = value;
                
                // Remove if planned yield is 0
                if (value === 0) {
                    weeklyDistributions = weeklyDistributions.filter(dist =>
                        !(dist.month === month.name &&
                          dist.quarter === quarter &&
                          dist.week === weekNum)
                    );
                }
            }

            // Update yield totals
            updateYieldTotals();
            
            // Update UI
            updateMonthSummary(monthNum);
            updateQuarterSummary(quarterId);
            updateSummary();
        }

        /* -------------------------------
           UTILITY FUNCTIONS
        ------------------------------- */
        function handleQuarterChange(quarterNum, isChecked) {
            if (isChecked) {
                selectedQuarters.add(quarterNum);
                $(`#quarter${quarterNum}`).closest('.card').removeClass('card-secondary').addClass('card-primary');
                expandQuarter(quarterNum);
            } else {
                selectedQuarters.delete(quarterNum);
                $(`#quarter${quarterNum}`).closest('.card').removeClass('card-primary').addClass('card-secondary');
                collapseQuarter(quarterNum);
                
                // Remove months from this quarter
                EC_QUARTERS[quarterNum].forEach(month => {
                    selectedMonths.delete(month);
                    $(`#month${month}_q${quarterNum}`).prop('checked', false);
                    $(`#month${month}_q${quarterNum}`).closest('.month-card').removeClass('border-primary');
                });
            }
            updateSummary();
        }

        function handleMonthChange(monthNum, isChecked) {
            if (isChecked) {
                selectedMonths.add(monthNum);
                $(`#month${monthNum}_q${findQuarterId(monthNum)}`).closest('.month-card').addClass('border-primary');
            } else {
                selectedMonths.delete(monthNum);
                $(`#month${monthNum}_q${findQuarterId(monthNum)}`).closest('.month-card').removeClass('border-primary');
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

        function getRemainingYield() {
            const plannedYield = weeklyDistributions.reduce((total, dist) => total + dist.planned_yield, 0);
            return Math.max(0, totalYield - plannedYield);
        }

        function calculateQuarterData(quarter) {
            const quarterName = `Q${quarter}`;
            const quarterDistributions = weeklyDistributions.filter(dist => dist.quarter === quarterName);
            
            return {
                planned: quarterDistributions.reduce((total, dist) => total + (dist.planned_yield || 0), 0)
            };
        }

        function calculateMonthData(monthNum) {
            const monthName = EC_MONTHS[monthNum - 1].name;
            const monthDistributions = weeklyDistributions.filter(dist => dist.month === monthName);
            
            return {
                planned: monthDistributions.reduce((total, dist) => total + (dist.planned_yield || 0), 0)
            };
        }

        function calculateQuarterPercentage(planned) {
            return totalYield > 0 ? (planned / totalYield * 100) : 0;
        }

        function calculateMonthPercentage(planned) {
            return totalYield > 0 ? (planned / totalYield * 100) : 0;
        }

        function updateMonthSummary(monthNum) {
            const monthData = calculateMonthData(monthNum);
            const monthPlanned = monthData.planned;
            const monthPercentage = calculateMonthPercentage(monthPlanned);
            
            $(`[id^="month${monthNum}_q"]`).each(function() {
                const monthBadge = $(this).closest('.month-card').find('.badge-success, .badge-light');
                monthBadge.removeClass('badge-success badge-light');
                monthBadge.addClass(monthPlanned > 0 ? 'badge-success' : 'badge-light');
                monthBadge.text(monthPlanned.toFixed(1) + ' {{ __('qt') }}');
            });
        }

        function updateQuarterSummary(quarterNum) {
            const quarterData = calculateQuarterData(quarterNum);
            const quarterPlanned = quarterData.planned;
            const quarterPercentage = calculateQuarterPercentage(quarterPlanned);
            
            $(`#quarter${quarterNum}`).closest('.quarter-card').find('.badge-success, .badge-light').each(function() {
                $(this).removeClass('badge-success badge-light');
                $(this).addClass(quarterPlanned > 0 ? 'badge-success' : 'badge-light');
                $(this).text(quarterPlanned.toFixed(1) + ' {{ __('qt') }}');
            });
        }

        /* -------------------------------
           SUMMARY FUNCTIONS
        ------------------------------- */
        function updateSummary() {
            updateSummaryTable();
        }

        function updateSummaryTable() {
            $("#summaryTable").empty();
            let totalPlanned = 0;

            if (weeklyDistributions.length === 0) {
                $("#summaryTable").append(`
                    <tr>
                        <td colspan="6" class="text-center py-4" style="color: black;">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            {{ __('No distribution yet. Start by selecting quarters above.') }}
                        </td>
                    </tr>
                `);
            } else {
                weeklyDistributions.forEach(dist => {
                    if (dist.planned_yield > 0) {
                        totalPlanned += dist.planned_yield;
                        const status = dist.planned_yield > 0 ? translations.planned : translations.not_planned;
                        const statusClass = dist.planned_yield > 0 ? 'badge-success' : 'badge-secondary';
                        const weekRange = getWeekRange(EC_MONTHS.findIndex(m => m.name === dist.month) + 1, dist.week);

                        $("#summaryTable").append(`
                            <tr>
                                <td style="color: black;">{{ __('Week') }} ${dist.week}</td>
                                <td style="color: black;">${dist.month}</td>
                                <td style="color: black;">${dist.quarter}</td>
                                <td style="color: black;">${weekRange}</td>
                                <td class="font-weight-bold" style="color: black;">${dist.planned_yield.toFixed(2)} qt</td>
                                <td><span class="badge ${statusClass}" style="color: black; background-color: ${dist.planned_yield > 0 ? '#d4edda' : 'white'}; border: 1px solid #ddd;">${status}</span></td>
                            </tr>
                        `);
                    }
                });
            }

            const overallPercentage = totalYield > 0 ? (totalPlanned / totalYield * 100) : 0;
            $("#summaryTotal").text(totalPlanned.toFixed(2));
            $("#summaryCompletion").text(overallPercentage.toFixed(1) + "%");
        }

        /* -------------------------------
           SAVE DATA (Matches your JSON structure)
        ------------------------------- */
        function saveJSON() {
            // Group data by quarter and month according to your structure
            const quartersMap = {};
            
            weeklyDistributions.forEach(dist => {
                const quarterNum = parseInt(dist.quarter.replace('Q', ''));
                
                if (!quartersMap[quarterNum]) {
                    quartersMap[quarterNum] = {
                        quarter: quarterNum,
                        name: `Q${quarterNum}`,
                        months: {}
                    };
                }
                
                const quarter = quartersMap[quarterNum];
                
                if (!quarter.months[dist.month]) {
                    quarter.months[dist.month] = {
                        month: dist.month,
                        weeks: []
                    };
                }
                
                const month = quarter.months[dist.month];
                
                // Check if week already exists
                const existingWeekIndex = month.weeks.findIndex(w => w.week === dist.week);
                if (existingWeekIndex > -1) {
                    // Update existing week
                    month.weeks[existingWeekIndex] = {
                        week: dist.week,
                        planned: dist.planned_yield,
                        achieved: null  // Always null for planning
                    };
                } else {
                    // Add new week
                    month.weeks.push({
                        week: dist.week,
                        planned: dist.planned_yield,
                        achieved: null  // Always null for planning
                    });
                }
            });
            
            // Convert to array format and sort according to your structure
            const quartersArray = Object.values(quartersMap)
                .sort((a, b) => a.quarter - b.quarter)
                .map(quarter => {
                    // Sort months according to Ethiopian calendar order
                    const monthsArray = Object.values(quarter.months)
                        .sort((a, b) => {
                            const monthOrder = EC_MONTHS.map(m => m.name);
                            return monthOrder.indexOf(a.month) - monthOrder.indexOf(b.month);
                        })
                        .map(month => {
                            // Sort weeks within each month
                            month.weeks.sort((a, b) => a.week - b.week);
                            return month;
                        });
                    
                    return {
                        quarter: quarter.quarter,
                        name: quarter.name,
                        months: monthsArray
                    };
                });
            
            const planData = {
                year: productionYear,
                quarters: quartersArray
            };
            
            console.log('Saved production plan data (matching your structure):', planData);
            $("#productionplanDetails").val(JSON.stringify(planData));
            return planData;
        }

        function saveAllDistributions() {
            let hasErrors = false;

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

        function clearAllSelections() {
            if (!confirm('{{ __('Are you sure you want to clear all selections? This cannot be undone.') }}')) {
                return;
            }

            selectedQuarters.clear();
            selectedMonths.clear();
            weeklyDistributions = [];
            
            $('.quarter-checkbox').prop('checked', false);
            $('.month-checkbox').prop('checked', false);
            $('.yield-input').val('');
            
            $('.quarter-card').removeClass('card-primary').addClass('card-secondary');
            $('.month-card').removeClass('border-primary');
            
            $('.quarter-months').collapse('hide');
            $('.month-distribution').collapse('hide');
            
            updateYieldTotals();
            updateSummary();
            
            showAlert('{{ __('All selections cleared') }}', 'info');
        }

        /* -------------------------------
           FORM VALIDATION
        ------------------------------- */
        function validateForm() {
            let isValid = true;
            const errors = [];

            $('.yield-input').each(function() {
                if ($(this).hasClass('is-invalid')) {
                    errors.push('{{ __('Some distributions have validation errors') }}');
                    isValid = false;
                    return false;
                }
            });

            // Check if total planned yield exceeds total yield
            const totalPlanned = weeklyDistributions.reduce((total, dist) => total + dist.planned_yield, 0);
            if (totalPlanned > totalYield) {
                errors.push('{{ __('Total planned yield exceeds available yield') }}');
                isValid = false;
            }

            if (errors.length > 0) {
                showValidationErrors(errors);
            }

            return isValid;
        }

        function showValidationErrors(errors) {
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
            
            $(`#${toastId}`).fadeIn();
            
            setTimeout(() => {
                $(`#${toastId}`).fadeOut(400, function() {
                    $(this).remove();
                });
            }, 3000);
            
            $(`#${toastId} .btn-close`).click(function() {
                $(`#${toastId}`).fadeOut(400, function() {
                    $(this).remove();
                });
            });
        }
    </script>

    <style>
        /* Card hover effects */
        .quarter-card:hover, .month-card:hover {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Custom checkbox styling */
        .custom-control-input:checked ~ .custom-control-label::before {
            border-color: #333;
            background-color: #333;
        }

        /* Week distribution sections */
        .week-distribution-section {
            transition: all 0.3s ease;
        }
        
        .week-distribution-section:hover {
            background-color: #f8f9fa;
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
            
            .form-control {
                font-size: 0.8rem !important;
            }
        }

        /* Table styling */
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        /* Input validation states */
        .form-control.is-valid {
            border-color: #28a745 !important;
        }

        .form-control.is-invalid {
            border-color: #dc3545 !important;
        }

        /* Expandable Cards */
        .quarter-card, .month-card {
            transition: all 0.3s ease;
        }
        
        .quarter-card.card-primary .card-header {
            border-bottom: 2px solid #333;
        }
        
        .month-card.border-primary .card-header {
            border-bottom: 2px solid #333;
        }

        /* Ensure all text is black and backgrounds white */
        body, .card, .card-header, .card-body, .card-title, .card-footer,
        .table, .table th, .table td, .form-control, .input-group-text,
        .badge, .alert, .btn, .breadcrumb, .content-header h1,
        .info-box-text, .info-box-number, .progress-description,
        h1, h2, h3, h4, h5, h6, p, span, div, label, small {
            color: black !important;
            background-color: white !important;
        }
        
        /* Ensure borders are visible */
        .card, .table, .form-control, .btn, .badge, .alert,
        .input-group-text {
            border-color: #ddd !important;
        }
    </style>
</x-layout>