 
<x-layout> 
    <div class="content-header">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                
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
                                <h3 class="card-title"><i class="fas fa-calendar-edit mr-2"></i>{{ __('Land Preparation Planning') }}</h3>
                            </div>
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
                                    <!-- Basic Information Display -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h5 class="text-primary mb-3">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                {{ __('Crop Plan Information') }}
                                            </h5>
                                            <hr class="mt-0">
                                        </div>
                                        
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-primary"><i class="fas fa-seedling"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Crop Type') }}</span>
                                                    <span class="info-box-number">{{ $plan->cropType->name ?? __('N/A') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-success"><i class="fas fa-leaf"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Crop') }}</span>
                                                    <span class="info-box-number">{{ $plan->crop->name ?? __('N/A') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2 col-sm-4">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-info"><i class="fas fa-calendar-alt"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Year') }}</span>
                                                    <span class="info-box-number">{{ $plan->year }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2 col-sm-4">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-warning"><i class="fas fa-sun"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Season') }}</span>
                                                    <span class="info-box-number">{{ $plan->season }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2 col-sm-4">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-purple"><i class="fas fa-globe-americas"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Area') }}</span>
                                                    <span class="info-box-number">{{ $plan->area_to_plant }} {{ __('ha') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Planning Summary -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h5 class="text-info mb-3">
                                                <i class="fas fa-chart-bar mr-1"></i>
                                                {{ __('Planning Overview') }} ({{ __('Preparation Year') }}: <span id="preparationYear">{{ $plan->year - 1 }}</span>)
                                            </h5>
                                            <hr class="mt-0">
                                        </div>

                                        <!-- Summary Cards -->
                                        <div class="col-md-4">
                                            <div class="info-box shadow">
                                                <span class="info-box-icon bg-primary"><i class="fas fa-bullseye"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Total Area') }}</span>
                                                    <span class="info-box-number" id="totalAreaDisplay">{{ $plan->area_to_plant }} {{ __('ha') }}</span>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-primary" style="width: 100%"></div>
                                                    </div>
                                                    <span class="progress-description">
                                                        100% {{ __('Target') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="info-box shadow">
                                                <span class="info-box-icon bg-warning"><i class="fas fa-calendar-check"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Planned Area') }}</span>
                                                    <span class="info-box-number" id="plannedAreaSummary">0 {{ __('ha') }}</span>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-warning" id="plannedProgress" style="width: 0%"></div>
                                                    </div>
                                                    <span class="progress-description">
                                                        <span id="completionPercentage">0%</span> {{ __('Allocation') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="info-box shadow">
                                                <span class="info-box-icon bg-info"><i class="fas fa-chart-pie"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Distribution') }}</span>
                                                    <span class="info-box-number">
                                                        <span id="selectedQuartersBadge">0</span>{{ __('Q') }} / <span id="selectedMonthsBadge">0</span>{{ __('M') }} / <span id="selectedWeeksBadge">0</span>{{ __('W') }}
                                                    </span>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-info" style="width: 100%"></div>
                                                    </div>
                                                    <span class="progress-description">
                                                        {{ __('Quarters') }} / {{ __('Months') }} / {{ __('Weeks') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Land Preparation Planning Section -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h5 class="text-info mb-3">
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                {{ __('Land Preparation Planning') }}
                                            </h5>
                                            <hr class="mt-0">
                                            <p class="text-muted">
                                                {{ __('Plan land preparation activities for each week in the preparation year') }} ({{ $plan->year - 1 }}).
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Quarters Selection -->
                                    <div class="card card-outline card-primary mb-3">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <i class="fas fa-calendar-range mr-2"></i>
                                                {{ __('Select Quarters') }} ({{ $plan->year - 1 }})
                                            </h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" onclick="selectAllQuarters()" title="{{ __('Select All') }}">
                                                    <i class="fas fa-check-square"></i>
                                                </button>
                                                <button type="button" class="btn btn-tool" onclick="clearAllQuarters()" title="{{ __('Clear All') }}">
                                                    <i class="fas fa-square"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" id="quartersContainer">
                                                <!-- Quarters will be populated here -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Months Section (Initially Hidden) -->
                                    <div class="card card-outline card-info mb-3" id="monthsSection" style="display: none;">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <i class="fas fa-calendar-month mr-2"></i>
                                                {{ __('Select Months') }} ({{ $plan->year - 1 }})
                                            </h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" onclick="selectAllMonths()" title="{{ __('Select All') }}">
                                                    <i class="fas fa-check-square"></i>
                                                </button>
                                                <button type="button" class="btn btn-tool" onclick="clearAllMonths()" title="{{ __('Clear All') }}">
                                                    <i class="fas fa-square"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-sm">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th width="50">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input class="custom-control-input" type="checkbox" id="selectAllMonthsCheckbox">
                                                                    <label for="selectAllMonthsCheckbox" class="custom-control-label"></label>
                                                                </div>
                                                            </th>
                                                            <th>{{ __('Month') }}</th>
                                                            <th>{{ __('Quarter') }}</th>
                                                            <th>{{ __('Year') }}</th>
                                                            <th>{{ __('Planned Area') }}</th>
                                                            <th>{{ __('Status') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="monthsTable">
                                                        <!-- Months will be populated here -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Weeks Section (Initially Hidden) -->
                                    <div class="card card-outline card-warning mb-3" id="weeksSection" style="display: none;">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <i class="fas fa-calendar-week mr-2"></i>
                                                {{ __('Plan Distribution') }} ({{ $plan->year - 1 }})
                                            </h3>
                                            <div class="card-tools">
                                                <span class="badge badge-primary" id="totalDistributedBadge">0 {{ __('ha') }}</span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th>{{ __('Week') }}</th>
                                                            <th>{{ __('Month') }}</th>
                                                            <th>{{ __('Quarter') }}</th>
                                                            <th>{{ __('Date Range') }}</th>
                                                            <th>{{ __('Year') }}</th>
                                                            <th width="150">{{ __('Planned Area') }} ({{ __('ha') }})</th>
                                                            <th width="80">{{ __('Status') }}</th>
                                                            <th width="50">% {{ __('of Total') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="weeksTable">
                                                        <!-- Weeks will be populated here -->
                                                    </tbody>
                                                    <tfoot class="bg-light">
                                                        <tr>
                                                            <td colspan="5" class="text-right font-weight-bold">{{ __('Total Planned') }}:</td>
                                                            <td class="font-weight-bold text-primary" id="weeksTotalPlanned">0 {{ __('ha') }}</td>
                                                            <td colspan="2" class="font-weight-bold text-info" id="weeksTotalPercentage">0%</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <div class="callout callout-info mt-3">
                                                <div class="row align-items-center">
                                                    <div class="col-md-8">
                                                        <h6><i class="fas fa-info-circle mr-2"></i>{{ __('Instructions') }}</h6>
                                                        <p class="mb-0">{{ __('Enter planned area for each week in the input column. The system will automatically calculate the percentage of total area.') }}</p>
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <button type="button" class="btn btn-success" onclick="saveAllDistributions()">
                                                            <i class="fas fa-save mr-2"></i> {{ __('Save All Distributions') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Summary Section -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <i class="fas fa-list-alt mr-2"></i>
                                                {{ __('Distribution Summary') }} ({{ __('Preparation Year') }}: {{ $plan->year - 1 }})
                                            </h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>{{ __('Year') }}</th>
                                                            <th>{{ __('Quarter') }}</th>
                                                            <th>{{ __('Month') }}</th>
                                                            <th>{{ __('Week') }}</th>
                                                            <th>{{ __('Date Range') }}</th>
                                                            <th>{{ __('Planned') }} ({{ __('ha') }})</th>
                                                            <th>{{ __('Status') }}</th>
                                                            <th>{{ __('Action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="summaryTable">
                                                        <tr>
                                                            <td colspan="8" class="text-center text-muted py-4">
                                                                <i class="fas fa-calendar-alt mr-2"></i>
                                                                {{ __('No distribution yet. Start by selecting quarters above.') }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot class="bg-gray">
                                                        <tr>
                                                            <td colspan="5" class="text-right font-weight-bold">{{ __('Total') }}:</td>
                                                            <td class="font-weight-bold text-primary" id="summaryTotal">0 {{ __('ha') }}</td>
                                                            <td colspan="2" class="font-weight-bold text-info" id="summaryCompletion">0%</td>
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
            w: "{{ __('W') }}"
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

            // Update the preparation year display
            $('#preparationYear').text(preparationYear);

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

            renderQuarters();
            loadExistingDistribution();
            updateSummaryCards();
        });

        /* -------------------------------
           QUARTERS MANAGEMENT
        ------------------------------- */
        function renderQuarters() {
            $("#quartersContainer").empty();

            Object.keys(EC_QUARTERS).forEach(q => {
                const quarterData = calculateQuarterData(parseInt(q));
                const isSelected = selectedQuarters.has(parseInt(q));
                const quarterName = `Q${q}`;
                const monthNames = EC_QUARTERS[q].map(m => EC_MONTHS[m-1].name).join(", ");

                $("#quartersContainer").append(`
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card card-outline ${isSelected ? 'card-primary card-selected' : 'card-secondary'} h-100 quarter-card" data-quarter="${q}">
                        <div class="card-header">
                            <h3 class="card-title">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input quarter-checkbox" type="checkbox"
                                           id="quarter${q}" ${isSelected ? 'checked' : ''}>
                                    <label for="quarter${q}" class="custom-control-label font-weight-bold">
                                        ${quarterName}
                                    </label>
                                </div>
                            </h3>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-2">
                                <small>${monthNames}</small>
                            </p>
                            <div class="row">
                                <div class="col-12">
                                    <small class="text-primary">
                                        <i class="fas fa-seedling mr-1"></i>{{ __('Planned') }}: ${quarterData.planned.toFixed(2)} {{ __('ha') }}
                                    </small>
                                </div>
                                <div class="col-12">
                                    <small class="text-info">
                                        <i class="fas fa-calendar mr-1"></i>{{ __('Year') }}: ${preparationYear}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `);

                // Add event listener to quarter card
                $(`#quarter${q}`).on('change', function() {
                    handleQuarterChange(parseInt(q), $(this).is(':checked'));
                });
            });

            // Add click event to whole quarter card
            $('.quarter-card').click(function(e) {
                if (!$(e.target).is('input') && !$(e.target).is('label')) {
                    const checkbox = $(this).find('.quarter-checkbox');
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    checkbox.trigger('change');
                }
            });
        }

        function handleQuarterChange(quarterNum, isChecked) {
            if (isChecked) {
                selectedQuarters.add(quarterNum);
                $(`.quarter-card[data-quarter="${quarterNum}"]`).removeClass('card-secondary').addClass('card-primary card-selected');
            } else {
                selectedQuarters.delete(quarterNum);
                $(`.quarter-card[data-quarter="${quarterNum}"]`).removeClass('card-primary card-selected').addClass('card-secondary');
                // Remove months from this quarter from selected months
                const quarterMonths = EC_QUARTERS[quarterNum];
                quarterMonths.forEach(month => {
                    selectedMonths.delete(month);
                });
            }

            renderMonthsTable();
            updateBadges();
        }

        function calculateQuarterData(quarter) {
            const quarterName = `Q${quarter}`;
            const quarterDistributions = weeklyDistributions.filter(dist => dist.quarter === quarterName);
            
            return {
                planned: quarterDistributions.reduce((total, dist) => total + (dist.planned_area || 0), 0)
            };
        }

        function selectAllQuarters() {
            Object.keys(EC_QUARTERS).forEach(q => {
                if (!selectedQuarters.has(parseInt(q))) {
                    selectedQuarters.add(parseInt(q));
                }
            });
            renderQuarters();
            renderMonthsTable();
            updateBadges();
            showAlert(translations.all_quarters_selected + ' ' + preparationYear, 'success');
        }

        function clearAllQuarters() {
            selectedQuarters.clear();
            selectedMonths.clear();
            renderQuarters();
            $('#monthsSection').hide();
            $('#weeksSection').hide();
            updateBadges();
            showAlert(translations.all_quarters_cleared, 'info');
        }

        /* -------------------------------
           MONTHS TABLE MANAGEMENT
        ------------------------------- */
        function renderMonthsTable() {
            $("#monthsTable").empty();

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

            if (allMonths.length === 0) {
                $("#monthsTable").append(`
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            ${translations.no_months_available}
                        </td>
                    </tr>
                `);
                return;
            }

            allMonths.forEach(monthNum => {
                const month = EC_MONTHS[monthNum - 1];
                const monthData = calculateMonthData(monthNum);
                const isSelected = selectedMonths.has(monthNum);
                const quarterNum = findQuarter(monthNum);
                const status = monthData.planned > 0 ? translations.partially_planned : translations.not_planned;
                const statusClass = monthData.planned > 0 ? 'badge-warning' : 'badge-secondary';

                $("#monthsTable").append(`
                    <tr class="${isSelected ? 'table-info' : ''} month-row" data-month="${monthNum}">
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input month-checkbox" type="checkbox"
                                       id="month${monthNum}" ${isSelected ? 'checked' : ''}>
                                <label for="month${monthNum}" class="custom-control-label"></label>
                            </div>
                        </td>
                        <td>
                            <strong>${month.name}</strong>
                        </td>
                        <td>
                            <span class="badge badge-primary">Q${quarterNum}</span>
                        </td>
                        <td>${preparationYear}</td>
                        <td>
                            <span class="text-primary font-weight-bold">${monthData.planned.toFixed(2)} {{ __('ha') }}</span>
                        </td>
                        <td>
                            <span class="badge ${statusClass}">${status}</span>
                        </td>
                    </tr>
                `);

                // Add event listener to month checkbox
                $(`#month${monthNum}`).on('change', function() {
                    handleMonthChange(monthNum, $(this).is(':checked'));
                });
            });

            // Add select all functionality
            $('#selectAllMonthsCheckbox').off('change').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('.month-checkbox').prop('checked', isChecked).trigger('change');
            });

            // Add click event to month rows
            $('.month-row').click(function(e) {
                if (!$(e.target).is('input') && !$(e.target).is('label')) {
                    const checkbox = $(this).find('.month-checkbox');
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    checkbox.trigger('change');
                }
            });

            // Update select all checkbox state
            updateSelectAllMonthsCheckbox();
        }

        function updateSelectAllMonthsCheckbox() {
            const totalMonths = $('.month-checkbox').length;
            const checkedMonths = $('.month-checkbox:checked').length;
            const selectAllCheckbox = $('#selectAllMonthsCheckbox');
            
            selectAllCheckbox.prop('checked', totalMonths > 0 && totalMonths === checkedMonths);
            selectAllCheckbox.prop('indeterminate', checkedMonths > 0 && checkedMonths < totalMonths);
        }

        function handleMonthChange(monthNum, isChecked) {
            if (isChecked) {
                selectedMonths.add(monthNum);
                $(`.month-row[data-month="${monthNum}"]`).addClass('table-info');
            } else {
                selectedMonths.delete(monthNum);
                $(`.month-row[data-month="${monthNum}"]`).removeClass('table-info');
            }

            updateSelectAllMonthsCheckbox();
            renderWeeksTable();
            updateBadges();
        }

        function calculateMonthData(monthNum) {
            const monthName = EC_MONTHS[monthNum - 1].name;
            const monthDistributions = weeklyDistributions.filter(dist => dist.month === monthName);
            
            return {
                planned: monthDistributions.reduce((total, dist) => total + (dist.planned_area || 0), 0)
            };
        }

        function selectAllMonths() {
            if (selectedQuarters.size === 0) {
                showAlert(translations.select_quarters_first, 'warning');
                return;
            }

            // Get all months from selected quarters
            let allMonths = [];
            selectedQuarters.forEach(quarter => {
                allMonths = allMonths.concat(EC_QUARTERS[quarter]);
            });

            allMonths.forEach(month => {
                if (!selectedMonths.has(month)) {
                    selectedMonths.add(month);
                }
            });

            renderMonthsTable();
            renderWeeksTable();
            updateBadges();
            showAlert(translations.all_months_selected + ' ' + preparationYear, 'success');
        }

        function clearAllMonths() {
            selectedMonths.clear();
            renderMonthsTable();
            $('#weeksSection').hide();
            updateBadges();
            showAlert(translations.all_months_cleared, 'info');
        }

        /* -------------------------------
           WEEKS TABLE MANAGEMENT
        ------------------------------- */
        function renderWeeksTable() {
            $("#weeksTable").empty();

            if (selectedMonths.size === 0) {
                $("#weeksSection").hide();
                return;
            }

            $("#weeksSection").show();

            // Group weeks by month for better organization
            const monthsArray = Array.from(selectedMonths).sort((a, b) => a - b);
            let totalPlanned = 0;

            monthsArray.forEach(monthNum => {
                const month = EC_MONTHS[monthNum - 1];
                const quarterNum = findQuarter(monthNum);
                const monthData = calculateMonthData(monthNum);

                // Calculate weeks for this month
                let weeks = [];
                let start = 1;
                while (start <= month.days) {
                    let end = Math.min(start + 6, month.days);
                    weeks.push({ start, end, weekNum: weeks.length + 1 });
                    start += 7;
                }

                weeks.forEach(week => {
                    const existingDistribution = weeklyDistributions.find(dist =>
                        dist.month === month.name && dist.week === `{{ __('Week') }} ${week.weekNum}`
                    );
                    
                    const plannedArea = existingDistribution ? existingDistribution.planned_area : 0;
                    const status = calculateStatus(plannedArea);
                    const statusClass = getStatusClass(status);
                    const percentage = totalArea > 0 ? (plannedArea / totalArea * 100) : 0;
                    
                    totalPlanned += plannedArea;

                    $("#weeksTable").append(`
                        <tr class="week-row" data-month="${monthNum}" data-week="${week.weekNum}">
                            <td>
                                <strong>{{ __('Week') }} ${week.weekNum}</strong>
                            </td>
                            <td>${month.name}</td>
                            <td>
                                <span class="badge badge-primary">Q${quarterNum}</span>
                            </td>
                            <td>
                                <small class="text-muted">{{ __('Days') }} ${week.start}-${week.end}</small>
                            </td>
                            <td>${preparationYear}</td>
                            <td>
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
                                <div class="validation-feedback mt-1" style="font-size: 0.75rem;"></div>
                            </td>
                            <td>
                                <span class="badge ${statusClass}">${status}</span>
                            </td>
                            <td class="text-center">
                                <span class="text-info font-weight-bold">${percentage.toFixed(1)}%</span>
                            </td>
                        </tr>
                    `);
                });
            });

            // Add input event listeners
            enhanceWeekInputValidation();
            
            // Update footer totals
            const totalPercentage = totalArea > 0 ? (totalPlanned / totalArea * 100) : 0;
            $('#weeksTotalPlanned').text(totalPlanned.toFixed(2) + ' {{ __('ha') }}');
            $('#weeksTotalPercentage').text(totalPercentage.toFixed(1) + '%');
            
            updateTotalDistributedBadge();
        }

        /* -------------------------------
           INPUT VALIDATION
        ------------------------------- */
        function validatePlannedInput(input) {
            const value = parseFloat($(input).val()) || 0;
            const feedback = $(input).parent().siblings('.validation-feedback');

            $(input).removeClass('is-valid is-invalid');
            feedback.empty();

            if (value < 0) {
                $(input).addClass('is-invalid');
                feedback.html('<small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>' + translations.negative_value + '</small>');
                return false;
            }

            if (value > totalArea) {
                $(input).addClass('is-invalid');
                feedback.html(`<small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>${translations.exceeds_total_area} (${totalArea.toFixed(2)} {{ __('ha') }})</small>`);
                return false;
            }

            if (value > 0) {
                $(input).addClass('is-valid');
            }

            updateWeekDistribution(input);
            return true;
        }

        function updateWeekDistribution(input) {
            const monthNum = parseInt($(input).data('month'));
            const weekNumber = parseInt($(input).data('week'));
            const month = EC_MONTHS[monthNum - 1];
            const quarter = `Q${findQuarter(monthNum)}`;
            
            const plannedInput = $(`.planned-input[data-month="${monthNum}"][data-week="${weekNumber}"]`);
            const plannedArea = parseFloat(plannedInput.val()) || 0;
            
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
            updateSummaryTable();
            updateSummaryCards();
            updateTotalDistributedBadge();
            updateMonthTableData(monthNum);
        }

        function updateWeekUI(monthNum, weekNumber, plannedArea) {
            const status = calculateStatus(plannedArea);
            const statusClass = getStatusClass(status);
            const percentage = totalArea > 0 ? (plannedArea / totalArea * 100) : 0;
            
            const weekRow = $(`.week-row[data-month="${monthNum}"][data-week="${weekNumber}"]`);
            
            weekRow.find('.badge').removeClass('badge-secondary badge-success');
            weekRow.find('.badge').addClass(statusClass).text(status);
            
            weekRow.find('.text-info').text(percentage.toFixed(1) + '%');
        }

        function updateMonthTableData(monthNum) {
            const monthData = calculateMonthData(monthNum);
            const monthName = EC_MONTHS[monthNum - 1].name;
            
            const monthRow = $(`.month-row[data-month="${monthNum}"]`);
            monthRow.find('td:nth-child(5)').html(`<span class="text-primary font-weight-bold">${monthData.planned.toFixed(2)} {{ __('ha') }}</span>`);
            
            const status = monthData.planned > 0 ? translations.partially_planned : translations.not_planned;
            const statusClass = monthData.planned > 0 ? 'badge-warning' : 'badge-secondary';
            monthRow.find('td:nth-child(6)').html(`<span class="badge ${statusClass}">${status}</span>`);
        }

        function enhanceWeekInputValidation() {
            $('.planned-input').off('input').on('input', function() {
                validatePlannedInput(this);
                // Update weeks table footer
                updateWeeksTableFooter();
            });
        }

        function updateWeeksTableFooter() {
            let totalPlanned = 0;
            
            $('.planned-input').each(function() {
                const value = parseFloat($(this).val()) || 0;
                totalPlanned += value;
            });
            
            const totalPercentage = totalArea > 0 ? (totalPlanned / totalArea * 100) : 0;
            $('#weeksTotalPlanned').text(totalPlanned.toFixed(2) + ' {{ __('ha') }}');
            $('#weeksTotalPercentage').text(totalPercentage.toFixed(1) + '%');
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

        /* -------------------------------
           SUMMARY & DATA MANAGEMENT
        ------------------------------- */
        function updateSummaryTable() {
            $("#summaryTable").empty();
            let totalPlanned = 0;

            if (weeklyDistributions.length === 0) {
                $("#summaryTable").append(`
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
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
                        <td>${dist.year || preparationYear}</td>
                        <td>${dist.quarter}</td>
                        <td>${dist.month}</td>
                        <td>${dist.week}</td>
                        <td>${dist.range}</td>
                        <td class="font-weight-bold text-primary">${dist.planned_area.toFixed(2)} {{ __('ha') }}</td>
                        <td><span class="badge ${statusClass}">${dist.status}</span></td>
                        <td>
                            {{-- <button class="btn btn-sm btn-outline-danger" onclick="removeDistribution(${i})">
                                <i class="fas fa-trash"></i>
                            </button> --}}
                        </td>
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

        function removeDistribution(index) {
            weeklyDistributions.splice(index, 1);
            updateSummaryTable();
            saveJSON();
            updateSummaryCards();
            renderWeeksTable();
            showAlert(translations.distribution_removed, 'info');
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

                if (selectedQuarters.size > 0) {
                    $("#monthsSection").show();
                    renderMonthsTable();
                }

                if (selectedMonths.size > 0) {
                    $("#weeksSection").show();
                    renderWeeksTable();
                }
            }
        }

        /* -------------------------------
           UTILITY FUNCTIONS
        ------------------------------- */
        function updateTotalDistributedBadge() {
            const totalPlanned = weeklyDistributions.reduce((total, dist) => total + dist.planned_area, 0);
            $('#totalDistributedBadge').text(totalPlanned.toFixed(2) + ' {{ __('ha') }}');
        }

        function updateBadges() {
            $("#selectedQuartersBadge").text(selectedQuarters.size);
            $("#selectedMonthsBadge").text(selectedMonths.size);
            $("#selectedWeeksBadge").text(weeklyDistributions.length);
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
            const toast = $(`
                <div class="toast align-items-center text-white bg-${type === 'error' ? 'danger' : type === 'warning' ? 'warning' : 'success'} border-0 position-fixed bottom-0 end-0 m-3" role="alert" style="z-index: 9999;">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} mr-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" style="opacity: 1;"></button>
                    </div>
                </div>
            `);

            $('body').append(toast);
            
            // Simple show/hide instead of Bootstrap toast
            toast.fadeIn();
            setTimeout(() => {
                toast.fadeOut(400, function() {
                    $(this).remove();
                });
            }, 3000);
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
    .quarter-card, .month-row {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .quarter-card:hover:not(.card-selected) {
        border-color: #007bff !important;
    }

    .month-row:hover {
        background-color: rgba(0, 123, 255, 0.05) !important;
    }

    /* Custom checkbox styling */
    .custom-control-input:checked ~ .custom-control-label::before {
        border-color: #007bff;
        background-color: #007bff;
    }

    /* Week table row styling */
    .week-row input.form-control-sm {
        min-width: 100px;
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
        
        .week-row td {
            padding: 0.5rem !important;
        }
        
        .planned-input {
            font-size: 0.8rem !important;
        }
    }

    /* Table styling */
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .table-info {
        background-color: rgba(0, 123, 255, 0.15) !important;
    }

    /* Input validation states */
    .planned-input.is-valid {
        border-color: #28a745;
    }

    .planned-input.is-invalid {
        border-color: #dc3545;
    }

    /* Table Header Styles - WHITESMOKE with BLACK TEXT - ONLY FOR ACTUAL TABLE HEADERS */
    /* Target ONLY table headers inside table elements */
    table.table thead {
        background-color: whitesmoke !important;
    }
    
    table.table thead th,
    .table thead th {
        background-color: whitesmoke !important;
        border-color: #dee2e6 !important;
        font-weight: 600;
        color: #000000 !important; /* BLACK TEXT */
        padding: 12px 8px;
    }
    
    /* Specific table header styles - only for tables */
    table.table thead.thead-light {
        background-color: whitesmoke !important;
    }
    
    table.table thead.thead-light th,
    .table thead.thead-light th {
        background-color: whitesmoke !important;
        border-color: #dee2e6;
        color: #000000 !important; /* BLACK TEXT */
    }
    
    /* Keep .thead-dark as is for dark headers */
    table.table thead.thead-dark {
        background-color: #343a40 !important;
    }
    
    table.table thead.thead-dark th,
    .table thead.thead-dark th {
        background-color: #343a40 !important;
        border-color: #454d55;
        color: white !important; /* Keep white text for dark headers */
    }
    
    /* EXCLUDE card headers from table header styles */
    .card-header,
    .card-header h3,
    .card-header .card-title,
    .card-header .card-tools,
    .card-header .badge {
        /* Reset any inherited table styles */
        color: inherit !important;
        background-color: transparent !important;
        border-color: transparent !important;
    }
    
    /* Table footer styling */
    table.table tfoot.bg-gray,
    .table tfoot.bg-gray {
        background-color: #f8f9fa !important;
    }
    
    table.table tfoot.bg-light,
    .table tfoot.bg-light {
        background-color: #f8f9fa !important;
    }
    
    /* Zebra striping for better readability - ONLY for tables */
    table.table.table-striped tbody tr:nth-of-type(odd),
    .table.table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    table.table.table-striped tbody tr:nth-of-type(even),
    .table.table-striped tbody tr:nth-of-type(even) {
        background-color: white;
    }
    
    /* Selected row highlighting - ONLY for tables */
    table.table tbody tr.month-row.table-info,
    .table tbody tr.month-row.table-info {
        background-color: rgba(23, 162, 184, 0.15) !important;
    }
    
    /* Card header backgrounds - these are NOT tables */
    .card-header {
        background-color: rgba(0, 0, 0, 0.03);
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        color: #495057 !important; /* Default AdminLTE card header color */
    }
    
    /* Hover effects on table rows - ONLY for tables */
    table.table.table-hover tbody tr:hover,
    .table.table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
    }
    
    /* Ensure we're only targeting TABLE elements */
    table {
        /* Any table-specific styles go here */
    }
    
    /* Reset card titles to their original colors */
    .card-title {
        color: #495057 !important;
    }
    
    .card-warning .card-header .card-title {
        color: #1F2D3D !important; /* Dark color for warning card headers */
    }
    
    .card-primary .card-header .card-title {
        color: white !important; /* White text for primary card headers */
    }
    
    .card-info .card-header .card-title {
        color: white !important; /* White text for info card headers */
    }
    
    .card-outline .card-header .card-title {
        color: #495057 !important; /* Default dark color for outline cards */
    }
</style>
</x-layout>