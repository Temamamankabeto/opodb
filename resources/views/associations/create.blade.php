<x-layout>
<div class="container py-5">
    <h2 class="mb-4">
        {{ isset($association) ? 'Edit Association' : 'Add New Association' }}
    </h2>

    {{-- Form --}}
    <form action="{{ isset($association) ? route('associations.update', $association->id) : route('associations.store') }}" method="POST">
        @csrf
        @if(isset($association))
            @method('PUT')
        @endif

        {{-- Association Name --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Association Name</label>
            <input 
                type="text" 
                name="name" 
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $association->name ?? '') }}" 
                placeholder="Enter association name"
                required>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Association Type --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Association Type</label>
            <select name="association_type" class="form-select @error('association_type') is-invalid @enderror" required>
                <option value="">-- Select Association Type --</option>
                <option value="General" @selected(old('association_type', $association->association_type ?? '') == 'General')>General</option>
                <option value="Specific" @selected(old('association_type', $association->association_type ?? '') == 'Specific')>Specific</option>
            </select>
            @error('association_type')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Federation --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Federation</label>
            <select id="federation" name="federation_id" class="form-select @error('federation_id') is-invalid @enderror" required>
                <option value="">-- Select Federation --</option>
                @foreach($federations as $f)
                    <option value="{{ $f->id }}" 
                        @selected(old('federation_id', $association->federation_id ?? '') == $f->id)>
                        {{ $f->name }}
                    </option>
                @endforeach
            </select>
            @error('federation_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Union --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Union</label>
            <select id="union" name="union_id" class="form-select @error('union_id') is-invalid @enderror" required>
                <option value="">-- Select Union --</option>
                @if(isset($association) && $association->union)
                    <option value="{{ $association->union_id }}" selected>
                        {{ $association->union->name }}
                    </option>
                @endif
            </select>
            @error('union_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Service Data --}}
        @php
            $serviceOptions = ['Extension', 'Training', 'Consultancy', 'Support', 'Other']; // predefined service names
            $services = old('service_data', $association->service_data ?? []);
        @endphp
        <div class="mb-3">
            <label class="form-label fw-bold">Service Data</label>
            <div id="servicesContainer">
                @foreach($services as $index => $service)
                    <div class="service-item mb-2 border p-2 rounded d-flex gap-2 align-items-center">
                        <select name="service_data[{{ $index }}][name]" class="form-select" required>
                            <option value="">-- Select Service --</option>
                            @foreach($serviceOptions as $option)
                                <option value="{{ $option }}" @selected(($service['name'] ?? '') == $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="service_data[{{ $index }}][description]" 
                               class="form-control" 
                               placeholder="Description (optional)" 
                               value="{{ $service['description'] ?? '' }}">
                        <button type="button" class="btn btn-danger remove-service">Remove</button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-primary mt-2" id="addService">Add Service</button>
            @error('service_data')
                <small class="text-danger d-block">{{ $message }}</small>
            @enderror
        </div>

        {{-- Buttons --}}
        <div class="mt-4">
            <button class="btn btn-success">
                {{ isset($association) ? 'Update' : 'Save' }}
            </button>
            <a href="{{ route('associations.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    // Federation → Union
    $('#federation').on('change', function() {
        const federationId = $(this).val();
        const unionSelect = $('#union');
        unionSelect.html('<option value="">Loading...</option>');
        if (!federationId) {
            unionSelect.html('<option value="">-- Select Federation First --</option>');
            return;
        }
        $.get(`/associations/get-unions/${federationId}`, function(data) {
            let options = '<option value="">-- Select Union --</option>';
            data.forEach(function(u) {
                options += `<option value="${u.id}">${u.name}</option>`;
            });
            unionSelect.html(options);
        });
    });

    // Auto-load unions when editing
    const existingFederation = $('#federation').val();
    const existingUnion = "{{ $association->union_id ?? '' }}";
    if (existingFederation && existingUnion) {
        $.get(`/associations/get-unions/${existingFederation}`, function(data) {
            let options = '<option value="">-- Select Union --</option>';
            data.forEach(function(u) {
                const selected = u.id == existingUnion ? 'selected' : '';
                options += `<option value="${u.id}" ${selected}>${u.name}</option>`;
            });
            $('#union').html(options);
        });
    }

    // Add/Remove service items
    let serviceIndex = {{ count($services) ?? 0 }};
    const serviceOptions = {!! json_encode($serviceOptions) !!};

    $('#addService').on('click', function() {
        let html = `<div class="service-item mb-2 border p-2 rounded d-flex gap-2 align-items-center">`;
        html += `<select name="service_data[${serviceIndex}][name]" class="form-select" required>`;
        html += `<option value="">-- Select Service --</option>`;
        serviceOptions.forEach(function(opt) {
            html += `<option value="${opt}">${opt}</option>`;
        });
        html += `</select>`;
        html += `<input type="text" name="service_data[${serviceIndex}][description]" class="form-control" placeholder="Description (optional)">`;
        html += `<button type="button" class="btn btn-danger remove-service">Remove</button>`;
        html += `</div>`;
        $('#servicesContainer').append(html);
        serviceIndex++;
    });

    $(document).on('click', '.remove-service', function() {
        $(this).closest('.service-item').remove();
    });
});
</script>
</x-layout>
