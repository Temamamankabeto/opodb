<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livestockproduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'baseline_year',
        'plan_year',
        'calves_ai_baseline',
        'calves_ai_plan',
        'eggs_produced_baseline',
        'eggs_produced_plan',
        'laying_chickens_baseline',
        'laying_chickens_plan',
        'eggs_per_chicken_baseline',
        'eggs_per_chicken_plan',
        'meat_chicken_baseline',
        'meat_chicken_plan',
        'meat_chickens_count_baseline',
        'meat_chickens_count_plan',
        'meat_per_chicken_baseline',
        'meat_per_chicken_plan',
        'honey_produced_baseline',
        'honey_produced_plan',
        'honey_per_beehive_baseline',
        'honey_per_beehive_plan',
        'fish_supplied_baseline',
        'fish_supplied_plan',
        'milk_produced_baseline',
        'milk_produced_plan',
        'lactating_cows_baseline',
        'lactating_cows_plan',
        'milk_per_cow_baseline',
        'milk_per_cow_plan',
    ];
}
