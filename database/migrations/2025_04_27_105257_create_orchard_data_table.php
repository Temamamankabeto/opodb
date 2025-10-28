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
        Schema::create('orchard_data', function (Blueprint $table) {
            $table->id();
            // Year fields
            $table->integer('baseline_year');
            $table->integer('plan_year');
            
            // Orchard fields
            $table->decimal('orchard_area_baseline', 10, 2)->nullable();
            $table->decimal('orchard_area_plan', 10, 2)->nullable();
            $table->integer('orchard_participant_baseline')->nullable();
            $table->integer('orchard_participant_plan')->nullable();
            $table->integer('orchard_days_baseline')->nullable();
            $table->integer('orchard_days_plan')->nullable();
            
            // Seedling fields
            $table->decimal('signal_baseline', 10, 2)->nullable();
            $table->decimal('signal_plan', 10, 2)->nullable();
            $table->decimal('sweetener_baseline', 10, 2)->nullable();
            $table->decimal('sweetener_plan', 10, 2)->nullable();
            $table->decimal('coffee_baseline', 10, 2)->nullable();
            $table->decimal('coffee_plan', 10, 2)->nullable();
            $table->decimal('tea_baseline', 10, 2)->nullable();
            $table->decimal('tea_plan', 10, 2)->nullable();
            $table->integer('seedling_participant_baseline')->nullable();
            $table->integer('seedling_participant_plan')->nullable();
            $table->integer('seedling_days_baseline')->nullable();
            $table->integer('seedling_days_plan')->nullable();
            
            // Fruits fields
            $table->decimal('avokaadoo_baseline', 10, 2)->nullable();
            $table->decimal('avokaadoo_plan', 10, 2)->nullable();
            $table->decimal('paappaayaa_baseline', 10, 2)->nullable();
            $table->decimal('paappaayaa_plan', 10, 2)->nullable();
            $table->decimal('appilli_baseline', 10, 2)->nullable();
            $table->decimal('appilli_plan', 10, 2)->nullable();
            $table->decimal('fruits_others_baseline', 10, 2)->nullable();
            $table->decimal('fruits_others_plan', 10, 2)->nullable();
            $table->integer('fruits_participant_baseline')->nullable();
            $table->integer('fruits_participant_plan')->nullable();
            $table->integer('fruits_days_baseline')->nullable();
            $table->integer('fruits_days_plan')->nullable();
            
            // Calculated fields
            $table->decimal('fruits_idaama_baseline', 10, 2)->nullable();
            $table->decimal('fruits_idaama_plan', 10, 2)->nullable();
            $table->integer('total_number_of_participant_baseline')->nullable();
            $table->integer('total_number_of_participant_plan')->nullable();
            $table->integer('total_number_of_days_baseline')->nullable();
            $table->integer('total_number_of_days_plan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orchard_data');
    }
};
