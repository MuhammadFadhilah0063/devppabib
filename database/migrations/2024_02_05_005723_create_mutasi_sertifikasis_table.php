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
        Schema::create('mutasi_sertifikasis', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nrp')->unsigned();
            $table->string('nama_karyawan', 150);
            $table->string('sertifikat', 255);
            $table->string('jobsite_lama', 20);
            $table->string('jobsite_tujuan', 20);
            $table->date('tgl_mutasi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mutasi_sertifikasis');
    }
};
