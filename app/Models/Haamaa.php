<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Haamaa extends Model
{
    use HasFactory;

    protected $table = 'haamaas';

    protected $fillable = [
        'lafa_maashinaan_haamame',
        'maashina_haamaa_hirmaate',
        'lafa_harkaan_haamame',
        'qotee_bulaa_dhi_haamame',
        'qotee_bulaa_du_haamame',
        'kan_tumame',
    ];
}
