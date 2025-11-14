<?php

namespace App\Models;

use App\Models\Zone;
use App\Models\Kebele;
use App\Models\Region;
use App\Models\Woreda;
use App\Models\KebeleSectors;
use App\Models\Association;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssociationExpansionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'association_id',
        'expansion_data',
        'sector_id',
        'kebele_id',
        'woreda_id',
        'zone_id',
        'region_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'expansion_data' => 'array',
    ];

    public function association()
    {
        return $this->belongsTo(AssociationType::class);
    }

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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}