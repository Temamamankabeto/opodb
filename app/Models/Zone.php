<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $fillable =
    [
        'region_id',
        'name',
    ];
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function woredas()
    {
        return $this->hasMany(Woreda::class);
    }
    public function users() {
        return $this->hasMany(User::class);
    }

    public function sectors()
    {
        return $this->hasMany(KebeleSectors::class);
    }
}
