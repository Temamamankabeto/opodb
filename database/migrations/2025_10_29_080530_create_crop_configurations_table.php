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
        Schema::create('crop_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('croptype_id')->constrained()->onDelete('cascade'); // Added this line
            $table->foreignId('crop_id')->constrained()->onDelete('cascade');
            $table->foreignId('kebele_id')->constrained()->onDelete('cascade');
            $table->foreignId('soil_type_id')->constrained()->onDelete('cascade');

            $table->decimal('productivity_per_ha', 8, 2)->nullable();
            $table->decimal('seed_rate_kg_per_ha', 8, 2)->nullable();

            // JSON column to store fertilizer configurations dynamically
            $table->json('fertilizer_requirements')->nullable();

            $table->text('notes')->nullable();
            $table->year('year');

            // Update the unique constraint to include croptype_id
            $table->unique(['croptype_id', 'crop_id', 'kebele_id', 'soil_type_id', 'year'], 'unique_crop_config');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_configurations');
    }
};
