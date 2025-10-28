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
        Schema::create('civil_registration_plans', function (Blueprint $table) {
            $table->id();
            $table->string('region')->nullable();           // Region name
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->unsignedBigInteger('woreda_id')->nullable();
            $table->unsignedBigInteger('kebele_id')->nullable();

            $table->integer('year');                        // Ethiopian year
            $table->integer('birth_cert');                  // Birth certificates
            $table->integer('marriage_cert');               // Marriage certificates
            $table->integer('adoption');                    // Adoption records
            $table->integer('divorce_cert');                // Divorce records
            $table->integer('death_cert');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('civil_registration_plans');
    }
};
