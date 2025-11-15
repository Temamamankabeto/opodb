<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmploymentOpportunityPlan extends Model
{
     use HasFactory;

    protected $fillable = [
        'year',
        'job_opportunity_id',
        'enterprise_work_id',
        'opportunity_data',
        'sector_id',
        'kebele_id',
        'woreda_id',
        'zone_id',
        'region_id',
        'created_by',
        'updated_by',
        'status',
        'approval_description',
        'approved_at',
        'approved_by'

    ];

    protected $casts = [
        'opportunity_data' => 'array',
        'approved_at' => 'datetime', // Add this line
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function jobOpportunity()
    {
        return $this->belongsTo(JobOpportunity::class, 'job_opportunity_id');
    }
    
public function approvedBy()
{
    return $this->belongsTo(User::class, 'approved_by');
}
    public function enterpriseWork()
    {
        return $this->belongsTo(EnterpriseWork::class, 'enterprise_work_id');
    }

    public function sector()
    {
        return $this->belongsTo(KebeleSectors::class, 'sector_id');
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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
