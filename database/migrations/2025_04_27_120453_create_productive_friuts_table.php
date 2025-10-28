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
        Schema::create('productive_friuts', function (Blueprint $table) {
            $table->id();
            $table->integer('baseline_year');
            $table->integer('plan_year');

            // Banana
            $table->float('banana_production_baseline')->nullable();
            $table->float('banana_production_plan')->nullable();
            $table->float('banana_land_area_baseline')->nullable();
            $table->float('banana_land_area_plan')->nullable();

            // Avocado
            $table->float('avocado_production_baseline')->nullable();
            $table->float('avocado_production_plan')->nullable();
            $table->float('avocado_land_area_baseline')->nullable();
            $table->float('avocado_land_area_plan')->nullable();

            // Papaya
            $table->float('papaya_production_baseline')->nullable();
            $table->float('papaya_production_plan')->nullable();
            $table->float('papaya_land_area_baseline')->nullable();
            $table->float('papaya_land_area_plan')->nullable();

            // Apple
            $table->float('apple_production_baseline')->nullable();
            $table->float('apple_production_plan')->nullable();
            $table->float('apple_land_area_baseline')->nullable();
            $table->float('apple_land_area_plan')->nullable();

            // Other
            $table->float('other_production_baseline')->nullable();
            $table->float('other_production_plan')->nullable();
            $table->float('other_land_area_baseline')->nullable();
            $table->float('other_land_area_plan')->nullable();

            // Different Spices
            $table->float('spices_production_baseline')->nullable();
            $table->float('spices_production_plan')->nullable();
            $table->float('spices_land_area_baseline')->nullable();
            $table->float('spices_land_area_plan')->nullable();

            // Coffee
            $table->float('coffee_production_baseline')->nullable();
            $table->float('coffee_production_plan')->nullable();
            $table->float('coffee_land_area_baseline')->nullable();
            $table->float('coffee_land_area_plan')->nullable();

            // Tea
            $table->float('tea_production_baseline')->nullable();
            $table->float('tea_production_plan')->nullable();
            $table->float('tea_land_area_baseline')->nullable();
            $table->float('tea_land_area_plan')->nullable();

            // Totals
            $table->float('total_productive_land_baseline')->nullable();
            $table->float('total_productive_land_plan')->nullable();
            $table->float('total_production_baseline')->nullable();
            $table->float('total_production_plan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productive_friuts');
    }
};
