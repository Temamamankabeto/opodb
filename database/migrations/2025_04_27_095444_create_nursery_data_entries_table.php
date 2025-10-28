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
        Schema::create('nursery_data_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('baseline_year');
            $table->integer('plan_year');
            $table->decimal('nursery_quantity_baseline', 8, 2)->nullable();
            $table->decimal('nursery_quantity_plan', 8, 2)->nullable();
            $table->decimal('nursery_area_baseline', 8, 2)->nullable();
            $table->decimal('nursery_area_plan', 8, 2)->nullable();
            $table->string('nursery_participant_baseline')->nullable();
            $table->string('nursery_participant_plan')->nullable();
            $table->integer('nursery_days_baseline')->nullable();
            $table->integer('nursery_days_plan')->nullable();

            // Seedling Section
            $table->decimal('signal_baseline', 8, 2)->nullable();
            $table->decimal('signal_plan', 8, 2)->nullable();
            $table->decimal('sweetener_baseline', 8, 2)->nullable();
            $table->decimal('sweetener_plan', 8, 2)->nullable();
            $table->decimal('coffee_baseline', 8, 2)->nullable();
            $table->decimal('coffee_plan', 8, 2)->nullable();
            $table->decimal('tea_baseline', 8, 2)->nullable();
            $table->decimal('tea_plan', 8, 2)->nullable();
            $table->string('seedling_participant_baseline')->nullable();
            $table->string('seedling_participant_plan')->nullable();
            $table->integer('seedling_days_baseline')->nullable();
            $table->integer('seedling_days_plan')->nullable();

            // Fruits Section
            $table->decimal('avokaadoo_baseline', 8, 2)->nullable();
            $table->decimal('avokaadoo_plan', 8, 2)->nullable();
            $table->decimal('paappaayaa_baseline', 8, 2)->nullable();
            $table->decimal('paappaayaa_plan', 8, 2)->nullable();
            $table->decimal('appilli_baseline', 8, 2)->nullable();
            $table->decimal('appilli_plan', 8, 2)->nullable();
            $table->decimal('fruits_others_baseline', 8, 2)->nullable();
            $table->decimal('fruits_others_plan', 8, 2)->nullable();
            $table->string('fruits_participant_baseline')->nullable();
            $table->string('fruits_participant_plan')->nullable();
            $table->integer('fruits_days_baseline')->nullable();
            $table->integer('fruits_days_plan')->nullable();
            $table->decimal('fruits_idaama_baseline', 8, 2)->nullable();
            $table->decimal('fruits_idaama_plan', 8, 2)->nullable();
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
        Schema::dropIfExists('nursery_data_entries');
    }
};
