<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('input_plans', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->unsignedBigInteger('sector_id');
            $table->unsignedBigInteger('kebele_crop_plan_id');
            $table->unsignedBigInteger('croptype_id');
            $table->unsignedBigInteger('fertilizer_type_id')->nullable();
            $table->unsignedBigInteger('crop_id');
            $table->unsignedBigInteger('kebele_id')->nullable(); 
            $table->unsignedBigInteger('woreda_id')->nullable();
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->unsignedBigInteger('region_id')->nullable();

            // Other fields
            $table->year('year');
            $table->string('season')->nullable();
            $table->decimal('seed_required', 10, 2)->nullable();
            $table->json('fertilizer_details')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // ✅ Foreign key constraints
            $table->foreign('sector_id')->references('id')->on('kebele_sectors')->cascadeOnDelete();
            $table->foreign('kebele_crop_plan_id')->references('id')->on('kebele_crop_plans')->cascadeOnDelete();
            $table->foreign('croptype_id')->references('id')->on('crop_types')->cascadeOnDelete();
            $table->foreign('fertilizer_type_id')->references('id')->on('fertilizer_types')->nullOnDelete();
            $table->foreign('crop_id')->references('id')->on('crops')->cascadeOnDelete();

            $table->foreign('kebele_id')->references('id')->on('kebeles')->nullOnDelete();
            $table->foreign('woreda_id')->references('id')->on('woredas')->nullOnDelete();
            $table->foreign('zone_id')->references('id')->on('zones')->nullOnDelete();
            $table->foreign('region_id')->references('id')->on('regions')->nullOnDelete();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('input_plans');
    }
};
