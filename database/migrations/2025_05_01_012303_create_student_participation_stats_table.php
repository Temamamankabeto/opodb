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
        Schema::create('student_participation_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained('zones')->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained('woredas')->onDelete('cascade');
            $table->foreignId('kebele_id')->constrained('kebeles')->onDelete('cascade');

            $table->year('baseline_year')->default(2017);
            $table->year('plan_year')->default(2018);

            // Regular student participation/registration
            $table->integer('registration_number_baseline')->nullable();
            $table->integer('registration_number_plan')->nullable();

            // By grade participation
            $table->integer('grade_1_6_participation_baseline')->nullable();
            $table->integer('grade_1_6_participation_plan')->nullable();
            $table->integer('grade_7_8_participation_baseline')->nullable();
            $table->integer('grade_7_8_participation_plan')->nullable();
            $table->integer('grade_9_12_participation_baseline')->nullable();
            $table->integer('grade_9_12_participation_plan')->nullable();

            // Adult education participation
            $table->integer('adult_education_baseline')->nullable();
            $table->integer('adult_education_plan')->nullable();

            // Continuity: students graduated that year
            $table->integer('graduation_number_baseline')->nullable();
            $table->integer('graduation_number_plan')->nullable();

            $table->integer('grade_1_6_graduated_baseline')->nullable();
            $table->integer('grade_1_6_graduated_plan')->nullable();
            $table->integer('grade_7_8_graduated_baseline')->nullable();
            $table->integer('grade_7_8_graduated_plan')->nullable();
            $table->integer('grade_9_12_graduated_baseline')->nullable();
            $table->integer('grade_9_12_graduated_plan')->nullable();

            // Cost
            $table->decimal('primary_school_cost_baseline', 10, 2)->nullable();
            $table->decimal('primary_school_cost_plan', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_participation_stats');
    }
};
