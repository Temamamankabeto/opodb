<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyLandPlan extends Model
{
    protected $fillable = [
        'croptype_id',
        'kebele_id',
        
        'crop_id',
        'plan_year',
        'month',
        'week',
        'planned_value',
        'achieved_value',
        'land_area_plan_idaama',
        'weekly_achievement_percentage',

        'upto_this_week_plan' ,
        'upto_this_week_achievement' ,
        'upto_this_week_achieved_percentage' ,
        'yearly_achieved_percentage' ,
        
        
    ];

    public function cropType()
    {
        return $this->belongsTo(CropType::class, 'croptype_id');
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class, 'crop_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kebele()
    {
        return $this->belongsTo(Kebele::class,'kebele_id');
    }
}
