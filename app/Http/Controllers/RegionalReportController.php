<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}
