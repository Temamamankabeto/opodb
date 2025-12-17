<x-layout>
<div class="content-header">

{{-- ================= HEADER ================= --}}
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6 d-flex align-items-center">
                <div class="bg-white border rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:60px;height:60px;">
                    <i class="fas fa-cogs fa-lg text-dark"></i>
                </div>
                <div>
                    <h1 class="m-0 text-dark">Regional Mechanization Report</h1>
                    <small class="text-dark">Mechanization analysis for your region</small>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ================= CONTENT ================= --}}
<section class="content">
<div class="container-fluid">

{{-- ================= FILTER ================= --}}
<div class="card card-outline card-dark">
    <div class="card-body">
        <form method="GET" action="{{ route('regional-mechanization-report.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <label>Year</label>
                    <select name="year" class="form-control form-control-sm">
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ request('year',$year)==$y?'selected':'' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Zone</label>
                    <select name="zone_id" id="zone" class="form-control form-control-sm">
                        <option value="">All Zones</option>
                        @foreach($zones as $id=>$name)
                            <option value="{{ $id }}" {{ $selectedZone==$id?'selected':'' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Woreda</label>
                    <select name="woreda_id" id="woreda" class="form-control form-control-sm">
                        <option value="">All Woredas</option>
                        @foreach($woredas as $id=>$name)
                            <option value="{{ $id }}" {{ $selectedWoreda==$id?'selected':'' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Kebele</label>
                    <select name="kebele_id" id="kebele" class="form-control form-control-sm">
                        <option value="">All Kebeles</option>
                        @foreach($kebeles as $id=>$name)
                            <option value="{{ $id }}" {{ $selectedKebele==$id?'selected':'' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="text-right mt-2">
                <button class="btn btn-dark btn-sm"><i class="fas fa-filter"></i> Apply</button>
                <a href="{{ route('regional-mechanization-report.index') }}" class="btn btn-light btn-sm border">
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
    <th>Mechanization Type</th>
    <th>Mechanization Name</th>
    <th colspan="2" class="text-center">Current ({{ $year }})</th>
    <th colspan="2" class="text-center">Previous ({{ $prevYear }})</th>
    <th>Change %</th>
    <th>Loans</th>
</tr>
<tr>
    <th colspan="4"></th>
    <th class="text-center">Amount</th>
    <th class="text-center">Unit</th>
    <th class="text-center">Amount</th>
    <th class="text-center">Unit</th>
    <th></th>
    <th></th>
    <th></th>
</tr>
</thead>

<tbody>
@php
$i=1;
$loanSummary=[];
$regionalCurrent=0;
$regionalPrev=0;
@endphp

@foreach($currentYearData as $zoneId => $zoneRows)
@php
$zoneName = $zones[$zoneId] ?? 'N/A';
$groupedByWoreda = $zoneRows->groupBy('woreda_id');
$zoneRowspan = $zoneRows->count() + $groupedByWoreda->count(); 
$zoneShown = false;
@endphp

@foreach($groupedByWoreda as $woredaId => $woredaRows)
@php
$woredaName = $woredas[$woredaId] ?? 'N/A';
$groupedByType = $woredaRows->groupBy('mechanization_type');
$woredaRowspan = $woredaRows->count() + $groupedByType->count();
$woredaShown = false;
@endphp

@foreach($groupedByType as $typeName => $typeRows)
@php
$typeCurrent = $typeRows->sum('amount');
$typePrev = 0;
foreach($typeRows as $r){
    $key = $r->mechanization_type.'_'.$r->name.'_'.$r->woreda_id;
    $typePrev += $previousYearData[$key]->amount ?? 0;
}
$typeRowspan = $typeRows->count()+1;
$typeShown=false;
@endphp

@foreach($typeRows as $row)
@php
$key = $row->mechanization_type.'_'.$row->name.'_'.$row->woreda_id;
$prev = $previousYearData[$key] ?? null;
$prevAmt = $prev->amount ?? 0;
$prevUnit = $prev->unit ?? '';
$change = $prevAmt>0?(($row->amount-$prevAmt)/$prevAmt)*100:0;

$regionalCurrent += $row->amount;
$regionalPrev += $prevAmt;

$loanDetails = json_decode($row->loan_details,true) ?? [];
foreach($loanDetails as $l){
    $loanSummary[$l['loan_type']] = ($loanSummary[$l['loan_type']] ?? 0)+($l['loan_amount'] ?? 0);
}
@endphp

<tr>
<td>{{ $i++ }}</td>

@if(!$zoneShown)
<td rowspan="{{ $zoneRowspan }}">{{ $zoneName }}</td>
@php $zoneShown=true; @endphp
@endif

@if(!$woredaShown)
<td rowspan="{{ $woredaRowspan }}">{{ $woredaName }}</td>
@php $woredaShown=true; @endphp
@endif

@if(!$typeShown)
<td rowspan="{{ $typeRowspan }}">{{ $typeName }}</td>
@php $typeShown=true; @endphp
@endif

<td>{{ $row->name }}</td>
<td class="text-center">{{ number_format($row->amount,2) }}</td>
<td class="text-center">{{ $row->unit }}</td>
<td class="text-center">{{ number_format($prevAmt,2) }}</td>
<td class="text-center">{{ $prevUnit }}</td>
<td class="text-center">{{ number_format($change,1) }}%</td>
<td class="text-center">
@if(count($loanDetails))
<button class="btn btn-xs btn-info" data-toggle="collapse" data-target="#loan-{{ $row->id }}">View</button>
@endif
</td>
</tr>

@if(count($loanDetails))
<tr class="collapse bg-light" id="loan-{{ $row->id }}">
<td colspan="11">
<ul class="mb-0">
@foreach($loanDetails as $loan)
<li><strong>{{ $loan['loan_type'] ?? 'N/A' }}</strong>: {{ number_format($loan['loan_amount']??0,2) }} ETB</li>
@endforeach
</ul>
</td>
</tr>
@endif

@endforeach

{{-- Type Total --}}
<tr class="bg-light font-weight-bold">
<td></td>
<td class="text-center">Total {{ $typeName }}</td>
<td class="text-center">{{ number_format($typeCurrent,2) }}</td>
<td></td>
<td class="text-center">{{ number_format($typePrev,2) }}</td>
<td></td>
<td class="text-center">{{ $typePrev>0?number_format((($typeCurrent-$typePrev)/$typePrev)*100,1):0 }}%</td>
<td></td>
<td></td>
<td></td>
</tr>

@endforeach
@endforeach
@endforeach

{{-- Regional Grand Total --}}
<tr class="bg-dark text-white font-weight-bold">
<td colspan="4" class="text-center">REGIONAL GRAND TOTAL</td>
<td class="text-center">{{ number_format($regionalCurrent,2) }}</td>
<td></td>
<td class="text-center">{{ number_format($regionalPrev,2) }}</td>
<td></td>
<td class="text-center">{{ $regionalPrev>0?number_format((($regionalCurrent-$regionalPrev)/$regionalPrev)*100,1):0 }}%</td>
<td></td>
<td></td>
</tr>

</tbody>
</table>
</div>
</div>

{{-- ================= LOAN SUMMARY ================= --}}
<div class="card mt-3">
<div class="card-header bg-secondary">
<h3 class="card-title">Loan Summary (All Woredas)</h3>
</div>
<div class="card-body p-0">
<table class="table table-bordered">
<thead class="thead-light">
<tr>
<th>Loan Type</th>
<th class="text-right">Total Amount (ETB)</th>
</tr>
</thead>
<tbody>
@forelse($loanSummary as $type=>$amount)
<tr>
<td>{{ $type }}</td>
<td class="text-right">{{ number_format($amount,2) }}</td>
</tr>
@empty
<tr>
<td colspan="2" class="text-center text-muted">No loan data available</td>
</tr>
@endforelse
</tbody>
</table>
</div>
</div>

</div>
</section>

{{-- ================= DEPENDENT DROPDOWNS JS ================= --}}
<script>
$(document).ready(function(){

    function loadWoredas(selectedZone, selectedWoreda = null){
        $('#woreda').html('<option value="">All Woredas</option>');
        $('#kebele').html('<option value="">All Kebeles</option>');
        if(selectedZone){
            $.get("{{ route('regional.get-woredas') }}",{zone_id: selectedZone}, function(data){
                $.each(data,function(id,name){
                    var selected = (id == selectedWoreda) ? 'selected' : '';
                    $('#woreda').append('<option value="'+id+'" '+selected+'>'+name+'</option>');
                });

                // If a woreda is selected, load kebeles too
                if(selectedWoreda){
                    loadKebeles(selectedWoreda, {{ $selectedKebele ?? 'null' }});
                }
            });
        }
    }

    function loadKebeles(selectedWoreda, selectedKebele = null){
        $('#kebele').html('<option value="">All Kebeles</option>');
        if(selectedWoreda){
            $.get("{{ route('regional.get-kebeles') }}",{woreda_id: selectedWoreda}, function(data){
                $.each(data,function(id,name){
                    var selected = (id == selectedKebele) ? 'selected' : '';
                    $('#kebele').append('<option value="'+id+'" '+selected+'>'+name+'</option>');
                });
            });
        }
    }

    // On page load: preload Woredas & Kebeles if zone/woreda selected
    var initialZone = '{{ $selectedZone ?? "" }}';
    var initialWoreda = '{{ $selectedWoreda ?? "" }}';
    if(initialZone){
        loadWoredas(initialZone, initialWoreda);
    }

    // On change events
    $('#zone').change(function(){
        var zoneId = $(this).val();
        loadWoredas(zoneId);
    });

    $('#woreda').change(function(){
        var woredaId = $(this).val();
        loadKebeles(woredaId);
    });

});
</script>


</x-layout>
