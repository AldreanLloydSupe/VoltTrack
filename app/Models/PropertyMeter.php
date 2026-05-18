<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Handles PropertyMeter responsibilities.
 */
class PropertyMeter extends Pivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     * Since you added $table->id() in your migration, set this to true.
     */
    public $incrementing = true;

    /**
     * The table associated with the model.
     */
    protected $table = 'property_meters';

    /**
     * The attributes that are mass assignable.
     * Matches your "Meter Initialization" and "Meter Network" screens.
     */
    protected $fillable = [
        'property_id',
        'meter_id',
        'initial_reading',
        'unit',
        'status',
        'assignment_date',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'assignment_date' => 'date',
        'initial_reading' => 'decimal:2',
    ];

    /**
     * Relationship to the Property.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Relationship to the Meter.
     */
    public function meter(): BelongsTo
    {
        return $this->belongsTo(Meter::class);
    }
}
