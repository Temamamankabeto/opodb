<x-layout>
    <div class="container-fluid py-4">
        <h2 class="mb-4"><i class="mdi mdi-sprout text-success"></i> Kebele Crop Plans Summary</h2>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="mdi mdi-check-circle-outline"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('sector.crop-plans.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus-circle-outline"></i> Add New Crop Plan
            </a>

            <form action="{{ route('sector.crop-plans.import') }}" method="POST" enctype="multipart/form-data"
                class="mb-4">
                @csrf
                <div class="input-group">
                    <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                    <button class="btn btn-success" type="submit"><i class="mdi mdi-file-import"></i> Import
                        Excel</button>
                </div>
            </form>

            <a href="{{ route('sector.crop-plans.download-template') }}" class="btn btn-outline-primary mb-3">
                <i class="mdi mdi-download"></i> Download Excel Template
            </a>

        </div>

        @if ($cropPlans->isEmpty())
            <div class="alert alert-info"><i class="mdi mdi-information-outline"></i> No crop plans found.</div>
        @else
            @php
                $grandTotalArea = $grandTotalYield = 0;
                $lastYear = $lastType = $lastCrop = null;
                $yearTotalArea = $yearTotalYield = 0;
                $typeTotalArea = $typeTotalYield = 0;
                $cropTotalArea = $cropTotalYield = 0;
            @endphp

            <div class="table-responsive shadow-sm rounded-4" style="max-height:600px; overflow:auto;">
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cropPlans->sortBy('year')->sortBy('croptype_id')->sortBy('crop_id')->sortBy('season') as $plan)
                            @php
                                // Reset subtotals on change
                                if ($lastYear !== null && $plan->year !== $lastYear) {
                                    echo "<tr class='table-secondary fw-bold'>
                                        <td colspan='4'>Year Subtotal ({$lastYear})</td>
                                        <td>" .
                                        number_format($yearTotalArea, 2) .
                                        "</td>
                                        <td>-</td>
                                        <td>" .
                                        number_format($yearTotalYield, 2) .
                                        "</td>
                                        <td>-</td>
                                      </tr>";
                                    $yearTotalArea = $yearTotalYield = 0;
                                    $lastType = $lastCrop = null;
                                }
                                if ($lastType !== null && $plan->croptype_id !== $lastType) {
                                    echo "<tr class='table-light fw-bold'>
                                        <td colspan='4'>Crop Type Subtotal ({$plan->cropType->name})</td>
                                        <td>" .
                                        number_format($typeTotalArea, 2) .
                                        "</td>
                                        <td>-</td>
                                        <td>" .
                                        number_format($typeTotalYield, 2) .
                                        "</td>
                                        <td>-</td>
                                      </tr>";
                                    $typeTotalArea = $typeTotalYield = 0;
                                    $lastCrop = null;
                                }
                                if ($lastCrop !== null && $plan->crop_id !== $lastCrop) {
                                    echo "<tr class='table-warning fw-bold'>
                                        <td colspan='4'>Crop Subtotal ({$plan->crop->name})</td>
                                        <td>" .
                                        number_format($cropTotalArea, 2) .
                                        "</td>
                                        <td>-</td>
                                        <td>" .
                                        number_format($cropTotalYield, 2) .
                                        "</td>
                                        <td>-</td>
                                      </tr>";
                                    $cropTotalArea = $cropTotalYield = 0;
                                }

                                $grandTotalArea += $plan->area_to_plant;
                                $grandTotalYield += $plan->expected_yield;

                                $yearTotalArea += $plan->area_to_plant;
                                $yearTotalYield += $plan->expected_yield;

                                $typeTotalArea += $plan->area_to_plant;
                                $typeTotalYield += $plan->expected_yield;

                                $cropTotalArea += $plan->area_to_plant;
                                $cropTotalYield += $plan->expected_yield;
                            @endphp

                            <tr>
                                <td>{{ $plan->year !== $lastYear ? $plan->year : '' }}</td>
                                <td>{{ $plan->croptype_id !== $lastType ? $plan->cropType->name : '' }}</td>
                                <td>{{ $plan->crop_id !== $lastCrop ? $plan->crop->name : '' }}</td>
                                <td>{{ $plan->season }}</td>
                                <td>{{ number_format($plan->area_to_plant, 2) }}</td>
                                <td>{{ $plan->productivity ?? '-' }}</td>
                                <td>{{ number_format($plan->expected_yield, 2) }}</td>
                                <td>
                                    <a href="{{ route('sector.crop-plans.edit', $plan->id) }}"
                                        class="btn btn-warning btn-sm"><i class="mdi mdi-pencil-outline"></i></a>
                                    <form action="{{ route('sector.crop-plans.destroy', $plan->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm"><i
                                                class="mdi mdi-delete-outline"></i></button>
                                    </form>
                                </td>
                            </tr>

                            @php
                                $lastYear = $plan->year;
                                $lastType = $plan->croptype_id;
                                $lastCrop = $plan->crop_id;
                            @endphp
                        @endforeach

                        {{-- Final Subtotals --}}
                        <tr class="table-warning fw-bold">
                            <td colspan="4">Crop Subtotal ({{ optional($plan->crop)->name ?? '' }})</td>
                            <td>{{ number_format($cropTotalArea, 2) }}</td>
                            <td>-</td>
                            <td>{{ number_format($cropTotalYield, 2) }}</td>
                            <td>-</td>
                        </tr>
                        <tr class="table-light fw-bold">
                            <td colspan="4">Crop Type Subtotal ({{ optional($plan->cropType)->name ?? '' }})</td>
                            <td>{{ number_format($typeTotalArea, 2) }}</td>
                            <td>-</td>
                            <td>{{ number_format($typeTotalYield, 2) }}</td>
                            <td>-</td>
                        </tr>
                        <tr class="table-secondary fw-bold">
                            <td colspan="4">Year Subtotal ({{ $lastYear }})</td>
                            <td>{{ number_format($yearTotalArea, 2) }}</td>
                            <td>-</td>
                            <td>{{ number_format($yearTotalYield, 2) }}</td>
                            <td>-</td>
                        </tr>
                    </tbody>
                    <tfoot class="table-dark text-white fw-bold">
                        <tr>
                            <td colspan="8">🌍 Grand Total — Area: {{ number_format($grandTotalArea, 2) }} ha |
                                Yield: {{ number_format($grandTotalYield, 2) }} qt</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
</x-layout>
