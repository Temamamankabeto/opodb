<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\Kebele;
use App\Models\SoilType;
use App\Models\Fertilizer;
use Illuminate\Http\Request;
use App\Models\CropConfiguration;
use Illuminate\Support\Facades\Auth;
use App\Models\CropType; // Add this import


class CropConfigurationController extends Controller
{
    public function index()
{
    $userKebeleId = Auth::user()->kebele_id;

    $configs = CropConfiguration::with(['croptype', 'crop', 'kebele', 'soilType'])
        ->where('kebele_id', $userKebeleId)
        ->latest()
        ->paginate(100);

    return view('crop_configurations.index', compact('configs'));
}


    public function create()
    {
        $croptypes = CropType::all(); // Add this
        $crops = Crop::all();
        $kebeles = Kebele::all();
        $fertilizers = Fertilizer::all();
        $soilTypes = SoilType::all();

        return view('crop_configurations.create', compact('croptypes', 'crops', 'kebeles', 'fertilizers', 'soilTypes'));
    }

   public function store(Request $request)
{
    $request->validate([
        'croptype_id' => 'required|exists:crop_types,id',
        'crop_id' => 'required|exists:crops,id',
        'soil_type_id' => 'required|exists:soil_types,id',
        'seed_rate_kg_per_ha' => 'nullable|numeric',
        'productivity_per_ha' => 'nullable|numeric',
        'notes' => 'nullable|string',
        'year' => 'required|digits:4',
    ]);

    $kebeleId = Auth::user()->kebele_id; // ✅ Use logged-in user's kebele_id

    // Check for existing configuration with the same combination
    $existingConfig = CropConfiguration::where([
        'croptype_id' => $request->croptype_id,
        'crop_id' => $request->crop_id,
        'kebele_id' => $kebeleId,
        'soil_type_id' => $request->soil_type_id,
        'year' => $request->year,
    ])->first();

    if ($existingConfig) {
        return redirect()->back()
            ->withInput()
            ->withErrors([
                'duplicate' => 'A crop configuration already exists for this crop type, crop, kebele, soil type, and year combination.'
            ]);
    }

    $fertilizerRequirements = [];
    if ($request->has('fertilizers')) {
        foreach ($request->fertilizers as $fertilizerId => $rate) {
            if ($rate !== null && $rate !== '') {
                $fertilizer = Fertilizer::find($fertilizerId);
                if ($fertilizer) {
                    $fertilizerRequirements[] = [
                        'fertilizer_id' => $fertilizer->id,
                        'fertilizer_name' => $fertilizer->name,
                        'rate_kg_per_ha' => (float)$rate,
                    ];
                }
            }
        }
    }

    CropConfiguration::create([
        'croptype_id' => $request->croptype_id,
        'crop_id' => $request->crop_id,
        'kebele_id' => $kebeleId, // ✅ Automatically assigned
        'soil_type_id' => $request->soil_type_id,
        'productivity_per_ha' => $request->productivity_per_ha,
        'seed_rate_kg_per_ha' => $request->seed_rate_kg_per_ha,
        'fertilizer_requirements' => $fertilizerRequirements,
        'notes' => $request->notes,
        'year' => $request->year,
    ]);

    return redirect()->route('crop-configurations.index')->with('success', 'Crop configuration saved successfully.');
}


    public function edit($id)
    {
        $config = CropConfiguration::findOrFail($id);
        $croptypes = CropType::all(); // Add this
        $crops = Crop::where('croptype_id', $config->croptype_id)->get(); // Filter crops by crop type
        $kebeles = Kebele::all();
        $fertilizers = Fertilizer::all();
        $soilTypes = SoilType::all();

        return view('crop_configurations.edit', compact('config', 'croptypes', 'crops', 'kebeles', 'fertilizers', 'soilTypes'));
    }

    public function update(Request $request, $id)
    {
        $config = CropConfiguration::findOrFail($id);

        $request->validate([
            'croptype_id' => 'required|exists:crop_types,id', // Add this validation
            'crop_id' => 'required|exists:crops,id',
            'kebele_id' => 'required|exists:kebeles,id',
            'soil_type_id' => 'required|exists:soil_types,id',
            'seed_rate_kg_per_ha' => 'nullable|numeric',
            'productivity_per_ha' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'year' => 'required|digits:4',
        ]);

        // Check for existing configuration with the same combination (excluding current record)
        $existingConfig = CropConfiguration::where([
            'croptype_id' => $request->croptype_id, // Add this
            'crop_id' => $request->crop_id,
            'kebele_id' => $request->kebele_id,
            'soil_type_id' => $request->soil_type_id,
            'year' => $request->year,
        ])->where('id', '!=', $id)->first();

        if ($existingConfig) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'duplicate' => 'A crop configuration already exists for this crop type, crop, kebele, soil type, and year combination.'
                ]);
        }

        $fertilizerRequirements = [];
        if ($request->has('fertilizers')) {
            foreach ($request->fertilizers as $fertilizerId => $rate) {
                if ($rate !== null && $rate !== '') {
                    $fertilizer = Fertilizer::find($fertilizerId);
                    if ($fertilizer) {
                        $fertilizerRequirements[] = [
                            'fertilizer_id' => $fertilizer->id,
                            'fertilizer_name' => $fertilizer->name,
                            'rate_kg_per_ha' => (float)$rate,
                        ];
                    }
                }
            }
        }

        $config->update([
            'croptype_id' => $request->croptype_id, // Add this
            'crop_id' => $request->crop_id,
            'kebele_id' => $request->kebele_id,
            'soil_type_id' => $request->soil_type_id,
            'productivity_per_ha' => $request->productivity_per_ha,
            'seed_rate_kg_per_ha' => $request->seed_rate_kg_per_ha,
            'fertilizer_requirements' => $fertilizerRequirements,
            'notes' => $request->notes,
            'year' => $request->year,
        ]);

        return redirect()->route('crop-configurations.index')->with('success', 'Crop configuration updated successfully.');
    }

    public function destroy($id)
    {
        $config = CropConfiguration::findOrFail($id);
        $config->delete();
        return back()->with('success', 'Configuration deleted successfully.');
    }

    // Add this method to handle AJAX requests for loading crops by crop type
    public function getCropsByType($croptype_id)
    {
        $crops = Crop::where('croptype_id', $croptype_id)->get();
        return response()->json($crops);
    }
}
