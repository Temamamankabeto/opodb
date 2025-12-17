<x-layout>
<div class="content-header">

    {{-- ================= HEADER ================= --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <div class="bg-white border rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:60px;height:60px;">
                        <i class="fas fa-seedling fa-lg text-success"></i>
                    </div>
                    <div>
                        <h1 class="m-0 text-dark">Regional Level Compost Report</h1>
                        <small class="text-muted">Compost distribution by zone, woreda, kebele, and type</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= FILTER ================= --}}
    <section class="content">
        <div class="container-fluid">

            <div class="card card-outline card-success">
                <div class="card-body">
                    <form method="GET" action="{{ route('regional-compost-report.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Year</label>
                                <select name="year" class="form-control form-control-sm">
                                    @foreach($years as $y)
                                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Zone</label>
                                <select name="zone_id" class="form-control form-control-sm">
                                    <option value="">All Zones</option>
                                    @foreach($zones as $id => $name)
                                        <option value="{{ $id }}" {{ $selectedZone == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="text-right mt-2">
                            <button class="btn btn-success btn-sm"><i class="fas fa-filter"></i> Apply</button>
                            <a href="{{ route('regional-compost-report.index') }}" class="btn btn-light btn-sm border">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ================= TABLE ================= --}}
            <div class="card">
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered table-hover text-nowrap">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Zone</th>
                                <th>Woreda</th>
                                <th>Kebele</th>
                                <th>Compost Type</th>
                                <th colspan="2" class="text-center">Current ({{ $selectedYear }})</th>
                                <th colspan="2" class="text-center">Previous ({{ $prevYear }})</th>
                                <th>Change %</th>
                                <th>Land Area (ha)</th>
                            </tr>
                            <tr>
                                <th colspan="5"></th>
                                <th>Common</th>
                                <th>Individual</th>
                                <th>Common</th>
                                <th>Individual</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $i = 1;
                                $regionalTotals = [];
                            @endphp

                            @foreach($regionalData as $zoneId => $woredas)
                                @php
                                    $zoneName = $zones[$zoneId] ?? 'N/A';
                                    $zoneRowspan = collect($woredas)->sum(fn($kebeles) => collect($kebeles)->sum(fn($rows) => count($rows)));
                                    $zoneShown = false;
                                @endphp

                                @foreach($woredas as $woredaId => $kebeles)
                                    @php
                                        $woredaRowspan = collect($kebeles)->sum(fn($rows) => count($rows));
                                        $woredaShown = false;
                                    @endphp

                                    @foreach($kebeles as $kebeleId => $rows)
                                        @php
                                            $kebeleRowspan = count($rows);
                                            $kebeleShown = false;
                                        @endphp

                                        @foreach($rows as $row)
                                            @php
                                                $prevRow = $previousYearData[$zoneId][$woredaId][$kebeleId][$row->compost_type] ?? null;
                                                $commonPrev = $prevRow->common ?? 0;
                                                $individualPrev = $prevRow->individual ?? 0;

                                                $change = ($commonPrev + $individualPrev) > 0
                                                    ? (($row->common + $row->individual - ($commonPrev + $individualPrev)) / ($commonPrev + $individualPrev)) * 100
                                                    : 0;

                                                // Aggregate totals by compost type
                                                if(!isset($regionalTotals[$row->compost_type])){
                                                    $regionalTotals[$row->compost_type] = ['common'=>0,'individual'=>0,'prev_common'=>0,'prev_individual'=>0];
                                                }
                                                $regionalTotals[$row->compost_type]['common'] += $row->common;
                                                $regionalTotals[$row->compost_type]['individual'] += $row->individual;
                                                $regionalTotals[$row->compost_type]['prev_common'] += $commonPrev;
                                                $regionalTotals[$row->compost_type]['prev_individual'] += $individualPrev;
                                            @endphp

                                            <tr>
                                                <td>{{ $i++ }}</td>

                                                @if(!$zoneShown)
                                                    <td rowspan="{{ $zoneRowspan }}">{{ $zoneName }}</td>
                                                    @php $zoneShown = true; @endphp
                                                @endif

                                                @if(!$woredaShown)
                                                    <td rowspan="{{ $woredaRowspan }}">{{ $row->woreda->name ?? 'N/A' }}</td>
                                                    @php $woredaShown = true; @endphp
                                                @endif

                                                @if(!$kebeleShown)
                                                    <td rowspan="{{ $kebeleRowspan }}">{{ $row->kebele->name ?? 'N/A' }}</td>
                                                    @php $kebeleShown = true; @endphp
                                                @endif

                                                <td>{{ $row->compost_type }}</td>
                                                <td class="text-center">{{ number_format($row->common,2) }} {{ $row->unit ?? '' }}</td>
                                                <td class="text-center">{{ number_format($row->individual,2) }} {{ $row->unit ?? '' }}</td>
                                                <td class="text-center">{{ number_format($commonPrev,2) }} {{ $row->unit ?? '' }}</td>
                                                <td class="text-center">{{ number_format($individualPrev,2) }} {{ $row->unit ?? '' }}</td>
                                                <td class="text-center">{{ number_format($change,1) }}%</td>
                                                <td class="text-center">{{ number_format($row->land_area ?? 0,2) }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @endforeach

                            {{-- Regional Grand Total --}}
                            @foreach($regionalTotals as $compostType => $totals)
                                <tr class="bg-success text-white font-weight-bold">
                                    <td colspan="5" class="text-center">{{ $compostType }} GRAND TOTAL</td>
                                    <td class="text-center">{{ number_format($totals['common'],2) }}</td>
                                    <td class="text-center">{{ number_format($totals['individual'],2) }}</td>
                                    <td class="text-center">{{ number_format($totals['prev_common'],2) }}</td>
                                    <td class="text-center">{{ number_format($totals['prev_individual'],2) }}</td>
                                    <td class="text-center">
                                        {{ ($totals['prev_common'] + $totals['prev_individual'])>0
                                            ? number_format((($totals['common']+$totals['individual']-($totals['prev_common']+$totals['prev_individual']))/($totals['prev_common']+$totals['prev_individual']))*100,1)
                                            : 0
                                        }}%
                                    </td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>

</div>
</x-layout>
