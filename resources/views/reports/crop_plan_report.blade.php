<x-layout>

    <style>
        body {
            background-color: #eaf6fd;
            font-family: Arial, sans-serif;
        }

        .dashboard-container {
            padding: 20px;
        }

        .dashboard-header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        .charts-row {
            display: flex;
            justify-content: space-around;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .chart-container {
            width: 300px;
            text-align: center;
        }

        .status-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
        }

        .status-table th,
        .status-table td {
            border: 1px solid #d0d7de;
            padding: 8px;
            text-align: center;
            font-size: 14px;
        }

        .status-table th {
            background-color: #b3d9f7;
            font-weight: bold;
        }

        .status-operational {
            color: green;
            font-weight: bold;
        }

        .status-warning {
            color: orange;
            font-weight: bold;
        }

        .status-error {
            color: red;
            font-weight: bold;
        }
    </style>

    <div class="dashboard-container">
        <div class="dashboard-header"></div>
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <strong class="text-center">Gabaasa Karooraa Fi Raawwii Misooma Midhaanii Sadarkaa</strong>
            </div>
            <form method="get" action="#">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="association_type">Ganda</label>
                            <select name="association_type" id="association_type" class="form-control">
                                <option value="">All</option>
                                {{-- Populate with options dynamically if needed --}}
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="midhaan">Midhaan</label>
                            <select name="midhaan" id="midhaan" class="form-control">
                                <option value="">All</option>
                                <option value="Kan Lafa Fudhate">Kan Lafa Fudhate</option>
                                <option value="Woldaa Haaraa">Woldaa Haaraa</option>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="waqtii">Waqtii</label>
                            <select name="waqtii" id="waqtii" class="form-control">
                                <option value="">All</option>
                                <option value="Guyyaa">Guyyaa</option>
                                <option value="Torban">Torban</option>
                                <option value="Ji'a">Ji'a</option>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="bara">Bara</label>
                            <select name="bara" id="bara" class="form-control">
                                <option value="">All</option>
                                <option value="2016">2016</option>
                                <option value="2017">2017</option>
                                <option value="2018">2018</option>
                                {{-- Add more years as needed --}}
                            </select>
                        </div>

                        <div class="form-group col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary mr-2" type="submit">Barbaadi</button>
                            <a href="#" class="btn btn-success">Deebi'i</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <strong>Visual Analytics</strong>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <canvas id="loginRatePie"></canvas>
                                <p class="mt-2">Failed Login Rate</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <canvas id="statusPie"></canvas>
                                <p class="mt-2">Status Breakdown</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <canvas id="barChart"></canvas>
                                <p class="mt-2">Rate Chart</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <strong>Boqqoolloo Gannaa</strong>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Ganda</th>
                                        <th>Karoora Lafa</th>
                                        <th>Rawwii Lafa</th>
                                        <th>Karoora Oomisha</th>
                                        <th>Raawwii Oomishaa</th>
                                        <th>Oomistummaa</th>
                                        <th>Bara</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>EM-CB2109</td>
                                        <td>v1.2.5</td>
                                        <td class="text-warning">Warning</td>
                                        <td>32</td>
                                        <td>🔼</td>
                                        <td>21%</td>
                                        <td>21%</td>
                                    </tr>
                                    <tr>
                                        <td>EM-CA2108</td>
                                        <td>v1.4.5</td>
                                        <td class="text-success">Operational</td>
                                        <td>0</td>
                                        <td>🔼</td>
                                        <td>1%</td>
                                        <td>21%</td>
                                    </tr>
                                    <tr>
                                        <td>EM-CA2108</td>
                                        <td>v1.4.5</td>
                                        <td class="text-success">Operational</td>
                                        <td>0</td>
                                        <td>🔼</td>
                                        <td>1%</td>
                                        <td>21%</td>
                                    </tr>
                                    <tr>
                                        <td>EM-CA2108</td>
                                        <td>v1.4.5</td>
                                        <td class="text-success">Operational</td>
                                        <td>0</td>
                                        <td>🔼</td>
                                        <td>0%</td>
                                        <td>21%</td>
                                    </tr>
                                    <tr>
                                        <td>EM-CD3100</td>
                                        <td>v2.1.3</td>
                                        <td class="text-success">Operational</td>
                                        <td>0</td>
                                        <td>🔼</td>
                                        <td>1%</td>
                                        <td>21%</td>
                                    </tr>
                                    <tr>
                                        <td>EM-CD3100</td>
                                        <td>v2.1.3</td>
                                        <td class="text-danger">Error</td>
                                        <td>1108</td>
                                        <td>🔼</td>
                                        <td>100%</td>
                                        <td>21%</td>
                                    </tr>
                                    <tr>
                                        <td>EM-CD3100</td>
                                        <td>v2.1.3</td>
                                        <td class="text-warning">Warning</td>
                                        <td>0</td>
                                        <td>🔼</td>
                                        <td>0%</td>
                                        <td>21%</td>
                                    </tr>
                                    <!-- Add more rows as needed -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <strong>Midhaan Bonaa</strong>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Ganda</th>
                                        <th>Karoora Lafa</th>
                                        <th>Rawwii Lafa</th>
                                        <th>Karoora Oomisha</th>
                                        <th>Raawwii Oomishaa</th>
                                        <th>Oomistummaa</th>
                                        <th>Bara</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>EM-CB2109</td>
                                        <td>v1.2.5</td>
                                        <td class="text-warning">Warning</td>
                                        <td>32</td>
                                        <td>🔼</td>
                                        <td>21%</td>
                                        <td>21%</td>
                                    </tr>
                                    <tr>
                                        <td>EM-CA2108</td>
                                        <td>v1.4.5</td>
                                        <td class="text-success">Operational</td>
                                        <td>0</td>
                                        <td>🔼</td>
                                        <td>1%</td>
                                        <td>21%</td>
                                    </tr>
                                    <tr>
                                        <td>EM-CA2108</td>
                                        <td>v1.4.5</td>
                                        <td class="text-success">Operational</td>
                                        <td>0</td>
                                        <td>🔼</td>
                                        <td>1%</td>
                                        <td>21%</td>
                                    </tr>
                                    <tr>
                                        <td>EM-CA2108</td>
                                        <td>v1.4.5</td>
                                        <td class="text-success">Operational</td>
                                        <td>0</td>
                                        <td>🔼</td>
                                        <td>0%</td>
                                        <td>21%</td>
                                    </tr>
                                    <tr>
                                        <td>EM-CD3100</td>
                                        <td>v2.1.3</td>
                                        <td class="text-success">Operational</td>
                                        <td>0</td>
                                        <td>🔼</td>
                                        <td>1%</td>
                                        <td>21%</td>
                                    </tr>
                                    <tr>
                                        <td>EM-CD3100</td>
                                        <td>v2.1.3</td>
                                        <td class="text-danger">Error</td>
                                        <td>1108</td>
                                        <td>🔼</td>
                                        <td>100%</td>
                                        <td>21%</td>
                                    </tr>
                                    <tr>
                                        <td>EM-CD3100</td>
                                        <td>v2.1.3</td>
                                        <td class="text-warning">Warning</td>
                                        <td>0</td>
                                        <td>🔼</td>
                                        <td>0%</td>
                                        <td>21%</td>
                                    </tr>
                                    <!-- Add more rows as needed -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <strong>Midhaan Arfaasaa</strong>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-center">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Ganda</th>
                                        <th>Karoora Lafa</th>
                                        <th>Rawwii Lafa</th>
                                        <th>Karoora Oomisha</th>
                                        <th>Raawwii Oomishaa</th>
                                        <th>Oomistummaa</th>
                                        <th>Bara</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>EM-CB2109</td>
                                        <td>v1.2.5</td>
                                        <td class="text-warning">Warning</td>
                                        <td>32</td>
                                        <td>🔼</td>
                                        <td>21%</td>
                                        <td>21%</td>
                                    </tr>
                                    <tr>
                                        <td>EM-CA2108</td>
                                        <td>v1.4.5</td>
                                        <td class="text-success">Operational</td>
                                        <td>0</td>
                                        <td>🔼</td>
                                        <td>1%</td>
                                        <td>21%</td>
                                    </tr>
                                    <tr>
                                        <td>EM-CA2108</td>
                                        <td>v1.4.5</td>
                                        <td class="text-success">Operational</td>
                                        <td>0</td>
                                        <td>🔼</td>
                                        <td>1%</td>
                                        <td>21%</td>
                                    </tr>
                                    <tr>
                                        <td>EM-CA2108</td>
                                        <td>v1.4.5</td>
                                        <td class="text-success">Operational</td>
                                        <td>0</td>
                                        <td>🔼</td>
                                        <td>0%</td>
                                        <td>21%</td>
                                    </tr>
                                    <tr>
                                        <td>EM-CD3100</td>
                                        <td>v2.1.3</td>
                                        <td class="text-success">Operational</td>
                                        <td>0</td>
                                        <td>🔼</td>
                                        <td>1%</td>
                                        <td>21%</td>
                                    </tr>
                                    <tr>
                                        <td>EM-CD3100</td>
                                        <td>v2.1.3</td>
                                        <td class="text-danger">Error</td>
                                        <td>1108</td>
                                        <td>🔼</td>
                                        <td>100%</td>
                                        <td>21%</td>
                                    </tr>
                                    <tr>
                                        <td>EM-CD3100</td>
                                        <td>v2.1.3</td>
                                        <td class="text-warning">Warning</td>
                                        <td>0</td>
                                        <td>🔼</td>
                                        <td>0%</td>
                                        <td>21%</td>
                                    </tr>
                                    <!-- Add more rows as needed -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                 
            </div>
        </div>
        <!-- Table Card -->



        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            new Chart(document.getElementById("loginRatePie"), {
                type: 'pie',
                data: {
                    labels: ["High failed login rate", "Low failed login rate"],
                    datasets: [{
                        data: [18, 52],
                        backgroundColor: ["#f7977a", "#84b6f4"]
                    }]
                }
            });

            new Chart(document.getElementById("statusPie"), {
                type: 'pie',
                data: {
                    labels: ["Failed Pages", "Failed Requests", "Failed Login", "Abort Connects"],
                    datasets: [{
                        data: [40, 25, 15, 20],
                        backgroundColor: ["#84b6f4", "#f9cdad", "#f7977a", "#c3aed6"]
                    }]
                }
            });

            new Chart(document.getElementById("barChart"), {
                type: 'bar',
                data: {
                    labels: ["Pages", "Requests", "Connects", "Login"],
                    datasets: [{
                        label: 'Rate',
                        data: [62, 55, 35, 61],
                        backgroundColor: "#6fcf97"
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        </script>


</x-layout>