<?php

namespace App\Http\Controllers;

use App\Exports\SertifikasiExport;
use App\Imports\SertifikasiImport;
use App\Models\IkatanDinas;
use App\Models\Jobsite;
use App\Models\Karyawan;
use App\Models\Kompetensi;
use App\Models\MutasiSertifikasi;
use App\Models\Sertifikasi;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SertifikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($jobsite)
    {
        $id_jobsite = Jobsite::select("id")->where('nama_jobsite', $jobsite)->first();
        $sertifikasis = Sertifikasi::where("jobsite", $jobsite)->with(['ikatanDinas'])->orderBy("id", "desc")->get();
        $jobsites = Jobsite::get();

        return view("dashboard.sertifikasi.index", compact("sertifikasis", "jobsite", "jobsites"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($jobsite)
    {
        $namaJobsite = $jobsite;
        $jobsites = Jobsite::get();
        $karyawans = Karyawan::where('jobsite', $namaJobsite)->get();
        $kompetensis = Kompetensi::get();
        $ikatanDinas = IkatanDinas::get();

        return view("dashboard.sertifikasi.tambah", compact("namaJobsite", "jobsite", "jobsites", "karyawans", "kompetensis", "ikatanDinas"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $namaJobsite)
    {

        try {
            // Hitung lama berlaku sertifikasi
            if ($request->tgl_terbit != null && $request->tgl_exp != null) {
                $selisihHari = selisihHariBerlakuKeExp($request->tgl_terbit, $request->tgl_exp);
                $lamaBerlaku = ($selisihHari == 0) ? $selisihHari + 1 : $selisihHari;
            } else {
                $lamaBerlaku = null;
            }

            // Hitung lama ikatan dinas (tahun)
            if ($harga = $request->harga) {
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

                if ($request->tgl_terbit) {
                    // Membuat objek Carbon dari nilai tanggal
                    $tgl_terbit = Carbon::createFromFormat('Y-m-d', $request->tgl_terbit);

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

                $ikatan_dinas = IkatanDinas::create([
                    'nrp' => $request->nrp,
                    'nama_karyawan' => $request->nama_karyawan,
                    'kode' => $request->kode,
                    'nama_kompetensi' => $request->nama_kompetensi,
                    'tgl_akhir' => $tgl_akhir,
                    'status' => $status,
                ]);

                $id_ikatan_dinas = $ikatan_dinas->id;
            } else {
                $id_ikatan_dinas = null;
            }

            if ($request->hasFile('sertifikat')) {

                // Simpan sertifikat
                $sertifikatname = $request->sertifikat->hashName();
                $request->sertifikat->move('storage/sertifikat/', $sertifikatname);

                $status = Sertifikasi::create([
                    'nrp' => $request->nrp,
                    'nama_karyawan' => $request->nama_karyawan,
                    'kode' => $request->kode,
                    'nama_kompetensi' => $request->nama_kompetensi,
                    'tgl_terbit' => $request->tgl_terbit,
                    'tgl_exp' => $request->tgl_exp,
                    'lama_berlaku' => $lamaBerlaku,
                    'pic_input' => $request->pic_input,
                    'jobsite' => $request->jobsite,
                    'harga' => $harga,
                    'id_ikatan_dinas' => $id_ikatan_dinas,
                    'lama_ikatan_dinas' => $lamaIkatan,
                    'sertifikat' => $sertifikatname,
                ]);
            } else {
                $status = Sertifikasi::create([
                    'nrp' => $request->nrp,
                    'kode' => $request->kode,
                    'nama_karyawan' => $request->nama_karyawan,
                    'nama_kompetensi' => $request->nama_kompetensi,
                    'tgl_terbit' => $request->tgl_terbit,
                    'tgl_exp' => $request->tgl_exp,
                    'lama_berlaku' => $lamaBerlaku,
                    'pic_input' => $request->pic_input,
                    'jobsite' => $request->jobsite,
                    'harga' => $harga,
                    'id_ikatan_dinas' => $id_ikatan_dinas,
                    'lama_ikatan_dinas' => $lamaIkatan,
                ]);
            }
        } catch (QueryException $err) {
            return redirect()->back()->with('gagal', "Terjadi kesalahan");
        }

        if ($status) {
            session()->flash("berhasil", "Berhasil menyimpan data sertifikasi karyawan $namaJobsite!");
        } else {
            session()->flash("gagal", "Terjadi kesalahan, Gagal menyimpan data sertifikasi karyawan $namaJobsite!");
        }
        return redirect("sertifikasi/$namaJobsite");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($jobsite, $id)
    {
        $sertifikasi = Sertifikasi::where("id", $id)->with(['ikatanDinas'])->first();
        $namaJobsite = $jobsite;
        $jobsites = Jobsite::get();
        $kompetensis = Kompetensi::get();

        // Ambil extensi file 
        if ($sertifikasi->sertifikat) {
            $extSertifikat = pathinfo($sertifikasi->sertifikat)['extension'];
        } else {
            $extSertifikat = NULL;
        }

        return view("dashboard.sertifikasi.edit", compact("namaJobsite", "jobsites", "jobsite", "kompetensis", "sertifikasi", "extSertifikat"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $namaJobsite, Sertifikasi $sertifikasi)
    {
        // Hitung lama ikatan dinas (tahun)
        if ($harga = $request->harga) {
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

        try {
            // Ubah sertifikat
            if ($request->hasFile('sertifikat')) {

                // Hapus sertifikat
                if ($sertifikasi->sertifikat) {
                    $filePath = 'storage/sertifikat/' . $sertifikasi->sertifikat;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }

                // Simpan sertifikat
                $sertifikatname = $request->sertifikat->hashName();
                $request->sertifikat->move('storage/sertifikat/', $sertifikatname);

                $status = $sertifikasi->update([
                    'sertifikat' => $sertifikatname,
                ]);
            }

            $selisihHari = selisihHariBerlakuKeExp($request->tgl_terbit, $request->tgl_exp);
            $lamaBerlaku = ($selisihHari == 0) ? $selisihHari + 1 : $selisihHari;

            // Buat ikatan dinas baru
            if ($lamaIkatan != null) {

                if ($request->tgl_terbit) {
                    // Membuat objek Carbon dari nilai tanggal
                    $tgl_terbit = Carbon::createFromFormat('Y-m-d', $request->tgl_terbit);

                    // Menambahkan sesuai lama tahun ke tanggal
                    $tgl_akhir = $tgl_terbit->addYears($lamaIkatan);
                } else {
                    $tgl_akhir = null;
                }

                // Hapus ikatan dinas lama
                if ($sertifikasi->id_ikatan_dinas) {
                    IkatanDinas::find($sertifikasi->id_ikatan_dinas)->delete();
                }

                $ikatan_dinas = IkatanDinas::create([
                    'nrp' => $request->nrp,
                    'nama_karyawan' => $request->nama_karyawan,
                    'kode' => $request->kode,
                    'nama_kompetensi' => $request->nama_kompetensi,
                    'tgl_akhir' => $tgl_akhir,
                    'status' => 0,
                ]);

                $id_ikatan_dinas = $ikatan_dinas->id;
            } else {
                $id_ikatan_dinas = null;
            }

            $status = $sertifikasi->update([
                'kode' => $request->kode,
                'nama_kompetensi' => $request->nama_kompetensi,
                'tgl_terbit' => $request->tgl_terbit,
                'tgl_exp' => $request->tgl_exp,
                'lama_berlaku' => $lamaBerlaku,
                'pic_input' => $request->pic_input,
                'harga' => $harga,
                'id_ikatan_dinas' => $id_ikatan_dinas,
                'lama_ikatan_dinas' => $lamaIkatan,
            ]);
        } catch (QueryException $err) {
            return redirect()->back()->with('gagal', "Terjadi kesalahan");
        }

        if ($status) {
            session()->flash("berhasil", "Berhasil edit data sertifikasi karyawan $namaJobsite!");
        } else {
            session()->flash("gagal", "Terjadi kesalahan, Gagal edit data sertifikasi karyawan $namaJobsite!");
        }
        return redirect("sertifikasi/$namaJobsite");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($namaJobsite, Sertifikasi $sertifikasi)
    {
        $mutasiSertifikasi = MutasiSertifikasi::where("sertifikat", $sertifikasi->sertifikat)->first();

        try {

            // Hapus sertifikat, jika tidak ada mutasi
            if ($mutasiSertifikasi == null) {

                // Hapus sertifikat
                if ($sertifikasi->sertifikat) {
                    $filePath = 'storage/sertifikat/' . $sertifikasi->sertifikat;

                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }

            // Hapus ikatan dinas lama
            if ($sertifikasi->id_ikatan_dinas) {
                IkatanDinas::find($sertifikasi->id_ikatan_dinas)->delete();
            }

            $status = $sertifikasi->delete();
        } catch (QueryException $err) {
            return redirect()->back()->with('gagal', "Terjadi kesalahan");
        }

        if ($status) {
            session()->flash("berhasil", "Berhasil hapus data sertifikasi karyawan $namaJobsite!");
        } else {
            session()->flash("gagal", "Terjadi kesalahan, Gagal hapus data sertifikasi karyawan $namaJobsite!");
        }
        return redirect("sertifikasi/$namaJobsite");
    }

    public function export($jobsite)
    {
        return Excel::download(new SertifikasiExport($jobsite), date("d-m-Y") . "sertifikasi_karyawan_$jobsite.xlsx");
    }

    public function import(Request $request, $jobsite)
    {

        $file = $request->file('file');

        // membuat nama file unik
        $nama_file = $file->hashName();

        // simpan file sementara
        $file->move('storage/export/', $nama_file);

        $filePath = 'storage/export/' . $nama_file;

        // import data
        $import = Excel::import(new SertifikasiImport(), $filePath);

        // hapus file
        unlink($filePath);

        if ($import) {
            session()->flash("berhasil", "Berhasil import data sertifikasi!");
        } else {
            session()->flash("gagal", "Terjadi kesalahan, Gagal import data sertifikasi!");
        }

        return redirect("sertifikasi/$jobsite");
    }
}
