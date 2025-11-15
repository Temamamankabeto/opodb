<x-layout>
<div class="container py-5">

    <h3 class="fw-bold mb-4">Educational Participations</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('educational_participations.create') }}" class="btn btn-success mb-3">
        + Add New
    </a>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height:400px; overflow-y:auto;">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-success">
                        <tr>
                            <th width="10%">#</th>
                            <th>Name</th>
                            <th width="20%">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($items as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    <a href="{{ route('educational_participations.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                    <form action="{{ route('educational_participations.destroy', $item->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No records found</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>
</x-layout>
