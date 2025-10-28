<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivestockSupply extends Model
{
    use HasFactory;

    protected $table = 'livestock_supplies';

    protected $fillable = [
        'baseline_year',
        'plan_year',
        'ai_cows_baseline',
        'ai_cows_plan',
        'one_day_chick_baseline',
        'one_day_chick_plan',
        'twentyone_days_chicks_baseline',
        'twentyone_days_chicks_plan',
        'fry_fish_baseline',
        'fry_fish_plan',
        'beehives_baseline',
        'beehives_plan',
        'animal_feed_land_baseline',
        'animal_feed_land_plan',
        'oomsha_nyaata_baseline',
        'oomsha_nyaata_plan',
        'productivity_baseline',
        'productivity_plan',
        'zone_id',
        'woreda_id',
        'kebele_id',
        'created_by',
    ];
    public function kebele()
    {
        return $this->belongsTo(Kebele::class);
    }

    public function livestockType()
    {
        return $this->belongsTo(LivestockType::class);
    }
}
