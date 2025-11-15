<x-layout>
    <div class="container mt-4">

        <div class="d-flex justify-content-between mb-3">
            <h3 class="text-primary">Registration of Civil Certificates</h3>
            <a href="{{ route('civil.create') }}" class="btn btn-success">+ Add New</a>
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
                        <a href="{{ route('civil.edit', $i->id) }}" class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('civil.destroy', $i->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    </td>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout>
