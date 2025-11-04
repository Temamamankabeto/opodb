<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParcelCropPlan extends Model
{
    protected $fillable = [
        'farmer_id',
        'region_id',
        'zone_id',
        'woreda_id',
        'kebele_id',
        'assocition_id',
        'union_id',
        'federation_id',
        'office_id',
        'parcel_id',
        'year',
        'croptype_id',
        'crop_id',
        'season',
        'area',
        'productivity_per_ha',
        'expected_yield',
        'fertilizer_recommendations',
    ];

    protected $casts = [
        'fertilizer_recommendations' => 'array', // ✅ auto-JSON encode/decode
    ];

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }
    public function cropType()
    {
        return $this->belongsTo(CropType::class, 'croptype_id');
    }
    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }
}
