<x-layout>
<div class="content-header">

    {{-- Header --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <div class="bg-white border rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:60px;height:60px;">
                        <i class="fas fa-seedling fa-lg text-success"></i>
                    </div>
                    <div>
                        <h1 class="m-0 text-dark">Kebele Level Compost Report</h1>
                        <small class="text-muted">Compost distribution by type</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Filter --}}
    <section class="content">
        <div class="container-fluid">
            <div class="card card-outline card-success">
                <div class="card-body">
                    <form method="GET" action="{{ route('kebele-compost-report.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Year</label>
                                <select name="year" class="form-control form-control-sm">
                                    @foreach($years as $y)
                                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="text-right mt-2">
                            <button class="btn btn-success btn-sm"><i class="fas fa-filter"></i> Apply</button>
                            <a href="{{ route('kebele-compost-report.index') }}" class="btn btn-light btn-sm border">
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
                                <th>Compost Type</th>
                                <th colspan="2" class="text-center">Current ({{ $selectedYear }})</th>
                                <th colspan="2" class="text-center">Previous ({{ $prevYear }})</th>
                                <th>Change %</th>
                                <th>Land Area (ha)</th>
                            </tr>
                            <tr>
                                <th></th>
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
                                $currentTotal = 0;
                                $prevTotal = 0;
                            @endphp
                            @foreach($kebelesData as $kebeleId => $rows)
                                @foreach($rows as $row)
                                    @php
                                        $prevRow = $previousYearData[$kebeleId][$row->compost_type] ?? null;
                                        $commonPrev = $prevRow->common ?? 0;
                                        $individualPrev = $prevRow->individual ?? 0;
                                        $change = ($commonPrev + $individualPrev) > 0
                                            ? (($row->common + $row->individual - ($commonPrev + $individualPrev)) / ($commonPrev + $individualPrev)) * 100
                                            : 0;
                                        $currentTotal += $row->common + $row->individual;
                                        $prevTotal += $commonPrev + $individualPrev;
                                    @endphp
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $row->compost_type }}</td>
                                        <td class="text-center">{{ number_format($row->common,2) }}</td>
                                        <td class="text-center">{{ number_format($row->individual,2) }}</td>
                                        <td class="text-center">{{ number_format($commonPrev,2) }}</td>
                                        <td class="text-center">{{ number_format($individualPrev,2) }}</td>
                                        <td class="text-center">{{ number_format($change,1) }}%</td>
                                        <td class="text-center">{{ number_format($row->land_area ?? 0,2) }}</td>
                                    </tr>
                                @endforeach
                            @endforeach

                            {{-- Grand Total --}}
                            <tr class="bg-success text-white font-weight-bold">
                                <td colspan="2" class="text-center">TOTAL</td>
                                <td class="text-center">{{ number_format($currentTotal,2) }}</td>
                                <td>-</td>
                                <td class="text-center">{{ number_format($prevTotal,2) }}</td>
                                <td>-</td>
                                <td class="text-center">{{ $prevTotal>0 ? number_format((($currentTotal-$prevTotal)/$prevTotal)*100,1) : 0 }}%</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</div>
</x-layout>
