<?php

namespace App\Models;

use App\Models\EnterpriseWork;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmploymentOpportunityPlan;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobOpportunity extends Model
{
    use HasFactory;

    protected $fillable = ['name'];


   public function employmentPlans()
    {
        return $this->hasMany(EmploymentOpportunityPlan::class, 'job_opportunity_id');
    }
}
