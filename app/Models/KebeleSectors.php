<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KebeleSectors extends Model
{
    //
    protected $fillable = ['kebele_id', 'woreda_id', 'zone_id', 'region_id', 'name'];

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
    // public function users() {
    //     return $this->hasMany(User::class);
    // }

    public function users()
    {
        return $this->hasMany(User::class, 'sector_id');
    }
}
