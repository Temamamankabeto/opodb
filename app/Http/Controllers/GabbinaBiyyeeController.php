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
            ->where('kebele_id', $user->kebele_id)
            ->orderBy('year', 'desc')
            ->orderBy('compost_type')
            ->get()
            ->groupBy('year');

        return view('crop-plan.gabbina_biyyee_index', compact('gabbinaBiyyee'));
    }
    
     
    

 
    public function create()
    {
        $compostTypes = CompostType::all();
        return view('crop-plan.gabbina_biyyee', compact('compostTypes'));
    }

    /**
     * Store the submitted data into database.
     */
 /**
 * Store the submitted data into database.
 */
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
        'entries.*.unit' => 'required|string', // Add unit validation
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
        $landArea = $entry['land_area'] ?? null;
        $unit = $entry['unit'] ?? 'Qt'; // Get the unit

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
            'land_area'    => $landArea,
            'unit'         => $unit, // Store the unit in database
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
        if ($gabbinaBiyyee->kebele_id !== Auth::user()->kebele_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('crop-plan.gabbina_biyyee_edit', compact('gabbinaBiyyee', 'compostTypes'));
    }
 

public function update(Request $request, $id)
{
    $gabbinaBiyyee = GabbinaBiyyee::findOrFail($id);

    // Check if user owns this record
    if ($gabbinaBiyyee->kebele_id !== Auth::user()->kebele_id) {
        abort(403, 'Unauthorized action.');
    }

    $request->validate([
        'year' => 'required|string',
        'compost_type' => 'required|string',
        'common' => 'nullable|numeric|min:0',
        'individual' => 'nullable|numeric|min:0',
        'land_area' => 'nullable|numeric|min:0',
        'unit' => 'required|string', // Add unit validation
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
    $landArea = $request->land_area ?? null;
    $unit = $request->unit ?? 'Qt'; // Get the unit

    $gabbinaBiyyee->update([
        'year' => $request->year,
        'compost_type' => $request->compost_type,
        'common' => $common,
        'individual' => $individual,
        'total' => $total,
        'land_area' => $landArea,
        'unit' => $unit, // Update the unit
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



    public function compostPlan($id)
{
    $user = Auth::user();

    // Retrieve the compost plan record; ensure user can only edit compost plans in their sector
    $gabbinaBiyyee = GabbinaBiyyee::where('id', $id)
        ->where('kebele_id', $user->kebele_id)
        ->firstOrFail();

    // Transform the data for compost distribution table
    $transformedCompostPlans = $this->transformCompostPlanDataForTable($gabbinaBiyyee);

    return view('compost.compostplan', compact('gabbinaBiyyee', 'transformedCompostPlans'));
}

private function transformCompostPlanDataForTable($gabbinaBiyyee)
{
    // Extract compost timeline data from compost distribution
    $compostDetails = $gabbinaBiyyee->compost_distribution_details ?? [];
    
    // Initialize timeline data structure
    $timelineData = [
        'quarters' => [],
        'months' => [],
        'weeks' => [],
        'total_distributed_quantity' => 0,
        'application_year' => $gabbinaBiyyee->year
    ];

    // Define Ethiopian months by quarter
    $ethiopianMonths = [
        'Q1' => ['Meskerem', 'Tikimt', 'Hidar'],
        'Q2' => ['Tahsas', 'Tir', 'Yekatit'],
        'Q3' => ['Megabit', 'Miyazia', 'Ginbot'],
        'Q4' => ['Sene', 'Hamle', 'Nehase', 'Pagumen']
    ];

    // Process compost details to extract timeline data
    if (!empty($compostDetails) && is_array($compostDetails)) {
        if (isset($compostDetails['weekly_distribution'])) {
            foreach ($compostDetails['weekly_distribution'] as $distribution) {
                if (isset($distribution['quantity']) && $distribution['quantity'] > 0) {
                    $quantity = floatval($distribution['quantity']);
                    $quarter = $distribution['quarter'] ?? '';
                    $month = $distribution['month'] ?? '';
                    $week = $distribution['week'] ?? '';
                    $unit = $distribution['unit'] ?? 'Qt';

                    // Add to total distributed quantity
                    $timelineData['total_distributed_quantity'] += $quantity;

                    // Add to quarter totals
                    if ($quarter && isset($ethiopianMonths[$quarter])) {
                        if (!isset($timelineData['quarters'][$quarter])) {
                            $timelineData['quarters'][$quarter] = ['quantity' => 0];
                        }
                        $timelineData['quarters'][$quarter]['quantity'] += $quantity;
                    }

                    // Add to month totals
                    if ($month) {
                        if (!isset($timelineData['months'][$month])) {
                            $timelineData['months'][$month] = ['quantity' => 0];
                        }
                        $timelineData['months'][$month]['quantity'] += $quantity;
                    }

                    // Add to week totals
                    if ($week && $month) {
                        $weekKey = $month . ' ' . $week;
                        if (!isset($timelineData['weeks'][$weekKey])) {
                            $timelineData['weeks'][$weekKey] = ['quantity' => 0];
                        }
                        $timelineData['weeks'][$weekKey]['quantity'] += $quantity;
                    }
                }
            }
        }
    }

    // Create the transformed compost plan structure
    return [
        [
            'planning_year' => $gabbinaBiyyee->year,
            'application_year' => $gabbinaBiyyee->year,
            'compost_name' => $gabbinaBiyyee->compost_type,
            'compost_type' => $gabbinaBiyyee->compost_type,
            'total_quantity' => $gabbinaBiyyee->total,
            'unit' => $gabbinaBiyyee->unit,
            'compost_details' => $compostDetails,
            'timeline_data' => $timelineData
        ]
    ];
}

public function updateCompostPlan(Request $request, $id)
{
    $user = Auth::user();

    // Retrieve the compost plan record; ensure user can only edit compost plans in their sector
    $gabbinaBiyyee = GabbinaBiyyee::where('id', $id)
        ->where('kebele_id', $user->kebele_id)
        ->firstOrFail();

    $validated = $request->validate([
        'compost_distribution_details' => 'nullable|json'
    ]);

    // Update compost distribution details in compost plan table
    $gabbinaBiyyee->update([
        'compost_distribution_details' => $request->has('compost_distribution_details') 
            ? json_decode($request->compost_distribution_details, true) 
            : null
    ]);

    return redirect()->route('sector.gabbina_biyyee.index')
        ->with('success', 'Compost distribution plan updated successfully!');
}



public function compostTime(Request $request)
{
    $user = Auth::user();

    // Get compost plans for the user's kebele
    $gabbinaBiyyee = GabbinaBiyyee::where('kebele_id', $user->kebele_id)
        ->orderBy('year', 'desc')
        ->orderBy('compost_type')
        ->get();

    // Transform data for the Excel-like display
    $transformedCompostPlans = $gabbinaBiyyee->map(function ($compost) {
        $compostDistributionDetails = $compost->compost_distribution_details ?? [];

        // Extract timeline data from compost_distribution_details
        $timelineData = $this->extractCompostTimelineData($compostDistributionDetails, $compost->year);

        return [
            'id' => $compost->id,
            'name' => $compost->compost_type,
            'compost_type' => $compost->compost_type,
            'unit' => $compost->unit,
            'application_year' => $compost->year,
            'compost_distribution_details' => $compostDistributionDetails,
            'timeline_data' => $timelineData,
            'total_quantity' => $compost->total,
            'year' => $compost->year,
            'common' => $compost->common,
            'individual' => $compost->individual,
            'land_area' => $compost->land_area,
            'updated_at' => $compost->updated_at->format('Y-m-d H:i:s'),
        ];
    });

    // If AJAX request, return JSON
    if ($request->ajax()) {
        return response()->json([
            'compost_plans' => $transformedCompostPlans,
            'total' => $transformedCompostPlans->count()
        ]);
    }

    return view('compost.composttime', compact('gabbinaBiyyee', 'transformedCompostPlans'));
}

/**
 * Extract compost timeline data from compost_distribution_details
 */
private function extractCompostTimelineData($compostDistributionDetails, $planYear)
{
    $timelineData = [
        'application_year' => $planYear,
        'quarters' => [],
        'months' => [],
        'weeks' => [],
        'total_distributed_quantity' => 0,
        'compost_breakdown' => []
    ];

    if (empty($compostDistributionDetails)) {
        return $timelineData;
    }

    // Handle different possible structures of compost_distribution_details
    if (is_array($compostDistributionDetails)) {
        // Check for application_year in the data
        if (isset($compostDistributionDetails['application_year'])) {
            $timelineData['application_year'] = $compostDistributionDetails['application_year'];
        }

        // Structure 1: Direct array with quarters, months, weeks
        if (isset($compostDistributionDetails['quarters'])) {
            $timelineData = array_merge($timelineData, $this->extractCompostFromStructuredData($compostDistributionDetails));
        }
        // Structure 2: Array of weekly distributions
        else if (isset($compostDistributionDetails[0]) && is_array($compostDistributionDetails[0])) {
            $timelineData = array_merge($timelineData, $this->extractCompostFromWeeklyDistribution($compostDistributionDetails));
        }
        // Structure 3: Legacy format with nested structure
        else if (isset($compostDistributionDetails['weekly_distribution'])) {
            $timelineData = array_merge($timelineData, $this->extractCompostFromWeeklyDistribution($compostDistributionDetails['weekly_distribution']));
        }

        // Also check for application_year in nested structures
        if (isset($compostDistributionDetails['application_year'])) {
            $timelineData['application_year'] = $compostDistributionDetails['application_year'];
        }
    }

    return $timelineData;
}

/**
 * Extract from structured data with quarters, months, weeks for compost
 */
private function extractCompostFromStructuredData($data)
{
    $timelineData = [
        'quarters' => [],
        'months' => [],
        'weeks' => [],
        'total_distributed_quantity' => 0,
        'compost_breakdown' => []
    ];

    // Check for application_year in structured data
    if (isset($data['application_year'])) {
        $timelineData['application_year'] = $data['application_year'];
    }

    // Extract quarters data
    if (isset($data['quarters']) && is_array($data['quarters'])) {
        foreach ($data['quarters'] as $quarterName => $quarterData) {
            if (isset($quarterData['quantity']) && $quarterData['quantity'] > 0) {
                $timelineData['quarters'][$quarterName] = [
                    'quantity' => $quarterData['quantity'],
                    'checked' => $quarterData['checked'] ?? false
                ];
                $timelineData['total_distributed_quantity'] += $quarterData['quantity'];
            }
        }
    }

    // Extract months data
    if (isset($data['months']) && is_array($data['months'])) {
        foreach ($data['months'] as $monthName => $monthData) {
            if (isset($monthData['quantity']) && $monthData['quantity'] > 0) {
                $timelineData['months'][$monthName] = [
                    'quantity' => $monthData['quantity'],
                    'quarter' => $monthData['quarter'] ?? 'Unknown',
                    'checked' => $monthData['checked'] ?? false
                ];
            }
        }
    }

    // Extract weeks data
    if (isset($data['weeks']) && is_array($data['weeks'])) {
        foreach ($data['weeks'] as $weekKey => $weekData) {
            if (isset($weekData['quantity']) && $weekData['quantity'] > 0) {
                $timelineData['weeks'][$weekKey] = [
                    'quantity' => $weekData['quantity'],
                    'month' => $weekData['month'] ?? 'Unknown',
                    'quarter' => $weekData['quarter'] ?? 'Unknown',
                    'week_number' => $weekData['week_number'] ?? 1,
                    'compost_type' => $weekData['compost_type'] ?? 'Unknown',
                    'unit' => $weekData['unit'] ?? 'Qt',
                    'checked' => $weekData['checked'] ?? false
                ];
                
                // Track compost breakdown
                $compostType = $weekData['compost_type'] ?? 'Unknown';
                if (!isset($timelineData['compost_breakdown'][$compostType])) {
                    $timelineData['compost_breakdown'][$compostType] = [
                        'quantity' => 0,
                        'unit' => $weekData['unit'] ?? 'Qt'
                    ];
                }
                $timelineData['compost_breakdown'][$compostType]['quantity'] += $weekData['quantity'];
            }
        }
    }

    return $timelineData;
}

/**
 * Extract from weekly distribution array for compost
 */
private function extractCompostFromWeeklyDistribution($weeklyDistribution)
{
    $timelineData = [
        'quarters' => [],
        'months' => [],
        'weeks' => [],
        'total_distributed_quantity' => 0,
        'compost_breakdown' => []
    ];

    foreach ($weeklyDistribution as $week) {
        if (is_array($week)) {
            $quantity = $week['quantity'] ?? 0;
            $quarter = $week['quarter'] ?? ($week['year'] ?? 'Unknown');
            $month = $week['month'] ?? 'Unknown';
            $weekNumber = $week['week'] ?? ($week['week_number'] ?? 1);
            $compostType = $week['compost_type'] ?? 'Unknown';
            $unit = $week['unit'] ?? 'Qt';

            // Check for application_year in weekly data
            if (isset($week['application_year'])) {
                $timelineData['application_year'] = $week['application_year'];
            }

            if ($quantity > 0) {
                // Add to total
                $timelineData['total_distributed_quantity'] += $quantity;

                // Track quarter
                if (!isset($timelineData['quarters'][$quarter])) {
                    $timelineData['quarters'][$quarter] = ['quantity' => 0];
                }
                $timelineData['quarters'][$quarter]['quantity'] += $quantity;

                // Track month
                if (!isset($timelineData['months'][$month])) {
                    $timelineData['months'][$month] = ['quantity' => 0, 'quarter' => $quarter];
                }
                $timelineData['months'][$month]['quantity'] += $quantity;

                // Track week
                $weekKey = $month . '_week_' . $weekNumber;
                $timelineData['weeks'][$weekKey] = [
                    'quantity' => $quantity,
                    'month' => $month,
                    'quarter' => $quarter,
                    'week_number' => $weekNumber,
                    'compost_type' => $compostType,
                    'unit' => $unit
                ];

                // Track compost breakdown
                if (!isset($timelineData['compost_breakdown'][$compostType])) {
                    $timelineData['compost_breakdown'][$compostType] = [
                        'quantity' => 0,
                        'unit' => $unit
                    ];
                }
                $timelineData['compost_breakdown'][$compostType]['quantity'] += $quantity;
            }
        }
    }

    return $timelineData;
}

/**
 * Export compost plans to Excel
 */
public function exportCompostExcel()
{
    $user = Auth::user();

    $gabbinaBiyyee = GabbinaBiyyee::where('kebele_id', $user->kebele_id)
        ->orderBy('year', 'desc')
        ->orderBy('compost_type')
        ->get();

    $fileName = 'compost_plans_' . date('Y-m-d') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    ];

    $callback = function() use ($gabbinaBiyyee) {
        $file = fopen('php://output', 'w');

        // Header row
        fputcsv($file, [
            'Compost Type', 'Year', 'Available Quantity', 'Unit',
            'Total Distributed', 'Remaining', 'Common', 'Individual', 'Land Area (ha)'
        ]);

        // Data rows
        foreach ($gabbinaBiyyee as $compost) {
            $totalDistributed = 0;
            
            if ($compost->compost_distribution_details && is_array($compost->compost_distribution_details)) {
                if (isset($compost->compost_distribution_details['weekly_distribution'])) {
                    foreach ($compost->compost_distribution_details['weekly_distribution'] as $distribution) {
                        $totalDistributed += $distribution['quantity'] ?? 0;
                    }
                }
            }

            $remaining = $compost->total - $totalDistributed;

            fputcsv($file, [
                $compost->compost_type,
                $compost->year,
                $compost->total,
                $compost->unit,
                $totalDistributed,
                $remaining,
                $compost->common ?? 0,
                $compost->individual ?? 0,
                $compost->land_area ?? 0
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

    
}