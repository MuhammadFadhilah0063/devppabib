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
        Schema::create('mutasi_karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('nrp', 20);
            $table->string('nama', 150);
            $table->string('jobsite_lama', 100);
            $table->string('jobsite_tujuan', 100);
            $table->date('tgl_mutasi');
            $table->enum('status', ["Menunggu", "Disetujui", "Ditolak"])->default("Menunggu");
            $table->string('disetujui', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mutasi_karyawans');
    }
};
