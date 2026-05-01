<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class FinancialSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'decimal:2',
    ];

    public static function getAmount(string $key, float $fallback = 0): float
    {
        if (! Schema::hasTable('financial_settings')) {
            return $fallback;
        }

        return (float) (static::query()->where('key', $key)->value('value') ?? $fallback);
    }

    public static function setAmount(string $key, float $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
