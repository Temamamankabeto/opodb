<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CropConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'croptype_id',
        'crop_id',
        'kebele_id',
        'soil_type_id',
        'productivity_per_ha',
        'seed_rate_kg_per_ha',
        'fertilizer_requirements',
        'notes',
        'year'
    ];

    protected $casts = [
        'fertilizer_requirements' => 'array',
    ];

    // Relationships
    public function croptype()
    {
        return $this->belongsTo(CropType::class);
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }

    public function kebele()
    {
        return $this->belongsTo(Kebele::class);
    }

    public function soilType()
    {
        return $this->belongsTo(SoilType::class);
    }
}
