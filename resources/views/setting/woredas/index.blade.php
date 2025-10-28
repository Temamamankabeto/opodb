<x-layout>
    <div class="container mt-5">
        <div class="row mb-3">
            <div class="col-md-6">
                <h3><i class="bi bi-building-fill"></i> Woredas</h3>
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
                    <th>Woreda Name</th>
                    <th>Zone</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($woredas as $woreda)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $woreda->name }}</td>
                        <td>{{ $woreda->zone?->name }}</td>
                        <td>
                          <button type="button"
    class="btn btn-sm btn-warning"
    data-bs-toggle="modal"
    data-bs-target="#editWoredaModal{{ $woreda->id }}">
    <i class="bi bi-pencil-square"></i> Edit
</button>
                            <form action="#" method="POST" class="d-inline"
                                  onsubmit="return confirm('Are you sure to delete this woreda?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    {{-- Edit Woreda Modal --}}
<div class="modal fade" id="editWoredaModal{{ $woreda->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{route('admin.woreda.update',$woreda->id)}}" id="editWoredaForm">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Woreda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Zone</label>
                        <select class="form-select" name="zone_id" id="editZoneId" required>
    <option value="">-- Select Zone --</option>
    @foreach ($zones as $zone)
        <option value="{{ $zone->id }}"
            {{ old('zone_id', $zone->zone_id ?? '') == $zone->id ? 'selected' : '' }}>
            {{ $zone->name }}
        </option>
    @endforeach
</select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Woreda Name</label>
                        <input type="text" name="name" value="{{$woreda->name}}" id="editName" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Update Woreda
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
                @empty
                    <tr><td colspan="4">No woredas found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $woredas->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>

    {{-- Create Woreda Modal --}}
    <div class="modal fade" id="createWoredaModal" tabindex="-1" aria-labelledby="createWoredaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{route('woredas.store')}}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Woreda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Zone</label>
                            <select class="form-select" name="zone_id" required>
                                <option value="">-- Select Zone --</option>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                            @error('zone_id')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Woreda Name</label>
                            <input type="text" name="name" class="form-control" required>
                            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Save Woreda
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layout>
