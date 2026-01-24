<x-layout>
    <div class="content-header">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>
                            <i class="fas fa-briefcase mr-2 text-green"></i>
                            {{ __('Employment Opportunity Report') }} - {{ $reportData['woreda_name'] }}
                            @if($selectedKebeleId)
                                <small class="text-info">({{ $reportData['selected_kebele_name'] }})</small>
                            @endif
                            <small class="text-muted">{{ __('Woreda-level employment statistics and analysis') }}</small>
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">{{ __('Dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('Employment Report') }}</li>
                        </ol>
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
                    
                    // Kebele data for chart
                    $kebeleLabels = array_keys($reportData['kebele_summary'] ?? []);
                    $kebeleMembers = array_map(function($kebele) {
                        return $kebele['current']['total_members'] ?? 0;
                    }, $reportData['kebele_summary'] ?? []);
                    $kebeleEnterprises = array_map(function($kebele) {
                        return $kebele['current']['enterprises'] ?? 0;
                    }, $reportData['kebele_summary'] ?? []);
                    
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

                <!-- Woreda Info Card -->
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-1"></i>
                            {{ __('Woreda Information') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <dl class="row">
                                    <dt class="col-sm-4">{{ __('Woreda Name') }}:</dt>
                                    <dd class="col-sm-8 font-weight-bold">{{ $reportData['woreda_name'] }}</dd>
                                    
                                    <dt class="col-sm-4">{{ __('Total Kebeles') }}:</dt>
                                    <dd class="col-sm-8">{{ $reportData['kebeles_count'] }} {{ __('Kebeles') }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="row">
                                    <dt class="col-sm-4">{{ __('Report Year') }}:</dt>
                                    <dd class="col-sm-8">{{ $selectedYear }}</dd>
                                    
                                    <dt class="col-sm-4">{{ __('Previous Year') }}:</dt>
                                    <dd class="col-sm-8">{{ $previousYear }}</dd>
                                </dl>
                            </div>
                        </div>
                        @if($selectedKebeleId)
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-filter mr-1"></i>
                                        <strong>{{ __('Filter Applied:') }}</strong>
                                        {{ __('Showing data for') }} <strong>{{ $reportData['selected_kebele_name'] }}</strong>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

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
                                    <div class="form-group">
                                        <label>{{ __('Kebele') }}</label>
                                        <select name="kebele_id" class="form-control">
                                            <option value="">{{ __('All Kebeles') }}</option>
                                            @foreach($kebelesInWoreda as $kebele)
                                                <option value="{{ $kebele->id }}" {{ $selectedKebeleId == $kebele->id ? 'selected' : '' }}>
                                                    {{ $kebele->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group" style="margin-top: 32px;">
                                        <button type="submit" class="btn btn-green">
                                            <i class="fas fa-filter mr-1"></i> {{ __('Apply Filter') }}
                                        </button>
                                        <a href="{{ url()->current() }}" class="btn btn-default">
                                            <i class="fas fa-redo mr-1"></i> {{ __('Reset') }}
                                        </a>
                                        @if($selectedKebeleId)
                                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#feedbackModal">
                                                <i class="fas fa-comment-alt mr-1"></i> {{ __('Give General Feedback') }}
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Summary Cards with Feedback Indicators -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3>{{ number_format($reportData['current']['total_members'] ?? 0) }}</h3>
                                <p>{{ __('Total Members') }}</p>
                                @if($selectedKebeleId)
                                    <small class="text-light">
                                        <i class="far fa-comment-dots mr-1"></i>
                                        {{ count($reportData['feedback_items']) }} {{ __('feedbacks') }}
                                    </small>
                                @endif
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
                                @if($selectedKebeleId)
                                    <small class="text-light">
                                        <i class="fas fa-building mr-1"></i>
                                        {{ array_sum(array_column($enterpriseTypeData['current'], 'feedback_count')) ?? 0 }} {{ __('with feedback') }}
                                    </small>
                                @endif
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

                <!-- Feedback Overview Card -->
                @if($selectedKebeleId && count($reportData['feedback_items']) > 0)
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-comments mr-1"></i>
                            {{ __('Feedback Overview') }} - {{ $reportData['selected_kebele_name'] }}
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-warning">{{ count($reportData['feedback_items']) }} {{ __('feedbacks') }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-icon bg-warning"><i class="fas fa-star"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ __('Average Rating') }}</span>
                                        <span class="info-box-number">
                                            @php
                                                $totalRating = 0;
                                                $ratingCount = 0;
                                                foreach($reportData['feedback_items'] as $item) {
                                                    if(isset($item['feedback']['rating'])) {
                                                        $totalRating += $item['feedback']['rating'];
                                                        $ratingCount++;
                                                    }
                                                }
                                                $averageRating = $ratingCount > 0 ? $totalRating / $ratingCount : 0;
                                            @endphp
                                            {{ number_format($averageRating, 1) }}/5
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= round($averageRating) ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-circle"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ __('High Priority Feedbacks') }}</span>
                                        <span class="info-box-number">
                                            @php
                                                $highPriorityCount = 0;
                                                foreach($reportData['feedback_items'] as $item) {
                                                    if(isset($item['feedback']['priority']) && $item['feedback']['priority'] == 'high') {
                                                        $highPriorityCount++;
                                                    }
                                                }
                                            @endphp
                                            {{ $highPriorityCount }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

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

                <!-- Kebele Performance Charts -->
                @if(count($kebeleLabels) > 0)
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-bar mr-1"></i>
                                    {{ __('Kebele Performance - Members') }}
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="kebeleMembersChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-bar mr-1"></i>
                                    {{ __('Kebele Performance - Enterprises') }}
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="kebeleEnterprisesChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

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

                <!-- Kebele Performance Table -->
                @if(count($kebeleLabels) > 0)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-bar mr-1"></i>
                            {{ __('Kebele Performance Summary') }} ({{ $selectedYear }})
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr class="bg-light">
                                        <th>{{ __('Kebele') }}</th>
                                        <th class="text-center">{{ __('Enterprises') }}</th>
                                        <th class="text-center">{{ __('Male') }}</th>
                                        <th class="text-center">{{ __('Female') }}</th>
                                        <th class="text-center">{{ __('Total Members') }}</th>
                                        <th class="text-center">{{ __('Opportunities') }}</th>
                                        <th class="text-center">{{ __('Feedback') }}</th>
                                        <th class="text-center">{{ __('Performance') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reportData['kebele_summary'] as $kebeleName => $data)
                                        @php
                                            $kebeleTotal = $data['current']['total_members'] ?? 0;
                                            $kebelePercentage = $reportData['current']['total_members'] > 0 ? 
                                                ($kebeleTotal / $reportData['current']['total_members']) * 100 : 0;
                                            
                                            // Performance rating based on percentage
                                            if ($kebelePercentage >= 20) {
                                                $performanceClass = 'success';
                                                $performanceText = __('Excellent');
                                            } elseif ($kebelePercentage >= 10) {
                                                $performanceClass = 'info';
                                                $performanceText = __('Good');
                                            } elseif ($kebelePercentage >= 5) {
                                                $performanceClass = 'warning';
                                                $performanceText = __('Average');
                                            } else {
                                                $performanceClass = 'secondary';
                                                $performanceText = __('Needs Improvement');
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                                {{ $kebeleName }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info">{{ number_format($data['current']['enterprises'] ?? 0) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-blue">{{ number_format($data['current']['male_members'] ?? 0) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-pink">{{ number_format($data['current']['female_members'] ?? 0) }}</span>
                                            </td>
                                            <td class="text-center font-weight-bold">
                                                {{ number_format($kebeleTotal) }}
                                                <small class="text-muted">({{ number_format($kebelePercentage, 1) }}%)</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning">{{ number_format($data['current']['opportunities'] ?? 0) }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if($data['current']['feedback_count'] > 0)
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-comment"></i> {{ $data['current']['feedback_count'] }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="far fa-comment"></i> 0
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $performanceClass }}">{{ $performanceText }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-secondary">
                                    <tr>
                                        <td class="font-weight-bold">{{ __('Total') }}</td>
                                        <td class="text-center font-weight-bold">{{ number_format($reportData['current']['enterprises'] ?? 0) }}</td>
                                        <td class="text-center font-weight-bold">{{ number_format($reportData['current']['male_members'] ?? 0) }}</td>
                                        <td class="text-center font-weight-bold">{{ number_format($reportData['current']['female_members'] ?? 0) }}</td>
                                        <td class="text-center font-weight-bold">{{ number_format($reportData['current']['total_members'] ?? 0) }}</td>
                                        <td class="text-center font-weight-bold">{{ number_format($reportData['current']['total_opportunities'] ?? 0) }}</td>
                                        <td class="text-center font-weight-bold">{{ array_sum(array_column($reportData['kebele_summary'], 'current.feedback_count')) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Detailed Data Tabs with Feedback Options -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header p-0">
                                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="current-tab" data-toggle="pill" href="#current" role="tab" aria-controls="current" aria-selected="true">
                                            <i class="fas fa-calendar-check mr-1"></i> {{ $selectedYear }}
                                            @if(count($detailedRecords['current']) > 0)
                                                <span class="badge badge-success ml-1">{{ count($detailedRecords['current']) }}</span>
                                            @endif
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="previous-tab" data-toggle="pill" href="#previous" role="tab" aria-controls="previous" aria-selected="false">
                                            <i class="fas fa-calendar-alt mr-1"></i> {{ $previousYear }}
                                            @if(count($detailedRecords['previous']) > 0)
                                                <span class="badge badge-secondary ml-1">{{ count($detailedRecords['previous']) }}</span>
                                            @endif
                                        </a>
                                    </li>
                                    @if($selectedKebeleId && count($reportData['feedback_items']) > 0)
                                    <li class="nav-item">
                                        <a class="nav-link" id="feedback-tab" data-toggle="pill" href="#feedback" role="tab" aria-controls="feedback" aria-selected="false">
                                            <i class="fas fa-comments mr-1"></i> {{ __('Feedbacks') }}
                                            <span class="badge badge-warning ml-1">{{ count($reportData['feedback_items']) }}</span>
                                        </a>
                                    </li>
                                    @endif
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
                                                            <th>{{ __('Kebele') }}</th>
                                                            <th>{{ __('Job Category') }}</th>
                                                            <th>{{ __('Enterprise Type') }}</th>
                                                            <th class="text-center">{{ __('Enterprises') }}</th>
                                                            <th class="text-center">{{ __('Male') }}</th>
                                                            <th class="text-center">{{ __('Female') }}</th>
                                                            <th class="text-center">{{ __('Total') }}</th>
                                                            <th class="text-center">{{ __('Actions') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($detailedRecords['current'] as $record)
                                                            @php
                                                                $hasFeedback = isset($reportData['feedback_by_plan_id'][$record['plan_id']]);
                                                                $feedbackData = $hasFeedback ? $reportData['feedback_by_plan_id'][$record['plan_id']] : null;
                                                            @endphp
                                                            <tr class="{{ $hasFeedback ? 'table-warning' : '' }}">
                                                                <td>
                                                                    <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                                                    {{ $record['kebele_name'] }}
                                                                </td>
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
                                                                <td class="text-center">
                                                                    @if($hasFeedback)
                                                                        <button type="button" class="btn btn-xs btn-warning" 
                                                                                data-toggle="modal" 
                                                                                data-target="#viewFeedbackModal{{ $record['plan_id'] }}">
                                                                            <i class="fas fa-eye"></i> {{ __('View Feedback') }}
                                                                        </button>
                                                                    @else
                                                                        <button type="button" class="btn btn-xs btn-outline-warning" 
                                                                                data-toggle="modal" 
                                                                                data-target="#giveFeedbackModal{{ $record['plan_id'] }}">
                                                                            <i class="fas fa-comment-alt"></i> {{ __('Give Feedback') }}
                                                                        </button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot class="table-secondary">
                                                        <tr>
                                                            <td colspan="3" class="text-right"><strong>{{ __('Total:') }}</strong></td>
                                                            <td class="text-center"><strong>{{ number_format($reportData['current']['enterprises'] ?? 0) }}</strong></td>
                                                            <td class="text-center"><strong>{{ number_format($reportData['current']['male_members'] ?? 0) }}</strong></td>
                                                            <td class="text-center"><strong>{{ number_format($reportData['current']['female_members'] ?? 0) }}</strong></td>
                                                            <td class="text-center"><strong>{{ number_format($reportData['current']['total_members'] ?? 0) }}</strong></td>
                                                            <td></td>
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
                                                            <th>{{ __('Kebele') }}</th>
                                                            <th>{{ __('Job Category') }}</th>
                                                            <th>{{ __('Enterprise Type') }}</th>
                                                            <th class="text-center">{{ __('Enterprises') }}</th>
                                                            <th class="text-center">{{ __('Male') }}</th>
                                                            <th class="text-center">{{ __('Female') }}</th>
                                                            <th class="text-center">{{ __('Total') }}</th>
                                                            <th class="text-center">{{ __('Actions') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($detailedRecords['previous'] as $record)
                                                            @php
                                                                $hasFeedback = isset($reportData['feedback_by_plan_id'][$record['plan_id']]);
                                                                $feedbackData = $hasFeedback ? $reportData['feedback_by_plan_id'][$record['plan_id']] : null;
                                                            @endphp
                                                            <tr class="{{ $hasFeedback ? 'table-warning' : '' }}">
                                                                <td>
                                                                    <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                                                    {{ $record['kebele_name'] }}
                                                                </td>
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
                                                                <td class="text-center">
                                                                    @if($hasFeedback)
                                                                        <button type="button" class="btn btn-xs btn-warning" 
                                                                                data-toggle="modal" 
                                                                                data-target="#viewFeedbackModal{{ $record['plan_id'] }}">
                                                                            <i class="fas fa-eye"></i> {{ __('View Feedback') }}
                                                                        </button>
                                                                    @else
                                                                        <button type="button" class="btn btn-xs btn-outline-warning" 
                                                                                data-toggle="modal" 
                                                                                data-target="#giveFeedbackModal{{ $record['plan_id'] }}">
                                                                            <i class="fas fa-comment-alt"></i> {{ __('Give Feedback') }}
                                                                        </button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot class="table-secondary">
                                                        <tr>
                                                            <td colspan="3" class="text-right"><strong>{{ __('Total:') }}</strong></td>
                                                            <td class="text-center"><strong>{{ number_format($reportData['previous']['enterprises'] ?? 0) }}</strong></td>
                                                            <td class="text-center"><strong>{{ number_format($reportData['previous']['male_members'] ?? 0) }}</strong></td>
                                                            <td class="text-center"><strong>{{ number_format($reportData['previous']['female_members'] ?? 0) }}</strong></td>
                                                            <td class="text-center"><strong>{{ number_format($reportData['previous']['total_members'] ?? 0) }}</strong></td>
                                                            <td></td>
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
                                    
                                    <!-- Feedback Tab -->
                                    @if($selectedKebeleId && count($reportData['feedback_items']) > 0)
                                    <div class="tab-pane fade" id="feedback" role="tabpanel" aria-labelledby="feedback-tab">
                                        <div class="timeline">
                                            @foreach($reportData['feedback_items'] as $feedbackItem)
                                                @php
                                                    $feedback = $feedbackItem['feedback'];
                                                    $priorityClass = $feedback['priority'] == 'high' ? 'danger' : 
                                                                     ($feedback['priority'] == 'medium' ? 'warning' : 'info');
                                                @endphp
                                                <div class="time-label mb-3">
                                                    <span class="bg-{{ $priorityClass }}">
                                                        {{ \Carbon\Carbon::parse($feedback['given_at'])->format('M d, Y') }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <i class="fas fa-comment bg-blue"></i>
                                                    <div class="timeline-item">
                                                        <span class="time">
                                                            <i class="fas fa-clock"></i> 
                                                            {{ \Carbon\Carbon::parse($feedback['given_at'])->format('h:i A') }}
                                                        </span>
                                                        <h3 class="timeline-header">
                                                            <strong>{{ $feedback['title'] }}</strong>
                                                            <span class="badge badge-{{ $priorityClass }}">
                                                                {{ ucfirst($feedback['priority']) }} {{ __('Priority') }}
                                                            </span>
                                                            <span class="badge badge-secondary">
                                                                {{ ucfirst($feedback['feedback_type']) }}
                                                            </span>
                                                            <small class="text-muted ml-2">
                                                                <i class="fas fa-user"></i> {{ $feedback['woreda_admin_name'] }}
                                                            </small>
                                                        </h3>
                                                        <div class="timeline-body">
                                                            <strong>{{ __('Description:') }}</strong>
                                                            <p>{{ $feedback['description'] }}</p>
                                                            
                                                            @if(!empty($feedback['action_items']))
                                                                <div class="mt-2">
                                                                    <strong>{{ __('Action Items:') }}</strong>
                                                                    <p>{{ $feedback['action_items'] }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="timeline-footer">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <span class="text-muted">
                                                                        <i class="fas fa-star text-warning"></i> 
                                                                        {{ __('Rating:') }} {{ $feedback['rating'] }}/5
                                                                        @for($i = 1; $i <= 5; $i++)
                                                                            <i class="fas fa-star {{ $i <= $feedback['rating'] ? 'text-warning' : 'text-muted' }}"></i>
                                                                        @endfor
                                                                    </span>
                                                                </div>
                                                                <div class="col-md-6 text-right">
                                                                    @if($feedback['follow_up_required'])
                                                                        <span class="badge badge-warning">
                                                                            <i class="fas fa-exclamation-circle"></i> {{ __('Follow-up Required') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feedback Modals -->
                @foreach($detailedRecords['current'] as $record)
                    @if(isset($reportData['feedback_by_plan_id'][$record['plan_id']]))
                        <!-- View Feedback Modal -->
                        <div class="modal fade" id="viewFeedbackModal{{ $record['plan_id'] }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning">
                                        <h5 class="modal-title">
                                            <i class="fas fa-eye mr-1"></i>
                                            {{ __('View Feedback') }}
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        @php
                                            $feedback = $reportData['feedback_by_plan_id'][$record['plan_id']];
                                            $priorityClass = $feedback['priority'] == 'high' ? 'danger' : 
                                                             ($feedback['priority'] == 'medium' ? 'warning' : 'info');
                                        @endphp
                                        <div class="alert alert-{{ $priorityClass }}">
                                            <h5><strong>{{ $feedback['title'] }}</strong></h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong>{{ __('Type:') }}</strong> {{ ucfirst($feedback['feedback_type']) }}
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>{{ __('Priority:') }}</strong> 
                                                    <span class="badge badge-{{ $priorityClass }}">{{ ucfirst($feedback['priority']) }}</span>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>{{ __('Given by:') }}</strong> {{ $feedback['woreda_admin_name'] }}
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>{{ __('Date:') }}</strong> {{ \Carbon\Carbon::parse($feedback['given_at'])->format('M d, Y h:i A') }}
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>{{ __('Description') }}</label>
                                            <div class="border p-3 bg-light rounded">
                                                {{ $feedback['description'] }}
                                            </div>
                                        </div>
                                        
                                        @if(!empty($feedback['action_items']))
                                            <div class="form-group">
                                                <label>{{ __('Action Items') }}</label>
                                                <div class="border p-3 bg-light rounded">
                                                    {{ $feedback['action_items'] }}
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ __('Rating') }}</label>
                                                    <div>
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star fa-2x {{ $i <= $feedback['rating'] ? 'text-warning' : 'text-muted' }}"></i>
                                                        @endfor
                                                        <span class="ml-2 font-weight-bold">{{ $feedback['rating'] }}/5</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ __('Follow-up Required') }}</label>
                                                    <div class="mt-2">
                                                        @if($feedback['follow_up_required'])
                                                            <span class="badge badge-warning p-2">
                                                                <i class="fas fa-exclamation-circle mr-1"></i> {{ __('Yes') }}
                                                            </span>
                                                        @else
                                                            <span class="badge badge-secondary p-2">
                                                                <i class="fas fa-check mr-1"></i> {{ __('No') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                        <button type="button" class="btn btn-warning" data-dismiss="modal" 
                                                data-toggle="modal" 
                                                data-target="#giveFeedbackModal{{ $record['plan_id'] }}">
                                            <i class="fas fa-edit mr-1"></i> {{ __('Update Feedback') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Give Feedback Modal -->
                    <div class="modal fade" id="giveFeedbackModal{{ $record['plan_id'] }}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-warning">
                                    <h5 class="modal-title">
                                        <i class="fas fa-comment-alt mr-1"></i>
                                        {{ __('Give Feedback') }}
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('woreda.feedback.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            {{ __('Providing feedback for:') }} 
                                            <strong>{{ $record['kebele_name'] }}</strong> - 
                                            {{ $record['job_name'] }} / {{ $record['enterprise_name'] }} - 
                                            {{ $selectedYear }}
                                        </div>
                                        
                                        <input type="hidden" name="plan_id" value="{{ $record['plan_id'] }}">
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ __('Feedback Type') }} <span class="text-danger">*</span></label>
                                                    <select name="feedback_type" class="form-control" required>
                                                        <option value="">{{ __('Select feedback type') }}</option>
                                                        <option value="performance" {{ isset($feedback) && $feedback['feedback_type'] == 'performance' ? 'selected' : '' }}>
                                                            {{ __('Performance Feedback') }}
                                                        </option>
                                                        <option value="suggestion" {{ isset($feedback) && $feedback['feedback_type'] == 'suggestion' ? 'selected' : '' }}>
                                                            {{ __('Suggestion') }}
                                                        </option>
                                                        <option value="concern" {{ isset($feedback) && $feedback['feedback_type'] == 'concern' ? 'selected' : '' }}>
                                                            {{ __('Concern') }}
                                                        </option>
                                                        <option value="improvement" {{ isset($feedback) && $feedback['feedback_type'] == 'improvement' ? 'selected' : '' }}>
                                                            {{ __('Improvement Idea') }}
                                                        </option>
                                                        <option value="other" {{ isset($feedback) && $feedback['feedback_type'] == 'other' ? 'selected' : '' }}>
                                                            {{ __('Other') }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ __('Priority Level') }} <span class="text-danger">*</span></label>
                                                    <select name="priority" class="form-control" required>
                                                        <option value="">{{ __('Select priority') }}</option>
                                                        <option value="low" {{ isset($feedback) && $feedback['priority'] == 'low' ? 'selected' : '' }}>
                                                            {{ __('Low') }}
                                                        </option>
                                                        <option value="medium" {{ isset($feedback) && $feedback['priority'] == 'medium' ? 'selected' : '' }}>
                                                            {{ __('Medium') }}
                                                        </option>
                                                        <option value="high" {{ isset($feedback) && $feedback['priority'] == 'high' ? 'selected' : '' }}>
                                                            {{ __('High') }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>{{ __('Feedback Title') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="title" class="form-control" 
                                                   value="{{ isset($feedback) ? $feedback['title'] : '' }}"
                                                   placeholder="{{ __('Enter feedback title') }}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>{{ __('Detailed Feedback') }} <span class="text-danger">*</span></label>
                                            <textarea name="description" class="form-control" rows="6" required>{{ isset($feedback) ? $feedback['description'] : '' }}</textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>{{ __('Action Items/Recommendations') }}</label>
                                            <textarea name="action_items" class="form-control" rows="3">{{ isset($feedback) ? $feedback['action_items'] : '' }}</textarea>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ __('Rating') }} <span class="text-danger">*</span></label>
                                                    <div class="rating-stars">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="rating" 
                                                                       id="rating{{ $record['plan_id'] }}{{ $i }}" 
                                                                       value="{{ $i }}" 
                                                                       {{ (isset($feedback) && $feedback['rating'] == $i) ? 'checked' : ($i == 3 ? 'checked' : '') }}>
                                                                <label class="form-check-label" for="rating{{ $record['plan_id'] }}{{ $i }}">
                                                                    @for($j = 1; $j <= $i; $j++)
                                                                        <i class="fas fa-star text-warning"></i>
                                                                    @endfor
                                                                </label>
                                                            </div>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ __('Follow-up Required') }}</label>
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox" name="follow_up_required" 
                                                               value="1" id="followUpRequired{{ $record['plan_id'] }}"
                                                               {{ isset($feedback) && $feedback['follow_up_required'] ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="followUpRequired{{ $record['plan_id'] }}">
                                                            {{ __('Requires follow-up action') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-paper-plane mr-1"></i> 
                                            {{ isset($feedback) ? __('Update Feedback') : __('Submit Feedback') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- General Feedback Modal -->
                @if($selectedKebeleId)
                <div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-warning">
                                <h5 class="modal-title">
                                    <i class="fas fa-comment-alt mr-1"></i>
                                    {{ __('General Feedback for Kebele') }}
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('woreda.general.feedback.store') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        {{ __('Providing general feedback for:') }} 
                                        <strong>{{ $reportData['selected_kebele_name'] }}</strong> - {{ $selectedYear }}
                                    </div>
                                    
                                    <input type="hidden" name="kebele_id" value="{{ $selectedKebeleId }}">
                                    <input type="hidden" name="year" value="{{ $selectedYear }}">
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('Feedback Type') }} <span class="text-danger">*</span></label>
                                                <select name="feedback_type" class="form-control" required>
                                                    <option value="">{{ __('Select feedback type') }}</option>
                                                    <option value="overall_performance">{{ __('Overall Performance') }}</option>
                                                    <option value="data_quality">{{ __('Data Quality') }}</option>
                                                    <option value="timeliness">{{ __('Timeliness') }}</option>
                                                    <option value="compliance">{{ __('Compliance') }}</option>
                                                    <option value="other">{{ __('Other') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('Priority Level') }} <span class="text-danger">*</span></label>
                                                <select name="priority" class="form-control" required>
                                                    <option value="">{{ __('Select priority') }}</option>
                                                    <option value="low">{{ __('Low') }}</option>
                                                    <option value="medium">{{ __('Medium') }}</option>
                                                    <option value="high">{{ __('High') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>{{ __('Feedback Title') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" placeholder="{{ __('Enter feedback title') }}" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>{{ __('Detailed Feedback') }} <span class="text-danger">*</span></label>
                                        <textarea name="description" class="form-control" rows="6" placeholder="{{ __('Provide detailed feedback about this kebele\'s overall performance...') }}" required></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>{{ __('Action Items/Recommendations') }}</label>
                                        <textarea name="action_items" class="form-control" rows="3" placeholder="{{ __('Specific action items or recommendations...') }}"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-paper-plane mr-1"></i> {{ __('Submit General Feedback') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
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
        .rating-stars .form-check {
            margin-right: 10px;
        }
        .rating-stars .fa-star {
            font-size: 1.2rem;
        }
        .timeline {
            position: relative;
            margin: 0 0 30px 0;
            padding: 0;
            list-style: none;
        }
        .timeline:before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #dee2e6;
            left: 31px;
            margin: 0;
            border-radius: 2px;
        }
        .timeline > div {
            position: relative;
            margin-right: 10px;
            margin-bottom: 15px;
        }
        .timeline > div:before,
        .timeline > div:after {
            content: "";
            display: table;
        }
        .timeline > div:after {
            clear: both;
        }
        .timeline .time-label {
            margin: 0 0 15px 0;
            padding: 0;
            font-size: 0.9rem;
            font-weight: bold;
        }
        .timeline .time-label > span {
            padding: 5px 10px;
            border-radius: 4px;
            color: #fff;
        }
        .timeline > div > .fa {
            position: absolute;
            left: 18px;
            top: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            text-align: center;
            line-height: 30px;
            color: #fff;
        }
        .timeline .timeline-item {
            margin-left: 60px;
            margin-right: 15px;
            padding: 0;
            position: relative;
            background: #fff;
            border-radius: 0.25rem;
            border: 1px solid #dee2e6;
        }
        .timeline .timeline-header {
            margin: 0;
            color: #495057;
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
            font-size: 1rem;
            line-height: 1.5;
        }
        .timeline .timeline-body,
        .timeline .timeline-footer {
            padding: 10px;
        }
        .timeline .time {
            float: right;
            color: #999;
            font-size: 0.875rem;
        }
        .timeline-footer {
            border-top: 1px solid #dee2e6;
        }
        .info-box {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            border-radius: .25rem;
            background: #fff;
            display: flex;
            margin-bottom: 1rem;
            min-height: 80px;
            padding: .5rem;
            position: relative;
        }
        .info-box .info-box-icon {
            border-radius: .25rem;
            align-items: center;
            display: flex;
            font-size: 1.875rem;
            justify-content: center;
            text-align: center;
            width: 70px;
        }
        .info-box .info-box-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            line-height: 1.8;
            flex: 1;
            padding: 0 10px;
        }
        .info-box .info-box-text {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .info-box .info-box-number {
            display: block;
            font-weight: 700;
        }
        .table-warning {
            background-color: rgba(255, 193, 7, 0.1) !important;
        }
    </style>

    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Document ready, initializing charts...');
            
            // Initialize charts after a short delay to ensure DOM is fully loaded
            setTimeout(initializeCharts, 100);
            
            // Initialize rating stars interaction
            const ratingInputs = document.querySelectorAll('input[name="rating"]');
            ratingInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const ratingValue = this.value;
                    console.log('Selected rating:', ratingValue);
                });
            });
            
            // Auto-open feedback modal if URL has feedback parameter
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('feedback') === 'true') {
                $('#feedbackModal').modal('show');
            }
            
            // Set up tab switching with URL parameters
            $('a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
                const tabId = $(e.target).attr('href').substring(1);
                if(tabId !== 'current') {
                    urlParams.set('tab', tabId);
                    window.history.replaceState({}, '', `${location.pathname}?${urlParams}`);
                }
            });
            
            // Check for tab parameter in URL
            const tabParam = urlParams.get('tab');
            if(tabParam) {
                $(`a[href="#${tabParam}"]`).tab('show');
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

            // Kebele Members Chart
            const kebeleMembersCtx = document.getElementById('kebeleMembersChart');
            if (kebeleMembersCtx && @json(count($kebeleLabels)) > 0) {
                console.log('Found kebeleMembersChart canvas');
                try {
                    if (kebeleMembersCtx.chart) {
                        kebeleMembersCtx.chart.destroy();
                    }
                    
                    // Limit to top 10 kebeles for readability
                    const maxKebeles = 10;
                    const limitedLabels = @json($kebeleLabels).slice(0, maxKebeles);
                    const limitedData = @json($kebeleMembers).slice(0, maxKebeles);
                    
                    kebeleMembersCtx.chart = new Chart(kebeleMembersCtx, {
                        type: 'bar',
                        data: {
                            labels: limitedLabels,
                            datasets: [{
                                label: '{{ __("Number of Members") }}',
                                data: limitedData,
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
                    console.log('Kebele Members Chart created successfully');
                } catch (error) {
                    console.error('Error creating Kebele Members Chart:', error);
                }
            }

            // Kebele Enterprises Chart
            const kebeleEnterprisesCtx = document.getElementById('kebeleEnterprisesChart');
            if (kebeleEnterprisesCtx && @json(count($kebeleLabels)) > 0) {
                console.log('Found kebeleEnterprisesChart canvas');
                try {
                    if (kebeleEnterprisesCtx.chart) {
                        kebeleEnterprisesCtx.chart.destroy();
                    }
                    
                    // Limit to top 10 kebeles for readability
                    const maxKebeles = 10;
                    const limitedLabels = @json($kebeleLabels).slice(0, maxKebeles);
                    const limitedData = @json($kebeleEnterprises).slice(0, maxKebeles);
                    
                    kebeleEnterprisesCtx.chart = new Chart(kebeleEnterprisesCtx, {
                        type: 'bar',
                        data: {
                            labels: limitedLabels,
                            datasets: [{
                                label: '{{ __("Number of Enterprises") }}',
                                data: limitedData,
                                backgroundColor: 'rgba(0, 123, 255, 0.7)',
                                borderColor: 'rgba(0, 123, 255, 1)',
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
                                        text: '{{ __("Number of Enterprises") }}'
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
                    console.log('Kebele Enterprises Chart created successfully');
                } catch (error) {
                    console.error('Error creating Kebele Enterprises Chart:', error);
                }
            }
            
            console.log('All charts initialization completed');
        }
    </script>
</x-layout>