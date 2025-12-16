<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mechanization;
use App\Models\KebeleCropPlan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KaroraController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Initialize empty collections
        $cropPlans = collect();
        $years = collect();
        $seasons = collect();
        $crops = collect();
        $selectedYear = null;
        $selectedSeason = null;
        $selectedCrop = null;
        
        // Only fetch data if at least year is selected
        if (request()->has('year') && request('year') != '') {
            $selectedYear = request('year');
            
            // Get years for dropdown (all available years)
            $years = KebeleCropPlan::where('kebele_id', $user->kebele_id)
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year');
            
            // Start building query
            $cropPlansQuery = KebeleCropPlan::with(['cropType', 'crop'])
                ->where('kebele_id', $user->kebele_id)
                ->where('year', $selectedYear);
            
            // Get seasons based on selected year
            $seasons = KebeleCropPlan::where('kebele_id', $user->kebele_id)
                ->where('year', $selectedYear)
                ->distinct()
                ->orderBy('season')
                ->pluck('season');
            
            // Apply season filter if selected
            if (request()->has('season') && request('season') != '') {
                $selectedSeason = request('season');
                $cropPlansQuery->where('season', $selectedSeason);
                
                // Get crops based on selected year and season
                $crops = KebeleCropPlan::with('crop')
                    ->where('kebele_id', $user->kebele_id)
                    ->where('year', $selectedYear)
                    ->where('season', $selectedSeason)
                    ->get()
                    ->pluck('crop.name', 'crop.id')
                    ->unique()
                    ->sort();
                
                // Apply crop filter if selected
                if (request()->has('crop') && request('crop') != '') {
                    $selectedCrop = request('crop');
                    $cropPlansQuery->where('crop_id', $selectedCrop);
                }
            }
            
            // Get filtered crop plans
            $cropPlans = $cropPlansQuery
                ->orderBy('year', 'desc')
                ->orderBy('season')
                ->orderBy('croptype_id')
                ->get();
        } else {
            // If no year selected, only fetch years for dropdown
            $years = KebeleCropPlan::where('kebele_id', $user->kebele_id)
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year');
        }
        
        // Calculate totals only if we have data
        $grandTotalArea = $cropPlans->sum('area_to_plant');
        $grandTotalYield = $cropPlans->sum('expected_yield');
        
        // Calculate yearly totals
        $yearlyTotals = [];
        if ($cropPlans->isNotEmpty()) {
            foreach ($cropPlans->groupBy('year') as $year => $plansByYear) {
                $yearlyTotals[$year] = [
                    'total_area' => $plansByYear->sum('area_to_plant'),
                    'total_yield' => $plansByYear->sum('expected_yield'),
                ];
            }
        }
        
        // Get crop types
        $cropTypes = \App\Models\CropType::all();
        
        return view('karoora_torbanii.midhan.index', compact(
            'cropPlans',
            'yearlyTotals',
            'grandTotalArea',
            'grandTotalYield',
            'cropTypes',
            'years',
            'seasons',
            'crops',
            'selectedYear',
            'selectedSeason',
            'selectedCrop'
        ));
    }



     public function indexrawwii()
    {
        $user = Auth::user();
        
        // Initialize empty collections
        $cropPlans = collect();
        $years = collect();
        $seasons = collect();
        $crops = collect();
        $selectedYear = null;
        $selectedSeason = null;
        $selectedCrop = null;
        
        // Only fetch data if at least year is selected
        if (request()->has('year') && request('year') != '') {
            $selectedYear = request('year');
            
            // Get years for dropdown (all available years)
            $years = KebeleCropPlan::where('kebele_id', $user->kebele_id)
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year');
            
            // Start building query
            $cropPlansQuery = KebeleCropPlan::with(['cropType', 'crop'])
                ->where('kebele_id', $user->kebele_id)
                ->where('year', $selectedYear);
            
            // Get seasons based on selected year
            $seasons = KebeleCropPlan::where('kebele_id', $user->kebele_id)
                ->where('year', $selectedYear)
                ->distinct()
                ->orderBy('season')
                ->pluck('season');
            
            // Apply season filter if selected
            if (request()->has('season') && request('season') != '') {
                $selectedSeason = request('season');
                $cropPlansQuery->where('season', $selectedSeason);
                
                // Get crops based on selected year and season
                $crops = KebeleCropPlan::with('crop')
                    ->where('kebele_id', $user->kebele_id)
                    ->where('year', $selectedYear)
                    ->where('season', $selectedSeason)
                    ->get()
                    ->pluck('crop.name', 'crop.id')
                    ->unique()
                    ->sort();
                
                // Apply crop filter if selected
                if (request()->has('crop') && request('crop') != '') {
                    $selectedCrop = request('crop');
                    $cropPlansQuery->where('crop_id', $selectedCrop);
                }
            }
            
            // Get filtered crop plans
            $cropPlans = $cropPlansQuery
                ->orderBy('year', 'desc')
                ->orderBy('season')
                ->orderBy('croptype_id')
                ->get();
        } else {
            // If no year selected, only fetch years for dropdown
            $years = KebeleCropPlan::where('kebele_id', $user->kebele_id)
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year');
        }
        
        // Calculate totals only if we have data
        $grandTotalArea = $cropPlans->sum('area_to_plant');
        $grandTotalYield = $cropPlans->sum('expected_yield');
        
        // Calculate yearly totals
        $yearlyTotals = [];
        if ($cropPlans->isNotEmpty()) {
            foreach ($cropPlans->groupBy('year') as $year => $plansByYear) {
                $yearlyTotals[$year] = [
                    'total_area' => $plansByYear->sum('area_to_plant'),
                    'total_yield' => $plansByYear->sum('expected_yield'),
                ];
            }
        }
        
        // Get crop types
        $cropTypes = \App\Models\CropType::all();
        
        return view('raawwii_torbanii.midhan.index', compact(
            'cropPlans',
            'yearlyTotals',
            'grandTotalArea',
            'grandTotalYield',
            'cropTypes',
            'years',
            'seasons',
            'crops',
            'selectedYear',
            'selectedSeason',
            'selectedCrop'
        ));
    }
    
    public function getSeasons(Request $request)
    {
        $user = Auth::user();
        $year = $request->get('year');
        
        if (!$year) {
            return response()->json([]);
        }
        
        $seasons = KebeleCropPlan::where('kebele_id', $user->kebele_id)
            ->where('year', $year)
            ->distinct()
            ->orderBy('season')
            ->pluck('season');
            
        return response()->json($seasons);
    }
    
    public function getCrops(Request $request)
    {
        $user = Auth::user();
        $year = $request->get('year');
        $season = $request->get('season');
        
        if (!$year || !$season) {
            return response()->json([]);
        }
        
        $crops = KebeleCropPlan::with('crop')
            ->where('kebele_id', $user->kebele_id)
            ->where('year', $year)
            ->where('season', $season)
            ->get()
            ->pluck('crop.name', 'crop.id')
            ->unique()
            ->sort()
            ->toArray();
            
        return response()->json($crops);
    }
// indexgabaasa 
   public function indexgabaasa(Request $request)
{
    $user = Auth::user();

    $years = DB::table('kebele_crop_plans')
        ->where('kebele_id', $user->kebele_id)
        ->whereNotNull('year')
        ->distinct()
        ->orderBy('year', 'desc')
        ->pluck('year');

    $year     = $request->year ?? $years->first();
    $prevYear = $year ? $year - 1 : null;

    $season   = $request->season;
    $croptype = $request->croptype_id;
    $crop     = $request->crop_id;

    // Current Year Data
    $currentYearData = DB::table('kebele_crop_plans as kcp')
        ->join('crops', 'crops.id', '=', 'kcp.crop_id')
        ->select(
            'kcp.crop_id',
            'kcp.season',
            'crops.name as crop_name',
            DB::raw('SUM(kcp.area_to_plant) as land'),
            DB::raw('SUM(kcp.expected_yield) as production')
        )
        ->where('kcp.kebele_id', $user->kebele_id)
        ->when($year, fn($q) => $q->where('kcp.year', $year))
        ->when($season, fn($q) => $q->where('kcp.season', $season))
        ->when($croptype, fn($q) => $q->where('kcp.croptype_id', $croptype))
        ->when($crop, fn($q) => $q->where('kcp.crop_id', $crop))
        ->groupBy('kcp.crop_id', 'kcp.season', 'crops.name')
        ->get()
        ->keyBy('crop_id');

    // Previous Year Data
    $previousYearData = DB::table('kebele_crop_plans')
        ->select(
            'crop_id',
            DB::raw('SUM(area_to_plant) as land'),
            DB::raw('SUM(expected_yield) as production')
        )
        ->where('kebele_id', $user->kebele_id)
        ->when($prevYear, fn($q) => $q->where('year', $prevYear))
        ->when($season, fn($q) => $q->where('season', $season))
        ->when($croptype, fn($q) => $q->where('croptype_id', $croptype))
        ->when($crop, fn($q) => $q->where('crop_id', $crop))
        ->groupBy('crop_id')
        ->get()
        ->keyBy('crop_id');

    return view('gabaasa_torbanii.midhan.index', compact(
        'years', 'year', 'prevYear', 'currentYearData', 'previousYearData'
    ));
}

// Dynamic Filters
public function getSeason(Request $request)
{
    return DB::table('kebele_crop_plans')
        ->where('year', $request->year)
        ->where('kebele_id', auth()->user()->kebele_id)
        ->distinct()
        ->orderBy('season')
        ->pluck('season');
}

public function getCropType(Request $request)
{
    return DB::table('kebele_crop_plans as kcp')
        ->join('crop_types', 'crop_types.id', '=', 'kcp.croptype_id')
        ->where('kcp.year', $request->year)
        ->where('kcp.season', $request->season)
        ->where('kcp.kebele_id', auth()->user()->kebele_id)
        ->distinct()
        ->orderBy('crop_types.name')
        ->select('crop_types.id', 'crop_types.name')
        ->get();
}

public function getCrop(Request $request)
{
    return DB::table('kebele_crop_plans as kcp')
        ->join('crops', 'crops.id', '=', 'kcp.crop_id')
        ->where('kcp.year', $request->year)
        ->where('kcp.season', $request->season)
        ->where('kcp.croptype_id', $request->croptype_id)
        ->where('kcp.kebele_id', auth()->user()->kebele_id)
        ->distinct()
        ->orderBy('crops.name')
        ->select('crops.id', 'crops.name')
        ->get();
}

public function fertilizerReport(Request $request)
{
    $user = auth()->user();

    /* ================= YEARS ================= */
    $years = DB::table('kebele_crop_plans')
        ->where('kebele_id', $user->kebele_id)
        ->whereNotNull('fertilizer_details')
        ->distinct()
        ->orderByDesc('year')
        ->pluck('year');

    $year     = $request->year ?? $years->first();
    $prevYear = $year ? $year - 1 : null;

    $season           = $request->season;
    $fertilizerTypeId = $request->fertilizer_type_id;
    $fertilizerId     = $request->fertilizer_id;

    /* ================= CURRENT YEAR ================= */
    $currentPlans = DB::table('kebele_crop_plans')
        ->where('kebele_id', $user->kebele_id)
        ->where('year', $year)
        ->when($season, fn ($q) => $q->where('season', $season))
        ->whereNotNull('fertilizer_details')
        ->get();

    $currentYearData = [];

    foreach ($currentPlans as $plan) {
        $fertilizers = json_decode($plan->fertilizer_details, true);
        if (!is_array($fertilizers)) continue;

        foreach ($fertilizers as $f) {

            if ($fertilizerTypeId && $f['fertilizer_type_id'] != $fertilizerTypeId) continue;
            if ($fertilizerId && $f['fertilizer_id'] != $fertilizerId) continue;

            $key = $plan->season . '_' . $f['fertilizer_id'];

            if (!isset($currentYearData[$key])) {
                $currentYearData[$key] = [
                    'season'        => $plan->season,
                    'fertilizer_id' => $f['fertilizer_id'],
                    'unit'          => $f['unit'],
                    'amount'        => 0,
                ];
            }

            $currentYearData[$key]['amount'] += (float) $f['amount'];
        }
    }

    /* ================= PREVIOUS YEAR ================= */
    $previousPlans = DB::table('kebele_crop_plans')
        ->where('kebele_id', $user->kebele_id)
        ->where('year', $prevYear)
        ->whereNotNull('fertilizer_details')
        ->get();

    $previousYearData = [];

    foreach ($previousPlans as $plan) {
        $fertilizers = json_decode($plan->fertilizer_details, true);
        if (!is_array($fertilizers)) continue;

        foreach ($fertilizers as $f) {
            $key = $plan->season . '_' . $f['fertilizer_id'];

            if (!isset($previousYearData[$key])) {
                $previousYearData[$key] = 0;
            }

            $previousYearData[$key] += (float) $f['amount'];
        }
    }

    /* ================= LOOKUPS ================= */
    $fertilizerNames = DB::table('fertilizers')->pluck('name', 'id');
    $fertilizerTypes = DB::table('fertilizer_types')->pluck('name', 'id');

    return view('gabaasa_torbanii.fertilizer.index', [
        'years'            => $years,
        'year'             => $year,
        'prevYear'         => $prevYear,
        'currentYearData'  => collect($currentYearData),
        'previousYearData' => collect($previousYearData),
        'fertilizerNames'  => $fertilizerNames,
        'fertilizerTypes'  => $fertilizerTypes,
    ]);
}

public function fertilizerSeasons(Request $request)
{
    return DB::table('kebele_crop_plans')
        ->where('year', $request->year)
        ->where('kebele_id', auth()->user()->kebele_id)
        ->whereNotNull('fertilizer_details')
        ->distinct()
        ->orderBy('season')
        ->pluck('season');
}

public function fertilizerTypes(Request $request)
{
    $plans = DB::table('kebele_crop_plans')
        ->where('year', $request->year)
        ->where('season', $request->season)
        ->where('kebele_id', auth()->user()->kebele_id)
        ->whereNotNull('fertilizer_details')
        ->get();

    $typeIds = [];

    foreach ($plans as $plan) {
        $fertilizers = json_decode($plan->fertilizer_details, true);
        if (!is_array($fertilizers)) continue;

        foreach ($fertilizers as $f) {
            $typeIds[$f['fertilizer_type_id']] = true;
        }
    }

    return DB::table('fertilizer_types')
        ->whereIn('id', array_keys($typeIds))
        ->select('id', 'name')
        ->orderBy('name')
        ->get();
}

public function fertilizers(Request $request)
{
    $plans = DB::table('kebele_crop_plans')
        ->where('year', $request->year)
        ->where('season', $request->season)
        ->where('kebele_id', auth()->user()->kebele_id)
        ->whereNotNull('fertilizer_details')
        ->get();

    $ids = [];

    foreach ($plans as $plan) {
        $fertilizers = json_decode($plan->fertilizer_details, true);
        if (!is_array($fertilizers)) continue;

        foreach ($fertilizers as $f) {
            if ($f['fertilizer_type_id'] == $request->fertilizer_type_id) {
                $ids[$f['fertilizer_id']] = true;
            }
        }
    }

    return DB::table('fertilizers')
        ->whereIn('id', array_keys($ids))
        ->select('id', 'name')
        ->orderBy('name')
        ->get();
}





 public function mechinization(Request $request)
    {
        $user = Auth::user();
        $kebeleId = $user->kebele_id;
        $kebeleName = $user->kebele->name ?? 'N/A'; // Adjust according to your Kebele relation

        // Selected year
        $year = $request->year ?? now()->year;
        $prevYear = $year - 1;

        // Years dropdown for this kebele
        $years = Mechanization::where('kebele_id', $kebeleId)
            ->distinct()
            ->orderBy('year','desc')
            ->pluck('year');

        // Current year mechanizations
        $currentYearData = Mechanization::where('kebele_id', $kebeleId)
            ->where('year', $year)
            ->get();

        // Previous year mechanizations (keyed by type + name)
        $previousYearData = Mechanization::where('kebele_id', $kebeleId)
            ->where('year', $prevYear)
            ->get()
            ->keyBy(fn($item) => $item->mechanization_type . '_' . $item->name);

        return view('gabaasa_torbanii.mechinization.kebele_mechanization_report', compact(
            'currentYearData',
            'previousYearData',
            'years',
            'year',
            'prevYear',
            'kebeleName'
        ));
    }

}