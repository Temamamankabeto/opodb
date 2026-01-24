<x-layout>
    <div class="content-header">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>
                            <i class="fas fa-landmark mr-2 text-orange"></i>
                            {{ __('Kebele Asset Report') }}
                            <small class="text-muted">{{ __('Government revenue, public contributions, and community asset management analysis') }}</small>
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
                    $assetCategories = [
                        __('Government Revenue'),
                        __('Public Contribution'),
                        __('Community Services'),
                        __('Bank Saving'),
                        __('Family Development')
                    ];
                    
                    $currentAssetData = [
                        $reportData['current']['government_revenue']['amount'] ?? 0,
                        $reportData['current']['public_contribution']['monetary_contribution'] ?? 0,
                        $reportData['current']['community_services']['amount'] ?? 0,
                        ($reportData['current']['bank_saving']['family_amount'] ?? 0) + ($reportData['current']['bank_saving']['association_amount'] ?? 0),
                        $reportData['current']['family_development']['cost'] ?? 0
                    ];
                    
                    $previousAssetData = [
                        $reportData['previous']['government_revenue']['amount'] ?? 0,
                        $reportData['previous']['public_contribution']['monetary_contribution'] ?? 0,
                        $reportData['previous']['community_services']['amount'] ?? 0,
                        ($reportData['previous']['bank_saving']['family_amount'] ?? 0) + ($reportData['previous']['bank_saving']['association_amount'] ?? 0),
                        $reportData['previous']['family_development']['cost'] ?? 0
                    ];
                    
                    // Prepare data for people served chart
                    $peopleLabels = [
                        __('Public Members'),
                        __('Transferred Persons'),
                        __('Daily Served')
                    ];
                    
                    $currentPeopleData = [
                        $reportData['current']['total_members'] ?? 0,
                        $reportData['current']['system_assets']['transferred_persons'] ?? 0,
                        $reportData['current']['system_assets']['daily_served_persons'] ?? 0
                    ];
                    
                    $previousPeopleData = [
                        $reportData['previous']['total_members'] ?? 0,
                        $reportData['previous']['system_assets']['transferred_persons'] ?? 0,
                        $reportData['previous']['system_assets']['daily_served_persons'] ?? 0
                    ];
                    
                    // Loan data
                    $loanLabels = [
                        __('Received'),
                        __('Returned'),
                        __('Not Returned')
                    ];
                    
                    $currentLoanData = [
                        $reportData['current']['loan_receipt']['received_this_year'] ?? 0,
                        $reportData['current']['loan_receipt']['returned_this_year'] ?? 0,
                        $reportData['current']['loan_receipt']['not_returned'] ?? 0
                    ];
                    
                    $previousLoanData = [
                        $reportData['previous']['loan_receipt']['received_this_year'] ?? 0,
                        $reportData['previous']['loan_receipt']['returned_this_year'] ?? 0,
                        $reportData['previous']['loan_receipt']['not_returned'] ?? 0
                    ];
                    
                    // Trend data
                    $trendLabels = [$previousYear, $selectedYear];
                    $revenueTrend = [
                        $reportData['previous']['total_amount'] ?? 0,
                        $reportData['current']['total_amount'] ?? 0
                    ];
                    
                    $membersTrend = [
                        $reportData['previous']['total_members'] ?? 0,
                        $reportData['current']['total_members'] ?? 0
                    ];
                    
                    // Asset colors
                    $assetColors = [
                        'rgba(255, 99, 132, 0.7)',    // Red - Government
                        'rgba(54, 162, 235, 0.7)',     // Blue - Public
                        'rgba(255, 206, 86, 0.7)',     // Yellow - Community
                        'rgba(75, 192, 192, 0.7)',     // Teal - Bank
                        'rgba(153, 102, 255, 0.7)'     // Purple - Family
                    ];
                    
                    $assetBorderColors = [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ];
                @endphp

                <!-- Year Filter Card -->
                <div class="card card-outline card-orange">
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
                                        <button type="submit" class="btn btn-orange">
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
                        <div class="small-box bg-orange">
                            <div class="inner">
                                <h3>{{ number_format($reportData['current']['total_amount'] ?? 0, 2) }} <small>ETB</small></h3>
                                <p>{{ __('Total Assets') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            @if($totalChange != 0)
                                <div class="small-box-footer">
                                    {{ $totalChange > 0 ? '↑' : '↓' }} 
                                    {{ number_format(abs($totalChange), 1) }}% 
                                    {{ __('from last year') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ number_format($reportData['current']['government_revenue']['amount'] ?? 0, 2) }} <small>ETB</small></h3>
                                <p>{{ __('Government Revenue') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-university"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ number_format($reportData['current']['public_contribution']['monetary_contribution'] ?? 0, 2) }} <small>ETB</small></h3>
                                <p>{{ __('Public Contribution') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-hand-holding-usd"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-purple">
                            <div class="inner">
                                <h3>{{ number_format($reportData['current']['total_members'] ?? 0) }}</h3>
                                <p>{{ __('Public Members') }}</p>
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
                </div>

                <!-- Additional Summary Cards -->
                <div class="row mb-4">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-teal">
                            <div class="inner">
                                <h3>{{ number_format($reportData['current']['system_assets']['daily_served_persons'] ?? 0) }}</h3>
                                <p>{{ __('Daily Served') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ number_format($reportData['current']['loan_receipt']['received_this_year'] ?? 0, 2) }} <small>ETB</small></h3>
                                <p>{{ __('Loans Received') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-hand-holding"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ number_format($reportData['current']['family_development']['quantity'] ?? 0) }}</h3>
                                <p>{{ __('Family Development') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-home"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-secondary">
                            <div class="inner">
                                <h3>{{ number_format(($reportData['current']['bank_saving']['family_amount'] ?? 0) + ($reportData['current']['bank_saving']['association_amount'] ?? 0), 2) }} <small>ETB</small></h3>
                                <p>{{ __('Bank Saving') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-piggy-bank"></i>
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
                                    {{ __('Asset Category Distribution') }} ({{ $selectedYear }})
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                @if($reportData['current']['total_amount'] > 0)
                                    <div class="chart">
                                        <canvas id="assetCategoryChart" height="300"></canvas>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-landmark fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">{{ __('No asset data available') }}</h5>
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
                                    {{ __('Revenue & Members Trend') }}
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
                                    {{ __('People Served') }}
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="peopleChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Report Table -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-table mr-1"></i>
                            {{ __('Detailed Asset Report') }}
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
                                    <tr class="bg-gradient-orange">
                                        <th>{{ __('Asset Category') }}</th>
                                        <th class="text-center">{{ __('Metrics') }}</th>
                                        <th class="text-center">{{ $selectedYear }}</th>
                                        <th class="text-center">{{ $previousYear }}</th>
                                        <th class="text-center">{{ __('Change') }}</th>
                                        <th class="text-center">{{ __('Change %') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Government Revenue -->
                                    <tr>
                                        <td>
                                            <i class="fas fa-university text-info mr-2"></i>
                                            <strong>{{ __('Government Revenue') }}</strong><br>
                                            <small class="text-muted">{{ $reportData['current']['government_revenue']['description'] }}</small>
                                        </td>
                                        <td class="text-center">{{ __('Amount (ETB)') }}</td>
                                        <td class="text-center font-weight-bold">
                                            {{ number_format($reportData['current']['government_revenue']['amount'] ?? 0, 2) }}
                                        </td>
                                        <td class="text-center text-muted">
                                            {{ number_format($reportData['previous']['government_revenue']['amount'] ?? 0, 2) }}
                                        </td>
                                        @php
                                            $govChange = $reportData['previous']['government_revenue']['amount'] ?? 0;
                                            $govCurrent = $reportData['current']['government_revenue']['amount'] ?? 0;
                                            $govDiff = $govCurrent - $govChange;
                                            $govPercent = $govChange > 0 ? round(($govDiff / $govChange) * 100, 1) : ($govCurrent > 0 ? 100 : 0);
                                        @endphp
                                        <td class="text-center {{ $govDiff > 0 ? 'text-success' : ($govDiff < 0 ? 'text-danger' : '') }}">
                                            @if($govDiff > 0)<i class="fas fa-arrow-up"></i>@elseif($govDiff < 0)<i class="fas fa-arrow-down"></i>@endif
                                            {{ number_format(abs($govDiff), 2) }}
                                        </td>
                                        <td class="text-center {{ $govDiff > 0 ? 'text-success' : ($govDiff < 0 ? 'text-danger' : '') }}">
                                            {{ $govPercent }}%
                                        </td>
                                    </tr>
                                    
                                    <!-- Public Contribution -->
                                    <tr>
                                        <td rowspan="4">
                                            <i class="fas fa-hand-holding-usd text-success mr-2"></i>
                                            <strong>{{ __('Public Contribution') }}</strong><br>
                                            <small class="text-muted">{{ $reportData['current']['public_contribution']['description'] }}</small>
                                        </td>
                                        <td class="text-center">{{ __('Members') }}</td>
                                        <td class="text-center font-weight-bold">
                                            {{ number_format($reportData['current']['public_contribution']['members'] ?? 0) }}
                                        </td>
                                        <td class="text-center text-muted">
                                            {{ number_format($reportData['previous']['public_contribution']['members'] ?? 0) }}
                                        </td>
                                        @php
                                            $membersChange = $reportData['previous']['public_contribution']['members'] ?? 0;
                                            $membersCurrent = $reportData['current']['public_contribution']['members'] ?? 0;
                                            $membersDiff = $membersCurrent - $membersChange;
                                            $membersPercent = $membersChange > 0 ? round(($membersDiff / $membersChange) * 100, 1) : ($membersCurrent > 0 ? 100 : 0);
                                        @endphp
                                        <td class="text-center {{ $membersDiff > 0 ? 'text-success' : ($membersDiff < 0 ? 'text-danger' : '') }}">
                                            @if($membersDiff > 0)<i class="fas fa-arrow-up"></i>@elseif($membersDiff < 0)<i class="fas fa-arrow-down"></i>@endif
                                            {{ number_format(abs($membersDiff)) }}
                                        </td>
                                        <td class="text-center {{ $membersDiff > 0 ? 'text-success' : ($membersDiff < 0 ? 'text-danger' : '') }}">
                                            {{ $membersPercent }}%
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">{{ __('Membership Fee (ETB)') }}</td>
                                        <td class="text-center">
                                            {{ number_format($reportData['current']['public_contribution']['membership_fee'] ?? 0, 2) }}
                                        </td>
                                        <td class="text-center text-muted">
                                            {{ number_format($reportData['previous']['public_contribution']['membership_fee'] ?? 0, 2) }}
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">{{ __('Contribution Kind') }}</td>
                                        <td class="text-center">
                                            {{ number_format($reportData['current']['public_contribution']['contribution_kind'] ?? 0, 2) }}
                                        </td>
                                        <td class="text-center text-muted">
                                            {{ number_format($reportData['previous']['public_contribution']['contribution_kind'] ?? 0, 2) }}
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">{{ __('Monetary (ETB)') }}</td>
                                        <td class="text-center font-weight-bold">
                                            {{ number_format($reportData['current']['public_contribution']['monetary_contribution'] ?? 0, 2) }}
                                        </td>
                                        <td class="text-center text-muted">
                                            {{ number_format($reportData['previous']['public_contribution']['monetary_contribution'] ?? 0, 2) }}
                                        </td>
                                        @php
                                            $moneyChange = $reportData['previous']['public_contribution']['monetary_contribution'] ?? 0;
                                            $moneyCurrent = $reportData['current']['public_contribution']['monetary_contribution'] ?? 0;
                                            $moneyDiff = $moneyCurrent - $moneyChange;
                                            $moneyPercent = $moneyChange > 0 ? round(($moneyDiff / $moneyChange) * 100, 1) : ($moneyCurrent > 0 ? 100 : 0);
                                        @endphp
                                        <td class="text-center {{ $moneyDiff > 0 ? 'text-success' : ($moneyDiff < 0 ? 'text-danger' : '') }}">
                                            @if($moneyDiff > 0)<i class="fas fa-arrow-up"></i>@elseif($moneyDiff < 0)<i class="fas fa-arrow-down"></i>@endif
                                            {{ number_format(abs($moneyDiff), 2) }}
                                        </td>
                                        <td class="text-center {{ $moneyDiff > 0 ? 'text-success' : ($moneyDiff < 0 ? 'text-danger' : '') }}">
                                            {{ $moneyPercent }}%
                                        </td>
                                    </tr>
                                    
                                    <!-- System Assets -->
                                    <tr>
                                        <td rowspan="2">
                                            <i class="fas fa-exchange-alt text-teal mr-2"></i>
                                            <strong>{{ __('System Assets') }}</strong><br>
                                            <small class="text-muted">{{ $reportData['current']['system_assets']['description'] }}</small>
                                        </td>
                                        <td class="text-center">{{ __('Transferred Persons') }}</td>
                                        <td class="text-center">
                                            {{ number_format($reportData['current']['system_assets']['transferred_persons'] ?? 0) }}
                                        </td>
                                        <td class="text-center text-muted">
                                            {{ number_format($reportData['previous']['system_assets']['transferred_persons'] ?? 0) }}
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">{{ __('Daily Served') }}</td>
                                        <td class="text-center font-weight-bold">
                                            {{ number_format($reportData['current']['system_assets']['daily_served_persons'] ?? 0) }}
                                        </td>
                                        <td class="text-center text-muted">
                                            {{ number_format($reportData['previous']['system_assets']['daily_served_persons'] ?? 0) }}
                                        </td>
                                        @php
                                            $servedChange = $reportData['previous']['system_assets']['daily_served_persons'] ?? 0;
                                            $servedCurrent = $reportData['current']['system_assets']['daily_served_persons'] ?? 0;
                                            $servedDiff = $servedCurrent - $servedChange;
                                            $servedPercent = $servedChange > 0 ? round(($servedDiff / $servedChange) * 100, 1) : ($servedCurrent > 0 ? 100 : 0);
                                        @endphp
                                        <td class="text-center {{ $servedDiff > 0 ? 'text-success' : ($servedDiff < 0 ? 'text-danger' : '') }}">
                                            @if($servedDiff > 0)<i class="fas fa-arrow-up"></i>@elseif($servedDiff < 0)<i class="fas fa-arrow-down"></i>@endif
                                            {{ number_format(abs($servedDiff)) }}
                                        </td>
                                        <td class="text-center {{ $servedDiff > 0 ? 'text-success' : ($servedDiff < 0 ? 'text-danger' : '') }}">
                                            {{ $servedPercent }}%
                                        </td>
                                    </tr>
                                    
                                    <!-- Community Services -->
                                    <tr>
                                        <td>
                                            <i class="fas fa-hands-helping text-warning mr-2"></i>
                                            <strong>{{ __('Community Services') }}</strong><br>
                                            <small class="text-muted">{{ $reportData['current']['community_services']['description'] }}</small>
                                        </td>
                                        <td class="text-center">{{ __('Amount (ETB)') }}</td>
                                        <td class="text-center font-weight-bold">
                                            {{ number_format($reportData['current']['community_services']['amount'] ?? 0, 2) }}
                                        </td>
                                        <td class="text-center text-muted">
                                            {{ number_format($reportData['previous']['community_services']['amount'] ?? 0, 2) }}
                                        </td>
                                        @php
                                            $communityChange = $reportData['previous']['community_services']['amount'] ?? 0;
                                            $communityCurrent = $reportData['current']['community_services']['amount'] ?? 0;
                                            $communityDiff = $communityCurrent - $communityChange;
                                            $communityPercent = $communityChange > 0 ? round(($communityDiff / $communityChange) * 100, 1) : ($communityCurrent > 0 ? 100 : 0);
                                        @endphp
                                        <td class="text-center {{ $communityDiff > 0 ? 'text-success' : ($communityDiff < 0 ? 'text-danger' : '') }}">
                                            @if($communityDiff > 0)<i class="fas fa-arrow-up"></i>@elseif($communityDiff < 0)<i class="fas fa-arrow-down"></i>@endif
                                            {{ number_format(abs($communityDiff), 2) }}
                                        </td>
                                        <td class="text-center {{ $communityDiff > 0 ? 'text-success' : ($communityDiff < 0 ? 'text-danger' : '') }}">
                                            {{ $communityPercent }}%
                                        </td>
                                    </tr>
                                    
                                    <!-- Loan Receipt -->
                                    <tr>
                                        <td rowspan="3">
                                            <i class="fas fa-hand-holding text-warning mr-2"></i>
                                            <strong>{{ __('Loan Receipt') }}</strong><br>
                                            <small class="text-muted">{{ $reportData['current']['loan_receipt']['description'] }}</small>
                                        </td>
                                        <td class="text-center">{{ __('Received (ETB)') }}</td>
                                        <td class="text-center font-weight-bold">
                                            {{ number_format($reportData['current']['loan_receipt']['received_this_year'] ?? 0, 2) }}
                                        </td>
                                        <td class="text-center text-muted">
                                            {{ number_format($reportData['previous']['loan_receipt']['received_this_year'] ?? 0, 2) }}
                                        </td>
                                        @php
                                            $receivedChange = $reportData['previous']['loan_receipt']['received_this_year'] ?? 0;
                                            $receivedCurrent = $reportData['current']['loan_receipt']['received_this_year'] ?? 0;
                                            $receivedDiff = $receivedCurrent - $receivedChange;
                                            $receivedPercent = $receivedChange > 0 ? round(($receivedDiff / $receivedChange) * 100, 1) : ($receivedCurrent > 0 ? 100 : 0);
                                        @endphp
                                        <td class="text-center {{ $receivedDiff > 0 ? 'text-success' : ($receivedDiff < 0 ? 'text-danger' : '') }}">
                                            @if($receivedDiff > 0)<i class="fas fa-arrow-up"></i>@elseif($receivedDiff < 0)<i class="fas fa-arrow-down"></i>@endif
                                            {{ number_format(abs($receivedDiff), 2) }}
                                        </td>
                                        <td class="text-center {{ $receivedDiff > 0 ? 'text-success' : ($receivedDiff < 0 ? 'text-danger' : '') }}">
                                            {{ $receivedPercent }}%
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">{{ __('Returned (ETB)') }}</td>
                                        <td class="text-center">
                                            {{ number_format($reportData['current']['loan_receipt']['returned_this_year'] ?? 0, 2) }}
                                        </td>
                                        <td class="text-center text-muted">
                                            {{ number_format($reportData['previous']['loan_receipt']['returned_this_year'] ?? 0, 2) }}
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">{{ __('Not Returned (ETB)') }}</td>
                                        <td class="text-center">
                                            {{ number_format($reportData['current']['loan_receipt']['not_returned'] ?? 0, 2) }}
                                        </td>
                                        <td class="text-center text-muted">
                                            {{ number_format($reportData['previous']['loan_receipt']['not_returned'] ?? 0, 2) }}
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                    
                                    <!-- Bank Saving -->
                                    <tr>
                                        <td rowspan="2">
                                            <i class="fas fa-piggy-bank text-secondary mr-2"></i>
                                            <strong>{{ __('Bank Saving') }}</strong><br>
                                            <small class="text-muted">{{ $reportData['current']['bank_saving']['description'] }}</small>
                                        </td>
                                        <td class="text-center">{{ __('Family (ETB)') }}</td>
                                        <td class="text-center">
                                            {{ number_format($reportData['current']['bank_saving']['family_amount'] ?? 0, 2) }}
                                        </td>
                                        <td class="text-center text-muted">
                                            {{ number_format($reportData['previous']['bank_saving']['family_amount'] ?? 0, 2) }}
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">{{ __('Association (ETB)') }}</td>
                                        <td class="text-center font-weight-bold">
                                            {{ number_format($reportData['current']['bank_saving']['association_amount'] ?? 0, 2) }}
                                        </td>
                                        <td class="text-center text-muted">
                                            {{ number_format($reportData['previous']['bank_saving']['association_amount'] ?? 0, 2) }}
                                        </td>
                                        @php
                                            $bankChange = ($reportData['previous']['bank_saving']['family_amount'] ?? 0) + ($reportData['previous']['bank_saving']['association_amount'] ?? 0);
                                            $bankCurrent = ($reportData['current']['bank_saving']['family_amount'] ?? 0) + ($reportData['current']['bank_saving']['association_amount'] ?? 0);
                                            $bankDiff = $bankCurrent - $bankChange;
                                            $bankPercent = $bankChange > 0 ? round(($bankDiff / $bankChange) * 100, 1) : ($bankCurrent > 0 ? 100 : 0);
                                        @endphp
                                        <td class="text-center {{ $bankDiff > 0 ? 'text-success' : ($bankDiff < 0 ? 'text-danger' : '') }}">
                                            @if($bankDiff > 0)<i class="fas fa-arrow-up"></i>@elseif($bankDiff < 0)<i class="fas fa-arrow-down"></i>@endif
                                            {{ number_format(abs($bankDiff), 2) }}
                                        </td>
                                        <td class="text-center {{ $bankDiff > 0 ? 'text-success' : ($bankDiff < 0 ? 'text-danger' : '') }}">
                                            {{ $bankPercent }}%
                                        </td>
                                    </tr>
                                    
                                    <!-- Family Development -->
                                    <tr>
                                        <td rowspan="2">
                                            <i class="fas fa-home text-danger mr-2"></i>
                                            <strong>{{ __('Family Development') }}</strong><br>
                                            <small class="text-muted">{{ $reportData['current']['family_development']['description'] }}</small>
                                        </td>
                                        <td class="text-center">{{ __('Quantity') }}</td>
                                        <td class="text-center">
                                            {{ number_format($reportData['current']['family_development']['quantity'] ?? 0) }}
                                        </td>
                                        <td class="text-center text-muted">
                                            {{ number_format($reportData['previous']['family_development']['quantity'] ?? 0) }}
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">{{ __('Cost (ETB)') }}</td>
                                        <td class="text-center font-weight-bold">
                                            {{ number_format($reportData['current']['family_development']['cost'] ?? 0, 2) }}
                                        </td>
                                        <td class="text-center text-muted">
                                            {{ number_format($reportData['previous']['family_development']['cost'] ?? 0, 2) }}
                                        </td>
                                        @php
                                            $familyChange = $reportData['previous']['family_development']['cost'] ?? 0;
                                            $familyCurrent = $reportData['current']['family_development']['cost'] ?? 0;
                                            $familyDiff = $familyCurrent - $familyChange;
                                            $familyPercent = $familyChange > 0 ? round(($familyDiff / $familyChange) * 100, 1) : ($familyCurrent > 0 ? 100 : 0);
                                        @endphp
                                        <td class="text-center {{ $familyDiff > 0 ? 'text-success' : ($familyDiff < 0 ? 'text-danger' : '') }}">
                                            @if($familyDiff > 0)<i class="fas fa-arrow-up"></i>@elseif($familyDiff < 0)<i class="fas fa-arrow-down"></i>@endif
                                            {{ number_format(abs($familyDiff), 2) }}
                                        </td>
                                        <td class="text-center {{ $familyDiff > 0 ? 'text-success' : ($familyDiff < 0 ? 'text-danger' : '') }}">
                                            {{ $familyPercent }}%
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="bg-gray">
                                    <tr>
                                        <th colspan="2" class="font-weight-bold">{{ __('TOTALS') }}</th>
                                        <th class="text-center font-weight-bold">
                                            {{ number_format($reportData['current']['total_amount'] ?? 0, 2) }} ETB<br>
                                            <small>{{ number_format($reportData['current']['total_members'] ?? 0) }} {{ __('members') }}</small>
                                        </th>
                                        <th class="text-center text-muted">
                                            {{ number_format($reportData['previous']['total_amount'] ?? 0, 2) }} ETB<br>
                                            <small>{{ number_format($reportData['previous']['total_members'] ?? 0) }} {{ __('members') }}</small>
                                        </th>
                                        <th class="text-center {{ $totalChange > 0 ? 'text-success' : ($totalChange < 0 ? 'text-danger' : '') }}">
                                            @if($totalChange > 0)
                                                <i class="fas fa-arrow-up"></i>
                                            @elseif($totalChange < 0)
                                                <i class="fas fa-arrow-down"></i>
                                            @endif
                                            {{ number_format(abs($reportData['current']['total_amount'] - $reportData['previous']['total_amount']), 2) }} ETB
                                        </th>
                                        <th class="text-center {{ $totalChange > 0 ? 'text-success' : ($totalChange < 0 ? 'text-danger' : '') }}">
                                            {{ number_format(abs($totalChange), 1) }}%
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .bg-orange {
            background-color: #fd7e14 !important;
        }
        .bg-gradient-orange {
            background: linear-gradient(45deg, #fd7e14, #ff8c42) !important;
            color: white;
        }
        .btn-orange {
            background-color: #fd7e14;
            border-color: #fd7e14;
            color: white;
        }
        .btn-orange:hover {
            background-color: #e76e00;
            border-color: #e76e00;
            color: white;
        }
        .card-outline.card-orange {
            border-top-color: #fd7e14;
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
            
            // Asset Category Chart
            const assetCategoryCtx = document.getElementById('assetCategoryChart');
            if (assetCategoryCtx && {{ $reportData['current']['total_amount'] ?? 0 }} > 0) {
                console.log('Found assetCategoryChart canvas');
                
                try {
                    // Destroy existing chart if it exists
                    if (assetCategoryCtx.chart) {
                        assetCategoryCtx.chart.destroy();
                    }
                    
                    assetCategoryCtx.chart = new Chart(assetCategoryCtx, {
                        type: 'doughnut',
                        data: {
                            labels: @json($assetCategories),
                            datasets: [{
                                data: @json($currentAssetData),
                                backgroundColor: @json($assetColors),
                                borderColor: @json($assetBorderColors),
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
                                            return `${label}: ${value.toLocaleString()} ETB (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                    console.log('Asset Category Chart created successfully');
                } catch (error) {
                    console.error('Error creating Asset Category Chart:', error);
                }
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
                            labels: @json($assetCategories),
                            datasets: [
                                {
                                    label: '{{ $selectedYear }}',
                                    data: @json($currentAssetData),
                                    backgroundColor: 'rgba(253, 126, 20, 0.7)',
                                    borderColor: 'rgba(253, 126, 20, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: '{{ $previousYear }}',
                                    data: @json($previousAssetData),
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
                                        text: '{{ __("Amount (ETB)") }}'
                                    },
                                    ticks: {
                                        callback: function(value) {
                                            return value.toLocaleString() + ' ETB';
                                        }
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
                            datasets: [
                                {
                                    label: '{{ __("Total Revenue (ETB)") }}',
                                    data: @json($revenueTrend),
                                    borderColor: 'rgba(253, 126, 20, 1)',
                                    backgroundColor: 'rgba(253, 126, 20, 0.1)',
                                    borderWidth: 3,
                                    tension: 0.3,
                                    fill: true,
                                    pointBackgroundColor: 'rgba(253, 126, 20, 1)',
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 2,
                                    pointRadius: 6,
                                    yAxisID: 'y'
                                },
                                {
                                    label: '{{ __("Total Members") }}',
                                    data: @json($membersTrend),
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
                                        text: '{{ __("Revenue (ETB)") }}'
                                    },
                                    ticks: {
                                        callback: function(value) {
                                            return value.toLocaleString() + ' ETB';
                                        }
                                    }
                                },
                                y1: {
                                    type: 'linear',
                                    display: true,
                                    position: 'right',
                                    title: {
                                        display: true,
                                        text: '{{ __("Members") }}'
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

            // People Chart
            const peopleCtx = document.getElementById('peopleChart');
            if (peopleCtx) {
                console.log('Found peopleChart canvas');
                try {
                    if (peopleCtx.chart) {
                        peopleCtx.chart.destroy();
                    }
                    
                    peopleCtx.chart = new Chart(peopleCtx, {
                        type: 'bar',
                        data: {
                            labels: @json($peopleLabels),
                            datasets: [
                                {
                                    label: '{{ $selectedYear }}',
                                    data: @json($currentPeopleData),
                                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                                    borderColor: 'rgba(40, 167, 69, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: '{{ $previousYear }}',
                                    data: @json($previousPeopleData),
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
                                        text: '{{ __("Number of People") }}'
                                    }
                                }
                            }
                        }
                    });
                    console.log('People Chart created successfully');
                } catch (error) {
                    console.error('Error creating People Chart:', error);
                }
            }
            
            console.log('All charts initialization completed');
        }
    </script>
</x-layout>