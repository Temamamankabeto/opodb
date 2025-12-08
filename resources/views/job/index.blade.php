<x-layout>
    <div class="content-header">
        <section class="content">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="text-primary">Job Opportunities</h3>
                            <a href="{{ route('job.create') }}" class="btn btn-success">+ Add New</a>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body p-0">
                                <table class="table table-bordered table-striped m-0">
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
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-layout>