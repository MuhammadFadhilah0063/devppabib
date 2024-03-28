<?php

namespace App\Imports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\ToModel;

class KaryawansImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Karyawan([
            'nrp'           => $row[0],
            'nama'          => ucwords($row[1]),
            'departemen'    => ucwords($row[2]),
            'posisi'        => ucwords($row[3]),
            'jobsite'       => strtoupper($row[4]),
            'status'        => ucwords($row[5]),
        ]);
    }
}
