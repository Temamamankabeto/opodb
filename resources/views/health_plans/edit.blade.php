<x-layout>
    <div class="content-header">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Health Plan</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Edit Health Plan Information</h3>
                            </div>
                            
                           <form action="{{ route('sector.health_plans.update', $plan->id) }}" method="POST">
    @csrf
    @method('PUT')
                                
                                <div class="card-body">
                                    <!-- Year Selection -->
                                    @php
                                        $currentYear = \App\Helpers\EthiopianYear::now();
                                        $years = range($currentYear - 2, $currentYear + 3);
                                    @endphp
                                    
                                    <div class="form-group">
                                        <label for="yearSelect" class="font-weight-bold">Year <span class="text-danger">*</span></label>
                                        <select name="year" class="form-control @error('year') is-invalid @enderror" 
                                                id="yearSelect" required>
                                            <option value="">-- Select Year --</option>
                                            @foreach ($years as $year)
                                                <option value="{{ $year }}" 
                                                    {{ (old('year', $plan->year) == $year) ? 'selected' : '' }}>
                                                    {{ $year }} EC
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('year')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <hr>
                                    
                                    <div class="row">
                                        <div class="col-12">
                                            <h5>Health Activities</h5>
                                            <p class="text-muted">Select activities and fill in the details</p>
                                        </div>
                                    </div>

                                    @foreach($activities as $item)
                                        @php
                                            $saved = $plan->health_activity_name[$item->id] ?? null;
                                            $isSelected = $saved || old("health_activity_name.{$item->id}.selected");
                                            $amountValue = old("health_activity_name.{$item->id}.amount", $saved['amount'] ?? '');
                                            $budgetValue = old("health_activity_name.{$item->id}.budget", $saved['budget'] ?? '');
                                        @endphp
                                        
                                        <div class="card card-outline card-info mb-3 activity-card">
                                            <div class="card-body">
                                                <div class="form-check">
                                                    <input type="checkbox"
                                                           name="health_activity_name[{{ $item->id }}][selected]"
                                                           value="1"
                                                           id="activity_{{ $item->id }}"
                                                           class="form-check-input activity-checkbox"
                                                           {{ $isSelected ? 'checked' : '' }}
                                                           data-activity-name="{{ $item->name }}"
                                                           data-activity-unit="{{ $item->unit }}"
                                                           data-activity-type="{{ $item->type }}">
                                                    <label class="form-check-label font-weight-bold" for="activity_{{ $item->id }}">
                                                        {{ $item->name }}
                                                        @if($item->type)
                                                            <small class="text-muted">({{ $item->type }})</small>
                                                        @endif
                                                    </label>
                                                </div>
                                                
                                                <div class="row mt-3 activity-fields" style="{{ $isSelected ? 'display: block;' : 'display: none;' }}">
                                                    <!-- Hidden type field -->
                                                    <input type="hidden" 
                                                           name="health_activity_name[{{ $item->id }}][type]" 
                                                           value="{{ $item->type }}">
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Amount <small class="text-muted">(Unit: {{ $item->unit }})</small></label>
                                                            <div class="input-group">
                                                                <input type="number" 
                                                                       step="0.01" 
                                                                       placeholder="Enter amount" 
                                                                       name="health_activity_name[{{ $item->id }}][amount]"
                                                                       value="{{ $amountValue }}"
                                                                       class="form-control activity-amount"
                                                                       min="0">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">{{ $item->unit }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Budget <small class="text-muted">(in Birr)</small></label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">ETB</span>
                                                                </div>
                                                                <input type="number" 
                                                                       step="0.01" 
                                                                       placeholder="Enter budget" 
                                                                       name="health_activity_name[{{ $item->id }}][budget]"
                                                                       value="{{ $budgetValue }}"
                                                                       class="form-control activity-budget"
                                                                       min="0">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">Birr</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Calculation Summary -->
                                                    <div class="col-12">
                                                        <div class="border rounded p-2 bg-light">
                                                            <div class="row text-center">
                                                                <div class="col-6 border-right">
                                                                    <small class="text-muted d-block">Amount</small>
                                                                    <span class="font-weight-bold activity-total-amount">
                                                                        {{ ($amountValue > 0) ? number_format($amountValue, 2) : '0.00' }} {{ $item->unit }}
                                                                    </span>
                                                                </div>
                                                                <div class="col-6">
                                                                    <small class="text-muted d-block">Budget</small>
                                                                    <span class="font-weight-bold text-success activity-total-budget">
                                                                        ETB {{ ($budgetValue > 0) ? number_format($budgetValue, 2) : '0.00' }} Birr
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @error('health_activity_name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Health Plan
                                    </button>
                                    <a href="{{ route('sector.health_plans.index') }}" class="btn btn-default">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-layout>

 
<script>
    $(document).ready(function() {
        // Show/hide amount and budget fields when checkbox is checked
        $('.activity-checkbox').change(function() {
            var fields = $(this).closest('.activity-card').find('.activity-fields');
            if ($(this).is(':checked')) {
                fields.show('fast');
            } else {
                fields.hide('fast');
                fields.find('input[type="number"]').val(''); // Clear values when unchecked
                updateActivityCalculations($(this).closest('.activity-card'));
            }
        });

        // Update calculations when amount or budget changes
        $('.activity-amount, .activity-budget').on('input', function() {
            updateActivityCalculations($(this).closest('.activity-card'));
        });

        function updateActivityCalculations(card) {
            var amountInput = card.find('.activity-amount');
            var budgetInput = card.find('.activity-budget');
            var unit = card.find('.activity-checkbox').data('activity-unit');
            
            var amount = parseFloat(amountInput.val()) || 0;
            var budget = parseFloat(budgetInput.val()) || 0;
            
            // Update amount display
            card.find('.activity-total-amount').text(
                amount.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ' + unit
            );
            
            // Update budget display
            card.find('.activity-total-budget').text(
                'ETB ' + budget.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' Birr'
            );
            
            // Update validation styling
            updateValidationStyling(amountInput, budgetInput);
        }

        function updateValidationStyling(amountInput, budgetInput) {
            var amount = parseFloat(amountInput.val()) || 0;
            var budget = parseFloat(budgetInput.val()) || 0;
            
            if (amount > 0 && budget > 0) {
                amountInput.removeClass('is-invalid').addClass('is-valid');
                budgetInput.removeClass('is-invalid').addClass('is-valid');
            } else {
                amountInput.removeClass('is-valid');
                budgetInput.removeClass('is-valid');
                if (amountInput.val() && amount <= 0) {
                    amountInput.addClass('is-invalid');
                }
                if (budgetInput.val() && budget <= 0) {
                    budgetInput.addClass('is-invalid');
                }
            }
        }

        // Initialize validation for existing values
        $('.activity-card').each(function() {
            var card = $(this);
            var amountInput = card.find('.activity-amount');
            var budgetInput = card.find('.activity-budget');
            updateValidationStyling(amountInput, budgetInput);
            
            // Initialize calculations
            updateActivityCalculations(card);
        });
    });
</script>
 

<style>
    .activity-card {
        transition: all 0.3s ease;
    }

    .activity-card:hover {
        background: #f8f9fa;
        transform: translateY(-2px);
    }

    .activity-checkbox:checked ~ .form-check-label {
        color: #17a2b8;
    }

    .is-valid {
        border-color: #28a745 !important;
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }

    .activity-fields {
        border-left: 3px solid #17a2b8;
        padding-left: 12px;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>