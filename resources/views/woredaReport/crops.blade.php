<x-layout>
    <div class="container-fluid py-4 crops-container">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="page-header">
                <h2 class="mb-0"><i class="mdi mdi-sprout text-success me-2"></i> Woreda Crop Plans Report</h2>
                <p class="text-muted mb-0 mt-1">Aggregated crop planning across all kebeles</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-outline-success btn-sm" onclick="exportToExcel()">
                    <i class="mdi mdi-download me-1"></i> Export
                </button>
            </div>
        </div>

        @if (empty($groupedData))
            <!-- Empty State -->
            <div class="empty-state text-center py-5">
                <i class="mdi mdi-information-outline display-4 text-muted mb-3"></i>
                <h4 class="text-muted">No crop plans found</h4>
                <p class="text-muted mb-4">There are no crop plans available for the woreda.</p>
            </div>
        @else
            <!-- Main Aggregated Crop Plans Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-dark">
                        <i class="mdi mdi-table me-2 text-primary"></i>
                        Woreda Crop Plans Summary
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
                                <th class="text-center">Crop</th>
                                <th class="text-center">Kebeles</th>
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

                                        {{-- YEAR (Merged) --}}
                                        @if ($index == 0)
                                            <td class="text-center fw-bold year-cell" rowspan="{{ $rowCount }}">
                                                {{ $yearSeasonData['year'] }}
                                                @if ($yearSeasonData['year'] == $currentYear)
                                                    <br><span class="badge bg-success small">Current</span>
                                                @endif
                                            </td>
                                        @endif

                                        {{-- SEASON (Merged) --}}
                                        @if ($index == 0)
                                            <td class="text-center season-cell" rowspan="{{ $rowCount }}">
                                                {{ $yearSeasonData['season'] }}
                                            </td>
                                        @endif

                                        {{-- CROP TYPE (Merged) --}}
                                        @if ($index == 0)
                                            <td class="fw-bold crop-type-cell" rowspan="{{ $rowCount }}">
                                                {{ $cropData['crop_type'] }}
                                            </td>
                                        @endif

                                        {{-- CROP --}}
                                        <td class="crop-cell">
                                            <i class="mdi mdi-sprout text-success me-1"></i>
                                            {{ $cropData['crop_name'] }}
                                        </td>
                                        {{-- KEBELES MERGED LIKE OTHER COLUMNS --}}
                                        @if ($index == 0)
                                            <td class="kebeles-cell" rowspan="{{ $rowCount }}">
                                                <div class="kebele-names fw-bold text-success">
                                                    {{ $cropData['kebele_display'] }}
                                                </div>
                                                <div class="small text-muted">
                                                    ({{ $cropData['kebele_count'] }} kebeles)
                                                </div>
                                            </td>
                                        @endif

                                        {{-- KEBELES --}}


                                        {{-- AREA --}}
                                        <td class="text-end area-cell fw-bold text-primary">
                                            {{ number_format($cropData['total_area'], 2) }}
                                        </td>

                                        {{-- PRODUCTIVITY --}}
                                        <td class="text-end productivity-cell">
                                            <span
                                                class="fw-bold text-info">{{ number_format($cropData['productivity'], 2) }}</span>
                                            <div class="small text-muted">qt/ha</div>
                                        </td>

                                        {{-- EXPECTED YIELD --}}
                                        <td class="text-end yield-cell fw-bold text-success">
                                            {{ number_format($cropData['total_yield'], 2) }}
                                        </td>

                                        {{-- PREV YEAR --}}
                                        <td class="text-center prev-year-cell">
                                            @if($cropData['prev_year_yield'] > 0)
                                                <div class="small">
                                                    <div>{{ number_format($cropData['prev_year_yield'], 0) }} qt</div>
                                                    <div
                                                        class="text-muted">{{ number_format($cropData['prev_year_productivity'], 1) }}
                                                        qt/ha
                                                    </div>
                                                </div>
                                            @else
                                                <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>

                                        {{-- CHANGE --}}
                                        <td class="text-center change-cell">
                                            @if($cropData['prev_year_yield'] > 0)
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

                                        {{-- TREND --}}
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
                                        <td>
                                            @php
                                                $countFeedback = \App\Models\KebeleCropPlan::where('id', $cropData['id'])
                                                    ->whereNotNull('feedback')
                                                    ->count();                                            @endphp
                                            <button
                                                class="btn btn-sm btn-info position-relative"
                                                title="Add Feedback"
                                                onclick="openFeedbackModal(
                                                    '{{ $cropData['id'] }}',
                                                    '{{ $cropData['kebele_display'] }}',
                                                    '{{ $cropData['crop_name'] }}',
                                                    '{{ $cropData['crop_type'] }}',
                                                    '{{ $yearSeasonData['season'] }}',
                                                    '{{ $yearSeasonData['year'] }}',
                                                    '{{ $cropData['total_area'] }}',
                                                    '{{ $cropData['productivity'] }}'
                                                )">
                                                <i class="fa fa-comment"></i>
                                                @if($countFeedback > 0)
                                                    <span
                                                        class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">
                                                          {{ $countFeedback }}
                                                        </span>
                                                 @endif
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                <!-- FEEDBACK MODAL -->
                                <div class="modal fade" id="feedbackModal" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered modal-md">
                                        <div class="modal-content">

                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title"><i class="fa fa-comment me-2"></i> Add Feedback
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Kebele</label>
                                                    <input type="text" id="fb_kebele" class="form-control" readonly>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Crop</label>
                                                        <input type="text" id="fb_crop" class="form-control" readonly>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Crop Type</label>
                                                        <input type="text" id="fb_type" class="form-control" readonly>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Season</label>
                                                        <input type="text" id="fb_season" class="form-control" readonly>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Year</label>
                                                        <input type="text" id="fb_year" class="form-control" readonly>
                                                    </div>
                                                </div>

                                                <!-- NEW CORRECT FIELDS -->
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Area (ha)</label>
                                                        <input type="text" id="fb_area" class="form-control" readonly>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Productivity (qt/ha)</label>
                                                        <input type="text" id="fb_productivity" class="form-control"
                                                               readonly>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Feedback</label>
                                                    <textarea id="fb_text" class="form-control" rows="3"
                                                              placeholder="Type your feedback..."></textarea>
                                                </div>

                                            </div>

                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button class="btn btn-success" onclick="submitFeedback()">
                                                    <i class="fa fa-paper-plane"></i> Submit
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

            <!-- Grand Totals -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card border-0 bg-black bg-opacity-10">
                        <div class="card-body text-center">
                            <h5 class="text-danger mb-1">Total Area</h5>
                            <h3 class="text-primary">{{ number_format($grandTotalArea, 2) }} ha</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 bg-warning bg-opacity-10">
                        <div class="card-body text-center">
                            <h5 class="text-white mb-1">Total Expected Yield</h5>
                            <h3 class="text-dark">{{ number_format($grandTotalYield, 2) }} qt</h3>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <script>
        document.querySelectorAll('.openFeedbackModal').forEach(btn => {
            btn.addEventListener('click', function () {

                document.getElementById('fb_kebele').value = this.dataset.kebele;
                document.getElementById('fb_crop').value = this.dataset.crop;
                document.getElementById('fb_type').value = this.dataset.type;
                document.getElementById('fb_season').value = this.dataset.season;
                document.getElementById('fb_year').value = this.dataset.year;

                // New fields
                document.getElementById('fb_area').value = this.dataset.area;
                document.getElementById('fb_productivity').value = this.dataset.productivity;

                const modal = new bootstrap.Modal(document.getElementById('feedbackModal'));
                modal.show();
            });
        });

        function exportToExcel() {
            try {
                const table = document.getElementById('cropsTable');
                const wb = XLSX.utils.table_to_book(table, {sheet: "Woreda Crop Plans"});
                XLSX.writeFile(wb, 'woreda-crop-plans.xlsx');
            } catch (error) {
                console.error('Export error:', error);
                alert('Error exporting to Excel: ' + error.message);
            }
        }
    </script>


    <script>
        window.selectedId = null;

        function openFeedbackModal(id, kebele, crop, type, season, year, area, prod) {
            window.selectedId = id;

            document.getElementById("fb_kebele").value = kebele;
            document.getElementById("fb_crop").value = crop;
            document.getElementById("fb_type").value = type;
            document.getElementById("fb_season").value = season;
            document.getElementById("fb_year").value = year;
            document.getElementById("fb_area").value = area;
            document.getElementById("fb_productivity").value = prod;
            document.getElementById("fb_text").value = "";

            new bootstrap.Modal(document.getElementById("feedbackModal")).show();
        }

        function submitFeedback() {
            if (!window.selectedId) {
                alert("No plan selected!");
                return;
            }

            let feedback = document.getElementById("fb_text").value;
            if (!feedback) {
                alert("Please enter feedback");
                return;
            }

            fetch(`/woredas/crop-plans/${window.selectedId}/feedback`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    feedback: feedback,
                    feedbacked_by: "{{ auth()->user()->name }}",
                    feedback_at: new Date().toISOString()
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success") {
                        alert("Feedback saved!");
                        bootstrap.Modal.getInstance(document.getElementById('feedbackModal')).hide();
                    }
                })
                .catch(err => console.error(err));
        }
    </script>

    <style>
        .crops-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .table-container {
            position: relative;
            max-height: 70vh;
            overflow: auto;
        }

        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 10;
            background: white;
        }

        .data-row:hover {
            background-color: #f8f9fa;
        }

        .year-cell {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .season-cell {
            background-color: #e3f2fd;
        }

        .crop-type-cell {
            background-color: #e8f5e9;
        }

        .kebeles-cell {
            max-width: 250px;
        }

        .kebele-names {
            font-size: 0.875rem;
            line-height: 1.4;
        }

        @media (max-width: 768px) {
            .table-container {
                font-size: 0.8rem;
            }

            .kebeles-cell {
                max-width: 150px;
            }
        }
    </style>
</x-layout>
