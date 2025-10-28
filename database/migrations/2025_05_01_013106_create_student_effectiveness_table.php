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
        Schema::create('student_effectiveness', function (Blueprint $table) {
            $table->id();

            $table->foreignId('zone_id')->constrained('zones')->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained('woredas')->onDelete('cascade');
            $table->foreignId('kebele_id')->constrained('kebeles')->onDelete('cascade');

            // Baseline and plan for different grades
            $table->integer('grade_1_5_class_baseline')->nullable();
            $table->integer('grade_1_5_class_plan')->nullable();
            $table->integer('grade_1_5_exam_baseline')->nullable();
            $table->integer('grade_1_5_exam_plan')->nullable();

            $table->integer('grade_6_sit_baseline')->nullable();
            $table->integer('grade_6_sit_plan')->nullable();
            $table->integer('grade_6_pass_baseline')->nullable();
            $table->integer('grade_6_pass_plan')->nullable();

            $table->integer('grade_7_class_baseline')->nullable();
            $table->integer('grade_7_class_plan')->nullable();
            $table->integer('grade_7_exam_baseline')->nullable();
            $table->integer('grade_7_exam_plan')->nullable();

            $table->integer('grade_8_take_baseline')->nullable();
            $table->integer('grade_8_take_plan')->nullable();
            $table->integer('grade_8_pass_baseline')->nullable();
            $table->integer('grade_8_pass_plan')->nullable();

            $table->integer('grade_9_11_class_baseline')->nullable();
            $table->integer('grade_9_11_class_plan')->nullable();
            $table->integer('grade_9_11_exam_baseline')->nullable();
            $table->integer('grade_9_11_exam_plan')->nullable();

            $table->integer('grade_12_take_baseline')->nullable();
            $table->integer('grade_12_take_plan')->nullable();
            $table->integer('grade_12_pass_baseline')->nullable();
            $table->integer('grade_12_pass_plan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_effectiveness');
    }
};
