<x-layout>
<div class="container py-5">
    <h2>Edit Federation</h2>

    <form method="POST" action="{{ route('federations.update', $federation->id) }}" class="card p-4 shadow mt-3">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Federation Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $federation->name) }}" required>
            @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('federations.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
</x-layout>
