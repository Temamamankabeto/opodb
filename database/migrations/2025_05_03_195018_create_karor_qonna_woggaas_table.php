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
        Schema::create('karor_qonna_woggaas', function (Blueprint $table) {
            $table->id();

            $table->integer('year');
            $table->string('season');
            $table->unsignedBigInteger('croptype_id')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // tracks the creator
            $table->foreignId('crop_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('kebele_id')->constrained()->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained()->onDelete('cascade');
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->foreignId('crop_id')->constrained()->onDelete('cascade');
            $table->string('type'); // Xaa'oo keemikaalaa, Sanyii Filataa ,Biifaa keemikaala farra aramaa fi farra ilbiisotaa
            $table->decimal('quantity', 10, 2);
            $table->string('unit');

            $table->decimal('land_hectares', 10, 2);
            $table->decimal('production', 10, 2);
            $table->float('productivity')->nullable();
           
            $table->string('created_by')->nullable();
            $table->string('phone')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karor_qonna_woggaas');
    }
};
