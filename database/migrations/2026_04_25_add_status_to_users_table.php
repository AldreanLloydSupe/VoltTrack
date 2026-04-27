<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending')
                  ->after('role');
            $table->timestamp('approved_at')->nullable()->after('status');
            $table->foreignId('approved_by')->nullable()->after('approved_at')
                  ->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status', 'approved_at', 'approved_by']);
        });
    }
};
