<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InputPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'sector_id',
        'kebele_crop_plan_id',
        'croptype_id',
        'crop_id',
        'kebele_id',
        'woreda_id',
        'zone_id',
        'region_id',
        'fertilizer_type_id',
        'year',
        'season',
        'seed_required',
        'fertilizer_details',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'fertilizer_details' => 'array',
    ];

    // ✅ Relationships
    public function cropType()
    {
        return $this->belongsTo(CropType::class, 'croptype_id');
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class, 'crop_id');
    }

    public function sector()
    {
        return $this->belongsTo(KebeleSectors::class, 'sector_id');
    }

    public function kebeleCropPlan()
    {
        return $this->belongsTo(KebeleCropPlan::class, 'kebele_crop_plan_id');
    }

    // ✅ Optional helper: get kebele_id directly (for convenience)
    public function getKebeleIdAttribute()
    {
        return $this->kebeleCropPlan->kebele_id ?? null;
    }
}
