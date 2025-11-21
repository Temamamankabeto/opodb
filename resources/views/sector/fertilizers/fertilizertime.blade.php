<x-layout>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="mdi mdi-fertilizer me-2"></i>
                            Fertilizer Distribution Plan - Grouped by Year & Crop Type
                        </h4>
                        <div>
                            <button class="btn btn-light btn-sm me-2" onclick="exportToExcel()">
                                <i class="mdi mdi-download me-1"></i> Export Excel
                            </button>
                            <button class="btn btn-warning btn-sm" onclick="toggleFertilizerBreakdown()">
                                <i class="mdi mdi-chart-pie me-1"></i> Toggle Breakdown
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0" id="fertilizerPlanTable">
                                <thead class="table-dark sticky-top">
                                    <tr>
                                        <th rowspan="4" class="text-center align-middle">#</th>
                                        <th rowspan="4" class="text-center align-middle">Planning Year</th>
                                        <th rowspan="4" class="text-center align-middle">Crop Type</th>
                                        <th rowspan="4" class="text-center align-middle">Crop Variety</th>
                                        <th rowspan="4" class="text-center align-middle">Unit</th>
                                        <th rowspan="4" class="text-center align-middle">Application Year</th>
                                        <th rowspan="4" class="text-center align-middle">Season</th>
                                        <th rowspan="4" class="text-center align-middle">Total Area (ha)</th>
                                        <th rowspan="4" class="text-center align-middle">Available Fertilizers</th>

                                        <!-- Fertilizer Breakdown Columns (initially hidden) -->
                                        <th colspan="0"
                                            class="text-center bg-info text-white fertilizer-breakdown-header"
                                            style="display: none;">
                                            Fertilizer Breakdown
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
                                            <th colspan="{{ $colspan }}" class="text-center bg-info text-white">
                                                {{ $quarter }}</th>
                                        @endforeach

                                        <!-- Summary Columns -->
                                        <th colspan="2" class="text-center bg-warning text-white">Quantity Summary
                                        </th>

                                        <!-- Fertilizer Breakdown Sub-headers (initially hidden) -->
                                        <th colspan="0"
                                            class="text-center bg-info text-white fertilizer-breakdown-header"
                                            style="display: none;">
                                            By Fertilizer Type
                                        </th>
                                    </tr>
                                    <tr>
                                        @foreach ($quarters as $quarter => $months)
                                            <!-- Quarter Total -->
                                            <th class="text-center bg-primary text-white">{{ $quarter }} Total</th>

                                            @foreach ($months as $month)
                                                @if (in_array($month, $monthsWithWeeks))
                                                    <!-- Month Header spans all weeks in the month -->
                                                    <th colspan="5" class="text-center bg-success text-white">
                                                        {{ $month }}</th>
                                                @else
                                                    <!-- Pagumen - only month header, no weeks -->
                                                    <th class="text-center bg-success text-white">{{ $month }}
                                                    </th>
                                                @endif
                                            @endforeach
                                        @endforeach

                                        <!-- Summary Sub-columns -->
                                        <th class="text-center bg-warning-subtle fw-bold">Distributed Quantity (kg)</th>
                                        <th class="text-center bg-warning-subtle fw-bold">Remaining Quantity (kg)</th>

                                        <!-- Fertilizer Breakdown Data Headers (will be populated dynamically) -->
                                    </tr>
                                    <tr>
                                        @foreach ($quarters as $quarter => $months)
                                            <!-- Quarter Total Sub-columns -->
                                            <th class="text-center bg-primary-subtle">Qty (kg)</th>

                                            @foreach ($months as $month)
                                                <!-- Month Total -->
                                                <th class="text-center bg-success-subtle">{{ $month }} Total</th>

                                                <!-- Weeks for this Month (only for months with weeks) -->
                                                @if (in_array($month, $monthsWithWeeks))
                                                    @for ($week = 1; $week <= 4; $week++)
                                                        <th class="text-center bg-warning-subtle">W{{ $week }}
                                                        </th>
                                                    @endfor
                                                @endif
                                            @endforeach
                                        @endforeach

                                        <!-- Summary Sub-columns (duplicated for alignment) -->
                                        <th class="text-center bg-warning-subtle fw-bold">Qty (kg)</th>
                                        <th class="text-center bg-warning-subtle fw-bold">Qty (kg)</th>

                                        <!-- Fertilizer Breakdown Data Sub-headers (will be populated dynamically) -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $counter = 1;
                                        $totalAvailableFertilizer = 0;
                                        $totalDistributedQuantity = 0;

                                        // Group data by Planning Year and Crop Type
                                        $groupedPlans = [];
                                        foreach ($transformedPlans as $plan) {
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
                                            'Q1' => ['quantity' => 0],
                                            'Q2' => ['quantity' => 0],
                                            'Q3' => ['quantity' => 0],
                                            'Q4' => ['quantity' => 0],
                                        ];
                                        $monthTotals = [];
                                        $weekTotals = [];
                                        $fertilizerTypeTotals = [];

                                        foreach ($quarters as $quarterMonths) {
                                            foreach ($quarterMonths as $month) {
                                                $monthTotals[$month] = ['quantity' => 0];
                                                if (in_array($month, $monthsWithWeeks)) {
                                                    for ($week = 1; $week <= 4; $week++) {
                                                        $weekKey = $month . ' W' . $week;
                                                        $weekTotals[$weekKey] = ['quantity' => 0];
                                                    }
                                                }
                                            }
                                        }
                                    @endphp

                                    @foreach ($groupedPlans as $year => $cropTypes)
                                        @php
                                            $yearRowspan = 0;
                                            foreach ($cropTypes as $cropTypePlans) {
                                                $yearRowspan += count($cropTypePlans);
                                            }
                                            $yearFirst = true;
                                        @endphp

                                        @foreach ($cropTypes as $cropType => $cropTypePlans)
                                            @php
                                                $cropTypeRowspan = count($cropTypePlans);
                                                $cropTypeFirst = true;
                                                $cropTypeTotalArea = 0;
                                                $cropTypeDistributedQuantity = 0;
                                                $cropTypeAvailableFertilizer = 0;

                                                // Calculate crop type totals
                                                foreach ($cropTypePlans as $plan) {
                                                    $cropTypeTotalArea += $plan['area_to_plant'];
                                                    $cropTypeDistributedQuantity +=
                                                        $plan['timeline_data']['total_distributed_quantity'] ?? 0;

                                                    // Calculate available fertilizer from fertilizer_details
                                                    if (
                                                        $plan['fertilizer_details'] &&
                                                        is_array($plan['fertilizer_details'])
                                                    ) {
                                                        foreach ($plan['fertilizer_details'] as $fert) {
                                                            $cropTypeAvailableFertilizer += $fert['amount'] ?? 0;
                                                        }
                                                    }
                                                }

                                                if (!isset($yearTotals[$year])) {
                                                    $yearTotals[$year] = [
                                                        'area' => 0,
                                                        'distributed' => 0,
                                                        'available_fert' => 0,
                                                    ];
                                                }
                                                if (!isset($cropTypeTotals[$cropType])) {
                                                    $cropTypeTotals[$cropType] = [
                                                        'area' => 0,
                                                        'distributed' => 0,
                                                        'available_fert' => 0,
                                                    ];
                                                }
                                                $yearTotals[$year]['area'] += $cropTypeTotalArea;
                                                $yearTotals[$year]['distributed'] += $cropTypeDistributedQuantity;
                                                $yearTotals[$year]['available_fert'] += $cropTypeAvailableFertilizer;
                                                $cropTypeTotals[$cropType]['area'] += $cropTypeTotalArea;
                                                $cropTypeTotals[$cropType][
                                                    'distributed'
                                                ] += $cropTypeDistributedQuantity;
                                                $cropTypeTotals[$cropType][
                                                    'available_fert'
                                                ] += $cropTypeAvailableFertilizer;
                                            @endphp

                                            @foreach ($cropTypePlans as $plan)
                                                @php
                                                    $timelineData = $plan['timeline_data'];
                                                    $quartersData = $timelineData['quarters'] ?? [];
                                                    $monthsData = $timelineData['months'] ?? [];
                                                    $weeksData = $timelineData['weeks'] ?? [];
                                                    $fertilizerBreakdown = $timelineData['fertilizer_breakdown'] ?? [];
                                                    $weeklyDistribution =
                                                        $plan['fertilizer_distribution_details'][
                                                            'weekly_distribution'
                                                        ] ?? [];
                                                    $applicationYear = $timelineData['application_year'] ?? 'N/A';

                                                    $distributedQuantity =
                                                        $timelineData['total_distributed_quantity'] ?? 0;

                                                    // Calculate available fertilizer quantity
                                                    $availableFertilizer = 0;
                                                    $fertilizerList = [];
                                                    if (
                                                        $plan['fertilizer_details'] &&
                                                        is_array($plan['fertilizer_details'])
                                                    ) {
                                                        foreach ($plan['fertilizer_details'] as $fert) {
                                                            $availableFertilizer += $fert['amount'] ?? 0;
                                                            $fertilizerList[] =
                                                                ($fert['fertilizer_name'] ?? 'Unknown') .
                                                                ' (' .
                                                                ($fert['amount'] ?? 0) .
                                                                ' ' .
                                                                ($fert['unit'] ?? 'kg') .
                                                                ')';
                                                        }
                                                    }

                                                    $remainingQuantity = $availableFertilizer - $distributedQuantity;

                                                    $totalAvailableFertilizer += $availableFertilizer;
                                                    $totalDistributedQuantity += $distributedQuantity;

                                                    // Process weekly distribution data for fertilizers
                                                    $weekDataByMonth = [];

                                                    // Method 1: Process from the structured weeks data (backend processed)
                                                    foreach ($weeksData as $weekKey => $weekItem) {
                                                        // Handle "Ginbot_week_1" format
                                                        if (preg_match('/^(.+)_week_(\d+)$/', $weekKey, $matches)) {
                                                            $monthName = $matches[1];
                                                            $weekNum = (int) $matches[2];
                                                            $quantity = $weekItem['quantity'] ?? 0;

                                                            if ($monthName && $weekNum >= 1 && $weekNum <= 4) {
                                                                if (!isset($weekDataByMonth[$monthName])) {
                                                                    $weekDataByMonth[$monthName] = [];
                                                                }
                                                                $weekDataByMonth[$monthName][$weekNum] = $quantity;
                                                            }
                                                        }
                                                    }

                                                    // Method 2: Process from raw weekly_distribution array (if weeksData is empty)
                                                    if (empty($weekDataByMonth) && !empty($weeklyDistribution)) {
                                                        foreach ($weeklyDistribution as $weekItem) {
                                                            if (is_array($weekItem)) {
                                                                $monthName = $weekItem['month'] ?? '';
                                                                $weekText = $weekItem['week'] ?? '';
                                                                $quantity = $weekItem['quantity'] ?? 0;

                                                                // Extract week number from "Week 1", "Week 2", etc.
                                                                $weekNum = 0;
                                                                if (
                                                                    preg_match('/Week\s*(\d+)/i', $weekText, $matches)
                                                                ) {
                                                                    $weekNum = (int) $matches[1];
                                                                }

                                                                if ($monthName && $weekNum >= 1 && $weekNum <= 4) {
                                                                    if (!isset($weekDataByMonth[$monthName])) {
                                                                        $weekDataByMonth[$monthName] = [];
                                                                    }
                                                                    $weekDataByMonth[$monthName][$weekNum] = $quantity;
                                                                }
                                                            }
                                                        }
                                                    }

                                                    // Track fertilizer type totals for breakdown
                                                    foreach ($fertilizerBreakdown as $fertName => $fertData) {
                                                        if (!isset($fertilizerTypeTotals[$fertName])) {
                                                            $fertilizerTypeTotals[$fertName] = [
                                                                'quantity' => 0,
                                                                'unit' => $fertData['unit'] ?? 'kg',
                                                            ];
                                                        }
                                                        $fertilizerTypeTotals[$fertName]['quantity'] +=
                                                            $fertData['quantity'];
                                                    }
                                                @endphp

                                                <tr data-plan-id="{{ $plan['id'] }}">
                                                    <!-- Row counter -->
                                                    <td class="text-center fw-bold">{{ $counter++ }}</td>

                                                    <!-- Year (with rowspan) -->
                                                    @if ($yearFirst && $cropTypeFirst)
                                                        <td rowspan="{{ $yearRowspan }}"
                                                            class="text-center align-middle fw-bold bg-light">
                                                            {{ $year }}
                                                        </td>
                                                    @endif

                                                    <!-- Crop Type (with rowspan) -->
                                                    @if ($cropTypeFirst)
                                                        <td rowspan="{{ $cropTypeRowspan }}"
                                                            class="text-center align-middle fw-bold bg-light">
                                                            {{ $cropType }}
                                                            <br>
                                                            <small class="text-muted">Area:
                                                                {{ number_format($cropTypeTotalArea, 2) }} ha</small>
                                                        </td>
                                                    @endif

                                                    <!-- Individual plan data -->
                                                    <td class="fw-bold text-primary">{{ $plan['crop_name'] }}</td>
                                                    <td class="text-center">kg</td>
                                                    <td class="text-center fw-bold text-info">{{ $applicationYear }}
                                                    </td>
                                                    <td class="text-center">{{ $plan['season'] }}</td>
                                                    <td class="text-end fw-bold">
                                                        {{ number_format($plan['area_to_plant'], 2) }}</td>
                                                    <td class="text-center">
                                                        <small>
                                                            @if (count($plan['fertilizer_list']) > 0)
                                                                @foreach ($plan['fertilizer_list'] as $fert)
                                                                    <span class="badge bg-info text-dark me-1 mb-1"
                                                                        title="{{ $fert['name'] }} - {{ $fert['amount'] }} {{ $fert['unit'] }}">
                                                                        {{ $fert['name'] }} ({{ $fert['amount'] }}
                                                                        {{ $fert['unit'] }})
                                                                    </span>
                                                                @endforeach
                                                            @else
                                                                <span class="text-muted">No fertilizers allocated</span>
                                                            @endif
                                                        </small>
                                                    </td>
                                                    <!-- Quarter, Month, and Week Data -->
                                                    @foreach ($quarters as $quarter => $months)
                                                        @php
                                                            // Quarter data extraction
                                                            $quarterQuantity = 0;
                                                            if (isset($quartersData[$quarter])) {
                                                                if (is_array($quartersData[$quarter])) {
                                                                    $quarterQuantity =
                                                                        $quartersData[$quarter]['quantity'] ?? 0;
                                                                } else {
                                                                    $quarterQuantity = floatval(
                                                                        $quartersData[$quarter],
                                                                    );
                                                                }
                                                            }
                                                            $quarterTotals[$quarter]['quantity'] += $quarterQuantity;
                                                        @endphp

                                                        <!-- Quarter Total -->
                                                        <td
                                                            class="text-end fw-bold {{ $quarterQuantity > 0 ? 'bg-primary-subtle' : '' }}">
                                                            {{ number_format($quarterQuantity, 2) }}
                                                        </td>

                                                        <!-- Month Data for this Quarter -->
                                                        @foreach ($months as $month)
                                                            @php
                                                                // Month data extraction
                                                                $monthQuantity = 0;
                                                                if (isset($monthsData[$month])) {
                                                                    if (is_array($monthsData[$month])) {
                                                                        $monthQuantity =
                                                                            $monthsData[$month]['quantity'] ?? 0;
                                                                    } else {
                                                                        $monthQuantity = floatval($monthsData[$month]);
                                                                    }
                                                                }
                                                                $monthTotals[$month]['quantity'] += $monthQuantity;
                                                            @endphp

                                                            <!-- Month Total -->
                                                            <td
                                                                class="text-end fw-bold {{ $monthQuantity > 0 ? 'bg-success-subtle' : '' }}">
                                                                {{ number_format($monthQuantity, 2) }}
                                                            </td>

                                                            <!-- Week Data for this Month (only for months with weeks) -->
                                                            @if (in_array($month, $monthsWithWeeks))
                                                                @for ($week = 1; $week <= 4; $week++)
                                                                    @php
                                                                        $weekKey = $month . ' W' . $week;
                                                                        $weekQuantity = 0;

                                                                        // Get week data from the processed weekly distribution
                                                                        if (isset($weekDataByMonth[$month][$week])) {
                                                                            $weekQuantity =
                                                                                $weekDataByMonth[$month][$week];
                                                                        }

                                                                        $weekTotals[$weekKey][
                                                                            'quantity'
                                                                        ] += $weekQuantity;
                                                                    @endphp
                                                                    <td
                                                                        class="text-end {{ $weekQuantity > 0 ? 'bg-warning-subtle' : '' }}">
                                                                        {{ number_format($weekQuantity, 2) }}
                                                                    </td>
                                                                @endfor
                                                            @endif
                                                        @endforeach
                                                    @endforeach

                                                    <!-- Summary Data -->
                                                    <td class="text-end fw-bold bg-warning-subtle distributed-quantity">
                                                        {{ number_format($distributedQuantity, 2) }}
                                                    </td>
                                                    <td
                                                        class="text-end fw-bold bg-warning-subtle remaining-quantity {{ $remainingQuantity >= 0 ? 'text-success' : 'text-danger' }}">
                                                        {{ number_format($remainingQuantity, 2) }}
                                                    </td>

                                                    <!-- Fertilizer Breakdown Data (will be populated dynamically) -->
                                                </tr>
                                                @php
                                                    $yearFirst = false;
                                                    $cropTypeFirst = false;
                                                @endphp
                                            @endforeach
                                        @endforeach
                                    @endforeach

                                    <!-- Grand Totals Row -->
                                    <tr class="table-dark fw-bold">
                                        <td colspan="8" class="text-center">GRAND TOTALS</td>
                                        <td class="text-center">
                                            <small>Total Available: {{ number_format($totalAvailableFertilizer, 2) }}
                                                kg</small>
                                        </td>

                                        <!-- Quarter Totals -->
                                        @foreach ($quarters as $quarter => $months)
                                            <td class="text-end bg-primary text-white">
                                                {{ number_format($quarterTotals[$quarter]['quantity'], 2) }}
                                            </td>
                                            <!-- Empty cells for months and weeks in this quarter -->
                                            @foreach ($months as $month)
                                                <td class="text-end bg-success text-white">
                                                    {{ number_format($monthTotals[$month]['quantity'], 2) }}
                                                </td>
                                                @if (in_array($month, $monthsWithWeeks))
                                                    @for ($week = 1; $week <= 4; $week++)
                                                        @php
                                                            $weekKey = $month . ' W' . $week;
                                                        @endphp
                                                        <td class="text-end bg-warning text-white">
                                                            {{ number_format($weekTotals[$weekKey]['quantity'], 2) }}
                                                        </td>
                                                    @endfor
                                                @endif
                                            @endforeach
                                        @endforeach

                                        <!-- Summary Totals -->
                                        <td class="text-end bg-warning text-white">
                                            {{ number_format($totalDistributedQuantity, 2) }}
                                        </td>
                                        <td
                                            class="text-end bg-warning text-white {{ $totalAvailableFertilizer - $totalDistributedQuantity >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($totalAvailableFertilizer - $totalDistributedQuantity, 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function exportToExcel() {
            const table = document.getElementById('fertilizerPlanTable');
            const html = table.outerHTML;
            const blob = new Blob([html], {
                type: 'application/vnd.ms-excel'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'fertilizer-plan-' + new Date().toISOString().split('T')[0] + '.xls';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        function toggleFertilizerBreakdown() {
            const breakdownHeaders = document.querySelectorAll('.fertilizer-breakdown-header');
            const isVisible = breakdownHeaders[0].style.display !== 'none';

            breakdownHeaders.forEach(header => {
                header.style.display = isVisible ? 'none' : '';
            });

            // Here you would dynamically add/remove fertilizer breakdown columns
            // This would require additional JavaScript to populate the breakdown data
            alert('Fertilizer breakdown feature would show distribution by fertilizer type');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('#fertilizerPlanTable tbody tr');
            rows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f8f9fa';
                });
                row.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                });
            });

            // Week visibility toggle (only for months with weeks)
            const monthHeaders = document.querySelectorAll('thead th.bg-success');
            monthHeaders.forEach(header => {
                const monthName = header.textContent.trim();
                if (monthName !== 'Pagumen') {
                    header.style.cursor = 'pointer';
                    header.title = 'Click to toggle weeks visibility';
                    header.addEventListener('click', function() {
                        toggleWeeksVisibility(monthName);
                    });
                }
            });
        });

        function toggleWeeksVisibility(month) {
            const allHeaders = document.querySelectorAll('thead th, thead td');
            let weekColumns = [];

            allHeaders.forEach((header, index) => {
                if (header.textContent.trim() === month && header.classList.contains('bg-success')) {
                    const monthIndex = index;

                    for (let i = 1; i <= 4; i++) {
                        const weekHeader = allHeaders[monthIndex + i + 1];
                        if (weekHeader && weekHeader.classList.contains('bg-warning-subtle')) {
                            weekColumns.push(weekHeader);
                        }
                    }
                }
            });

            weekColumns.forEach(weekHeader => {
                const isHidden = weekHeader.style.display === 'none';
                weekHeader.style.display = isHidden ? '' : 'none';

                const columnIndex = Array.from(weekHeader.parentElement.cells).indexOf(weekHeader);
                const dataRows = document.querySelectorAll('#fertilizerPlanTable tbody tr');

                dataRows.forEach(row => {
                    const cell = row.cells[columnIndex];
                    if (cell) {
                        cell.style.display = isHidden ? '' : 'none';
                    }
                });
            });
        }
    </script>

    <style>
        .table th {
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .table td {
            font-size: 0.8rem;
            white-space: nowrap;
        }

        .sticky-top {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table-responsive {
            max-height: 70vh;
            overflow: auto;
        }

        .bg-info-subtle {
            background-color: #d1ecf1 !important;
        }

        .bg-success-subtle {
            background-color: #d1f2eb !important;
        }

        .bg-warning-subtle {
            background-color: #fff3cd !important;
        }

        .bg-primary-subtle {
            background-color: #cfe2ff !important;
        }

        /* Improved visibility for summary columns */
        .distributed-quantity,
        .remaining-quantity {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%) !important;
            border-left: 3px solid #ffc107 !important;
            font-weight: 700 !important;
            font-size: 0.85rem !important;
        }

        .distributed-quantity {
            border-right: 2px solid #dee2e6 !important;
        }

        .remaining-quantity {
            border-right: 2px solid #dee2e6 !important;
        }

        /* Enhanced header styling for summary columns */
        thead th.bg-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%) !important;
            color: #000 !important;
            font-weight: 700 !important;
            font-size: 0.8rem !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        thead th.bg-warning-subtle {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%) !important;
            color: #000 !important;
            font-weight: 700 !important;
            font-size: 0.75rem !important;
            border-bottom: 2px solid #ffc107 !important;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table-hover tbody tr:hover .distributed-quantity,
        .table-hover tbody tr:hover .remaining-quantity {
            background: linear-gradient(135deg, #ffeaa7 0%, #ffd54f 100%) !important;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }

        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.7rem;
            }

            .table th,
            .table td {
                padding: 0.3rem;
            }

            .distributed-quantity,
            .remaining-quantity {
                font-size: 0.75rem !important;
            }
        }

        @media print {
            .card-header button {
                display: none;
            }

            .table-responsive {
                overflow: visible;
            }

            .card {
                border: none;
                box-shadow: none;
            }

            .distributed-quantity,
            .remaining-quantity {
                background: #fff3cd !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* Highlight positive/negative remaining quantities */
        .remaining-quantity.text-success {
            color: #198754 !important;
            font-weight: 800 !important;
        }

        .remaining-quantity.text-danger {
            color: #dc3545 !important;
            font-weight: 800 !important;
        }

        /* Success theme for fertilizer table */
        .card-header.bg-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
        }

        thead th.bg-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
        }
    </style>
</x-layout>
