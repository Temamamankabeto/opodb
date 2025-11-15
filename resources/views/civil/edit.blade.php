<x-layout>
    <div class="container mt-4">

        <h3 class="text-warning mb-3">Edit Registration of Civil Certificate</h3>

        <form action="{{ route('civil.update', $item->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control"
                       value="{{ $item->name }}" required>

                @error('name')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button class="btn btn-primary">Update</button>
            <a href="{{ route('civil.index') }}" class="btn btn-secondary">Back</a>
        </form>

    </div>
</x-layout>
