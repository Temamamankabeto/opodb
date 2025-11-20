<x-layout>
    <div class="container-fluid py-4 crop-plan-edit-container">
        <!-- Header Section -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <div class="page-header text-center">
                    <div class="header-icon mb-3">
                        <i class="mdi mdi-sprout-edit display-4 text-warning"></i>
                    </div>
                    <h1 class="display-6 fw-bold text-warning mb-2">Edit Crop Plan</h1>
                    <p class="lead text-muted">Update agricultural production plan for {{ Auth::user()->kebele->name }} Kebele</p>
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
                                        <input type="number" name="year" id="year" value="{{ $plan->year }}"
                                            class="form-control form-control-lg" required>
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
                                            @foreach ($cropTypes as $type)
                                                <option value="{{ $type->id }}"
                                                    {{ $plan->croptype_id == $type->id ? 'selected' : '' }}>
                                                    {{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="croptype_id" class="form-label">
                                            <i class="mdi mdi-seed-outline me-1 text-muted"></i>
                                            Crop Type
                                        </label>
                                    </div>
                                </div>

                                {{-- Crop Selection --}}
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-floating">
                                        <select name="crop_id" id="crop_id" class="form-select form-select-lg" required>
                                            <option value="">Select Crop</option>
                                            {{-- will be filled dynamically --}}
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
                                </div>

                                {{-- Season Selection --}}
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-floating">
                                        <select name="season" id="season" class="form-select form-select-lg" required>
                                            <option value="">Select Season</option>
                                            <option value="Ganna" {{ $plan->season == 'Ganna' ? 'selected' : '' }}>Ganna</option>
                                            <option value="Bona" {{ $plan->season == 'Bona' ? 'selected' : '' }}>Bona</option>
                                            <option value="Arfaasaa" {{ $plan->season == 'Arfaasaa' ? 'selected' : '' }}>Arfaasaa</option>
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
                                            value="{{ $plan->area_to_plant }}" class="form-control form-control-lg"
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
                                            value="{{ $plan->seed_required }}" class="form-control form-control-lg"
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
                                            value="{{ $plan->productivity }}" class="form-control form-control-lg"
                                            placeholder="Productivity rate" readonly required>
                                        <label for="productivity" class="form-label">
                                            <i class="mdi mdi-trending-up me-1 text-muted"></i>
                                            Productivity (qt/hectare)
                                        </label>
                                    </div>
                                    <div class="form-text text-muted mt-2">
                                        <i class="mdi mdi-information-outline me-1"></i>
                                        Auto-filled based on selected crop
                                    </div>
                                </div>

                                {{-- Expected Yield --}}
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" step="0.01" id="expected_yield" name="expected_yield"  
                                            value="{{ $plan->expected_yield }}"
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
                                            <span class="ms-2" id="yieldCalculation">
                                                {{ $plan->area_to_plant }} ha × {{ $plan->productivity }} qt/ha = {{ $plan->expected_yield }} qt
                                            </span>
                                        </div>
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
        let cropData = [];
        const selectedCropId = {{ $plan->crop_id }};

        // Initialize form
        $(document).ready(function() {
            console.log('Document ready - initializing edit form...');
            
            // Load crops for existing plan
            const year = $('#year').val();
            const typeId = $('#croptype_id').val();
            if (year && typeId) {
                populateCrops(year, typeId, selectedCropId);
            }

            // Initialize yield calculation
            updateExpectedYield();
        });

        // Function to populate crops with enhanced UI
        function populateCrops(year, typeId, selectedId = null) {
            if (!year || !typeId) return;
            
            showLoadingState('crop');
            
            $.ajax({
                url: `/sector/crops-by-year-type/${year}/${typeId}`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    hideLoadingState('crop');
                    cropData = data;
                    $('#crop_id').empty().append('<option value="">Select Crop</option>');
                    
                    if (data.length === 0) {
                        $('#crop_id').append('<option value="">No crops available</option>');
                        showEmptyState('crop', 'No crops available for selected type and year');
                    } else {
                        data.forEach(c => {
                            let selected = selectedId && selectedId == c.id ? 'selected' : '';
                            $('#crop_id').append(`<option value="${c.id}" ${selected}>${c.name}</option>`);
                        });
                    }

                    // Trigger change to fill productivity/fertilizers if crop already selected
                    if (selectedId) {
                        setTimeout(() => {
                            $('#crop_id').trigger('change');
                        }, 500);
                    }
                },
                error: function(xhr, status, error) {
                    hideLoadingState('crop');
                    console.error('Error loading crops:', error);
                    showErrorState('crop', 'Failed to load crops');
                }
            });
        }

        // On Year change
        $('#year').on('change', function() {
            const year = $(this).val();
            const typeId = $('#croptype_id').val();
            if (year && typeId) {
                populateCrops(year, typeId);
            }
        });

        // On Crop Type change
        $('#croptype_id').on('change', function() {
            const year = $('#year').val();
            const typeId = $(this).val();
            populateCrops(year, typeId);
        });

        // On Crop change with enhanced UI
        $('#crop_id').on('change', function() {
            const selectedId = $(this).val();
            const crop = cropData.find(c => c.id == selectedId);
            
            if (!crop) {
                $('#fertilizer_list').html(`
                    <div class="text-center py-4">
                        <i class="mdi mdi-sprout text-muted display-6 mb-3"></i>
                        <p class="text-muted">Select a crop to view fertilizer requirements...</p>
                    </div>
                `);
                return;
            }

            // Update productivity with animation
            $('#productivity').val(crop.productivity || 0);
            $('#productivity').addClass('highlight-field');
            setTimeout(() => $('#productivity').removeClass('highlight-field'), 2000);
            
            updateExpectedYield();

            // Enhanced fertilizer display
            if (crop.fertilizers && crop.fertilizers.length > 0) {
                let fertArr = typeof crop.fertilizers === 'string' ? JSON.parse(crop.fertilizers) : crop.fertilizers;
                
                let html = `
                    <div class="fertilizer-table-container">
                        <div class="table-responsive">
                            <table class="table table-hover fertilizer-table">
                                <thead class="table-success">
                                    <tr>
                                        <th><i class="mdi mdi-fertilizer me-1"></i> Fertilizer Type</th>
                                        <th class="text-center"><i class="mdi mdi-weight me-1"></i> Quantity per Hectare</th>
                                        <th class="text-center"><i class="mdi mdi-calculator me-1"></i> Total Required</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                
                const area = parseFloat($('#area_to_plant').val()) || 0;
                
                fertArr.forEach(f => {
                    const totalRequired = (f.quantity_per_ha * area).toFixed(2);
                    html += `
                        <tr class="fertilizer-row">
                            <td class="fw-semibold">${f.type}</td>
                            <td class="text-center">${f.quantity_per_ha} kg/ha</td>
                            <td class="text-center fw-bold text-success">${totalRequired} kg</td>
                        </tr>`;
                });
                
                html += `
                                </tbody>
                            </table>
                        </div>
                        <div class="fertilizer-summary mt-3 p-3 bg-light rounded">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <i class="mdi mdi-information-outline text-primary me-2"></i>
                                    <small class="text-muted">Based on planting area of <strong>${area} hectares</strong></small>
                                </div>
                                <div class="col-md-4 text-end">
                                    <span class="badge bg-success fs-6">
                                        Total Items: ${fertArr.length}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>`;
                
                $('#fertilizer_list').html(html).addClass('loaded');
            } else {
                $('#fertilizer_list').html(`
                    <div class="alert alert-warning border-0">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-alert-outline me-2 fs-4"></i>
                            <div>
                                <h6 class="alert-heading mb-1">No Fertilizers Defined</h6>
                                <p class="mb-0">This crop doesn't have specific fertilizer requirements defined in the system.</p>
                            </div>
                        </div>
                    </div>
                `);
            }
        });

        // Enhanced Expected Yield Calculation
        $('#area_to_plant').on('input', function() {
            updateExpectedYield();
            // Update fertilizer totals if they exist
            setTimeout(() => $('#crop_id').trigger('change'), 100);
        });

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

        // Form submission enhancement
        $('#cropPlanForm').on('submit', function() {
            $('#submitBtn').prop('disabled', true).html(
                '<div class="spinner-border spinner-border-sm me-2"></div>Updating...'
            );
        });

        // Helper functions
        function showLoadingState(field) {
            $(`#${field}_loading`).removeClass('d-none');
            $(`#${field}_id`).prop('disabled', true);
        }

        function hideLoadingState(field) {
            $(`#${field}_loading`).addClass('d-none');
            $(`#${field}_id`).prop('disabled', false);
        }

        function showErrorState(field, message) {
            $(`#${field}_error`).removeClass('d-none').find('small').text(message);
        }

        function showEmptyState(field, message) {
            $(`#${field}_empty`).removeClass('d-none').find('small').text(message);
        }
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

        .form-control-lg {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control-lg:focus {
            border-color: #ffa726;
            box-shadow: 0 0 0 0.2rem rgba(255, 167, 38, 0.25);
            transform: translateY(-2px);
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

        .fertilizer-table {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .fertilizer-table th {
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            background: linear-gradient(135deg, #e8f5e8 0%, #d4edda 100%);
        }

        .fertilizer-row:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
            transition: all 0.3s ease;
        }

        .fertilizer-summary {
            border-left: 4px solid #17a2b8;
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

        .btn-outline-secondary, .btn-outline-danger {
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
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
            
            .form-actions .d-flex {
                flex-direction: column;
                gap: 1rem;
            }
            
            .form-actions .btn {
                width: 100%;
            }
        }

        .fertilizer-display.loaded {
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</x-layout>