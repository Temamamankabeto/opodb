<x-layout>
    <div class="content-header">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>
                            <i class="fas fa-briefcase mr-2 text-green"></i>
                            {{ __('Employment Opportunity Report') }}
                            <small class="text-muted">{{ __('Job opportunities, enterprise development, and employment statistics analysis') }}</small>
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
                    $jobCategoryLabels = array_keys($jobCategoryData['current'] ?? []);
                    $jobCategoryMembers = array_column($jobCategoryData['current'] ?? [], 'total_members');
                    $jobCategoryEnterprises = array_column($jobCategoryData['current'] ?? [], 'enterprises');
                    
                    $enterpriseTypeLabels = array_keys($enterpriseTypeData['current'] ?? []);
                    $enterpriseTypeMembers = array_column($enterpriseTypeData['current'] ?? [], 'total_members');
                    
                    // Gender distribution data
                    $genderLabels = [__('Male'), __('Female')];
                    $currentGenderData = [
                        $reportData['current']['male_members'] ?? 0,
                        $reportData['current']['female_members'] ?? 0
                    ];
                    $previousGenderData = [
                        $reportData['previous']['male_members'] ?? 0,
                        $reportData['previous']['female_members'] ?? 0
                    ];
                    
                    // Trend data
                    $trendLabels = [$previousYear, $selectedYear];
                    $membersTrend = [
                        $reportData['previous']['total_members'] ?? 0,
                        $reportData['current']['total_members'] ?? 0
                    ];
                    $enterprisesTrend = [
                        $reportData['previous']['enterprises'] ?? 0,
                        $reportData['current']['enterprises'] ?? 0
                    ];
                    
                    // Colors
                    $genderColors = ['rgba(54, 162, 235, 0.7)', 'rgba(255, 99, 132, 0.7)'];
                    $genderBorderColors = ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'];
                    
                    $jobColors = [
                        'rgba(40, 167, 69, 0.7)',    // Green
                        'rgba(0, 123, 255, 0.7)',     // Blue
                        'rgba(255, 193, 7, 0.7)',     // Yellow
                        'rgba(220, 53, 69, 0.7)',     // Red
                        'rgba(108, 117, 125, 0.7)',   // Gray
                        'rgba(23, 162, 184, 0.7)',    // Teal
                        'rgba(102, 16, 242, 0.7)',    // Purple
                        'rgba(253, 126, 20, 0.7)'     // Orange
                    ];
                @endphp

                <!-- Year Filter Card -->
                <div class="card card-outline card-green">
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
                                        <button type="submit" class="btn btn-green">
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
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3>{{ number_format($reportData['current']['total_members'] ?? 0) }}</h3>
                                <p>{{ __('Total Members') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                            @if($membersChange != 0)
                                <div class="small-box-footer">
                                    {{ $membersChange > 0 ? '↑' : '↓' }} 
                                    {{ number_format(abs($membersChange), 1) }}% 
                                    {{ __('from last year') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ number_format($reportData['current']['enterprises'] ?? 0) }}</h3>
                                <p>{{ __('Enterprises') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-building"></i>
                            </div>
                            @if($enterprisesChange != 0)
                                <div class="small-box-footer">
                                    {{ $enterprisesChange > 0 ? '↑' : '↓' }} 
                                    {{ number_format(abs($enterprisesChange), 1) }}% 
                                    {{ __('from last year') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-blue">
                            <div class="inner">
                                <h3>{{ number_format($reportData['current']['male_members'] ?? 0) }}</h3>
                                <p>{{ __('Male Members') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-male"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-pink">
                            <div class="inner">
                                <h3>{{ number_format($reportData['current']['female_members'] ?? 0) }}</h3>
                                <p>{{ __('Female Members') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-female"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Summary Cards -->
                <div class="row mb-4">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ number_format($reportData['current']['total_opportunities'] ?? 0) }}</h3>
                                <p>{{ __('Job Opportunities') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-briefcase"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-teal">
                            <div class="inner">
                                <h3>{{ number_format(count($jobCategoryLabels)) }}</h3>
                                <p>{{ __('Job Categories') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-tags"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-purple">
                            <div class="inner">
                                <h3>{{ number_format(count($enterpriseTypeLabels)) }}</h3>
                                <p>{{ __('Enterprise Types') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-industry"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-secondary">
                            <div class="inner">
                                <h3>{{ number_format($genderRatioCurrent, 1) }}<small>%</small></h3>
                                <p>{{ __('Female Ratio') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-percentage"></i>
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
                                    {{ __('Gender Distribution') }} ({{ $selectedYear }})
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                @if($reportData['current']['total_members'] > 0)
                                    <div class="chart">
                                        <canvas id="genderChart" height="300"></canvas>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">{{ __('No employment data available') }}</h5>
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
                                    {{ __('Job Categories by Members') }}
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                @if(count($jobCategoryLabels) > 0)
                                    <div class="chart">
                                        <canvas id="jobCategoryChart" height="300"></canvas>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">{{ __('No job category data available') }}</h5>
                                    </div>
                                @endif
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
                                    {{ __('Employment Trend') }}
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
                                    {{ __('Enterprise Types') }}
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                @if(count($enterpriseTypeLabels) > 0)
                                    <div class="chart">
                                        <canvas id="enterpriseChart" height="250"></canvas>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-industry fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">{{ __('No enterprise data available') }}</h5>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Report Table -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-table mr-1"></i>
                            {{ __('Employment Summary Report') }}
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
                                    <tr class="bg-gradient-green">
                                        <th>{{ __('Metric') }}</th>
                                        <th class="text-center">{{ $selectedYear }}</th>
                                        <th class="text-center">{{ $previousYear }}</th>
                                        <th class="text-center">{{ __('Change') }}</th>
                                        <th class="text-center">{{ __('Change %') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $metrics = [
                                            [
                                                'name' => __('Total Members'),
                                                'icon' => 'fas fa-users',
                                                'current' => $reportData['current']['total_members'] ?? 0,
                                                'previous' => $reportData['previous']['total_members'] ?? 0
                                            ],
                                            [
                                                'name' => __('Enterprises'),
                                                'icon' => 'fas fa-building',
                                                'current' => $reportData['current']['enterprises'] ?? 0,
                                                'previous' => $reportData['previous']['enterprises'] ?? 0
                                            ],
                                            [
                                                'name' => __('Male Members'),
                                                'icon' => 'fas fa-male text-blue',
                                                'current' => $reportData['current']['male_members'] ?? 0,
                                                'previous' => $reportData['previous']['male_members'] ?? 0
                                            ],
                                            [
                                                'name' => __('Female Members'),
                                                'icon' => 'fas fa-female text-pink',
                                                'current' => $reportData['current']['female_members'] ?? 0,
                                                'previous' => $reportData['previous']['female_members'] ?? 0
                                            ],
                                            [
                                                'name' => __('Job Opportunities'),
                                                'icon' => 'fas fa-briefcase',
                                                'current' => $reportData['current']['total_opportunities'] ?? 0,
                                                'previous' => $reportData['previous']['total_opportunities'] ?? 0
                                            ]
                                        ];
                                    @endphp
                                    
                                    @foreach($metrics as $metric)
                                        @php
                                            $change = $metric['current'] - $metric['previous'];
                                            $changePercent = $metric['previous'] > 0 ? round(($change / $metric['previous']) * 100, 1) : ($metric['current'] > 0 ? 100 : 0);
                                            
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
                                                <i class="{{ $metric['icon'] }} mr-2"></i>
                                                {{ $metric['name'] }}
                                            </td>
                                            <td class="text-center font-weight-bold">
                                                {{ number_format($metric['current']) }}
                                            </td>
                                            <td class="text-center text-muted">
                                                {{ number_format($metric['previous']) }}
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
                                        <th class="font-weight-bold">{{ __('GENDER RATIO') }}</th>
                                        <th class="text-center font-weight-bold">
                                            {{ number_format($genderRatioCurrent, 1) }}% {{ __('female') }}
                                        </th>
                                        <th class="text-center text-muted">
                                            {{ number_format($genderRatioPrevious, 1) }}% {{ __('female') }}
                                        </th>
                                        <th class="text-center {{ ($genderRatioCurrent - $genderRatioPrevious) > 0 ? 'text-success' : (($genderRatioCurrent - $genderRatioPrevious) < 0 ? 'text-danger' : '') }}">
                                            @if(($genderRatioCurrent - $genderRatioPrevious) > 0)
                                                <i class="fas fa-arrow-up"></i>
                                            @elseif(($genderRatioCurrent - $genderRatioPrevious) < 0)
                                                <i class="fas fa-arrow-down"></i>
                                            @endif
                                            {{ number_format(abs($genderRatioCurrent - $genderRatioPrevious), 1) }}%
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Detailed Data Tabs -->
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
                                        @if(count($detailedRecords['current']) > 0)
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover">
                                                    <thead>
                                                        <tr class="bg-light">
                                                            <th>{{ __('Job Category') }}</th>
                                                            <th>{{ __('Enterprise Type') }}</th>
                                                            <th class="text-center">{{ __('Enterprises') }}</th>
                                                            <th class="text-center">{{ __('Male') }}</th>
                                                            <th class="text-center">{{ __('Female') }}</th>
                                                            <th class="text-center">{{ __('Total') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($detailedRecords['current'] as $record)
                                                            <tr>
                                                                <td>
                                                                    <i class="fas fa-briefcase text-green mr-1"></i>
                                                                    {{ $record['job_name'] }}
                                                                </td>
                                                                <td>
                                                                    <i class="fas fa-building text-info mr-1"></i>
                                                                    {{ $record['enterprise_name'] }}
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-info">{{ number_format($record['enterprises']) }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-blue">{{ number_format($record['male_members']) }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-pink">{{ number_format($record['female_members']) }}</span>
                                                                </td>
                                                                <td class="text-center font-weight-bold">
                                                                    {{ number_format($record['total_members']) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot class="table-secondary">
                                                        <tr>
                                                            <td colspan="2" class="text-right"><strong>{{ __('Total:') }}</strong></td>
                                                            <td class="text-center"><strong>{{ number_format($reportData['current']['enterprises'] ?? 0) }}</strong></td>
                                                            <td class="text-center"><strong>{{ number_format($reportData['current']['male_members'] ?? 0) }}</strong></td>
                                                            <td class="text-center"><strong>{{ number_format($reportData['current']['female_members'] ?? 0) }}</strong></td>
                                                            <td class="text-center"><strong>{{ number_format($reportData['current']['total_members'] ?? 0) }}</strong></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-center py-5">
                                                <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">{{ __('No employment data available for') }} {{ $selectedYear }}</h5>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Previous Year Tab -->
                                    <div class="tab-pane fade" id="previous" role="tabpanel" aria-labelledby="previous-tab">
                                        @if(count($detailedRecords['previous']) > 0)
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover">
                                                    <thead>
                                                        <tr class="bg-light">
                                                            <th>{{ __('Job Category') }}</th>
                                                            <th>{{ __('Enterprise Type') }}</th>
                                                            <th class="text-center">{{ __('Enterprises') }}</th>
                                                            <th class="text-center">{{ __('Male') }}</th>
                                                            <th class="text-center">{{ __('Female') }}</th>
                                                            <th class="text-center">{{ __('Total') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($detailedRecords['previous'] as $record)
                                                            <tr>
                                                                <td>
                                                                    <i class="fas fa-briefcase text-green mr-1"></i>
                                                                    {{ $record['job_name'] }}
                                                                </td>
                                                                <td>
                                                                    <i class="fas fa-building text-info mr-1"></i>
                                                                    {{ $record['enterprise_name'] }}
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-info">{{ number_format($record['enterprises']) }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-blue">{{ number_format($record['male_members']) }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-pink">{{ number_format($record['female_members']) }}</span>
                                                                </td>
                                                                <td class="text-center font-weight-bold">
                                                                    {{ number_format($record['total_members']) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot class="table-secondary">
                                                        <tr>
                                                            <td colspan="2" class="text-right"><strong>{{ __('Total:') }}</strong></td>
                                                            <td class="text-center"><strong>{{ number_format($reportData['previous']['enterprises'] ?? 0) }}</strong></td>
                                                            <td class="text-center"><strong>{{ number_format($reportData['previous']['male_members'] ?? 0) }}</strong></td>
                                                            <td class="text-center"><strong>{{ number_format($reportData['previous']['female_members'] ?? 0) }}</strong></td>
                                                            <td class="text-center"><strong>{{ number_format($reportData['previous']['total_members'] ?? 0) }}</strong></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-center py-5">
                                                <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">{{ __('No employment data available for') }} {{ $previousYear }}</h5>
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
        .bg-green {
            background-color: #28a745 !important;
        }
        .bg-blue {
            background-color: #007bff !important;
        }
        .bg-pink {
            background-color: #e83e8c !important;
        }
        .bg-gradient-green {
            background: linear-gradient(45deg, #28a745, #20c997) !important;
            color: white;
        }
        .btn-green {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }
        .btn-green:hover {
            background-color: #218838;
            border-color: #218838;
            color: white;
        }
        .card-outline.card-green {
            border-top-color: #28a745;
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
        .table td, .table th {
            vertical-align: middle;
        }
        .badge.bg-blue {
            background-color: #007bff !important;
        }
        .badge.bg-pink {
            background-color: #e83e8c !important;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Document ready, initializing charts...');
            
            // Initialize charts after a short delay to ensure DOM is fully loaded
            setTimeout(initializeCharts, 100);
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
            
            // Gender Chart
            const genderCtx = document.getElementById('genderChart');
            if (genderCtx && {{ $reportData['current']['total_members'] ?? 0 }} > 0) {
                console.log('Found genderChart canvas');
                
                try {
                    if (genderCtx.chart) {
                        genderCtx.chart.destroy();
                    }
                    
                    genderCtx.chart = new Chart(genderCtx, {
                        type: 'doughnut',
                        data: {
                            labels: @json($genderLabels),
                            datasets: [{
                                data: @json($currentGenderData),
                                backgroundColor: @json($genderColors),
                                borderColor: @json($genderBorderColors),
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
                    console.log('Gender Chart created successfully');
                } catch (error) {
                    console.error('Error creating Gender Chart:', error);
                }
            }

            // Job Category Chart
            const jobCategoryCtx = document.getElementById('jobCategoryChart');
            if (jobCategoryCtx && @json(count($jobCategoryLabels)) > 0) {
                console.log('Found jobCategoryChart canvas');
                try {
                    if (jobCategoryCtx.chart) {
                        jobCategoryCtx.chart.destroy();
                    }
                    
                    // Limit to top 8 categories for readability
                    const maxCategories = 8;
                    const limitedLabels = @json($jobCategoryLabels).slice(0, maxCategories);
                    const limitedData = @json($jobCategoryMembers).slice(0, maxCategories);
                    const limitedColors = @json($jobColors).slice(0, maxCategories);
                    
                    jobCategoryCtx.chart = new Chart(jobCategoryCtx, {
                        type: 'bar',
                        data: {
                            labels: limitedLabels,
                            datasets: [{
                                label: '{{ __("Number of Members") }}',
                                data: limitedData,
                                backgroundColor: limitedColors,
                                borderColor: limitedColors.map(color => color.replace('0.7', '1')),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false,
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: '{{ __("Number of Members") }}'
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
                    console.log('Job Category Chart created successfully');
                } catch (error) {
                    console.error('Error creating Job Category Chart:', error);
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
                            datasets: [
                                {
                                    label: '{{ __("Total Members") }}',
                                    data: @json($membersTrend),
                                    borderColor: 'rgba(40, 167, 69, 1)',
                                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                                    borderWidth: 3,
                                    tension: 0.3,
                                    fill: true,
                                    pointBackgroundColor: 'rgba(40, 167, 69, 1)',
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 2,
                                    pointRadius: 6,
                                    yAxisID: 'y'
                                },
                                {
                                    label: '{{ __("Enterprises") }}',
                                    data: @json($enterprisesTrend),
                                    borderColor: 'rgba(0, 123, 255, 1)',
                                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                                    borderWidth: 3,
                                    tension: 0.3,
                                    fill: true,
                                    pointBackgroundColor: 'rgba(0, 123, 255, 1)',
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 2,
                                    pointRadius: 6,
                                    yAxisID: 'y1'
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            stacked: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                }
                            },
                            scales: {
                                y: {
                                    type: 'linear',
                                    display: true,
                                    position: 'left',
                                    title: {
                                        display: true,
                                        text: '{{ __("Total Members") }}'
                                    }
                                },
                                y1: {
                                    type: 'linear',
                                    display: true,
                                    position: 'right',
                                    title: {
                                        display: true,
                                        text: '{{ __("Enterprises") }}'
                                    },
                                    grid: {
                                        drawOnChartArea: false,
                                    },
                                }
                            }
                        }
                    });
                    console.log('Trend Chart created successfully');
                } catch (error) {
                    console.error('Error creating Trend Chart:', error);
                }
            }

            // Enterprise Chart
            const enterpriseCtx = document.getElementById('enterpriseChart');
            if (enterpriseCtx && @json(count($enterpriseTypeLabels)) > 0) {
                console.log('Found enterpriseChart canvas');
                try {
                    if (enterpriseCtx.chart) {
                        enterpriseCtx.chart.destroy();
                    }
                    
                    enterpriseCtx.chart = new Chart(enterpriseCtx, {
                        type: 'bar',
                        data: {
                            labels: @json($enterpriseTypeLabels),
                            datasets: [{
                                label: '{{ __("Number of Members") }}',
                                data: @json($enterpriseTypeMembers),
                                backgroundColor: 'rgba(23, 162, 184, 0.7)',
                                borderColor: 'rgba(23, 162, 184, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false,
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: '{{ __("Number of Members") }}'
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
                    console.log('Enterprise Chart created successfully');
                } catch (error) {
                    console.error('Error creating Enterprise Chart:', error);
                }
            }
            
            console.log('All charts initialization completed');
        }
    </script>
</x-layout>