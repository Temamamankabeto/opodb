<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeedlingPreparation extends Model
{
    protected $table = 'seedling_preparations';
    
    protected $fillable = [
        'year',
        'preparation_data',
        'planting_data',
        'plantingdata_detail',        
        'baayyina_nersery',
        'ballina_nersery',
        'nerserif_nama_hirmaate',
        'nerserif_baayina_guyyaa',
        'sector_id',
        'kebele_id',
        'woreda_id',
        'zone_id',
        'region_id',
        'created_by',
        'phone',
    ];

    protected $casts = [
        'preparation_data' => 'array',
        'planting_data' => 'array',
        'plantingdata_detail' => 'array',
        
        'baayyina_nersery' => 'decimal:2',
        'ballina_nersery' => 'decimal:2',
        'nerserif_nama_hirmaate' => 'decimal:2',
        'nerserif_baayina_guyyaa' => 'decimal:2',
    ];

    // Relationships
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

    // Accessors for preparation data
    public function getPreparationSeedlingTypeAttribute()
    {
        return $this->preparation_data['seedling_type']['name'] ?? null;
    }

    public function getPreparationSeedlingsAttribute()
    {
        return $this->preparation_data['selected_seedlings'] ?? [];
    }

    // Accessors for planting data
    public function getPlantingSeedlingTypeAttribute()
    {
        return $this->planting_data['seedling_type']['name'] ?? null;
    }

    public function getPlantingSeedlingsAttribute()
    {
        return $this->planting_data['selected_seedlings'] ?? [];
    }
}