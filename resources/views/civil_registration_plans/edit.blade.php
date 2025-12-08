<x-layout>
    <div class="content-header">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Civil Registration Plan</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> Validation Error!</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Edit Civil Registration Plan Information</h3>
                            </div>
                            
                            <form action="{{ route('sector.civil_registration_plans.update', $plan->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="card-body">
                                    <!-- Year Selection -->
                                    <div class="form-group">
                                        <label for="yearSelect" class="font-weight-bold">Year <span class="text-danger">*</span></label>
                                        <select name="year" class="form-control @error('year') is-invalid @enderror" 
                                                id="yearSelect" required>
                                            <option value="">-- Select Year --</option>
                                            @foreach ($years as $year)
                                                <option value="{{ $year }}" {{ old('year', $plan->year) == $year ? 'selected' : '' }}>
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
                                            <h5>Civil Registration Activities</h5>
                                            <p class="text-muted">Select activities and fill in the details</p>
                                        </div>
                                    </div>

                                    @foreach($activities as $item)
                                        @php
                                            $saved = $plan->civil_registration_activities[$item->id] ?? null;
                                            $isSelected = $saved || old("civil_registration_activities.{$item->id}.selected");
                                            $quantityValue = old("civil_registration_activities.{$item->id}.quantity", $saved['quantity'] ?? '');
                                        @endphp
                                        
                                        <div class="card card-outline card-info mb-3 activity-card">
                                            <div class="card-body">
                                                <div class="form-check">
                                                    <input type="checkbox" 
                                                           name="civil_registration_activities[{{ $item->id }}][selected]" 
                                                           value="1" 
                                                           id="activity_{{ $item->id }}"
                                                           class="form-check-input activity-checkbox"
                                                           {{ $isSelected ? 'checked' : '' }}
                                                           data-activity-name="{{ $item->name }}"
                                                           data-activity-unit="{{ $item->unit }}">
                                                    <label class="form-check-label font-weight-bold" for="activity_{{ $item->id }}">
                                                        {{ $item->name }}
                                                        @if($item->unit)
                                                            <small class="text-muted">(Unit: {{ $item->unit }})</small>
                                                        @endif
                                                    </label>
                                                </div>
                                                
                                                <div class="row mt-3 activity-fields" style="{{ $isSelected ? 'display: block;' : 'display: none;' }}">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Quantity <small class="text-muted">(Unit: {{ $item->unit }})</small></label>
                                                            <div class="input-group">
                                                                <input type="number" 
                                                                       step="0.01" 
                                                                       placeholder="Enter quantity" 
                                                                       name="civil_registration_activities[{{ $item->id }}][quantity]"
                                                                       value="{{ $quantityValue }}"
                                                                       class="form-control activity-quantity"
                                                                       min="0">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">{{ $item->unit }}</span>
                                                                </div>
                                                            </div>
                                                            @error("civil_registration_activities.{$item->id}.quantity")
                                                                <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Calculation Summary -->
                                                    <div class="col-12">
                                                        <div class="border rounded p-2 bg-light">
                                                            <div class="row text-center">
                                                                <div class="col-12">
                                                                    <small class="text-muted d-block">Quantity</small>
                                                                    <span class="font-weight-bold activity-total-quantity">
                                                                        {{ ($quantityValue > 0) ? number_format($quantityValue, 2) : '0.00' }} {{ $item->unit }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @error('civil_registration_activities')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Civil Registration Plan
                                    </button>
                                    <a href="{{ route('sector.civil_registration_plans.index') }}" class="btn btn-default">
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

@push('scripts')
<script>
    $(document).ready(function() {
        // Show/hide quantity fields when checkbox is checked
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

        // Update calculations when quantity changes
        $('.activity-quantity').on('input', function() {
            updateActivityCalculations($(this).closest('.activity-card'));
        });

        function updateActivityCalculations(card) {
            var quantityInput = card.find('.activity-quantity');
            var unit = card.find('.activity-checkbox').data('activity-unit');
            
            var quantity = parseFloat(quantityInput.val()) || 0;
            
            // Update quantity display
            card.find('.activity-total-quantity').text(
                quantity.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ' + unit
            );
            
            // Update validation styling
            updateValidationStyling(quantityInput);
        }

        function updateValidationStyling(quantityInput) {
            var quantity = parseFloat(quantityInput.val()) || 0;
            
            if (quantity > 0) {
                quantityInput.removeClass('is-invalid').addClass('is-valid');
            } else {
                quantityInput.removeClass('is-valid');
                if (quantityInput.val() && quantity <= 0) {
                    quantityInput.addClass('is-invalid');
                }
            }
        }

        // Initialize validation and calculations for existing values
        $('.activity-card').each(function() {
            var card = $(this);
            var quantityInput = card.find('.activity-quantity');
            updateValidationStyling(quantityInput);
            
            // Initialize calculations
            updateActivityCalculations(card);
            
            // If checkbox is checked, ensure fields are visible
            var checkbox = card.find('.activity-checkbox');
            if (checkbox.is(':checked')) {
                card.find('.activity-fields').show();
            }
        });
    });
</script>
@endpush

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