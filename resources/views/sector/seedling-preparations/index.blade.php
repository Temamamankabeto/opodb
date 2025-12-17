<x-layout>
    <div class="content-header p-3">

        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 align-items-center">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-success">
                            <i class="fas fa-tree me-2"></i> Seedling Preparations (Qophii fi Dhaabbii)
                        </h1>
                        <p class="text-muted mt-1 mb-0">Track and manage seedling preparation and planting activities</p>
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="{{ route('sector.seedling-preparations.create') }}" class="btn btn-success">
                            <i class="fas fa-plus-circle me-1"></i> Add New Preparation
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- Success Alert -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check me-1"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($preparations->isEmpty())
                    <!-- Empty State -->
                    <div class="card card-outline card-warning text-center py-5">
                        <div class="card-body">
                            <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted mb-2">No seedling preparations found</h4>
                            <p class="text-muted mb-3">Get started by creating your first seedling preparation</p>
                            <a href="{{ route('sector.seedling-preparations.create') }}" class="btn btn-success">
                                <i class="fas fa-plus-circle me-1"></i> Create Preparation
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Preparations Table -->
                    <div class="card card-outline card-primary">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title"><i class="fas fa-table me-1"></i> Seedling Preparations Overview</h3>
                            <div>
                                <span class="badge bg-info me-2">{{ $preparations->count() }} found</span>
                                <button class="btn btn-tool" onclick="scrollToTop('seedlingTable')">
                                    <i class="fas fa-arrow-up"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0" style="max-height: 70vh;">
                            <table class="table table-hover text-nowrap align-middle" id="seedlingTable">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th class="text-center" style="width: 10%">Year (EC)</th>
                                        <th style="width: 15%">Seedling Type</th>
                                        <th style="width: 20%">Seedlings</th>
                                        <th class="text-center" style="width: 20%">Preparation Data (Qophii)</th>
                                        <th class="text-center" style="width: 20%">Planting Data (Dhaabbii)</th>
                                        <th class="text-center" style="width: 15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $groupedPreparations = [];
                                        foreach ($preparations as $preparation) {
                                            $prepData = $preparation->preparation_data ?? [];
                                            $plantData = $preparation->planting_data ?? [];
                                            $seedlingType = $prepData['seedling_type']['name'] ?? ($plantData['seedling_type']['name'] ?? 'N/A');
                                            $year = $preparation->year;

                                            if (!isset($groupedPreparations[$year])) {
                                                $groupedPreparations[$year] = [];
                                            }
                                            if (!isset($groupedPreparations[$year][$seedlingType])) {
                                                $groupedPreparations[$year][$seedlingType] = [];
                                            }
                                            $groupedPreparations[$year][$seedlingType][] = $preparation;
                                        }
                                    @endphp

                                    @foreach($groupedPreparations as $year => $types)
                                        @php
                                            $yearRowspan = array_sum(array_map('count', $types));
                                            $yearDisplayed = false;
                                        @endphp
                                        @foreach($types as $seedlingType => $typePreparations)
                                            @php
                                                $typeRowspan = count($typePreparations);
                                                $typeDisplayed = false;
                                            @endphp
                                            @foreach($typePreparations as $preparation)
                                                @php
                                                    $prepSeedlings = $preparation->preparation_data['selected_seedlings'] ?? [];
                                                    $plantSeedlings = $preparation->planting_data['selected_seedlings'] ?? [];
                                                @endphp
                                                <tr class="hover-row" data-year="{{ $year }}" data-type="{{ $seedlingType }}">
                                                    <!-- Year -->
                                                    @if (!$yearDisplayed)
                                                        <td class="align-middle text-center fw-bold bg-light" rowspan="{{ $yearRowspan }}">
                                                            {{ $year }}
                                                            @if($year == date('Y'))
                                                                <span class="badge bg-success ms-1">Current</span>
                                                            @endif
                                                        </td>
                                                        @php $yearDisplayed = true; @endphp
                                                    @endif

                                                    <!-- Type -->
                                                    @if (!$typeDisplayed)
                                                        <td class="align-middle bg-light" rowspan="{{ $typeRowspan }}">
                                                            {{ $seedlingType }}
                                                        </td>
                                                        @php $typeDisplayed = true; @endphp
                                                    @endif

                                                    <!-- Seedlings -->
                                                    <td>
                                                        @forelse($prepSeedlings as $s)
                                                            <div class="d-flex align-items-center mb-1">
                                                                <i class="fas fa-seedling text-success me-1"></i>
                                                                {{ $s['name'] }}
                                                            </div>
                                                        @empty
                                                            <span class="text-muted">No seedlings</span>
                                                        @endforelse
                                                    </td>

                                                    <!-- Preparation Data -->
                                                    <td>
                                                        @forelse($prepSeedlings as $s)
                                                            <div class="p-2 mb-1 bg-warning bg-opacity-10 rounded border-start border-warning">
                                                                <div class="d-flex justify-content-between">
                                                                    <span>{{ $s['name'] }}</span>
                                                                    <span class="badge bg-warning">{{ $preparation->status == 'completed' ? 'Completed' : 'In Progress' }}</span>
                                                                </div>
                                                                <small>Hanga: {{ $s['hanga_qophii'] ?? 0 }}</small><br>
                                                                <small>Participants: {{ $s['nama_hirmaate_qophii'] ?? 0 }}</small><br>
                                                                <small>Days: {{ $s['baayina_guyyaa_qophii'] ?? 0 }}</small>
                                                            </div>
                                                        @empty
                                                            <span class="text-muted">No preparation data</span>
                                                        @endforelse
                                                    </td>

                                                    <!-- Planting Data -->
                                                    <td>
                                                        @forelse($plantSeedlings as $s)
                                                            <div class="p-2 mb-1 bg-success bg-opacity-10 rounded border-start border-success">
                                                                <div class="d-flex justify-content-between">
                                                                    <span>{{ $s['name'] }}</span>
                                                                    <span class="badge bg-success">Planted</span>
                                                                </div>
                                                                <small>Hanga: {{ $s['hanga_dhaabbii'] ?? 0 }}</small><br>
                                                                <small>Participants: {{ $s['nama_hirmaate_dhaabbii'] ?? 0 }}</small><br>
                                                                <small>Days: {{ $s['baayina_guyyaa_dhaabbii'] ?? 0 }}</small>
                                                            </div>
                                                        @empty
                                                            <span class="text-muted">No planting data</span>
                                                        @endforelse
                                                    </td>

                                                    <!-- Actions -->
                                                    <td class="text-center align-middle">
                                                        <div class="btn-group-vertical w-100">
                                                            <a href="{{ route('sector.seedling-preparations.edit', $preparation->id) }}" class="btn btn-primary btn-sm mb-1">
                                                                <i class="fas fa-eye me-1"></i> View
                                                            </a>
                                                            <a href="{{ url('/sector/seedling-preparations/' . $preparation->id . '/editpreparation') }}" class="btn btn-warning btn-sm mb-1">
                                                                <i class="fas fa-edit me-1"></i> Edit
                                                            </a>
                                                            <a href="{{ route('sector.seedling-preparations.plan', $preparation->id) }}" class="btn btn-info btn-sm">
                                                                <i class="fas fa-calendar-alt me-1"></i> Schedule
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>

    <!-- JS -->
    <script>
        function scrollToTop(tableId) {
            const container = document.querySelector(`#${tableId}`).closest('.table-responsive');
            container.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>

    <style>
        .hover-row:hover {
            background-color: #f1f3f5;
        }
        .btn-group-vertical .btn {
            width: 100%;
            text-align: left;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .border-start {
            border-left-width: 3px !important;
        }
    </style>
</x-layout>
