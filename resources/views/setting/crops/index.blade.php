<x-layout>
    <div class="container mt-5">
        <div class="row mb-3">
            <div class="col-md-6">
                <h3><i class="bi bi-building-fill"></i> Crop</h3>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createWoredaModal">
                    <i class="bi bi-plus-circle"></i> Add Crop
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Crop Name</th>
                    <th>Crop Type</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($crops as $crop)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $crop->name }}</td>
                        <td>{{ $crop->croptype->name }}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="#" method="POST" class="d-inline"
                                  onsubmit="return confirm('Are you sure to delete this woreda?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">No crop found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $crops->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>

    {{-- Create crop Modal --}}
    <div class="modal fade" id="createWoredaModal" tabindex="-1" aria-labelledby="createWoredaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{route('crops.store')}}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New crop</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Crop type</label>
                            <select class="form-select" name="croptype_id" required>
                                <option value="">-- Select Zone --</option>
                                @foreach ($croptypes as $croptype)
                                    <option value="{{ $zone->id }}">{{ $croptype->name }}</option>
                                @endforeach
                            </select>
                            @error('croptype_id')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Crop Name</label>
                            <input type="text" name="name" class="form-control" required>
                            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Save Crop
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layout>
