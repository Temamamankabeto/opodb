<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivestockPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'supply_data',
        'product_data',
        'sector_id',
        'kebele_id',
        'woreda_id',
        'zone_id',
        'region_id',
        'created_by',
    ];

    protected $casts = [
        'supply_data' => 'array',
        'product_data' => 'array',
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
