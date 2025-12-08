<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CulturalPlan extends Model
{
    use HasFactory;

    protected $table = 'cultural_plans';

    protected $fillable = [
        'year',
        'cultural_activities',
        'sector_id',
        'kebele_id',
        'woreda_id',
        'zone_id',
        'region_id',
        'created_by',
        'updated_by',
        'status',
    ];

    protected $casts = [
        'cultural_activities' => 'array', // JSON cast
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function sector()
    {
        return $this->belongsTo(KebeleSector::class, 'sector_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    public function woreda()
    {
        return $this->belongsTo(Woreda::class, 'woreda_id');
    }

    public function kebele()
    {
        return $this->belongsTo(Kebele::class, 'kebele_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Helpers
    |--------------------------------------------------------------------------
    |
    | cultural_activities JSON format:
    | [
    |     activity_id => [
    |         "quantity" => 10,
    |         "unit" => "people"
    |     ]
    | ]
    |
    */

    public function getActivityListAttribute()
    {
        // Returns activities including model data (unit, name, etc.)
        $result = [];

        if (!is_array($this->cultural_activities)) {
            return [];
        }

        foreach ($this->cultural_activities as $activityId => $data) {
            $activity = CulturalRevival::find($activityId);

            if ($activity) {
                $result[] = [
                    'id' => $activity->id,
                    'name' => $activity->name,
                    'unit' => $activity->unit,
                    'quantity' => $data['quantity'] ?? 0,
                ];
            }
        }

        return $result;
    }
}
