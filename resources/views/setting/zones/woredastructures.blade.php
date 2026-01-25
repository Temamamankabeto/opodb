 <x-layout>
    <div class="container py-4">
        <h2 class="mb-4 text-primary fw-bold">
            <i class="bi bi-diagram-3-fill me-2"></i> Oromia Kebele Administrative Tree Structure
        </h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Add Zone button for Super Admin --}}
        @if(Auth::user()->role_id == 1)
            <div class="mb-4">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addZoneModal">
                    <i class="bi bi-plus-circle-fill"></i> Add Zone
                </button>
            </div>
        @endif

        {{-- Loop Zones --}}
        @forelse ($zones as $zone)
            <div class="card shadow-sm mb-4 border-primary">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-map-fill me-2"></i> {{ $zone->name }}
                        </h5>
                        <button class="btn btn-sm btn-outline-primary rounded-circle"
                                type="button"  {{-- IMPORTANT: Add type="button" --}}
                                data-bs-toggle="collapse" 
                                data-bs-target="#zone-{{ $zone->id }}">
                            <i class="bi bi-chevron-double-down"></i>
                        </button>
                    </div>
                </div>

                <div id="zone-{{ $zone->id }}" class="collapse show">
                    <div class="card-body py-2">

                        {{-- Add Woreda button for Super Admin only --}}
                        @if(Auth::user()->role_id == 1)
                            <button class="btn btn-sm btn-success mb-2" 
                                    type="button" {{-- IMPORTANT: Add type="button" --}}
                                    data-bs-toggle="modal"
                                    data-bs-target="#addWoredaModal{{ $zone->id }}">
                                <i class="bi bi-plus-circle"></i> Add Woreda
                            </button>
                        @endif

                        {{-- Loop Woredas --}}
                        @forelse ($zone->woredas as $woreda)
                            <div class="border rounded bg-light p-3 mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>
                                        <i class="bi bi-signpost-2-fill text-success me-2"></i> {{ $woreda->name }}
                                    </strong>
                                    <button class="btn btn-sm btn-outline-secondary" 
                                            type="button" {{-- IMPORTANT: Add type="button" --}}
                                            data-bs-toggle="collapse"
                                            data-bs-target="#woreda-{{ $woreda->id }}">
                                        <i class="bi bi-caret-down-square"></i>
                                    </button>
                                </div>

                                {{-- Kebeles --}}
                                <div class="mt-2 collapse show" id="woreda-{{ $woreda->id }}">
                                    @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                                        <button class="btn btn-sm btn-secondary mb-2" 
                                                type="button" {{-- IMPORTANT: Add type="button" --}}
                                                data-bs-toggle="modal"
                                                data-bs-target="#addKebeleModal{{ $woreda->id }}">
                                            <i class="bi bi-plus-circle"></i> Add Kebele
                                        </button>
                                    @endif

                                    <ul class="list-group list-group-flush ms-4">
                                        @forelse ($woreda->kebeles as $kebele)
                                            <li class="list-group-item border-0 ps-4">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span><i class="bi bi-dot text-primary"></i> {{ $kebele->name }}</span>
                                                    <button class="btn btn-sm btn-outline-info"
                                                            type="button" {{-- IMPORTANT: Add type="button" --}}
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#sectors-{{ $kebele->id }}">
                                                        <i class="bi bi-diagram-3"></i> Sectors
                                                    </button>
                                                </div>

                                                {{-- Sectors --}}
                                                <div class="collapse mt-2 ms-4" id="sectors-{{ $kebele->id }}">
                                                    @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 3)
                                                        <button class="btn btn-sm btn-info mb-2" 
                                                                type="button" {{-- IMPORTANT: Add type="button" --}}
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#addSectorModal{{ $kebele->id }}">
                                                            <i class="bi bi-plus-circle"></i> Add Sector
                                                        </button>
                                                    @endif

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
                                            @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 3)
                                                <div class="modal fade" id="addSectorModal{{ $kebele->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <form method="POST" action="{{ route('sectors.store') }}" class="modal-content">
                                                            @csrf
                                                            <div class="modal-header bg-info text-white">
                                                                <h5 class="modal-title">Add Sector to {{ $kebele->name }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" name="kebele_id" value="{{ $kebele->id }}">
                                                                <input type="text" name="name" class="form-control" placeholder="Sector Name" required>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button class="btn btn-info" type="submit">Save</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endif
                                        @empty
                                            <li class="list-group-item text-muted ps-4">No Kebeles</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>

                            {{-- Add Kebele Modal --}}
                            @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                                <div class="modal fade" id="addKebeleModal{{ $woreda->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form method="POST" action="{{ route('kebeles.store') }}" class="modal-content">
                                            @csrf
                                            <div class="modal-header bg-secondary text-white">
                                                <h5 class="modal-title">Add Kebele to {{ $woreda->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="woreda_id" value="{{ $woreda->id }}">
                                                <input type="text" name="name" class="form-control" placeholder="Kebele Name" required>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button class="btn btn-secondary" type="submit">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <p class="text-muted">No Woredas in this zone.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Add Woreda Modal --}}
            @if(Auth::user()->role_id == 1)
                <div class="modal fade" id="addWoredaModal{{ $zone->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('woredas.store') }}" class="modal-content">
                            @csrf
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title">Add Woreda to {{ $zone->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="zone_id" value="{{ $zone->id }}">
                                <input type="text" name="name" class="form-control" placeholder="Woreda Name" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button class="btn btn-success" type="submit">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @empty
            <p class="text-muted">No data available.</p>
        @endforelse
    </div>

    {{-- Add Zone Modal --}}
    @if(Auth::user()->role_id == 1)
        <div class="modal fade" id="addZoneModal" tabindex="-1" aria-labelledby="addZoneModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('zones.store') }}" class="modal-content">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="addZoneModalLabel">Add Zone</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="name" class="form-control" placeholder="Zone Name" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Styles --}}
    <style>
        body {
            font-family: 'Segoe UI', Roboto, sans-serif;
            background-color: #f8f9fa;
        }
        .list-group-item:hover {
            background-color: #e9ecef;
        }
        /* Fix for modal backdrop */
        .modal-backdrop {
            z-index: 1040;
        }
        .modal {
            z-index: 1050;
        }
    </style>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    {{-- Bootstrap JS (MOST IMPORTANT - You were missing this!) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- Optional: Debug script if buttons still don't work --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded - Bootstrap should work now');
            
            // Check if Bootstrap is loaded
            if (typeof bootstrap === 'undefined') {
                console.error('Bootstrap not loaded! Check script tag.');
            } else {
                console.log('Bootstrap loaded successfully');
            }
            
            // Add click event listeners as fallback
            document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
                button.addEventListener('click', function(e) {
                    console.log('Modal button clicked:', this.getAttribute('data-bs-target'));
                });
            });
            
            document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(button => {
                button.addEventListener('click', function(e) {
                    console.log('Collapse button clicked:', this.getAttribute('data-bs-target'));
                });
            });
        });
    </script>
</x-layout>