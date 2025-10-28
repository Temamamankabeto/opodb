<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Crop extends Model
{
    protected $fillable = ['croptype_id', 'name', 'seeds_per_ha'];

    public function cropType()
    {
        return $this->belongsTo(CropType::class, 'croptype_id');
    }

    // In App\Models\Crop
public function karorQonnaWoggaas()
{
    return $this->hasMany(KarorQonnaWoggaa::class);
}
    public function fertilizers()
    {
        return $this->hasMany(CropFertilizer::class);
    }
}
