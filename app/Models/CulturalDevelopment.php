<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CulturalDevelopment extends Model
{
    //
    protected $table='cultural_plans';
    protected $fillable = [
        'baseline_year',
        'plan_year',
        'awareness_renewal_baseline',
        'awareness_renewal_plan',
        'youth_participation_baseline',
        'youth_participation_plan',
        'zone_id',
        'woreda_id',
        'kebele_id',
    ];
}
