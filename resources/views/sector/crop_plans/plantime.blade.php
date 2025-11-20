<x-layout>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="mdi mdi-calendar-clock me-2"></i>
                            Crop Plan Timeline Distribution - Excel Format
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
                                    <th rowspan="2" class="text-center align-middle">#</th>
                                    <th rowspan="2" class="text-center align-middle">Crop Type</th>
                                    <th rowspan="2" class="text-center align-middle">Crop Variety</th>
                                    <th rowspan="2" class="text-center align-middle">Unit</th>
                                    <th rowspan="2" class="text-center align-middle">Planning Year</th>
                                    <th rowspan="2" class="text-center align-middle">Preparation Year</th>
                                    <th rowspan="2" class="text-center align-middle">Season</th>
                                    <th rowspan="2" class="text-center align-middle">Total Area (ha)</th>
                                    <th rowspan="2" class="text-center align-middle">Expected Yield (qt)</th>

                                    <!-- Quarters Header -->
                                    <th colspan="8" class="text-center bg-info text-dark">QUARTERLY DISTRIBUTION</th>

                                    <!-- Months Header -->
                                    <th colspan="26" class="text-center bg-success text-white">MONTHLY DISTRIBUTION</th>

                                    <!-- Summary -->
                                    <th colspan="4" class="text-center bg-warning text-dark">SUMMARY</th>
                                </tr>
                                <tr>
                                    <!-- Quarter Columns -->
                                    <th class="text-center bg-info-subtle">Q1 Area (ha)</th>
                                    <th class="text-center bg-info-subtle">Q1 Yield (qt)</th>
                                    <th class="text-center bg-info-subtle">Q2 Area (ha)</th>
                                    <th class="text-center bg-info-subtle">Q2 Yield (qt)</th>
                                    <th class="text-center bg-info-subtle">Q3 Area (ha)</th>
                                    <th class="text-center bg-info-subtle">Q3 Yield (qt)</th>
                                    <th class="text-center bg-info-subtle">Q4 Area (ha)</th>
                                    <th class="text-center bg-info-subtle">Q4 Yield (qt)</th>

                                    <!-- Month Columns -->
                                    @foreach(['Meskerem', 'Tikimt', 'Hidar', 'Tahsas', 'Tir', 'Yekatit', 'Megabit', 'Miyazia', 'Ginbot', 'Sene', 'Hamle', 'Nehase', 'Pagumen'] as $month)
                                        <th class="text-center bg-success-subtle">{{ $month }} Area</th>
                                        <th class="text-center bg-success-subtle">{{ $month }} Yield</th>
                                    @endforeach

                                    <!-- Summary Columns -->
                                    <th class="text-center bg-warning-subtle">Distributed Area (ha)</th>
                                    <th class="text-center bg-warning-subtle">Distributed Yield (qt)</th>
                                    <th class="text-center bg-warning-subtle">Remaining Area (ha)</th>
                                    <th class="text-center bg-warning-subtle">Remaining Yield (qt)</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $counter = 1;
                                    $totalArea = 0;
                                    $totalYield = 0;
                                    $totalDistributedArea = 0;
                                    $totalDistributedYield = 0;

                                    // Ethiopian months in order
                                    $ethiopianMonths = [
                                        'Meskerem', 'Tikimt', 'Hidar', 'Tahsas', 'Tir', 'Yekatit',
                                        'Megabit', 'Miyazia', 'Ginbot', 'Sene', 'Hamle', 'Nehase', 'Pagumen'
                                    ];
                                @endphp

                                @foreach($transformedPlans as $plan)
                                    @php
                                        $timelineData = $plan['timeline_data'];
                                        $quarters = $timelineData['quarters'] ?? [];
                                        $months = $timelineData['months'] ?? [];
                                        $preparationYear = $timelineData['preparation_year'] ?? 'N/A';

                                        $distributedArea = $timelineData['total_distributed_area'] ?? 0;
                                        $distributedYield = $timelineData['total_distributed_yield'] ?? 0;
                                        $remainingArea = $plan['area_to_plant'] - $distributedArea;
                                        $remainingYield = $plan['expected_yield'] - $distributedYield;

                                        $totalArea += $plan['area_to_plant'];
                                        $totalYield += $plan['expected_yield'];
                                        $totalDistributedArea += $distributedArea;
                                        $totalDistributedYield += $distributedYield;
                                    @endphp

                                    <tr>
                                        <td class="text-center fw-bold">{{ $counter++ }}</td>
                                        <td class="fw-bold">{{ $plan['crop_type'] }}</td>
                                        <td class="fw-bold text-primary">{{ $plan['crop_name'] }}</td>
                                        <td class="text-center">{{ $plan['unit'] }}</td>
                                        <td class="text-center">{{ $plan['year'] }}</td>
                                        <td class="text-center fw-bold text-info">{{ $preparationYear }}</td>
                                        <td class="text-center">{{ $plan['season'] }}</td>
                                        <td class="text-end fw-bold">{{ number_format($plan['area_to_plant'], 2) }}</td>
                                        <td class="text-end fw-bold">{{ number_format($plan['expected_yield'], 2) }}</td>

                                        <!-- Quarter Data -->
                                        @foreach(['Q1', 'Q2', 'Q3', 'Q4'] as $quarter)
                                            @php
                                                $quarterData = $quarters[$quarter] ?? ['area' => 0, 'yield' => 0];
                                            @endphp
                                            <td class="text-end {{ $quarterData['area'] > 0 ? 'bg-info-subtle' : '' }}">
                                                {{ number_format($quarterData['area'], 2) }}
                                            </td>
                                            <td class="text-end {{ $quarterData['yield'] > 0 ? 'bg-info-subtle' : '' }}">
                                                {{ number_format($quarterData['yield'], 2) }}
                                            </td>
                                        @endforeach

                                        <!-- Month Data -->
                                        @foreach($ethiopianMonths as $month)
                                            @php
                                                $monthData = $months[$month] ?? ['area' => 0, 'yield' => 0];
                                            @endphp
                                            <td class="text-end {{ $monthData['area'] > 0 ? 'bg-success-subtle' : '' }}">
                                                {{ number_format($monthData['area'], 2) }}
                                            </td>
                                            <td class="text-end {{ $monthData['yield'] > 0 ? 'bg-success-subtle' : '' }}">
                                                {{ number_format($monthData['yield'], 2) }}
                                            </td>
                                        @endforeach

                                        <!-- Summary Data -->
                                        <td class="text-end fw-bold {{ $distributedArea > 0 ? 'bg-warning-subtle' : '' }}">
                                            {{ number_format($distributedArea, 2) }}
                                        </td>
                                        <td class="text-end fw-bold {{ $distributedYield > 0 ? 'bg-warning-subtle' : '' }}">
                                            {{ number_format($distributedYield, 2) }}
                                        </td>
                                        <td class="text-end fw-bold {{ $remainingArea >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($remainingArea, 2) }}
                                        </td>
                                        <td class="text-end fw-bold {{ $remainingYield >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($remainingYield, 2) }}
                                        </td>
                                    </tr>
                                @endforeach

                                <!-- Total Row -->
                                <tr class="table-secondary fw-bold">
                                    <td colspan="7" class="text-center">TOTAL</td>
                                    <td class="text-end">{{ number_format($totalArea, 2) }}</td>
                                    <td class="text-end">{{ number_format($totalYield, 2) }}</td>

                                    <!-- Quarter Totals -->
                                    @foreach(['Q1', 'Q2', 'Q3', 'Q4'] as $quarter)
                                        @php
                                            $quarterTotalArea = 0;
                                            $quarterTotalYield = 0;
                                            foreach($transformedPlans as $plan) {
                                                $quarterData = $plan['timeline_data']['quarters'][$quarter] ?? ['area' => 0, 'yield' => 0];
                                                $quarterTotalArea += $quarterData['area'];
                                                $quarterTotalYield += $quarterData['yield'];
                                            }
                                        @endphp
                                        <td class="text-end bg-info-subtle">{{ number_format($quarterTotalArea, 2) }}</td>
                                        <td class="text-end bg-info-subtle">{{ number_format($quarterTotalYield, 2) }}</td>
                                    @endforeach

                                    <!-- Month Totals -->
                                    @foreach($ethiopianMonths as $month)
                                        @php
                                            $monthTotalArea = 0;
                                            $monthTotalYield = 0;
                                            foreach($transformedPlans as $plan) {
                                                $monthData = $plan['timeline_data']['months'][$month] ?? ['area' => 0, 'yield' => 0];
                                                $monthTotalArea += $monthData['area'];
                                                $monthTotalYield += $monthData['yield'];
                                            }
                                        @endphp
                                        <td class="text-end bg-success-subtle">{{ number_format($monthTotalArea, 2) }}</td>
                                        <td class="text-end bg-success-subtle">{{ number_format($monthTotalYield, 2) }}</td>
                                    @endforeach

                                    <!-- Summary Totals -->
                                    <td class="text-end bg-warning-subtle">{{ number_format($totalDistributedArea, 2) }}</td>
                                    <td class="text-end bg-warning-subtle">{{ number_format($totalDistributedYield, 2) }}</td>
                                    <td class="text-end bg-warning-subtle">{{ number_format($totalArea - $totalDistributedArea, 2) }}</td>
                                    <td class="text-end bg-warning-subtle">{{ number_format($totalYield - $totalDistributedYield, 2) }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h3>{{ count($transformedPlans) }}</h3>
                                <p class="mb-0">Total Crop Plans</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h3>{{ number_format($totalArea, 2) }} ha</h3>
                                <p class="mb-0">Total Planning Area</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h3>{{ number_format($totalDistributedArea, 2) }} ha</h3>
                                <p class="mb-0">Total Distributed Area</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h3>{{ number_format(($totalDistributedArea / $totalArea) * 100, 1) }}%</h3>
                                <p class="mb-0">Distribution Rate</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function exportToExcel() {
            // Create a temporary table for export
            const table = document.getElementById('cropPlanTable');
            const html = table.outerHTML;

            // Create blob and download
            const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'crop-plan-timeline-' + new Date().toISOString().split('T')[0] + '.xls';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        // Add hover effects
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
        });
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

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }

        /* Print styles */
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
        }
    </style>
</x-layout>
