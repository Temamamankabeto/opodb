<x-layout>
<div class="content-header">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <div class="bg-white border rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:60px;height:60px;">
                        <i class="fas fa-seedling fa-lg text-dark"></i>
                    </div>
                    <div>
                        <h1 class="m-0 text-dark">Regional Agricultural Report</h1>
                        <small class="text-dark">
                            Crop production analysis for {{ $regionName }}
                            @if($selectedZone)
                                - {{ $zones[$selectedZone] ?? 'Selected Zone' }}
                            @else
                                - All Zones
                            @endif
                            @if($selectedWoreda)
                                , {{ $woredas[$selectedWoreda] ?? 'Selected Woreda' }}
                            @endif
                        </small>
                    </div>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#" class="text-dark"><i class="fas fa-home text-dark"></i> Home</a></li>
                        <li class="breadcrumb-item active text-dark">Regional Crop Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <!-- Filters -->
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('regional-crop-gabaasa.index') }}">
                        <div class="row">
                            <!-- Zone -->
                            <div class="col-md-3">
                                <label class="text-dark">Zone</label>
                                <select id="zone" name="zone_id" class="form-control form-control-sm border">
                                    <option value="">All Zones</option>
                                    @foreach($zones as $id => $name)
                                        <option value="{{ $id }}" {{ request('zone_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Woreda -->
                            <div class="col-md-3">
                                <label class="text-dark">Woreda</label>
                                <select id="woreda" name="woreda_id" class="form-control form-control-sm border" disabled>
                                    <option value="">All Woredas</option>
                                </select>
                            </div>

                            <!-- Kebele -->
                            <div class="col-md-3">
                                <label class="text-dark">Kebele</label>
                                <select id="kebele" name="kebele_id" class="form-control form-control-sm border" disabled>
                                    <option value="">All Kebeles</option>
                                </select>
                            </div>

                            <!-- Year -->
                            <div class="col-md-2">
                                <label class="text-dark">Year</label>
                                <select id="year" name="year" class="form-control form-control-sm border">
                                    <option value="">Select Year</option>
                                    @foreach($years as $y)
                                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Season -->
                            <div class="col-md-2">
                                <label class="text-dark">Season</label>
                                <select id="season" name="season" class="form-control form-control-sm border" disabled>
                                    <option value="">All Seasons</option>
                                </select>
                            </div>

                            <!-- Crop Type -->
                            <div class="col-md-2">
                                <label class="text-dark">Crop Type</label>
                                <select id="croptype" name="croptype_id" class="form-control form-control-sm border" disabled>
                                    <option value="">All Crop Types</option>
                                </select>
                            </div>

                            <!-- Crop -->
                            <div class="col-md-2">
                                <label class="text-dark">Crop</label>
                                <select id="crop" name="crop_id" class="form-control form-control-sm border" disabled>
                                    <option value="">All Crops</option>
                                </select>
                            </div>
                        </div>

                        <div class="text-right mt-2">
                            <button type="submit" class="btn btn-dark btn-sm"><i class="fas fa-filter mr-1"></i> Apply Filter</button>
                            <a href="{{ route('regional-crop-gabaasa.index') }}" class="btn btn-light btn-sm border"><i class="fas fa-redo mr-1"></i> Reset</a>
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
                            <th>Zone</th>
                            <th>Woreda</th>
                            <th>Kebele</th>
                            <th>Season</th>
                            <th>Crop</th>
                            <th colspan="2" class="text-center">Current Year ({{ $year }})</th>
                            <th colspan="2" class="text-center">Previous Year ({{ $prevYear }})</th>
                            <th>Change %</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
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
                            $groupedData = $currentYearData->groupBy('zone_name');
                        @endphp
                        @foreach($groupedData as $zoneName => $zoneRows)
                            @foreach($zoneRows->groupBy('woreda_name') as $woredaName => $woredaRows)
                                @foreach($woredaRows->groupBy('kebele_name') as $kebeleName => $kebeleRows)
                                    @foreach($kebeleRows->groupBy('season') as $seasonName => $seasonRows)
                                        @foreach($seasonRows as $row)
                                            @php
                                                $prevKey = $row->zone_id.'_'.$row->woreda_id.'_'.$row->kebele_id.'_'.$row->season.'_'.$row->crop_id;
                                                $prevData = $previousYearData[$prevKey] ?? null;
                                                $prevLand = $prevData->land ?? 0;
                                                $prevProd = $prevData->production ?? 0;
                                                $prodChange = $prevProd > 0 ? (($row->production - $prevProd)/$prevProd)*100 : 0;
                                            @endphp
                                            <tr>
                                                @if($loop->first && $loop->parent->first && $loop->parent->parent->first && $loop->parent->parent->parent->first)
                                                    <td rowspan="{{ $groupedData[$zoneName]->count() }}">{{ $i++ }}</td>
                                                    <td rowspan="{{ $groupedData[$zoneName]->count() }}">{{ $zoneName }}</td>
                                                @endif
                                                @if($loop->first && $loop->parent->first && $loop->parent->parent->first)
                                                    <td rowspan="{{ $woredaRows->count() }}">{{ $woredaName }}</td>
                                                @endif
                                                @if($loop->first && $loop->parent->first)
                                                    <td rowspan="{{ $kebeleRows->count() }}">{{ $kebeleName }}</td>
                                                @endif
                                                @if($loop->first)
                                                    <td rowspan="{{ $seasonRows->count() }}">{{ $seasonName }}</td>
                                                @endif
                                                <td>{{ $row->crop_name }}</td>
                                                <td class="text-center">{{ number_format($row->land,2) }}</td>
                                                <td class="text-center">{{ number_format($row->production,2) }}</td>
                                                <td class="text-center">{{ number_format($prevLand,2) }}</td>
                                                <td class="text-center">{{ number_format($prevProd,2) }}</td>
                                                <td class="text-center">{{ number_format($prodChange,1) }}%</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const zone = document.getElementById('zone');
    const woreda = document.getElementById('woreda');
    const kebele = document.getElementById('kebele');
    const year = document.getElementById('year');
    const season = document.getElementById('season');
    const croptype = document.getElementById('croptype');
    const crop = document.getElementById('crop');

    function reset(select, placeholder){
        select.innerHTML = `<option value="">${placeholder}</option>`;
        select.disabled = true;
    }

    function setOptions(select, items, placeholder){
        reset(select, placeholder);
        if(!items || !items.length) return;
        items.forEach(i=>{
            const opt = document.createElement('option');
            opt.value = i.id ?? i;
            opt.textContent = i.name ?? i;
            select.appendChild(opt);
        });
        select.disabled = false;
    }

    async function fetchData(url, params){
        const query = new URLSearchParams(params).toString();
        const res = await fetch(`${url}?${query}`, { headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'} });
        if(!res.ok) return [];
        return await res.json();
    }

    async function loadWoredas(){
        if(!zone.value) return reset(woreda,'All Woredas');
        const data = await fetchData('{{ url("admin/filter/woredas") }}',{zone_id:zone.value});
        setOptions(woreda,data,'All Woredas');
    }

    async function loadKebeles(){
        if(!woreda.value) return reset(kebele,'All Kebeles');
        const data = await fetchData('{{ url("admin/filter/kebeles") }}',{woreda_id:woreda.value});
        setOptions(kebele,data,'All Kebeles');
    }

    async function loadSeasons(){
        if(!year.value) return reset(season,'All Seasons');
        const data = await fetchData('{{ url("admin/filter/seasons") }}',{
            zone_id:zone.value,
            woreda_id:woreda.value,
            kebele_id:kebele.value,
            year:year.value
        });
        setOptions(season,data,'All Seasons');
        if("{{ request('season') }}") season.value = "{{ request('season') }}";
    }

    async function loadCropTypes(){
        if(!season.value) return reset(croptype,'All Crop Types');
        const data = await fetchData('{{ url("admin/filter/crop-types") }}',{
            zone_id:zone.value,
            woreda_id:woreda.value,
            kebele_id:kebele.value,
            year:year.value,
            season:season.value
        });
        setOptions(croptype,data,'All Crop Types');
        if("{{ request('croptype_id') }}") croptype.value = "{{ request('croptype_id') }}";
    }

    async function loadCrops(){
        if(!croptype.value) return reset(crop,'All Crops');
        const data = await fetchData('{{ url("admin/filter/crops") }}',{
            zone_id:zone.value,
            woreda_id:woreda.value,
            kebele_id:kebele.value,
            year:year.value,
            season:season.value,
            croptype_id:croptype.value
        });
        setOptions(crop,data,'All Crops');
        if("{{ request('crop_id') }}") crop.value = "{{ request('crop_id') }}";
    }

    zone?.addEventListener('change', async()=>{ await loadWoredas(); reset(kebele,'All Kebeles'); reset(season,'All Seasons'); reset(croptype,'All Crop Types'); reset(crop,'All Crops'); });
    woreda?.addEventListener('change', async()=>{ await loadKebeles(); reset(season,'All Seasons'); reset(croptype,'All Crop Types'); reset(crop,'All Crops'); });
    year?.addEventListener('change', async()=>{ await loadSeasons(); reset(croptype,'All Crop Types'); reset(crop,'All Crops'); });
    season?.addEventListener('change', async()=>{ await loadCropTypes(); reset(crop,'All Crops'); });
    croptype?.addEventListener('change', async()=>{ await loadCrops(); });

    // Initialize previous selections
    (async function init(){
        if(zone.value) await loadWoredas();
        if(woreda.value) await loadKebeles();
        if(year.value) await loadSeasons();
        if(season.value) await loadCropTypes();
        if(croptype.value) await loadCrops();

        woreda.disabled = !woreda.value;
        kebele.disabled = !kebele.value;
        season.disabled = !season.value;
        croptype.disabled = !croptype.value;
        crop.disabled = !crop.value;
    })();
});
</script>
</x-layout>
