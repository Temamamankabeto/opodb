<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CooperativeActivity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'baseline_year',
        'plan_year',
        'cooperatives_count_baseline',
        'cooperatives_count_plan',
        'members_baseline',
        'members_plan',
        'capitals_baseline',
        'capitals_plan',
        'credit_taken_baseline',
        'credit_taken_plan',
        'credit_repaid_baseline',
        'credit_repaid_plan',
        'extension_services_baseline',
        'extension_services_plan',
        'union_membership_baseline',
        'union_membership_plan',
        'export_market_baseline',
        'export_market_plan'
    ];

    protected $casts = [
        'capitals_baseline' => 'decimal:2',
        'capitals_plan' => 'decimal:2',
        'credit_taken_baseline' => 'decimal:2',
        'credit_taken_plan' => 'decimal:2',
        'credit_repaid_baseline' => 'decimal:2',
        'credit_repaid_plan' => 'decimal:2',
    ];

    /**
     * Scope a query to filter by baseline year.
     */
    public function scopeByBaselineYear($query, $year)
    {
        return $query->where('baseline_year', $year);
    }

    /**
     * Scope a query to filter by plan year.
     */
    public function scopeByPlanYear($query, $year)
    {
        return $query->where('plan_year', $year);
    }

    /**
     * Calculate the total capital difference between plan and baseline
     */
    public function getCapitalDifferenceAttribute()
    {
        return $this->capitals_plan - $this->capitals_baseline;
    }

    /**
     * Calculate the repayment rate for baseline
     */
    public function getBaselineRepaymentRateAttribute()
    {
        if ($this->credit_taken_baseline > 0) {
            return ($this->credit_repaid_baseline / $this->credit_taken_baseline) * 100;
        }
        return 0;
    }

    /**
     * Calculate the projected repayment rate for plan
     */
    public function getPlanRepaymentRateAttribute()
    {
        if ($this->credit_taken_plan > 0) {
            return ($this->credit_repaid_plan / $this->credit_taken_plan) * 100;
        }
        return 0;
    }
}
