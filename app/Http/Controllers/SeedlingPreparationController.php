<?php

namespace App\Http\Controllers;

use App\Models\Seedling;
use App\Models\SeedlingType;
use Illuminate\Http\Request;
use App\Models\SeedlingPreparation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SeedlingPreparationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    $user = Auth::user();

    $preparations = SeedlingPreparation::with(['sector', 'kebele', 'woreda', 'zone', 'region'])
        ->where('kebele_id', $user->kebele_id)
        ->latest('year')
        ->latest('created_at')
        ->paginate(15);

    $seedlingTypes = SeedlingType::all();

    // Decode and compute totals
    $totalsByType = [];

    foreach ($preparations as $prep) {
        // Decode JSON safely
        if (is_string($prep->preparation_data)) {
            $prep->preparation_data = json_decode($prep->preparation_data, true) ?: [];
        }
        if (is_string($prep->planting_data)) {
            $prep->planting_data = json_decode($prep->planting_data, true) ?: [];
        }

        // Get seedling type name
        $typeName = $prep->preparation_data['seedling_type']['name'] ?? 'Unknown';

        // Get seedling amounts
        $prepSeedlings = $prep->preparation_data['selected_seedlings'] ?? [];
        $plantSeedlings = $prep->planting_data['selected_seedlings'] ?? [];

        // Sum all "hanga" values (amounts)
        $prepTotal = collect($prepSeedlings)->sum(fn($s) => $s['hanga_qophii'] ?? 0);
        $plantTotal = collect($plantSeedlings)->sum(fn($s) => $s['hanga_dhaabbii'] ?? 0);

        // Add to total per seedling type
        if (!isset($totalsByType[$typeName])) {
            $totalsByType[$typeName] = 0;
        }
        $totalsByType[$typeName] += ($prepTotal + $plantTotal);
    }

    return view('sector.seedling-preparations.index', compact('preparations', 'seedlingTypes', 'totalsByType'));
}

 
    public function create()
    {
        // Get seedling types with their seedlings
        $seedlingTypes = SeedlingType::with(['seedlings' => function($query) {
            $query->orderBy('name');
        }])->get();
        
        return view('sector.seedling-preparations.create', compact('seedlingTypes'));
    }
 
public function store(Request $request)
{
    // Convert JSON string to array for validation
    $preparationData = json_decode($request->preparation_data, true);
    
    if (!is_array($preparationData)) {
        return redirect()->back()->withErrors([
            'preparation_data' => 'Invalid data format.'
        ])->withInput();
    }

    $request->merge([
        'preparation_data' => $preparationData
    ]);

    $request->validate([
        'year' => 'required|string',
        'preparation_data' => 'required|array',
        'preparation_data.seedling_type_id' => 'required|exists:seedling_types,id',
        'preparation_data.selected_seedlings' => 'required|array|min:1',
        'preparation_data.selected_seedlings.*.seedling_id' => 'required|exists:seedlings,id',
        'preparation_data.selected_seedlings.*.hanga_qophii' => 'required|numeric|min:0',
        'preparation_data.selected_seedlings.*.nama_hirmaate_qophii' => 'required|numeric|min:0',
        'preparation_data.selected_seedlings.*.baayina_guyyaa_qophii' => 'required|numeric|min:0',
        
        'baayyina_nersery' => 'nullable|numeric|min:0',
        'ballina_nersery' => 'nullable|numeric|min:0',
        'nerserif_nama_hirmaate' => 'nullable|numeric|min:0',
        'nerserif_baayina_guyyaa' => 'nullable|numeric|min:0',
    ]);

    // Get seedling type data
    $seedlingType = SeedlingType::findOrFail($request->preparation_data['seedling_type_id']);

    // Filter out seedlings where all values are 0 (not filled by user)
    $filteredSeedlings = array_filter($request->preparation_data['selected_seedlings'], function($seedling) {
        return $seedling['hanga_qophii'] > 0 || 
               $seedling['nama_hirmaate_qophii'] > 0 || 
               $seedling['baayina_guyyaa_qophii'] > 0;
    });

    // Check if at least one seedling has data
    if (empty($filteredSeedlings)) {
        return redirect()->back()->withErrors([
            'preparation_data' => 'Please fill preparation details for at least one seedling.'
        ])->withInput();
    }

    // Extract seedling IDs from the request
    $requestSeedlingIds = array_map(function($seedling) {
        return $seedling['seedling_id'];
    }, $filteredSeedlings);

    // Check for duplicate seedlings in existing records
    $existingPreparations = SeedlingPreparation::where('sector_id', Auth::user()->sector_id)
        ->where('year', $request->year)
        ->whereJsonContains('preparation_data->seedling_type->id', $request->preparation_data['seedling_type_id'])
        ->get();

    $duplicateSeedlings = [];

    foreach ($existingPreparations as $existingPrep) {
        $existingSeedlings = $existingPrep->preparation_data['selected_seedlings'] ?? [];
        
        foreach ($existingSeedlings as $existingSeedling) {
            if (in_array($existingSeedling['id'], $requestSeedlingIds)) {
                $seedling = Seedling::find($existingSeedling['id']);
                if ($seedling) {
                    $duplicateSeedlings[] = $seedling->name;
                }
            }
        }
    }

    // Remove duplicates from the array
    $duplicateSeedlings = array_unique($duplicateSeedlings);

    // If there are duplicate seedlings, return error
    if (!empty($duplicateSeedlings)) {
        $seedlingList = implode(', ', $duplicateSeedlings);
        return redirect()->back()->withErrors([
            'preparation_data' => "The following seedlings already have preparation records for year {$request->year}: {$seedlingList}. Please edit the existing record instead."
        ])->withInput();
    }

    // Prepare preparation data for JSON storage
    $preparationDataJson = [
        'seedling_type' => [
            'id' => $seedlingType->id,
            'name' => $seedlingType->name,
        ],
        'selected_seedlings' => []
    ];

    // Process preparation seedlings (only those with data)
    foreach ($filteredSeedlings as $prepSeedling) {
        $seedling = Seedling::findOrFail($prepSeedling['seedling_id']);
        $preparationDataJson['selected_seedlings'][] = [
            'id' => $seedling->id,
            'name' => $seedling->name,
            'hanga_qophii' => $prepSeedling['hanga_qophii'],
            'nama_hirmaate_qophii' => $prepSeedling['nama_hirmaate_qophii'],
            'baayina_guyyaa_qophii' => $prepSeedling['baayina_guyyaa_qophii'],
        ];
    }

    // Check for duplicate entry (same seedling type in same year)
    $exists = SeedlingPreparation::where('sector_id', Auth::user()->sector_id)
                ->where('year', $request->year)
                ->whereJsonContains('preparation_data->seedling_type->id', $request->preparation_data['seedling_type_id'])
                ->exists();

    if ($exists) {
        return redirect()->back()->withErrors([
            'preparation_data' => "A seedling preparation record for '{$seedlingType->name}' already exists for year {$request->year} in your sector."
        ])->withInput();
    }

    SeedlingPreparation::create([
        'year' => $request->year,
        'preparation_data' => $preparationDataJson,
        'planting_data' => null, // Planting data will be added later
        'baayyina_nersery' => $request->baayyina_nersery,
        'ballina_nersery' => $request->ballina_nersery,
        'nerserif_nama_hirmaate' => $request->nerserif_nama_hirmaate,
        'nerserif_baayina_guyyaa' => $request->nerserif_baayina_guyyaa,
        'sector_id' => Auth::user()->sector_id,
        'kebele_id' => Auth::user()->kebele_id,
        'woreda_id' => Auth::user()->woreda_id,
        'zone_id' => Auth::user()->zone_id,
        'region_id' => Auth::user()->region_id,
        'created_by' => Auth::id(),
        'phone' => Auth::user()->phone,
        'status' => 'prepared', // Mark as prepared, ready for planting
    ]);

    return redirect()->route('sector.seedling-preparations.index')->with('success', 'Seedling preparation created successfully! You can add planting data later.');
}

    
    public function edit($id)
    {
        $preparation = SeedlingPreparation::where('kebele_id', Auth::user()->kebele_id)
                        ->findOrFail($id);
        
        $seedlingTypes = SeedlingType::withCount('seedlings')->get();
        
        return view('sector.seedling-preparations.edit', compact('preparation', 'seedlingTypes'));
    }





     

    /**
     * Update the planting data for the specified resource.
     */
    public function update(Request $request, $id)
    {
        $preparation = SeedlingPreparation::where('kebele_id', Auth::user()->kebele_id)
                        ->findOrFail($id);

        // Convert JSON string to array for validation
        $plantingData = json_decode($request->planting_data, true);
        
        if (!is_array($plantingData)) {
            return redirect()->back()->withErrors([
                'planting_data' => 'Invalid data format.'
            ])->withInput();
        }

        $request->merge([
            'planting_data' => $plantingData
        ]);

        $request->validate([
            'planting_data' => 'required|array',
            'planting_data.seedling_type_id' => 'required|exists:seedling_types,id',
            'planting_data.selected_seedlings' => 'required|array|min:1',
            'planting_data.selected_seedlings.*.seedling_id' => 'required|exists:seedlings,id',
            'planting_data.selected_seedlings.*.hanga_dhaabbii' => 'required|numeric|min:0',
            'planting_data.selected_seedlings.*.nama_hirmaate_dhaabbii' => 'required|numeric|min:0',
            'planting_data.selected_seedlings.*.baayina_guyyaa_dhaabbii' => 'required|numeric|min:0',
        ]);

        // Get seedling type data
        $seedlingType = SeedlingType::findOrFail($request->planting_data['seedling_type_id']);

        // Prepare planting data for JSON storage
        $plantingDataJson = [
            'seedling_type' => [
                'id' => $seedlingType->id,
                'name' => $seedlingType->name,
            ],
            'selected_seedlings' => []
        ];

        // Process planting seedlings
        foreach ($request->planting_data['selected_seedlings'] as $plantSeedling) {
            $seedling = Seedling::findOrFail($plantSeedling['seedling_id']);
            $plantingDataJson['selected_seedlings'][] = [
                'id' => $seedling->id,
                'name' => $seedling->name,
                'hanga_dhaabbii' => $plantSeedling['hanga_dhaabbii'],
                'nama_hirmaate_dhaabbii' => $plantSeedling['nama_hirmaate_dhaabbii'],
                'baayina_guyyaa_dhaabbii' => $plantSeedling['baayina_guyyaa_dhaabbii'],
            ];
        }

        $preparation->update([
            'planting_data' => $plantingDataJson,
            'status' => 'completed', // Mark as completed
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('sector.seedling-preparations.index')->with('success', 'Planting data updated successfully!');
    }

    public function editpreparation($id)
    {
        $preparation = SeedlingPreparation::where('kebele_id', Auth::user()->kebele_id)
                        ->findOrFail($id);
        
        $seedlingTypes = SeedlingType::withCount('seedlings')->get();
        
        return view('sector.seedling-preparations.editpreparation', compact('preparation', 'seedlingTypes'));
    }
 public function updatePreparation(Request $request, $id)
{
    $preparation = SeedlingPreparation::where('kebele_id', Auth::user()->kebele_id)
        ->findOrFail($id);

         $preparationData = $request->preparation_data;
    if (is_string($preparationData)) {
        $preparationData = json_decode($preparationData, true);
    }

    if (!is_array($preparationData)) {
        return back()->withErrors(['preparation_data' => 'Invalid data format.'])->withInput();
    }

       $validator = Validator::make(['preparation_data' => $preparationData], [
        'preparation_data' => 'required|array',
        'preparation_data.seedling_type_id' => 'required|exists:seedling_types,id',
        'preparation_data.selected_seedlings' => 'required|array|min:1',
        'preparation_data.selected_seedlings.*.seedling_id' => 'required|exists:seedlings,id',
        'preparation_data.selected_seedlings.*.hanga_qophii' => 'required|numeric|min:0',
        'preparation_data.selected_seedlings.*.nama_hirmaate_qophii' => 'required|numeric|min:0',
        'preparation_data.selected_seedlings.*.baayina_guyyaa_qophii' => 'required|numeric|min:0',
    ]);
       if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    $data = $validator->validated()['preparation_data'];

    $seedlingType = SeedlingType::findOrFail($data['seedling_type_id']);

    $formatted = [
        'seedling_type' => [
            'id' => $seedlingType->id,
            'name' => $seedlingType->name,
        ],
        'selected_seedlings' => [],
    ];

    foreach ($data['selected_seedlings'] as $s) {
        $seedling = Seedling::findOrFail($s['seedling_id']);
        $formatted['selected_seedlings'][] = [
            'id' => $seedling->id,
            'name' => $seedling->name,
            'hanga_qophii' => $s['hanga_qophii'],
            'nama_hirmaate_qophii' => $s['nama_hirmaate_qophii'],
            'baayina_guyyaa_qophii' => $s['baayina_guyyaa_qophii'],
        ];
    }

    $preparation->update([
        'preparation_data' => $formatted,
        'updated_by' => Auth::id(),
    ]);

    return redirect()
        ->route('sector.seedling-preparations.index')
        ->with('success', 'Preparation data updated successfully!');
}


 public function getSeedlingsByType($typeId)
    {
        $seedlings = Seedling::where('seedlingtype_id', $typeId)->orderBy('name')->get();
        return response()->json($seedlings);
    }




    public function seedlingPlan($id)
{
    $user = Auth::user();

    // Retrieve the seedling preparation record; ensure user can only edit in their sector
    $seedlingPreparation = SeedlingPreparation::where('id', $id)
        ->where('kebele_id', $user->kebele_id)
        ->firstOrFail();

    // Transform the data for planting timeline table
    $transformedSeedlingPlans = $this->transformSeedlingPlanDataForTable($seedlingPreparation);

    return view('sector.seedling-preparations.seedlingplan', compact('seedlingPreparation', 'transformedSeedlingPlans'));
}

private function transformSeedlingPlanDataForTable($seedlingPreparation)
{
    // Extract planting timeline data from plantingdata_detail
    $plantingDetails = $seedlingPreparation->plantingdata_detail ?? [];
    
    // Initialize timeline data structure
    $timelineData = [
        'quarters' => [],
        'months' => [],
        'weeks' => [],
        'total_planted_quantity' => 0,
        'total_participants' => 0,
        'total_days' => 0,
        'planting_year' => $seedlingPreparation->year
    ];

    // Define Ethiopian months by quarter
    $ethiopianMonths = [
        'Q1' => ['Meskerem', 'Tikimt', 'Hidar'],
        'Q2' => ['Tahsas', 'Tir', 'Yekatit'],
        'Q3' => ['Megabit', 'Miyazia', 'Ginbot'],
        'Q4' => ['Sene', 'Hamle', 'Nehase', 'Pagumen']
    ];

    // Process planting details to extract timeline data
    if (!empty($plantingDetails) && is_array($plantingDetails)) {
        if (isset($plantingDetails['selected_seedlings'])) {
            foreach ($plantingDetails['selected_seedlings'] as $seedling) {
                if (isset($seedling['weekly_planting'])) {
                    foreach ($seedling['weekly_planting'] as $planting) {
                        if (isset($planting['hanga_dhaabbii']) && $planting['hanga_dhaabbii'] > 0) {
                            $quantity = floatval($planting['hanga_dhaabbii']);
                            $participants = floatval($planting['nama_hirmaate_dhaabbii'] ?? 0);
                            $days = floatval($planting['baayina_guyyaa_dhaabbii'] ?? 0);
                            $quarter = $planting['quarter'] ?? '';
                            $month = $planting['month'] ?? '';
                            $week = $planting['week'] ?? '';
                            $seedlingName = $seedling['name'] ?? '';

                            // Add to totals
                            $timelineData['total_planted_quantity'] += $quantity;
                            $timelineData['total_participants'] += $participants;
                            $timelineData['total_days'] += $days;

                            // Add to quarter totals
                            if ($quarter && isset($ethiopianMonths[$quarter])) {
                                if (!isset($timelineData['quarters'][$quarter])) {
                                    $timelineData['quarters'][$quarter] = [
                                        'quantity' => 0,
                                        'participants' => 0,
                                        'days' => 0
                                    ];
                                }
                                $timelineData['quarters'][$quarter]['quantity'] += $quantity;
                                $timelineData['quarters'][$quarter]['participants'] += $participants;
                                $timelineData['quarters'][$quarter]['days'] += $days;
                            }

                            // Add to month totals
                            if ($month) {
                                if (!isset($timelineData['months'][$month])) {
                                    $timelineData['months'][$month] = [
                                        'quantity' => 0,
                                        'participants' => 0,
                                        'days' => 0
                                    ];
                                }
                                $timelineData['months'][$month]['quantity'] += $quantity;
                                $timelineData['months'][$month]['participants'] += $participants;
                                $timelineData['months'][$month]['days'] += $days;
                            }

                            // Add to week totals
                            if ($week && $month) {
                                $weekKey = $month . ' ' . $week;
                                if (!isset($timelineData['weeks'][$weekKey])) {
                                    $timelineData['weeks'][$weekKey] = [
                                        'quantity' => 0,
                                        'participants' => 0,
                                        'days' => 0
                                    ];
                                }
                                $timelineData['weeks'][$weekKey]['quantity'] += $quantity;
                                $timelineData['weeks'][$weekKey]['participants'] += $participants;
                                $timelineData['weeks'][$weekKey]['days'] += $days;
                            }
                        }
                    }
                }
            }
        }
    }

    // Create the transformed seedling plan structure
    return [
        [
            'planning_year' => $seedlingPreparation->year,
            'planting_year' => $seedlingPreparation->year,
            'seedling_type' => $plantingDetails['seedling_type']['name'] ?? ($seedlingPreparation->preparation_data['seedling_type']['name'] ?? 'N/A'),
            'total_prepared_quantity' => $this->calculateTotalPreparedQuantity($seedlingPreparation),
            'total_planted_quantity' => $timelineData['total_planted_quantity'],
            'remaining_quantity' => $this->calculateTotalPreparedQuantity($seedlingPreparation) - $timelineData['total_planted_quantity'],
            'plantingdata_detail' => $plantingDetails,
            'timeline_data' => $timelineData
        ]
    ];
}

// private function calculateTotalPreparedQuantity($seedlingPreparation)
// {
//     $total = 0;
//     $prepData = $seedlingPreparation->preparation_data ?? [];
    
//     if (isset($prepData['selected_seedlings'])) {
//         foreach ($prepData['selected_seedlings'] as $seedling) {
//             $total += floatval($seedling['hanga_qophii'] ?? 0);
//         }
//     }
    
//     return $total;
// }

public function updateSeedlingPlan(Request $request, $id)
{
    $user = Auth::user();

    // Retrieve the seedling preparation record; ensure user can only edit in their sector
    $seedlingPreparation = SeedlingPreparation::where('id', $id)
        ->where('kebele_id', $user->kebele_id)
        ->firstOrFail();

    $validated = $request->validate([
        'plantingdata_detail' => 'nullable|json'
    ]);

    // Update planting data and status
    $seedlingPreparation->update([
        'plantingdata_detail' => $request->has('plantingdata_detail') 
            ? json_decode($request->plantingdata_detail, true) 
            : null,
        'status' => 'completed'
    ]);

    return redirect()->route('sector.seedling-preparations.index')
        ->with('success', 'Seedling planting plan updated successfully!');
}





public function seedlingTime(Request $request)
{
    $user = Auth::user();

    // Get seedling preparations for the user's sector
    $seedlingPreparations = SeedlingPreparation::where('kebele_id', $user->kebele_id)
        ->orderBy('year', 'desc')
        ->orderBy('created_at', 'desc')
        ->get();

    // Transform data for the Excel-like display
    $transformedSeedlingPlans = $seedlingPreparations->map(function ($preparation) {
        $preparationData = $preparation->preparation_data ?? [];
        $plantingData = $preparation->planting_data ?? [];

        // Extract timeline data from planting_data
        $timelineData = $this->extractSeedlingTimelineData($plantingData, $preparation->year);

        return [
            'id' => $preparation->id,
            'seedling_type' => $preparationData['seedling_type']['name'] ?? 'N/A',
            'seedlings' => $preparationData['selected_seedlings'] ?? [],
            'application_year' => $preparation->year,
            'planting_data' => $plantingData,
            'timeline_data' => $timelineData,
            'total_prepared_quantity' => $this->calculateTotalPreparedQuantity($preparation),
            'total_planted_quantity' => $timelineData['total_planted_quantity'] ?? 0,
            'total_participants' => $timelineData['total_participants'] ?? 0,
            'total_days' => $timelineData['total_days'] ?? 0,
            'year' => $preparation->year,
            'status' => $preparation->status,
            'updated_at' => $preparation->updated_at->format('Y-m-d H:i:s'),
        ];
    });

    // If AJAX request, return JSON
    if ($request->ajax()) {
        return response()->json([
            'seedling_plans' => $transformedSeedlingPlans,
            'total' => $transformedSeedlingPlans->count()
        ]);
    }

    return view('sector.seedling-preparations.seedlingtime', compact('seedlingPreparations', 'transformedSeedlingPlans'));
}

/**
 * Calculate total prepared seedlings quantity
 */
private function calculateTotalPreparedQuantity($seedlingPreparation)
{
    $total = 0;
    $prepData = $seedlingPreparation->plantingdata_detail ?? [];
    
    if (isset($prepData['selected_seedlings'])) {
        foreach ($prepData['selected_seedlings'] as $seedling) {
            $total += floatval($seedling['hanga_qophii'] ?? 0);
        }
    }
    
    return $total;
}

/**
 * Extract seedling timeline data from planting_data
 */
private function extractSeedlingTimelineData($plantingData, $planYear)
{
    $timelineData = [
        'application_year' => $planYear,
        'quarters' => [],
        'months' => [],
        'weeks' => [],
        'total_planted_quantity' => 0,
        'total_participants' => 0,
        'total_days' => 0,
        'seedling_breakdown' => []
    ];

    if (empty($plantingData)) {
        return $timelineData;
    }

    // Handle different possible structures of planting_data
    if (is_array($plantingData)) {
        // Check for application_year in the data
        if (isset($plantingData['application_year'])) {
            $timelineData['application_year'] = $plantingData['application_year'];
        }

        // Structure 1: Direct array with quarters, months, weeks
        if (isset($plantingData['quarters'])) {
            $timelineData = array_merge($timelineData, $this->extractSeedlingFromStructuredData($plantingData));
        }
        // Structure 2: Array of weekly plantings
        else if (isset($plantingData['selected_seedlings']) && is_array($plantingData['selected_seedlings'])) {
            $timelineData = array_merge($timelineData, $this->extractSeedlingFromWeeklyPlanting($plantingData['selected_seedlings']));
        }
        // Structure 3: Legacy format with nested structure
        else if (isset($plantingData[0]) && is_array($plantingData[0])) {
            $timelineData = array_merge($timelineData, $this->extractSeedlingFromWeeklyPlanting($plantingData));
        }

        // Also check for application_year in nested structures
        if (isset($plantingData['application_year'])) {
            $timelineData['application_year'] = $plantingData['application_year'];
        }
    }

    return $timelineData;
}

/**
 * Extract from structured data with quarters, months, weeks for seedlings
 */
private function extractSeedlingFromStructuredData($data)
{
    $timelineData = [
        'quarters' => [],
        'months' => [],
        'weeks' => [],
        'total_planted_quantity' => 0,
        'total_participants' => 0,
        'total_days' => 0,
        'seedling_breakdown' => []
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
                    'participants' => $quarterData['participants'] ?? 0,
                    'days' => $quarterData['days'] ?? 0,
                    'checked' => $quarterData['checked'] ?? false
                ];
                $timelineData['total_planted_quantity'] += $quarterData['quantity'];
                $timelineData['total_participants'] += $quarterData['participants'] ?? 0;
                $timelineData['total_days'] += $quarterData['days'] ?? 0;
            }
        }
    }

    // Extract months data
    if (isset($data['months']) && is_array($data['months'])) {
        foreach ($data['months'] as $monthName => $monthData) {
            if (isset($monthData['quantity']) && $monthData['quantity'] > 0) {
                $timelineData['months'][$monthName] = [
                    'quantity' => $monthData['quantity'],
                    'participants' => $monthData['participants'] ?? 0,
                    'days' => $monthData['days'] ?? 0,
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
                    'participants' => $weekData['participants'] ?? 0,
                    'days' => $weekData['days'] ?? 0,
                    'month' => $weekData['month'] ?? 'Unknown',
                    'quarter' => $weekData['quarter'] ?? 'Unknown',
                    'week_number' => $weekData['week_number'] ?? 1,
                    'seedling' => $weekData['seedling'] ?? 'Unknown',
                    'checked' => $weekData['checked'] ?? false
                ];
                
                // Track seedling breakdown
                $seedlingName = $weekData['seedling'] ?? 'Unknown';
                if (!isset($timelineData['seedling_breakdown'][$seedlingName])) {
                    $timelineData['seedling_breakdown'][$seedlingName] = [
                        'quantity' => 0,
                        'participants' => 0,
                        'days' => 0
                    ];
                }
                $timelineData['seedling_breakdown'][$seedlingName]['quantity'] += $weekData['quantity'];
                $timelineData['seedling_breakdown'][$seedlingName]['participants'] += $weekData['participants'] ?? 0;
                $timelineData['seedling_breakdown'][$seedlingName]['days'] += $weekData['days'] ?? 0;
            }
        }
    }

    return $timelineData;
}

/**
 * Extract from weekly planting array for seedlings
 */
private function extractSeedlingFromWeeklyPlanting($selectedSeedlings)
{
    $timelineData = [
        'quarters' => [],
        'months' => [],
        'weeks' => [],
        'total_planted_quantity' => 0,
        'total_participants' => 0,
        'total_days' => 0,
        'seedling_breakdown' => []
    ];

    foreach ($selectedSeedlings as $seedling) {
        if (is_array($seedling)) {
            $seedlingName = $seedling['name'] ?? 'Unknown';
            $weeklyPlanting = $seedling['weekly_planting'] ?? [];

            foreach ($weeklyPlanting as $week) {
                if (is_array($week)) {
                    $quantity = $week['hanga_dhaabbii'] ?? 0;
                    $participants = $week['nama_hirmaate_dhaabbii'] ?? 0;
                    $days = $week['baayina_guyyaa_dhaabbii'] ?? 0;
                    $quarter = $week['quarter'] ?? ($week['year'] ?? 'Unknown');
                    $month = $week['month'] ?? 'Unknown';
                    $weekNumber = $week['week'] ?? ($week['week_number'] ?? 1);

                    // Check for application_year in weekly data
                    if (isset($week['application_year'])) {
                        $timelineData['application_year'] = $week['application_year'];
                    }

                    if ($quantity > 0 || $participants > 0 || $days > 0) {
                        // Add to totals
                        $timelineData['total_planted_quantity'] += $quantity;
                        $timelineData['total_participants'] += $participants;
                        $timelineData['total_days'] += $days;

                        // Track quarter
                        if (!isset($timelineData['quarters'][$quarter])) {
                            $timelineData['quarters'][$quarter] = [
                                'quantity' => 0,
                                'participants' => 0,
                                'days' => 0
                            ];
                        }
                        $timelineData['quarters'][$quarter]['quantity'] += $quantity;
                        $timelineData['quarters'][$quarter]['participants'] += $participants;
                        $timelineData['quarters'][$quarter]['days'] += $days;

                        // Track month
                        if (!isset($timelineData['months'][$month])) {
                            $timelineData['months'][$month] = [
                                'quantity' => 0,
                                'participants' => 0,
                                'days' => 0,
                                'quarter' => $quarter
                            ];
                        }
                        $timelineData['months'][$month]['quantity'] += $quantity;
                        $timelineData['months'][$month]['participants'] += $participants;
                        $timelineData['months'][$month]['days'] += $days;

                        // Track week
                        $weekKey = $month . '_week_' . $weekNumber;
                        $timelineData['weeks'][$weekKey] = [
                            'quantity' => $quantity,
                            'participants' => $participants,
                            'days' => $days,
                            'month' => $month,
                            'quarter' => $quarter,
                            'week_number' => $weekNumber,
                            'seedling' => $seedlingName
                        ];

                        // Track seedling breakdown
                        if (!isset($timelineData['seedling_breakdown'][$seedlingName])) {
                            $timelineData['seedling_breakdown'][$seedlingName] = [
                                'quantity' => 0,
                                'participants' => 0,
                                'days' => 0
                            ];
                        }
                        $timelineData['seedling_breakdown'][$seedlingName]['quantity'] += $quantity;
                        $timelineData['seedling_breakdown'][$seedlingName]['participants'] += $participants;
                        $timelineData['seedling_breakdown'][$seedlingName]['days'] += $days;
                    }
                }
            }
        }
    }

    return $timelineData;
}

/**
 * Export seedling plans to Excel
 */
public function exportSeedlingExcel()
{
    $user = Auth::user();

    $seedlingPreparations = SeedlingPreparation::where('sector_id', $user->sector_id)
        ->orderBy('year', 'desc')
        ->orderBy('created_at', 'desc')
        ->get();

    $fileName = 'seedling_plans_' . date('Y-m-d') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    ];

    $callback = function() use ($seedlingPreparations) {
        $file = fopen('php://output', 'w');

        // Header row
        fputcsv($file, [
            'Seedling Type', 'Year', 'Total Prepared', 'Total Planted', 'Remaining',
            'Total Participants', 'Total Days', 'Status'
        ]);

        // Data rows
        foreach ($seedlingPreparations as $preparation) {
            $prepData = $preparation->preparation_data ?? [];
            $plantingData = $preparation->planting_data ?? [];
            
            $totalPrepared = $this->calculateTotalPreparedQuantity($preparation);
            
            $totalPlanted = 0;
            $totalParticipants = 0;
            $totalDays = 0;
            
            if (isset($plantingData['selected_seedlings'])) {
                foreach ($plantingData['selected_seedlings'] as $seedling) {
                    if (isset($seedling['weekly_planting'])) {
                        foreach ($seedling['weekly_planting'] as $planting) {
                            $totalPlanted += $planting['hanga_dhaabbii'] ?? 0;
                            $totalParticipants += $planting['nama_hirmaate_dhaabbii'] ?? 0;
                            $totalDays += $planting['baayina_guyyaa_dhaabbii'] ?? 0;
                        }
                    }
                }
            }

            $remaining = $totalPrepared - $totalPlanted;

            fputcsv($file, [
                $prepData['seedling_type']['name'] ?? 'N/A',
                $preparation->year,
                $totalPrepared,
                $totalPlanted,
                $remaining,
                $totalParticipants,
                $totalDays,
                $preparation->status
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
}