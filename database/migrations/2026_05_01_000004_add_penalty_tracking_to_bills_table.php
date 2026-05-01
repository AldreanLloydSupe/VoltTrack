<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            if (! Schema::hasColumn('bills', 'base_total_bill')) {
                $table->decimal('base_total_bill', 12, 2)->nullable()->after('service_fee');
            }

            if (! Schema::hasColumn('bills', 'penalty_days_applied')) {
                $table->unsignedInteger('penalty_days_applied')->default(0)->after('base_total_bill');
            }
        });

        DB::table('bills')
            ->whereNull('base_total_bill')
            ->update([
                'base_total_bill' => DB::raw('total_bill'),
            ]);
    }

    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            if (Schema::hasColumn('bills', 'penalty_days_applied')) {
                $table->dropColumn('penalty_days_applied');
            }

            if (Schema::hasColumn('bills', 'base_total_bill')) {
                $table->dropColumn('base_total_bill');
            }
        });
    }
};

