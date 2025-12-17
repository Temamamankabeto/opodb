<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\Kebele;
use App\Models\Woreda;
use Illuminate\Http\Request;
use App\Models\GabbinaBiyyee;
use App\Models\Mechanization;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ZonalReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $year             = $request->year;
        $season           = $request->season;
        $woredaId         = $request->woreda_id;
        $kebeleId         = $request->kebele_id;
        $fertilizerTypeId = $request->fertilizer_type_id;
        $fertilizerId     = $request->fertilizer_id;

        // ================= YEARS =================
        $years = DB::table('kebele_crop_plans as kcp')
            ->join('kebeles as k', 'k.id', '=', 'kcp.kebele_id')
            ->join('woredas as w', 'w.id', '=', 'k.woreda_id')
            ->where('w.zone_id', $user->zone_id)
            ->whereNotNull('kcp.fertilizer_details')
            ->distinct()
            ->orderByDesc('kcp.year')
            ->pluck('kcp.year');

        $year     = $year ?? $years->first();
        $prevYear = $year ? $year - 1 : null;

        // ================= LOOKUPS =================
        $woredas = DB::table('woredas')
            ->where('zone_id', $user->zone_id)
            ->orderBy('name')
            ->pluck('name','id');

        $kebeles = DB::table('kebeles')
            ->join('woredas as w', 'w.id', '=', 'kebeles.woreda_id')
            ->where('w.zone_id', $user->zone_id)
            ->when($woredaId, fn($q)=> $q->where('w.id', $woredaId))
            ->orderBy('kebeles.name')
            ->pluck('kebeles.name','kebeles.id');

        $fertilizerNames = DB::table('fertilizers')->pluck('name','id');
        $fertilizerTypes = DB::table('fertilizer_types')->pluck('name','id');

        // ================= CURRENT YEAR =================
        $currentPlans = DB::table('kebele_crop_plans as kcp')
            ->join('kebeles as k', 'k.id', '=', 'kcp.kebele_id')
            ->join('woredas as w', 'w.id', '=', 'k.woreda_id')
            ->where('w.zone_id', $user->zone_id)
            ->where('kcp.year', $year)
            ->when($season, fn($q) => $q->where('kcp.season', $season))
            ->when($woredaId, fn($q) => $q->where('w.id', $woredaId))
            ->when($kebeleId, fn($q) => $q->where('k.id', $kebeleId))
            ->whereNotNull('kcp.fertilizer_details')
            ->select('w.id as woreda_id', 'kcp.kebele_id', 'kcp.season', 'kcp.fertilizer_details')
            ->get();

        $currentYearData = [];

        foreach($currentPlans as $plan){
            $fertilizers = json_decode($plan->fertilizer_details,true);
            if(!is_array($fertilizers)) continue;

            foreach($fertilizers as $f){
                if($fertilizerTypeId && $f['fertilizer_type_id'] != $fertilizerTypeId) continue;
                if($fertilizerId && $f['fertilizer_id'] != $fertilizerId) continue;

                $currentYearData
                    [$plan->woreda_id]
                    [$plan->kebele_id]
                    [$plan->season]
                    [$f['fertilizer_id']]
                    [$f['unit']] =
                    ($currentYearData
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
            ->where('w.zone_id', $user->zone_id)
            ->where('kcp.year', $prevYear)
            ->when($season, fn($q)=> $q->where('kcp.season', $season))
            ->when($woredaId, fn($q)=> $q->where('w.id', $woredaId))
            ->when($kebeleId, fn($q)=> $q->where('k.id', $kebeleId))
            ->whereNotNull('kcp.fertilizer_details')
            ->select('w.id as woreda_id','kcp.kebele_id','kcp.season','kcp.fertilizer_details')
            ->get();

        $previousYearData = [];

        foreach($previousPlans as $plan){
            $fertilizers = json_decode($plan->fertilizer_details,true);
            if(!is_array($fertilizers)) continue;

            foreach($fertilizers as $f){
                $previousYearData
                    [$plan->woreda_id]
                    [$plan->kebele_id]
                    [$plan->season]
                    [$f['fertilizer_id']]
                    [$f['unit']] =
                    ($previousYearData
                        [$plan->woreda_id]
                        [$plan->kebele_id]
                        [$plan->season]
                        [$f['fertilizer_id']]
                        [$f['unit']] ?? 0)
                    + (float)$f['amount'];
            }
        }

        return view('gabaasa_torbanii.fertilizer.zone_index', compact(
            'years','year','prevYear',
            'currentYearData','previousYearData',
            'fertilizerNames','fertilizerTypes',
            'woredas','kebeles'
        ));
    }

    // ================= AJAX =================
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
        $zoneId = $user->zone_id;

        // Woredas in the zone
        $woredas = DB::table('woredas')
            ->where('zone_id', $zoneId)
            ->orderBy('name')
            ->pluck('name', 'id');

        $selectedWoreda = $request->woreda_id;
        $woredaIds = $selectedWoreda 
            ? [$selectedWoreda] 
            : DB::table('woredas')->where('zone_id', $zoneId)->pluck('id')->toArray();

        // Year selection (independent)
        $years = DB::table('kebele_crop_plans')
            ->whereIn('kebele_id', DB::table('kebeles')->whereIn('woreda_id', $woredaIds)->pluck('id'))
            ->whereNotNull('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $year = $request->year ?? $years->first();
        $prevYear = $year ? $year - 1 : null;

        $season = $request->season;
        $croptype = $request->croptype_id;
        $crop = $request->crop_id;
        $kebele = $request->kebele_id;

        // Current Year Data
        $currentYearData = DB::table('kebele_crop_plans as kcp')
            ->join('crops', 'crops.id', '=', 'kcp.crop_id')
            ->join('kebeles', 'kebeles.id', '=', 'kcp.kebele_id')
            ->join('woredas', 'woredas.id', '=', 'kebeles.woreda_id')
            ->select(
                'woredas.id as woreda_id',
                'woredas.name as woreda_name',
                'kebeles.id as kebele_id',
                'kebeles.name as kebele_name',
                'kcp.season',
                'kcp.crop_id',
                'crops.name as crop_name',
                DB::raw('SUM(kcp.area_to_plant) as land'),
                DB::raw('SUM(kcp.expected_yield) as production')
            )
            ->whereIn('woredas.id', $woredaIds)
            ->when($year, fn($q) => $q->where('kcp.year', $year))
            ->when($season, fn($q) => $q->where('kcp.season', $season))
            ->when($croptype, fn($q) => $q->where('kcp.croptype_id', $croptype))
            ->when($crop, fn($q) => $q->where('kcp.crop_id', $crop))
            ->when($kebele, fn($q) => $q->where('kebeles.id', $kebele))
            ->groupBy('woredas.id','woredas.name','kebeles.id','kebeles.name','kcp.season','kcp.crop_id','crops.name')
            ->orderBy('woredas.name')
            ->orderBy('kebeles.name')
            ->orderBy('kcp.season')
            ->orderBy('crops.name')
            ->get();

        // Previous Year Data
        $previousYearData = DB::table('kebele_crop_plans as kcp')
            ->join('kebeles', 'kebeles.id', '=', 'kcp.kebele_id')
            ->join('woredas', 'woredas.id', '=', 'kebeles.woreda_id')
            ->select(
                'woredas.id as woreda_id',
                'woredas.name as woreda_name',
                'kebeles.id as kebele_id',
                'kebeles.name as kebele_name',
                'kcp.season',
                'kcp.crop_id',
                DB::raw('SUM(kcp.area_to_plant) as land'),
                DB::raw('SUM(kcp.expected_yield) as production')
            )
            ->whereIn('woredas.id', $woredaIds)
            ->when($prevYear, fn($q) => $q->where('kcp.year', $prevYear))
            ->when($season, fn($q) => $q->where('kcp.season', $season))
            ->when($croptype, fn($q) => $q->where('kcp.croptype_id', $croptype))
            ->when($crop, fn($q) => $q->where('kcp.crop_id', $crop))
            ->when($kebele, fn($q) => $q->where('kebeles.id', $kebele))
            ->groupBy('woredas.id','woredas.name','kebeles.id','kebeles.name','kcp.season','kcp.crop_id')
            ->get()
            ->keyBy(fn($item) => $item->woreda_id.'_'.$item->kebele_id.'_'.$item->season.'_'.$item->crop_id);

        $zoneName = DB::table('zones')->where('id', $zoneId)->value('name') ?? 'Zone';

        return view('gabaasa_torbanii.midhan.zonal_index', compact(
            'woredas','selectedWoreda','years','year','prevYear',
            'season','croptype','crop','kebele','currentYearData','previousYearData','zoneName'
        ));
    }

    // AJAX: Get Kebeles
    public function getKebeles(Request $request)
    {
        $query = DB::table('kebeles')->select('id','name');
        if($request->woreda_id) $query->where('woreda_id', $request->woreda_id);
        return response()->json($query->orderBy('name')->get());
    }

    // AJAX: Get Years
    public function getYears(Request $request)
    {
        $query = DB::table('kebele_crop_plans')->select('year')->distinct();
        return response()->json($query->orderByDesc('year')->pluck('year'));
    }

    // AJAX: Get Seasons
    public function getSeasons(Request $request)
    {
        $query = DB::table('kebele_crop_plans')->select('season')->distinct();
        if($request->woreda_id){
            $kebeleIds = $request->kebele_id ? [$request->kebele_id] : DB::table('kebeles')->where('woreda_id',$request->woreda_id)->pluck('id')->toArray();
            $query->whereIn('kebele_id',$kebeleIds);
        }
        if($request->year) $query->where('year', $request->year);
        return response()->json($query->orderBy('season')->pluck('season'));
    }

    // AJAX: Get Crop Types
    public function getCropTypes(Request $request)
    {
        $query = DB::table('kebele_crop_plans as kcp')
            ->join('crop_types','crop_types.id','=','kcp.croptype_id')
            ->select('crop_types.id','crop_types.name')
            ->distinct();

        if($request->woreda_id){
            $kebeleIds = $request->kebele_id ? [$request->kebele_id] : DB::table('kebeles')->where('woreda_id',$request->woreda_id)->pluck('id')->toArray();
            $query->whereIn('kcp.kebele_id',$kebeleIds);
        }
        if($request->year) $query->where('kcp.year',$request->year);
        if($request->season) $query->where('kcp.season',$request->season);

        return response()->json($query->orderBy('crop_types.name')->get());
    }

    // AJAX: Get Crops
    public function getCrops(Request $request)
    {
        $query = DB::table('kebele_crop_plans as kcp')
            ->join('crops','crops.id','=','kcp.crop_id')
            ->select('crops.id','crops.name')
            ->distinct();

        if($request->woreda_id){
            $kebeleIds = $request->kebele_id ? [$request->kebele_id] : DB::table('kebeles')->where('woreda_id',$request->woreda_id)->pluck('id')->toArray();
            $query->whereIn('kcp.kebele_id',$kebeleIds);
        }
        if($request->year) $query->where('kcp.year',$request->year);
        if($request->season) $query->where('kcp.season',$request->season);
        if($request->croptype_id) $query->where('kcp.croptype_id',$request->croptype_id);

        return response()->json($query->orderBy('crops.name')->get());
    }



    public function zoneMechanization(Request $request)
{
    $zoneId = Auth::user()->zone_id; // Logged-in zone admin

    // Woreda filter
    $woredas = Woreda::where('zone_id', $zoneId)->pluck('name','id');
    $selectedWoreda = $request->woreda_id ?? null;

    // Kebele filter (depends on selected woreda)
    $kebeles = collect();
    if($selectedWoreda){
        $kebeles = Kebele::where('woreda_id', $selectedWoreda)->pluck('name','id');
    }
    $selectedKebele = $request->kebele_id ?? null;

    // Year filter
    $years = Mechanization::where('zone_id', $zoneId)->distinct()->pluck('year');
    $year = $request->year ?? Carbon::now()->year;
    $prevYear = $year - 1;

    // Current year data
    $currentYearDataQuery = Mechanization::where('zone_id', $zoneId)
        ->where('year', $year);

    if($selectedWoreda) $currentYearDataQuery->where('woreda_id', $selectedWoreda);
    if($selectedKebele) $currentYearDataQuery->where('kebele_id', $selectedKebele);

    $currentYearData = $currentYearDataQuery->get()->groupBy('woreda_id');

    // Previous year data
    $previousYearRaw = Mechanization::where('zone_id', $zoneId)
        ->where('year', $prevYear)
        ->when($selectedWoreda, fn($q) => $q->where('woreda_id', $selectedWoreda))
        ->when($selectedKebele, fn($q) => $q->where('kebele_id', $selectedKebele))
        ->get();

    $previousYearData = [];
    foreach($previousYearRaw as $row){
        $previousYearData[$row->mechanization_type.'_'.$row->name.'_'.$row->kebele_id] = $row;
    }

    return view('gabaasa_torbanii.mechinization.mechinization_zone_report', compact(
        'woredas','kebeles','selectedWoreda','selectedKebele',
        'years','year','prevYear','currentYearData','previousYearData'
    ));
}


public function compost(Request $request)
    {
       // Distinct years from compost table
        $years = GabbinaBiyyee::select('year')->distinct()->orderBy('year','desc')->pluck('year');

        // Selected filters
        $selectedYear = $request->year ?? $years->first();
        $selectedWoreda = $request->woreda_id;

        // Woredas list for filter
        $woredas = Woreda::pluck('name','id');

        // Current year data
        $query = GabbinaBiyyee::where('year', $selectedYear);
        if ($selectedWoreda) $query->where('woreda_id', $selectedWoreda);
        $zonalDataRaw = $query->orderBy('zone_id')->orderBy('woreda_id')->orderBy('kebele_id')->get();

        // Previous year
        $prevYear = $selectedYear - 1;
        $prevDataRaw = GabbinaBiyyee::where('year', $prevYear)->get();

        // Group data: woreda -> kebele -> compost type
        $zonalData = [];
        foreach ($zonalDataRaw as $row) {
            $zonalData[$row->woreda_id][$row->kebele_id][] = $row;
        }

        // Previous year grouped for lookup
        $previousYearData = [];
        foreach ($prevDataRaw as $row) {
            $previousYearData[$row->woreda_id][$row->kebele_id][$row->compost_type] = $row;
        }
        return view('gabaasa_torbanii.compost.zonal_report', compact(
           'years',
            'selectedYear',
            'prevYear',
            'woredas',
            'selectedWoreda',
            'zonalData',
            'previousYearData'
        ));
    }
    
}
