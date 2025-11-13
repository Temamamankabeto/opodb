<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\CropType;
use App\Models\InputPlan;
use App\Models\Fertilizer;
use App\Models\FertilizerType;
use App\Models\KebeleCropPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InputPlanController extends Controller
{
    /** Show list of Input Plans for logged-in sector */
    public function index()
    {
        $inputPlans = InputPlan::with(['cropType', 'crop'])
            ->where('sector_id', Auth::user()->sector_id)
            ->where('kebele_id', Auth::user()->kebele_id )
            ->latest()
            ->get();

        return view('sector.input_plans.index', compact('inputPlans'));
    }

    /** Show create form */
    public function create()
    {
        $years = KebeleCropPlan::select('year')->distinct()->pluck('year');
        $fertilizerTypes = FertilizerType::all();

        return view('sector.input_plans.create', compact('years', 'fertilizerTypes'));
    }

    /** Show edit form */
 public function edit($id)
{
    $user = Auth::user();

    // Ensure input plan belongs to user's kebele and sector
    $plan = InputPlan::with(['cropType', 'crop', 'kebeleCropPlan'])
        ->where('id', $id)
        ->where('sector_id', $user->sector_id)
        ->whereHas('kebeleCropPlan', function ($query) use ($user) {
            $query->where('kebele_id', $user->kebele_id);
        })
        ->firstOrFail();

    $years = KebeleCropPlan::select('year')->distinct()->pluck('year');
    $fertilizerTypes = FertilizerType::all();

    // Load available crop types and crops dynamically (optional)
    $cropTypes = CropType::all();
    $crops = Crop::where('croptype_id', $plan->croptype_id)->get();
    $seasons = ['Ganna', 'Bona', 'Arfaasaa']; // same as your create blade

    return view('sector.input_plans.edit', compact('plan', 'years', 'fertilizerTypes', 'cropTypes', 'crops', 'seasons'));
}



    /** Store new input plan */
    public function store(Request $request)
{
    $request->validate([
        'year' => 'required',
        'croptype_id' => 'required|exists:crop_types,id',
        'crop_id' => 'required|exists:crops,id',
        'season' => 'required|string',
        'seed_required' => 'required|numeric|min:0',
    ]);

           $user = Auth::user();


    // ✅ 1. Find matching Kebele Crop Plan
    $kebeleCropPlan = KebeleCropPlan::where('year', $request->year)
        ->where('croptype_id', $request->croptype_id)
        ->where('crop_id', $request->crop_id)
        ->where('sector_id', $user->sector_id)
        ->where('season', $request->season)
        ->first();

    if (!$kebeleCropPlan) {
        return back()
            ->with('error', 'No matching Kebele Crop Plan found for this crop, season, and year.')
            ->withInput();
    }

    // ✅ 2. Prevent duplicate InputPlan for same kebele, crop, type, season, and year
    $exists = InputPlan::where([
            'year' => $request->year,
            'croptype_id' => $request->croptype_id,
            'crop_id' => $request->crop_id,
            'sector_id' => $user->sector_id,
            'kebele_id' => $user->kebele_id,
            'season' => $request->season,
        ])
        ->whereHas('kebeleCropPlan', function ($query) use ($user) {
            $query->where('kebele_id', $user->kebele_id);
        })
        ->exists();

    if ($exists) {
        return back()
            ->with('error', 'An input plan for this kebele, crop, and season already exists.')
            ->withInput();
    }


    
      
    // ✅ 3. Collect fertilizer details
    $fertilizerDetails = $this->collectFertilizerDetails($request);

    // ✅ 4. Create Input Plan
    InputPlan::create([
        'sector_id' => $user->sector_id,
        'kebele_id' => $user->kebele_id,
        'woreda_id' => $user->woreda_id,
        'zone_id' => $user->zone_id,
        'region_id' => $user->region_id,
        'kebele_crop_plan_id' => $kebeleCropPlan->id,
        'croptype_id' => $request->croptype_id,
        'crop_id' => $request->crop_id,
        'year' => $request->year,
        'season' => $request->season,
        'seed_required' => $request->seed_required,
        'fertilizer_type_id' => $request->fertilizer_types[0] ?? null,

        'fertilizer_details' => $fertilizerDetails,
        'created_by' => Auth::id(),
    ]);

    return redirect()
        ->route('sector.input-plans.index')
        ->with('success', 'Input plan created successfully!');
}


    /** Update existing input plan */
  public function update(Request $request, $id)
{
    $user = Auth::user();

    $plan = InputPlan::with('kebeleCropPlan')
        ->where('id', $id)
        ->where('sector_id', $user->sector_id)
        ->whereHas('kebeleCropPlan', function ($query) use ($user) {
            $query->where('kebele_id', $user->kebele_id);
        })
        ->firstOrFail();

    $request->validate([
        'year' => 'required',
        'croptype_id' => 'required|exists:crop_types,id',
        'crop_id' => 'required|exists:crops,id',
        'season' => 'required|string',
        'seed_required' => 'required|numeric|min:0',
    ]);

    // ✅ Check for duplicates
    $exists = InputPlan::where('year', $request->year)
        ->where('croptype_id', $request->croptype_id)
        ->where('crop_id', $request->crop_id)
        ->where('sector_id', $user->sector_id)
        ->where('season', $request->season)
        ->whereHas('kebeleCropPlan', function ($query) use ($user) {
            $query->where('kebele_id', $user->kebele_id);
        })
        ->where('id', '!=', $id)
        ->exists();

    if ($exists) {
        return back()->with('error', 'An input plan for this kebele, crop, and season already exists.')->withInput();
    }

    // ✅ Collect fertilizer details
    $fertilizerDetails = $this->collectFertilizerDetails($request);

    // ✅ Update
    $plan->update([
        'year' => $request->year,
        'croptype_id' => $request->croptype_id,
        'crop_id' => $request->crop_id,
        'season' => $request->season,
        'seed_required' => $request->seed_required,
        'fertilizer_details' => $fertilizerDetails,
        'updated_by' => Auth::id(),
    ]);

    return redirect()->route('sector.input-plans.index')
        ->with('success', 'Input plan updated successfully!');
}



    /** Delete input plan */
    public function destroy($id)
    {
        $plan = InputPlan::findOrFail($id);
        $plan->delete();

        return back()->with('success', 'Input plan deleted successfully.');
    }

    /** Helper - collect fertilizer details */
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



    /** AJAX - get crop types by year */
    public function getCropTypes($year)
    {
        $cropTypeIds = KebeleCropPlan::where('year', $year)->pluck('croptype_id')->unique();
        return response()->json(CropType::whereIn('id', $cropTypeIds)->get());
    }

    /** AJAX - get crops by crop type */
    public function getCrops($croptypeId)
    {
        
        return response()->json(Crop::where('croptype_id', $croptypeId)->get());
    }

    /** AJAX - get seasons by crop */
    public function getSeasons($cropId)
    {
        $seasons = ['Ganna', 'Bona', 'Arfaasaa']; // example - you can fetch dynamically
        return response()->json($seasons);
    }

    /** AJAX - get fertilizers by type */
    public function getFertilizers($typeId)
    {
        return response()->json(Fertilizer::where('fertilizer_type_id', $typeId)->get());
    }
}
