<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NurseryDataEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'baseline_year',
        'plan_year',
        'nursery_quantity_baseline',
        'nursery_quantity_plan',
        'nursery_area_baseline',
        'nursery_area_plan',
        'nursery_participant_baseline',
        'nursery_participant_plan',
        'nursery_days_baseline',
        'nursery_days_plan',
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
        'fruits_idaama_baseline',
        'fruits_idaama_plan',
        'total_number_of_participant_baseline',
        'total_number_of_participant_plan',
        'total_number_of_days_baseline',
        'total_number_of_days_plan',
    ];

    protected $casts = [
        'baseline_year' => 'integer',
        'plan_year' => 'integer',
        'nursery_quantity_baseline' => 'decimal:2',
        'nursery_quantity_plan' => 'decimal:2',
        'nursery_area_baseline' => 'decimal:2',
        'nursery_area_plan' => 'decimal:2',
        'signal_baseline' => 'decimal:2',
        'signal_plan' => 'decimal:2',
        'sweetener_baseline' => 'decimal:2',
        'sweetener_plan' => 'decimal:2',
        'coffee_baseline' => 'decimal:2',
        'coffee_plan' => 'decimal:2',
        'tea_baseline' => 'decimal:2',
        'tea_plan' => 'decimal:2',
        'avokaadoo_baseline' => 'decimal:2',
        'avokaadoo_plan' => 'decimal:2',
        'paappaayaa_baseline' => 'decimal:2',
        'paappaayaa_plan' => 'decimal:2',
        'appilli_baseline' => 'decimal:2',
        'appilli_plan' => 'decimal:2',
        'fruits_others_baseline' => 'decimal:2',
        'fruits_others_plan' => 'decimal:2',
        'fruits_idaama_baseline' => 'decimal:2',
        'fruits_idaama_plan' => 'decimal:2',
        'total_number_of_participant_baseline' => 'integer',
        'total_number_of_participant_plan' => 'integer',
        'total_number_of_days_baseline' => 'integer',
        'total_number_of_days_plan' => 'integer',
    ];
}
