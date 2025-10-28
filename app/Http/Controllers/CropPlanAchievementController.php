<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\CropPlanAchievement;
use App\Models\CropType;
use App\Models\LivestockType;
use App\Models\YealrlyCropPlan;
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;

class CropPlanAchievementController extends Controller
{
    public function index(Request $request)
    {
       
        
        $selectedYear = $request->input('year');

        $cropPlans = YearlyCropPlan::with('crop','croptype')
        ->select('plan_year', 'crop_id', 'summury_production_plan')
        ->get()

        ->groupBy('plan_year');

    $chartData = [];

    foreach ($cropPlans as $year => $plans) {
        $labels = [];
        $data = [];

        foreach ($plans as $plan) {
            if ($plan->crop && $plan->crop->name && $plan->summury_production_plan) {
                $labels[] = $plan->crop->name;
                $data[] = (float) $plan->summury_production_plan;
            }
        }

        $chartData[$year] = [
            'labels' => $labels,
            'data' => $data,
        ];
    }


    $achievementsQuery = YearlyCropPlan::with(['crop', 'croptype']);
    if ($selectedYear) {
        $achievementsQuery->where('production_year', $selectedYear);
    }
    $achievements = $achievementsQuery->get();

    $grouped = $achievements->groupBy(function ($item) {
        return $item->crop_type_id . '_' . $item->maqaa_midhaanii;
    
});

             
        return view('crop-plan.index', compact('achievements','grouped','chartData'));
    }


    public function indexrawwii(Request $request)
    {
        $years = YearlyCropPlan::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');
        $selectedYear = $request->input('year', $years->first());

        $achievements = CropPlanAchievement::with('cropType')
            ->where('year', $selectedYear)
            ->get();

        return view('PA.crop_plan_achievements.raawwii', compact('achievements', 'years', 'selectedYear'));
    }


    public function create()
    {
        $cropTypes = CropType::all();
        return view('crop-plan.create', compact('cropTypes'));
    }

    public function livestockcreate()
    {
        $livestocktypes = LivestockType::all();
        return view('crop-plan.livestock_create', compact('livestocktypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_year' => 'required|string',
            'production_year' => 'required|string',
            'croptype_id' => 'nullable|exists:crop_types,id',
            'crop_id' => 'nullable|exists:crops,id',

            // Plan fields
            'summury_land_area_plan' => 'nullable|string',
            'spring_land_area_plan' => 'nullable|string',
            'autumn_land_area_plan' => 'nullable|string',
            'total_land_area_plan' => 'nullable|string',

            'summury_production_plan' => 'nullable|string',
            'spring_production_plan' => 'nullable|string',
            'autumn_production_plan' => 'nullable|string',
            'total_production_plan' => 'nullable|string',

            'summury_productivity_plan' => 'nullable|string',
            'spring_productivity_plan' => 'nullable|string',
            'autumn_productivity_plan' => 'nullable|string',
            'total_productivity_plan' => 'nullable|string',

            // Achievement fields
            'summury_land_area_achievement' => 'nullable|string',
            'spring_land_area_achievement' => 'nullable|string',
            'sautumn_land_area_achievement' => 'nullable|string',
            'total_land_area_achievement' => 'nullable|string',


            'summury_production_achievement' => 'nullable|string',
            'spring_production_achievement' => 'nullable|string',
            'sautumn_production_achievement' => 'nullable|string',

            'total_production_acheved' => 'nullable|string',

            'summury_productivity_achieved' => 'nullable|string',
            'spring_productivity_achieved' => 'nullable|string',
            'autumn_productivity_achieved' => 'nullable|string',
            'total_productivity_achieved' => 'nullable|string',

            'performancy' => 'nullable|string',
            'created_by' => 'nullable|string',
        ]);


        $validated['created_by'] = auth()->user()->name ?? 'System';

        YearlyCropPlan::create($validated);
        return redirect()->route('crop_plan_achievements.index')->with('success', 'Saved successfully!');
    }

    public function edit(YearlyCropPlan $yearlyCropPlan)
    {
        $cropTypes = CropType::all();
        $crops = Crop::all();
        return view('yearly_crop_plans.edit', compact('yearlyCropPlan', 'cropTypes', 'crops'));
    }


    public function update(Request $request, YearlyCropPlan $yearlyCropPlan)
    {
        $validated = $request->validate([
            'plan_year' => 'required|string',
            'production_year' => 'required|string',
            'croptype_id' => 'nullable|exists:crop_types,id',
            'crop_id' => 'nullable|exists:crops,id',

            // Plan fields
            'summury_land_area_plan' => 'nullable|string',
            'spring_land_area_plan' => 'nullable|string',
            'autumn_land_area_plan' => 'nullable|string',
            'total_land_area_plan' => 'nullable|string',

            'summury_production_plan' => 'nullable|string',
            'spring_production_plan' => 'nullable|string',
            'autumn_production_plan' => 'nullable|string',
            'total_production_plan' => 'nullable|string',

            'summury_productivity_plan' => 'nullable|string',
            'spring_productivity_plan' => 'nullable|string',
            'autumn_productivity_plan' => 'nullable|string',
            'total_productivity_plan' => 'nullable|string',

            // Achievement fields
            'summury_land_area_achievement' => 'nullable|string',
            'spring_land_area_achievement' => 'nullable|string',
            'sautumn_land_area_achievement' => 'nullable|string',

            'summury_production_achievement' => 'nullable|string',
            'spring_production_achievement' => 'nullable|string',
            'sautumn_production_achievement' => 'nullable|string',

            'total_production_acheved' => 'nullable|string',

            'summury_productivity_achieved' => 'nullable|string',
            'spring_productivity_achieved' => 'nullable|string',
            'autumn_productivity_achieved' => 'nullable|string',
            'total_productivity_achieved' => 'nullable|string',

            'performancy' => 'nullable|string',
            'created_by' => 'nullable|string',
        ]);


        $yearlyCropPlan->update($validated);

        return redirect()->route('crop_plan_achievements.index')->with('success', 'Crop Plan Updated Successfully!');
    }


    public function destroy($id)
    {
        $achievement = CropPlanAchievement::findOrFail($id);
        $achievement->delete();

        return redirect()->route('crop_plan_achievements.index')->with('success', 'Deleted successfully!');
    }

    public function getCropsByType($crop_type_id)
    {
        $crops = Crop::where('croptype_id', $crop_type_id) // Changed from croptype_id
        ->select('id', 'name')
            ->get();

        return response()->json($crops);
    }


    public function getPreviousYearCropData(Request $request)
    {
        $previousYear = $request->year - 1;

        $data = CropPlanAchievement::where('crop_type_id', $request->crop_type_id)
            ->where('maqaa_midhaanii', $request->maqaa)
            ->where('year', $previousYear)
            ->first();

        return response()->json($data);
    }


}
