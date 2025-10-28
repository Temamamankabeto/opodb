<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentEffectiveness extends Model
{
    //
    protected $table='student_effectiveness';
    protected $fillable = [
        'baseline_grade_1_5_class',
        'plan_grade_1_5_class',
        'baseline_grade_1_5_exam',
        'plan_grade_1_5_exam',
        'baseline_grade_6_sit',
        'plan_grade_6_sit',
        'baseline_grade_6_pass',
        'plan_grade_6_pass',
        'baseline_grade_7_class',
        'plan_grade_7_class',
        'baseline_grade_7_exam',
        'plan_grade_7_exam',
        'baseline_grade_8_take',
        'plan_grade_8_take',
        'baseline_grade_8_pass',
        'plan_grade_8_pass',
        'baseline_grade_9_11_class',
        'plan_grade_9_11_class',
        'baseline_grade_9_11_exam',
        'plan_grade_9_11_exam',
        'baseline_grade_12_take',
        'plan_grade_12_take',
        'baseline_grade_12_pass',
        'plan_grade_12_pass',
        'zone_id',
        'kebele_id',
        'woreda_id',
    ];

}
