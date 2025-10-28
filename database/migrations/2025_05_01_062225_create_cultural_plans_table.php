<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cultural_plans', function (Blueprint $table) {
            $table->id();
            $table->year('baseline_year');
            $table->year('plan_year');

            $table->integer('awareness_renewal_baseline')->nullable();
            $table->integer('awareness_renewal_plan')->nullable();

            $table->integer('youth_participation_baseline')->nullable();
            $table->integer('youth_participation_plan')->nullable();

            $table->foreignId('zone_id')->constrained('zones')->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained('woredas')->onDelete('cascade');
            $table->foreignId('kebele_id')->constrained('kebeles')->onDelete('cascade');
//            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cultural_plans');
    }
};
