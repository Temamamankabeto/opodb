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
        Schema::create('crop_plan_achievements', function (Blueprint $table) {
            $table->id();             
            $table->string('maqaa_midhaanii'); 
            $table->string('kblg')->nullable(); 
            $table->string('kblb')->nullable(); 
            $table->string('kbla')->nullable(); 
            $table->string('rblg')->nullable(); 
            $table->string('rblb')->nullable(); 
            $table->string('rbla')->nullable(); 

            $table->string('kog')->nullable(); 
            $table->string('kob')->nullable(); 
            $table->string('koa')->nullable(); 
            $table->string('rog')->nullable(); 
            $table->string('rob')->nullable(); 
            $table->string('roa')->nullable(); 
            $table->string('year'); 
            $table->string('ikbl')->nullable(); 
            $table->string('iKo')->nullable(); 
            $table->string('irbl')->nullable(); 
            $table->string('iro')->nullable(); 
            $table->string('this_year_gana_productivity')->nullable(); 
            $table->string('this_year_bona_productivity')->nullable(); 
            $table->string('this_year_arfasa_productivity')->nullable(); 
            $table->unsignedBigInteger('crop_type_id')->nullable();
            $table->timestamps();
            $table->foreign('crop_type_id')->references('id')->on('crop_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_achievements');
    }
};
