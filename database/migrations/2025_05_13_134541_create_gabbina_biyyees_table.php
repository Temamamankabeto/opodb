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
        Schema::create('gabbina_biyyees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('kebele_id')->nullable();
            $table->unsignedBigInteger('woreda_id')->nullable();
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->unsignedBigInteger('region_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('year');
            $table->string('compost_type');
            $table->decimal('common', 10, 2)->default(0);
            $table->decimal('individual', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('land_area', 10, 2)->nullable(); // Only for certain compost types

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gabbina_biyyees');
    }
};
