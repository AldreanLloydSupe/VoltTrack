<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'resident_id',
        'subject',
        'message',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function resident(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resident_id');
    }
}
