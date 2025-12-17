<x-layout>
<div class="content-header">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <div class="bg-white border rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:60px;height:60px;">
                        <i class="fas fa-seedling fa-lg text-success"></i>
                    </div>
                    <div>
                        <h1 class="m-0 text-dark">Zonal Level Compost Report</h1>
                        <small class="text-muted">Compost distribution by zone and woredas</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            {{-- Filter --}}
            <div class="card card-outline card-success">
                <div class="card-body">
                <form method="GET" action="{{ route('zonal-compost-report.index') }}">
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
            <label>Woreda</label>
            <select name="woreda_id" class="form-control form-control-sm">
                <option value="">All Woredas</option>
                @foreach($woredas as $id => $name)
                    <option value="{{ $id }}" {{ $selectedWoreda == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="text-right mt-2">
        <button class="btn btn-success btn-sm"><i class="fas fa-filter"></i> Apply</button>
        <a href="{{ route('zonal-compost-report.index') }}" class="btn btn-light btn-sm border">
            <i class="fas fa-redo"></i> Reset
        </a>
    </div>
</form>

                </div>
            </div>

            {{-- Table --}}
            <div class="card">
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered table-hover text-nowrap">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Zone</th>
                                <th>Woreda</th>
                                <th>Compost Type</th>
                                <th colspan="2" class="text-center">Current ({{ $selectedYear }})</th>
                                <th colspan="2" class="text-center">Previous ({{ $prevYear }})</th>
                                <th>Change %</th>
                                <th>Land Area (ha)</th>
                            </tr>
                            <tr>
                                <th colspan="3"></th>
                                <th></th>
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
                                $zonalGrandTotals = []; // per compost type
                            @endphp

                            @foreach($zonalData as $zoneId => $woredas)
                                @php
                                    $zoneName = $zones[$zoneId] ?? 'N/A';
                                    $zoneRowspan = 0;
                                    foreach($woredas as $woredaId => $rows) $zoneRowspan += count($rows);
                                    $zoneShown = false;
                                @endphp

                                @foreach($woredas as $woredaId => $rows)
                                    @php
                                        $woredaName = $woredasList[$woredaId] ?? 'N/A';
                                        $woredaShown = false;
                                    @endphp

                                    @foreach($rows as $row)
                                        @php
                                            $prevRow = $previousYearData[$zoneId][$woredaId][$row->compost_type] ?? null;

                                            $commonPrev = $prevRow->common ?? 0;
                                            $individualPrev = $prevRow->individual ?? 0;

                                            $change = ($commonPrev + $individualPrev) > 0
                                                ? (($row->common + $row->individual - ($commonPrev + $individualPrev)) / ($commonPrev + $individualPrev)) * 100
                                                : 0;

                                            if(!isset($zonalGrandTotals[$row->compost_type])){
                                                $zonalGrandTotals[$row->compost_type] = [
                                                    'current_common'=>0,
                                                    'current_individual'=>0,
                                                    'prev_common'=>0,
                                                    'prev_individual'=>0
                                                ];
                                            }

                                            $zonalGrandTotals[$row->compost_type]['current_common'] += $row->common;
                                            $zonalGrandTotals[$row->compost_type]['current_individual'] += $row->individual;
                                            $zonalGrandTotals[$row->compost_type]['prev_common'] += $commonPrev;
                                            $zonalGrandTotals[$row->compost_type]['prev_individual'] += $individualPrev;
                                        @endphp
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            @if(!$zoneShown)
                                                <td rowspan="{{ $zoneRowspan }}">{{ $zoneName }}</td>
                                                @php $zoneShown = true; @endphp
                                            @endif

                                            @if(!$woredaShown)
                                                <td rowspan="{{ count($rows) }}">{{ $woredaName }}</td>
                                                @php $woredaShown = true; @endphp
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

                            {{-- Zonal Grand Total per Compost Type --}}
                            @foreach($zonalGrandTotals as $type => $totals)
                            @php
                                $totalCurrent = $totals['current_common'] + $totals['current_individual'];
                                $totalPrev = $totals['prev_common'] + $totals['prev_individual'];
                                $totalChange = $totalPrev>0 ? (($totalCurrent-$totalPrev)/$totalPrev)*100 : 0;
                            @endphp
                            <tr class="bg-success text-white font-weight-bold">
                                <td colspan="3" class="text-center">ZONAL GRAND TOTAL - {{ $type }}</td>
                                <td>{{ $type }}</td>
                                <td class="text-center">{{ number_format($totals['current_common'],2) }}</td>
                                <td class="text-center">{{ number_format($totals['current_individual'],2) }}</td>
                                <td class="text-center">{{ number_format($totals['prev_common'],2) }}</td>
                                <td class="text-center">{{ number_format($totals['prev_individual'],2) }}</td>
                                <td class="text-center">{{ number_format($totalChange,1) }}%</td>
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
