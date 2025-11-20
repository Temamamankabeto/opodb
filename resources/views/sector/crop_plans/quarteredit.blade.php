<x-layout>
    <div class="container-fluid py-4 crop-plan-edit-container">
        <!-- Header Section -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <div class="page-header text-center">
                    <div class="header-icon mb-3">
                        <i class="mdi mdi-sprout-edit display-4 text-warning"></i>
                    </div>
                     
                    <p class="lead text-muted">  {{ Auth::user()->kebele->name }} Kebele</p>
                    <div class="header-divider">
                        <div class="divider-line"></div>
                        <div class="divider-icon">
                            <i class="mdi mdi-leaf"></i>
                        </div>
                        <div class="divider-line"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="row justify-content-center mb-4">
                <div class="col-lg-10">
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

        @if ($errors->has('crop_id'))
            <div class="row justify-content-center mb-4">
                <div class="col-lg-10">
                    <div class="alert alert-warning alert-dismissible fade show custom-alert" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-alert-circle-outline me-2 fs-5"></i>
                            <div class="flex-grow-1">
                                <h6 class="alert-heading mb-2">Duplicate Crop Plan Detected</h6>
                                <p class="mb-0">{!! $errors->first('crop_id') !!}</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card form-card border-0 shadow-lg">
                    <div class="card-header bg-gradient-warning text-white py-4">
                        <h4 class="mb-0">
                            <i class="mdi mdi-pencil-outline me-2"></i>
                            Edit Crop Plan Details
                        </h4>
                    </div>
                    <div class="card-body p-5">
                        <form action="{{ route('sector.crop-plans.update', $plan->id) }}" method="POST" id="cropPlanForm">
                            @csrf
                            @method('PUT')

                            <!-- Uneditable Header Section -->
                            <div class="uneditable-header-section mb-5">
                                <div class="section-header mb-4">
                                    <h5 class="section-title">
                                        <i class="mdi mdi-lock-outline me-2 text-secondary"></i>
                                        Plan Summary (Read Only)
                                    </h5>
                                    <div class="section-divider"></div>
                                </div>

                                <div class="row g-4">
                                    {{-- Year --}}
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="number" value="{{ $plan->year }}" class="form-control form-control-lg bg-light" readonly>
                                            <label class="form-label">
                                                <i class="mdi mdi-calendar me-1 text-muted"></i>
                                                Planning Year
                                            </label>
                                        </div>
                                    </div>

                                    {{-- Crop Type --}}
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="text" value="{{ $plan->cropType->name ?? 'N/A' }}" class="form-control form-control-lg bg-light" readonly>
                                            <label class="form-label">
                                                <i class="mdi mdi-seed-outline me-1 text-muted"></i>
                                                Crop Type
                                            </label>
                                        </div>
                                    </div>

                                    {{-- Crop --}}
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="text" value="{{ $plan->crop->name ?? 'N/A' }}" class="form-control form-control-lg bg-light" readonly>
                                            <label class="form-label">
                                                <i class="mdi mdi-sprout me-1 text-muted"></i>
                                                Crop Variety
                                            </label>
                                        </div>
                                    </div>

                                    {{-- Season --}}
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="text" value="{{ $plan->season }}" class="form-control form-control-lg bg-light" readonly>
                                            <label class="form-label">
                                                <i class="mdi mdi-weather-sunny me-1 text-muted"></i>
                                                Growing Season
                                            </label>
                                        </div>
                                    </div>

                                    {{-- Planning Area --}}
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="number" step="0.01" value="{{ $plan->area_to_plant }}" class="form-control form-control-lg bg-light" readonly>
                                            <label class="form-label">
                                                <i class="mdi mdi-earth me-1 text-muted"></i>
                                                Planning Area (ha)
                                            </label>
                                        </div>
                                    </div>

                                    {{-- Seed Required --}}
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="number" step="0.01" value="{{ $plan->seed_required }}" class="form-control form-control-lg bg-light" readonly>
                                            <label class="form-label">
                                                <i class="mdi mdi-seed me-1 text-muted"></i>
                                                Seed Required (kg)
                                            </label>
                                        </div>
                                    </div>

                                    {{-- Expected Yield --}}
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="number" step="0.01" value="{{ $plan->expected_yield }}" class="form-control form-control-lg bg-light" readonly>
                                            <label class="form-label">
                                                <i class="mdi mdi-scale me-1 text-muted"></i>
                                                Expected Yield (qt)
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Summary Cards -->
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="summary-card card border-0 bg-primary bg-opacity-10">
                                            <div class="card-body text-center">
                                                <h4 class="text-primary mb-1">{{ $plan->area_to_plant }} ha</h4>
                                                <p class="text-muted mb-0">Total Planning Area</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="summary-card card border-0 bg-success bg-opacity-10">
                                            <div class="card-body text-center">
                                                <h4 class="text-success mb-1">{{ $plan->expected_yield }} qt</h4>
                                                <p class="text-muted mb-0">Total Expected Yield</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Planning Timeline Section -->
                            <div class="section-header mt-5 mb-4">
                                <h5 class="section-title">
                                    <i class="mdi mdi-timeline-clock me-2 text-info"></i>
                                    Planning Timeline Distribution (Ethiopian Calendar)
                                </h5>
                                <div class="section-divider"></div>
                            </div>

                            <div class="planning-timeline-section">
                                <div class="alert alert-info border-0 mb-4">
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-calendar-clock me-2 fs-5"></i>
                                        <div>
                                            <strong>Planning Distribution:</strong> Distribute your planning area and expected yield across quarters, months, and weeks. 
                                            Total cannot exceed the plan summary values above.
                                        </div>
                                    </div>
                                </div>

                                <!-- Quarterly Planning -->
                                <div class="quarterly-planning mb-5">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="text-primary mb-0">
                                            <i class="mdi mdi-chart-pie me-2"></i>
                                            Quarterly Distribution
                                        </h6>
                                        <div class="allocation-summary">
                                            <span class="badge bg-primary fs-6">
                                                Remaining: <span id="quarterly_remaining_area">{{ $plan->area_to_plant }}</span> ha / 
                                                <span id="quarterly_remaining_yield">{{ $plan->expected_yield }}</span> qt
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="row g-3" id="quarterly_distribution">
                                        <!-- Quarters will be populated dynamically -->
                                    </div>
                                </div>

                                <!-- Monthly Planning Section -->
                                <div class="monthly-planning-section mb-5">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="text-success mb-0">
                                            <i class="mdi mdi-calendar-month me-2"></i>
                                            Monthly Distribution
                                            <small class="text-muted ms-2" id="expanded_quarters_info"></small>
                                        </h6>
                                        <div class="allocation-summary">
                                            <span class="badge bg-success fs-6">
                                                Remaining: <span id="monthly_remaining_area">0</span> ha / 
                                                <span id="monthly_remaining_yield">0</span> qt
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row g-3" id="monthly_distribution">
                                        <!-- Months from all expanded quarters will be displayed here -->
                                    </div>
                                </div>

                                <!-- Weekly Planning Section -->
                                <div class="weekly-planning-section">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="text-warning mb-0">
                                            <i class="mdi mdi-calendar-week me-2"></i>
                                            Weekly Distribution
                                            <small class="text-muted ms-2" id="expanded_months_info"></small>
                                        </h6>
                                        <div class="allocation-summary">
                                            <span class="badge bg-warning fs-6">
                                                Remaining: <span id="weekly_remaining_area">0</span> ha / 
                                                <span id="weekly_remaining_yield">0</span> qt
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row g-3" id="weekly_distribution">
                                        <!-- Weeks from all expanded months will be displayed here -->
                                    </div>
                                </div>
                            </div>

                            

                            <!-- Hidden fields for planning data -->
                            <input type="hidden" name="plandivision_details" id="plandivision_details">
                            <input type="hidden" name="yield_timeline_details" id="yield_timeline_details">

                            <!-- Form Actions -->
                            <div class="form-actions mt-5 pt-4 border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('sector.crop-plans.index') }}" class="btn btn-outline-secondary btn-lg">
                                        <i class="mdi mdi-arrow-left me-2"></i>
                                        Back to Plans
                                    </a>
                                    <div class="d-flex gap-3">
                                        <a href="{{ route('sector.crop-plans.index') }}" class="btn btn-outline-danger btn-lg">
                                            <i class="mdi mdi-cancel me-2"></i>
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-warning btn-lg px-5" id="submitBtn">
                                            <i class="mdi mdi-content-save-outline me-2"></i>
                                            Update Plan
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

    {{-- JavaScript Section --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const totalPlanningArea = {{ $plan->area_to_plant }};
        const totalExpectedYield = {{ $plan->expected_yield }};
        
        const ethiopianMonths = [
            'Adoolessa', 'Hagayya', 'Pagume', 'Fulbaana', 'Onkololeessa', 'Sadaasa', 
            'Muddee', 'Amajjii', 'Gurraandhala', 'Bitootessa', 'Ebla', 'Caamsaa', 'Woxabajjii'
        ];
        
        const ethiopianQuarters = [
            { 
                name: 'Q1', 
                months: ['Adoolessa', 'Hagayya',, 'Pagume', 'Fulbaana'],
                checked: false,
                expanded: false
            },
            { 
                name: 'Q2', 
                months: ['Onkololeessa', 'Sadaasa', 'Muddee'],
                checked: false,
                expanded: false
            },
            { 
                name: 'Q3', 
                months: ['Amajjii', 'Gurraandhala', 'Bitootessa'],
                checked: false,
                expanded: false
            },
            { 
                name: 'Q4', 
                months: ['Ebla', 'Caamsaa', 'Woxabajjii'],
                checked: false,
                expanded: false
            }
        ];

        let expandedMonths = {}; // Track expanded months: { monthName: { expanded: boolean, quarter: string } }

        let planningData = {
            quarters: {},
            months: {},
            weeks: {}
        };

        let yieldData = {
            quarters: {},
            months: {},
            weeks: {}
        };

        // Initialize form
        $(document).ready(function() {
            initializeQuarterlyPlanning();
            updateQuarterlyRemaining();
        });

        // Initialize Quarterly Planning
        function initializeQuarterlyPlanning() {
            const container = $('#quarterly_distribution');
            container.empty();
            
            const existingData = @json($plan->plandivision_details ?? []);
            const existingYieldData = @json($plan->yield_timeline_details ?? []);
            
            ethiopianQuarters.forEach((quarter, index) => {
                const quarterPlanData = existingData.quarters ? existingData.quarters[quarter.name] : { area: 0, checked: false };
                const quarterYieldData = existingYieldData.quarters ? existingYieldData.quarters[quarter.name] : { yield: 0 };
                
                const quarterId = `quarter_${index + 1}`;
                const isChecked = quarterPlanData.checked || false;
                const isExpanded = quarter.expanded || false;
                
                const quarterHtml = `
                    <div class="col-md-6">
                        <div class="planning-card card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input quarter-checkbox" type="checkbox" 
                                               id="check_${quarterId}" 
                                               data-quarter="${quarter.name}"
                                               ${isChecked ? 'checked' : ''}>
                                        <label class="form-check-label fw-bold text-primary" for="check_${quarterId}">
                                            ${quarter.name}
                                        </label>
                                    </div>
                                    <button type="button" class="btn btn-sm ${isExpanded ? 'btn-primary' : 'btn-outline-primary'} expand-months-btn" 
                                            data-quarter="${quarter.name}" 
                                            data-bs-toggle="tooltip" title="${isExpanded ? 'Collapse' : 'Expand'} months">
                                        <i class="mdi ${isExpanded ? 'mdi-arrow-collapse-vertical' : 'mdi-arrow-expand-vertical'}"></i>
                                    </button>
                                </div>
                                
                                <p class="text-muted small mb-3">${quarter.months.join(', ')}</p>
                                
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="form-floating">
                                            <input type="number" step="0.01" 
                                                   class="form-control quarterly-area-input ${isChecked ? '' : 'bg-light'}" 
                                                   id="${quarterId}_area" 
                                                   value="${quarterPlanData.area}"
                                                   data-quarter="${quarter.name}"
                                                   data-type="area"
                                                   placeholder="Area"
                                                   ${isChecked ? '' : 'readonly'}>
                                            <label for="${quarterId}_area">Area (ha)</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-floating">
                                            <input type="number" step="0.01" 
                                                   class="form-control quarterly-yield-input ${isChecked ? '' : 'bg-light'}" 
                                                   id="${quarterId}_yield" 
                                                   value="${quarterYieldData.yield}"
                                                   data-quarter="${quarter.name}"
                                                   data-type="yield"
                                                   placeholder="Yield"
                                                   ${isChecked ? '' : 'readonly'}>
                                            <label for="${quarterId}_yield">Yield (qt)</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="allocation-feedback mt-2">
                                    <small class="text-muted allocation-text" id="allocation_${quarter.name}">
                                        Available: ${totalPlanningArea.toFixed(2)} ha / ${totalExpectedYield.toFixed(2)} qt
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.append(quarterHtml);
                
                // Store initial state
                ethiopianQuarters[index].checked = isChecked;
                ethiopianQuarters[index].expanded = isExpanded;
                
                if (isChecked) {
                    planningData.quarters[quarter.name] = { area: quarterPlanData.area, checked: true };
                    yieldData.quarters[quarter.name] = { yield: quarterYieldData.yield };
                }
                
                // Initialize expanded months tracking
                quarter.months.forEach(month => {
                    expandedMonths[month] = { expanded: false, quarter: quarter.name };
                });
            });
            
            // Add event listeners
            $('.quarter-checkbox').on('change', handleQuarterCheckboxChange);
            $('.quarterly-area-input, .quarterly-yield-input').on('input', handleQuarterInputChange);
            $('.expand-months-btn').on('click', handleExpandMonths);
            
            updateQuarterlyRemaining();
            updateMonthlySection(); // Initialize monthly section
        }

        // Handle Quarter Checkbox Change
        function handleQuarterCheckboxChange() {
            const checkbox = $(this);
            const quarter = checkbox.data('quarter');
            const isChecked = checkbox.is(':checked');
            const quarterIndex = ethiopianQuarters.findIndex(q => q.name === quarter);
            
            // Update quarter state
            ethiopianQuarters[quarterIndex].checked = isChecked;
            
            // Toggle input fields
            const areaInput = $(`input[data-quarter="${quarter}"][data-type="area"]`);
            const yieldInput = $(`input[data-quarter="${quarter}"][data-type="yield"]`);
            const expandBtn = $(`button[data-quarter="${quarter}"]`);
            
            if (isChecked) {
                areaInput.removeClass('bg-light').prop('readonly', false);
                yieldInput.removeClass('bg-light').prop('readonly', false);
                
                // Initialize with zero if not set
                if (!areaInput.val()) areaInput.val(0);
                if (!yieldInput.val()) yieldInput.val(0);
                
                planningData.quarters[quarter] = { area: parseFloat(areaInput.val()) || 0, checked: true };
                yieldData.quarters[quarter] = { yield: parseFloat(yieldInput.val()) || 0 };
            } else {
                areaInput.addClass('bg-light').prop('readonly', true).val(0);
                yieldInput.addClass('bg-light').prop('readonly', true).val(0);
                
                delete planningData.quarters[quarter];
                delete yieldData.quarters[quarter];
                
                // Also remove monthly and weekly data for this quarter
                const quarterObj = ethiopianQuarters.find(q => q.name === quarter);
                quarterObj.months.forEach(month => {
                    delete planningData.months[month];
                    delete yieldData.months[month];
                    // Remove weekly data for this month's weeks
                    for (let week = 1; week <= 4; week++) {
                        const weekKey = `${month}_week_${week}`;
                        delete planningData.weeks[weekKey];
                        delete yieldData.weeks[weekKey];
                    }
                    // Unexpand the month
                    expandedMonths[month].expanded = false;
                });
                
                // Unexpand the quarter
                ethiopianQuarters[quarterIndex].expanded = false;
                updateExpandButton(quarter, false);
            }
            
            updateQuarterlyRemaining();
            updateMonthlySection();
            updateWeeklySection();
        }

        // Handle Quarter Input Change
        function handleQuarterInputChange() {
            const input = $(this);
            const quarter = input.data('quarter');
            const type = input.data('type');
            const value = parseFloat(input.val()) || 0;
            
            if (type === 'area') {
                planningData.quarters[quarter].area = value;
            } else {
                yieldData.quarters[quarter].yield = value;
            }
            
            updateQuarterlyRemaining();
            validateQuarterInput(input, quarter, type, value);
            updateMonthlySection(); // Update monthly allocations
        }

        // Expand/Collapse Months for a Quarter
        function handleExpandMonths() {
            const button = $(this);
            const quarter = button.data('quarter');
            const quarterIndex = ethiopianQuarters.findIndex(q => q.name === quarter);
            
            // Toggle expansion state
            ethiopianQuarters[quarterIndex].expanded = !ethiopianQuarters[quarterIndex].expanded;
            const isExpanded = ethiopianQuarters[quarterIndex].expanded;
            
            // Update button appearance
            updateExpandButton(quarter, isExpanded);
            
            // Update monthly section
            updateMonthlySection();
        }

        function updateExpandButton(quarter, isExpanded) {
            const button = $(`button[data-quarter="${quarter}"]`);
            const icon = button.find('i');
            
            if (isExpanded) {
                button.removeClass('btn-outline-primary').addClass('btn-primary');
                icon.removeClass('mdi-arrow-expand-vertical').addClass('mdi-arrow-collapse-vertical');
                button.attr('title', 'Collapse months');
            } else {
                button.removeClass('btn-primary').addClass('btn-outline-primary');
                icon.removeClass('mdi-arrow-collapse-vertical').addClass('mdi-arrow-expand-vertical');
                button.attr('title', 'Expand months');
            }
        }

        // Update Monthly Section - Show months from all expanded quarters
        function updateMonthlySection() {
            const container = $('#monthly_distribution');
            container.empty();
            
            const expandedQuarters = ethiopianQuarters.filter(q => q.expanded && q.checked);
            const expandedQuarterNames = expandedQuarters.map(q => q.name);
            
            // Update expanded quarters info
            if (expandedQuarterNames.length > 0) {
                $('#expanded_quarters_info').text(`(Expanded quarters: ${expandedQuarterNames.join(', ')})`);
            } else {
                $('#expanded_quarters_info').text('(No quarters expanded)');
            }
            
            if (expandedQuarters.length === 0) {
                return;
            }
            
            const existingData = @json($plan->plandivision_details ?? []);
            const existingYieldData = @json($plan->yield_timeline_details ?? []);
            
            expandedQuarters.forEach(quarter => {
                const quarterArea = planningData.quarters[quarter.name]?.area || 0;
                const quarterYield = yieldData.quarters[quarter.name]?.yield || 0;
                
                quarter.months.forEach(month => {
                    const monthPlanData = existingData.months ? existingData.months[month] : { area: 0, checked: false };
                    const monthYieldData = existingYieldData.months ? existingYieldData.months[month] : { yield: 0 };
                    
                    const monthId = `month_${month.replace(/\s+/g, '_')}`;
                    const isChecked = monthPlanData.checked || false;
                    const isExpanded = expandedMonths[month]?.expanded || false;
                    
                    const monthHtml = `
                        <div class="col-md-4">
                            <div class="planning-card card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input month-checkbox" type="checkbox" 
                                                   id="check_${monthId}" 
                                                   data-month="${month}"
                                                   data-quarter="${quarter.name}"
                                                   ${isChecked ? 'checked' : ''}>
                                            <label class="form-check-label fw-bold text-success" for="check_${monthId}">
                                                ${month}
                                            </label>
                                        </div>
                                        <div>
                                            <span class="badge bg-primary me-2">${quarter.name}</span>
                                            <button type="button" class="btn btn-sm ${isExpanded ? 'btn-success' : 'btn-outline-success'} expand-weeks-btn" 
                                                    data-month="${month}" 
                                                    data-quarter="${quarter.name}"
                                                    data-bs-toggle="tooltip" title="${isExpanded ? 'Collapse' : 'Expand'} weeks">
                                                <i class="mdi ${isExpanded ? 'mdi-arrow-collapse-vertical' : 'mdi-arrow-expand-vertical'}"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="form-floating">
                                                <input type="number" step="0.01" 
                                                       class="form-control monthly-area-input ${isChecked ? '' : 'bg-light'}" 
                                                       id="${monthId}_area" 
                                                       value="${monthPlanData.area}"
                                                       data-month="${month}"
                                                       data-quarter="${quarter.name}"
                                                       data-type="area"
                                                       placeholder="Area"
                                                       ${isChecked ? '' : 'readonly'}>
                                                <label for="${monthId}_area">Area (ha)</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-floating">
                                                <input type="number" step="0.01" 
                                                       class="form-control monthly-yield-input ${isChecked ? '' : 'bg-light'}" 
                                                       id="${monthId}_yield" 
                                                       value="${monthYieldData.yield}"
                                                       data-month="${month}"
                                                       data-quarter="${quarter.name}"
                                                       data-type="yield"
                                                       placeholder="Yield"
                                                       ${isChecked ? '' : 'readonly'}>
                                                <label for="${monthId}_yield">Yield (qt)</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="allocation-feedback mt-2">
                                        <small class="text-muted allocation-text" id="month_allocation_${month}">
                                            Quarter ${quarter.name}: ${quarterArea.toFixed(2)} ha / ${quarterYield.toFixed(2)} qt
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    container.append(monthHtml);
                    
                    if (isChecked) {
                        planningData.months[month] = { area: monthPlanData.area, checked: true, quarter: quarter.name };
                        yieldData.months[month] = { yield: monthYieldData.yield, quarter: quarter.name };
                    }
                });
            });
            
            // Add event listeners for months
            $('.month-checkbox').on('change', handleMonthCheckboxChange);
            $('.monthly-area-input, .monthly-yield-input').on('input', handleMonthInputChange);
            $('.expand-weeks-btn').on('click', handleExpandWeeks);
            
            updateMonthlyRemaining();
            updateWeeklySection(); // Update weekly section when monthly changes
        }

        // Handle Month Checkbox Change
        function handleMonthCheckboxChange() {
            const checkbox = $(this);
            const month = checkbox.data('month');
            const quarter = checkbox.data('quarter');
            const isChecked = checkbox.is(':checked');
            
            // Toggle input fields
            const areaInput = $(`input[data-month="${month}"][data-type="area"]`);
            const yieldInput = $(`input[data-month="${month}"][data-type="yield"]`);
            
            if (isChecked) {
                areaInput.removeClass('bg-light').prop('readonly', false);
                yieldInput.removeClass('bg-light').prop('readonly', false);
                
                // Initialize with zero if not set
                if (!areaInput.val()) areaInput.val(0);
                if (!yieldInput.val()) yieldInput.val(0);
                
                planningData.months[month] = { area: parseFloat(areaInput.val()) || 0, checked: true, quarter: quarter };
                yieldData.months[month] = { yield: parseFloat(yieldInput.val()) || 0, quarter: quarter };
            } else {
                areaInput.addClass('bg-light').prop('readonly', true).val(0);
                yieldInput.addClass('bg-light').prop('readonly', true).val(0);
                
                delete planningData.months[month];
                delete yieldData.months[month];
                
                // Also remove weekly data for this month
                for (let week = 1; week <= 4; week++) {
                    const weekKey = `${month}_week_${week}`;
                    delete planningData.weeks[weekKey];
                    delete yieldData.weeks[weekKey];
                }
                
                // Unexpand the month
                expandedMonths[month].expanded = false;
                updateMonthlyExpandButton(month, false);
            }
            
            updateMonthlyRemaining();
            updateWeeklySection();
        }

        // Handle Month Input Change
        function handleMonthInputChange() {
            const input = $(this);
            const month = input.data('month');
            const quarter = input.data('quarter');
            const type = input.data('type');
            const value = parseFloat(input.val()) || 0;
            
            if (type === 'area') {
                planningData.months[month].area = value;
            } else {
                yieldData.months[month].yield = value;
            }
            
            updateMonthlyRemaining();
            validateMonthInput(input, month, quarter, type, value);
            updateWeeklySection(); // Update weekly allocations
        }

        // Expand/Collapse Weeks for a Month
        function handleExpandWeeks() {
            const button = $(this);
            const month = button.data('month');
            const quarter = button.data('quarter');
            
            // Toggle expansion state
            expandedMonths[month].expanded = !expandedMonths[month].expanded;
            const isExpanded = expandedMonths[month].expanded;
            
            // Update button appearance
            updateMonthlyExpandButton(month, isExpanded);
            
            // Update weekly section
            updateWeeklySection();
        }

        function updateMonthlyExpandButton(month, isExpanded) {
            const button = $(`button[data-month="${month}"]`);
            const icon = button.find('i');
            
            if (isExpanded) {
                button.removeClass('btn-outline-success').addClass('btn-success');
                icon.removeClass('mdi-arrow-expand-vertical').addClass('mdi-arrow-collapse-vertical');
                button.attr('title', 'Collapse weeks');
            } else {
                button.removeClass('btn-success').addClass('btn-outline-success');
                icon.removeClass('mdi-arrow-collapse-vertical').addClass('mdi-arrow-expand-vertical');
                button.attr('title', 'Expand weeks');
            }
        }

        // Update Weekly Section - Show weeks from all expanded months
        function updateWeeklySection() {
            const container = $('#weekly_distribution');
            container.empty();
            
            const expandedMonthsList = Object.keys(expandedMonths).filter(month => 
                expandedMonths[month].expanded && planningData.months[month]?.checked
            );
            
            // Update expanded months info
            if (expandedMonthsList.length > 0) {
                $('#expanded_months_info').text(`(Expanded months: ${expandedMonthsList.join(', ')})`);
            } else {
                $('#expanded_months_info').text('(No months expanded)');
            }
            
            if (expandedMonthsList.length === 0) {
                return;
            }
            
            const existingData = @json($plan->plandivision_details ?? []);
            const existingYieldData = @json($plan->yield_timeline_details ?? []);
            
            expandedMonthsList.forEach(month => {
                const quarter = expandedMonths[month].quarter;
                const monthArea = planningData.months[month]?.area || 0;
                const monthYield = yieldData.months[month]?.yield || 0;
                
                // Create 4 weeks for the month
                for (let week = 1; week <= 4; week++) {
                    const weekKey = `${month}_week_${week}`;
                    const weekPlanData = existingData.weeks ? existingData.weeks[weekKey] : { area: 0, checked: false };
                    const weekYieldData = existingYieldData.weeks ? existingYieldData.weeks[weekKey] : { yield: 0 };
                    
                    const weekId = `week_${month.replace(/\s+/g, '_')}_${week}`;
                    const isChecked = weekPlanData.checked || false;
                    
                    const weekHtml = `
                        <div class="col-md-3">
                            <div class="planning-card card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input week-checkbox" type="checkbox" 
                                                   id="check_${weekId}" 
                                                   data-week="${weekKey}"
                                                   data-month="${month}"
                                                   data-quarter="${quarter}"
                                                   data-week-number="${week}"
                                                   ${isChecked ? 'checked' : ''}>
                                            <label class="form-check-label fw-bold text-warning" for="check_${weekId}">
                                                Week ${week}
                                            </label>
                                        </div>
                                        <span class="badge bg-success">${month}</span>
                                    </div>
                                    
                                    <p class="text-muted small mb-3">${month} - Week ${week}</p>
                                    
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="form-floating">
                                                <input type="number" step="0.01" 
                                                       class="form-control weekly-area-input ${isChecked ? '' : 'bg-light'}" 
                                                       id="${weekId}_area" 
                                                       value="${weekPlanData.area}"
                                                       data-week="${weekKey}"
                                                       data-month="${month}"
                                                       data-quarter="${quarter}"
                                                       data-type="area"
                                                       placeholder="Area"
                                                       ${isChecked ? '' : 'readonly'}>
                                                <label for="${weekId}_area">Area (ha)</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-floating">
                                                <input type="number" step="0.01" 
                                                       class="form-control weekly-yield-input ${isChecked ? '' : 'bg-light'}" 
                                                       id="${weekId}_yield" 
                                                       value="${weekYieldData.yield}"
                                                       data-week="${weekKey}"
                                                       data-month="${month}"
                                                       data-quarter="${quarter}"
                                                       data-type="yield"
                                                       placeholder="Yield"
                                                       ${isChecked ? '' : 'readonly'}>
                                                <label for="${weekId}_yield">Yield (qt)</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="allocation-feedback mt-2">
                                        <small class="text-muted allocation-text" id="week_allocation_${weekKey}">
                                            Month ${month}: ${monthArea.toFixed(2)} ha / ${monthYield.toFixed(2)} qt
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    container.append(weekHtml);
                    
                    if (isChecked) {
                        planningData.weeks[weekKey] = { area: weekPlanData.area, checked: true, month: month, quarter: quarter, weekNumber: week };
                        yieldData.weeks[weekKey] = { yield: weekYieldData.yield, month: month, quarter: quarter, weekNumber: week };
                    }
                }
            });
            
            // Add event listeners for weeks
            $('.week-checkbox').on('change', handleWeekCheckboxChange);
            $('.weekly-area-input, .weekly-yield-input').on('input', handleWeekInputChange);
            
            updateWeeklyRemaining();
        }

        // Handle Week Checkbox Change
        function handleWeekCheckboxChange() {
            const checkbox = $(this);
            const weekKey = checkbox.data('week');
            const month = checkbox.data('month');
            const quarter = checkbox.data('quarter');
            const weekNumber = checkbox.data('week-number');
            const isChecked = checkbox.is(':checked');
            
            // Toggle input fields
            const areaInput = $(`input[data-week="${weekKey}"][data-type="area"]`);
            const yieldInput = $(`input[data-week="${weekKey}"][data-type="yield"]`);
            
            if (isChecked) {
                areaInput.removeClass('bg-light').prop('readonly', false);
                yieldInput.removeClass('bg-light').prop('readonly', false);
                
                // Initialize with zero if not set
                if (!areaInput.val()) areaInput.val(0);
                if (!yieldInput.val()) yieldInput.val(0);
                
                planningData.weeks[weekKey] = { area: parseFloat(areaInput.val()) || 0, checked: true, month: month, quarter: quarter, weekNumber: weekNumber };
                yieldData.weeks[weekKey] = { yield: parseFloat(yieldInput.val()) || 0, month: month, quarter: quarter, weekNumber: weekNumber };
            } else {
                areaInput.addClass('bg-light').prop('readonly', true).val(0);
                yieldInput.addClass('bg-light').prop('readonly', true).val(0);
                
                delete planningData.weeks[weekKey];
                delete yieldData.weeks[weekKey];
            }
            
            updateWeeklyRemaining();
        }

        // Handle Week Input Change
        function handleWeekInputChange() {
            const input = $(this);
            const weekKey = input.data('week');
            const month = input.data('month');
            const quarter = input.data('quarter');
            const type = input.data('type');
            const value = parseFloat(input.val()) || 0;
            
            if (type === 'area') {
                planningData.weeks[weekKey].area = value;
            } else {
                yieldData.weeks[weekKey].yield = value;
            }
            
            updateWeeklyRemaining();
            validateWeekInput(input, weekKey, month, quarter, type, value);
        }

        // Helper Functions
        function getUsedArea() {
            return Object.values(planningData.quarters).reduce((sum, q) => sum + (q.area || 0), 0);
        }

        function getUsedYield() {
            return Object.values(yieldData.quarters).reduce((sum, q) => sum + (q.yield || 0), 0);
        }

        function getRemainingArea() {
            return totalPlanningArea - getUsedArea();
        }

        function getRemainingYield() {
            return totalExpectedYield - getUsedYield();
        }

        function getMonthTotal(quarter, type) {
            const quarterMonths = ethiopianQuarters.find(q => q.name === quarter)?.months || [];
            return quarterMonths.reduce((sum, month) => {
                const data = type === 'area' ? planningData.months[month] : yieldData.months[month];
                return sum + (data?.area || data?.yield || 0);
            }, 0);
        }

        function getWeekTotal(month, type) {
            let total = 0;
            for (let week = 1; week <= 4; week++) {
                const weekKey = `${month}_week_${week}`;
                const data = type === 'area' ? planningData.weeks[weekKey] : yieldData.weeks[weekKey];
                total += (data?.area || data?.yield || 0);
            }
            return total;
        }

        function updateQuarterlyRemaining() {
            const usedArea = getUsedArea();
            const usedYield = getUsedYield();
            
            const remainingArea = totalPlanningArea - usedArea;
            const remainingYield = totalExpectedYield - usedYield;
            
            $('#quarterly_remaining_area').text(remainingArea.toFixed(2));
            $('#quarterly_remaining_yield').text(remainingYield.toFixed(2));
            
            // Update progress colors
            const areaBadge = $('#quarterly_remaining_area').parent().parent();
            const yieldBadge = $('#quarterly_remaining_yield').parent().parent();
            
            if (remainingArea >= 0) {
                areaBadge.removeClass('bg-danger').addClass('bg-primary');
            } else {
                areaBadge.removeClass('bg-primary').addClass('bg-danger');
            }
            
            if (remainingYield >= 0) {
                yieldBadge.removeClass('bg-danger').addClass('bg-primary');
            } else {
                yieldBadge.removeClass('bg-primary').addClass('bg-danger');
            }
        }

        function updateMonthlyRemaining() {
            // Calculate remaining for each quarter and update display
            let totalRemainingArea = 0;
            let totalRemainingYield = 0;
            
            ethiopianQuarters.forEach(quarter => {
                if (quarter.checked) {
                    const quarterArea = planningData.quarters[quarter.name]?.area || 0;
                    const quarterYield = yieldData.quarters[quarter.name]?.yield || 0;
                    
                    const usedMonthArea = getMonthTotal(quarter.name, 'area');
                    const usedMonthYield = getMonthTotal(quarter.name, 'yield');
                    
                    totalRemainingArea += (quarterArea - usedMonthArea);
                    totalRemainingYield += (quarterYield - usedMonthYield);
                }
            });
            
            $('#monthly_remaining_area').text(totalRemainingArea.toFixed(2));
            $('#monthly_remaining_yield').text(totalRemainingYield.toFixed(2));
            
            // Update progress colors
            const areaBadge = $('#monthly_remaining_area').parent().parent();
            const yieldBadge = $('#monthly_remaining_yield').parent().parent();
            
            if (totalRemainingArea >= 0) {
                areaBadge.removeClass('bg-danger').addClass('bg-success');
            } else {
                areaBadge.removeClass('bg-success').addClass('bg-danger');
            }
            
            if (totalRemainingYield >= 0) {
                yieldBadge.removeClass('bg-danger').addClass('bg-success');
            } else {
                yieldBadge.removeClass('bg-success').addClass('bg-danger');
            }
        }

        function updateWeeklyRemaining() {
            // Calculate remaining for each month and update display
            let totalRemainingArea = 0;
            let totalRemainingYield = 0;
            
            Object.keys(planningData.months).forEach(month => {
                const monthArea = planningData.months[month]?.area || 0;
                const monthYield = yieldData.months[month]?.yield || 0;
                
                const usedWeekArea = getWeekTotal(month, 'area');
                const usedWeekYield = getWeekTotal(month, 'yield');
                
                totalRemainingArea += (monthArea - usedWeekArea);
                totalRemainingYield += (monthYield - usedWeekYield);
            });
            
            $('#weekly_remaining_area').text(totalRemainingArea.toFixed(2));
            $('#weekly_remaining_yield').text(totalRemainingYield.toFixed(2));
            
            // Update progress colors
            const areaBadge = $('#weekly_remaining_area').parent().parent();
            const yieldBadge = $('#weekly_remaining_yield').parent().parent();
            
            if (totalRemainingArea >= 0) {
                areaBadge.removeClass('bg-danger').addClass('bg-warning');
            } else {
                areaBadge.removeClass('bg-warning').addClass('bg-danger');
            }
            
            if (totalRemainingYield >= 0) {
                yieldBadge.removeClass('bg-danger').addClass('bg-warning');
            } else {
                yieldBadge.removeClass('bg-warning').addClass('bg-danger');
            }
        }

        // Validation functions
        function validateQuarterInput(input, quarter, type, value) {
            const remaining = type === 'area' ? getRemainingArea() : getRemainingYield();
            const total = type === 'area' ? totalPlanningArea : totalExpectedYield;
            
            if (value < 0) {
                input.addClass('is-invalid');
                showAllocationFeedback(quarter, 'Value cannot be negative', 'danger');
                return false;
            }
            
            if (value > total) {
                input.addClass('is-invalid');
                showAllocationFeedback(quarter, `Value exceeds total ${type}`, 'danger');
                return false;
            }
            
            if (value > remaining + value) {
                input.addClass('is-invalid');
                showAllocationFeedback(quarter, `Exceeds available ${type}`, 'danger');
                return false;
            }
            
            input.removeClass('is-invalid');
            updateAllocationFeedback(quarter);
            return true;
        }

        function validateMonthInput(input, month, quarter, type, value) {
            const quarterTotal = type === 'area' ? planningData.quarters[quarter]?.area || 0 : yieldData.quarters[quarter]?.yield || 0;
            const monthTotal = getMonthTotal(quarter, type);
            
            if (value < 0) {
                input.addClass('is-invalid');
                return false;
            }
            
            if (monthTotal > quarterTotal) {
                input.addClass('is-invalid');
                showMonthAllocationFeedback(month, `Exceeds quarter ${type}`, 'danger');
                return false;
            }
            
            input.removeClass('is-invalid');
            updateMonthAllocationFeedback(month, quarter, type);
            return true;
        }

        function validateWeekInput(input, weekKey, month, quarter, type, value) {
            const monthTotal = type === 'area' ? planningData.months[month]?.area || 0 : yieldData.months[month]?.yield || 0;
            const weekTotal = getWeekTotal(month, type);
            
            if (value < 0) {
                input.addClass('is-invalid');
                return false;
            }
            
            if (weekTotal > monthTotal) {
                input.addClass('is-invalid');
                showWeekAllocationFeedback(weekKey, `Exceeds month ${type}`, 'danger');
                return false;
            }
            
            input.removeClass('is-invalid');
            updateWeekAllocationFeedback(weekKey, month, type);
            return true;
        }

        // Feedback functions
        function showAllocationFeedback(quarter, message, type) {
            $(`#allocation_${quarter}`).removeClass('text-muted text-success text-danger').addClass(`text-${type}`).text(message);
        }

        function updateAllocationFeedback(quarter) {
            const remainingArea = getRemainingArea();
            const remainingYield = getRemainingYield();
            $(`#allocation_${quarter}`).removeClass('text-danger text-success')
                .addClass(remainingArea >= 0 && remainingYield >= 0 ? 'text-success' : 'text-danger')
                .text(`Available: ${remainingArea.toFixed(2)} ha / ${remainingYield.toFixed(2)} qt`);
        }

        function showMonthAllocationFeedback(month, message, type) {
            $(`#month_allocation_${month}`).removeClass('text-muted text-success text-danger').addClass(`text-${type}`).text(message);
        }

        function updateMonthAllocationFeedback(month, quarter, type) {
            const quarterTotal = type === 'area' ? planningData.quarters[quarter]?.area || 0 : yieldData.quarters[quarter]?.yield || 0;
            const monthTotal = getMonthTotal(quarter, type);
            const remaining = quarterTotal - monthTotal;
            const text = `Quarter ${quarter}: ${quarterTotal.toFixed(2)} ha / Remaining: ${remaining.toFixed(2)} ha`;
            $(`#month_allocation_${month}`).removeClass('text-danger text-success')
                .addClass(remaining >= 0 ? 'text-success' : 'text-danger').text(text);
        }

        function showWeekAllocationFeedback(weekKey, message, type) {
            $(`#week_allocation_${weekKey}`).removeClass('text-muted text-success text-danger').addClass(`text-${type}`).text(message);
        }

        function updateWeekAllocationFeedback(weekKey, month, type) {
            const monthTotal = type === 'area' ? planningData.months[month]?.area || 0 : yieldData.months[month]?.yield || 0;
            const weekTotal = getWeekTotal(month, type);
            const remaining = monthTotal - weekTotal;
            const text = `Month ${month}: ${monthTotal.toFixed(2)} ha / Remaining: ${remaining.toFixed(2)} ha`;
            $(`#week_allocation_${weekKey}`).removeClass('text-danger text-success')
                .addClass(remaining >= 0 ? 'text-success' : 'text-danger').text(text);
        }

        // Collect planning data before form submission
        function collectPlanningData() {
            return {
                quarters: planningData.quarters,
                months: planningData.months,
                weeks: planningData.weeks
            };
        }

        function collectYieldTimelineData() {
            return {
                quarters: yieldData.quarters,
                months: yieldData.months,
                weeks: yieldData.weeks
            };
        }

        // Form submission
        $('#cropPlanForm').on('submit', function(e) {
            const planningDataJson = collectPlanningData();
            const yieldTimelineData = collectYieldTimelineData();
            
            // Validate totals
            const totalAllocatedArea = getUsedArea();
            const totalAllocatedYield = getUsedYield();
            
            if (Math.abs(totalAllocatedArea - totalPlanningArea) > 0.01) {
                e.preventDefault();
                alert(`Total allocated area (${totalAllocatedArea.toFixed(2)} ha) does not match planning area (${totalPlanningArea.toFixed(2)} ha). Please adjust your distribution.`);
                return false;
            }
            
            if (Math.abs(totalAllocatedYield - totalExpectedYield) > 0.01) {
                e.preventDefault();
                alert(`Total allocated yield (${totalAllocatedYield.toFixed(2)} qt) does not match expected yield (${totalExpectedYield.toFixed(2)} qt). Please adjust your distribution.`);
                return false;
            }
            
            $('#plandivision_details').val(JSON.stringify(planningDataJson));
            $('#yield_timeline_details').val(JSON.stringify(yieldTimelineData));
            
            $('#submitBtn').prop('disabled', true).html('<div class="spinner-border spinner-border-sm me-2"></div>Updating...');
        });

        // Initialize tooltips
        $(function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>

    <style>
        .crop-plan-edit-container {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .form-card {
            border-radius: 20px;
            overflow: hidden;
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffa726 0%, #fb8c00 100%) !important;
        }

        .page-header .header-icon {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
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
            color: #ffa726;
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
            background: linear-gradient(90deg, #ffa726, #fb8c00);
            border-radius: 2px;
            width: 60px;
        }

        .uneditable-header-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 2rem;
            border: 2px dashed #dee2e6;
        }

        .uneditable-header-section .form-control {
            background-color: #f8f9fa !important;
            border-color: #dee2e6;
            color: #6c757d;
            cursor: not-allowed;
        }

        .summary-card {
            border-radius: 12px;
            transition: transform 0.3s ease;
        }

        .summary-card:hover {
            transform: translateY(-5px);
        }

        .planning-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            border-left: 4px solid #dee2e6;
        }

        .planning-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }

        .quarter-checkbox:checked ~ .planning-card {
            border-left-color: #0d6efd;
        }

        .month-checkbox:checked ~ .planning-card {
            border-left-color: #198754;
        }

        .week-checkbox:checked ~ .planning-card {
            border-left-color: #ffc107;
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .allocation-summary .badge {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
        }

        .expand-months-btn, .expand-weeks-btn {
            border-radius: 20px;
            padding: 0.25rem 0.5rem;
        }

        .allocation-feedback {
            min-height: 20px;
        }

        .allocation-text {
            font-size: 0.75rem;
        }

        .is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem !important;
            }
            
            .uneditable-header-section {
                padding: 1.5rem;
            }
            
            .planning-card {
                margin-bottom: 1rem;
            }
            
            .weekly-planning .col-md-3 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media (max-width: 576px) {
            .weekly-planning .col-md-3 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        /* Smooth transitions */
        .planning-card, .form-control, .btn {
            transition: all 0.3s ease;
        }

        /* Weekly card specific styles */
        .weekly-planning .planning-card {
            border-left-color: #ffc107;
        }

        .weekly-planning .card-body {
            padding: 1rem;
        }

        .weekly-planning .form-floating {
            margin-bottom: 0.5rem;
        }

        .weekly-planning .allocation-text {
            font-size: 0.7rem;
        }

        .form-actions {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            margin-left: -2.5rem;
            margin-right: -2.5rem;
            padding-left: 2.5rem;
            padding-right: 2.5rem;
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffa726 0%, #fb8c00 100%);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 167, 38, 0.3);
        }
    </style>
</x-layout>