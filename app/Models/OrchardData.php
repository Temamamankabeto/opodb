<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrchardData extends Model
{
    use HasFactory;

    protected $fillable = [
        'baseline_year',
        'plan_year',
        
        // Orchard fields
        'orchard_area_baseline',
        'orchard_area_plan',
        'orchard_participant_baseline',
        'orchard_participant_plan',
        'orchard_days_baseline',
        'orchard_days_plan',
        
        // Seedling fields
        'signal_baseline',
        'signal_plan',
        'sweetener_baseline',
        'sweetener_plan',
        'coffee_baseline',
        'coffee_plan',
        'tea_baseline',
        'tea_plan',
        'seedling_participant_baseline',
        'seedling_participant_plan',
        'seedling_days_baseline',
        'seedling_days_plan',
        
        // Fruits fields
        'avokaadoo_baseline',
        'avokaadoo_plan',
        'paappaayaa_baseline',
        'paappaayaa_plan',
        'appilli_baseline',
        'appilli_plan',
        'fruits_others_baseline',
        'fruits_others_plan',
        'fruits_participant_baseline',
        'fruits_participant_plan',
        'fruits_days_baseline',
        'fruits_days_plan',
        
        // Calculated fields
        'fruits_idaama_baseline',
        'fruits_idaama_plan',
        'total_number_of_participant_baseline',
        'total_number_of_participant_plan',
        'total_number_of_days_baseline',
        'total_number_of_days_plan',
    ];
}
