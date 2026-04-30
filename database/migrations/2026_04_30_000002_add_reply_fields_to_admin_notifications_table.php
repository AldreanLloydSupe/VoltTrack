<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('admin_notifications', 'replied_by')) {
            Schema::table('admin_notifications', function (Blueprint $table) {
                $table->foreignId('replied_by')->nullable()->after('read_at')->constrained('users')->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('admin_notifications', 'reply_message')) {
            Schema::table('admin_notifications', function (Blueprint $table) {
                $table->text('reply_message')->nullable()->after('replied_by');
            });
        }

        if (! Schema::hasColumn('admin_notifications', 'replied_at')) {
            Schema::table('admin_notifications', function (Blueprint $table) {
                $table->timestamp('replied_at')->nullable()->after('reply_message');
            });
        }
    }

    public function down(): void
    {
        Schema::table('admin_notifications', function (Blueprint $table) {
            if (Schema::hasColumn('admin_notifications', 'replied_by')) {
                $table->dropConstrainedForeignId('replied_by');
            }

            foreach (['reply_message', 'replied_at'] as $column) {
                if (Schema::hasColumn('admin_notifications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
