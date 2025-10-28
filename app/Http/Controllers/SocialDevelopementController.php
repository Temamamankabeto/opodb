<?php

namespace App\Http\Controllers;

use App\Models\CropType;
use App\Models\StudentEffectiveness;
use App\Models\StudentFoodSupport;
use App\Models\StudentParticipations;
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SocialDevelopementController extends Controller
{
    //
    public function create()
    {
        $cropTypes = CropType::all();
        $planYears = YearlyCropPlan::select('plan_year')
            ->distinct()
            ->orderBy('plan_year', 'desc')
            ->pluck('plan_year');

        return view('social_development.new', compact('cropTypes', 'planYears'));
    }

    public function SaveStudentParticipation(Request $request)
    {
        $new = new StudentParticipations();

        $new->baseline_year = $request->baseline_year;
        $new->plan_year = $request->plan_year;
        $new->registration_number_baseline = $request->registration_number_baseline;
        $new->registration_number_plan = $request->registration_number_plan;

        $new->grade_1_6_participation_baseline = $request->grade_1_6_participation_baseline; // May conflict — should be unique name in form
        $new->grade_1_6_participation_plan = $request->grade_1_6_participation_plan;

        $new->grade_7_8_participation_baseline = $request->grade_7_8_participation_baseline;
        $new->grade_7_8_participation_plan = $request->grade_7_8_participation_plan;

        $new->grade_9_12_participation_baseline = $request->grade_9_12_participation_baseline;
        $new->grade_9_12_participation_plan = $request->grade_9_12_participation_plan;

        $new->adult_education_baseline = $request->adult_education_baseline;
        $new->adult_education_plan = $request->adult_education_plan;

        // --- Primary School: Graduates Continuity ---
        $new->graduation_number_baseline = $request->graduation_number_baseline;
        $new->graduation_number_plan = $request->graduation_number_plan;

        $new->grade_1_6_graduated_baseline = $request->grade_1_6_graduated_baseline; // this input name is unique
        $new->grade_7_8_graduated_baseline = $request->grade_7_8_graduated_baseline;

        $new->grade_7_8_graduated_baseline = $request->grade_7_8_graduated_baseline;
        $new->grade_7_8_graduated_plan = $request->grade_7_8_graduated_plan;

        $new->grade_9_12_graduated_baseline = $request->grade_9_12_graduated_baseline;
        $new->grade_9_12_graduated_plan = $request->grade_9_12_graduated_plan;

        $new->primary_school_cost_baseline = $request->primary_school_cost_baseline;
        $new->primary_school_cost_plan = $request->primary_school_cost_plan;
        $new->zone_id = Auth::user()->zone_id;
        $new->woreda_id = Auth::user()->woreda_id;
        $new->kebele_id = Auth::user()->kebele_id;
//dd(Auth::user()->kebele_id);
        $new->save();

        return redirect()->back()->with('success', 'Student participation data stored successfully.');

    }

    public function studentEffectivenessCreate()
    {
        $cropTypes = CropType::all();
        $planYears = YearlyCropPlan::select('plan_year')
            ->distinct()
            ->orderBy('plan_year', 'desc')
            ->pluck('plan_year');

        return view('social_development.effectiveness_student', compact('cropTypes', 'planYears'));

    }


    public function studentEffectivenessStore(Request $request)
    {
        $student_effectiveness = new StudentEffectiveness();

        $student_effectiveness->grade_1_5_class_baseline = $request->baseline_grade_1_5_class;
        $student_effectiveness->grade_1_5_class_plan = $request->plan_grade_1_5_class;

        $student_effectiveness->grade_1_5_exam_baseline = $request->baseline_grade_1_5_exam;
        $student_effectiveness->grade_1_5_exam_plan = $request->plan_grade_1_5_exam;

        $student_effectiveness->grade_6_sit_baseline = $request->baseline_grade_6_sit;
        $student_effectiveness->grade_6_sit_plan = $request->plan_grade_6_sit;

        $student_effectiveness->grade_6_pass_baseline = $request->baseline_grade_6_pass;
        $student_effectiveness->grade_6_pass_plan = $request->plan_grade_6_pass;

        $student_effectiveness->grade_7_class_baseline = $request->baseline_grade_7_class;
        $student_effectiveness->grade_7_class_plan = $request->plan_grade_7_class;

        $student_effectiveness->grade_7_exam_baseline = $request->baseline_grade_7_exam;
        $student_effectiveness->grade_7_exam_plan = $request->plan_grade_7_exam;

        $student_effectiveness->grade_8_take_baseline = $request->baseline_grade_8_take;
        $student_effectiveness->grade_8_take_plan = $request->plan_grade_8_take;

        $student_effectiveness->grade_8_pass_baseline = $request->baseline_grade_8_pass;
        $student_effectiveness->grade_8_pass_plan = $request->plan_grade_8_pass;

        $student_effectiveness->grade_9_11_class_baseline = $request->baseline_grade_9_11_class;
        $student_effectiveness->grade_9_11_class_plan = $request->plan_grade_9_11_class;

        $student_effectiveness->grade_9_11_exam_baseline = $request->baseline_grade_9_11_exam;
        $student_effectiveness->grade_9_11_exam_plan = $request->plan_grade_9_11_exam;

        $student_effectiveness->grade_12_take_baseline = $request->baseline_grade_12_take;
        $student_effectiveness->grade_12_take_plan = $request->plan_grade_12_take;

        $student_effectiveness->grade_12_pass_baseline = $request->baseline_grade_12_pass;
        $student_effectiveness->grade_12_pass_plan = $request->plan_grade_12_pass;

        $student_effectiveness->zone_id = Auth::user()->zone_id;
        $student_effectiveness->woreda_id = Auth::user()->woreda_id;
        $student_effectiveness->kebele_id = Auth::user()->kebele_id;

        $student_effectiveness->save();


        return redirect()->back()->with('success', 'Data saved.');
    }

    public function studentFoodSupply()
    {
        $cropTypes = CropType::all();
        $planYears = YearlyCropPlan::select('plan_year')
            ->distinct()
            ->orderBy('plan_year', 'desc')
            ->pluck('plan_year');

        return view('social_development.student_food_supply', compact('cropTypes', 'planYears'));

    }

    public function studentFoodSupplyStore(Request $request)
    {
        $student_food_supply = new StudentFoodSupport();

        // Students that need food
        $student_food_supply->produced_by_school_baseline = $request->baseline_produced_by_school;
        $student_food_supply->produced_by_school_plan = $request->plan_produced_by_school;

        $student_food_supply->collected_by_community_baseline = $request->baseline_collected_by_community;
        $student_food_supply->collected_by_community_plan = $request->plan_collected_by_community;

        $student_food_supply->idaama_supply_baseline = $request->baseline_idaama_supply;
        $student_food_supply->idaama_supply_plan = $request->plan_idaama_supply;

        // Expense of students food
        $student_food_supply->food_price_school_baseline = $request->baseline_food_price_school;
        $student_food_supply->food_price_school_plan = $request->plan_food_price_school;

        $student_food_supply->community_contributions_baseline = $request->baseline_community_contributions;
        $student_food_supply->community_contributions_plan = $request->plan_community_contributions;

        $student_food_supply->idaama_expense_baseline = $request->baseline_idaama_expense;
        $student_food_supply->idaama_expense_plan = $request->plan_idaama_expense;

        // Additional common fields (optional)
        $student_food_supply->zone_id = Auth::user()->zone_id;
        $student_food_supply->woreda_id = Auth::user()->woreda_id;
        $student_food_supply->kebele_id = Auth::user()->kebele_id;

        $student_food_supply->save();
        return redirect()->back()->with('success', 'Student Food Supply saved successfully!');
    }
}
