<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class
KarorQonnaWoggaa extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'season',
        'crop_type_id',
        'crop_id',
        'land_hectares',
        'production',
        'productivity',
        'type',
        'quantity',
        'unit',

        'kebele_id',
        'woreda_id',
        'zone_id',
        'region_id',
        'created_by',
        'phone',
        'user_id;'
    ];

    // Ensure year, season, crop_id, and kebele_id combination is unique
    protected $uniqueFields = [
        'year',
        'season',
        'crop_id',
        'kebele_id',
        'woreda_id',
        'zone_id',
        'region_id',
    ];

    public function cropType()
    {
        return $this->belongsTo(CropType::class);
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
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

    public function scopeSeasonalPlans($query, $year, $kebeleId)
    {
        return $query->where('year', $year)
            ->where('kebele_id', $kebeleId)
            ->where('season', '!=', 'yearly_total');  // Exclude yearly totals
    }

    public function scopeYearlyTotal($query, $year, $kebeleId)
    {
        return $query->where('year', $year)
            ->where('season', 'yearly_total')
            ->where('kebele_id', $kebeleId);
    }

    // In App\Models\KarorQonnaWoggaas

}
