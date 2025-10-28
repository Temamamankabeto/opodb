<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcononomicTransition extends Model
{
    //
    protected $table='economic_transitions';
    protected $fillable = [
        'plan_year',
        'amount',
        'activity_name',
        'family',
        'cooperative',
        'community',
        'total',
        'construction_cost',
        'zone_id',
        'woreda_id',
        'kebele_id',
        'sector_id',
    ];
}
