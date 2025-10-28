<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewConstruction extends Model
{
    use HasFactory;

    protected $table = 'new_constructions';

    protected $fillable = [
        'baseline_year',
        'plan_year',
        
        // But'uura Boruu School
        'butuura_school_number_baseline',
        'butuura_school_cost_baseline',
        'butuura_school_number_plan',
        'butuura_school_cost_plan',
        
        // Primary school
        'primary_school_number_baseline',
        'primary_school_cost_baseline',
        'primary_school_number_plan',
        'primary_school_cost_plan',
        
        // Teachers house
        'teachers_house_number_baseline',
        'teachers_house_cost_baseline',
        'teachers_house_number_plan',
        'teachers_house_cost_plan',
        
        // Secondary Generation Healthy centre
        'health_center_number_baseline',
        'health_center_cost_baseline',
        'health_center_number_plan',
        'health_center_cost_plan',
        
        // Farmers/pastoralist Training Center
        'training_center_number_baseline',
        'training_center_cost_baseline',
        'training_center_number_plan',
        'training_center_cost_plan',
        
        // House of the leaders of kebele
        'kebele_house_number_baseline',
        'kebele_house_cost_baseline',
        'kebele_house_number_plan',
        'kebele_house_cost_plan',
        
        // Traditional court office
        'court_office_number_baseline',
        'court_office_cost_baseline',
        'court_office_number_plan',
        'court_office_cost_plan',
        
        // Cultural development center
        'cultural_center_number_baseline',
        'cultural_center_cost_baseline',
        'cultural_center_number_plan',
        'cultural_center_cost_plan',
    ];

    protected $casts = [
        'butuura_school_cost_baseline' => 'decimal:2',
        'butuura_school_cost_plan' => 'decimal:2',
        'primary_school_cost_baseline' => 'decimal:2',
        'primary_school_cost_plan' => 'decimal:2',
        'teachers_house_cost_baseline' => 'decimal:2',
        'teachers_house_cost_plan' => 'decimal:2',
        'health_center_cost_baseline' => 'decimal:2',
        'health_center_cost_plan' => 'decimal:2',
        'training_center_cost_baseline' => 'decimal:2',
        'training_center_cost_plan' => 'decimal:2',
        'kebele_house_cost_baseline' => 'decimal:2',
        'kebele_house_cost_plan' => 'decimal:2',
        'court_office_cost_baseline' => 'decimal:2',
        'court_office_cost_plan' => 'decimal:2',
        'cultural_center_cost_baseline' => 'decimal:2',
        'cultural_center_cost_plan' => 'decimal:2',
    ];
}
