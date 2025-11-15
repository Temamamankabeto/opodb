<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PowerInstitutionPlan extends Model
{
   use HasFactory;

    protected $fillable = [
        'year',
        'institution_id',
        'new_building_data',
        'improvement_data',
        'sector_id',
        'kebele_id',
        'woreda_id',
        'zone_id',
        'region_id',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'new_building_data' => 'array',
        'improvement_data' => 'array'
    ];

    // Relationships
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
