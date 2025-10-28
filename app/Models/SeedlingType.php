<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeedlingType extends Model
{
    protected $table = 'seedling_types';
    protected $fillable = ['name'];
}
