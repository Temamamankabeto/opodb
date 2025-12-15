<x-layout>
    <div class="content-header">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ __('Fertilizer Distribution Plan') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('sector.crop-plans.index') }}">{{ __('Crop Plans') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('Fertilizer Distribution') }}</li>
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
                                <h3 class="card-title" style="color: black;"><i class="fas fa-pagelines mr-2"></i>{{ __('Fertilizer Distribution Plan') }}</h3>
                                <div class="card-tools">
                                    <span class="badge badge-light" style="color: black; background-color: white; border: 1px solid #ddd;">{{ $plan->crop->name ?? __('N/A') }} - {{ $plan->year }} {{ $plan->season }}</span>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form action="{{ route('sector.crop-plans.update-fertilizer-distribution', $plan->id) }}" method="POST" id="fertilizerPlanForm">
                                @csrf
                                @method('PUT')

                                <!-- Hidden field for fertilizer distribution details -->
                                <input type="hidden" name="fertilizer_distribution_details" id="fertilizerDistributionDetails"
                                       value="{{ json_encode($plan->fertilizer_distribution_details ?? ['year' => $plan->year - 1, 'quarters' => []]) }}">

                                <div class="card-body" style="background-color: white; color: black;">
                                    <!-- Crop Information and Available Fertilizers -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h5 class="mb-3" style="color: black;">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                {{ __('Available Fertilizers') }}
                                            </h5>
                                            <div class="table-responsive">
                                                <table class="table table-bordered" style="background-color: white; color: black; border-color: #ddd;">
                                                    <thead>
                                                        <tr style="background-color: #f8f9fa;">
                                                            <th style="color: black;">{{ __('Fertilizer') }}</th>
                                                            <th style="color: black;">{{ __('Type') }}</th>
                                                            <th style="color: black;">{{ __('Total Available') }}</th>
                                                            <th style="color: black;">{{ __('Planned') }}</th>
                                                            <th style="color: black;">{{ __('Remaining') }}</th>
                                                            <th style="color: black;">{{ __('Progress') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $fertilizerDetails = $plan->fertilizer_details ?? [];
                                                            $fertilizerTotals = [];
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
                                                                        'unit' => $fertDetail['unit'],
                                                                        'planned' => 0,
                                                                        'remaining' => $fertDetail['amount']
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
                                                                        <td style="color: black; border-color: #ddd;" class="planned-display" data-fert-id="{{ $fertDetail['fertilizer_id'] }}">
                                                                            0 {{ $fertDetail['unit'] }}
                                                                        </td>
                                                                        <td style="color: black; border-color: #ddd;" class="remaining-display" data-fert-id="{{ $fertDetail['fertilizer_id'] }}">
                                                                            {{ $fertDetail['amount'] }} {{ $fertDetail['unit'] }}
                                                                        </td>
                                                                        <td style="color: black; border-color: #ddd;">
                                                                            <div class="progress" style="height: 20px;">
                                                                                <div class="progress-bar progress-bar-striped" 
                                                                                     role="progressbar" 
                                                                                     data-fert-id="{{ $fertDetail['fertilizer_id'] }}"
                                                                                     style="width: 0%; background-color: #ddd; color: black;">
                                                                                    0%
                                                                                </div>
                                                                            </div>
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

                                    <!-- Fertilizer Summary Boxes -->
                                    <div class="row mb-4">
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box" style="background-color: white; border: 1px solid #ddd; color: black;">
                                                <span class="info-box-icon" style="background-color: white; color: black;"><i class="fas fa-weight-hanging"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text" style="color: black;">{{ __('Total Fertilizer') }}</span>
                                                    <span class="info-box-number" style="color: black;" id="totalFertilizerDisplay">0</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box" style="background-color: white; border: 1px solid #ddd; color: black;">
                                                <span class="info-box-icon" style="background-color: white; color: black;"><i class="fas fa-pagelines"></i></span>
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
                                                    <span class="info-box-number" style="color: black;" id="totalRemainingDisplay">0</span>
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
                                                <h6><i class="fas fa-info-circle mr-2"></i>{{ __('Instructions') }}</h6>
                                                <p class="mb-0">
                                                    {{ __('Expand quarters to see months, then expand months to plan fertilizer distribution for each week. You can distribute each fertilizer type separately for each week. The system will track remaining quantities and prevent over-allocation.') }}
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
                                                {{ __('Fertilizer Distribution Summary') }}
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
                                                            <th style="color: black;">{{ __('Fertilizer') }}</th>
                                                            <th style="color: black;">{{ __('Planned Quantity') }}</th>
                                                            <th style="color: black;">{{ __('Status') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="summaryTable">
                                                        <tr>
                                                            <td colspan="7" class="text-center py-4" style="color: black;">
                                                                <i class="fas fa-pagelines mr-2"></i>
                                                                {{ __('No distribution yet. Start by selecting quarters above.') }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="5" class="text-right" style="color: black; font-weight: bold;">{{ __('Total Planned') }}:</td>
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
                                                <i class="fas fa-check-circle mr-2"></i> {{ __('Update Fertilizer Distribution') }}
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
            partially_planned: "{{ __('Partially Planned') }}",
            fix_validation_errors: "{{ __('Please fix the validation errors before submitting.') }}",
            fix_validation_errors_save: "{{ __('Please fix validation errors before saving') }}",
            all_distributions_saved: "{{ __('All distributions saved successfully!') }}",
            negative_value: "{{ __('Negative value') }}",
            exceeds_total_quantity: "{{ __('Exceeds total quantity') }}",
            kg: "{{ __('kg') }}",
            expand: "{{ __('Expand') }}",
            collapse: "{{ __('Collapse') }}"
        };

        // Global variables
        let fertilizerDistributions = [];
        let selectedQuarters = new Set();
        let selectedMonths = new Set();
        const applicationYear = {{ $plan->year - 1 }};
        
        // Available fertilizers data
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
                        type_id: {{ $fertilizerType->id ?? 0 }},
                        type_name: "{{ $fertilizerType->name ?? __('N/A') }}",
                        unit: "{{ $fertDetail['unit'] }}",
                        total_quantity: {{ $fertDetail['amount'] }},
                        planned_quantity: 0,
                        remaining_quantity: {{ $fertDetail['amount'] }}
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

        // Helper function to calculate week range
        function getWeekRange(monthNum, weekNumber) {
            const month = EC_MONTHS[monthNum - 1];
            const start = 1 + (weekNumber - 1) * 7;
            const end = Math.min(start + 6, month.days);
            return `${start}-${end}`;
        }

        // Initialize form
        $(document).ready(function() {
            console.log('Initializing fertilizer plan for application year:', applicationYear);
            console.log('Available fertilizers:', AVAILABLE_FERTILIZERS);
            
            // Initialize fertilizer totals display
            updateFertilizerTotals();
            
            // Load existing distribution
            loadExistingDistribution();
            
            // Render planning container
            renderPlanningContainer();
            
            // Update summary
            updateSummary();
            
            // Form submission
            $('#fertilizerPlanForm').on('submit', function(e) {
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
            console.log('Loading existing distribution:', existing);
            
            fertilizerDistributions = [];
            
            if (existing.quarters && Array.isArray(existing.quarters)) {
                existing.quarters.forEach(quarter => {
                    const quarterNum = parseInt(quarter.quarter);
                    selectedQuarters.add(quarterNum);
                    
                    if (quarter.months && Array.isArray(quarter.months)) {
                        quarter.months.forEach(monthData => {
                            const monthNum = EC_MONTHS.findIndex(m => m.name === monthData.month) + 1;
                            if (monthNum > 0) selectedMonths.add(monthNum);
                            
                            if (monthData.weeks && Array.isArray(monthData.weeks)) {
                                monthData.weeks.forEach(weekData => {
                                    if (weekData.fertilizers && Array.isArray(weekData.fertilizers)) {
                                        weekData.fertilizers.forEach(fertData => {
                                            fertilizerDistributions.push({
                                                year: existing.year,
                                                quarter: `Q${quarterNum}`,
                                                month: monthData.month,
                                                week: weekData.week,
                                                fertilizer_id: fertData.fertilizer_id,
                                                fertilizer_name: fertData.name || AVAILABLE_FERTILIZERS[fertData.fertilizer_id]?.name || 'Unknown',
                                                planned_value: parseFloat(fertData.planned_value || 0),
                                                achieved_value: parseFloat(fertData.achieved_value || 0),
                                                unit: fertData.unit || AVAILABLE_FERTILIZERS[fertData.fertilizer_id]?.unit || 'kg'
                                            });
                                        });
                                    }
                                });
                            }
                        });
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
            // Reset planned quantities
            Object.keys(AVAILABLE_FERTILIZERS).forEach(fertId => {
                AVAILABLE_FERTILIZERS[fertId].planned_quantity = 0;
            });
            
            // Calculate planned quantities from distributions
            fertilizerDistributions.forEach(dist => {
                const fertId = dist.fertilizer_id;
                if (AVAILABLE_FERTILIZERS[fertId]) {
                    AVAILABLE_FERTILIZERS[fertId].planned_quantity += dist.planned_value;
                }
            });
            
            // Update remaining quantities and progress bars
            let totalAvailable = 0;
            let totalPlanned = 0;
            
            Object.keys(AVAILABLE_FERTILIZERS).forEach(fertId => {
                const fert = AVAILABLE_FERTILIZERS[fertId];
                fert.remaining_quantity = Math.max(0, fert.total_quantity - fert.planned_quantity);
                
                // Update display
                $(`.planned-display[data-fert-id="${fertId}"]`).text(`${fert.planned_quantity.toFixed(2)} ${fert.unit}`);
                $(`.remaining-display[data-fert-id="${fertId}"]`).text(`${fert.remaining_quantity.toFixed(2)} ${fert.unit}`);
                
                const progressPercentage = fert.total_quantity > 0 ? (fert.planned_quantity / fert.total_quantity * 100) : 0;
                $(`.progress-bar[data-fert-id="${fertId}"]`).css('width', `${progressPercentage}%`).text(`${progressPercentage.toFixed(1)}%`);
                
                totalAvailable += fert.total_quantity;
                totalPlanned += fert.planned_quantity;
            });
            
            const totalRemaining = Math.max(0, totalAvailable - totalPlanned);
            const overallProgress = totalAvailable > 0 ? (totalPlanned / totalAvailable * 100) : 0;
            
            // Update summary boxes
            $('#totalFertilizerDisplay').text(`${totalAvailable.toFixed(2)}`);
            $('#totalPlannedDisplay').text(`${totalPlanned.toFixed(2)}`);
            $('#totalRemainingDisplay').text(`${totalRemaining.toFixed(2)}`);
            $('#overallProgressDisplay').text(`${overallProgress.toFixed(1)}%`);
            $('#overallProgressBar').css('width', `${overallProgress}%`);
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
                const quarterMonths = EC_QUARTERS[q];
                
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
                                        Q${q} ({{ __('Year') }} ${applicationYear})
                                    </label>
                                </div>
                                <div class="ml-3">
                                    <span class="badge ${quarterPlanned > 0 ? 'badge-success' : 'badge-light'}" style="color: black; background-color: ${quarterPlanned > 0 ? '#d4edda' : 'white'}; border: 1px solid #ddd;">
                                        ${quarterPlanned.toFixed(1)} {{ __('kg') }}
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
                        <p class="text-muted mt-2">{{ __('Loading months...') }}</p>
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
                                        ${monthPlanned.toFixed(1)} {{ __('kg') }}
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
                                        <i class="fas fa-pagelines mr-1"></i>
                                        {{ __('Fertilizer Distribution for') }} ${month.name}
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
            
            // Calculate weeks for this month
            let weeks = [];
            let start = 1;
            while (start <= month.days) {
                let end = Math.min(start + 6, month.days);
                weeks.push({ start, end, weekNum: weeks.length + 1 });
                start += 7;
            }
            
            // Get existing distributions for this month and quarter
            const monthDistributions = fertilizerDistributions.filter(dist => 
                dist.month === month.name && dist.quarter === `Q${quarterId}`
            );
            
            // Render distribution inputs for each week
            let distributionHtml = '';
            
            weeks.forEach(week => {
                const weekRange = getWeekRange(monthNum, week.weekNum);
                
                distributionHtml += `
                    <div class="week-distribution-section mb-4 p-3" style="border: 1px solid #ddd; border-radius: 5px; background-color: white;">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge mr-2" style="color: black; background-color: white; border: 1px solid #ddd;">
                                {{ __('Week') }} ${week.weekNum}
                            </span>
                            <small class="text-muted">${weekRange}</small>
                        </div>
                        
                        <div class="fertilizer-inputs" id="fertilizerInputs${monthNum}_${week.weekNum}_${quarterId}">
                            <!-- Fertilizer inputs will be loaded here -->
                        </div>
                    </div>
                `;
            });
            
            $(`#distributionContainer${monthNum}_q${quarterId}`).html(distributionHtml);
            
            // Load fertilizer inputs for each week
            weeks.forEach(week => {
                loadFertilizerInputsForWeek(monthNum, week.weekNum, quarterId);
            });
            
            toggleBtn.html(`<i class="fas fa-times"></i>`);
        }

        function loadFertilizerInputsForWeek(monthNum, weekNum, quarterId) {
            const month = EC_MONTHS[monthNum - 1];
            const container = $(`#fertilizerInputs${monthNum}_${weekNum}_${quarterId}`);
            
            // Check if we have fertilizers available
            const fertilizerIds = Object.keys(AVAILABLE_FERTILIZERS);
            if (fertilizerIds.length === 0) {
                container.html(`
                    <div class="alert" style="background-color: white; border: 1px solid #ddd; color: black;">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ __('No fertilizers available for distribution.') }}
                    </div>
                `);
                return;
            }
            
            let fertilizerInputs = '';
            
            // Loop through all available fertilizers
            Object.values(AVAILABLE_FERTILIZERS).forEach(fert => {
                const existingDistribution = fertilizerDistributions.find(dist =>
                    dist.month === month.name && 
                    dist.quarter === `Q${quarterId}` &&
                    dist.week === weekNum &&
                    dist.fertilizer_id == fert.id
                );
                
                const plannedValue = existingDistribution ? existingDistribution.planned_value : 0;
                const remaining = getRemainingQuantity(fert.id);
                const percentage = fert.total_quantity > 0 ? (fert.planned_quantity / fert.total_quantity * 100) : 0;
                
                fertilizerInputs += `
                    <div class="fertilizer-input-group mb-3 p-3" style="background-color: #f8f9fa; border-radius: 5px; border-left: 3px solid #ddd;">
                        <div class="d-flex align-items-center">
                            <div class="mr-3" style="width: 150px;">
                                <strong class="d-block" style="color: black;">${fert.name}</strong>
                                <small class="text-muted d-block">${fert.type_name}</small>
                                <small class="text-muted">${fert.unit}</small>
                            </div>
                            <div class="flex-grow-1 mr-3">
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.01" min="0" max="${remaining + plannedValue}"
                                           class="form-control form-control-sm planned-input"
                                           data-month="${monthNum}"
                                           data-week="${weekNum}"
                                           data-quarter="${quarterId}"
                                           data-fert-id="${fert.id}"
                                           value="${plannedValue.toFixed(2)}"
                                           placeholder="0.00"
                                           style="color: black; background-color: white; border: 1px solid #ddd;"
                                           ${remaining === 0 && plannedValue === 0 ? 'disabled' : ''}>
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="color: black; background-color: white; border: 1px solid #ddd;">${fert.unit}</span>
                                    </div>
                                </div>
                                <div class="validation-feedback" style="font-size: 0.75rem; min-height: 18px; color: black;"></div>
                                <small class="text-muted">${remaining.toFixed(2)} {{ __('remaining') }}</small>
                            </div>
                            <div class="text-center" style="width: 80px;">
                                <span class="badge ${plannedValue > 0 ? 'badge-success' : 'badge-light'}" style="color: black; background-color: ${plannedValue > 0 ? '#d4edda' : 'white'}; border: 1px solid #ddd;">
                                    ${plannedValue > 0 ? translations.planned : translations.not_planned}
                                </span>
                                <div class="small mt-1" style="color: black;">${percentage.toFixed(1)}%</div>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 3px; background-color: #ddd;">
                            <div class="progress-bar ${plannedValue > 0 ? 'bg-success' : 'bg-secondary'}" 
                                 style="width: ${percentage}%; background-color: ${plannedValue > 0 ? '#28a745' : '#6c757d'} !important;"></div>
                        </div>
                    </div>
                `;
            });
            
            container.html(fertilizerInputs);
            
            // Add input validation
            $(`#fertilizerInputs${monthNum}_${weekNum}_${quarterId} .planned-input`).off('input').on('input', function() {
                validatePlannedInput(this);
            });
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
        function validatePlannedInput(input) {
            const value = parseFloat($(input).val()) || 0;
            const monthNum = parseInt($(input).data('month'));
            const weekNum = parseInt($(input).data('week'));
            const quarterId = parseInt($(input).data('quarter'));
            const fertId = parseInt($(input).data('fert-id'));
            const feedback = $(input).closest('.fertilizer-input-group').find('.validation-feedback');

            $(input).removeClass('is-valid is-invalid border-success border-danger');
            feedback.empty();

            if (value < 0) {
                $(input).addClass('is-invalid border-danger');
                feedback.html('<small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>' + translations.negative_value + '</small>');
                return false;
            }

            // Calculate remaining quantity excluding current input
            const month = EC_MONTHS[monthNum - 1];
            const plannedWithoutCurrent = fertilizerDistributions
                .filter(dist => dist.fertilizer_id == fertId &&
                    !(dist.month === month.name &&
                      dist.quarter === `Q${quarterId}` &&
                      dist.week === weekNum))
                .reduce((total, dist) => total + dist.planned_value, 0);

            const fert = AVAILABLE_FERTILIZERS[fertId];
            const maxAllowed = Math.max(0, fert.total_quantity - plannedWithoutCurrent);

            if (value > maxAllowed) {
                $(input).addClass('is-invalid border-danger');
                feedback.html(
                    `<small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>${translations.exceeds_total_quantity} (${maxAllowed.toFixed(2)} ${fert.unit})</small>`
                );
                return false;
            }

            if (value > 0) {
                $(input).addClass('is-valid border-success');
            }

            updateDistribution(input, value);
            return true;
        }

        function updateDistribution(input, value) {
            const monthNum = parseInt($(input).data('month'));
            const weekNum = parseInt($(input).data('week'));
            const quarterId = parseInt($(input).data('quarter'));
            const fertId = parseInt($(input).data('fert-id'));
            const month = EC_MONTHS[monthNum - 1];
            const quarter = `Q${quarterId}`;
            const fert = AVAILABLE_FERTILIZERS[fertId];
            
            // Find or create distribution
            let distribution = fertilizerDistributions.find(dist =>
                dist.month === month.name &&
                dist.quarter === quarter &&
                dist.week === weekNum &&
                dist.fertilizer_id == fertId
            );

            if (distribution) {
                distribution.planned_value = value;
            } else {
                distribution = {
                    year: applicationYear,
                    quarter: quarter,
                    month: month.name,
                    week: weekNum,
                    fertilizer_id: fertId,
                    fertilizer_name: fert.name,
                    planned_value: value,
                    achieved_value: 0,
                    unit: fert.unit
                };
                fertilizerDistributions.push(distribution);
            }

            // Remove if value is 0
            if (value === 0) {
                fertilizerDistributions = fertilizerDistributions.filter(dist =>
                    !(dist.month === month.name &&
                      dist.quarter === quarter &&
                      dist.week === weekNum &&
                      dist.fertilizer_id == fertId)
                );
            }

            // Update fertilizer totals
            updateFertilizerTotals();
            
            // Update UI
            updateWeekUI(monthNum, weekNum, quarterId, fertId, value);
            updateMonthSummary(monthNum);
            updateQuarterSummary(quarterId);
            updateSummary();
        }

        function updateWeekUI(monthNum, weekNum, quarterId, fertId, value) {
            const status = value > 0 ? translations.planned : translations.not_planned;
            const statusClass = value > 0 ? 'badge-success' : 'badge-secondary';
            const fert = AVAILABLE_FERTILIZERS[fertId];
            const percentage = fert.total_quantity > 0 ? (fert.planned_quantity / fert.total_quantity * 100) : 0;
            
            const fertInputGroup = $(`.planned-input[data-month="${monthNum}"][data-week="${weekNum}"][data-quarter="${quarterId}"][data-fert-id="${fertId}"]`)
                .closest('.fertilizer-input-group');
            
            // Update status badge
            fertInputGroup.find('.badge').removeClass('badge-success badge-light');
            fertInputGroup.find('.badge').addClass(statusClass).text(status);
            
            // Update remaining quantity
            const remaining = getRemainingQuantity(fertId);
            fertInputGroup.find('small.text-muted').text(`${remaining.toFixed(2)} {{ __('remaining') }}`);
            
            // Update percentage
            fertInputGroup.find('.small').text(`${percentage.toFixed(1)}%`);
            
            // Update progress bar
            const progressBar = fertInputGroup.find('.progress-bar');
            progressBar.removeClass('bg-secondary bg-success');
            progressBar.addClass(value > 0 ? 'bg-success' : 'bg-secondary');
            progressBar.css('width', percentage + '%');
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

        function getRemainingQuantity(fertId) {
            const fert = AVAILABLE_FERTILIZERS[fertId];
            if (!fert) return 0;
            return Math.max(0, fert.total_quantity - fert.planned_quantity);
        }

        function calculateQuarterData(quarter) {
            const quarterName = `Q${quarter}`;
            const quarterDistributions = fertilizerDistributions.filter(dist => dist.quarter === quarterName);
            
            return {
                planned: quarterDistributions.reduce((total, dist) => total + (dist.planned_value || 0), 0)
            };
        }

        function calculateMonthData(monthNum) {
            const monthName = EC_MONTHS[monthNum - 1].name;
            const monthDistributions = fertilizerDistributions.filter(dist => dist.month === monthName);
            
            return {
                planned: monthDistributions.reduce((total, dist) => total + (dist.planned_value || 0), 0)
            };
        }

        function calculateQuarterPercentage(planned) {
            const totalAvailable = Object.values(AVAILABLE_FERTILIZERS).reduce((sum, fert) => sum + fert.total_quantity, 0);
            return totalAvailable > 0 ? (planned / totalAvailable * 100) : 0;
        }

        function calculateMonthPercentage(planned) {
            const totalAvailable = Object.values(AVAILABLE_FERTILIZERS).reduce((sum, fert) => sum + fert.total_quantity, 0);
            return totalAvailable > 0 ? (planned / totalAvailable * 100) : 0;
        }

        function updateMonthSummary(monthNum) {
            const monthData = calculateMonthData(monthNum);
            const monthPlanned = monthData.planned;
            const monthPercentage = calculateMonthPercentage(monthPlanned);
            
            $(`[id^="month${monthNum}_q"]`).each(function() {
                const monthBadge = $(this).closest('.month-card').find('.badge-success, .badge-light');
                monthBadge.removeClass('badge-success badge-light');
                monthBadge.addClass(monthPlanned > 0 ? 'badge-success' : 'badge-light');
                monthBadge.text(monthPlanned.toFixed(1) + ' {{ __('kg') }}');
            });
        }

        function updateQuarterSummary(quarterNum) {
            const quarterData = calculateQuarterData(quarterNum);
            const quarterPlanned = quarterData.planned;
            const quarterPercentage = calculateQuarterPercentage(quarterPlanned);
            
            $(`#quarter${quarterNum}`).closest('.quarter-card').find('.badge-success, .badge-light').each(function() {
                $(this).removeClass('badge-success badge-light');
                $(this).addClass(quarterPlanned > 0 ? 'badge-success' : 'badge-light');
                $(this).text(quarterPlanned.toFixed(1) + ' {{ __('kg') }}');
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

            if (fertilizerDistributions.length === 0) {
                $("#summaryTable").append(`
                    <tr>
                        <td colspan="7" class="text-center py-4" style="color: black;">
                            <i class="fas fa-pagelines mr-2"></i>
                            {{ __('No distribution yet. Start by selecting quarters above.') }}
                        </td>
                    </tr>
                `);
            } else {
                fertilizerDistributions.forEach(dist => {
                    if (dist.planned_value > 0) {
                        totalPlanned += dist.planned_value;
                        const status = dist.planned_value > 0 ? translations.planned : translations.not_planned;
                        const statusClass = dist.planned_value > 0 ? 'badge-success' : 'badge-secondary';
                        const weekRange = getWeekRange(EC_MONTHS.findIndex(m => m.name === dist.month) + 1, dist.week);

                        $("#summaryTable").append(`
                            <tr>
                                <td style="color: black;">{{ __('Week') }} ${dist.week}</td>
                                <td style="color: black;">${dist.month}</td>
                                <td style="color: black;">${dist.quarter}</td>
                                <td style="color: black;">${weekRange}</td>
                                <td style="color: black;">${dist.fertilizer_name}</td>
                                <td class="font-weight-bold" style="color: black;">${dist.planned_value.toFixed(2)} ${dist.unit}</td>
                                <td><span class="badge ${statusClass}" style="color: black; background-color: ${dist.planned_value > 0 ? '#d4edda' : 'white'}; border: 1px solid #ddd;">${status}</span></td>
                            </tr>
                        `);
                    }
                });
            }

            const totalAvailable = Object.values(AVAILABLE_FERTILIZERS).reduce((sum, fert) => sum + fert.total_quantity, 0);
            const overallPercentage = totalAvailable > 0 ? (totalPlanned / totalAvailable * 100) : 0;
            $("#summaryTotal").text(totalPlanned.toFixed(2));
            $("#summaryCompletion").text(overallPercentage.toFixed(1) + "%");
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
            
            console.log('Saved fertilizer distribution data:', planData);
            $("#fertilizerDistributionDetails").val(JSON.stringify(planData));
            return planData;
        }

        function saveAllDistributions() {
            let hasErrors = false;

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

        function clearAllSelections() {
            selectedQuarters.clear();
            selectedMonths.clear();
            fertilizerDistributions = [];
            
            $('.quarter-checkbox').prop('checked', false);
            $('.month-checkbox').prop('checked', false);
            $('.planned-input').val('0.00');
            
            $('.quarter-card').removeClass('card-primary').addClass('card-secondary');
            $('.month-card').removeClass('border-primary');
            
            $('.quarter-months').collapse('hide');
            $('.month-distribution').collapse('hide');
            
            updateFertilizerTotals();
            updateSummary();
            
            showAlert('{{ __('All selections cleared') }}', 'info');
        }

        /* -------------------------------
           FORM VALIDATION
        ------------------------------- */
        function validateForm() {
            let isValid = true;
            const errors = [];

            $('.planned-input').each(function() {
                if ($(this).hasClass('is-invalid')) {
                    errors.push('{{ __('Some distributions have validation errors') }}');
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

        /* Week distribution sections */
        .week-distribution-section {
            transition: all 0.3s ease;
        }
        
        .week-distribution-section:hover {
            background-color: #f8f9fa;
        }

        /* Fertilizer input groups */
        .fertilizer-input-group {
            transition: all 0.3s ease;
        }
        
        .fertilizer-input-group:hover {
            background-color: #e9ecef;
            border-left-color: #333;
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
            
            .planned-input {
                font-size: 0.8rem !important;
            }
            
            .fertilizer-input-group .d-flex {
                flex-direction: column;
            }
            
            .fertilizer-input-group .d-flex > div {
                width: 100% !important;
                margin-bottom: 5px;
            }
        }

        /* Table styling */
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        /* Input validation states */
        .planned-input.is-valid {
            border-color: #28a745 !important;
        }

        .planned-input.is-invalid {
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

        /* Progress bars */
        .progress {
            border-radius: 2px;
            overflow: hidden;
            background-color: #ddd !important;
        }
        
        .progress-bar {
            border-radius: 2px;
            transition: width 0.3s ease;
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