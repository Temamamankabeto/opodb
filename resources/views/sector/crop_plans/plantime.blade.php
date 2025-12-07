<x-layout>
    <div class="content-header">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h6>Crop Plan Area Distribution</h6>
                    </div>
                    <div class="col-sm-6">
                         
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    Crop Plan Area Distribution - Grouped by Year & Crop Type
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
                                    <table class="table table-bordered table-hover table-head-fixed text-nowrap mb-0" id="cropPlanTable">
                                        <thead class="thead-light sticky-top">
                                            <tr>
                                                <th rowspan="4" class="text-center align-middle bg-gray">#</th>
                                                <th rowspan="4" class="text-center align-middle bg-gray">Planning Year</th>
                                                <th rowspan="4" class="text-center align-middle bg-gray">Crop Type</th>
                                                <th rowspan="4" class="text-center align-middle bg-gray">Crop Variety</th>
                                                <th rowspan="4" class="text-center align-middle bg-gray">Unit</th>
                                                <th rowspan="4" class="text-center align-middle bg-gray">Preparation Year</th>
                                                <th rowspan="4" class="text-center align-middle bg-gray">Season</th>
                                                <th rowspan="4" class="text-center align-middle bg-gray">Total Area (ha)</th>

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
                                                    <!-- Quarter Header spans all months and weeks in the quarter -->
                                                    <th colspan="{{ $colspan }}" class="text-center bg-info">{{ $quarter }}</th>
                                                @endforeach

                                                <!-- Summary Columns -->
                                                <th colspan="2" class="text-center bg-warning">Area Summary</th>
                                            </tr>
                                            <tr>
                                                @foreach($quarters as $quarter => $months)
                                                    <!-- Quarter Total -->
                                                    <th class="text-center bg-primary">{{ $quarter }} Total</th>
                                                    
                                                    @foreach($months as $month)
                                                        @if(in_array($month, $monthsWithWeeks))
                                                            <!-- Month Header spans all weeks in the month -->
                                                            <th colspan="5" class="text-center bg-success">{{ $month }}</th>
                                                        @else
                                                            <!-- Pagumen - only month header, no weeks -->
                                                            <th class="text-center bg-success">{{ $month }}</th>
                                                        @endif
                                                    @endforeach
                                                @endforeach

                                                <!-- Summary Sub-columns -->
                                                <th class="text-center bg-warning">Distributed Area (ha)</th>
                                                <th class="text-center bg-warning">Remaining Area (ha)</th>
                                            </tr>
                                            <tr>
                                                @foreach($quarters as $quarter => $months)
                                                    <!-- Quarter Total Sub-columns -->
                                                    <th class="text-center bg-lightblue">Area (ha)</th>
                                                    
                                                    @foreach($months as $month)
                                                        <!-- Month Total -->
                                                        <th class="text-center bg-teal">{{ $month }} Total</th>
                                                        
                                                        <!-- Weeks for this Month (only for months with weeks) -->
                                                        @if(in_array($month, $monthsWithWeeks))
                                                            @for($week = 1; $week <= 4; $week++)
                                                                <th class="text-center bg-olive">W{{ $week }}</th>
                                                            @endfor
                                                        @endif
                                                    @endforeach
                                                @endforeach

                                                <!-- Summary Sub-columns (duplicated for alignment) -->
                                                <th class="text-center bg-warning">Area (ha)</th>
                                                <th class="text-center bg-warning">Area (ha)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $counter = 1;
                                                $totalArea = 0;
                                                $totalDistributedArea = 0;
                                                
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
                                                $yearTotals = [];
                                                $cropTypeTotals = [];
                                                $quarterTotals = [
                                                    'Q1' => ['area' => 0],
                                                    'Q2' => ['area' => 0],
                                                    'Q3' => ['area' => 0],
                                                    'Q4' => ['area' => 0]
                                                ];
                                                $monthTotals = [];
                                                $weekTotals = [];
                                                
                                                foreach($quarters as $quarterMonths) {
                                                    foreach($quarterMonths as $month) {
                                                        $monthTotals[$month] = ['area' => 0];
                                                        if (in_array($month, $monthsWithWeeks)) {
                                                            for($week = 1; $week <= 4; $week++) {
                                                                $weekKey = $month . ' W' . $week;
                                                                $weekTotals[$weekKey] = ['area' => 0];
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
                                                        $cropTypeTotalArea = 0;
                                                        $cropTypeDistributedArea = 0;
                                                        
                                                        // Calculate crop type totals
                                                        foreach($cropTypePlans as $plan) {
                                                            $cropTypeTotalArea += $plan['area_to_plant'];
                                                            $cropTypeDistributedArea += $plan['timeline_data']['total_distributed_area'] ?? 0;
                                                        }
                                                        
                                                        if (!isset($yearTotals[$year])) {
                                                            $yearTotals[$year] = ['area' => 0, 'distributed' => 0];
                                                        }
                                                        if (!isset($cropTypeTotals[$cropType])) {
                                                            $cropTypeTotals[$cropType] = ['area' => 0, 'distributed' => 0];
                                                        }
                                                        $yearTotals[$year]['area'] += $cropTypeTotalArea;
                                                        $yearTotals[$year]['distributed'] += $cropTypeDistributedArea;
                                                        $cropTypeTotals[$cropType]['area'] += $cropTypeTotalArea;
                                                        $cropTypeTotals[$cropType]['distributed'] += $cropTypeDistributedArea;
                                                    @endphp
                                                    
                                                    @foreach($cropTypePlans as $plan)
                                                        @php
                                                            $timelineData = $plan['timeline_data'];
                                                            $quartersData = $timelineData['quarters'] ?? [];
                                                            $monthsData = $timelineData['months'] ?? [];
                                                            $weeksData = $timelineData['weeks'] ?? [];
                                                            $weeklyDistribution = $plan['plandivision_details']['weekly_distribution'] ?? [];
                                                            $preparationYear = $timelineData['preparation_year'] ?? 'N/A';

                                                            $distributedArea = $timelineData['total_distributed_area'] ?? 0;
                                                            $remainingArea = $plan['area_to_plant'] - $distributedArea;

                                                            $totalArea += $plan['area_to_plant'];
                                                            $totalDistributedArea += $distributedArea;
                                                            
                                                            // Process weekly distribution data
                                                            $weekDataByMonth = [];
                                                            
                                                            // Method 1: Process from the structured weeks data (backend processed)
                                                            foreach ($weeksData as $weekKey => $weekItem) {
                                                                // Handle "Ginbot_week_1" format
                                                                if (preg_match('/^(.+)_week_(\d+)$/', $weekKey, $matches)) {
                                                                    $monthName = $matches[1];
                                                                    $weekNum = (int)$matches[2];
                                                                    $area = $weekItem['area'] ?? 0;
                                                                    
                                                                    if ($monthName && $weekNum >= 1 && $weekNum <= 4) {
                                                                        if (!isset($weekDataByMonth[$monthName])) {
                                                                            $weekDataByMonth[$monthName] = [];
                                                                        }
                                                                        $weekDataByMonth[$monthName][$weekNum] = $area;
                                                                    }
                                                                }
                                                            }
                                                            
                                                            // Method 2: Process from raw weekly_distribution array (if weeksData is empty)
                                                            if (empty($weekDataByMonth) && !empty($weeklyDistribution)) {
                                                                foreach ($weeklyDistribution as $weekItem) {
                                                                    if (is_array($weekItem)) {
                                                                        $monthName = $weekItem['month'] ?? '';
                                                                        $weekText = $weekItem['week'] ?? '';
                                                                        $area = $weekItem['area'] ?? 0;
                                                                        
                                                                        // Extract week number from "Week 1", "Week 2", etc.
                                                                        $weekNum = 0;
                                                                        if (preg_match('/Week\s*(\d+)/i', $weekText, $matches)) {
                                                                            $weekNum = (int)$matches[1];
                                                                        }
                                                                        
                                                                        if ($monthName && $weekNum >= 1 && $weekNum <= 4) {
                                                                            if (!isset($weekDataByMonth[$monthName])) {
                                                                                $weekDataByMonth[$monthName] = [];
                                                                            }
                                                                            $weekDataByMonth[$monthName][$weekNum] = $area;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        @endphp

                                                        <tr>
                                                            <!-- Row counter -->
                                                            <td class="text-center font-weight-bold">{{ $counter++ }}</td>
                                                            
                                                            <!-- Year (with rowspan) -->
                                                            @if($yearFirst && $cropTypeFirst)
                                                                <td rowspan="{{ $yearRowspan }}" class="text-center align-middle font-weight-bold bg-gray-light">
                                                                    {{ $year }}
                                                                </td>
                                                            @endif
                                                            
                                                            <!-- Crop Type (with rowspan) -->
                                                            @if($cropTypeFirst)
                                                                <td rowspan="{{ $cropTypeRowspan }}" class="text-center align-middle font-weight-bold bg-gray-light">
                                                                    {{ $cropType }}
                                                                    <br>
                                                                    <small class="text-muted">Total: {{ number_format($cropTypeTotalArea, 2) }} ha</small>
                                                                </td>
                                                            @endif
                                                            
                                                            <!-- Individual plan data -->
                                                            <td class="font-weight-bold text-primary">{{ $plan['crop_name'] }}</td>
                                                            <td class="text-center">{{ $plan['unit'] }}</td>
                                                            <td class="text-center font-weight-bold text-info">{{ $preparationYear }}</td>
                                                            <td class="text-center">{{ $plan['season'] }}</td>
                                                            <td class="text-right font-weight-bold">{{ number_format($plan['area_to_plant'], 2) }}</td>

                                                            <!-- Quarter, Month, and Week Data -->
                                                            @foreach($quarters as $quarter => $months)
                                                                @php
                                                                    // Quarter data extraction
                                                                    $quarterArea = 0;
                                                                    if (isset($quartersData[$quarter])) {
                                                                        if (is_array($quartersData[$quarter])) {
                                                                            $quarterArea = $quartersData[$quarter]['area'] ?? 0;
                                                                        } else {
                                                                            $quarterArea = floatval($quartersData[$quarter]);
                                                                        }
                                                                    }
                                                                    $quarterTotals[$quarter]['area'] += $quarterArea;
                                                                @endphp
                                                                
                                                                <!-- Quarter Total -->
                                                                <td class="text-right font-weight-bold {{ $quarterArea > 0 ? 'bg-lightblue' : '' }}">
                                                                    {{ number_format($quarterArea, 2) }}
                                                                </td>
                                                                
                                                                <!-- Month Data for this Quarter -->
                                                                @foreach($months as $month)
                                                                    @php
                                                                        // Month data extraction
                                                                        $monthArea = 0;
                                                                        if (isset($monthsData[$month])) {
                                                                            if (is_array($monthsData[$month])) {
                                                                                $monthArea = $monthsData[$month]['area'] ?? 0;
                                                                            } else {
                                                                                $monthArea = floatval($monthsData[$month]);
                                                                            }
                                                                        }
                                                                        $monthTotals[$month]['area'] += $monthArea;
                                                                    @endphp
                                                                    
                                                                    <!-- Month Total -->
                                                                    <td class="text-right font-weight-bold {{ $monthArea > 0 ? 'bg-teal' : '' }}">
                                                                        {{ number_format($monthArea, 2) }}
                                                                    </td>
                                                                    
                                                                    <!-- Week Data for this Month (only for months with weeks) -->
                                                                    @if(in_array($month, $monthsWithWeeks))
                                                                        @for($week = 1; $week <= 4; $week++)
                                                                            @php
                                                                                $weekKey = $month . ' W' . $week;
                                                                                $weekArea = 0;
                                                                                
                                                                                // Get week data from the processed weekly distribution
                                                                                if (isset($weekDataByMonth[$month][$week])) {
                                                                                    $weekArea = $weekDataByMonth[$month][$week];
                                                                                }
                                                                                
                                                                                $weekTotals[$weekKey]['area'] += $weekArea;
                                                                            @endphp
                                                                            <td class="text-right {{ $weekArea > 0 ? 'bg-olive' : '' }}">
                                                                                {{ number_format($weekArea, 2) }}
                                                                            </td>
                                                                        @endfor
                                                                    @endif
                                                                @endforeach
                                                            @endforeach

                                                            <!-- Summary Data -->
                                                            <td class="text-right font-weight-bold bg-warning distributed-area">
                                                                {{ number_format($distributedArea, 2) }}
                                                            </td>
                                                            <td class="text-right font-weight-bold bg-warning remaining-area {{ $remainingArea >= 0 ? 'text-success' : 'text-danger' }}">
                                                                {{ number_format($remainingArea, 2) }}
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $yearFirst = false;
                                                            $cropTypeFirst = false;
                                                        @endphp
                                                    @endforeach
                                                @endforeach
                                            @endforeach

                                            <!-- Grand Total Row -->
                                            <tr class="bg-gray-dark text-white font-weight-bold">
                                                <td colspan="7" class="text-center">GRAND TOTAL</td>
                                                <td class="text-right">{{ number_format($totalArea, 2) }}</td>
                                                
                                                <!-- Quarter Totals -->
                                                @foreach($quarters as $quarter => $months)
                                                    <td class="text-right bg-primary">{{ number_format($quarterTotals[$quarter]['area'], 2) }}</td>
                                                    
                                                    @foreach($months as $month)
                                                        <td class="text-right bg-success">{{ number_format($monthTotals[$month]['area'], 2) }}</td>
                                                        
                                                        @if(in_array($month, $monthsWithWeeks))
                                                            @for($week = 1; $week <= 4; $week++)
                                                                @php
                                                                    $weekKey = $month . ' W' . $week;
                                                                @endphp
                                                                <td class="text-right bg-olive">{{ number_format($weekTotals[$weekKey]['area'], 2) }}</td>
                                                            @endfor
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                                
                                                <!-- Summary Totals -->
                                                <td class="text-right bg-warning">{{ number_format($totalDistributedArea, 2) }}</td>
                                                <td class="text-right bg-warning {{ ($totalArea - $totalDistributedArea) >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ number_format($totalArea - $totalDistributedArea, 2) }}
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
                                    <div class="badge badge-warning ml-1">ha = Hectares</div>
                                </div>
                                <div class="float-right">
                                    <span class="text-muted">
                                        Showing {{ $counter-1 }} crop plans
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
            const table = document.getElementById('cropPlanTable');
            const html = table.outerHTML;
            const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'crop-plan-grouped-' + new Date().toISOString().split('T')[0] + '.xls';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            // Show success message
            toastr.success('Excel file downloaded successfully!');
        }

        $(document).ready(function() {
            // Make table headers sticky
            $('.table-responsive').on('scroll', function() {
                $('.sticky-top').css('top', this.scrollTop + 'px');
            });

            // Hover effect for rows
            $('#cropPlanTable tbody tr').hover(
                function() {
                    $(this).addClass('bg-gray');
                },
                function() {
                    $(this).removeClass('bg-gray');
                }
            );

            // Week visibility toggle (only for months with weeks)
            $('#cropPlanTable thead th.bg-success').each(function() {
                const monthName = $(this).text().trim();
                if (monthName !== 'Pagumen') {
                    $(this).css('cursor', 'pointer');
                    $(this).attr('title', 'Click to toggle weeks visibility');
                    $(this).click(function() {
                        toggleWeeksVisibility(monthName);
                    });
                }
            });

            // Initialize tooltips
            $('[title]').tooltip({
                placement: 'top',
                trigger: 'hover'
            });
        });

        function toggleWeeksVisibility(month) {
            const allHeaders = $('#cropPlanTable thead th, #cropPlanTable thead td');
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
                    $('#cropPlanTable tbody tr td').eq(columnIndex).show();
                } else {
                    weekHeader.hide();
                    $('#cropPlanTable tbody tr td').eq(columnIndex).hide();
                }
            });
            
            // Show notification
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
        
        /* AdminLTE color overrides for better visibility */
        .bg-info { background-color: #17a2b8 !important; color: white !important; }
        .bg-primary { background-color: #007bff !important; color: white !important; }
        .bg-success { background-color: #28a745 !important; color: white !important; }
        .bg-warning { background-color: #ffc107 !important; color: #212529 !important; }
        .bg-lightblue { background-color: #3c8dbc !important; color: white !important; }
        .bg-teal { background-color: #20c997 !important; color: white !important; }
        .bg-olive { background-color: #3d9970 !important; color: white !important; }
        .bg-gray-dark { background-color: #343a40 !important; color: white !important; }
        
        .distributed-area, .remaining-area {
            border-left: 3px solid #ffc107 !important;
            font-weight: 700 !important;
            font-size: 0.85rem !important;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }
        
        .table-hover tbody tr:hover .distributed-area,
        .table-hover tbody tr:hover .remaining-area {
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
            .distributed-area, .remaining-area { 
                font-size: 0.75rem !important; 
            }
        }
        
        /* Print styles */
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
            .distributed-area, .remaining-area { 
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
        
        /* Highlight positive/negative remaining areas */
        .remaining-area.text-success {
            color: #28a745 !important;
            font-weight: 800 !important;
        }
        
        .remaining-area.text-danger {
            color: #dc3545 !important;
            font-weight: 800 !important;
        }
        
        /* Ensure proper spacing for table headers */
        .table th {
            vertical-align: middle !important;
            text-align: center !important;
        }
        
        /* Fix for sticky header z-index */
        .sticky-top {
            z-index: 1020 !important;
        }
    </style>
</x-layout>