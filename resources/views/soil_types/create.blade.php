<x-layout>
<div class="container">
    <h2>Add Soil Type</h2>

    <form action="{{ route('soil.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Soil Type Name</label>
            <input type="text" name="name" class="form-control" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button class="btn btn-success">Save</button>
        <a href="{{ route('soil.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
</x-layout>