<?php

namespace App\Models;

use App\Models\Institution;
use Illuminate\Database\Eloquent\Model;

class InstitutionType extends Model
{
    protected $fillable = ['name'];


    
    public function institutions()
    {
        return $this->hasMany(Institution::class);
    }
}


