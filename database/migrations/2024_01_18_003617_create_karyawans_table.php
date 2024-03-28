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
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('nrp', 20)->unique();
            $table->string('nama', 150);
            $table->string('departemen', 255);
            $table->string('posisi', 255);
            $table->string('jobsite', 100);
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawans');
    }
};
