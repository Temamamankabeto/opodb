<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaballinaWaldaa extends Model
{
    protected $fillable = [
        'year',
        'association_type',
        'association_number',
        'association_member',
        'capital',
        'tooked_borrow',
        'repair_borrow',
        'region',
        'zone_id',
        'woreda_id',
        'kebele_id',
        'created_by',
    ];

    // Relationships
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function woreda()
    {
        return $this->belongsTo(Woreda::class);
    }

    public function kebele()
    {
        return $this->belongsTo(Kebele::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
