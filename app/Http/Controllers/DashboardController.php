<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\Kebele;
use App\Models\Woreda;
use Illuminate\Http\Request;
use App\Models\KebeleCropPlan;
use App\Models\KarorQonnaWoggaa;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

public function indexregioncrop()
    {
        $user = Auth::user();
        
        // Get all zones under this region
        $zones = Zone::where('region_id', $user->region_id)->pluck('id');
        
        // Get all woredas under these zones
        $woredas = Woreda::whereIn('zone_id', $zones)->pluck('id');
        
        // Get all kebeles under these woredas
        $kebeles = Kebele::whereIn('woreda_id', $woredas)->pluck('id');
        
        // Get all crop plans from all kebeles in this region
        $cropPlans = KebeleCropPlan::with(['cropType', 'crop', 'kebele.woreda.zone'])
            ->whereIn('kebele_id', $kebeles)
            ->orderBy('year', 'desc')
            ->orderBy('kebele_id')
            ->orderBy('season')
            ->orderBy('croptype_id')
            ->get();

        // Calculate aggregated totals
        $grandTotalArea = $cropPlans->sum('area_to_plant');
        $grandTotalYield = $cropPlans->sum('expected_yield');
        
        // Calculate zone-wise totals
        $zoneTotals = [];
        foreach ($cropPlans->groupBy('kebele.woreda.zone_id') as $zoneId => $plans) {
            $zone = $plans->first()->kebele->woreda->zone;
            $zoneTotals[$zoneId] = [
                'zone_name' => $zone->name,
                'total_area' => $plans->sum('area_to_plant'),
                'total_yield' => $plans->sum('expected_yield'),
                'plan_count' => $plans->count(),
                'woreda_count' => $plans->groupBy('kebele.woreda_id')->count(),
                'kebele_count' => $plans->groupBy('kebele_id')->count(),
            ];
        }

        // Calculate woreda-wise totals
        $woredaTotals = [];
        foreach ($cropPlans->groupBy('kebele.woreda_id') as $woredaId => $plans) {
            $woreda = $plans->first()->kebele->woreda;
            $woredaTotals[$woredaId] = [
                'woreda_name' => $woreda->name,
                'zone_name' => $woreda->zone->name,
                'total_area' => $plans->sum('area_to_plant'),
                'total_yield' => $plans->sum('expected_yield'),
                'plan_count' => $plans->count(),
                'kebele_count' => $plans->groupBy('kebele_id')->count(),
            ];
        }

        // Calculate kebele-wise totals
        $kebeleTotals = [];
        foreach ($cropPlans->groupBy('kebele_id') as $kebeleId => $plans) {
            $kebele = $plans->first()->kebele;
            $kebeleTotals[$kebeleId] = [
                'kebele_name' => $kebele->name,
                'woreda_name' => $kebele->woreda->name,
                'zone_name' => $kebele->woreda->zone->name,
                'total_area' => $plans->sum('area_to_plant'),
                'total_yield' => $plans->sum('expected_yield'),
                'plan_count' => $plans->count(),
            ];
        }

        // Yearly totals
        $yearlyTotals = [];
        foreach ($cropPlans->groupBy('year') as $year => $plansByYear) {
            $yearlyTotals[$year] = [
                'total_area' => $plansByYear->sum('area_to_plant'),
                'total_yield' => $plansByYear->sum('expected_yield'),
                'zone_count' => $plansByYear->groupBy('kebele.woreda.zone_id')->count(),
                'woreda_count' => $plansByYear->groupBy('kebele.woreda_id')->count(),
                'kebele_count' => $plansByYear->groupBy('kebele_id')->count(),
            ];
        }

        // For productivity comparison
        $currentYear = $cropPlans->max('year');
        $previousYear = $currentYear - 1;
        
        $currentYearData = $cropPlans->where('year', $currentYear)->groupBy('crop_id');
        $previousYearData = $cropPlans->where('year', $previousYear)->groupBy('crop_id');
        $currentYearPlans = $cropPlans->where('year', $currentYear);

        // Fertilizer calculations
        $cropFertilizers = [];
        $typeFertilizers = [];
        
        foreach($currentYearPlans as $plan) {
            // Crop fertilizer calculation
            $cropName = $plan->crop->name ?? 'Unknown';
            $fertilizerDetails = $plan->fertilizer_details ?? [];
            
            if(!isset($cropFertilizers[$cropName])) {
                $cropFertilizers[$cropName] = [
                    'total_fertilizer' => 0,
                    'total_area' => 0,
                    'plans' => 0
                ];
            }
            
            $totalFertilizer = 0;
            if(is_array($fertilizerDetails)) {
                foreach($fertilizerDetails as $fert) {
                    $totalFertilizer += ($fert['amount'] ?? 0);
                }
            }
            
            $cropFertilizers[$cropName]['total_fertilizer'] += $totalFertilizer;
            $cropFertilizers[$cropName]['total_area'] += $plan->area_to_plant;
            $cropFertilizers[$cropName]['plans']++;

            // Crop type fertilizer calculation
            $typeName = $plan->cropType->name ?? 'Unknown';
            if(!isset($typeFertilizers[$typeName])) {
                $typeFertilizers[$typeName] = [
                    'total_fertilizer' => 0,
                    'total_area' => 0,
                    'plans' => 0
                ];
            }
            
            $typeFertilizers[$typeName]['total_fertilizer'] += $totalFertilizer;
            $typeFertilizers[$typeName]['total_area'] += $plan->area_to_plant;
            $typeFertilizers[$typeName]['plans']++;
        }

        return view('regionReport.crops', compact(
            'cropPlans', 
            'yearlyTotals', 
            'grandTotalArea', 
            'grandTotalYield',
            'zoneTotals',
            'woredaTotals',
            'kebeleTotals',
            'currentYear',
            'previousYear',
            'currentYearData',
            'previousYearData',
            'currentYearPlans',
            'cropFertilizers',
            'typeFertilizers'
        ));
    }

     public function indexzonecrop()
    {
        $user = Auth::user();
        
        // Get all woredas under this zone
        $woredas = Woreda::where('zone_id', $user->zone_id)->pluck('id');
        
        // Get all kebeles under these woredas
        $kebeles = Kebele::whereIn('woreda_id', $woredas)->pluck('id');
        
        // Get all crop plans from all kebeles in this zone
        $cropPlans = KebeleCropPlan::with(['cropType', 'crop', 'kebele.woreda'])
            ->whereIn('kebele_id', $kebeles)
            ->orderBy('year', 'desc')
            ->orderBy('kebele_id')
            ->orderBy('season')
            ->orderBy('croptype_id')
            ->get();

        // Calculate aggregated totals
        $grandTotalArea = $cropPlans->sum('area_to_plant');
        $grandTotalYield = $cropPlans->sum('expected_yield');
        
        // Calculate woreda-wise totals
        $woredaTotals = [];
        foreach ($cropPlans->groupBy('kebele.woreda_id') as $woredaId => $plans) {
            $woreda = $plans->first()->kebele->woreda;
            $woredaTotals[$woredaId] = [
                'woreda_name' => $woreda->name,
                'total_area' => $plans->sum('area_to_plant'),
                'total_yield' => $plans->sum('expected_yield'),
                'plan_count' => $plans->count(),
                'kebele_count' => $plans->groupBy('kebele_id')->count(),
            ];
        }

        // Calculate kebele-wise totals
        $kebeleTotals = [];
        foreach ($cropPlans->groupBy('kebele_id') as $kebeleId => $plans) {
            $kebele = $plans->first()->kebele;
            $kebeleTotals[$kebeleId] = [
                'kebele_name' => $kebele->name,
                'woreda_name' => $kebele->woreda->name,
                'total_area' => $plans->sum('area_to_plant'),
                'total_yield' => $plans->sum('expected_yield'),
                'plan_count' => $plans->count(),
            ];
        }

        // Yearly totals
        $yearlyTotals = [];
        foreach ($cropPlans->groupBy('year') as $year => $plansByYear) {
            $yearlyTotals[$year] = [
                'total_area' => $plansByYear->sum('area_to_plant'),
                'total_yield' => $plansByYear->sum('expected_yield'),
                'woreda_count' => $plansByYear->groupBy('kebele.woreda_id')->count(),
                'kebele_count' => $plansByYear->groupBy('kebele_id')->count(),
            ];
        }

        // For productivity comparison
        $currentYear = $cropPlans->max('year');
        $previousYear = $currentYear - 1;
        
        $currentYearData = $cropPlans->where('year', $currentYear)->groupBy('crop_id');
        $previousYearData = $cropPlans->where('year', $previousYear)->groupBy('crop_id');
        $currentYearPlans = $cropPlans->where('year', $currentYear);

        // Fertilizer calculations
        $cropFertilizers = [];
        $typeFertilizers = [];
        
        foreach($currentYearPlans as $plan) {
            // Crop fertilizer calculation
            $cropName = $plan->crop->name ?? 'Unknown';
            $fertilizerDetails = $plan->fertilizer_details ?? [];
            
            if(!isset($cropFertilizers[$cropName])) {
                $cropFertilizers[$cropName] = [
                    'total_fertilizer' => 0,
                    'total_area' => 0,
                    'plans' => 0
                ];
            }
            
            $totalFertilizer = 0;
            if(is_array($fertilizerDetails)) {
                foreach($fertilizerDetails as $fert) {
                    $totalFertilizer += ($fert['amount'] ?? 0);
                }
            }
            
            $cropFertilizers[$cropName]['total_fertilizer'] += $totalFertilizer;
            $cropFertilizers[$cropName]['total_area'] += $plan->area_to_plant;
            $cropFertilizers[$cropName]['plans']++;

            // Crop type fertilizer calculation
            $typeName = $plan->cropType->name ?? 'Unknown';
            if(!isset($typeFertilizers[$typeName])) {
                $typeFertilizers[$typeName] = [
                    'total_fertilizer' => 0,
                    'total_area' => 0,
                    'plans' => 0
                ];
            }
            
            $typeFertilizers[$typeName]['total_fertilizer'] += $totalFertilizer;
            $typeFertilizers[$typeName]['total_area'] += $plan->area_to_plant;
            $typeFertilizers[$typeName]['plans']++;
        }

        return view('zoneReport.crops', compact(
            'cropPlans', 
            'yearlyTotals', 
            'grandTotalArea', 
            'grandTotalYield',
            'woredaTotals',
            'kebeleTotals',
            'currentYear',
            'previousYear',
            'currentYearData',
            'previousYearData',
            'currentYearPlans',
            'cropFertilizers',
            'typeFertilizers'
        ));
    }
    //
    public function kebeleDashboard()
    {
        return view('components.dashboard.kebele');
    }
    public function daDashboard()
    {
        return view('components.dashboard.da');
    }

    
    public function sectorDashboard()
    {
        return view('components.dashboard.sector');
    }


    public function woredaDashboard()
    {
        $user = auth()->user(); // Get currently logged-in user

        if ($user->role_id != 3) {
            abort(403, 'Unauthorized access'); // or redirect to another page
        }

        return view('components.dashboard.woreda');
    }

    public function zoneDashboard()
    {
        $user = Auth::user();

        // Get the zone assigned to logged-in user
        $zone = Zone::with(['woredas.kebeles'])->find($user->zone_id);

        if (!$zone) {
            abort(403, 'No zone assigned to your account.');
        }

        // Total reports in the user's zone
        $totalReports = KarorQonnaWoggaa::where('zone_id', $zone->id)->count();

        // Recent reports for user's zone
        $recentReports = KarorQonnaWoggaa::with('user')
            ->where('zone_id', $zone->id)
            ->latest()
            ->take(2)
            ->get();

        // Prepare chart data
        $zoneLabels = [$zone->name];
        $zoneLandData = [$zone->woredas->flatMap->kebeles->sum('total_area')];
        $zoneWoredaCount = [$zone->woredas->count()];
        $zoneKebeleCount = [$zone->woredas->flatMap->kebeles->count()];
        $zoneProductionData = [
            KarorQonnaWoggaa::where('zone_id', $zone->id)->sum('production')
        ];

        return view('components.dashboard.zone', compact(
            'totalReports',
            'recentReports',
            'zoneLabels',
            'zoneLandData',
            'zoneProductionData',
            'zoneWoredaCount',
            'zoneKebeleCount'
        ));
    }
    public function regionDashboard()
    {
        $user = auth()->user(); // Get the currently authenticated user

        // Check if user is not role_id 1
        if ($user->role_id != 1) {
            abort(403, 'Unauthorized access'); // Or redirect to another page
        }

        $zones = Zone::with(['woredas.kebeles'])->get();

        $totalWoredas = $zones->flatMap->woredas->count();
        $totalKebeles = $zones->flatMap->woredas->flatMap->kebeles->count();
        $totallandarea = $zones->flatMap->woredas->flatMap->kebeles->sum('total_area');

        $reportData = KarorQonnaWoggaa::with(['zone', 'woreda', 'cropType', 'crop'])
            ->get()
            ->groupBy(['zone_id', 'woreda_id', 'crop_type_id', 'crop_id']);

        // Chart Data Arrays
        $zoneLabels = [];
        $zoneLandData = [];
        $zoneProductionData = [];
        $zoneWoredaCount = [];
        $zoneKebeleCount = [];

        foreach ($zones as $zone) {
            $zoneLabels[] = $zone->name;
            $zoneLandData[] = $zone->woredas->flatMap->kebeles->sum('total_area');
            $zoneWoredaCount[] = $zone->woredas->count();
            $zoneKebeleCount[] = $zone->woredas->flatMap->kebeles->count();

            // Production per zone from crop reports
            $production = KarorQonnaWoggaa::where('zone_id', $zone->id)->sum('production');
            $zoneProductionData[] = $production;
        }

        return view('components.dashboard.region', compact(
            'zones',
            'totalWoredas',
            'totalKebeles',
            'totallandarea',
            'reportData',
            'zoneLabels',
            'zoneLandData',
            'zoneProductionData',
            'zoneWoredaCount',
            'zoneKebeleCount'
        ));
    }


   public function indexcrop()
{
    $user = Auth::user();
    
    // Get all kebeles under this woreda
    $kebeles = Kebele::where('woreda_id', $user->woreda_id)->pluck('id');
    
    // Get all crop plans from all kebeles in this woreda
    $cropPlans = KebeleCropPlan::with(['cropType', 'crop', 'kebele'])
        ->whereIn('kebele_id', $kebeles)
        ->orderBy('year', 'desc')
        ->orderBy('kebele_id')
        ->orderBy('season')
        ->orderBy('croptype_id')
        ->get();

    // Calculate aggregated totals
    $grandTotalArea = $cropPlans->sum('area_to_plant');
    $grandTotalYield = $cropPlans->sum('expected_yield');
    
    // Calculate kebele-wise totals
    $kebeleTotals = [];
    foreach ($cropPlans->groupBy('kebele_id') as $kebeleId => $plans) {
        $kebele = $plans->first()->kebele;
        $kebeleTotals[$kebeleId] = [
            'kebele_name' => $kebele->name,
            'total_area' => $plans->sum('area_to_plant'),
            'total_yield' => $plans->sum('expected_yield'),
            'plan_count' => $plans->count(),
        ];
    }

    // Yearly totals
    $yearlyTotals = [];
    foreach ($cropPlans->groupBy('year') as $year => $plansByYear) {
        $yearlyTotals[$year] = [
            'total_area' => $plansByYear->sum('area_to_plant'),
            'total_yield' => $plansByYear->sum('expected_yield'),
            'kebele_count' => $plansByYear->groupBy('kebele_id')->count(),
        ];
    }

    // For productivity comparison
    $currentYear = $cropPlans->max('year');
    $previousYear = $currentYear - 1;
    
    $currentYearData = $cropPlans->where('year', $currentYear)->groupBy('crop_id');
    $previousYearData = $cropPlans->where('year', $previousYear)->groupBy('crop_id');
    $currentYearPlans = $cropPlans->where('year', $currentYear);

    // Fertilizer calculations
    $cropFertilizers = [];
    $typeFertilizers = [];
    
    foreach($currentYearPlans as $plan) {
        // Crop fertilizer calculation
        $cropName = $plan->crop->name ?? 'Unknown';
        $fertilizerDetails = $plan->fertilizer_details ?? [];
        
        if(!isset($cropFertilizers[$cropName])) {
            $cropFertilizers[$cropName] = [
                'total_fertilizer' => 0,
                'total_area' => 0,
                'plans' => 0
            ];
        }
        
        $totalFertilizer = 0;
        if(is_array($fertilizerDetails)) {
            foreach($fertilizerDetails as $fert) {
                $totalFertilizer += ($fert['amount'] ?? 0);
            }
        }
        
        $cropFertilizers[$cropName]['total_fertilizer'] += $totalFertilizer;
        $cropFertilizers[$cropName]['total_area'] += $plan->area_to_plant;
        $cropFertilizers[$cropName]['plans']++;

        // Crop type fertilizer calculation
        $typeName = $plan->cropType->name ?? 'Unknown';
        if(!isset($typeFertilizers[$typeName])) {
            $typeFertilizers[$typeName] = [
                'total_fertilizer' => 0,
                'total_area' => 0,
                'plans' => 0
            ];
        }
        
        $typeFertilizers[$typeName]['total_fertilizer'] += $totalFertilizer;
        $typeFertilizers[$typeName]['total_area'] += $plan->area_to_plant;
        $typeFertilizers[$typeName]['plans']++;
    }

    return view('woredaReport.crops', compact(
        'cropPlans', 
        'yearlyTotals', 
        'grandTotalArea', 
        'grandTotalYield',
        'kebeleTotals',
        'currentYear',
        'previousYear',
        'currentYearData',
        'previousYearData',
        'currentYearPlans',
        'cropFertilizers',
        'typeFertilizers'
    ));
}
}
