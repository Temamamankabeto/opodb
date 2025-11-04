<x-layout>
<div class="container">
    <h2>Edit Soil Type</h2>

    <form action="{{ route('soil.update', $soil->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Soil Type Name</label>
            <input type="text" name="name" class="form-control" value="{{ $soil->name }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('soil.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
</x-layout>