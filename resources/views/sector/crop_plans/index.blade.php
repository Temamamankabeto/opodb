<x-layout>
    <div class="container-fluid py-4">
        <h2 class="mb-4">
            <i class="mdi mdi-sprout text-success"></i> Kebele Crop Plans Summary
        </h2>

        <!-- Flash Message -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle-outline"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Add Button -->
        <div class="mb-3">
            <a href="{{ route('sector.crop-plans.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus-circle-outline"></i> Add New Crop Plan
            </a>
        </div>

        @if($cropPlans->isEmpty())
        <div class="alert alert-info">
            <i class="mdi mdi-information-outline"></i> No crop plans found.
        </div>
        @else
        @php
        $lastYear = $lastType = $lastCrop = null;
        $yearTotalArea = $yearTotalYield = 0;
        $typeTotalArea = $typeTotalYield = 0;
        $cropTotalArea = $cropTotalYield = 0;
        $grandTotalArea = $grandTotalYield = 0;
        $yearlyData = []; // Store summary data per year
        @endphp

        <div class="table-responsive shadow-sm rounded-4" style="max-height: 600px; overflow:auto;">
            <table class="table table-bordered align-middle table-hover">
                <thead class="table-success position-sticky top-0" style="z-index:2;">
                    <tr>
                        <th class="position-sticky start-0 bg-success">Year</th>
                        <th class="position-sticky start-0 bg-success">Crop Type</th>
                        <th class="position-sticky start-0 bg-success">Crop</th>
                        <th>Season</th>
                        <th>Area (ha)</th>
                        <th>Productivity</th>
                        <th>Expected Yield (qt)</th>
                        <th>Input Needed</th>
                        <th>target farmers</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cropPlans->sortBy(['year','croptype_id','crop_id','season']) as $plan)

                    @php
                    // Prepare yearly summary
                    $yearlyData[$plan->year][] = [
                    'crop' => optional($plan->crop)->name ?? 'N/A',
                    'area' => $plan->area_to_plant,
                    'yield' => $plan->expected_yield,
                    'fertilizer' => $plan->input_needed,
                    ];
                    @endphp

                    <tr>
                        <td>
                            @if($plan->year !== $lastYear)
                            <a href="#" class="year-summary-link text-decoration-underline fw-bold"
                                data-year="{{ $plan->year }}">
                                {{ $plan->year }}
                            </a>
                            @php $lastYear = $plan->year; @endphp
                            @endif
                        </td>
                        <td>@if($plan->croptype_id !== $lastType) {{ optional($plan->cropType)->name ?? 'N/A' }} @php $lastType = $plan->croptype_id; @endphp @endif</td>
                        <td>@if($plan->crop_id !== $lastCrop) {{ optional($plan->crop)->name ?? 'N/A' }} @php $lastCrop = $plan->crop_id; @endphp @endif</td>
                        <td>{{ $plan->season }}</td>
                        <td>{{ number_format($plan->area_to_plant, 2) }}</td>
                        <td>{{ $plan->productivity ?? '-' }}</td>
                        <td>{{ number_format($plan->expected_yield, 2) }}</td>
                        <td>
    @if($plan->input_needed)
        @php
            $inputs = json_decode($plan->input_needed, true);
        @endphp

        @if(is_array($inputs))
            <ul class="list-unstyled mb-0">
                @foreach($inputs as $name => $amount)
                    <li><strong>{{ ucfirst($name) }}</strong> = {{ $amount }}</li>
                @endforeach
            </ul>
        @else
            {{ $plan->input_needed }}
        @endif
    @else
        -
    @endif
</td>

                        <td>{{ $plan->responsible_staff ?? '-' }}</td>
                        <td>{{ $plan->notes ?? '-' }}</td>
                        <td>
                            <a href="{{ route('sector.crop-plans.edit', $plan->id) }}" class="btn btn-warning btn-sm">
                                <i class="mdi mdi-pencil-outline"></i>
                            </a>
                            <form action="{{ route('sector.crop-plans.destroy', $plan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    <i class="mdi mdi-delete-outline"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    @php
                    $cropTotalArea += $plan->area_to_plant;
                    $cropTotalYield += $plan->expected_yield;
                    $typeTotalArea += $plan->area_to_plant;
                    $typeTotalYield += $plan->expected_yield;
                    $yearTotalArea += $plan->area_to_plant;
                    $yearTotalYield += $plan->expected_yield;
                    $grandTotalArea += $plan->area_to_plant;
                    $grandTotalYield += $plan->expected_yield;
                    @endphp
                    @endforeach
                </tbody>
                <tfoot class="table-dark text-white fw-bold">
                    <tr>
                        <td colspan="11">
                            🌍 Grand Total — Area: {{ number_format($grandTotalArea,2) }} ha |
                            Yield: {{ number_format($grandTotalYield,2) }} qt
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Summary Modal -->
        <div class="modal fade" id="yearSummaryModal" tabindex="-1" aria-labelledby="yearSummaryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="yearSummaryModalLabel">Yearly Crop Summary</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="yearSummaryContent">
                        <!-- Content populated by JS -->
                    </div>
                </div>
            </div>
        </div>

        <script>
            const yearlyData = @json($yearlyData);

            document.querySelectorAll('.year-summary-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const year = this.dataset.year;
                    const data = yearlyData[year];
                    let html = `<h5>📅 Year: ${year}</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Crop</th>
                                            <th>Total Area (ha)</th>
                                            <th>Expected Yield (qt)</th>
                                            <th>Fertilizer / Input</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                    let totalArea = 0;
                    let totalYield = 0;
                    data.forEach(d => {
                        html += `<tr>
                                    <td>${d.crop}</td>
                                    <td>${d.area.toFixed(2)}</td>
                                    <td>${d.yield.toFixed(2)}</td>
                                    <td>${d.fertilizer}</td>
                                </tr>`;
                        totalArea += d.area;
                        totalYield += d.yield;
                    });
                    html += `<tr class="fw-bold bg-light">
                                <td>Total</td>
                                <td>${totalArea.toFixed(2)}</td>
                                <td>${totalYield.toFixed(2)}</td>
                                <td>-</td>
                            </tr>`;
                    html += '</tbody></table>';
                    document.getElementById('yearSummaryContent').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('yearSummaryModal')).show();
                });
            });
        </script>
        @endif
    </div>
</x-layout>