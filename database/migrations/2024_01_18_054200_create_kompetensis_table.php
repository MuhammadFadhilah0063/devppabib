<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kompetensis', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('kode', 50);
            $table->string('jenis', 100);
            $table->string('sertifikasi', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kompetensi');
    }
};
