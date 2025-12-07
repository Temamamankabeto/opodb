<x-layout>
    @section('title', 'Create Livestock Plan')
    
    <div class="content-header">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>
                            <i class="fas fa-plus-circle text-success mr-2"></i>
                            Create Livestock Plan
                        </h1>
                    </div>
                    <div class="col-sm-6">
                         
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Required Fields Info -->
                <div class="alert alert-info mb-4">
                    <h5><i class="icon fas fa-info"></i> Important Information</h5>
                    All fields marked with <span class="text-danger">*</span> are required.
                </div>

                <form action="{{ route('sector.livestock_plans.store') }}" method="POST" class="needs-validation" novalidate id="mainForm">
                    @csrf

                    <!-- Year Selection -->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Year Selection
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="yearSelect">Year <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-calendar"></i>
                                                </span>
                                            </div>
                                            <select name="year" class="form-control select2" id="yearSelect" required style="width: 100%;">
                                                <option value="">-- Select Year --</option>
                                                @foreach ($years as $year)
                                                    <option value="{{ $year }}">{{ $year }} EC</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('year')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Supplies -->
                    <div class="card card-success mt-4">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-truck mr-2"></i>
                                Livestock Supplies <span class="text-warning">*</span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="callout callout-info mb-4">
                                <h5><i class="fas fa-info-circle mr-2"></i> Required Information</h5>
                                <p class="mb-0">Check at least one supply item and enter amount with unit</p>
                            </div>
                            
                            <div class="row" id="suppliesContainer">
                                @foreach ($supplies as $supply)
                                    <div class="col-md-6 mb-3">
                                        <div class="card supply-item">
                                            <div class="card-body">
                                                <div class="custom-control custom-checkbox mb-3">
                                                    <input class="custom-control-input supply-checkbox" 
                                                           type="checkbox" 
                                                           value="{{ $supply->name }}" 
                                                           id="supply-{{ $supply->id }}"
                                                           data-supply-name="{{ $supply->name }}">
                                                    <label class="custom-control-label font-weight-bold" for="supply-{{ $supply->id }}">
                                                        {{ $supply->name }}
                                                    </label>
                                                </div>

                                                <div class="supply-fields" style="display: none;">
                                                    <div class="row">
                                                        <div class="col-12 mb-3">
                                                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                                                            <input type="number" step="0.01" 
                                                                   class="form-control supply-amount" 
                                                                   placeholder="0.00" min="0" disabled>
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="form-label">Unit <span class="text-danger">*</span></label>
                                                            <select class="form-control select2 supply-unit" disabled style="width: 100%;">
                                                                <option value="">-- Select Unit --</option>
                                                                <option value="kg">kg</option>
                                                                <option value="liter">Liter</option>
                                                                <option value="piece">Piece</option>
                                                                <option value="bag">Bag</option>
                                                                <option value="quintal">Quintal</option>
                                                                <option value="ton">Ton</option>
                                                                <option value="box">Box</option>
                                                                <option value="bottle">Bottle</option>
                                                                <option value="pack">Pack</option>
                                                                <option value="unit">Unit</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="alert alert-danger supplies-error mt-3" style="display: none;">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Please select at least one supply item and fill the amount and unit
                            </div>
                            
                            @error('supply_data')
                                <div class="alert alert-danger mt-3">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Products (optional) -->
                    <div class="card card-success mt-4">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-box mr-2"></i>
                                Livestock Products (Optional)
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="callout callout-info mb-4">
                                <h5><i class="fas fa-info-circle mr-2"></i> Optional Information</h5>
                                <p class="mb-0">Check product items if you want to include them in your plan</p>
                            </div>
                            
                            <div class="row" id="productsContainer">
                                @foreach ($products as $product)
                                    <div class="col-md-6 mb-3">
                                        <div class="card product-item">
                                            <div class="card-body">
                                                <div class="custom-control custom-checkbox mb-3">
                                                    <input class="custom-control-input product-checkbox" 
                                                           type="checkbox" 
                                                           value="{{ $product->name }}" 
                                                           id="product-{{ $product->id }}"
                                                           data-product-name="{{ $product->name }}">
                                                    <label class="custom-control-label font-weight-bold" for="product-{{ $product->id }}">
                                                        {{ $product->name }}
                                                    </label>
                                                </div>

                                                <div class="product-fields" style="display: none;">
                                                    <div class="row">
                                                        <div class="col-12 mb-3">
                                                            <label class="form-label">Amount</label>
                                                            <input type="number" step="0.01" 
                                                                   class="form-control product-amount" 
                                                                   placeholder="0.00" min="0" disabled>
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="form-label">Unit</label>
                                                            <select class="form-control select2 product-unit" disabled style="width: 100%;">
                                                                <option value="">-- Select Unit --</option>
                                                                <option value="kg">kg</option>
                                                                <option value="liter">Liter</option>
                                                                <option value="piece">Piece</option>
                                                                <option value="bag">Bag</option>
                                                                <option value="quintal">Quintal</option>
                                                                <option value="ton">Ton</option>
                                                                <option value="box">Box</option>
                                                                <option value="bottle">Bottle</option>
                                                                <option value="pack">Pack</option>
                                                                <option value="unit">Unit</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            @error('product_data')
                                <div class="alert alert-danger mt-3">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="float-right">
                                        <a href="{{ route('sector.livestock_plans.index') }}" class="btn btn-default mr-2">
                                            <i class="fas fa-arrow-left mr-2"></i>Cancel
                                        </a>
                                        <button type="submit" class="btn btn-success px-4" id="submitBtn" disabled>
                                            <i class="fas fa-save mr-2"></i>Save Plan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>

    @section('scripts')
    <script>
        $(function () {
            // Initialize Select2
            $('.select2').select2();

            const yearSelect = $('#yearSelect');
            const submitBtn = $('#submitBtn');
            const suppliesError = $('.supplies-error');
            const mainForm = $('#mainForm');

            let selectedSupplies = new Map();
            let selectedProducts = new Map();

            // Supply checkbox event listeners
            $('.supply-checkbox').on('change', function() {
                const supplyName = $(this).data('supply-name');
                const fieldsContainer = $(this).closest('.supply-item').find('.supply-fields');
                const amountInput = fieldsContainer.find('.supply-amount');
                const unitSelect = fieldsContainer.find('.supply-unit');

                if (this.checked) {
                    fieldsContainer.show();
                    amountInput.prop('disabled', false);
                    unitSelect.prop('disabled', false).trigger('change');
                    $(this).closest('.supply-item').addClass('checked');
                    selectedSupplies.set(supplyName, {
                        amountInput: amountInput,
                        unitSelect: unitSelect,
                        isValid: false
                    });
                } else {
                    fieldsContainer.hide();
                    amountInput.prop('disabled', true);
                    unitSelect.prop('disabled', true).trigger('change');
                    $(this).closest('.supply-item').removeClass('checked');
                    amountInput.val('');
                    unitSelect.val('').trigger('change');
                    amountInput.removeClass('is-valid is-invalid');
                    unitSelect.removeClass('is-valid is-invalid');
                    selectedSupplies.delete(supplyName);
                }
                validateForm();
            });

            // Product checkbox event listeners
            $('.product-checkbox').on('change', function() {
                const productName = $(this).data('product-name');
                const fieldsContainer = $(this).closest('.product-item').find('.product-fields');
                const amountInput = fieldsContainer.find('.product-amount');
                const unitSelect = fieldsContainer.find('.product-unit');

                if (this.checked) {
                    fieldsContainer.show();
                    amountInput.prop('disabled', false);
                    unitSelect.prop('disabled', false).trigger('change');
                    $(this).closest('.product-item').addClass('checked');
                    selectedProducts.set(productName, {
                        amountInput: amountInput,
                        unitSelect: unitSelect
                    });
                } else {
                    fieldsContainer.hide();
                    amountInput.prop('disabled', true);
                    unitSelect.prop('disabled', true).trigger('change');
                    $(this).closest('.product-item').removeClass('checked');
                    amountInput.val('');
                    unitSelect.val('').trigger('change');
                    amountInput.removeClass('is-valid is-invalid');
                    unitSelect.removeClass('is-valid is-invalid');
                    selectedProducts.delete(productName);
                }
            });

            // Input validation for supplies
            $(document).on('input change', '.supply-amount, .supply-unit', function() {
                validateSupplyItem($(this));
                validateForm();
            });

            // Input validation for products
            $(document).on('input change', '.product-amount, .product-unit', function() {
                validateProductItem($(this));
            });

            function validateSupplyItem(input) {
                const supplyItem = input.closest('.supply-item');
                const supplyName = supplyItem.find('.supply-checkbox').data('supply-name');
                const amountInput = supplyItem.find('.supply-amount');
                const unitSelect = supplyItem.find('.supply-unit');

                const amount = parseFloat(amountInput.val());
                const unit = unitSelect.val();

                const isValid = !isNaN(amount) && amount > 0 && unit !== '';

                // Update visual feedback
                if (amountInput.val() !== '') {
                    amountInput.toggleClass('is-valid', !isNaN(amount) && amount > 0);
                    amountInput.toggleClass('is-invalid', isNaN(amount) || amount <= 0);
                } else {
                    amountInput.removeClass('is-valid is-invalid');
                }

                if (unitSelect.val() !== '') {
                    unitSelect.toggleClass('is-valid', unit !== '');
                    unitSelect.toggleClass('is-invalid', unit === '');
                } else {
                    unitSelect.removeClass('is-valid is-invalid');
                }

                // Update selected supplies map
                if (selectedSupplies.has(supplyName)) {
                    selectedSupplies.get(supplyName).isValid = isValid;
                }

                return isValid;
            }

            function validateProductItem(input) {
                const productItem = input.closest('.product-item');
                const amountInput = productItem.find('.product-amount');
                const unitSelect = productItem.find('.product-unit');

                const amount = parseFloat(amountInput.val());
                const unit = unitSelect.val();

                // Update visual feedback
                if (amountInput.val() !== '') {
                    amountInput.toggleClass('is-valid', !isNaN(amount) && amount > 0);
                    amountInput.toggleClass('is-invalid', isNaN(amount) || amount < 0);
                } else {
                    amountInput.removeClass('is-valid is-invalid');
                }

                if (unitSelect.val() !== '') {
                    unitSelect.toggleClass('is-valid', unit !== '');
                    unitSelect.toggleClass('is-invalid', unit === '');
                } else {
                    unitSelect.removeClass('is-valid is-invalid');
                }
            }

            function validateForm() {
                const yearSelected = yearSelect.val() !== '';
                
                // Check if at least one supply is selected and valid
                let atLeastOneValidSupply = false;
                selectedSupplies.forEach((supply, supplyName) => {
                    if (supply.isValid) {
                        atLeastOneValidSupply = true;
                    }
                });

                const canSubmit = yearSelected && atLeastOneValidSupply;
                
                submitBtn.prop('disabled', !canSubmit);
                
                // Show/hide error message
                if (yearSelected && !atLeastOneValidSupply && selectedSupplies.size > 0) {
                    suppliesError.show();
                } else {
                    suppliesError.hide();
                }

                // Visual feedback for submit button
                if (submitBtn.prop('disabled')) {
                    submitBtn.removeClass('btn-success').addClass('btn-secondary');
                } else {
                    submitBtn.removeClass('btn-secondary').addClass('btn-success');
                }

                return canSubmit;
            }

            yearSelect.on('change', validateForm);

            // Handle form submission
            mainForm.on('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please fill all required fields correctly before submitting.'
                    });
                    return;
                }

                // Prepare supply data
                const supplyData = [];
                selectedSupplies.forEach((supply, supplyName) => {
                    if (supply.isValid) {
                        supplyData.push({
                            name: supplyName,
                            amount: parseFloat(supply.amountInput.val()),
                            unit: supply.unitSelect.val()
                        });
                    }
                });

                // Prepare product data (only if products are selected and have valid data)
                const productData = [];
                selectedProducts.forEach((product, productName) => {
                    const amount = parseFloat(product.amountInput.val()) || 0;
                    const unit = product.unitSelect.val();
                    
                    // Only include products with valid data
                    if (amount > 0 && unit !== '') {
                        productData.push({
                            name: productName,
                            amount: amount,
                            unit: unit
                        });
                    }
                });

                // Remove any existing hidden inputs
                $('input[name="supply_data"], input[name="product_data"]').remove();

                // Create hidden input for supply data
                const supplyInput = $('<input>', {
                    type: 'hidden',
                    name: 'supply_data',
                    value: JSON.stringify(supplyData)
                });
                $(this).append(supplyInput);

                // Create hidden input for product data only if there are products
                if (productData.length > 0) {
                    const productInput = $('<input>', {
                        type: 'hidden',
                        name: 'product_data',
                        value: JSON.stringify(productData)
                    });
                    $(this).append(productInput);
                }
            });

            // Bootstrap form validation
            (function () {
                'use strict'
                window.addEventListener('load', function () {
                    var forms = document.getElementsByClassName('needs-validation')
                    var validation = Array.prototype.filter.call(forms, function (form) {
                        form.addEventListener('submit', function (event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault()
                                event.stopPropagation()
                            }
                            form.classList.add('was-validated')
                        }, false)
                    })
                }, false)
            })()
        });
    </script>

    <style>
        .supply-item, .product-item {
            transition: all 0.3s ease;
            cursor: pointer;
            border: 1px solid #dee2e6;
        }
        .supply-item:hover, .product-item:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .supply-item.checked, .product-item.checked {
            border-color: #28a745;
            background-color: #f8fff9;
        }
        .custom-control-input:checked ~ .custom-control-label {
            color: #28a745;
            font-weight: bold;
        }
        .is-valid {
            border-color: #28a745 !important;
        }
        .is-invalid {
            border-color: #dc3545 !important;
        }
        .supply-fields, .product-fields {
            border-left: 3px solid #17a2b8;
            padding-left: 15px;
            margin-left: -5px;
        }
        .btn:disabled {
            cursor: not-allowed;
            opacity: 0.65;
        }
        
        /* AdminLTE card header custom styling */
        .card-success:not(.card-outline) > .card-header {
            background-color: #28a745;
            color: white;
        }
        
        .card-success:not(.card-outline) > .card-header .card-title {
            color: white;
        }
        
        /* Ensure all card headers are green */
        .card > .card-header {
            background-color: #28a745 !important;
            color: white !important;
        }
        
        .card > .card-header .card-title {
            color: white !important;
        }
    </style>
    
    <!-- Include SweetAlert2 for better alerts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @endsection
</x-layout>