<x-layout>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="mdi mdi-calendar-clock me-2"></i>
                            Crop Plan Area Distribution - Grouped by Year & Crop Type
                        </h4>
                        <button class="btn btn-light btn-sm" onclick="exportToExcel()">
                            <i class="mdi mdi-download me-1"></i> Export Excel
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0" id="cropPlanTable">
                                <thead class="table-dark sticky-top">
                                <tr>
                                    <th rowspan="4" class="text-center align-middle">#</th>
                                    <th rowspan="4" class="text-center align-middle">Planning Year</th>
                                    <th rowspan="4" class="text-center align-middle">Crop Type</th>
                                    <th rowspan="4" class="text-center align-middle">Crop Variety</th>
                                    <th rowspan="4" class="text-center align-middle">Unit</th>
                                    <th rowspan="4" class="text-center align-middle">Preparation Year</th>
                                    <th rowspan="4" class="text-center align-middle">Season</th>
                                    <th rowspan="4" class="text-center align-middle">Total Area (ha)</th>

                                   

                                  
                                </tr>
                                <tr>
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
                                        <th colspan="{{ $colspan }}" class="text-center bg-info text-white">{{ $quarter }}</th>
                                    @endforeach

                                    <!-- Summary Columns -->
                                    <th colspan="2" class="text-center bg-warning text-white">Area Summary</th>
                                </tr>
                                <tr>
                                    @foreach($quarters as $quarter => $months)
                                        <!-- Quarter Total -->
                                        <th class="text-center bg-primary text-white">{{ $quarter }} Total</th>
                                        
                                        @foreach($months as $month)
                                            @if(in_array($month, $monthsWithWeeks))
                                                <!-- Month Header spans all weeks in the month -->
                                                <th colspan="5" class="text-center bg-success text-white">{{ $month }}</th>
                                            @else
                                                <!-- Pagumen - only month header, no weeks -->
                                                <th class="text-center bg-success text-white">{{ $month }}</th>
                                            @endif
                                        @endforeach
                                    @endforeach

                                    <!-- Summary Sub-columns -->
                                    <th class="text-center bg-warning-subtle fw-bold">Distributed Area (ha)</th>
                                    <th class="text-center bg-warning-subtle fw-bold">Remaining Area (ha)</th>
                                </tr>
                                <tr>
                                    @foreach($quarters as $quarter => $months)
                                        <!-- Quarter Total Sub-columns -->
                                        <th class="text-center bg-primary-subtle">Area (ha)</th>
                                        
                                        @foreach($months as $month)
                                            <!-- Month Total -->
                                            <th class="text-center bg-success-subtle">{{ $month }} Total</th>
                                            
                                            <!-- Weeks for this Month (only for months with weeks) -->
                                            @if(in_array($month, $monthsWithWeeks))
                                                @for($week = 1; $week <= 4; $week++)
                                                    <th class="text-center bg-warning-subtle">W{{ $week }}</th>
                                                @endfor
                                            @endif
                                        @endforeach
                                    @endforeach

                                    <!-- Summary Sub-columns (duplicated for alignment) -->
                                    <th class="text-center bg-warning-subtle fw-bold">Area (ha)</th>
                                    <th class="text-center bg-warning-subtle fw-bold">Area (ha)</th>
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
                                                <td class="text-center fw-bold">{{ $counter++ }}</td>
                                                
                                                <!-- Year (with rowspan) -->
                                                @if($yearFirst && $cropTypeFirst)
                                                    <td rowspan="{{ $yearRowspan }}" class="text-center align-middle fw-bold bg-light">
                                                        {{ $year }}
                                                    </td>
                                                @endif
                                                
                                                <!-- Crop Type (with rowspan) -->
                                                @if($cropTypeFirst)
                                                    <td rowspan="{{ $cropTypeRowspan }}" class="text-center align-middle fw-bold bg-light">
                                                        {{ $cropType }}
                                                        <br>
                                                        <small class="text-muted">Total: {{ number_format($cropTypeTotalArea, 2) }} ha</small>
                                                    </td>
                                                @endif
                                                
                                                <!-- Individual plan data -->
                                                <td class="fw-bold text-primary">{{ $plan['crop_name'] }}</td>
                                                <td class="text-center">{{ $plan['unit'] }}</td>
                                                <td class="text-center fw-bold text-info">{{ $preparationYear }}</td>
                                                <td class="text-center">{{ $plan['season'] }}</td>
                                                <td class="text-end fw-bold">{{ number_format($plan['area_to_plant'], 2) }}</td>

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
                                                    <td class="text-end fw-bold {{ $quarterArea > 0 ? 'bg-primary-subtle' : '' }}">
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
                                                        <td class="text-end fw-bold {{ $monthArea > 0 ? 'bg-success-subtle' : '' }}">
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
                                                                <td class="text-end {{ $weekArea > 0 ? 'bg-warning-subtle' : '' }}">
                                                                    {{ number_format($weekArea, 2) }}
                                                                </td>
                                                            @endfor
                                                        @endif
                                                    @endforeach
                                                @endforeach

                                                <!-- Summary Data -->
                                                <td class="text-end fw-bold bg-warning-subtle distributed-area">
                                                    {{ number_format($distributedArea, 2) }}
                                                </td>
                                                <td class="text-end fw-bold bg-warning-subtle remaining-area {{ $remainingArea >= 0 ? 'text-success' : 'text-danger' }}">
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
        }

        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('#cropPlanTable tbody tr');
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
                const dataRows = document.querySelectorAll('#cropPlanTable tbody tr');
                
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
        .bg-info-subtle { background-color: #d1ecf1 !important; }
        .bg-success-subtle { background-color: #d1f2eb !important; }
        .bg-warning-subtle { background-color: #fff3cd !important; }
        .bg-primary-subtle { background-color: #cfe2ff !important; }
        
        /* Improved visibility for summary columns */
        .distributed-area, .remaining-area {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%) !important;
            border-left: 3px solid #ffc107 !important;
            font-weight: 700 !important;
            font-size: 0.85rem !important;
        }
        
        .distributed-area {
            border-right: 2px solid #dee2e6 !important;
        }
        
        .remaining-area {
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
        
        .table-hover tbody tr:hover .distributed-area,
        .table-hover tbody tr:hover .remaining-area {
            background: linear-gradient(135deg, #ffeaa7 0%, #ffd54f 100%) !important;
        }
        
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
        
        @media (max-width: 768px) {
            .table-responsive { font-size: 0.7rem; }
            .table th, .table td { padding: 0.3rem; }
            .distributed-area, .remaining-area { font-size: 0.75rem !important; }
        }
        
        @media print {
            .card-header button { display: none; }
            .table-responsive { overflow: visible; }
            .card { border: none; box-shadow: none; }
            .distributed-area, .remaining-area { 
                background: #fff3cd !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        
        /* Highlight positive/negative remaining areas */
        .remaining-area.text-success {
            color: #198754 !important;
            font-weight: 800 !important;
        }
        
        .remaining-area.text-danger {
            color: #dc3545 !important;
            font-weight: 800 !important;
        }
    </style>
</x-layout>