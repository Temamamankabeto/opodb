<x-layout>
    <div class="container-fluid py-4 fertilizers-container">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="page-header">
                <h2 class="mb-0"><i class="mdi mdi-chemical-weapon text-warning me-2"></i> Fertilizer Management</h2>
                <p class="text-muted mb-0 mt-1">Grouped fertilizer summary by Year, Season & Crop Type</p>
            </div>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
                <div class="d-flex align-items-center">
                    <i class="mdi mdi-check-circle-outline me-2 fs-5"></i>
                    <span class="flex-grow-1">{{ session('success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if (empty($groupedData))
            <!-- Empty State -->
            <div class="empty-state text-center py-5">
                <i class="mdi mdi-information-outline display-4 text-muted mb-3"></i>
                <h4 class="text-muted">No crop plans found</h4>
                <p class="text-muted mb-4">There are no crop plans available for fertilizer management.</p>
            </div>
        @else
           

            <!-- Grouped Fertilizer Summary -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark">
                        <i class="mdi mdi-table me-2 text-primary"></i>
                        Fertilizer Summary
                    </h5>
                    <div class="d-flex gap-2">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="mdi mdi-magnify text-muted"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control border-start-0"
                                placeholder="Search year, season, or crop type...">
                        </div>
                        <button class="btn btn-outline-primary btn-sm" onclick="exportToExcel()">
                            <i class="mdi mdi-download me-1"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table table-hover align-middle mb-0" id="fertilizerTable">
                            <thead class="table-light sticky-header">
                                <tr>
                                    <th class="ps-4" style="width: 12%">Year</th>
                                    <th style="width: 10%">Season</th>
                                    <th style="width: 15%">Crop Type</th>
                                    <th style="width: 18%">Crop Details</th>
                                    <th style="width: 25%">Fertilizer Details</th>

                                    <th class="text-center" style="width: 5%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Calculate proper rowspans
                                    $yearRowspans = [];
                                    $seasonRowspans = [];
                                    $cropTypeRowspans = [];

                                    foreach ($groupedData as $yearSeasonKey => $yearSeasonData) {
                                        $year = $yearSeasonData['year'];
                                        $season = $yearSeasonData['season'];

                                        if (!isset($yearRowspans[$year])) {
                                            $yearRowspans[$year] = 0;
                                        }
                                        if (!isset($seasonRowspans[$yearSeasonKey])) {
                                            $seasonRowspans[$yearSeasonKey] = 0;
                                        }

                                        foreach ($yearSeasonData['crop_types'] as $cropType => $typeData) {
                                            $cropCount = count($typeData['crops']);
                                            $yearRowspans[$year] += $cropCount;
                                            $seasonRowspans[$yearSeasonKey] += $cropCount;
                                            $cropTypeRowspans[$yearSeasonKey . '_' . $cropType] = $cropCount;
                                        }
                                    }

                                    $renderedYears = [];
                                    $renderedSeasons = [];
                                    $renderedCropTypes = [];
                                @endphp

                                @foreach ($groupedData as $yearSeasonKey => $yearSeasonData)
                                    @foreach ($yearSeasonData['crop_types'] as $cropType => $typeData)
                                        @foreach ($typeData['crops'] as $cropKey => $cropData)
                                            <tr class="data-row hover-row" data-year="{{ $yearSeasonData['year'] }}"
                                                data-season="{{ $yearSeasonData['season'] }}"
                                                data-type="{{ $cropType }}"
                                                data-crop="{{ $cropData['crop_name'] }}">

                                                <!-- Year Column -->
                                                @if (!in_array($yearSeasonData['year'], $renderedYears))
                                                    <td class="year-cell align-middle text-center fw-bold"
                                                        rowspan="{{ $yearRowspans[$yearSeasonData['year']] }}"
                                                        style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                                        <div class="year-content">
                                                            <div class="year-value fs-5 text-primary mb-1">
                                                                {{ $yearSeasonData['year'] }}
                                                            </div>
                                                            @if ($yearSeasonData['year'] == date('Y'))
                                                                <span
                                                                    class="badge bg-success bg-opacity-20 text-success border border-success">
                                                                    Current
                                                                </span>
                                                            @endif
                                                            <div class="year-totals small text-muted mt-2">
                                                                <div class="fw-semibold text-dark mb-1">Year Total:
                                                                </div>
                                                                <div>
                                                                    <i class="mdi mdi-earth me-1 text-success">Area</i>
                                                                    {{ number_format($yearSeasonData['year_total_area'], 2) }}
                                                                    ha
                                                                </div>
                                                                @foreach ($yearSeasonData['year_fertilizer_by_unit'] as $unit => $amount)
                                                                    <div>
                                                                        <i
                                                                            class="mdi mdi-chemical-weapon me-1 text-warning">fertilizer</i>
                                                                        {{ number_format($amount, 2) }}
                                                                        {{ $unit }}
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                             
                                                        </div>
                                                    </td>
                                                    @php $renderedYears[] = $yearSeasonData['year']; @endphp
                                                @endif

                                                <!-- Season Column -->
                                                @if (!in_array($yearSeasonKey, $renderedSeasons))
                                                    <td class="season-cell align-middle text-center"
                                                        rowspan="{{ $seasonRowspans[$yearSeasonKey] }}"
                                                        style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);">
                                                        <span class="season-badge fw-semibold text-dark">
                                                            <i class="mdi mdi-weather-sunny me-1 text-warning"></i>
                                                            {{ $yearSeasonData['season'] }}
                                                        </span>
                                                    </td>
                                                    @php $renderedSeasons[] = $yearSeasonKey; @endphp
                                                @endif

                                                <!-- Crop Type Column -->
                                                <!-- Crop Type Column -->
                                                @if (!in_array($yearSeasonKey . '_' . $cropType, $renderedCropTypes))
                                                    <td class="crop-type-cell align-middle"
                                                        rowspan="{{ $cropTypeRowspans[$yearSeasonKey . '_' . $cropType] }}"
                                                        style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);">
                                                        <div class="crop-type-content text-center">
                                                            <div class="crop-type-icon mb-2">
                                                                <i class="mdi mdi-seed-outline fs-4 text-success"></i>
                                                            </div>
                                                            <div class="crop-type-name fw-bold text-dark mb-1">
                                                                {{ $cropType }}
                                                            </div>
                                                            <div class="crop-type-totals small text-muted">
                                                                <div class="mb-1">
                                                                    <i class="mdi mdi-earth me-1"></i>
                                                                    {{ number_format($typeData['total_area'], 2) }} ha
                                                                </div>
                                                                @foreach ($typeData['fertilizer_by_unit'] as $unit => $amount)
                                                                    <div>
                                                                        <i class="mdi mdi-chemical-weapon me-1"></i>
                                                                        {{ number_format($amount, 2) }}
                                                                        {{ $unit }}
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @php $renderedCropTypes[] = $yearSeasonKey . '_' . $cropType; @endphp
                                                @endif

                                                <!-- Crop Details Column -->
                                                <td class="crop-details-cell">
                                                    <div class="crop-details">
                                                        <div
                                                            class="d-flex justify-content-between align-items-start mb-2">
                                                            <div class="crop-info">
                                                                <div class="crop-name fw-semibold text-dark">
                                                                    <i class="mdi mdi-sprout text-success me-1"></i>
                                                                    {{ $cropData['crop_name'] }}
                                                                </div>

                                                            </div>
                                                            <div class="crop-area">
                                                                <span class="badge bg-primary rounded-pill px-3 py-2">
                                                                    {{ number_format($cropData['area'], 2) }} ha
                                                                </span>
                                                            </div>
                                                        </div>


                                                    </div>
                                                </td>

                                                <!-- Fertilizer Details Column -->
                                                <td class="fertilizer-details-cell">
                                                    @if (!empty($cropData['fertilizers']))
                                                        <div class="fertilizer-breakdown">
                                                            @foreach ($cropData['fertilizers'] as $fertKey => $fertData)
                                                                <div class="fert-item mb-2 p-2 rounded border">

                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center">
                                                                        <span class="fert-product small text-muted">
                                                                            {{ $fertData['fertilizer_name'] }}
                                                                        </span>
                                                                        <span class="fert-total fw-bold text-primary">
                                                                            {{ number_format($fertData['amount'], 2) }}
                                                                            {{ $fertData['unit'] }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="text-center py-3">
                                                            <i
                                                                class="mdi mdi-chemical-weapon-off text-muted display-6 mb-2"></i>
                                                            <div class="text-muted small">No fertilizer requirements
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>



                                                <!-- Actions Column -->
                                                <td class="actions-cell text-center align-middle">
                                                    <div class="action-buttons">
                                                        <a href="{{ route('sector.crop-plans.editfertilizer', $cropData['plan_id']) }}"
                                                            class="btn btn-warning btn-sm action-btn mb-1"
                                                            title="Update Fertilizer"
                                                            data-plan-id="{{ $cropData['plan_id'] }}"
                                                            style="min-width: 80px;">
                                                            <i class="mdi mdi-pencil"></i>
                                                        </a>

                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const value = this.value.toLowerCase();
                    const rows = document.querySelectorAll('#fertilizerTable tbody tr.data-row');

                    rows.forEach(function(row) {
                        const year = row.getAttribute('data-year').toLowerCase();
                        const season = row.getAttribute('data-season').toLowerCase();
                        const type = row.getAttribute('data-type').toLowerCase();
                        const crop = row.getAttribute('data-crop').toLowerCase();

                        const show = year.includes(value) || season.includes(value) || type
                            .includes(value) || crop.includes(value);
                        row.style.display = show ? '' : 'none';
                    });
                });
            }

            // Add loading state to action buttons
            document.querySelectorAll('.action-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    const originalHtml = this.innerHTML;
                    this.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i>';
                    this.disabled = true;

                    setTimeout(() => {
                        this.innerHTML = originalHtml;
                        this.disabled = false;
                    }, 1500);
                });
            });

            // Hover effects
            document.querySelectorAll('.hover-row').forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.background = 'linear-gradient(135deg, #f0f8ff 0%, #e7f3ff 100%)';
                    this.style.borderLeft = '3px solid #007bff';
                });

                row.addEventListener('mouseleave', function() {
                    this.style.background = '';
                    this.style.borderLeft = '';
                });
            });
        });

        function exportToExcel() {
            try {
                const table = document.getElementById('fertilizerTable');
                const wb = XLSX.utils.table_to_book(table);
                XLSX.writeFile(wb, 'fertilizer-management-summary.xlsx');
            } catch (error) {
                console.error('Export error:', error);
                alert('Error exporting to Excel: ' + error.message);
            }
        }
    </script>

    <style>
        .fertilizers-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        .summary-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: none;
            border-radius: 12px;
        }

        .summary-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Table Styles */
        .table-container {
            position: relative;
            max-height: 70vh;
            overflow: auto;
            border: 1px solid #e9ecef;
            border-radius: 8px;
        }

        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 10;
            background: white;
        }

        .sticky-header th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #495057;
            vertical-align: middle;
        }

        /* Cell Styles */
        .year-cell,
        .season-cell,
        .crop-type-cell {
            border-right: 2px solid #dee2e6;
            vertical-align: middle !important;
        }

        .crop-details-cell,
        .fertilizer-details-cell,
        .crop-type-summary-cell {
            vertical-align: top;
        }

        .year-content,
        .crop-type-content {
            padding: 0.5rem;
        }

        .season-badge {
            font-size: 0.9rem;
        }

        .crop-type-icon {
            width: 40px;
            height: 40px;
            margin: 0 auto 0.5rem auto;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Table row styling */
        .table td {
            padding: 0.75rem;
            border-bottom: 1px solid #e9ecef;
        }

        .crop-details,
        .fertilizer-breakdown,
        .type-summary {
            padding: 0.5rem;
        }

        .fert-item {
            background: #f8f9fa;
            border: 1px solid #e9ecef !important;
        }

        .type-total {
            background: linear-gradient(135deg, #fff9e6 0%, #fff3cd 100%);
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .action-btn {
            font-size: 0.75rem;
            padding: 6px;
            border-radius: 6px;
            transition: all 0.2s ease;
            width: 100%;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        /* Scrollbar */
        .table-container::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .table-container {
                font-size: 0.8rem;
            }

            .action-btn {
                font-size: 0.7rem;
                padding: 4px;
            }

            .year-value {
                font-size: 1rem !important;
            }
        }

        /* Animations */
        .mdi-loading {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Ensure proper vertical alignment */
        .align-middle {
            vertical-align: middle !important;
        }
    </style>
</x-layout>
