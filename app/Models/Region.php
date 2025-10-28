<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{

    public function sectors()
    {
        return $this->hasMany(KebeleSectors::class);
    } //
}


