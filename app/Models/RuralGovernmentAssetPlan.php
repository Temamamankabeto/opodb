<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuralGovernmentAssetPlan extends Model
{
    //
    protected $table = 'rural_government_asset_plans';
    protected $fillable = [
        'user_id', 'kebele_id', 'plan_year',
        'bg_members_plan', 'bg_contributions_plan', 'bg_crop_quintal_plan',
        'bg_monetary_value_plan', 'bg_cash_plan', 'bg_idaama_plan',
        'gs_trained_plan', 'gs_assigned_daily_plan',
        'citizenship_service_plan', 'family_prosperity_plan',
        'loan_not_paid_plan', 'loan_taken_this_year_plan',
        'loan_idaama_plan', 'loan_paid_saving_plan',
        'loan_paid_coop_plan', 'loan_paid_idaama_plan',
    ];
}
