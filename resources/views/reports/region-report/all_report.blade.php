<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regional Administrative & Land Use Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
            color: white;
            padding: 1.5rem 0;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
            border-left: 4px solid var(--secondary-color);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .stat-card h5 {
            color: var(--dark-color);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }

        .stat-card .value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .chart-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .table-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background-color: var(--light-color);
            color: var(--dark-color);
            font-weight: 600;
            padding: 0.75rem;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
        }

        .data-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #dee2e6;
        }

        .data-table tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }

        .level-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .level-region {
            background-color: #e74c3c;
            color: white;
        }

        .level-zone {
            background-color: #3498db;
            color: white;
        }

        .level-woreda {
            background-color: #2ecc71;
            color: white;
        }

        .level-kebele {
            background-color: #f39c12;
            color: white;
        }

        .sector-tag {
            display: inline-block;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
            font-size: 0.75rem;
            margin-right: 0.25rem;
            margin-bottom: 0.25rem;
            background-color: var(--light-color);
        }

        .nav-tabs .nav-link.active {
            font-weight: 600;
            border-bottom: 3px solid var(--secondary-color);
        }

        .filter-section {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 1.5rem 0;
            margin-top: 2rem;
        }

        @media (max-width: 768px) {
            .stat-card .value {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="dashboard-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-1">Regional Administrative Dashboard</h1>
                    <p class="mb-0">Comprehensive overview of administrative divisions and land use data</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="btn-group">
                        <button class="btn btn-light">Export Data</button>
                        <button class="btn btn-outline-light">Print Report</button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mt-4">
        <!-- Summary Statistics -->
        <div class="row">
            <div class="col-md-3">
                <div class="stat-card">
                    <h5>Total Population</h5>
                    <div class="value">5,247,890</div>
                    <small>Across all administrative levels</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h5>Total Land Area</h5>
                    <div class="value">45,678 km²</div>
                    <small>Farmland: 22,450 km² (49%)</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h5>Administrative Zones</h5>
                    <div class="value">12</div>
                    <small>With 84 woredas and 420 kebeles</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h5>Average Density</h5>
                    <div class="value">115/km²</div>
                    <small>Population per square kilometer</small>
                </div>
            </div>
        </div>

        <!-- Filters and Navigation -->
        <div class="filter-section">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="regionSelect" class="form-label">Select Region</label>
                        <select class="form-select" id="regionSelect">
                            <option selected>Central Region</option>
                            <option>Northern Region</option>
                            <option>Southern Region</option>
                            <option>Eastern Region</option>
                            <option>Western Region</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="levelSelect" class="form-label">Administrative Level</label>
                        <select class="form-select" id="levelSelect">
                            <option selected>All Levels</option>
                            <option>Regional</option>
                            <option>Zonal</option>
                            <option>Woreda</option>
                            <option>Kebele</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Visualizations -->
        <div class="row">
            <div class="col-md-6">
                <div class="chart-container">
                    <h4>Population Distribution by Zone</h4>
                    <canvas id="populationChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container">
                    <h4>Land Use Composition</h4>
                    <canvas id="landUseChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Data Tables -->
        <div class="row">
            <div class="col-12">
                <div class="table-container">
                    <h4>Administrative Units Overview</h4>
                    <ul class="nav nav-tabs mb-3" id="adminTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="zones-tab" data-bs-toggle="tab" data-bs-target="#zones" type="button" role="tab">Zones</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="woredas-tab" data-bs-toggle="tab" data-bs-target="#woredas" type="button" role="tab">Woredas</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="kebeles-tab" data-bs-toggle="tab" data-bs-target="#kebeles" type="button" role="tab">Kebeles</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="adminTabsContent">
                        <!-- Zones Tab -->
                        <div class="tab-pane fade show active" id="zones" role="tabpanel">
                            <div class="table-responsive">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Zone Name</th>
                                            <th>Woredas</th>
                                            <th>Kebeles</th>
                                            <th>Population</th>
                                            <th>Area (km²)</th>
                                            <th>Primary Sectors</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>North Zone <span class="level-badge level-zone">Zone</span></td>
                                            <td>8</td>
                                            <td>42</td>
                                            <td>623,450</td>
                                            <td>5,230</td>
                                            <td>
                                                <span class="sector-tag">Agriculture</span>
                                                <span class="sector-tag">Livestock</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>South Zone <span class="level-badge level-zone">Zone</span></td>
                                            <td>7</td>
                                            <td>35</td>
                                            <td>587,210</td>
                                            <td>4,850</td>
                                            <td>
                                                <span class="sector-tag">Manufacturing</span>
                                                <span class="sector-tag">Trade</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>East Zone <span class="level-badge level-zone">Zone</span></td>
                                            <td>9</td>
                                            <td>45</td>
                                            <td>712,340</td>
                                            <td>6,120</td>
                                            <td>
                                                <span class="sector-tag">Agriculture</span>
                                                <span class="sector-tag">Tourism</span>
                                            </td>
                                        </tr>
                                        <!-- Additional rows would go here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Woredas Tab -->
                        <div class="tab-pane fade" id="woredas" role="tabpanel">
                            <div class="table-responsive">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Woreda Name</th>
                                            <th>Zone</th>
                                            <th>Kebeles</th>
                                            <th>Population</th>
                                            <th>Area (km²)</th>
                                            <th>Primary Sectors</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Bako Woreda <span class="level-badge level-woreda">Woreda</span></td>
                                            <td>North Zone</td>
                                            <td>6</td>
                                            <td>89,450</td>
                                            <td>780</td>
                                            <td>
                                                <span class="sector-tag">Agriculture</span>
                                                <span class="sector-tag">Fishery</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tulu Woreda <span class="level-badge level-woreda">Woreda</span></td>
                                            <td>South Zone</td>
                                            <td>5</td>
                                            <td>76,320</td>
                                            <td>650</td>
                                            <td>
                                                <span class="sector-tag">Manufacturing</span>
                                                <span class="sector-tag">Services</span>
                                            </td>
                                        </tr>
                                        <!-- Additional rows would go here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Kebeles Tab -->
                        <div class="tab-pane fade" id="kebeles" role="tabpanel">
                            <div class="table-responsive">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Kebele Name</th>
                                            <th>Woreda</th>
                                            <th>Zone</th>
                                            <th>Population</th>
                                            <th>Area (km²)</th>
                                            <th>Land Use</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Kebele 01 <span class="level-badge level-kebele">Kebele</span></td>
                                            <td>Bako Woreda</td>
                                            <td>North Zone</td>
                                            <td>15,230</td>
                                            <td>120</td>
                                            <td>
                                                <span class="sector-tag">Farmland: 65%</span>
                                                <span class="sector-tag">Grassland: 20%</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Kebele 02 <span class="level-badge level-kebele">Kebele</span></td>
                                            <td>Tulu Woreda</td>
                                            <td>South Zone</td>
                                            <td>12,450</td>
                                            <td>95</td>
                                            <td>
                                                <span class="sector-tag">Farmland: 45%</span>
                                                <span class="sector-tag">Forest: 30%</span>
                                            </td>
                                        </tr>
                                        <!-- Additional rows would go here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Land Use Details -->
        <div class="row">
            <div class="col-12">
                <div class="table-container">
                    <h4>Detailed Land Use by Administrative Level</h4>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Administrative Unit</th>
                                    <th>Level</th>
                                    <th>Total Area (km²)</th>
                                    <th>Farmland (km²)</th>
                                    <th>Grassland (km²)</th>
                                    <th>Forest (km²)</th>
                                    <th>Water (km²)</th>
                                    <th>Urban (km²)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Central Region</td>
                                    <td><span class="level-badge level-region">Region</span></td>
                                    <td>45,678</td>
                                    <td>22,450 (49%)</td>
                                    <td>12,340 (27%)</td>
                                    <td>7,890 (17%)</td>
                                    <td>1,230 (3%)</td>
                                    <td>1,768 (4%)</td>
                                </tr>
                                <tr>
                                    <td>North Zone</td>
                                    <td><span class="level-badge level-zone">Zone</span></td>
                                    <td>5,230</td>
                                    <td>2,890 (55%)</td>
                                    <td>1,450 (28%)</td>
                                    <td>650 (12%)</td>
                                    <td>120 (2%)</td>
                                    <td>120 (2%)</td>
                                </tr>
                                <tr>
                                    <td>Bako Woreda</td>
                                    <td><span class="level-badge level-woreda">Woreda</span></td>
                                    <td>780</td>
                                    <td>450 (58%)</td>
                                    <td>200 (26%)</td>
                                    <td>90 (12%)</td>
                                    <td>20 (3%)</td>
                                    <td>20 (3%)</td>
                                </tr>
                                <!-- Additional rows would go here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Regional Administrative Dashboard</h5>
                    <p>Comprehensive data visualization for administrative divisions and land use</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>Last updated: June 15, 2023</p>
                    <p>Data Source: Ministry of Agriculture & Regional Bureau of Statistics</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Population Chart
        const populationCtx = document.getElementById('populationChart').getContext('2d');
        const populationChart = new Chart(populationCtx, {
            type: 'bar',
            data: {
                labels: ['North Zone', 'South Zone', 'East Zone', 'West Zone', 'Central Zone', 'Highland Zone'],
                datasets: [{
                    label: 'Population',
                    data: [623450, 587210, 712340, 543210, 689120, 456780],
                    backgroundColor: [
                        'rgba(52, 152, 219, 0.7)',
                        'rgba(46, 204, 113, 0.7)',
                        'rgba(155, 89, 182, 0.7)',
                        'rgba(241, 196, 15, 0.7)',
                        'rgba(230, 126, 34, 0.7)',
                        'rgba(231, 76, 60, 0.7)'
                    ],
                    borderColor: [
                        'rgb(52, 152, 219)',
                        'rgb(46, 204, 113)',
                        'rgb(155, 89, 182)',
                        'rgb(241, 196, 15)',
                        'rgb(230, 126, 34)',
                        'rgb(231, 76, 60)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Population by Zone'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Population'
                        }
                    }
                }
            }
        });

        // Land Use Chart
        const landUseCtx = document.getElementById('landUseChart').getContext('2d');
        const landUseChart = new Chart(landUseCtx, {
            type: 'doughnut',
            data: {
                labels: ['Farmland', 'Grassland', 'Forest', 'Water Bodies', 'Urban Areas', 'Other'],
                datasets: [{
                    data: [49, 27, 17, 3, 4, 0],
                    backgroundColor: [
                        'rgba(46, 204, 113, 0.7)',
                        'rgba(241, 196, 15, 0.7)',
                        'rgba(39, 174, 96, 0.7)',
                        'rgba(52, 152, 219, 0.7)',
                        'rgba(231, 76, 60, 0.7)',
                        'rgba(149, 165, 166, 0.7)'
                    ],
                    borderColor: [
                        'rgb(46, 204, 113)',
                        'rgb(241, 196, 15)',
                        'rgb(39, 174, 96)',
                        'rgb(52, 152, 219)',
                        'rgb(231, 76, 60)',
                        'rgb(149, 165, 166)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: true,
                        text: 'Land Use Composition (%)'
                    }
                }
            }
        });
    </script>
</body>

</html>