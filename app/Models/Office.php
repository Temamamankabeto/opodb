<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $fillable = ['woreda_id','kebele_id',  'name' ,'level'];

    public function woreda()
    {
        return $this->belongsTo(Woreda::class, 'woreda_id');
    }
    
    public function kebele()
    {
        return $this->belongsTo(Kebele::class, 'kebele_id');
    }
}
