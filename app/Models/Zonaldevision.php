<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Zonaldevision extends Model
{
    use HasFactory;

    protected $table = 'zonaldevisions';

    protected $fillable = [
        'zone_id',
        'crop_id',
        'total_land',
        'totla_production',
        'ammount_of_land_for_zone',
        'ammount_of_prucuction_for',
        'productivity',
        'production_year',
    ];

    // Relationship with Zone
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }
}
