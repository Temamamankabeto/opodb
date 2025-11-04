<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\CropType;
use Illuminate\Http\Request;
use App\Models\KebeleCropPlan;
use App\Models\CropConfiguration;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KebeleCropPlanImport;
use App\Imports\KebeleCropPlansImport;
use App\Exports\KebeleCropPlanTemplateExport;


class KebeleCropPlanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $cropPlans = KebeleCropPlan::with(['cropType', 'crop'])
            ->where('sector_id', auth()->user()->sector_id)
            ->paginate(10);

        return view('sector.crop_plans.index', compact('cropPlans'));
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

        return view('sector.crop_plans.create', compact('years', 'cropTypes'));
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
            'responsible_staff' => 'nullable|string|max:255',
        ]);

        // ✅ Prevent duplicates
        $existingPlan = KebeleCropPlan::where('kebele_id', $user->kebele_id)
            ->where('sector_id', $user->sector_id)
            ->where('year', $validated['year'])
            ->where('season', $validated['season'])
            ->where('croptype_id', $validated['croptype_id'])
            ->where('crop_id', $validated['crop_id'])
            ->first();

        if ($existingPlan) {
            return back()->withErrors([
                'duplicate' => 'A crop plan for this year, crop type, crop, and season already exists.'
            ])->withInput();
        }

        $area = $validated['area_to_plant'];
        $productivity = $validated['productivity'];
        $expectedYield = $area * $productivity;

        // ✅ Fetch CropConfiguration for selected year, crop, and type
        $config = CropConfiguration::where('year', $validated['year'])
            ->where('crop_id', $validated['crop_id'])
            ->where('croptype_id', $validated['croptype_id'])
            ->where('kebele_id', $user->kebele_id)
            ->first();

        $seedType = $config?->crop?->name ?? 'Seeds';
        $seedsPerHa = $config?->seed_rate_kg_per_ha ?? 20;
        $totalSeeds = $area * $seedsPerHa;

        

        KebeleCropPlan::create([
            'kebele_id' => $user->kebele_id,
            'sector_id' => $user->sector_id,
            'croptype_id' => $validated['croptype_id'],
            'crop_id' => $validated['crop_id'],
            'area_to_plant' => $area,
            'productivity' => $productivity,
            'expected_yield' => $expectedYield,
        
            'year' => $validated['year'],
            'season' => $validated['season'],
            'responsible_staff' => $validated['responsible_staff'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'created_by' => $user->id,
        ]);

        return redirect()->route('sector.crop-plans.index')->with('success', '✅ Kebele Crop Plan added successfully!');
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

        $plan = KebeleCropPlan::where('id', $id)
            ->where('sector_id', $user->sector_id)
            ->firstOrFail();

        // Validate the input
        $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'croptype_id' => 'required|exists:crops,id',   // your croptype_id foreign key
            'crop_id' => 'required|exists:crops,id',
            'area_to_plant' => 'required|numeric|min:0',
            'expected_yield' => 'required|numeric|min:0',
            'productivity' => 'nullable|numeric|min:0',
            'season' => 'required|string|max:255',
            'input_needed' => 'nullable|string|max:255',
            'responsible_staff' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Update plan
        $plan->update($request->only([
            'year',
            'croptype_id',
            'crop_id',
            'area_to_plant',
            'expected_yield',
            'productivity',
            'season',
            'input_needed',
            'responsible_staff',
            'notes',
        ]));

        return redirect()->route('sector.crop-plans.index')
            ->with('success', 'Crop plan updated successfully!');
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
