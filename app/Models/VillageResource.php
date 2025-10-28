<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class VillageResource extends Model
{
    use HasFactory;

    protected $table = 'village_resources';

    protected $fillable = [
        'baseline_year',
        'plan_year',
        
        // Tractors in the village
        'tractors_private_baseline',
        'tractors_private_plan',
        'tractors_association_baseline',
        'tractors_association_plan',
        'tractors_idaama_baseline',
        'tractors_idaama_plan',
        
        // A combiner in the village
        'combiner_private_baseline',
        'combiner_private_plan',
        'combiner_association_baseline',
        'combiner_association_plan',
        'combiner_idaama_baseline',
        'combiner_idaama_plan',
        
        // Water pump/Motor in the village
        'water_pump_private_baseline',
        'water_pump_private_plan',
        'water_pump_association_baseline',
        'water_pump_association_plan',
        'water_pump_idaama_baseline',
        'water_pump_idaama_plan'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'baseline_year' => 'integer',
        'plan_year' => 'integer',
    ];
}
