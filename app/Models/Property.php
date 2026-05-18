<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Handles Property responsibilities.
 */
class Property extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * Matches your migration columns perfectly.
     */
    protected $fillable = [
        'user_id',
        'property_unit_id',
        'physical_address',
        'unit_type',
        'cluster_housing',
        'lease_commencement_date',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'lease_commencement_date' => 'date',
    ];

    /**
     * Get the User (Resident) that owns or occupies the property.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the meters associated with this property through the PropertyMeter junction.
     * Based on your previous "Meter Initialization" UI.
     */
    public function meters()
    {
        return $this->belongsToMany(Meter::class, 'property_meters', 'property_id', 'meter_id')
            ->using(PropertyMeter::class)
            ->withPivot('initial_reading', 'unit', 'status', 'assignment_date')
            ->withTimestamps();
    }
}
