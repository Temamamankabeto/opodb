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

        
 

        {{-- Buttons --}}
        <div class="mt-4">
            <button class="btn btn-success">
                {{ isset($association) ? 'Update' : 'Save' }}
            </button>
            <a href="{{ route('associations.index') }}" class="btn btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>

{{-- AJAX Script for Federation → Union --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    $('#federation').on('change', function() {
        const federationId = $(this).val();
        const unionSelect = $('#union');
        unionSelect.html('<option value="">Loading...</option>');

        if (!federationId) {
            unionSelect.html('<option value="">-- Select Federation First --</option>');
            return;
        }

        $.ajax({
            url: `/kebele/associations/get-unions/${federationId}`,
            method: 'GET',
            success: function(data) {
                let options = '<option value="">-- Select Union --</option>';
                data.forEach(function(u) {
                    options += `<option value="${u.id}">${u.name}</option>`;
                });
                unionSelect.html(options);
            },
            error: function() {
                unionSelect.html('<option value="">Error loading unions</option>');
            }
        });
    });

    // Auto-load unions when editing
    const existingFederation = $('#federation').val();
    const existingUnion = "{{ $association->union_id ?? '' }}";
    if (existingFederation && !$('#union').children('option').length > 1) {
        $.get(`/associations/get-unions/${existingFederation}`, function(data) {
            let options = '<option value="">-- Select Union --</option>';
            data.forEach(function(u) {
                const selected = u.id == existingUnion ? 'selected' : '';
                options += `<option value="${u.id}" ${selected}>${u.name}</option>`;
            });
            $('#union').html(options);
        });
    }
});
</script>
</x-layout>
