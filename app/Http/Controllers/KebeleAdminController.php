<?php

namespace App\Http\Controllers;

use App\Models\KebeleCropPlan;
use App\Models\KebeleEconomicPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KebeleAdminController extends Controller
{
   
  public function dashboard()
{
    $kebeleId = auth()->user()->kebele_id;
    
    // Debug: Check if we have the correct kebele_id
    logger('Kebele ID: ' . $kebeleId);

    // 1. TOTAL LAND AREA: Sum all area_to_plant for this kebele (all years)
    $totalArea = KebeleCropPlan::where('kebele_id', $kebeleId)
        ->sum('area_to_plant') ?? 0;

    logger('Total Land Area (all years): ' . $totalArea);

    // 2. TOTAL PRODUCTION: Sum all expected_yield for this kebele (all years)
    $totalYield = KebeleCropPlan::where('kebele_id', $kebeleId)
        ->sum('expected_yield') ?? 0;

    logger('Total Production (all years): ' . $totalYield);

    // 3. AVERAGE PRODUCTIVITY: Calculate average productivity for this kebele
    $totalAreaForProductivity = KebeleCropPlan::where('kebele_id', $kebeleId)
        ->sum('area_to_plant') ?? 0;
    
    $totalYieldForProductivity = KebeleCropPlan::where('kebele_id', $kebeleId)
        ->sum('expected_yield') ?? 0;
    
    $avgProductivity = $totalAreaForProductivity > 0 ? $totalYieldForProductivity / $totalAreaForProductivity : 0;

    logger('Average Productivity: ' . $avgProductivity);

    // 4. FARMERS REGISTERED: Count farmers from farmers table for this kebele
    $totalFarmers = \App\Models\Farmer::where('kebele_id', $kebeleId)
        ->count();

    logger('Total Farmers: ' . $totalFarmers);

    // 5. CROP PERFORMANCE OVERVIEW: Group by year and crop
    $cropPerformanceData = KebeleCropPlan::with(['crop', 'cropType'])
        ->where('kebele_id', $kebeleId)
        ->get()
        ->groupBy(['year', 'crop.name']);

    logger('Crop Performance Data Groups: ' . $cropPerformanceData->count());

    // Process crop performance data for display
    $cropData = [];
    $yearlyComparison = [];
    $seasonalData = [];

    foreach ($cropPerformanceData as $year => $cropsByYear) {
        $yearTotalArea = 0;
        $yearTotalYield = 0;
        
        foreach ($cropsByYear as $cropName => $plans) {
            $cropArea = $plans->sum('area_to_plant') ?? 0;
            $cropYield = $plans->sum('expected_yield') ?? 0;
            $cropProductivity = $cropArea > 0 ? $cropYield / $cropArea : 0;
            
            // Add to yearly comparison
            $yearTotalArea += $cropArea;
            $yearTotalYield += $cropYield;
            
            // Add to crop data (accumulate across years or show latest year)
            if (!isset($cropData[$cropName]) || $year == max(array_keys($cropPerformanceData->toArray()))) {
                $cropData[$cropName] = [
                    'area' => $cropArea,
                    'yield' => $cropYield,
                    'productivity' => $cropProductivity,
                    'percentage' => $totalYield > 0 ? ($cropYield / $totalYield) * 100 : 0,
                    'type' => $plans->first()->cropType->name ?? 'Unknown',
                    'year' => $year
                ];
            }

            // Seasonal data (from latest year)
            if ($year == max(array_keys($cropPerformanceData->toArray()))) {
                foreach ($plans->groupBy('season') as $season => $seasonPlans) {
                    if (!isset($seasonalData[$season])) {
                        $seasonalData[$season] = ['area' => 0, 'yield' => 0];
                    }
                    $seasonalData[$season]['area'] += $seasonPlans->sum('area_to_plant') ?? 0;
                    $seasonalData[$season]['yield'] += $seasonPlans->sum('expected_yield') ?? 0;
                }
            }
        }
        
        // Yearly comparison data
        $yearlyComparison[$year] = [
            'area' => $yearTotalArea,
            'yield' => $yearTotalYield,
            'productivity' => $yearTotalArea > 0 ? $yearTotalYield / $yearTotalArea : 0
        ];
    }

    logger('Processed Crop Data Count: ' . count($cropData));
    logger('Yearly Comparison Count: ' . count($yearlyComparison));

    // Calculate growth percentages (compare latest year with previous year)
    $years = array_keys($yearlyComparison);
    sort($years);
    
    $latestYear = end($years);
    $previousYear = prev($years);
    
    $latestYearData = $yearlyComparison[$latestYear] ?? ['area' => 0, 'yield' => 0, 'productivity' => 0];
    $previousYearData = $yearlyComparison[$previousYear] ?? ['area' => 0, 'yield' => 0, 'productivity' => 0];

    $areaGrowth = $previousYearData['area'] > 0 ? 
        (($latestYearData['area'] - $previousYearData['area']) / $previousYearData['area']) * 100 : 
        ($latestYearData['area'] > 0 ? 100 : 0);
    
    $yieldGrowth = $previousYearData['yield'] > 0 ? 
        (($latestYearData['yield'] - $previousYearData['yield']) / $previousYearData['yield']) * 100 : 
        ($latestYearData['yield'] > 0 ? 100 : 0);
    
    $productivityGrowth = $previousYearData['productivity'] > 0 ? 
        (($latestYearData['productivity'] - $previousYearData['productivity']) / $previousYearData['productivity']) * 100 : 
        ($latestYearData['productivity'] > 0 ? 100 : 0);

    // Calculate target progress safely
    $targetYield = max($totalYield * 1.1, 1);
    $progressPercentage = $totalYield > 0 ? min(100, ($totalYield / $targetYield) * 100) : 0;

    // Regional average for comparison
    $regionalAverage = 4.5;
    $regionalProgress = $avgProductivity > 0 ? min(100, ($avgProductivity / $regionalAverage) * 100) : 0;

    // Get pending plans for notifications
    $pendingCropPlans = KebeleCropPlan::where('kebele_id', $kebeleId)
        ->where('status', 'pending')
        ->count();

    // Current year for display
    $currentYear = $latestYear ?? date('Y');
    $previousYear = $previousYear ?? $currentYear - 1;

    return view('kebele.admin.dashboard', compact(
        'totalArea',
        'totalYield',
        'totalFarmers',
        'avgProductivity',
        'areaGrowth',
        'yieldGrowth',
        'productivityGrowth',
        'cropData',
        'seasonalData',
        'yearlyComparison',
        'currentYear',
        'previousYear',
        'pendingCropPlans',
        'progressPercentage',
        'regionalProgress',
        'targetYield'
    ));
}

    


    public function approve($sector, $id)
    {
        $model = match ($sector) {
            'crop' => KebeleCropPlan::class,
            'economic' => KebeleEconomicPlan::class,
            default => null,
        };

        if (!$model) {
            return back()->with('error', 'Invalid sector type.');
        }

        $plan = $model::findOrFail($id);
        $plan->status = 'Approved';
        $plan->approved_by = Auth::id();
        $plan->save();

        return back()->with('success', ucfirst($sector) . ' plan approved successfully!');
    }

    public function reject($sector, $id)
    {
        $model = match ($sector) {
            'crop' => KebeleCropPlan::class,
            'economic' => KebeleEconomicPlan::class,
            default => null,
        };

        if (!$model) {
            return back()->with('error', 'Invalid sector type.');
        }

        $plan = $model::findOrFail($id);
        $plan->status = 'Rejected';
        $plan->approved_by = Auth::id();
        $plan->save();

        return back()->with('error', ucfirst($sector) . ' plan rejected.');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'sector' => 'required|string',
            'id' => 'required|integer',
            'status' => 'required|in:approved,rejected',
        ]);

        $model = match ($request->sector) {
            'crop' => KebeleCropPlan::class,
            'economic' => KebeleEconomicPlan::class,
            default => abort(404, 'Invalid sector'),
        };

        $plan = $model::findOrFail($request->id);
        $plan->status = $request->status;
        $plan->approved_by = auth()->id();
        $plan->approved_at = now();
        $plan->save();

        return response()->json([
            'success' => true,
            'message' => ucfirst($request->sector) . " plan {$request->status} successfully.",
        ]);
    }
}
