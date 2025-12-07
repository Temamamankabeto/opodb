<x-layout>
    <div class="content-header">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Crop Plan Yield Distribution</h1>
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
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-bar mr-2"></i>
                                    Crop Plan Yield Distribution - Grouped by Year & Crop Type
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
                                    <table class="table table-bordered table-hover table-head-fixed text-nowrap mb-0" id="productionPlanTable">
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
                                                    <!-- Quarter Header spans all months and weeks in the quarter -->
                                                    <th colspan="{{ $colspan }}" class="text-center bg-info">{{ $quarter }}</th>
                                                @endforeach

                                                <!-- Summary Columns -->
                                                <th colspan="2" class="text-center bg-warning">Yield Summary</th>
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
                                                <th class="text-center bg-warning">Distributed Yield (qt)</th>
                                                <th class="text-center bg-warning">Remaining Yield (qt)</th>
                                            </tr>
                                            <tr>
                                                @foreach($quarters as $quarter => $months)
                                                    <!-- Quarter Total Sub-columns -->
                                                    <th class="text-center bg-lightblue">Yield (qt)</th>
                                                    
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
                                                $yearTotals = [];
                                                $cropTypeTotals = [];
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
                                                            $cropTypeDistributedYield += $plan['production_data']['total_distributed_yield'] ?? 0;
                                                        }
                                                        
                                                        if (!isset($yearTotals[$year])) {
                                                            $yearTotals[$year] = ['yield' => 0, 'distributed' => 0];
                                                        }
                                                        if (!isset($cropTypeTotals[$cropType])) {
                                                            $cropTypeTotals[$cropType] = ['yield' => 0, 'distributed' => 0];
                                                        }
                                                        $yearTotals[$year]['yield'] += $cropTypeTotalYield;
                                                        $yearTotals[$year]['distributed'] += $cropTypeDistributedYield;
                                                        $cropTypeTotals[$cropType]['yield'] += $cropTypeTotalYield;
                                                        $cropTypeTotals[$cropType]['distributed'] += $cropTypeDistributedYield;
                                                    @endphp
                                                    
                                                    @foreach($cropTypePlans as $plan)
                                                        @php
                                                            $productionData = $plan['production_data'];
                                                            $quartersData = $productionData['quarters'] ?? [];
                                                            $monthsData = $productionData['months'] ?? [];
                                                            $weeksData = $productionData['weeks'] ?? [];
                                                            $weeklyProduction = $plan['productionplan_details']['weekly_production'] ?? [];
                                                            $productionYear = $productionData['production_year'] ?? $plan['year'];

                                                            $distributedYield = $productionData['total_distributed_yield'] ?? 0;
                                                            $remainingYield = $plan['expected_yield'] - $distributedYield;

                                                            $totalYield += $plan['expected_yield'];
                                                            $totalDistributedYield += $distributedYield;
                                                            
                                                            // Process weekly production data
                                                            $weekDataByMonth = [];
                                                            
                                                            // Method 1: Process from the structured weeks data (backend processed)
                                                            foreach ($weeksData as $weekKey => $weekItem) {
                                                                // Handle "Ginbot_week_1" format
                                                                if (preg_match('/^(.+)_week_(\d+)$/', $weekKey, $matches)) {
                                                                    $monthName = $matches[1];
                                                                    $weekNum = (int)$matches[2];
                                                                    $yield = $weekItem['yield'] ?? 0;
                                                                    
                                                                    if ($monthName && $weekNum >= 1 && $weekNum <= 4) {
                                                                        if (!isset($weekDataByMonth[$monthName])) {
                                                                            $weekDataByMonth[$monthName] = [];
                                                                        }
                                                                        $weekDataByMonth[$monthName][$weekNum] = $yield;
                                                                    }
                                                                }
                                                            }
                                                            
                                                            // Method 2: Process from raw weekly_production array (if weeksData is empty)
                                                            if (empty($weekDataByMonth) && !empty($weeklyProduction)) {
                                                                foreach ($weeklyProduction as $weekItem) {
                                                                    if (is_array($weekItem)) {
                                                                        $monthName = $weekItem['month'] ?? '';
                                                                        $weekText = $weekItem['week'] ?? '';
                                                                        $yield = $weekItem['yield'] ?? 0;
                                                                        
                                                                        // Extract week number from "Week 1", "Week 2", etc.
                                                                        $weekNum = 0;
                                                                        if (preg_match('/Week\s*(\d+)/i', $weekText, $matches)) {
                                                                            $weekNum = (int)$matches[1];
                                                                        }
                                                                        
                                                                        if ($monthName && $weekNum >= 1 && $weekNum <= 4) {
                                                                            if (!isset($weekDataByMonth[$monthName])) {
                                                                                $weekDataByMonth[$monthName] = [];
                                                                            }
                                                                            $weekDataByMonth[$monthName][$weekNum] = $yield;
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
                                                                    <small class="text-muted">Total: {{ number_format($cropTypeTotalYield, 2) }} qt</small>
                                                                </td>
                                                            @endif
                                                            
                                                            <!-- Individual plan data -->
                                                            <td class="font-weight-bold text-primary">{{ $plan['crop_name'] }}</td>
                                                            <td class="text-center">{{ $plan['unit'] }}</td>
                                                            <td class="text-center font-weight-bold text-info">{{ $productionYear }}</td>
                                                            <td class="text-center">{{ $plan['season'] }}</td>
                                                            <td class="text-right font-weight-bold">{{ number_format($plan['expected_yield'], 2) }}</td>

                                                            <!-- Quarter, Month, and Week Data -->
                                                            @foreach($quarters as $quarter => $months)
                                                                @php
                                                                    // Quarter data extraction
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
                                                                
                                                                <!-- Quarter Total -->
                                                                <td class="text-right font-weight-bold {{ $quarterYield > 0 ? 'bg-lightblue' : '' }}">
                                                                    {{ number_format($quarterYield, 2) }}
                                                                </td>
                                                                
                                                                <!-- Month Data for this Quarter -->
                                                                @foreach($months as $month)
                                                                    @php
                                                                        // Month data extraction
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
                                                                    
                                                                    <!-- Month Total -->
                                                                    <td class="text-right font-weight-bold {{ $monthYield > 0 ? 'bg-teal' : '' }}">
                                                                        {{ number_format($monthYield, 2) }}
                                                                    </td>
                                                                    
                                                                    <!-- Week Data for this Month (only for months with weeks) -->
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

                                                            <!-- Summary Data -->
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

                                            <!-- Grand Total Row -->
                                            <tr class="bg-gray-dark text-white font-weight-bold">
                                                <td colspan="7" class="text-center">GRAND TOTAL</td>
                                                <td class="text-right">{{ number_format($totalYield, 2) }}</td>
                                                
                                                <!-- Quarter Totals -->
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
                                                
                                                <!-- Summary Totals -->
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
            const table = document.getElementById('productionPlanTable');
            const html = table.outerHTML;
            const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'production-plan-yield-' + new Date().toISOString().split('T')[0] + '.xls';
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
            $('#productionPlanTable tbody tr').hover(
                function() {
                    $(this).addClass('bg-gray');
                },
                function() {
                    $(this).removeClass('bg-gray');
                }
            );

            // Week visibility toggle (only for months with weeks)
            $('#productionPlanTable thead th.bg-success').each(function() {
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

            // Add highlight for negative remaining yields
            $('.remaining-yield.text-danger').each(function() {
                $(this).closest('tr').addClass('bg-danger-light');
            });
        });

        function toggleWeeksVisibility(month) {
            const allHeaders = $('#productionPlanTable thead th, #productionPlanTable thead td');
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
                    $('#productionPlanTable tbody tr td').eq(columnIndex).show();
                } else {
                    weekHeader.hide();
                    $('#productionPlanTable tbody tr td').eq(columnIndex).hide();
                }
            });
            
            // Show notification
            toastr.info('Week columns for ' + month + ' are now ' + (isHidden ? 'visible' : 'hidden'));
        }

        // Add filter functionality
        function filterTable() {
            const yearFilter = $('#yearFilter').val();
            const cropTypeFilter = $('#cropTypeFilter').val();
            
            $('#productionPlanTable tbody tr:not(.bg-gray-dark)').each(function() {
                const year = $(this).find('td:nth-child(2)').text().trim();
                const cropType = $(this).find('td:nth-child(3)').text().trim();
                
                let showRow = true;
                
                if (yearFilter && year !== yearFilter) {
                    showRow = false;
                }
                
                if (cropTypeFilter && cropType !== cropTypeFilter) {
                    showRow = false;
                }
                
                if (showRow) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
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
        
        /* Light background for danger rows */
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
        
        .card-success .card-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
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
            .distributed-yield, .remaining-yield { 
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
        
        /* Highlight positive/negative remaining yields */
        .remaining-yield.text-success {
            color: #28a745 !important;
            font-weight: 800 !important;
        }
        
        .remaining-yield.text-danger {
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
        
        /* Card success header styling */
        .card-success:not(.card-outline) .card-header {
            border-bottom: none;
        }
        
        /* Responsive adjustments */
        @media (max-width: 576px) {
            .card-footer .float-left, 
            .card-footer .float-right {
                float: none !important;
                text-align: center;
                margin-bottom: 5px;
            }
        }
    </style>
</x-layout>