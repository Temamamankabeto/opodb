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
        Schema::create('kebele_crop_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kebele_id');
            $table->unsignedBigInteger('sector_id');
            $table->unsignedBigInteger('croptype_id');
            $table->unsignedBigInteger('crop_id');
            $table->float('area_to_plant');
            $table->float('expected_yield');
            $table->integer('year');
            $table->string('season');
             $table->decimal('seed_required', 10, 2)->nullable();
            $table->json('fertilizer_type_id')->nullable();

            $table->json('fertilizer_details')->nullable();
            $table->string('responsible_staff')->nullable();
            $table->string('notes')->nullable();
            $table->float('productivity')->nullable(); // new field
            $table->unsignedBigInteger('created_by');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Foreign keys
            $table->foreign('kebele_id')->references('id')->on('kebeles')->onDelete('cascade');
            $table->foreign('sector_id')->references('id')->on('sectors')->onDelete('cascade');
            $table->foreign('croptype_id')->references('id')->on('crop_types')->onDelete('cascade');
            $table->foreign('crop_id')->references('id')->on('crops')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kebele_crop_plans');
    }
};
