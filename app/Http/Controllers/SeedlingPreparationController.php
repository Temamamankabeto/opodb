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

    /**
     * Store a newly created resource in storage.
     */
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

    // Check for duplicate entry
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

    /**
     * Show the form for editing planting data.
     */
    public function edit($id)
    {
        $preparation = SeedlingPreparation::where('sector_id', Auth::user()->sector_id)
                        ->findOrFail($id);
        
        $seedlingTypes = SeedlingType::withCount('seedlings')->get();
        
        return view('sector.seedling-preparations.edit', compact('preparation', 'seedlingTypes'));
    }





     

    /**
     * Update the planting data for the specified resource.
     */
    public function update(Request $request, $id)
    {
        $preparation = SeedlingPreparation::where('sector_id', Auth::user()->sector_id)
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
        $preparation = SeedlingPreparation::where('sector_id', Auth::user()->sector_id)
                        ->findOrFail($id);
        
        $seedlingTypes = SeedlingType::withCount('seedlings')->get();
        
        return view('sector.seedling-preparations.editpreparation', compact('preparation', 'seedlingTypes'));
    }
 public function updatePreparation(Request $request, $id)
{
    $preparation = SeedlingPreparation::where('sector_id', Auth::user()->sector_id)
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


}