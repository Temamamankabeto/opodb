<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KebeleAssetPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'kebele_asset_plan',
        'sector_id',
        'kebele_id',
        'woreda_id',
        'zone_id',
        'region_id',
        'created_by',
        'updated_by',
        'status'
    ];

    protected $casts = [
        'kebele_asset_plan' => 'array', // decode JSON to array automatically
    ];

    /* ------------------------ RELATIONSHIPS ------------------------ */

    public function sector()
    {
        return $this->belongsTo(KebeleSectors::class, 'sector_id');
    }

    public function kebele()
    {
        return $this->belongsTo(Kebele::class, 'kebele_id');
    }

    public function woreda()
    {
        return $this->belongsTo(Woreda::class, 'woreda_id');
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

     public function getKebeleAssetPlanAttribute($value)
    {
        $data = is_array($value) ? $value : json_decode($value, true);
        
        if (is_array($data)) {
            // Convert string keys to integers if possible
            $result = [];
            foreach ($data as $key => $value) {
                $intKey = (int)$key;
                $result[$intKey] = $value;
            }
            return $result;
        }
        
        return $data;
    }
}
