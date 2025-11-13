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

   public function index()
    {
        $user = Auth::user();
        
        $gabbinaBiyyee = GabbinaBiyyee::with(['kebele', 'woreda', 'zone', 'region'])
            ->where('sector_id', $user->sector_id)
            ->orderBy('year', 'desc')
            ->orderBy('compost_type')
            ->get()
            ->groupBy('year');

        return view('crop-plan.gabbina_biyyee_index', compact('gabbinaBiyyee'));
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
                'region_id'    => Auth::user()->region_id,
                'sector_id'    => Auth::user()->sector_id,
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

        return redirect()->route('sector.gabbina_biyyee.index')->with('success', 'Agricultural production data successfully saved!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $gabbinaBiyyee = GabbinaBiyyee::findOrFail($id);
        $compostTypes = CompostType::all();
        
        // Check if user owns this record
        if ($gabbinaBiyyee->sector_id !== Auth::user()->sector_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('crop-plan.gabbina_biyyee_edit', compact('gabbinaBiyyee', 'compostTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $gabbinaBiyyee = GabbinaBiyyee::findOrFail($id);

        // Check if user owns this record
        if ($gabbinaBiyyee->sector_id !== Auth::user()->sector_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'year' => 'required|string',
            'compost_type' => 'required|string',
            'common' => 'nullable|numeric|min:0',
            'individual' => 'nullable|numeric|min:0',
            'land_area' => 'nullable|numeric|min:0',
        ]);

        // Check for duplicate entry (excluding current record)
        $exists = GabbinaBiyyee::where('kebele_id', $gabbinaBiyyee->kebele_id)
                    ->where('year', $request->year)
                    ->where('compost_type', $request->compost_type)
                    ->where('id', '!=', $id)
                    ->exists();

        if ($exists) {
            return redirect()->back()->withErrors([
                'compost_type' => "A compost plan for '{$request->compost_type}' already exists for year {$request->year} in your kebele."
            ])->withInput();
        }

        $common = $request->common ?? 0;
        $individual = $request->individual ?? 0;
        $total = $common + $individual;

        $gabbinaBiyyee->update([
            'year' => $request->year,
            'compost_type' => $request->compost_type,
            'common' => $common,
            'individual' => $individual,
            'total' => $total,
            'land_area' => $request->compost_type === 'Nooraa Hojiirra oole' ? ($request->land_area ?? null) : null,
        ]);

        return redirect()->route('sector.gabbina_biyyee.index')->with('success', 'Compost plan updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $gabbinaBiyyee = GabbinaBiyyee::findOrFail($id);

        // Check if user owns this record
        if ($gabbinaBiyyee->sector_id !== Auth::user()->sector_id) {
            abort(403, 'Unauthorized action.');
        }

        $gabbinaBiyyee->delete();

        return redirect()->route('gabbina_biyyee.index')->with('success', 'Compost plan deleted successfully!');
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
