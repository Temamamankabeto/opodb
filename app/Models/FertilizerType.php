<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FertilizerType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    

    public function fertilizers()
    {
        return $this->hasMany(Fertilizer::class);
    }
}

