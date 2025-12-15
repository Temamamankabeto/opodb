<x-layout>
    <div class="content-header">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ __('Land Preparation Achievement') }}</h1>
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

                @if(session('success'))
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
                                <h3 class="card-title" style="color: black;"><i class="fas fa-tractor mr-2"></i>{{ __('Land Preparation Achievement') }}</h3>
                                <div class="card-tools">
                                    <span class="badge badge-light" style="color: black; background-color: white; border: 1px solid #ddd;">{{ $plan->crop->name ?? __('N/A') }} - {{ $plan->year }} {{ $plan->season }}</span>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form action="{{ route('sector.crop-plans.update-land-preparation', $plan->id) }}" method="POST" id="achievementForm">
                                @csrf
                                @method('PUT')

                                <!-- Hidden field for achievement details -->
                                <input type="hidden" name="plandivision_details" id="plandivisionDetails"
                                       value="{{ json_encode($plan->plandivision_details ?? ['year' => $plan->year - 1, 'quarters' => []]) }}">

                                <div class="card-body" style="background-color: white; color: black;">
                                   

                                    <!-- Area Summary Boxes -->
                                    <div class="row mb-4">
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box" style="background-color: white; border: 1px solid #ddd; color: black;">
                                                <span class="info-box-icon" style="background-color: white; color: black;"><i class="fas fa-landmark"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text" style="color: black;">{{ __('Total Area') }}</span>
                                                    <span class="info-box-number" style="color: black;" id="totalAreaBox">{{ $plan->area_to_plant }}</span>
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
                                                <span class="info-box-icon" style="background-color: white; color: black;"><i class="fas fa-trophy"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text" style="color: black;">{{ __('Total Achieved') }}</span>
                                                    <span class="info-box-number" style="color: black;" id="totalAchievedDisplay">0</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box" style="background-color: white; border: 1px solid #ddd; color: black;">
                                                <span class="info-box-icon" style="background-color: white; color: black;"><i class="fas fa-chart-line"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text" style="color: black;">{{ __('Achievement Rate') }}</span>
                                                    <span class="info-box-number" style="color: black;" id="achievementRateDisplay">0%</span>
                                                    <div class="progress">
                                                        <div class="progress-bar" id="achievementRateBar" style="width: 0%; background-color: #ddd;"></div>
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
                                                    {{ __('Expand quarters to see months, then expand months to record achievement for each week. Enter the actual area prepared (in hectares) for each week. The system will track achievement against the planned area.') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Main Achievement Container -->
                                    <div class="achievement-container" id="achievementContainer">
                                        <!-- Quarters with expandable months will be rendered here -->
                                    </div>

                                    <!-- Summary Section -->
                                    <div class="card mt-4" style="background-color: white; border: 1px solid #ddd; color: black;">
                                        <div class="card-header" style="background-color: white; color: black; border-bottom: 1px solid #ddd;">
                                            <h3 class="card-title" style="color: black;">
                                                <i class="fas fa-list-alt mr-2"></i>
                                                {{ __('Achievement Summary') }}
                                            </h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-sm" onclick="saveAllAchievements()" style="background-color: white; border: 1px solid #ddd; color: black;">
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
                                                            <th style="color: black;">{{ __('Planned Area') }} ({{ __('ha') }})</th>
                                                            <th style="color: black;">{{ __('Achieved Area') }} ({{ __('ha') }})</th>
                                                            <th style="color: black;">{{ __('Status') }}</th>
                                                            <th style="color: black;">{{ __('Performance') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="summaryTable">
                                                        <tr>
                                                            <td colspan="8" class="text-center py-4" style="color: black;">
                                                                <i class="fas fa-calendar-alt mr-2"></i>
                                                                {{ __('No achievement data yet. Start by selecting quarters above.') }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="4" class="text-right" style="color: black; font-weight: bold;">{{ __('Total') }}:</td>
                                                            <td class="font-weight-bold" style="color: black;" id="summaryPlanned">0</td>
                                                            <td class="font-weight-bold" style="color: black;" id="summaryAchieved">0</td>
                                                            <td colspan="2" class="font-weight-bold" style="color: black;" id="summaryPerformance">0%</td>
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
                                            <button type="button" class="btn ml-2" onclick="clearAllAchievements()" style="background-color: white; border: 1px solid #ddd; color: black;">
                                                <i class="fas fa-undo mr-2"></i> {{ __('Clear All') }}
                                            </button>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <button type="button" class="btn" onclick="saveAllAchievements()" style="background-color: white; border: 1px solid #ddd; color: black;">
                                                <i class="fas fa-save mr-2"></i> {{ __('Save Achievements') }}
                                            </button>
                                            <button type="submit" class="btn ml-2" id="submitBtn" style="background-color: white; border: 1px solid #ddd; color: black;">
                                                <i class="fas fa-check-circle mr-2"></i> {{ __('Update Achievements') }}
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
            not_started: "{{ __('Not Started') }}",
            in_progress: "{{ __('In Progress') }}",
            completed: "{{ __('Completed') }}",
            over_achieved: "{{ __('Over Achieved') }}",
            poor: "{{ __('Poor') }}",
            below_target: "{{ __('Below Target') }}",
            good: "{{ __('Good') }}",
            excellent: "{{ __('Excellent') }}",
            outstanding: "{{ __('Outstanding') }}",
            fix_validation_errors: "{{ __('Please fix the validation errors before submitting.') }}",
            fix_validation_errors_save: "{{ __('Please fix validation errors before saving') }}",
            all_achievements_saved: "{{ __('All achievements saved successfully!') }}",
            negative_value: "{{ __('Negative value') }}",
            exceeds_total_area: "{{ __('Exceeds total area') }}",
            ha: "{{ __('ha') }}",
            expand: "{{ __('Expand') }}",
            collapse: "{{ __('Collapse') }}"
        };

        // Global variables
        let weeklyDistributions = [];
        let selectedQuarters = new Set();
        let selectedMonths = new Set();
        const preparationYear = {{ $plan->year - 1 }};
        const totalArea = parseFloat({{ $plan->area_to_plant }});

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

        // Performance calculation functions
        function calculateStatus(planned, achieved) {
            if (achieved === 0) return translations.not_started;
            if (achieved < planned) return translations.in_progress;
            if (achieved === planned) return translations.completed;
            return translations.over_achieved;
        }

        function calculatePerformance(planned, achieved) {
            if (planned === 0) return translations.outstanding; // If no planned but achieved
            
            const percentage = (achieved / planned) * 100;
            
            if (percentage === 0) return translations.not_started;
            if (percentage < 50) return translations.poor;
            if (percentage < 80) return translations.below_target;
            if (percentage < 100) return translations.good;
            if (percentage === 100) return translations.excellent;
            if (percentage <= 120) return translations.outstanding;
            return translations.outstanding; // For over 120%
        }

        function getPerformanceColor(percentage) {
            if (percentage === 0) return '#6c757d'; // Not started - gray
            if (percentage < 50) return '#dc3545'; // Poor - red
            if (percentage < 80) return '#ffc107'; // Below target - yellow
            if (percentage < 100) return '#17a2b8'; // Good - teal
            if (percentage === 100) return '#28a745'; // Excellent - green
            return '#007bff'; // Outstanding/Over achieved - blue
        }

        function getPerformanceClass(performance) {
            switch(performance) {
                case translations.not_started: return 'badge-secondary';
                case translations.poor: return 'badge-danger';
                case translations.below_target: return 'badge-warning';
                case translations.good: return 'badge-info';
                case translations.excellent: return 'badge-success';
                case translations.outstanding: return 'badge-primary';
                default: return 'badge-secondary';
            }
        }

        function getStatusClass(status) {
            switch(status) {
                case translations.not_started: return 'badge-secondary';
                case translations.in_progress: return 'badge-warning';
                case translations.completed: return 'badge-success';
                case translations.over_achieved: return 'badge-primary';
                default: return 'badge-secondary';
            }
        }

        // Initialize form
        $(document).ready(function() {
            console.log('Initializing land preparation achievement for preparation year:', preparationYear);
            console.log('Total area:', totalArea);
            
            // Update area totals display
            updateAreaTotals();
            
            // Load existing distribution
            loadExistingDistribution();
            
            // Render achievement container
            renderAchievementContainer();
            
            // Update summary
            updateSummary();
            
            // Form submission
            $('#achievementForm').on('submit', function(e) {
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
            const existingData = {!! json_encode($plan->plandivision_details ?? ['year' => $plan->year - 1, 'quarters' => []]) !!};
            console.log('Loading existing achievement data:', existingData);
            
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
                                            planned_area: parseFloat(weekData.planned_area || 0),
                                            achieved_area: parseFloat(weekData.achieved_area || 0),
                                            unit: 'ha'
                                        });
                                    });
                                }
                            }
                        });
                    }
                });
            }
            
            console.log('Loaded distributions:', weeklyDistributions);
            updateAreaTotals();
            updateSummary();
        }

        /* -------------------------------
           UPDATE AREA TOTALS
        -------------------------------- */
        function updateAreaTotals() {
            const plannedArea = weeklyDistributions.reduce((total, dist) => total + dist.planned_area, 0);
            const achievedArea = weeklyDistributions.reduce((total, dist) => total + dist.achieved_area, 0);
            const achievementRate = plannedArea > 0 ? (achievedArea / plannedArea * 100) : 0;
            
            // Update displays
            $('#totalPlannedDisplay').text(plannedArea.toFixed(2));
            $('#totalAchievedDisplay').text(achievedArea.toFixed(2));
            $('#achievementRateDisplay').text(achievementRate.toFixed(1) + '%');
            $('#achievementRateBar').css('width', achievementRate + '%');
            $('#achievementRateBar').css('background-color', getPerformanceColor(achievementRate));
            
            // Update hidden field with current data
            saveJSON();
        }

        /* -------------------------------
           RENDER ACHIEVEMENT CONTAINER
        ------------------------------- */
        function renderAchievementContainer() {
            $("#achievementContainer").empty();

            Object.keys(EC_QUARTERS).forEach(q => {
                const quarterNum = parseInt(q);
                const quarterData = calculateQuarterData(quarterNum);
                const isSelected = selectedQuarters.has(quarterNum);
                const quarterName = `Q${q}`;
                
                const quarterPlanned = quarterData.planned;
                const quarterAchieved = quarterData.achieved;
                const quarterRate = quarterPlanned > 0 ? (quarterAchieved / quarterPlanned * 100) : 0;
                const quarterColor = getPerformanceColor(quarterRate);

                $("#achievementContainer").append(`
                    <div class="card quarter-card mb-3 ${quarterPlanned > 0 ? 'card-primary' : 'card-secondary'}" style="background-color: white; border: 1px solid #ddd;">
                        <div class="card-header d-flex justify-content-between align-items-center" style="cursor: pointer; background-color: white; color: black; border-bottom: 1px solid #ddd;">
                            <div class="d-flex align-items-center">
                                <div class="custom-control custom-checkbox mr-3">
                                    <input class="custom-control-input quarter-checkbox" type="checkbox"
                                           id="quarter${q}" ${isSelected ? 'checked' : ''}
                                           ${quarterPlanned === 0 ? 'disabled' : ''}>
                                    <label for="quarter${q}" class="custom-control-label font-weight-bold h5 mb-0" style="color: black;">
                                        Q${q} ({{ __('Year') }} ${preparationYear})
                                    </label>
                                </div>
                                <div class="ml-3">
                                    <span class="badge ${quarterPlanned > 0 ? 'badge-warning' : 'badge-light'}" style="color: black; background-color: ${quarterPlanned > 0 ? '#fff3cd' : 'white'}; border: 1px solid #ddd;">
                                        ${quarterPlanned.toFixed(1)} {{ __('ha') }}
                                    </span>
                                    <span class="badge ${quarterAchieved > 0 ? 'badge-success' : 'badge-light'} ml-1" style="color: black; background-color: ${quarterAchieved > 0 ? '#d4edda' : 'white'}; border: 1px solid #ddd;">
                                        ${quarterAchieved.toFixed(1)} {{ __('ha') }}
                                    </span>
                                    <span class="badge ml-1" style="color: white; background-color: ${quarterColor};">
                                        ${quarterRate.toFixed(1)}%
                                    </span>
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn btn-sm toggle-quarter" data-quarter="${q}" 
                                        style="background-color: white; border: 1px solid #ddd; color: black;"
                                        ${quarterPlanned === 0 ? 'disabled' : ''}>
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
                    if (!$(this).prop('disabled')) {
                        handleQuarterChange(quarterNum, $(this).is(':checked'));
                    }
                });

                $(`.toggle-quarter[data-quarter="${q}"]`).on('click', function(e) {
                    e.stopPropagation();
                    if (!$(this).prop('disabled')) {
                        const quarterId = $(this).data('quarter');
                        const monthsContainer = $(`#quarterMonths${quarterId}`);
                        
                        if (monthsContainer.hasClass('show')) {
                            collapseQuarter(quarterId);
                        } else {
                            expandQuarter(quarterId);
                        }
                    }
                });

                // Expand if selected
                if (isSelected && quarterPlanned > 0) {
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
                const hasPlannedData = monthData.planned > 0;
                const monthPlanned = monthData.planned;
                const monthAchieved = monthData.achieved;
                const monthRate = monthPlanned > 0 ? (monthAchieved / monthPlanned * 100) : 0;
                const monthColor = getPerformanceColor(monthRate);
                
                monthsRow.append(`
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="card month-card h-100 ${hasPlannedData ? 'border-primary' : ''}" style="background-color: white; border: 1px solid #ddd;">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center" style="cursor: pointer; background-color: white !important; color: black; border-bottom: 1px solid #ddd;">
                                <div class="d-flex align-items-center">
                                    <div class="custom-control custom-checkbox mr-2">
                                        <input class="custom-control-input month-checkbox" type="checkbox"
                                               id="month${monthNum}_q${quarterId}" ${selectedMonths.has(monthNum) ? 'checked' : ''}
                                               data-month="${monthNum}" data-quarter="${quarterId}"
                                               ${!hasPlannedData ? 'disabled' : ''}>
                                        <label for="month${monthNum}_q${quarterId}" class="custom-control-label font-weight-bold" style="color: black;">
                                            ${month.name}
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    
                                    <span class="badge ml-1" style="color: white; background-color: ${monthColor};">
                                        ${monthRate.toFixed(1)}%
                                    </span>
                                    <button type="button" class="btn btn-sm ml-2 toggle-achievement" 
                                            data-month="${monthNum}" data-quarter="${quarterId}" 
                                            style="background-color: white; border: 1px solid #ddd; color: black;"
                                            ${!hasPlannedData ? 'disabled' : ''}>
                                        <i class="fas fa-trophy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="month-achievement collapse" id="achievement${monthNum}_q${quarterId}">
                                <div class="card-body p-3" style="background-color: white;">
                                    <h6 class="mb-3" style="color: black;">
                                        <i class="fas fa-trophy mr-1"></i>
                                        {{ __('Achievement Tracking for') }} ${month.name}
                                    </h6>
                                    
                                    <div class="achievement-container" id="achievementContainer${monthNum}_q${quarterId}">
                                        <!-- Achievement inputs will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);

                // Event listeners
                $(`#month${monthNum}_q${quarterId}`).on('change', function() {
                    const monthNum = parseInt($(this).data('month'));
                    const isChecked = $(this).is(':checked');
                    if (!$(this).prop('disabled')) {
                        handleMonthChange(monthNum, isChecked);
                    }
                });

                $(`.toggle-achievement[data-month="${monthNum}"][data-quarter="${quarterId}"]`).on('click', function(e) {
                    e.stopPropagation();
                    if (!$(this).prop('disabled')) {
                        const monthNum = $(this).data('month');
                        const quarterId = $(this).data('quarter');
                        const achievementContainer = $(`#achievement${monthNum}_q${quarterId}`);
                        
                        if (achievementContainer.hasClass('show')) {
                            collapseMonthAchievement(monthNum, quarterId);
                        } else {
                            expandMonthAchievement(monthNum, quarterId);
                        }
                    }
                });

                // Add click handler for month card header
                $(`#month${monthNum}_q${quarterId}`).closest('.month-card').find('.card-header').on('click', function(e) {
                    if (!$(e.target).is('input') && !$(e.target).is('label') && !$(e.target).is('button') && !$(e.target).closest('button').length) {
                        if (!$(this).find('.toggle-achievement').prop('disabled')) {
                            const monthNum = $(this).find('.toggle-achievement').data('month');
                            const quarterId = $(this).find('.toggle-achievement').data('quarter');
                            const achievementContainer = $(`#achievement${monthNum}_q${quarterId}`);
                            
                            if (achievementContainer.hasClass('show')) {
                                collapseMonthAchievement(monthNum, quarterId);
                            } else {
                                expandMonthAchievement(monthNum, quarterId);
                            }
                        }
                    }
                });

                // Expand if has planned data
                if (hasPlannedData) {
                    setTimeout(() => expandMonthAchievement(monthNum, quarterId), 200);
                }
            });
        }

        /* -------------------------------
           MONTH FUNCTIONS - ACHIEVEMENT TRACKING
        ------------------------------- */
        function expandMonthAchievement(monthNum, quarterId) {
            const achievementContainer = $(`#achievement${monthNum}_q${quarterId}`);
            const toggleBtn = $(`.toggle-achievement[data-month="${monthNum}"][data-quarter="${quarterId}"]`);
            const month = EC_MONTHS[monthNum - 1];
            
            achievementContainer.collapse('show');
            
            // Calculate weeks for this month
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
            
            // Get distributions for this month and quarter
            const monthDistributions = weeklyDistributions.filter(dist => 
                dist.month === month.name && dist.quarter === `Q${quarterId}`
            );
            
            // Render achievement inputs for each week
            let achievementHtml = '';
            
            weeks.forEach(week => {
                const existingDistribution = monthDistributions.find(dist => dist.week === week.weekNum);
                const weekRange = getWeekRange(monthNum, week.weekNum);
                const plannedArea = existingDistribution ? existingDistribution.planned_area : 0;
                const achievedArea = existingDistribution ? existingDistribution.achieved_area : 0;
                const status = calculateStatus(plannedArea, achievedArea);
                const performance = calculatePerformance(plannedArea, achievedArea);
                const statusClass = getStatusClass(status);
                const performanceClass = getPerformanceClass(performance);
                const achievementRate = plannedArea > 0 ? (achievedArea / plannedArea * 100) : 0;
                const achievementColor = getPerformanceColor(achievementRate);
                
                // Only show weeks that have planned area
                if (plannedArea === 0) {
                    return;
                }
                
                achievementHtml += `
                    <div class="achievement-input-group mb-4 p-3" style="border: 1px solid #ddd; border-radius: 5px; background-color: white; border-left: 4px solid ${achievementColor};">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge mr-2" style="color: black; background-color: white; border: 1px solid #ddd;">
                                {{ __('Week') }} ${week.weekNum}
                            </span>
                            <small class="text-muted" style="color: black;">${weekRange}</small>
                            <div class="ml-auto">
                                <span class="badge ${statusClass}">${status}</span>
                                <span class="badge ${performanceClass} ml-1">${performance}</span>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: black;">
                                        <i class="fas fa-calendar-check mr-1"></i> {{ __('Planned Area') }} ({{ __('ha') }})
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control form-control-sm planned-input"
                                               value="${plannedArea.toFixed(2)}"
                                               readonly
                                               style="color: black; background-color: #f8f9fa; border: 1px solid #ddd;">
                                        <div class="input-group-append">
                                            <span class="input-group-text" style="color: black; background-color: white; border: 1px solid #ddd;">{{ __('ha') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: black;">
                                        <i class="fas fa-trophy mr-1"></i> {{ __('Achieved Area') }} ({{ __('ha') }})
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               step="0.01" 
                                               min="0"
                                               class="form-control form-control-sm achievement-input"
                                               id="achieved_${monthNum}_${week.weekNum}_${quarterId}"
                                               data-month="${monthNum}"
                                               data-week="${week.weekNum}"
                                               data-quarter="${quarterId}"
                                               data-field="achieved_area"
                                               value="${achievedArea.toFixed(2)}"
                                               placeholder="0.00"
                                               style="color: black; background-color: white; border: 1px solid #ddd;">
                                        <div class="input-group-append">
                                            <span class="input-group-text" style="color: black; background-color: white; border: 1px solid #ddd;">{{ __('ha') }}</span>
                                        </div>
                                    </div>
                                    <div class="validation-feedback" style="font-size: 0.75rem; min-height: 18px;"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Achievement Progress Bar -->
                        <div class="achievement-progress mt-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small style="color: black;">${achievementRate.toFixed(1)}%</small>
                                <small style="color: black;">${achievedArea.toFixed(2)} / ${plannedArea.toFixed(2)} {{ __('ha') }}</small>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar" style="width: ${Math.min(achievementRate, 100)}%; background-color: ${achievementColor};"></div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            const achievementContainerDiv = $(`#achievementContainer${monthNum}_q${quarterId}`);
            
            if (achievementHtml === '') {
                achievementContainerDiv.html(`
                    <div class="alert alert-info" style="background-color: white; border: 1px solid #ddd; color: black;">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ __('No planned weeks found for this month. Please ensure the plan has been properly set up.') }}
                    </div>
                `);
            } else {
                achievementContainerDiv.html(achievementHtml);
            }
            
            // Add input validation for achievement areas
            $(`#achievementContainer${monthNum}_q${quarterId} .achievement-input`).each(function() {
                validateAchievementInput(this);
                
                // Add input event listener
                $(this).on('input', function() {
                    validateAchievementInput(this);
                });
            });
            
            toggleBtn.html(`<i class="fas fa-times"></i>`);
        }

        function collapseMonthAchievement(monthNum, quarterId) {
            const achievementContainer = $(`#achievement${monthNum}_q${quarterId}`);
            const toggleBtn = $(`.toggle-achievement[data-month="${monthNum}"][data-quarter="${quarterId}"]`);
            
            achievementContainer.collapse('hide');
            toggleBtn.html(`<i class="fas fa-trophy"></i>`);
        }

        /* -------------------------------
           ACHIEVEMENT INPUT VALIDATION
        ------------------------------- */
        function validateAchievementInput(input) {
            const value = parseFloat($(input).val()) || 0;
            const monthNum = parseInt($(input).data('month'));
            const weekNum = parseInt($(input).data('week'));
            const quarterId = parseInt($(input).data('quarter'));
            const feedback = $(input).closest('.form-group').find('.validation-feedback');

            $(input).removeClass('is-valid is-invalid border-success border-danger');
            feedback.empty();

            if (value < 0) {
                $(input).addClass('is-invalid border-danger');
                feedback.html('<small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>' + translations.negative_value + '</small>');
                return false;
            }

            // No maximum limit for achievement - can exceed planned
            if (value > 0) {
                $(input).addClass('is-valid border-success');
            }

            updateAchievementDistribution(input, value);
            return true;
        }

        function updateAchievementDistribution(input, value) {
            const monthNum = parseInt($(input).data('month'));
            const weekNum = parseInt($(input).data('week'));
            const quarterId = parseInt($(input).data('quarter'));
            const month = EC_MONTHS[monthNum - 1];
            const quarter = `Q${quarterId}`;
            
            // Find distribution
            let distribution = weeklyDistributions.find(dist =>
                dist.month === month.name &&
                dist.quarter === quarter &&
                dist.week === weekNum
            );

            if (distribution) {
                distribution.achieved_area = value;
                
                // Update achievement progress
                const plannedArea = distribution.planned_area || 0;
                const achievementRate = plannedArea > 0 ? (value / plannedArea * 100) : 0;
                const status = calculateStatus(plannedArea, value);
                const performance = calculatePerformance(plannedArea, value);
                const achievementColor = getPerformanceColor(achievementRate);
                
                // Update UI elements
                const achievementGroup = $(input).closest('.achievement-input-group');
                achievementGroup.css('border-left-color', achievementColor);
                
                const progressBar = achievementGroup.find('.progress-bar');
                progressBar.css({
                    'width': Math.min(achievementRate, 100) + '%',
                    'background-color': achievementColor
                });
                
                achievementGroup.find('.badge:first-child').removeClass().addClass('badge ' + getStatusClass(status)).text(status);
                achievementGroup.find('.badge:last-child').removeClass().addClass('badge ' + getPerformanceClass(performance)).text(performance);
                
                achievementGroup.find('small:first-child').text(achievementRate.toFixed(1) + '%');
                achievementGroup.find('small:last-child').text(value.toFixed(2) + ' / ' + plannedArea.toFixed(2) + ' {{ __('ha') }}');
            }

            // Update area totals
            updateAreaTotals();
            
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

        function calculateQuarterData(quarter) {
            const quarterName = `Q${quarter}`;
            const quarterDistributions = weeklyDistributions.filter(dist => dist.quarter === quarterName);
            
            return {
                planned: quarterDistributions.reduce((total, dist) => total + (dist.planned_area || 0), 0),
                achieved: quarterDistributions.reduce((total, dist) => total + (dist.achieved_area || 0), 0)
            };
        }

        function calculateMonthData(monthNum) {
            const monthName = EC_MONTHS[monthNum - 1].name;
            const monthDistributions = weeklyDistributions.filter(dist => dist.month === monthName);
            
            return {
                planned: monthDistributions.reduce((total, dist) => total + (dist.planned_area || 0), 0),
                achieved: monthDistributions.reduce((total, dist) => total + (dist.achieved_area || 0), 0)
            };
        }

        function updateMonthSummary(monthNum) {
            const monthData = calculateMonthData(monthNum);
            const monthPlanned = monthData.planned;
            const monthAchieved = monthData.achieved;
            const monthRate = monthPlanned > 0 ? (monthAchieved / monthPlanned * 100) : 0;
            const monthColor = getPerformanceColor(monthRate);
            
            $(`[id^="month${monthNum}_q"]`).each(function() {
                const plannedBadge = $(this).closest('.month-card').find('.badge-warning, .badge-light');
                plannedBadge.removeClass('badge-warning badge-light');
                plannedBadge.addClass(monthPlanned > 0 ? 'badge-warning' : 'badge-light');
                plannedBadge.text(monthPlanned.toFixed(1) + ' {{ __('ha') }}');
                
                const achievedBadge = $(this).closest('.month-card').find('.badge-success, .badge-light').first();
                achievedBadge.removeClass('badge-success badge-light');
                achievedBadge.addClass(monthAchieved > 0 ? 'badge-success' : 'badge-light');
                achievedBadge.text(monthAchieved.toFixed(1) + ' {{ __('ha') }}');
                
                const rateBadge = $(this).closest('.month-card').find('span[style*="background-color"]').last();
                rateBadge.css('background-color', monthColor);
                rateBadge.text(monthRate.toFixed(1) + '%');
            });
        }

        function updateQuarterSummary(quarterNum) {
            const quarterData = calculateQuarterData(quarterNum);
            const quarterPlanned = quarterData.planned;
            const quarterAchieved = quarterData.achieved;
            const quarterRate = quarterPlanned > 0 ? (quarterAchieved / quarterPlanned * 100) : 0;
            const quarterColor = getPerformanceColor(quarterRate);
            
            $(`#quarter${quarterNum}`).closest('.quarter-card').find('.badge-warning, .badge-light').each(function() {
                $(this).removeClass('badge-warning badge-light');
                $(this).addClass(quarterPlanned > 0 ? 'badge-warning' : 'badge-light');
                $(this).text(quarterPlanned.toFixed(1) + ' {{ __('ha') }}');
            });
            
            $(`#quarter${quarterNum}`).closest('.quarter-card').find('.badge-success, .badge-light').each(function() {
                $(this).removeClass('badge-success badge-light');
                $(this).addClass(quarterAchieved > 0 ? 'badge-success' : 'badge-light');
                $(this).text(quarterAchieved.toFixed(1) + ' {{ __('ha') }}');
            });
            
            const rateBadge = $(`#quarter${quarterNum}`).closest('.quarter-card').find('span[style*="background-color"]').last();
            rateBadge.css('background-color', quarterColor);
            rateBadge.text(quarterRate.toFixed(1) + '%');
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
            let totalAchieved = 0;

            if (weeklyDistributions.length === 0) {
                $("#summaryTable").append(`
                    <tr>
                        <td colspan="8" class="text-center py-4" style="color: black;">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            {{ __('No achievement data yet. Start by selecting quarters above.') }}
                        </td>
                    </tr>
                `);
            } else {
                weeklyDistributions.forEach(dist => {
                    if (dist.planned_area > 0) {
                        totalPlanned += dist.planned_area;
                        totalAchieved += dist.achieved_area;
                        
                        const status = calculateStatus(dist.planned_area, dist.achieved_area);
                        const performance = calculatePerformance(dist.planned_area, dist.achieved_area);
                        const statusClass = getStatusClass(status);
                        const performanceClass = getPerformanceClass(performance);
                        const weekRange = getWeekRange(EC_MONTHS.findIndex(m => m.name === dist.month) + 1, dist.week);

                        $("#summaryTable").append(`
                            <tr>
                                <td style="color: black;">{{ __('Week') }} ${dist.week}</td>
                                <td style="color: black;">${dist.month}</td>
                                <td style="color: black;">${dist.quarter}</td>
                                <td style="color: black;">${weekRange}</td>
                                <td class="font-weight-bold" style="color: black;">${dist.planned_area.toFixed(2)} ha</td>
                                <td class="font-weight-bold" style="color: black;">${dist.achieved_area.toFixed(2)} ha</td>
                                <td><span class="badge ${statusClass}" style="color: black; background-color: ${status === 'Not Started' ? 'white' : status === 'In Progress' ? '#fff3cd' : status === 'Completed' ? '#d4edda' : '#cce5ff'}; border: 1px solid #ddd;">${status}</span></td>
                                <td><span class="badge ${performanceClass}" style="color: ${performanceClass.includes('secondary') ? 'black' : 'white'};">${performance}</span></td>
                            </tr>
                        `);
                    }
                });
            }

            const overallPerformance = totalPlanned > 0 ? (totalAchieved / totalPlanned * 100) : 0;
            $("#summaryPlanned").text(totalPlanned.toFixed(2));
            $("#summaryAchieved").text(totalAchieved.toFixed(2));
            $("#summaryPerformance").text(overallPerformance.toFixed(1) + "%");
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
                        planned_area: dist.planned_area,
                        achieved_area: dist.achieved_area
                    };
                } else {
                    // Add new week
                    month.weeks.push({
                        week: dist.week,
                        planned_area: dist.planned_area,
                        achieved_area: dist.achieved_area
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
                year: preparationYear,
                quarters: quartersArray
            };
            
            console.log('Saved achievement data:', planData);
            $("#plandivisionDetails").val(JSON.stringify(planData));
            return planData;
        }

        function saveAllAchievements() {
            let hasErrors = false;

            $('.achievement-input').each(function() {
                if (!validateAchievementInput(this)) hasErrors = true;
            });

            if (hasErrors) {
                showAlert(translations.fix_validation_errors_save, 'error');
                return;
            }

            saveJSON();
            showAlert(translations.all_achievements_saved, 'success');
        }

        function clearAllAchievements() {
            if (!confirm('{{ __('Are you sure you want to clear all achievements? This cannot be undone.') }}')) {
                return;
            }

            // Reset all achievement values to 0 but keep planned areas
            weeklyDistributions.forEach(dist => {
                dist.achieved_area = 0;
            });
            
            // Reset all achievement inputs
            $('.achievement-input').val('0.00');
            
            // Re-validate all inputs
            $('.achievement-input').each(function() {
                validateAchievementInput(this);
            });
            
            updateAreaTotals();
            updateSummary();
            
            // Re-render the containers to update badges
            renderAchievementContainer();
            
            showAlert('{{ __('All achievements cleared') }}', 'info');
        }

        /* -------------------------------
           FORM VALIDATION
        ------------------------------- */
        function validateForm() {
            let isValid = true;
            const errors = [];

            $('.achievement-input').each(function() {
                if ($(this).hasClass('is-invalid')) {
                    errors.push('{{ __('Some achievements have validation errors') }}');
                    isValid = false;
                    return false;
                }
            });

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

        .custom-control-input:disabled ~ .custom-control-label {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Achievement input groups */
        .achievement-input-group {
            transition: all 0.3s ease;
        }
        
        .achievement-input-group:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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
            
            .achievement-input-group .row > div {
                margin-bottom: 15px;
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

        /* Progress bars */
        .progress {
            background-color: #e9ecef;
            border-radius: 0.25rem;
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

        /* Badge styling */
        .badge-warning {
            background-color: #fff3cd !important;
            color: #856404 !important;
        }
        
        .badge-success {
            background-color: #d4edda !important;
            color: #155724 !important;
        }
        
        .badge-info {
            background-color: #d1ecf1 !important;
            color: #0c5460 !important;
        }
        
        .badge-danger {
            background-color: #f8d7da !important;
            color: #721c24 !important;
        }
        
        .badge-primary {
            background-color: #cce5ff !important;
            color: #004085 !important;
        }

        /* Achievement progress bars */
        .achievement-progress .progress {
            height: 10px;
        }
        
        .achievement-progress .progress-bar {
            transition: width 0.6s ease, background-color 0.3s ease;
        }

        /* Ensure all text is black and backgrounds white */
        body, .card, .card-header, .card-body, .card-title, .card-footer,
        .table, .table th, .table td, .form-control, .input-group-text,
        .badge.badge-light, .alert, .btn, .breadcrumb, .content-header h1,
        .info-box-text, .info-box-number, .progress-description,
        h1, h2, h3, h4, h5, h6, p, span, div, label, small {
            color: black !important;
            background-color: white !important;
        }
        
        /* Ensure borders are visible */
        .card, .table, .form-control, .btn, .badge.badge-light, .alert,
        .input-group-text {
            border-color: #ddd !important;
        }
        
        /* Override bootstrap badge colors for light badges */
        .badge-light {
            color: #212529 !important;
            background-color: #f8f9fa !important;
        }
    </style>
</x-layout>