<x-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Livestock Plan</h3>
            <div class="badge bg-light text-dark fs-6">
                <i class="bi bi-info-circle me-1"></i>
                All fields marked with <span class="text-danger">*</span> are required
            </div>
        </div>

        @if (isset($plan))
            <form action="{{ route('sector.livestock_plans.update', $plan->id) }}" method="POST" class="needs-validation"
                novalidate id="mainForm">
                @csrf
                @method('PUT')

                <!-- Year Selection -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-calendar3 me-2"></i>Year Selection
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
                                    <select name="year" class="form-select border-start-0 ps-0" id="yearSelect"
                                        required>
                                        <option value="">-- Select Year --</option>
                                        @foreach ($years as $year)
                                            <option value="{{ $year }}"
                                                {{ $plan->year == $year ? 'selected' : '' }}>
                                                {{ $year }} EC
                                            </option>
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

                <!-- Livestock Supplies (Readonly Summary) -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-truck me-2"></i>Livestock Supplies (Summary)
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @if (!empty($plan->supply_data))
                            <ul class="list-unstyled mb-0">
                                @foreach ($plan->supply_data as $supply)
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-1"></i>
                                        <strong>{{ $supply['name'] ?? 'N/A' }}</strong>:
                                        {{ $supply['amount'] ?? 0 }} {{ $supply['unit'] ?? '' }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">No supply data available.</span>
                        @endif
                    </div>
                </div>

                <!-- Livestock Products (Editable) -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-box-seam me-2"></i>Livestock Products (Optional)
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-info py-2 mb-3">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Optional:</strong> Check product items if you want to include them in your plan
                        </div>

                        <div class="row g-3" id="productsContainer">
                            @foreach ($products as $product)
                                @php
                                    $existingProduct = collect($plan->product_data ?? [])->firstWhere(
                                        'name',
                                        $product->name,
                                    );
                                    $isChecked = $existingProduct !== null;
                                    $amount = $isChecked ? $existingProduct['amount'] : '';
                                    $unit = $isChecked ? $existingProduct['unit'] : '';
                                @endphp
                                <div class="col-md-6">
                                    <div class="card border product-item {{ $isChecked ? 'checked' : '' }}">
                                        <div class="card-body">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input product-checkbox" type="checkbox"
                                                    value="{{ $product->name }}" id="product-{{ $product->id }}"
                                                    data-product-name="{{ $product->name }}"
                                                    {{ $isChecked ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold"
                                                    for="product-{{ $product->id }}">
                                                    {{ $product->name }}
                                                </label>
                                            </div>

                                            <div class="product-fields"
                                                style="display: {{ $isChecked ? 'block' : 'none' }};">
                                                <div class="row g-2">
                                                    <div class="col-12">
                                                        <label class="form-label small">Amount</label>
                                                        <input type="number" step="0.01"
                                                            class="form-control product-amount" placeholder="0.00"
                                                            min="0" value="{{ $amount }}"
                                                            {{ $isChecked ? '' : 'disabled' }}>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small">Unit</label>
                                                        <select class="form-select product-unit"
                                                            {{ $isChecked ? '' : 'disabled' }}>
                                                            <option value="">-- Select Unit --</option>
                                                            <option value="kg"
                                                                {{ $unit == 'kg' ? 'selected' : '' }}>kg</option>
                                                            <option value="liter"
                                                                {{ $unit == 'liter' ? 'selected' : '' }}>Liter</option>
                                                            <option value="piece"
                                                                {{ $unit == 'piece' ? 'selected' : '' }}>Piece</option>
                                                            <option value="bag"
                                                                {{ $unit == 'bag' ? 'selected' : '' }}>Bag</option>
                                                            <option value="quintal"
                                                                {{ $unit == 'quintal' ? 'selected' : '' }}>Quintal
                                                            </option>
                                                            <option value="ton"
                                                                {{ $unit == 'ton' ? 'selected' : '' }}>Ton</option>
                                                            <option value="box"
                                                                {{ $unit == 'box' ? 'selected' : '' }}>Box</option>
                                                            <option value="bottle"
                                                                {{ $unit == 'bottle' ? 'selected' : '' }}>Bottle
                                                            </option>
                                                            <option value="pack"
                                                                {{ $unit == 'pack' ? 'selected' : '' }}>Pack</option>
                                                            <option value="unit"
                                                                {{ $unit == 'unit' ? 'selected' : '' }}>Unit</option>
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

                <!-- Form Actions -->
                <div class="d-flex gap-3 justify-content-end border-top pt-4">
                    <a href="{{ route('sector.livestock_plans.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-warning px-4" id="submitBtn">
                        <i class="bi bi-check-circle me-2"></i>Update Plan
                    </button>
                </div>
            </form>
        @else
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Plan not found. Please check the URL and try again.
            </div>
            <a href="{{ route('sector.livestock_plans.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Plans
            </a>
        @endif
    </div>

    <!-- JS for Products Only -->
    @if (isset($plan))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const submitBtn = document.getElementById('submitBtn');
                const selectedProducts = new Map();

                // Initialize products from existing data
                @foreach ($plan->product_data ?? [] as $product)
                    selectedProducts.set("{{ $product['name'] }}", {
                        amountInput: document.querySelector(
                                `input[data-product-name="{{ $product['name'] }}"]`)?.closest('.product-item')
                            ?.querySelector('.product-amount'),
                        unitSelect: document.querySelector(`input[data-product-name="{{ $product['name'] }}"]`)
                            ?.closest('.product-item')?.querySelector('.product-unit')
                    });
                @endforeach

                document.querySelectorAll('.product-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const productName = this.getAttribute('data-product-name');
                        const fieldsContainer = this.closest('.product-item').querySelector(
                            '.product-fields');
                        const amountInput = fieldsContainer.querySelector('.product-amount');
                        const unitSelect = fieldsContainer.querySelector('.product-unit');

                        if (this.checked) {
                            fieldsContainer.style.display = 'block';
                            amountInput.disabled = false;
                            unitSelect.disabled = false;
                            this.closest('.product-item').classList.add('checked');
                            selectedProducts.set(productName, {
                                amountInput,
                                unitSelect
                            });
                        } else {
                            fieldsContainer.style.display = 'none';
                            amountInput.disabled = true;
                            unitSelect.disabled = true;
                            this.closest('.product-item').classList.remove('checked');
                            amountInput.value = '';
                            unitSelect.value = '';
                            selectedProducts.delete(productName);
                        }
                    });
                });

                // Submit form
                document.getElementById('mainForm').addEventListener('submit', function(e) {
                    const productData = [];
                    selectedProducts.forEach((product, name) => {
                        const amount = parseFloat(product.amountInput.value) || 0;
                        const unit = product.unitSelect.value;
                        if (amount > 0 && unit !== '') {
                            productData.push({
                                name,
                                amount,
                                unit
                            });
                        }
                    });

                    // Remove existing hidden input if any
                    document.querySelector('input[name="product_data"]')?.remove();

                    const productInput = document.createElement('input');
                    productInput.type = 'hidden';
                    productInput.name = 'product_data';
                    productInput.value = JSON.stringify(productData);
                    this.appendChild(productInput);
                });
            });
        </script>
    @endif
</x-layout>
