<x-layout>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="mdi mdi-tree me-2"></i>
                            Seedling Planting Plan - Grouped by Year & Type
                        </h4>
                        <div>
                            <button class="btn btn-light btn-sm me-2" onclick="exportToExcel()">
                                <i class="mdi mdi-download me-1"></i> Export Excel
                            </button>
                            <button class="btn btn-warning btn-sm" onclick="toggleSeedlingBreakdown()">
                                <i class="mdi mdi-chart-pie me-1"></i> Toggle Breakdown
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0" id="seedlingPlanTable">
                                <thead class="table-dark sticky-top">
                                    <tr>
                                        <th rowspan="4" class="text-center align-middle">#</th>
                                        <th rowspan="4" class="text-center align-middle">Planning Year</th>
                                        <th rowspan="4" class="text-center align-middle">Seedling Type</th>
                                        <th rowspan="4" class="text-center align-middle">Seedling Name</th>
                                        <th rowspan="4" class="text-center align-middle">Application Year</th>
                                        <th rowspan="4" class="text-center align-middle">Total Prepared</th>

                                        <!-- Quarter Headers -->
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

                                            // Calculate total columns for each quarter
                                            $quarterColspans = [];
                                            foreach ($quarters as $quarter => $months) {
                                                $colspan = 3; // Quarter total columns
                                                foreach ($months as $month) {
                                                    $colspan += 3; // Month total columns
                                                    if (in_array($month, $monthsWithWeeks)) {
                                                        $colspan += 12; // 4 weeks × 3 columns each
                                                    }
                                                }
                                                $quarterColspans[$quarter] = $colspan;
                                            }
                                        @endphp

                                        @foreach ($quarters as $quarter => $months)
                                            <th colspan="{{ $quarterColspans[$quarter] }}" class="text-center bg-info text-white">
                                                {{ $quarter }}
                                            </th>
                                        @endforeach

                                        <!-- Summary Columns -->
                                        <th colspan="6" class="text-center bg-warning text-white">Planting Summary</th>

                                        <!-- Seedling Breakdown Columns (initially hidden) -->
                                        <th colspan="0" class="text-center bg-info text-white seedling-breakdown-header" style="display: none;">
                                            Seedling Breakdown
                                        </th>
                                    </tr>
                                    <tr>
                                        @foreach ($quarters as $quarter => $months)
                                            <!-- Quarter Total -->
                                            <th colspan="3" class="text-center bg-primary text-white">{{ $quarter }} Total</th>

                                            @foreach ($months as $month)
                                                @if (in_array($month, $monthsWithWeeks))
                                                    <!-- Month Header spans all weeks in the month -->
                                                    <th colspan="15" class="text-center bg-success text-white">
                                                        {{ $month }}
                                                    </th>
                                                @else
                                                    <!-- Pagumen - only month header, no weeks -->
                                                    <th colspan="3" class="text-center bg-success text-white">{{ $month }}</th>
                                                @endif
                                            @endforeach
                                        @endforeach

                                        <!-- Summary Sub-columns -->
                                        <th colspan="2" class="text-center bg-warning-subtle fw-bold">Planted Quantity</th>
                                        <th colspan="2" class="text-center bg-warning-subtle fw-bold">Participants</th>
                                        <th colspan="2" class="text-center bg-warning-subtle fw-bold">Days</th>

                                        <!-- Seedling Breakdown Data Headers (will be populated dynamically) -->
                                    </tr>
                                    <tr>
                                        @foreach ($quarters as $quarter => $months)
                                            <!-- Quarter Total Sub-columns -->
                                            <th class="text-center bg-primary-subtle">Seedlings</th>
                                            <th class="text-center bg-primary-subtle">Participants</th>
                                            <th class="text-center bg-primary-subtle">Days</th>

                                            @foreach ($months as $month)
                                                <!-- Month Total -->
                                                <th class="text-center bg-success-subtle">Seedlings</th>
                                                <th class="text-center bg-success-subtle">Participants</th>
                                                <th class="text-center bg-success-subtle">Days</th>

                                                <!-- Weeks for this Month (only for months with weeks) -->
                                                @if (in_array($month, $monthsWithWeeks))
                                                    @for ($week = 1; $week <= 4; $week++)
                                                        <th colspan="3" class="text-center bg-warning-subtle">W{{ $week }}</th>
                                                    @endfor
                                                @endif
                                            @endforeach
                                        @endforeach

                                        <!-- Summary Sub-columns -->
                                        <th class="text-center bg-warning-subtle fw-bold">Planted</th>
                                        <th class="text-center bg-warning-subtle fw-bold">Remaining</th>
                                        <th class="text-center bg-warning-subtle fw-bold">Total</th>
                                        <th class="text-center bg-warning-subtle fw-bold">Avg/Day</th>
                                        <th class="text-center bg-warning-subtle fw-bold">Total</th>
                                        <th class="text-center bg-warning-subtle fw-bold">Avg/Day</th>

                                        <!-- Seedling Breakdown Data Sub-headers (will be populated dynamically) -->
                                    </tr>
                                    <tr>
                                        @foreach ($quarters as $quarter => $months)
                                            <!-- Quarter Total Sub-columns (already defined above) -->
                                            <th class="text-center bg-primary-subtle">Qty</th>
                                            <th class="text-center bg-primary-subtle">Parts</th>
                                            <th class="text-center bg-primary-subtle">Days</th>

                                            @foreach ($months as $month)
                                                <!-- Month Total Sub-columns -->
                                                <th class="text-center bg-success-subtle">Qty</th>
                                                <th class="text-center bg-success-subtle">Parts</th>
                                                <th class="text-center bg-success-subtle">Days</th>

                                                <!-- Weeks for this Month (only for months with weeks) -->
                                                @if (in_array($month, $monthsWithWeeks))
                                                    @for ($week = 1; $week <= 4; $week++)
                                                        <th class="text-center bg-warning-subtle">Qty</th>
                                                        <th class="text-center bg-warning-subtle">Parts</th>
                                                        <th class="text-center bg-warning-subtle">Days</th>
                                                    @endfor
                                                @endif
                                            @endforeach
                                        @endforeach

                                        <!-- Summary Sub-columns (already defined) -->
                                        <th class="text-center bg-warning-subtle fw-bold">Qty</th>
                                        <th class="text-center bg-warning-subtle fw-bold">Rem</th>
                                        <th class="text-center bg-warning-subtle fw-bold">Total</th>
                                        <th class="text-center bg-warning-subtle fw-bold">Avg</th>
                                        <th class="text-center bg-warning-subtle fw-bold">Total</th>
                                        <th class="text-center bg-warning-subtle fw-bold">Avg</th>

                                        <!-- Seedling Breakdown Data Sub-headers (will be populated dynamically) -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $counter = 1;
                                        $totalPreparedQuantity = 0;
                                        $totalPlantedQuantity = 0;
                                        $totalParticipants = 0;
                                        $totalDays = 0;

                                        // Group data by Planning Year and Seedling Type
                                        $groupedSeedlingPlans = [];
                                        foreach ($transformedSeedlingPlans as $seedling) {
                                            $year = $seedling['year'];
                                            $seedlingType = $seedling['seedling_type'];
                                            if (!isset($groupedSeedlingPlans[$year])) {
                                                $groupedSeedlingPlans[$year] = [];
                                            }
                                            if (!isset($groupedSeedlingPlans[$year][$seedlingType])) {
                                                $groupedSeedlingPlans[$year][$seedlingType] = [];
                                            }
                                            $groupedSeedlingPlans[$year][$seedlingType][] = $seedling;
                                        }

                                        // Initialize totals arrays
                                        $yearTotals = [];
                                        $seedlingTypeTotals = [];
                                        $quarterTotals = [
                                            'Q1' => ['quantity' => 0, 'participants' => 0, 'days' => 0],
                                            'Q2' => ['quantity' => 0, 'participants' => 0, 'days' => 0],
                                            'Q3' => ['quantity' => 0, 'participants' => 0, 'days' => 0],
                                            'Q4' => ['quantity' => 0, 'participants' => 0, 'days' => 0],
                                        ];
                                        $monthTotals = [];
                                        $weekTotals = [];
                                        $seedlingNameTotals = [];

                                        foreach ($quarters as $quarterMonths) {
                                            foreach ($quarterMonths as $month) {
                                                $monthTotals[$month] = ['quantity' => 0, 'participants' => 0, 'days' => 0];
                                                if (in_array($month, $monthsWithWeeks)) {
                                                    for ($week = 1; $week <= 4; $week++) {
                                                        $weekKey = $month . ' W' . $week;
                                                        $weekTotals[$weekKey] = ['quantity' => 0, 'participants' => 0, 'days' => 0];
                                                    }
                                                }
                                            }
                                        }
                                    @endphp

                                    @foreach ($groupedSeedlingPlans as $year => $seedlingTypes)
                                        @php
                                            $yearRowspan = 0;
                                            foreach ($seedlingTypes as $seedlingTypePlans) {
                                                $yearRowspan += count($seedlingTypePlans);
                                            }
                                            $yearFirst = true;
                                        @endphp

                                        @foreach ($seedlingTypes as $seedlingType => $seedlingTypePlans)
                                            @php
                                                $seedlingTypeRowspan = count($seedlingTypePlans);
                                                $seedlingTypeFirst = true;
                                                $seedlingTypeTotalPrepared = 0;
                                                $seedlingTypeTotalPlanted = 0;
                                                $seedlingTypeTotalParticipants = 0;
                                                $seedlingTypeTotalDays = 0;

                                                // Calculate seedling type totals
                                                foreach ($seedlingTypePlans as $seedling) {
                                                    $seedlingTypeTotalPrepared += $seedling['total_prepared_quantity'];
                                                    $seedlingTypeTotalPlanted += $seedling['total_planted_quantity'];
                                                    $seedlingTypeTotalParticipants += $seedling['total_participants'];
                                                    $seedlingTypeTotalDays += $seedling['total_days'];
                                                }

                                                if (!isset($yearTotals[$year])) {
                                                    $yearTotals[$year] = [
                                                        'prepared' => 0,
                                                        'planted' => 0,
                                                        'participants' => 0,
                                                        'days' => 0,
                                                    ];
                                                }
                                                if (!isset($seedlingTypeTotals[$seedlingType])) {
                                                    $seedlingTypeTotals[$seedlingType] = [
                                                        'prepared' => 0,
                                                        'planted' => 0,
                                                        'participants' => 0,
                                                        'days' => 0,
                                                    ];
                                                }
                                                $yearTotals[$year]['prepared'] += $seedlingTypeTotalPrepared;
                                                $yearTotals[$year]['planted'] += $seedlingTypeTotalPlanted;
                                                $yearTotals[$year]['participants'] += $seedlingTypeTotalParticipants;
                                                $yearTotals[$year]['days'] += $seedlingTypeTotalDays;
                                                $seedlingTypeTotals[$seedlingType]['prepared'] += $seedlingTypeTotalPrepared;
                                                $seedlingTypeTotals[$seedlingType]['planted'] += $seedlingTypeTotalPlanted;
                                                $seedlingTypeTotals[$seedlingType]['participants'] += $seedlingTypeTotalParticipants;
                                                $seedlingTypeTotals[$seedlingType]['days'] += $seedlingTypeTotalDays;
                                            @endphp

                                            @foreach ($seedlingTypePlans as $seedling)
                                                @php
                                                    $timelineData = $seedling['timeline_data'];
                                                    $quartersData = $timelineData['quarters'] ?? [];
                                                    $monthsData = $timelineData['months'] ?? [];
                                                    $weeksData = $timelineData['weeks'] ?? [];
                                                    $seedlingBreakdown = $timelineData['seedling_breakdown'] ?? [];
                                                    $weeklyPlanting = $seedling['planting_data']['selected_seedlings'] ?? [];
                                                    $applicationYear = $timelineData['application_year'] ?? 'N/A';

                                                    $plantedQuantity = $seedling['total_planted_quantity'];
                                                    $participants = $seedling['total_participants'];
                                                    $days = $seedling['total_days'];

                                                    // Calculate prepared quantity
                                                    $preparedQuantity = $seedling['total_prepared_quantity'];
                                                    $remainingQuantity = $preparedQuantity - $plantedQuantity;

                                                    $totalPreparedQuantity += $preparedQuantity;
                                                    $totalPlantedQuantity += $plantedQuantity;
                                                    $totalParticipants += $participants;
                                                    $totalDays += $days;

                                                    // Calculate averages
                                                    $avgParticipantsPerDay = $days > 0 ? $participants / $days : 0;
                                                    $avgDaysPerParticipant = $participants > 0 ? $days / $participants : 0;

                                                    // Process weekly planting data for seedlings
                                                    $weekDataByMonth = [];

                                                    // Method 1: Process from the structured weeks data (backend processed)
                                                    foreach ($weeksData as $weekKey => $weekItem) {
                                                        // Handle "Ginbot_week_1" format
                                                        if (preg_match('/^(.+)_week_(\d+)$/', $weekKey, $matches)) {
                                                            $monthName = $matches[1];
                                                            $weekNum = (int) $matches[2];
                                                            $quantity = $weekItem['quantity'] ?? 0;
                                                            $weekParticipants = $weekItem['participants'] ?? 0;
                                                            $weekDays = $weekItem['days'] ?? 0;

                                                            if ($monthName && $weekNum >= 1 && $weekNum <= 4) {
                                                                if (!isset($weekDataByMonth[$monthName])) {
                                                                    $weekDataByMonth[$monthName] = [];
                                                                }
                                                                $weekDataByMonth[$monthName][$weekNum] = [
                                                                    'quantity' => $quantity,
                                                                    'participants' => $weekParticipants,
                                                                    'days' => $weekDays
                                                                ];
                                                            }
                                                        }
                                                    }

                                                    // Method 2: Process from weekly planting data if method 1 didn't work
                                                    if (empty($weekDataByMonth) && !empty($weeklyPlanting)) {
                                                        foreach ($weeklyPlanting as $seedlingData) {
                                                            if (is_array($seedlingData)) {
                                                                $weeklyPlantings = $seedlingData['weekly_planting'] ?? [];
                                                                foreach ($weeklyPlantings as $weekItem) {
                                                                    if (is_array($weekItem)) {
                                                                        $monthName = $weekItem['month'] ?? '';
                                                                        $weekText = $weekItem['week'] ?? '';
                                                                        $quantity = $weekItem['hanga_dhaabbii'] ?? 0;
                                                                        $weekParticipants = $weekItem['nama_hirmaate_dhaabbii'] ?? 0;
                                                                        $weekDays = $weekItem['baayina_guyyaa_dhaabbii'] ?? 0;

                                                                        // Extract week number from "Week 1", "Week 2", etc.
                                                                        $weekNum = 0;
                                                                        if (preg_match('/Week\s*(\d+)/i', $weekText, $matches)) {
                                                                            $weekNum = (int) $matches[1];
                                                                        }

                                                                        if ($monthName && $weekNum >= 1 && $weekNum <= 4) {
                                                                            if (!isset($weekDataByMonth[$monthName])) {
                                                                                $weekDataByMonth[$monthName] = [];
                                                                            }
                                                                            $weekDataByMonth[$monthName][$weekNum] = [
                                                                                'quantity' => $quantity,
                                                                                'participants' => $weekParticipants,
                                                                                'days' => $weekDays
                                                                            ];
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }

                                                    // Track seedling name totals for breakdown
                                                    foreach ($seedlingBreakdown as $seedlingName => $seedlingData) {
                                                        if (!isset($seedlingNameTotals[$seedlingName])) {
                                                            $seedlingNameTotals[$seedlingName] = [
                                                                'quantity' => 0,
                                                                'participants' => 0,
                                                                'days' => 0,
                                                            ];
                                                        }
                                                        $seedlingNameTotals[$seedlingName]['quantity'] += $seedlingData['quantity'];
                                                        $seedlingNameTotals[$seedlingName]['participants'] += $seedlingData['participants'];
                                                        $seedlingNameTotals[$seedlingName]['days'] += $seedlingData['days'];
                                                    }
                                                @endphp

                                                <tr data-seedling-id="{{ $seedling['id'] }}">
                                                    <!-- Row counter -->
                                                    <td class="text-center fw-bold">{{ $counter++ }}</td>

                                                    <!-- Year (with rowspan) -->
                                                    @if ($yearFirst && $seedlingTypeFirst)
                                                        <td rowspan="{{ $yearRowspan }}" class="text-center align-middle fw-bold bg-light">
                                                            {{ $year }}
                                                        </td>
                                                    @endif

                                                    <!-- Seedling Type (with rowspan) -->
                                                    @if ($seedlingTypeFirst)
                                                        <td rowspan="{{ $seedlingTypeRowspan }}" class="text-center align-middle fw-bold bg-light">
                                                            {{ $seedlingType }}
                                                            <br>
                                                            <small class="text-muted">Total Prepared: {{ number_format($seedlingTypeTotalPrepared, 2) }}</small>
                                                        </td>
                                                    @endif

                                                    <!-- Individual seedling data -->
                                                    <td class="fw-bold text-success">
                                                        @if(!empty($seedling['seedlings']))
                                                            @foreach($seedling['seedlings'] as $s)
                                                                <div class="small">{{ $s['name'] }} ({{ number_format($s['hanga_qophii'], 2) }})</div>
                                                            @endforeach
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td class="text-center fw-bold text-info">{{ $applicationYear }}</td>
                                                    <td class="text-end fw-bold">
                                                        {{ number_format($preparedQuantity, 2) }}</td>

                                                    <!-- Quarter, Month, and Week Data -->
                                                    @foreach ($quarters as $quarter => $months)
                                                        @php
                                                            // Quarter data extraction
                                                            $quarterQuantity = 0;
                                                            $quarterParticipants = 0;
                                                            $quarterDays = 0;
                                                            if (isset($quartersData[$quarter])) {
                                                                if (is_array($quartersData[$quarter])) {
                                                                    $quarterQuantity = $quartersData[$quarter]['quantity'] ?? 0;
                                                                    $quarterParticipants = $quartersData[$quarter]['participants'] ?? 0;
                                                                    $quarterDays = $quartersData[$quarter]['days'] ?? 0;
                                                                }
                                                            }
                                                            $quarterTotals[$quarter]['quantity'] += $quarterQuantity;
                                                            $quarterTotals[$quarter]['participants'] += $quarterParticipants;
                                                            $quarterTotals[$quarter]['days'] += $quarterDays;
                                                        @endphp

                                                        <!-- Quarter Total -->
                                                        <td class="text-end fw-bold {{ $quarterQuantity > 0 ? 'bg-primary-subtle' : '' }}">
                                                            {{ number_format($quarterQuantity, 2) }}
                                                        </td>
                                                        <td class="text-end fw-bold {{ $quarterParticipants > 0 ? 'bg-primary-subtle' : '' }}">
                                                            {{ number_format($quarterParticipants, 2) }}
                                                        </td>
                                                        <td class="text-end fw-bold {{ $quarterDays > 0 ? 'bg-primary-subtle' : '' }}">
                                                            {{ number_format($quarterDays, 2) }}
                                                        </td>

                                                        <!-- Month Data for this Quarter -->
                                                        @foreach ($months as $month)
                                                            @php
                                                                // Month data extraction
                                                                $monthQuantity = 0;
                                                                $monthParticipants = 0;
                                                                $monthDays = 0;
                                                                if (isset($monthsData[$month])) {
                                                                    if (is_array($monthsData[$month])) {
                                                                        $monthQuantity = $monthsData[$month]['quantity'] ?? 0;
                                                                        $monthParticipants = $monthsData[$month]['participants'] ?? 0;
                                                                        $monthDays = $monthsData[$month]['days'] ?? 0;
                                                                    }
                                                                }
                                                                $monthTotals[$month]['quantity'] += $monthQuantity;
                                                                $monthTotals[$month]['participants'] += $monthParticipants;
                                                                $monthTotals[$month]['days'] += $monthDays;
                                                            @endphp

                                                            <!-- Month Total -->
                                                            <td class="text-end fw-bold {{ $monthQuantity > 0 ? 'bg-success-subtle' : '' }}">
                                                                {{ number_format($monthQuantity, 2) }}
                                                            </td>
                                                            <td class="text-end fw-bold {{ $monthParticipants > 0 ? 'bg-success-subtle' : '' }}">
                                                                {{ number_format($monthParticipants, 2) }}
                                                            </td>
                                                            <td class="text-end fw-bold {{ $monthDays > 0 ? 'bg-success-subtle' : '' }}">
                                                                {{ number_format($monthDays, 2) }}
                                                            </td>

                                                            <!-- Week Data for this Month (only for months with weeks) -->
                                                            @if (in_array($month, $monthsWithWeeks))
                                                                @for ($week = 1; $week <= 4; $week++)
                                                                    @php
                                                                        $weekKey = $month . ' W' . $week;
                                                                        $weekQuantity = 0;
                                                                        $weekParticipants = 0;
                                                                        $weekDays = 0;

                                                                        // Get week data from the processed weekly planting
                                                                        if (isset($weekDataByMonth[$month][$week])) {
                                                                            $weekQuantity = $weekDataByMonth[$month][$week]['quantity'];
                                                                            $weekParticipants = $weekDataByMonth[$month][$week]['participants'];
                                                                            $weekDays = $weekDataByMonth[$month][$week]['days'];
                                                                        }

                                                                        $weekTotals[$weekKey]['quantity'] += $weekQuantity;
                                                                        $weekTotals[$weekKey]['participants'] += $weekParticipants;
                                                                        $weekTotals[$weekKey]['days'] += $weekDays;
                                                                    @endphp
                                                                    <td class="text-end {{ $weekQuantity > 0 ? 'bg-warning-subtle' : '' }}">
                                                                        {{ number_format($weekQuantity, 2) }}
                                                                    </td>
                                                                    <td class="text-end {{ $weekParticipants > 0 ? 'bg-warning-subtle' : '' }}">
                                                                        {{ number_format($weekParticipants, 2) }}
                                                                    </td>
                                                                    <td class="text-end {{ $weekDays > 0 ? 'bg-warning-subtle' : '' }}">
                                                                        {{ number_format($weekDays, 2) }}
                                                                    </td>
                                                                @endfor
                                                            @endif
                                                        @endforeach
                                                    @endforeach

                                                    <!-- Summary Data -->
                                                    <td class="text-end fw-bold bg-warning-subtle planted-quantity">
                                                        {{ number_format($plantedQuantity, 2) }}
                                                    </td>
                                                    <td class="text-end fw-bold bg-warning-subtle remaining-quantity {{ $remainingQuantity >= 0 ? 'text-success' : 'text-danger' }}">
                                                        {{ number_format($remainingQuantity, 2) }}
                                                    </td>
                                                    <td class="text-end fw-bold bg-warning-subtle">
                                                        {{ number_format($participants, 2) }}
                                                    </td>
                                                    <td class="text-end fw-bold bg-warning-subtle">
                                                        {{ number_format($avgParticipantsPerDay, 2) }}
                                                    </td>
                                                    <td class="text-end fw-bold bg-warning-subtle">
                                                        {{ number_format($days, 2) }}
                                                    </td>
                                                    <td class="text-end fw-bold bg-warning-subtle">
                                                        {{ number_format($avgDaysPerParticipant, 2) }}
                                                    </td>

                                                    <!-- Seedling Breakdown Data (will be populated dynamically) -->
                                                </tr>
                                                @php
                                                    $yearFirst = false;
                                                    $seedlingTypeFirst = false;
                                                @endphp
                                            @endforeach
                                        @endforeach
                                    @endforeach

                                    <!-- Grand Totals Row -->
                                    <tr class="table-dark fw-bold">
                                        <td colspan="5" class="text-center">GRAND TOTALS</td>
                                        <td class="text-center">
                                            <small>Total Prepared: {{ number_format($totalPreparedQuantity, 2) }}</small>
                                        </td>

                                        <!-- Quarter Totals -->
                                        @foreach ($quarters as $quarter => $months)
                                            <td class="text-end bg-primary text-white">
                                                {{ number_format($quarterTotals[$quarter]['quantity'], 2) }}
                                            </td>
                                            <td class="text-end bg-primary text-white">
                                                {{ number_format($quarterTotals[$quarter]['participants'], 2) }}
                                            </td>
                                            <td class="text-end bg-primary text-white">
                                                {{ number_format($quarterTotals[$quarter]['days'], 2) }}
                                            </td>
                                            <!-- Month and Week totals in this quarter -->
                                            @foreach ($months as $month)
                                                <td class="text-end bg-success text-white">
                                                    {{ number_format($monthTotals[$month]['quantity'], 2) }}
                                                </td>
                                                <td class="text-end bg-success text-white">
                                                    {{ number_format($monthTotals[$month]['participants'], 2) }}
                                                </td>
                                                <td class="text-end bg-success text-white">
                                                    {{ number_format($monthTotals[$month]['days'], 2) }}
                                                </td>
                                                @if (in_array($month, $monthsWithWeeks))
                                                    @for ($week = 1; $week <= 4; $week++)
                                                        @php
                                                            $weekKey = $month . ' W' . $week;
                                                        @endphp
                                                        <td class="text-end bg-warning text-white">
                                                            {{ number_format($weekTotals[$weekKey]['quantity'], 2) }}
                                                        </td>
                                                        <td class="text-end bg-warning text-white">
                                                            {{ number_format($weekTotals[$weekKey]['participants'], 2) }}
                                                        </td>
                                                        <td class="text-end bg-warning text-white">
                                                            {{ number_format($weekTotals[$weekKey]['days'], 2) }}
                                                        </td>
                                                    @endfor
                                                @endif
                                            @endforeach
                                        @endforeach

                                        <!-- Summary Totals -->
                                        <td class="text-end bg-warning text-white">
                                            {{ number_format($totalPlantedQuantity, 2) }}
                                        </td>
                                        <td class="text-end bg-warning text-white {{ $totalPreparedQuantity - $totalPlantedQuantity >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($totalPreparedQuantity - $totalPlantedQuantity, 2) }}
                                        </td>
                                        <td class="text-end bg-warning text-white">
                                            {{ number_format($totalParticipants, 2) }}
                                        </td>
                                        <td class="text-end bg-warning text-white">
                                            {{ number_format($totalDays > 0 ? $totalParticipants / $totalDays : 0, 2) }}
                                        </td>
                                        <td class="text-end bg-warning text-white">
                                            {{ number_format($totalDays, 2) }}
                                        </td>
                                        <td class="text-end bg-warning text-white">
                                            {{ number_format($totalParticipants > 0 ? $totalDays / $totalParticipants : 0, 2) }}
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
            const table = document.getElementById('seedlingPlanTable');
            const html = table.outerHTML;
            const blob = new Blob([html], {
                type: 'application/vnd.ms-excel'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'seedling-plan-' + new Date().toISOString().split('T')[0] + '.xls';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        function toggleSeedlingBreakdown() {
            const breakdownHeaders = document.querySelectorAll('.seedling-breakdown-header');
            const isVisible = breakdownHeaders[0].style.display !== 'none';

            breakdownHeaders.forEach(header => {
                header.style.display = isVisible ? 'none' : '';
            });

            // Here you would dynamically add/remove seedling breakdown columns
            // This would require additional JavaScript to populate the breakdown data
            alert('Seedling breakdown feature would show distribution by seedling type');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('#seedlingPlanTable tbody tr');
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

                    for (let i = 1; i <= 12; i++) { // 4 weeks × 3 columns each
                        const weekHeader = allHeaders[monthIndex + i + 3]; // +3 for month total columns
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
                const dataRows = document.querySelectorAll('#seedlingPlanTable tbody tr');

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
        .planted-quantity,
        .remaining-quantity {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%) !important;
            border-left: 3px solid #ffc107 !important;
            font-weight: 700 !important;
            font-size: 0.85rem !important;
        }

        .planted-quantity {
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

        .table-hover tbody tr:hover .planted-quantity,
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

            .planted-quantity,
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

            .planted-quantity,
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

        /* Success theme for seedling table */
        .card-header.bg-success {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%) !important;
        }

        thead th.bg-success {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%) !important;
        }

        thead th.bg-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%) !important;
        }

        /* Better spacing for seedling names */
        .table td .small {
            line-height: 1.2;
            margin-bottom: 2px;
        }

        .table td .small:last-child {
            margin-bottom: 0;
        }
    </style>
</x-layout>