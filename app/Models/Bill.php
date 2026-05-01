<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Bill extends Model
{
    protected $fillable = [
        'user_id', 'meter_no', 'utility_type', 'previous_reading',
        'current_reading', 'consumption', 'reading_date',
        'billing_period_start', 'billing_period_end',
        'price_per_unit', 'service_fee', 'base_total_bill', 'total_bill',
        'status', 'is_done', 'paid_at', 'overdue_notified_at', 'payment_reference'
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

            $daysOverdue = max(0, Carbon::parse($bill->billing_period_end)->startOfDay()->diffInDays(today()->startOfDay()));
            $baseAmount = (float) ($bill->base_total_bill ?? $bill->total_bill);
            $nextTotal = round($baseAmount * (1.05 ** $daysOverdue), 2);

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

    public static function statusForDueDate(string $status, $billingPeriodEnd): string
    {
        if ($status !== 'Paid' && Carbon::parse($billingPeriodEnd)->lt(today())) {
            return 'Overdue';
        }

        return $status;
    }

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
        $amount = number_format((float) $this->total_bill, 2);

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

        $this->update([
            'previous_reading' => $previous,
            'current_reading'  => $current,
            'consumption'      => $consumption,
            'price_per_unit'   => $pricePerUnit,
            'service_fee'      => $serviceFee,
            'base_total_bill'  => $total,
            'total_bill'       => $total,
            'penalty_days_applied' => 0,
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
