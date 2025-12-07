<x-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">
                <i class="bi bi-plus-circle text-success me-2"></i>Natural Resource Plan
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

        <form action="{{ route('sector.natural-plans.store') }}" method="POST" id="mainForm">
            @csrf

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
                                    <option value="{{ $year }}" {{ old('year') == $year ? 'selected' : '' }}>
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
                    <h5 class="mb-0"><i class="bi bi-tree me-2"></i>Natural Resources</h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info py-2 mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        Select at least one resource and enter its amount and unit.
                    </div>
                    <div class="row g-3" id="resourcesContainer">
                        @foreach ($natural as $resource)
                            <div class="col-md-6">
                                <div class="card border resource-item">
                                    <div class="card-body">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input resource-checkbox" type="checkbox"
                                                value="{{ $resource->name }}" 
                                                id="resource-{{ $resource->id }}"
                                                data-resource-id="{{ $resource->id }}">
                                            <label class="form-check-label fw-semibold"
                                                for="resource-{{ $resource->id }}">
                                                {{ $resource->name }}
                                            </label>
                                        </div>

                                        <div class="resource-fields" style="display: none;">
                                            <label class="form-label small">Amount <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control resource-amount"
                                                placeholder="0.00" min="0" disabled>

                                            <label class="form-label small mt-2">Unit <span class="text-danger">*</span></label>
                                            <select class="form-select resource-unit" disabled>
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
                        @endforeach
                    </div>
                    @error('natural_data')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                    <input type="hidden" name="natural_data" id="natural_data">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 border-top pt-4">
                <a href="{{ route('sector.natural-plans.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-success px-4" id="submitBtn" disabled>
                    <i class="bi bi-save me-2"></i>Save Plan
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
                        selected.set(resourceName, { 
                            id: resourceId, 
                            name: resourceName, 
                            amount: '', 
                            unit: '' 
                        });
                    } else {
                        fields.style.display = 'none';
                        amount.disabled = true;
                        unit.disabled = true;
                        amount.value = '';
                        unit.value = '';
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
                submitBtn.disabled = !(yearSelect.value && data.length > 0);
                
                // Visual feedback
                if (submitBtn.disabled) {
                    submitBtn.classList.remove('btn-success');
                    submitBtn.classList.add('btn-secondary');
                } else {
                    submitBtn.classList.remove('btn-secondary');
                    submitBtn.classList.add('btn-success');
                }
            }

            yearSelect.addEventListener('change', updateFormState);

            // Initialize form with old values if validation failed
            function initializeOldValues() {
                @if(old('natural_data'))
                    try {
                        const oldData = @json(json_decode(old('natural_data'), true));
                        if (Array.isArray(oldData)) {
                            oldData.forEach(item => {
                                const checkbox = document.querySelector(`.resource-checkbox[value="${item.name}"]`);
                                if (checkbox) {
                                    checkbox.checked = true;
                                    checkbox.dispatchEvent(new Event('change'));
                                    
                                    const parent = checkbox.closest('.resource-item');
                                    parent.querySelector('.resource-amount').value = item.amount;
                                    parent.querySelector('.resource-unit').value = item.unit;
                                    
                                    selected.set(item.name, item);
                                }
                            });
                        }
                    } catch (e) {
                        console.error('Error initializing old values:', e);
                    }
                @endif
                updateFormState();
            }

            initializeOldValues();
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
    </style>
</x-layout>