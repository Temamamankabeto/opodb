<x-layout>
    <div class="content-header">
        

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
                                <h3 class="card-title"><i class="fas fa-pagelines mr-2"></i>{{ __('Fertilizer Distribution Plan') }}</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form action="{{ route('sector.fertilizer-plans.update', $plan->id) }}" method="POST"
                                id="fertilizerPlanForm">
                                @csrf
                                @method('PUT')

                                <div class="card-body">
                                    

                                    <!-- Additional Information Display -->
                                    <div class="row mb-4">
                                        <div class="col-md-4 col-sm-6">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-success"><i class="fas fa-globe-americas"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Area to Plant') }}</span>
                                                    <span class="info-box-number">{{ $plan->area_to_plant }} {{ __('ha') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4 col-sm-6">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-info"><i class="fas fa-chart-line"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Productivity') }}</span>
                                                    <span class="info-box-number">{{ $plan->productivity }} {{ __('qt/ha') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4 col-sm-6">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-warning"><i class="fas fa-weight-hanging"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Expected Yield') }}</span>
                                                    <span class="info-box-number">{{ $plan->expected_yield }} {{ __('qt') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Available Fertilizers Summary -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h5 class="text-info mb-3">
                                                <i class="fas fa-pagelines mr-1"></i>
                                                {{ __('Available Fertilizers Summary') }}
                                            </h5>
                                            <hr class="mt-0">
                                        </div>

                                        @php
                                            $fertilizerDetails = $plan->fertilizer_details ?? [];
                                        @endphp
                                        @if (count($fertilizerDetails) > 0)
                                            @foreach ($fertilizerDetails as $fertDetail)
                                                @php
                                                    $fertilizer = \App\Models\Fertilizer::find($fertDetail['fertilizer_id']);
                                                    $fertilizerType = $fertilizer->fertilizerType ?? null;
                                                @endphp
                                                @if ($fertilizer)
                                                    <div class="col-md-4 col-sm-6">
                                                        <div class="info-box bg-light">
                                                            <span class="info-box-icon bg-success"><i class="fas fa-pagelines"></i></span>
                                                            <div class="info-box-content">
                                                                <span class="info-box-text">{{ $fertilizer->name }}</span>
                                                                <span class="info-box-number">{{ $fertDetail['amount'] }} {{ $fertDetail['unit'] }}</span>
                                                                <span class="text-muted">{{ $fertilizerType->name ?? __('N/A') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            <div class="col-12">
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                                    {{ __('No fertilizers allocated for this crop plan.') }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Fertilizer Types Section -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h5 class="text-success mb-3">
                                                <i class="fas fa-pagelines mr-1"></i>
                                                {{ __('Select Fertilizer Types for Distribution') }}
                                            </h5>
                                            <hr class="mt-0">
                                        </div>
                                    </div>

                                    <div class="row mb-4" id="fertilizerTypesContainer">
                                        @foreach ($fertilizerTypes as $fertilizerType)
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <div class="card fertilizer-type-card h-100"
                                                    data-type-id="{{ $fertilizerType->id }}">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <div class="custom-control custom-checkbox mr-3">
                                                                <input class="custom-control-input fertilizer-type-checkbox" type="checkbox"
                                                                       id="fertilizerType{{ $fertilizerType->id }}"
                                                                       value="{{ $fertilizerType->id }}">
                                                                <label for="fertilizerType{{ $fertilizerType->id }}" class="custom-control-label font-weight-bold">
                                                                    {{ $fertilizerType->name }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        @if ($fertilizerType->fertilizers->count() > 0)
                                                            <div class="ml-auto">
                                                                <span class="badge badge-success">{{ $fertilizerType->fertilizers->count() }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="card-body">
                                                        @if ($fertilizerType->fertilizers->count() > 0)
                                                            <small class="text-muted">{{ __('Available in this type:') }}</small>
                                                            <div class="fertilizer-list mt-2">
                                                                @foreach ($fertilizerType->fertilizers as $fertilizer)
                                                                    @php
                                                                        $fertDetail = collect($plan->fertilizer_details ?? [])
                                                                            ->where('fertilizer_id', $fertilizer->id)
                                                                            ->first();
                                                                        $totalQuantity = $fertDetail['amount'] ?? 0;
                                                                    @endphp
                                                                    @if ($totalQuantity > 0)
                                                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                                                            <span class="text-dark">{{ $fertilizer->name }}</span>
                                                                            <span class="badge badge-light">{{ $totalQuantity }} {{ $fertilizer->unit ?? __('kg') }}</span>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Fertilizer Distribution Planning Section -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h5 class="text-info mb-3">
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                {{ __('Fertilizer Distribution Planning') }}
                                            </h5>
                                            <hr class="mt-0">
                                            <div class="callout callout-info mt-2">
                                                <h6><i class="fas fa-info-circle mr-2"></i>{{ __('Application Year Information') }}</h6>
                                                <p class="mb-0">{{ __('All fertilizer application activities occur in the year before planting') }} ({{ $plan->year - 1 }}).</p>
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
                                                {{ __('Fertilizer Distribution Summary') }}
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
                                                            <th>{{ __('Fertilizer') }}</th>
                                                            <th>{{ __('Quantity') }}</th>
                                                            <th>{{ __('Status') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="summaryTable">
                                                        <tr>
                                                            <td colspan="7" class="text-center text-muted py-4">
                                                                <i class="fas fa-pagelines mr-2"></i>
                                                                {{ __('No fertilizer distribution yet. Start by selecting fertilizer types and quarters above.') }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="5" class="text-right font-weight-bold">{{ __('Total Distributed') }}:</td>
                                                            <td class="font-weight-bold text-success" id="summaryTotal">0 {{ __('kg') }}</td>
                                                            <td class="font-weight-bold text-info" id="summaryCompletion">0%</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hidden field to store structured fertilizer distribution data -->
                                    <input type="hidden" name="fertilizer_distribution_details"
                                        id="fertilizerDistributionDetails"
                                        value="{{ json_encode($plan->fertilizer_distribution_details ?? []) }}">
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
                                                <i class="fas fa-save mr-2"></i> {{ __('Update Fertilizer Plan') }}
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
            all_quarters_selected: "{{ __('All quarters selected for application year') }}",
            all_quarters_cleared: "{{ __('All quarters cleared') }}",
            all_months_selected: "{{ __('All months in selected quarters selected for application year') }}",
            all_months_cleared: "{{ __('All months cleared') }}",
            no_months_available: "{{ __('No months available in selected quarters.') }}",
            negative_value: "{{ __('Negative value') }}",
            exceeds_total_quantity: "{{ __('Exceeds total quantity') }}",
            kg: "{{ __('kg') }}",
            ha: "{{ __('ha') }}",
            q: "{{ __('Q') }}",
            m: "{{ __('M') }}",
            w: "{{ __('W') }}",
            expand: "{{ __('Expand') }}",
            collapse: "{{ __('Collapse') }}",
            show_distribution: "{{ __('Show Distribution') }}",
            hide_distribution: "{{ __('Hide Distribution') }}"
        };

        let fertilizerTotals = {};
        let fertilizerUnits = {};
        let selectedFertilizerTypes = new Set();
        let selectedQuarters = new Set();
        let selectedMonths = new Set();
        let fertilizerDistributions = [];
        const applicationYear = {{ $plan->year - 1 }};
        const planningYear = {{ $plan->year }};

        // Extract fertilizer totals from fertilizer_details column
        @if ($plan->fertilizer_details && is_array($plan->fertilizer_details))
            @foreach ($plan->fertilizer_details as $fertDetail)
                fertilizerTotals[{{ $fertDetail['fertilizer_id'] }}] = {{ $fertDetail['amount'] }};
                fertilizerUnits[{{ $fertDetail['fertilizer_id'] }}] = "{{ $fertDetail['unit'] }}";
            @endforeach
        @endif

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

        // Available fertilizers data with totals from fertilizer_details
        const FERTILIZERS = {
            @foreach ($fertilizerTypes as $type)
                {{ $type->id }}: [
                    @foreach ($type->fertilizers as $fert)
                        @php
                            $fertDetail = collect($plan->fertilizer_details ?? [])
                                ->where('fertilizer_id', $fert->id)
                                ->first();
                            $totalQuantity = $fertDetail['amount'] ?? 0;
                        @endphp
                        @if ($totalQuantity > 0)
                            {
                                id: {{ $fert->id }},
                                name: "{{ $fert->name }}",
                                unit: "{{ $fertDetail['unit'] ?? __('kg') }}",
                                total_quantity: {{ $totalQuantity }},
                                type_id: {{ $type->id }},
                                type_name: "{{ $type->name }}"
                            },
                        @endif
                    @endforeach
                ],
            @endforeach
        };

        // Status calculation function
        function calculateStatus(quantity) {
            if (quantity === 0) return translations.not_planned;
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
            console.log('Document ready - initializing fertilizer distribution for application year:', applicationYear);
            console.log('Fertilizer totals loaded:', fertilizerTotals);
            console.log('Available fertilizers:', FERTILIZERS);

            // Fertilizer type selection
            $('.fertilizer-type-checkbox').on('change', function() {
                const typeId = parseInt($(this).val());
                const card = $(this).closest('.fertilizer-type-card');

                if ($(this).is(':checked')) {
                    selectedFertilizerTypes.add(typeId);
                    card.removeClass('card-secondary').addClass('card-success border-success');
                } else {
                    selectedFertilizerTypes.delete(typeId);
                    card.removeClass('card-success border-success').addClass('card-secondary');
                }

                renderPlanningContainer();
                updateSummaryTable();
            });

            // Form submission validation
            $('#fertilizerPlanForm').on('submit', function(e) {
                console.log('Form submitted, validating...');
                if (!validateForm()) {
                    e.preventDefault();
                    showAlert(translations.fix_validation_errors, 'error');
                } else {
                    console.log('Form validation passed, submitting...');
                    saveFertilizerJSON();
                }
            });

            // Load existing distribution FIRST to populate selected data
            loadExistingFertilizerDistribution();
            
            // Then render the planning container
            renderPlanningContainer();
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
                        distributed: monthData.distributed
                    };
                });
                
                const quarterDistributed = monthStats.reduce((sum, stat) => sum + stat.distributed, 0);
                const quarterPercentage = calculateQuarterPercentage(quarterNum);

                $("#planningContainer").append(`
                    <div class="card quarter-card mb-3 ${isSelected ? 'card-primary' : 'card-secondary'}">
                        <div class="card-header d-flex justify-content-between align-items-center" style="cursor: pointer;">
                            <div class="d-flex align-items-center">
                                <div class="custom-control custom-checkbox mr-3">
                                    <input class="custom-control-input quarter-checkbox" type="checkbox"
                                           id="quarter${q}" ${isSelected ? 'checked' : ''}>
                                    <label for="quarter${q}" class="custom-control-label font-weight-bold h5 mb-0">
                                        ${quarterName} ({{ __('Year') }} ${applicationYear})
                                    </label>
                                </div>
                                <div class="ml-3">
                                    <span class="badge ${quarterDistributed > 0 ? 'badge-success' : 'badge-light'}">
                                        ${quarterDistributed.toFixed(1)} {{ __('kg') }}
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
            
            updateSummaryTable();
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
                const monthDistributed = monthData.distributed;
                const monthPercentage = calculateMonthPercentage(monthNum);
                
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
                                    <span class="badge ${monthDistributed > 0 ? 'badge-success' : 'badge-light'}">
                                        ${monthDistributed.toFixed(1)} {{ __('kg') }}
                                    </span>
                                    <span class="badge badge-info ml-1">${monthPercentage}%</span>
                                    <button type="button" class="btn btn-sm btn-outline-secondary ml-2 toggle-distribution" 
                                            data-month="${monthNum}" data-quarter="${quarterId}">
                                        <i class="fas fa-pagelines"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="month-distribution collapse" id="distribution${monthNum}_q${quarterId}">
                                <div class="card-body p-3">
                                    <h6 class="text-muted mb-3">
                                        <i class="fas fa-pagelines mr-1"></i>
                                        {{ __('Fertilizer Distribution for') }} ${month.name}
                                    </h6>
                                    
                                    <div class="distribution-container" id="distributionContainer${monthNum}_q${quarterId}">
                                        <!-- Fertilizer distribution will be loaded here when month is expanded -->
                                    </div>
                                    
                                    <div class="month-summary mt-3 pt-3 border-top">
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted">{{ __('Month Total') }}:</small>
                                                <div class="font-weight-bold text-success" id="monthTotal${monthNum}_q${quarterId}">
                                                    ${monthDistributed.toFixed(2)} {{ __('kg') }}
                                                </div>
                                            </div>
                                            <div class="col-6 text-right">
                                                <small class="text-muted">{{ __('Distribution') }}:</small>
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
                    const distributionContainer = $(`#distribution${monthNum}_q${quarterId}`);
                    
                    if (distributionContainer.hasClass('show')) {
                        collapseMonthDistribution(monthNum, quarterId);
                    } else {
                        expandMonthDistribution(monthNum, quarterId);
                    }
                }
            });

            // Toggle distribution button click
            $(`.toggle-distribution[data-quarter="${quarterId}"]`).off('click').on('click', function(e) {
                e.stopPropagation();
                const monthNum = $(this).data('month');
                const quarterId = $(this).data('quarter');
                const distributionContainer = $(`#distribution${monthNum}_q${quarterId}`);
                
                if (distributionContainer.hasClass('show')) {
                    collapseMonthDistribution(monthNum, quarterId);
                } else {
                    expandMonthDistribution(monthNum, quarterId);
                }
            });

            // Expand months that are already selected
            quarterMonths.forEach(monthNum => {
                if (selectedMonths.has(monthNum)) {
                    setTimeout(() => {
                        expandMonthDistribution(monthNum, quarterId);
                    }, 200);
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
            
            // If distribution is already loaded, just show it
            if ($(`#distributionContainer${monthNum}_q${quarterId} .fertilizer-input-group`).length > 0) {
                distributionContainer.collapse('show');
                toggleBtn.html(`<i class="fas fa-times"></i>`);
                toggleBtn.removeClass('btn-outline-secondary').addClass('btn-outline-primary');
                return;
            }
            
            distributionContainer.collapse('show');
            
            // Calculate weeks for this month
            let weeks = [];
            let start = 1;
            while (start <= month.days) {
                let end = Math.min(start + 6, month.days);
                weeks.push({ start, end, weekNum: weeks.length + 1 });
                start += 7;
            }
            
            // Render fertilizer distribution for each week
            const distributionHtml = weeks.map(week => {
                return `
                    <div class="week-distribution-section mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge badge-light mr-2">
                                {{ __('Week') }} ${week.weekNum}
                            </span>
                            <small class="text-muted">${week.start}-${week.end}</small>
                        </div>
                        
                        <div class="fertilizer-inputs" id="fertilizerInputs${monthNum}_${week.weekNum}">
                            <!-- Fertilizer inputs for this week will be loaded here -->
                        </div>
                    </div>
                `;
            }).join('');
            
            $(`#distributionContainer${monthNum}_q${quarterId}`).html(distributionHtml);
            
            // Now load fertilizer inputs for each week
            weeks.forEach(week => {
                loadFertilizerInputsForWeek(monthNum, week.weekNum, quarterId);
            });
            
            toggleBtn.html(`<i class="fas fa-times"></i>`);
            toggleBtn.removeClass('btn-outline-secondary').addClass('btn-outline-primary');
        }

        function loadFertilizerInputsForWeek(monthNum, weekNumber, quarterId) {
            const month = EC_MONTHS[monthNum - 1];
            const container = $(`#fertilizerInputs${monthNum}_${weekNumber}`);
            
            // Check if we have selected fertilizer types
            if (selectedFertilizerTypes.size === 0) {
                container.html(`
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ __('No fertilizer types selected. Please select fertilizer types above.') }}
                    </div>
                `);
                return;
            }
            
            let fertilizerInputs = '';
            
            // Loop through selected fertilizer types
            Array.from(selectedFertilizerTypes).forEach(typeId => {
                const fertilizers = FERTILIZERS[typeId] || [];
                fertilizers.forEach(fert => {
                    const existingDistribution = fertilizerDistributions.find(dist =>
                        dist.month === month.name && 
                        dist.week === `{{ __('Week') }} ${weekNumber}` &&
                        dist.fertilizer_id == fert.id
                    );
                    
                    const quantity = existingDistribution ? existingDistribution.quantity : 0;
                    const distributed = getCurrentDistributedQuantity(fert.id);
                    const total = fert.total_quantity;
                    const percentage = total > 0 ? (distributed / total * 100) : 0;
                    const remaining = Math.max(0, total - distributed);
                    
                    fertilizerInputs += `
                        <div class="fertilizer-input-group mb-2" data-month="${monthNum}" data-week="${weekNumber}" data-fert-id="${fert.id}">
                            <div class="d-flex align-items-center">
                                <div class="mr-2" style="width: 120px;">
                                    <small class="font-weight-bold d-block">${fert.name}</small>
                                    <small class="text-muted d-block">${fert.unit}</small>
                                </div>
                                <div class="flex-grow-1 mr-2">
                                    <div class="input-group input-group-sm">
                                        <input type="number" step="0.01" min="0" max="${remaining + quantity}"
                                               class="form-control form-control-sm fertilizer-quantity"
                                               data-month="${monthNum}"
                                               data-week="${weekNumber}"
                                               data-fert-id="${fert.id}"
                                               value="${quantity.toFixed(2)}"
                                               placeholder="0.00"
                                               ${remaining === 0 && quantity === 0 ? 'disabled' : ''}>
                                        <div class="input-group-append">
                                            <span class="input-group-text">${fert.unit}</span>
                                        </div>
                                    </div>
                                    <div class="validation-feedback" style="font-size: 0.75rem; min-height: 18px;"></div>
                                </div>
                                <div class="text-center" style="width: 80px;">
                                    <span class="badge ${quantity > 0 ? 'badge-success' : 'badge-secondary'}">
                                        ${calculateStatus(quantity)}
                                    </span>
                                    <div class="text-info small">${percentage.toFixed(1)}%</div>
                                </div>
                            </div>
                            <div class="progress mt-1" style="height: 3px;">
                                <div class="progress-bar ${quantity > 0 ? 'bg-success' : 'bg-secondary'}" 
                                     style="width: ${percentage}%"></div>
                            </div>
                        </div>
                    `;
                });
            });
            
            container.html(fertilizerInputs);
            
            // Add input validation
            $(`#fertilizerInputs${monthNum}_${weekNumber} .fertilizer-quantity`).off('input').on('input', function() {
                validateFertilizerInput(this);
            });
        }

        function collapseMonthDistribution(monthNum, quarterId) {
            const distributionContainer = $(`#distribution${monthNum}_q${quarterId}`);
            const toggleBtn = $(`.toggle-distribution[data-month="${monthNum}"][data-quarter="${quarterId}"]`);
            
            distributionContainer.collapse('hide');
            toggleBtn.html(`<i class="fas fa-pagelines"></i>`);
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
        }

        function handleMonthChange(monthNum, isChecked) {
            if (isChecked) {
                selectedMonths.add(monthNum);
                $(`#month${monthNum}_q${findQuarterId(monthNum)}`).closest('.month-card').addClass('border-primary');
                
                // Find which quarter this month belongs to and expand distribution
                const quarterId = findQuarterId(monthNum);
                if (quarterId && $(`#quarter${quarterId}`).is(':checked')) {
                    expandMonthDistribution(monthNum, quarterId);
                }
            } else {
                selectedMonths.delete(monthNum);
                $(`#month${monthNum}_q${findQuarterId(monthNum)}`).closest('.month-card').removeClass('border-primary');
                
                // Collapse distribution for this month
                const quarterId = findQuarterId(monthNum);
                if (quarterId) {
                    collapseMonthDistribution(monthNum, quarterId);
                }
            }

            updateSummaryTable();
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
           FERTILIZER QUANTITY VALIDATION
        ------------------------------- */
        function getCurrentDistributedQuantity(fertId) {
            return fertilizerDistributions
                .filter(dist => dist.fertilizer_id == fertId)
                .reduce((total, dist) => total + dist.quantity, 0);
        }

        function getRemainingQuantity(fertId) {
            const total = fertilizerTotals[fertId] || 0;
            const distributed = getCurrentDistributedQuantity(fertId);
            return Math.max(0, total - distributed);
        }

        function getFertilizerTotal(fertId) {
            return fertilizerTotals[fertId] || 0;
        }

        function validateFertilizerInput(input) {
            const value = parseFloat($(input).val()) || 0;
            const fertId = parseInt($(input).data('fert-id'));
            const monthNum = parseInt($(input).data('month'));
            const weekNumber = parseInt($(input).data('week'));
            const feedback = $(input).closest('.fertilizer-input-group').find('.validation-feedback');

            $(input).removeClass('is-valid is-invalid border-success border-danger');
            feedback.empty();

            if (value < 0) {
                $(input).addClass('is-invalid border-danger');
                feedback.html('<small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>' + translations.negative_value + '</small>');
                return false;
            }

            // Calculate remaining quantity excluding current row
            const month = EC_MONTHS[monthNum - 1];
            const distributedWithoutCurrent = fertilizerDistributions
                .filter(dist => dist.fertilizer_id == fertId &&
                    !(dist.month === month.name &&
                        dist.week === `{{ __('Week') }} ${weekNumber}`))
                .reduce((total, dist) => total + dist.quantity, 0);

            const totalAvailable = getFertilizerTotal(fertId);
            const maxAllowed = Math.max(0, totalAvailable - distributedWithoutCurrent);

            if (value > maxAllowed) {
                $(input).addClass('is-invalid border-danger');
                const fertilizer = getFertilizerById(fertId);
                feedback.html(
                    `<small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>${translations.exceeds_total_quantity} (${maxAllowed.toFixed(2)} ${fertilizer.unit})</small>`
                );
                return false;
            }

            if (value > 0) {
                $(input).addClass('is-valid border-success');
            }

            // Update distribution
            updateFertilizerDistributionFromInput(input, value);
            return true;
        }

        function updateFertilizerDistributionFromInput(input, value) {
            const fertId = parseInt($(input).data('fert-id'));
            const monthNum = parseInt($(input).data('month'));
            const weekNumber = parseInt($(input).data('week'));
            const month = EC_MONTHS[monthNum - 1];
            const quarter = `Q${findQuarter(monthNum)}`;
            const fertilizer = getFertilizerById(fertId);
            
            // Find or create distribution
            let distribution = fertilizerDistributions.find(dist =>
                dist.month === month.name &&
                dist.week === `{{ __('Week') }} ${weekNumber}` &&
                dist.fertilizer_id == fertId
            );

            if (distribution) {
                distribution.quantity = value;
            } else {
                const weekData = getWeekData(monthNum, weekNumber);
                distribution = {
                    year: applicationYear,
                    quarter: quarter,
                    month: month.name,
                    week: `{{ __('Week') }} ${weekNumber}`,
                    range: weekData.range,
                    fertilizer_type_id: fertilizer.type_id,
                    fertilizer_type_name: fertilizer.type_name,
                    fertilizer_id: fertId,
                    fertilizer_name: fertilizer.name,
                    quantity: value,
                    unit: fertilizer.unit,
                    status: calculateStatus(value)
                };
                fertilizerDistributions.push(distribution);
            }

            // Remove if quantity is 0
            if (value === 0) {
                fertilizerDistributions = fertilizerDistributions.filter(dist =>
                    !(dist.month === month.name &&
                        dist.week === `{{ __('Week') }} ${weekNumber}` &&
                        dist.fertilizer_id == fertId)
                );
            }

            // Update month and quarter summaries
            updateMonthSummary(monthNum);
            updateQuarterSummary(findQuarter(monthNum));
            updateSummaryTable();
        }

        function getFertilizerById(fertId) {
            for (const typeId in FERTILIZERS) {
                const fertilizer = FERTILIZERS[typeId].find(f => f.id === fertId);
                if (fertilizer) return fertilizer;
            }
            return {
                name: '{{ __('Unknown') }}',
                unit: '{{ __('kg') }}',
                type_id: 0,
                type_name: '{{ __('Unknown Type') }}'
            };
        }

        /* -------------------------------
           SUMMARY CALCULATIONS
        ------------------------------- */
        function calculateQuarterData(quarter) {
            const quarterName = `Q${quarter}`;
            const quarterDistributions = fertilizerDistributions.filter(dist => dist.quarter === quarterName);
            
            return {
                distributed: quarterDistributions.reduce((total, dist) => total + dist.quantity, 0)
            };
        }

        function calculateMonthData(monthNum) {
            const monthName = EC_MONTHS[monthNum - 1].name;
            const monthDistributions = fertilizerDistributions.filter(dist => dist.month === monthName);
            
            return {
                distributed: monthDistributions.reduce((total, dist) => total + dist.quantity, 0)
            };
        }

        function calculateQuarterPercentage(quarterNum) {
            const quarterData = calculateQuarterData(quarterNum);
            const totalQuarterQuantity = Array.from(selectedFertilizerTypes).reduce((sum, typeId) => {
                const fertilizers = FERTILIZERS[typeId] || [];
                return sum + fertilizers.reduce((fertSum, fert) => fertSum + fert.total_quantity, 0);
            }, 0);
            
            return totalQuarterQuantity > 0 ? ((quarterData.distributed / totalQuarterQuantity) * 100).toFixed(1) : 0;
        }

        function calculateMonthPercentage(monthNum) {
            const monthData = calculateMonthData(monthNum);
            const totalMonthQuantity = Array.from(selectedFertilizerTypes).reduce((sum, typeId) => {
                const fertilizers = FERTILIZERS[typeId] || [];
                return sum + fertilizers.reduce((fertSum, fert) => fertSum + fert.total_quantity, 0);
            }, 0);
            
            return totalMonthQuantity > 0 ? ((monthData.distributed / totalMonthQuantity) * 100).toFixed(1) : 0;
        }

        function updateMonthSummary(monthNum) {
            const monthData = calculateMonthData(monthNum);
            const monthDistributed = monthData.distributed;
            const monthPercentage = calculateMonthPercentage(monthNum);
            
            // Find all instances of this month in different quarters
            $(`[id^="month${monthNum}_q"]`).each(function() {
                const quarterId = $(this).data('quarter');
                if (quarterId) {
                    $(`#monthTotal${monthNum}_q${quarterId}`).text(monthDistributed.toFixed(2) + ' {{ __('kg') }}');
                    $(`#monthPercentage${monthNum}_q${quarterId}`).text(monthPercentage + '%');
                    
                    // Update month badge
                    const monthBadge = $(this).closest('.month-card').find('.badge-success, .badge-light');
                    monthBadge.removeClass('badge-success badge-light');
                    monthBadge.addClass(monthDistributed > 0 ? 'badge-success' : 'badge-light');
                    monthBadge.text(monthDistributed.toFixed(1) + ' {{ __('kg') }}');
                }
            });
        }

        function updateQuarterSummary(quarterNum) {
            const quarterData = calculateQuarterData(quarterNum);
            const quarterDistributed = quarterData.distributed;
            const quarterPercentage = calculateQuarterPercentage(quarterNum);
            
            // Update quarter badge
            $(`#quarter${quarterNum}`).closest('.quarter-card').find('.badge-success, .badge-light').each(function() {
                $(this).removeClass('badge-success badge-light');
                $(this).addClass(quarterDistributed > 0 ? 'badge-success' : 'badge-light');
                $(this).text(quarterDistributed.toFixed(1) + ' {{ __('kg') }}');
            });
            
            // Update quarter percentage
            $(`#quarter${quarterNum}`).closest('.quarter-card').find('.badge-info').text(quarterPercentage + '%');
        }

        /* -------------------------------
           LOAD EXISTING DISTRIBUTION
        -------------------------------- */
        function loadExistingFertilizerDistribution() {
            let existing = {!! json_encode($plan->fertilizer_distribution_details ?? '[]') !!};

            console.log('Loading existing fertilizer distribution:', existing);

            if (existing && typeof existing === 'object') {
                if (Array.isArray(existing)) {
                    fertilizerDistributions = existing.map(row => ({
                        year: row.year || applicationYear,
                        quarter: row.quarter,
                        month: row.month,
                        week: row.week,
                        range: row.range || '',
                        fertilizer_type_id: row.fertilizer_type_id,
                        fertilizer_type_name: row.fertilizer_type_name,
                        fertilizer_id: row.fertilizer_id,
                        fertilizer_name: row.fertilizer_name,
                        quantity: parseFloat(row.quantity) || 0,
                        unit: row.unit || '{{ __('kg') }}',
                        status: row.status || calculateStatus(parseFloat(row.quantity) || 0)
                    }));
                } else if (existing.weekly_distribution) {
                    fertilizerDistributions = existing.weekly_distribution.map(row => ({
                        year: row.year || applicationYear,
                        quarter: row.quarter,
                        month: row.month,
                        week: row.week,
                        range: row.range || '',
                        fertilizer_type_id: row.fertilizer_type_id,
                        fertilizer_type_name: row.fertilizer_type_name,
                        fertilizer_id: row.fertilizer_id,
                        fertilizer_name: row.fertilizer_name,
                        quantity: parseFloat(row.quantity) || 0,
                        unit: row.unit || '{{ __('kg') }}',
                        status: row.status || calculateStatus(parseFloat(row.quantity) || 0)
                    }));
                }

                // Restore selections
                fertilizerDistributions.forEach(dist => {
                    const quarterNum = parseInt(dist.quarter.replace('Q', ''));
                    selectedQuarters.add(quarterNum);

                    const monthNum = EC_MONTHS.findIndex(m => m.name === dist.month) + 1;
                    if (monthNum > 0) selectedMonths.add(monthNum);

                    if (dist.fertilizer_type_id) {
                        selectedFertilizerTypes.add(parseInt(dist.fertilizer_type_id));
                    }
                });

                // Update UI
                $('.fertilizer-type-checkbox').each(function() {
                    const typeId = parseInt($(this).val());
                    if (selectedFertilizerTypes.has(typeId)) {
                        $(this).prop('checked', true);
                        $(this).closest('.fertilizer-type-card').removeClass('card-secondary').addClass('card-success border-success');
                    }
                });

                updateSummaryTable();
            }
        }

        /* -------------------------------
           UTILITY FUNCTIONS
        ------------------------------- */
        function findQuarter(monthId) {
            return Object.keys(EC_QUARTERS).find(q => EC_QUARTERS[q].includes(monthId));
        }

        function getWeekData(monthNum, weekNumber) {
            const month = EC_MONTHS[monthNum - 1];
            let start = 1 + (weekNumber - 1) * 7;
            let end = Math.min(start + 6, month.days);
            return { range: `${start}-${end}` };
        }

        /* -------------------------------
           SUMMARY TABLE
        ------------------------------- */
        function updateSummaryTable() {
            $("#summaryTable").empty();
            let totalDistributed = 0;

            if (fertilizerDistributions.length === 0) {
                $("#summaryTable").append(`
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-pagelines mr-2"></i>
                        {{ __('No fertilizer distribution yet. Start by selecting fertilizer types and quarters above.') }}
                    </td>
                </tr>
            `);
            } else {
                // Group by week for better organization
                const weekGroups = {};
                fertilizerDistributions.forEach(dist => {
                    const weekKey = `${dist.month}-${dist.week}`;
                    if (!weekGroups[weekKey]) {
                        weekGroups[weekKey] = {
                            month: dist.month,
                            week: dist.week,
                            quarter: dist.quarter,
                            range: dist.range,
                            fertilizers: [],
                            weekTotal: 0
                        };
                    }
                    weekGroups[weekKey].fertilizers.push(dist);
                    weekGroups[weekKey].weekTotal += dist.quantity;
                    totalDistributed += dist.quantity;
                });

                Object.values(weekGroups).forEach(weekGroup => {
                    weekGroup.fertilizers.forEach((dist, index) => {
                        const isFirst = index === 0;
                        $("#summaryTable").append(`
                        <tr>
                            ${isFirst ? `<td rowspan="${weekGroup.fertilizers.length}">${weekGroup.week}</td>` : ''}
                            ${isFirst ? `<td rowspan="${weekGroup.fertilizers.length}">${weekGroup.month}</td>` : ''}
                            ${isFirst ? `<td rowspan="${weekGroup.fertilizers.length}">${weekGroup.quarter}</td>` : ''}
                            ${isFirst ? `<td rowspan="${weekGroup.fertilizers.length}">${weekGroup.range}</td>` : ''}
                            <td>${dist.fertilizer_name}</td>
                            <td class="font-weight-bold text-success">${dist.quantity.toFixed(2)} ${dist.unit}</td>
                            <td><span class="badge ${getStatusClass(dist.status)}">${dist.status}</span></td>
                        </tr>
                    `);
                    });
                });
            }

            const overallCompletion = calculateOverallCompletion();
            $("#summaryTotal").text(totalDistributed.toFixed(2) + " {{ __('kg') }}");
            $("#summaryCompletion").text(overallCompletion + "%");
        }

        function calculateOverallCompletion() {
            const totalDistributed = fertilizerDistributions.reduce((total, dist) => total + dist.quantity, 0);
            const totalAvailable = Object.values(fertilizerTotals).reduce((sum, total) => sum + total, 0);
            return totalAvailable > 0 ? ((totalDistributed / totalAvailable) * 100).toFixed(1) : 0;
        }

        function saveAllDistributions() {
            let hasErrors = false;

            // Validate all inputs first
            $('.fertilizer-quantity').each(function() {
                if (!validateFertilizerInput(this)) hasErrors = true;
            });

            if (hasErrors) {
                showAlert(translations.fix_validation_errors_save, 'error');
                return;
            }

            saveFertilizerJSON();
            showAlert(translations.all_distributions_saved, 'success');
        }

        function saveFertilizerJSON() {
            const distributionData = {
                planning_year: planningYear,
                application_year: applicationYear,
                weekly_distribution: fertilizerDistributions,
                total_distributed: fertilizerDistributions.reduce((total, dist) => total + dist.quantity, 0),
                overall_completion: calculateOverallCompletion(),
                updated_at: new Date().toISOString()
            };

            $("#fertilizerDistributionDetails").val(JSON.stringify(distributionData));
            console.log('Saved fertilizer_distribution_details for application year ' + applicationYear + ':', distributionData);
        }

        /* -------------------------------
           FORM VALIDATION & ALERTS
        ------------------------------- */
        function validateForm() {
            let isValid = true;
            const errors = [];

            // Check if fertilizer types are selected
            if (selectedFertilizerTypes.size === 0) {
                errors.push('{{ __('Please select at least one fertilizer type') }}');
                isValid = false;
            }

            // Check for individual input errors
            $('.fertilizer-quantity').each(function() {
                if ($(this).hasClass('is-invalid')) {
                    errors.push('{{ __('Some fertilizer distributions have validation errors') }}');
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
        /* Expandable Cards */
        .quarter-card, .month-card, .fertilizer-type-card {
            transition: all 0.3s ease;
            border-width: 2px;
        }
        
        .quarter-card:hover, .month-card:hover, .fertilizer-type-card:hover {
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
        
        .fertilizer-type-card.card-success {
            border-color: #28a745 !important;
        }
        
        .fertilizer-type-card.card-success .card-header {
            background-color: rgba(40, 167, 69, 0.1);
            border-bottom: 2px solid #28a745;
        }

        /* Fertilizer Input Groups */
        .fertilizer-input-group {
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 3px solid #6c757d;
            transition: all 0.3s ease;
        }
        
        .fertilizer-input-group:hover {
            background-color: #e9ecef;
            border-left-color: #28a745;
        }
        
        .week-distribution-section {
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 15px;
            background-color: #fff;
        }

        /* Form Control Validation */
        .fertilizer-quantity.is-valid {
            border-color: #28a745 !important;
        }
        
        .fertilizer-quantity.is-invalid {
            border-color: #dc3545 !important;
        }

        /* Collapse Animation */
        .collapse {
            transition: all 0.3s ease;
        }

        /* Responsive Adjustments */
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
            
            .fertilizer-input-group .d-flex {
                flex-direction: column;
            }
            
            .fertilizer-input-group .d-flex > div {
                width: 100% !important;
                margin-bottom: 5px;
            }
            
            .fertilizer-input-group .text-center {
                text-align: left !important;
            }
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
        .month-card .card-header:hover,
        .fertilizer-type-card .card-header:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        .quarter-card.card-primary .card-header:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }

        /* Card selection styles */
        .card-selected {
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25) !important;
        }

        .fertilizer-type-card.card-selected {
            box-shadow: 0 0 0 3px rgba(102, 187, 106, 0.25) !important;
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

        /* Custom checkbox styling */
        .custom-control-input:checked ~ .custom-control-label::before {
            border-color: #007bff;
            background-color: #007bff;
        }

        .fertilizer-type-card .custom-control-input:checked ~ .custom-control-label::before {
            border-color: #28a745;
            background-color: #28a745;
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

        /* Quantity info styling */
        .quantity-info small {
            display: block;
            line-height: 1.2;
            font-size: 0.75rem;
        }

        /* Table styling */
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }
    </style>
</x-layout>