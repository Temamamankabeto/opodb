<?php

namespace App\Http\Controllers;

use App\Models\Kebele;
use Illuminate\Http\Request;
use App\Models\Mechanization;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WoredaReportController extends Controller
{
  public function index(Request $request)
{
    $user = Auth::user();

    /* ================= YEARS ================= */
    $years = DB::table('kebele_crop_plans as kcp')
        ->join('kebeles as k', 'k.id', '=', 'kcp.kebele_id')
        ->where('k.woreda_id', $user->woreda_id)
        ->whereNotNull('kcp.fertilizer_details')
        ->distinct()
        ->orderByDesc('kcp.year')
        ->pluck('kcp.year');

    $year     = $request->year ?? $years->first();
    $prevYear = $year ? $year - 1 : null;

    $season           = $request->season;
    $fertilizerTypeId = $request->fertilizer_type_id;
    $fertilizerId     = $request->fertilizer_id;
    $kebeleId         = $request->kebele_id;

    /* ================= CURRENT YEAR ================= */
    $currentPlans = DB::table('kebele_crop_plans as kcp')
        ->join('kebeles as k', 'k.id', '=', 'kcp.kebele_id')
        ->where('k.woreda_id', $user->woreda_id)
        ->when($kebeleId, fn ($q) => $q->where('k.id', $kebeleId))
        ->where('kcp.year', $year)
        ->when($season, fn ($q) => $q->where('kcp.season', $season))
        ->whereNotNull('kcp.fertilizer_details')
        ->select('kcp.kebele_id','kcp.season','kcp.fertilizer_details')
        ->get();

    $currentYearData = [];

    foreach ($currentPlans as $plan) {
        $fertilizers = json_decode($plan->fertilizer_details, true);
        if (!is_array($fertilizers)) continue;

        foreach ($fertilizers as $f) {
            if ($fertilizerTypeId && $f['fertilizer_type_id'] != $fertilizerTypeId) continue;
            if ($fertilizerId && $f['fertilizer_id'] != $fertilizerId) continue;

            $k = $plan->kebele_id;
            $s = $plan->season;
            $fid = $f['fertilizer_id'];
            $unit = $f['unit'];

            if (!isset($currentYearData[$k])) $currentYearData[$k] = [];
            if (!isset($currentYearData[$k][$s])) $currentYearData[$k][$s] = [];
            if (!isset($currentYearData[$k][$s][$fid][$unit])) {
                $currentYearData[$k][$s][$fid][$unit] = 0;
            }

            $currentYearData[$k][$s][$fid][$unit] += (float)$f['amount'];
        }
    }

    /* ================= PREVIOUS YEAR ================= */
    $previousPlans = DB::table('kebele_crop_plans as kcp')
        ->join('kebeles as k', 'k.id', '=', 'kcp.kebele_id')
        ->where('k.woreda_id', $user->woreda_id)
        ->when($kebeleId, fn ($q) => $q->where('k.id', $kebeleId))
        ->where('kcp.year', $prevYear)
        ->whereNotNull('kcp.fertilizer_details')
        ->select('kcp.kebele_id','kcp.season','kcp.fertilizer_details')
        ->get();

    $previousYearData = [];

    foreach ($previousPlans as $plan) {
        $fertilizers = json_decode($plan->fertilizer_details, true);
        if (!is_array($fertilizers)) continue;

        foreach ($fertilizers as $f) {
            $k = $plan->kebele_id;
            $s = $plan->season;
            $fid = $f['fertilizer_id'];
            $unit = $f['unit'];

            if (!isset($previousYearData[$k])) $previousYearData[$k] = [];
            if (!isset($previousYearData[$k][$s])) $previousYearData[$k][$s] = [];
            if (!isset($previousYearData[$k][$s][$fid][$unit])) {
                $previousYearData[$k][$s][$fid][$unit] = 0;
            }

            $previousYearData[$k][$s][$fid][$unit] += (float)$f['amount'];
        }
    }

    /* ================= LOOKUPS ================= */
    $fertilizerNames = DB::table('fertilizers')->pluck('name','id');
    $fertilizerTypes = DB::table('fertilizer_types')->pluck('name','id');

    $kebeles = DB::table('kebeles')
        ->where('woreda_id', $user->woreda_id)
        ->orderBy('name')
        ->pluck('name','id');

    return view('gabaasa_torbanii.fertilizer.woreda_index', compact(
        'years','year','prevYear','currentYearData','previousYearData',
        'fertilizerNames','fertilizerTypes','kebeles','kebeleId'
    ));
}



public function indexgabaasa(Request $request)
{
    $user = Auth::user();

    // Get kebeles in this woreda for the filter
    $kebeles = DB::table('kebeles')
        ->where('woreda_id', $user->woreda_id)
        ->orderBy('name')
        ->pluck('name', 'id');

    $selectedKebeleId = $request->kebele_id;

    // Get kebele IDs for querying
    $kebeleIds = $selectedKebeleId 
        ? [$selectedKebeleId] 
        : DB::table('kebeles')
            ->where('woreda_id', $user->woreda_id)
            ->pluck('id')->toArray();

    // Available years for the woreda (from kebele_crop_plans)
    $years = DB::table('kebele_crop_plans')
        ->whereIn('kebele_id', $kebeleIds)
        ->whereNotNull('year')
        ->distinct()
        ->orderBy('year', 'desc')
        ->pluck('year');

    $year = $request->year ?? $years->first();
    $prevYear = $year ? $year - 1 : null;

    $season = $request->season;
    $croptype = $request->croptype_id;
    $crop = $request->crop_id;

    /* ================= CURRENT YEAR ================= */
   $currentYearData = DB::table('kebele_crop_plans as kcp')
    ->join('crops', 'crops.id', '=', 'kcp.crop_id')
    ->join('kebeles', 'kebeles.id', '=', 'kcp.kebele_id') // add this join
    ->select(
        'kcp.kebele_id',
        'kebeles.name as kebele_name', // add kebele name
        'kcp.crop_id',
        'kcp.season',
        'crops.name as crop_name',
        DB::raw('SUM(kcp.area_to_plant) as land'),
        DB::raw('SUM(kcp.expected_yield) as production')
    )
    ->whereIn('kcp.kebele_id', $kebeleIds)
    ->when($year, fn ($q) => $q->where('kcp.year', $year))
    ->when($season, fn ($q) => $q->where('kcp.season', $season))
    ->when($croptype, fn ($q) => $q->where('kcp.croptype_id', $croptype))
    ->when($crop, fn ($q) => $q->where('kcp.crop_id', $crop))
    ->groupBy('kcp.kebele_id','kebeles.name','kcp.crop_id', 'kcp.season', 'crops.name')
    ->orderBy('kebeles.name')
    ->orderBy('kcp.season')
    ->orderBy('crops.name')
    ->get();


    /* ================= PREVIOUS YEAR ================= */
   $previousYearData = DB::table('kebele_crop_plans')
    ->select(
        'kebele_id',
        'crop_id',
        'season',
        DB::raw('SUM(area_to_plant) as land'),
        DB::raw('SUM(expected_yield) as production')
    )
    ->whereIn('kebele_id', $kebeleIds)
    ->when($prevYear, fn ($q) => $q->where('year', $prevYear))
    ->when($season, fn ($q) => $q->where('season', $season))
    ->when($croptype, fn ($q) => $q->where('croptype_id', $croptype))
    ->when($crop, fn ($q) => $q->where('crop_id', $crop))
    ->groupBy('kebele_id','crop_id','season')
    ->get()
    ->keyBy(function($item) {
        return $item->kebele_id.'_'.$item->season.'_'.$item->crop_id;
    });


    // Get crop types and crops for filters
    $cropTypes = DB::table('crop_types')->pluck('name', 'id');
    $crops = DB::table('crops')->pluck('name', 'id');

    // Get woreda name
    $woredaName = DB::table('woredas')
        ->where('id', $user->woreda_id)
        ->value('name') ?? 'Woreda';

    return view('gabaasa_torbanii.midhan.woreda_index', compact(
        'years',
        'year',
        'prevYear',
        'currentYearData',
        'previousYearData',
        'cropTypes',
        'crops',
        'kebeles',
        'selectedKebeleId',
        'woredaName'
    ));
}

public function getKebeles(Request $request)
{
    $user = Auth::user();
    
    return DB::table('kebeles')
        ->where('woreda_id', $user->woreda_id)
        ->orderBy('name')
        ->select('id', 'name')
        ->get();
}

public function getSeason(Request $request)
{
    $user = Auth::user();
    
    // Get kebele IDs based on selection
    $kebeleIds = $request->kebele_id 
        ? [$request->kebele_id]
        : DB::table('kebeles')
            ->where('woreda_id', $user->woreda_id)
            ->pluck('id')->toArray();

    $query = DB::table('kebele_crop_plans')
        ->where('year', $request->year)
        ->whereIn('kebele_id', $kebeleIds)
        ->distinct()
        ->orderBy('season');

    return $query->pluck('season');
}

public function getCropType(Request $request)
{
    $user = Auth::user();
    
    // Get kebele IDs based on selection
    $kebeleIds = $request->kebele_id 
        ? [$request->kebele_id]
        : DB::table('kebeles')
            ->where('woreda_id', $user->woreda_id)
            ->pluck('id')->toArray();

    $query = DB::table('kebele_crop_plans as kcp')
        ->join('crop_types', 'crop_types.id', '=', 'kcp.croptype_id')
        ->where('kcp.year', $request->year)
        ->whereIn('kcp.kebele_id', $kebeleIds)
        ->distinct()
        ->orderBy('crop_types.name')
        ->select('crop_types.id', 'crop_types.name');

    if ($request->season) {
        $query->where('kcp.season', $request->season);
    }

    return $query->get();
}

public function getCrop(Request $request)
{
    $user = Auth::user();
    
    // Get kebele IDs based on selection
    $kebeleIds = $request->kebele_id 
        ? [$request->kebele_id]
        : DB::table('kebeles')
            ->where('woreda_id', $user->woreda_id)
            ->pluck('id')->toArray();

    $query = DB::table('kebele_crop_plans as kcp')
        ->join('crops', 'crops.id', '=', 'kcp.crop_id')
        ->where('kcp.year', $request->year)
        ->whereIn('kcp.kebele_id', $kebeleIds)
        ->distinct()
        ->orderBy('crops.name')
        ->select('crops.id', 'crops.name');

    if ($request->season) {
        $query->where('kcp.season', $request->season);
    }

    if ($request->croptype_id) {
        $query->where('kcp.croptype_id', $request->croptype_id);
    }

    return $query->get();
}




public function mechinization(Request $request)
    {
          $woredaId = Auth::user()->woreda_id; // Logged-in woreda admin

        // Kebele filter
        $selectedKebele = $request->kebele_id ?? null;
        $kebelesQuery = Kebele::where('woreda_id', $woredaId);
        $kebeles = $kebelesQuery->pluck('name', 'id');

        // Years for filter
        $years = Mechanization::where('woreda_id', $woredaId)
                    ->distinct()->pluck('year');

        $year = $request->year ?? Carbon::now()->year;
        $prevYear = $year - 1;

        // Current year data
        $currentYearDataQuery = Mechanization::where('woreda_id', $woredaId)
            ->where('year', $year);
        if($selectedKebele){
            $currentYearDataQuery->where('kebele_id', $selectedKebele);
        }
        $currentYearData = $currentYearDataQuery->get()->groupBy('kebele_id');

        // Previous year data
        $previousYearRaw = Mechanization::where('woreda_id', $woredaId)
            ->where('year', $prevYear)
            ->when($selectedKebele, fn($q) => $q->where('kebele_id', $selectedKebele))
            ->get();

        $previousYearData = [];
        foreach($previousYearRaw as $row){
            $previousYearData[$row->mechanization_type.'_'.$row->name.'_'.$row->kebele_id] = $row;
        }

        return view('gabaasa_torbanii.mechinization.woreda_mechanization_report', compact(
           'currentYearData', 'previousYearData', 'year', 'prevYear', 'years', 'kebeles', 'selectedKebele'
        ));
    }

}