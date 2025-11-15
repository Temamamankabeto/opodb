<x-layout>
    <div class="container mt-4">

        <h3 class="text-success mb-3">Add Kebele Government Property</h3>

        <form action="{{ route('govprop.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Property Name</label>
                <input type="text" name="name" class="form-control" required>

                @error('name')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button class="btn btn-primary">Save</button>
            <a href="{{ route('govprop.index') }}" class="btn btn-secondary">Back</a>
        </form>

    </div>
</x-layout>
