<x-layout>
    <div class="container py-4">
        <h2 class="mb-4 text-success fw-bold">Livestock Supplies</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('livestock_product.create') }}" class="btn btn-success mb-3">+ Add New Supply</a>

        <table class="table table-bordered table-striped">
            <thead class="table-success">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th width="180px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($supplies as $supply)
                    <tr>
                        <td>{{ $supply->id }}</td>
                        <td>{{ $supply->name }}</td>
                        <td>
                            <a href="{{ route('livestock_product.edit', $supply->id) }}"
                                class="btn btn-warning btn-sm">Edit</a>
                            <a href="{{ route('livestock_product.delete', $supply->id) }}"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">No livestock supplies found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layout>
