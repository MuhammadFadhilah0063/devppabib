<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiKaryawan extends Model
{
    use HasFactory;
    protected $table = 'mutasi_karyawans';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nrp', 'nama', 'jobsite_lama', 'jobsite_tujuan', 'tgl_mutasi', 'status', 'disetujui'
    ];
    public $timestamps = false;
}
