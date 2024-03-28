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
        Schema::create('sertifikasis', function (Blueprint $table) {
            $table->id();
            $table->string('nrp', 20);
            $table->string('nama_karyawan', 255);
            $table->string('nama_kompetensi', 150);
            $table->string('kode', 100);
            $table->date('tgl_terbit');
            $table->integer('lama_berlaku');
            $table->date('tgl_exp');
            $table->string('pic_input', 150);
            $table->string('jobsite', 20);
            $table->string('sertifikat', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sertifikasis');
    }
};
