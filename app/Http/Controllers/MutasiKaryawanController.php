<?php

namespace App\Http\Controllers;

use App\Exports\MutasiKaryawanExport;
use App\Models\Jobsite;
use App\Models\Karyawan;
use App\Models\MutasiKaryawan;
use App\Models\MutasiSertifikasi;
use App\Models\Sertifikasi;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class MutasiKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->level == "admin") {
            $mutasiKaryawans = MutasiKaryawan::orderBy("id", "DESC")->get();
        } else {
            $mutasiKaryawans = MutasiKaryawan::where("jobsite_tujuan", Auth::user()->jobsite)->orderBy("id", "DESC")->get();
        }
        $jobsites = Jobsite::get();

        return view("dashboard.mutasi_karyawan.index", compact("mutasiKaryawans", "jobsites"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jobsites = Jobsite::get();
        $karyawans = Karyawan::get();
        return view("dashboard.mutasi_karyawan.tambah", compact("jobsites", "karyawans"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ($karyawan = Karyawan::where("nrp", $request->nrp)->first()) {

            if ($karyawan->jobsite != $request->jobsite_tujuan) {

                try {

                    $jobsiteLama = $karyawan->jobsite;
                    $status = MutasiKaryawan::create([
                        'nrp' => $request->nrp,
                        'nama' => $request->nama,
                        'jobsite_lama' => $jobsiteLama,
                        'jobsite_tujuan' => $request->jobsite_tujuan,
                        'tgl_mutasi' => $request->tgl_mutasi,
                    ]);

                    DB::commit();
                } catch (QueryException $err) {

                    DB::rollBack();
                    session()->flash("gagal", "Terjadi kesalahan");
                    return redirect("mutasi_karyawan/create");
                }
            } else {
                session()->flash("gagal", "Terjadi kesalahan, karyawan dengan NRP $request->nrp ini jobsite tujuan sama dengan jobsite lama!");
                return redirect("mutasi_karyawan/create");
            }
        } else {
            session()->flash("gagal", "Terjadi kesalahan, karyawan dengan NRP $request->nrp tidak terdaftar!");
            return redirect("mutasi_karyawan/create");
        }

        if ($status) {
            session()->flash("berhasil", "Berhasil menyimpan data mutasi karyawan!");
        } else {
            session()->flash("gagal", "Terjadi kesalahan, Gagal menyimpan data mutasi karyawan!");
        }
        return redirect("mutasi_karyawan");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(MutasiKaryawan $mutasiKaryawan)
    {
        $jobsites = Jobsite::get();
        $karyawans = Karyawan::get();
        return view("dashboard.mutasi_karyawan.edit", compact("jobsites", "mutasiKaryawan", "karyawans"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MutasiKaryawan $mutasiKaryawan)
    {
        $karyawan = Karyawan::where("nrp", $request->nrp)->first();

        if ($karyawan->jobsite != $request->jobsite_tujuan) {

            try {
                $jobsiteLama = $karyawan->jobsite;
                $status = $mutasiKaryawan->update([
                    'jobsite_lama' => $jobsiteLama,
                    'jobsite_tujuan' => $request->jobsite_tujuan,
                    'tgl_mutasi' => $request->tgl_mutasi,
                    'status' => "Menunggu",
                    'disetujui' => NULL,
                ]);
            } catch (QueryException $err) {

                session()->flash("gagal", "Terjadi kesalahan");
                return redirect("mutasi_karyawan/$mutasiKaryawan->id/edit");
            }
        } else {
            session()->flash("gagal", "Terjadi kesalahan, karyawan dengan NRP $request->nrp ini jobsite tujuan sama dengan jobsite lama!");
            return redirect("mutasi_karyawan/$mutasiKaryawan->id/edit");
        }

        if ($status) {
            session()->flash("berhasil", "Berhasil edit, tunggu perubahan mutasi disetujui!");
        } else {
            session()->flash("gagal", "Terjadi kesalahan, Gagal edit data mutasi karyawan!");
        }
        return redirect("mutasi_karyawan");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MutasiKaryawan $mutasiKaryawan)
    {
        try {
            $status = $mutasiKaryawan->delete();
        } catch (QueryException $err) {
            session()->flash("gagal", "Terjadi kesalahan, gagal hapus mutasi karyawan");
            return redirect("mutasi_karyawan");
        }

        if ($status) {
            session()->flash("berhasil", "Berhasil hapus data mutasi karyawan!");
        } else {
            session()->flash("gagal", "Terjadi kesalahan, Gagal hapus data mutasi karyawan!");
        }
        return redirect("mutasi_karyawan");
    }

    public function export()
    {
        return Excel::download(new MutasiKaryawanExport(), date("d-m-Y") . "mutasi_karyawan.xlsx");
    }

    public function permintaan()
    {
        $jobsites = Jobsite::get();
        $mutasis = MutasiKaryawan::where("status", "menunggu")->orderBy("id", "DESC")->get();
        return view("dashboard.approve_mutasi_karyawan.index", compact("mutasis", "jobsites"));
    }

    public function approve($id)
    {
        $mutasi = MutasiKaryawan::find($id);
        $karyawan = Karyawan::where("nrp", $mutasi->nrp)->first();

        DB::beginTransaction();

        try {
            $mutasi->update(["status" => "Disetujui", "disetujui" => Auth::user()->nama]);

            // Ubah jobsite karyawan
            $karyawan->update([
                "jobsite" => $mutasi->jobsite_tujuan
            ]);

            // Ubah jobsite sertifikasi
            Sertifikasi::where('nrp', $mutasi->nrp)->update(['jobsite' => $mutasi->jobsite_tujuan]);

            $sertifikasis = Sertifikasi::where('nrp', $mutasi->nrp)->pluck('sertifikat')->toArray();

            // Hapus mutasi sertifikasi yang dulu
            MutasiSertifikasi::whereIn('sertifikat', $sertifikasis)
                ->where("nrp", $mutasi->nrp)
                ->delete();

            // Tambah mutasi sertifikasi
            foreach ($sertifikasis as $sertifikasi) {
                $data = MutasiSertifikasi::create([
                    'nrp' => $mutasi->nrp,
                    'nama_karyawan' => $mutasi->nama,
                    'jobsite_lama' => $mutasi->jobsite_lama,
                    'jobsite_tujuan' => $mutasi->jobsite_tujuan,
                    'tgl_mutasi' => $mutasi->tgl_mutasi,
                    'sertifikat' => $sertifikasi,
                ]);
            }

            DB::commit();
            session()->flash("berhasil", "Mutasi berhasil disetujui!");
            return redirect("approve/mutasi_karyawan");
        } catch (QueryException $err) {

            DB::rollBack();
            session()->flash("gagal", "Terjadi kesalahan");
            return redirect("approve/mutasi_karyawan");
        }
    }

    public function reject($id)
    {
        $mutasi = MutasiKaryawan::find($id);

        try {
            $mutasi->update(["status" => "Ditolak"]);

            session()->flash("berhasil", "Mutasi berhasil ditolak!");
            return redirect("approve/mutasi_karyawan");
        } catch (QueryException $err) {

            session()->flash("gagal", "Terjadi kesalahan");
            return redirect("approve/mutasi_karyawan");
        }
    }
}
