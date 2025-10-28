<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthPlan extends Model
{
    //
    protected $fillable = [
        'baseline_year',
        'plan_year',
        'pregnant_followed_baseline',
        'pregnant_followed_plan',
        'institution_delivery_baseline',
        'institution_delivery_plan',
        'postpartum_followup_baseline',
        'postpartum_followup_plan',
        'balanced_food_support_baseline',
        'balanced_food_support_plan',
        'newborn_vaccination_followup_baseline',
        'newborn_vaccination_followup_plan',
        'under5_campaign_vaccine_baseline',
        'under5_campaign_vaccine_plan',
        'under5_diarrhea_baseline',
        'under5_diarrhea_plan',
        'malaria_site_cleaned_baseline',
        'malaria_site_cleaned_plan',
        'family_planning_service_baseline',
        'family_planning_service_plan',
        'community_insurance_spouses_baseline',
        'community_insurance_spouses_plan',
        'community_insurance_contribution_baseline',
        'community_insurance_contribution_plan',
        'child_development_centers_baseline',
        'child_development_centers_plan',
        'ecd_food_low_income_baseline',
        'ecd_food_low_income_plan',
        'ecd_centers_baseline',
        'ecd_centers_plan',
        'idaama_baseline',
        'idaama_plan',
        'zone_id',
        'kebele_id',
        'woreda_id',
        'user_id',
    ];
}
