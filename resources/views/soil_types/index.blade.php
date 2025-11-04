<x-layout>
<div class="container">
    <h2>Soil Types</h2>
    <a href="{{ route('soil.create') }}" class="btn btn-primary mb-3">Add Soil Type</a>

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
            @forelse($soils as $soil)
            <tr>
                <td>{{ $soil->id }}</td>
                <td>{{ $soil->name }}</td>
                <td>
                    <a href="{{ route('soil.edit', $soil->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('soil.delete', $soil->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this soil type?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center">No soil types found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
</x-layout>