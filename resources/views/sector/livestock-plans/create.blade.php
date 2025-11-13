<x-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0"><i class="bi bi-plus-circle text-success me-2"></i>Create Livestock Plan</h3>
            <div class="badge bg-light text-dark fs-6">
                <i class="bi bi-info-circle me-1"></i>
                All fields marked with <span class="text-danger">*</span> are required
            </div>
        </div>

        <form action="{{ route('sector.livestock_plans.store') }}" method="POST" class="needs-validation" novalidate id="mainForm">
            @csrf

            <!-- Year Selection -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar3 me-2"></i>
                        Year Selection
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Year <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-calendar3 text-muted"></i>
                                </span>
                                <select name="year" class="form-select border-start-0 ps-0" id="yearSelect" required>
                                    <option value="">-- Select Year --</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}">{{ $year }} EC</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('year')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Supplies -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-truck me-2"></i>Livestock Supplies <span class="text-warning">*</span></h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info py-2 mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Required:</strong> Check at least one supply item and enter amount with unit
                    </div>
                    <div class="row g-3" id="suppliesContainer">
                        @foreach ($supplies as $supply)
                            <div class="col-md-6">
                                <div class="card border supply-item">
                                    <div class="card-body">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input supply-checkbox"
                                                   type="checkbox"
                                                   value="{{ $supply->name }}"
                                                   id="supply-{{ $supply->id }}"
                                                   data-supply-name="{{ $supply->name }}">
                                            <label class="form-check-label fw-semibold" for="supply-{{ $supply->id }}">
                                                {{ $supply->name }}
                                            </label>
                                        </div>

                                        <div class="supply-fields" style="display: none;">
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <label class="form-label small">Amount <span class="text-danger">*</span></label>
                                                    <input type="number" step="0.01"
                                                           class="form-control supply-amount"
                                                           placeholder="0.00" min="0" disabled>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label small">Unit <span class="text-danger">*</span></label>
                                                    <select class="form-select supply-unit" disabled>
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
                    <div class="text-danger small mt-2 supplies-error" style="display: none;">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Please select at least one supply item and fill the amount and unit
                    </div>
                    @error('supply_data')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Products (optional) -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-warning text-dark py-3">
                    <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Livestock Products (Optional)</h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info py-2 mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Optional:</strong> Check product items if you want to include them in your plan
                    </div>
                    <div class="row g-3" id="productsContainer">
                        @foreach ($products as $product)
                            <div class="col-md-6">
                                <div class="card border product-item">
                                    <div class="card-body">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input product-checkbox"
                                                   type="checkbox"
                                                   value="{{ $product->name }}"
                                                   id="product-{{ $product->id }}"
                                                   data-product-name="{{ $product->name }}">
                                            <label class="form-check-label fw-semibold" for="product-{{ $product->id }}">
                                                {{ $product->name }}
                                            </label>
                                        </div>

                                        <div class="product-fields" style="display: none;">
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <label class="form-label small">Amount</label>
                                                    <input type="number" step="0.01" 
                                                           class="form-control product-amount" 
                                                           placeholder="0.00" min="0" disabled>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label small">Unit</label>
                                                    <select class="form-select product-unit" disabled>
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
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-end gap-3 border-top pt-4">
                <a href="{{ route('sector.livestock_plans.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-success px-4" id="submitBtn" disabled>
                    <i class="bi bi-save me-2"></i>Save Plan
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const yearSelect = document.getElementById('yearSelect');
            const submitBtn = document.getElementById('submitBtn');
            const suppliesError = document.querySelector('.supplies-error');
            const mainForm = document.getElementById('mainForm');

            let selectedSupplies = new Map();
            let selectedProducts = new Map();

            // Supply checkbox event listeners
            document.querySelectorAll('.supply-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const supplyName = this.getAttribute('data-supply-name');
                    const fieldsContainer = this.closest('.supply-item').querySelector('.supply-fields');
                    const amountInput = fieldsContainer.querySelector('.supply-amount');
                    const unitSelect = fieldsContainer.querySelector('.supply-unit');

                    if (this.checked) {
                        fieldsContainer.style.display = 'block';
                        amountInput.disabled = false;
                        unitSelect.disabled = false;
                        this.closest('.supply-item').classList.add('checked');
                        selectedSupplies.set(supplyName, {
                            amountInput: amountInput,
                            unitSelect: unitSelect,
                            isValid: false
                        });
                    } else {
                        fieldsContainer.style.display = 'none';
                        amountInput.disabled = true;
                        unitSelect.disabled = true;
                        this.closest('.supply-item').classList.remove('checked');
                        amountInput.value = '';
                        unitSelect.value = '';
                        amountInput.classList.remove('is-valid', 'is-invalid');
                        unitSelect.classList.remove('is-valid', 'is-invalid');
                        selectedSupplies.delete(supplyName);
                    }
                    validateForm();
                });
            });

            // Product checkbox event listeners
            document.querySelectorAll('.product-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const productName = this.getAttribute('data-product-name');
                    const fieldsContainer = this.closest('.product-item').querySelector('.product-fields');
                    const amountInput = fieldsContainer.querySelector('.product-amount');
                    const unitSelect = fieldsContainer.querySelector('.product-unit');

                    if (this.checked) {
                        fieldsContainer.style.display = 'block';
                        amountInput.disabled = false;
                        unitSelect.disabled = false;
                        this.closest('.product-item').classList.add('checked');
                        selectedProducts.set(productName, {
                            amountInput: amountInput,
                            unitSelect: unitSelect
                        });
                    } else {
                        fieldsContainer.style.display = 'none';
                        amountInput.disabled = true;
                        unitSelect.disabled = true;
                        this.closest('.product-item').classList.remove('checked');
                        amountInput.value = '';
                        unitSelect.value = '';
                        amountInput.classList.remove('is-valid', 'is-invalid');
                        unitSelect.classList.remove('is-valid', 'is-invalid');
                        selectedProducts.delete(productName);
                    }
                });
            });

            // Input validation for supplies
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('supply-amount') || e.target.classList.contains('supply-unit')) {
                    validateSupplyItem(e.target);
                }
                validateForm();
            });

            // Input validation for products
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('product-amount') || e.target.classList.contains('product-unit')) {
                    validateProductItem(e.target);
                }
            });

            function validateSupplyItem(input) {
                const supplyItem = input.closest('.supply-item');
                const supplyName = supplyItem.querySelector('.supply-checkbox').getAttribute('data-supply-name');
                const amountInput = supplyItem.querySelector('.supply-amount');
                const unitSelect = supplyItem.querySelector('.supply-unit');

                const amount = parseFloat(amountInput.value);
                const unit = unitSelect.value;

                const isValid = !isNaN(amount) && amount > 0 && unit !== '';

                // Update visual feedback
                if (amountInput.value !== '') {
                    amountInput.classList.toggle('is-valid', !isNaN(amount) && amount > 0);
                    amountInput.classList.toggle('is-invalid', isNaN(amount) || amount <= 0);
                } else {
                    amountInput.classList.remove('is-valid', 'is-invalid');
                }

                if (unitSelect.value !== '') {
                    unitSelect.classList.toggle('is-valid', unit !== '');
                    unitSelect.classList.toggle('is-invalid', unit === '');
                } else {
                    unitSelect.classList.remove('is-valid', 'is-invalid');
                }

                // Update selected supplies map
                if (selectedSupplies.has(supplyName)) {
                    selectedSupplies.get(supplyName).isValid = isValid;
                }

                return isValid;
            }

            function validateProductItem(input) {
                const productItem = input.closest('.product-item');
                const amountInput = productItem.querySelector('.product-amount');
                const unitSelect = productItem.querySelector('.product-unit');

                const amount = parseFloat(amountInput.value);
                const unit = unitSelect.value;

                // Update visual feedback
                if (amountInput.value !== '') {
                    amountInput.classList.toggle('is-valid', !isNaN(amount) && amount > 0);
                    amountInput.classList.toggle('is-invalid', isNaN(amount) || amount < 0);
                } else {
                    amountInput.classList.remove('is-valid', 'is-invalid');
                }

                if (unitSelect.value !== '') {
                    unitSelect.classList.toggle('is-valid', unit !== '');
                    unitSelect.classList.toggle('is-invalid', unit === '');
                } else {
                    unitSelect.classList.remove('is-valid', 'is-invalid');
                }
            }

            function validateForm() {
                const yearSelected = yearSelect.value !== '';
                
                // Check if at least one supply is selected and valid
                let atLeastOneValidSupply = false;
                selectedSupplies.forEach((supply, supplyName) => {
                    if (supply.isValid) {
                        atLeastOneValidSupply = true;
                    }
                });

                const canSubmit = yearSelected && atLeastOneValidSupply;
                
                submitBtn.disabled = !canSubmit;
                
                // Show/hide error message
                if (yearSelected && !atLeastOneValidSupply && selectedSupplies.size > 0) {
                    suppliesError.style.display = 'block';
                } else {
                    suppliesError.style.display = 'none';
                }

                // Visual feedback for submit button
                if (submitBtn.disabled) {
                    submitBtn.classList.remove('btn-success');
                    submitBtn.classList.add('btn-secondary');
                } else {
                    submitBtn.classList.remove('btn-secondary');
                    submitBtn.classList.add('btn-success');
                }

                return canSubmit;
            }

            yearSelect.addEventListener('change', validateForm);

            // Handle form submission
            mainForm.addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    alert('Please fill all required fields correctly before submitting.');
                    return;
                }

                // Prepare supply data
                const supplyData = [];
                selectedSupplies.forEach((supply, supplyName) => {
                    if (supply.isValid) {
                        supplyData.push({
                            name: supplyName,
                            amount: parseFloat(supply.amountInput.value),
                            unit: supply.unitSelect.value
                        });
                    }
                });

                // Prepare product data (only if products are selected and have valid data)
                const productData = [];
                selectedProducts.forEach((product, productName) => {
                    const amount = parseFloat(product.amountInput.value) || 0;
                    const unit = product.unitSelect.value;
                    
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
                const existingSupplyInput = document.querySelector('input[name="supply_data"]');
                const existingProductInput = document.querySelector('input[name="product_data"]');
                if (existingSupplyInput) existingSupplyInput.remove();
                if (existingProductInput) existingProductInput.remove();

                // Create hidden input for supply data
                const supplyInput = document.createElement('input');
                supplyInput.type = 'hidden';
                supplyInput.name = 'supply_data';
                supplyInput.value = JSON.stringify(supplyData);
                this.appendChild(supplyInput);

                // Create hidden input for product data only if there are products
                if (productData.length > 0) {
                    const productInput = document.createElement('input');
                    productInput.type = 'hidden';
                    productInput.name = 'product_data';
                    productInput.value = JSON.stringify(productData);
                    this.appendChild(productInput);
                }
            });

            // Bootstrap form validation
            (function () {
                'use strict'
                var forms = document.querySelectorAll('.needs-validation')
                Array.prototype.slice.call(forms)
                    .forEach(function (form) {
                        form.addEventListener('submit', function (event) {
                            if (!form.checkValidity()) {
                                event.preventDefault()
                                event.stopPropagation()
                            }
                            form.classList.add('was-validated')
                        }, false)
                    })
            })()
        });
    </script>

    <style>
        .supply-item, .product-item {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .supply-item:hover, .product-item:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .supply-item.checked, .product-item.checked {
            border-color: #198754;
            background-color: #f8fff9;
        }
        .form-check-input:checked ~ .form-check-label {
            color: #198754;
            font-weight: bold;
        }
        .is-valid {
            border-color: #198754 !important;
        }
        .is-invalid {
            border-color: #dc3545 !important;
        }
        .supply-fields, .product-fields {
            border-left: 3px solid #0dcaf0;
            padding-left: 15px;
            margin-left: -5px;
        }
        .btn:disabled {
            cursor: not-allowed;
        }
    </style>
</x-layout>