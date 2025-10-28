<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EggumsaNaannoo extends Model
{
   use HasFactory;

    protected $table = 'eggumsa_naannoos';

    protected $fillable = [
        'year',
        'limited_forest_land',
        'land_free_from_contact',
        'associations_organized',
        'number_of_members',
        'dry_waste_sites',
        'private_bathroom',
        'community_toilets',
        'region',
        'zone_id',
        'woreda_id',
        'kebele_id',
        'created_by',
    ];

    protected $appends = ['sum_bathroom'];

    // Accessor for sum_bathroom if not using a virtual column in DB
    public function getSumBathroomAttribute()
    {
        return $this->private_bathroom + $this->community_toilets;
    }

    // Relationships
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
