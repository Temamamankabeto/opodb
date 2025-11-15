<x-layout>
<div class="container py-4">

    <h3 class="mb-3">Educational Effectiveness List</h3>

    <a href="{{ route('educational_effectiveness.create') }}" class="btn btn-success mb-3">Add New</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th width="80">ID</th>
                <th>Name</th>
                <th width="200">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $i)
            <tr>
                <td>{{ $i->id }}</td>
                <td>{{ $i->name }}</td>
                <td>
                    <a href="{{ route('educational_effectiveness.edit', $i->id) }}" class="btn btn-primary btn-sm">Edit</a>
                    
                    <form method="POST" action="{{ route('educational_effectiveness.delete', $i->id) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Delete this item?')" class="btn btn-danger btn-sm">Delete</button>
                    </form>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
</x-layout>
