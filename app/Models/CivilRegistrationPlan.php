<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CivilRegistrationPlan extends Model
{
    //
    protected $table='civil_registration_plans';
    protected $fillable = [
        'baseline_year', 'plan_year',
        'birth_cert_baseline', 'birth_cert_plan',
        'marriage_cert_baseline', 'marriage_cert_plan',
        'id_card_baseline', 'id_card_plan',
        'divorce_cert_baseline', 'divorce_cert_plan',
        'death_cert_baseline', 'death_cert_plan',
        'zone_id', 'woreda_id', 'kebele_id'
    ];
}
