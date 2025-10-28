<x-layout>
    <h2 class="text-xl mb-4">Add Seedling Type</h2>

    <form action="{{ route('seedling-types.store') }}" method="POST">
        @csrf
        <div>
            <label>Name</label>
            <input type="text" name="name" class="input" required>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Save</button>
        <a href="{{ route('seedling-types.index') }}" class="btn btn-secondary mt-2">Cancel</a>
    </form>
</x-layout>
