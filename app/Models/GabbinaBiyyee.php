<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GabbinaBiyyee extends Model
{
    use HasFactory;

    protected $table = 'gabbina_biyyees';

    protected $fillable = [
        'created_by',
        'kebele_id',
        'woreda_id',
        'zone_id',
        'region_id',
        'phone',
        'year',
        'compost_type',
        'common',
        'individual',
        'total',
        'land_area',
    ];

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

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

    // Optional: If compost types are stored in a table and related
    public function compostType()
    {
        return $this->belongsTo(CompostType::class, 'compost_type', 'name');
    }

}
