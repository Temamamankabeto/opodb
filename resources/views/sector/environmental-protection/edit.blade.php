<x-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">
                <i class="bi bi-pencil-square text-primary me-2"></i>Edit Environmental Protection Plan
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

        <form action="{{ route('sector.environmental-plans.update', $plan->id) }}" method="POST" id="mainForm">
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

            <!-- Environmental Activities -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-tree-fill me-2"></i>Environmental Protection Activities</h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info py-2 mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        Select at least one activity and enter its target and unit.
                    </div>
                    <div class="row g-3" id="activitiesContainer">
                        @foreach ($activities as $activity)
                            @php
                                // Check if this activity was previously selected
                                $existingData = null;
                                if (is_array($plan->environmental_data)) {
                                    $existingData = collect($plan->environmental_data)->firstWhere('name', $activity->name);
                                }
                                $isChecked = $existingData !== null;
                                $hasSpecialFields = $existingData['has_special_fields'] ?? false;
                                
                                if ($hasSpecialFields) {
                                    $privateTarget = $existingData['private_target'] ?? '';
                                    $publicTarget = $existingData['public_target'] ?? '';
                                } else {
                                    $target = $existingData['target'] ?? '';
                                }
                                $unit = $existingData['unit'] ?? '';
                            @endphp
                            <div class="col-md-6">
                                <div class="card border activity-item {{ $isChecked ? 'checked' : '' }}">
                                    <div class="card-body">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input activity-checkbox" type="checkbox"
                                                value="{{ $activity->name }}" 
                                                id="activity-{{ $activity->id }}"
                                                data-activity-id="{{ $activity->id }}"
                                                data-activity-name="{{ $activity->name }}"
                                                {{ $isChecked ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold"
                                                for="activity-{{ $activity->id }}">
                                                {{ $activity->name }}
                                            </label>
                                            @if($activity->description)
                                                <div class="form-text small text-muted">{{ $activity->description }}</div>
                                            @endif
                                        </div>

                                        <div class="activity-fields" style="display: {{ $isChecked ? 'block' : 'none' }};">
                                            <!-- Default fields for most activities -->
                                            <div class="default-fields" style="display: {{ $hasSpecialFields ? 'none' : 'block' }};">
                                                <label class="form-label small">Target <span class="text-danger">*</span></label>
                                                <input type="number" step="0.01" class="form-control activity-target"
                                                    placeholder="0.00" min="0" 
                                                    value="{{ $target ?? '' }}"
                                                    {{ $isChecked && !$hasSpecialFields ? '' : 'disabled' }}>
                                            </div>

                                            <!-- Special fields for Mana Fincaanii ijaarame -->
                                            <div class="special-fields" style="display: {{ $hasSpecialFields ? 'block' : 'none' }};">
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <label class="form-label small">Private Target <span class="text-danger">*</span></label>
                                                        <input type="number" step="0.01" class="form-control activity-private-target"
                                                            placeholder="0.00" min="0" 
                                                            value="{{ $privateTarget ?? '' }}"
                                                            {{ $isChecked && $hasSpecialFields ? '' : 'disabled' }}>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small">Public Target <span class="text-danger">*</span></label>
                                                        <input type="number" step="0.01" class="form-control activity-public-target"
                                                            placeholder="0.00" min="0" 
                                                            value="{{ $publicTarget ?? '' }}"
                                                            {{ $isChecked && $hasSpecialFields ? '' : 'disabled' }}>
                                                    </div>
                                                </div>
                                            </div>

                                            <label class="form-label small mt-2">Unit <span class="text-danger">*</span></label>
                                            <select class="form-select activity-unit" {{ $isChecked ? '' : 'disabled' }}>
                                                <option value="">-- Select Unit --</option>
                                                <option value="hectare" {{ $unit == 'hectare' ? 'selected' : '' }}>Hectare</option>
                                                <option value="km" {{ $unit == 'km' ? 'selected' : '' }}>Kilometer</option>
                                                <option value="tree" {{ $unit == 'tree' ? 'selected' : '' }}>Tree</option>
                                                <option value="person" {{ $unit == 'person' ? 'selected' : '' }}>Person</option>
                                                <option value="household" {{ $unit == 'household' ? 'selected' : '' }}>Household</option>
                                                <option value="site" {{ $unit == 'site' ? 'selected' : '' }}>Site</option>
                                                <option value="project" {{ $unit == 'project' ? 'selected' : '' }}>Project</option>
                                                <option value="session" {{ $unit == 'session' ? 'selected' : '' }}>Training Session</option>
                                                <option value="unit" {{ $unit == 'unit' ? 'selected' : '' }}>Unit</option>
                                                <option value="piece" {{ $unit == 'piece' ? 'selected' : '' }}>Piece</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('environmental_data')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                    <input type="hidden" name="environmental_data" id="environmental_data" value="{{ old('environmental_data') }}">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 border-top pt-4">
                <a href="{{ route('sector.environmental-plans.index') }}" class="btn btn-outline-secondary">
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
            const environmentalInput = document.getElementById('environmental_data');
            const selected = new Map();

            // Initialize with existing data
            function initializeExistingData() {
                @if(is_array($plan->environmental_data))
                    @foreach($plan->environmental_data as $activity)
                        selected.set('{{ $activity['name'] }}', {
                            id: '{{ $activity['id'] ?? '' }}',
                            name: '{{ $activity['name'] }}',
                            @if(isset($activity['has_special_fields']) && $activity['has_special_fields'])
                                private_target: '{{ $activity['private_target'] }}',
                                public_target: '{{ $activity['public_target'] }}',
                                has_special_fields: true,
                            @else
                                target: '{{ $activity['target'] }}',
                                has_special_fields: false,
                            @endif
                            unit: '{{ $activity['unit'] }}'
                        });
                    @endforeach
                @endif
                updateFormState();
            }

            document.querySelectorAll('.activity-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    const parent = this.closest('.activity-item');
                    const activityName = this.value;
                    const activityId = this.getAttribute('data-activity-id');
                    const fields = parent.querySelector('.activity-fields');
                    const defaultFields = parent.querySelector('.default-fields');
                    const specialFields = parent.querySelector('.special-fields');
                    const targetInput = parent.querySelector('.activity-target');
                    const privateTargetInput = parent.querySelector('.activity-private-target');
                    const publicTargetInput = parent.querySelector('.activity-public-target');
                    const unitSelect = parent.querySelector('.activity-unit');

                    if (this.checked) {
                        fields.style.display = 'block';
                        parent.classList.add('checked');
                        
                        // Show special fields for "Mana Fincaanii ijaarame", default for others
                        if (activityName === 'Mana Fincaanii ijaarame') {
                            defaultFields.style.display = 'none';
                            specialFields.style.display = 'block';
                            privateTargetInput.disabled = false;
                            publicTargetInput.disabled = false;
                            
                            if (!selected.has(activityName)) {
                                selected.set(activityName, { 
                                    id: activityId, 
                                    name: activityName, 
                                    private_target: '', 
                                    public_target: '', 
                                    unit: '',
                                    has_special_fields: true
                                });
                            }
                        } else {
                            defaultFields.style.display = 'block';
                            specialFields.style.display = 'none';
                            targetInput.disabled = false;
                            
                            if (!selected.has(activityName)) {
                                selected.set(activityName, { 
                                    id: activityId, 
                                    name: activityName, 
                                    target: '', 
                                    unit: '',
                                    has_special_fields: false
                                });
                            }
                        }
                        
                        unitSelect.disabled = false;
                    } else {
                        fields.style.display = 'none';
                        parent.classList.remove('checked');
                        targetInput.disabled = true;
                        privateTargetInput.disabled = true;
                        publicTargetInput.disabled = true;
                        unitSelect.disabled = true;
                        selected.delete(activityName);
                    }
                    updateFormState();
                });
            });

            document.addEventListener('input', e => {
                if (e.target.classList.contains('activity-target') || 
                    e.target.classList.contains('activity-private-target') ||
                    e.target.classList.contains('activity-public-target') ||
                    e.target.classList.contains('activity-unit')) {
                    
                    const item = e.target.closest('.activity-item');
                    const name = item.querySelector('.activity-checkbox').value;
                    const activityId = item.querySelector('.activity-checkbox').getAttribute('data-activity-id');
                    const unit = item.querySelector('.activity-unit').value;
                    
                    if (selected.has(name)) {
                        const activityData = selected.get(name);
                        
                        if (activityData.has_special_fields) {
                            const privateTarget = item.querySelector('.activity-private-target').value;
                            const publicTarget = item.querySelector('.activity-public-target').value;
                            
                            selected.set(name, { 
                                ...activityData,
                                private_target: privateTarget,
                                public_target: publicTarget,
                                unit: unit
                            });
                        } else {
                            const target = item.querySelector('.activity-target').value;
                            
                            selected.set(name, { 
                                ...activityData,
                                target: target,
                                unit: unit
                            });
                        }
                    }
                    updateFormState();
                }
            });

            function updateFormState() {
                const data = Array.from(selected.values()).map(activity => {
                    if (activity.has_special_fields) {
                        return {
                            id: activity.id,
                            name: activity.name,
                            private_target: activity.private_target,
                            public_target: activity.public_target,
                            unit: activity.unit,
                            has_special_fields: true
                        };
                    } else {
                        return {
                            id: activity.id,
                            name: activity.name,
                            target: activity.target,
                            unit: activity.unit,
                            has_special_fields: false
                        };
                    }
                }).filter(activity => {
                    if (activity.has_special_fields) {
                        return activity.private_target && activity.public_target && activity.unit;
                    } else {
                        return activity.target && activity.unit;
                    }
                });

                environmentalInput.value = JSON.stringify(data);
                submitBtn.disabled = !(yearSelect.value && data.length > 0);
                
                // Visual feedback
                if (submitBtn.disabled) {
                    submitBtn.classList.remove('btn-primary');
                    submitBtn.classList.add('btn-secondary');
                } else {
                    submitBtn.classList.remove('btn-secondary');
                    submitBtn.classList.add('btn-primary');
                }

                // Add validation styling
                document.querySelectorAll('.activity-item').forEach(item => {
                    const checkbox = item.querySelector('.activity-checkbox');
                    const activityName = checkbox.value;
                    
                    if (checkbox.checked && selected.has(activityName)) {
                        const activityData = selected.get(activityName);
                        
                        if (activityData.has_special_fields) {
                            const privateTarget = item.querySelector('.activity-private-target');
                            const publicTarget = item.querySelector('.activity-public-target');
                            const unit = item.querySelector('.activity-unit');
                            
                            // Validate private target
                            if (privateTarget.value) {
                                privateTarget.classList.remove('is-invalid');
                                privateTarget.classList.add('is-valid');
                            } else {
                                privateTarget.classList.remove('is-valid');
                                privateTarget.classList.add('is-invalid');
                            }
                            
                            // Validate public target
                            if (publicTarget.value) {
                                publicTarget.classList.remove('is-invalid');
                                publicTarget.classList.add('is-valid');
                            } else {
                                publicTarget.classList.remove('is-valid');
                                publicTarget.classList.add('is-invalid');
                            }
                            
                            // Validate unit
                            if (unit.value) {
                                unit.classList.remove('is-invalid');
                                unit.classList.add('is-valid');
                            } else {
                                unit.classList.remove('is-valid');
                                unit.classList.add('is-invalid');
                            }
                        } else {
                            const target = item.querySelector('.activity-target');
                            const unit = item.querySelector('.activity-unit');
                            
                            if (target.value && unit.value) {
                                target.classList.remove('is-invalid');
                                target.classList.add('is-valid');
                                unit.classList.remove('is-invalid');
                                unit.classList.add('is-valid');
                            } else {
                                target.classList.remove('is-valid');
                                target.classList.add('is-invalid');
                                unit.classList.remove('is-valid');
                                unit.classList.add('is-invalid');
                            }
                        }
                    }
                });
            }

            yearSelect.addEventListener('change', updateFormState);

            // Initialize with existing data
            initializeExistingData();

            // Handle form submission to ensure data is valid
            document.getElementById('mainForm').addEventListener('submit', function(e) {
                updateFormState();
                const data = Array.from(selected.values()).filter(activity => {
                    if (activity.has_special_fields) {
                        return activity.private_target && activity.public_target && activity.unit;
                    } else {
                        return activity.target && activity.unit;
                    }
                });
                
                if (!yearSelect.value || data.length === 0) {
                    e.preventDefault();
                    alert('Please fill all required fields correctly before submitting.');
                    return;
                }
            });
        });
    </script>

    <style>
        .activity-item {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .activity-item:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
        }

        .activity-item.checked {
            border-color: #198754;
            background-color: #f8fff9;
        }

        .activity-fields {
            border-left: 3px solid #0dcaf0;
            padding-left: 10px;
        }

        .special-fields .form-control {
            font-size: 0.875rem;
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