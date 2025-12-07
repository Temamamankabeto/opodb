<x-layout>
    <div class="content-header">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ __('Edit Compost Distribution Schedule') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('sector.gabbina_biyyee.index') }}">{{ __('Compost Plans') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('Edit Schedule') }}</li>
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
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-recycle mr-2"></i>{{ __('Compost Distribution Schedule') }}</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form action="{{ route('sector.compost-plans.update', $gabbinaBiyyee->id) }}" method="POST" id="compostPlanForm">
                                @csrf
                                @method('PUT')

                                <!-- Hidden field to store structured compost distribution data -->
                                <input type="hidden" name="compost_distribution_details" id="compostDistributionDetails"
                                       value="{{ json_encode($gabbinaBiyyee->compost_distribution_details ?? []) }}">

                                <div class="card-body">
                                    <!-- Basic Information Display -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h5 class="text-success mb-3">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                {{ __('Compost Information') }}
                                            </h5>
                                            <hr class="mt-0">
                                        </div>
                                        
                                        <div class="col-md-4 col-sm-6">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-success"><i class="fas fa-recycle"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Compost Type') }}</span>
                                                    <span class="info-box-number">{{ $gabbinaBiyyee->compost_type }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2 col-sm-4">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-info"><i class="fas fa-calendar-alt"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Year') }}</span>
                                                    <span class="info-box-number">{{ $gabbinaBiyyee->year }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2 col-sm-4">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-warning"><i class="fas fa-calendar-check"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Application Year') }}</span>
                                                    <span class="info-box-number">{{ $gabbinaBiyyee->year }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2 col-sm-4">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-primary"><i class="fas fa-weight"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Unit') }}</span>
                                                    <span class="info-box-number">{{ $gabbinaBiyyee->unit }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        @if($gabbinaBiyyee->land_area)
                                        <div class="col-md-2 col-sm-4">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-purple"><i class="fas fa-globe-americas"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Land Area') }}</span>
                                                    <span class="info-box-number">{{ number_format($gabbinaBiyyee->land_area, 2) }} ha</span>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Quantity Information Display -->
                                    <div class="row mb-4">
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-success"><i class="fas fa-users"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Common Quantity') }}</span>
                                                    <span class="info-box-number">{{ $gabbinaBiyyee->common ? number_format($gabbinaBiyyee->common, 2) : '0.00' }} {{ $gabbinaBiyyee->unit }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-info"><i class="fas fa-user"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Individual Quantity') }}</span>
                                                    <span class="info-box-number">{{ $gabbinaBiyyee->individual ? number_format($gabbinaBiyyee->individual, 2) : '0.00' }} {{ $gabbinaBiyyee->unit }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-warning"><i class="fas fa-tachometer-alt"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Available Quantity') }}</span>
                                                    <span class="info-box-number" id="availableQuantity">{{ $gabbinaBiyyee->total ? number_format($gabbinaBiyyee->total, 2) : '0.00' }} {{ $gabbinaBiyyee->unit }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-danger"><i class="fas fa-calculator"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Remaining Quantity') }}</span>
                                                    <span class="info-box-number" id="remainingQuantity">{{ $gabbinaBiyyee->total ? number_format($gabbinaBiyyee->total, 2) : '0.00' }} {{ $gabbinaBiyyee->unit }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Distribution Scheduling Section -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h5 class="text-info mb-3">
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                {{ __('Compost Distribution Schedule') }}
                                            </h5>
                                            <hr class="mt-0">
                                            <p class="text-muted">
                                                {{ __('Select multiple quarters, months, and schedule distribution across weeks for application year') }} ({{ $gabbinaBiyyee->year }}).
                                            </p>
                                            <div class="callout callout-info mt-2">
                                                <h6><i class="fas fa-info-circle mr-2"></i>{{ __('Distribution Status') }}</h6>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <small class="text-muted">{{ __('Total Available') }}</small>
                                                        <div class="font-weight-bold text-primary">{{ $gabbinaBiyyee->total ? number_format($gabbinaBiyyee->total, 2) : '0.00' }} {{ $gabbinaBiyyee->unit }}</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small class="text-muted">{{ __('Scheduled Quantity') }}</small>
                                                        <div class="font-weight-bold text-success" id="scheduledQuantityDisplay">0 {{ $gabbinaBiyyee->unit }}</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small class="text-muted">{{ __('Remaining Quantity') }}</small>
                                                        <div class="font-weight-bold text-warning" id="remainingQuantityDisplay">{{ $gabbinaBiyyee->total ? number_format($gabbinaBiyyee->total, 2) : '0.00' }} {{ $gabbinaBiyyee->unit }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quarters Selection -->
                                    <div class="card card-outline card-primary mb-3">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <i class="fas fa-calendar-range mr-2"></i>
                                                {{ __('Select Quarters') }} ({{ $gabbinaBiyyee->year }})
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
                                                {{ __('Select Months') }} ({{ $gabbinaBiyyee->year }})
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
                                                            <th>{{ __('Scheduled') }}</th>
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
                                                {{ __('Schedule Distribution') }} ({{ $gabbinaBiyyee->year }})
                                            </h3>
                                            <div class="card-tools">
                                                <span class="badge badge-success" id="totalScheduledBadge">0 {{ $gabbinaBiyyee->unit }}</span>
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
                                                            <th width="150">{{ __('Quantity') }}</th>
                                                            <th width="80">{{ __('Status') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="weeksTable">
                                                        <!-- Weeks will be populated here -->
                                                    </tbody>
                                                    <tfoot class="bg-light">
                                                        <tr>
                                                            <td colspan="5" class="text-right font-weight-bold">{{ __('Total Scheduled') }}:</td>
                                                            <td class="font-weight-bold text-success" id="weeksTotalScheduled">0 {{ $gabbinaBiyyee->unit }}</td>
                                                            <td class="font-weight-bold text-info">{{ __('Scheduled') }}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <div class="callout callout-info mt-3">
                                                <div class="row align-items-center">
                                                    <div class="col-md-8">
                                                        <h6><i class="fas fa-info-circle mr-2"></i>{{ __('Instructions') }}</h6>
                                                        <p class="mb-0">{{ __('Enter quantity for each week. The system will validate against available quantity.') }}</p>
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <button type="button" class="btn btn-success" onclick="saveAllSchedules()">
                                                            <i class="fas fa-save mr-2"></i> {{ __('Save All Schedules') }}
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
                                                {{ __('Distribution Summary') }} ({{ __('Application Year') }}: {{ $gabbinaBiyyee->year }})
                                            </h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>{{ __('Year') }}</th>
                                                            <th>{{ __('Compost Type') }}</th>
                                                            <th>{{ __('Quarter') }}</th>
                                                            <th>{{ __('Month') }}</th>
                                                            <th>{{ __('Week') }}</th>
                                                            <th>{{ __('Date Range') }}</th>
                                                            <th>{{ __('Quantity') }}</th>
                                                            <th>{{ __('Action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="summaryTable">
                                                        <tr>
                                                            <td colspan="8" class="text-center text-muted py-4">
                                                                <i class="fas fa-recycle mr-2"></i>
                                                                {{ __('No distribution scheduled yet. Start by selecting quarters above.') }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot class="bg-gray">
                                                        <tr>
                                                            <td colspan="6" class="text-right font-weight-bold">{{ __('Total') }}:</td>
                                                            <td class="font-weight-bold text-success" id="summaryTotal">0 {{ $gabbinaBiyyee->unit }}</td>
                                                            <td></td>
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
                                            <a href="{{ route('sector.gabbina_biyyee.index') }}" class="btn btn-default">
                                                <i class="fas fa-arrow-left mr-2"></i> {{ __('Back to Compost Plans') }}
                                            </a>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <button type="submit" class="btn btn-success ml-2" id="submitBtn">
                                                <i class="fas fa-save mr-2"></i> {{ __('Update Schedule') }}
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
            all_schedules_saved: "{{ __('All schedules saved successfully for application year') }}",
            schedule_removed: "{{ __('Schedule removed successfully!') }}",
            no_quantity_available: "{{ __('No quantity available to schedule for this month') }}",
            scheduled_equally: "{{ __('Distributed equally across weeks in') }}",
            all_schedules_cleared: "{{ __('All schedules cleared for') }}",
            no_months_available: "{{ __('No months available in selected quarters.') }}",
            fix_validation_errors: "{{ __('Please fix validation errors before saving') }}",
            no_schedule_warning: "{{ __('No compost distribution has been scheduled for application year . Do you want to proceed without scheduling?') }}",
            please_add_schedule: "{{ __('Please add compost schedule or confirm to proceed without it') }}",
            scheduled_exceeds_available: "{{ __('Scheduled quantity exceeds available quantity') }}",
            exceeds_available_quantity: "{{ __('Exceeds available quantity') }}",
            value_cannot_negative: "{{ __('Value cannot be negative') }}",
            valid: "{{ __('Valid') }}",
            week: "{{ __('Week') }}",
            days: "{{ __('Days') }}",
            year: "{{ __('Year') }}",
            scheduled: "{{ __('Scheduled') }}",
            not_scheduled: "{{ __('Not Scheduled') }}",
            partially_scheduled: "{{ __('Partially Scheduled') }}",
            months: "{{ __('months') }}",
            quarters: "{{ __('quarters') }}",
            weeks: "{{ __('weeks') }}",
            selected: "{{ __('Selected') }}",
            q: "{{ __('Q') }}",
            please_fix_errors: "{{ __('Please fix the validation errors before submitting.') }}"
        };

        let availableQuantity = parseFloat({{ $gabbinaBiyyee->total ?? 0 }});
        const unit = '{{ $gabbinaBiyyee->unit }}';
        let selectedQuarters = new Set();
        let selectedMonths = new Set();
        let compostSummaryRows = [];
        const applicationYear = {{ $gabbinaBiyyee->year }};

        // Ethiopian Calendar Data with translations
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
        function calculateStatus(quantityValue) {
            if (quantityValue === 0) return translations.not_scheduled;
            return translations.scheduled;
        }

        function getStatusClass(status) {
            switch(status) {
                case translations.not_scheduled: return 'badge-secondary';
                case translations.scheduled: return 'badge-success';
                case translations.partially_scheduled: return 'badge-warning';
                default: return 'badge-secondary';
            }
        }

        // Initialize form
        $(document).ready(function() {
            console.log('Document ready - initializing compost scheduling for application year:', applicationYear);
            console.log('Available quantity:', availableQuantity);

            // Update the application year display
            $('#availableQuantity').text(availableQuantity.toFixed(2) + ' ' + unit);

            // Form submission validation
            $('#compostPlanForm').on('submit', function(e) {
                console.log('Form submitted, validating...');
                if (!validateForm()) {
                    e.preventDefault();
                    showAlert(translations.please_fix_errors, 'error');
                } else {
                    console.log('Form validation passed, submitting...');
                    saveCompostJSON();
                }
            });

            renderQuarters();
            loadExistingCompostDistribution();
            updateQuantitySummary();
        });

        /* -------------------------------
           QUANTITY VALIDATION & CALCULATION
        ------------------------------- */
        function getCurrentScheduledQuantity() {
            return compostSummaryRows.reduce((total, row) => total + row.quantity, 0);
        }

        function getRemainingQuantity() {
            return Math.max(0, availableQuantity - getCurrentScheduledQuantity());
        }

        function updateQuantitySummary() {
            const scheduled = getCurrentScheduledQuantity();
            const remaining = getRemainingQuantity();

            $("#scheduledQuantityDisplay").text(scheduled.toFixed(2) + " " + unit);
            $("#remainingQuantityDisplay").text(remaining.toFixed(2) + " " + unit);
            $("#remainingQuantity").text(remaining.toFixed(2) + " " + unit);

            // Update color based on remaining quantity
            if (remaining === 0) {
                $("#remainingQuantityDisplay").removeClass('text-warning').addClass('text-danger');
                $("#remainingQuantity").removeClass('text-success').addClass('text-danger');
            } else if (remaining < availableQuantity * 0.2) {
                $("#remainingQuantityDisplay").removeClass('text-danger').addClass('text-warning');
                $("#remainingQuantity").removeClass('text-success').addClass('text-warning');
            } else {
                $("#remainingQuantityDisplay").removeClass('text-danger text-warning').addClass('text-primary');
                $("#remainingQuantity").removeClass('text-danger text-warning').addClass('text-success');
            }
        }

        function validateQuantityInput(input) {
            const value = parseFloat($(input).val()) || 0;
            const feedback = $(input).parent().siblings('.validation-feedback');

            $(input).removeClass('is-valid is-invalid');
            feedback.empty();

            if (value < 0) {
                $(input).addClass('is-invalid');
                feedback.html('<small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>' + translations.value_cannot_negative + '</small>');
                return false;
            }

            // Calculate remaining quantity excluding current row
            const scheduledWithoutCurrent = compostSummaryRows
                .filter(row => !(row.month === $(input).data('month-name') &&
                                row.week === translations.week + ' ' + $(input).data('week')))
                .reduce((total, row) => total + row.quantity, 0);

            const maxAllowed = Math.max(0, availableQuantity - scheduledWithoutCurrent);

            if (value > maxAllowed) {
                $(input).addClass('is-invalid');
                feedback.html(`<small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>${translations.exceeds_available_quantity} (${maxAllowed.toFixed(2)})</small>`);
                return false;
            }

            if (value > 0) {
                $(input).addClass('is-valid');
            }

            return true;
        }

        function validateAllSchedules() {
            let isValid = true;
            const errors = [];

            const totalScheduled = getCurrentScheduledQuantity();
            
            if (totalScheduled > availableQuantity) {
                isValid = false;
                errors.push(translations.scheduled_exceeds_available + ` (${totalScheduled.toFixed(2)}/${availableQuantity.toFixed(2)})`);
            }

            // Check individual inputs
            $('.quantity-input').each(function() {
                if (!validateQuantityInput(this)) {
                    isValid = false;
                }
            });

            return {
                isValid,
                errors
            };
        }

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
                                    <small class="text-success">
                                        <i class="fas fa-recycle mr-1"></i>{{ __('Scheduled') }}: ${quarterData.scheduled.toFixed(2)} ${unit}
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

            renderMonthsTable();
            updateQuantitySummary();
        }

        function calculateQuarterData(quarter) {
            const quarterName = `Q${quarter}`;
            const quarterSchedules = compostSummaryRows.filter(row => row.quarter === quarterName);
            
            return {
                scheduled: quarterSchedules.reduce((total, row) => total + (row.quantity || 0), 0)
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
            showAlert(translations.all_quarters_selected + ' ' + applicationYear, 'success');
        }

        function clearAllQuarters() {
            selectedQuarters.clear();
            selectedMonths.clear();
            renderQuarters();
            $('#monthsSection').hide();
            $('#weeksSection').hide();
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
                const status = monthData.scheduled > 0 ? translations.partially_scheduled : translations.not_scheduled;
                const statusClass = monthData.scheduled > 0 ? 'badge-warning' : 'badge-secondary';

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
                        <td>${applicationYear}</td>
                        <td>
                            <span class="text-success font-weight-bold">${monthData.scheduled.toFixed(2)} ${unit}</span>
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
        }

        function calculateMonthData(monthNum) {
            const monthName = EC_MONTHS[monthNum - 1].name;
            const monthSchedules = compostSummaryRows.filter(row => row.month === monthName);
            
            return {
                scheduled: monthSchedules.reduce((total, row) => total + (row.quantity || 0), 0)
            };
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

            renderMonthsTable();
            renderWeeksTable();
            showAlert(translations.all_months_selected + ' ' + applicationYear, 'success');
        }

        function clearAllMonths() {
            selectedMonths.clear();
            renderMonthsTable();
            $('#weeksSection').hide();
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
            let totalScheduled = 0;

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
                    const existingSchedule = compostSummaryRows.find(row =>
                        row.month === month.name && row.week === translations.week + ' ' + week.weekNum
                    );
                    
                    const quantityValue = existingSchedule ? existingSchedule.quantity : 0;
                    const status = calculateStatus(quantityValue);
                    const statusClass = getStatusClass(status);
                    
                    totalScheduled += quantityValue;

                    $("#weeksTable").append(`
                        <tr class="week-row" data-month="${monthNum}" data-week="${week.weekNum}">
                            <td>
                                <strong>${translations.week} ${week.weekNum}</strong>
                            </td>
                            <td>${month.name}</td>
                            <td>
                                <span class="badge badge-primary">Q${quarterNum}</span>
                            </td>
                            <td>
                                <small class="text-muted">${translations.days} ${week.start}-${week.end}</small>
                            </td>
                            <td>${applicationYear}</td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.01" min="0" max="${availableQuantity}"
                                           class="form-control form-control-sm quantity-input"
                                           data-month="${monthNum}"
                                           data-week="${week.weekNum}"
                                           data-month-name="${month.name}"
                                           value="${quantityValue.toFixed(2)}"
                                           placeholder="0.00">
                                    <div class="input-group-append">
                                        <span class="input-group-text">${unit}</span>
                                    </div>
                                </div>
                                <div class="validation-feedback mt-1" style="font-size: 0.75rem;"></div>
                            </td>
                            <td>
                                <span class="badge ${statusClass}">${status}</span>
                            </td>
                        </tr>
                    `);
                });
            });

            // Add input event listeners
            enhanceQuantityInputValidation();
            
            // Update footer totals
            $('#weeksTotalScheduled').text(totalScheduled.toFixed(2) + ' ' + unit);
            $('#totalScheduledBadge').text(totalScheduled.toFixed(2) + ' ' + unit);
        }

        /* -------------------------------
           INPUT VALIDATION
        ------------------------------- */
        function updateWeekDistribution(input) {
            const monthNum = parseInt($(input).data('month'));
            const weekNumber = parseInt($(input).data('week'));
            const month = EC_MONTHS[monthNum - 1];
            const quarter = `Q${findQuarter(monthNum)}`;
            
            const quantityInput = $(`.quantity-input[data-month="${monthNum}"][data-week="${weekNumber}"]`);
            const quantityValue = parseFloat(quantityInput.val()) || 0;
            
            // Find or create schedule
            let schedule = compostSummaryRows.find(row =>
                row.month === month.name && row.week === translations.week + ' ' + weekNumber
            );

            if (schedule) {
                schedule.quantity = quantityValue;
            } else {
                const weekData = getWeekData(monthNum, weekNumber);
                schedule = {
                    year: applicationYear,
                    quarter: quarter,
                    month: month.name,
                    week: translations.week + ' ' + weekNumber,
                    range: weekData.range,
                    compost_type: '{{ $gabbinaBiyyee->compost_type }}',
                    quantity: quantityValue,
                    unit: unit
                };
                compostSummaryRows.push(schedule);
            }

            // Update status
            schedule.status = calculateStatus(quantityValue);

            // Remove if quantity is 0
            if (quantityValue === 0) {
                compostSummaryRows = compostSummaryRows.filter(row =>
                    !(row.month === month.name && row.week === translations.week + ' ' + weekNumber)
                );
            }

            // Update UI
            updateWeekUI(monthNum, weekNumber, quantityValue);
            updateSummaryTable();
            updateQuantitySummary();
            updateMonthTableData(monthNum);
            updateWeeksTableFooter();
        }

        function updateWeekUI(monthNum, weekNumber, quantityValue) {
            const status = calculateStatus(quantityValue);
            const statusClass = getStatusClass(status);
            
            const weekRow = $(`.week-row[data-month="${monthNum}"][data-week="${weekNumber}"]`);
            
            weekRow.find('.badge').removeClass('badge-secondary badge-success');
            weekRow.find('.badge').addClass(statusClass).text(status);
        }

        function updateMonthTableData(monthNum) {
            const monthData = calculateMonthData(monthNum);
            const monthName = EC_MONTHS[monthNum - 1].name;
            
            const monthRow = $(`.month-row[data-month="${monthNum}"]`);
            monthRow.find('td:nth-child(5)').html(`<span class="text-success font-weight-bold">${monthData.scheduled.toFixed(2)} ${unit}</span>`);
            
            const status = monthData.scheduled > 0 ? translations.partially_scheduled : translations.not_scheduled;
            const statusClass = monthData.scheduled > 0 ? 'badge-warning' : 'badge-secondary';
            monthRow.find('td:nth-child(6)').html(`<span class="badge ${statusClass}">${status}</span>`);
        }

        function enhanceQuantityInputValidation() {
            $('.quantity-input').off('input').on('input', function() {
                if (validateQuantityInput(this)) {
                    updateWeekDistribution(this);
                }
            });
        }

        function updateWeeksTableFooter() {
            let totalScheduled = 0;
            
            $('.quantity-input').each(function() {
                const value = parseFloat($(this).val()) || 0;
                totalScheduled += value;
            });
            
            $('#weeksTotalScheduled').text(totalScheduled.toFixed(2) + ' ' + unit);
            $('#totalScheduledBadge').text(totalScheduled.toFixed(2) + ' ' + unit);
        }

        function saveAllSchedules() {
            let hasErrors = false;

            // Validate all inputs first
            $('.quantity-input').each(function() {
                if (!validateQuantityInput(this)) hasErrors = true;
            });

            if (hasErrors) {
                showAlert(translations.fix_validation_errors, 'error');
                return;
            }

            // Update all schedules from inputs
            $('.quantity-input').each(function() {
                updateWeekDistribution(this);
            });

            saveCompostJSON();
            updateSummaryTable();
            updateQuantitySummary();
            showAlert(translations.all_schedules_saved + ' ' + applicationYear + '!', 'success');
        }

        /* -------------------------------
           SUMMARY & DATA MANAGEMENT
        ------------------------------- */
        function updateSummaryTable() {
            $("#summaryTable").empty();
            let totalScheduled = 0;

            if (compostSummaryRows.length === 0) {
                $("#summaryTable").append(`
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="fas fa-recycle mr-2"></i>
                        {{ __('No distribution scheduled yet. Start by selecting quarters above.') }}
                    </td>
                </tr>
            `);
            } else {
                compostSummaryRows.forEach((row, i) => {
                    totalScheduled += row.quantity;
                    const statusClass = getStatusClass(row.status);

                    $("#summaryTable").append(`
                    <tr>
                        <td>${row.year || applicationYear}</td>
                        <td>${row.compost_type}</td>
                        <td>${row.quarter}</td>
                        <td>${row.month}</td>
                        <td>${row.week}</td>
                        <td>${row.range}</td>
                        <td class="font-weight-bold text-success">${row.quantity.toFixed(2)} ${row.unit}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger" onclick="removeCompostRow(${i})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);
                });
            }

            $("#summaryTotal").text(totalScheduled.toFixed(2) + " " + unit);
        }

        function removeCompostRow(index) {
            compostSummaryRows.splice(index, 1);
            updateSummaryTable();
            saveCompostJSON();
            updateQuantitySummary();
            renderWeeksTable();
            showAlert(translations.schedule_removed, 'info');
        }

        function saveCompostJSON() {
            const totalScheduled = compostSummaryRows.reduce((total, row) => total + row.quantity, 0);
            const remainingQuantity = getRemainingQuantity();

            const distributionData = {
                planning_year: applicationYear,
                application_year: applicationYear,
                weekly_distribution: compostSummaryRows,
                total_scheduled: totalScheduled,
                available_quantity: availableQuantity,
                remaining_quantity: remainingQuantity,
                updated_at: new Date().toISOString()
            };

            $("#compostDistributionDetails").val(JSON.stringify(distributionData));
            console.log('Saved compost_distribution_details for application year ' + applicationYear + ':',
                distributionData);
        }

        function loadExistingCompostDistribution() {
            let existing = {!! json_encode($gabbinaBiyyee->compost_distribution_details ?? '[]') !!};
            console.log('Loading existing compost distribution:', existing);

            if (existing && typeof existing === 'object') {
                if (Array.isArray(existing)) {
                    compostSummaryRows = existing.map(row => ({
                        year: row.year || applicationYear,
                        quarter: row.quarter,
                        month: row.month,
                        week: row.week,
                        range: row.range || '',
                        compost_type: row.compost_type || '{{ $gabbinaBiyyee->compost_type }}',
                        quantity: parseFloat(row.quantity) || 0,
                        unit: row.unit || unit,
                        status: row.status || calculateStatus(parseFloat(row.quantity || 0))
                    }));
                } else if (existing.weekly_distribution) {
                    compostSummaryRows = existing.weekly_distribution.map(row => ({
                        year: row.year || applicationYear,
                        quarter: row.quarter,
                        month: row.month,
                        week: row.week,
                        range: row.range || '',
                        compost_type: row.compost_type || '{{ $gabbinaBiyyee->compost_type }}',
                        quantity: parseFloat(row.quantity) || 0,
                        unit: row.unit || unit,
                        status: row.status || calculateStatus(parseFloat(row.quantity || 0))
                    }));
                }

                // Restore selections
                compostSummaryRows.forEach(row => {
                    const quarterNum = parseInt(row.quarter.replace('Q', ''));
                    selectedQuarters.add(quarterNum);

                    const monthNum = EC_MONTHS.findIndex(m => m.name === row.month) + 1;
                    if (monthNum > 0) selectedMonths.add(monthNum);
                });

                updateSummaryTable();
                updateQuantitySummary();

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

            // Enhanced distribution validation
            const scheduledQuantity = getCurrentScheduledQuantity();

            if (scheduledQuantity === 0) {
                const proceed = confirm(translations.no_schedule_warning + ' ' + applicationYear + '?');
                if (!proceed) {
                    errors.push(translations.please_add_schedule);
                    isValid = false;
                }
            }

            // Validate compost quantity
            const compostValidation = validateAllSchedules();
            if (!compostValidation.isValid) {
                errors.push(...compostValidation.errors);
                isValid = false;
            }

            // Check for individual input errors
            $('.quantity-input').each(function() {
                if ($(this).hasClass('is-invalid')) {
                    errors.push('{{ __('Some compost schedules have validation errors') }}');
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
            
            .quantity-input {
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
        .quantity-input.is-valid {
            border-color: #28a745;
        }

        .quantity-input.is-invalid {
            border-color: #dc3545;
        }

        /* Table Header Styles - WHITESMOKE with BLACK TEXT */
        table.table thead {
            background-color: whitesmoke !important;
        }
        
        table.table thead th,
        .table thead th {
            background-color: whitesmoke !important;
            border-color: #dee2e6 !important;
            font-weight: 600;
            color: #000000 !important;
            padding: 12px 8px;
        }
        
        table.table thead.thead-light {
            background-color: whitesmoke !important;
        }
        
        table.table thead.thead-light th,
        .table thead.thead-light th {
            background-color: whitesmoke !important;
            border-color: #dee2e6;
            color: #000000 !important;
        }
        
        table.table thead.thead-dark {
            background-color: #343a40 !important;
        }
        
        table.table thead.thead-dark th,
        .table thead.thead-dark th {
            background-color: #343a40 !important;
            border-color: #454d55;
            color: white !important;
        }
        
        /* EXCLUDE card headers from table header styles */
        .card-header,
        .card-header h3,
        .card-header .card-title,
        .card-header .card-tools,
        .card-header .badge {
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
        
        /* Zebra striping for better readability */
        table.table.table-striped tbody tr:nth-of-type(odd),
        .table.table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        table.table.table-striped tbody tr:nth-of-type(even),
        .table.table-striped tbody tr:nth-of-type(even) {
            background-color: white;
        }
        
        /* Selected row highlighting */
        table.table tbody tr.month-row.table-info,
        .table tbody tr.month-row.table-info {
            background-color: rgba(23, 162, 184, 0.15) !important;
        }
        
        /* Card header backgrounds */
        .card-header {
            background-color: rgba(0, 0, 0, 0.03);
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            color: #495057 !important;
        }
        
        /* Hover effects on table rows */
        table.table.table-hover tbody tr:hover,
        .table.table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.1);
        }
        
        /* Reset card titles to their original colors */
        .card-title {
            color: #495057 !important;
        }
        
        .card-success .card-header .card-title {
            color: white !important;
        }
        
        .card-primary .card-header .card-title {
            color: white !important;
        }
        
        .card-info .card-header .card-title {
            color: white !important;
        }
        
        .card-outline .card-header .card-title {
            color: #495057 !important;
        }
    </style>
</x-layout>