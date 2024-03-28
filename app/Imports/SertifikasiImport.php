<?php

namespace App\Imports;

use App\Models\IkatanDinas;
use App\Models\Sertifikasi;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;

class SertifikasiImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Hitung lama ikatan dinas (tahun)
        if ($harga = (int) $row[9]) {
            if ($harga < 2500000) {
                $lamaIkatan = null;
            } elseif ($harga >= 2500000 && $harga <= 5000000) {
                $lamaIkatan = 1;
            } elseif ($harga > 5000000 && $harga <= 10000000) {
                $lamaIkatan = 2;
            } elseif ($harga > 10000000 && $harga <= 15000000) {
                $lamaIkatan = 3;
            } elseif ($harga > 15000000 && $harga <= 20000000) {
                $lamaIkatan = 4;
            } elseif ($harga > 20000000 && $harga <= 25000000) {
                $lamaIkatan = 5;
            } else {
                $lamaIkatan = 6;
            }
        } else {
            $lamaIkatan = null;
        }

        // Buat ikatan dinas
        if ($lamaIkatan != null) {

            if ($row[4]) {
                // Membuat objek Carbon dari nilai tanggal
                $tgl_terbit = Carbon::createFromFormat('Y-m-d', $row[4]);

                // Menambahkan sesuai lama tahun ke tanggal
                $tgl_akhir = $tgl_terbit->addYears($lamaIkatan);
            } else {
                $tgl_akhir = null;
            }

            // Atur status
            if ($tgl_akhir != null) {
                $tanggalSekarang = Carbon::now();
                $tgl_akhir = Carbon::parse($tgl_akhir);

                if ($tgl_akhir->greaterThan($tanggalSekarang)) {
                    $status = 0;
                } else {
                    $status = 1;
                }
            } else {
                $status = null;
            }

            IkatanDinas::create([
                "nrp"                => $row[0],
                "nama_karyawan"      => ucwords($row[1]),
                'nama_kompetensi'    => ucwords($row[2]),
                'kode'               => strtoupper($row[3]),
                'tgl_akhir'          => $tgl_akhir,
                'status'             => $status,
            ]);

            $dinas = IkatanDinas::orderBy("id", "DESC")->first();

            $id_ikatan_dinas = $dinas->id;
        } else {
            $id_ikatan_dinas = null;
        }

        return new Sertifikasi([
            'nrp'                => $row[0], // kolom 1
            'nama_karyawan'      => ucwords($row[1]), // kolom 2
            'nama_kompetensi'    => ucwords($row[2]), // kolom 3
            'kode'               => strtoupper($row[3]), // kolom 4
            'tgl_terbit'         => $row[4], // kolom 5
            'tgl_exp'            => $row[5], // kolom 6
            'pic_input'          => ucwords($row[6]), // kolom 7
            'jobsite'            => strtoupper($row[7]), // kolom 8
            'sertifikat'         => trim($row[8]), // kolom 9
            'harga'              => $row[9], // kolom 10
            'lama_berlaku'       => $row[10], // kolom 11
            'id_ikatan_dinas'    => $id_ikatan_dinas,
            'lama_ikatan_dinas'  => $lamaIkatan,
        ]);
    }
}
