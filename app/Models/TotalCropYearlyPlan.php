<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TotalCropYearlyPlan extends Model
{
    protected $fillable = [
        'year',
        'crop_id',
        'crop_type_id',
        'total_land_hectares',
        'total_production',
        'average_productivity',
        'kebele_id',
        'woreda_id',
        'zone_id',
        'region_id',
    ];
    
}
