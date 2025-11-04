<x-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">

                <!-- Success Message -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold">Kebele Sectors</h4>
                    <a href="{{ route('sectors.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add New Sector
                    </a>
                </div>

                <div class="card shadow-lg border-0">
                    <div class="card-body p-0">
                        @if($sectors->count() > 0)
                        <table class="table table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Sector Name</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sectors as $sector)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $sector->name }}</td>
                                    <td>{{ $sector->created_at->format('d-M-Y') }}</td>
                                    <td>
                                        <form action="{{ route('sectors.destroy', $sector->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="p-3 text-center text-muted">
                            No sectors added yet. Click "Add New Sector" to start.
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layout>