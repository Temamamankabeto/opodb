<x-layout>
    <h2 class="text-xl mb-4">Seedlings List</h2>

    <a href="{{ route('seedlings.create') }}" class="btn btn-primary mb-3">Add New Seedling</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table-auto w-full border">
        <thead>
            <tr>
                <th class="border px-4 py-2">#</th>
                <th class="border px-4 py-2">Name</th>
                <th class="border px-4 py-2">Type</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($seedlings as $seedling)
                <tr>
                    <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="border px-4 py-2">{{ $seedling->name }}</td>
                    <td class="border px-4 py-2">{{ $seedling->seedlingType->name }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('seedlings.edit', $seedling->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('seedlings.destroy', $seedling->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-layout>
