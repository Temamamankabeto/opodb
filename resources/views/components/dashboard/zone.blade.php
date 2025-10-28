<x-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <main class="col-md-12 px-md-4">

                <!-- Top Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="#">Zone Dashboard</a>
                        <div class="d-flex ms-auto align-items-center">
                            <button class="btn btn-outline-secondary me-2"><i class="bi bi-brightness-high"></i></button>
                            <button class="btn btn-outline-secondary me-2"><i class="bi bi-bell"></i></button>
                            <button class="btn btn-outline-secondary"><i class="bi bi-person-circle"></i></button>
                        </div>
                    </div>
                </nav>

                <!-- Dashboard Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title">Total Reports</h5>
                                    <p class="card-text fs-4">{{ $totalReports }}</p>
                                </div>
                                <div class="card-icon fs-2"><i class="bi bi-file-earmark-text"></i></div>
                            </div>
                        </div>
                    </div>
                    <!-- Add more cards here if needed -->
                </div>

                <!-- Recent Reports Table -->
                <div class="card mb-4">
                    <div class="card-header">Recent Crop Reports</div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Crop Type</th>
                                <th>Crop</th>
                                <th>Date</th>
{{--                                <th>Status</th>--}}
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($recentReports as $report)
                                <tr>
                                    <td>{{ $report->cropType->name ?? 'Unknown Type' }}</td>
                                    <td>{{ $report->crop->name ?? 'Unknown Crop' }}</td>
{{--                                    <td>{{ $report->created_at->format('Y-m-d') }}</td>--}}
                                    <td>
                                        @php
                                            $badge = match($report->status) {
                                                'approved' => 'success',
                                                'pending' => 'warning',
                                                'rejected' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badge }}">{{ ucfirst($report->status) }}</span>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i></a>
                                        <a href="#" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach

                            @if ($recentReports->isEmpty())
                                <tr><td colspan="5" class="text-center text-muted">No crop reports found.</td></tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Example Chart Area (You can integrate Chart.js here) -->
                <div class="card mb-4">
                    <div class="card-header">Zone Production Summary</div>
                    <div class="card-body">
                        <canvas id="productionChart" height="100"></canvas>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- Chart.js script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('productionChart').getContext('2d');
        const productionChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($zoneLabels),
                datasets: [{
                    label: 'Production',
                    data: @json($zoneProductionData),
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</x-layout>
