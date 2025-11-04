<?php

namespace App\Models;

use App\Models\Farmer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SoilType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    
    public function farmers()
    {
        return $this->belongsToMany(Farmer::class);
    }
}
