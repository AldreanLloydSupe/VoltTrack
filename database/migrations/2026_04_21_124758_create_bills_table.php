<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('meter_no');
            $table->enum('utility_type', ['Water', 'Electricity']);

            // Reading Data from UI
            $table->decimal('previous_reading', 10, 2);
            $table->decimal('current_reading', 10, 2);
            $table->decimal('consumption', 10, 2); // (Current - Previous)

            // Billing Period
            $table->date('reading_date');
            $table->date('billing_period_start');
            $table->date('billing_period_end');

            // Computation from UI
            $table->decimal('price_per_unit', 8, 2); // e.g., ₱12.50
            $table->decimal('service_fee', 8, 2)->default(0);
            $table->decimal('total_bill', 12, 2);

            // Status from UI
            $table->enum('status', ['Pending', 'Overdue', 'Paid'])->default('Pending');
            $table->boolean('is_done')->default(false); // For the "Mark Done" button

            $table->timestamp('paid_at')->nullable();
            $table->string('payment_reference')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index('meter_no');
            $table->index(['status', 'is_done']);
        });
    }
};
