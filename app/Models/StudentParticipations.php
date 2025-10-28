<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentParticipations extends Model
{
    //
    protected $table = 'student_participation_stats';
    protected $fillable = [
        'zone_id',
        'woreda_id',
        'kebele_id',
        'baseline_year',
        'plan_year',
        'registration_number_baseline',
        'registration_number_plan',
        'grade_1_6_participation_baseline',
        'grade_1_6_participation_plan',
        'grade_7_8_participation_baseline',
        'grade_7_8_participation_plan',
        'grade_9_12_participation_baseline',
        'grade_9_12_participation_plan',
        'adult_education_baseline',
        'adult_education_plan',
        'graduation_number_baseline',
        'graduation_number_plan',
        'grade_1_6_graduated_baseline',
        'grade_1_6_graduated_plan',
        'grade_7_8_graduated_baseline',
        'grade_7_8_graduated_plan',
        'grade_9_12_graduated_baseline',
        'grade_9_12_graduated_plan',
        'primary_school_cost_baseline',
        'primary_school_cost_plan'
    ];
}
