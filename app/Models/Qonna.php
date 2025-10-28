<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Qonna extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'qonnas';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'haala_qonnaa',
        'lafa_qotame',
        'sadarkaa_qonnaa',
        'traaktara_hirmaate',
        'lafa_tiraakta_tokkoon_qotame',
        'rakkoo_muudate',
        'furmaata',
        'carraa_argame',
        'deeggarsa',
        'rakkoo_deddeebie',
        'baayina_dhiiraa',
        'baayina_durbaa',
        'baayina_woliigalaa',
        'dhabbii_dhiiraa',
        'dhabbii_durbaa',
        'dhabbii_woliigalaa',
        'yeroo_dhiiraa',
        'yeroo_durbaa',
        'yeroo_woliigalaa',
        'dargaggoota_dhiiraa',
        'dargaggoota_durbaa',
        'dargaggoota_woliigalaa',
    ];

}
