<x-layout>
<div class="container">
    <h2>Add Fertilizer Type</h2>

    <form action="{{ route('fertilizer.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Fertilizer Name</label>
            <input type="text" name="name" class="form-control" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button class="btn btn-success">Save</button>
        <a href="{{ route('fertilizer.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
</x-layout>