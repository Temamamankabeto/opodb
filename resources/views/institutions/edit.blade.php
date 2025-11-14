<x-layout>
<div class="container py-5">
    <h3 class="mb-4 fw-bold">Edit Institution</h3>

    <form action="{{ route('institutions.update', $institution->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label fw-bold">Institution Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $institution->name) }}" required>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Institution Type</label>
            <select name="institution_type_id" class="form-select @error('institution_type_id') is-invalid @enderror" required>
                <option value="">-- Select Type --</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}" @selected(old('institution_type_id', $institution->institution_type_id) == $type->id)>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            @error('institution_type_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button class="btn btn-warning">Update</button>
        <a href="{{ route('institutions.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</x-layout>
