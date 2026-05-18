<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

/**
 * Handles Bill responsibilities.
 */
class Bill extends Model
{
    public const PENALTY_RATE = 0.05;

    public const VAT_RATE = 0.12;

    protected $fillable = [
        'user_id', 'meter_no', 'utility_type', 'previous_reading',
        'current_reading', 'consumption', 'reading_date',
        'billing_period_start', 'billing_period_end',
        'price_per_unit', 'service_fee', 'base_total_bill', 'total_bill',
        'status', 'is_done', 'paid_at', 'overdue_notified_at', 'payment_reference',
    ];

    protected $casts = [
        'reading_date' => 'date',
        'billing_period_start' => 'date',
        'billing_period_end' => 'date',
        'paid_at' => 'datetime',
        'overdue_notified_at' => 'datetime',
        'previous_reading' => 'decimal:2',
        'current_reading' => 'decimal:2',
        'consumption' => 'decimal:2',
        'price_per_unit' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'base_total_bill' => 'decimal:2',
        'total_bill' => 'decimal:2',
        'penalty_days_applied' => 'integer',
        'is_done' => 'boolean',
    ];

    /**
     * User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function markPastDueAsOverdue(): int
    {
        static::applyDailyOverduePenalties();

        $bills = static::query()
            ->with('user')
            ->where('status', 'Pending')
            ->whereDate('billing_period_end', '<', today())
            ->get();

        if ($bills->isEmpty()) {
            return 0;
        }

        static::query()
            ->whereKey($bills->pluck('id'))
            ->update(['status' => 'Overdue']);

        $bills->each(function (Bill $bill) {
            $bill->status = 'Overdue';
            $bill->notifyResidentIfOverdue();
        });

        return $bills->count();
    }

    public static function applyDailyOverduePenalties(): int
    {
        if (! Schema::hasColumn('bills', 'base_total_bill') || ! Schema::hasColumn('bills', 'penalty_days_applied')) {
            return 0;
        }

        $overdueBills = static::query()
            ->where('status', 'Overdue')
            ->whereDate('billing_period_end', '<', today())
            ->get();

        $updated = 0;

        foreach ($overdueBills as $bill) {
            if (! $bill->billing_period_end) {
                continue;
            }

            $baseAmount = (float) ($bill->base_total_bill ?? $bill->total_bill);
            $amounts = static::calculateAmounts($baseAmount, $bill->billing_period_end, $bill->status);
            $daysOverdue = $amounts['penalty_days'];
            $nextTotal = $amounts['total_before_vat'];

            $hasChanges = (int) $bill->penalty_days_applied !== $daysOverdue
                || (float) $bill->total_bill !== $nextTotal
                || is_null($bill->base_total_bill);

            if (! $hasChanges) {
                continue;
            }

            $bill->forceFill([
                'base_total_bill' => $baseAmount,
                'penalty_days_applied' => $daysOverdue,
                'total_bill' => $nextTotal,
            ])->save();

            $updated++;
        }

        return $updated;
    }

    public static function calculateAmounts(float $baseAmount, $billingPeriodEnd = null, string $status = 'Pending'): array
    {
        $baseAmount = round(max($baseAmount, 0), 2);
        $penaltyDays = static::penaltyDaysFor($billingPeriodEnd, $status);
        $totalBeforeVat = $penaltyDays > 0
            ? round($baseAmount * ((1 + static::PENALTY_RATE) ** $penaltyDays), 2)
            : $baseAmount;
        $penaltyAmount = round(max($totalBeforeVat - $baseAmount, 0), 2);
        $vatAmount = round($baseAmount * static::VAT_RATE, 2);

        return [
            'base_amount' => $baseAmount,
            'penalty_days' => $penaltyDays,
            'penalty_amount' => $penaltyAmount,
            'vat_amount' => $vatAmount,
            'total_before_vat' => $totalBeforeVat,
            'amount_payable' => round($totalBeforeVat + $vatAmount, 2),
        ];
    }

    public static function penaltyDaysFor($billingPeriodEnd = null, string $status = 'Pending'): int
    {
        if ($status !== 'Overdue' || ! $billingPeriodEnd) {
            return 0;
        }

        return (int) max(0, Carbon::parse($billingPeriodEnd)->startOfDay()->diffInDays(today()->startOfDay()));
    }

    /**
     * Get base amount attribute.
     */
    public function getBaseAmountAttribute(): float
    {
        return round((float) ($this->base_total_bill ?? $this->total_bill), 2);
    }

    /**
     * Get penalty amount attribute.
     */
    public function getPenaltyAmountAttribute(): float
    {
        return round(max((float) $this->total_bill - $this->base_amount, 0), 2);
    }

    /**
     * Get vat amount attribute.
     */
    public function getVatAmountAttribute(): float
    {
        return round($this->base_amount * static::VAT_RATE, 2);
    }

    /**
     * Get amount payable attribute.
     */
    public function getAmountPayableAttribute(): float
    {
        return round((float) $this->total_bill + $this->vat_amount, 2);
    }

    public static function statusForDueDate(string $status, $billingPeriodEnd): string
    {
        if ($status !== 'Paid' && Carbon::parse($billingPeriodEnd)->lt(today())) {
            return 'Overdue';
        }

        return $status;
    }

    /**
     * Notify resident if overdue.
     */
    public function notifyResidentIfOverdue(?int $adminId = null): bool
    {
        if ($this->status !== 'Overdue' || $this->overdue_notified_at) {
            return false;
        }

        if (
            ! Schema::hasTable('admin_notifications')
            || ! Schema::hasColumn('admin_notifications', 'reply_message')
            || ! Schema::hasColumn('admin_notifications', 'replied_by')
            || ! Schema::hasColumn('admin_notifications', 'replied_at')
            || ! Schema::hasColumn('bills', 'overdue_notified_at')
        ) {
            return false;
        }

        $adminId ??= User::query()->where('role', 'admin')->value('id');

        if (! $adminId || ! $this->user_id) {
            return false;
        }

        $dueDate = $this->billing_period_end
            ? Carbon::parse($this->billing_period_end)->format('M d, Y')
            : 'the due date';
        $amount = number_format((float) $this->amount_payable, 2);

        AdminNotification::create([
            'user_id' => $adminId,
            'resident_id' => $this->user_id,
            'subject' => "{$this->utility_type} Bill Overdue",
            'message' => "Automatic notice for bill #{$this->id}.",
            'read_at' => now(),
            'replied_by' => $adminId,
            'reply_message' => "Your {$this->utility_type} bill for PHP {$amount}, due on {$dueDate}, is now overdue. Please settle this bill as soon as possible to keep your account updated.",
            'replied_at' => now(),
        ]);

        $this->forceFill(['overdue_notified_at' => now()])->save();

        return true;
    }

    /**
     * Logic for the "Calculate Bill" button
     */
    public function calculateTotal($current, $previous, $pricePerUnit, $serviceFee = 0)
    {
        $consumption = $current - $previous;
        $total = ($consumption * $pricePerUnit) + $serviceFee;
        $amounts = static::calculateAmounts($total, $this->billing_period_end, $this->status ?? 'Pending');

        $this->update([
            'previous_reading' => $previous,
            'current_reading' => $current,
            'consumption' => $consumption,
            'price_per_unit' => $pricePerUnit,
            'service_fee' => $serviceFee,
            'base_total_bill' => $amounts['base_amount'],
            'total_bill' => $amounts['total_before_vat'],
            'penalty_days_applied' => $amounts['penalty_days'],
        ]);
    }

    /**
     * Logic for the "Mark Done" button in your Update screenshots
     */
    public function markAsDone()
    {
        $this->update(['is_done' => true]);
    }
}
