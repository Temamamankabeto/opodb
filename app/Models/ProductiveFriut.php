<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductiveFriut extends Model
{
    use HasFactory;

    protected $fillable = [
        'baseline_year',
        'plan_year',
        
        'banana_production_baseline', 'banana_production_plan',
        'banana_land_area_baseline', 'banana_land_area_plan',

        'avocado_production_baseline', 'avocado_production_plan',
        'avocado_land_area_baseline', 'avocado_land_area_plan',

        'papaya_production_baseline', 'papaya_production_plan',
        'papaya_land_area_baseline', 'papaya_land_area_plan',

        'apple_production_baseline', 'apple_production_plan',
        'apple_land_area_baseline', 'apple_land_area_plan',

        'other_production_baseline', 'other_production_plan',
        'other_land_area_baseline', 'other_land_area_plan',

        'spices_production_baseline', 'spices_production_plan',
        'spices_land_area_baseline', 'spices_land_area_plan',

        'coffee_production_baseline', 'coffee_production_plan',
        'coffee_land_area_baseline', 'coffee_land_area_plan',

        'tea_production_baseline', 'tea_production_plan',
        'tea_land_area_baseline', 'tea_land_area_plan',

        'total_productive_land_baseline', 'total_productive_land_plan',
        'total_production_baseline', 'total_production_plan',
    ];
}
