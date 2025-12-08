<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'health_activity_name',
        'sector_id',
        'kebele_id',
        'woreda_id',
        'zone_id',
        'region_id',
        'created_by',
        'updated_by',
        'status',
    ];

    /**
     * Cast JSON fields
     */
    protected $casts = [
        'health_activity_name' => 'array', // auto convert json <-> array
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function sector()
    {
        return $this->belongsTo(KebeleSectors::class, 'sector_id');
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
