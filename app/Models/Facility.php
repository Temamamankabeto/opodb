<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    //
    protected $fillable = [
        'activity_name',
        'zone_id',
        'woreda_id',
        'kebele_id',
        'sector_id',
        'raawwii_maatii',
        'raawwii_hawaasaa',
        'raawwii_idaama',
        'karoora_maatii',
        'karoora_hawaasaa',
        'karoora_idaama',
        'baasii',
    ];
}
