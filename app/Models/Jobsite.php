<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobsite extends Model
{
    use HasFactory;
    protected $table = 'jobsites';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'nama_jobsite', 'alamat'
    ];
}
