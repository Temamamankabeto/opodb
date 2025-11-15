<x-layout>
<div class="container py-4">

    <h3 class="mb-3">Student Meal Supply List</h3>

    <a href="{{ route('student_meal_supply.create') }}" class="btn btn-success mb-3">Add New</a>

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
                    <a href="{{ route('student_meal_supply.edit', $i->id) }}" class="btn btn-primary btn-sm">Edit</a>
                    
                    <form action="{{ route('student_meal_supply.delete', $i->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</button>
                    </form>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
</x-layout>
