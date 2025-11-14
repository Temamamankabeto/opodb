<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('association_expansion_plans', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->foreignId('association_id')->constrained()->onDelete('cascade');
            $table->json('expansion_data'); // Stores expansion metrics data
            $table->foreignId('sector_id')->constrained('kebele_sectors')->onDelete('cascade');
            $table->foreignId('kebele_id')->constrained()->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained()->onDelete('cascade');
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Unique constraint to prevent duplicate plans for same year, association, and kebele
            $table->unique(['year', 'association_id', 'kebele_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('association_expansion_plans');
    }
};