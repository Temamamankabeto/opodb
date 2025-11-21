<x-layout>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="mdi mdi-tractor me-2"></i>
                            Mechanization Distribution Plan - Grouped by Year & Type
                        </h4>
                        <div>
                            <button class="btn btn-light btn-sm me-2" onclick="exportToExcel()">
                                <i class="mdi mdi-download me-1"></i> Export Excel
                            </button>
                            <button class="btn btn-warning btn-sm" onclick="toggleMechanizationBreakdown()">
                                <i class="mdi mdi-chart-pie me-1"></i> Toggle Breakdown
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0" id="mechanizationPlanTable">
                                <thead class="table-dark sticky-top">
                                    <tr>
                                        <th rowspan="4" class="text-center align-middle">#</th>
                                        <th rowspan="4" class="text-center align-middle">Planning Year</th>
                                        <th rowspan="4" class="text-center align-middle">Mechanization Type</th>
                                        <th rowspan="4" class="text-center align-middle">Mechanization Name</th>
                                        <th rowspan="4" class="text-center align-middle">Unit</th>
                                        <th rowspan="4" class="text-center align-middle">Application Year</th>
                                        <th rowspan="4" class="text-center align-middle">Available Number</th>

                                        <!-- Mechanization Breakdown Columns (initially hidden) -->
                                        <th colspan="0"
                                            class="text-center bg-info text-white mechanization-breakdown-header"
                                            style="display: none;">
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
                                            <th colspan="{{ $colspan }}" class="text-center bg-info text-white">
                                                {{ $quarter }}</th>
                                        @endforeach

                                        <!-- Summary Columns -->
                                        <th colspan="2" class="text-center bg-warning text-white">Number Summary
                                        </th>

                                        <!-- Mechanization Breakdown Sub-headers (initially hidden) -->
                                        <th colspan="0"
                                            class="text-center bg-info text-white mechanization-breakdown-header"
                                            style="display: none;">
                                            By Mechanization Type
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
                                        <th class="text-center bg-warning-subtle fw-bold">Distributed Number</th>
                                        <th class="text-center bg-warning-subtle fw-bold">Remaining Number</th>

                                        <!-- Mechanization Breakdown Data Headers (will be populated dynamically) -->
                                    </tr>
                                    <tr>
                                        @foreach ($quarters as $quarter => $months)
                                            <!-- Quarter Total Sub-columns -->
                                            <th class="text-center bg-primary-subtle">Number</th>

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
                                        <th class="text-center bg-warning-subtle fw-bold">Number</th>
                                        <th class="text-center bg-warning-subtle fw-bold">Number</th>

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
                                                    $mechanizationTypeDistributedNumber +=
                                                        $mechanization['timeline_data']['total_distributed_number'] ?? 0;
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
                                                $mechanizationTypeTotals[$mechanizationType][
                                                    'distributed'
                                                ] += $mechanizationTypeDistributedNumber;
                                                $mechanizationTypeTotals[$mechanizationType][
                                                    'available'
                                                ] += $mechanizationTypeAvailableNumber;
                                            @endphp

                                            @foreach ($mechanizationTypePlans as $mechanization)
                                                @php
                                                    $timelineData = $mechanization['timeline_data'];
                                                    $quartersData = $timelineData['quarters'] ?? [];
                                                    $monthsData = $timelineData['months'] ?? [];
                                                    $weeksData = $timelineData['weeks'] ?? [];
                                                    $mechanizationBreakdown = $timelineData['mechanization_breakdown'] ?? [];
                                                    $weeklyDistribution =
                                                        $mechanization['mechanization_distribution_details'][
                                                            'weekly_distribution'
                                                        ] ?? [];
                                                    $applicationYear = $timelineData['application_year'] ?? 'N/A';

                                                    $distributedNumber =
                                                        $timelineData['total_distributed_number'] ?? 0;

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
                                                                if (
                                                                    preg_match('/Week\s*(\d+)/i', $weekText, $matches)
                                                                ) {
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
                                                        $mechanizationNameTotals[$mechName]['number'] +=
                                                            $mechData['number'];
                                                    }
                                                @endphp

                                                <tr data-mechanization-id="{{ $mechanization['id'] }}">
                                                    <!-- Row counter -->
                                                    <td class="text-center fw-bold">{{ $counter++ }}</td>

                                                    <!-- Year (with rowspan) -->
                                                    @if ($yearFirst && $mechanizationTypeFirst)
                                                        <td rowspan="{{ $yearRowspan }}"
                                                            class="text-center align-middle fw-bold bg-light">
                                                            {{ $year }}
                                                        </td>
                                                    @endif

                                                    <!-- Mechanization Type (with rowspan) -->
                                                    @if ($mechanizationTypeFirst)
                                                        <td rowspan="{{ $mechanizationTypeRowspan }}"
                                                            class="text-center align-middle fw-bold bg-light">
                                                            {{ $mechanizationType }}
                                                            <br>
                                                            <small class="text-muted">Total Available:
                                                                {{ number_format($mechanizationTypeTotalNumber, 2) }}</small>
                                                        </td>
                                                    @endif

                                                    <!-- Individual mechanization data -->
                                                    <td class="fw-bold text-primary">{{ $mechanization['name'] }}</td>
                                                    <td class="text-center">number</td>
                                                    <td class="text-center fw-bold text-info">{{ $applicationYear }}
                                                    </td>
                                                    <td class="text-end fw-bold">
                                                        {{ number_format($availableNumber, 2) }}</td>

                                                    <!-- Quarter, Month, and Week Data -->
                                                    @foreach ($quarters as $quarter => $months)
                                                        @php
                                                            // Quarter data extraction
                                                            $quarterNumber = 0;
                                                            if (isset($quartersData[$quarter])) {
                                                                if (is_array($quartersData[$quarter])) {
                                                                    $quarterNumber =
                                                                        $quartersData[$quarter]['number'] ?? 0;
                                                                } else {
                                                                    $quarterNumber = floatval(
                                                                        $quartersData[$quarter],
                                                                    );
                                                                }
                                                            }
                                                            $quarterTotals[$quarter]['number'] += $quarterNumber;
                                                        @endphp

                                                        <!-- Quarter Total -->
                                                        <td
                                                            class="text-end fw-bold {{ $quarterNumber > 0 ? 'bg-primary-subtle' : '' }}">
                                                            {{ number_format($quarterNumber, 2) }}
                                                        </td>

                                                        <!-- Month Data for this Quarter -->
                                                        @foreach ($months as $month)
                                                            @php
                                                                // Month data extraction
                                                                $monthNumber = 0;
                                                                if (isset($monthsData[$month])) {
                                                                    if (is_array($monthsData[$month])) {
                                                                        $monthNumber =
                                                                            $monthsData[$month]['number'] ?? 0;
                                                                    } else {
                                                                        $monthNumber = floatval($monthsData[$month]);
                                                                    }
                                                                }
                                                                $monthTotals[$month]['number'] += $monthNumber;
                                                            @endphp

                                                            <!-- Month Total -->
                                                            <td
                                                                class="text-end fw-bold {{ $monthNumber > 0 ? 'bg-success-subtle' : '' }}">
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
                                                                            $weekNumber =
                                                                                $weekDataByMonth[$month][$week];
                                                                        }

                                                                        $weekTotals[$weekKey][
                                                                            'number'
                                                                        ] += $weekNumber;
                                                                    @endphp
                                                                    <td
                                                                        class="text-end {{ $weekNumber > 0 ? 'bg-warning-subtle' : '' }}">
                                                                        {{ number_format($weekNumber, 2) }}
                                                                    </td>
                                                                @endfor
                                                            @endif
                                                        @endforeach
                                                    @endforeach

                                                    <!-- Summary Data -->
                                                    <td class="text-end fw-bold bg-warning-subtle distributed-number">
                                                        {{ number_format($distributedNumber, 2) }}
                                                    </td>
                                                    <td
                                                        class="text-end fw-bold bg-warning-subtle remaining-number {{ $remainingNumber >= 0 ? 'text-success' : 'text-danger' }}">
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
                                    <tr class="table-dark fw-bold">
                                        <td colspan="6" class="text-center">GRAND TOTALS</td>
                                        <td class="text-center">
                                            <small>Total Available: {{ number_format($totalAvailableNumber, 2) }}
                                                number</small>
                                        </td>

                                        <!-- Quarter Totals -->
                                        @foreach ($quarters as $quarter => $months)
                                            <td class="text-end bg-primary text-white">
                                                {{ number_format($quarterTotals[$quarter]['number'], 2) }}
                                            </td>
                                            <!-- Empty cells for months and weeks in this quarter -->
                                            @foreach ($months as $month)
                                                <td class="text-end bg-success text-white">
                                                    {{ number_format($monthTotals[$month]['number'], 2) }}
                                                </td>
                                                @if (in_array($month, $monthsWithWeeks))
                                                    @for ($week = 1; $week <= 4; $week++)
                                                        @php
                                                            $weekKey = $month . ' W' . $week;
                                                        @endphp
                                                        <td class="text-end bg-warning text-white">
                                                            {{ number_format($weekTotals[$weekKey]['number'], 2) }}
                                                        </td>
                                                    @endfor
                                                @endif
                                            @endforeach
                                        @endforeach

                                        <!-- Summary Totals -->
                                        <td class="text-end bg-warning text-white">
                                            {{ number_format($totalDistributedNumber, 2) }}
                                        </td>
                                        <td
                                            class="text-end bg-warning text-white {{ $totalAvailableNumber - $totalDistributedNumber >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($totalAvailableNumber - $totalDistributedNumber, 2) }}
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
        }

        function toggleMechanizationBreakdown() {
            const breakdownHeaders = document.querySelectorAll('.mechanization-breakdown-header');
            const isVisible = breakdownHeaders[0].style.display !== 'none';

            breakdownHeaders.forEach(header => {
                header.style.display = isVisible ? 'none' : '';
            });

            // Here you would dynamically add/remove mechanization breakdown columns
            // This would require additional JavaScript to populate the breakdown data
            alert('Mechanization breakdown feature would show distribution by mechanization type');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('#mechanizationPlanTable tbody tr');
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
                const dataRows = document.querySelectorAll('#mechanizationPlanTable tbody tr');

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
        .distributed-number,
        .remaining-number {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%) !important;
            border-left: 3px solid #ffc107 !important;
            font-weight: 700 !important;
            font-size: 0.85rem !important;
        }

        .distributed-number {
            border-right: 2px solid #dee2e6 !important;
        }

        .remaining-number {
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

        .table-hover tbody tr:hover .distributed-number,
        .table-hover tbody tr:hover .remaining-number {
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

            .distributed-number,
            .remaining-number {
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

            .distributed-number,
            .remaining-number {
                background: #fff3cd !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* Highlight positive/negative remaining numbers */
        .remaining-number.text-success {
            color: #198754 !important;
            font-weight: 800 !important;
        }

        .remaining-number.text-danger {
            color: #dc3545 !important;
            font-weight: 800 !important;
        }

        /* Primary theme for mechanization table */
        .card-header.bg-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%) !important;
        }

        thead th.bg-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%) !important;
        }
    </style>
</x-layout>