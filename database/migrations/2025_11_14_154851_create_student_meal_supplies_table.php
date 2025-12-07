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
        Schema::create('student_meal_supplies', function (Blueprint $table) {
            $table->id();
              $table->string('ammount')->required();
              $table->string('unit')->nullable();
            $table->string('source')->required();
            $table->string('cost')->required();
             $table->string('cost_unit')->default('Birr');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_meal_supplies');
    }
};
