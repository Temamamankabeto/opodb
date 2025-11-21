<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompostType extends Model
{
    use HasFactory;

    protected $table = 'compost_types';

    protected $fillable = [
        'name',
        'unit',
    ];
}
