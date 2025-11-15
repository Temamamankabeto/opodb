<x-layout>
<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h3 class="text-primary">Enterprise Work</h3>
        <a href="{{ route('enterprise.create') }}" class="btn btn-success">+ Add New</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th width="60">ID</th>
                <th>Name</th>
                <th width="160">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($items as $i)
            <tr>
                <td>{{ $i->id }}</td>
                <td>{{ $i->name }}</td>
                <td>
                    <a href="{{ route('enterprise.edit', $i->id) }}" class="btn btn-warning btn-sm">Edit</a>

                    <form action="{{ route('enterprise.destroy', $i->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>

</div>
</x-layout>
