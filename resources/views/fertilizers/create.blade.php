<x-layout>
<div class="container">
    <h2>Add Fertilizer</h2>

    <form action="{{ route('fertilizer.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Fertilizer Type</label>
            <select name="fertilizer_type_id" class="form-select" required>
                <option value="">-- Select Type --</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
            @error('fertilizer_type_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Fertilizer Name</label>
            <input type="text" name="name" class="form-control" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Unit</label>
            <input type="text" name="unit" class="form-control" required>
            @error('unit') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button class="btn btn-success">Save</button>
        <a href="{{ route('fertilizer.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
</x-layout>
