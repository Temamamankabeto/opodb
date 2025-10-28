<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('weekly_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('croptype_id');
            $table->string('baseline_year');
            $table->string('production_plan_idaama');
            $table->string('weekly_achievement_percentage');
            $table->unsignedBigInteger('kebele_id')->nullable();
            $table->unsignedBigInteger('crop_id')->nullable();

            $table->integer('plan_year'); // e.g., 2016
            $table->string('month'); // e.g., Meskerem (or July)
            $table->unsignedTinyInteger('week'); // 1 to 5
            $table->decimal('planned_value', 10, 2)->nullable();
            $table->decimal('achieved_value', 10, 2)->nullable();
            $table->foreign('croptype_id')->references('id')->on('crop_types')->onDelete('cascade');
            $table->foreign('kebele_id')->references('id')->on('crop_types')->onDelete('cascade');
            $table->foreign('crop_id')->references('id')->on('crops')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_plans');
    }
};

