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
        Schema::create('natural_resource_plans', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            // Separate JSON columns for supply_data and product_data data
          $table->json('natural_data')->nullable()->comment('Data for natural_resources');// all data from natural_resources table 
            // Location fields
            $table->foreignId('sector_id')->constrained('kebele_sectors')->onDelete('cascade');    
            $table->foreignId('kebele_id')->constrained('kebeles')->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained('woredas')->onDelete('cascade');
            $table->foreignId('zone_id')->constrained('zones')->onDelete('cascade');
            $table->foreignId('region_id')->nullable()->constrained('regions')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('natural_resource_plans');
    }
};
