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
        Schema::create('kebele_sectors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('kebele_id')->nullable()->constrained('kebeles');
            $table->foreignId('woreda_id')->nullable()->constrained('woredas');
            $table->foreignId('zone_id')->nullable()->constrained('zones');
            $table->foreignId('region_id')->nullable()->constrained('regions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kebele_sectors');
    }
};
