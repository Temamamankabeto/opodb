<x-layout>
<div class="container py-5">
    <h2>Add New Federation</h2>

    <form method="POST" action="{{ route('federations.store') }}" class="card p-4 shadow mt-3">
        @csrf
        <div class="mb-3">
            <label class="form-label">Federation Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('federations.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
</x-layout>
