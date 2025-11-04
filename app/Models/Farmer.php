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
        'land_details',
        'total_land',
        'land_type_details',
        'created_by',
    ];

    protected $casts = [
        'land_details' => 'array',
        'total_land' => 'array',
        'land_type_details' => 'array',
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
