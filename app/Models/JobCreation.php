<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCreation extends Model
{
   protected $fillable = [
        'year',
        'enterprise_type',
        'job_type_id',
        'region',
        'zone_id',
        'woreda_id',
        'kebele_id',
        'created_by', 
        'number',
        'male',
        'female',
        'sum',
    ];

    public function jobType()
    {
        return $this->belongsTo(JobType::class);
    }
     
     public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
     public function woreda()
    {
        return $this->belongsTo(Woreda::class);
    }
     public function kebele()
    {
        return $this->belongsTo(Kebele::class);
    }

}
