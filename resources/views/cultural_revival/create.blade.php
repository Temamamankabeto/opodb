<x-layout>
<div class="container py-4">

    <h3>Add Cultural Revival</h3>

    <form action="{{ route('cultural_revival.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="fw-bold">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <button class="btn btn-success">Save</button>
        <a href="{{ route('cultural_revival.index') }}" class="btn btn-secondary">Cancel</a>
    </form>

</div>
</x-layout>
