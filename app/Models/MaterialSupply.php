<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialSupply extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'year',
        'material_name',
        'private_qty',
        'group_qty',
        'total_qty',
        'kebele_id',
        'woreda_id',
        'zone_id',
        'region_id',
        'created_by',
        'phone'
    ];

    protected $casts = [
        'private_qty' => 'decimal:2',
        'group_qty' => 'decimal:2',
        'total_qty' => 'decimal:2',
    ];

    /**
     * Get the user who created this record.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the kebele associated with this record.
     */
    public function kebele()
    {
        return $this->belongsTo(Kebele::class);
    }

    /**
     * Get the woreda associated with this record.
     */
    public function woreda()
    {
        return $this->belongsTo(Woreda::class);
    }

    /**
     * Get the zone associated with this record.
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    /**
     * Get the region associated with this record.
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
