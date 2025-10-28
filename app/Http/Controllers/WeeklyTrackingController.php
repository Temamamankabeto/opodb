<?php

namespace App\Http\Controllers;

use App\Models\Kebele;
use App\Models\User;
use App\Models\WeeklyCropPlanAchievement;
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WeeklyTrackingController extends Controller
{
    public function create()
    {
        $planYears = range(2010, (int)date('Y') + 5);
        $kebeles = Kebele::all();
        $yearlyCropPlans = YearlyCropPlan::all();
        $measurements = ['Hectare', 'Square Meter', 'Quintal', 'Ton'];
        $cropTypes = ['Cereal', 'Pulse', 'Oil Crop', 'Vegetable', 'Fruit'];

        return view('crop-plan.weekly_activity', [
            'planYears' => $planYears,
            'kebeles' => $kebeles,
            'yearlyCropPlans' => $yearlyCropPlans,
            'measurements' => $measurements,
            'cropTypes' => $cropTypes
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kebele_id' => 'required|exists:kebeles,id',
            'yearly_crop_plan_id' => 'required|exists:yearly_crop_plans,id',
            'measurement' => 'required|string',
            'crop_type' => 'required|string',
            'crop_name' => 'required|string',
            'baseline_year' => 'required|integer|min:2000|max:2100',
            'plan_year' => 'required|integer|min:2000|max:2100|different:baseline_year',
            'yearly_plan' => 'nullable|numeric',
            'weekly_plan' => 'nullable|numeric',
            'weekly_achievement' => 'nullable|numeric',
            'weekly_achievement_percentage' => 'nullable|numeric|between:0,100',
            'upto_this_week_plan' => 'nullable|numeric',
            'upto_this_week_achievement' => 'nullable|numeric',
            'upto_this_week_percentage_achievement' => 'nullable|numeric|between:0,100',
            'yearly_percentage_achievement' => 'nullable|numeric|between:0,100',
            // Include validation for all your original fields
            'solar_center_baseline' => 'nullable|numeric',
            // ... etc ...
        ]);

        // Add authenticated user ID
        $validated['user_id'] = Auth::id();

        WeeklyCropPlanAchievement::create($validated);

        return redirect()->route('construction_maintenance.create')
            ->with('success', 'Data saved successfully!');
    }


    public function getCropTypes($year)
    {
        $types = YearlyCropPlan::where('plan_year', $year)
            ->with('cropType')
            ->get()
            ->pluck('cropType')
            ->unique('id')
            ->values();

        return response()->json($types);
    }

    public function getCrops($year, $cropTypeId)
{
    $crops = YearlyCropPlan::where('plan_year', $year)
        ->where('crop_type_id', $cropTypeId)
        ->select('name') // change if your crop name column is different
        ->distinct()
        ->get()
        ->map(function ($item) {
            return [
                'name' => $item->crop_id // make sure this column exists
            ];
        });

    return response()->json($crops);
}

public function karora(){
    $yearlyCropPlans=YearlyCropPlan::with('cropType')->get();

    return view('karora.list',compact('yearlyCropPlans'));
}

public function Karoratorbee(){

    $planYears=YearlyCropPlan::with('cropType')->get();
    $users=User::all();
    $kebeles=Kebele::all();
    

    return view('karora.karor_torbee',compact('planYears','users','kebeles'));
}
public function weeklyppfParticipationContinity(){

    $planYears=YearlyCropPlan::with('cropType')->get();

    return view('karora.social_development.weekly_plan_perform',compact(''));
}



}
