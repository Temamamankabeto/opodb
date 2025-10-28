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
        Schema::create('student_food_supports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained('zones')->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained('woredas')->onDelete('cascade');
            $table->foreignId('kebele_id')->constrained('kebeles')->onDelete('cascade');

            $table->string('school_year')->default('2017/2018 EFY');

            $table->integer('produced_by_school_baseline')->nullable();
            $table->integer('produced_by_school_plan')->nullable();
            $table->integer('collected_by_community_baseline')->nullable();
            $table->integer('collected_by_community_plan')->nullable();
            $table->integer('idaama_supply_baseline')->nullable();
            $table->integer('idaama_supply_plan')->nullable();

            $table->integer('food_price_school_baseline')->nullable();
            $table->integer('food_price_school_plan')->nullable();
            $table->integer('community_contributions_baseline')->nullable();
            $table->integer('community_contributions_plan')->nullable();
            $table->integer('idaama_expense_baseline')->nullable();
            $table->integer('idaama_expense_plan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_food_supports');
    }
};
