<x-layout>
<div class="container py-5">
    <h2 class="mb-4">
        <i class="bi bi-people-fill me-2"></i>
        {{ isset($association) ? 'Edit Association' : 'Add New Association' }}
    </h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($association) ? route('associations.update', $association->id) : route('associations.store') }}" method="POST" id="mainForm">
        @csrf
        @if(isset($association))
            @method('PUT')
        @endif

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0"><i class="bi bi-building me-2"></i>Association Information</h5>
            </div>
            <div class="card-body p-4">
                {{-- Association Name --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Association Name <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            name="name" 
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $association->name ?? '') }}" 
                            placeholder="Enter association name"
                            required>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Association Type --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Association Type <span class="text-danger">*</span></label>
                        <select name="association_type" class="form-select @error('association_type') is-invalid @enderror" required>
                            <option value="">-- Select Association Type --</option>
                            <option value="General" @selected(old('association_type', $association->association_type ?? '') == 'General')>General</option>
                            <option value="Specific" @selected(old('association_type', $association->association_type ?? '') == 'Specific')>Specific</option>
                            <option value="Cooperative" @selected(old('association_type', $association->association_type ?? '') == 'Cooperative')>Cooperative</option>
                            <option value="Trade Union" @selected(old('association_type', $association->association_type ?? '') == 'Trade Union')>Trade Union</option>
                        </select>
                        @error('association_type')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Federation and Union --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Federation <span class="text-danger">*</span></label>
                        <select id="federation" name="federation_id" class="form-select @error('federation_id') is-invalid @enderror" required>
                            <option value="">-- Select Federation --</option>
                            @foreach($federations as $federation)
                                <option value="{{ $federation->id }}" 
                                    @selected(old('federation_id', $association->federation_id ?? '') == $federation->id)>
                                    {{ $federation->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('federation_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Union <span class="text-danger">*</span></label>
                        <select id="union" name="union_id" class="form-select @error('union_id') is-invalid @enderror" required>
                            <option value="">-- Select Union --</option>
                            @if(isset($association) && $association->union)
                                <option value="{{ $association->union_id }}" selected>
                                    {{ $association->union->name }}
                                </option>
                            @endif
                        </select>
                        @error('union_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Services Section --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success text-white py-3">
                <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Association Services</h5>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info py-2 mb-3">
                    <i class="bi bi-info-circle me-2"></i>
                    Select the services provided by this association. At least one service must be selected.
                </div>

                <div class="row g-3" id="servicesContainer">
                    @foreach($services as $service)
                        @php
                            $isChecked = false;
                            if (isset($association) && is_array($association->service_data)) {
                                $isChecked = collect($association->service_data)->contains('id', $service->id);
                            }
                        @endphp
                        <div class="col-md-6">
                            <div class="card border service-item {{ $isChecked ? 'checked' : '' }}">
                                <div class="card-body">
                                    <div class="form-check">
                                        <input class="form-check-input service-checkbox" 
                                               type="checkbox" 
                                               value="{{ $service->id }}" 
                                               id="service-{{ $service->id }}"
                                               data-service-name="{{ $service->name }}"
                                               name="services[]"
                                               {{ $isChecked ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="service-{{ $service->id }}">
                                            {{ $service->name }}
                                        </label>
                                        @if($service->description)
                                            <div class="form-text small text-muted mt-1">{{ $service->description }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('service_data')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
                <input type="hidden" name="service_data" id="service_data" value="{{ old('service_data', isset($association) ? json_encode($association->service_data) : '') }}">
            </div>
        </div>

        {{-- Buttons --}}
        <div class="d-flex justify-content-end gap-3 border-top pt-4">
            <a href="{{ route('associations.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Cancel
            </a>
            <button type="submit" class="btn btn-success px-4" id="submitBtn">
                <i class="bi bi-save me-2"></i>{{ isset($association) ? 'Update' : 'Save' }} Association
            </button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    const federationSelect = $('#federation');
    const unionSelect = $('#union');
    const serviceDataInput = $('#service_data');
    const submitBtn = $('#submitBtn');
    const selectedServices = new Map();

    // Initialize with existing service data
    function initializeServiceData() {
        @if(isset($association) && is_array($association->service_data))
            @foreach($association->service_data as $service)
                selectedServices.set({{ $service['id'] }}, {
                    id: {{ $service['id'] }},
                    name: '{{ $service['name'] }}'
                });
            @endforeach
            updateServiceData();
        @endif
    }

    // Federation -> Union cascading
    federationSelect.on('change', function() {
        const federationId = $(this).val();
        console.log('Federation changed to:', federationId);
        
        unionSelect.html('<option value="">Loading unions...</option>');
        unionSelect.prop('disabled', true);

        if (!federationId) {
            unionSelect.html('<option value="">-- Select Federation First --</option>');
            unionSelect.prop('disabled', false);
            return;
        }

        // Use the correct route URL
        const url = '{{ route("associations.get-unions", ":federation_id") }}'.replace(':federation_id', federationId);
        
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log('Unions loaded:', data);
                let options = '<option value="">-- Select Union --</option>';
                
                if (data.length > 0) {
                    data.forEach(union => {
                        options += `<option value="${union.id}">${union.name}</option>`;
                    });
                } else {
                    options = '<option value="">No unions found for this federation</option>';
                }
                
                unionSelect.html(options);
                unionSelect.prop('disabled', false);
                
                // Trigger form validation
                validateForm();
            },
            error: function(xhr, status, error) {
                console.error('Error loading unions:', error);
                unionSelect.html('<option value="">Error loading unions</option>');
                unionSelect.prop('disabled', false);
            }
        });
    });

    // Service checkbox handling
    $('.service-checkbox').on('change', function() {
        const serviceId = $(this).val();
        const serviceName = $(this).data('service-name');
        const serviceItem = $(this).closest('.service-item');

        if (this.checked) {
            serviceItem.addClass('checked');
            selectedServices.set(serviceId, {
                id: serviceId,
                name: serviceName
            });
        } else {
            serviceItem.removeClass('checked');
            selectedServices.delete(serviceId);
        }
        updateServiceData();
        validateForm();
    });

    function updateServiceData() {
        const serviceData = Array.from(selectedServices.values());
        serviceDataInput.val(JSON.stringify(serviceData));
        console.log('Service data updated:', serviceData);
    }

    function validateForm() {
        const hasFederation = federationSelect.val() !== '';
        const hasUnion = unionSelect.val() !== '';
        const hasServices = selectedServices.size > 0;
        const hasName = $('input[name="name"]').val() !== '';
        const hasType = $('select[name="association_type"]').val() !== '';

        const isValid = hasFederation && hasUnion && hasServices && hasName && hasType;
        
        submitBtn.prop('disabled', !isValid);
        
        // Visual feedback
        if (submitBtn.prop('disabled')) {
            submitBtn.removeClass('btn-success').addClass('btn-secondary');
        } else {
            submitBtn.removeClass('btn-secondary').addClass('btn-success');
        }

        console.log('Form validation:', { hasFederation, hasUnion, hasServices, hasName, hasType, isValid });
        return isValid;
    }

    // Real-time form validation
    $('input[name="name"], select[name="association_type"], #federation, #union').on('change input', function() {
        validateForm();
    });

    // Form submission handler
    $('#mainForm').on('submit', function(e) {
        updateServiceData(); // Ensure service data is updated
        
        if (!validateForm()) {
            e.preventDefault();
            alert('Please fill all required fields and select at least one service.');
            return;
        }
        
        // Additional check for service data
        const serviceData = JSON.parse(serviceDataInput.val() || '[]');
        if (serviceData.length === 0) {
            e.preventDefault();
            alert('Please select at least one service.');
            return;
        }
    });

    // If editing and federation is already selected, load its unions
    @if(isset($association) && $association->federation_id)
        $(document).ready(function() {
            // Trigger the change event to load unions for the pre-selected federation
            federationSelect.trigger('change');
        });
    @endif

    // Initialize
    initializeServiceData();
    validateForm();
});
</script>

<style>
.service-item {
    transition: all 0.3s ease;
    cursor: pointer;
}

.service-item:hover {
    background-color: #f8f9fa;
    transform: translateY(-2px);
}

.service-item.checked {
    border-color: #198754;
    background-color: #f8fff9;
}

.form-check-input:checked ~ .form-check-label {
    color: #198754;
    font-weight: bold;
}

.btn:disabled {
    cursor: not-allowed;
}

.card-header {
    border-radius: 0.375rem 0.375rem 0 0 !important;
}

#union:disabled {
    background-color: #f8f9fa;
    opacity: 0.7;
}
</style>
</x-layout>