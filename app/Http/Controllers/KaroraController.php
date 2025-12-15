<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KebeleCropPlan;
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
}