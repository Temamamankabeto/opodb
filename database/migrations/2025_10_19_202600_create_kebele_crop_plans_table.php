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
            $table->unsignedBigInteger('croptype_id');
            $table->unsignedBigInteger('crop_id');
            $table->float('area_to_plant');
            $table->float('expected_yield');
            $table->string('season');
            $table->string('input_needed')->nullable();
            $table->string('responsible_staff')->nullable();
            $table->string('notes')->nullable();
            $table->float('productivity')->nullable(); // new field
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('kebele_id')->references('id')->on('kebeles')->onDelete('cascade');
            $table->foreign('croptype_id')->references('id')->on('crops')->onDelete('cascade');
            $table->foreign('crop_id')->references('id')->on('crops')->onDelete('cascade'); // added for crop_id

            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
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
