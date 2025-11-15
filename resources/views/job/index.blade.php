<x-layout>
<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h3 class="text-primary">Job Opportunities</h3>
        <a href="{{ route('job.create') }}" class="btn btn-success">+ Add New</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th width="50">ID</th>
                <th>Name</th>
                <th width="180">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($items as $i)
            <tr>
                <td>{{ $i->id }}</td>
                <td>{{ $i->name }}</td>
                <td>
                    <a href="{{ route('job.edit', $i->id) }}" class="btn btn-warning btn-sm">Edit</a>

                    <form action="{{ route('job.destroy', $i->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this item?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>
</x-layout>
