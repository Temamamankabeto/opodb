<?php

namespace App\Models;

use App\Models\Crop;
use Illuminate\Database\Eloquent\Model;

class CropType extends Model
{
    protected $fillable = [
        'name'
    ];
    public function cropConfigurations()
    {
        return $this->hasMany(CropConfiguration::class, 'croptype_id');
    }

    public function crops()
    {
        return $this->hasMany(Crop::class,'croptype_id');
    }
    public function achievements()
    {
        return $this->hasMany(CropPlanAchievement::class);
    }

}
