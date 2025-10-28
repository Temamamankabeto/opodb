<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class AgriculturalInput extends Model
{
    use HasFactory;

    protected $table = 'agricultural_inputs';

    protected $fillable = [
        'baseline_year',
        'plan_year',
        
        // Chemical fertilizers [Tons]
        'chemical_fertilizer_ganna_baseline',
        'chemical_fertilizer_ganna_plan',
        'chemical_fertilizer_bona_baseline',
        'chemical_fertilizer_bona_plan',
        'chemical_fertilizer_arfaasaa_baseline',
        'chemical_fertilizer_arfaasaa_plan',
        'chemical_fertilizer_idaama_baseline',
        'chemical_fertilizer_idaama_plan',
        
        // Selective Seed [Tons]
        'selective_seed_ganna_baseline',
        'selective_seed_ganna_plan',
        'selective_seed_bona_baseline',
        'selective_seed_bona_plan',
        'selective_seed_arfaasaa_baseline',
        'selective_seed_arfaasaa_plan',
        'selective_seed_idaama_baseline',
        'selective_seed_idaama_plan',
        
        // Chemical pesticide and insecticide sprayers [Litre]
        'pesticide_ganna_baseline',
        'pesticide_ganna_plan',
        'pesticide_bona_baseline',
        'pesticide_bona_plan',
        'pesticide_arfaasaa_baseline',
        'pesticide_arfaasaa_plan',
        'pesticide_idaama_baseline',
        'pesticide_idaama_plan',
        
        // Spraying of pesticides and insecticides [Hectares of land]
        'spraying_ganna_baseline',
        'spraying_ganna_plan',
        'spraying_bona_baseline',
        'spraying_bona_plan',
        'spraying_arfaasaa_baseline',
        'spraying_arfaasaa_plan',
        'spraying_idaama_baseline',
        'spraying_idaama_plan'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'baseline_year' => 'integer',
        'plan_year' => 'integer',
    ];
}
