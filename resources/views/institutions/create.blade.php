<x-layout>
<div class="container py-5">
    <h3 class="mb-4 fw-bold">Add Institution</h3>

    <form action="{{ route('institutions.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-bold">Institution Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   placeholder="Enter institution name" value="{{ old('name') }}" required>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Institution Type</label>
            <select name="institution_type_id" class="form-select @error('institution_type_id') is-invalid @enderror" required>
                <option value="">-- Select Type --</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}" @selected(old('institution_type_id') == $type->id)>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            @error('institution_type_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button class="btn btn-success">Save</button>
        <a href="{{ route('institutions.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</x-layout>
