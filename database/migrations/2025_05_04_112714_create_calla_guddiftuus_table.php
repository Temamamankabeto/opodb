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
        Schema::create('calla_guddiftuus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // tracks the creator
            $table->foreignId('kebele_id')->constrained()->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained()->onDelete('cascade');
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->year('year');
            $table->string('season'); //Ganna , Bona  , Arfaasaa
            $table->string('type'); // Xaa'oo keemikaalaa, Sanyii Filataa ,Biifaa keemikaala farra aramaa fi farra ilbiisotaa
            $table->decimal('quantity', 10, 2);
            $table->string('unit');
            $table->string('created_by');
            $table->string('phone');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calla_guddiftuus');
    }
};
