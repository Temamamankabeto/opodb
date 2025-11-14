<x-layout>
<div class="container py-5">
    <h3 class="mb-4 fw-bold">Edit Association Type</h3>

    <form action="{{ route('sector.association_types.update', $type->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label fw-bold">Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $type->name) }}" required>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button class="btn btn-warning">Update</button>
        <a href="{{ route('sector.association_types.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</x-layout>
