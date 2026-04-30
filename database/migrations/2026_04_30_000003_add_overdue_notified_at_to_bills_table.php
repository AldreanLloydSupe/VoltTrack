<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('bills', 'overdue_notified_at')) {
            Schema::table('bills', function (Blueprint $table) {
                $table->timestamp('overdue_notified_at')->nullable()->after('paid_at');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('bills', 'overdue_notified_at')) {
            Schema::table('bills', function (Blueprint $table) {
                $table->dropColumn('overdue_notified_at');
            });
        }
    }
};
