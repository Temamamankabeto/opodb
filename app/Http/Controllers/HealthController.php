<?php

namespace App\Http\Controllers;

use App\Models\CropType;
use App\Models\HealthPlan;
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthController extends Controller
{
    //
    public function create()
    {
        $cropTypes = CropType::all();
        $planYears = YearlyCropPlan::select('plan_year')
            ->distinct()
            ->orderBy('plan_year', 'desc')
            ->pluck('plan_year');
        return view('health.new', compact('cropTypes', 'planYears'));
    }

    public function store(Request $request)
    {
        $new = new HealthPlan();

        $new->baseline_year = $request->baseline_year;
        $new->plan_year = $request->plan_year;

        // Health Data Fields
        $new->pregnant_followed_baseline = $request->pregnant_followed_baseline;
        $new->pregnant_followed_plan = $request->pregnant_followed_plan;

        $new->institution_delivery_baseline = $request->institution_delivery_baseline;
        $new->institution_delivery_plan = $request->institution_delivery_plan;

        $new->postpartum_followup_baseline = $request->postpartum_followup_baseline;
        $new->postpartum_followup_plan = $request->postpartum_followup_plan;

        $new->balanced_food_support_baseline = $request->balanced_food_support_baseline;
        $new->balanced_food_support_plan = $request->balanced_food_support_plan;

        $new->newborn_vaccination_followup_baseline = $request->newborn_vaccination_followup_baseline;
        $new->newborn_vaccination_followup_plan = $request->newborn_vaccination_followup_plan;

        $new->under5_campaign_vaccine_baseline = $request->under5_campaign_vaccine_baseline;
        $new->under5_campaign_vaccine_plan = $request->under5_campaign_vaccine_plan;

        $new->under5_diarrhea_baseline = $request->under5_diarrhea_baseline;
        $new->under5_diarrhea_plan = $request->under5_diarrhea_plan;

        $new->malaria_site_cleaned_baseline = $request->malaria_site_cleaned_baseline;
        $new->malaria_site_cleaned_plan = $request->malaria_site_cleaned_plan;

        $new->family_planning_service_baseline = $request->family_planning_service_baseline;
        $new->family_planning_service_plan = $request->family_planning_service_plan;

        $new->community_insurance_spouses_baseline = $request->community_insurance_spouses_baseline;
        $new->community_insurance_spouses_plan = $request->community_insurance_spouses_plan;

        $new->community_insurance_contribution_baseline = $request->community_insurance_contribution_baseline;
        $new->community_insurance_contribution_plan = $request->community_insurance_contribution_plan;

        $new->child_development_centers_baseline = $request->child_development_centers_baseline;
        $new->child_development_centers_plan = $request->child_development_centers_plan;

        // ECD Expenditure
        $new->ecd_food_low_income_baseline = $request->ecd_food_low_income_baseline;
        $new->ecd_food_low_income_plan = $request->ecd_food_low_income_plan;

        $new->ecd_centers_baseline = $request->ecd_centers_baseline;
        $new->ecd_centers_plan = $request->ecd_centers_plan;

        $new->idaama_baseline = $request->idaama_baseline;
        $new->idaama_plan = $request->idaama_plan;
        $new->zone_id = Auth::user()->zone_id;
        $new->woreda_id = Auth::user()->woreda_id;
        $new->kebele_id = Auth::user()->kebele_id;
        $new->user_id = Auth::user()->id;


        $new->save();

        return redirect()->back()->with('success', 'Health plan data saved successfully.');

    }
}
