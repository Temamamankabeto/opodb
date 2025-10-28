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
        Schema::create('institution_inprovements', function (Blueprint $table) {
            $table->id();

            // Foreign keys for geographic hierarchy
            $table->unsignedBigInteger('zone_id');
            $table->unsignedBigInteger('woreda_id');
            $table->unsignedBigInteger('kebele_id');
            $table->unsignedBigInteger('sector_id')->nullable();  // Sector might be optional

            // Data columns
            $table->string('main_category');  // e.g., 'Fooyya’insa Dhaabbilee'
            $table->string('sub_category');   // e.g., 'Haaromsa Waliigalaa'
            $table->string('service_name');   // e.g., 'Mana Barumsa Bu’uura Boruu'
            $table->year('year');             // 2017 or 2018
            $table->enum('type', ['actual', 'plan']);  // distinguish actual or plan data
            $table->integer('quantity')->nullable();   // number/count
            $table->double('cost', 12, 2)->nullable(); // qarshii cost

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
            $table->foreign('woreda_id')->references('id')->on('woredas')->onDelete('cascade');
            $table->foreign('kebele_id')->references('id')->on('kebeles')->onDelete('cascade');
            $table->foreign('sector_id')->references('id')->on('kebele_sectors')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institution_inprovements');
    }
};
