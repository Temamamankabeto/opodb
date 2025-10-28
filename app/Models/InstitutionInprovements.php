<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutionInprovements extends Model
{
    //

    protected $fillable = [
        'zone_id',
        'woreda_id',
        'kebele_id',
        'sector_id',
        'main_category',
        'sub_category',
        'service_name',
        'year',
        'type',        // 'actual' or 'plan'
        'quantity',
        'cost',
    ];

    // Optional: Define relationships if you want
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

    public function sector()
    {
        return $this->belongsTo(KebeleSectors::class);
    }
}
