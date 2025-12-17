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

    // Current and previous year (Ethiopian year)
    $currentYear = 2018;
    $previousYear = $currentYear - 1;

    // Get all zones, woredas, kebeles
    $zones = Zone::where('region_id', $user->region_id)->pluck('id');
    $woredas = Woreda::whereIn('zone_id', $zones)->pluck('id');
    $kebeles = Kebele::whereIn('woreda_id', $woredas)->pluck('id');

    // All crop plans in this region
    $cropPlans = KebeleCropPlan::with(['cropType', 'crop', 'kebele.woreda.zone'])
        ->whereIn('kebele_id', $kebeles)
        ->orderBy('year', 'desc')
        ->orderBy('kebele_id')
        ->orderBy('season')
        ->orderBy('croptype_id')
        ->get();

    // Filter plans by current and previous year
    $currentYearPlans = $cropPlans->where('year', $currentYear);
    $previousYearData = $cropPlans->where('year', $previousYear)->groupBy('crop_id');
    $currentYearData = $currentYearPlans->groupBy('crop_id');

    // Aggregate totals
    $grandTotalArea = $currentYearPlans->sum('area_to_plant');
    $grandTotalYield = $currentYearPlans->sum('expected_yield');

    return view('regionReport.crops', compact(
        'cropPlans',
        'currentYearPlans',
        'currentYearData',
        'previousYearData',
        'grandTotalArea',
        'grandTotalYield',
        'currentYear',
        'previousYear'
    ));
}



    public function indexzonecrop()
    {
        $user = Auth::user();

        // 1️⃣ Get all woredas in the user's zone
        $woredas = Woreda::where('zone_id', $user->zone_id)->get();

        // 2️⃣ Get all kebeles inside those woredas
        $kebeles = Kebele::whereIn('woreda_id', $woredas->pluck('id'))->get();

        // 3️⃣ Get crop plans of all kebeles
        $cropPlans = KebeleCropPlan::with(['cropType', 'crop', 'kebele.woreda'])
            ->whereIn('kebele_id', $kebeles->pluck('id'))
            ->orderBy('year', 'desc')
            ->orderBy('season')
            ->orderBy('croptype_id')
            ->orderBy('crop_id')
            ->get();

        $years = $cropPlans->pluck('year')->unique()->sortDesc();
        $currentYear = $years->first() ?? date('Y');
        $prevYear = $currentYear - 1;

        // 4️⃣ Previous year plans for comparison
        $prevYearPlans = KebeleCropPlan::with(['cropType', 'crop'])
            ->whereIn('kebele_id', $kebeles->pluck('id'))
            ->where('year', $prevYear)
            ->get();

        // 5️⃣ Aggregate current year data
        $aggregatedData = [];
        foreach ($cropPlans as $plan) {
            $key = $plan->year . '_' . $plan->season . '_' . $plan->croptype_id . '_' . $plan->crop_id;

            if (!isset($aggregatedData[$key])) {
                $aggregatedData[$key] = [
                    'year' => $plan->year,
                    'season' => $plan->season,
                    'crop_type' => $plan->cropType->name ?? 'Unknown',
                    'crop_name' => $plan->crop->name ?? 'Unknown',
                    'total_area' => 0,
                    'total_yield' => 0,
                    'woreda_names' => [], // Fixed: changed from kebele_names to woreda_names
                    'woreda_count' => 0,  // Fixed: changed from kebele_count to woreda_count
                    'ids' => [],
                ];
            }

            $aggregatedData[$key]['total_area'] += $plan->area_to_plant;
            $aggregatedData[$key]['total_yield'] += $plan->expected_yield;
            $aggregatedData[$key]['ids'][] = $plan->id;

            // Fixed: Collect woreda names instead of kebele names
            $woredaName = $plan->kebele->woreda->name ?? 'Unknown';
            if (!in_array($woredaName, $aggregatedData[$key]['woreda_names'])) {
                $aggregatedData[$key]['woreda_names'][] = $woredaName;
                $aggregatedData[$key]['woreda_count']++;
            }
        }

        // 6️⃣ Previous year aggregated
        $prevYearAggregated = [];
        foreach ($prevYearPlans as $plan) {
            $key = $plan->croptype_id . '_' . $plan->crop_id;

            if (!isset($prevYearAggregated[$key])) {
                $prevYearAggregated[$key] = [
                    'crop_type' => $plan->cropType->name ?? 'Unknown',
                    'crop_name' => $plan->crop->name ?? 'Unknown',
                    'total_area' => 0,
                    'total_yield' => 0,
                ];
            }

            $prevYearAggregated[$key]['total_area'] += $plan->area_to_plant;
            $prevYearAggregated[$key]['total_yield'] += $plan->expected_yield;
        }

        // 7️⃣ Build grouped data for Blade table
        $groupedData = [];
        foreach ($aggregatedData as $data) {
            $yearSeasonKey = $data['year'] . '_' . $data['season'];

            $productivity = $data['total_area'] > 0
                ? $data['total_yield'] / $data['total_area']
                : 0;

            $prevKey = $data['crop_type'] . '_' . $data['crop_name'];
            $prevYearData = $prevYearAggregated[$prevKey] ?? null;

            $prevYield = $prevYearData['total_yield'] ?? 0;
            $prevArea = $prevYearData['total_area'] ?? 0;
            $prevProd = $prevArea > 0 ? $prevYield / $prevArea : 0;

            $yieldChange = ($data['year'] == $currentYear && $prevYield > 0)
                ? (($data['total_yield'] - $prevYield) / $prevYield) * 100
                : 0;

            $prodChange = ($data['year'] == $currentYear && $prevProd > 0)
                ? (($productivity - $prevProd) / $prevProd) * 100
                : 0;

            $trend = $this->calculatezoneTrend($yieldChange, $prodChange) ?? 'stable';

            if (!isset($groupedData[$yearSeasonKey])) {
                $groupedData[$yearSeasonKey] = [
                    'year' => $data['year'],
                    'season' => $data['season'],
                    'crops' => []
                ];
            }

            $groupedData[$yearSeasonKey]['crops'][] = [
                'id' => $data['ids'][0],
                'crop_type' => $data['crop_type'],
                'crop_name' => $data['crop_name'],
                'total_area' => $data['total_area'],
                'total_yield' => $data['total_yield'],
                'productivity' => $productivity,
                'woreda_names' => $data['woreda_names'], // Fixed: use woreda_names
                'woreda_count' => $data['woreda_count'], // Fixed: use woreda_count
                'prev_yield' => $prevYield, // Fixed: changed from prev_year_yield
                'prev_area' => $prevArea,   // Fixed: changed from prev_year_area
                'prev_prod' => $prevProd,   // Fixed: changed from prev_year_productivity
                'yield_change' => $yieldChange,
                'productivity_change' => $prodChange,
                'trend' => $trend,
            ];
        }

        // 8️⃣ Grand totals
        $grandTotalArea = $cropPlans->sum('area_to_plant');
        $grandTotalYield = $cropPlans->sum('expected_yield');

        return view('zoneReport.crops', compact(
            'groupedData',
            'grandTotalArea',
            'grandTotalYield',
            'currentYear',
            'prevYear'
        ));
    }

    /**
     * Calculate trend based on yield and productivity changes.
     */
    private function calculatezoneTrend($yieldChange, $prodChange)
    {
        if ($yieldChange > 10 && $prodChange > 5) return 'strong_growth';
        if ($yieldChange > 0 && $prodChange > 0) return 'growth';
        if (abs($yieldChange) <= 5 && abs($prodChange) <= 5) return 'stable';
        if ($yieldChange < 0 && $prodChange < 0) return 'decline';
        if ($yieldChange < 0 || $prodChange < 0) return 'slight_decline';
        return 'stable';
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
        // Get all kebeles under the woreda
        $kebeles = Kebele::where('woreda_id', $user->woreda_id)->get();

        $cropPlans = KebeleCropPlan::with(['cropType', 'crop', 'kebele'])
            ->whereIn('kebele_id', $kebeles->pluck('id'))
            ->orderBy('year', 'desc')
            ->orderBy('season')
            ->orderBy('croptype_id')
            ->orderBy('crop_id')
            ->get();

        // Get available years from data
        $years = $cropPlans->pluck('year')->unique()->sortDesc();
        $currentYear = $years->first() ?? date('Y');
        $prevYear = $currentYear - 1;

        // Get previous year data for comparison
        $prevYearPlans = KebeleCropPlan::with(['cropType', 'crop'])
            ->whereIn('kebele_id', $kebeles->pluck('id'))
            ->where('year', $prevYear)
            ->get();

        // Aggregate data by year, season, crop type, crop
        $aggregatedData = [];

        foreach ($cropPlans as $plan) {
            $key = $plan->year . '_' . $plan->season . '_' . $plan->croptype_id . '_' . $plan->crop_id;

            if (!isset($aggregatedData[$key])) {
                $aggregatedData[$key] = [
                    'year' => $plan->year,
                    'season' => $plan->season,
                    'crop_type' => $plan->cropType->name ?? 'Unknown',
                    'crop_name' => $plan->crop->name ?? 'Unknown',
                    'total_area' => 0,
                    'total_yield' => 0,
                    'kebele_names' => [],
                    'kebele_count' => 0,
                    'ids' => [] // store IDs of plans for feedback
                ];
            }

            // Aggregate data
            $aggregatedData[$key]['total_area'] += $plan->area_to_plant;
            $aggregatedData[$key]['total_yield'] += $plan->expected_yield;
            $aggregatedData[$key]['ids'][] = $plan->id;

            // Add kebele name if not already added
            if (!in_array($plan->kebele->name, $aggregatedData[$key]['kebele_names'])) {
                $aggregatedData[$key]['kebele_names'][] = $plan->kebele->name;
                $aggregatedData[$key]['kebele_count']++;
            }
        }

        // Aggregate previous year data
        $prevYearAggregated = [];
        foreach ($prevYearPlans as $plan) {
            $key = $plan->croptype_id . '_' . $plan->crop_id;

            if (!isset($prevYearAggregated[$key])) {
                $prevYearAggregated[$key] = [
                    'crop_type' => $plan->cropType->name ?? 'Unknown',
                    'crop_name' => $plan->crop->name ?? 'Unknown',
                    'total_area' => 0,
                    'total_yield' => 0
                ];
            }

            $prevYearAggregated[$key]['total_area'] += $plan->area_to_plant;
            $prevYearAggregated[$key]['total_yield'] += $plan->expected_yield;
        }

        // Prepare grouped data for table
        $groupedData = [];

        foreach ($aggregatedData as $key => $data) {
            $yearSeasonKey = $data['year'] . '_' . $data['season'];

            $productivity = $data['total_area'] > 0 ? $data['total_yield'] / $data['total_area'] : 0;

            $prevYearKey = $data['crop_type'] . '_' . $data['crop_name'];
            $prevYearData = $prevYearAggregated[$prevYearKey] ?? null;

            $prevYearYield = $prevYearData['total_yield'] ?? 0;
            $prevYearArea = $prevYearData['total_area'] ?? 0;
            $prevYearProductivity = $prevYearArea > 0 ? $prevYearYield / $prevYearArea : 0;

            // Calculate changes
            $yieldChange = 0;
            $productivityChange = 0;
            if ($data['year'] == $currentYear && $prevYearYield > 0) {
                $yieldChange = (($data['total_yield'] - $prevYearYield) / $prevYearYield) * 100;
                $productivityChange = $prevYearProductivity > 0 ?
                    (($productivity - $prevYearProductivity) / $prevYearProductivity) * 100 : 0;
            }

            $trend = $this->calculateTrend($yieldChange, $productivityChange);
            $feedbackCount = KebeleCropPlan::whereIn('id', $data['ids'])->count();


            if (!isset($groupedData[$yearSeasonKey])) {
                $groupedData[$yearSeasonKey] = [
                    'year' => $data['year'],
                    'season' => $data['season'],
                    'crops' => []
                ];
            }

            // Add aggregated crop data
            $groupedData[$yearSeasonKey]['crops'][] = [
                'id' => $data['ids'][0], // use first ID for feedback
                'crop_type' => $data['crop_type'],
                'crop_name' => $data['crop_name'],
                'total_area' => $data['total_area'],
                'total_yield' => $data['total_yield'],
                'productivity' => $productivity,
                'kebele_display' => implode(', ', $data['kebele_names']),
                'kebele_count' => $data['kebele_count'],
                'prev_year_yield' => $prevYearYield,
                'prev_year_area' => $prevYearArea,
                'prev_year_productivity' => $prevYearProductivity,
                'yield_change' => $yieldChange,
                'productivity_change' => $productivityChange,
                'trend' => $trend,
                 'feedback_count' => $feedbackCount
            ];
        }

        // Grand totals
        $grandTotalArea = $cropPlans->sum('area_to_plant');
        $grandTotalYield = $cropPlans->sum('expected_yield');

        return view('woredaReport.crops', compact(
            'groupedData',
            'grandTotalArea',
            'grandTotalYield',
            'currentYear',
            'prevYear'
        ));
    }
    // Update feedback for a specific crop plan
    public function storeFeedback(Request $request, $id)
    {
        $request->validate([
            'feedback' => 'required|string|max:1000',
        ]);

        $plan = KebeleCropPlan::findOrFail($id);

        $plan->update([
            'feedback' => $request->feedback,
            'feedbacked_by' => Auth::user()->name,
            'feedback_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Feedback updated']);
    }

    // Trend calculation
    private function calculateTrend($yieldChange, $productivityChange)
    {
        if ($yieldChange > 10 && $productivityChange > 5) {
            return 'strong_growth';
        } elseif ($yieldChange > 2 && $productivityChange > 1) {
            return 'growth';
        } elseif ($yieldChange < -10 || $productivityChange < -5) {
            return 'decline';
        } elseif ($yieldChange < -2 || $productivityChange < -1) {
            return 'slight_decline';
        } else {
            return 'stable';
        }
    }



}
