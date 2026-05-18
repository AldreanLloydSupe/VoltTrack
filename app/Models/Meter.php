<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Handles Meter responsibilities.
 */
class Meter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Matches the columns from your "Meter Network" and "Meter Initialization" UI.
     */
    protected $fillable = [
        'serial_number',
        'utility_type',
        'hardware_meter_number',
        'status',
    ];

    /**
     * Get the property that owns the meter.
     * Use this to link the meter to specific housing units in your UI.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the bills generated for this specific meter.
     * This will be useful for the "Overall Bill Usage" section in your screens.
     */
    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }
}
