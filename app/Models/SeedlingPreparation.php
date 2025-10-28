<?php

namespace App\Models;

use App\Models\Seedling;
use App\Models\SeedlingType;
use Illuminate\Database\Eloquent\Model;

class SeedlingPreparation extends Model
{

    protected $table = 'seedling_preparations';
    protected $fillable = [
        'year',
        'seedlingtype_id',
        
        'seedling_id',   
        'nama_hirmaate',
        'baayina_guyyaa',
        'hanga',
        'kebele_id',
        'woreda_id',
        'zone_id',
        'region_id',
        'created_by',
        'phone',
    ];

    // Relationships
    public function seedlingType()
    {
        return $this->belongsTo(SeedlingType::class, 'seedlingtype_id');
    }
    
    public function seedling()
    {
        return $this->belongsTo(Seedling::class, 'seedling_id');
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
