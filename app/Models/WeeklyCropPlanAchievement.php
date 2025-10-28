<?php

namespace App\Models;

use App\Models\Kebele;
use App\Models\YearlyCropPlan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeeklyCropPlanAchievement extends Model
{ use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'kebele_id',
        'yearly_crop_plan_id',
        'measurement',
        'crop_type',
        'crop_name',
        'baseline_year',
        'plan_year',
        'yearly_plan',
        'weekly_plan',
        'weekly_achievement',
        'weekly_achievement_percentage',
        'upto_this_week_plan',
        'upto_this_week_achievement',
        'upto_this_week_percentage_achievement',
        'yearly_percentage_achievement',
        // Include all your original fields here
        'solar_center_baseline',
        'solar_center_plan',
        // ... etc ...
    ];

    protected $casts = [
        'baseline_year' => 'integer',
        'plan_year' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kebele()
    {
        return $this->belongsTo(Kebele::class);
    }

    public function yearlyCropPlan()
    {
        return $this->belongsTo(YearlyCropPlan::class);
    } //`

    public function cropType()
    {
        return $this->belongsTo(CropType::class, 'croptype_id');
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class, 'crop_id');
    }
}
