<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barnoota extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'barnootas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'Hirmaannaa_itti_fufiinsa',
        'Bu_aa_qabeessummaa',
        'Dhiyeesii_nyaata_barattootaa',
        'sector_id',
        'kebele_id',
        'woreda_id',
        'zone_id',
        'region_id',
        'created_by',
        'updated_by',
        'status',
        'year',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'Hirmaannaa_itti_fufiinsa' => 'array',
        'Bu_aa_qabeessummaa' => 'array',
        'Dhiyeesii_nyaata_barattootaa' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => 'pending',
    ];

    /**
     * Get the sector that owns the barnoota.
     */
    public function sector()
    {
        return $this->belongsTo(KebeleSectors::class, 'sector_id');
    }

    /**
     * Get the kebele that owns the barnoota.
     */
    public function kebele() 
    {
        return $this->belongsTo(Kebele::class, 'kebele_id');
    }

    /**
     * Get the woreda that owns the barnoota.
     */
    public function woreda()
    {
        return $this->belongsTo(Woreda::class, 'woreda_id');
    }

    /**
     * Get the zone that owns the barnoota.
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    /**
     * Get the region that owns the barnoota.
     */
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    /**
     * Get the user who created the barnoota.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the barnoota.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope a query to only include pending barnootas.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved barnootas.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected barnootas.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Check if the barnoota is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the barnoota is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the barnoota is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Approve the barnoota.
     */
    public function approve(): bool
    {
        return $this->update(['status' => 'approved']);
    }

    /**
     * Reject the barnoota.
     */
    public function reject(): bool
    {
        return $this->update(['status' => 'rejected']);
    }

    /**
     * Get the Himraannaa_itti_fufiinsa data as an array.
     */
    public function getHirmaannaaAttribute(): array
    {
        return $this->Hirmaannaa_itti_fufiinsa ?? [];
    }

    /**
     * Get the Bu_aa_qabeessummaa data as an array.
     */
    public function getQabeessummaaAttribute(): array
    {
        return $this->Bu_aa_qabeessummaa ?? [];
    }

    /**
     * Get the Dhiyeesii_nyaata_barattootaa data as an array.
     */
    public function getNyaataBarattootaaAttribute(): array
    {
        return $this->Dhiyeesii_nyaata_barattootaa ?? [];
    }
}
