<x-layout>
    <div class="container-fluid py-4 crops-container">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="page-header">
                <h2 class="mb-0"><i class="mdi mdi-sprout text-success me-2"></i> Zone Crop Plans Report</h2>
                <p class="text-muted mb-0 mt-1">Aggregated crop planning across all woredas in your zone</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-outline-success btn-sm" onclick="exportToExcel()">
                    <i class="mdi mdi-download me-1"></i> Export
                </button>
            </div>
        </div>

        @if (empty($groupedData))
            <div class="empty-state text-center py-5">
                <i class="mdi mdi-information-outline display-4 text-muted mb-3"></i>
                <h4 class="text-muted">No crop plans found</h4>
                <p class="text-muted mb-4">There are no crop plans available for this zone.</p>
            </div>
        @else
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-dark">
                        <i class="mdi mdi-table me-2 text-primary"></i>
                        Zone Crop Plans Summary
                    </h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table table-bordered table-hover align-middle mb-0" id="cropsTable">
                            <thead class="table-light sticky-header">
                            <tr>
                                <th class="text-center">Year</th>
                                <th class="text-center">Season</th>
                                <th class="text-center">Crop Type</th>
                                <th class="text-center">Woredas</th>
                                <th class="text-center">Crop</th>
                                <th class="text-center">Area (ha)</th>
                                <th class="text-center">Productivity</th>
                                <th class="text-center">Expected Yield (qt)</th>
                                <th class="text-center">Prev Year</th>
                                <th class="text-center">Change</th>
                                <th class="text-center">Trend</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($groupedData as $yearSeasonKey => $yearSeasonData)
                                @php
                                    $crops = $yearSeasonData['crops'];
                                    $rowCount = count($crops);
                                @endphp

                                @foreach ($crops as $index => $cropData)
                                    <tr class="data-row">

                                        @if ($index == 0)
                                            <td class="text-center fw-bold year-cell" rowspan="{{ $rowCount }}">
                                                {{ $yearSeasonData['year'] }}
                                                @if ($yearSeasonData['year'] == $currentYear)
                                                    <br><span class="badge bg-success small">Current</span>
                                                @endif
                                            </td>
                                            <td class="text-center season-cell" rowspan="{{ $rowCount }}">
                                                {{ $yearSeasonData['season'] }}
                                            </td>
                                            <td class="fw-bold crop-type-cell" rowspan="{{ $rowCount }}">
                                                {{ $cropData['crop_type'] }}
                                            </td>
                                            <td class="woredas-cell" rowspan="{{ $rowCount }}">
                                                <div class="woreda-names fw-bold text-primary">
                                                    {{ implode(', ', $cropData['woreda_names'] ?? []) }}
                                                </div>
                                                <div class="small text-muted">
                                                    ({{ $cropData['woreda_count'] ?? 0 }} woredas)
                                                </div>
                                            </td>
                                        @endif

                                        <td class="crop-cell">
                                            <i class="mdi mdi-sprout text-success me-1"></i>
                                            {{ $cropData['crop_name'] }}
                                        </td>

                                        <td class="text-end area-cell fw-bold text-primary">
                                            {{ number_format($cropData['total_area'], 2) }}
                                        </td>

                                        <td class="text-end productivity-cell">
                                            <span class="fw-bold text-info">
                                                {{ number_format($cropData['productivity'], 2) }}
                                            </span>
                                            <div class="small text-muted">qt/ha</div>
                                        </td>

                                        <td class="text-end yield-cell fw-bold text-success">
                                            {{ number_format($cropData['total_yield'], 2) }}
                                        </td>

                                        <td class="text-center prev-year-cell">
                                            @if($cropData['prev_yield'] > 0)
                                                <div class="small">
                                                    <div>{{ number_format($cropData['prev_yield'], 0) }} qt</div>
                                                    <div class="text-muted">
                                                        {{ number_format($cropData['prev_prod'], 1) }} qt/ha
                                                    </div>
                                                </div>
                                            @else
                                                <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>

                                        <td class="text-center change-cell">
                                            @if($cropData['prev_yield'] > 0)
                                                @php
                                                    $changeClass = $cropData['yield_change'] >= 0 ? 'text-success' : 'text-danger';
                                                    $changeIcon  = $cropData['yield_change'] >= 0 ? 'mdi-arrow-up' : 'mdi-arrow-down';
                                                @endphp

                                                <div class="{{ $changeClass }} fw-bold">
                                                    <i class="mdi {{ $changeIcon }}"></i>
                                                    {{ number_format(abs($cropData['yield_change']), 1) }}%
                                                </div>

                                                <div class="small text-muted">
                                                    {{ number_format($cropData['productivity_change'], 1) }}% prod
                                                </div>
                                            @else
                                                <span class="badge bg-info">New</span>
                                            @endif
                                        </td>

                                        <td class="text-center trend-cell">
                                            @switch($cropData['trend'])
                                                @case('strong_growth')
                                                    <i class="mdi mdi-trending-up fs-5 text-success"></i>
                                                    @break
                                                @case('growth')
                                                    <i class="mdi mdi-arrow-up fs-6 text-success"></i>
                                                    @break
                                                @case('stable')
                                                    <i class="mdi mdi-minus fs-6 text-warning"></i>
                                                    @break
                                                @case('slight_decline')
                                                    <i class="mdi mdi-arrow-down fs-6 text-warning"></i>
                                                    @break
                                                @case('decline')
                                                    <i class="mdi mdi-trending-down fs-5 text-danger"></i>
                                                    @break
                                                @default
                                                    <i class="mdi mdi-help fs-6 text-secondary"></i>
                                            @endswitch
                                        </td>

                                        <td class="text-center">
                                            @php
                                                $countFeedback = \App\Models\KebeleCropPlan::where('id', $cropData['id'])
                                                    ->whereNotNull('feedback')
                                                    ->count();
                                            @endphp

                                            <button class="btn btn-sm btn-info position-relative"
                                                    onclick="openFeedbackModal(
                                                        '{{ $cropData['id'] }}',
                                                        '{{ implode(', ', $cropData['woreda_names'] ?? []) }}',
                                                        '{{ $cropData['crop_name'] }}',
                                                        '{{ $cropData['crop_type'] }}',
                                                        '{{ $yearSeasonData['season'] }}',
                                                        '{{ $yearSeasonData['year'] }}',
                                                        '{{ $cropData['total_area'] }}',
                                                        '{{ $cropData['productivity'] }}'
                                                    )">
                                                <i class="fa fa-comment"></i>
                                                @if($countFeedback > 0)
                                                    <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">
                                                        {{ $countFeedback }}
                                                    </span>
                                                @endif
                                            </button>
                                        </td>

                                    </tr>
                                @endforeach

                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

            <!-- Grand Totals -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card border-0 bg-primary bg-opacity-10">
                        <div class="card-body text-center">
                            <h5 class="text-primary mb-1">Total Area</h5>
                            <h3 class="text-primary">{{ number_format($grandTotalArea, 2) }} ha</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 bg-success bg-opacity-10">
                        <div class="card-body text-center">
                            <h5 class="text-success mb-1">Total Expected Yield</h5>
                            <h3 class="text-success">{{ number_format($grandTotalYield, 2) }} qt</h3>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Add these scripts if needed -->
    <script>
        function exportToExcel() {
            // Your export logic here
            alert('Export functionality would go here');
        }

        function openFeedbackModal(id, woredas, cropName, cropType, season, year, area, productivity) {
            // Your feedback modal logic here
            alert(`Feedback for: ${cropName} (${cropType}) in ${woredas} - ${season} ${year}`);
        }
    </script>
</x-layout>
