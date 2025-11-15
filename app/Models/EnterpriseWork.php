<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EnterpriseWork extends Model
{
   use HasFactory;

    protected $fillable = ['name'];

      public function employmentPlans()
    {
        return $this->hasMany(EmploymentOpportunityPlan::class, 'enterprise_work_id');
    }
}
