<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sertifikasi extends Model
{
    use HasFactory;
    protected $table = 'sertifikasis';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nrp', 'nama_karyawan', 'nama_kompetensi', 'kode', 'tgl_terbit', 'lama_berlaku', 'tgl_exp', 'pic_input', 'jobsite', 'sertifikat', 'harga', 'id_ikatan_dinas', 'lama_ikatan_dinas',
    ];
    public $timestamps = false;

    public function ikatanDinas(): HasOne
    {
        return $this->hasOne(IkatanDinas::class, "id", "id_ikatan_dinas");
    }
}
