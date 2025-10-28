<?php

namespace App\Http\Controllers;

use App\Exports\WoredaCropAnalysisExport;
use App\Models\Crop;
use App\Models\CropType;
use App\Models\GabbinaBiyyee;
use App\Models\KarorQonnaWoggaa;
use App\Models\Kebele;
use App\Models\TotalCropYearlyPlan;
use App\Models\Woreda;
use App\Models\YearlyCropPlan;
use App\Models\Zone;
use id;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class KaroorController extends Controller
{
    // public function create()
    // {
    //     $cropTypes = CropType::all();
    //     $planYears = YearlyCropPlan::select('plan_year')
    //         ->distinct()
    //         ->orderBy('plan_year', 'desc')
    //         ->pluck('plan_year');

    //     $months = [
    //         'July', 'August', 'September', 'October', 'November', 'December',
    //         'January', 'February', 'March', 'April', 'May', 'June','phagume'
    //     ];

    //     $quarters = [
    //         'Quarter 1' => ['July', 'August', 'phagume', 'September'],
    //         'Quarter 2' => ['October', 'November', 'December'],
    //         'Quarter 3' => ['January', 'February', 'March'],
    //         'Quarter 4' => ['April', 'May', 'June'],
    //     ];

    //     $seasons = [
    //         'Bega' => ['October', 'November', 'December'],
    //         'Bonaa' => ['January', 'February', 'March'],
    //         'Gannaa' => ['April', 'May', 'June'],
    //         'Arfasa' => ['July', 'August','phagume', 'September'],
    //     ];

    //     // Divide each month into 4 weeks
    //     $weeks = [];
    //     foreach ($months as $month) {
    //         $weeks[$month] = [
    //             'Week 1' => ["start" => "$month 1", "end" => "$month 7"],
    //             'Week 2' => ["start" => "$month 8", "end" => "$month 14"],
    //             'Week 3' => ["start" => "$month 15", "end" => "$month 21"],
    //             'Week 4' => ["start" => "$month 22", "end" => "$month 28"],
    //             'Week 5' => ["start" => "$month 29", "end" => "$month 30"],
    //         ];
    //     }

    //     // Get the current quarter, month, and week
    //     $currentMonth = now()->format('F'); // Get current month
    //     $currentQuarter = $this->getCurrentQuarter($currentMonth); // Helper function to get the current quarter
    //     $currentWeek = $this->getCurrentWeek($currentMonth); // Helper function to get the current week

    //     return view('karora.karoora-gandaa.kurmana', compact('months', 'cropTypes', 'planYears', 'quarters', 'weeks', 'seasons', 'currentQuarter', 'currentMonth', 'currentWeek'));
    // }

    public function getCurrentQuarter($month)
    {
        $quarters = [
            'Quarter 1' => ['July', 'August', 'phagume', 'September'],
            'Quarter 2' => ['October', 'November', 'December'],
            'Quarter 3' => ['January', 'February', 'March'],
            'Quarter 4' => ['April', 'May', 'June'],
        ];

        foreach ($quarters as $quarter => $months) {
            if (in_array($month, $months)) {
                return $quarter;
            }
        }

        return null; // Default if no match
    }

    public function getCurrentWeek($month)
    {
        $weeks = [
            'July' => ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'],
            'August' => ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'],
            // Add weeks for all months similarly
        ];

        // Logic to get the current week based on the current month and date
        $weekIndex = now()->weekOfMonth; // Get current week of month
        return $weeks[$month][$weekIndex - 1] ?? 'Week 1'; // Return default if no week found
    }


    public function getCrops($cropTypeId)
    {
        $crops = Crop::where('croptype_id', $cropTypeId)->get();
        return response()->json($crops);
    }

    public function index()
    {
        $landPlans = KarorQonnaWoggaa::with(['crop', 'cropType'])
            ->orderBy('year', 'desc')
            ->paginate(10);

        $groupedData = $landPlans->groupBy(['year', 'season', function ($plan) {
            return $plan->crop_type_id . '-' . ($plan->cropType->name ?? 'Unknown');
        }, function ($plan) {
            return $plan->crop_id . '-' . ($plan->crop->name ?? 'Unknown');
        }]);

        return view('karora.karoora-gandaa.index', compact('landPlans', 'groupedData'));
    }



    public function create()
    {
        $cropTypes = CropType::all();
        $crops = Crop::all();

        return view('karora.karoora-gandaa.kurmana', compact('cropTypes', 'crops'));
    }


   


    public function store(Request $request)
    {
        $validated = $request->validate([
            'plans.*.year' => 'required|integer',
            'plans.*.season' => 'required|string',
            'plans.*.crop_type_id' => 'nullable|exists:crop_types,id',
            'plans.*.crop_id' => 'nullable',
            'plans.*.land_hectares' => 'nullable|numeric|min:0',
            'plans.*.production' => 'nullable|numeric|min:0',
            'plans.*.type' => 'nullable|string',
            'plans.*.quantity' => 'nullable|numeric',
            'plans.*.unit' => 'nullable|string',


        ]);

        $user = Auth::user();
        $errors = [];

        foreach ($validated['plans'] as $index => $plan) {
            $cropId = $plan['crop_id'] ?? null;


            $productivity = isset($plan['land_hectares']) && $plan['land_hectares'] > 0
                ? round($plan['production'] / $plan['land_hectares'], 2)
                : null;

            $exists = KarorQonnaWoggaa::where('year', $plan['year'])
                ->where('season', $plan['season'])
                ->where('crop_id', $cropId)
                ->where('kebele_id', $user->kebele_id)
                ->where('woreda_id', $user->woreda_id)
                ->where('zone_id', $user->zone_id)
                ->where('region_id', $user->region_id)
                ->exists();

            if ($exists) {
                $errors[] = "Crop ID {$cropId} is already planned for {$plan['season']} {$plan['year']}.";
            } else {
                $inputData = $validated['inputs'][$index] ?? null;

                KarorQonnaWoggaa::create([
                    'year' => $plan['year'],
                    'season' => $plan['season'],
                    'crop_type_id' => $plan['crop_type_id'] ?? null,
                    'crop_id' => $cropId,
                    'land_hectares' => $plan['land_hectares'] ?? null,
                    'production' => $plan['production'] ?? null,
                    'type' => $plan['type'] ?? null,
                    'quantity' => $plan['quantity'] ?? null,
                    'unit' => $plan['unit'] ?? null,
                    'productivity' => $productivity,
                    'kebele_id' => $user->kebele_id,
                    'woreda_id' => $user->woreda_id,
                    'zone_id' => $user->zone_id,
                    'region_id' => $user->region_id,
                    'created_by' => $user->id,
                    'phone' => $user->phone,
                ]);
            }
        }

        // After inserting all plans, calculate yearly total
        $groupedPlans = collect($validated['plans'])->groupBy(function ($item) {
            return $item['year'] . '-' . ($item['crop_id'] ?? 'null');
        });

        foreach ($groupedPlans as $group) {
            $year = $group[0]['year'];
            $cropId = $group[0]['crop_id'] ?? null;
            $cropTypeId = $group[0]['crop_type_id'] ?? null;

            $totalLand = $group->sum('land_hectares');
            $totalProduction = $group->sum('production');
            $averageProductivity = $totalLand > 0 ? round($totalProduction / $totalLand, 2) : null;

            $existingPlan = TotalCropYearlyPlan::where('year', $year)
                ->where('crop_id', $cropId)
                ->where('kebele_id', $user->kebele_id)
                ->where('woreda_id', $user->woreda_id)
                ->where('zone_id', $user->zone_id)
                ->where('region_id', $user->region_id)
                ->first();

            if ($existingPlan) {
                $existingPlan->update([
                    'total_land_hectares' => $existingPlan->total_land_hectares + $totalLand,
                    'total_production' => $existingPlan->total_production + $totalProduction,
                    'average_productivity' => ($existingPlan->total_land_hectares + $totalLand) > 0
                        ? round(($existingPlan->total_production + $totalProduction) / ($existingPlan->total_land_hectares + $totalLand), 2)
                        : null,
                ]);
            } else {
                TotalCropYearlyPlan::create([
                    'year' => $year,
                    'crop_id' => $cropId,
                    'crop_type_id' => $cropTypeId,
                    'total_land_hectares' => $totalLand,
                    'total_production' => $totalProduction,
                    'average_productivity' => $averageProductivity,
                    'kebele_id' => $user->kebele_id,
                    'woreda_id' => $user->woreda_id,
                    'zone_id' => $user->zone_id,
                    'region_id' => $user->region_id,
                ]);
            }
        }

        if (!empty($errors)) {
            return redirect()->back()
                ->withErrors($errors)
                ->withInput();
        }

        return redirect()->route('land-plans.create')
            ->with('success', 'Karoora lafaa fi Oomishaa bara kanaa Haala sirriidhaan galchitee jirta!');
    }
    public function edit($id)
    {
        $plan = KarorQonnaWoggaa::findOrFail($id);
        $cropTypes = CropType::all();
        $crops = Crop::where('croptype_id', $plan->crop_type_id)->get();

        return view('karora.karoora-gandaa.edit', compact('plan', 'cropTypes', 'crops'));
    }


    public function update(Request $request, $id)
    {
        $plan = KarorQonnaWoggaa::findOrFail($id);

        $validated = $request->validate([
            'year' => 'required|integer',
            'season' => 'required|string',
            'crop_type_id' => 'required|exists:crop_types,id',
            'crop_id' => 'required|exists:crops,id',
            'land_hectares' => 'required|numeric|min:0',
            'productivity' => 'required|numeric|min:0',
        ]);

        $validated['production'] = $validated['land_hectares'] * $validated['productivity'];

        $plan->update([
            'year' => $validated['year'],
            'season' => $validated['season'],
            'crop_type_id' => $validated['crop_type_id'],
            'crop_id' => $validated['crop_id'],
            'land_hectares' => $validated['land_hectares'],
            'productivity' => $validated['productivity'],
            'production' => $validated['production'],
        ]);

        return redirect()
            ->route('land-plans.index')
            ->with('success', 'Land farming plan updated successfully!');
    }


    public function fertilizer()
    {
        $cropTypes = CropType::all();
        $crops = Crop::all();

        return view('karora.karoora-gandaa.fertilizer', compact('cropTypes', 'crops'));
    }
    public function generateReport(Request $request)
    {
        // Fetch the report data grouped by year, season, and crop type
        $reportData = KarorQonnaWoggaa::with(['cropType', 'crop'])
            ->selectRaw('year, season, crop_type_id, crop_id, SUM(land_hectares) as total_land_hectares, SUM(production) as total_production, SUM(productivity) as total_productivity')
            ->groupBy('year', 'season', 'crop_type_id', 'crop_id')
            ->orderBy('year', 'desc')
            ->get();

        // Calculate average productivity for each group
        $reportData = $reportData->map(function ($item) {
            $item->average_productivity = $item->total_land_hectares > 0
                ? round($item->total_production / $item->total_land_hectares, 2)
                : 0;

            return $item;
        });

        return view('reports.land_farming_report', compact('reportData'));
    }


    public function gannaSeasonCrops()
    {
        $userId = Auth::id();

        // Get only Ganna season records for the logged-in user with related data
        $records = KarorQonnaWoggaa::with(['crop', 'cropType', 'kebele', 'user'])
            ->where('season', 'Ganna')
            ->where('created_by', $userId)
            ->orderBy('year')
            ->orderBy('kebele_id')
            ->orderBy('crop_type_id')
            ->orderBy('crop_id')
            ->get();

        // Group records by year
        $groupedByYear = $records->groupBy('year');

        // Prepare yearly summaries for the CURRENT user
        $userYearlySummaries = [];
        foreach ($groupedByYear as $year => $yearRecords) {
            $userYearlySummaries[$year] = [
                'total_land' => $yearRecords->sum('land_hectares'),
                'total_production' => $yearRecords->sum('production'),
                'avg_productivity' => $yearRecords->avg('productivity'),
                'crop_count' => $yearRecords->count()
            ];
        }

        // Prepare crop summaries by year for the user
        $userCropSummariesByYear = [];
        foreach ($groupedByYear as $year => $yearRecords) {
            foreach ($yearRecords as $record) {
                $cropName = $record->crop->name;
                if (!isset($userCropSummariesByYear[$year][$cropName])) {
                    $userCropSummariesByYear[$year][$cropName] = [
                        'total_land' => 0,
                        'total_production' => 0,
                        'avg_productivity' => 0,
                        'count' => 0
                    ];
                }

                $userCropSummariesByYear[$year][$cropName]['total_land'] += $record->land_hectares;
                $userCropSummariesByYear[$year][$cropName]['total_production'] += $record->production;
                $userCropSummariesByYear[$year][$cropName]['avg_productivity'] += $record->productivity;
                $userCropSummariesByYear[$year][$cropName]['count']++;
            }

            // Calculate averages for each crop in the year
            foreach ($userCropSummariesByYear[$year] as &$cropSummary) {
                $cropSummary['avg_productivity'] = $cropSummary['avg_productivity'] / $cropSummary['count'];
            }
        }

        // Prepare data for charts - aggregate all crop data
        $allUserCropData = [];
        foreach ($records as $record) {
            $cropName = $record->crop->name;
            if (!isset($allUserCropData[$cropName])) {
                $allUserCropData[$cropName] = [
                    'total_land' => 0,
                    'total_production' => 0
                ];
            }
            $allUserCropData[$cropName]['total_land'] += $record->land_hectares;
            $allUserCropData[$cropName]['total_production'] += $record->production;
        }

        return view('reports.kebele-report.ganna-season-crops', [
            'groupedByYear' => $groupedByYear,
            'userYearlySummaries' => $userYearlySummaries,
            'userCropSummariesByYear' => $userCropSummariesByYear,
            'allUserCropData' => $allUserCropData
        ]);
    }


    public function bonaSeasonCrops()
    {
        $userId = Auth::id();

        // Get only Ganna season records for the logged-in user with related data
        $records = KarorQonnaWoggaa::with(['crop', 'cropType', 'kebele', 'user'])
            ->where('season', 'Bona')
            ->where('created_by', $userId)
            ->orderBy('year')
            ->orderBy('kebele_id')
            ->orderBy('crop_type_id')
            ->orderBy('crop_id')
            ->get();

        // Group records by year
        $groupedByYear = $records->groupBy('year');

        // Prepare yearly summaries for the CURRENT user
        $userYearlySummaries = [];
        foreach ($groupedByYear as $year => $yearRecords) {
            $userYearlySummaries[$year] = [
                'total_land' => $yearRecords->sum('land_hectares'),
                'total_production' => $yearRecords->sum('production'),
                'avg_productivity' => $yearRecords->avg('productivity'),
                'crop_count' => $yearRecords->count()
            ];
        }

        // Prepare crop summaries by year for the user
        $userCropSummariesByYear = [];
        foreach ($groupedByYear as $year => $yearRecords) {
            foreach ($yearRecords as $record) {
                $cropName = $record->crop->name;
                if (!isset($userCropSummariesByYear[$year][$cropName])) {
                    $userCropSummariesByYear[$year][$cropName] = [
                        'total_land' => 0,
                        'total_production' => 0,
                        'avg_productivity' => 0,
                        'count' => 0
                    ];
                }

                $userCropSummariesByYear[$year][$cropName]['total_land'] += $record->land_hectares;
                $userCropSummariesByYear[$year][$cropName]['total_production'] += $record->production;
                $userCropSummariesByYear[$year][$cropName]['avg_productivity'] += $record->productivity;
                $userCropSummariesByYear[$year][$cropName]['count']++;
            }

            // Calculate averages for each crop in the year
            foreach ($userCropSummariesByYear[$year] as &$cropSummary) {
                $cropSummary['avg_productivity'] = $cropSummary['avg_productivity'] / $cropSummary['count'];
            }
        }


        // Prepare data for charts - aggregate all crop data
        $allUserCropData = [];
        foreach ($records as $record) {
            $cropName = $record->crop->name;
            if (!isset($allUserCropData[$cropName])) {
                $allUserCropData[$cropName] = [
                    'total_land' => 0,
                    'total_production' => 0
                ];
            }
            $allUserCropData[$cropName]['total_land'] += $record->land_hectares;
            $allUserCropData[$cropName]['total_production'] += $record->production;
        }

        return view('reports.kebele-report.bona-season-crops', [
            'groupedByYear' => $groupedByYear,
            'userYearlySummaries' => $userYearlySummaries,
            'userCropSummariesByYear' => $userCropSummariesByYear,
            'allUserCropData' => $allUserCropData
        ]);
    }

    public function arfasaSeasonCrops()
    {
        $userId = Auth::id();

        // Get only Ganna season records for the logged-in user with related data
        $records = KarorQonnaWoggaa::with(['crop', 'cropType', 'kebele', 'user'])
            ->where('season', 'Arfaasa')
            ->where('created_by', $userId)
            ->orderBy('year')
            ->orderBy('kebele_id')
            ->orderBy('crop_type_id')
            ->orderBy('crop_id')
            ->get();

        // Group records by year
        $groupedByYear = $records->groupBy('year');

        // Prepare yearly summaries for the CURRENT user
        $userYearlySummaries = [];
        foreach ($groupedByYear as $year => $yearRecords) {
            $userYearlySummaries[$year] = [
                'total_land' => $yearRecords->sum('land_hectares'),
                'total_production' => $yearRecords->sum('production'),
                'avg_productivity' => $yearRecords->avg('productivity'),
                'crop_count' => $yearRecords->count()
            ];
        }

        // Prepare crop summaries by year for the user
        $userCropSummariesByYear = [];
        foreach ($groupedByYear as $year => $yearRecords) {
            foreach ($yearRecords as $record) {
                $cropName = $record->crop->name;
                if (!isset($userCropSummariesByYear[$year][$cropName])) {
                    $userCropSummariesByYear[$year][$cropName] = [
                        'total_land' => 0,
                        'total_production' => 0,
                        'avg_productivity' => 0,
                        'count' => 0
                    ];
                }

                $userCropSummariesByYear[$year][$cropName]['total_land'] += $record->land_hectares;
                $userCropSummariesByYear[$year][$cropName]['total_production'] += $record->production;
                $userCropSummariesByYear[$year][$cropName]['avg_productivity'] += $record->productivity;
                $userCropSummariesByYear[$year][$cropName]['count']++;
            }

            // Calculate averages for each crop in the year
            foreach ($userCropSummariesByYear[$year] as &$cropSummary) {
                $cropSummary['avg_productivity'] = $cropSummary['avg_productivity'] / $cropSummary['count'];
            }
        }

        // Prepare data for charts - aggregate all crop data
        $allUserCropData = [];
        foreach ($records as $record) {
            $cropName = $record->crop->name;
            if (!isset($allUserCropData[$cropName])) {
                $allUserCropData[$cropName] = [
                    'total_land' => 0,
                    'total_production' => 0
                ];
            }
            $allUserCropData[$cropName]['total_land'] += $record->land_hectares;
            $allUserCropData[$cropName]['total_production'] += $record->production;
        }

        return view('reports.kebele-report.arfasa-season-crops', [
            'groupedByYear' => $groupedByYear,
            'userYearlySummaries' => $userYearlySummaries,
            'userCropSummariesByYear' => $userCropSummariesByYear,
            'allUserCropData' => $allUserCropData
        ]);
    }


    public function kebeleCropAnalysis(Request $request)
    {
        $user = Auth::user();
        $selectedYear = $request->input('year', date('Y'));
        $selectedSeason = $request->input('season', 'Ganna');
        $selectedCrop = $request->input('crop_id');
        $selectedYearminusOne = $selectedYear - 1;

        $kebeles = Kebele::where('id', $user->kebele_id)->get();

        // Current year crop plan data
        $query = KarorQonnaWoggaa::with(['kebele', 'cropType', 'crop'])
            ->whereIn('kebele_id', $kebeles->pluck('id'))
            ->where('year', $selectedYear);

        if ($selectedSeason) {
            $query->where('season', $selectedSeason);
        }

        if ($selectedCrop) {
            $query->where('crop_id', $selectedCrop);
        }

        $availableYears = KarorQonnaWoggaa::whereIn('kebele_id', $kebeles->pluck('id'))
            ->distinct()->pluck('year')->sort();

        $availableSeasons = KarorQonnaWoggaa::whereIn('kebele_id', $kebeles->pluck('id'))
            ->distinct()->pluck('season')->unique()->sort();

        $availableCrops = Crop::whereHas('karorQonnaWoggaas', function ($q) use ($kebeles) {
            $q->whereIn('kebele_id', $kebeles->pluck('id'));
        })->orderBy('name')->get();

        $reportData = $query->orderBy('kebele_id')
            ->orderBy('crop_type_id')
            ->orderBy('crop_id')
            ->get()
            ->groupBy(['kebele_id', 'crop_type_id', 'crop_id']);

        // Current year plan (Callguddiftu)
        $callguddiftu = KarorQonnaWoggaa::with(['kebele', 'cropType', 'crop'])
            ->whereIn('kebele_id', $kebeles->pluck('id'))
            ->where('year', $selectedYear);

        if ($selectedCrop) {
            $callguddiftu->where('crop_id', $selectedCrop);
        }

        $callguddiftu = $callguddiftu->orderBy('season')->get();

        // Previous year baseline (Callguddiftu)
        $baselineCallguddiftu = KarorQonnaWoggaa::with(['kebele', 'cropType', 'crop'])
            ->whereIn('kebele_id', $kebeles->pluck('id'))
            ->where('year', $selectedYearminusOne);

        if ($selectedCrop) {
            $baselineCallguddiftu->where('crop_id', $selectedCrop);
        }

        $baselineCallguddiftu = $baselineCallguddiftu->orderBy('season')->get();

        // Gabbina Biyyee current year
        $gabinabiyoCurrent = GabbinaBiyyee::with(['kebele'])
            ->whereIn('kebele_id', $kebeles->pluck('id'))
            ->where('year', $selectedYear)
            ->get();

        $totallandarea = Kebele:: where('id', $user->kebele_id)
            ->first();

        // Gabbina Biyyee previous year (baseline)
        $gabinabiyoBaseline = GabbinaBiyyee::with(['kebele'])
            ->whereIn('kebele_id', $kebeles->pluck('id'))
            ->where('year', $selectedYearminusOne)
            ->orderBy('compost_type')
            ->get();

        // Totals for current year
        $totals = [
            'total_land' => $reportData->flatten(3)->sum('land_hectares'),
            'total_production' => $reportData->flatten(3)->sum('production'),
        ];
        $totals['avg_productivity'] = $totals['total_land'] > 0
            ? $totals['total_production'] / $totals['total_land']
            : 0;

        $chartData = $this->prepareChartData($reportData, $kebeles);

        // Previous year crop plan data
        $previousQuery = KarorQonnaWoggaa::with(['kebele', 'cropType', 'crop'])
            ->whereIn('kebele_id', $kebeles->pluck('id'))
            ->where('year', $selectedYearminusOne);

        if ($selectedSeason) {
            $previousQuery->where('season', $selectedSeason);
        }

        if ($selectedCrop) {
            $previousQuery->where('crop_id', $selectedCrop);
        }

        $previousYearData = $previousQuery
            ->orderBy('kebele_id')
            ->orderBy('crop_type_id')
            ->orderBy('crop_id')
            ->get()
            ->groupBy(['kebele_id', 'crop_type_id', 'crop_id']);

        $previousTotals = [
            'total_land' => $previousYearData->flatten(3)->sum('land_hectares'),
            'total_production' => $previousYearData->flatten(3)->sum('production'),
        ];
        $previousTotals['avg_productivity'] = $previousTotals['total_land'] > 0
            ? $previousTotals['total_production'] / $previousTotals['total_land']
            : 0;

        // Pie chart data
        $pieChartLabels = [];
        $pieChartData = [];

        foreach ($reportData as $kebeleData) {
            foreach ($kebeleData as $cropTypeData) {
                foreach ($cropTypeData as $cropData) {
                    $totalProduction = $cropData->sum('production');
                    $pieChartLabels[] = $cropData->first()->crop?->name;
                    $pieChartData[] = $totalProduction;
                }
            }
        }

        return view('reports.kebele-report.ganna-season-crops', [
            'kebeles' => $kebeles,
            'reportData' => $reportData,
            'availableYears' => $availableYears,
            'availableSeasons' => $availableSeasons,
            'availableCrops' => $availableCrops,
            'selectedYear' => $selectedYear,
            'selectedSeason' => $selectedSeason,
            'selectedCrop' => $selectedCrop,
            'totals' => $totals,
            'chartData' => $chartData,
            'previousYearData' => $previousYearData,
            'previousTotals' => $previousTotals,
            'pieChartLabels' => $pieChartLabels,
            'pieChartData' => $pieChartData,
            'callguddiftu' => $callguddiftu,
            'baselineCallguddiftu' => $baselineCallguddiftu,
            'gabinabiyoCurrent' => $gabinabiyoCurrent,
            'gabinabiyoBaseline' => $gabinabiyoBaseline,
            'totallandarea' => $totallandarea,

        ]);
    }


    public function woredaCropAnalysis(Request $request)
    {
        $user = Auth::user();
        $selectedYear = $request->input('year', date('Y'));
        $selectedSeason = $request->input('season', 'Ganna');
        $selectedCrop = $request->input('crop_id');
        $selectedYearminusOne = $selectedYear - 1;
//dd(Auth::user()->woreda_id);
        $woredas = Woreda::where('id', Auth::user()->woreda_id)->get();

        // Current year crop plan data
        $query = KarorQonnaWoggaa::with(['woreda', 'kebele', 'cropType', 'crop'])
            ->whereIn('woreda_id', $woredas->pluck('id'))
            ->where('year', $selectedYear);

//<<<<<<< HEAD
        if ($selectedSeason) {
            $query->where('season', $selectedSeason);
        }
//=======
    $woredas = Woreda::where('id', Auth::user()->woreda_id)->get();
//>>>>>>> 367e87292d4a6e8e254bf38eb8c311d9ae705b71

        if ($selectedCrop) {
            $query->where('crop_id', $selectedCrop);
        }

//    $availableYears1=['2011','2012','2013','2014','2015','2016','2017','2018','2019','2020','2021'];

        $availableYears = KarorQonnaWoggaa::whereIn('woreda_id', $woredas->pluck('id'))
            ->distinct()->pluck('year')->sort();

        $availableSeasons = KarorQonnaWoggaa::whereIn('woreda_id', $woredas->pluck('id'))
            ->distinct()->pluck('season')->unique()->sort();

        $availableCrops = Crop::whereHas('karorQonnaWoggaas', function ($q) use ($woredas) {
            $q->whereIn('woreda_id', $woredas->pluck('id'),);
        })->orderBy('name')->get();
//dd($availableCrops);
//     $query->whereNotNull('crop_type_id')->whereNotNull('crop_id');

        $reportData = $query->orderBy('woreda_id')
            ->orderBy('kebele_id')
            ->orderBy('crop_type_id')
            ->orderBy('crop_id')
            ->get()
            ->groupBy([
                'kebele_id',
                'crop_type_id',
                'crop_id'
            ]);

        // Current year plan (Callguddiftu)
        $callguddiftu = KarorQonnaWoggaa::with(['woreda', 'cropType', 'crop'])
            ->whereIn('woreda_id', $woredas->pluck('id'))
            ->where('year', $selectedYear);

        if ($selectedCrop) {
            $callguddiftu->where('crop_id', $selectedCrop);
        }

        $callguddiftu = $callguddiftu->orderBy('season')->get();

        // Previous year baseline (Callguddiftu)
        $baselineCallguddiftu = KarorQonnaWoggaa::with(['woreda', 'cropType', 'crop'])
            ->whereIn('woreda_id', $woredas->pluck('id'))
            ->where('year', $selectedYearminusOne);

        if ($selectedCrop) {
            $baselineCallguddiftu->where('crop_id', $selectedCrop);
        }

        $baselineCallguddiftu = $baselineCallguddiftu->orderBy('season')->get();

        // Gabbina Biyyee current year
        $gabinabiyoCurrent = GabbinaBiyyee::with(['kebele'])
            ->whereIn('woreda_id', $woredas->pluck('id'))
            ->where('year', $selectedYear)
            ->get();

        $woreda = Woreda::with('kebeles')->find(Auth::user()->woreda_id);
        $totallandarea = $woreda->kebeles->sum('total_area');

        // Gabbina Biyyee previous year (baseline)
        $gabinabiyoBaseline = GabbinaBiyyee::with(['woreda'])
            ->whereIn('woreda_id', $woredas->pluck('id'))
            ->where('year', $selectedYearminusOne)
            ->orderBy('compost_type')
            ->get();

        // Totals for current year
        $totals = [
            'total_land' => $reportData->flatten(3)->sum('land_hectares'),
            'total_production' => $reportData->flatten(3)->sum('production'),
        ];
        $totals['avg_productivity'] = $totals['total_land'] > 0
            ? $totals['total_production'] / $totals['total_land']
            : 0;

        $chartData = $this->prepareChartData($reportData, $woredas);

        // Previous year crop plan data
        $previousQuery = KarorQonnaWoggaa::with(['kebele', 'cropType', 'crop'])
            ->whereIn('woreda_id', $woredas->pluck('id'))
            ->where('year', $selectedYearminusOne);

        if ($selectedSeason) {
            $previousQuery->where('season', $selectedSeason);
        }

        if ($selectedCrop) {
            $previousQuery->where('crop_id', $selectedCrop);
        }

        $previousYearData = $previousQuery
            ->orderBy('woreda_id')
            ->orderBy('crop_type_id')
            ->orderBy('crop_id')
            ->get()
            ->groupBy(['woreda_id', 'crop_type_id', 'crop_id']);

        $previousTotals = [
            'total_land' => $previousYearData->flatten(3)->sum('land_hectares'),
            'total_production' => $previousYearData->flatten(3)->sum('production'),
        ];
        $previousTotals['avg_productivity'] = $previousTotals['total_land'] > 0
            ? $previousTotals['total_production'] / $previousTotals['total_land']
            : 0;

        // Pie chart data
        $pieChartLabels = [];
        $pieChartData = [];

        foreach ($reportData as $kebeleData) {
            foreach ($kebeleData as $cropTypeData) {
                foreach ($cropTypeData as $cropData) {
                    $totalProduction = $cropData->sum('production');
                    $pieChartLabels[] = $cropData->first()->crop?->name;
                    $pieChartData[] = $totalProduction;
                }
            }
        }

        return view('reports.woreda-report.gana-season-crops', [
            'woredas' => $woredas,
            'reportData' => $reportData,
            'availableYears' => $availableYears,
            'availableSeasons' => $availableSeasons,
            'availableCrops' => $availableCrops,
            'selectedYear' => $selectedYear,
            'selectedSeason' => $selectedSeason,
            'selectedCrop' => $selectedCrop,
            'totals' => $totals,
            'chartData' => $chartData,
            'previousYearData' => $previousYearData,
            'previousTotals' => $previousTotals,
            'pieChartLabels' => $pieChartLabels,
            'pieChartData' => $pieChartData,
            'callguddiftu' => $callguddiftu,
            'baselineCallguddiftu' => $baselineCallguddiftu,
            'gabinabiyoCurrent' => $gabinabiyoCurrent,
            'gabinabiyoBaseline' => $gabinabiyoBaseline,
            'totallandarea' => $totallandarea,

        ]);
    }

    public function ZoneCropAnalysis(Request $request)
    {
        $user = Auth::user();
        $selectedYear = $request->input('year', date('Y'));
        $selectedSeason = $request->input('season', 'Ganna');
        $selectedCrop = $request->input('crop_id');
        $selectedYearminusOne = $selectedYear - 1;
//dd(Auth::user()->woreda_id);
        $zones = Zone::where('id', Auth::user()->zone_id)->get();

        // Current year crop plan data
        $query = KarorQonnaWoggaa::with(['woreda', 'zone', 'kebele', 'cropType', 'crop'])
            ->whereIn('zone_id', $zones->pluck('id'))
            ->where('year', $selectedYear);

        if ($selectedSeason) {
            $query->where('season', $selectedSeason);
        }

        if ($selectedCrop) {
            $query->where('crop_id', $selectedCrop);
        }

//    $availableYears1=['2011','2012','2013','2014','2015','2016','2017','2018','2019','2020','2021'];

        $availableYears = KarorQonnaWoggaa::whereIn('zone_id', $zones->pluck('id'))
            ->distinct()->pluck('year')->sort();

        $availableSeasons = KarorQonnaWoggaa::whereIn('zone_id', $zones->pluck('id'))
            ->distinct()->pluck('season')->unique()->sort();

        $availableCrops = Crop::whereHas('karorQonnaWoggaas', function ($q) use ($zones) {
            $q->whereIn('zone_id', $zones->pluck('id'),);
        })->orderBy('name')->get();
//dd($availableCrops);
//     $query->whereNotNull('crop_type_id')->whereNotNull('crop_id');

        $reportData = $query->orderBy('zone_id')
            ->orderBy('woreda_id')
            ->orderBy('crop_type_id')
            ->orderBy('crop_id')
            ->get()
            ->groupBy([
                'woreda_id',
                'crop_type_id',
                'crop_id'
            ]);

        // Current year plan (Callguddiftu)
        $callguddiftu = KarorQonnaWoggaa::with(['woreda', 'cropType', 'crop'])
            ->whereIn('zone_id', $zones->pluck('id'))
            ->where('year', $selectedYear);

        if ($selectedCrop) {
            $callguddiftu->where('crop_id', $selectedCrop);
        }

        $callguddiftu = $callguddiftu->orderBy('season')->get();

        // Previous year baseline (Callguddiftu)
        $baselineCallguddiftu = KarorQonnaWoggaa::with(['zone', 'woreda', 'cropType', 'crop'])
            ->whereIn('zone_id', $zones->pluck('id'))
            ->where('year', $selectedYearminusOne);

        if ($selectedCrop) {
            $baselineCallguddiftu->where('crop_id', $selectedCrop);
        }

        $baselineCallguddiftu = $baselineCallguddiftu->orderBy('season')->get();

        // Gabbina Biyyee current year
        $gabinabiyoCurrent = GabbinaBiyyee::with(['kebele'])
            ->whereIn('zone_id', $zones->pluck('id'))
            ->where('year', $selectedYear)
            ->get();

        $woreda = Woreda::with('kebeles')->find(Auth::user()->zone_id);
        $totallandarea = $woreda->kebeles->sum('total_area');

        // Gabbina Biyyee previous year (baseline)
        $gabinabiyoBaseline = GabbinaBiyyee::with(['woreda'])
            ->whereIn('zone_id', $zones->pluck('id'))
            ->where('year', $selectedYearminusOne)
            ->orderBy('compost_type')
            ->get();

        // Totals for current year
        $totals = [
            'total_land' => $reportData->flatten(3)->sum('land_hectares'),
            'total_production' => $reportData->flatten(3)->sum('production'),
        ];
        $totals['avg_productivity'] = $totals['total_land'] > 0
            ? $totals['total_production'] / $totals['total_land']
            : 0;

        $chartData = $this->prepareChartData($reportData, $zones);

        // Previous year crop plan data
        $previousQuery = KarorQonnaWoggaa::with(['kebele', 'cropType', 'crop'])
            ->whereIn('zone_id', $zones->pluck('id'))
            ->where('year', $selectedYearminusOne);

        if ($selectedSeason) {
            $previousQuery->where('season', $selectedSeason);
        }

        if ($selectedCrop) {
            $previousQuery->where('crop_id', $selectedCrop);
        }

        $previousYearData = $previousQuery
            ->orderBy('zone_id')
            ->orderBy('crop_type_id')
            ->orderBy('crop_id')
            ->get()
            ->groupBy(['zone_id', 'crop_type_id', 'crop_id']);

        $previousTotals = [
            'total_land' => $previousYearData->flatten(3)->sum('land_hectares'),
            'total_production' => $previousYearData->flatten(3)->sum('production'),
        ];
        $previousTotals['avg_productivity'] = $previousTotals['total_land'] > 0
            ? $previousTotals['total_production'] / $previousTotals['total_land']
            : 0;

        // Pie chart data
        $pieChartLabels = [];
        $pieChartData = [];

        foreach ($reportData as $kebeleData) {
            foreach ($kebeleData as $cropTypeData) {
                foreach ($cropTypeData as $cropData) {
                    $totalProduction = $cropData->sum('production');
                    $pieChartLabels[] = $cropData->first()->crop?->name;
                    $pieChartData[] = $totalProduction;
                }
            }
        }

        return view('reports.zone-report.gana-season-crops', [
            'woredas' => $zones,
            'reportData' => $reportData,
            'availableYears' => $availableYears,
            'availableSeasons' => $availableSeasons,
            'availableCrops' => $availableCrops,
            'selectedYear' => $selectedYear,
            'selectedSeason' => $selectedSeason,
            'selectedCrop' => $selectedCrop,
            'totals' => $totals,
            'chartData' => $chartData,
            'previousYearData' => $previousYearData,
            'previousTotals' => $previousTotals,
            'pieChartLabels' => $pieChartLabels,
            'pieChartData' => $pieChartData,
            'callguddiftu' => $callguddiftu,
            'baselineCallguddiftu' => $baselineCallguddiftu,
            'gabinabiyoCurrent' => $gabinabiyoCurrent,
            'gabinabiyoBaseline' => $gabinabiyoBaseline,
            'totallandarea' => $totallandarea,

        ]);
    }

    public function RegionCropAnalysis(Request $request)
    {
        $user = Auth::user();
        $selectedYear = $request->input('year', date('Y'));
        $selectedSeason = $request->input('season', 'Ganna');
        $selectedCrop = $request->input('crop_id');
        $selectedYearminusOne = $selectedYear - 1;

        $zones = Zone::where('id', $user->zone_id)->get();

        // Main Query: Group by Zone → Woreda → CropType → Crop
        $query = KarorQonnaWoggaa::with(['zone', 'woreda', 'kebele', 'cropType', 'crop'])
            ->where('year', $selectedYear);

        if ($selectedSeason) {
            $query->where('season', $selectedSeason);
        }

        if ($selectedCrop) {
            $query->where('crop_id', $selectedCrop);
        }

        $reportData = $query->orderBy('zone_id')
            ->orderBy('woreda_id')
            ->orderBy('crop_type_id')
            ->orderBy('crop_id')
            ->get()
            ->groupBy([
                'zone_id',
                'woreda_id',
                'crop_type_id',
                'crop_id'
            ]);

        // Load dropdown filter data
        $availableYears = KarorQonnaWoggaa::distinct()->pluck('year')->sort();

        $availableSeasons = KarorQonnaWoggaa::whereIn('zone_id', $zones->pluck('id'))
            ->distinct()->pluck('season')->unique()->sort();

        $availableCrops = Crop::whereHas('karorQonnaWoggaas', function ($q) use ($zones) {
            $q->whereIn('zone_id', $zones->pluck('id'));
        })->orderBy('name')->get();

        // Callguddiftu
        $callguddiftu = KarorQonnaWoggaa::with(['woreda', 'cropType', 'crop'])
            ->whereIn('zone_id', $zones->pluck('id'))
            ->where('year', $selectedYear);
        if ($selectedCrop) {
            $callguddiftu->where('crop_id', $selectedCrop);
        }
        $callguddiftu = $callguddiftu->orderBy('season')->get();

        // Baseline
        $baselineCallguddiftu = KarorQonnaWoggaa::with(['zone', 'woreda', 'cropType', 'crop'])
            ->whereIn('zone_id', $zones->pluck('id'))
            ->where('year', $selectedYearminusOne);
        if ($selectedCrop) {
            $baselineCallguddiftu->where('crop_id', $selectedCrop);
        }
        $baselineCallguddiftu = $baselineCallguddiftu->orderBy('season')->get();

        // Gabbina Biyyee current
        $gabinabiyoCurrent = GabbinaBiyyee::with('kebele')
            ->whereIn('zone_id', $zones->pluck('id'))
            ->where('year', $selectedYear)
            ->get();

        // Gabbina Biyyee previous
        $gabinabiyoBaseline = GabbinaBiyyee::with('woreda')
            ->whereIn('zone_id', $zones->pluck('id'))
            ->where('year', $selectedYearminusOne)
            ->orderBy('compost_type')
            ->get();

        // Totals for current year
        $totals = [
            'total_land' => $reportData->flatten(4)->sum('land_hectares'),
            'total_production' => $reportData->flatten(4)->sum('production'),
        ];
        $totals['avg_productivity'] = $totals['total_land'] > 0
            ? $totals['total_production'] / $totals['total_land']
            : 0;

        // Pie Chart
        $pieChartLabels = [];
        $pieChartData = [];

        foreach ($reportData as $zoneData) {
            foreach ($zoneData as $woredaData) {
                foreach ($woredaData as $cropTypeData) {
                    foreach ($cropTypeData as $cropData) {
                        $totalProduction = $cropData->sum('production');
                        $cropName = optional($cropData->first()->crop)->name;
                        if ($cropName) {
                            $pieChartLabels[] = $cropName;
                            $pieChartData[] = $totalProduction;
                        }
                    }
                }
            }
        }

        // Previous year data
        $previousYearData = KarorQonnaWoggaa::with(['kebele', 'cropType', 'crop'])
            ->whereIn('zone_id', $zones->pluck('id'))
            ->where('year', $selectedYearminusOne);
        if ($selectedSeason) {
            $previousYearData->where('season', $selectedSeason);
        }
        if ($selectedCrop) {
            $previousYearData->where('crop_id', $selectedCrop);
        }

        $previousYearData = $previousYearData
            ->orderBy('zone_id')
            ->orderBy('crop_type_id')
            ->orderBy('crop_id')
            ->get()
            ->groupBy(['zone_id', 'crop_type_id', 'crop_id']);

        $previousTotals = [
            'total_land' => $previousYearData->flatten(3)->sum('land_hectares'),
            'total_production' => $previousYearData->flatten(3)->sum('production'),
        ];
        $previousTotals['avg_productivity'] = $previousTotals['total_land'] > 0
            ? $previousTotals['total_production'] / $previousTotals['total_land']
            : 0;

        // Kebele land area
//        $totallandarea = 0;
        $zones = Zone::with('woredas.kebeles')->get();

        $totallandarea = 0;
        foreach ($zones as $zone) {
            foreach ($zone->woredas as $woreda) {
                $totallandarea += $woreda->kebeles->sum('total_area');
            }
        }
//        dd($totallandarea);

        return view('reports.region-report.gana-season-crops', [
            'zones' => $zones,
            'reportData' => $reportData,
            'availableYears' => $availableYears,
            'availableSeasons' => $availableSeasons,
            'availableCrops' => $availableCrops,
            'selectedYear' => $selectedYear,
            'selectedSeason' => $selectedSeason,
            'selectedCrop' => $selectedCrop,
            'totals' => $totals,
            'previousYearData' => $previousYearData,
            'previousTotals' => $previousTotals,
            'pieChartLabels' => $pieChartLabels,
            'pieChartData' => $pieChartData,
            'callguddiftu' => $callguddiftu,
            'baselineCallguddiftu' => $baselineCallguddiftu,
            'gabinabiyoCurrent' => $gabinabiyoCurrent,
            'gabinabiyoBaseline' => $gabinabiyoBaseline,
            'totallandarea' => $totallandarea,
        ]);
    }





    // public function woredaCropAnalysis(Request $request)
    // {
    //     $user = Auth::user();

    //     if (!$user->woreda_id) {
    //         abort(403, 'Only woreda administrators can access this report');
    //     }

    //     $selectedYear = $request->input('year', date('Y'));
    //     $selectedSeason = $request->input('season', 'Ganna');
    //     $selectedCrop = $request->input('crop_id');
    //     $selectedKebele = $request->input('kebele_id');
    //     $selectedYearminusOne = $selectedYear - 1;

    //     $kebeles = Kebele::where('woreda_id', $user->woreda_id)->get();
    //     //        dd($kebeles);

    //     // If a specific kebele is selected, use only that kebele, otherwise use all kebeles in the woreda
    //     $kebeleIds = $selectedKebele ? [$selectedKebele] : $kebeles->pluck('id');

    //     // Current year data
    //     $reportData = $this->getCropDataWoreda($kebeleIds, $selectedYear, $selectedSeason, $selectedCrop);

    //     // Totals for current year
    //     $totals = [
    //         'total_land' => $reportData->flatten(3)->sum('land_hectares'),
    //         'total_production' => $reportData->flatten(3)->sum('production'),
    //     ];
    //     $totals['avg_productivity'] = $totals['total_land'] > 0
    //         ? $totals['total_production'] / $totals['total_land']
    //         : 0;

    //     // Chart Data
    //     $chartData = $this->prepareChartDataWoreda($reportData, $kebeles);

    //     // Previous year data
    //     $previousYearData = $this->getCropData($kebeleIds, $selectedYearminusOne, $selectedSeason, $selectedCrop);

    //     $previousTotals = [
    //         'total_land' => $previousYearData->flatten(3)->sum('land_hectares'),
    //         'total_production' => $previousYearData->flatten(3)->sum('production'),
    //     ];
    //     $previousTotals['avg_productivity'] = $previousTotals['total_land'] > 0
    //         ? $previousTotals['total_production'] / $previousTotals['total_land']
    //         : 0;

    //     // Available filters
    //     $availableYears = KarorQonnaWoggaa::whereIn('kebele_id', $kebeles->pluck('id'))
    //         ->distinct()->pluck('year')->sort()->values();

    //     $availableSeasons = KarorQonnaWoggaa::whereIn('kebele_id', $kebeles->pluck('id'))
    //         ->distinct()->pluck('season')->unique()->sort()->values();

    //     $availableCrops = Crop::whereHas('karorQonnaWoggaas', function ($q) use ($kebeles) {
    //         $q->whereIn('kebele_id', $kebeles->pluck('id'));
    //     })->orderBy('name')->get();

    //     // Pie chart data
    //     $pieChartLabels = [];
    //     $pieChartData = [];
    //     $cropNames = [];

    //     foreach ($reportData as $kebeleData) {
    //         foreach ($kebeleData as $cropTypeData) {
    //             foreach ($cropTypeData as $cropData) {
    //                 $cropName = $cropData->first()->crop->name;
    //                 if (!in_array($cropName, $cropNames)) {
    //                     $cropNames[] = $cropName;
    //                     $pieChartLabels[] = $cropName;
    //                     $pieChartData[] = $cropData->sum('production');
    //                 }
    //             }
    //         }
    //     }

    //     $chartData = [
    //         'labels' => [],
    //         'currentYear' => [],
    //         'previousYear' => [],
    //         'productivityCurrent' => [],
    //         'productivityPrevious' => []
    //     ];

    //     foreach ($reportData as $kebeleId => $cropTypes) {
    //         foreach ($cropTypes as $cropTypeId => $crops) {
    //             foreach ($crops as $cropId => $records) {
    //                 $cropName = $records->first()->crop->name;

    //                 // Current year data
    //                 $totalLand = $records->sum('land_hectares');
    //                 $totalProduction = $records->sum('production');
    //                 $productivity = $totalLand > 0 ? $totalProduction / $totalLand : 0;

    //                 // Previous year data
    //                 $prevRecords = $previousYearData[$kebeleId][$cropTypeId][$cropId] ?? collect();
    //                 $prevTotalLand = $prevRecords->sum('land_hectares');
    //                 $prevTotalProduction = $prevRecords->sum('production');
    //                 $prevProductivity = $prevTotalLand > 0 ? $prevTotalProduction / $prevTotalLand : 0;

    //                 // Add to chart data
    //                 $chartData['labels'][] = $cropName;
    //                 $chartData['currentYear'][] = $totalProduction;
    //                 $chartData['previousYear'][] = $prevTotalProduction;
    //                 $chartData['productivityCurrent'][] = $productivity;
    //                 $chartData['productivityPrevious'][] = $prevProductivity;
    //             }
    //         }
    //     }


    //     return view('reports.woreda-report.gana-season-crops', [
    //         'kebeles' => $kebeles,
    //         'reportData' => $reportData,
    //         'availableYears' => $availableYears,
    //         'availableSeasons' => $availableSeasons,
    //         'availableCrops' => $availableCrops,
    //         'selectedYear' => $selectedYear,
    //         'selectedSeason' => $selectedSeason,
    //         'selectedCrop' => $selectedCrop,
    //         'selectedKebele' => $selectedKebele,
    //         'totals' => $totals,
    //         'chartData' => $chartData,
    //         'previousYearData' => $previousYearData,
    //         'previousTotals' => $previousTotals,
    //         'pieChartLabels' => $pieChartLabels,
    //         'pieChartData' => $pieChartData,
    //     ]);
    // }

    private function getCropData($kebeleIds, $year, $season, $cropId = null)
    {
        $query = KarorQonnaWoggaa::with(['crop', 'cropType', 'kebele'])
            ->whereIn('kebele_id', $kebeleIds)
            ->where('year', $year);

        if ($season) {
            $query->where('season', $season);
        }

        if ($cropId) {
            $query->where('crop_id', $cropId);
        }

        return $query->get()
            ->groupBy(['kebele_id', 'crop_type_id', 'crop_id']);
    }

    private function prepareChartDataWoreda($reportData, $kebeles)
    {
        $chartData = [];

        foreach ($reportData as $kebeleId => $cropTypes) {
            $kebeleName = $kebeles->find($kebeleId)->name;

            foreach ($cropTypes as $cropTypeId => $crops) {
                foreach ($crops as $cropId => $records) {
                    $totalLand = $records->sum('land_hectares');
                    $totalProduction = $records->sum('production');
                    $productivity = $totalLand > 0 ? $totalProduction / $totalLand : 0;

                    $chartData[] = [
                        'kebele' => $kebeleName,
                        'crop' => $records->first()->crop->name,
                        'land' => $totalLand,
                        'production' => $totalProduction,
                        'productivity' => $productivity
                    ];
                }
            }
        }

        return $chartData;
    }

    private function getCropDataWoreda($kebeleIds, $year, $season = null, $cropId = null)
    {
        $query = KarorQonnaWoggaa::with(['kebele', 'cropType', 'crop'])
            ->whereIn('kebele_id', $kebeleIds)
            ->where('year', $year);

        if ($season) {
            $query->where('season', $season);
        }
        if ($cropId) {
            $query->where('crop_id', $cropId);
        }

        return $query->orderBy('kebele_id')
            ->orderBy('crop_type_id')
            ->orderBy('crop_id')
            ->get()
            ->groupBy(['kebele_id', 'crop_type_id', 'crop_id']);
    }

    protected function prepareChartData($reportData, $woredas)
    {
        $cropDistribution = [];
        $woredaPerformance = [];

        foreach ($reportData as $woredaId => $cropTypes) {
            $woreda = $woredas->find($woredaId);
            if (!$woreda) {
                continue;
            }

            $woredaName = $woreda->name;
            $woredaPerformance[$woredaName] = [
                'land' => 0,
                'production' => 0
            ];

            foreach ($cropTypes as $cropTypeId => $crops) {
                foreach ($crops as $cropId => $records) {
                    $first = $records->first();
                    if (!$first) {
                        continue;
                    }

                    $cropName = $first->crop?->name ?? 'Unknown Crop';
                    $totalLand = $records->sum('land_hectares');
                    $totalProduction = $records->sum('production');

                    // Crop distribution
                    if (!isset($cropDistribution[$cropName])) {
                        $cropDistribution[$cropName] = 0;
                    }
                    $cropDistribution[$cropName] += $totalLand;

                    // Woreda performance
                    $woredaPerformance[$woredaName]['land'] += $totalLand;
                    $woredaPerformance[$woredaName]['production'] += $totalProduction;
                }
            }
        }

        return [
            'cropDistribution' => $cropDistribution,
            'woredaPerformance' => $woredaPerformance
        ];
    }

    protected function prepareChartWoredaData($reportData, $kebeles)
    {
        $cropDistribution = [];
        $kebelePerformance = [];

        foreach ($reportData as $kebeleId => $cropTypes) {
            $kebeleName = $kebeles->find($kebeleId)->name;
            $kebelePerformance[$kebeleName] = [
                'land' => 0,
                'production' => 0
            ];

            foreach ($cropTypes as $cropTypeId => $crops) {
                foreach ($crops as $cropId => $records) {
                    $cropName = $records->first()->crop?->name;
                    $totalLand = $records->sum('land_hectares');
                    $totalProduction = $records->sum('production');

                    // For crop distribution
                    if (!isset($cropDistribution[$cropName])) {
                        $cropDistribution[$cropName] = 0;
                    }
                    $cropDistribution[$cropName] += $totalLand;

                    // For kebele performance
                    $kebelePerformance[$kebeleName]['land'] += $totalLand;
                    $kebelePerformance[$kebeleName]['production'] += $totalProduction;
                }
            }
        }

        return [
            'cropDistribution' => $cropDistribution,
            'kebelePerformance' => $kebelePerformance
        ];
    }


//    public function ZoneCropAnalysis(Request $request)
//    {
//        $selectedYear = $request->get('year', date('Y')); // Default to current year
//        $selectedSeason = $request->get('season');
//        $selectedCrop = $request->get('crop_id');
//        $selectedKebele = $request->get('kebele_id'); // Get selected kebele
//
//        // Retrieve available kebeles, crops, seasons, and years
//        $availableYears = KarorQonnaWoggaa::distinct()->pluck('year');
//        $availableSeasons = KarorQonnaWoggaa::distinct()->pluck('season');
//        $availableCrops = Crop::all();
//        $kebeles = Kebele::all(); // Adjust as needed to retrieve kebeles for the user
//
//        // Filter data based on selected values
//        $query = KarorQonnaWoggaa::where('zone_id', auth()->user()->zone_id); // Example filter for the zone of the logged-in user
//
//        if ($selectedYear) {
//            $query->where('year', $selectedYear);
//        }
//        if ($selectedSeason) {
//            $query->where('season', $selectedSeason);
//        }
//        if ($selectedCrop) {
//            $query->where('crop_id', $selectedCrop);
//        }
//        if ($selectedKebele) {
//            $query->where('kebele_id', $selectedKebele);
//        }
//
//        $reportData = $query->get(); // Adjust based on your model relationships
//        $chartData = $reportData->groupBy('crop_id')->map(function ($group) {
//            return $group->sum('production_amount'); // Adjust this depending on your data and chart requirements
//        });
//
//        $pieChartData = $reportData->groupBy('crop_id')->map(function ($group) {
//            return $group->sum('production_amount'); // Adjust this depending on your data and chart requirements
//        });
//
//        $pieChartLabels = $pieChartData->keys()->map(function ($cropId) {
//            $crop = Crop::find($cropId); // Get crop name based on crop_id
//            return $crop ? $crop->name : 'Unknown Crop';
//        });
//
//        // Pass data to the view
//        return view('reports.zone-report.gana-season-crops', compact(
//            'selectedYear',
//            'selectedSeason',
//            'selectedCrop',
//            'selectedKebele',
//            'availableYears',
//            'availableSeasons',
//            'availableCrops',
//            'kebeles',
//            'reportData',
//            'pieChartData',
//            'chartData'
//        ));
//    }


}
