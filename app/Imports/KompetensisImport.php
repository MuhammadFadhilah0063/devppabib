<?php

namespace App\Imports;

use App\Models\Kompetensi;
use Maatwebsite\Excel\Concerns\ToModel;

class KompetensisImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Kompetensi([
            'nama'              => ucwords($row[0]),
            'kode'              => strtoupper($row[1]),
            'jenis'             => ucwords($row[2]),
            'sertifikasi'       => $row[3],
        ]);
    }
}
