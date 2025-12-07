<x-layout>
    <div class="content-header">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Mechanization Distribution Plan</h1>
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
                                    <i class="fas fa-tractor mr-2"></i>
                                    Mechanization Distribution Plan - Grouped by Year & Type
                                </h3>
                                <div class="card-tools">
                                    <button class="btn btn-sm btn-light" onclick="exportToExcel()" title="Export to Excel">
                                        <i class="fas fa-file-excel mr-1"></i> Export Excel
                                    </button>
                                    <button class="btn btn-sm btn-warning ml-1" onclick="toggleMechanizationBreakdown()" title="Toggle Mechanization Breakdown">
                                        <i class="fas fa-chart-pie mr-1"></i> Toggle Breakdown
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive" style="max-height: 70vh;">
                                    <table class="table table-bordered table-hover table-head-fixed text-nowrap mb-0" id="mechanizationPlanTable">
                                        <thead class="thead-light sticky-top">
                                            <tr>
                                                <th rowspan="4" class="text-center align-middle bg-gray">#</th>
                                                <th rowspan="4" class="text-center align-middle bg-gray">Planning Year</th>
                                                <th rowspan="4" class="text-center align-middle bg-gray">Mechanization Type</th>
                                                <th rowspan="4" class="text-center align-middle bg-gray">Mechanization Name</th>
                                                <th rowspan="4" class="text-center align-middle bg-gray">Unit</th>
                                                <th rowspan="4" class="text-center align-middle bg-gray">Application Year</th>
                                                <th rowspan="4" class="text-center align-middle bg-gray">Available Number</th>

                                                <!-- Mechanization Breakdown Columns (initially hidden) -->
                                                <th colspan="0" class="text-center bg-info mechanization-breakdown-header" style="display: none;">
                                                    Mechanization Breakdown
                                                </th>
                                            </tr>
                                            <tr>
                                                @php
                                                    // Define months for each quarter - Pagumen has no weeks
                                                    $quarters = [
                                                        'Q1' => ['Meskerem', 'Tikimt', 'Hidar'],
                                                        'Q2' => ['Tahsas', 'Tir', 'Yekatit'],
                                                        'Q3' => ['Megabit', 'Miyazia', 'Ginbot'],
                                                        'Q4' => ['Sene', 'Hamle', 'Nehase', 'Pagumen'],
                                                    ];

                                                    // Define months that have weeks (all except Pagumen)
                                                    $monthsWithWeeks = [
                                                        'Meskerem',
                                                        'Tikimt',
                                                        'Hidar',
                                                        'Tahsas',
                                                        'Tir',
                                                        'Yekatit',
                                                        'Megabit',
                                                        'Miyazia',
                                                        'Ginbot',
                                                        'Sene',
                                                        'Hamle',
                                                        'Nehase',
                                                    ];
                                                @endphp

                                                @foreach ($quarters as $quarter => $months)
                                                    @php
                                                        $colspan = 1; // Quarter total column
                                                        foreach ($months as $month) {
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
                                                <th colspan="2" class="text-center bg-warning">Number Summary</th>

                                                <!-- Mechanization Breakdown Sub-headers (initially hidden) -->
                                                <th colspan="0" class="text-center bg-info mechanization-breakdown-header" style="display: none;">
                                                    By Mechanization Type
                                                </th>
                                            </tr>
                                            <tr>
                                                @foreach ($quarters as $quarter => $months)
                                                    <!-- Quarter Total -->
                                                    <th class="text-center bg-primary">{{ $quarter }} Total</th>

                                                    @foreach ($months as $month)
                                                        @if (in_array($month, $monthsWithWeeks))
                                                            <!-- Month Header spans all weeks in the month -->
                                                            <th colspan="5" class="text-center bg-success">{{ $month }}</th>
                                                        @else
                                                            <!-- Pagumen - only month header, no weeks -->
                                                            <th class="text-center bg-success">{{ $month }}</th>
                                                        @endif
                                                    @endforeach
                                                @endforeach

                                                <!-- Summary Sub-columns -->
                                                <th class="text-center bg-warning">Distributed Number</th>
                                                <th class="text-center bg-warning">Remaining Number</th>

                                                <!-- Mechanization Breakdown Data Headers (will be populated dynamically) -->
                                            </tr>
                                            <tr>
                                                @foreach ($quarters as $quarter => $months)
                                                    <!-- Quarter Total Sub-columns -->
                                                    <th class="text-center bg-lightblue">Number</th>

                                                    @foreach ($months as $month)
                                                        <!-- Month Total -->
                                                        <th class="text-center bg-teal">{{ $month }} Total</th>

                                                        <!-- Weeks for this Month (only for months with weeks) -->
                                                        @if (in_array($month, $monthsWithWeeks))
                                                            @for ($week = 1; $week <= 4; $week++)
                                                                <th class="text-center bg-olive">W{{ $week }}</th>
                                                            @endfor
                                                        @endif
                                                    @endforeach
                                                @endforeach

                                                <!-- Summary Sub-columns (duplicated for alignment) -->
                                                <th class="text-center bg-warning">Number</th>
                                                <th class="text-center bg-warning">Number</th>

                                                <!-- Mechanization Breakdown Data Sub-headers (will be populated dynamically) -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $counter = 1;
                                                $totalAvailableNumber = 0;
                                                $totalDistributedNumber = 0;

                                                // Group data by Planning Year and Mechanization Type
                                                $groupedMechanizations = [];
                                                foreach ($transformedMechanizations as $mechanization) {
                                                    $year = $mechanization['year'];
                                                    $mechanizationType = $mechanization['mechanization_type'];
                                                    if (!isset($groupedMechanizations[$year])) {
                                                        $groupedMechanizations[$year] = [];
                                                    }
                                                    if (!isset($groupedMechanizations[$year][$mechanizationType])) {
                                                        $groupedMechanizations[$year][$mechanizationType] = [];
                                                    }
                                                    $groupedMechanizations[$year][$mechanizationType][] = $mechanization;
                                                }

                                                // Initialize totals arrays
                                                $yearTotals = [];
                                                $mechanizationTypeTotals = [];
                                                $quarterTotals = [
                                                    'Q1' => ['number' => 0],
                                                    'Q2' => ['number' => 0],
                                                    'Q3' => ['number' => 0],
                                                    'Q4' => ['number' => 0],
                                                ];
                                                $monthTotals = [];
                                                $weekTotals = [];
                                                $mechanizationNameTotals = [];

                                                foreach ($quarters as $quarterMonths) {
                                                    foreach ($quarterMonths as $month) {
                                                        $monthTotals[$month] = ['number' => 0];
                                                        if (in_array($month, $monthsWithWeeks)) {
                                                            for ($week = 1; $week <= 4; $week++) {
                                                                $weekKey = $month . ' W' . $week;
                                                                $weekTotals[$weekKey] = ['number' => 0];
                                                            }
                                                        }
                                                    }
                                                }
                                            @endphp

                                            @foreach ($groupedMechanizations as $year => $mechanizationTypes)
                                                @php
                                                    $yearRowspan = 0;
                                                    foreach ($mechanizationTypes as $mechanizationTypePlans) {
                                                        $yearRowspan += count($mechanizationTypePlans);
                                                    }
                                                    $yearFirst = true;
                                                @endphp

                                                @foreach ($mechanizationTypes as $mechanizationType => $mechanizationTypePlans)
                                                    @php
                                                        $mechanizationTypeRowspan = count($mechanizationTypePlans);
                                                        $mechanizationTypeFirst = true;
                                                        $mechanizationTypeTotalNumber = 0;
                                                        $mechanizationTypeDistributedNumber = 0;
                                                        $mechanizationTypeAvailableNumber = 0;

                                                        // Calculate mechanization type totals
                                                        foreach ($mechanizationTypePlans as $mechanization) {
                                                            $mechanizationTypeTotalNumber += $mechanization['amount'];
                                                            $mechanizationTypeDistributedNumber += $mechanization['timeline_data']['total_distributed_number'] ?? 0;
                                                            $mechanizationTypeAvailableNumber += $mechanization['amount'];
                                                        }

                                                        if (!isset($yearTotals[$year])) {
                                                            $yearTotals[$year] = [
                                                                'number' => 0,
                                                                'distributed' => 0,
                                                                'available' => 0,
                                                            ];
                                                        }
                                                        if (!isset($mechanizationTypeTotals[$mechanizationType])) {
                                                            $mechanizationTypeTotals[$mechanizationType] = [
                                                                'number' => 0,
                                                                'distributed' => 0,
                                                                'available' => 0,
                                                            ];
                                                        }
                                                        $yearTotals[$year]['number'] += $mechanizationTypeTotalNumber;
                                                        $yearTotals[$year]['distributed'] += $mechanizationTypeDistributedNumber;
                                                        $yearTotals[$year]['available'] += $mechanizationTypeAvailableNumber;
                                                        $mechanizationTypeTotals[$mechanizationType]['number'] += $mechanizationTypeTotalNumber;
                                                        $mechanizationTypeTotals[$mechanizationType]['distributed'] += $mechanizationTypeDistributedNumber;
                                                        $mechanizationTypeTotals[$mechanizationType]['available'] += $mechanizationTypeAvailableNumber;
                                                    @endphp

                                                    @foreach ($mechanizationTypePlans as $mechanization)
                                                        @php
                                                            $timelineData = $mechanization['timeline_data'];
                                                            $quartersData = $timelineData['quarters'] ?? [];
                                                            $monthsData = $timelineData['months'] ?? [];
                                                            $weeksData = $timelineData['weeks'] ?? [];
                                                            $mechanizationBreakdown = $timelineData['mechanization_breakdown'] ?? [];
                                                            $weeklyDistribution = $mechanization['mechanization_distribution_details']['weekly_distribution'] ?? [];
                                                            $applicationYear = $timelineData['application_year'] ?? 'N/A';

                                                            $distributedNumber = $timelineData['total_distributed_number'] ?? 0;

                                                            // Calculate available mechanization number
                                                            $availableNumber = $mechanization['amount'];

                                                            $remainingNumber = $availableNumber - $distributedNumber;

                                                            $totalAvailableNumber += $availableNumber;
                                                            $totalDistributedNumber += $distributedNumber;

                                                            // Process weekly distribution data for mechanizations
                                                            $weekDataByMonth = [];

                                                            // Method 1: Process from the structured weeks data (backend processed)
                                                            foreach ($weeksData as $weekKey => $weekItem) {
                                                                // Handle "Ginbot_week_1" format
                                                                if (preg_match('/^(.+)_week_(\d+)$/', $weekKey, $matches)) {
                                                                    $monthName = $matches[1];
                                                                    $weekNum = (int) $matches[2];
                                                                    $number = $weekItem['number'] ?? 0;

                                                                    if ($monthName && $weekNum >= 1 && $weekNum <= 4) {
                                                                        if (!isset($weekDataByMonth[$monthName])) {
                                                                            $weekDataByMonth[$monthName] = [];
                                                                        }
                                                                        $weekDataByMonth[$monthName][$weekNum] = $number;
                                                                    }
                                                                }
                                                            }

                                                            // Method 2: Process from raw weekly_distribution array (if weeksData is empty)
                                                            if (empty($weekDataByMonth) && !empty($weeklyDistribution)) {
                                                                foreach ($weeklyDistribution as $weekItem) {
                                                                    if (is_array($weekItem)) {
                                                                        $monthName = $weekItem['month'] ?? '';
                                                                        $weekText = $weekItem['week'] ?? '';
                                                                        $number = $weekItem['number'] ?? 0;

                                                                        // Extract week number from "Week 1", "Week 2", etc.
                                                                        $weekNum = 0;
                                                                        if (preg_match('/Week\s*(\d+)/i', $weekText, $matches)) {
                                                                            $weekNum = (int) $matches[1];
                                                                        }

                                                                        if ($monthName && $weekNum >= 1 && $weekNum <= 4) {
                                                                            if (!isset($weekDataByMonth[$monthName])) {
                                                                                $weekDataByMonth[$monthName] = [];
                                                                            }
                                                                            $weekDataByMonth[$monthName][$weekNum] = $number;
                                                                        }
                                                                    }
                                                                }
                                                            }

                                                            // Track mechanization name totals for breakdown
                                                            foreach ($mechanizationBreakdown as $mechName => $mechData) {
                                                                if (!isset($mechanizationNameTotals[$mechName])) {
                                                                    $mechanizationNameTotals[$mechName] = [
                                                                        'number' => 0,
                                                                        'unit' => $mechData['unit'] ?? 'number',
                                                                    ];
                                                                }
                                                                $mechanizationNameTotals[$mechName]['number'] += $mechData['number'];
                                                            }
                                                        @endphp

                                                        <tr data-mechanization-id="{{ $mechanization['id'] }}">
                                                            <!-- Row counter -->
                                                            <td class="text-center font-weight-bold">{{ $counter++ }}</td>

                                                            <!-- Year (with rowspan) -->
                                                            @if ($yearFirst && $mechanizationTypeFirst)
                                                                <td rowspan="{{ $yearRowspan }}" class="text-center align-middle font-weight-bold bg-gray-light">
                                                                    {{ $year }}
                                                                </td>
                                                            @endif

                                                            <!-- Mechanization Type (with rowspan) -->
                                                            @if ($mechanizationTypeFirst)
                                                                <td rowspan="{{ $mechanizationTypeRowspan }}" class="text-center align-middle font-weight-bold bg-gray-light">
                                                                    {{ $mechanizationType }}
                                                                    <br>
                                                                    <small class="text-muted">Total Available: {{ number_format($mechanizationTypeTotalNumber, 2) }}</small>
                                                                </td>
                                                            @endif

                                                            <!-- Individual mechanization data -->
                                                            <td class="font-weight-bold text-primary">{{ $mechanization['name'] }}</td>
                                                            <td class="text-center">number</td>
                                                            <td class="text-center font-weight-bold text-info">{{ $applicationYear }}</td>
                                                            <td class="text-right font-weight-bold">{{ number_format($availableNumber, 2) }}</td>

                                                            <!-- Quarter, Month, and Week Data -->
                                                            @foreach ($quarters as $quarter => $months)
                                                                @php
                                                                    // Quarter data extraction
                                                                    $quarterNumber = 0;
                                                                    if (isset($quartersData[$quarter])) {
                                                                        if (is_array($quartersData[$quarter])) {
                                                                            $quarterNumber = $quartersData[$quarter]['number'] ?? 0;
                                                                        } else {
                                                                            $quarterNumber = floatval($quartersData[$quarter]);
                                                                        }
                                                                    }
                                                                    $quarterTotals[$quarter]['number'] += $quarterNumber;
                                                                @endphp

                                                                <!-- Quarter Total -->
                                                                <td class="text-right font-weight-bold {{ $quarterNumber > 0 ? 'bg-lightblue' : '' }}">
                                                                    {{ number_format($quarterNumber, 2) }}
                                                                </td>

                                                                <!-- Month Data for this Quarter -->
                                                                @foreach ($months as $month)
                                                                    @php
                                                                        // Month data extraction
                                                                        $monthNumber = 0;
                                                                        if (isset($monthsData[$month])) {
                                                                            if (is_array($monthsData[$month])) {
                                                                                $monthNumber = $monthsData[$month]['number'] ?? 0;
                                                                            } else {
                                                                                $monthNumber = floatval($monthsData[$month]);
                                                                            }
                                                                        }
                                                                        $monthTotals[$month]['number'] += $monthNumber;
                                                                    @endphp

                                                                    <!-- Month Total -->
                                                                    <td class="text-right font-weight-bold {{ $monthNumber > 0 ? 'bg-teal' : '' }}">
                                                                        {{ number_format($monthNumber, 2) }}
                                                                    </td>

                                                                    <!-- Week Data for this Month (only for months with weeks) -->
                                                                    @if (in_array($month, $monthsWithWeeks))
                                                                        @for ($week = 1; $week <= 4; $week++)
                                                                            @php
                                                                                $weekKey = $month . ' W' . $week;
                                                                                $weekNumber = 0;

                                                                                // Get week data from the processed weekly distribution
                                                                                if (isset($weekDataByMonth[$month][$week])) {
                                                                                    $weekNumber = $weekDataByMonth[$month][$week];
                                                                                }

                                                                                $weekTotals[$weekKey]['number'] += $weekNumber;
                                                                            @endphp
                                                                            <td class="text-right {{ $weekNumber > 0 ? 'bg-olive' : '' }}">
                                                                                {{ number_format($weekNumber, 2) }}
                                                                            </td>
                                                                        @endfor
                                                                    @endif
                                                                @endforeach
                                                            @endforeach

                                                            <!-- Summary Data -->
                                                            <td class="text-right font-weight-bold bg-warning distributed-number">
                                                                {{ number_format($distributedNumber, 2) }}
                                                            </td>
                                                            <td class="text-right font-weight-bold bg-warning remaining-number {{ $remainingNumber >= 0 ? 'text-success' : 'text-danger' }}">
                                                                {{ number_format($remainingNumber, 2) }}
                                                            </td>

                                                            <!-- Mechanization Breakdown Data (will be populated dynamically) -->
                                                        </tr>
                                                        @php
                                                            $yearFirst = false;
                                                            $mechanizationTypeFirst = false;
                                                        @endphp
                                                    @endforeach
                                                @endforeach
                                            @endforeach

                                            <!-- Grand Totals Row -->
                                            <tr class="bg-gray-dark text-white font-weight-bold">
                                                <td colspan="6" class="text-center">GRAND TOTALS</td>
                                                <td class="text-center">
                                                    <small>Total Available: {{ number_format($totalAvailableNumber, 2) }} number</small>
                                                </td>

                                                <!-- Quarter Totals -->
                                                @foreach ($quarters as $quarter => $months)
                                                    <td class="text-right bg-primary">{{ number_format($quarterTotals[$quarter]['number'], 2) }}</td>
                                                    <!-- Empty cells for months and weeks in this quarter -->
                                                    @foreach ($months as $month)
                                                        <td class="text-right bg-success">{{ number_format($monthTotals[$month]['number'], 2) }}</td>
                                                        @if (in_array($month, $monthsWithWeeks))
                                                            @for ($week = 1; $week <= 4; $week++)
                                                                @php
                                                                    $weekKey = $month . ' W' . $week;
                                                                @endphp
                                                                <td class="text-right bg-olive">{{ number_format($weekTotals[$weekKey]['number'], 2) }}</td>
                                                            @endfor
                                                        @endif
                                                    @endforeach
                                                @endforeach

                                                <!-- Summary Totals -->
                                                <td class="text-right bg-warning">{{ number_format($totalDistributedNumber, 2) }}</td>
                                                <td class="text-right bg-warning {{ $totalAvailableNumber - $totalDistributedNumber >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ number_format($totalAvailableNumber - $totalDistributedNumber, 2) }}
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
                                    <div class="badge badge-warning ml-1">Number = Equipment Count</div>
                                    <div class="badge badge-info ml-1">Machinery</div>
                                </div>
                                <div class="float-right">
                                    <span class="text-muted">
                                        Showing {{ $counter-1 }} mechanization plans | 
                                        Total Available: {{ number_format($totalAvailableNumber, 2) }} | 
                                        Total Distributed: {{ number_format($totalDistributedNumber, 2) }}
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
            const table = document.getElementById('mechanizationPlanTable');
            const html = table.outerHTML;
            const blob = new Blob([html], {
                type: 'application/vnd.ms-excel'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'mechanization-plan-' + new Date().toISOString().split('T')[0] + '.xls';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            // Show success message
            toastr.success('Excel file downloaded successfully!');
        }

        function toggleMechanizationBreakdown() {
            const breakdownHeaders = document.querySelectorAll('.mechanization-breakdown-header');
            const isVisible = breakdownHeaders[0].style.display !== 'none';

            breakdownHeaders.forEach(header => {
                header.style.display = isVisible ? 'none' : '';
            });

            // Show notification
            toastr.info('Mechanization breakdown ' + (isVisible ? 'hidden' : 'shown'));
        }

        $(document).ready(function() {
            // Make table headers sticky
            $('.table-responsive').on('scroll', function() {
                $('.sticky-top').css('top', this.scrollTop + 'px');
            });

            // Hover effect for rows
            $('#mechanizationPlanTable tbody tr').hover(
                function() {
                    $(this).addClass('bg-gray');
                },
                function() {
                    $(this).removeClass('bg-gray');
                }
            );

            // Week visibility toggle (only for months with weeks)
            $('#mechanizationPlanTable thead th.bg-success').each(function() {
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

            // Highlight negative remaining numbers
            $('.remaining-number.text-danger').each(function() {
                $(this).closest('tr').addClass('bg-danger-light');
            });
        });

        function toggleWeeksVisibility(month) {
            const allHeaders = $('#mechanizationPlanTable thead th, #mechanizationPlanTable thead td');
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
                    $('#mechanizationPlanTable tbody tr td').eq(columnIndex).show();
                } else {
                    weekHeader.hide();
                    $('#mechanizationPlanTable tbody tr td').eq(columnIndex).hide();
                }
            });
            
            // Show notification
            toastr.info('Week columns for ' + month + ' are now ' + (isHidden ? 'visible' : 'hidden'));
        }

        // Add filter functionality
        function filterMechanizationTable() {
            const yearFilter = $('#yearFilter').val();
            const typeFilter = $('#typeFilter').val();
            const nameFilter = $('#nameFilter').val();
            
            $('#mechanizationPlanTable tbody tr:not(.bg-gray-dark)').each(function() {
                const year = $(this).find('td:nth-child(2)').text().trim();
                const mechType = $(this).find('td:nth-child(3)').text().trim();
                const mechName = $(this).find('td:nth-child(4)').text().trim();
                
                let showRow = true;
                
                if (yearFilter && year !== yearFilter) {
                    showRow = false;
                }
                
                if (typeFilter && mechType !== typeFilter) {
                    showRow = false;
                }
                
                if (nameFilter && !mechName.toLowerCase().includes(nameFilter.toLowerCase())) {
                    showRow = false;
                }
                
                if (showRow) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        // Add search functionality
        $('#mechanizationSearch').on('keyup', function() {
            const value = $(this).val().toLowerCase();
            $('#mechanizationPlanTable tbody tr:not(.bg-gray-dark)').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
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
        
        .distributed-number, .remaining-number {
            border-left: 3px solid #ffc107 !important;
            font-weight: 700 !important;
            font-size: 0.85rem !important;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }
        
        .table-hover tbody tr:hover .distributed-number,
        .table-hover tbody tr:hover .remaining-number {
            background-color: #e0a800 !important;
        }
        
        .card-primary .card-header {
            background: linear-gradient(135deg, #007bff 0%, #0dcaf0 100%) !important;
        }
        
        .card {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        }
        
        /* Mechanization icon styling */
        .fa-tractor {
            color: #007bff;
        }
        
        @media (max-width: 768px) {
            .table-responsive { 
                font-size: 0.75rem; 
            }
            .table th, .table td { 
                padding: 0.3rem; 
            }
            .distributed-number, .remaining-number { 
                font-size: 0.75rem !important; 
            }
            .card-footer .badge {
                font-size: 0.7rem;
                margin-bottom: 0.2rem;
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
            .distributed-number, .remaining-number { 
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
        
        /* Highlight positive/negative remaining numbers */
        .remaining-number.text-success {
            color: #28a745 !important;
            font-weight: 800 !important;
        }
        
        .remaining-number.text-danger {
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
        
        /* Card primary header styling */
        .card-primary:not(.card-outline) .card-header {
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
            .card-footer .text-muted {
                display: block;
                margin-top: 10px;
            }
            .card-header .card-tools {
                margin-top: 5px;
            }
        }
        
        /* Custom styling for mechanization table */
        .mechanization-type {
            background-color: #e3f2fd !important;
            border-left: 3px solid #007bff;
        }
        
        .mechanization-name {
            font-weight: 600;
            color: #2c3e50;
        }
        
        /* Animation for row highlighting */
        @keyframes highlightRow {
            0% { background-color: rgba(0, 123, 255, 0.1); }
            100% { background-color: transparent; }
        }
        
        .highlighted {
            animation: highlightRow 2s ease-out;
        }
    </style>
</x-layout>