<x-layout>
<div class="container py-4">

    <h3>Add Health</h3>

    <form action="{{ route('health.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="fw-bold">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <button class="btn btn-success">Save</button>
        <a href="{{ route('health.index') }}" class="btn btn-secondary">Cancel</a>
    </form>

</div>
</x-layout>
