<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kompetensi extends Model
{
    use HasFactory;
    protected $table = 'kompetensis';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama', 'kode', 'jenis', 'sertifikasi'
    ];
    public $timestamps = false;
}
