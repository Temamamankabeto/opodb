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
        Schema::create('upgarading_constructions', function (Blueprint $table) {
            $table->id();

            $table->integer('baseline_year');
            $table->integer('plan_year');
            
            // But'uura Boruu School
            $table->integer('butuura_school_number_baseline')->nullable();
            $table->decimal('butuura_school_cost_baseline', 12, 2)->nullable();
            $table->integer('butuura_school_number_plan')->nullable();
            $table->decimal('butuura_school_cost_plan', 12, 2)->nullable();
            
            // Primary school
            $table->integer('primary_school_number_baseline')->nullable();
            $table->decimal('primary_school_cost_baseline', 12, 2)->nullable();
            $table->integer('primary_school_number_plan')->nullable();
            $table->decimal('primary_school_cost_plan', 12, 2)->nullable();
            
            // Teachers house
            $table->integer('teachers_house_number_baseline')->nullable();
            $table->decimal('teachers_house_cost_baseline', 12, 2)->nullable();
            $table->integer('teachers_house_number_plan')->nullable();
            $table->decimal('teachers_house_cost_plan', 12, 2)->nullable();
            
            // Secondary Generation Healthy centre
            $table->integer('health_center_number_baseline')->nullable();
            $table->decimal('health_center_cost_baseline', 12, 2)->nullable();
            $table->integer('health_center_number_plan')->nullable();
            $table->decimal('health_center_cost_plan', 12, 2)->nullable();
            
            // Farmers/pastoralist Training Center
            $table->integer('training_center_number_baseline')->nullable();
            $table->decimal('training_center_cost_baseline', 12, 2)->nullable();
            $table->integer('training_center_number_plan')->nullable();
            $table->decimal('training_center_cost_plan', 12, 2)->nullable();
            
            // House of the leaders of kebele
            $table->integer('kebele_house_number_baseline')->nullable();
            $table->decimal('kebele_house_cost_baseline', 12, 2)->nullable();
            $table->integer('kebele_house_number_plan')->nullable();
            $table->decimal('kebele_house_cost_plan', 12, 2)->nullable();
            
            // Traditional court office
            $table->integer('court_office_number_baseline')->nullable();
            $table->decimal('court_office_cost_baseline', 12, 2)->nullable();
            $table->integer('court_office_number_plan')->nullable();
            $table->decimal('court_office_cost_plan', 12, 2)->nullable();
            
            // Cultural development center
            $table->integer('cultural_center_number_baseline')->nullable();
            $table->decimal('cultural_center_cost_baseline', 12, 2)->nullable();
            $table->integer('cultural_center_number_plan')->nullable();
            $table->decimal('cultural_center_cost_plan', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upgarading_constructions');
    }
};
