<x-layout>
    <div class="content-header">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h6>Production Yield Distribution</h6>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('sector.crop-plans.index') }}">{{ __('Crop Plans') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('Production Distribution') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    Production Yield Distribution - Grouped by Year & Crop Type
                                </h3>
                                <div class="card-tools">
                                    <button class="btn btn-sm btn-light" onclick="exportToExcel()" title="Export to Excel">
                                        <i class="fas fa-file-excel mr-1"></i> Export Excel
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive" style="max-height: 70vh;">
                                    <table class="table table-bordered table-hover table-head-fixed text-nowrap mb-0" id="productionTable">
                                        <thead class="thead-light sticky-top">
                                            <tr>
                                                <th rowspan="4" class="text-center align-middle bg-gray">#</th>
                                                <th rowspan="4" class="text-center align-middle bg-gray">Planning Year</th>
                                                <th rowspan="4" class="text-center align-middle bg-gray">Crop Type</th>
                                                <th rowspan="4" class="text-center align-middle bg-gray">Crop Variety</th>
                                                <th rowspan="4" class="text-center align-middle bg-gray">Unit</th>
                                                <th rowspan="4" class="text-center align-middle bg-gray">Production Year</th>
                                                <th rowspan="4" class="text-center align-middle bg-gray">Season</th>
                                                <th rowspan="4" class="text-center align-middle bg-gray">Total Expected Yield (qt)</th>

                                                @php
                                                    // Define months for each quarter - Pagumen has no weeks
                                                    $quarters = [
                                                        'Q1' => ['Meskerem', 'Tikimt', 'Hidar'],
                                                        'Q2' => ['Tahsas', 'Tir', 'Yekatit'],
                                                        'Q3' => ['Megabit', 'Miyazia', 'Ginbot'],
                                                        'Q4' => ['Sene', 'Hamle', 'Nehase', 'Pagumen']
                                                    ];
                                                    
                                                    // Define months that have weeks (all except Pagumen)
                                                    $monthsWithWeeks = ['Meskerem', 'Tikimt', 'Hidar', 'Tahsas', 'Tir', 'Yekatit', 'Megabit', 'Miyazia', 'Ginbot', 'Sene', 'Hamle', 'Nehase'];
                                                @endphp
                                                
                                                @foreach($quarters as $quarter => $months)
                                                    @php
                                                        $colspan = 1; // Quarter total column
                                                        foreach($months as $month) {
                                                            if (in_array($month, $monthsWithWeeks)) {
                                                                $colspan += 5; // Month total + 4 weeks
                                                            } else {
                                                                $colspan += 1; // Only month total (no weeks for Pagumen)
                                                            }
                                                        }
                                                    @endphp
                                                    <th colspan="{{ $colspan }}" class="text-center bg-info">{{ $quarter }}</th>
                                                @endforeach

                                                <th colspan="2" class="text-center bg-warning">Yield Summary</th>
                                            </tr>
                                            <tr>
                                                @foreach($quarters as $quarter => $months)
                                                    <th class="text-center bg-primary">{{ $quarter }} Total</th>
                                                    
                                                    @foreach($months as $month)
                                                        @if(in_array($month, $monthsWithWeeks))
                                                            <th colspan="5" class="text-center bg-success">{{ $month }}</th>
                                                        @else
                                                            <th class="text-center bg-success">{{ $month }}</th>
                                                        @endif
                                                    @endforeach
                                                @endforeach

                                                <th class="text-center bg-warning">Distributed Yield (qt)</th>
                                                <th class="text-center bg-warning">Remaining Yield (qt)</th>
                                            </tr>
                                            <tr>
                                                @foreach($quarters as $quarter => $months)
                                                    <th class="text-center bg-lightblue">Yield (qt)</th>
                                                    
                                                    @foreach($months as $month)
                                                        <th class="text-center bg-teal">{{ $month }} Total</th>
                                                        
                                                        @if(in_array($month, $monthsWithWeeks))
                                                            @for($week = 1; $week <= 4; $week++)
                                                                <th class="text-center bg-olive">W{{ $week }}</th>
                                                            @endfor
                                                        @endif
                                                    @endforeach
                                                @endforeach

                                                <th class="text-center bg-warning">Yield (qt)</th>
                                                <th class="text-center bg-warning">Yield (qt)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $counter = 1;
                                                $totalYield = 0;
                                                $totalDistributedYield = 0;
                                                
                                                // Group data by Planning Year and Crop Type
                                                $groupedPlans = [];
                                                foreach($transformedPlans as $plan) {
                                                    $year = $plan['year'];
                                                    $cropType = $plan['crop_type'];
                                                    if (!isset($groupedPlans[$year])) {
                                                        $groupedPlans[$year] = [];
                                                    }
                                                    if (!isset($groupedPlans[$year][$cropType])) {
                                                        $groupedPlans[$year][$cropType] = [];
                                                    }
                                                    $groupedPlans[$year][$cropType][] = $plan;
                                                }
                                                
                                                // Initialize totals arrays
                                                $quarterTotals = [
                                                    'Q1' => ['yield' => 0],
                                                    'Q2' => ['yield' => 0],
                                                    'Q3' => ['yield' => 0],
                                                    'Q4' => ['yield' => 0]
                                                ];
                                                $monthTotals = [];
                                                $weekTotals = [];
                                                
                                                foreach($quarters as $quarterMonths) {
                                                    foreach($quarterMonths as $month) {
                                                        $monthTotals[$month] = ['yield' => 0];
                                                        if (in_array($month, $monthsWithWeeks)) {
                                                            for($week = 1; $week <= 4; $week++) {
                                                                $weekKey = $month . ' W' . $week;
                                                                $weekTotals[$weekKey] = ['yield' => 0];
                                                            }
                                                        }
                                                    }
                                                }
                                            @endphp

                                            @foreach($groupedPlans as $year => $cropTypes)
                                                @php
                                                    $yearRowspan = 0;
                                                    foreach($cropTypes as $cropTypePlans) {
                                                        $yearRowspan += count($cropTypePlans);
                                                    }
                                                    $yearFirst = true;
                                                @endphp
                                                
                                                @foreach($cropTypes as $cropType => $cropTypePlans)
                                                    @php
                                                        $cropTypeRowspan = count($cropTypePlans);
                                                        $cropTypeFirst = true;
                                                        $cropTypeTotalYield = 0;
                                                        $cropTypeDistributedYield = 0;
                                                        
                                                        // Calculate crop type totals
                                                        foreach($cropTypePlans as $plan) {
                                                            $cropTypeTotalYield += $plan['expected_yield'];
                                                            $cropTypeDistributedYield += $plan['total_distributed_yield'] ?? 0;
                                                        }
                                                    @endphp
                                                    
                                                    @foreach($cropTypePlans as $plan)
                                                        @php
                                                            // Use the processed data from controller
                                                            $quartersData = $plan['quarters_data'] ?? [];
                                                            $monthsData = $plan['months_data'] ?? [];
                                                            $weeksData = $plan['weeks_data'] ?? [];
                                                            $productionplanDetails = $plan['productionplan_details'] ?? [];
                                                            $productionYear = $plan['production_year'] ?? $plan['year'];

                                                            $distributedYield = $plan['total_distributed_yield'] ?? 0;
                                                            $remainingYield = $plan['expected_yield'] - $distributedYield;

                                                            $totalYield += $plan['expected_yield'];
                                                            $totalDistributedYield += $distributedYield;
                                                            
                                                            // Process weekly production data from the structured data
                                                            $weekDataByMonth = [];
                                                            
                                                            // Method 1: Process from the processed weeks data
                                                            foreach ($weeksData as $weekKey => $weekItem) {
                                                                if (isset($weekItem['yield']) && $weekItem['yield'] > 0) {
                                                                    $monthName = $weekItem['month'] ?? '';
                                                                    $weekNum = $weekItem['week_number'] ?? 1;
                                                                    
                                                                    if ($monthName && $weekNum >= 1 && $weekNum <= 4) {
                                                                        if (!isset($weekDataByMonth[$monthName])) {
                                                                            $weekDataByMonth[$monthName] = [];
                                                                        }
                                                                        $weekDataByMonth[$monthName][$weekNum] = $weekItem['yield'];
                                                                    }
                                                                }
                                                            }
                                                            
                                                            // Method 2: Process from the original raw data if weeksData is empty
                                                            if (empty($weekDataByMonth) && isset($productionplanDetails['quarters'])) {
                                                                foreach ($productionplanDetails['quarters'] as $quarterData) {
                                                                    if (isset($quarterData['months'])) {
                                                                        foreach ($quarterData['months'] as $monthData) {
                                                                            if (isset($monthData['month']) && isset($monthData['weeks'])) {
                                                                                $monthName = $monthData['month'];
                                                                                foreach ($monthData['weeks'] as $weekData) {
                                                                                    if (isset($weekData['week'])) {
                                                                                        $weekNum = $weekData['week'];
                                                                                        $yield = floatval($weekData['planned'] ?? 0);
                                                                                        
                                                                                        if ($yield > 0) {
                                                                                            if (!isset($weekDataByMonth[$monthName])) {
                                                                                                $weekDataByMonth[$monthName] = [];
                                                                                            }
                                                                                            $weekDataByMonth[$monthName][$weekNum] = $yield;
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        @endphp

                                                        <tr>
                                                            <td class="text-center font-weight-bold">{{ $counter++ }}</td>
                                                            
                                                            @if($yearFirst && $cropTypeFirst)
                                                                <td rowspan="{{ $yearRowspan }}" class="text-center align-middle font-weight-bold bg-gray-light">
                                                                    {{ $year }}
                                                                </td>
                                                            @endif
                                                            
                                                            @if($cropTypeFirst)
                                                                <td rowspan="{{ $cropTypeRowspan }}" class="text-center align-middle font-weight-bold bg-gray-light">
                                                                    {{ $cropType }}
                                                                    <br>
                                                                    <small class="text-muted">Total: {{ number_format($cropTypeTotalYield, 2) }} qt</small>
                                                                </td>
                                                            @endif
                                                            
                                                            <td class="font-weight-bold text-primary">{{ $plan['crop_name'] }}</td>
                                                            <td class="text-center">{{ $plan['unit'] }}</td>
                                                            <td class="text-center font-weight-bold text-info">{{ $productionYear }}</td>
                                                            <td class="text-center">{{ $plan['season'] }}</td>
                                                            <td class="text-right font-weight-bold">{{ number_format($plan['expected_yield'], 2) }}</td>

                                                            @foreach($quarters as $quarter => $months)
                                                                @php
                                                                    // Quarter data extraction from processed data
                                                                    $quarterYield = 0;
                                                                    if (isset($quartersData[$quarter])) {
                                                                        if (is_array($quartersData[$quarter])) {
                                                                            $quarterYield = $quartersData[$quarter]['yield'] ?? 0;
                                                                        } else {
                                                                            $quarterYield = floatval($quartersData[$quarter]);
                                                                        }
                                                                    }
                                                                    $quarterTotals[$quarter]['yield'] += $quarterYield;
                                                                @endphp
                                                                
                                                                <td class="text-right font-weight-bold {{ $quarterYield > 0 ? 'bg-lightblue' : '' }}">
                                                                    {{ number_format($quarterYield, 2) }}
                                                                </td>
                                                                
                                                                @foreach($months as $month)
                                                                    @php
                                                                        // Month data extraction from processed data
                                                                        $monthYield = 0;
                                                                        if (isset($monthsData[$month])) {
                                                                            if (is_array($monthsData[$month])) {
                                                                                $monthYield = $monthsData[$month]['yield'] ?? 0;
                                                                            } else {
                                                                                $monthYield = floatval($monthsData[$month]);
                                                                            }
                                                                        }
                                                                        $monthTotals[$month]['yield'] += $monthYield;
                                                                    @endphp
                                                                    
                                                                    <td class="text-right font-weight-bold {{ $monthYield > 0 ? 'bg-teal' : '' }}">
                                                                        {{ number_format($monthYield, 2) }}
                                                                    </td>
                                                                    
                                                                    @if(in_array($month, $monthsWithWeeks))
                                                                        @for($week = 1; $week <= 4; $week++)
                                                                            @php
                                                                                $weekKey = $month . ' W' . $week;
                                                                                $weekYield = 0;
                                                                                
                                                                                // Get week data from the processed weekly production
                                                                                if (isset($weekDataByMonth[$month][$week])) {
                                                                                    $weekYield = $weekDataByMonth[$month][$week];
                                                                                }
                                                                                
                                                                                $weekTotals[$weekKey]['yield'] += $weekYield;
                                                                            @endphp
                                                                            <td class="text-right {{ $weekYield > 0 ? 'bg-olive' : '' }}">
                                                                                {{ number_format($weekYield, 2) }}
                                                                            </td>
                                                                        @endfor
                                                                    @endif
                                                                @endforeach
                                                            @endforeach

                                                            <td class="text-right font-weight-bold bg-warning distributed-yield">
                                                                {{ number_format($distributedYield, 2) }}
                                                            </td>
                                                            <td class="text-right font-weight-bold bg-warning remaining-yield {{ $remainingYield >= 0 ? 'text-success' : 'text-danger' }}">
                                                                {{ number_format($remainingYield, 2) }}
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $yearFirst = false;
                                                            $cropTypeFirst = false;
                                                        @endphp
                                                    @endforeach
                                                @endforeach
                                            @endforeach

                                            <tr class="bg-gray-dark text-white font-weight-bold">
                                                <td colspan="7" class="text-center">GRAND TOTAL</td>
                                                <td class="text-right">{{ number_format($totalYield, 2) }}</td>
                                                
                                                @foreach($quarters as $quarter => $months)
                                                    <td class="text-right bg-primary">{{ number_format($quarterTotals[$quarter]['yield'], 2) }}</td>
                                                    
                                                    @foreach($months as $month)
                                                        <td class="text-right bg-success">{{ number_format($monthTotals[$month]['yield'], 2) }}</td>
                                                        
                                                        @if(in_array($month, $monthsWithWeeks))
                                                            @for($week = 1; $week <= 4; $week++)
                                                                @php
                                                                    $weekKey = $month . ' W' . $week;
                                                                @endphp
                                                                <td class="text-right bg-olive">{{ number_format($weekTotals[$weekKey]['yield'], 2) }}</td>
                                                            @endfor
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                                
                                                <td class="text-right bg-warning">{{ number_format($totalDistributedYield, 2) }}</td>
                                                <td class="text-right bg-warning {{ ($totalYield - $totalDistributedYield) >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ number_format($totalYield - $totalDistributedYield, 2) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer clearfix">
                                <div class="float-left">
                                    <div class="badge badge-primary">Q = Quarter</div>
                                    <div class="badge badge-success ml-1">W = Week</div>
                                    <div class="badge badge-warning ml-1">qt = Quintals</div>
                                </div>
                                <div class="float-right">
                                    <span class="text-muted">
                                        Showing {{ $counter-1 }} production plans
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        function exportToExcel() {
            const table = document.getElementById('productionTable');
            const html = table.outerHTML;
            const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'production-yield-distribution-' + new Date().toISOString().split('T')[0] + '.xls';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            toastr.success('Excel file downloaded successfully!');
        }

        $(document).ready(function() {
            $('.table-responsive').on('scroll', function() {
                $('.sticky-top').css('top', this.scrollTop + 'px');
            });

            $('#productionTable tbody tr').hover(
                function() {
                    $(this).addClass('bg-gray');
                },
                function() {
                    $(this).removeClass('bg-gray');
                }
            );

            $('#productionTable thead th.bg-success').each(function() {
                const monthName = $(this).text().trim();
                if (monthName !== 'Pagumen') {
                    $(this).css('cursor', 'pointer');
                    $(this).attr('title', 'Click to toggle weeks visibility');
                    $(this).click(function() {
                        toggleWeeksVisibility(monthName);
                    });
                }
            });

            $('[title]').tooltip({
                placement: 'top',
                trigger: 'hover'
            });

            $('.remaining-yield.text-danger').each(function() {
                $(this).closest('tr').addClass('bg-danger-light');
            });
        });

        function toggleWeeksVisibility(month) {
            const allHeaders = $('#productionTable thead th, #productionTable thead td');
            let weekColumns = [];
            
            allHeaders.each(function(index) {
                if ($(this).text().trim() === month && $(this).hasClass('bg-success')) {
                    for (let i = 1; i <= 4; i++) {
                        const weekHeader = allHeaders.eq(index + i + 1);
                        if (weekHeader.length && weekHeader.hasClass('bg-olive')) {
                            weekColumns.push(weekHeader);
                        }
                    }
                }
            });

            const isHidden = weekColumns.length > 0 && weekColumns[0].css('display') === 'none';
            
            weekColumns.forEach(function(weekHeader) {
                const columnIndex = weekHeader.index();
                
                if (isHidden) {
                    weekHeader.show();
                    $('#productionTable tbody tr td').eq(columnIndex).show();
                } else {
                    weekHeader.hide();
                    $('#productionTable tbody tr td').eq(columnIndex).hide();
                }
            });
            
            toastr.info('Week columns for ' + month + ' are now ' + (isHidden ? 'visible' : 'hidden'));
        }
    </script>

    <style>
        .table-head-fixed {
            position: relative;
        }
        
        .table-head-fixed thead {
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .bg-gray-light {
            background-color: #f8f9fa !important;
        }
        
        .bg-info { background-color: #17a2b8 !important; color: white !important; }
        .bg-primary { background-color: #007bff !important; color: white !important; }
        .bg-success { background-color: #28a745 !important; color: white !important; }
        .bg-warning { background-color: #ffc107 !important; color: #212529 !important; }
        .bg-lightblue { background-color: #3c8dbc !important; color: white !important; }
        .bg-teal { background-color: #20c997 !important; color: white !important; }
        .bg-olive { background-color: #3d9970 !important; color: white !important; }
        .bg-gray-dark { background-color: #343a40 !important; color: white !important; }
        
        .bg-danger-light {
            background-color: rgba(220, 53, 69, 0.1) !important;
        }
        
        .distributed-yield, .remaining-yield {
            border-left: 3px solid #ffc107 !important;
            font-weight: 700 !important;
            font-size: 0.85rem !important;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }
        
        .table-hover tbody tr:hover .distributed-yield,
        .table-hover tbody tr:hover .remaining-yield {
            background-color: #e0a800 !important;
        }
        
        .card {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        }
        
        @media (max-width: 768px) {
            .table-responsive { 
                font-size: 0.75rem; 
            }
            .table th, .table td { 
                padding: 0.3rem; 
            }
            .distributed-yield, .remaining-yield { 
                font-size: 0.75rem !important; 
            }
        }
        
        @media print {
            .card-header .card-tools { 
                display: none !important; 
            }
            .card-footer { 
                display: none !important; 
            }
            .table-responsive { 
                overflow: visible !important; 
                max-height: none !important;
            }
            .card { 
                border: none !important; 
                box-shadow: none !important; 
            }
            .distributed-yield, .remaining-yield { 
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
        
        .remaining-yield.text-success {
            color: #28a745 !important;
            font-weight: 800 !important;
        }
        
        .remaining-yield.text-danger {
            color: #dc3545 !important;
            font-weight: 800 !important;
        }
        
        .table th {
            vertical-align: middle !important;
            text-align: center !important;
        }
        
        .sticky-top {
            z-index: 1020 !important;
        }
    </style>
</x-layout>