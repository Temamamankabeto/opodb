<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConstructionOfInstitution extends Model
{
    //w
    protected $fillable = [
        'zone_id', 'woreda_id', 'kebele_id', 'sector_id',
        'service_name', 'type', 'year', 'count', 'cost'
    ];
}
