<x-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2c5aa0;
            --secondary-color: #4caf50;
            --accent-color: #ff9800;
            --warning-color: #f44336;
            --info-color: #2196f3;
            --light-bg: #f8f9fa;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
            --hover-shadow: 0 10px 15px rgba(0, 0, 0, 0.1), 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-container {
            background: transparent;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }

        .dashboard-card {
            border: none;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
            position: relative;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .icon-primary { background: rgba(44, 90, 160, 0.1); color: var(--primary-color); }
        .icon-success { background: rgba(76, 175, 80, 0.1); color: var(--secondary-color); }
        .icon-warning { background: rgba(255, 152, 0, 0.1); color: var(--accent-color); }
        .icon-info { background: rgba(33, 150, 243, 0.1); color: var(--info-color); }

        .growth-indicator {
            font-size: 0.85rem;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 12px;
        }

        .growth-positive { background: #d4edda; color: #155724; }
        .growth-negative { background: #f8d7da; color: #721c24; }
        .growth-neutral { background: #e2e3e5; color: #383d41; }

        .crop-card {
            border-left: 4px solid;
            transition: all 0.3s ease;
        }

        .crop-card:hover {
            transform: translateX(5px);
        }

        .crop-card.wheat { border-left-color: #f9a825; }
        .crop-card.teff { border-left-color: #8d6e63; }
        .crop-card.barley { border-left-color: #5d4037; }
        .crop-card.corn { border-left-color: #7cb342; }
        .crop-card.beans { border-left-color: #d32f2f; }
        .crop-card.other { border-left-color: #757575; }

        .chart-container {
            position: relative;
            height: 300px;
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: var(--card-shadow);
        }

        .progress-thin {
            height: 6px;
            border-radius: 3px;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .bg-target-exceeded { background: #d4edda; color: #155724; }
        .bg-target-met { background: #d1ecf1; color: #0c5460; }
        .bg-target-below { background: #fff3cd; color: #856404; }

        .table-hover tbody tr:hover {
            background-color: rgba(44, 90, 160, 0.05);
            transform: scale(1.01);
            transition: all 0.2s ease;
        }

        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--warning-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .seasonal-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .season-spring { background: #c8e6c9; color: #2e7d32; }
        .season-summer { background: #ffecb3; color: #ff8f00; }
        .season-autumn { background: #ffccbc; color: #d84315; }
        .season-winter { background: #bbdefb; color: #1565c0; }

        .kpi-number {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .kpi-number {
                font-size: 1.5rem;
            }
            
            .chart-container {
                height: 250px;
            }
            
            .empty-state {
                padding: 2rem 1rem;
            }
        }

        .floating-action {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>

    <div class="container-fluid dashboard-container py-4">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white rounded-3 shadow-sm mb-4">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="#">
                    <i class="fas fa-tractor me-2"></i>
                    <span>Kebele Agricultural Dashboard</span>
                </a>
                <div class="d-flex ms-auto align-items-center">
                    <div class="me-3">
                        <small class="text-muted">Welcome,</small>
                        <div class="fw-bold text-dark">{{ auth()->user()->name }}</div>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-outline-primary btn-sm position-relative">
                            <i class="fas fa-bell"></i>
                            @if($pendingCropPlans > 0)
                                <span class="notification-badge">{{ $pendingCropPlans }}</span>
                            @endif
                        </button>
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-cog"></i>
                        </button>
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-user"></i>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Key Performance Indicators -->
        <div class="row mb-4">
            <!-- Total Land Area Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-muted mb-2">Total Land Area</h6>
                                <div class="kpi-number">{{ number_format($totalArea, 2) }} <small class="text-muted">ha</small></div>
                                <div class="mt-2">
                                    @if($areaGrowth > 0)
                                        <span class="growth-indicator growth-positive">
                                            <i class="fas fa-arrow-up"></i> {{ number_format($areaGrowth, 1) }}%
                                        </span>
                                        <small class="text-muted ms-1">vs last year</small>
                                    @elseif($areaGrowth < 0)
                                        <span class="growth-indicator growth-negative">
                                            <i class="fas fa-arrow-down"></i> {{ number_format(abs($areaGrowth), 1) }}%
                                        </span>
                                        <small class="text-muted ms-1">vs last year</small>
                                    @else
                                        <span class="growth-indicator growth-neutral">
                                            <i class="fas fa-minus"></i> 0%
                                        </span>
                                        <small class="text-muted ms-1">vs last year</small>
                                    @endif
                                </div>
                            </div>
                            <div class="card-icon icon-primary">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Cultivated Area</small>
                                <small class="text-muted">{{ number_format($totalArea, 1) }} ha</small>
                            </div>
                            <div class="progress progress-thin">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Production Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-muted mb-2">Total Production</h6>
                                <div class="kpi-number">{{ number_format($totalYield, 2) }} <small class="text-muted">Qt</small></div>
                                <div class="mt-2">
                                    @if($yieldGrowth > 0)
                                        <span class="growth-indicator growth-positive">
                                            <i class="fas fa-arrow-up"></i> {{ number_format($yieldGrowth, 1) }}%
                                        </span>
                                        <small class="text-muted ms-1">vs last year</small>
                                    @elseif($yieldGrowth < 0)
                                        <span class="growth-indicator growth-negative">
                                            <i class="fas fa-arrow-down"></i> {{ number_format(abs($yieldGrowth), 1) }}%
                                        </span>
                                        <small class="text-muted ms-1">vs last year</small>
                                    @else
                                        <span class="growth-indicator growth-neutral">
                                            <i class="fas fa-minus"></i> 0%
                                        </span>
                                        <small class="text-muted ms-1">vs last year</small>
                                    @endif
                                </div>
                            </div>
                            <div class="card-icon icon-success">
                                <i class="fas fa-wheat-alt"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Annual Target</small>
                                <small class="text-muted">{{ number_format($targetYield, 0) }} Qt</small>
                            </div>
                            <div class="progress progress-thin">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: {{ $progressPercentage }}%"></div>
                            </div>
                            <small class="text-muted mt-1">{{ number_format($progressPercentage, 1) }}% of target</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Average Productivity Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-muted mb-2">Avg Productivity</h6>
                                <div class="kpi-number">{{ number_format($avgProductivity, 2) }} <small class="text-muted">Qt/ha</small></div>
                                <div class="mt-2">
                                    @if($productivityGrowth > 0)
                                        <span class="growth-indicator growth-positive">
                                            <i class="fas fa-arrow-up"></i> {{ number_format($productivityGrowth, 1) }}%
                                        </span>
                                        <small class="text-muted ms-1">vs last year</small>
                                    @elseif($productivityGrowth < 0)
                                        <span class="growth-indicator growth-negative">
                                            <i class="fas fa-arrow-down"></i> {{ number_format(abs($productivityGrowth), 1) }}%
                                        </span>
                                        <small class="text-muted ms-1">vs last year</small>
                                    @else
                                        <span class="growth-indicator growth-neutral">
                                            <i class="fas fa-minus"></i> 0%
                                        </span>
                                        <small class="text-muted ms-1">vs last year</small>
                                    @endif
                                </div>
                            </div>
                            <div class="card-icon icon-warning">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Regional Avg</small>
                                <small class="text-muted">4.50 Qt/ha</small>
                            </div>
                            <div class="progress progress-thin">
                                <div class="progress-bar bg-warning" role="progressbar" 
                                     style="width: {{ $regionalProgress }}%"></div>
                            </div>
                            <small class="text-muted mt-1">
                                @if($avgProductivity > 4.5)
                                    {{ number_format((($avgProductivity - 4.5) / 4.5) * 100, 1) }}% above regional avg
                                @elseif($avgProductivity > 0)
                                    {{ number_format(abs((($avgProductivity - 4.5) / 4.5) * 100), 1) }}% below regional avg
                                @else
                                    No data available
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Farmers Registered Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-muted mb-2">Farmers Registered</h6>
                                <div class="kpi-number">{{ number_format($totalFarmers) }}</div>
                                <div class="mt-2">
                                    <span class="growth-indicator growth-positive">
                                        <i class="fas fa-users"></i> Active
                                    </span>
                                </div>
                            </div>
                            <div class="card-icon icon-info">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Participation Rate</small>
                                <small class="text-muted">{{ number_format(min(100, ($totalFarmers / max(1, $totalFarmers)) * 100)) }}%</small>
                            </div>
                            <div class="progress progress-thin">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Crop-Specific Performance -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0 text-dark">
                        <i class="fas fa-seedling me-2 text-success"></i>
                        Crop Performance Overview ({{ $currentYear }})
                    </h4>
                    <div class="text-muted">
                        <small>Latest Available Data</small>
                    </div>
                </div>
            </div>

            @if(count($cropData) > 0)
                @foreach($cropData as $cropName => $data)
                <div class="col-xl-2 col-lg-4 col-md-6 mb-3">
                    <div class="card dashboard-card crop-card {{ strtolower($cropName) }} h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0">{{ $cropName }}</h6>
                                <span class="badge bg-light text-dark">{{ $data['type'] }}</span>
                            </div>
                            <div class="kpi-number" style="font-size: 1.5rem;">{{ number_format($data['yield'], 0) }} <small class="text-muted">Qt</small></div>
                            
                            <div class="mt-3">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Area</small>
                                        <strong>{{ number_format($data['area'], 1) }} ha</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Productivity</small>
                                        <strong>{{ number_format($data['productivity'], 2) }} Qt/ha</strong>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Contribution</small>
                                    <small class="fw-bold">{{ number_format($data['percentage'], 1) }}%</small>
                                </div>
                                <div class="progress progress-thin mt-1">
                                    <div class="progress-bar" 
                                         style="width: {{ $data['percentage'] }}%; 
                                                background-color: {{ $loop->index % 2 == 0 ? 'var(--primary-color)' : 'var(--secondary-color)' }};">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="card dashboard-card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-seedling fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Crop Data Available</h5>
                            <p class="text-muted mb-0">No crop plans have been submitted for this kebele.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Charts Section -->
        <div class="row mb-4">
            <div class="col-lg-6 mb-4">
                <div class="chart-container">
                    <h5 class="mb-3">Production Distribution ({{ $currentYear }})</h5>
                    @if(count($cropData) > 0 && array_sum(array_column($cropData, 'yield')) > 0)
                        <canvas id="cropProductionChart"></canvas>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-chart-pie"></i>
                            <p>No production data available for chart</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="chart-container">
                    <h5 class="mb-3">Yearly Performance Trend</h5>
                    @if(count($yearlyComparison) > 0 && array_sum(array_column($yearlyComparison, 'yield')) > 0)
                        <canvas id="yearlyComparisonChart"></canvas>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-chart-line"></i>
                            <p>No yearly data available for chart</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Seasonal Distribution -->
        @if(count($seasonalData) > 0 && array_sum(array_column($seasonalData, 'area')) > 0)
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="chart-container">
                    <h5 class="mb-3">Seasonal Distribution ({{ $currentYear }})</h5>
                    <canvas id="seasonalChart"></canvas>
                </div>
            </div>
        </div>
        @endif

        <!-- Detailed Report -->
        <div class="row">
            <div class="col-md-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-table me-2 text-primary"></i>
                                Detailed Crop Production Report ({{ $currentYear }})
                            </h4>
                            <div class="btn-group">
                                <button class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-download me-1"></i> Export
                                </button>
                                <button class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-print me-1"></i> Print
                                </button>
                            </div>
                        </div>

                        @if(count($cropData) > 0)
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
                                    @foreach($cropData as $cropName => $data)
                                    <tr>
                                        <td class="fw-semibold">{{ $data['type'] }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-seedling me-2 text-success"></i>
                                                {{ $cropName }}
                                            </div>
                                        </td>
                                        <td>{{ number_format($data['area'], 2) }}</td>
                                        <td>
                                            <span class="fw-bold {{ $data['productivity'] > 4.5 ? 'text-success' : ($data['productivity'] > 0 ? 'text-warning' : 'text-muted') }}">
                                                {{ number_format($data['productivity'], 2) }}
                                            </span>
                                        </td>
                                        <td class="fw-bold">{{ number_format($data['yield'], 2) }}</td>
                                        <td>
                                            @if($data['productivity'] > 5.0)
                                                <span class="status-badge bg-target-exceeded">Exceeding Target</span>
                                            @elseif($data['productivity'] >= 4.5)
                                                <span class="status-badge bg-target-met">On Target</span>
                                            @elseif($data['productivity'] > 0)
                                                <span class="status-badge bg-target-below">Below Target</span>
                                            @else
                                                <span class="status-badge bg-secondary">No Data</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress progress-thin flex-grow-1 me-2">
                                                    <div class="progress-bar" 
                                                         style="width: {{ $data['percentage'] }}%;
                                                                background-color: {{ $loop->index % 2 == 0 ? 'var(--primary-color)' : 'var(--secondary-color)' }};">
                                                    </div>
                                                </div>
                                                <small class="fw-bold">{{ number_format($data['percentage'], 1) }}%</small>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="2" class="fw-bold">Total</td>
                                        <td class="fw-bold">{{ number_format(array_sum(array_column($cropData, 'area')), 2) }}</td>
                                        <td class="fw-bold">{{ number_format($avgProductivity, 2) }}</td>
                                        <td class="fw-bold">{{ number_format(array_sum(array_column($cropData, 'yield')), 2) }}</td>
                                        <td></td>
                                        <td class="fw-bold">100%</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @else
                        <div class="empty-state">
                            <i class="fas fa-table"></i>
                            <h5>No Production Data Available</h5>
                            <p class="mb-0">There are no crop production records for this kebele.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <div class="floating-action">
        <button class="btn btn-primary btn-lg rounded-circle shadow" data-bs-toggle="tooltip" title="Quick Actions">
            <i class="fas fa-plus"></i>
        </button>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Only initialize charts if data exists
            @if(count($cropData) > 0 && array_sum(array_column($cropData, 'yield')) > 0)
            // Crop Production Chart
            const cropCtx = document.getElementById('cropProductionChart');
            if (cropCtx) {
                const cropLabels = {!! json_encode(array_keys($cropData)) !!};
                const cropYields = {!! json_encode(array_column($cropData, 'yield')) !!};
                
                new Chart(cropCtx, {
                    type: 'doughnut',
                    data: {
                        labels: cropLabels,
                        datasets: [{
                            data: cropYields,
                            backgroundColor: [
                                'rgba(255, 159, 64, 0.8)',
                                'rgba(75, 192, 192, 0.8)',
                                'rgba(153, 102, 255, 0.8)',
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(255, 99, 132, 0.8)',
                                'rgba(201, 203, 207, 0.8)',
                                'rgba(255, 205, 86, 0.8)',
                                'rgba(76, 175, 80, 0.8)'
                            ],
                            borderColor: [
                                'rgba(255, 159, 64, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(201, 203, 207, 1)',
                                'rgba(255, 205, 86, 1)',
                                'rgba(76, 175, 80, 1)'
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return `${label}: ${value.toLocaleString()} Qt (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        cutout: '50%'
                    }
                });
            }
            @endif

            @if(count($yearlyComparison) > 0 && array_sum(array_column($yearlyComparison, 'yield')) > 0)
            // Yearly Comparison Chart
            const yearlyCtx = document.getElementById('yearlyComparisonChart');
            if (yearlyCtx) {
                const yearlyData = {!! json_encode($yearlyComparison) !!};
                const years = Object.keys(yearlyData);
                const yields = Object.values(yearlyData).map(item => item.yield);
                const areas = Object.values(yearlyData).map(item => item.area);
                
                new Chart(yearlyCtx, {
                    type: 'line',
                    data: {
                        labels: years,
                        datasets: [
                            {
                                label: 'Total Production (Qt)',
                                data: yields,
                                borderColor: 'rgba(54, 162, 235, 1)',
                                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4
                            },
                            {
                                label: 'Total Area (ha)',
                                data: areas,
                                borderColor: 'rgba(76, 175, 80, 1)',
                                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4
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
                                    text: 'Values'
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        }
                    }
                });
            }
            @endif

            @if(count($seasonalData) > 0 && array_sum(array_column($seasonalData, 'area')) > 0)
            // Seasonal Chart
            const seasonalCtx = document.getElementById('seasonalChart');
            if (seasonalCtx) {
                const seasonalData = {!! json_encode($seasonalData) !!};
                const seasons = Object.keys(seasonalData).map(season => season.charAt(0).toUpperCase() + season.slice(1));
                const seasonalAreas = Object.values(seasonalData).map(item => item.area);
                const seasonalYields = Object.values(seasonalData).map(item => item.yield);
                
                new Chart(seasonalCtx, {
                    type: 'bar',
                    data: {
                        labels: seasons,
                        datasets: [
                            {
                                label: 'Area (ha)',
                                data: seasonalAreas,
                                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Production (Qt)',
                                data: seasonalYields,
                                backgroundColor: 'rgba(76, 175, 80, 0.8)',
                                borderColor: 'rgba(76, 175, 80, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
            @endif
        });
    </script>
</x-layout>