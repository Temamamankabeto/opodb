<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentFoodSupport extends Model
{
    //
    protected $fillable = [
        'school_year',
        'produced_by_school_baseline',
        'produced_by_school_plan',
        'collected_by_community_baseline',
        'collected_by_community_plan',
        'sum_supply_baseline',
        'sum_supply_plan',
        'food_price_school_baseline',
        'food_price_school_plan',
        'community_contributions_baseline',
        'community_contributions_plan',
        'sum_expense_baseline',
        'sum_expense_plan',
        'zone_id',
        'woreda_id',
        'kebele_id',
    ];

}
