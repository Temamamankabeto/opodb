<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WatershedResource extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'watershed_resources';

    protected $fillable = [
        'baseline_year',
        'plan_year',
        'watershed_length_baseline',
        'watershed_length_plan',
        'watershed_area_baseline',
        'watershed_area_plan',
        'hr_male_baseline',
        'hr_male_plan',
        'hr_female_baseline',
        'hr_female_plan',
        'hr_total_baseline',
        'hr_total_plan',
        'hr_days_baseline',
        'hr_days_plan'
    ];

    protected $casts = [
        'baseline_year' => 'integer',
        'plan_year' => 'integer',
        'watershed_length_baseline' => 'decimal:2',
        'watershed_length_plan' => 'decimal:2',
        'watershed_area_baseline' => 'decimal:2',
        'watershed_area_plan' => 'decimal:2',
        'hr_male_baseline' => 'integer',
        'hr_male_plan' => 'integer',
        'hr_female_baseline' => 'integer',
        'hr_female_plan' => 'integer',
        'hr_total_baseline' => 'integer',
        'hr_total_plan' => 'integer',
        'hr_days_baseline' => 'integer',
        'hr_days_plan' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Calculate totals automatically before saving
            $model->hr_total_baseline = ($model->hr_male_baseline ?? 0) + ($model->hr_female_baseline ?? 0);
            $model->hr_total_plan = ($model->hr_male_plan ?? 0) + ($model->hr_female_plan ?? 0);
        });
    }

    public function scopeForYears($query, $baselineYear, $planYear)
    {
        return $query->where('baseline_year', $baselineYear)
                    ->where('plan_year', $planYear);
    }
}
