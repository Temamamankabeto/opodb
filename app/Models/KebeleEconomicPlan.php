<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KebeleEconomicPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id',
        'zone_id',
        'woreda_id',
        'kebele_id',
        'sector_id',
        'created_by',
        'approved_by',
        
        'year',
        'activity',
        'target_group',
        'timeline',
        'budget',
        'responsible_staff',
        'expected_outcome',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected $casts = [
        'approved_at' => 'datetime',
    ];
}
