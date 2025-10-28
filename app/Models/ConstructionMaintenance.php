<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConstructionMaintenance extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'construction_maintenances';

    protected $fillable = [
        'baseline_year',
        'plan_year',
        
        // Solar Center
        'solar_center_baseline',
        'solar_center_plan',
        
        // Biogas Construction
        'biogas_family_baseline',
        'biogas_family_plan',
        'biogas_community_baseline',
        'biogas_community_plan',
        'biogas_cost_baseline',
        'biogas_cost_plan',
        'biogas_idaama_baseline',
        'biogas_idaama_plan',
        
        // Electric Lines
        'electric_lines_baseline',
        'electric_lines_plan',
        
        // Rainfall Water Harvesting
        'rainfall_family_baseline',
        'rainfall_family_plan',
        'rainfall_community_baseline',
        'rainfall_community_plan',
        'rainfall_cost_baseline',
        'rainfall_cost_plan',
        'rainfall_idaama_baseline',
        'rainfall_idaama_plan',
        
        // Water Spring Development
        'water_spring_baseline',
        'water_spring_plan',
        
        // Hand Developed Well
        'well_family_baseline',
        'well_family_plan',
        'well_community_baseline',
        'well_community_plan',
        'well_cost_baseline',
        'well_cost_plan',
        'well_idaama_baseline',
        'well_idaama_plan',
        
        // Water Supply Maintenance
        'water_supply_maintenance_baseline',
        'water_supply_maintenance_plan',
        
        // New Road Construction
        'new_road_baseline',
        'new_road_plan',
        
        // Road Maintenance
        'road_maintenance_baseline',
        'road_maintenance_plan',
        
        // Water Furrow Construction
        'water_furrow_baseline',
        'water_furrow_plan',
        
        // Small Bridges Construction
        'small_bridges_baseline',
        'small_bridges_plan',
    ];

    protected $casts = [
        'baseline_year' => 'integer',
        'plan_year' => 'integer',
    ];
}
