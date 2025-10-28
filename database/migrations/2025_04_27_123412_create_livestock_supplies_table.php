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
        Schema::create('livestock_supplies', function (Blueprint $table) {
            $table->id();
            $table->integer('baseline_year');
            $table->integer('plan_year');

            $table->integer('ai_cows_baseline')->nullable();
            $table->integer('ai_cows_plan')->nullable();

            $table->integer('one_day_chick_baseline')->nullable();
            $table->integer('one_day_chick_plan')->nullable();

            $table->integer('twentyone_days_chicks_baseline')->nullable();
            $table->integer('twentyone_days_chicks_plan')->nullable();

            $table->integer('fry_fish_baseline')->nullable();
            $table->integer('fry_fish_plan')->nullable();

            $table->integer('beehives_baseline')->nullable();
            $table->integer('beehives_plan')->nullable();

            $table->decimal('animal_feed_land_baseline', 10, 2)->nullable();
            $table->decimal('animal_feed_land_plan', 10, 2)->nullable();

            $table->decimal('oomsha_nyaata_baseline', 10, 2)->nullable();
            $table->decimal('oomsha_nyaata_plan', 10, 2)->nullable();

            $table->decimal('productivity_baseline', 10, 2)->nullable();
            $table->decimal('productivity_plan', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestock_supplies');
    }
};
