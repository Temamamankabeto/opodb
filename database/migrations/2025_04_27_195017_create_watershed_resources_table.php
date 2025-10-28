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
        Schema::create('watershed_resources', function (Blueprint $table) {
            $table->id(); $table->integer('baseline_year');
            $table->integer('plan_year');
            
            // Watershed/Catchment Area
            $table->decimal('watershed_length_baseline', 10, 2)->nullable();
            $table->decimal('watershed_length_plan', 10, 2)->nullable();
            $table->decimal('watershed_area_baseline', 10, 2)->nullable();
            $table->decimal('watershed_area_plan', 10, 2)->nullable();
            
            // Human Resources
            $table->integer('hr_male_baseline')->nullable();
            $table->integer('hr_male_plan')->nullable();
            $table->integer('hr_female_baseline')->nullable();
            $table->integer('hr_female_plan')->nullable();
            $table->integer('hr_total_baseline')->nullable();
            $table->integer('hr_total_plan')->nullable();
            $table->integer('hr_days_baseline')->nullable();
            $table->integer('hr_days_plan')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['baseline_year', 'plan_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watershed_resources');
    }
};
