<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\Kebele;
use App\Models\Woreda;
use Illuminate\Http\Request;
use App\Models\GabbinaBiyyee;
use App\Models\Mechanization;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RegionalReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user(); // User must belong to a region

        $year             = $request->year;
        $season           = $request->season;
        $zoneId           = $request->zone_id;
        $woredaId         = $request->woreda_id;
        $kebeleId         = $request->kebele_id;
        $fertilizerTypeId = $request->fertilizer_type_id;
        $fertilizerId     = $request->fertilizer_id;

        // ================= YEARS =================
        $years = DB::table('kebele_crop_plans as kcp')
            ->join('kebeles as k', 'k.id', '=', 'kcp.kebele_id')
            ->join('woredas as w', 'w.id', '=', 'k.woreda_id')
            ->join('zones as z', 'z.id', '=', 'w.zone_id')
            ->where('z.region_id', $user->region_id)
            ->whereNotNull('kcp.fertilizer_details')
            ->distinct()
            ->orderByDesc('kcp.year')
            ->pluck('kcp.year');

        $year     = $year ?? $years->first();
        $prevYear = $year ? $year - 1 : null;

        // ================= LOOKUPS =================
        $zones = DB::table('zones')
            ->where('region_id', $user->region_id)
            ->orderBy('name')
            ->pluck('name','id');

        $woredas = DB::table('woredas')
            ->when($zoneId, fn($q)=> $q->where('zone_id', $zoneId))
            ->orderBy('name')
            ->pluck('name','id');

        $kebeles = DB::table('kebeles')
            ->when($woredaId, fn($q)=> $q->where('woreda_id', $woredaId))
            ->orderBy('name')
            ->pluck('name','id');

        $fertilizerNames = DB::table('fertilizers')->pluck('name','id');
        $fertilizerTypes = DB::table('fertilizer_types')->pluck('name','id');

        // ================= CURRENT YEAR =================
        $currentPlans = DB::table('kebele_crop_plans as kcp')
            ->join('kebeles as k', 'k.id', '=', 'kcp.kebele_id')
            ->join('woredas as w', 'w.id', '=', 'k.woreda_id')
            ->join('zones as z', 'z.id', '=', 'w.zone_id')
            ->where('z.region_id', $user->region_id)
            ->where('kcp.year', $year)
            ->when($season, fn($q) => $q->where('kcp.season', $season))
            ->when($zoneId, fn($q) => $q->where('z.id', $zoneId))
            ->when($woredaId, fn($q) => $q->where('w.id', $woredaId))
            ->when($kebeleId, fn($q) => $q->where('k.id', $kebeleId))
            ->whereNotNull('kcp.fertilizer_details')
            ->select('z.id as zone_id','w.id as woreda_id','kcp.kebele_id','kcp.season','kcp.fertilizer_details')
            ->get();

        $currentYearData = [];

        foreach($currentPlans as $plan){
            $fertilizers = json_decode($plan->fertilizer_details,true);
            if(!is_array($fertilizers)) continue;

            foreach($fertilizers as $f){
                if($fertilizerTypeId && $f['fertilizer_type_id'] != $fertilizerTypeId) continue;
                if($fertilizerId && $f['fertilizer_id'] != $fertilizerId) continue;

                $currentYearData
                    [$plan->zone_id]
                    [$plan->woreda_id]
                    [$plan->kebele_id]
                    [$plan->season]
                    [$f['fertilizer_id']]
                    [$f['unit']] =
                    ($currentYearData
                        [$plan->zone_id]
                        [$plan->woreda_id]
                        [$plan->kebele_id]
                        [$plan->season]
                        [$f['fertilizer_id']]
                        [$f['unit']] ?? 0)
                    + (float)$f['amount'];
            }
        }

        // ================= PREVIOUS YEAR =================
        $previousPlans = DB::table('kebele_crop_plans as kcp')
            ->join('kebeles as k', 'k.id', '=', 'kcp.kebele_id')
            ->join('woredas as w', 'w.id', '=', 'k.woreda_id')
            ->join('zones as z', 'z.id', '=', 'w.zone_id')
            ->where('z.region_id', $user->region_id)
            ->where('kcp.year', $prevYear)
            ->when($season, fn($q)=> $q->where('kcp.season', $season))
            ->when($zoneId, fn($q) => $q->where('z.id', $zoneId))
            ->when($woredaId, fn($q) => $q->where('w.id', $woredaId))
            ->when($kebeleId, fn($q) => $q->where('k.id', $kebeleId))
            ->whereNotNull('kcp.fertilizer_details')
            ->select('z.id as zone_id','w.id as woreda_id','kcp.kebele_id','kcp.season','kcp.fertilizer_details')
            ->get();

        $previousYearData = [];

        foreach($previousPlans as $plan){
            $fertilizers = json_decode($plan->fertilizer_details,true);
            if(!is_array($fertilizers)) continue;

            foreach($fertilizers as $f){
                $previousYearData
                    [$plan->zone_id]
                    [$plan->woreda_id]
                    [$plan->kebele_id]
                    [$plan->season]
                    [$f['fertilizer_id']]
                    [$f['unit']] =
                    ($previousYearData
                        [$plan->zone_id]
                        [$plan->woreda_id]
                        [$plan->kebele_id]
                        [$plan->season]
                        [$f['fertilizer_id']]
                        [$f['unit']] ?? 0)
                    + (float)$f['amount'];
            }
        }

        return view('gabaasa_torbanii.fertilizer.region_index', compact(
            'years','year','prevYear',
            'currentYearData','previousYearData',
            'fertilizerNames','fertilizerTypes',
            'zones','woredas','kebeles'
        ));
    }

    // ================= AJAX =================
    public function woredasByZone($zoneId)
    {
        return DB::table('woredas')
            ->where('zone_id', $zoneId)
            ->orderBy('name')
            ->get(['id','name']);
    }

    public function kebelesByWoreda($woredaId)
    {
        return DB::table('kebeles')
            ->where('woreda_id', $woredaId)
            ->orderBy('name')
            ->get(['id','name']);
    }



     public function crop(Request $request)
    {
        $user = Auth::user();
        $regionId = $user->region_id;

        // Get zones in region
        $zones = DB::table('zones')->where('region_id', $regionId)->orderBy('name')->pluck('name','id');
        $selectedZone = $request->zone_id;
        $zoneIds = $selectedZone ? [$selectedZone] : DB::table('zones')->where('region_id', $regionId)->pluck('id')->toArray();

        // Woredas in zones
        $woredas = DB::table('woredas')->whereIn('zone_id',$zoneIds)->orderBy('name')->pluck('name','id');
        $selectedWoreda = $request->woreda_id;
        $woredaIds = $selectedWoreda ? [$selectedWoreda] : $woredas->keys()->toArray();

        // Year selection (independent)
        $years = DB::table('kebele_crop_plans')
            ->whereIn('kebele_id', DB::table('kebeles')->whereIn('woreda_id',$woredaIds)->pluck('id'))
            ->whereNotNull('year')->distinct()->orderByDesc('year')->pluck('year');
        $year = $request->year ?? $years->first();
        $prevYear = $year ? $year - 1 : null;

        $season = $request->season;
        $croptype = $request->croptype_id;
        $crop = $request->crop_id;
        $kebele = $request->kebele_id;

        // Current Year Data
        $currentYearData = DB::table('kebele_crop_plans as kcp')
            ->join('crops','crops.id','=','kcp.crop_id')
            ->join('kebeles','kebeles.id','=','kcp.kebele_id')
            ->join('woredas','woredas.id','=','kebeles.woreda_id')
            ->join('zones','zones.id','=','woredas.zone_id')
            ->select(
                'zones.id as zone_id','zones.name as zone_name',
                'woredas.id as woreda_id','woredas.name as woreda_name',
                'kebeles.id as kebele_id','kebeles.name as kebele_name',
                'kcp.season','kcp.crop_id','crops.name as crop_name',
                DB::raw('SUM(kcp.area_to_plant) as land'),
                DB::raw('SUM(kcp.expected_yield) as production')
            )
            ->whereIn('zones.id', $zoneIds)
            ->when($woredaIds, fn($q)=> $q->whereIn('woredas.id', $woredaIds))
            ->when($year, fn($q)=> $q->where('kcp.year',$year))
            ->when($season, fn($q)=> $q->where('kcp.season',$season))
            ->when($croptype, fn($q)=> $q->where('kcp.croptype_id',$croptype))
            ->when($crop, fn($q)=> $q->where('kcp.crop_id',$crop))
            ->when($kebele, fn($q)=> $q->where('kebeles.id',$kebele))
            ->groupBy('zones.id','zones.name','woredas.id','woredas.name','kebeles.id','kebeles.name','kcp.season','kcp.crop_id','crops.name')
            ->orderBy('zones.name')->orderBy('woredas.name')->orderBy('kebeles.name')->orderBy('kcp.season')->orderBy('crops.name')
            ->get();

        // Previous Year Data
        $previousYearData = DB::table('kebele_crop_plans as kcp')
            ->join('kebeles','kebeles.id','=','kcp.kebele_id')
            ->join('woredas','woredas.id','=','kebeles.woreda_id')
            ->join('zones','zones.id','=','woredas.zone_id')
            ->select(
                'zones.id as zone_id','zones.name as zone_name',
                'woredas.id as woreda_id','woredas.name as woreda_name',
                'kebeles.id as kebele_id','kebeles.name as kebele_name',
                'kcp.season','kcp.crop_id',
                DB::raw('SUM(kcp.area_to_plant) as land'),
                DB::raw('SUM(kcp.expected_yield) as production')
            )
            ->whereIn('zones.id',$zoneIds)
            ->when($woredaIds, fn($q)=> $q->whereIn('woredas.id', $woredaIds))
            ->when($prevYear, fn($q)=> $q->where('kcp.year',$prevYear))
            ->when($season, fn($q)=> $q->where('kcp.season',$season))
            ->when($croptype, fn($q)=> $q->where('kcp.croptype_id',$croptype))
            ->when($crop, fn($q)=> $q->where('kcp.crop_id',$crop))
            ->when($kebele, fn($q)=> $q->where('kebeles.id',$kebele))
            ->groupBy('zones.id','zones.name','woredas.id','woredas.name','kebeles.id','kebeles.name','kcp.season','kcp.crop_id')
            ->get()
            ->keyBy(fn($item)=> $item->zone_id.'_'.$item->woreda_id.'_'.$item->kebele_id.'_'.$item->season.'_'.$item->crop_id);

        $regionName = DB::table('regions')->where('id',$regionId)->value('name') ?? 'Region';

        return view('gabaasa_torbanii.midhan.regional_index', compact(
            'zones','selectedZone','woredas','selectedWoreda','years','year','prevYear',
            'season','croptype','crop','kebele','currentYearData','previousYearData','regionName'
        ));
    }

    // AJAX functions for cascading filters
    public function getZones(Request $request){
        $regionId = Auth::user()->region_id;
        return response()->json(DB::table('zones')->where('region_id',$regionId)->orderBy('name')->get(['id','name']));
    }

    public function getWoredas(Request $request){
        $query = DB::table('woredas')->select('id','name');
        if($request->zone_id) $query->where('zone_id',$request->zone_id);
        return response()->json($query->orderBy('name')->get());
    }

    public function getKebeles(Request $request){
        $query = DB::table('kebeles')->select('id','name');
        if($request->woreda_id) $query->where('woreda_id',$request->woreda_id);
        return response()->json($query->orderBy('name')->get());
    }

    public function getYears(Request $request){
        $query = DB::table('kebele_crop_plans')->select('year')->distinct()->orderByDesc('year');
        return response()->json($query->pluck('year'));
    }

    public function getSeasons(Request $request){
        $query = DB::table('kebele_crop_plans')->select('season')->distinct();
        if($request->woreda_id){
            $kebeleIds = $request->kebele_id ? [$request->kebele_id] : DB::table('kebeles')->where('woreda_id',$request->woreda_id)->pluck('id')->toArray();
            $query->whereIn('kebele_id',$kebeleIds);
        }
        if($request->year) $query->where('year',$request->year);
        return response()->json($query->orderBy('season')->pluck('season'));
    }

    public function getCropTypes(Request $request){
        $query = DB::table('kebele_crop_plans as kcp')
            ->join('crop_types','crop_types.id','=','kcp.croptype_id')
            ->select('crop_types.id','crop_types.name')->distinct();
        if($request->woreda_id){
            $kebeleIds = $request->kebele_id ? [$request->kebele_id] : DB::table('kebeles')->where('woreda_id',$request->woreda_id)->pluck('id')->toArray();
            $query->whereIn('kcp.kebele_id',$kebeleIds);
        }
        if($request->year) $query->where('kcp.year',$request->year);
        if($request->season) $query->where('kcp.season',$request->season);
        return response()->json($query->orderBy('crop_types.name')->get());
    }

    public function getCrops(Request $request){
        $query = DB::table('kebele_crop_plans as kcp')
            ->join('crops','crops.id','=','kcp.crop_id')
            ->select('crops.id','crops.name')->distinct();
        if($request->woreda_id){
            $kebeleIds = $request->kebele_id ? [$request->kebele_id] : DB::table('kebeles')->where('woreda_id',$request->woreda_id)->pluck('id')->toArray();
            $query->whereIn('kcp.kebele_id',$kebeleIds);
        }
        if($request->year) $query->where('kcp.year',$request->year);
        if($request->season) $query->where('kcp.season',$request->season);
        if($request->croptype_id) $query->where('kcp.croptype_id',$request->croptype_id);
        return response()->json($query->orderBy('crops.name')->get());
    }



    public function mechinization(Request $request)
{
    // Years available in the Mechanization table
    $years = Mechanization::distinct()->orderBy('year', 'desc')->pluck('year');
    
    $year = $request->year ?? $years->first(); // Default to latest year
    $prevYear = $year - 1;

    // Filters
    $selectedZone = $request->zone_id;
    $selectedWoreda = $request->woreda_id;
    $selectedKebele = $request->kebele_id;

    $zones = Zone::pluck('name','id');

    // Woredas filtered by selected zone
    $woredas = collect();
    if($selectedZone){
        $woredas = Woreda::where('zone_id', $selectedZone)->pluck('name','id');
    }

    // Kebeles filtered by selected woreda
    $kebeles = collect();
    if($selectedWoreda){
        $kebeles = Kebele::where('woreda_id', $selectedWoreda)->pluck('name','id');
    }

    // Current Year Data
    $currentYearData = Mechanization::with('woreda')
        ->when($selectedZone, fn($q) => $q->whereHas('woreda', fn($q2) => $q2->where('zone_id', $selectedZone)))
        ->when($selectedWoreda, fn($q) => $q->where('woreda_id', $selectedWoreda))
        ->when($selectedKebele, fn($q) => $q->where('kebele_id', $selectedKebele))
        ->where('year', $year)
        ->get()
        ->groupBy('woreda.zone_id'); // group by zone for regional report

    // Previous Year Data
    $previousYearData = Mechanization::where('year', $prevYear)->get()
        ->keyBy(fn($item) => $item->mechanization_type.'_'.$item->name.'_'.$item->woreda_id);

    return view('gabaasa_torbanii.mechinization.regional_index', compact(
        'years','year','prevYear','zones','woredas','kebeles',
        'selectedZone','selectedWoreda','selectedKebele',
        'currentYearData','previousYearData'
    ));
}


    // AJAX endpoint to fetch woredas based on zone
    public function getWoreda(Request $request)
    {
        $zoneId = $request->zone_id;
        $woredas = Woreda::where('zone_id', $zoneId)->pluck('name','id');
        return response()->json($woredas);
    }

    // AJAX endpoint to fetch kebeles based on woreda
    public function getKebele(Request $request)
    {
        $woredaId = $request->woreda_id;
        $kebeles = Kebele::where('woreda_id', $woredaId)->pluck('name','id');
        return response()->json($kebeles);
    }



    public function compost(Request $request)
    {
        // Distinct years
        $years = GabbinaBiyyee::select('year')->distinct()->orderBy('year','desc')->pluck('year');

        $selectedYear = $request->year ?? $years->first();

        // Zones for filter
        $zones = Zone::pluck('name','id');
        $selectedZone = $request->zone_id;

        // Current year data
        $query = GabbinaBiyyee::where('year', $selectedYear);
        if ($selectedZone) $query->where('zone_id', $selectedZone);
        $regionalDataRaw = $query->orderBy('zone_id')->orderBy('woreda_id')->orderBy('kebele_id')->get();

        // Previous year data
        $prevYear = $selectedYear - 1;
        $prevDataRaw = GabbinaBiyyee::where('year', $prevYear)->get();

        // Group by Zone → Woreda → Kebele → Compost Type
        $regionalData = [];
        foreach ($regionalDataRaw as $row) {
            $regionalData[$row->zone_id][$row->woreda_id][$row->kebele_id][] = $row;
        }

        // Previous year lookup
        $previousYearData = [];
        foreach ($prevDataRaw as $row) {
            $previousYearData[$row->zone_id][$row->woreda_id][$row->kebele_id][$row->compost_type] = $row;
        }

        return view('gabaasa_torbanii.compost.regional_report', compact(
            'years',
            'selectedYear',
            'prevYear',
            'zones',
            'selectedZone',
            'regionalData',
            'previousYearData'
        ));
    }
}
