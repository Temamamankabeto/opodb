<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NurserySite extends Model
{
    protected $fillable = [
        'year',
        'baayyina',
        'ballina',
        'nama_hirmaate',
        'baayina_guyyaa',
        'kebele_id',
        'woreda_id',
        'zone_id',
        'region_id',
        'created_by',
        'phone'
    ];

    // Relationships
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
}
