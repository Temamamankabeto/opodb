<?php

namespace App\Models;

use App\Models\Zone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Union extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'federation_id', 'zone_id'];

    public function federation()
    {
        return $this->belongsTo(Federation::class);
    }
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
