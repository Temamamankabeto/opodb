<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MisomaBeylada extends Model
{
    protected $table = 'misoma_beyladas';

    protected $fillable = [
        'seedlingtype_id',
        'seedling_id',
        'year',
        'raaddeen',
        'lukkuu_cuucii',
        'lukkuu_sookee',
        'cuucii_qurxummii',
        'gaagura_hammayya',
        'lafa_omisha_beylada',
        'omisha_beylada',
        'kebele_id',
        'woreda_id',
        'zone_id',
        'region_id',
        'created_by',
        'phone',
    ];

    // Relationships

     

    public function kebele()
    {
        return $this->belongsTo(Kebele::class);
    }

    public function woreda()
    {
        return $this->belongsTo(Woreda::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
