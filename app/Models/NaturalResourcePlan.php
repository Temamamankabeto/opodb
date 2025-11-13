<?php

namespace App\Models;

use App\Models\Zone;
use App\Models\Kebele;
use App\Models\Region;
use App\Models\Woreda;
use App\Models\KebeleSectors;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NaturalResourcePlan extends Model
{
     use HasFactory;

    protected $fillable = [
        'year',
        'natural_data',
        'sector_id',
        'kebele_id',
        'woreda_id',
        'zone_id',
        'region_id',
        'created_by',
    ];

    protected $casts = [
        'natural_data' => 'array',
    ];

    public function sector()
    {
        return $this->belongsTo(KebeleSectors::class);
    }

    public function kebele()
    {
        return $this->belongsTo(Kebele::class);
    }

    public function woreda()
    {
        return $this->belongsTo(Woreda::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
