<x-layout>
<div class="content-header">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center">
                        <div class="bg-white border mr-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-seedling fa-lg text-dark"></i>
                        </div>
                        <div>
                            <h1 class="m-0 text-dark">Woreda Agricultural Report</h1>
                            <small class="text-dark">
                                Crop production analysis for {{ $woredaName }}
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#" class="text-dark"><i class="fas fa-home"></i> Home</a></li>
                        <li class="breadcrumb-item active text-dark">Woreda Crop Report</li>
                    </ol>
                    <div class="float-sm-right mt-2">
                        <span class="badge badge-light border">
                            <i class="far fa-clock mr-1 text-dark"></i>
                            <span class="text-dark">{{ now()->format('d M Y, h:i A') }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('crop-gabaasa.index') }}">
                        <div class="row">
                            <!-- Kebele -->
                            <div class="col-md-3">
                                <label>Kebele</label>
                                <select id="kebele" name="kebele_id" class="form-control form-control-sm">
                                    <option value="">All Kebeles</option>
                                    @foreach($kebeles as $id => $name)
                                        <option value="{{ $id }}" {{ request('kebele_id') == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Year -->
                            <div class="col-md-3">
                                <label>Year</label>
                                <select id="year" name="year" class="form-control form-control-sm">
                                    <option value="">Select Year</option>
                                    @foreach($years as $y)
                                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Season -->
                            <div class="col-md-3">
                                <label>Season</label>
                                <select id="season" name="season" class="form-control form-control-sm" disabled>
                                    <option value="">All Seasons</option>
                                </select>
                            </div>

                            <!-- Crop Type -->
                            <div class="col-md-3">
                                <label>Crop Type</label>
                                <select id="croptype" name="croptype_id" class="form-control form-control-sm" disabled>
                                    <option value="">All Crop Types</option>
                                </select>
                            </div>

                            <!-- Crop -->
                            <div class="col-md-3 mt-2">
                                <label>Crop</label>
                                <select id="crop" name="crop_id" class="form-control form-control-sm" disabled>
                                    <option value="">All Crops</option>
                                </select>
                            </div>
                        </div>

                        <div class="text-right mt-3">
                            <button type="submit" class="btn btn-dark btn-sm"><i class="fas fa-filter mr-1"></i> Apply Filter</button>
                            <a href="{{ route('crop-gabaasa.index') }}" class="btn btn-light btn-sm border"><i class="fas fa-redo mr-1"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            @if($currentYearData->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>Kebele</th>
                            <th>Season</th>
                            <th>Crop</th>
                            <th colspan="2" class="text-center">Current Year ({{ $year }})</th>
                            <th colspan="2" class="text-center">Previous Year ({{ $prevYear }})</th>
                            <th>Production %</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="text-center">Land (Ha)</th>
                            <th class="text-center">Production (Qt)</th>
                            <th class="text-center">Land (Ha)</th>
                            <th class="text-center">Production (Qt)</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                        $i = 1;
                        $totalCurrentLand = 0;
                        $totalCurrentProd = 0;
                        $totalPrevLand = 0;
                        $totalPrevProd = 0;
                    @endphp
                    @foreach($currentYearData->groupBy('kebele_name') as $kebeleName => $kebeleRows)
                        @foreach($kebeleRows->groupBy('season') as $season => $rows)
                            @php
                                $seasonRowCount = $rows->count();
                                $seasonRowIndex = 0;
                                $seasonCurrentLand = 0;
                                $seasonCurrentProd = 0;
                                $seasonPrevLand = 0;
                                $seasonPrevProd = 0;
                            @endphp
                            @foreach($rows as $row)
                                @php
                                    $prevKey = $row->kebele_id . '_' . $row->season . '_' . $row->crop_id;
                                    $prevData = $previousYearData[$prevKey] ?? null;
                                    $prevLand = $prevData->land ?? 0;
                                    $prevProd = $prevData->production ?? 0;
                                    $prodChange = $prevProd > 0 ? (($row->production - $prevProd)/$prevProd)*100 : 0;

                                    $totalCurrentLand += $row->land;
                                    $totalCurrentProd += $row->production;
                                    $totalPrevLand += $prevLand;
                                    $totalPrevProd += $prevProd;

                                    $seasonCurrentLand += $row->land;
                                    $seasonCurrentProd += $row->production;
                                    $seasonPrevLand += $prevLand;
                                    $seasonPrevProd += $prevProd;

                                    $seasonRowIndex++;
                                @endphp
                                <tr>
                                    @if($seasonRowIndex == 1)
                                        <td rowspan="{{ $seasonRowCount }}" class="text-center align-middle">{{ $i++ }}</td>
                                        <td rowspan="{{ $seasonRowCount }}" class="align-middle">{{ $kebeleName }}</td>
                                        <td rowspan="{{ $seasonRowCount }}" class="align-middle">{{ $season }}</td>
                                    @endif
                                    <td>{{ $row->crop_name }}</td>
                                    <td class="text-center">{{ number_format($row->land,2) }}</td>
                                    <td class="text-center">{{ number_format($row->production,2) }}</td>
                                    <td class="text-center">{{ number_format($prevLand,2) }}</td>
                                    <td class="text-center">{{ number_format($prevProd,2) }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $prodChange >= 0 ? 'badge-success' : 'badge-danger' }}">
                                            {{ $prodChange >= 0 ? '+' : '' }}{{ number_format($prodChange,1) }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            <!-- Season Total -->
                            <tr class="bg-secondary text-white">
                                <td colspan="3" class="text-right">Season Total ({{ $season }})</td>
                                <td class="text-center">{{ number_format($seasonCurrentLand,2) }}</td>
                                <td class="text-center">{{ number_format($seasonCurrentProd,2) }}</td>
                                <td class="text-center">{{ number_format($seasonPrevLand,2) }}</td>
                                <td class="text-center">{{ number_format($seasonPrevProd,2) }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                    <tfoot class="bg-dark text-white">
                        <tr>
                            <td colspan="4" class="text-right">Woreda Grand Total</td>
                            <td class="text-center">{{ number_format($totalCurrentLand,2) }}</td>
                            <td class="text-center">{{ number_format($totalCurrentProd,2) }}</td>
                            <td class="text-center">{{ number_format($totalPrevLand,2) }}</td>
                            <td class="text-center">{{ number_format($totalPrevProd,2) }}</td>
                            <td class="text-center">
                                @php
                                    $totalChange = $totalPrevProd > 0 ? (($totalCurrentProd - $totalPrevProd)/$totalPrevProd)*100 : 0;
                                @endphp
                                <span class="badge {{ $totalChange >= 0 ? 'badge-success' : 'badge-danger' }}">
                                    {{ $totalChange >= 0 ? '+' : '' }}{{ number_format($totalChange,1) }}%
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No data found for selected filters.
                </div>
            @endif
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kebele = document.getElementById('kebele');
    const year = document.getElementById('year');
    const season = document.getElementById('season');
    const croptype = document.getElementById('croptype');
    const crop = document.getElementById('crop');

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    async function fetchData(url, params) {
        const query = new URLSearchParams(params).toString();
        const res = await fetch(`${url}?${query}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            }
        });
        return await res.json();
    }

    function resetSelect(sel, text) {
        sel.innerHTML = `<option value="">${text}</option>`;
        sel.disabled = true;
    }

    // Kebele → Year, reset dependent selects
    kebele.addEventListener('change', () => {
        resetSelect(year, 'Select Year');
        resetSelect(season, 'All Seasons');
        resetSelect(croptype, 'All Crop Types');
        resetSelect(crop, 'All Crops');
        // optionally reload years if dynamic
    });

    // Year → Season
    year.addEventListener('change', async () => {
        resetSelect(season, 'All Seasons');
        resetSelect(croptype, 'All Crop Types');
        resetSelect(crop, 'All Crops');
        if (!year.value) return;
        try {
            const data = await fetchData('/woredas/filter/seasons', {
                kebele_id: kebele.value,
                year: year.value
            });
            season.disabled = false;
            season.innerHTML = '<option value="">All Seasons</option>';
            data.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s;
                opt.textContent = s;
                season.appendChild(opt);
            });
        } catch(e){ console.error(e); }
    });

    // Season → Crop Type
    season.addEventListener('change', async () => {
        resetSelect(croptype, 'All Crop Types');
        resetSelect(crop, 'All Crops');
        if (!season.value) return;
        try {
            const data = await fetchData('/woredas/filter/crop-types', {
                kebele_id: kebele.value,
                year: year.value,
                season: season.value
            });
            croptype.disabled = false;
            croptype.innerHTML = '<option value="">All Crop Types</option>';
            data.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.id;
                opt.textContent = c.name;
                croptype.appendChild(opt);
            });
        } catch(e){ console.error(e); }
    });

    // Crop Type → Crop
    croptype.addEventListener('change', async () => {
        resetSelect(crop, 'All Crops');
        if (!croptype.value) return;
        try {
            const data = await fetchData('/woredas/filter/crops', {
                kebele_id: kebele.value,
                year: year.value,
                season: season.value,
                croptype_id: croptype.value
            });
            crop.disabled = false;
            crop.innerHTML = '<option value="">All Crops</option>';
            data.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.id;
                opt.textContent = c.name;
                crop.appendChild(opt);
            });
        } catch(e){ console.error(e); }
    });
});
</script>
</x-layout>
