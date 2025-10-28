<x-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <main class="col-md-12 px-md-4">
                <!-- Top Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="#">Dashboard Naannoo</a>
                        <div class="d-flex ms-auto align-items-center">
                            <button class="btn btn-outline-secondary me-2"><i class="bi bi-brightness-high"></i></button>
                            <button class="btn btn-outline-secondary me-2"><i class="bi bi-bell"></i></button>
                            <button class="btn btn-outline-secondary"><i class="bi bi-person-circle"></i></button>
                        </div>
                    </div>
                </nav>

                <!-- Regional Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-info mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Godinoota</h5>
                                <p class="card-text fs-4">{{ $zones->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Aanoota</h5>
                                <p class="card-text fs-4">{{ $totalWoredas }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Gandootaa</h5>
                                <p class="card-text fs-4">{{ $totalKebeles }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Bal'ina Lafa waliigalaa (ha)</h5>
                                <p class="card-text fs-4">{{ number_format($totallandarea, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Crop Production Summary Table -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Cuunfaa Oomishaa Gosa Godinaa</h5>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                            <tr>
                                <th>Godina</th>
                                <th>Aanaa</th>
                                <th>Gosa Midhaani</th>
                                <th>Midhaan</th>
                                <th>Bal'ina Lafa (ha)</th>
                                <th>Oomishaa (Qt)</th>
                                <th>Oomishtummaa (Qt/ha)</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reportData as $zoneId => $woredaGroup)
                                @php $zone = $zones->find($zoneId); @endphp
                                @foreach($woredaGroup as $woredaId => $cropTypes)
                                    @foreach($cropTypes as $cropTypeId => $crops)
                                        @foreach($crops as $cropId => $records)
                                            @php
                                                $first = $records->first();
                                                $land = $records->sum('land_hectares');
                                                $production = $records->sum('production');
                                                $productivity = $land > 0 ? $production / $land : 0;
                                            @endphp
                                            <tr>
                                                <td>{{ $zone?->name }}</td>
                                                <td>{{ $first?->woreda?->name }}</td>
                                                <td>{{ $first?->cropType?->name }}</td>
                                                <td>{{ $first?->crop?->name }}</td>
                                                <td>{{ number_format($land, 2) }}</td>
                                                <td>{{ number_format($production, 2) }}</td>
                                                <td>{{ number_format($productivity, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Chart.js CDN -->
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">Land Area by Zone</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="landChart"></canvas>
                        </div>
                    </div>
                    <!-- Chart.js already included -->

                    <div class="row">
                        <!-- Productivity Chart -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-success text-white">Production by Zone (Qt)</div>
                                <div class="card-body">
                                    <canvas id="productionChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Woredas and Kebeles Chart -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-secondary text-white">Woredas vs Kebeles per Zone</div>
                                <div class="card-body">
                                    <canvas id="structureChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>


                    <script>
                        const ctx = document.getElementById('landChart').getContext('2d');
                        const landChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: @json($zoneLabels),
                                datasets: [{
                                    label: 'Total Land Area (ha)',
                                    data: @json($zoneLandData),
                                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                                    borderColor: 'rgba(40, 167, 69, 1)',
                                    borderWidth: 1,
                                    barThickness: 40
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Land Area (hectares)'
                                        }
                                    }
                                }
                            }
                        });
                    </script>
                </div>
            </main>
        </div>
    </div>
</x-layout>
