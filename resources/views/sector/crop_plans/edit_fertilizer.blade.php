<x-layout>
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="mb-1"><i class="mdi mdi-sprout text-success me-2"></i>Edit Fertilizer Details</h3>
                    <p class="text-muted mb-0">Manage fertilizer requirements for your crop plan</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('sector.crop-plans.index') }}" class="btn btn-outline-secondary">
                        <i class="mdi mdi-arrow-left me-1"></i> Back to Plans
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Crop Plan Summary -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0"><i class="mdi mdi-information-outline me-2"></i>Crop Plan Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6 col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-light-success rounded p-2 me-3">
                                    <i class="mdi mdi-calendar text-success fs-4"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Year</small>
                                    <strong class="text-dark">{{ $cropPlan->year }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-light-warning rounded p-2 me-3">
                                    <i class="mdi mdi-weather-sunny text-warning fs-4"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Season</small>
                                    <strong class="text-dark">{{ ucfirst($cropPlan->season) }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-light-info rounded p-2 me-3">
                                    <i class="mdi mdi-seed text-info fs-4"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Crop Type</small>
                                    <strong class="text-dark">{{ $cropPlan->cropType->name ?? '-' }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-light-primary rounded p-2 me-3">
                                    <i class="mdi mdi-leaf text-primary fs-4"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Crop</small>
                                    <strong class="text-dark">{{ $cropPlan->crop->name ?? '-' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Fertilizer Selection -->
        <div class="col-lg-8">
            <form action="{{ route('sector.crop-plans.updatefertilizer', $cropPlan->id) }}" method="POST" id="fertilizerForm">
                @csrf
                @method('PUT')

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="mdi mdi-fertilizer text-success me-2"></i>Select Fertilizers</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach ($fertilizerTypes as $type)
                                @php
                                    $isSelectedType = in_array($type->id, $cropPlan->fertilizer_type_id ?? []);
                                    $typeFertilizers = $fertilizers->where('fertilizer_type_id', $type->id);
                                    $details = collect($cropPlan->fertilizer_details ?? []);
                                @endphp

                                <div class="col-xl-4 col-lg-6 col-md-6">
                                    <div class="card fertilizer-type-card h-100 {{ $isSelectedType ? 'border-success' : 'border-light' }}">
                                        <div class="card-body">
                                            <div class="form-check form-switch mb-3">
                                                <input
                                                    class="form-check-input fertilizer-type-checkbox"
                                                    type="checkbox"
                                                    value="{{ $type->id }}"
                                                    id="type{{ $type->id }}"
                                                    name="fertilizer_types[]"
                                                    {{ $isSelectedType ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold fs-6 text-dark" for="type{{ $type->id }}">
                                                    {{ $type->name }}
                                                </label>
                                            </div>

                                            <div class="fertilizers-list mt-3"
                                                 id="fertilizers-type{{ $type->id }}"
                                                 style="{{ $isSelectedType ? '' : 'display:none;' }}">
                                                @foreach ($typeFertilizers as $fert)
                                                    @php
                                                        $detail = $details->firstWhere('fertilizer_id', $fert->id);
                                                        $isFertSelected = $detail !== null;
                                                    @endphp
                                                    <div class="fertilizer-item card border mb-3 {{ $isFertSelected ? 'border-success bg-light-success' : '' }}">
                                                        <div class="card-body p-3">
                                                            <div class="form-check">
                                                                <input
                                                                    class="form-check-input fertilizer-checkbox"
                                                                    type="checkbox"
                                                                    id="fert{{ $type->id }}-{{ $fert->id }}"
                                                                    name="selected_fertilizers[]"
                                                                    value="{{ $type->id }}_{{ $fert->id }}"
                                                                    {{ $isFertSelected ? 'checked' : '' }}
                                                                    data-type="{{ $type->id }}"
                                                                    data-fert="{{ $fert->id }}"
                                                                    data-name="{{ $fert->name }}">
                                                                <label class="form-check-label fw-semibold" for="fert{{ $type->id }}-{{ $fert->id }}">
                                                                    {{ $fert->name }}
                                                                </label>
                                                            </div>

                                                            <div class="row g-2 mt-2">
                                                                <div class="col-6">
                                                                    <input
                                                                        type="number"
                                                                        step="0.01"
                                                                        min="0"
                                                                        placeholder="Amount"
                                                                        class="form-control form-control-sm fertilizer-amount"
                                                                        name="fertilizer_details[{{ $type->id }}][{{ $fert->id }}][amount]"
                                                                        value="{{ $detail['amount'] ?? '' }}"
                                                                        {{ !$isFertSelected ? 'disabled' : '' }}>
                                                                </div>
                                                                <div class="col-6">
                                                                    <input type="text"
                                                                           class="form-control form-control-sm fertilizer-unit"
                                                                           value="{{ $fert->unit }}"
                                                                           disabled="{{ !$isFertSelected }}">

                                                                    <!-- Hidden value submitted with the form -->
                                                                    <input type="hidden"
                                                                           name="fertilizer_details[{{ $type->id }}][{{ $fert->id }}][unit]"
                                                                           value="{{ $fert->unit }}">
                                                                </div>
                                                            </div>
                                                            <!-- Hidden fields -->
                                                            <input type="hidden" name="fertilizer_details[{{ $type->id }}][{{ $fert->id }}][fertilizer_type_id]" value="{{ $type->id }}">
                                                            <input type="hidden" name="fertilizer_details[{{ $type->id }}][{{ $fert->id }}][fertilizer_id]" value="{{ $fert->id }}">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-success btn-lg px-4">
                        <i class="mdi mdi-content-save-outline me-2"></i> Update Fertilizer Plan
                    </button>
                    <a href="{{ route('sector.crop-plans.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                        <i class="mdi mdi-close me-2"></i> Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Live Summary Sidebar -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 20px;">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0"><i class="mdi mdi-chart-bar me-2"></i>Live Summary</h5>
                    </div>
                    <div class="card-body">
                        <div id="summaryContent">
                            <div class="text-center text-muted py-4">
                                <i class="mdi mdi-information-outline fs-1 mb-2"></i>
                                <p>Select fertilizers to see summary</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0"><i class="mdi mdi-speedometer me-2 text-info"></i>Quick Stats</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2 text-center">
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <div class="text-success fw-bold fs-4" id="totalTypes">0</div>
                                    <small class="text-muted">Fertilizer Types</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <div class="text-primary fw-bold fs-4" id="totalFertilizers">0</div>
                                    <small class="text-muted">Total Fertilizers</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize summary
    updateSummary();

    // Fertilizer type checkbox handling
    document.querySelectorAll('.fertilizer-type-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const container = document.getElementById('fertilizers-type' + this.value);
            const card = this.closest('.fertilizer-type-card');

            if (this.checked) {
                container.style.display = '';
                card.classList.add('border-success');
                card.classList.remove('border-light');
            } else {
                container.style.display = 'none';
                card.classList.remove('border-success');
                card.classList.add('border-light');
                // Uncheck all fertilizers in this type
                container.querySelectorAll('.fertilizer-checkbox').forEach(f => {
                    f.checked = false;
                    const parent = f.closest('.fertilizer-item');
                    parent.querySelector('.fertilizer-amount').disabled = true;
                    parent.querySelector('.fertilizer-unit').disabled = true;
                    parent.classList.remove('border-success', 'bg-light-success');
                });
            }
            updateSummary();
        });
    });

    // Individual fertilizer checkbox handling
    document.querySelectorAll('.fertilizer-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const parent = this.closest('.fertilizer-item');
            const amountInput = parent.querySelector('.fertilizer-amount');
            const unitSelect = parent.querySelector('.fertilizer-unit');

            if (this.checked) {
                amountInput.disabled = false;
                unitSelect.disabled = false;
                parent.classList.add('border-success', 'bg-light-success');
            } else {
                amountInput.disabled = true;
                unitSelect.disabled = true;
                amountInput.value = '';
                unitSelect.selectedIndex = 0;
                parent.classList.remove('border-success', 'bg-light-success');
            }
            updateSummary();
        });
    });

    // Amount and unit input changes
    document.querySelectorAll('.fertilizer-amount, .fertilizer-unit').forEach(input => {
        input.addEventListener('input', updateSummary);
        input.addEventListener('change', updateSummary);
    });

    function updateSummary() {
        const selectedFertilizers = [];
        let totalTypes = new Set();
        let totalFertCount = 0;

        document.querySelectorAll('.fertilizer-checkbox:checked').forEach(cb => {
            const parent = cb.closest('.fertilizer-item');
            const amountInput = parent.querySelector('.fertilizer-amount');
            const unitSelect = parent.querySelector('.fertilizer-unit');
            const amount = amountInput.value;
            const unit = unitSelect.value;
            const fertName = cb.getAttribute('data-name');
            const typeId = cb.getAttribute('data-type');

            if (amount && unit) {
                selectedFertilizers.push({
                    name: fertName,
                    amount: amount,
                    unit: unit,
                    typeId: typeId
                });
                totalTypes.add(typeId);
                totalFertCount++;
            }
        });

        // Update quick stats
        document.getElementById('totalTypes').textContent = totalTypes.size;
        document.getElementById('totalFertilizers').textContent = totalFertCount;

        // Update summary content
        const summaryContent = document.getElementById('summaryContent');

        if (selectedFertilizers.length === 0) {
            summaryContent.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="mdi mdi-information-outline fs-1 mb-2"></i>
                    <p>Select fertilizers to see summary</p>
                </div>
            `;
            return;
        }

        let summaryHTML = `
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold">Selected Fertilizers:</span>
                    <span class="badge bg-success">${selectedFertilizers.length}</span>
                </div>
                <div class="list-group list-group-flush">
        `;

        // Group by fertilizer type
        const groupedByType = selectedFertilizers.reduce((acc, fert) => {
            if (!acc[fert.typeId]) acc[fert.typeId] = [];
            acc[fert.typeId].push(fert);
            return acc;
        }, {});

        Object.entries(groupedByType).forEach(([typeId, ferts]) => {
            const typeName = document.querySelector(`#type${typeId}`)?.nextElementSibling?.textContent || 'Unknown Type';

            summaryHTML += `
                <div class="list-group-item border-0 px-0 py-2">
                    <div class="fw-semibold text-primary mb-1">${typeName}</div>
            `;

            ferts.forEach(fert => {
                summaryHTML += `
                    <div class="d-flex justify-content-between align-items-center py-1">
                        <span class="text-muted small">${fert.name}</span>
                        <span class="fw-bold text-dark">${fert.amount} ${fert.unit}</span>
                    </div>
                `;
            });

            summaryHTML += `</div>`;
        });

        summaryHTML += `
                </div>
            </div>
            <div class="alert alert-success mb-0">
                <div class="d-flex align-items-center">
                    <i class="mdi mdi-check-circle-outline me-2 fs-5"></i>
                    <div>
                        <strong class="d-block">Ready to Update</strong>
                        <small class="d-block">Your fertilizer plan looks good!</small>
                    </div>
                </div>
            </div>
        `;

        summaryContent.innerHTML = summaryHTML;
    }

    // Form submission handling
    document.getElementById('fertilizerForm').addEventListener('submit', function(e) {
        // Remove disabled inputs so they don't get submitted
        this.querySelectorAll('input:disabled, select:disabled').forEach(input => {
            input.disabled = false;
            if (input.type === 'number' && !input.value) {
                input.removeAttribute('name');
            }
            if (input.tagName === 'SELECT' && !input.value) {
                input.removeAttribute('name');
            }
        });

        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i> Updating...';
        submitBtn.disabled = true;

        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 3000);
    });
});
</script>

<style>
.fertilizer-type-card {
    transition: all 0.3s ease;
    border: 2px solid;
}

.fertilizer-type-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.fertilizer-item {
    transition: all 0.3s ease;
}

.fertilizer-item:hover {
    transform: translateX(5px);
}

.bg-light-success {
    background-color: rgba(40, 167, 69, 0.05) !important;
}

.bg-light-warning {
    background-color: rgba(255, 193, 7, 0.05) !important;
}

.bg-light-info {
    background-color: rgba(23, 162, 184, 0.05) !important;
}

.bg-light-primary {
    background-color: rgba(0, 123, 255, 0.05) !important;
}

.form-switch .form-check-input {
    width: 2.5em;
    height: 1.25em;
}

.sticky-top {
    position: sticky;
    z-index: 10;
}

@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem;
    }

    .card-body {
        padding: 1rem;
    }

    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
    }
}

/* Custom scrollbar for summary */
#summaryContent {
    max-height: 400px;
    overflow-y: auto;
}

#summaryContent::-webkit-scrollbar {
    width: 4px;
}

#summaryContent::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#summaryContent::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

#summaryContent::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
</x-layout>
