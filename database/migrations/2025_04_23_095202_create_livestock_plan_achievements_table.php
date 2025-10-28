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
        Schema::create('livestock_plan_achievements', function (Blueprint $table) {
            $table->id();
            $table->string('plan_year');
            $table->string('production_year');
            $table->string('loon_ai_argatee');
            $table->string('cuccii_guyyaa_tokkoo');
            $table->string('lukkuu_sookkee');
            $table->string('cuucii_qurxummii');
            $table->string('gaagura_ammayaa');
            $table->string('oomishaa_nyaata_beeylada');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestock_plan_achievements');
    }
};
