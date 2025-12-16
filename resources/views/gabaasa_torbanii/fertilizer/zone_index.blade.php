<x-layout>
<section class="content">
<div class="container-fluid">

<div class="card card-success">

    <!-- ================= HEADER ================= -->
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-leaf"></i>
            Zonal Fertilizer Report ({{ $year }} vs {{ $prevYear }})
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
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- SEASON -->
                <div class="col-md-2">
                    <label>Season</label>
                    <select name="season" class="form-control">
                        <option value="">All</option>
                        @foreach(['Ganna','Bona','Arfaasaa'] as $s)
                            <option value="{{ $s }}" {{ request('season') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- WOREDA -->
                <div class="col-md-3">
                    <label>Woreda</label>
                    <select name="woreda_id" id="woreda" class="form-control">
                        <option value="">All Woredas</option>
                        @foreach($woredas as $id => $name)
                            <option value="{{ $id }}" {{ request('woreda_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- KEBELE -->
                <div class="col-md-3">
                    <label>Kebele</label>
                    <select name="kebele_id" id="kebele" class="form-control">
                        <option value="">All Kebeles</option>
                        @foreach($kebeles as $id => $name)
                            <option value="{{ $id }}" {{ request('kebele_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- FERTILIZER TYPE -->
                <div class="col-md-2">
                    <label>Fertilizer Type</label>
                    <select name="fertilizer_type_id" class="form-control">
                        <option value="">All</option>
                        @foreach($fertilizerTypes as $id => $name)
                            <option value="{{ $id }}" {{ request('fertilizer_type_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- FERTILIZER -->
                <div class="col-md-2">
                    <label>Fertilizer</label>
                    <select name="fertilizer_id" class="form-control">
                        <option value="">All</option>
                        @foreach($fertilizerNames as $id => $name)
                            <option value="{{ $id }}" {{ request('fertilizer_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button class="btn btn-success btn-block"><i class="fas fa-filter"></i></button>
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
@php $grandTotals = []; @endphp

@forelse($currentYearData as $woredaId => $kebelesData)

    <!-- ================= WOREDA GROUP ================= -->
    <tr class="bg-primary text-white font-weight-bold">
        <td colspan="7">
            <i class="fas fa-city"></i>
            Woreda: {{ $woredas[$woredaId] ?? 'Unknown Woreda' }}
        </td>
    </tr>

    @php $woredaTotals = []; @endphp

    @foreach($kebelesData as $kebeleId => $seasons)

        <!-- ================= KEBELE GROUP ================= -->
        <tr class="bg-light font-weight-bold">
            <td colspan="7" class="pl-3">
                <i class="fas fa-map-marker-alt text-success"></i>
                {{ $kebeles[$kebeleId] ?? 'Unknown Kebele' }}
            </td>
        </tr>

        @foreach($seasons as $season => $fertilizers)

            <!-- ================= SEASON GROUP ================= -->
            <tr class="bg-secondary text-white">
                <td colspan="7" class="pl-5">
                    <i class="fas fa-cloud-sun"></i>
                    Season: {{ $season }}
                </td>
            </tr>

            @php $seasonTotals = []; @endphp

            @foreach($fertilizers as $fertilizerId => $units)
                @foreach($units as $unit => $currentAmount)

                    @php
                        $prevAmount = $previousYearData[$woredaId][$kebeleId][$season][$fertilizerId][$unit] ?? 0;
                        $diff = $currentAmount - $prevAmount;

                        if (!isset($seasonTotals[$unit])) {
                            $seasonTotals[$unit] = ['current'=>0,'previous'=>0,'diff'=>0];
                        }
                        if (!isset($woredaTotals[$unit])) {
                            $woredaTotals[$unit] = ['current'=>0,'previous'=>0,'diff'=>0];
                        }
                        if (!isset($grandTotals[$unit])) {
                            $grandTotals[$unit] = ['current'=>0,'previous'=>0,'diff'=>0];
                        }

                        $seasonTotals[$unit]['current'] += $currentAmount;
                        $seasonTotals[$unit]['previous'] += $prevAmount;
                        $seasonTotals[$unit]['diff'] += $diff;

                        $woredaTotals[$unit]['current'] += $currentAmount;
                        $woredaTotals[$unit]['previous'] += $prevAmount;
                        $woredaTotals[$unit]['diff'] += $diff;

                        $grandTotals[$unit]['current'] += $currentAmount;
                        $grandTotals[$unit]['previous'] += $prevAmount;
                        $grandTotals[$unit]['diff'] += $diff;
                    @endphp

                    <tr>
                        <td></td>
                        <td></td>
                        <td>{{ $fertilizerNames[$fertilizerId] ?? 'Unknown' }}</td>
                        <td class="text-right">{{ number_format($currentAmount,2) }}</td>
                        <td class="text-right">{{ number_format($prevAmount,2) }}</td>
                        <td class="text-right">{{ number_format($diff,2) }}</td>
                        <td>{{ $unit }}</td>
                    </tr>

                @endforeach
            @endforeach

            <!-- ================= SEASON SUBTOTAL ================= -->
            @foreach($seasonTotals as $unit => $totals)
                <tr class="bg-light font-weight-bold">
                    <td colspan="3" class="text-right pr-4">
                        Sub-total for {{ $season }}
                    </td>
                    <td class="text-right">{{ number_format($totals['current'],2) }}</td>
                    <td class="text-right">{{ number_format($totals['previous'],2) }}</td>
                    <td class="text-right">{{ number_format($totals['diff'],2) }}</td>
                    <td>{{ $unit }}</td>
                </tr>
            @endforeach

        @endforeach
    @endforeach

    <!-- ================= WOREDA SUBTOTAL ================= -->
    @foreach($woredaTotals as $unit => $totals)
        <tr class="bg-warning font-weight-bold">
            <td colspan="3" class="text-right">
                Woreda Total ({{ $woredas[$woredaId] ?? '' }})
            </td>
            <td class="text-right">{{ number_format($totals['current'],2) }}</td>
            <td class="text-right">{{ number_format($totals['previous'],2) }}</td>
            <td class="text-right">{{ number_format($totals['diff'],2) }}</td>
            <td>{{ $unit }}</td>
        </tr>
    @endforeach

@empty
    <tr>
        <td colspan="7" class="text-center text-muted">No data found</td>
    </tr>
@endforelse
</tbody>

            <!-- ================= GRAND TOTAL ================= -->
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

        </table>
    </div>

</div>
</div>
</section>

<script>
document.getElementById('woreda').addEventListener('change', function () {
    let kebele = document.getElementById('kebele');
    kebele.innerHTML = '<option value="">Loading...</option>';

    if (!this.value) {
        kebele.innerHTML = '<option value="">All Kebeles</option>';
        return;
    }

    fetch(`/zones/kebeles-by-woreda/${this.value}`)
        .then(res => res.json())
        .then(data => {
            let options = '<option value="">All Kebeles</option>';
            data.forEach(k => {
                options += `<option value="${k.id}">${k.name}</option>`;
            });
            kebele.innerHTML = options;
        });
});
</script>

</x-layout>
