<x-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        .table thead th {
            white-space: nowrap;
            position: relative;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }
        .status-refunded {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-canceled {
            background-color: #f8d7da;
            color: #721c24;
        }
        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .search-box {
            position: relative;
        }
        .search-box i {
            position: absolute;
            left: 15px;
            top: 12px;
            color: #6c757d;
        }
        .search-box input {
            padding-left: 40px;
        }
        .dashboard-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .card-icon {
            font-size: 2rem;
            opacity: 0.7;
        }
        .progress-thin {
            height: 6px;
        }
        .crop-card {
            border-left: 4px solid;
        }
        .crop-card.wheat {
            border-left-color: #f9a825;
        }
        .crop-card.corn {
            border-left-color: #7cb342;
        }
        .crop-card.barley {
            border-left-color: #5d4037;
        }
        .crop-card.teff {
            border-left-color: #4e342e;
        }
        .chart-container {
            position: relative;
            height: 300px;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <main class="col-md-12 px-md-4">
                <!-- Top Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="#">Kebele Agricultural Dashboard</a>
                        <div class="d-flex ms-auto align-items-center">
                            
                        </div>
                    </div>
                </nav>

                <!-- Dashboard Cards -->
                <div class="row mb-4">
                    <!-- Total Land Area Card -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 grid-margin stretch-card">
                        <div class="card dashboard-card bg-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">Total Land Area</h6>
                                        <h3 class="mb-0">1,245 <small class="text-muted">ha</small></h3>
                                        <small class="text-success">+12.5% from last year</small>
                                    </div>
                                    <div class="card-icon text-primary">
                                        <i class="bi bi-map"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <small>Cultivated</small>
                                        <small>85%</small>
                                    </div>
                                    <div class="progress progress-thin">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 85%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Production Card -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 grid-margin stretch-card">
                        <div class="card dashboard-card bg-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">Total Production</h6>
                                        <h3 class="mb-0">5,678 <small class="text-muted">Qt</small></h3>
                                        <small class="text-success">+8.3% from last year</small>
                                    </div>
                                    <div class="card-icon text-success">
                                        <i class="bi bi-basket2"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <small>Target</small>
                                        <small>92%</small>
                                    </div>
                                    <div class="progress progress-thin">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 92%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Average Productivity Card -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 grid-margin stretch-card">
                        <div class="card dashboard-card bg-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">Avg Productivity</h6>
                                        <h3 class="mb-0">4.56 <small class="text-muted">Qt/ha</small></h3>
                                        <small class="text-success">+5.2% from last year</small>
                                    </div>
                                    <div class="card-icon text-warning">
                                        <i class="bi bi-graph-up"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <small>Regional Avg</small>
                                        <small>4.12 Qt/ha</small>
                                    </div>
                                    <div class="progress progress-thin">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 75%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Farmers Registered Card -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 grid-margin stretch-card">
                        <div class="card dashboard-card bg-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">Farmers Registered</h6>
                                        <h3 class="mb-0">1,245</h3>
                                        <small class="text-success">+15 new this month</small>
                                    </div>
                                    <div class="card-icon text-info">
                                        <i class="bi bi-people"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <small>Active</small>
                                        <small>87%</small>
                                    </div>
                                    <div class="progress progress-thin">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 87%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Crop-Specific Cards -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h4 class="mb-3">Crop Production Summary</h4>
                    </div>

                    <!-- Wheat Card -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 grid-margin stretch-card">
                        <div class="card dashboard-card crop-card wheat">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">Wheat</h6>
                                        <h3 class="mb-0">2,450 <small class="text-muted">Qt</small></h3>
                                        <small class="text-success">+7.8% from last year</small>
                                    </div>
                                    <div class="card-icon text-warning">
                                        <i class="bi bi-wheat"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <small>Land Area</small>
                                            <p class="mb-0">450 ha</p>
                                        </div>
                                        <div class="col-6">
                                            <small>Productivity</small>
                                            <p class="mb-0">5.44 Qt/ha</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Teff Card -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 grid-margin stretch-card">
                        <div class="card dashboard-card crop-card teff">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">Teff</h6>
                                        <h3 class="mb-0">1,780 <small class="text-muted">Qt</small></h3>
                                        <small class="text-success">+12.3% from last year</small>
                                    </div>
                                    <div class="card-icon text-brown">
                                        <i class="bi bi-egg-fill"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <small>Land Area</small>
                                            <p class="mb-0">320 ha</p>
                                        </div>
                                        <div class="col-6">
                                            <small>Productivity</small>
                                            <p class="mb-0">5.56 Qt/ha</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Barley Card -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 grid-margin stretch-card">
                        <div class="card dashboard-card crop-card barley">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">Barley</h6>
                                        <h3 class="mb-0">980 <small class="text-muted">Qt</small></h3>
                                        <small class="text-danger">-2.1% from last year</small>
                                    </div>
                                    <div class="card-icon text-secondary">
                                        <i class="bi bi-cup-straw"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <small>Land Area</small>
                                            <p class="mb-0">180 ha</p>
                                        </div>
                                        <div class="col-6">
                                            <small>Productivity</small>
                                            <p class="mb-0">5.44 Qt/ha</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Corn Card -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 grid-margin stretch-card">
                        <div class="card dashboard-card crop-card corn">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">Corn</h6>
                                        <h3 class="mb-0">1,250 <small class="text-muted">Qt</small></h3>
                                        <small class="text-success">+15.6% from last year</small>
                                    </div>
                                    <div class="card-icon text-success">
                                        <i class="bi bi-egg-fried"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <small>Land Area</small>
                                            <p class="mb-0">230 ha</p>
                                        </div>
                                        <div class="col-6">
                                            <small>Productivity</small>
                                            <p class="mb-0">5.43 Qt/ha</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="row mb-4">
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Production by Crop Type</h4>
                                <div class="chart-container">
                                    <canvas id="cropProductionChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Yearly Comparison</h4>
                                <div class="chart-container">
                                    <canvas id="yearlyComparisonChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Report Section -->
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h4 class="card-title">Kebele Crop Production Details</h4>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-download"></i> Export</button>
                                        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer"></i> Print</button>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                        <tr>
                                            <th>Crop Type</th>
                                            <th>Crop Name</th>
                                            <th>Land Area (ha)</th>
                                            <th>Productivity (Qt/ha)</th>
                                            <th>Production (Qt)</th>
                                            <th>Status</th>
                                            <th>% of Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Cereal</td>
                                            <td>Wheat</td>
                                            <td>450.00</td>
                                            <td>5.44</td>
                                            <td>2,450.00</td>
                                            <td><span class="badge bg-success">Above Target</span></td>
                                            <td>
                                                <div class="progress progress-thin">
                                                    <div class="progress-bar bg-success" style="width: 35%"></div>
                                                </div>
                                                <small>35%</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Cereal</td>
                                            <td>Teff</td>
                                            <td>320.00</td>
                                            <td>5.56</td>
                                            <td>1,780.00</td>
                                            <td><span class="badge bg-success">Above Target</span></td>
                                            <td>
                                                <div class="progress progress-thin">
                                                    <div class="progress-bar bg-success" style="width: 25%"></div>
                                                </div>
                                                <small>25%</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Cereal</td>
                                            <td>Barley</td>
                                            <td>180.00</td>
                                            <td>5.44</td>
                                            <td>980.00</td>
                                            <td><span class="badge bg-warning">Below Target</span></td>
                                            <td>
                                                <div class="progress progress-thin">
                                                    <div class="progress-bar bg-warning" style="width: 14%"></div>
                                                </div>
                                                <small>14%</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Cereal</td>
                                            <td>Corn</td>
                                            <td>230.00</td>
                                            <td>5.43</td>
                                            <td>1,250.00</td>
                                            <td><span class="badge bg-success">Above Target</span></td>
                                            <td>
                                                <div class="progress progress-thin">
                                                    <div class="progress-bar bg-success" style="width: 18%"></div>
                                                </div>
                                                <small>18%</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Pulse</td>
                                            <td>Beans</td>
                                            <td>65.00</td>
                                            <td>4.23</td>
                                            <td>275.00</td>
                                            <td><span class="badge bg-info">On Target</span></td>
                                            <td>
                                                <div class="progress progress-thin">
                                                    <div class="progress-bar bg-info" style="width: 4%"></div>
                                                </div>
                                                <small>4%</small>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <div>
                                        <p class="mb-0">Showing 1 to 5 of 15 entries</p>
                                    </div>
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination pagination-sm">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                                            </li>
                                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">Next</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Crop Production Chart
        const cropCtx = document.getElementById('cropProductionChart').getContext('2d');
        new Chart(cropCtx, {
            type: 'doughnut',
            data: {
                labels: ['Wheat', 'Teff', 'Barley', 'Corn', 'Beans', 'Other'],
                datasets: [{
                    data: [2450, 1780, 980, 1250, 275, 350],
                    backgroundColor: [
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(201, 203, 207, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 159, 64, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(201, 203, 207, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value.toLocaleString()} Qt (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Yearly Comparison Chart
        const yearlyCtx = document.getElementById('yearlyComparisonChart').getContext('2d');
        new Chart(yearlyCtx, {
            type: 'bar',
            data: {
                labels: ['Wheat', 'Teff', 'Barley', 'Corn', 'Beans'],
                datasets: [
                    {
                        label: '2022',
                        data: [2200, 1580, 1000, 1080, 250],
                        backgroundColor: 'rgba(201, 203, 207, 0.7)',
                        borderColor: 'rgba(201, 203, 207, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '2023',
                        data: [2450, 1780, 980, 1250, 275],
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Production (Qt)'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw.toLocaleString()} Qt`;
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-layout>
