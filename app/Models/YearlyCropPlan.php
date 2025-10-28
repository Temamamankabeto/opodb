<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class YearlyCropPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_year',
        'baseline_year',
        'kebele_id',

        'croptype_id',
        'crop_id',

        // Plan fields
        'land_area_plan_ganna',
        'land_area_plan_bona',
        'land_area_plan_arfaasaa',
        'land_area_plan_idaama',

        'production_plan_ganna',
        'production_plan_bona',
        'production_plan_arfaasaa',
        'production_plan_idaama',

        'productivity_plan_ganna',
        'productivity_plan_bona',
        'productivity_plan_arfaasaa',
        'productivity_plan_idaama',

        // Baseline (achievement) fields
        'land_area_baseline_ganna',
        'land_area_baseline_bona',
        'land_area_baseline_arfaasaa',
        'land_area_baseline_idaama',

        'production_baseline_ganna',
        'production_baseline_bona',
        'production_baseline_arfaasaa',
        'production_baseline_idaama',

        'productivity_baseline_gana',
        'productivity_baseline_bona',
        'productivity_baseline_arfasa',
        'productivity_baseline_idaama',

        'performancy',
        'created_by',
    ];

    // Define relationships
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
