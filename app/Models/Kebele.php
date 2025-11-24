<?php

namespace App\Models;

use App\Models\Woreda;
use Illuminate\Database\Eloquent\Model;

class Kebele extends Model
{
    protected $fillable = [
        'woreda_id',
        'name',
        'total_area',
        'total_farm_area',
        'total_population',
        'male',
        'female',
    ];

    public function woreda()
    {
        return $this->belongsTo(Woreda::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function sectors()
    {
        return $this->hasMany(KebeleSectors::class);
    }


     public function livestockPlans()
    {
        return $this->hasMany(LivestockPlan::class, 'kebele_id');
    }

    // Other relationships you might have...
     
}
