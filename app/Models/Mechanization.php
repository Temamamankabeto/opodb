<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class mechanization extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'mechanization_type',
        'amount',
        'unit',
        'year',
        'loan_details',
        'mechanization_distribution_details',
        
         
        'sector_id',
        'kebele_id',
        'woreda_id',
        'zone_id',
        'region_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'year' => 'integer',
        'amount' => 'decimal:2',
         'loan_details' => 'array',
         'mechanization_distribution_details' => 'array',
         
    ];

    /**
     * Get the sector that owns the mechanization.
     */
    public function sector(): BelongsTo
    {
        return $this->belongsTo(KebeleSectors::class);
    }

    /**
     * Get the kebele that owns the mechanization.
     */
    public function kebele(): BelongsTo
    {
        return $this->belongsTo(Kebele::class);
    }

    /**
     * Get the woreda that owns the mechanization.
     */
    public function woreda(): BelongsTo
    {
        return $this->belongsTo(Woreda::class);
    }

    /**
     * Get the zone that owns the mechanization.
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    /**
     * Get the region that owns the mechanization.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}