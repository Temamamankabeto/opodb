<x-layout>
    <div class="container-fluid py-4 crop-plan-create-container">
        <!-- Header Section -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <div class="page-header text-center">
                    <div class="header-icon mb-3">
                        <i class="mdi mdi-sprout-outline display-4 text-success"></i>
                    </div>
                    <h1 class="display-6 fw-bold text-success mb-2">Create New Crop Plan</h1>
                    <p class="lead text-muted">Add agricultural production plan for {{ Auth::user()->kebele->name }} Kebele</p>
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

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card form-card border-0 shadow-lg">
                    <div class="card-header bg-gradient-primary text-white py-4">
                        <h4 class="mb-0">
                            <i class="mdi mdi-file-document-outline me-2"></i>
                            Crop Plan Details
                        </h4>
                    </div>
                    <div class="card-body p-5">
                        <form action="{{ route('sector.crop-plans.store') }}" method="POST" id="cropPlanForm">
                            @csrf

                            <!-- Basic Information Section -->
                            <div class="section-header mb-4">
                                <h5 class="section-title">
                                    <i class="mdi mdi-information-outline me-2 text-primary"></i>
                                    Basic Information
                                </h5>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row g-4">
                                {{-- Year Selection --}}
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-floating">
                                        <select name="year" id="year" class="form-select form-select-lg" required>
                                            <option value="">Select Year</option>
                                            @foreach($years as $year)
                                                <option value="{{ $year }}" {{ old('year') == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="year" class="form-label">
                                            <i class="mdi mdi-calendar me-1 text-muted"></i>
                                            Planning Year
                                        </label>
                                    </div>
                                </div>

                                {{-- Crop Type --}}
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-floating">
                                        <select name="croptype_id" id="croptype_id" class="form-select form-select-lg" required>
                                            <option value="">Select Crop Type</option>
                                            {{-- Crop types will be loaded dynamically --}}
                                        </select>
                                        <label for="croptype_id" class="form-label">
                                            <i class="mdi mdi-seed-outline me-1 text-muted"></i>
                                            Crop Type
                                        </label>
                                    </div>
                                    <div id="croptype_loading" class="d-none mt-2">
                                        <small class="text-muted">
                                            <i class="mdi mdi-loading mdi-spin me-1"></i>
                                            Loading crop types...
                                        </small>
                                    </div>
                                    <div id="croptype_error" class="d-none mt-2">
                                        <small class="text-danger">
                                            <i class="mdi mdi-alert-circle-outline me-1"></i>
                                            Failed to load crop types
                                        </small>
                                    </div>
                                </div>

                                {{-- Crop Selection --}}
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-floating">
                                        <select name="crop_id" id="crop_id" class="form-select form-select-lg" required disabled>
                                            <option value="">Select Crop</option>
                                            {{-- Crops will be loaded dynamically --}}
                                        </select>
                                        <label for="crop_id" class="form-label">
                                            <i class="mdi mdi-sprout me-1 text-muted"></i>
                                            Crop Variety
                                        </label>
                                    </div>
                                    <div id="crop_loading" class="d-none mt-2">
                                        <small class="text-muted">
                                            <i class="mdi mdi-loading mdi-spin me-1"></i>
                                            Loading crops...
                                        </small>
                                    </div>
                                    <div id="crop_error" class="d-none mt-2">
                                        <small class="text-danger">
                                            <i class="mdi mdi-alert-circle-outline me-1"></i>
                                            Failed to load crops
                                        </small>
                                    </div>
                                </div>

                                {{-- Season Selection --}}
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-floating">
                                        <select name="season" id="season" class="form-select form-select-lg" required>
                                            <option value="">Select Season</option>
                                            <option value="Ganna" {{ old('season') == 'Ganna' ? 'selected' : '' }}>Ganna</option>
                                            <option value="Bona" {{ old('season') == 'Bona' ? 'selected' : '' }}>Bona</option>
                                            <option value="Arfaasaa" {{ old('season') == 'Arfaasaa' ? 'selected' : '' }}>Arfaasaa</option>
                                        </select>
                                        <label for="season" class="form-label">
                                            <i class="mdi mdi-weather-sunny me-1 text-muted"></i>
                                            Growing Season
                                        </label>
                                    </div>
                                </div>

                                {{-- Area to Plant --}}
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-floating">
                                        <input type="number" step="0.01" name="area_to_plant" id="area_to_plant"
                                            class="form-control form-control-lg" value="{{ old('area_to_plant') }}"
                                            placeholder="Area in hectares" required>
                                        <label for="area_to_plant" class="form-label">
                                            <i class="mdi mdi-earth me-1 text-muted"></i>
                                            Planting Area (hectares)
                                        </label>
                                    </div>
                                </div>

                                {{-- Seed Required --}}
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-floating">
                                        <input type="number" step="0.01" name="seed_required" id="seed_required"
                                            class="form-control form-control-lg" value="{{ old('seed_required') }}"
                                            placeholder="Seed quantity" required>
                                        <label for="seed_required" class="form-label">
                                            <i class="mdi mdi-seed me-1 text-muted"></i>
                                            Seed Required (kg)
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Productivity & Yield Section -->
                            <div class="section-header mt-5 mb-4">
                                <h5 class="section-title">
                                    <i class="mdi mdi-chart-line me-2 text-success"></i>
                                    Yield Estimation
                                </h5>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row g-4">
                                {{-- Productivity --}}
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" step="0.01" name="productivity" id="productivity"
                                            class="form-control form-control-lg" value="{{ old('productivity') }}"
                                            placeholder="Productivity rate" required>
                                        <label for="productivity" class="form-label">
                                            <i class="mdi mdi-trending-up me-1 text-muted"></i>
                                            Productivity (qt/hectare)
                                        </label>
                                    </div>
                                    <div class="form-text text-muted mt-2">
                                        <i class="mdi mdi-information-outline me-1"></i>
                                        Expected yield per hectare in quintals
                                    </div>
                                </div>

                                {{-- Expected Yield --}}
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" step="0.01" id="expected_yield"
                                            class="form-control form-control-lg bg-light" readonly>
                                        <label for="expected_yield" class="form-label">
                                            <i class="mdi mdi-scale me-1 text-muted"></i>
                                            Total Expected Yield (qt)
                                        </label>
                                    </div>
                                    <div class="yield-preview mt-3 p-3 rounded bg-success bg-opacity-10">
                                        <div class="d-flex align-items-center">
                                            <i class="mdi mdi-calculator text-success me-2"></i>
                                            <span class="fw-semibold">Calculation:</span>
                                            <span class="ms-2" id="yieldCalculation">Area × Productivity = Yield</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fertilizer Section -->
                            <div class="section-header mt-5 mb-4">
                                <h5 class="section-title">
                                    <i class="mdi mdi-chemical-weapon me-2 text-warning"></i>
                                    Fertilizer Management
                                </h5>
                                <div class="section-divider"></div>
                            </div>

                            <div class="fertilizer-section">
                                <div class="alert alert-info border-0 mb-4">
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-information-outline me-2 fs-5"></i>
                                        <div>
                                            <strong>Fertilizer Planning:</strong> Select fertilizer types and specify quantities with appropriate units for each crop requirement.
                                        </div>
                                    </div>
                                </div>

                                <div id="fertilizerTypesContainer" class="row g-3">
                                    @foreach($fertilizerTypes as $type)
                                        <div class="col-lg-6">
                                            <div class="fertilizer-type-card card border-0 shadow-sm h-100">
                                                <div class="card-body">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input fertTypeCheckbox"
                                                            id="fertType{{ $type->id }}" value="{{ $type->id }}"
                                                            name="fertilizer_types[]" {{ in_array($type->id, old('fertilizer_types', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-bold text-dark d-flex align-items-center" for="fertType{{ $type->id }}">
                                                            <i class="mdi mdi-fertilizer me-2 text-success"></i>
                                                            {{ $type->name }}
                                                        </label>
                                                    </div>
                                                    <div id="fertilizers_{{ $type->id }}" class="fertilizer-items mt-3 ps-4 border-start border-3 border-success"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="section-header mt-5 mb-4">
                                <h5 class="section-title">
                                    <i class="mdi mdi-account-circle-outline me-2 text-info"></i>
                                    Additional Information
                                </h5>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row g-4">
                                {{-- Responsible Staff --}}
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="responsible_staff" class="form-control form-control-lg"
                                            value="{{ old('responsible_staff') }}" placeholder="Staff name">
                                        <label for="responsible_staff" class="form-label">
                                            <i class="mdi mdi-account-tie me-1 text-muted"></i>
                                            Responsible Staff
                                        </label>
                                    </div>
                                </div>

                                {{-- Notes --}}
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea name="notes" rows="4" class="form-control form-control-lg" 
                                            placeholder="Additional notes">{{ old('notes') }}</textarea>
                                        <label for="notes" class="form-label">
                                            <i class="mdi mdi-note-text-outline me-1 text-muted"></i>
                                            Plan Notes & Remarks
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="form-actions mt-5 pt-4 border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('sector.crop-plans.index') }}" class="btn btn-outline-secondary btn-lg">
                                        <i class="mdi mdi-arrow-left me-2"></i>
                                        Back to Plans
                                    </a>
                                    <button type="submit" class="btn btn-success btn-lg px-5" id="submitBtn">
                                        <i class="mdi mdi-content-save-outline me-2"></i>
                                        Save Crop Plan
                                    </button>
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

    <style>
        /* Your existing CSS styles remain the same */
        .crop-plan-create-container {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .form-card {
            border-radius: 20px;
            overflow: hidden;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }



     .crop-plan-create-container {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .form-card {
            border-radius: 20px;
            overflow: hidden;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
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
            color: #28a745;
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
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 2px;
            width: 60px;
        }

        .form-floating > .form-control:focus,
        .form-floating > .form-control:not(:placeholder-shown) {
            padding-top: 1.625rem;
            padding-bottom: 0.625rem;
        }

        .form-control-lg {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control-lg:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            transform: translateY(-2px);
        }

        .fertilizer-type-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .fertilizer-type-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .fertilizer-type-card.active-fertilizer {
            border-color: #28a745;
            background: linear-gradient(135deg, #f8fff9 0%, #e8f5e8 100%);
        }

        .fertilizer-item {
            transition: all 0.3s ease;
            border: 1px solid #dee2e6;
        }

        .fertilizer-item:hover {
            border-color: #28a745;
        }

        .fertilizer-item.selected-fertilizer {
            border-color: #28a745;
            background: linear-gradient(135deg, #e8f5e8 0%, #d4edda 100%);
        }

        .highlight-field {
            animation: highlight 2s ease;
            background-color: #fff3cd !important;
        }

        @keyframes highlight {
            0% { background-color: #fff3cd; }
            100% { background-color: white; }
        }

        .calculated-field {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%) !important;
            color: #155724;
            font-weight: 600;
        }

        .yield-preview {
            border-left: 4px solid #28a745;
        }

        .custom-alert {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .form-actions {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            margin-left: -2.5rem;
            margin-right: -2.5rem;
            padding-left: 2.5rem;
            padding-right: 2.5rem;
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        }

        .btn-outline-secondary {
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .amount-input, .unit-select {
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .amount-input:focus, .unit-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        /* Responsive adjustments */
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
            
            .fertilizer-item .row > div {
                margin-bottom: 0.5rem;
            }
        }
    </style>
</x-layout>