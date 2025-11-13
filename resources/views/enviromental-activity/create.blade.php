<x-layout>
<div class="container py-4">
    <h3 class="mb-4 text-success">Add Enviromental Activity</h3>

    <form action="{{ route('enviromental-activity.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Enviromental Activity</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('enviromental-activity.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</x-layout>
