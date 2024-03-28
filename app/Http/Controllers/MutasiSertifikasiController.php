<?php

namespace App\Http\Controllers;

use App\Exports\MutasiSertifikasiExport;
use App\Models\Jobsite;
use App\Models\MutasiSertifikasi;
use App\Models\Sertifikasi;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class MutasiSertifikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->level == "admin") {
            $mutasiSertifikasis = MutasiSertifikasi::orderBy("id", "DESC")->get();
        } else {
            $mutasiSertifikasis = MutasiSertifikasi::where("jobsite_tujuan", Auth::user()->jobsite)->orderBy("id", "DESC")->get();
        }
        $jobsites = Jobsite::get();

        return view("dashboard.mutasi_sertifikat.index", compact("mutasiSertifikasis", "jobsites"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try {
            $mutasi = MutasiSertifikasi::where("id", $id)->first();
            $sertifikasi = Sertifikasi::where("sertifikat", $mutasi->sertifikat)->first();

            // Hapus sertifikat, jika tidak ada sertifikasi
            if ($sertifikasi == null) {

                // Hapus sertifikat
                if ($mutasi->sertifikat) {
                    $filePath = 'storage/sertifikat/' . $mutasi->sertifikat;
                    unlink($filePath);
                }
            }

            $status = $mutasi->delete();
        } catch (QueryException $err) {
            session()->flash("gagal", "Terjadi kesalahan pada sistem, Gagal hapus data mutasi sertifikat");
            return redirect("mutasi_sertifikasi");
        }

        if ($status) {
            session()->flash("berhasil", "Berhasil hapus data mutasi sertifikat!");
        } else {
            session()->flash("gagal", "Terjadi kesalahan, Gagal hapus data mutasi sertifikat!");
        }
        return redirect("mutasi_sertifikasi");
    }

    public function export()
    {
        return Excel::download(new MutasiSertifikasiExport(), date("d-m-Y") . "mutasi_sertifikat.xlsx");
    }
}
