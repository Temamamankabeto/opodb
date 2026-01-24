<x-layout>
    <div class="content-header">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>
                            <i class="fas fa-id-card mr-2 text-indigo"></i>
                            {{ __('Civil Registration Report') }}
                            <small class="text-muted">{{ __('Birth, marriage, death, and identity document registration analysis') }}</small>
                        </h1>
                    </div>
                    <div class="col-sm-6">
                         
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @php
                    // Prepare data for charts
                    $activityKeys = array_keys($activityData['current'] ?? []);
                    $activityAmounts = array_values($activityData['current'] ?? []);
                    
                    // Prepare data for document type chart
                    $documentTypeLabels = [
                        __('Birth Certificates'),
                        __('Marriage Certificates'),
                        __('ID Cards'),
                        __('Divorce Certificates'),
                        __('Death Certificates')
                    ];
                    $currentDocumentData = [
                        $reportData['current']['birth_certificates']['quantity'] ?? 0,
                        $reportData['current']['marriage_certificates']['quantity'] ?? 0,
                        $reportData['current']['id_cards']['quantity'] ?? 0,
                        $reportData['current']['divorce_certificates']['quantity'] ?? 0,
                        $reportData['current']['death_certificates']['quantity'] ?? 0
                    ];
                    $previousDocumentData = [
                        $reportData['previous']['birth_certificates']['quantity'] ?? 0,
                        $reportData['previous']['marriage_certificates']['quantity'] ?? 0,
                        $reportData['previous']['id_cards']['quantity'] ?? 0,
                        $reportData['previous']['divorce_certificates']['quantity'] ?? 0,
                        $reportData['previous']['death_certificates']['quantity'] ?? 0
                    ];
                    
                    // Prepare data for trend analysis
                    $trendLabels = [$previousYear, $selectedYear];
                    $totalTrend = [
                        $reportData['previous']['total_documents'] ?? 0,
                        $reportData['current']['total_documents'] ?? 0
                    ];
                    
                    // Category colors for charts
                    $documentColors = [
                        'rgba(23, 162, 184, 0.7)',    // Teal - Birth
                        'rgba(40, 167, 69, 0.7)',     // Green - Marriage
                        'rgba(0, 123, 255, 0.7)',     // Blue - ID Cards
                        'rgba(108, 117, 125, 0.7)',   // Gray - Divorce
                        'rgba(220, 53, 69, 0.7)'      // Red - Death
                    ];
                    
                    $documentBorderColors = [
                        'rgba(23, 162, 184, 1)',
                        'rgba(40, 167, 69, 1)',
                        'rgba(0, 123, 255, 1)',
                        'rgba(108, 117, 125, 1)',
                        'rgba(220, 53, 69, 1)'
                    ];
                @endphp

                <!-- Year Filter Card -->
                <div class="card card-outline card-indigo">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-filter mr-1"></i>
                            {{ __('Filter Options') }}
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ __('Year') }}</label>
                                        <select name="year" class="form-control">
                                            @foreach($availableYears as $year)
                                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" style="margin-top: 32px;">
                                        <button type="submit" class="btn btn-indigo">
                                            <i class="fas fa-filter mr-1"></i> {{ __('Apply Filter') }}
                                        </button>
                                        <a href="{{ url()->current() }}" class="btn btn-default">
                                            <i class="fas fa-redo mr-1"></i> {{ __('Reset') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-indigo">
                            <div class="inner">
                                <h3>{{ number_format($reportData['current']['total_documents'] ?? 0) }}</h3>
                                <p>{{ __('Total Documents') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            @if($documentChange != 0)
                                <div class="small-box-footer">
                                    {{ $documentChange > 0 ? '↑' : '↓' }} 
                                    {{ number_format(abs($documentChange), 1) }}% 
                                    {{ __('from last year') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-teal">
                            <div class="inner">
                                <h3>{{ number_format($reportData['current']['birth_certificates']['quantity'] ?? 0) }}</h3>
                                <p>{{ __('Birth Certificates') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-baby"></i>
                            </div>
                            <a href="#birth-certificates" class="small-box-footer">
                                {{ __('View details') }} <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ number_format($reportData['current']['marriage_certificates']['quantity'] ?? 0) }}</h3>
                                <p>{{ __('Marriage Certificates') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <a href="#marriage-certificates" class="small-box-footer">
                                {{ __('View details') }} <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ number_format($reportData['current']['death_certificates']['quantity'] ?? 0) }}</h3>
                                <p>{{ __('Death Certificates') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-cross"></i>
                            </div>
                            <a href="#death-certificates" class="small-box-footer">
                                {{ __('View details') }} <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Additional Summary Cards -->
                <div class="row mb-4">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ number_format($reportData['current']['id_cards']['quantity'] ?? 0) }}</h3>
                                <p>{{ __('ID Cards') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-address-card"></i>
                            </div>
                            <a href="#id-cards" class="small-box-footer">
                                {{ __('View details') }} <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-secondary">
                            <div class="inner">
                                <h3>{{ number_format($reportData['current']['divorce_certificates']['quantity'] ?? 0) }}</h3>
                                <p>{{ __('Divorce Certificates') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-heart-broken"></i>
                            </div>
                            <a href="#divorce-certificates" class="small-box-footer">
                                {{ __('View details') }} <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ number_format($reportData['current']['total_categories'] ?? 0) }}</h3>
                                <p>{{ __('Document Types') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-list"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-dark">
                            <div class="inner">
                                <h3>{{ count($activityKeys) }}</h3>
                                <p>{{ __('Registration Activities') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-tasks"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-pie mr-1"></i>
                                    {{ __('Document Type Distribution') }}
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                @if($reportData['current']['total_documents'] > 0)
                                    <div class="chart">
                                        <canvas id="documentTypeChart" height="300"></canvas>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">{{ __('No document data available') }}</h5>
                                        <p class="text-muted">{{ __('Try selecting a different year') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-bar mr-1"></i>
                                    {{ __('Year-over-Year Comparison') }}
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="yearComparisonChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Charts -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-line mr-1"></i>
                                    {{ __('Document Issuance Trend') }}
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="trendChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-bar mr-1"></i>
                                    {{ __('Registration Activities') }}
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                @if(count($activityKeys) > 0)
                                    <div class="chart">
                                        <canvas id="activitiesChart" height="250"></canvas>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">{{ __('No registration activity data available') }}</h5>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Report Table -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-table mr-1"></i>
                            {{ __('Detailed Registration Report') }}
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr class="bg-gradient-indigo">
                                        <th>{{ __('Document Type') }}</th>
                                        <th class="text-center">{{ $selectedYear }}</th>
                                        <th class="text-center">{{ $previousYear }}</th>
                                        <th class="text-center">{{ __('Change') }}</th>
                                        <th class="text-center">{{ __('Change %') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($documentTypeLabels as $index => $label)
                                        @php
                                            $currentQty = $currentDocumentData[$index] ?? 0;
                                            $previousQty = $previousDocumentData[$index] ?? 0;
                                            $change = $currentQty - $previousQty;
                                            $changePercent = $previousQty > 0 ? round(($change / $previousQty) * 100, 1) : ($currentQty > 0 ? 100 : 0);
                                            
                                            $changeClass = '';
                                            $changeIcon = '';
                                            if ($change > 0) {
                                                $changeClass = 'text-success';
                                                $changeIcon = '<i class="fas fa-arrow-up"></i>';
                                            } elseif ($change < 0) {
                                                $changeClass = 'text-danger';
                                                $changeIcon = '<i class="fas fa-arrow-down"></i>';
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <i class="fas @switch($index)
                                                    @case(0) fa-baby text-teal @break
                                                    @case(1) fa-heart text-success @break
                                                    @case(2) fa-address-card text-info @break
                                                    @case(3) fa-heart-broken text-secondary @break
                                                    @case(4) fa-cross text-danger @break
                                                @endswitch mr-2"></i>
                                                {{ $label }}
                                            </td>
                                            <td class="text-center font-weight-bold">
                                                {{ number_format($currentQty) }}
                                            </td>
                                            <td class="text-center text-muted">
                                                {{ number_format($previousQty) }}
                                            </td>
                                            <td class="text-center {{ $changeClass }}">
                                                {!! $changeIcon !!} {{ number_format(abs($change)) }}
                                            </td>
                                            <td class="text-center {{ $changeClass }}">
                                                {{ $changePercent }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray">
                                    <tr>
                                        <th class="font-weight-bold">{{ __('TOTAL') }}</th>
                                        <th class="text-center font-weight-bold">{{ number_format($reportData['current']['total_documents'] ?? 0) }}</th>
                                        <th class="text-center text-muted">{{ number_format($reportData['previous']['total_documents'] ?? 0) }}</th>
                                        <th class="text-center {{ $documentChange > 0 ? 'text-success' : ($documentChange < 0 ? 'text-danger' : '') }}">
                                            @if($documentChange > 0)
                                                <i class="fas fa-arrow-up"></i>
                                            @elseif($documentChange < 0)
                                                <i class="fas fa-arrow-down"></i>
                                            @endif
                                            {{ number_format(abs($reportData['current']['total_documents'] - $reportData['previous']['total_documents'])) }}
                                        </th>
                                        <th class="text-center {{ $documentChange > 0 ? 'text-success' : ($documentChange < 0 ? 'text-danger' : '') }}">
                                            {{ number_format(abs($documentChange), 1) }}%
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Activity Details -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header p-0">
                                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="current-tab" data-toggle="pill" href="#current" role="tab" aria-controls="current" aria-selected="true">
                                            <i class="fas fa-calendar-check mr-1"></i> {{ $selectedYear }}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="previous-tab" data-toggle="pill" href="#previous" role="tab" aria-controls="previous" aria-selected="false">
                                            <i class="fas fa-calendar-alt mr-1"></i> {{ $previousYear }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-three-tabContent">
                                    <!-- Current Year Tab -->
                                    <div class="tab-pane fade show active" id="current" role="tabpanel" aria-labelledby="current-tab">
                                        @if(count($activityData['current']) > 0)
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">#</th>
                                                            <th>{{ __('Activity') }}</th>
                                                            <th width="15%" class="text-right">{{ __('Quantity') }}</th>
                                                            <th width="20%">{{ __('Document Type') }}</th>
                                                            <th width="15%">{{ __('Status') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($activityData['current'] as $key => $quantity)
                                                            @php
                                                                $documentType = __('Other');
                                                                $badgeClass = 'badge-secondary';
                                                                if (strpos($key, 'Ragaa dhalootaa') !== false) {
                                                                    $documentType = __('Birth Certificate');
                                                                    $badgeClass = 'badge-teal';
                                                                } elseif (strpos($key, 'Ragaa gaa\'elaa') !== false && strpos($key, 'diigamuu') === false) {
                                                                    $documentType = __('Marriage Certificate');
                                                                    $badgeClass = 'badge-success';
                                                                } elseif (strpos($key, 'Waraqaa eenyummaa') !== false) {
                                                                    $documentType = __('ID Card');
                                                                    $badgeClass = 'badge-info';
                                                                } elseif (strpos($key, 'diigamuu gaa\'elaa') !== false) {
                                                                    $documentType = __('Divorce Certificate');
                                                                    $badgeClass = 'badge-secondary';
                                                                } elseif (strpos($key, 'Ragaa du\'aatii') !== false) {
                                                                    $documentType = __('Death Certificate');
                                                                    $badgeClass = 'badge-danger';
                                                                }
                                                                
                                                                $previousQty = $activityData['previous'][$key] ?? 0;
                                                                if ($previousQty == 0) {
                                                                    $status = __('New');
                                                                    $statusClass = 'badge-info';
                                                                } elseif ($quantity > $previousQty) {
                                                                    $status = __('Increased');
                                                                    $statusClass = 'badge-success';
                                                                } elseif ($quantity < $previousQty) {
                                                                    $status = __('Decreased');
                                                                    $statusClass = 'badge-danger';
                                                                } else {
                                                                    $status = __('Stable');
                                                                    $statusClass = 'badge-secondary';
                                                                }
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $key }}</td>
                                                                <td class="text-right font-weight-bold">{{ number_format($quantity) }}</td>
                                                                <td>
                                                                    <span class="badge {{ $badgeClass }}">{{ $documentType }}</span>
                                                                </td>
                                                                <td>
                                                                    <span class="badge {{ $statusClass }}">{{ $status }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-center py-5">
                                                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">{{ __('No registration activity data available for') }} {{ $selectedYear }}</h5>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Previous Year Tab -->
                                    <div class="tab-pane fade" id="previous" role="tabpanel" aria-labelledby="previous-tab">
                                        @if(count($activityData['previous']) > 0)
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">#</th>
                                                            <th>{{ __('Activity') }}</th>
                                                            <th width="15%" class="text-right">{{ __('Quantity') }}</th>
                                                            <th width="20%">{{ __('Document Type') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($activityData['previous'] as $key => $quantity)
                                                            @php
                                                                $documentType = __('Other');
                                                                $badgeClass = 'badge-secondary';
                                                                if (strpos($key, 'Ragaa dhalootaa') !== false) {
                                                                    $documentType = __('Birth Certificate');
                                                                    $badgeClass = 'badge-teal';
                                                                } elseif (strpos($key, 'Ragaa gaa\'elaa') !== false && strpos($key, 'diigamuu') === false) {
                                                                    $documentType = __('Marriage Certificate');
                                                                    $badgeClass = 'badge-success';
                                                                } elseif (strpos($key, 'Waraqaa eenyummaa') !== false) {
                                                                    $documentType = __('ID Card');
                                                                    $badgeClass = 'badge-info';
                                                                } elseif (strpos($key, 'diigamuu gaa\'elaa') !== false) {
                                                                    $documentType = __('Divorce Certificate');
                                                                    $badgeClass = 'badge-secondary';
                                                                } elseif (strpos($key, 'Ragaa du\'aatii') !== false) {
                                                                    $documentType = __('Death Certificate');
                                                                    $badgeClass = 'badge-danger';
                                                                }
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $key }}</td>
                                                                <td class="text-right">{{ number_format($quantity) }}</td>
                                                                <td>
                                                                    <span class="badge {{ $badgeClass }}">{{ $documentType }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-center py-5">
                                                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">{{ __('No registration activity data available for') }} {{ $previousYear }}</h5>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .bg-indigo {
            background-color: #6610f2 !important;
        }
        .bg-gradient-indigo {
            background: linear-gradient(45deg, #6610f2, #6f42c1) !important;
            color: white;
        }
        .btn-indigo {
            background-color: #6610f2;
            border-color: #6610f2;
            color: white;
        }
        .btn-indigo:hover {
            background-color: #5a0ed9;
            border-color: #5a0ed9;
            color: white;
        }
        .card-outline.card-indigo {
            border-top-color: #6610f2;
        }
        .badge-teal {
            background-color: #20c997;
            color: white;
        }
        .nav-tabs .nav-link.active {
            border-top-color: #6610f2;
            color: #6610f2;
        }
        .small-box .icon {
            font-size: 70px;
            top: -15px;
        }
        .chart {
            position: relative;
            height: 300px;
        }
        canvas {
            width: 100% !important;
            height: 100% !important;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Document ready, initializing charts...');
            
            // Initialize charts after a short delay to ensure DOM is fully loaded
            setTimeout(initializeCharts, 100);
            
            // Initialize tooltips
            if (typeof $ !== 'undefined') {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        function initializeCharts() {
            console.log('initializeCharts function called');
            
            // Check if Chart.js is loaded
            if (typeof Chart === 'undefined') {
                console.error('Chart.js is not loaded! Loading dynamically...');
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                script.onload = function() {
                    console.log('Chart.js loaded dynamically');
                    createCharts();
                };
                document.head.appendChild(script);
            } else {
                console.log('Chart.js is already loaded');
                createCharts();
            }
        }

        function createCharts() {
            console.log('createCharts function called');
            
            // Document Type Chart
            const documentTypeCtx = document.getElementById('documentTypeChart');
            if (documentTypeCtx) {
                console.log('Found documentTypeChart canvas');
                console.log('Data:', @json($currentDocumentData));
                
                try {
                    // Destroy existing chart if it exists
                    if (documentTypeCtx.chart) {
                        documentTypeCtx.chart.destroy();
                    }
                    
                    documentTypeCtx.chart = new Chart(documentTypeCtx, {
                        type: 'doughnut',
                        data: {
                            labels: @json($documentTypeLabels),
                            datasets: [{
                                data: @json($currentDocumentData),
                                backgroundColor: @json($documentColors),
                                borderColor: @json($documentBorderColors),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.raw || 0;
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                            return `${label}: ${value} (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                    console.log('Document Type Chart created successfully');
                } catch (error) {
                    console.error('Error creating Document Type Chart:', error);
                }
            } else {
                console.log('documentTypeChart canvas not found');
            }

            // Year Comparison Chart
            const yearComparisonCtx = document.getElementById('yearComparisonChart');
            if (yearComparisonCtx) {
                console.log('Found yearComparisonChart canvas');
                try {
                    if (yearComparisonCtx.chart) {
                        yearComparisonCtx.chart.destroy();
                    }
                    
                    yearComparisonCtx.chart = new Chart(yearComparisonCtx, {
                        type: 'bar',
                        data: {
                            labels: @json($documentTypeLabels),
                            datasets: [
                                {
                                    label: '{{ $selectedYear }}',
                                    data: @json($currentDocumentData),
                                    backgroundColor: 'rgba(102, 16, 242, 0.7)',
                                    borderColor: 'rgba(102, 16, 242, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: '{{ $previousYear }}',
                                    data: @json($previousDocumentData),
                                    backgroundColor: 'rgba(108, 117, 125, 0.7)',
                                    borderColor: 'rgba(108, 117, 125, 1)',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: '{{ __("Number of Documents") }}'
                                    }
                                },
                                x: {
                                    ticks: {
                                        maxRotation: 45,
                                        minRotation: 45
                                    }
                                }
                            }
                        }
                    });
                    console.log('Year Comparison Chart created successfully');
                } catch (error) {
                    console.error('Error creating Year Comparison Chart:', error);
                }
            }

            // Trend Chart
            const trendCtx = document.getElementById('trendChart');
            if (trendCtx) {
                console.log('Found trendChart canvas');
                try {
                    if (trendCtx.chart) {
                        trendCtx.chart.destroy();
                    }
                    
                    trendCtx.chart = new Chart(trendCtx, {
                        type: 'line',
                        data: {
                            labels: @json($trendLabels),
                            datasets: [{
                                label: '{{ __("Total Documents") }}',
                                data: @json($totalTrend),
                                borderColor: 'rgba(255, 193, 7, 1)',
                                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                                borderWidth: 3,
                                tension: 0.3,
                                fill: true,
                                pointBackgroundColor: 'rgba(255, 193, 7, 1)',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: '{{ __("Number of Documents") }}'
                                    }
                                }
                            }
                        }
                    });
                    console.log('Trend Chart created successfully');
                } catch (error) {
                    console.error('Error creating Trend Chart:', error);
                }
            }

            // Activities Chart
            const activitiesCtx = document.getElementById('activitiesChart');
            if (activitiesCtx) {
                console.log('Found activitiesChart canvas');
                console.log('Activity Keys:', @json($activityKeys));
                
                if (@json(count($activityKeys)) > 0) {
                    try {
                        if (activitiesCtx.chart) {
                            activitiesCtx.chart.destroy();
                        }
                        
                        activitiesCtx.chart = new Chart(activitiesCtx, {
                            type: 'bar',
                            data: {
                                labels: @json($activityKeys),
                                datasets: [{
                                    label: '{{ __("Number of Documents") }}',
                                    data: @json($activityAmounts),
                                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                                    borderColor: 'rgba(40, 167, 69, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top',
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: '{{ __("Number of Documents") }}'
                                        }
                                    },
                                    x: {
                                        ticks: {
                                            autoSkip: false,
                                            maxRotation: 45,
                                            minRotation: 45
                                        }
                                    }
                                }
                            }
                        });
                        console.log('Activities Chart created successfully');
                    } catch (error) {
                        console.error('Error creating Activities Chart:', error);
                    }
                } else {
                    console.log('No activity data for chart');
                }
            }
            
            console.log('All charts initialization completed');
        }
    </script>
</x-layout>