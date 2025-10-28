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
        Schema::create('total_crop_yearly_plans', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->unsignedBigInteger('crop_type_id');
            $table->unsignedBigInteger('crop_id');
            $table->unsignedBigInteger('kebele_id');
            $table->unsignedBigInteger('woreda_id');
            $table->unsignedBigInteger('zone_id');
            $table->unsignedBigInteger('region_id');
            $table->decimal('total_land_hectares', 10, 2)->default(0);
            $table->decimal('total_production', 10, 2)->default(0);
            $table->decimal('average_productivity', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('total_crop_yearly_plans');
    }
};
