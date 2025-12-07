<?php

namespace App\Http\Controllers;

use App\Models\MaterialSupply;
use App\Models\Mechanization;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MechanizationController extends Controller
{
    // Show all mechanizations
    public function index()
    {
        $user = Auth::user();
        $mechanizations = mechanization::where('kebele_id', $user->kebele_id)
            
            ->latest()
            ->paginate(10);

        return view('mechanizations.index', compact('mechanizations'));
    }

    // Show create form
    public function create()
    {
        return view('mechanizations.create');
    }

    // Store new mechanization
public function store(Request $request)
{
    $user = Auth::user();

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'mechanization_type' => 'nullable|string|max:255',
        'amount' => 'nullable|numeric',
        'unit' => 'nullable|string|max:50',
        'year' => 'required|digits:4|integer',
        'loan_name.*' => 'nullable|string|max:255',
        'loan_type.*' => 'nullable|string|max:255',
        'loan_amount.*' => 'nullable|numeric',
        'loan_duration.*' => 'nullable|integer',
        'loan_interest.*' => 'nullable|numeric',
    ]);

    $loanDetails = [];
    
    // Process loan data only if loan_type exists and has values
    if ($request->has('loan_type') && is_array($request->loan_type)) {
        foreach ($request->loan_type as $index => $type) {
            // Only create loan entry if loan_type is provided
            if (!empty(trim($type))) {
                $loanDetails[] = [
                    'loan_name' => $request->loan_name[$index] ?? null,
                    'loan_type' => $type,
                    'loan_amount' => $request->loan_amount[$index] ?? null,
                    'loan_duration' => $request->loan_duration[$index] ?? null,
                    'loan_interest' => $request->loan_interest[$index] ?? null,
                ];
            }
        }
    }

    // Get the first loan for individual columns (backward compatibility)
    $firstLoan = !empty($loanDetails) ? $loanDetails[0] : null;

    Mechanization::create([
        'name' => $validated['name'],
        'mechanization_type' => $validated['mechanization_type'] ?? null,
        'amount' => $validated['amount'] ?? null,
        'unit' => $validated['unit'] ?? null,
        'year' => $validated['year'],
        // Individual loan columns (first loan only)
        'loan_name' => $firstLoan['loan_name'] ?? null,
        'loan_type' => $firstLoan['loan_type'] ?? null,
        'loan_amount' => $firstLoan['loan_amount'] ?? null,
        'loan_duration' => $firstLoan['loan_duration'] ?? null,
        'loan_interest' => $firstLoan['loan_interest'] ?? null,
        // All loans in JSON
        'loan_details' => !empty($loanDetails) ? json_encode($loanDetails) : null,
        'sector_id' => $user->sector_id,
        'kebele_id' => $user->kebele_id,
        'woreda_id' => $user->woreda_id,
        'zone_id' => $user->zone_id,
        'region_id' => $user->region_id,
    ]);

    return redirect()->route('sector.mechanizations.index')->with('success', 'Mechanization added successfully.');
}


    // Show edit form
    public function edit($id)
    {
        $mechanization = Mechanization::findOrFail($id);
        return view('mechanizations.edit', compact('mechanization'));
    }

    // Update existing record
   public function update(Request $request, $id)
{
    $mechanization = Mechanization::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'mechanization_type' => 'nullable|string|max:255',
        'amount' => 'nullable|numeric',
        'unit' => 'nullable|string|max:50',
        'year' => 'required|digits:4|integer',
        'loan_name.*' => 'nullable|string|max:255',
        'loan_type.*' => 'nullable|string|max:255',
        'loan_amount.*' => 'nullable|numeric',
        'loan_duration.*' => 'nullable|integer',
        'loan_interest.*' => 'nullable|numeric',
    ]);

    $loanDetails = [];
    
    // Process loan data
    if ($request->has('loan_type') && is_array($request->loan_type)) {
        foreach ($request->loan_type as $index => $type) {
            // Only create loan entry if loan_type is provided
            if (!empty(trim($type))) {
                $loanDetails[] = [
                    'loan_name' => $request->loan_name[$index] ?? null,
                    'loan_type' => $type,
                    'loan_amount' => $request->loan_amount[$index] ?? null,
                    'loan_duration' => $request->loan_duration[$index] ?? null,
                    'loan_interest' => $request->loan_interest[$index] ?? null,
                ];
            }
        }
    }

    // Get the first loan for individual columns (backward compatibility)
    $firstLoan = !empty($loanDetails) ? $loanDetails[0] : null;

    $updateData = [
        'name' => $validated['name'],
        'mechanization_type' => $validated['mechanization_type'] ?? null,
        'amount' => $validated['amount'] ?? null,
        'unit' => $validated['unit'] ?? null,
        'year' => $validated['year'],
        // Individual loan columns (first loan only)
        'loan_name' => $firstLoan['loan_name'] ?? null,
        'loan_type' => $firstLoan['loan_type'] ?? null,
        'loan_amount' => $firstLoan['loan_amount'] ?? null,
        'loan_duration' => $firstLoan['loan_duration'] ?? null,
        'loan_interest' => $firstLoan['loan_interest'] ?? null,
        // All loans in JSON
        'loan_details' => !empty($loanDetails) ? json_encode($loanDetails) : null,
    ];

    $mechanization->update($updateData);

    return redirect()->route('sector.mechanizations.index')->with('success', 'Mechanization updated successfully.');
}

    // Show single record
    public function show($id)
    {
        $mechanization = Mechanization::findOrFail($id);
        return view('mechanizations.show', compact('mechanization'));
    }

    // Delete
    public function destroy($id)
    {
        Mechanization::findOrFail($id)->delete();
        return redirect()->route('sector.mechanizations.index')->with('success', 'Mechanization deleted successfully.');
    }




  public function mechanizationPlan($id)
{
    $user = Auth::user();

    // Retrieve the mechanization record; ensure user can only edit mechanizations in their sector
    $mechanization = Mechanization::where('id', $id)
        ->where('kebele_id', $user->kebele_id)
        ->firstOrFail();

    // Transform the data for mechanization distribution table
    $transformedMechanizationPlans = $this->transformMechanizationPlanDataForTable($mechanization);

    return view('mechanizations.mechanizationplan', compact('mechanization', 'transformedMechanizationPlans'));
}

private function transformMechanizationPlanDataForTable($mechanization)
{
    // Extract mechanization timeline data from mechanization distribution
    $mechanizationDetails = $mechanization->mechanization_distribution_details ?? [];
    
    // Initialize timeline data structure
    $timelineData = [
        'quarters' => [],
        'months' => [],
        'weeks' => [],
        'total_distributed_number' => 0, // Changed from hours to number
        'application_year' => $mechanization->year
    ];

    // Define Ethiopian months by quarter
    $ethiopianMonths = [
        'Q1' => ['Meskerem', 'Tikimt', 'Hidar'],
        'Q2' => ['Tahsas', 'Tir', 'Yekatit'],
        'Q3' => ['Megabit', 'Miyazia', 'Ginbot'],
        'Q4' => ['Sene', 'Hamle', 'Nehase', 'Pagumen']
    ];

    // Process mechanization details to extract timeline data
    if (!empty($mechanizationDetails) && is_array($mechanizationDetails)) {
        if (isset($mechanizationDetails['weekly_distribution'])) {
            foreach ($mechanizationDetails['weekly_distribution'] as $distribution) {
                if (isset($distribution['number']) && $distribution['number'] > 0) { // Changed from hours to number
                    $number = floatval($distribution['number']); // Changed from hours to number
                    $quarter = $distribution['quarter'] ?? '';
                    $month = $distribution['month'] ?? '';
                    $week = $distribution['week'] ?? '';
                    $unit = $distribution['unit'] ?? 'number'; // Changed from hours to number

                    // Add to total distributed number
                    $timelineData['total_distributed_number'] += $number; // Changed from hours to number

                    // Add to quarter totals
                    if ($quarter && isset($ethiopianMonths[$quarter])) {
                        if (!isset($timelineData['quarters'][$quarter])) {
                            $timelineData['quarters'][$quarter] = ['number' => 0]; // Changed from hours to number
                        }
                        $timelineData['quarters'][$quarter]['number'] += $number; // Changed from hours to number
                    }

                    // Add to month totals
                    if ($month) {
                        if (!isset($timelineData['months'][$month])) {
                            $timelineData['months'][$month] = ['number' => 0]; // Changed from hours to number
                        }
                        $timelineData['months'][$month]['number'] += $number; // Changed from hours to number
                    }

                    // Add to week totals
                    if ($week && $month) {
                        $weekKey = $month . ' ' . $week;
                        if (!isset($timelineData['weeks'][$weekKey])) {
                            $timelineData['weeks'][$weekKey] = ['number' => 0]; // Changed from hours to number
                        }
                        $timelineData['weeks'][$weekKey]['number'] += $number; // Changed from hours to number
                    }
                }
            }
        }
    }

    // Create the transformed mechanization plan structure
    return [
        [
            'planning_year' => $mechanization->year,
            'application_year' => $mechanization->year,
            'mechanization_name' => $mechanization->name,
            'mechanization_type' => $mechanization->mechanization_type,
            'amount' => $mechanization->amount,
            'unit' => $mechanization->unit,
            'mechanization_details' => $mechanizationDetails,
            'timeline_data' => $timelineData
        ]
    ];
}

public function updateMechanizationPlan(Request $request, $id)
{
    $user = Auth::user();

    // Retrieve the mechanization record; ensure user can only edit mechanizations in their sector
    $mechanization = Mechanization::where('id', $id)
        ->where('kebele_id', $user->kebele_id)
        ->firstOrFail();

    $validated = $request->validate([
        'mechanization_distribution_details' => 'nullable|json'
    ]);

    // Update mechanization distribution details in mechanization table
    $mechanization->update([
        'mechanization_distribution_details' => $request->has('mechanization_distribution_details') 
            ? json_decode($request->mechanization_distribution_details, true) 
            : null
    ]);

    return redirect()->route('sector.mechanizations.index')
        ->with('success', 'Mechanization plan updated successfully!');
}





public function mechanizationTime(Request $request)
{
    $user = Auth::user();

    // Get mechanizations for the user's kebele
    $mechanizations = Mechanization::where('kebele_id', $user->kebele_id)
        ->orderBy('year', 'desc')
        ->orderBy('name')
        ->get();

    // Transform data for the Excel-like display
    $transformedMechanizations = $mechanizations->map(function ($mechanization) {
        $mechanizationDistributionDetails = $mechanization->mechanization_distribution_details ?? [];

        // Extract timeline data from mechanization_distribution_details
        $timelineData = $this->extractMechanizationTimelineData($mechanizationDistributionDetails, $mechanization->year);

        return [
            'id' => $mechanization->id,
            'name' => $mechanization->name,
            'mechanization_type' => $mechanization->mechanization_type ?? 'N/A',
            'unit' => 'Number',
            'application_year' => $mechanization->year,
            'mechanization_distribution_details' => $mechanizationDistributionDetails,
            'timeline_data' => $timelineData,
            'amount' => $mechanization->amount,
            'year' => $mechanization->year,
            'responsible_staff' => $mechanization->responsible_staff,
            'notes' => $mechanization->notes,
            'updated_at' => $mechanization->updated_at->format('Y-m-d H:i:s'),
        ];
    });

    // If AJAX request, return JSON
    if ($request->ajax()) {
        return response()->json([
            'mechanizations' => $transformedMechanizations,
            'total' => $transformedMechanizations->count()
        ]);
    }

    return view('mechanizations.mechanizationtime', compact('mechanizations', 'transformedMechanizations'));
}

/**
 * Extract mechanization timeline data from mechanization_distribution_details
 */
private function extractMechanizationTimelineData($mechanizationDistributionDetails, $planYear)
{
    $timelineData = [
        'application_year' => $planYear,
        'quarters' => [],
        'months' => [],
        'weeks' => [],
        'total_distributed_number' => 0,
        'mechanization_breakdown' => []
    ];

    if (empty($mechanizationDistributionDetails)) {
        return $timelineData;
    }

    // Handle different possible structures of mechanization_distribution_details
    if (is_array($mechanizationDistributionDetails)) {
        // Check for application_year in the data
        if (isset($mechanizationDistributionDetails['application_year'])) {
            $timelineData['application_year'] = $mechanizationDistributionDetails['application_year'];
        }

        // Structure 1: Direct array with quarters, months, weeks
        if (isset($mechanizationDistributionDetails['quarters'])) {
            $timelineData = array_merge($timelineData, $this->extractMechanizationFromStructuredData($mechanizationDistributionDetails));
        }
        // Structure 2: Array of weekly distributions
        else if (isset($mechanizationDistributionDetails[0]) && is_array($mechanizationDistributionDetails[0])) {
            $timelineData = array_merge($timelineData, $this->extractMechanizationFromWeeklyDistribution($mechanizationDistributionDetails));
        }
        // Structure 3: Legacy format with nested structure
        else if (isset($mechanizationDistributionDetails['weekly_distribution'])) {
            $timelineData = array_merge($timelineData, $this->extractMechanizationFromWeeklyDistribution($mechanizationDistributionDetails['weekly_distribution']));
        }

        // Also check for application_year in nested structures
        if (isset($mechanizationDistributionDetails['application_year'])) {
            $timelineData['application_year'] = $mechanizationDistributionDetails['application_year'];
        }
    }

    return $timelineData;
}

/**
 * Extract from structured data with quarters, months, weeks for mechanizations
 */
private function extractMechanizationFromStructuredData($data)
{
    $timelineData = [
        'quarters' => [],
        'months' => [],
        'weeks' => [],
        'total_distributed_number' => 0,
        'mechanization_breakdown' => []
    ];

    // Check for application_year in structured data
    if (isset($data['application_year'])) {
        $timelineData['application_year'] = $data['application_year'];
    }

    // Extract quarters data
    if (isset($data['quarters']) && is_array($data['quarters'])) {
        foreach ($data['quarters'] as $quarterName => $quarterData) {
            if (isset($quarterData['number']) && $quarterData['number'] > 0) {
                $timelineData['quarters'][$quarterName] = [
                    'number' => $quarterData['number'],
                    'checked' => $quarterData['checked'] ?? false
                ];
                $timelineData['total_distributed_number'] += $quarterData['number'];
            }
        }
    }

    // Extract months data
    if (isset($data['months']) && is_array($data['months'])) {
        foreach ($data['months'] as $monthName => $monthData) {
            if (isset($monthData['number']) && $monthData['number'] > 0) {
                $timelineData['months'][$monthName] = [
                    'number' => $monthData['number'],
                    'quarter' => $monthData['quarter'] ?? 'Unknown',
                    'checked' => $monthData['checked'] ?? false
                ];
            }
        }
    }

    // Extract weeks data
    if (isset($data['weeks']) && is_array($data['weeks'])) {
        foreach ($data['weeks'] as $weekKey => $weekData) {
            if (isset($weekData['number']) && $weekData['number'] > 0) {
                $timelineData['weeks'][$weekKey] = [
                    'number' => $weekData['number'],
                    'month' => $weekData['month'] ?? 'Unknown',
                    'quarter' => $weekData['quarter'] ?? 'Unknown',
                    'week_number' => $weekData['week_number'] ?? 1,
                    'mechanization_name' => $weekData['mechanization_name'] ?? 'Unknown',
                    'unit' => $weekData['unit'] ?? 'number',
                    'checked' => $weekData['checked'] ?? false
                ];
                
                // Track mechanization breakdown
                $mechName = $weekData['mechanization_name'] ?? 'Unknown';
                if (!isset($timelineData['mechanization_breakdown'][$mechName])) {
                    $timelineData['mechanization_breakdown'][$mechName] = [
                        'number' => 0,
                        'unit' => $weekData['unit'] ?? 'number'
                    ];
                }
                $timelineData['mechanization_breakdown'][$mechName]['number'] += $weekData['number'];
            }
        }
    }

    return $timelineData;
}

/**
 * Extract from weekly distribution array for mechanizations
 */
private function extractMechanizationFromWeeklyDistribution($weeklyDistribution)
{
    $timelineData = [
        'quarters' => [],
        'months' => [],
        'weeks' => [],
        'total_distributed_number' => 0,
        'mechanization_breakdown' => []
    ];

    foreach ($weeklyDistribution as $week) {
        if (is_array($week)) {
            $number = $week['number'] ?? 0;
            $quarter = $week['quarter'] ?? ($week['year'] ?? 'Unknown');
            $month = $week['month'] ?? 'Unknown';
            $weekNumber = $week['week'] ?? ($week['week_number'] ?? 1);
            $mechanizationName = $week['mechanization_name'] ?? 'Unknown';
            $unit = $week['unit'] ?? 'number';

            // Check for application_year in weekly data
            if (isset($week['application_year'])) {
                $timelineData['application_year'] = $week['application_year'];
            }

            if ($number > 0) {
                // Add to total
                $timelineData['total_distributed_number'] += $number;

                // Track quarter
                if (!isset($timelineData['quarters'][$quarter])) {
                    $timelineData['quarters'][$quarter] = ['number' => 0];
                }
                $timelineData['quarters'][$quarter]['number'] += $number;

                // Track month
                if (!isset($timelineData['months'][$month])) {
                    $timelineData['months'][$month] = ['number' => 0, 'quarter' => $quarter];
                }
                $timelineData['months'][$month]['number'] += $number;

                // Track week
                $weekKey = $month . '_week_' . $weekNumber;
                $timelineData['weeks'][$weekKey] = [
                    'number' => $number,
                    'month' => $month,
                    'quarter' => $quarter,
                    'week_number' => $weekNumber,
                    'mechanization_name' => $mechanizationName,
                    'unit' => $unit
                ];

                // Track mechanization breakdown
                if (!isset($timelineData['mechanization_breakdown'][$mechanizationName])) {
                    $timelineData['mechanization_breakdown'][$mechanizationName] = [
                        'number' => 0,
                        'unit' => $unit
                    ];
                }
                $timelineData['mechanization_breakdown'][$mechanizationName]['number'] += $number;
            }
        }
    }

    return $timelineData;
}

/**
 * Export mechanization plans to Excel
 */
public function exportMechanizationExcel()
{
    $user = Auth::user();

    $mechanizations = Mechanization::where('kebele_id', $user->kebele_id)
        ->orderBy('year', 'desc')
        ->orderBy('name')
        ->get();

    $fileName = 'mechanization_plans_' . date('Y-m-d') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    ];

    $callback = function() use ($mechanizations) {
        $file = fopen('php://output', 'w');

        // Header row
        fputcsv($file, [
            'Mechanization Name', 'Type', 'Year', 'Available Number', 
            'Total Distributed', 'Remaining', 'Responsible Staff'
        ]);

        // Data rows
        foreach ($mechanizations as $mechanization) {
            $totalDistributed = 0;
            
            if ($mechanization->mechanization_distribution_details && is_array($mechanization->mechanization_distribution_details)) {
                if (isset($mechanization->mechanization_distribution_details['weekly_distribution'])) {
                    foreach ($mechanization->mechanization_distribution_details['weekly_distribution'] as $distribution) {
                        $totalDistributed += $distribution['number'] ?? 0;
                    }
                }
            }

            $remaining = $mechanization->amount - $totalDistributed;

            fputcsv($file, [
                $mechanization->name,
                $mechanization->mechanization_type ?? 'N/A',
                $mechanization->year,
                $mechanization->amount,
                $totalDistributed,
                $remaining,
                $mechanization->responsible_staff ?? 'N/A'
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
}
