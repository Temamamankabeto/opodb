<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Facaasaa extends Model
{
    use HasFactory;

    protected $table = 'facaasaas';

    protected $fillable = [
        'lafa_facae',
        'mala_facaasaa',
        'qonnaan_bulaa_dhi',
        'qonnaan_bulaa_du',
        'qonnaan_bulaa_idaama',
    ];
}
