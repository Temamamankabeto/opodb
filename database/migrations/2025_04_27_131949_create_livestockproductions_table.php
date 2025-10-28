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
        Schema::create('livestockproductions', function (Blueprint $table) {
            $table->id();
            $table->integer('baseline_year');
            $table->integer('plan_year');

            $table->integer('calves_ai_baseline')->nullable();
            $table->integer('calves_ai_plan')->nullable();
            $table->integer('eggs_produced_baseline')->nullable();
            $table->integer('eggs_produced_plan')->nullable();
            $table->integer('laying_chickens_baseline')->nullable();
            $table->integer('laying_chickens_plan')->nullable();
            $table->float('eggs_per_chicken_baseline', 8, 2)->nullable();
            $table->float('eggs_per_chicken_plan', 8, 2)->nullable();
            $table->integer('meat_chicken_baseline')->nullable();
            $table->integer('meat_chicken_plan')->nullable();
            $table->integer('meat_chickens_count_baseline')->nullable();
            $table->integer('meat_chickens_count_plan')->nullable();
            $table->float('meat_per_chicken_baseline', 8, 2)->nullable();
            $table->float('meat_per_chicken_plan', 8, 2)->nullable();
            $table->float('honey_produced_baseline', 8, 2)->nullable();
            $table->float('honey_produced_plan', 8, 2)->nullable();
            $table->float('honey_per_beehive_baseline', 8, 2)->nullable();
            $table->float('honey_per_beehive_plan', 8, 2)->nullable();
            $table->float('fish_supplied_baseline', 8, 2)->nullable();
            $table->float('fish_supplied_plan', 8, 2)->nullable();
            $table->float('milk_produced_baseline', 8, 2)->nullable();
            $table->float('milk_produced_plan', 8, 2)->nullable();
            $table->integer('lactating_cows_baseline')->nullable();
            $table->integer('lactating_cows_plan')->nullable();
            $table->float('milk_per_cow_baseline', 8, 2)->nullable();
            $table->float('milk_per_cow_plan', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestockproductions');
    }
};
