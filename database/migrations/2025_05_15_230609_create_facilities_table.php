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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->onDelete('cascade');

            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained()->onDelete('cascade');
            $table->foreignId('kebele_id')->constrained()->onDelete('cascade');
            $table->foreignId('sector_id')->constrained('kebele_sectors')->onDelete('cascade');

            $table->integer('raawwii_maatii')->nullable();
            $table->integer('raawwii_hawaasaa')->nullable();
            $table->integer('raawwii_idaama')->nullable();
            $table->integer('karoora_maatii')->nullable();
            $table->integer('karoora_hawaasaa')->nullable();
            $table->integer('karoora_idaama')->nullable();
            $table->decimal('baasii', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
