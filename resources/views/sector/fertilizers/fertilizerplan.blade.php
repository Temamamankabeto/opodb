<x-layout>
    <div class="container-fluid py-3 py-md-4 fertilizer-plan-container">
        <!-- Header Section -->
        <div class="row justify-content-center mb-4 mb-md-5">
            <div class="col-12 col-lg-10">
                <div class="page-header text-center">
                    <div class="header-icon mb-2 mb-md-3">
                        <i class="mdi mdi-fertilizer display-4 text-success"></i>
                    </div>
                    <h1 class="display-6 fw-bold text-success mb-2">Edit Fertilizer Distribution Plan</h1>
                    <p class="lead text-muted d-none d-md-block">Update fertilizer distribution schedule for
                        {{ Auth::user()->kebele->name }} Kebele</p>
                    <p class="text-muted d-md-none">Update fertilizer distribution schedule</p>
                    <div class="header-divider">
                        <div class="divider-line"></div>
                        <div class="divider-icon">
                            <i class="mdi mdi-sprout"></i>
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
                            <i class="mdi mdi-calendar-edit me-2"></i>
                            Edit Fertilizer Distribution Planning
                        </h4>
                    </div>
                    <div class="card-body p-3 p-md-5">
                        <form action="{{ route('sector.fertilizer-plans.update', $plan->id) }}" method="POST"
                            id="fertilizerPlanForm">
                            @csrf
                            @method('PUT')

                            <!-- Basic Information Display (Read-only) -->
                            <div class="section-header mb-3 mb-md-4">
                                <h5 class="section-title fs-6 fs-md-5">
                                    <i class="mdi mdi-information-outline me-2 text-primary"></i>
                                    Crop Plan Information
                                </h5>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row g-2 g-md-4 mb-3 mb-md-4">
                                <div class="col-6 col-md-3">
                                    <div class="info-card p-2 p-md-3 bg-light rounded">
                                        <small class="text-muted">Crop Type</small>
                                        <div class="fw-bold text-primary fs-6 fs-md-normal">
                                            {{ $plan->cropType->name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="info-card p-2 p-md-3 bg-light rounded">
                                        <small class="text-muted">Crop</small>
                                        <div class="fw-bold text-primary fs-6 fs-md-normal">
                                            {{ $plan->crop->name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="info-card p-2 p-md-3 bg-light rounded">
                                        <small class="text-muted">Planning Year</small>
                                        <div class="fw-bold text-primary fs-6 fs-md-normal">{{ $plan->year }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="info-card p-2 p-md-3 bg-light rounded">
                                        <small class="text-muted">Application Year</small>
                                        <div class="fw-bold text-primary fs-6 fs-md-normal">{{ $plan->year - 1 }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="info-card p-2 p-md-3 bg-light rounded">
                                        <small class="text-muted">Season</small>
                                        <div class="fw-bold text-primary fs-6 fs-md-normal">{{ $plan->season }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information Display -->
                            <div class="row g-2 g-md-4 mb-3 mb-md-4">
                                <div class="col-6 col-md-4">
                                    <div class="info-card p-2 p-md-3 bg-light rounded">
                                        <small class="text-muted">Area to Plant</small>
                                        <div class="fw-bold text-primary fs-6 fs-md-normal">{{ $plan->area_to_plant }}
                                            ha</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="info-card p-2 p-md-3 bg-light rounded">
                                        <small class="text-muted">Productivity</small>
                                        <div class="fw-bold text-primary fs-6 fs-md-normal">{{ $plan->productivity }}
                                            qt/ha</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="info-card p-2 p-md-3 bg-light rounded">
                                        <small class="text-muted">Expected Yield</small>
                                        <div class="fw-bold text-primary fs-6 fs-md-normal">{{ $plan->expected_yield }}
                                            qt</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Available Fertilizers Summary -->
                            <div class="section-header mt-4 mt-md-5 mb-3 mb-md-4">
                                <h5 class="section-title fs-6 fs-md-5">
                                    <i class="mdi mdi-fertilizer me-2 text-info"></i>
                                    Available Fertilizers Summary
                                </h5>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row g-2 g-md-3 mb-4">
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
                                            <div class="col-12 col-md-6 col-lg-4">
                                                <div class="card bg-light border-0">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h6 class="mb-1">{{ $fertilizer->name }}</h6>
                                                                <small
                                                                    class="text-muted">{{ $fertilizerType->name ?? 'N/A' }}</small>
                                                            </div>
                                                            <span class="badge bg-success fs-6">
                                                                {{ $fertDetail['amount'] }} {{ $fertDetail['unit'] }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <div class="alert alert-warning">
                                            <i class="mdi mdi-alert-circle-outline me-2"></i>
                                            No fertilizers allocated for this crop plan.
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Fertilizer Types Section -->
                            <div class="section-header mt-4 mt-md-5 mb-3 mb-md-4">
                                <h5 class="section-title fs-6 fs-md-5">
                                    <i class="mdi mdi-fertilizer me-2 text-success"></i>
                                    Select Fertilizer Types for Distribution
                                </h5>
                                <div class="section-divider"></div>
                                <p class="text-muted mt-2 d-none d-md-block">
                                    Select one or more fertilizer types to distribute. Quantities will be validated
                                    against available amounts.
                                </p>
                            </div>

                            <div class="row g-2 g-md-3" id="fertilizerTypesContainer">
                                @foreach ($fertilizerTypes as $fertilizerType)
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <div class="card fertilizer-type-card p-3"
                                            data-type-id="{{ $fertilizerType->id }}">
                                            <div class="form-check">
                                                <input class="form-check-input fertilizer-type-checkbox"
                                                    type="checkbox" id="fertilizerType{{ $fertilizerType->id }}"
                                                    value="{{ $fertilizerType->id }}">
                                                <label class="form-check-label fw-bold"
                                                    for="fertilizerType{{ $fertilizerType->id }}">
                                                    {{ $fertilizerType->name }}
                                                </label>
                                            </div>
                                            @if ($fertilizerType->fertilizers->count() > 0)
                                                <div class="mt-2">
                                                    <small class="text-muted">Available in this type:</small>
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
                                                                    {{ $fertilizer->unit ?? 'kg' }})
                                                                </span>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Fertilizer Distribution Planning Section -->
                            <div class="section-header mt-4 mt-md-5 mb-3 mb-md-4">
                                <h5 class="section-title fs-6 fs-md-5">
                                    <i class="mdi mdi-calendar-multiple me-2 text-info"></i>
                                    Fertilizer Distribution Planning (Application Year: <span
                                        id="applicationYear">{{ $plan->year - 1 }}</span>)
                                </h5>
                                <div class="section-divider"></div>
                                <p class="text-muted mt-2 d-none d-md-block">
                                    Select multiple quarters, months, and distribute fertilizer quantities across weeks
                                    for application in the preparation year ({{ $plan->year - 1 }}).
                                </p>
                                <p class="text-muted mt-1 d-md-none small">
                                    Select quarters, months, and distribute fertilizer quantities for application year.
                                </p>
                            </div>

                            <div class="fertilizer-distribution-container">
                                <!-- Application Year Section -->
                                <div class="card mb-3 mb-md-4 border-0 shadow-sm">
                                    <div class="card-header bg-light py-2 py-md-3">
                                        <div
                                            class="d-flex justify-content-between align-items-center flex-column flex-md-row">
                                            <h6 class="mb-1 mb-md-0 fs-6">
                                                <i class="mdi mdi-calendar-range me-2 text-success"></i>
                                                Application Year: {{ $plan->year - 1 }}
                                            </h6>
                                            <div class="mt-1 mt-md-0">
                                                <span class="badge bg-success fs-6">Fertilizer Application
                                                    Activities</span>
                                            </div>
                                        </div>
                                        <small class="text-muted d-none d-md-block">All fertilizer application
                                            activities occur in the year before planting
                                            ({{ $plan->year - 1 }})</small>
                                        <small class="text-muted d-md-none small">Application year:
                                            {{ $plan->year - 1 }}</small>
                                    </div>
                                </div>

                                <!-- Quarters Section -->
                                <div class="card mb-3 mb-md-4 border-0 shadow-sm">
                                    <div class="card-header bg-light py-2 py-md-3">
                                        <div
                                            class="d-flex justify-content-between align-items-center flex-column flex-md-row">
                                            <h6 class="mb-1 mb-md-0 fs-6">
                                                <i class="mdi mdi-chart-pie me-2 text-primary"></i>
                                                Select Quarters ({{ $plan->year - 1 }})
                                            </h6>
                                            <div class="mt-1 mt-md-0">
                                                <button type="button" class="btn btn-outline-primary btn-xs btn-sm"
                                                    onclick="selectAllQuarters()">
                                                    <i class="mdi mdi-select-all me-1"></i> Select All
                                                </button>
                                                <button type="button"
                                                    class="btn btn-outline-secondary btn-xs btn-sm ms-1"
                                                    onclick="clearAllQuarters()">
                                                    <i class="mdi mdi-select-off me-1"></i> Clear All
                                                </button>
                                            </div>
                                        </div>
                                        <small class="text-muted d-none d-md-block">Click on quarter cards to
                                            select/deselect multiple quarters for application year
                                            {{ $plan->year - 1 }}</small>
                                        <small class="text-muted d-md-none small">Tap quarter cards to select</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2 g-md-3" id="quartersContainer">
                                            <!-- Quarters will be populated here -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Months Section -->
                                <div class="card mb-3 mb-md-4 border-0 shadow-sm" id="monthsSection"
                                    style="display: none;">
                                    <div class="card-header bg-light py-2 py-md-3">
                                        <div
                                            class="d-flex justify-content-between align-items-center flex-column flex-md-row">
                                            <h6 class="mb-1 mb-md-0 fs-6">
                                                <i class="mdi mdi-calendar-month me-2 text-info"></i>
                                                Select Months ({{ $plan->year - 1 }})
                                            </h6>
                                            <div class="mt-1 mt-md-0">
                                                <button type="button" class="btn btn-outline-info btn-xs btn-sm"
                                                    onclick="selectAllMonths()">
                                                    <i class="mdi mdi-select-all me-1"></i> Select All
                                                </button>
                                                <button type="button"
                                                    class="btn btn-outline-secondary btn-xs btn-sm ms-1"
                                                    onclick="clearAllMonths()">
                                                    <i class="mdi mdi-select-off me-1"></i> Clear All
                                                </button>
                                            </div>
                                        </div>
                                        <small class="text-muted d-none d-md-block">Select months from the chosen
                                            quarters for application year {{ $plan->year - 1 }}</small>
                                        <small class="text-muted d-md-none small">Select months from chosen
                                            quarters</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2 g-md-3" id="monthsContainer">
                                            <!-- Months will be populated here -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Weeks & Fertilizer Distribution Section -->
                                <div class="card mb-3 mb-md-4 border-0 shadow-sm" id="distributionSection"
                                    style="display: none;">
                                    <div class="card-header bg-light py-2 py-md-3">
                                        <div
                                            class="d-flex justify-content-between align-items-center flex-column flex-md-row">
                                            <h6 class="mb-1 mb-md-0 fs-6">
                                                <i class="mdi mdi-calendar-week me-2 text-warning"></i>
                                                Distribute Fertilizers ({{ $plan->year - 1 }})
                                            </h6>
                                            <div class="mt-1 mt-md-0 text-center text-md-end">
                                                <small class="text-muted me-2 d-none d-md-inline">Total Distributed:
                                                </small>
                                                <span class="badge bg-primary fs-6" id="totalDistributedBadge">0
                                                    kg</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="distributionContainer">
                                            <!-- Distribution will be populated here -->
                                        </div>
                                        <div class="mt-2 mt-md-3 p-2 p-md-3 bg-light rounded">
                                            <div class="row align-items-center">
                                                <div class="col-md-8 mb-2 mb-md-0">
                                                    <small class="text-muted">
                                                        <i class="mdi mdi-information-outline me-1"></i>
                                                        Enter fertilizer quantities for each week in application year
                                                        {{ $plan->year - 1 }}. Quantities will be validated against
                                                        available amounts.
                                                    </small>
                                                </div>
                                                <div class="col-md-4 text-center text-md-end">
                                                    <button type="button"
                                                        class="btn btn-success btn-sm w-100 w-md-auto"
                                                        onclick="saveAllDistributions()">
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
                                        <div
                                            class="d-flex justify-content-between align-items-center flex-column flex-md-row">
                                            <h6 class="mb-1 mb-md-0 fs-6">
                                                <i class="mdi mdi-chart-bar me-2 text-success"></i>
                                                Fertilizer Distribution Summary (Application Year:
                                                {{ $plan->year - 1 }})
                                            </h6>
                                            <div class="selected-summary mt-1 mt-md-0 text-center text-md-end">
                                                <small class="text-muted me-2">Selected: </small>
                                                <span class="badge bg-primary" id="selectedQuartersBadge">0
                                                    quarters</span>
                                                <span class="badge bg-info mx-1" id="selectedMonthsBadge">0
                                                    months</span>
                                                <span class="badge bg-warning" id="selectedWeeksBadge">0
                                                    distributions</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="d-none d-md-table-cell">Year</th>
                                                        <th>Fertilizer</th>
                                                        <th class="d-none d-md-table-cell">Type</th>
                                                        <th class="d-none d-md-table-cell">Quarter</th>
                                                        <th>Month</th>
                                                        <th>Week</th>
                                                        <th class="d-none d-md-table-cell">Date Range</th>
                                                        <th>Quantity</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="summaryTable">
                                                    <tr>
                                                        <td colspan="9" class="text-center text-muted py-4">
                                                            <i class="mdi mdi-fertilizer me-2"></i>
                                                            No fertilizer distribution yet. Start by selecting
                                                            fertilizer types and quarters above.
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot class="table-secondary">
                                                    <tr>
                                                        <td colspan="7"
                                                            class="text-end fw-bold d-none d-md-table-cell">Total
                                                            Distributed:</td>
                                                        <td colspan="2" class="text-center fw-bold d-md-none">Total
                                                            Distributed:</td>
                                                        <td class="fw-bold text-success" id="summaryTotal">0 kg</td>
                                                        <td class="d-md-none"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden field to store structured fertilizer distribution data -->
                            <input type="hidden" name="fertilizer_distribution_details"
                                id="fertilizerDistributionDetails"
                                value="{{ json_encode($plan->fertilizer_distribution_details ?? []) }}">

                            <!-- Form Actions -->
                            <div class="form-actions mt-4 mt-md-5 pt-3 pt-md-4 border-top">
                                <div
                                    class="d-flex justify-content-between align-items-center flex-column flex-md-row gap-3">
                                    <a href="{{ route('sector.crop-plans.index') }}"
                                        class="btn btn-outline-secondary btn-lg w-100 w-md-auto order-2 order-md-1">
                                        <i class="mdi mdi-arrow-left me-2"></i>
                                        Back to Plans
                                    </a>
                                    <div class="d-flex gap-2 gap-md-3 w-100 w-md-auto order-1 order-md-2">
                                        <a href="{{ route('sector.crop-plans.index') }}"
                                            class="btn btn-outline-danger btn-lg flex-fill">
                                            <i class="mdi mdi-cancel me-2"></i>
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-success btn-lg flex-fill"
                                            id="submitBtn">
                                            <i class="mdi mdi-content-save-outline me-2"></i>
                                            Update Fertilizer Plan
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
        const EC_MONTHS = [{
                id: 1,
                name: "Meskerem",
                days: 30
            },
            {
                id: 2,
                name: "Tikimt",
                days: 30
            },
            {
                id: 3,
                name: "Hidar",
                days: 30
            },
            {
                id: 4,
                name: "Tahsas",
                days: 30
            },
            {
                id: 5,
                name: "Tir",
                days: 30
            },
            {
                id: 6,
                name: "Yekatit",
                days: 30
            },
            {
                id: 7,
                name: "Megabit",
                days: 30
            },
            {
                id: 8,
                name: "Miyazia",
                days: 30
            },
            {
                id: 9,
                name: "Ginbot",
                days: 30
            },
            {
                id: 10,
                name: "Sene",
                days: 30
            },
            {
                id: 11,
                name: "Hamle",
                days: 30
            },
            {
                id: 12,
                name: "Nehase",
                days: 30
            },
            {
                id: 13,
                name: "Pagumen",
                days: 5
            }
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
                                unit: "{{ $fertDetail['unit'] ?? 'kg' }}",
                                total_quantity: {{ $totalQuantity }}
                            },
                        @endif
                    @endforeach
                ],
            @endforeach
        };

        $(document).ready(function() {
            console.log('Document ready - initializing fertilizer distribution for application year:',
                applicationYear);
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
                    card.addClass('card-selected');
                } else {
                    selectedFertilizerTypes.delete(typeId);
                    card.removeClass('card-selected');
                }

                renderQuarters();
                updateBadges();
            });

            // Form submission validation
            $('#fertilizerPlanForm').on('submit', function(e) {
                console.log('Form submitted, validating...');
                if (!validateForm()) {
                    e.preventDefault();
                    showAlert('Please fix the validation errors before submitting.', 'error');
                } else {
                    console.log('Form validation passed, submitting...');
                    saveFertilizerJSON();
                }
            });

            renderQuarters();
            loadExistingFertilizerDistribution();
            updateTotalDistributedBadge();

            // Mobile touch improvements
            improveMobileTouch();
        });

        /* -------------------------------
           MOBILE TOUCH IMPROVEMENTS
        ------------------------------- */
        function improveMobileTouch() {
            // Prevent zoom on input focus for better mobile experience
            $('input, select, textarea').on('focus', function() {
                $('meta[name=viewport]').attr('content',
                    'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0');
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
                    '<small class="text-danger"><i class="mdi mdi-alert-circle me-1"></i>Value cannot be negative</small>'
                    );
                return false;
            }

            // Calculate remaining quantity excluding current row
            const distributedWithoutCurrent = fertilizerSummaryRows
                .filter(row => row.fertilizer_id == fertId &&
                    !(row.month === $(input).data('month-name') &&
                        row.week === `Week ${$(input).data('week')}`))
                .reduce((total, row) => total + row.quantity, 0);

            const totalAvailable = getFertilizerTotal(fertId);
            const maxAllowed = Math.max(0, totalAvailable - distributedWithoutCurrent);

            if (value > maxAllowed) {
                $(input).addClass('is-invalid');
                const fertilizer = getFertilizerById(fertId);
                feedback.html(
                    `<small class="text-danger"><i class="mdi mdi-alert-circle me-1"></i>Exceeds available ${fertilizer.unit} (max: ${maxAllowed.toFixed(2)})</small>`
                    );
                return false;
            }

            if (value > 0) {
                $(input).addClass('is-valid');
                feedback.html('<small class="text-success"><i class="mdi mdi-check-circle me-1"></i>Valid</small>');
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
                        `Total distributed ${group.fertilizer.name} (${group.totalDistributed.toFixed(2)} ${group.fertilizer.unit}) exceeds available quantity (${totalAvailable.toFixed(2)} ${group.fertilizer.unit})`
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
                name: 'Unknown',
                unit: 'kg'
            };
        }

        function getFertilizerTypeName(typeId) {
            const type = {!! json_encode($fertilizerTypes->keyBy('id')) !!}[typeId];
            return type ? type.name : 'Unknown Type';
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
                const monthNames = EC_QUARTERS[q].map(m => EC_MONTHS[m - 1].name).join(", ");

                $("#quartersContainer").append(`
                <div class="col-6 col-md-3 mb-2 mb-md-3">
                    <div class="card quarter-card p-2 p-md-3 text-center ${isSelected ? 'card-selected' : ''}" data-q="${q}">
                        <div class="form-check mb-1 mb-md-2">
                            <input class="form-check-input quarter-checkbox" type="checkbox"
                                   id="quarter${q}" ${isSelected ? 'checked' : ''}>
                            <label class="form-check-label fw-bold fs-6 fs-md-5" for="quarter${q}">
                                ${quarterName}
                            </label>
                        </div>
                        <small class="text-muted d-none d-md-block">${monthNames}</small>
                        <small class="text-muted d-md-none">${EC_QUARTERS[q].length} months</small>
                        <div class="mt-1 mt-md-2">
                            <small class="text-success">${quarterQuantity.toFixed(2)} kg</small>
                        </div>
                        <div class="mt-1">
                            <small class="text-info">Year: ${applicationYear}</small>
                        </div>
                    </div>
                </div>
            `);
            });

            // Add event listeners for quarter checkboxes
            $('.quarter-checkbox').on('change', function() {
                const quarterNum = parseInt($(this).closest('.quarter-card').data('q'));
                const isChecked = $(this).is(':checked');

                if (isChecked) {
                    selectedQuarters.add(quarterNum);
                    $(this).closest('.quarter-card').addClass('card-selected');
                } else {
                    selectedQuarters.delete(quarterNum);
                    $(this).closest('.quarter-card').removeClass('card-selected');
                    // Remove months from this quarter from selected months
                    const quarterMonths = EC_QUARTERS[quarterNum];
                    quarterMonths.forEach(month => {
                        selectedMonths.delete(month);
                    });
                }

                renderMonths();
                updateBadges();
            });

            $(".quarter-card").click(function(e) {
                if (!$(e.target).is('input') && !$(e.target).is('label')) {
                    const checkbox = $(this).find('.quarter-checkbox');
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    checkbox.trigger('change');
                }
            });
        }

        function selectAllQuarters() {
            Object.keys(EC_QUARTERS).forEach(q => {
                selectedQuarters.add(parseInt(q));
            });
            renderQuarters();
            renderMonths();
            updateBadges();
            showAlert('All quarters selected for application year ' + applicationYear, 'success');
        }

        function clearAllQuarters() {
            selectedQuarters.clear();
            selectedMonths.clear();
            renderQuarters();
            renderMonths();
            $('#distributionSection').hide();
            updateBadges();
            showAlert('All quarters cleared', 'info');
        }

        /* -------------------------------
           MONTHS MANAGEMENT
        ------------------------------- */
        function renderMonths() {
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

            allMonths.forEach(monthNum => {
                const month = EC_MONTHS[monthNum - 1];
                const monthQuantity = calculateMonthDistributedQuantity(monthNum);
                const isSelected = selectedMonths.has(monthNum);

                $("#monthsContainer").append(`
                <div class="col-6 col-md-4 mb-2 mb-md-3">
                    <div class="card month-card p-2 p-md-3 text-center ${isSelected ? 'card-selected' : ''}" data-month="${monthNum}">
                        <div class="form-check mb-1 mb-md-2">
                            <input class="form-check-input month-checkbox" type="checkbox"
                                   id="month${monthNum}" ${isSelected ? 'checked' : ''}>
                            <label class="form-check-label fw-bold fs-6" for="month${monthNum}">
                                ${month.name}
                            </label>
                        </div>
                        <small class="text-muted">Q${findQuarter(monthNum)}</small>
                        <div class="mt-1 mt-md-2">
                            <small class="text-success">${monthQuantity.toFixed(2)} kg</small>
                        </div>
                        <div class="mt-1">
                            <small class="text-info">Year: ${applicationYear}</small>
                        </div>
                    </div>
                </div>
            `);
            });

            // Add event listeners for month checkboxes
            $('.month-checkbox').on('change', function() {
                const monthNum = parseInt($(this).closest('.month-card').data('month'));
                const isChecked = $(this).is(':checked');

                if (isChecked) {
                    selectedMonths.add(monthNum);
                    $(this).closest('.month-card').addClass('card-selected');
                } else {
                    selectedMonths.delete(monthNum);
                    $(this).closest('.month-card').removeClass('card-selected');
                }

                renderDistribution();
                updateBadges();
            });

            $(".month-card").click(function(e) {
                if (!$(e.target).is('input') && !$(e.target).is('label')) {
                    const checkbox = $(this).find('.month-checkbox');
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    checkbox.trigger('change');
                }
            });
        }

        function selectAllMonths() {
            if (selectedQuarters.size === 0) {
                showAlert('Please select quarters first', 'warning');
                return;
            }

            // Get all months from selected quarters
            let allMonths = [];
            selectedQuarters.forEach(quarter => {
                allMonths = allMonths.concat(EC_QUARTERS[quarter]);
            });

            allMonths.forEach(month => {
                selectedMonths.add(month);
            });

            renderMonths();
            renderDistribution();
            updateBadges();
            showAlert('All months in selected quarters selected for application year ' + applicationYear, 'success');
        }

        function clearAllMonths() {
            selectedMonths.clear();
            renderMonths();
            $('#distributionSection').hide();
            updateBadges();
            showAlert('All months cleared', 'info');
        }

        /* -------------------------------
           DISTRIBUTION MANAGEMENT
        ------------------------------- */
        function renderDistribution() {
            $("#distributionContainer").empty();

            if (selectedMonths.size === 0 || selectedFertilizerTypes.size === 0) {
                $("#distributionSection").hide();
                return;
            }

            $("#distributionSection").show();

            const monthsArray = Array.from(selectedMonths).sort((a, b) => a - b);

            monthsArray.forEach(monthNum => {
                const month = EC_MONTHS[monthNum - 1];
                const weeks = getWeeksForMonth(monthNum);
                const monthQuantity = calculateMonthDistributedQuantity(monthNum);

                $("#distributionContainer").append(`
                <div class="month-distribution-section mb-4 p-3 border rounded bg-light">
                    <div class="d-flex justify-content-between align-items-center flex-column flex-md-row mb-2 mb-md-3">
                        <h6 class="mb-1 mb-md-0 text-primary fs-6">
                            <i class="mdi mdi-calendar-month me-2"></i>
                            ${month.name} (Q${findQuarter(monthNum)}) - ${applicationYear}
                        </h6>
                        <div class="text-center text-md-end mt-1 mt-md-0">
                            <span class="badge bg-info me-1 me-md-2 mb-1 mb-md-0">${monthQuantity.toFixed(2)} kg</span>
                            <button type="button" class="btn btn-outline-primary btn-xs btn-sm me-1" onclick="distributeEqually(${monthNum})">
                                <i class="mdi mdi-autorenew me-1"></i> Equal
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-xs btn-sm" onclick="clearMonthDistribution(${monthNum})">
                                <i class="mdi mdi-refresh me-1"></i> Clear
                            </button>
                        </div>
                    </div>
                    
                    <div class="weeks-distribution">
                        ${weeks.map(week => `
                                <div class="week-distribution mb-3 p-3 border rounded bg-white">
                                    <div class="row align-items-center mb-2">
                                        <div class="col-12 col-md-3 mb-2 mb-md-0">
                                            <strong class="fs-6">Week ${week.weekNumber}</strong><br>
                                            <small class="text-muted">Days ${week.start}-${week.end}</small>
                                            <br>
                                            <small class="text-info">Year: ${applicationYear}</small>
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <div class="fertilizer-inputs">
                                                ${Array.from(selectedFertilizerTypes).map(typeId => `
                                                <div class="fertilizer-input-group mb-2">
                                                    ${renderFertilizerInputs(typeId, monthNum, week.weekNumber)}
                                                </div>
                                            `).join('')}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                    </div>
                </div>
            `);
            });

            updateTotalDistributedBadge();
            enhanceFertilizerInputValidation();
        }

        function renderFertilizerInputs(typeId, monthNum, weekNumber) {
            const fertilizers = FERTILIZERS[typeId] || [];
            const month = EC_MONTHS[monthNum - 1];
            const fertilizerType = getFertilizerTypeName(typeId);

            return fertilizers.map(fert => {
                const existing = fertilizerSummaryRows.find(row =>
                    row.month === month.name &&
                    row.week === `Week ${weekNumber}` &&
                    row.fertilizer_id == fert.id
                );

                const distributed = getCurrentDistributedQuantity(fert.id);
                const remaining = getRemainingQuantity(fert.id);
                const total = getFertilizerTotal(fert.id);

                // Calculate max allowed for this input
                const distributedWithoutCurrent = fertilizerSummaryRows
                    .filter(row => row.fertilizer_id == fert.id &&
                        !(row.month === month.name &&
                            row.week === `Week ${weekNumber}`))
                    .reduce((total, row) => total + row.quantity, 0);

                const maxAllowed = Math.max(0, total - distributedWithoutCurrent);

                return `
                <div class="fertilizer-input-item mb-2 p-2 border rounded bg-light">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-md-5">
                            <small class="fw-bold d-block">${fert.name}</small>
                            <small class="text-muted">${fertilizerType}</small>
                            <div class="quantity-info mt-1">
                                <small class="text-success">Used: ${distributed.toFixed(2)}/${total.toFixed(2)} ${fert.unit}</small><br>
                                <small class="${remaining === 0 ? 'text-danger' : 'text-warning'}">Available: ${remaining.toFixed(2)} ${fert.unit}</small>
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
                            ${remaining === 0 && !existing?.quantity ? '<small class="text-danger">No quantity available</small>' : ''}
                        </div>
                        <div class="col-5 col-md-3 text-center">
                            ${existing && existing.quantity > 0 ?
                            '<span class="badge bg-success"><i class="mdi mdi-check me-1"></i>Saved</span>' :
                            '<span class="badge bg-secondary"><i class="mdi mdi-clock me-1"></i>Pending</span>'
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
                d.week === `Week ${weekNumber}` &&
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
                    week: `Week ${weekNumber}`,
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
                        d.week === `Week ${weekNumber}` &&
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
                showAlert('No fertilizers selected for distribution', 'warning');
                return;
            }

            const weeks = getWeeksForMonth(monthNum);
            if (weeks.length === 0) return;

            selectedFertilizers.forEach(({
                typeId,
                fert
            }) => {
                const remaining = getRemainingQuantity(fert.id);
                if (remaining <= 0) {
                    showAlert(`No remaining ${fert.name} available for distribution`, 'warning');
                    return;
                }

                const equalQuantity = remaining / weeks.length;

                weeks.forEach(week => {
                    const input = $(
                        `.fertilizer-quantity[data-month="${monthNum}"][data-week="${week.weekNumber}"][data-fert-id="${fert.id}"]`
                        );
                    if (input.length) {
                        input.val(equalQuantity.toFixed(2));
                        updateFertilizerDistribution(month.name, week.weekNumber, typeId, fert.id, fert
                            .name, equalQuantity, fert.unit);
                    }
                });
            });

            updateSummaryTable();
            updateTotalDistributedBadge();
            enhanceFertilizerInputValidation();
            showAlert(`Distributed remaining quantities equally across all weeks in ${month.name}`, 'success');
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

            showAlert(`All distributions cleared for ${month.name}`, 'info');
        }

        function saveAllDistributions() {
            const validation = validateAllFertilizerDistributions();

            if (!validation.isValid) {
                showAlert('Please fix validation errors before saving', 'error');
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
            showAlert('All fertilizer distributions saved successfully for application year ' + applicationYear + '!',
                'success');
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
                        <i class="mdi mdi-fertilizer me-2"></i>
                        No fertilizer distribution yet. Start by selecting fertilizer types and quarters above.
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
                                <i class="mdi mdi-fertilizer me-2"></i>
                                ${group.fertilizer.name} - Total: ${group.total.toFixed(2)} ${group.fertilizer.unit}
                            </td>
                            <td colspan="4" class="text-end">
                                <small class="text-muted">
                                    Available: ${getFertilizerTotal(group.fertilizer.id)} ${group.fertilizer.unit} | 
                                    Remaining: ${getRemainingQuantity(group.fertilizer.id).toFixed(2)} ${group.fertilizer.unit}
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
                            <td class="fw-bold text-success">${row.quantity.toFixed(2)} ${row.unit}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-danger" onclick="removeFertilizerRow(${fertilizerSummaryRows.findIndex(r => 
                                    r.month === row.month && 
                                    r.week === row.week && 
                                    r.fertilizer_id === row.fertilizer_id
                                )})">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                    });
                });
            }

            $("#summaryTotal").text(total.toFixed(2) + " kg");
        }

        function removeFertilizerRow(i) {
            fertilizerSummaryRows.splice(i, 1);
            updateSummaryTable();
            saveFertilizerJSON();
            updateTotalDistributedBadge();
            renderDistribution(); // Refresh distribution inputs
            showAlert('Fertilizer distribution removed successfully!', 'info');
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

        function calculateQuarterDistributedQuantity(quarter) {
            const quarterName = `Q${quarter}`;
            return fertilizerSummaryRows
                .filter(row => row.quarter === quarterName)
                .reduce((total, row) => total + row.quantity, 0);
        }

        function calculateMonthDistributedQuantity(month) {
            const monthName = EC_MONTHS[month - 1].name;
            return fertilizerSummaryRows
                .filter(row => row.month === monthName)
                .reduce((total, row) => total + row.quantity, 0);
        }

        function updateTotalDistributedBadge() {
            const totalDistributed = calculateTotalDistributedQuantity();
            $('#totalDistributedBadge').text(totalDistributed.toFixed(2) + ' kg');
        }

        function updateBadges() {
            $("#selectedQuartersBadge").text(selectedQuarters.size + " quarters");
            $("#selectedMonthsBadge").text(selectedMonths.size + " months");
            $("#selectedWeeksBadge").text(fertilizerSummaryRows.length + " distributions");
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
                        unit: row.unit || 'kg'
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
                        unit: row.unit || 'kg'
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
                        $(this).closest('.fertilizer-type-card').addClass('card-selected');
                    }
                });

                updateSummaryTable();
                updateBadges();

                if (selectedQuarters.size > 0) {
                    renderQuarters();
                    renderMonths();
                }

                if (selectedMonths.size > 0 && selectedFertilizerTypes.size > 0) {
                    renderDistribution();
                }

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

        function validateForm() {
            let isValid = true;
            const errors = [];

            // Check if fertilizer types are selected
            if (selectedFertilizerTypes.size === 0) {
                errors.push('Please select at least one fertilizer type');
                isValid = false;
            }

            // Enhanced distribution validation
            const distributedQuantity = calculateTotalDistributedQuantity();

            if (distributedQuantity === 0) {
                const proceed = confirm('No fertilizer distribution has been planned for application year ' +
                    applicationYear + '. Do you want to proceed without distribution?');
                if (!proceed) {
                    errors.push('Please add fertilizer distribution or confirm to proceed without it');
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
                    errors.push('Some fertilizer distributions have validation errors');
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
            } [type] || 'alert-info';

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

            document.getElementById(toastId).addEventListener('hidden.bs.toast', function() {
                this.remove();
            });
        }
    </script>

    <style>
        .fertilizer-plan-container {
            background: linear-gradient(135deg, #f0f9f0 0%, #e6f7e6 100%);
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

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
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
            border-left: 4px solid #66bb6a;
            min-height: 80px;
        }

        /* Fertilizer Type Card Styles */
        .fertilizer-type-card {
            transition: all 0.3s ease;
            border: 2px solid #e9ecef;
            cursor: pointer;
            min-height: 120px;
        }

        .fertilizer-type-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-color: #66bb6a;
        }

        .fertilizer-type-card.card-selected {
            border-color: #66bb6a !important;
            background-color: #f0f9f0 !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 187, 106, 0.15) !important;
        }

        .fertilizer-type-checkbox {
            transform: scale(1.2);
        }

        .fertilizer-list {
            max-height: 80px;
            overflow-y: auto;
        }

        /* Quarter and Month Card Styles */
        .quarter-card,
        .month-card {
            transition: all 0.3s ease;
            border: 2px solid #e9ecef;
            cursor: pointer;
            min-height: 120px;
        }

        .quarter-card:hover,
        .month-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-color: #007bff;
        }

        .card-selected {
            border-color: #007bff !important;
            background-color: #f8f9ff !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.15) !important;
        }

        .quarter-checkbox,
        .month-checkbox {
            transform: scale(1.2);
        }

        /* Distribution Section Styles */
        .month-distribution-section {
            border-left: 4px solid #66bb6a !important;
        }

        .week-distribution {
            border-left: 3px solid #4caf50;
        }

        .fertilizer-input-item {
            border-left: 3px solid #81c784;
        }

        .fertilizer-quantity:focus {
            border-color: #4caf50;
            box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
        }

        .quantity-info {
            font-size: 0.75rem;
        }

        .quantity-info small {
            display: block;
            line-height: 1.2;
        }

        .btn-xs {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        /* Validation styles */
        .validation-feedback {
            min-height: 20px;
        }

        .fertilizer-quantity.is-valid {
            border-color: #28a745;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
        }

        .fertilizer-quantity.is-invalid {
            border-color: #dc3545;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6.4.4.4-.4'/%3e%3cpath d='M6 7v2.5'/%3e%3c/svg%3e");
        }

        .table-primary td {
            background-color: #e3f2fd !important;
            border-bottom: 2px solid #bbdefb;
        }

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

            .fertilizer-distribution-container {
                padding: 10px;
            }

            .info-card {
                min-height: 70px;
            }

            .fertilizer-type-card,
            .quarter-card,
            .month-card {
                min-height: 100px;
            }

            .btn-lg {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }

            .fertilizer-input-item {
                padding: 0.75rem !important;
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

            .fertilizer-distribution-container {
                padding: 15px;
            }

            .fertilizer-type-card .col-lg-4,
            .quarter-card .col-md-3,
            .month-card .col-md-4 {
                width: 100%;
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

            .row.g-2>[class*="col-"] {
                padding: 5px;
            }

            .week-distribution {
                padding: 1rem !important;
            }
        }
    </style>
</x-layout>
