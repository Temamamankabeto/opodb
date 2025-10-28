<?php

namespace App\Http\Controllers;

use App\Models\CropType;
use App\Models\UpgaradingConstruction;
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;

class UpgradingConstructionPlanController extends Controller
{
    public function create()
    {
        $cropTypes = CropType::all();
                $planYears = YearlyCropPlan::select('plan_year')
                                ->distinct()
                                ->orderBy('plan_year', 'desc')
                                ->pluck('plan_year');
                
                return view('crop-plan.upgrade_construction', compact('cropTypes', 'planYears'));
    }

    /**
     * Store a newly created construction plan in the database.
     */
    public function store(Request $request)
    {
        // Validate incoming data
        $validatedData = $request->validate([
            'baseline_year' => 'required|integer',
            'plan_year' => 'required|integer',
            // You can add more specific validation if needed
        ]);

        // Save the construction plan
        UpgaradingConstruction::create([
            'baseline_year' => $request->baseline_year,
            'plan_year' => $request->plan_year,

            'butuura_school_number_baseline' => $request->butuura_school_number_baseline,
            'butuura_school_number_plan' => $request->butuura_school_number_plan,
            'butuura_school_cost_baseline' => $request->butuura_school_cost_baseline,
            'butuura_school_cost_plan' => $request->butuura_school_cost_plan,

            'primary_school_number_baseline' => $request->primary_school_number_baseline,
            'primary_school_number_plan' => $request->primary_school_number_plan,
            'primary_school_cost_baseline' => $request->primary_school_cost_baseline,
            'primary_school_cost_plan' => $request->primary_school_cost_plan,

            'teachers_house_number_baseline' => $request->teachers_house_number_baseline,
            'teachers_house_number_plan' => $request->teachers_house_number_plan,
            'teachers_house_cost_baseline' => $request->teachers_house_cost_baseline,
            'teachers_house_cost_plan' => $request->teachers_house_cost_plan,

            'health_center_number_baseline' => $request->health_center_number_baseline,
            'health_center_number_plan' => $request->health_center_number_plan,
            'health_center_cost_baseline' => $request->health_center_cost_baseline,
            'health_center_cost_plan' => $request->health_center_cost_plan,

            'training_center_number_baseline' => $request->training_center_number_baseline,
            'training_center_number_plan' => $request->training_center_number_plan,
            'training_center_cost_baseline' => $request->training_center_cost_baseline,
            'training_center_cost_plan' => $request->training_center_cost_plan,

            'kebele_house_number_baseline' => $request->kebele_house_number_baseline,
            'kebele_house_number_plan' => $request->kebele_house_number_plan,
            'kebele_house_cost_baseline' => $request->kebele_house_cost_baseline,
            'kebele_house_cost_plan' => $request->kebele_house_cost_plan,

            'court_office_number_baseline' => $request->court_office_number_baseline,
            'court_office_number_plan' => $request->court_office_number_plan,
            'court_office_cost_baseline' => $request->court_office_cost_baseline,
            'court_office_cost_plan' => $request->court_office_cost_plan,

            'cultural_center_number_baseline' => $request->cultural_center_number_baseline,
            'cultural_center_number_plan' => $request->cultural_center_number_plan,
            'cultural_center_cost_baseline' => $request->cultural_center_cost_baseline,
            'cultural_center_cost_plan' => $request->cultural_center_cost_plan,
        ]);

        return redirect()->route('construction_plans.create')->with('success', 'Construction Plan created successfully!');
    }
}
