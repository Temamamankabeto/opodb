<x-layout>
<section class="content">
<div class="container-fluid">

<div class="card card-success">

    <!-- ================= HEADER ================= -->
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-leaf"></i>
            Woreda Fertilizer Report ({{ $year }} vs {{ $prevYear }})
        </h3>
    </div>

    <!-- ================= FILTERS ================= -->
    <form method="GET">
        <div class="card-body">
            <div class="row">

                <!-- YEAR -->
                <div class="col-md-2">
                    <label>Year</label>
                    <select name="year" class="form-control" required>
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- SEASON -->
                <div class="col-md-2">
                    <label>Season</label>
                    <select name="season" class="form-control">
                        <option value="">All</option>
                        @foreach(['Ganna','Bona','Arfaasaa'] as $s)
                            <option value="{{ $s }}" {{ request('season') == $s ? 'selected' : '' }}>
                                {{ $s }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- KEBELE -->
                <div class="col-md-3">
                    <label>Kebele</label>
                    <select name="kebele_id" class="form-control">
                        <option value="">All Kebeles</option>
                        @foreach($kebeles as $id => $name)
                            <option value="{{ $id }}" {{ request('kebele_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- FERTILIZER TYPE -->
                <div class="col-md-2">
                    <label>Fertilizer Type</label>
                    <select name="fertilizer_type_id" class="form-control">
                        <option value="">All</option>
                        @foreach($fertilizerTypes as $id => $name)
                            <option value="{{ $id }}" {{ request('fertilizer_type_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- FERTILIZER -->
                <div class="col-md-2">
                    <label>Fertilizer</label>
                    <select name="fertilizer_id" class="form-control">
                        <option value="">All</option>
                        @foreach($fertilizerNames as $id => $name)
                            <option value="{{ $id }}" {{ request('fertilizer_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- BUTTON -->
                <div class="col-md-1 d-flex align-items-end">
                    <button class="btn btn-success btn-block">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>

            </div>
        </div>
    </form>

    <!-- ================= TABLE ================= -->
    <div class="card-body table-responsive">

        <table class="table table-bordered table-striped">
            <thead class="bg-success">
                <tr>
                    <th>Kebele</th>
                    <th>Season</th>
                    <th>Fertilizer</th>
                    <th class="text-right">{{ $year }}</th>
                    <th class="text-right">{{ $prevYear }}</th>
                    <th class="text-right">Difference</th>
                    <th>Unit</th>
                </tr>
            </thead>

            <tbody>
@php
    $grandTotals = [];
@endphp

@forelse($currentYearData as $kebeleId => $seasons)
    <!-- ================= KEBELE GROUP ================= -->
    <tr class="bg-light font-weight-bold">
        <td colspan="7">
            <i class="fas fa-map-marker-alt text-success"></i>
            {{ $kebeles[$kebeleId] ?? 'Unknown Kebele' }}
        </td>
    </tr>

    @foreach($seasons as $season => $fertilizers)
        <!-- ================= SEASON GROUP ================= -->
        <tr class="bg-secondary text-white">
            <td colspan="7" class="pl-4">
                <i class="fas fa-cloud-sun"></i>
                Season: {{ $season }}
            </td>
        </tr>

        @php
            $seasonTotals = [];
        @endphp

        @foreach($fertilizers as $fertilizerId => $units)
            @foreach($units as $unit => $currentAmount)
                @php
                    $prevAmount = $previousYearData[$kebeleId][$season][$fertilizerId][$unit] ?? 0;
                    $diff = $currentAmount - $prevAmount;

                    // Track season totals
                    if (!isset($seasonTotals[$unit])) {
                        $seasonTotals[$unit] = ['current'=>0,'previous'=>0,'diff'=>0];
                    }
                    $seasonTotals[$unit]['current'] += $currentAmount;
                    $seasonTotals[$unit]['previous'] += $prevAmount;
                    $seasonTotals[$unit]['diff'] += $diff;

                    // Track grand totals
                    if (!isset($grandTotals[$unit])) {
                        $grandTotals[$unit] = ['current'=>0,'previous'=>0,'diff'=>0];
                    }
                    $grandTotals[$unit]['current'] += $currentAmount;
                    $grandTotals[$unit]['previous'] += $prevAmount;
                    $grandTotals[$unit]['diff'] += $diff;
                @endphp

                <tr>
                    <td></td> {{-- grouped kebele --}}
                    <td></td> {{-- grouped season --}}
                    <td>{{ $fertilizerNames[$fertilizerId] ?? 'Unknown' }}</td>
                    <td class="text-right">{{ number_format($currentAmount,2) }}</td>
                    <td class="text-right">{{ number_format($prevAmount,2) }}</td>
                    <td class="text-right">{{ number_format($diff,2) }}</td>
                    <td>{{ $unit }}</td>
                </tr>
            @endforeach
        @endforeach

        <!-- ================= SEASON SUBTOTALS ================= -->
        @if(!empty($seasonTotals))
            <tr class="bg-light font-weight-bold">
                <td colspan="3" class="text-right pr-4">
                    <i class="fas fa-calendar-alt text-primary"></i>
                    Sub-total for {{ $season }}
                </td>
                @php
                    // Display the first unit's totals (assuming all in season have same unit)
                    $firstUnit = array_key_first($seasonTotals);
                @endphp
                <td class="text-right">{{ number_format($seasonTotals[$firstUnit]['current'],2) }}</td>
                <td class="text-right">{{ number_format($seasonTotals[$firstUnit]['previous'],2) }}</td>
                <td class="text-right">{{ number_format($seasonTotals[$firstUnit]['diff'],2) }}</td>
                <td>{{ $firstUnit }}</td>
            </tr>
            
            <!-- Additional units if they exist in the same season -->
            @php unset($seasonTotals[$firstUnit]); @endphp
            @foreach($seasonTotals as $unit => $totals)
                <tr class="bg-light font-weight-bold">
                    <td colspan="3" class="text-right pr-4">
                        <i class="fas fa-calendar-alt text-primary"></i>
                        Sub-total for {{ $season }} ({{ $unit }})
                    </td>
                    <td class="text-right">{{ number_format($totals['current'],2) }}</td>
                    <td class="text-right">{{ number_format($totals['previous'],2) }}</td>
                    <td class="text-right">{{ number_format($totals['diff'],2) }}</td>
                    <td>{{ $unit }}</td>
                </tr>
            @endforeach
        @endif

    @endforeach
@empty
    <tr>
        <td colspan="7" class="text-center text-muted">
            No data found for selected filters
        </td>
    </tr>
@endforelse
</tbody>

            <!-- ================= GRAND TOTALS ================= -->
            @if(!empty($grandTotals))
                <tfoot class="bg-secondary text-white">
                    @foreach($grandTotals as $unit => $totals)
                        <tr>
                            <th colspan="3" class="text-right">Grand Total ({{ $unit }})</th>
                            <th class="text-right">{{ number_format($totals['current'],2) }}</th>
                            <th class="text-right">{{ number_format($totals['previous'],2) }}</th>
                            <th class="text-right">{{ number_format($totals['diff'],2) }}</th>
                            <th>{{ $unit }}</th>
                        </tr>
                    @endforeach
                </tfoot>
            @endif
        </table>

    </div>

</div>
</div>
</section>
</x-layout>