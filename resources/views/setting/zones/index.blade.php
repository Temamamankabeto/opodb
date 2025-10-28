 <x-layout>
    @php
    $nonce = request()->attributes->get('csp_nonce');
@endphp

<script nonce="{{ $nonce }}">
    console.log('Safe inline script');
</script>
    <div class="container py-4">
        <h2 class="mb-4 text-primary fw-bold"><i class="bi bi-diagram-3-fill me-2"></i> Oromia Kebele Administrative Tree Structure</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        {{-- Add Zone button --}}
        @if(Auth::user()->role_id == 1)
            <div class="mb-4">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addZoneModal">
                    <i class="bi bi-plus-circle-fill"></i> Add Zone
                </button>
            </div>
        @endif

        @foreach ($zones as $zone)
            <div class="card shadow-sm mb-4 border-primary">
                <div class="card-header" style="background-color: whitesmoke;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary"><i class="bi bi-map-fill me-2"></i> {{ $zone->name }}</h5>
                        <button class="btn btn-sm btn-outline-primary rounded-circle" data-bs-toggle="collapse"
                            data-bs-target="#zone-{{ $zone->id }}">
                            <i class="bi bi-chevron-double-down"></i>
                        </button>
                    </div>
                </div>

                <div id="zone-{{ $zone->id }}" class="collapse">
                    <div class="card-body py-2">
                        <button class="btn btn-sm btn-success mb-2" data-bs-toggle="modal"
                            data-bs-target="#addWoredaModal{{ $zone->id }}">
                            <i class="bi bi-plus-circle"></i> Add Woreda
                        </button>

                        @forelse ($zone->woredas as $woreda)
                            <div class="border rounded bg-light p-3 mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi bi-signpost-2-fill text-success me-2"></i><strong>{{ $woreda->name }}</strong>
                                    </div>
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse"
                                        data-bs-target="#woreda-{{ $woreda->id }}">
                                        <i class="bi bi-caret-down-square"></i>
                                    </button>
                                </div>

                                <div class="mt-2 collapse" id="woreda-{{ $woreda->id }}">
                                    <button class="btn btn-sm btn-secondary mb-2" data-bs-toggle="modal"
                                        data-bs-target="#addKebeleModal{{ $woreda->id }}">
                                        <i class="bi bi-plus-circle"></i> Add Kebele
                                    </button>

                                    <ul class="list-group list-group-flush ms-4">
                                        @forelse ($woreda->kebeles as $kebele)
                                            <li class="list-group-item border-0 ps-4">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class="bi bi-dot text-primary"></i> {{ $kebele->name }}
                                                    </div>

                                                    <div>
                                                        <button class="btn btn-sm btn-outline-info"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#sectors-{{ $kebele->id }}">
                                                            <i class="bi bi-diagram-3"></i> Sectors
                                                        </button>
                                                        <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                                            data-bs-target="#editKebeleModal{{ $kebele->id }}">
                                                            <i class="bi bi-pencil"></i> Edit
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="collapse mt-2 ms-4" id="sectors-{{ $kebele->id }}">
                                                    <button class="btn btn-sm btn-info mb-2" data-bs-toggle="modal"
                                                        data-bs-target="#addSectorModal{{ $kebele->id }}">
                                                        <i class="bi bi-plus-circle"></i> Add Sector
                                                    </button>

                                                    <ul class="list-group list-group-flush ms-3">
                                                        @forelse ($kebele->sectors as $sector)
                                                            <li class="list-group-item border-0">
                                                                <i class="bi bi-box-arrow-in-right text-info"></i> {{ $sector->name }}
                                                            </li>
                                                        @empty
                                                            <li class="list-group-item text-muted">No sectors</li>
                                                        @endforelse
                                                    </ul>
                                                </div>
                                            </li>

                                            {{-- Add Sector Modal --}}
                                            <div class="modal fade" id="addSectorModal{{ $kebele->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <form method="POST" action="{{ route('sectors.store') }}"
                                                        class="modal-content">
                                                        @csrf
                                                        <div class="modal-header bg-dark text-white">
                                                            <h5 class="modal-title">Add Sector to {{ $kebele->name }}</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="kebele_id"
                                                                value="{{ $kebele->id }}">
                                                            <input type="text" name="name" class="form-control"
                                                                placeholder="Sector Name" required>
                                                                
                                                                
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-dark" type="submit">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            {{-- Edit Kebele Modal --}}
                                            <div class="modal fade" id="editKebeleModal{{ $kebele->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title">Edit Kebele: {{ $kebele->name }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form method="POST" action="{{ route('admin.kebeles.update', $kebele->id) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="row mb-3">
                                                                    <label for="name" class="col-sm-3 col-form-label">Kebele Name</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control" name="name" value="{{ $kebele->name }}" required>
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3">
    <label for="total_area" class="col-sm-3 col-form-label">Total Area</label>
    <div class="col-sm-9">
        <input type="number" class="form-control" name="total_area" value="{{ $kebele->total_area }}" min="1" step="1" required>
    </div>
</div>

<div class="row mb-3">
    <label for="total_farm_area" class="col-sm-3 col-form-label">Farm Area</label>
    <div class="col-sm-9">
        <input type="number" class="form-control" name="total_farm_area" value="{{ $kebele->total_farm_area }}" min="1" step="1" required>
    </div>
</div>

<div class="row mb-3">
    <label for="total_population"  class="col-sm-3 col-form-label">Population </label>
    <div class="col-sm-9">
        <input type="number" class="form-control" name="total_population" value="{{ $kebele->total_population }}" min="1" step="1" required>
    </div>
</div>

<div class="row mb-3">
    <label for="male" class="col-sm-3 col-form-label">Male</label>
    <div class="col-sm-9">
        <input type="number" class="form-control" name="male" value="{{ $kebele->male }}" min="1" step="1" required>
    </div>
</div>

<div class="row mb-3">
    <label for="female" class="col-sm-3 col-form-label">Female</label>
    <div class="col-sm-9">
        <input type="number" class="form-control" name="female" value="{{ $kebele->female }}" min="1" step="1" required>
    </div>
</div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <li class="list-group-item text-muted ps-4">No Kebeles</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>

                            {{-- Add Kebele Modal --}}
                            <div class="modal fade" id="addKebeleModal{{ $woreda->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('admin.kebeles.store') }}" class="modal-content">
                                        @csrf
                                        <div class="modal-header bg-secondary text-white">
                                            <h5 class="modal-title">Add Kebele to {{ $woreda->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="woreda_id" value="{{ $woreda->id }}">
                                            <div class="mb-3">
                                                <input type="text" name="name" class="form-control" placeholder="Kebele Name" required>
                                            </div>
                                            <div class="mb-3">
                                                <input type="text" name="total_area" class="form-control" placeholder="Total Area" required>
                                            </div>
                                            <div class="mb-3">
                                                <input type="text" name="total_farm_area" class="form-control" placeholder="Total Farm Area" required>
                                            </div>
                                            <div class="mb-3">
                                                <input type="text" name="total_population" class="form-control" placeholder="Total Population" required>
                                            </div>
                                            <div class="mb-3">
                                                <input type="text" name="male" class="form-control" placeholder="Male" required>
                                            </div>
                                            <div class="mb-3">
                                                <input type="text" name="female" class="form-control" placeholder="Female" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="submit">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No Woredas in this zone.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Add Woreda Modal --}}
            <div class="modal fade" id="addWoredaModal{{ $zone->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('admin.woreda.store') }}" class="modal-content">
                        @csrf
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">Add Woreda to {{ $zone->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="zone_id" value="{{ $zone->id }}">
                            <input type="text" name="name" class="form-control" placeholder="Woreda Name" required>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Add Zone Modal --}}
    <div class="modal fade" id="addZoneModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.zones.store') }}" class="modal-content">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add Zone</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="name"  class="form-control" placeholder="Zone Name" required>
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