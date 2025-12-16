<x-layout>
<div class="content-header">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <div class="bg-white border rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:60px;height:60px;">
                        <i class="fas fa-cogs fa-lg text-dark"></i>
                    </div>
                    <div>
                        <h1 class="m-0 text-dark">Woreda Mechanization Report</h1>
                        <small class="text-dark">Mechanization analysis for your woreda</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <!-- Filters -->
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('woreda-mechanization-report.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="text-dark">Year</label>
                                <select id="year" name="year" class="form-control form-control-sm border">
                                    <option value="">Select Year</option>
                                    @foreach($years as $y)
                                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="text-dark">Kebele</label>
                                <select id="kebele" name="kebele_id" class="form-control form-control-sm border">
                                    <option value="">All Kebeles</option>
                                    @foreach($kebeles as $id => $name)
                                        <option value="{{ $id }}" {{ $selectedKebele == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="text-right mt-2">
                            <button type="submit" class="btn btn-dark btn-sm"><i class="fas fa-filter mr-1"></i> Apply Filter</button>
                            <a href="{{ route('woreda-mechanization-report.index') }}" class="btn btn-light btn-sm border"><i class="fas fa-redo mr-1"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>Kebele</th>
                            <th>Mechanization Type</th>
                            <th>Mechanization Name</th>
                            <th colspan="2" class="text-center">Current Year ({{ $year }})</th>
                            <th colspan="2" class="text-center">Previous Year ({{ $prevYear }})</th>
                            <th>Change %</th>
                            <th>Loans</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Unit</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Unit</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $i = 1; 
                            $loanSummary = [];
                        @endphp

                        @foreach($currentYearData as $kebeleId => $kebeleRows)
                            @php
                                $kebeleName = $kebeles[$kebeleId] ?? 'N/A';
                                $groupedByType = $kebeleRows->groupBy('mechanization_type');

                                // Calculate kebele rowspan as total mechanization rows in this kebele
                                $kebeleRowCount = $kebeleRows->count();
                                $kebeleFirstRow = true;
                            @endphp

                            @foreach($groupedByType as $typeName => $typeRows)
                                @php
                                    $typeRowCount = $typeRows->count();
                                    $typeFirstRow = true;

                                    // Pre-calculate totals for current and previous year for this type
                                    $typeCurrentTotal = $typeRows->sum('amount');
                                    $typePrevTotal = 0;
                                    foreach($typeRows as $row){
                                        $prevKey = $row->mechanization_type.'_'.$row->name.'_'.$row->kebele_id;
                                        $typePrevTotal += $previousYearData[$prevKey]->amount ?? 0;
                                    }
                                    $typeChangePercent = $typePrevTotal > 0 ? (($typeCurrentTotal - $typePrevTotal) / $typePrevTotal) * 100 : 0;
                                @endphp

                                @foreach($typeRows as $row)
                                    @php
                                        $prevKey = $row->mechanization_type.'_'.$row->name.'_'.$row->kebele_id;
                                        $prevData = $previousYearData[$prevKey] ?? null;
                                        $prevAmount = $prevData->amount ?? 0;
                                        $prevUnit = $prevData->unit ?? '';
                                        $prodChange = $prevAmount > 0 ? (($row->amount - $prevAmount)/$prevAmount)*100 : 0;
                                        $loanDetails = json_decode($row->loan_details,true) ?? [];

                                        foreach($loanDetails as $loan){
                                            $loanType = $loan['loan_type'] ?? 'N/A';
                                            $loanAmount = floatval($loan['loan_amount'] ?? 0);
                                            $loanSummary[$loanType] = ($loanSummary[$loanType] ?? 0) + $loanAmount;
                                        }
                                    @endphp

                                    <tr>
                                        <td>{{ $i++ }}</td>

                                        {{-- Kebele rowspan --}}
                                        @if($kebeleFirstRow)
                                            <td rowspan="{{ $kebeleRowCount }}">{{ $kebeleName }}</td>
                                            @php $kebeleFirstRow = false; @endphp
                                        @endif

                                        {{-- Mechanization Type rowspan --}}
                                        @if($typeFirstRow)
                                            <td rowspan="{{ $typeRowCount }}">{{ $typeName }}</td>
                                            @php $typeFirstRow = false; @endphp
                                        @endif

                                        <td>{{ $row->name }}</td>
                                        <td class="text-center">{{ number_format($row->amount,2) }}</td>
                                        <td class="text-center">{{ $row->unit ?? '' }}</td>
                                        <td class="text-center">{{ number_format($prevAmount,2) }}</td>
                                        <td class="text-center">{{ $prevUnit }}</td>
                                        <td class="text-center">{{ number_format($prodChange,1) }}%</td>
                                        <td class="text-center">
                                            @if(count($loanDetails))
                                                <button class="btn btn-sm btn-info" type="button" data-toggle="collapse" data-target="#loan-{{ $kebeleId }}-{{ $row->id }}" aria-expanded="false">
                                                    View Loans
                                                </button>
                                            @endif
                                        </td>
                                    </tr>

                                    @if(count($loanDetails))
                                        <tr class="collapse bg-light" id="loan-{{ $kebeleId }}-{{ $row->id }}">
                                            <td colspan="10">
                                                <ul class="mb-0">
                                                    @foreach($loanDetails as $loan)
                                                        <li>{{ $loan['loan_type'] ?? 'N/A' }}, {{ $loan['loan_amount'] ?? 0 }} ETB</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        </tr>
                                    @endif

                                @endforeach

                                {{-- Total per Mechanization Type --}}
                                <tr class="font-weight-bold bg-light">
                                    <td colspan="3"></td> {{-- empty for #, kebele, type --}}
                                    <td class="text-center">Total for {{ $typeName }} ({{ $kebeleName }})</td>
                                    <td class="text-center">{{ number_format($typeCurrentTotal,2) }}</td>
                                    <td></td>
                                    <td class="text-center">{{ number_format($typePrevTotal,2) }}</td>
                                    <td></td>
                                    <td class="text-center">{{ number_format($typeChangePercent,1) }}%</td>
                                    <td></td>
                                </tr>

                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Loan Summary -->
            <div class="card mt-3">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Loan Summary by Loan Type (All Kebeles)</h5>
                </div>
                <div class="card-body">
                    @if(count($loanSummary))
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Loan Type</th>
                                    <th>Total Amount (ETB)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loanSummary as $type => $amount)
                                    <tr>
                                        <td>{{ $type }}</td>
                                        <td class="text-right">{{ number_format($amount,2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No loan data available for this year.</p>
                    @endif
                </div>
            </div>

        </div>
    </section>
</div>
</x-layout>
