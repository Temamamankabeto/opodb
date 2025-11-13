<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'zone_id',
        'woreda_id',
        'kebele_id',
        'role_id',
        'password',
        'employee_id',
        'sector_id',
        'created_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

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

    // public function sector()
    // {
    //     return $this->belongsTo(KebeleSectors::class);
    // }
    public function sector()
    {
        return $this->belongsTo(KebeleSectors::class, 'sector_id');
    }

   

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function isOwner()
{
    return $this->created_by === auth()->id();
}


protected static function booted()
    {
        static::creating(function ($user) {
            // Only assign UUID if not already set
            if (empty($user->uuid)) {
                $user->uuid = (string) Str::uuid();
            }
        });
    static::updating(function ($user) {
        // Prevent overwriting UUID if it already exists
        if (empty($user->uuid)) {
            $user->uuid = (string) Str::uuid();
        }
    });
    }

    public function scopeAccessibleBy($query, $user)
{
    if ($user->role_id === 1) {
        return $query; // all data
    } elseif ($user->role_id === 2) {
        return $query->where('zone_id', $user->zone_id);
    } elseif ($user->role_id === 3) {
        return $query->where('woreda_id', $user->woreda_id);
    } else {
        return $query->where('id', $user->id); // normal user only sees own data
    }
}


public function canManage()
{
    $currentUser = auth()->user();
    return $currentUser->role_id == 1 || ($currentUser->role_id == 2 && $this->isOwner());
}
public function canEditUser(User $userToEdit): bool
{
    $roleName = $this->role?->name; // make sure role is loaded

    // Admin can edit anyone
    if ($roleName === 'super_admin') {
        return true;
    }

    // Zone admin can edit users in the same zone only
    if ($roleName === 'zone_admin') {
        return $this->zone_id && $this->zone_id === $userToEdit->zone_id;
    }

    // Regular user can only edit themselves
    return $this->id === $userToEdit->id;
}
}
