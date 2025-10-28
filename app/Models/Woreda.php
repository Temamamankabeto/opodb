<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Woreda extends Model
{

    protected $fillable = ['zone_id', 'name'];

    public function kebeles()
    {
        return $this->hasMany(Kebele::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
    public function users() {
        return $this->hasMany(User::class);
    }

    public function sectors()
    {
        return $this->hasMany(KebeleSectors::class);
    }
}
