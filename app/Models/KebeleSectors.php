<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KebeleSectors extends Model
{
    //
    protected $fillable = [  
        'name',
        'level'
    ];

    
    // public function users() {
    //     return $this->hasMany(User::class);
    // }

    public function users()
    {
        return $this->hasMany(User::class, 'sector_id');
    }
}
