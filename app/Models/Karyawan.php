<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;
    protected $table = 'karyawans';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nrp', 'nama', 'departemen', 'posisi', "jobsite", "status"
    ];
    public $timestamps = false;
}
