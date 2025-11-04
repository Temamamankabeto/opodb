<x-layout>
<div class="container">
    <h2>Fertilizer Types</h2>
    <a href="{{ url('fertilizers/create') }}" class="btn btn-primary mb-3">Add Fertilizer Type</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="table-secondary">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th width="180px">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($fertilizers as $fertilizer)
            <tr>
                <td>{{ $fertilizer->id }}</td>
                <td>{{ $fertilizer->name }}</td>
                <td>
                    <a href="{{ route('fertilizer.edit', $fertilizer->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('fertilizer.delete', $fertilizer->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this item?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center">No data found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
</x-layout>
