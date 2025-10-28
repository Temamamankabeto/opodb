<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallaGuddiftuu extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'kebele_id',
        'woreda_id',
        'zone_id',
         
        'user_id',
        'season',
        'type',
        'quantity',
        'unit',
        'created_by',
        'phone',
    ];
   
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kebele()
    {
        return $this->belongsTo(Kebele::class,'kebele_id');
    }
    public function woreda()
    {
        return $this->belongsTo(Woreda::class,'woreda_id');
    }
    public function zone()
    {
        return $this->belongsTo(Zone::class,'zone_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
