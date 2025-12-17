<x-layout>
    {{-- AdminLTE CSS --}}
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <div class="content-header">

        {{-- Content Header --}}
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="fw-bold">
                            <i class="fas fa-layer-group text-primary"></i>
                            Zone   Dashboard
                        </h4>
                    </div>
                </div>
            </div>
        </section>

        {{-- Main content --}}
        <section class="content">
            <div class="container-fluid">

                {{-- ===================== SUMMARY BOXES ===================== --}}
                <div class="row">

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>18,420</h3>
                                <p>Total Land Area (ha)</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-map"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>82,300</h3>
                                <p>Total Production (Qt)</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-seedling"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>4.47</h3>
                                <p>Avg Productivity (Qt/ha)</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>28,560</h3>
                                <p>Total Farmers</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ===================== CROP SUMMARY ===================== --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-leaf"></i> Zone Crop Production Summary
                                </h5>
                            </div>

                            <div class="card-body">
                                <div class="row">

                                    @php
                                        $crops = [
                                            ['name'=>'Wheat','qty'=>28500,'land'=>4200,'icon'=>'fa-wheat-awn','color'=>'warning'],
                                            ['name'=>'Teff','qty'=>21700,'land'=>3650,'icon'=>'fa-seedling','color'=>'info'],
                                            ['name'=>'Barley','qty'=>12800,'land'=>2800,'icon'=>'fa-mug-hot','color'=>'secondary'],
                                            ['name'=>'Corn','qty'=>19300,'land'=>3770,'icon'=>'fa-apple-alt','color'=>'success'],
                                        ];
                                    @endphp

                                    @foreach($crops as $crop)
                                        <div class="col-lg-3 col-md-6">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-{{ $crop['color'] }}">
                                                    <i class="fas {{ $crop['icon'] }}"></i>
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ $crop['name'] }}</span>
                                                    <span class="info-box-number">
                                                        {{ number_format($crop['qty']) }} Qt
                                                    </span>
                                                    <small>Land: {{ number_format($crop['land']) }} ha</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===================== CHARTS ===================== --}}
                <div class="row">

                    <div class="col-md-6">
                        <div class="card card-outline card-info">
                            <div class="card-header">
                                <h5 class="card-title">Production by Crop (Zone)</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="cropProductionChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-outline card-success">
                            <div class="card-header">
                                <h5 class="card-title">Yearly Comparison (Zone)</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="yearlyComparisonChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ===================== DETAILED TABLE ===================== --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-table"></i> Zone Crop Production Details
                                </h5>
                            </div>

                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Crop Type</th>
                                            <th>Crop</th>
                                            <th>Land (ha)</th>
                                            <th>Productivity</th>
                                            <th>Production (Qt)</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Cereal</td>
                                            <td>Wheat</td>
                                            <td>4,200</td>
                                            <td>6.78</td>
                                            <td>28,500</td>
                                            <td><span class="badge badge-success">Above Target</span></td>
                                        </tr>
                                        <tr>
                                            <td>Cereal</td>
                                            <td>Teff</td>
                                            <td>3,650</td>
                                            <td>5.95</td>
                                            <td>21,700</td>
                                            <td><span class="badge badge-success">Above Target</span></td>
                                        </tr>
                                        <tr>
                                            <td>Cereal</td>
                                            <td>Barley</td>
                                            <td>2,800</td>
                                            <td>4.57</td>
                                            <td>12,800</td>
                                            <td><span class="badge badge-warning">Below Target</span></td>
                                        </tr>
                                        <tr>
                                            <td>Cereal</td>
                                            <td>Corn</td>
                                            <td>3,770</td>
                                            <td>5.12</td>
                                            <td>19,300</td>
                                            <td><span class="badge badge-success">Above Target</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>

    {{-- AdminLTE Scripts --}}
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Charts (same logic) --}}
    <script>
        new Chart(document.getElementById('cropProductionChart'), {
            type: 'doughnut',
            data: {
                labels: ['Wheat','Teff','Barley','Corn'],
                datasets: [{
                    data: [28500,21700,12800,19300],
                }]
            }
        });

        new Chart(document.getElementById('yearlyComparisonChart'), {
            type: 'bar',
            data: {
                labels: ['Wheat','Teff','Barley','Corn'],
                datasets: [
                    { label:'2022', data:[26000,20000,13500,17500] },
                    { label:'2023', data:[28500,21700,12800,19300] }
                ]
            }
        });
    </script>
</x-layout>
