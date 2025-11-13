<x-layout>
<div class="container py-4">
    <h3 class="mb-4 text-success">Edit Livestock Supply</h3>

    <form action="{{ route('naturals.update', $supply->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Supply Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $supply->name) }}" required>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-warning">Update</button>
        <a href="{{ route('naturals.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
</x-layout>
