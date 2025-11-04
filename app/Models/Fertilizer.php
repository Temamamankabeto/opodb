<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fertilizer extends Model
{
    use HasFactory;

    protected $fillable = ['fertilizer_type_id', 'name'];

    public function fertilizerType()
    {
        return $this->belongsTo(FertilizerType::class);
    }
}
