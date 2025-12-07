<x-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">
                <i class="bi bi-pencil-square text-primary me-2"></i>Edit Natural Resource Plan
            </h3>
            <div class="badge bg-light text-dark fs-6">
                <i class="bi bi-info-circle me-1"></i>
                All fields marked with <span class="text-danger">*</span> are required
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('sector.natural-plans.update', $plan->id) }}" method="POST" id="mainForm">
            @csrf
            @method('PUT')

            <!-- Year Selection -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-calendar3 me-2"></i>Year Selection</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Year <span class="text-danger">*</span></label>
                            <select name="year" class="form-select" id="yearSelect" required>
                                <option value="">-- Select Year --</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year }}" 
                                        {{ old('year', $plan->year) == $year ? 'selected' : '' }}>
                                        {{ $year }} EC
                                    </option>
                                @endforeach
                            </select>
                            @error('year')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Natural Resources -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-tree me-2"></i>Natural / Livestock Resources</h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info py-2 mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        Select at least one resource and enter its amount and unit.
                    </div>
                    <div class="row g-3" id="resourcesContainer">
                        @foreach ($natural as $resource)
                            @php
                                // Check if this resource was previously selected
                                $existingData = null;
                                if (is_array($plan->natural_data)) {
                                    $existingData = collect($plan->natural_data)->firstWhere('name', $resource->name);
                                }
                                $isChecked = $existingData !== null;
                                $amount = $isChecked ? $existingData['amount'] : '';
                                $unit = $isChecked ? $existingData['unit'] : '';
                            @endphp
                            <div class="col-md-6">
                                <div class="card border resource-item {{ $isChecked ? 'checked' : '' }}">
                                    <div class="card-body">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input resource-checkbox" type="checkbox"
                                                value="{{ $resource->name }}" 
                                                id="resource-{{ $resource->id }}"
                                                data-resource-id="{{ $resource->id }}"
                                                {{ $isChecked ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold"
                                                for="resource-{{ $resource->id }}">
                                                {{ $resource->name }}
                                            </label>
                                        </div>

                                        <div class="resource-fields" style="display: {{ $isChecked ? 'block' : 'none' }};">
                                            <label class="form-label small">Amount <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control resource-amount"
                                                placeholder="0.00" min="0" 
                                                value="{{ $amount }}"
                                                {{ $isChecked ? '' : 'disabled' }}>

                                            <label class="form-label small mt-2">Unit <span class="text-danger">*</span></label>
                                            <select class="form-select resource-unit" {{ $isChecked ? '' : 'disabled' }}>
                                                <option value="">-- Select Unit --</option>
                                                <option value="kg" {{ $unit == 'kg' ? 'selected' : '' }}>kg</option>
                                                <option value="liter" {{ $unit == 'liter' ? 'selected' : '' }}>Liter</option>
                                                <option value="piece" {{ $unit == 'piece' ? 'selected' : '' }}>Piece</option>
                                                <option value="bag" {{ $unit == 'bag' ? 'selected' : '' }}>Bag</option>
                                                <option value="quintal" {{ $unit == 'quintal' ? 'selected' : '' }}>Quintal</option>
                                                <option value="ton" {{ $unit == 'ton' ? 'selected' : '' }}>Ton</option>
                                                <option value="box" {{ $unit == 'box' ? 'selected' : '' }}>Box</option>
                                                <option value="bottle" {{ $unit == 'bottle' ? 'selected' : '' }}>Bottle</option>
                                                <option value="pack" {{ $unit == 'pack' ? 'selected' : '' }}>Pack</option>
                                                <option value="unit" {{ $unit == 'unit' ? 'selected' : '' }}>Unit</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('natural_data')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                    <input type="hidden" name="natural_data" id="natural_data" value="{{ old('natural_data') }}">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 border-top pt-4">
                <a href="{{ route('sector.natural-plans.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                    <i class="bi bi-save me-2"></i>Update Plan
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const yearSelect = document.getElementById('yearSelect');
            const submitBtn = document.getElementById('submitBtn');
            const naturalInput = document.getElementById('natural_data');
            const selected = new Map();

            // Initialize with existing data
            function initializeExistingData() {
                @if(is_array($plan->natural_data))
                    @foreach($plan->natural_data as $resource)
                        selected.set('{{ $resource['name'] }}', {
                            id: '{{ $resource['id'] ?? '' }}',
                            name: '{{ $resource['name'] }}',
                            amount: '{{ $resource['amount'] }}',
                            unit: '{{ $resource['unit'] }}'
                        });
                    @endforeach
                @endif
                updateFormState();
            }

            document.querySelectorAll('.resource-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    const parent = this.closest('.resource-item');
                    const amount = parent.querySelector('.resource-amount');
                    const unit = parent.querySelector('.resource-unit');
                    const fields = parent.querySelector('.resource-fields');
                    const resourceId = this.getAttribute('data-resource-id');
                    const resourceName = this.value;

                    if (this.checked) {
                        fields.style.display = 'block';
                        amount.disabled = false;
                        unit.disabled = false;
                        parent.classList.add('checked');
                        
                        // If not already in selected, add it with current values
                        if (!selected.has(resourceName)) {
                            selected.set(resourceName, { 
                                id: resourceId, 
                                name: resourceName, 
                                amount: amount.value, 
                                unit: unit.value 
                            });
                        }
                    } else {
                        fields.style.display = 'none';
                        amount.disabled = true;
                        unit.disabled = true;
                        parent.classList.remove('checked');
                        selected.delete(resourceName);
                    }
                    updateFormState();
                });
            });

            document.addEventListener('input', e => {
                if (e.target.classList.contains('resource-amount') || e.target.classList.contains('resource-unit')) {
                    const item = e.target.closest('.resource-item');
                    const name = item.querySelector('.resource-checkbox').value;
                    const amount = item.querySelector('.resource-amount').value;
                    const unit = item.querySelector('.resource-unit').value;
                    const resourceId = item.querySelector('.resource-checkbox').getAttribute('data-resource-id');
                    
                    if (selected.has(name)) {
                        selected.set(name, { 
                            id: resourceId, 
                            name, 
                            amount, 
                            unit 
                        });
                    }
                    updateFormState();
                }
            });

            function updateFormState() {
                const data = Array.from(selected.values()).filter(r => r.amount && r.unit);
                naturalInput.value = JSON.stringify(data);
                
                // Enable/disable submit button based on validation
                const isValid = yearSelect.value && data.length > 0;
                submitBtn.disabled = !isValid;
                
                // Visual feedback
                if (submitBtn.disabled) {
                    submitBtn.classList.remove('btn-primary');
                    submitBtn.classList.add('btn-secondary');
                } else {
                    submitBtn.classList.remove('btn-secondary');
                    submitBtn.classList.add('btn-primary');
                }

                // Add validation styling
                document.querySelectorAll('.resource-item').forEach(item => {
                    const checkbox = item.querySelector('.resource-checkbox');
                    const amount = item.querySelector('.resource-amount');
                    const unit = item.querySelector('.resource-unit');
                    
                    if (checkbox.checked) {
                        if (amount.value && unit.value) {
                            amount.classList.remove('is-invalid');
                            amount.classList.add('is-valid');
                            unit.classList.remove('is-invalid');
                            unit.classList.add('is-valid');
                        } else {
                            amount.classList.remove('is-valid');
                            amount.classList.add('is-invalid');
                            unit.classList.remove('is-valid');
                            unit.classList.add('is-invalid');
                        }
                    } else {
                        amount.classList.remove('is-valid', 'is-invalid');
                        unit.classList.remove('is-valid', 'is-invalid');
                    }
                });
            }

            yearSelect.addEventListener('change', updateFormState);

            // Initialize with existing data
            initializeExistingData();

            // Handle form submission to ensure data is valid
            document.getElementById('mainForm').addEventListener('submit', function(e) {
                updateFormState();
                const data = Array.from(selected.values()).filter(r => r.amount && r.unit);
                
                if (!yearSelect.value || data.length === 0) {
                    e.preventDefault();
                    alert('Please fill all required fields correctly before submitting.');
                    return;
                }
            });
        });
    </script>

    <style>
        .resource-item {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .resource-item:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
        }

        .resource-item.checked {
            border-color: #198754;
            background-color: #f8fff9;
        }

        .resource-fields {
            border-left: 3px solid #0dcaf0;
            padding-left: 10px;
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

        .btn:disabled {
            cursor: not-allowed;
        }
    </style>
</x-layout>