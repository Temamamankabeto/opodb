<x-layout>
<div class="container mt-4">

    <h3 class="text-warning">Edit Enterprise Work</h3>

    <form action="{{ route('enterprise.update', $item->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>

            @error('name')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('enterprise.index') }}" class="btn btn-secondary">Cancel</a>
    </form>

</div>
</x-layout>
