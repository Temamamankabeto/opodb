<x-layout>
    <div class="container py-4">
        <h2 class="mb-4 text-primary fw-bold"><i class="bi bi-diagram-3-fill me-2"></i> Gosa Midhaanii Hunda </h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Add Crop Type button --}}
        <div class="mb-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addZoneModal">
                <i class="bi bi-plus-circle-fill"></i> Add Crop Type
            </button>
        </div>

        @foreach ($croptypes as $croptype)
            <div class="card shadow-sm mb-4 border-primary">
                <div class="card-header" style="background-color: whitesmoke;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary"><i class="bi bi-map-fill me-2"></i> {{ $croptype->name }}</h5>
                        <button class="btn btn-sm btn-outline-primary rounded-circle" data-bs-toggle="collapse" data-bs-target="#croptype-{{ $croptype->id }}">
                            <i class="bi bi-chevron-double-down"></i>
                        </button>
                    </div>
                </div>

                <div id="croptype-{{ $croptype->id }}" class="collapse">
                    <div class="card-body py-2">
                        <button class="btn btn-sm btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addWoredaModal{{ $croptype->id }}">
                            <i class="bi bi-plus-circle"></i> Add Crop
                        </button>

                        @forelse ($croptype->crops as $crop)
                            <div class="border rounded bg-light p-3 mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div><i class="bi bi-signpost-2-fill text-success me-2"></i><strong>{{ $crop->name }}</strong></div>
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#crop-{{ $crop->id }}">
                                        <i class="bi bi-caret-down-square"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No crops in this crop type.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Add Crop Modal --}}
            <div class="modal fade" id="addWoredaModal{{ $croptype->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('crops.store') }}" class="modal-content">
                        @csrf
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">Add Crop to {{ $croptype->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="croptype_id" value="{{ $croptype->id }}">
                            <input type="text" name="name" class="form-control" placeholder="Crop Name" required>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Add Crop Type Modal --}}
    <div class="modal fade" id="addZoneModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('croptype.store') }}" class="modal-content">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add Crop Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="name" class="form-control" placeholder="Crop Type Name" required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Styles --}}
    <style>
        body {
            font-family: 'Segoe UI', Roboto, sans-serif;
            background-color: #f8f9fa;
        }
        .list-group-item:hover {
            background-color: #e9ecef;
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</x-layout>