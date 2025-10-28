<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForestManagement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'forest_management';

    protected $fillable = [
        'baseline_year',
        'plan_year',
        'forest_area_baseline',
        'forest_area_plan',
        'grazing_free_area_baseline',
        'grazing_free_area_plan',
        'cooperatives_baseline',
        'cooperatives_plan',
        'cooperative_members_baseline',
        'cooperative_members_plan',
        'dry_waste_areas_baseline',
        'dry_waste_areas_plan',
        'latrine_private_baseline',
        'latrine_private_plan',
        'latrine_community_baseline',
        'latrine_community_plan',
        'latrine_total_baseline',
        'latrine_total_plan'
    ];

    protected $casts = [
        'baseline_year' => 'integer',
        'plan_year' => 'integer',
        'forest_area_baseline' => 'decimal:2',
        'forest_area_plan' => 'decimal:2',
        'grazing_free_area_baseline' => 'decimal:2',
        'grazing_free_area_plan' => 'decimal:2',
        'cooperatives_baseline' => 'integer',
        'cooperatives_plan' => 'integer',
        'cooperative_members_baseline' => 'integer',
        'cooperative_members_plan' => 'integer',
        'dry_waste_areas_baseline' => 'integer',
        'dry_waste_areas_plan' => 'integer',
        'latrine_private_baseline' => 'integer',
        'latrine_private_plan' => 'integer',
        'latrine_community_baseline' => 'integer',
        'latrine_community_plan' => 'integer',
        'latrine_total_baseline' => 'integer',
        'latrine_total_plan' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Calculate latrine totals automatically before saving
            $model->latrine_total_baseline = ($model->latrine_private_baseline ?? 0) + ($model->latrine_community_baseline ?? 0);
            $model->latrine_total_plan = ($model->latrine_private_plan ?? 0) + ($model->latrine_community_plan ?? 0);
        });
    }

    public function scopeForYears($query, $baselineYear, $planYear)
    {
        return $query->where('baseline_year', $baselineYear)
                    ->where('plan_year', $planYear);
    }
}
