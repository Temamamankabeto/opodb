<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Kunuunsa extends Model
{
    use HasFactory;

    protected $table = 'kunuunsas';

    protected $fillable = [
        'lafa_harkaan_aramame',
        'lafa_keemikaalaan_aramame',
        'hamma_keemikaalaa_litiraa',
        'lafa_dhibeen_tooatame',
        'lafa_ilbiisni_toatame',
    ];
}
