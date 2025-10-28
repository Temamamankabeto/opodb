<?php

namespace App\Http\Controllers;

use App\Models\CompostType;
use App\Models\CropType;
use App\Models\GabbinaBiyyee;
use App\Models\Kebele;
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GabbinaBiyyeeController extends Controller
{

    public function index(Request $request)
    {
        $kebeleId = Auth::user()->kebele_id;
        $compostTypes = CompostType::all();
        
        // Get all available years with data for this kebele
        $availableYears = GabbinaBiyyee::where('kebele_id', $kebeleId)
            ->distinct()
            ->pluck('year')
            ->sort()
            ->values()
            ->toArray();
            
        $selectedYear = $request->input('year', end($availableYears) ?: null);
        $baselineYear = $this->getBaselineYear($availableYears, $selectedYear);
        
        // Initialize empty arrays to prevent undefined variable errors
        $yearsData = [];
        $chartLabels = [];
        $chartData = [];
    
        if ($selectedYear) {
            $yearsToShow = array_unique([$baselineYear, $selectedYear]);
            sort($yearsToShow);
            
            foreach ($yearsToShow as $year) {
                if ($year) {
                    $yearData = GabbinaBiyyee::where('kebele_id', $kebeleId)
                        ->where('year', $year)
                        ->get()
                        ->keyBy(function($item) {
                            return $item->compost_type; // Make sure this matches your relationship/column name
                        });
                    
                    // Initialize empty entries for all compost types
                    foreach ($compostTypes as $type) {
                        if (!isset($yearData[$type->name])) {
                            $yearData[$type->name] = (object)[
                                'common' => 0,
                                'individual' => 0,
                                'land_area' => 0
                            ];
                        }
                    }
                    
                    $yearsData[$year] = $yearData;
                }
            }
    
            $chartLabels = array_keys($yearsData);
    
            foreach ($compostTypes as $type) {
                $typeName = $type->name;
                $chartData[$typeName] = [
                    'common' => [],
                    'individual' => [],
                    'total' => [],
                    'land' => [],
                    'change' => null,
                ];
    
                foreach ($chartLabels as $year) {
                    $data = $yearsData[$year][$typeName] ?? (object)[
                        'common' => 0,
                        'individual' => 0,
                        'land_area' => 0
                    ];
    
                    $common = $data->common ?? 0;
                    $individual = $data->individual ?? 0;
                    $total = $common + $individual;
                    $land = $data->land_area ?? 0;
    
                    $chartData[$typeName]['common'][] = $common;
                    $chartData[$typeName]['individual'][] = $individual;
                    $chartData[$typeName]['total'][] = $total;
                    $chartData[$typeName]['land'][] = $land;
                }
    
                // Calculate % change from baseline to selected year
                if (count($chartLabels) == 2) {
                    $baselineValue = $chartData[$typeName]['total'][0] ?? 0;
                    $currentValue = $chartData[$typeName]['total'][1] ?? 0;
                    $chartData[$typeName]['change'] = $this->calculateChange($currentValue, $baselineValue);
                }
            }
        }
    
        return view('reports.kebele-report.gabbina-biyo.kebele_report', compact(
            'compostTypes',
            'availableYears',
            'selectedYear',
            'baselineYear',
            'yearsData',
            'chartLabels',
            'chartData'
        ));
    }
    
    protected function getBaselineYear($availableYears, $selectedYear)
    {
        if (!$selectedYear || count($availableYears) < 2) {
            return null;
        }
        
        $selectedIndex = array_search($selectedYear, $availableYears);
        
        if ($selectedIndex === false || $selectedIndex === 0) {
            return null;
        }
        
        return $availableYears[$selectedIndex - 1];
    }
    
    public function calculateChange($current, $baseline)
    {
        if ($baseline == 0) {
            return $current == 0 ? 0 : 100; // 100% increase if baseline was 0
        }
        
        return (($current - $baseline) / $baseline) * 100;
    }


    public function woreda(Request $request)
    {
        // Get the logged-in user's woreda ID
        $woredaId = Auth::user()->woreda_id;
            
        // Get all kebeles in this woreda
        $kebeles = Kebele::where('woreda_id', $woredaId)->pluck('id');
        $compostTypes = CompostType::all();
        
        // Get all available years with data for this woreda
        $availableYears = GabbinaBiyyee::whereIn('kebele_id', $kebeles)
            ->distinct()
            ->pluck('year')
            ->sort()
            ->values()
            ->toArray();
            
        $selectedYear = $request->input('year', end($availableYears) ?: null);
        $baselineYear = $this->getBaselineYear2($availableYears, $selectedYear);
        
        // Initialize data structures
        $yearsData = [];
        $kebeleSummaries = [];
        $reportData = [];
        $chartLabels = [];  // Initialize chartLabels here
    
        if ($selectedYear && $baselineYear) {
            $yearsToShow = [$baselineYear, $selectedYear];
            
            // Populate chartLabels with years to be displayed
            $chartLabels = array_map('strval', $yearsToShow); // Convert to string if necessary
            
            // Process each kebele
            foreach ($kebeles as $kebeleId) {
                $kebeleData = Kebele::find($kebeleId);
                $kebeleSummaries[$kebeleId] = [
                    'name' => $kebeleData->name,
                    'data' => []
                ];
                
                foreach ($yearsToShow as $year) {
                    $yearData = GabbinaBiyyee::where('kebele_id', $kebeleId)
                        ->where('year', $year)
                        ->get()
                        ->keyBy('compost_type');
                    
                    // Initialize empty entries for all compost types
                    foreach ($compostTypes as $type) {
                        if (!isset($yearData[$type->name])) {
                            $yearData[$type->name] = (object)[
                                'common' => 0,
                                'individual' => 0,
                                'land_area' => 0
                            ];
                        }
                    }
                    
                    $kebeleSummaries[$kebeleId]['data'][$year] = $yearData;
                }
            }
            
            // Calculate consolidated woreda data
            foreach ($yearsToShow as $year) {
                $woredaYearData = [];
                
                foreach ($compostTypes as $type) {
                    $typeName = $type->name;
                    $woredaYearData[$typeName] = (object)[
                        'common' => 0,
                        'individual' => 0,
                        'land_area' => 0
                    ];
                    
                    // Sum up data from all kebeles
                    foreach ($kebeles as $kebeleId) {
                        if (isset($kebeleSummaries[$kebeleId]['data'][$year][$typeName])) {
                            $kebeleData = $kebeleSummaries[$kebeleId]['data'][$year][$typeName];
                            $woredaYearData[$typeName]->common += $kebeleData->common ?? 0;
                            $woredaYearData[$typeName]->individual += $kebeleData->individual ?? 0;
                            $woredaYearData[$typeName]->land_area += $kebeleData->land_area ?? 0;
                        }
                    }
                }
                
                $yearsData[$year] = $woredaYearData;
            }
    
            // Prepare report data for the view
            foreach ($compostTypes as $type) {
                $typeName = $type->name;
                $baseline = $yearsData[$baselineYear][$typeName] ?? null;
                $current = $yearsData[$selectedYear][$typeName] ?? null;
    
                $reportData[$typeName] = [
                    'baseline_common' => $baseline->common ?? 0,
                    'baseline_individual' => $baseline->individual ?? 0,
                    'current_common' => $current->common ?? 0,
                    'current_individual' => $current->individual ?? 0,
                    'baseline_land' => $baseline->land_area ?? 0,
                    'current_land' => $current->land_area ?? 0,
                    'change' => $this->calculateChange2(
                        ($current->common ?? 0) + ($current->individual ?? 0),
                        ($baseline->common ?? 0) + ($baseline->individual ?? 0)
                    ),
                ];
            }
        }
    
        // Return the view with the necessary data
        return view('reports.woreda-report.gabbina-biyo.woreda_report', [
            'compostTypes' => $compostTypes,
            'availableYears' => $availableYears,
            'selectedYear' => $selectedYear,
            'baselineYear' => $baselineYear,
            'reportData' => $reportData,
            'kebeleSummaries' => $kebeleSummaries,
            'chartLabels' => $chartLabels,  // Pass chartLabels to the view
            'hasData' => !empty($reportData)
        ]);
    }
    

protected function getBaselineYear2($availableYears, $selectedYear)
    {
        if (!$selectedYear || count($availableYears) < 2) {
            return null;
        }
        
        $selectedIndex = array_search($selectedYear, $availableYears);
        
        return ($selectedIndex !== false && $selectedIndex > 0) 
            ? $availableYears[$selectedIndex - 1] 
            : null;
    }

    protected function calculateChange2($current, $baseline)
    {
        if ($baseline == 0) {
            return $current == 0 ? 0 : 100;
        }
        
        return (($current - $baseline) / $baseline) * 100;
    }
    public function create()
    {
        $compostTypes = CompostType::all();
        
        return view('crop-plan.gabbina_biyyee', compact('compostTypes'));
    }

    /**
     * Store the submitted data into database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|string',
            'entries' => 'required|array|min:1',
            'entries.*.compost_type' => 'required|string',
            'entries.*.common' => 'nullable|numeric|min:0',
            'entries.*.individual' => 'nullable|numeric|min:0',
            'entries.*.total' => 'nullable|numeric|min:0',
            'entries.*.land_area' => 'nullable|numeric|min:0',
        ]);
    
        $kebeleId = Auth::user()->kebele_id;
        $year = $request->year;
    
        foreach ($request->entries as $entry) {
            $compostType = $entry['compost_type'];
    
            // Check if entry for same kebele, year, and compost type already exists
            $exists = GabbinaBiyyee::where('kebele_id', $kebeleId)
                        ->where('year', $year)
                        ->where('compost_type', $compostType)
                        ->exists();
    
            if ($exists) {
                return redirect()->back()->withErrors([
                    'entries' => "A compost plan for '{$compostType}' already exists for year {$year} in your kebele."
                ])->withInput();
            }
        }
    
        foreach ($request->entries as $entry) {
            $common = $entry['common'] ?? 0;
            $individual = $entry['individual'] ?? 0;
            $total = $common + $individual;
    
            GabbinaBiyyee::create([
                'created_by'   => Auth::id(),
                'kebele_id'    => $kebeleId,
                'woreda_id'    => Auth::user()->woreda_id,
                'zone_id'      => Auth::user()->zone_id,
                'phone'        => Auth::user()->phone,
                'year'         => $year,
                'compost_type' => $entry['compost_type'],
                'common'       => $common,
                'individual'   => $individual,
                'total'        => $total,
                'land_area'    => $entry['compost_type'] === 'Nooraa Hojiirra oole' ? ($entry['land_area'] ?? null) : null,
            ]);
        }
        return redirect()->back()->with('success', 'Agricultural production data successfully saved!');
    }


    public function kebeleReport(Request $request)
    {
        $selectedYear = $request->input('year');
        $kebeleId = Auth::user()->kebele_id;
    
        $years = GabbinaBiyyee::select('year')->distinct()->pluck('year')->sortDesc();
    
        $baselineYear = $selectedYear ? ((int) $selectedYear - 1) : null;
    
        $currentYearData = [];
        $baselineYearData = [];
    
        if ($selectedYear) {
            // Group by compost_type for current year
            $currentYearData = GabbinaBiyyee::where('kebele_id', $kebeleId)
                ->where('year', $selectedYear)
                ->get()
                ->groupBy('compost_type');
    
            // Group by compost_type for baseline year
            $baselineYearData = GabbinaBiyyee::where('kebele_id', $kebeleId)
                ->where('year', $baselineYear)
                ->get()
                ->groupBy('compost_type');
        }
    
        return view('reports.kebele-report.gabbina-biyo.kebele_report', compact(
            'years', 'currentYearData', 'baselineYearData', 'selectedYear', 'baselineYear'
        ));
    }
}
