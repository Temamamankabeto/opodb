<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\CropType;
use Illuminate\Http\Request;
use App\Models\FertilizerType;
use App\Models\Fertilizer;
use App\Models\KebeleCropPlan;
use App\Models\CropConfiguration;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KebeleCropPlanImport;
use App\Imports\KebeleCropPlansImport;
use App\Exports\KebeleCropPlanTemplateExport;


class KebeleCropPlanController extends Controller
{
//   public function index()
// {
//     $user = Auth::user();

//     $cropPlans = KebeleCropPlan::with(['cropType', 'crop'])
//         // ->where('sector_id', $user->sector_id)
//         ->where('kebele_id', $user->kebele_id)
//         ->orderBy('year')
//         ->orderBy('season')
//         ->orderBy('croptype_id')
//         ->get();

//     // Calculate yearly totals
//     $yearlyTotals = [];
//     foreach ($cropPlans->groupBy('year') as $year => $plansByYear) {
//         $yearlyTotals[$year] = [
//             'total_area' => $plansByYear->sum('area_to_plant'),
//             'total_yield' => $plansByYear->sum('expected_yield'),
//         ];
//     }

//     return view('sector.crop_plans.index', compact('cropPlans', 'yearlyTotals'));
// }
public function index()
{
    $user = Auth::user();

    $cropPlans = KebeleCropPlan::with(['cropType', 'crop'])
        ->where('kebele_id', $user->kebele_id)
        ->orderBy('year')
        ->orderBy('season')
        ->orderBy('croptype_id')
        ->get();

    // Calculate totals
    $grandTotalArea = $cropPlans->sum('area_to_plant');
    $grandTotalYield = $cropPlans->sum('expected_yield');
    
    // Calculate yearly totals
    $yearlyTotals = [];
    foreach ($cropPlans->groupBy('year') as $year => $plansByYear) {
        $yearlyTotals[$year] = [
            'total_area' => $plansByYear->sum('area_to_plant'),
            'total_yield' => $plansByYear->sum('expected_yield'),
        ];
    }

    // Get crop types for the productivity comparison
    $cropTypes = \App\Models\CropType::all(); // Adjust this based on your model

    return view('sector.crop_plans.index', compact('cropPlans', 'yearlyTotals', 'grandTotalArea', 'grandTotalYield', 'cropTypes'));
}





        // Optional: AJAX route to fetch crops by crop type
  
public function getCropTypesByYear($year)
{
    $user = auth()->user();
    $cropTypes = CropConfiguration::where('kebele_id', $user->kebele_id)
        ->where('year', $year)
        ->with('cropType')
        ->get()
        ->pluck('cropType')
        ->unique('id')
        ->values();

    return response()->json($cropTypes);
}

public function getCropsByYearAndType($year, $croptypeId)
{
    $user = auth()->user();
    $configs = CropConfiguration::with('crop')
        ->where('kebele_id', $user->kebele_id)
        ->where('year', $year)
        ->where('croptype_id', $croptypeId)
        ->get();

    $crops = $configs->map(function($config) {
        return [
            'id' => $config->crop->id,
            'name' => $config->crop->name,
            'productivity' => $config->productivity_per_ha,
            'seed_rate' => $config->seed_rate_kg_per_ha,
            'fertilizers' => $config->fertilizer_requirements,
        ];
    });

    return response()->json($crops);
}

 public function create()
{
    $user = Auth::user();
    $kebeleId = $user->kebele_id;

    $fertilizerTypes = FertilizerType::all();

    // ✅ Get distinct years, crop types, and crops available for the user's kebele
    $years = CropConfiguration::where('kebele_id', $kebeleId)
        ->select('year')
        ->distinct()
        ->pluck('year')
        ->sortDesc();

    $cropTypes = CropConfiguration::where('kebele_id', $kebeleId)
        ->with('cropType')
        ->select('croptype_id')
        ->distinct()
        ->get()
        ->pluck('cropType')
        ->filter();

    return view('sector.crop_plans.create', compact('years', 'cropTypes', 'fertilizerTypes'));
}

public function store(Request $request)
{
    $user = Auth::user();

    $validated = $request->validate([
        'croptype_id' => 'required|exists:crop_types,id',
        'crop_id' => 'required|exists:crops,id',
        'area_to_plant' => 'required|numeric|min:0',
        'productivity' => 'required|numeric|min:0',
        'year' => 'required|integer|min:1',
        'season' => 'required|string|max:255',
        'seed_required' => 'required|numeric|min:0',
    ]);

    // Check for duplicate crop plan
    $existingPlan = KebeleCropPlan::with(['crop', 'cropType'])
        ->where('kebele_id', $user->kebele_id)
        ->where('crop_id', $validated['crop_id'])
        ->where('year', $validated['year'])
        ->where('season', $validated['season'])
        ->first();

    if ($existingPlan) {
        $cropName = $existingPlan->crop->name ?? 'Unknown Crop';
        $cropType = $existingPlan->cropType->name ?? 'Unknown Type';
        
        return redirect()->back()
            ->withInput()
            ->withErrors([
                'crop_id' => "A crop plan for \"{$cropName}\" ({$cropType}) in {$validated['year']} {$validated['season']} season already exists. " 
            ]);
    }

    // Collect all fertilizer data
    $fertilizerDetails = $this->collectFertilizerDetails($request);
    $fertilizerTypeIds = $request->input('fertilizer_types', []);

    $area = $validated['area_to_plant'];
    $productivity = $validated['productivity'];
    $expectedYield = $area * $productivity;

    KebeleCropPlan::create([
        'kebele_id' => $user->kebele_id,
        'sector_id' => $user->sector_id,
        'croptype_id' => $validated['croptype_id'],
        'crop_id' => $validated['crop_id'],
        'area_to_plant' => $area,
        'expected_yield' => $expectedYield,
        'year' => $validated['year'],
        'season' => $validated['season'],
        'seed_required' => $validated['seed_required'],
        'fertilizer_type_id' => $fertilizerTypeIds,
        'fertilizer_details' => $fertilizerDetails,
        'responsible_staff' => $request->responsible_staff,
        'notes' => $request->notes,
        'productivity' => $productivity,
        'created_by' => $user->id,
    ]);

    return redirect()->route('sector.crop-plans.index')
        ->with('success', '✅ Kebele Crop Plan added successfully!');
}


private function collectFertilizerDetails(Request $request)
{
    $details = [];

    if ($request->has('fertilizer_types')) {
        foreach ($request->fertilizer_types as $typeId) {
            if ($request->has("fertilizers.$typeId")) {
                $fertilizers = $request->input("fertilizers.$typeId", []);
                $amounts = $request->input("amount.$typeId", []);
                $units = $request->input("unit.$typeId", []);

                foreach ($fertilizers as $index => $fertilizerId) {
                    $details[] = [
                        'fertilizer_type_id' => $typeId,
                        'fertilizer_id' => $fertilizerId,
                        'amount' => $amounts[$index] ?? 0,
                        'unit' => $units[$index] ?? 'kg',
                    ];
                }
            }
        }
    }

    return $details;
}




public function getFertilizers($typeId)
{
    return response()->json(Fertilizer::where('fertilizer_type_id', $typeId)->get());
}







    public function edit($id)
    {
        $user = Auth::user();

        // Retrieve the plan; ensure user can only edit plans in their sector
        $plan = KebeleCropPlan::where('id', $id)
            ->where('sector_id', $user->sector_id)
            ->firstOrFail();

        $cropTypes = CropType::with('crops')->get(); // load crop types and their crops

        return view('sector.crop_plans.edit', compact('plan', 'cropTypes'));
    }

    public function update(Request $request, $id)
{
    $user = Auth::user();
    $cropPlan = KebeleCropPlan::findOrFail($id);

    $validated = $request->validate([
        'croptype_id' => 'required|exists:crop_types,id',
        'crop_id' => 'required|exists:crops,id',
        'area_to_plant' => 'required|numeric|min:0',
        'productivity' => 'required|numeric|min:0',
        'year' => 'required|integer|min:1',
        'season' => 'required|string|max:255',
        'seed_required' => 'required|numeric|min:0',
    ]);

    // Check for duplicate crop plan (excluding current record)
    $existingPlan = KebeleCropPlan::where('kebele_id', $user->kebele_id)
        ->where('crop_id', $validated['crop_id'])
        ->where('year', $validated['year'])
        ->where('season', $validated['season'])
        ->where('id', '!=', $id) // Exclude current record
        ->first();

    if ($existingPlan) {
        $cropName = $existingPlan->crop->name ?? 'Unknown Crop';
        $cropType = $existingPlan->cropType->name ?? 'Unknown Type';
        
        return redirect()->back()
            ->withInput()
            ->withErrors([
                'crop_id' => "A crop plan for \"{$cropName}\" ({$cropType}) in {$validated['year']} {$validated['season']} season already exists. " .
                           "Please choose a different crop, year, or season."
            ]);
    }

    // Collect all fertilizer data
    $fertilizerDetails = $this->collectFertilizerDetails($request);
    $fertilizerTypeIds = $request->input('fertilizer_types', []);

    $area = $validated['area_to_plant'];
    $productivity = $validated['productivity'];
    $expectedYield = $area * $productivity;

    $cropPlan->update([
        'croptype_id' => $validated['croptype_id'],
        'crop_id' => $validated['crop_id'],
        'area_to_plant' => $area,
        'expected_yield' => $expectedYield,
        'year' => $validated['year'],
        'season' => $validated['season'],
        'seed_required' => $validated['seed_required'],
        'fertilizer_type_id' => $fertilizerTypeIds,
        'fertilizer_details' => $fertilizerDetails,
        'responsible_staff' => $request->responsible_staff,
        'notes' => $request->notes,
        'productivity' => $productivity,
        'updated_by' => $user->id,
    ]);

    return redirect()->route('sector.crop-plans.index')
        ->with('success', '✅ Kebele Crop Plan updated successfully!');
}


  public function editfertilizer($id)
{
    $cropPlan = KebeleCropPlan::findOrFail($id);
    $fertilizerTypes = \App\Models\FertilizerType::all();
    $fertilizers = \App\Models\Fertilizer::all();

    return view('sector.crop_plans.edit_fertilizer', compact('cropPlan', 'fertilizerTypes', 'fertilizers'));
}

public function updatefertilizer(Request $request, $id)
{
    $cropPlan = KebeleCropPlan::findOrFail($id);

    $validated = $request->validate([
        'fertilizer_types' => 'nullable|array',
        'fertilizer_types.*' => 'exists:fertilizer_types,id',
        'fertilizer_details' => 'nullable|array',
    ]);

    // Clean up fertilizer_details - remove empty entries
    $fertilizerDetails = [];
    if (!empty($validated['fertilizer_details'])) {
        foreach ($validated['fertilizer_details'] as $typeId => $fertilizers) {
            foreach ($fertilizers as $fertId => $detail) {
                // Only include if amount is provided
                if (!empty($detail['amount']) && !empty($detail['unit'])) {
                    $fertilizerDetails[] = [
                        'fertilizer_type_id' => $typeId,
                        'fertilizer_id' => $fertId,
                        'amount' => $detail['amount'],
                        'unit' => $detail['unit']
                    ];
                }
            }
        }
    }

    $cropPlan->fertilizer_type_id = $validated['fertilizer_types'] ?? [];
    $cropPlan->fertilizer_details = $fertilizerDetails;

    $cropPlan->save();

    return redirect()->route('sector.crop-plans.index')
        ->with('success', 'Fertilizer details updated successfully.');
}





    public function destroy($id)
    {
        $user = Auth::user();
        $plan = KebeleCropPlan::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        $plan->delete();

        return back()->with('success', 'Crop plan deleted successfully!');
        
    }


 

public function importExcel(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls'
    ]);

    Excel::import(new KebeleCropPlansImport, $request->file('file'));

    return redirect()->route('sector.crop-plans.index')
        ->with('success', '✅ Crop plans imported successfully!');
}



 
public function downloadTemplate()
{
    return Excel::download(new KebeleCropPlanTemplateExport, 'kebele_crop_plan_template.xlsx');
}


 

public function showImportForm()
{
    return view('sector.crop_plans.import');
}

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls',
    ]);

    Excel::import(new KebeleCropPlanImport, $request->file('file'));

    return redirect()->route('sector.crop-plans.index')
        ->with('success', '✅ Kebele Crop Plans imported successfully!');
}



}
