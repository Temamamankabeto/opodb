<x-layout>
    <div class="content-header">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ __('Fertilizer Achievement Tracking') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('sector.crop-plans.index') }}">{{ __('Crop Plans') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('Fertilizer Achievement') }}</li>
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
                            <div class="card-header" style="background-color: white; color: black;">
                                <h3 class="card-title" style="color: black;"><i class="fas fa-chart-line mr-2"></i>{{ __('Fertilizer Achievement Tracking') }}</h3>
                            </div>
                            
                            <!-- form start -->
                            <form action="{{ route('sector.crop-plans.update-fertilizer-distribution', $plan->id) }}" method="POST" id="achievementForm">
                                @csrf
                                @method('PUT')

                                <!-- Hidden field for fertilizer distribution details -->
                                <input type="hidden" name="fertilizer_distribution_details" id="fertilizerDistributionDetails"
                                       value="{{ json_encode($plan->fertilizer_distribution_details ?? ['year' => $plan->year - 1, 'quarters' => []]) }}">

                                <div class="card-body" style="background-color: white; color: black;">
                                    <!-- Available Fertilizers Summary -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h5 class="mb-3" style="color: black;">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                {{ __('Available Fertilizers') }}
                                            </h5>
                                            <div class="table-responsive">
                                                <table class="table table-bordered" style="background-color: white; color: black; border-color: #ddd;">
                                                    <thead style="background-color: #f8f9fa;">
                                                        <tr>
                                                            <th style="color: black;">{{ __('Fertilizer') }}</th>
                                                            <th style="color: black;">{{ __('Type') }}</th>
                                                            <th style="color: black;">{{ __('Total Available') }}</th>
                                                            <th style="color: black;">{{ __('Planned') }}</th>
                                                            <th style="color: black;">{{ __('Achieved') }}</th>
                                                            <th style="color: black;">{{ __('Performance') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $fertilizerDetails = $plan->fertilizer_details ?? [];
                                                            $fertilizerTotals = [];
                                                            $totalPlanned = 0;
                                                            $totalAchieved = 0;
                                                        @endphp
                                                        @if (count($fertilizerDetails) > 0)
                                                            @foreach ($fertilizerDetails as $fertDetail)
                                                                @php
                                                                    $fertilizer = \App\Models\Fertilizer::find($fertDetail['fertilizer_id']);
                                                                    $fertilizerType = $fertilizer->fertilizerType ?? null;
                                                                    $fertilizerTotals[$fertDetail['fertilizer_id']] = [
                                                                        'name' => $fertilizer->name ?? 'Unknown',
                                                                        'type' => $fertilizerType->name ?? 'N/A',
                                                                        'total' => $fertDetail['amount'],
                                                                        'unit' => $fertDetail['unit']
                                                                    ];
                                                                @endphp
                                                                @if ($fertilizer)
                                                                    <tr>
                                                                        <td style="color: black; border-color: #ddd;">
                                                                            {{ $fertilizer->name }}
                                                                        </td>
                                                                        <td style="color: black; border-color: #ddd;">
                                                                            {{ $fertilizerType->name ?? 'N/A' }}
                                                                        </td>
                                                                        <td style="color: black; border-color: #ddd;">
                                                                            {{ $fertDetail['amount'] }} {{ $fertDetail['unit'] }}
                                                                        </td>
                                                                        <td style="color: black; border-color: #ddd;" class="planned-total" data-fert-id="{{ $fertDetail['fertilizer_id'] }}">
                                                                            0 {{ $fertDetail['unit'] }}
                                                                        </td>
                                                                        <td style="color: black; border-color: #ddd;" class="achieved-total" data-fert-id="{{ $fertDetail['fertilizer_id'] }}">
                                                                            0 {{ $fertDetail['unit'] }}
                                                                        </td>
                                                                        <td style="color: black; border-color: #ddd;" class="performance-total" data-fert-id="{{ $fertDetail['fertilizer_id'] }}">
                                                                            0%
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td colspan="6" style="color: black; border-color: #ddd; text-align: center;">
                                                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                                                    {{ __('No fertilizers allocated for this crop plan.') }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Summary Cards -->
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <div class="info-box" style="background-color: white; border: 1px solid #ddd; color: black;">
                                                <span class="info-box-icon" style="background-color: white; color: black;"><i class="fas fa-bullseye"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text" style="color: black;">{{ __('Total Planned') }}</span>
                                                    <span class="info-box-number" style="color: black;" id="totalPlannedDisplay">0</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-box" style="background-color: white; border: 1px solid #ddd; color: black;">
                                                <span class="info-box-icon" style="background-color: white; color: black;"><i class="fas fa-calendar-check"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text" style="color: black;">{{ __('Distribution Points') }}</span>
                                                    <span class="info-box-number" style="color: black;" id="distributionPoints">0</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-box" style="background-color: white; border: 1px solid #ddd; color: black;">
                                                <span class="info-box-icon" style="background-color: white; color: black;"><i class="fas fa-trophy"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text" style="color: black;">{{ __('Total Achieved') }}</span>
                                                    <span class="info-box-number" style="color: black;" id="totalAchieved">0</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-box" style="background-color: white; border: 1px solid #ddd; color: black;">
                                                <span class="info-box-icon" style="background-color: white; color: black;"><i class="fas fa-chart-line"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text" style="color: black;">{{ __('Performance') }}</span>
                                                    <span class="info-box-number" style="color: black;" id="performanceRate">0%</span>
                                                    <div class="progress">
                                                        <div class="progress-bar" style="background-color: #ddd;" id="performanceBar" style="width: 0%"></div>
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
                                                    {{ __('Expand quarters to see planned weeks. Each week may have multiple fertilizers. Enter achieved quantities for each fertilizer. You can enter values higher than planned to track over-achievement.') }}
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
                                                            <th style="color: black;">{{ __('Fertilizer') }}</th>
                                                            <th style="color: black;">{{ __('Planned') }}</th>
                                                            <th style="color: black;">{{ __('Achieved') }}</th>
                                                            <th style="color: black;">{{ __('Status') }}</th>
                                                            <th style="color: black;">{{ __('Performance') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="summaryTable">
                                                        <tr>
                                                            <td colspan="9" class="text-center py-4" style="color: black;">
                                                                <i class="fas fa-calendar-alt mr-2"></i>
                                                                {{ __('No achievement data yet. Expand quarters to view planned weeks.') }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="5" class="text-right" style="color: black; font-weight: bold;">{{ __('Total') }}:</td>
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
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <button type="submit" class="btn" id="submitBtn" style="background-color: white; border: 1px solid #ddd; color: black;">
                                                <i class="fas fa-save mr-2"></i> {{ __('Update Achievements') }}
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
            nearly_done: "{{ __('Nearly Done') }}",
            completed: "{{ __('Completed') }}",
            over_achieved: "{{ __('Over Achieved') }}",
            poor: "{{ __('Poor') }}",
            below_target: "{{ __('Below Target') }}",
            near_target: "{{ __('Near Target') }}",
            on_target: "{{ __('On Target') }}",
            exceeded_target: "{{ __('Exceeded Target') }}",
            outstanding: "{{ __('Outstanding') }}",
            fix_validation_errors: "{{ __('Please fix the validation errors before submitting.') }}",
            fix_validation_errors_save: "{{ __('Please fix validation errors before saving') }}",
            all_achievements_saved: "{{ __('All achievements saved successfully!') }}",
            negative_value: "{{ __('Negative value') }}",
            kg: "{{ __('kg') }}",
            expand: "{{ __('Expand') }}",
            collapse: "{{ __('Collapse') }}",
            no_planned_data: "{{ __('No planned data for this quarter.') }}"
        };

        // Global variables
        let fertilizerDistributions = [];
        let selectedQuarters = new Set();
        const applicationYear = {{ $plan->year - 1 }};
        
        // Available fertilizers data (for reference only, not for validation)
        const AVAILABLE_FERTILIZERS = {
            @foreach ($plan->fertilizer_details ?? [] as $fertDetail)
                @php
                    $fertilizer = \App\Models\Fertilizer::find($fertDetail['fertilizer_id']);
                    $fertilizerType = $fertilizer->fertilizerType ?? null;
                @endphp
                @if ($fertilizer)
                    {{ $fertDetail['fertilizer_id'] }}: {
                        id: {{ $fertDetail['fertilizer_id'] }},
                        name: "{{ $fertilizer->name }}",
                        type_name: "{{ $fertilizerType->name ?? __('N/A') }}",
                        unit: "{{ $fertDetail['unit'] }}",
                        total_quantity: {{ $fertDetail['amount'] }}
                    },
                @endif
            @endforeach
        };

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

        // Performance calculation functions
        function calculateStatus(planned, achieved) {
            if (achieved === 0) return translations.not_started;
            if (achieved < planned * 0.5) return translations.in_progress;
            if (achieved < planned) return translations.nearly_done;
            if (achieved === planned) return translations.completed;
            return translations.over_achieved;
        }

        function calculatePerformance(planned, achieved) {
            if (planned === 0) return translations.outstanding;
            
            const percentage = (achieved / planned) * 100;
            
            if (percentage === 0) return translations.not_started;
            if (percentage < 50) return translations.poor;
            if (percentage < 80) return translations.below_target;
            if (percentage < 100) return translations.near_target;
            if (percentage === 100) return translations.on_target;
            if (percentage <= 120) return translations.exceeded_target;
            return translations.outstanding;
        }

        function getPerformanceClass(performance) {
            switch(performance) {
                case translations.not_started: return 'badge-secondary';
                case translations.poor: return 'badge-danger';
                case translations.below_target: return 'badge-warning';
                case translations.near_target: return 'badge-info';
                case translations.on_target: return 'badge-success';
                case translations.exceeded_target: return 'badge-primary';
                case translations.outstanding: return 'badge-primary';
                default: return 'badge-secondary';
            }
        }

        function getStatusClass(status) {
            switch(status) {
                case translations.not_started: return 'badge-secondary';
                case translations.in_progress: return 'badge-warning';
                case translations.nearly_done: return 'badge-info';
                case translations.completed: return 'badge-success';
                case translations.over_achieved: return 'badge-primary';
                default: return 'badge-secondary';
            }
        }

        function getPerformanceColor(percentage) {
            if (percentage === 0) return '#ddd';
            if (percentage < 50) return '#dc3545';
            if (percentage < 80) return '#ffc107';
            if (percentage < 100) return '#17a2b8';
            if (percentage === 100) return '#28a745';
            if (percentage <= 120) return '#007bff';
            return '#6f42c1';
        }

        // Helper function to calculate week range
        function getWeekRange(monthNum, weekNumber) {
            const month = EC_MONTHS[monthNum - 1];
            const start = 1 + (weekNumber - 1) * 7;
            const end = Math.min(start + 6, month.days);
            return `${start}-${end}`;
        }

        // Initialize form
        $(document).ready(function() {
            console.log('Initializing fertilizer achievement for application year:', applicationYear);
            console.log('Available fertilizers:', AVAILABLE_FERTILIZERS);
            
            // Load existing distribution
            loadExistingDistribution();
            
            // Render planning container
            renderPlanningContainer();
            
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
            const existing = {!! json_encode($plan->fertilizer_distribution_details ?? ['year' => $plan->year - 1, 'quarters' => []]) !!};
            console.log('Loading existing achievement data:', existing);
            
            fertilizerDistributions = [];
            
            if (existing.quarters && Array.isArray(existing.quarters)) {
                existing.quarters.forEach(quarter => {
                    const quarterNum = parseInt(quarter.quarter);
                    
                    // Add quarter to selected if it has planned data
                    let hasPlannedData = false;
                    
                    if (quarter.months && Array.isArray(quarter.months)) {
                        quarter.months.forEach(monthData => {
                            if (monthData.weeks && Array.isArray(monthData.weeks)) {
                                monthData.weeks.forEach(weekData => {
                                    if (weekData.fertilizers && Array.isArray(weekData.fertilizers)) {
                                        weekData.fertilizers.forEach(fertData => {
                                            if (parseFloat(fertData.planned_value || 0) > 0) {
                                                hasPlannedData = true;
                                            }
                                            
                                            fertilizerDistributions.push({
                                                year: existing.year,
                                                quarter: `Q${quarterNum}`,
                                                month: monthData.month,
                                                week: weekData.week,
                                                fertilizer_id: fertData.fertilizer_id,
                                                fertilizer_name: fertData.name || AVAILABLE_FERTILIZERS[fertData.fertilizer_id]?.name || 'Unknown',
                                                planned_value: parseFloat(fertData.planned_value || 0),
                                                achieved_value: parseFloat(fertData.achieved_value || 0),
                                                unit: fertData.unit || AVAILABLE_FERTILIZERS[fertData.fertilizer_id]?.unit || 'kg',
                                                status: calculateStatus(parseFloat(fertData.planned_value || 0), parseFloat(fertData.achieved_value || 0)),
                                                performance: calculatePerformance(parseFloat(fertData.planned_value || 0), parseFloat(fertData.achieved_value || 0))
                                            });
                                        });
                                    }
                                });
                            }
                        });
                    }
                    
                    if (hasPlannedData) {
                        selectedQuarters.add(quarterNum);
                    }
                });
            }
            
            console.log('Loaded distributions:', fertilizerDistributions);
            updateFertilizerTotals();
            updateSummary();
        }

        /* -------------------------------
           UPDATE FERTILIZER TOTALS
        -------------------------------- */
        function updateFertilizerTotals() {
            // Calculate totals for each fertilizer
            const fertTotals = {};
            
            fertilizerDistributions.forEach(dist => {
                const fertId = dist.fertilizer_id;
                if (!fertTotals[fertId]) {
                    fertTotals[fertId] = {
                        planned: 0,
                        achieved: 0
                    };
                }
                fertTotals[fertId].planned += dist.planned_value;
                fertTotals[fertId].achieved += dist.achieved_value;
            });
            
            // Update display for each fertilizer
            Object.keys(fertTotals).forEach(fertId => {
                const total = fertTotals[fertId];
                const percentage = total.planned > 0 ? (total.achieved / total.planned * 100) : 0;
                const fert = AVAILABLE_FERTILIZERS[fertId];
                
                if (fert) {
                    $(`.planned-total[data-fert-id="${fertId}"]`).text(`${total.planned.toFixed(2)} ${fert.unit}`);
                    $(`.achieved-total[data-fert-id="${fertId}"]`).text(`${total.achieved.toFixed(2)} ${fert.unit}`);
                    $(`.performance-total[data-fert-id="${fertId}"]`).text(`${percentage.toFixed(1)}%`);
                }
            });
            
            // Update overall totals
            updateSummaryCards();
        }

        /* -------------------------------
           RENDER PLANNING CONTAINER
        ------------------------------- */
        function renderPlanningContainer() {
            $("#planningContainer").empty();

            Object.keys(EC_QUARTERS).forEach(q => {
                const quarterNum = parseInt(q);
                const quarterData = calculateQuarterData(quarterNum);
                const quarterName = `Q${q}`;
                
                // Check if this quarter has planned data
                const hasPlannedData = fertilizerDistributions.some(dist => 
                    dist.quarter === quarterName && dist.planned_value > 0
                );
                
                const quarterPlanned = quarterData.planned;
                const quarterAchieved = quarterData.achieved;
                const quarterPerformance = quarterPlanned > 0 ? (quarterAchieved / quarterPlanned * 100) : 0;

                $("#planningContainer").append(`
                    <div class="card quarter-card mb-3 ${hasPlannedData ? 'card-primary' : 'card-secondary'}" style="background-color: white; border: 1px solid #ddd;">
                        <div class="card-header d-flex justify-content-between align-items-center" style="cursor: pointer; background-color: white; color: black; border-bottom: 1px solid #ddd;">
                            <div class="d-flex align-items-center">
                                <div class="custom-control custom-checkbox mr-3">
                                    <input class="custom-control-input quarter-checkbox" type="checkbox"
                                           id="quarter${q}" ${hasPlannedData ? 'checked' : ''}
                                           ${!hasPlannedData ? 'disabled' : ''}>
                                    <label for="quarter${q}" class="custom-control-label font-weight-bold h5 mb-0" style="color: black;">
                                        Q${q} ({{ __('Application Year') }} ${applicationYear})
                                    </label>
                                </div>
                                <div class="ml-3">
                                    <span class="badge ${hasPlannedData ? 'badge-warning' : 'badge-light'}" style="color: black; background-color: ${hasPlannedData ? '#fff3cd' : 'white'}; border: 1px solid #ddd;">
                                        ${quarterPlanned.toFixed(1)}
                                    </span>
                                    <span class="badge ${quarterAchieved > 0 ? 'badge-success' : 'badge-light'} ml-1" style="color: black; background-color: ${quarterAchieved > 0 ? '#d4edda' : 'white'}; border: 1px solid #ddd;">
                                        ${quarterAchieved.toFixed(1)}
                                    </span>
                                    <span class="badge ml-1" style="color: black; background-color: white; border: 1px solid #ddd;">
                                        ${quarterPerformance.toFixed(1)}%
                                    </span>
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn btn-sm toggle-quarter" data-quarter="${q}"
                                        ${!hasPlannedData ? 'disabled' : ''} style="background-color: white; border: 1px solid #ddd; color: black;">
                                    <i class="fas fa-chevron-down"></i> ${translations.expand}
                                </button>
                            </div>
                        </div>
                        <div class="quarter-months collapse" id="quarterMonths${q}" data-quarter="${q}">
                            <div class="card-body p-0" style="background-color: white;">
                                <!-- Months will be loaded here when quarter is expanded -->
                            </div>
                        </div>
                    </div>
                `);

                // Event listeners
                $(`.toggle-quarter[data-quarter="${q}"]`).on('click', function(e) {
                    e.stopPropagation();
                    const quarterId = $(this).data('quarter');
                    const isDisabled = $(this).prop('disabled');
                    
                    if (!isDisabled) {
                        const monthsContainer = $(`#quarterMonths${quarterId}`);
                        
                        if (monthsContainer.hasClass('show')) {
                            collapseQuarter(quarterId);
                        } else {
                            expandQuarter(quarterId);
                        }
                    }
                });

                // Expand if has planned data
                if (hasPlannedData) {
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
            
            setTimeout(() => {
                renderMonthsForQuarter(parseInt(quarterId), quarterId);
                toggleBtn.html(`<i class="fas fa-chevron-up"></i> ${translations.collapse}`);
            }, 100);
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
                    <div class="alert m-3" style="background-color: white; border: 1px solid #ddd; color: black;">
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
                const hasPlannedData = fertilizerDistributions.some(dist => 
                    dist.month === month.name && dist.planned_value > 0
                );
                const monthPlanned = monthData.planned;
                const monthAchieved = monthData.achieved;
                const monthPerformance = monthPlanned > 0 ? (monthAchieved / monthPlanned * 100) : (monthAchieved > 0 ? 100 : 0);

                monthsRow.append(`
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="card month-card h-100 ${hasPlannedData ? 'border-primary' : ''}" style="background-color: white; border: 1px solid #ddd;">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center" style="cursor: pointer; background-color: white !important; color: black; border-bottom: 1px solid #ddd;">
                                <div class="d-flex align-items-center">
                                    <div class="custom-control custom-checkbox mr-2">
                                        <input class="custom-control-input month-checkbox" type="checkbox"
                                               id="month${monthNum}_q${quarterId}" 
                                               data-month="${monthNum}" data-quarter="${quarterId}"
                                               ${hasPlannedData ? '' : 'disabled'}>
                                        <label for="month${monthNum}_q${quarterId}" class="custom-control-label font-weight-bold" style="color: black;">
                                            ${month.name}
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge ${hasPlannedData ? 'badge-warning' : 'badge-light'}" style="color: black; background-color: ${hasPlannedData ? '#fff3cd' : 'white'}; border: 1px solid #ddd;">
                                        ${monthPlanned.toFixed(1)}
                                    </span>
                                    <span class="badge ${monthAchieved > 0 ? 'badge-success' : 'badge-light'} ml-1" style="color: black; background-color: ${monthAchieved > 0 ? '#d4edda' : 'white'}; border: 1px solid #ddd;">
                                        ${monthAchieved.toFixed(1)}
                                    </span>
                                    <span class="badge ml-1" style="color: black; background-color: white; border: 1px solid #ddd;">${monthPerformance.toFixed(1)}%</span>
                                    <button type="button" class="btn btn-sm ml-2 toggle-achievement" 
                                            data-month="${monthNum}" data-quarter="${quarterId}"
                                            ${hasPlannedData ? '' : 'disabled'} style="background-color: white; border: 1px solid #ddd; color: black;">
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
                $(`.toggle-achievement[data-month="${monthNum}"][data-quarter="${quarterId}"]`).on('click', function(e) {
                    e.stopPropagation();
                    const achievementContainer = $(`#achievement${monthNum}_q${quarterId}`);
                    
                    if (achievementContainer.hasClass('show')) {
                        collapseMonthAchievement(monthNum, quarterId);
                    } else {
                        expandMonthAchievement(monthNum, quarterId);
                    }
                });

                // Expand if has planned data
                if (hasPlannedData) {
                    setTimeout(() => expandMonthAchievement(monthNum, quarterId), 200);
                }
            });
        }

        /* -------------------------------
           MONTH FUNCTIONS
        ------------------------------- */
        function expandMonthAchievement(monthNum, quarterId) {
            const achievementContainer = $(`#achievement${monthNum}_q${quarterId}`);
            const toggleBtn = $(`.toggle-achievement[data-month="${monthNum}"][data-quarter="${quarterId}"]`);
            const month = EC_MONTHS[monthNum - 1];
            
            achievementContainer.collapse('show');
            
            // Get distributions for this month and quarter
            const monthDistributions = fertilizerDistributions.filter(dist => 
                dist.month === month.name && dist.quarter === `Q${quarterId}` && dist.planned_value > 0
            );
            
            // Group by week
            const weekGroups = {};
            monthDistributions.forEach(dist => {
                if (!weekGroups[dist.week]) {
                    weekGroups[dist.week] = [];
                }
                weekGroups[dist.week].push(dist);
            });
            
            // Render achievement inputs
            let achievementHtml = '';
            
            Object.keys(weekGroups).sort((a, b) => a - b).forEach(weekNum => {
                const weekDistributions = weekGroups[weekNum];
                const weekRange = getWeekRange(monthNum, parseInt(weekNum));
                
                achievementHtml += `
                    <div class="achievement-input-group mb-4 p-3" style="border: 1px solid #ddd; border-radius: 5px; background-color: white;" data-month="${monthNum}" data-week="${weekNum}">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="badge" style="color: black; background-color: white; border: 1px solid #ddd;">
                                    {{ __('Week') }} ${weekNum}
                                </span>
                                <small class="text-muted d-block">${weekRange}</small>
                            </div>
                        </div>
                        
                        <div class="fertilizer-achievements">
                `;
                
                weekDistributions.forEach((dist, index) => {
                    // Add unique ID for tracking
                    dist.uniqueId = `${month.name}_${dist.quarter}_${dist.week}_${dist.fertilizer_id}`;
                    
                    const status = calculateStatus(dist.planned_value, dist.achieved_value);
                    const performance = calculatePerformance(dist.planned_value, dist.achieved_value);
                    const statusClass = getStatusClass(status);
                    const performanceClass = getPerformanceClass(performance);
                    const performanceColor = getPerformanceColor(dist.planned_value > 0 ? 
                        (dist.achieved_value / dist.planned_value * 100) : 
                        (dist.achieved_value > 0 ? 100 : 0));
                    
                    achievementHtml += `
                        <div class="fertilizer-achievement-item mb-3 p-3" style="border: 1px solid #ddd; border-radius: 5px; background-color: #f8f9fa;" data-unique-id="${dist.uniqueId}">
                            <div class="row">
                                <!-- Fertilizer Info -->
                                <div class="col-md-3 mb-2">
                                    <strong class="d-block" style="color: black;">${dist.fertilizer_name}</strong>
                                    <small class="text-muted d-block">${AVAILABLE_FERTILIZERS[dist.fertilizer_id]?.type_name || ''}</small>
                                    <small class="text-muted">${dist.unit}</small>
                                </div>
                                
                                <!-- Planned Quantity (Read Only) -->
                                <div class="col-md-3 mb-2">
                                    <label class="small fw-bold" style="color: black;">{{ __('Planned') }}</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" step="0.01"
                                               class="form-control planned-input"
                                               data-unique-id="${dist.uniqueId}"
                                               value="${dist.planned_value.toFixed(2)}"
                                               readonly
                                               style="background-color: #fff3cd; color: black; border: 1px solid #ddd; cursor: not-allowed;">
                                        <span class="input-group-text" style="color: black; background-color: white; border: 1px solid #ddd;">${dist.unit}</span>
                                    </div>
                                    <small class="text-muted">{{ __('Read only') }}</small>
                                </div>
                                
                                <!-- Achieved Quantity (Editable) -->
                                <div class="col-md-3 mb-2">
                                    <label class="small fw-bold" style="color: black;">{{ __('Achieved') }}</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" step="0.01" min="0"
                                               class="form-control achievement-input"
                                               data-unique-id="${dist.uniqueId}"
                                               value="${dist.achieved_value.toFixed(2)}"
                                               placeholder="0.00"
                                               style="color: black; background-color: white; border: 1px solid #ddd;"
                                               oninput="validateAchievementInput(this)">
                                        <span class="input-group-text" style="color: black; background-color: white; border: 1px solid #ddd;">${dist.unit}</span>
                                    </div>
                                    <div class="validation-feedback" style="font-size: 0.75rem; min-height: 18px; color: black;"></div>
                                </div>
                                
                                <!-- Status & Performance -->
                                <div class="col-md-3 text-center">
                                    <div class="mb-1">
                                        <span class="badge ${statusClass}" style="color: black; background-color: ${status === translations.not_started ? 'white' : status === translations.in_progress ? '#fff3cd' : status === translations.nearly_done ? '#d1ecf1' : status === translations.completed ? '#d4edda' : '#cce5ff'}; border: 1px solid #ddd;">${status}</span>
                                    </div>
                                    <div>
                                        <span class="badge ${performanceClass}" style="color: black; background-color: ${performance === translations.not_started ? 'white' : performance === translations.poor ? '#f8d7da' : performance === translations.below_target ? '#fff3cd' : performance === translations.near_target ? '#d1ecf1' : performance === translations.on_target ? '#d4edda' : '#cce5ff'}; border: 1px solid #ddd;">${performance}</span>
                                    </div>
                                    <div class="progress mt-2" style="height: 6px; background-color: #ddd;">
                                        <div class="progress-bar" 
                                             style="width: ${dist.planned_value > 0 ? 
                                                Math.min((dist.achieved_value / dist.planned_value * 100), 120) : 
                                                (dist.achieved_value > 0 ? 100 : 0)}%; 
                                             background-color: ${performanceColor}">
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        ${dist.planned_value > 0 ? 
                                            ((dist.achieved_value / dist.planned_value * 100).toFixed(1)) : 
                                            (dist.achieved_value > 0 ? '100.0' : '0.0')}%
                                    </small>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                achievementHtml += `
                        </div>
                    </div>
                `;
            });
            
            const achievementContainerDiv = $(`#achievementContainer${monthNum}_q${quarterId}`);
            
            if (achievementHtml === '') {
                achievementContainerDiv.html(`
                    <div class="alert" style="background-color: white; border: 1px solid #ddd; color: black;">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ __('No planned weeks found for this month.') }}
                    </div>
                `);
            } else {
                achievementContainerDiv.html(achievementHtml);
                
                // Add input validation
                $(`#achievementContainer${monthNum}_q${quarterId} .achievement-input`).off('input').on('input', function() {
                    validateAchievementInput(this);
                });
            }
            
            toggleBtn.html(`<i class="fas fa-times"></i>`);
        }

        function collapseMonthAchievement(monthNum, quarterId) {
            const achievementContainer = $(`#achievement${monthNum}_q${quarterId}`);
            const toggleBtn = $(`.toggle-achievement[data-month="${monthNum}"][data-quarter="${quarterId}"]`);
            
            achievementContainer.collapse('hide');
            toggleBtn.html(`<i class="fas fa-trophy"></i>`);
        }

        /* -------------------------------
           INPUT VALIDATION
        ------------------------------- */
        function validateAchievementInput(input) {
            const uniqueId = $(input).data('unique-id');
            const value = parseFloat($(input).val()) || 0;
            const feedback = $(input).closest('.fertilizer-achievement-item').find('.validation-feedback');

            $(input).removeClass('is-valid is-invalid border-success border-danger');
            feedback.empty();

            if (value < 0) {
                $(input).addClass('is-invalid border-danger');
                feedback.html(`<small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>${translations.negative_value}</small>`);
                return false;
            }

            if (value > 0) {
                $(input).addClass('is-valid border-success');
            }

            updateAchievementDistribution(input);
            return true;
        }

        function updateAchievementDistribution(input) {
            const uniqueId = $(input).data('unique-id');
            const achievedValue = parseFloat($(input).val()) || 0;
            
            // Find distribution by unique ID
            let distribution = fertilizerDistributions.find(dist => 
                dist.uniqueId === uniqueId ||
                `${dist.month}_${dist.quarter}_${dist.week}_${dist.fertilizer_id}` === uniqueId
            );

            if (distribution) {
                distribution.achieved_value = achievedValue;
                distribution.status = calculateStatus(distribution.planned_value, achievedValue);
                distribution.performance = calculatePerformance(distribution.planned_value, achievedValue);
                
                // Update UI
                updateAchievementUI(uniqueId, distribution.planned_value, achievedValue);
                updateMonthSummary(distribution.month);
                updateQuarterSummary(parseInt(distribution.quarter.replace('Q', '')));
                updateFertilizerTotals();
                updateSummary();
            }
        }

        function updateAchievementUI(uniqueId, plannedValue, achievedValue) {
            const status = calculateStatus(plannedValue, achievedValue);
            const performance = calculatePerformance(plannedValue, achievedValue);
            const statusClass = getStatusClass(status);
            const performanceClass = getPerformanceClass(performance);
            const performanceColor = getPerformanceColor(plannedValue > 0 ? 
                (achievedValue / plannedValue * 100) : 
                (achievedValue > 0 ? 100 : 0));
            const percentage = plannedValue > 0 ? (achievedValue / plannedValue * 100) : (achievedValue > 0 ? 100 : 0);
            
            const achievementItem = $(`.fertilizer-achievement-item[data-unique-id="${uniqueId}"]`);
            
            if (achievementItem.length > 0) {
                achievementItem.find('.badge:first')
                    .removeClass('badge-secondary badge-warning badge-info badge-success badge-primary')
                    .addClass(statusClass)
                    .text(status)
                    .css({
                        'color': 'black',
                        'background-color': status === translations.not_started ? 'white' : 
                                          status === translations.in_progress ? '#fff3cd' : 
                                          status === translations.nearly_done ? '#d1ecf1' : 
                                          status === translations.completed ? '#d4edda' : '#cce5ff',
                        'border': '1px solid #ddd'
                    });
                
                achievementItem.find('.badge:eq(1)')
                    .removeClass('badge-secondary badge-danger badge-warning badge-info badge-success badge-primary')
                    .addClass(performanceClass)
                    .text(performance)
                    .css({
                        'color': 'black',
                        'background-color': performance === translations.not_started ? 'white' : 
                                          performance === translations.poor ? '#f8d7da' : 
                                          performance === translations.below_target ? '#fff3cd' : 
                                          performance === translations.near_target ? '#d1ecf1' : 
                                          performance === translations.on_target ? '#d4edda' : '#cce5ff',
                        'border': '1px solid #ddd'
                    });
                
                const progressBar = achievementItem.find('.progress-bar');
                progressBar.css({
                    'width': Math.min(percentage, 120) + '%',
                    'background-color': performanceColor
                });
                
                achievementItem.find('.text-muted').last().text(percentage.toFixed(1) + '%');
            }
        }

        /* -------------------------------
           UTILITY FUNCTIONS
        ------------------------------- */
        function calculateQuarterData(quarter) {
            const quarterName = `Q${quarter}`;
            const quarterDistributions = fertilizerDistributions.filter(dist => dist.quarter === quarterName);
            
            const planned = quarterDistributions.reduce((total, dist) => total + (dist.planned_value || 0), 0);
            const achieved = quarterDistributions.reduce((total, dist) => total + (dist.achieved_value || 0), 0);
            
            return { planned, achieved };
        }

        function calculateMonthData(monthNum) {
            const monthName = EC_MONTHS[monthNum - 1].name;
            const monthDistributions = fertilizerDistributions.filter(dist => dist.month === monthName);
            
            const planned = monthDistributions.reduce((total, dist) => total + (dist.planned_value || 0), 0);
            const achieved = monthDistributions.reduce((total, dist) => total + (dist.achieved_value || 0), 0);
            
            return { planned, achieved };
        }

        function updateMonthSummary(monthName) {
            const monthNum = EC_MONTHS.findIndex(m => m.name === monthName) + 1;
            if (monthNum <= 0) return;
            
            const monthData = calculateMonthData(monthNum);
            const monthPlanned = monthData.planned;
            const monthAchieved = monthData.achieved;
            const monthPerformance = monthPlanned > 0 ? (monthAchieved / monthPlanned * 100) : (monthAchieved > 0 ? 100 : 0);
            
            // Update month badges
            $(`[id^="month${monthNum}_q"]`).each(function() {
                const monthBadges = $(this).closest('.month-card').find('.badge-warning, .badge-light');
                monthBadges.first()
                    .removeClass('badge-warning badge-light')
                    .addClass(monthPlanned > 0 ? 'badge-warning' : 'badge-light')
                    .text(monthPlanned.toFixed(1))
                    .css({
                        'color': 'black',
                        'background-color': monthPlanned > 0 ? '#fff3cd' : 'white',
                        'border': '1px solid #ddd'
                    });
                
                const achievedBadges = $(this).closest('.month-card').find('.badge-success, .badge-light');
                achievedBadges.first()
                    .removeClass('badge-success badge-light')
                    .addClass(monthAchieved > 0 ? 'badge-success' : 'badge-light')
                    .text(monthAchieved.toFixed(1))
                    .css({
                        'color': 'black',
                        'background-color': monthAchieved > 0 ? '#d4edda' : 'white',
                        'border': '1px solid #ddd'
                    });
                
                const performanceBadge = $(this).closest('.month-card').find('.badge').eq(2);
                performanceBadge.text(monthPerformance.toFixed(1) + '%');
                performanceBadge.css({
                    'color': 'black',
                    'background-color': 'white',
                    'border': '1px solid #ddd'
                });
            });
        }

        function updateQuarterSummary(quarterNum) {
            const quarterData = calculateQuarterData(quarterNum);
            const quarterPlanned = quarterData.planned;
            const quarterAchieved = quarterData.achieved;
            const quarterPerformance = quarterPlanned > 0 ? (quarterAchieved / quarterPlanned * 100) : (quarterAchieved > 0 ? 100 : 0);
            
            const quarterCard = $(`#quarter${quarterNum}`).closest('.quarter-card');
            
            // Update planned badge
            quarterCard.find('.badge-warning, .badge-light').first()
                .removeClass('badge-warning badge-light')
                .addClass(quarterPlanned > 0 ? 'badge-warning' : 'badge-light')
                .text(quarterPlanned.toFixed(1))
                .css({
                    'color': 'black',
                    'background-color': quarterPlanned > 0 ? '#fff3cd' : 'white',
                    'border': '1px solid #ddd'
                });
            
            // Update achieved badge
            quarterCard.find('.badge-success, .badge-light').eq(1)
                .removeClass('badge-success badge-light')
                .addClass(quarterAchieved > 0 ? 'badge-success' : 'badge-light')
                .text(quarterAchieved.toFixed(1))
                .css({
                    'color': 'black',
                    'background-color': quarterAchieved > 0 ? '#d4edda' : 'white',
                    'border': '1px solid #ddd'
                });
            
            // Update performance badge
            const performanceBadge = quarterCard.find('.badge').eq(2);
            performanceBadge.text(quarterPerformance.toFixed(1) + '%');
            performanceBadge.css({
                'color': 'black',
                'background-color': 'white',
                'border': '1px solid #ddd'
            });
        }

        /* -------------------------------
           SUMMARY FUNCTIONS
        ------------------------------- */
        function updateSummary() {
            updateSummaryTable();
            updateSummaryCards();
        }

        function updateSummaryTable() {
            $("#summaryTable").empty();
            let totalPlanned = 0;
            let totalAchieved = 0;
            let distributionPoints = 0;

            if (fertilizerDistributions.length === 0) {
                $("#summaryTable").append(`
                    <tr>
                        <td colspan="9" class="text-center py-4" style="color: black;">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            {{ __('No achievement data yet. Expand quarters to view planned weeks.') }}
                        </td>
                    </tr>
                `);
            } else {
                fertilizerDistributions.forEach(dist => {
                    if (dist.planned_value > 0) {
                        totalPlanned += dist.planned_value;
                        totalAchieved += dist.achieved_value;
                        distributionPoints++;
                        
                        const statusClass = getStatusClass(dist.status);
                        const performanceClass = getPerformanceClass(dist.performance);
                        const weekRange = getWeekRange(EC_MONTHS.findIndex(m => m.name === dist.month) + 1, dist.week);

                        $("#summaryTable").append(`
                            <tr>
                                <td style="color: black;">{{ __('Week') }} ${dist.week}</td>
                                <td style="color: black;">${dist.month}</td>
                                <td style="color: black;">${dist.quarter}</td>
                                <td style="color: black;">${weekRange}</td>
                                <td style="color: black;">${dist.fertilizer_name}</td>
                                <td class="fw-bold" style="color: black;">${dist.planned_value.toFixed(2)} ${dist.unit}</td>
                                <td class="fw-bold" style="color: black;">${dist.achieved_value.toFixed(2)} ${dist.unit}</td>
                                <td><span class="badge ${statusClass}" style="color: black; background-color: ${dist.status === translations.not_started ? 'white' : dist.status === translations.in_progress ? '#fff3cd' : dist.status === translations.nearly_done ? '#d1ecf1' : dist.status === translations.completed ? '#d4edda' : '#cce5ff'}; border: 1px solid #ddd;">${dist.status}</span></td>
                                <td><span class="badge ${performanceClass}" style="color: black; background-color: ${dist.performance === translations.not_started ? 'white' : dist.performance === translations.poor ? '#f8d7da' : dist.performance === translations.below_target ? '#fff3cd' : dist.performance === translations.near_target ? '#d1ecf1' : dist.performance === translations.on_target ? '#d4edda' : '#cce5ff'}; border: 1px solid #ddd;">${dist.performance}</span></td>
                            </tr>
                        `);
                    }
                });
            }

            const overallPerformance = totalPlanned > 0 ? (totalAchieved / totalPlanned * 100) : 0;
            $("#summaryPlanned").text(totalPlanned.toFixed(2));
            $("#summaryAchieved").text(totalAchieved.toFixed(2));
            $("#summaryPerformance").text(overallPerformance.toFixed(1) + "%");
            
            // Update distribution points count
            $("#distributionPoints").text(distributionPoints);
        }

        function updateSummaryCards() {
            const totalPlanned = fertilizerDistributions.reduce((total, dist) => total + dist.planned_value, 0);
            const totalAchieved = fertilizerDistributions.reduce((total, dist) => total + dist.achieved_value, 0);
            const distributionPoints = fertilizerDistributions.filter(dist => dist.planned_value > 0).length;
            const performanceRate = totalPlanned > 0 ? (totalAchieved / totalPlanned * 100) : 0;

            $('#totalPlannedDisplay').text(totalPlanned.toFixed(2));
            $('#totalAchieved').text(totalAchieved.toFixed(2));
            $('#distributionPoints').text(distributionPoints);
            $('#performanceRate').text(performanceRate.toFixed(1) + '%');
            $('#performanceBar').css('width', Math.min(performanceRate, 100) + '%');
        }

        /* -------------------------------
           SAVE DATA
        ------------------------------- */
        function saveJSON() {
            // Group data by quarter and month
            const quartersData = {};
            
            fertilizerDistributions.forEach(dist => {
                const quarterNum = parseInt(dist.quarter.replace('Q', ''));
                const quarterKey = `Q${quarterNum}`;
                
                if (!quartersData[quarterKey]) {
                    quartersData[quarterKey] = {
                        quarter: quarterNum,
                        name: quarterKey,
                        months: {}
                    };
                }
                
                const quarter = quartersData[quarterKey];
                
                if (!quarter.months[dist.month]) {
                    quarter.months[dist.month] = {
                        month: dist.month,
                        weeks: {}
                    };
                }
                
                const month = quarter.months[dist.month];
                
                if (!month.weeks[dist.week]) {
                    month.weeks[dist.week] = {
                        week: dist.week,
                        fertilizers: []
                    };
                }
                
                const week = month.weeks[dist.week];
                
                // Check if fertilizer already exists in this week
                const existingFert = week.fertilizers.find(f => f.fertilizer_id == dist.fertilizer_id);
                if (existingFert) {
                    existingFert.planned_value = dist.planned_value;
                    existingFert.achieved_value = dist.achieved_value;
                    existingFert.name = dist.fertilizer_name;
                    existingFert.unit = dist.unit;
                } else {
                    week.fertilizers.push({
                        fertilizer_id: dist.fertilizer_id,
                        name: dist.fertilizer_name,
                        planned_value: dist.planned_value,
                        achieved_value: dist.achieved_value,
                        unit: dist.unit
                    });
                }
            });
            
            // Convert to array format and sort
            const quartersArray = Object.values(quartersData).map(quarter => {
                // Sort months according to Ethiopian calendar order
                const monthsArray = Object.values(quarter.months)
                    .sort((a, b) => {
                        const monthOrder = EC_MONTHS.map(m => m.name);
                        return monthOrder.indexOf(a.month) - monthOrder.indexOf(b.month);
                    });
                
                // Sort weeks within each month and convert to array
                monthsArray.forEach(month => {
                    const weeksArray = Object.values(month.weeks)
                        .sort((a, b) => a.week - b.week);
                    month.weeks = weeksArray;
                });
                
                return {
                    quarter: quarter.quarter,
                    name: quarter.name,
                    months: monthsArray
                };
            });
            
            // Sort quarters
            quartersArray.sort((a, b) => a.quarter - b.quarter);
            
            const planData = {
                year: applicationYear,
                quarters: quartersArray
            };
            
            console.log('Saved fertilizer achievement data:', planData);
            $("#fertilizerDistributionDetails").val(JSON.stringify(planData));
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
        /* Expandable Cards */
        .quarter-card, .month-card {
            transition: all 0.3s ease;
            border-width: 2px;
        }
        
        .quarter-card:hover, .month-card:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .quarter-card .card-header {
            background: white !important;
        }
        
        .quarter-card.card-primary .card-header {
            border-bottom: 2px solid #333;
        }
        
        .month-card .card-header {
            background-color: white !important;
            transition: background-color 0.3s ease;
        }
        
        .month-card.border-primary .card-header {
            border-bottom: 2px solid #333;
        }

        /* Achievement Input Groups */
        .achievement-input-group {
            border-left: 4px solid #ddd;
            transition: all 0.3s ease;
        }
        
        .achievement-input-group:hover {
            border-left-color: #333;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* Fertilizer Achievement Items */
        .fertilizer-achievement-item {
            border-left: 3px solid #ddd;
            transition: all 0.3s ease;
        }
        
        .fertilizer-achievement-item:hover {
            background-color: #e9ecef;
        }

        /* Form Control Validation */
        .achievement-input.is-valid {
            border-color: #28a745 !important;
        }
        
        .achievement-input.is-invalid {
            border-color: #dc3545 !important;
        }

        /* Collapse Animation */
        .collapse {
            transition: all 0.3s ease;
        }

        /* Progress bars */
        .progress {
            border-radius: 2px;
            overflow: hidden;
            background-color: #ddd !important;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .achievement-input-group .row > div {
                margin-bottom: 10px;
            }
            
            .fertilizer-achievement-item .row > div {
                margin-bottom: 10px;
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
        .month-achievement .card-body {
            max-height: 500px;
            overflow-y: auto;
        }
        
        .month-achievement .card-body::-webkit-scrollbar {
            width: 6px;
        }
        
        .month-achievement .card-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        .month-achievement .card-body::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        
        .month-achievement .card-body::-webkit-scrollbar-thumb:hover {
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
        
        /* Hover effects */
        .quarter-card .card-header:hover,
        .month-card .card-header:hover {
            background-color: rgba(0, 0, 0, 0.05) !important;
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
        
        /* Remove all background images/gradients */
        .bg-gradient-*, .bg-* {
            background-image: none !important;
            background-color: white !important;
        }
        
        /* Custom checkbox styling */
        .custom-control-input:checked ~ .custom-control-label::before {
            border-color: #333;
            background-color: #333;
        }
    </style>
</x-layout>