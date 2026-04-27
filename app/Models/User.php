<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'house_number',
        'gender',
        'password',
        'role',
        'status',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the properties owned by this user.
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    /**
     * Get the primary property (first property) for this user.
     */
    public function property(): HasOne
    {
        return $this->hasOne(Property::class);
    }

    /**
     * Get the utility assignments for this user.
     */
    public function utilityAssignments(): HasMany
    {
        return $this->hasMany(UtilityAssignment::class);
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function adminNotifications(): HasMany
    {
        return $this->hasMany(AdminNotification::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isResident(): bool
    {
        return in_array($this->role, ['renter', 'resident'], true);
    }
}
