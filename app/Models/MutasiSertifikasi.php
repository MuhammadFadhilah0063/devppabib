<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiSertifikasi extends Model
{
    use HasFactory;
    protected $table = 'mutasi_sertifikasis';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nrp', 'nama_karyawan', 'sertifikat', 'jobsite_lama', 'jobsite_tujuan', 'tgl_mutasi'
    ];
    public $timestamps = false;
}
