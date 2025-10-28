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
        Schema::create('qonnas', function (Blueprint $table) {
            $table->id();
            $table->string('haala_qonnaa')->nullable();
            $table->decimal('lafa_qotame', 8, 2)->nullable();
            $table->string('sadarkaa_qonnaa')->nullable(); // e.g. Sadarkaa 1ffaa, 2ffaa
            $table->integer('traaktara_hirmaate')->nullable();
            $table->decimal('lafa_tiraakta_tokkoon_qotame', 8, 2)->nullable();

            // new 
            $table->string('rakkoo_muudate')->nullable();
            $table->string('furmaata')->nullable();
            $table->string('carraa_argame')->nullable();
            $table->string('deeggarsa')->nullable();
            $table->string('rakkoo_deddeebie')->nullable();
            $table->string('baayina_dhiiraa')->nullable();
            $table->string('baayina_durbaa')->nullable();
            $table->string('baayina_woliigalaa')->nullable();
            $table->string('dhabbii_dhiiraa')->nullable();
            $table->string('dhabbii_durbaa')->nullable();
            $table->string('dhabbii_woliigalaa')->nullable();

            $table->string('yeroo_dhiiraa')->nullable();
            $table->string('yeroo_durbaa')->nullable();
            $table->string('yeroo_woliigalaa')->nullable();

            
            $table->string('dargaggoota_dhiiraa')->nullable();
            $table->string('dargaggoota_durbaa')->nullable();
            $table->string('dargaggoota_woliigalaa')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qonnas');
    }
};
