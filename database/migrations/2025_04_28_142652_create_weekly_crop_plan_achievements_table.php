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
        Schema::create('weekly_crop_plan_achievements', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreignId('kebele_id')->constrained()->onDelete('cascade');
            $table->foreignId('yearly_crop_plan_id')->constrained()->onDelete('cascade');
            $table->string('measurement')->nullable();
            $table->string('crop_type')->nullable();
            $table->string('crop_name')->nullable();

            // Original attributes
            $table->integer('baseline_year');
            $table->integer('plan_year');
            $table->decimal('yearly_plan', 12, 2)->nullable();
            $table->decimal('weekly_plan', 12, 2)->nullable();
            $table->decimal('weekly_achievement', 12, 2)->nullable();
            $table->decimal('weekly_achievement_percentage', 5, 2)->nullable();
            $table->decimal('upto_this_week_plan', 12, 2)->nullable();
            $table->decimal('upto_this_week_achievement', 12, 2)->nullable();
            $table->decimal('upto_this_week_percentage_achievement', 5, 2)->nullable();
            $table->decimal('yearly_percentage_achievement', 5, 2)->nullable();

            // Original fields (truncated for brevity - include all your original fields here)
            $table->decimal('solar_center_baseline', 12, 2)->nullable();
            // ... include all other original fields ...

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_crop_plan_achievements');
    }
};
