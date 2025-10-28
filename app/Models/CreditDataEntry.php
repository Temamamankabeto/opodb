<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditDataEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'baseline_year',
        'plan_year',
        'total_debt_baseline_private',
        'total_debt_baseline_association',
        'total_debt_baseline_total',
        'total_debt_plan_private',
        'total_debt_plan_association',
        'total_debt_plan_total',
        'loans_repaid_baseline_private',
        'loans_repaid_baseline_association',
        'loans_repaid_baseline_total',
        'loans_repaid_plan_private',
        'loans_repaid_plan_association',
        'loans_repaid_plan_total',
        'remaining_debt_baseline_private',
        'remaining_debt_baseline_association',
        'remaining_debt_plan_private',
        'remaining_debt_plan_association',
        'savings_baseline_association',
        'savings_baseline_private',
        'savings_baseline_total',
        'savings_plan_association',
        'savings_plan_private',
        'savings_plan_total',
    ];

    protected $casts = [
        'baseline_year' => 'integer',
        'plan_year' => 'integer',
    ];
}
