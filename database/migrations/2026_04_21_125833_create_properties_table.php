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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            // Property Details from "Create New Rent" form
            $table->string('property_unit_id')->unique();
            $table->string('physical_address');
            $table->enum('unit_type', ['Residential', 'Commercial']);
            $table->string('cluster_housing')->nullable();
            $table->date('lease_commencement_date')->nullable();

            // Status
            $table->enum('status', ['Active', 'Inactive', 'Archived'])->default('Active');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
