<x-layout>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 text-center">
            <div class="col-sm-12">
                <h6 class="text-muted mb-0 fas fa-seedling">
                    {{ __('Agricultural yearly plan for') }}
                </h6>
            </div>
        </div>
    </div>
</section>

<section class="content">

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <h5><i class="icon fas fa-ban"></i> {{ __('Please fix the following errors:') }}</h5>
            <ul class="pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert">×</button>
        </div>
    @endif

    <div class="card card-primary card-outline shadow">
        <div class="card-header bg-primary">
            <h3 class="card-title text-white">
                <i class="fas fa-edit mr-2"></i> {{ __('Crop Plan Details') }}
            </h3>
        </div>

        <form action="{{ route('sector.crop-plans.store') }}" method="POST" id="cropPlanForm">
            @csrf

            <div class="card-body">

                <!-- Basic Information -->
                <div class="callout callout-info">
                    <h5><i class="fas fa-info-circle"></i> {{ __('Basic Information') }}</h5>
                </div>

                <div class="row">

                    <!-- Year -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ __('Planning Year') }}</label>
                            <select name="year" id="year" class="form-control" required>
                                <option value="">{{ __('Select Year') }}</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ old('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Crop Type -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ __('Crop Type') }}</label>
                            <select name="croptype_id" id="croptype_id" class="form-control" required>
                                <option value="">{{ __('Select Crop Type') }}</option>
                            </select>
                            <small id="croptype_loading" class="text-muted d-none">
                                <i class="fas fa-spinner fa-spin"></i> {{ __('Loading crop types...') }}
                            </small>
                            <small id="croptype_error" class="text-danger d-none"></small>
                        </div>
                    </div>

                    <!-- Crop -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ __('Crop Variety') }}</label>
                            <select name="crop_id" id="crop_id" class="form-control" required disabled>
                                <option value="">{{ __('Select Crop') }}</option>
                            </select>
                            <small id="crop_loading" class="text-muted d-none">
                                <i class="fas fa-spinner fa-spin"></i> {{ __('Loading crops...') }}
                            </small>
                            <small id="crop_error" class="text-danger d-none"></small>
                        </div>
                    </div>

                    <!-- Season -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ __('Growing Season') }}</label>
                            <select name="season" id="season" class="form-control" required>
                                <option value="">{{ __('Select Season') }}</option>
                                <option value="Ganna">{{ __('Ganna') }}</option>
                                <option value="Bona">{{ __('Bona') }}</option>
                                <option value="Arfaasaa">{{ __('Arfaasaa') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Area -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ __('Planting Area (hectares)') }}</label>
                            <input type="number" step="0.01" name="area_to_plant" id="area_to_plant"
                                   class="form-control" required>
                        </div>
                    </div>

                    <!-- Seed Required -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ __('Seed Required (kg)') }}</label>
                            <input type="number" step="0.01" name="seed_required" id="seed_required"
                                   class="form-control" required>
                        </div>
                    </div>
                </div>

                <!-- Yield Section -->
                <div class="callout callout-success mt-4">
                    <h5><i class="fas fa-chart-line"></i> {{ __('Yield Estimation') }}</h5>
                </div>

                <div class="row">
                    <!-- Productivity -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('Productivity (qt/hectare)') }}</label>
                            <input type="number" step="0.01" name="productivity" id="productivity"
                                   class="form-control" required>
                        </div>
                    </div>

                    <!-- Expected Yield -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('Total Expected Yield (qt)') }}</label>
                            <input type="number" step="0.01" id="expected_yield"
                                   class="form-control bg-light" readonly>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-footer text-right">
                <a href="{{ route('sector.crop-plans.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                </a>
                <button type="submit" class="btn btn-success" id="submitBtn">
                    <i class="fas fa-save"></i> {{ __('Save Crop Plan') }}
                </button>
            </div>

        </form>
    </div>

</section>

    {{-- JavaScript Section --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let cropData = [];

        // Initialize form with enhanced functionality
        $(document).ready(function() {
            console.log('Document ready - initializing form...');
            
            // Load crop types if year is already selected (for form repopulation)
            if ($('#year').val()) {
                console.log('Year already selected:', $('#year').val());
                loadCropTypes($('#year').val());
            }
        });

        // Load Crop Types for Selected Year - FIXED VERSION
        $('#year').on('change', function() {
            const year = $(this).val();
            console.log('Year changed to:', year);
            
            // Reset dependent fields
            $('#croptype_id').html('<option value="">Select Crop Type</option>').prop('disabled', false);
            $('#crop_id').html('<option value="">Select Crop</option>').prop('disabled', true);
            
            if (!year) {
                $('#croptype_id').prop('disabled', true);
                hideAllStatusMessages();
                return;
            }
            
            showLoadingState('croptype');
            loadCropTypes(year);
        });

        function loadCropTypes(year) {
            console.log('Loading crop types for year:', year);
            
            $.ajax({
                url: `/sector/crops-by-year/${year}`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log('Crop types loaded successfully:', data);
                    hideAllStatusMessages();
                    
                    $('#croptype_id').html('<option value="">Select Crop Type</option>');
                    
                    if (data.length === 0) {
                        $('#croptype_id').append('<option value="">No crop types available</option>');
                        showErrorState('croptype', 'No crop types available for selected year');
                    } else {
                        data.forEach(t => {
                            $('#croptype_id').append(`<option value="${t.id}">${t.name}</option>`);
                        });
                        $('#croptype_id').prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading crop types:', error);
                    console.log('Response:', xhr.responseText);
                    showErrorState('croptype', 'Failed to load crop types');
                    $('#croptype_id').html('<option value="">Error loading crop types</option>');
                }
            });
        }

        // Load Crops for Selected Type - FIXED VERSION
        $('#croptype_id').on('change', function() {
            const year = $('#year').val();
            const typeId = $(this).val();
            
            console.log('Crop type changed to:', typeId, 'Year:', year);
            
            if (!year || !typeId) {
                $('#crop_id').html('<option value="">Select Crop</option>').prop('disabled', true);
                hideAllStatusMessages();
                return;
            }

            showLoadingState('crop');
            loadCrops(year, typeId);
        });

        function loadCrops(year, typeId) {
            console.log('Loading crops for year:', year, 'type:', typeId);
            
            $.ajax({
                url: `/sector/crops-by-year-type/${year}/${typeId}`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log('Crops loaded successfully:', data);
                    hideAllStatusMessages();
                    
                    cropData = data;
                    $('#crop_id').html('<option value="">Select Crop</option>');
                    
                    if (data.length === 0) {
                        $('#crop_id').append('<option value="">No crops available</option>');
                        showErrorState('crop', 'No crops available for selected type');
                    } else {
                        data.forEach(c => {
                            $('#crop_id').append(`<option value="${c.id}">${c.name}</option>`);
                        });
                        $('#crop_id').prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading crops:', error);
                    console.log('Response:', xhr.responseText);
                    showErrorState('crop', 'Failed to load crops');
                    $('#crop_id').html('<option value="">Error loading crops</option>');
                }
            });
        }

        // Set Productivity from Crop Data
        $('#crop_id').on('change', function() {
            const cropId = $(this).val();
            const crop = cropData.find(c => c.id == cropId);
            
            console.log('Crop selected:', crop);
            
            if (crop) {
                $('#productivity').val(crop.productivity || 0);
                $('#productivity').addClass('highlight-field');
                setTimeout(() => $('#productivity').removeClass('highlight-field'), 2000);
            } else {
                $('#productivity').val(0);
            }
            updateExpectedYield();
        });

        // Calculate Expected Yield
        $('#area_to_plant, #productivity').on('input', updateExpectedYield);

        function updateExpectedYield() {
            const area = parseFloat($('#area_to_plant').val()) || 0;
            const prod = parseFloat($('#productivity').val()) || 0;
            const yieldValue = area * prod;
            
            $('#expected_yield').val(yieldValue.toFixed(2));
            
            // Update calculation display
            $('#yieldCalculation').html(
                `${area.toFixed(2)} ha × ${prod.toFixed(2)} qt/ha = ${yieldValue.toFixed(2)} qt`
            );
            
            // Visual feedback
            if (yieldValue > 0) {
                $('#expected_yield').addClass('calculated-field');
            } else {
                $('#expected_yield').removeClass('calculated-field');
            }
        }

        // Enhanced Fertilizer Type Selection
        $('.fertTypeCheckbox').on('change', function() {
            const typeId = this.value;
            const container = $(`#fertilizers_${typeId}`);
            const card = $(this).closest('.fertilizer-type-card');
            
            if (this.checked) {
                card.addClass('active-fertilizer');
                container.html('<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-success me-2"></div>Loading fertilizers...</div>');
                
                fetch(`/sector/get-fertilizers/${typeId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length === 0) {
                            container.html('<div class="alert alert-warning py-2 mb-0">No fertilizers available for this type.</div>');
                            return;
                        }
                        
                        let html = '<div class="fertilizer-list">';
                        data.forEach((f, index) => {
                            html += `
                                <div class="fertilizer-item card border-0 bg-light mb-2">
                                    <div class="card-body py-3">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-md-5">
                                                <div class="form-check">
                                                    <input type="checkbox" name="fertilizers[${typeId}][]" 
                                                        value="${f.id}" id="fert_${typeId}_${f.id}" 
                                                        class="form-check-input fertilizer-checkbox">
                                                    <label for="fert_${typeId}_${f.id}" class="form-check-label fw-medium">
                                                        ${f.name}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group input-group-sm">
                                                    <input type="number" step="0.01" name="amount[${typeId}][]" 
                                                        placeholder="0.00" class="form-control amount-input">
                                                    <span class="input-group-text bg-white">Qty</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <select name="unit[${typeId}][]" class="form-select form-select-sm unit-select">
                                                    <option value="kg">kg</option>
                                                    <option value="qun">Quintal</option>
                                                    <option value="ltr">Liter</option>
                                                    <option value="bag">Bag</option>
                                                    <option value="ton">Ton</option>
                                                    <option value="pack">Pack</option>
                                                    <option value="unit">Unit</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                        });
                        html += '</div>';
                        container.html(html);
                        
                        // Add interactivity to fertilizer items
                        container.find('.fertilizer-checkbox').on('change', function() {
                            const item = $(this).closest('.fertilizer-item');
                            if (this.checked) {
                                item.addClass('selected-fertilizer');
                            } else {
                                item.removeClass('selected-fertilizer');
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error loading fertilizers:', error);
                        container.html('<div class="alert alert-danger py-2 mb-0">Failed to load fertilizers.</div>');
                    });
            } else {
                card.removeClass('active-fertilizer');
                container.html('');
            }
        });

        // Form submission enhancement
        $('#cropPlanForm').on('submit', function() {
            $('#submitBtn').prop('disabled', true).html(
                '<div class="spinner-border spinner-border-sm me-2"></div>Saving...'
            );
        });

        // Helper functions for status messages
        function showLoadingState(field) {
            hideAllStatusMessages();
            $(`#${field}_loading`).removeClass('d-none');
            $(`#${field}_id`).prop('disabled', true);
        }

        function showErrorState(field, message) {
            hideAllStatusMessages();
            $(`#${field}_error`).removeClass('d-none').find('small').text(message);
            $(`#${field}_id`).prop('disabled', false);
        }

        function hideAllStatusMessages() {
            $('#croptype_loading, #croptype_error, #crop_loading, #crop_error').addClass('d-none');
        }

        // Initialize yield calculation on page load
        updateExpectedYield();

        // Debug helper - log all changes
        $('#year, #croptype_id, #crop_id').on('change', function() {
            console.log(`${this.id} changed to:`, $(this).val());
        });
    </script>

 
</x-layout>