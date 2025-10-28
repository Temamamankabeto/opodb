<x-layout>
    <div class="container">
        <h2 class="my-4">Ganna Season Crop Report (Grouped by Year)</h2>
        
        <!-- Main Content Area - Split into two columns -->
        <div class="row">
            <!-- Detailed Records Column (75% width) -->
            <div class="col-md-9">
                @if($groupedByYear->count() > 0)
                    <!-- Charts Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">Your Data Visualization</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Pie Chart -->
                                <div class="col-md-6">
                                    <div class="chart-container">
                                        <canvas id="pieChart" height="250"></canvas>
                                    </div>
                                </div>
                                <!-- Bar Chart -->
                                <div class="col-md-6">
                                    <div class="chart-container">
                                        <canvas id="barChart" height="250"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @foreach($groupedByYear as $year => $records)
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">{{ $year }} - Your Records</h4>
                            <button class="btn btn-sm btn-light toggle-summary" data-year="{{ $year }}">
                                <i class="fas fa-chart-pie"></i> Show Summary
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped detailed-table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Kebele</th>
                                            <th>Crop Type</th>
                                            <th>Crop</th>
                                            <th>Land (ha)</th>
                                            <th>Productivity (Qt/ha)</th>
                                            <th>Production (Qt)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $prevKebele = null;
                                            $prevCropType = null;
                                        @endphp
                                        @foreach($records as $record)
                                        <tr>
                                            <td>
                                                @if($record->kebele->name != $prevKebele)
                                                    {{ $record->kebele->name }}
                                                    @php $prevKebele = $record->kebele->name @endphp
                                                @endif
                                            </td>
                                            <td>
                                                @if($record->cropType->name != $prevCropType)
                                                    {{ $record->cropType->name }}
                                                    @php $prevCropType = $record->cropType->name @endphp
                                                @endif
                                            </td>
                                            <td>{{ $record->crop->name }}</td>
                                            <td>{{ number_format($record->land_hectares, 2) }}</td>
                                            <td>{{ number_format($record->productivity, 2) }}</td>
                                            <td>{{ number_format($record->production, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="alert alert-info">
                        You have no records for Ganna season.
                    </div>
                @endif
            </div>

            <!-- Summary Column (25% width) - Fixed position -->
            <div class="col-md-3">
                <div class="sticky-top" style="top: 20px;">
                    <div class="card summary-card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Your Yearly Summaries</h5>
                        </div>
                        <div class="card-body p-2">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Year</th>
                                            <th>Land (ha)</th>
                                            <th>Prod. (Qt)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($userYearlySummaries as $year => $summary)
                                        <tr class="summary-row" data-year="{{ $year }}">
                                            <td>{{ $year }}</td>
                                            <td>{{ number_format($summary['total_land'], 1) }}</td>
                                            <td>{{ number_format($summary['total_production'], 1) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Year-Specific Summary Panel -->
                    <div id="yearSummaryPanel" class="card mt-3">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0" id="yearSummaryTitle">Click a year to see details</h5>
                        </div>
                        <div class="card-body p-2">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Crop</th>
                                            <th>Land (ha)</th>
                                            <th>Prod. (Qt)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="yearSummaryContent">
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Select a year</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTables if tables exist
            if ($('.detailed-table').length) {
                $('.detailed-table').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    responsive: true,
                    paging: false,
                    searching: false,
                    info: false
                });
            }

            // Prepare the summary data
            const summaryData = @json($userCropSummariesByYear);
            const yearlyData = @json($userYearlySummaries);
            const allCropData = @json($allUserCropData);

            // Initialize Charts
            initializeCharts(allCropData, yearlyData);

            // Handle summary row click
            $('.summary-row').click(function() {
                const year = $(this).data('year');
                showYearSummary(year);
            });

            // Handle toggle summary button clicks
            $('.toggle-summary').click(function() {
                const year = $(this).data('year');
                showYearSummary(year);
            });

            function showYearSummary(year) {
                if (!summaryData[year] || Object.keys(summaryData[year]).length === 0) {
                    $('#yearSummaryContent').html(`
                        <tr>
                            <td colspan="3" class="text-center text-muted">No data available for ${year}</td>
                        </tr>
                    `);
                    return;
                }
                
                // Update title
                $('#yearSummaryTitle').text(`${year} - Your Crop Summary`);
                
                // Clear and rebuild content
                const $content = $('#yearSummaryContent');
                $content.empty();
                
                // Add each crop's summary
                $.each(summaryData[year], function(cropName, data) {
                    $content.append(`
                        <tr>
                            <td>${cropName}</td>
                            <td>${data.total_land.toFixed(1)}</td>
                            <td>${data.total_production.toFixed(1)}</td>
                        </tr>
                    `);
                });
                
                // Highlight the active row
                $('.summary-row').removeClass('table-active');
                $(`.summary-row[data-year="${year}"]`).addClass('table-active');
            }

            function initializeCharts(cropData, yearlyData) {
                // Prepare data for Pie Chart (Land Distribution by Crop)
                const pieLabels = Object.keys(cropData);
                const pieLandData = pieLabels.map(crop => cropData[crop].total_land);
                const pieProductionData = pieLabels.map(crop => cropData[crop].total_production);

                // Prepare data for Bar Chart (Yearly Production)
                const barYears = Object.keys(yearlyData).sort();
                const barLandData = barYears.map(year => yearlyData[year].total_land);
                const barProductionData = barYears.map(year => yearlyData[year].total_production);

                // Pie Chart - Land Distribution by Crop
                const pieCtx = document.getElementById('pieChart').getContext('2d');
                new Chart(pieCtx, {
                    type: 'pie',
                    data: {
                        labels: pieLabels,
                        datasets: [{
                            data: pieLandData,
                            backgroundColor: [
                                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
                                '#9966FF', '#FF9F40', '#8AC24A', '#607D8B'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Land Distribution by Crop (ha)',
                                font: { size: 14 }
                            },
                            legend: {
                                position: 'right'
                            }
                        }
                    }
                });

                // Bar Chart - Yearly Production Comparison
                const barCtx = document.getElementById('barChart').getContext('2d');
                new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: barYears,
                        datasets: [
                            {
                                label: 'Land (ha)',
                                data: barLandData,
                                backgroundColor: '#36A2EB',
                                borderColor: '#36A2EB',
                                borderWidth: 1
                            },
                            {
                                label: 'Production (Qt)',
                                data: barProductionData,
                                backgroundColor: '#FFCE56',
                                borderColor: '#FFCE56',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Yearly Production Comparison',
                                font: { size: 14 }
                            },
                            legend: {
                                position: 'top'
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endsection

    @section('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    
    <style>
        .summary-card {
            max-height: 400px;
            overflow-y: auto;
        }
        .summary-row {
            cursor: pointer;
            transition: all 0.2s;
        }
        .summary-row:hover {
            background-color: #f8f9fa;
            transform: translateX(2px);
        }
        .table-active {
            background-color: rgba(0, 123, 255, 0.1);
        }
        .sticky-top {
            position: -webkit-sticky;
            position: sticky;
            z-index: 1000;
        }
        .detailed-table {
            font-size: 0.9rem;
        }
        #yearSummaryPanel {
            transition: all 0.3s ease;
        }
        .detailed-table td {
            vertical-align: middle;
        }
        .card-header {
            padding: 0.75rem 1.25rem;
        }
        .chart-container {
            position: relative;
            margin: auto;
            width: 100%;
        }
    </style>
    @endsection
</x-layout>