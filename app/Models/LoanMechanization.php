<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanMechanization extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'zone_id',
        'woreda_id',
        'kebele_id',
        'year',
        'loan_type',
        'private_quantity',
        'group_quantity',
        'total_quantity',
        'unit',
        'created_by',
        'phone'
    ];

    protected $casts = [
        'year' => 'integer',
        'private_quantity' => 'decimal:2',
        'group_quantity' => 'decimal:2',
        'total_quantity' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Auto-calculate total before saving
            $model->total_quantity = $model->private_quantity + $model->group_quantity;
            
            // Set default unit if empty
            if (empty($model->unit)) {
                $model->unit = 'ETB';
            }
        });
    }

    // Relationships
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function woreda()
    {
        return $this->belongsTo(Woreda::class);
    }

    public function kebele()
    {
        return $this->belongsTo(Kebele::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeForYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeForKebele($query, $kebeleId)
    {
        return $query->where('kebele_id', $kebeleId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('loan_type', $type);
    }
}
