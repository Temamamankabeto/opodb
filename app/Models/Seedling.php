<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seedling extends Model
{
    protected $table = 'seedlings';
    protected $fillable = ['name', 'seedlingtype_id'];

    public function seedlingType()
    {
        return $this->belongsTo(SeedlingType::class, 'seedlingtype_id');
    }
}
