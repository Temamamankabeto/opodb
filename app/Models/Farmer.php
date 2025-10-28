<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_name',
        'gender',
        'phone_number',
        'region',
        'zone_id',
        'woreda_id',
        'kebele_id',
        'union_id',
        'association_id',
        'house_number',
        'field_description',
        'landholding_size',
        'gps_coordinates',
        'irrigation_type',
        'soil_type'
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
    public function woreda()
    {
        return $this->belongsTo(Woreda::class);
    }
    public function kebele()
    {
        return $this->belongsTo(Kebele::class);
    }
    public function union()
    {
        return $this->belongsTo(Union::class);
    }
    public function association()
    {
        return $this->belongsTo(Association::class);
    }
}
