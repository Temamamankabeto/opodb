<x-layout>
<div class="container mt-4">

    <h3 class="text-success">Add Enterprise Work</h3>

    <form action="{{ route('enterprise.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>

            @error('name')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button class="btn btn-primary">Save</button>
        <a href="{{ route('enterprise.index') }}" class="btn btn-secondary">Cancel</a>
    </form>

</div>
</x-layout>
