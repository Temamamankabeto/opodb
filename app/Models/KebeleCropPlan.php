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
        'approved_by',
        
        'crop_id',
        'area_to_plant',
        'expected_yield',
        'season',
        'year',
        
        'input_needed',
        'responsible_staff',
        'notes',
        'productivity',
        'created_by',
    ];

     
    public function sector()
    {
        return $this->belongsTo(KebeleSectors::class, 'sector_id');
    }

    public function kebele()
    {
        return $this->belongsTo(Kebele::class);
    }

    public function cropType()
    {
        return $this->belongsTo(CropType::class, 'croptype_id');
    }

    // Relationship to Crop
    public function crop()
    {
        return $this->belongsTo(Crop::class, 'crop_id');
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    protected $casts = [
        'approved_at' => 'datetime',
    ];
}
