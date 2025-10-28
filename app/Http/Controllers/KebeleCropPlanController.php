<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\CropType;
use App\Models\KebeleCropPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function getCropsByType($croptypeId)
    {
        $crops = Crop::with('fertilizers')
            ->where('croptype_id', $croptypeId)
            ->get()
            ->mapWithKeys(function ($crop) {
                return [
                    $crop->id => [
                        'name' => $crop->name,
                        'seeds_per_ha' => $crop->seeds_per_ha ?? 20,
                        'fertilizers' => $crop->fertilizers->map(fn($f) => [
                            'type' => $f->type,
                            'quantity_per_ha' => $f->quantity_per_ha,
                        ])->toArray(),
                    ],
                ];
            });

        return response()->json($crops);
    }



    public function create()
    {
        $cropTypes = CropType::all();
        $crops = Crop::with('fertilizers')->get();

        return view('sector.crop_plans.create', compact('cropTypes', 'crops'));
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
            'notes' => 'nullable|string',
        ]);

        // ✅ Prevent planning for past Ethiopian years
        $currentEthYear = \App\Helpers\EthiopianDateHelper::currentEthiopianYear();
        if ($validated['year'] < $currentEthYear) {
            return back()->withErrors([
                'year' => "You cannot plan for past years. Current Ethiopian year is $currentEthYear."
            ])->withInput();
        }

        // Duplicate check
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
        $responsible = $validated['responsible_staff'];
        $productivity = $validated['productivity'];
        $expectedYield = $area * $productivity;

        $crop = Crop::with('fertilizers')->find($validated['crop_id']);

        // Seeds
        $seedType = $crop->name ?? 'Seeds';
        $seedsPerHa = $crop->seeds_per_ha ?? 20;
        $totalSeeds = $area * $seedsPerHa;

        // Fertilizers
        $fertList = $crop->fertilizers->map(function ($f) use ($area) {
            return $f->type . ': ' . ($f->quantity_per_ha * $area) . ' kg';
        })->implode(', ');

        $inputNeeded = $seedType . ': ' . $totalSeeds . ' kg' . ($fertList ? ', ' . $fertList : '');

        // Save crop plan
        KebeleCropPlan::create([
            'kebele_id' => $user->kebele_id,
            'sector_id' => $user->sector_id,
            'croptype_id' => $validated['croptype_id'],
            'crop_id' => $validated['crop_id'],
            'area_to_plant' => $area,
            'productivity' => $productivity,
            'expected_yield' => $expectedYield,
            'input_needed' => $inputNeeded,
            'year' => $validated['year'],
            'season' => $validated['season'],
            'responsible_staff' => $responsible,
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
}
