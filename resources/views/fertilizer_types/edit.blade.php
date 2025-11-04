<x-layout>
<div class="container">
    <h2>Edit Fertilizer Type</h2>

    <form action="{{ route('fertilizer.update', $fertilizer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Fertilizer Name</label>
            <input type="text" name="name" class="form-control" value="{{ $fertilizer->name }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('fertilizer.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
</x-layout>