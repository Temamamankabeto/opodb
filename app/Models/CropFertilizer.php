<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropFertilizer extends Model
{
    use HasFactory;

    protected $fillable = ['crop_id', 'type', 'quantity_per_ha'];

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }
}
