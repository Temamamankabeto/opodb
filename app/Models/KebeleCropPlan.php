<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KebeleCropPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kebele_id',
        'sector_id',
        'croptype_id',
        'crop_id',
        'area_to_plant',
        'expected_yield',
        'season',
        'year',
        'seed_required',
        'fertilizer_type_id',
        'fertilizer_details',
        'plandivision_details',        
        'responsible_staff',
        'notes',
        'productivity',
        'created_by',
        'approved_by',
    ];

    protected $casts = [
        'fertilizer_details' => 'array',  
        'fertilizer_type_id' => 'array',
        'plandivision_details' => 'array',
        
        'productivity' => 'float',
        'area_to_plant' => 'float',
        'expected_yield' => 'float',
        'seed_required' => 'float',
        'approved_at' => 'datetime',
    ];

    // 🔹 Relationships
    public function kebele()
    {
        return $this->belongsTo(Kebele::class);
    }
    public function sector()
    {
        return $this->belongsTo(KebeleSectors::class);
    }

    public function cropType()
    {
        return $this->belongsTo(CropType::class, 'croptype_id');
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class, 'crop_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
