<x-layout>
<div class="container py-5">
    <h3 class="mb-4 fw-bold">Add Association Type</h3>

    <form action="{{ route('sector.association_types.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-bold">Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   placeholder="Enter type name" value="{{ old('name') }}" required>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button class="btn btn-success">Save</button>
        <a href="{{ route('sector.association_types.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</x-layout>
