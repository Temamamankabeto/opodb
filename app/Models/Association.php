<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Association extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'association_type',
        'service_data',
        'federation_id',
        'union_id',
        'zone_id',
        'woreda_id',
        'kebele_id'
    ];
        
    protected $casts = [
     
         'service_data' => 'array',
    ];

    public function federation()
    {
        return $this->belongsTo(Federation::class);
    }
    public function union()
    {
        return $this->belongsTo(Union::class);
    }
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
}
