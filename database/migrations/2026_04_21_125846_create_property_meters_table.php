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
        Schema::create('property_meters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('meter_id')->constrained()->onDelete('cascade');
            
            // Initial Reading from "Meter Initialization" section
            $table->decimal('initial_reading', 10, 2)->default(0);
            $table->string('unit')->nullable(); // e.g., 'kWh', 'm³'
            
            // Assignment Status
            $table->enum('status', ['Assigned', 'Unassigned', 'Replaced'])->default('Assigned');
            $table->date('assignment_date')->nullable();
            
            $table->timestamps();
            $table->unique(['property_id', 'meter_id']);

            // Index for performance
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_meters');
    }
};
