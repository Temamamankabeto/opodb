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
                                <h3 class="card-title"><i class="fas fa-pagelines mr-2"></i>{{ __('Edit Fertilizer Distribution Plan') }}</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form action="{{ route('sector.fertilizer-plans.update', $plan->id) }}" method="POST"
                                id="fertilizerPlanForm">
                                @csrf
                                @method('PUT')

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
                                                    <span class="info-box-text">{{ __('Planning Year') }}</span>
                                                    <span class="info-box-number">{{ $plan->year }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2 col-sm-4">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-warning"><i class="fas fa-calendar-check"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Application Year') }}</span>
                                                    <span class="info-box-number">{{ $plan->year - 1 }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2 col-sm-4">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-purple"><i class="fas fa-sun"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Season') }}</span>
                                                    <span class="info-box-number">{{ $plan->season }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
                                                                <span class="text-muted">{{ $fertilizerType->name ?? 'N/A' }}</span>
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
                                            <p class="text-muted">
                                                {{ __('Select one or more fertilizer types to distribute. Quantities will be validated against available amounts.') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="row" id="fertilizerTypesContainer">
                                        @foreach ($fertilizerTypes as $fertilizerType)
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <div class="card card-outline card-secondary fertilizer-type-card h-100"
                                                    data-type-id="{{ $fertilizerType->id }}">
                                                    <div class="card-header">
                                                        <h3 class="card-title">
                                                            <div class="custom-control custom-checkbox">
                                                                <input class="custom-control-input fertilizer-type-checkbox" type="checkbox"
                                                                       id="fertilizerType{{ $fertilizerType->id }}"
                                                                       value="{{ $fertilizerType->id }}">
                                                                <label for="fertilizerType{{ $fertilizerType->id }}" class="custom-control-label font-weight-bold">
                                                                    {{ $fertilizerType->name }}
                                                                </label>
                                                            </div>
                                                        </h3>
                                                    </div>
                                                    <div class="card-body">
                                                        @if ($fertilizerType->fertilizers->count() > 0)
                                                            <div class="mt-2">
                                                                <small class="text-muted">{{ __('Available in this type:') }}</small>
                                                                <div class="fertilizer-list mt-1">
                                                                    @foreach ($fertilizerType->fertilizers as $fertilizer)
                                                                        @php
                                                                            $fertDetail = collect($plan->fertilizer_details ?? [])
                                                                                ->where('fertilizer_id', $fertilizer->id)
                                                                                ->first();
                                                                            $totalQuantity = $fertDetail['amount'] ?? 0;
                                                                        @endphp
                                                                        @if ($totalQuantity > 0)
                                                                            <span class="badge bg-light text-dark me-1 mb-1">
                                                                                {{ $fertilizer->name }} ({{ $totalQuantity }}
                                                                                {{ $fertilizer->unit ?? __('kg') }})
                                                                            </span>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
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
                                                {{ __('Fertilizer Distribution Planning') }} ({{ __('Application Year') }}: <span
                                                    id="applicationYear">{{ $plan->year - 1 }}</span>)
                                            </h5>
                                            <hr class="mt-0">
                                            <p class="text-muted">
                                                {{ __('Select multiple quarters, months, and distribute fertilizer quantities across weeks for application in the preparation year') }} ({{ $plan->year - 1 }}).
                                            </p>
                                            <div class="callout callout-info mt-2">
                                                <h6><i class="fas fa-info-circle mr-2"></i>{{ __('Application Year Information') }}</h6>
                                                <p class="mb-0">{{ __('All fertilizer application activities occur in the year before planting') }} ({{ $plan->year - 1 }}).</p>
                                            </div>
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
                                            <div class="row" id="monthsContainer">
                                                <!-- Months will be populated here -->
                                            </div>
                                        </div>
                                    </div>

                                  <!-- Weeks & Fertilizer Distribution Section -->
<div class="card card-outline card-warning mb-3" id="distributionSection" style="display: none;">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-calendar-week mr-2"></i>
            {{ __('Distribute Fertilizers') }} ({{ $plan->year - 1 }})
        </h3>
        <div class="card-tools">
            <span class="badge badge-primary" id="totalDistributedBadge">0 {{ __('kg') }}</span>
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
                        @foreach($fertilizerTypes as $type)
                            @if($type->fertilizers->count() > 0)
                                @foreach($type->fertilizers as $fert)
                                    @php
                                        $fertDetail = collect($plan->fertilizer_details ?? [])
                                            ->where('fertilizer_id', $fert->id)
                                            ->first();
                                        $totalQuantity = $fertDetail['amount'] ?? 0;
                                    @endphp
                                    @if($totalQuantity > 0)
                                        <th width="150">{{ $fert->name }} ({{ __('kg') }})</th>
                                        <th width="80">{{ __('Status') }}</th>
                                        <th width="50">% {{ __('of Total') }}</th>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody id="distributionTable">
                    <!-- Distribution rows will be populated here -->
                </tbody>
                <tfoot class="bg-light">
                    <tr>
                        <td colspan="5" class="text-right font-weight-bold">{{ __('Total Distributed') }}:</td>
                        <!-- This will be populated dynamically for each fertilizer column -->
                        <td class="font-weight-bold text-success" colspan="3" id="fertilizerTotal_0">0 {{ __('kg') }}</td>
                        <!-- More columns will be added dynamically -->
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="callout callout-info mt-3">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h6><i class="fas fa-info-circle mr-2"></i>{{ __('Instructions') }}</h6>
                    <p class="mb-0">{{ __('Enter fertilizer quantities for each week. Quantities will be validated against available amounts.') }}</p>
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
                                                {{ __('Fertilizer Distribution Summary') }} ({{ __('Application Year') }}: {{ $plan->year - 1 }})
                                            </h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th class="d-none d-md-table-cell">{{ __('Year') }}</th>
                                                            <th>{{ __('Fertilizer') }}</th>
                                                            <th class="d-none d-md-table-cell">{{ __('Type') }}</th>
                                                            <th class="d-none d-md-table-cell">{{ __('Quarter') }}</th>
                                                            <th>{{ __('Month') }}</th>
                                                            <th>{{ __('Week') }}</th>
                                                            <th class="d-none d-md-table-cell">{{ __('Date Range') }}</th>
                                                            <th>{{ __('Quantity') }}</th>
                                                            <th>{{ __('Action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="summaryTable">
                                                        <tr>
                                                            <td colspan="9" class="text-center text-muted py-4">
                                                                <i class="fas fa-pagelines mr-2"></i>
                                                                {{ __('No fertilizer distribution yet. Start by selecting fertilizer types and quarters above.') }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot class="bg-gray">
                                                        <tr>
                                                            <td colspan="7" class="text-right font-weight-bold">{{ __('Total Distributed') }}:</td>
                                                            <td class="font-weight-bold text-success" id="summaryTotal">0 {{ __('kg') }}</td>
                                                            <td></td>
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
        // Translations for JavaScript messages
        const translations = {
            please_select_quarters_first: "{{ __('Please select quarters first') }}",
            all_quarters_selected: "{{ __('All quarters selected for application year') }}",
            all_quarters_cleared: "{{ __('All quarters cleared') }}",
            all_months_selected: "{{ __('All months in selected quarters selected for application year') }}",
            all_months_cleared: "{{ __('All months cleared') }}",
            all_distributions_saved: "{{ __('All distributions saved successfully for application year') }}",
            distribution_removed: "{{ __('Distribution removed successfully!') }}",
            no_fertilizer_available: "{{ __('No fertilizer available to distribute for this month') }}",
            distributed_equally: "{{ __('Distributed equally across weeks in') }}",
            all_distributions_cleared: "{{ __('All distributions cleared for') }}",
            no_months_available: "{{ __('No months available in selected quarters.') }}",
            fix_validation_errors: "{{ __('Please fix validation errors before saving') }}",
            no_distribution_warning: "{{ __('No fertilizer distribution has been planned for application year') }}",
            please_add_distribution: "{{ __('Please add fertilizer distribution or confirm to proceed without it') }}",
            distributed_exceeds_available: "{{ __('Distributed quantity exceeds available quantity') }}",
            exceeds_available_quantity: "{{ __('Exceeds available quantity') }}",
            value_cannot_negative: "{{ __('Value cannot be negative') }}",
            valid: "{{ __('Valid') }}",
            week: "{{ __('Week') }}",
            days: "{{ __('Days') }}",
            year: "{{ __('Year') }}",
            months: "{{ __('months') }}",
            quarters: "{{ __('quarters') }}",
            distributions: "{{ __('distributions') }}",
            selected: "{{ __('Selected') }}",
            kg: "{{ __('kg') }}",
            ha: "{{ __('ha') }}",
            q: "{{ __('Q') }}",
            please_fix_errors: "{{ __('Please fix the validation errors before submitting.') }}",
            saved: "{{ __('Saved') }}",
            pending: "{{ __('Pending') }}",
            no_quantity_available: "{{ __('No quantity available') }}"
        };

        let selectedFertilizerTypes = new Set();
        let selectedQuarters = new Set();
        let selectedMonths = new Set();
        let fertilizerSummaryRows = [];
        const applicationYear = {{ $plan->year - 1 }};
        const planningYear = {{ $plan->year }};

        // Extract fertilizer totals from fertilizer_details column
        let fertilizerTotals = {};
        let fertilizerUnits = {};
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
                                total_quantity: {{ $totalQuantity }}
                            },
                        @endif
                    @endforeach
                ],
            @endforeach
        };

        // Initialize form
        $(document).ready(function() {
            console.log('Document ready - initializing fertilizer distribution for application year:', applicationYear);
            console.log('Fertilizer totals loaded:', fertilizerTotals);
            console.log('Available fertilizers:', FERTILIZERS);

            // Update the application year display
            $('#applicationYear').text(applicationYear);

            // Fertilizer type selection
            $('.fertilizer-type-checkbox').on('change', function() {
                const typeId = parseInt($(this).val());
                const card = $(this).closest('.fertilizer-type-card');

                if ($(this).is(':checked')) {
                    selectedFertilizerTypes.add(typeId);
                    card.removeClass('card-secondary').addClass('card-success card-selected');
                } else {
                    selectedFertilizerTypes.delete(typeId);
                    card.removeClass('card-success card-selected').addClass('card-secondary');
                }

                renderQuarters();
                updateBadges();
                checkAndRenderDistribution();
            });

            // Form submission validation
            $('#fertilizerPlanForm').on('submit', function(e) {
                console.log('Form submitted, validating...');
                if (!validateForm()) {
                    e.preventDefault();
                    showAlert(translations.please_fix_errors, 'error');
                } else {
                    console.log('Form validation passed, submitting...');
                    saveFertilizerJSON();
                }
            });

            renderQuarters();
            loadExistingFertilizerDistribution();
            updateTotalDistributedBadge();
        });

        /* -------------------------------
           FERTILIZER QUANTITY VALIDATION
        ------------------------------- */
        function getCurrentDistributedQuantity(fertId) {
            return fertilizerSummaryRows
                .filter(row => row.fertilizer_id == fertId)
                .reduce((total, row) => total + row.quantity, 0);
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
            const feedback = $(input).siblings('.validation-feedback');

            // Clear previous validation
            $(input).removeClass('is-valid is-invalid');
            feedback.empty();

            if (value < 0) {
                $(input).addClass('is-invalid');
                feedback.html(
                    '<small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>' + translations.value_cannot_negative + '</small>'
                );
                return false;
            }

            // Calculate remaining quantity excluding current row
            const distributedWithoutCurrent = fertilizerSummaryRows
                .filter(row => row.fertilizer_id == fertId &&
                    !(row.month === $(input).data('month-name') &&
                        row.week === `${translations.week} ${$(input).data('week')}`))
                .reduce((total, row) => total + row.quantity, 0);

            const totalAvailable = getFertilizerTotal(fertId);
            const maxAllowed = Math.max(0, totalAvailable - distributedWithoutCurrent);

            if (value > maxAllowed) {
                $(input).addClass('is-invalid');
                const fertilizer = getFertilizerById(fertId);
                feedback.html(
                    `<small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>${translations.exceeds_available_quantity} (${maxAllowed.toFixed(2)} ${fertilizer.unit})</small>`
                );
                return false;
            }

            if (value > 0) {
                $(input).addClass('is-valid');
                feedback.html('<small class="text-success"><i class="fas fa-check-circle mr-1"></i>' + translations.valid + '</small>');
            }

            return true;
        }

        function validateAllFertilizerDistributions() {
            let isValid = true;
            const errors = [];

            // Group by fertilizer ID and check totals
            const fertilizerGroups = {};
            fertilizerSummaryRows.forEach(row => {
                if (!fertilizerGroups[row.fertilizer_id]) {
                    fertilizerGroups[row.fertilizer_id] = {
                        fertilizer: getFertilizerById(row.fertilizer_id),
                        totalDistributed: 0,
                        rows: []
                    };
                }
                fertilizerGroups[row.fertilizer_id].totalDistributed += row.quantity;
                fertilizerGroups[row.fertilizer_id].rows.push(row);
            });

            // Check each fertilizer group
            Object.values(fertilizerGroups).forEach(group => {
                const totalAvailable = getFertilizerTotal(group.fertilizer.id);
                if (group.totalDistributed > totalAvailable) {
                    isValid = false;
                    errors.push(
                        `${translations.distributed_exceeds_available} ${group.fertilizer.name} (${group.totalDistributed.toFixed(2)} ${group.fertilizer.unit})`
                    );
                }
            });

            // Check individual inputs
            $('.fertilizer-quantity').each(function() {
                if (!validateFertilizerInput(this)) {
                    isValid = false;
                }
            });

            return {
                isValid,
                errors
            };
        }

        function getFertilizerById(fertId) {
            for (const typeId in FERTILIZERS) {
                const fertilizer = FERTILIZERS[typeId].find(f => f.id === fertId);
                if (fertilizer) return fertilizer;
            }
            return {
                name: '{{ __('Unknown') }}',
                unit: '{{ __('kg') }}'
            };
        }

        function getFertilizerTypeName(typeId) {
            const type = {!! json_encode($fertilizerTypes->keyBy('id')) !!}[typeId];
            return type ? type.name : '{{ __('Unknown Type') }}';
        }

        /* -------------------------------
           QUARTERS MANAGEMENT
        ------------------------------- */
        function renderQuarters() {
            $("#quartersContainer").empty();

            Object.keys(EC_QUARTERS).forEach(q => {
                const quarterQuantity = calculateQuarterDistributedQuantity(parseInt(q));
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
                                    <small class="text-success">
                                        <i class="fas fa-pagelines mr-1"></i>{{ __('Distributed') }}: ${quarterQuantity.toFixed(2)} {{ __('kg') }}
                                    </small>
                                </div>
                                <div class="col-12">
                                    <small class="text-info">
                                        <i class="fas fa-calendar mr-1"></i>{{ __('Year') }}: ${applicationYear}
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

            renderMonthsContainer();
            updateBadges();
            checkAndRenderDistribution();
        }

        function calculateQuarterDistributedQuantity(quarter) {
            const quarterName = `Q${quarter}`;
            return fertilizerSummaryRows
                .filter(row => row.quarter === quarterName)
                .reduce((total, row) => total + row.quantity, 0);
        }

        function selectAllQuarters() {
            Object.keys(EC_QUARTERS).forEach(q => {
                if (!selectedQuarters.has(parseInt(q))) {
                    selectedQuarters.add(parseInt(q));
                }
            });
            renderQuarters();
            renderMonthsContainer();
            updateBadges();
            checkAndRenderDistribution();
            showAlert(translations.all_quarters_selected + ' ' + applicationYear, 'success');
        }

        function clearAllQuarters() {
            selectedQuarters.clear();
            selectedMonths.clear();
            renderQuarters();
            $('#monthsSection').hide();
            $('#distributionSection').hide();
            updateBadges();
            showAlert(translations.all_quarters_cleared, 'info');
        }

        /* -------------------------------
           MONTHS MANAGEMENT
        ------------------------------- */
        function renderMonthsContainer() {
            $("#monthsContainer").empty();

            if (selectedQuarters.size === 0) {
                $("#monthsSection").hide();
                $("#distributionSection").hide();
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
                $("#monthsContainer").append(`
                    <div class="col-12">
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            ${translations.no_months_available}
                        </div>
                    </div>
                `);
                return;
            }

            allMonths.forEach(monthNum => {
                const month = EC_MONTHS[monthNum - 1];
                const monthQuantity = calculateMonthDistributedQuantity(monthNum);
                const isSelected = selectedMonths.has(monthNum);
                const quarterNum = findQuarter(monthNum);

                $("#monthsContainer").append(`
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="card card-outline ${isSelected ? 'card-info card-selected' : 'card-secondary'} h-100 month-card" data-month="${monthNum}">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input month-checkbox" type="checkbox"
                                               id="month${monthNum}" ${isSelected ? 'checked' : ''}>
                                        <label for="month${monthNum}" class="custom-control-label font-weight-bold">
                                            ${month.name}
                                        </label>
                                    </div>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <small class="text-muted">
                                            {{ __('Quarter') }}: <span class="badge badge-primary">Q${quarterNum}</span>
                                        </small>
                                    </div>
                                    <div class="col-12">
                                        <small class="text-success">
                                            <i class="fas fa-pagelines mr-1"></i>{{ __('Distributed') }}: ${monthQuantity.toFixed(2)} {{ __('kg') }}
                                        </small>
                                    </div>
                                    <div class="col-12">
                                        <small class="text-info">
                                            <i class="fas fa-calendar mr-1"></i>{{ __('Year') }}: ${applicationYear}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);

                // Add event listener to month card
                $(`#month${monthNum}`).on('change', function() {
                    handleMonthChange(monthNum, $(this).is(':checked'));
                });
            });

            // Add click event to month cards
            $('.month-card').click(function(e) {
                if (!$(e.target).is('input') && !$(e.target).is('label')) {
                    const checkbox = $(this).find('.month-checkbox');
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    checkbox.trigger('change');
                }
            });
        }

        function handleMonthChange(monthNum, isChecked) {
            if (isChecked) {
                selectedMonths.add(monthNum);
                $(`.month-card[data-month="${monthNum}"]`).removeClass('card-secondary').addClass('card-info card-selected');
            } else {
                selectedMonths.delete(monthNum);
                $(`.month-card[data-month="${monthNum}"]`).removeClass('card-info card-selected').addClass('card-secondary');
            }

            checkAndRenderDistribution();
            updateBadges();
        }

        function calculateMonthDistributedQuantity(month) {
            const monthName = EC_MONTHS[month - 1].name;
            return fertilizerSummaryRows
                .filter(row => row.month === monthName)
                .reduce((total, row) => total + row.quantity, 0);
        }

        function selectAllMonths() {
            if (selectedQuarters.size === 0) {
                showAlert(translations.please_select_quarters_first, 'warning');
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

            renderMonthsContainer();
            checkAndRenderDistribution();
            updateBadges();
            showAlert(translations.all_months_selected + ' ' + applicationYear, 'success');
        }

        function clearAllMonths() {
            selectedMonths.clear();
            renderMonthsContainer();
            $('#distributionSection').hide();
            updateBadges();
            showAlert(translations.all_months_cleared, 'info');
        }

        /* -------------------------------
           DISTRIBUTION MANAGEMENT
        ------------------------------- */
        function checkAndRenderDistribution() {
            if (selectedMonths.size === 0 || selectedFertilizerTypes.size === 0) {
                $("#distributionSection").hide();
                return;
            }
            renderDistribution();
        }

       function renderDistribution() {
    console.log('Rendering distribution for months:', Array.from(selectedMonths), 'and fertilizer types:', Array.from(selectedFertilizerTypes));
    
    $("#distributionTable").empty();

    if (selectedMonths.size === 0 || selectedFertilizerTypes.size === 0) {
        $("#distributionSection").hide();
        return;
    }

    $("#distributionSection").show();

    const monthsArray = Array.from(selectedMonths).sort((a, b) => a - b);
    let allRows = [];

    monthsArray.forEach(monthNum => {
        const month = EC_MONTHS[monthNum - 1];
        const weeks = getWeeksForMonth(monthNum);
        const quarterNum = findQuarter(monthNum);

        weeks.forEach(week => {
            const row = {
                monthNum: monthNum,
                weekNumber: week.weekNumber,
                monthName: month.name,
                quarterNum: quarterNum,
                dateRange: `${translations.days} ${week.start}-${week.end}`,
                year: applicationYear,
                fertilizers: {}
            };

            // Get existing distribution for this week
            Array.from(selectedFertilizerTypes).forEach(typeId => {
                const fertilizers = FERTILIZERS[typeId] || [];
                fertilizers.forEach(fert => {
                    const existing = fertilizerSummaryRows.find(row =>
                        row.month === month.name &&
                        row.week === `${translations.week} ${week.weekNumber}` &&
                        row.fertilizer_id == fert.id
                    );
                    
                    row.fertilizers[fert.id] = {
                        quantity: existing ? existing.quantity : 0,
                        fertilizer: fert,
                        typeId: typeId
                    };
                });
            });

            allRows.push(row);
        });
    });

    // Render table rows
    allRows.forEach(row => {
        let fertilizerCells = '';
        
        Array.from(selectedFertilizerTypes).forEach(typeId => {
            const fertilizers = FERTILIZERS[typeId] || [];
            fertilizers.forEach(fert => {
                const fertData = row.fertilizers[fert.id];
                const distributed = getCurrentDistributedQuantity(fert.id);
                const total = getFertilizerTotal(fert.id);
                const percentage = total > 0 ? (distributed / total * 100) : 0;
                const status = fertData.quantity > 0 ? translations.saved : translations.pending;
                const statusClass = fertData.quantity > 0 ? 'badge-success' : 'badge-secondary';
                
                fertilizerCells += `
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="number" step="0.01" min="0" max="${getRemainingQuantity(fert.id) + fertData.quantity}"
                                   class="form-control form-control-sm fertilizer-quantity"
                                   data-type-id="${typeId}"
                                   data-fert-id="${fert.id}"
                                   data-month="${row.monthNum}"
                                   data-week="${row.weekNumber}"
                                   data-month-name="${row.monthName}"
                                   value="${fertData.quantity.toFixed(2)}"
                                   placeholder="0.00"
                                   ${getRemainingQuantity(fert.id) === 0 && fertData.quantity === 0 ? 'disabled' : ''}>
                            <div class="input-group-append">
                                <span class="input-group-text">{{ __('kg') }}</span>
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
                `;
            });
        });

        $("#distributionTable").append(`
            <tr class="week-row" data-month="${row.monthNum}" data-week="${row.weekNumber}">
                <td>
                    <strong>${translations.week} ${row.weekNumber}</strong>
                </td>
                <td>${row.monthName}</td>
                <td>
                    <span class="badge badge-primary">Q${row.quarterNum}</span>
                </td>
                <td>
                    <small class="text-muted">${row.dateRange}</small>
                </td>
                <td>${row.year}</td>
                ${fertilizerCells}
            </tr>
        `);
    });

    // Update footer totals
    updateDistributionTableFooter();
    
    updateTotalDistributedBadge();
    enhanceFertilizerInputValidation();
}

function updateDistributionTableFooter() {
    // Calculate totals for each fertilizer
    const fertilizerTotalsMap = {};
    
    // Initialize totals map
    Array.from(selectedFertilizerTypes).forEach(typeId => {
        const fertilizers = FERTILIZERS[typeId] || [];
        fertilizers.forEach(fert => {
            fertilizerTotalsMap[fert.id] = {
                distributed: 0,
                fertilizer: fert
            };
        });
    });
    
    // Sum up all distributions
    fertilizerSummaryRows.forEach(row => {
        if (fertilizerTotalsMap[row.fertilizer_id]) {
            fertilizerTotalsMap[row.fertilizer_id].distributed += row.quantity;
        }
    });
    
    // Update footer row - this will need to be re-rendered or updated
    $('tfoot').empty();
    
    let footerCells = '';
    let colSpan = 5; // For the first 5 columns
    
    Array.from(selectedFertilizerTypes).forEach(typeId => {
        const fertilizers = FERTILIZERS[typeId] || [];
        fertilizers.forEach(fert => {
            const total = fertilizerTotalsMap[fert.id];
            const percentage = fert.total_quantity > 0 ? (total.distributed / fert.total_quantity * 100) : 0;
            
            footerCells += `
                <td colspan="3" class="font-weight-bold text-primary">
                    ${total.distributed.toFixed(2)} {{ __('kg') }}<br>
                    <small class="text-info">${percentage.toFixed(1)}%</small>
                </td>
            `;
        });
    });
    
    $('tfoot').html(`
        <tr class="bg-light">
            <td colspan="${colSpan}" class="text-right font-weight-bold">{{ __('Total Distributed') }}:</td>
            ${footerCells}
        </tr>
    `);
}

        function initializeExistingInputValues() {
            fertilizerSummaryRows.forEach(row => {
                const monthNum = EC_MONTHS.findIndex(m => m.name === row.month) + 1;
                const weekNumber = parseInt(row.week.replace('{{ __('Week') }} ', ''));
                
                const input = $(`.fertilizer-quantity[data-month="${monthNum}"][data-week="${weekNumber}"][data-fert-id="${row.fertilizer_id}"]`);
                if (input.length) {
                    input.val(row.quantity);
                    validateFertilizerInput(input[0]);
                }
            });
        }

        function renderFertilizerInputs(typeId, monthNum, weekNumber) {
            const fertilizers = FERTILIZERS[typeId] || [];
            const month = EC_MONTHS[monthNum - 1];
            const fertilizerType = getFertilizerTypeName(typeId);

            return fertilizers.map(fert => {
                const existing = fertilizerSummaryRows.find(row =>
                    row.month === month.name &&
                    row.week === `${translations.week} ${weekNumber}` &&
                    row.fertilizer_id == fert.id
                );

                const distributed = getCurrentDistributedQuantity(fert.id);
                const remaining = getRemainingQuantity(fert.id);
                const total = getFertilizerTotal(fert.id);

                // Calculate max allowed for this input
                const distributedWithoutCurrent = fertilizerSummaryRows
                    .filter(row => row.fertilizer_id == fert.id &&
                        !(row.month === month.name &&
                            row.week === `${translations.week} ${weekNumber}`))
                    .reduce((total, row) => total + row.quantity, 0);

                const maxAllowed = Math.max(0, total - distributedWithoutCurrent);

                return `
                <div class="fertilizer-input-item mb-2 p-2 border rounded bg-light">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-md-5">
                            <small class="fw-bold d-block">${fert.name}</small>
                            <small class="text-muted">${fertilizerType}</small>
                            <div class="quantity-info mt-1">
                                <small class="text-success">{{ __('Used') }}: ${distributed.toFixed(2)}/${total.toFixed(2)} ${fert.unit}</small><br>
                                <small class="${remaining === 0 ? 'text-danger' : 'text-warning'}">{{ __('Available') }}: ${remaining.toFixed(2)} ${fert.unit}</small>
                            </div>
                        </div>
                        <div class="col-7 col-md-4">
                            <div class="input-group input-group-sm">
                                <input type="number" 
                                       step="0.01" 
                                       min="0" 
                                       max="${maxAllowed}"
                                       class="form-control fertilizer-quantity"
                                       data-type-id="${typeId}"
                                       data-fert-id="${fert.id}"
                                       data-month="${monthNum}"
                                       data-week="${weekNumber}"
                                       data-month-name="${month.name}"
                                       value="${existing ? existing.quantity : ''}"
                                       placeholder="0.00"
                                       ${remaining === 0 && !existing?.quantity ? 'disabled' : ''}>
                                <span class="input-group-text">${fert.unit}</span>
                            </div>
                            <div class="validation-feedback mt-1"></div>
                            ${remaining === 0 && !existing?.quantity ? '<small class="text-danger">' + translations.no_quantity_available + '</small>' : ''}
                        </div>
                        <div class="col-5 col-md-3 text-center">
                            ${existing && existing.quantity > 0 ?
                            '<span class="badge bg-success"><i class="fas fa-check me-1"></i>' + translations.saved + '</span>' :
                            '<span class="badge bg-secondary"><i class="fas fa-clock me-1"></i>' + translations.pending + '</span>'
                            }
                        </div>
                    </div>
                </div>
            `;
            }).join('');
        }

       function enhanceFertilizerInputValidation() {
    $('.fertilizer-quantity').off('input').on('input', function() {
        validateFertilizerInput(this);
        updateTotalDistributedBadge();
        updateDistributionTableFooter(); // Add this line
        
        // Update the distribution data when input changes
        const typeId = parseInt($(this).data('type-id'));
        const fertId = parseInt($(this).data('fert-id'));
        const monthNum = parseInt($(this).data('month'));
        const weekNumber = parseInt($(this).data('week'));
        const quantity = parseFloat($(this).val()) || 0;

        const month = EC_MONTHS[monthNum - 1];
        const fertilizer = FERTILIZERS[typeId]?.find(f => f.id === fertId);

        if (fertilizer && validateFertilizerInput(this)) {
            updateFertilizerDistribution(month.name, weekNumber, typeId, fertId, fertilizer.name, quantity, fertilizer.unit);
            updateSummaryTable();
            updateTotalDistributedBadge();
            updateDistributionTableFooter(); // Add this line
        }
    });
}

        // Update fertilizer distribution when input changes
        $(document).on('input', '.fertilizer-quantity', function() {
            const typeId = parseInt($(this).data('type-id'));
            const fertId = parseInt($(this).data('fert-id'));
            const monthNum = parseInt($(this).data('month'));
            const weekNumber = parseInt($(this).data('week'));
            const quantity = parseFloat($(this).val()) || 0;

            const month = EC_MONTHS[monthNum - 1];
            const fertilizer = FERTILIZERS[typeId]?.find(f => f.id === fertId);

            if (!fertilizer) return;

            // Validate before updating
            if (validateFertilizerInput(this)) {
                updateFertilizerDistribution(month.name, weekNumber, typeId, fertId, fertilizer.name, quantity,
                    fertilizer.unit);
                updateSummaryTable();
                updateTotalDistributedBadge();
            }
        });

        function updateFertilizerDistribution(monthName, weekNumber, typeId, fertId, fertName, quantity, unit) {
            // Find or create distribution entry
            let distribution = fertilizerSummaryRows.find(d =>
                d.month === monthName &&
                d.week === `${translations.week} ${weekNumber}` &&
                d.fertilizer_type_id == typeId &&
                d.fertilizer_id == fertId
            );

            if (distribution) {
                distribution.quantity = quantity;
            } else {
                const weekData = getWeekData(EC_MONTHS.findIndex(m => m.name === monthName) + 1, weekNumber);
                distribution = {
                    year: applicationYear,
                    quarter: `Q${findQuarter(EC_MONTHS.findIndex(m => m.name === monthName) + 1)}`,
                    month: monthName,
                    week: `${translations.week} ${weekNumber}`,
                    range: weekData.range,
                    fertilizer_type_id: typeId,
                    fertilizer_id: fertId,
                    fertilizer_name: fertName,
                    quantity: quantity,
                    unit: unit
                };
                fertilizerSummaryRows.push(distribution);
            }

            // Remove if quantity is 0
            if (quantity === 0) {
                fertilizerSummaryRows = fertilizerSummaryRows.filter(d =>
                    !(d.month === monthName &&
                        d.week === `${translations.week} ${weekNumber}` &&
                        d.fertilizer_type_id == typeId &&
                        d.fertilizer_id == fertId)
                );
            }
        }

      function distributeEqually(monthNum) {
    const month = EC_MONTHS[monthNum - 1];
    const selectedFertilizers = Array.from(selectedFertilizerTypes).flatMap(typeId =>
        FERTILIZERS[typeId]?.map(fert => ({
            typeId,
            fert
        })) || []
    );

    if (selectedFertilizers.length === 0) {
        showAlert('{{ __('No fertilizers selected for distribution') }}', 'warning');
        return;
    }

    const weeks = getWeeksForMonth(monthNum);
    if (weeks.length === 0) return;

    selectedFertilizers.forEach(({ typeId, fert }) => {
        const remaining = getRemainingQuantity(fert.id);
        if (remaining <= 0) {
            showAlert(`{{ __('No remaining') }} ${fert.name} {{ __('available for distribution') }}`, 'warning');
            return;
        }

        const equalQuantity = remaining / weeks.length;

        weeks.forEach(week => {
            const input = $(
                `.fertilizer-quantity[data-month="${monthNum}"][data-week="${week.weekNumber}"][data-fert-id="${fert.id}"]`
            );
            if (input.length) {
                input.val(equalQuantity.toFixed(2));
                updateFertilizerDistribution(month.name, week.weekNumber, typeId, fert.id, fert.name, equalQuantity, fert.unit);
            }
        });
    });

    updateSummaryTable();
    updateTotalDistributedBadge();
    updateDistributionTableFooter(); // Add this line
    enhanceFertilizerInputValidation();
    showAlert(`${translations.distributed_equally} ${month.name}`, 'success');
}

function clearMonthDistribution(monthNum) {
    const month = EC_MONTHS[monthNum - 1];
    fertilizerSummaryRows = fertilizerSummaryRows.filter(row => row.month !== month.name);

    // Clear all inputs for this month
    $(`.fertilizer-quantity[data-month="${monthNum}"]`).val('');

    updateSummaryTable();
    saveFertilizerJSON();
    updateTotalDistributedBadge();
    updateDistributionTableFooter(); // Add this line
    renderDistribution(); // This will refresh the entire table
    enhanceFertilizerInputValidation();

    showAlert(`${translations.all_distributions_cleared} ${month.name}`, 'info');
}

        function clearMonthDistribution(monthNum) {
            const month = EC_MONTHS[monthNum - 1];
            fertilizerSummaryRows = fertilizerSummaryRows.filter(row => row.month !== month.name);

            // Clear all inputs for this month
            $(`.fertilizer-quantity[data-month="${monthNum}"]`).val('');

            updateSummaryTable();
            saveFertilizerJSON();
            updateTotalDistributedBadge();
            renderDistribution();
            enhanceFertilizerInputValidation();

            showAlert(`${translations.all_distributions_cleared} ${month.name}`, 'info');
        }

        function saveAllDistributions() {
            const validation = validateAllFertilizerDistributions();

            if (!validation.isValid) {
                showAlert(translations.fix_validation_errors, 'error');
                validation.errors.forEach(error => showAlert(error, 'warning'));
                return;
            }

            // Update all fertilizer distributions from inputs
            $('.fertilizer-quantity').each(function() {
                const typeId = parseInt($(this).data('type-id'));
                const fertId = parseInt($(this).data('fert-id'));
                const monthNum = parseInt($(this).data('month'));
                const weekNumber = parseInt($(this).data('week'));
                const quantity = parseFloat($(this).val()) || 0;

                const month = EC_MONTHS[monthNum - 1];
                const fertilizer = FERTILIZERS[typeId]?.find(f => f.id === fertId);

                if (fertilizer) {
                    updateFertilizerDistribution(month.name, weekNumber, typeId, fertId, fertilizer.name, quantity,
                        fertilizer.unit);
                }
            });

            updateSummaryTable();
            saveFertilizerJSON();
            updateTotalDistributedBadge();
            showAlert(translations.all_distributions_saved + ' ' + applicationYear + '!',
                'success');
        }

        /* -------------------------------
           UTILITY FUNCTIONS
        ------------------------------- */
        function getWeekData(monthNum, weekNumber) {
            const month = EC_MONTHS[monthNum - 1];
            let start = 1 + (weekNumber - 1) * 7;
            let end = Math.min(start + 6, month.days);
            return {
                range: `${start}-${end}`
            };
        }

        function getWeeksForMonth(monthNum) {
            const month = EC_MONTHS[monthNum - 1];
            let weeks = [];
            let start = 1;
            let weekNumber = 1;

            while (start <= month.days) {
                let end = Math.min(start + 6, month.days);
                weeks.push({
                    weekNumber,
                    start,
                    end
                });
                start += 7;
                weekNumber++;
            }

            return weeks;
        }

        function findQuarter(monthId) {
            return Object.keys(EC_QUARTERS).find(q => EC_QUARTERS[q].includes(monthId));
        }

        function calculateTotalDistributedQuantity() {
            let total = 0;
            fertilizerSummaryRows.forEach(row => {
                total += row.quantity;
            });
            return total;
        }

        function updateTotalDistributedBadge() {
            const totalDistributed = calculateTotalDistributedQuantity();
            $('#totalDistributedBadge').text(totalDistributed.toFixed(2) + ' {{ __('kg') }}');
        }

        function updateBadges() {
            $("#selectedQuartersBadge").text(selectedQuarters.size + " {{ __('quarters') }}");
            $("#selectedMonthsBadge").text(selectedMonths.size + " {{ __('months') }}");
            $("#selectedWeeksBadge").text(fertilizerSummaryRows.length + " {{ __('distributions') }}");
        }

        function loadExistingFertilizerDistribution() {
            let existing = {!! json_encode($plan->fertilizer_distribution_details ?? '[]') !!};

            console.log('Loading existing fertilizer distribution:', existing);

            if (existing && typeof existing === 'object') {
                if (Array.isArray(existing)) {
                    fertilizerSummaryRows = existing.map(row => ({
                        year: row.year || applicationYear,
                        quarter: row.quarter,
                        month: row.month,
                        week: row.week,
                        range: row.range || '',
                        fertilizer_type_id: row.fertilizer_type_id,
                        fertilizer_id: row.fertilizer_id,
                        fertilizer_name: row.fertilizer_name,
                        quantity: parseFloat(row.quantity) || 0,
                        unit: row.unit || '{{ __('kg') }}'
                    }));
                } else if (existing.weekly_distribution) {
                    fertilizerSummaryRows = existing.weekly_distribution.map(row => ({
                        year: row.year || applicationYear,
                        quarter: row.quarter,
                        month: row.month,
                        week: row.week,
                        range: row.range || '',
                        fertilizer_type_id: row.fertilizer_type_id,
                        fertilizer_id: row.fertilizer_id,
                        fertilizer_name: row.fertilizer_name,
                        quantity: parseFloat(row.quantity) || 0,
                        unit: row.unit || '{{ __('kg') }}'
                    }));
                }

                // Restore selections
                fertilizerSummaryRows.forEach(row => {
                    const quarterNum = parseInt(row.quarter.replace('Q', ''));
                    selectedQuarters.add(quarterNum);

                    const monthNum = EC_MONTHS.findIndex(m => m.name === row.month) + 1;
                    if (monthNum > 0) selectedMonths.add(monthNum);

                    if (row.fertilizer_type_id) {
                        selectedFertilizerTypes.add(parseInt(row.fertilizer_type_id));
                    }
                });

                // Update UI
                $('.fertilizer-type-checkbox').each(function() {
                    const typeId = parseInt($(this).val());
                    if (selectedFertilizerTypes.has(typeId)) {
                        $(this).prop('checked', true);
                        $(this).closest('.fertilizer-type-card').removeClass('card-secondary').addClass('card-success card-selected');
                    }
                });

                updateSummaryTable();
                updateBadges();

                if (selectedQuarters.size > 0) {
                    renderQuarters();
                    renderMonthsContainer();
                }

                // Check and render distribution after loading existing data
                checkAndRenderDistribution();

                updateTotalDistributedBadge();
            }
        }

        function saveFertilizerJSON() {
            const distributionData = {
                planning_year: planningYear,
                application_year: applicationYear,
                weekly_distribution: fertilizerSummaryRows,
                total_distributed: calculateTotalDistributedQuantity(),
                updated_at: new Date().toISOString()
            };

            $("#fertilizerDistributionDetails").val(JSON.stringify(distributionData));
            console.log('Saved fertilizer_distribution_details for application year ' + applicationYear + ':',
                distributionData);
        }

        /* -------------------------------
           SUMMARY TABLE
        ------------------------------- */
        function updateSummaryTable() {
            $("#summaryTable").empty();
            let total = 0;

            if (fertilizerSummaryRows.length === 0) {
                $("#summaryTable").append(`
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        <i class="fas fa-pagelines mr-2"></i>
                        {{ __('No fertilizer distribution yet. Start by selecting fertilizer types and quarters above.') }}
                    </td>
                </tr>
            `);
            } else {
                // Group by fertilizer for better organization
                const fertilizerGroups = {};
                fertilizerSummaryRows.forEach(row => {
                    if (!fertilizerGroups[row.fertilizer_id]) {
                        fertilizerGroups[row.fertilizer_id] = {
                            fertilizer: getFertilizerById(row.fertilizer_id),
                            rows: [],
                            total: 0
                        };
                    }
                    fertilizerGroups[row.fertilizer_id].rows.push(row);
                    fertilizerGroups[row.fertilizer_id].total += row.quantity;
                    total += row.quantity;
                });

                Object.values(fertilizerGroups).forEach(group => {
                    // Add header row for fertilizer
                    $("#summaryTable").append(`
                        <tr class="table-primary">
                            <td colspan="5" class="fw-bold">
                                <i class="fas fa-pagelines me-2"></i>
                                ${group.fertilizer.name} - {{ __('Total') }}: ${group.total.toFixed(2)} ${group.fertilizer.unit}
                            </td>
                            <td colspan="4" class="text-end">
                                <small class="text-muted">
                                    {{ __('Available') }}: ${getFertilizerTotal(group.fertilizer.id)} ${group.fertilizer.unit} | 
                                    {{ __('Remaining') }}: ${getRemainingQuantity(group.fertilizer.id).toFixed(2)} ${group.fertilizer.unit}
                                </small>
                            </td>
                        </tr>
                    `);

                    // Add distribution rows
                    group.rows.forEach((row, i) => {
                        $("#summaryTable").append(`
                        <tr>
                            <td class="d-none d-md-table-cell">${row.year || applicationYear}</td>
                            <td>${row.fertilizer_name}</td>
                            <td class="d-none d-md-table-cell">${getFertilizerTypeName(row.fertilizer_type_id)}</td>
                            <td class="d-none d-md-table-cell">${row.quarter}</td>
                            <td>${row.month}</td>
                            <td>${row.week}</td>
                            <td class="d-none d-md-table-cell">${row.range || ''}</td>
                            <td class="font-weight-bold text-success">${row.quantity.toFixed(2)} ${row.unit}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-danger" onclick="removeFertilizerRow(${fertilizerSummaryRows.findIndex(r => 
                                    r.month === row.month && 
                                    r.week === row.week && 
                                    r.fertilizer_id === row.fertilizer_id
                                )})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                    });
                });
            }

            $("#summaryTotal").text(total.toFixed(2) + " {{ __('kg') }}");
        }

        function removeFertilizerRow(i) {
            fertilizerSummaryRows.splice(i, 1);
            updateSummaryTable();
            saveFertilizerJSON();
            updateTotalDistributedBadge();
            renderDistribution(); // Refresh distribution inputs
            showAlert(translations.distribution_removed, 'info');
        }

        function validateForm() {
            let isValid = true;
            const errors = [];

            // Check if fertilizer types are selected
            if (selectedFertilizerTypes.size === 0) {
                errors.push('{{ __('Please select at least one fertilizer type') }}');
                isValid = false;
            }

            // Enhanced distribution validation
            const distributedQuantity = calculateTotalDistributedQuantity();

            if (distributedQuantity === 0) {
                const proceed = confirm(translations.no_distribution_warning + ' ' +
                    applicationYear + '?');
                if (!proceed) {
                    errors.push(translations.please_add_distribution);
                    isValid = false;
                }
            }

            // Validate fertilizer quantities
            const fertilizerValidation = validateAllFertilizerDistributions();
            if (!fertilizerValidation.isValid) {
                errors.push(...fertilizerValidation.errors);
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

        /* Card hover effects */
        .card-outline:hover:not(.card-selected) {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Quarter, month, and fertilizer type card hover */
        .quarter-card, .month-card, .fertilizer-type-card {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .quarter-card:hover:not(.card-selected) {
            border-color: #007bff !important;
        }

        .month-card:hover:not(.card-selected) {
            border-color: #17a2b8 !important;
        }

        .fertilizer-type-card:hover:not(.card-selected) {
            border-color: #28a745 !important;
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

        /* Week row styling */
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

        /* Fertilizer input styling */
        .fertilizer-input-item {
            border-left: 3px solid #28a745 !important;
        }

        .month-section {
            border-left: 4px solid #28a745 !important;
        }

        .fertilizer-quantity:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        .fertilizer-quantity.is-valid {
            border-color: #28a745;
        }

        .fertilizer-quantity.is-invalid {
            border-color: #dc3545;
        }

        /* Quantity info styling */
        .quantity-info small {
            display: block;
            line-height: 1.2;
            font-size: 0.75rem;
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
            
            .fertilizer-quantity {
                font-size: 0.8rem !important;
            }
        }

        /* Table styling */
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table-primary td {
            background-color: #e3f2fd !important;
            border-bottom: 2px solid #bbdefb;
        }

        /* Table Header Styles - WHITESMOKE with BLACK TEXT - ONLY FOR ACTUAL TABLE HEADERS */
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
        
        /* Zebra striping for better readability - ONLY for tables */
        table.table.table-striped tbody tr:nth-of-type(odd),
        .table.table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        table.table.table-striped tbody tr:nth-of-type(even),
        .table.table-striped tbody tr:nth-of-type(even) {
            background-color: white;
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
        
        .card-success .card-header .card-title {
            color: white !important; /* White text for success card headers */
        }
        
        .card-outline .card-header .card-title {
            color: #495057 !important; /* Default dark color for outline cards */
        }

        /* Ensure table headers have whitesmoke background with black text */
#distributionSection table.table thead {
    background-color: whitesmoke !important;
}

#distributionSection table.table thead th {
    background-color: whitesmoke !important;
    color: #000000 !important;
    border-color: #dee2e6 !important;
}

#distributionSection table.table thead.thead-dark th {
    background-color: #343a40 !important;
    color: white !important;
}
    </style>
</x-layout>