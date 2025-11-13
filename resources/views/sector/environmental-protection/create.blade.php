<x-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">
                <i class="bi bi-plus-circle text-success me-2"></i>Create Environmental Protection Plan
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

        <form action="{{ route('sector.environmental-plans.store') }}" method="POST" id="mainForm">
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
                            <div class="col-md-6">
                                <div class="card border activity-item">
                                    <div class="card-body">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input activity-checkbox" type="checkbox"
                                                value="{{ $activity->name }}" 
                                                id="activity-{{ $activity->id }}"
                                                data-activity-id="{{ $activity->id }}"
                                                data-activity-name="{{ $activity->name }}">
                                            <label class="form-check-label fw-semibold"
                                                for="activity-{{ $activity->id }}">
                                                {{ $activity->name }}
                                            </label>
                                            @if($activity->description)
                                                <div class="form-text small text-muted">{{ $activity->description }}</div>
                                            @endif
                                        </div>

                                        <div class="activity-fields" style="display: none;">
                                            <!-- Default fields for most activities -->
                                            <div class="default-fields">
                                                <label class="form-label small">Target <span class="text-danger">*</span></label>
                                                <input type="number" step="0.01" class="form-control activity-target"
                                                    placeholder="0.00" min="0" disabled>
                                            </div>

                                            <!-- Special fields for Mana Fincaanii ijaarame -->
                                            <div class="special-fields" style="display: none;">
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <label class="form-label small">Private Target <span class="text-danger">*</span></label>
                                                        <input type="number" step="0.01" class="form-control activity-private-target"
                                                            placeholder="0.00" min="0" disabled>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small">Public Target <span class="text-danger">*</span></label>
                                                        <input type="number" step="0.01" class="form-control activity-public-target"
                                                            placeholder="0.00" min="0" disabled>
                                                    </div>
                                                </div>
                                            </div>

                                            <label class="form-label small mt-2">Unit <span class="text-danger">*</span></label>
                                            <select class="form-select activity-unit" disabled>
                                                <option value="">-- Select Unit --</option>
                                                <option value="hectare">Hectare</option>
                                                <option value="km">Kilometer</option>
                                                <option value="tree">Tree</option>
                                                <option value="person">Person</option>
                                                <option value="household">Household</option>
                                                <option value="site">Site</option>
                                                <option value="project">Project</option>
                                                <option value="session">Training Session</option>
                                                <option value="unit">Unit</option>
                                                <option value="piece">Piece</option>
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
                    <input type="hidden" name="environmental_data" id="environmental_data">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 border-top pt-4">
                <a href="{{ route('sector.environmental-plans.index') }}" class="btn btn-outline-secondary">
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
            const environmentalInput = document.getElementById('environmental_data');
            const selected = new Map();

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
                        
                        // Show special fields for "Mana Fincaanii ijaarame", default for others
                        if (activityName === 'Mana Fincaanii ijaarame') {
                            defaultFields.style.display = 'none';
                            specialFields.style.display = 'block';
                            privateTargetInput.disabled = false;
                            publicTargetInput.disabled = false;
                            selected.set(activityName, { 
                                id: activityId, 
                                name: activityName, 
                                private_target: '', 
                                public_target: '', 
                                unit: '',
                                has_special_fields: true
                            });
                        } else {
                            defaultFields.style.display = 'block';
                            specialFields.style.display = 'none';
                            targetInput.disabled = false;
                            selected.set(activityName, { 
                                id: activityId, 
                                name: activityName, 
                                target: '', 
                                unit: '',
                                has_special_fields: false
                            });
                        }
                        
                        unitSelect.disabled = false;
                    } else {
                        fields.style.display = 'none';
                        targetInput.disabled = true;
                        privateTargetInput.disabled = true;
                        publicTargetInput.disabled = true;
                        unitSelect.disabled = true;
                        
                        // Clear all inputs
                        targetInput.value = '';
                        privateTargetInput.value = '';
                        publicTargetInput.value = '';
                        unitSelect.value = '';
                        
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
                            // For Mana Fincaanii ijaarame
                            const privateTarget = item.querySelector('.activity-private-target').value;
                            const publicTarget = item.querySelector('.activity-public-target').value;
                            
                            selected.set(name, { 
                                ...activityData,
                                private_target: privateTarget,
                                public_target: publicTarget,
                                unit: unit
                            });
                        } else {
                            // For other activities
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
                        // Validate special fields activity
                        return {
                            id: activity.id,
                            name: activity.name,
                            private_target: activity.private_target,
                            public_target: activity.public_target,
                            unit: activity.unit,
                            has_special_fields: true
                        };
                    } else {
                        // Validate regular activity
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
                    submitBtn.classList.remove('btn-success');
                    submitBtn.classList.add('btn-secondary');
                } else {
                    submitBtn.classList.remove('btn-secondary');
                    submitBtn.classList.add('btn-success');
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

            // Initialize form with old values if validation failed
            function initializeOldValues() {
                @if(old('environmental_data'))
                    try {
                        const oldData = @json(json_decode(old('environmental_data'), true));
                        if (Array.isArray(oldData)) {
                            oldData.forEach(item => {
                                const checkbox = document.querySelector(`.activity-checkbox[value="${item.name}"]`);
                                if (checkbox) {
                                    checkbox.checked = true;
                                    checkbox.dispatchEvent(new Event('change'));
                                    
                                    const parent = checkbox.closest('.activity-item');
                                    
                                    if (item.has_special_fields) {
                                        // For Mana Fincaanii ijaarame
                                        parent.querySelector('.activity-private-target').value = item.private_target || '';
                                        parent.querySelector('.activity-public-target').value = item.public_target || '';
                                    } else {
                                        // For other activities
                                        parent.querySelector('.activity-target').value = item.target || '';
                                    }
                                    
                                    parent.querySelector('.activity-unit').value = item.unit || '';
                                    
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