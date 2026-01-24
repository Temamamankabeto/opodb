<x-layout>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-briefcase mr-2 text-green"></i>
                        {{ __('Employment Opportunity Report') }} - {{ $reportData['zone_name'] }}
                        @if($selectedWoredaId)
                            <small class="text-info">({{ $reportData['selected_woreda_name'] }})</small>
                        @endif
                        <small class="text-muted">{{ __('Zonal-level employment statistics and analysis') }}</small>
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
    </div>

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
                
                // Woreda data for chart
                $woredaLabels = array_keys($reportData['woreda_summary'] ?? []);
                $woredaMembers = array_map(function($woreda) {
                    return $woreda['current']['total_members'] ?? 0;
                }, $reportData['woreda_summary'] ?? []);
                $woredaEnterprises = array_map(function($woreda) {
                    return $woreda['current']['enterprises'] ?? 0;
                }, $reportData['woreda_summary'] ?? []);
                
                // Colors
                $genderColors = ['#36a2eb', '#ff6384'];  // Solid colors instead of rgba
                $genderBorderColors = ['#36a2eb', '#ff6384'];
                
                $jobColors = [
                    '#28a745',    // Green
                    '#007bff',     // Blue
                    '#ffc107',     // Yellow
                    '#dc3545',     // Red
                    '#6c757d',     // Gray
                    '#17a2b8',     // Teal
                    '#6610f2',     // Purple
                    '#fd7e14'      // Orange
                ];
            @endphp

            <!-- Zone Info Card -->
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-1"></i>
                        {{ __('Zone Information') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">{{ __('Zone Name') }}:</dt>
                                <dd class="col-sm-8 font-weight-bold">{{ $reportData['zone_name'] }}</dd>
                                
                                <dt class="col-sm-4">{{ __('Total Woredas') }}:</dt>
                                <dd class="col-sm-8">{{ $reportData['woredas_count'] }} {{ __('Woredas') }}</dd>
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
                    @if($selectedWoredaId)
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-filter mr-1"></i>
                                    <strong>{{ __('Filter Applied:') }}</strong>
                                    {{ __('Showing data for') }} <strong>{{ $reportData['selected_woreda_name'] }}</strong>
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
                                    <label>{{ __('Woreda') }}</label>
                                    <select name="woreda_id" class="form-control">
                                        <option value="">{{ __('All Woredas') }}</option>
                                        @foreach($woredasInZone as $woreda)
                                            <option value="{{ $woreda->id }}" {{ $selectedWoredaId == $woreda->id ? 'selected' : '' }}>
                                                {{ $woreda->name }}
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
                                    @if($selectedWoredaId)
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
                            @if($selectedWoredaId)
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
                            @if($selectedWoredaId)
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
            @if($selectedWoredaId && count($reportData['feedback_items']) > 0)
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-comments mr-1"></i>
                        {{ __('Feedback Overview') }} - {{ $reportData['selected_woreda_name'] }}
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
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-1"></i>
                                {{ __('Note: Showing feedback from woreda administrators') }}
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

            <!-- Woreda Performance Charts -->
            @if(count($woredaLabels) > 0)
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Woreda Performance - Members') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="woredaMembersChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Woreda Performance - Enterprises') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="woredaEnterprisesChart" height="300"></canvas>
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

            <!-- Woreda Performance Table -->
            @if(count($woredaLabels) > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-1"></i>
                        {{ __('Woreda Performance Summary') }} ({{ $selectedYear }})
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
                                    <th>{{ __('Woreda') }}</th>
                                    <th class="text-center">{{ __('Kebeles') }}</th>
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
                                @foreach($reportData['woreda_summary'] as $woredaName => $data)
                                    @php
                                        $woredaTotal = $data['current']['total_members'] ?? 0;
                                        $woredaPercentage = $reportData['current']['total_members'] > 0 ? 
                                            ($woredaTotal / $reportData['current']['total_members']) * 100 : 0;
                                        
                                        // Performance rating based on percentage
                                        if ($woredaPercentage >= 20) {
                                            $performanceClass = 'success';
                                            $performanceText = __('Excellent');
                                        } elseif ($woredaPercentage >= 10) {
                                            $performanceClass = 'info';
                                            $performanceText = __('Good');
                                        } elseif ($woredaPercentage >= 5) {
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
                                            {{ $woredaName }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $data['current']['kebeles_count'] ?? 0 }}</span>
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
                                            {{ number_format($woredaTotal) }}
                                            <small class="text-muted">({{ number_format($woredaPercentage, 1) }}%)</small>
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
                                    <td class="text-center font-weight-bold">{{ array_sum(array_column($reportData['woreda_summary'], 'current.kebeles_count')) }}</td>
                                    <td class="text-center font-weight-bold">{{ number_format($reportData['current']['enterprises'] ?? 0) }}</td>
                                    <td class="text-center font-weight-bold">{{ number_format($reportData['current']['male_members'] ?? 0) }}</td>
                                    <td class="text-center font-weight-bold">{{ number_format($reportData['current']['female_members'] ?? 0) }}</td>
                                    <td class="text-center font-weight-bold">{{ number_format($reportData['current']['total_members'] ?? 0) }}</td>
                                    <td class="text-center font-weight-bold">{{ number_format($reportData['current']['total_opportunities'] ?? 0) }}</td>
                                    <td class="text-center font-weight-bold">{{ array_sum(array_column($reportData['woreda_summary'], 'current.feedback_count')) }}</td>
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
                                @if($selectedWoredaId && count($reportData['feedback_items']) > 0)
                                <li class="nav-item">
                                    <a class="nav-link" id="feedback-tab" data-toggle="pill" href="#feedback" role="tab" aria-controls="feedback" aria-selected="false">
                                        <i class="fas fa-comments mr-1"></i> {{ __('Woreda Feedbacks') }}
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
                                                        <th>{{ __('Woreda') }}</th>
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
                                                                {{ $record['woreda_name'] }}
                                                            </td>
                                                            <td>
                                                                <i class="fas fa-map-marker-alt text-secondary mr-1"></i>
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
                                                        <td colspan="4" class="text-right"><strong>{{ __('Total:') }}</strong></td>
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
                                                        <th>{{ __('Woreda') }}</th>
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
                                                                {{ $record['woreda_name'] }}
                                                            </td>
                                                            <td>
                                                                <i class="fas fa-map-marker-alt text-secondary mr-1"></i>
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
                                                        <td colspan="4" class="text-right"><strong>{{ __('Total:') }}</strong></td>
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
                                @if($selectedWoredaId && count($reportData['feedback_items']) > 0)
                                <div class="tab-pane fade" id="feedback" role="tabpanel" aria-labelledby="feedback-tab">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        {{ __('Showing feedback provided by woreda administrators for') }} <strong>{{ $reportData['selected_woreda_name'] }}</strong>
                                    </div>
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
                                                            <i class="fas fa-user"></i> {{ $feedback['woreda_admin_name'] ?? 'Woreda Admin' }}
                                                        </small>
                                                        <small class="text-muted ml-2">
                                                            <i class="fas fa-map-marker-alt"></i> {{ $feedbackItem['kebele_name'] }}
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
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>{{ __('Woreda:') }}</strong> {{ $record['woreda_name'] }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>{{ __('Kebele:') }}</strong> {{ $record['kebele_name'] }}
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>{{ __('Job Category:') }}</strong> {{ $record['job_name'] }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>{{ __('Enterprise Type:') }}</strong> {{ $record['enterprise_name'] }}
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="callout callout-{{ $priorityClass }}">
                                        <h5>{{ $feedback['title'] }}</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>{{ __('Type:') }}</strong> {{ ucfirst($feedback['feedback_type']) }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>{{ __('Priority:') }}</strong> 
                                                <span class="badge badge-{{ $priorityClass }}">
                                                    {{ ucfirst($feedback['priority']) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <strong>{{ __('From:') }}</strong> {{ $feedback['woreda_admin_name'] ?? 'Woreda Admin' }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>{{ __('Date:') }}</strong> {{ \Carbon\Carbon::parse($feedback['given_at'])->format('M d, Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <strong>{{ __('Description:') }}</strong>
                                        <p class="p-2 bg-light rounded">{{ $feedback['description'] }}</p>
                                    </div>
                                    
                                    @if(!empty($feedback['action_items']))
                                        <div class="mt-3">
                                            <strong>{{ __('Action Items:') }}</strong>
                                            <p class="p-2 bg-light rounded">{{ $feedback['action_items'] }}</p>
                                        </div>
                                    @endif
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <strong>{{ __('Rating:') }}</strong>
                                            <div class="mt-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $feedback['rating'] ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                                <span class="ml-2">({{ $feedback['rating'] }}/5)</span>
                                            </div>
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
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- Give Feedback Modal -->
            @foreach($detailedRecords['current'] as $record)
                @if(!isset($reportData['feedback_by_plan_id'][$record['plan_id']]))
                    <div class="modal fade" id="giveFeedbackModal{{ $record['plan_id'] }}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <form action="{{ route('zonal.feedback.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="plan_id" value="{{ $record['plan_id'] }}">
                                    
                                    <div class="modal-header bg-warning">
                                        <h5 class="modal-title">
                                            <i class="fas fa-comment-alt mr-1"></i>
                                            {{ __('Give Feedback') }}
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            {{ __('Providing feedback for:') }} 
                                            <strong>{{ $record['woreda_name'] }} - {{ $record['kebele_name'] }}</strong>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <strong>{{ __('Job Category:') }}</strong> {{ $record['job_name'] }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>{{ __('Enterprise Type:') }}</strong> {{ $record['enterprise_name'] }}
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <strong>{{ __('Enterprises:') }}</strong> {{ number_format($record['enterprises']) }}
                                            </div>
                                            <div class="col-md-4">
                                                <strong>{{ __('Members:') }}</strong> {{ number_format($record['total_members']) }}
                                            </div>
                                            <div class="col-md-4">
                                                <strong>{{ __('Female Ratio:') }}</strong> 
                                                {{ $record['total_members'] > 0 ? number_format(($record['female_members'] / $record['total_members']) * 100, 1) : 0 }}%
                                            </div>
                                        </div>
                                        
                                        <hr>
                                        
                                        <div class="form-group">
                                            <label for="feedback_type">{{ __('Feedback Type') }} *</label>
                                            <select name="feedback_type" id="feedback_type" class="form-control" required>
                                                <option value="">{{ __('Select type') }}</option>
                                                <option value="performance">{{ __('Performance') }}</option>
                                                <option value="suggestion">{{ __('Suggestion') }}</option>
                                                <option value="concern">{{ __('Concern') }}</option>
                                                <option value="improvement">{{ __('Improvement') }}</option>
                                                <option value="other">{{ __('Other') }}</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="priority">{{ __('Priority') }} *</label>
                                            <select name="priority" id="priority" class="form-control" required>
                                                <option value="">{{ __('Select priority') }}</option>
                                                <option value="low">{{ __('Low') }}</option>
                                                <option value="medium">{{ __('Medium') }}</option>
                                                <option value="high">{{ __('High') }}</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="title">{{ __('Title') }} *</label>
                                            <input type="text" name="title" id="title" class="form-control" placeholder="{{ __('Enter feedback title') }}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="description">{{ __('Description') }} *</label>
                                            <textarea name="description" id="description" class="form-control" rows="4" placeholder="{{ __('Enter detailed feedback description') }}" required></textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="action_items">{{ __('Action Items') }}</label>
                                            <textarea name="action_items" id="action_items" class="form-control" rows="3" placeholder="{{ __('Suggest action items if any') }}"></textarea>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="rating">{{ __('Rating (1-5)') }} *</label>
                                                    <select name="rating" id="rating" class="form-control" required>
                                                        <option value="">{{ __('Select rating') }}</option>
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <option value="{{ $i }}">{{ $i }} {{ __('Star') }}{{ $i > 1 ? 's' : '' }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mt-4">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="follow_up_required{{ $record['plan_id'] }}" name="follow_up_required" value="1">
                                                        <label class="custom-control-label" for="follow_up_required{{ $record['plan_id'] }}">
                                                            {{ __('Follow-up Required') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-paper-plane mr-1"></i> {{ __('Submit Feedback') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- General Feedback Modal -->
            @if($selectedWoredaId)
            <div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <form action="{{ route('zonal.general.feedback.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="woreda_id" value="{{ $selectedWoredaId }}">
                            <input type="hidden" name="year" value="{{ $selectedYear }}">
                            
                            <div class="modal-header bg-warning">
                                <h5 class="modal-title">
                                    <i class="fas fa-comments mr-1"></i>
                                    {{ __('Give General Feedback') }}
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    {{ __('Providing general feedback for:') }} 
                                    <strong>{{ $reportData['selected_woreda_name'] }} - {{ $selectedYear }}</strong>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="small-box bg-light p-2 text-center">
                                            <h5 class="mb-0">{{ number_format($reportData['current']['total_members'] ?? 0) }}</h5>
                                            <small>{{ __('Total Members') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="small-box bg-light p-2 text-center">
                                            <h5 class="mb-0">{{ number_format($reportData['current']['enterprises'] ?? 0) }}</h5>
                                            <small>{{ __('Enterprises') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="small-box bg-light p-2 text-center">
                                            <h5 class="mb-0">{{ count($detailedRecords['current']) ?? 0 }}</h5>
                                            <small>{{ __('Records') }}</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="form-group">
                                    <label for="general_feedback_type">{{ __('Feedback Type') }} *</label>
                                    <select name="feedback_type" id="general_feedback_type" class="form-control" required>
                                        <option value="">{{ __('Select type') }}</option>
                                        <option value="overall_performance">{{ __('Overall Performance') }}</option>
                                        <option value="data_quality">{{ __('Data Quality') }}</option>
                                        <option value="timeliness">{{ __('Timeliness') }}</option>
                                        <option value="compliance">{{ __('Compliance') }}</option>
                                        <option value="other">{{ __('Other') }}</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="general_priority">{{ __('Priority') }} *</label>
                                    <select name="priority" id="general_priority" class="form-control" required>
                                        <option value="">{{ __('Select priority') }}</option>
                                        <option value="low">{{ __('Low') }}</option>
                                        <option value="medium">{{ __('Medium') }}</option>
                                        <option value="high">{{ __('High') }}</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="general_title">{{ __('Title') }} *</label>
                                    <input type="text" name="title" id="general_title" class="form-control" placeholder="{{ __('Enter feedback title') }}" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="general_description">{{ __('Description') }} *</label>
                                    <textarea name="description" id="general_description" class="form-control" rows="5" placeholder="{{ __('Enter detailed feedback description') }}" required></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="general_action_items">{{ __('Action Items / Recommendations') }}</label>
                                    <textarea name="action_items" id="general_action_items" class="form-control" rows="3" placeholder="{{ __('Suggest action items or recommendations') }}"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-paper-plane mr-1"></i> {{ __('Submit Feedback') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </section>

    <!-- JavaScript Section - MOVE THIS TO BOTTOM -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing charts...');
        
        // Debug: Check if Chart.js is loaded
        console.log('Chart object available:', typeof Chart !== 'undefined');
        
        // Gender Distribution Chart
        const genderCtx = document.getElementById('genderChart');
        if (genderCtx) {
            console.log('Found gender chart canvas');
            try {
                new Chart(genderCtx, {
                    type: 'pie',
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
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Gender Distribution'
                            }
                        }
                    }
                });
                console.log('Gender chart created successfully');
            } catch (error) {
                console.error('Error creating gender chart:', error);
            }
        } else {
            console.warn('Gender chart canvas not found');
        }

        // Job Categories Chart
        const jobCtx = document.getElementById('jobCategoryChart');
        if (jobCtx) {
            console.log('Found job category chart canvas');
            const jobLabels = @json(array_slice($jobCategoryLabels, 0, 8));
            const jobData = @json(array_slice($jobCategoryMembers, 0, 8));
            
            try {
                new Chart(jobCtx, {
                    type: 'bar',
                    data: {
                        labels: jobLabels,
                        datasets: [{
                            label: 'Members',
                            data: jobData,
                            backgroundColor: @json(array_slice($jobColors, 0, 8)),
                            borderColor: '#000000',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Top Job Categories by Members'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Members'
                                }
                            }
                        }
                    }
                });
                console.log('Job category chart created successfully');
            } catch (error) {
                console.error('Error creating job category chart:', error);
            }
        }

        // Trend Chart
        const trendCtx = document.getElementById('trendChart');
        if (trendCtx) {
            console.log('Found trend chart canvas');
            try {
                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: @json($trendLabels),
                        datasets: [{
                            label: 'Members',
                            data: @json($membersTrend),
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40, 167, 69, 0.1)',
                            borderWidth: 2,
                            tension: 0.4
                        }, {
                            label: 'Enterprises',
                            data: @json($enterprisesTrend),
                            borderColor: '#007bff',
                            backgroundColor: 'rgba(0, 123, 255, 0.1)',
                            borderWidth: 2,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Year-over-Year Comparison'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Count'
                                }
                            }
                        }
                    }
                });
                console.log('Trend chart created successfully');
            } catch (error) {
                console.error('Error creating trend chart:', error);
            }
        }

        // Enterprise Types Chart
        const enterpriseCtx = document.getElementById('enterpriseChart');
        if (enterpriseCtx) {
            console.log('Found enterprise chart canvas');
            const enterpriseLabels = @json(array_slice($enterpriseTypeLabels, 0, 6));
            const enterpriseData = @json(array_slice($enterpriseTypeMembers, 0, 6));
            
            try {
                new Chart(enterpriseCtx, {
                    type: 'doughnut',
                    data: {
                        labels: enterpriseLabels,
                        datasets: [{
                            data: enterpriseData,
                            backgroundColor: [
                                '#ff6384',
                                '#36a2eb',
                                '#ffce56',
                                '#4bc0c0',
                                '#9966ff',
                                '#ff9f40'
                            ],
                            borderColor: '#ffffff',
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
                            title: {
                                display: true,
                                text: 'Enterprise Types Distribution'
                            }
                        }
                    }
                });
                console.log('Enterprise chart created successfully');
            } catch (error) {
                console.error('Error creating enterprise chart:', error);
            }
        }

        // Woreda Members Chart
        const woredaMembersCtx = document.getElementById('woredaMembersChart');
        if (woredaMembersCtx && @json(count($woredaLabels) > 0)) {
            console.log('Found woreda members chart canvas');
            try {
                new Chart(woredaMembersCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($woredaLabels),
                        datasets: [{
                            label: 'Members',
                            data: @json($woredaMembers),
                            backgroundColor: '#28a745',
                            borderColor: '#28a745',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Woreda Performance - Members'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Members'
                                }
                            }
                        }
                    }
                });
                console.log('Woreda members chart created successfully');
            } catch (error) {
                console.error('Error creating woreda members chart:', error);
            }
        }

        // Woreda Enterprises Chart
        const woredaEnterprisesCtx = document.getElementById('woredaEnterprisesChart');
        if (woredaEnterprisesCtx && @json(count($woredaLabels) > 0)) {
            console.log('Found woreda enterprises chart canvas');
            try {
                new Chart(woredaEnterprisesCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($woredaLabels),
                        datasets: [{
                            label: 'Enterprises',
                            data: @json($woredaEnterprises),
                            backgroundColor: '#007bff',
                            borderColor: '#007bff',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Woreda Performance - Enterprises'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Enterprises'
                                }
                            }
                        }
                    }
                });
                console.log('Woreda enterprises chart created successfully');
            } catch (error) {
                console.error('Error creating woreda enterprises chart:', error);
            }
        }

        // Initialize tooltips
        if (typeof $ !== 'undefined') {
            $('[data-toggle="tooltip"]').tooltip();
            
            // Tab activation
            $('#custom-tabs-three-tab a').on('click', function (e) {
                e.preventDefault();
                $(this).tab('show');
            });
        }
    });
    </script>
</x-layout>