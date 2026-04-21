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
        Schema::create('meters', function (Blueprint $table) {
            $table->id();
            
            // Meter Details from "Meter Network" section
            $table->string('serial_number')->unique();
            $table->enum('utility_type', ['Electricity', 'Water']);
            $table->string('hardware_meter_number')->nullable();
            
            // Meter Status
            $table->enum('status', ['Active', 'Inactive', 'Faulty'])->default('Active');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meters');
    }
};
