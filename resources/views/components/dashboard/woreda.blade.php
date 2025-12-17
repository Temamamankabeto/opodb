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
                            <i class="fas fa-map-marked-alt text-primary"></i>
                            Woreda  Dashboard
                        </h4>
                    </div>
                </div>
            </div>
        </section>

        {{-- Main content --}}
        <section class="content">
            <div class="container-fluid">

                {{-- ===================== STAT CARDS ===================== --}}
                <div class="row">

                    {{-- Total Land --}}
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>1,245</h3>
                                <p>Total Land Area (ha)</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-map"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Total Production --}}
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>5,678</h3>
                                <p>Total Production (Qt)</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-seedling"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Productivity --}}
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>4.56</h3>
                                <p>Avg Productivity (Qt/ha)</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Farmers --}}
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>1,245</h3>
                                <p>Registered Farmers</p>
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
                                    <i class="fas fa-leaf"></i> Crop Production Summary
                                </h5>
                            </div>

                            <div class="card-body">
                                <div class="row">

                                    @php
                                        $crops = [
                                            ['name'=>'Wheat','qty'=>2450,'land'=>450,'icon'=>'fa-wheat-awn','color'=>'warning'],
                                            ['name'=>'Teff','qty'=>1780,'land'=>320,'icon'=>'fa-seedling','color'=>'info'],
                                            ['name'=>'Barley','qty'=>980,'land'=>180,'icon'=>'fa-mug-hot','color'=>'secondary'],
                                            ['name'=>'Corn','qty'=>1250,'land'=>230,'icon'=>'fa-apple-alt','color'=>'success'],
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
                                                    <small>Land: {{ $crop['land'] }} ha</small>
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
                                <h5 class="card-title">Production by Crop</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="cropProductionChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-outline card-success">
                            <div class="card-header">
                                <h5 class="card-title">Yearly Comparison</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="yearlyComparisonChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===================== TABLE ===================== --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-table"></i> Woreda Crop Production Details
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
                                            <td>450</td>
                                            <td>5.44</td>
                                            <td>2,450</td>
                                            <td><span class="badge badge-success">Above Target</span></td>
                                        </tr>
                                        <tr>
                                            <td>Cereal</td>
                                            <td>Teff</td>
                                            <td>320</td>
                                            <td>5.56</td>
                                            <td>1,780</td>
                                            <td><span class="badge badge-success">Above Target</span></td>
                                        </tr>
                                        <tr>
                                            <td>Cereal</td>
                                            <td>Barley</td>
                                            <td>180</td>
                                            <td>5.44</td>
                                            <td>980</td>
                                            <td><span class="badge badge-warning">Below Target</span></td>
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

    {{-- Charts (unchanged logic) --}}
    <script>
        new Chart(document.getElementById('cropProductionChart'), {
            type: 'doughnut',
            data: {
                labels: ['Wheat','Teff','Barley','Corn'],
                datasets: [{
                    data: [2450,1780,980,1250],
                }]
            }
        });

        new Chart(document.getElementById('yearlyComparisonChart'), {
            type: 'bar',
            data: {
                labels: ['Wheat','Teff','Barley','Corn'],
                datasets: [
                    { label:'2022', data:[2200,1580,1000,1080] },
                    { label:'2023', data:[2450,1780,980,1250] }
                ]
            }
        });
    </script>
</x-layout>
