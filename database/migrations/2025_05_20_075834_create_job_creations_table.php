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
        Schema::create('job_creations', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->string('enterprise_type');
            $table->foreignId('job_type_id')->constrained()->onDelete('cascade');
            $table->string('region');
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained()->onDelete('cascade');
            $table->foreignId('kebele_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->unsignedInteger('number');
            $table->unsignedInteger('male');
            $table->unsignedInteger('female');
            $table->unsignedInteger('sum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_creations');
    }
};
