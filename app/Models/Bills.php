<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = [
        'user_id', 'meter_no', 'utility_type', 'previous_reading', 
        'current_reading', 'consumption', 'reading_date', 
        'billing_period_start', 'billing_period_end', 
        'price_per_unit', 'service_fee', 'total_bill', 
        'status', 'is_done'
    ];

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
            'total_bill'       => $total,
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