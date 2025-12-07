<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentMealSupply extends Model
{
     protected $fillable = [
          'ammount',
          'unit',
          'source',
          'cost',
          'cost_unit'

     ];

      protected $attributes = [
        'unit' => 'Kunt',
        'cost_unit' => 'Birr',
    ];
}
