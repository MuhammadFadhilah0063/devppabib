<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IkatanDinas extends Model
{
    use HasFactory;
    protected $table = 'ikatan_dinass';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'nrp', 'nama_karyawan', 'nama_kompetensi', 'kode', 'tgl_akhir', 'status'
    ];

    public function sertifikasi(): BelongsTo
    {
        return $this->belongsTo(Sertifikasi::class, "id", "id_ikatan_dinas");
    }
}
