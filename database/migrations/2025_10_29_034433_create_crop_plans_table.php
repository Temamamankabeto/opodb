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
        Schema::create('crop_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained()->onDelete('cascade');
            $table->string('land_type_id'); // link to the specific land type
            $table->unsignedInteger('parcel_index'); // index of parcel in land_type_details JSON
            $table->string('crop_name');
            $table->string('season')->nullable(); // Belg, Meher, etc.
            $table->year('year');
            $table->float('planned_area')->nullable(); // hectares planned for this crop
            $table->string('expected_yield')->nullable();
            $table->float('productivity')->nullable(); // quintals per hectare
            $table->json('input_requirements')->nullable(); // seed, fertilizers, chemicals, etc.
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_plans');
    }
};
