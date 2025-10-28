<x-layout>
    <h2 class="text-xl mb-4">Add New Seedling</h2>

    <form method="POST" action="{{ route('seedlings.store') }}">
        @csrf

        <div class="mb-4">
            <label>Name</label>
            <input type="text" name="name" class="input" required>
        </div>

        <div class="mb-4">
            <label>Seedling Type</label>
            <select name="seedlingtype_id" class="input" required>
                <option value="">-- Select Type --</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('seedlings.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</x-layout>
