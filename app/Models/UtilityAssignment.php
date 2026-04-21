<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UtilityAssignment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Matches the "Confirming Utility Assignment" form fields.
     */
    protected $fillable = [
        'property_id',
        'user_id',
        'initial_deposit',
        'notes',
        'status',
        'confirmed_at',
        'confirmed_by',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'confirmed_at' => 'datetime',
        'initial_deposit' => 'decimal:2',
    ];

    /**
     * Get the property being assigned.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the resident (user) being assigned to the property.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Logic for the "Confirm & Commit Assignment" button.
     * This helper method can be called from your Controller.
     */
    public function confirmAssignment($adminName)
    {
        return $this->update([
            'status' => 'Confirmed',
            'confirmed_at' => now(),
            'confirmed_by' => $adminName,
        ]);
    }
}