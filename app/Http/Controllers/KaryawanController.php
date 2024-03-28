<?php

namespace App\Http\Controllers;

use App\Exports\KaryawanExport;
use App\Imports\KaryawansImport;
use App\Models\Jobsite;
use App\Models\Karyawan;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($jobsite)
    {
        // Yajra DataTables
        if (request()->ajax()) {
            $jobsite = request()->input('jobsite');
            $karyawans = Karyawan::where('jobsite', $jobsite)->orderBy("id", "DESC")->get();
            return DataTables::of($karyawans)
                ->addColumn('ubah_status', 'dashboard.karyawan.button.ubah_status')
                ->rawColumns(['ubah_status'])
                ->make(true);
        }

        $jobsites = Jobsite::get();

        return view("dashboard.karyawan.index", compact("jobsite", "jobsites"));
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
        return view("dashboard.karyawan.tambah", compact("namaJobsite", "jobsite", "jobsites"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $namaJobsite)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'nrp'          => 'unique:karyawans',
            ],
            [
                'nrp.unique'    => 'NRP sudah ada!',
            ]
        );

        if ($validation->fails()) {
            return redirect()->back()->with('errors', $validation->errors());
        }

        try {
            $status = Karyawan::create([
                'nrp' => $request->nrp,
                'nama' => $request->nama,
                'departemen' => $request->departemen,
                'posisi' => $request->posisi,
                'jobsite' => $namaJobsite,
            ]);
        } catch (QueryException $err) {
            return redirect()->back()->with('gagal', "Terjadi kesalahan");
        }

        if ($status) {
            session()->flash("berhasil", "Berhasil menyimpan data karyawan $namaJobsite!");
        } else {
            session()->flash("gagal", "Terjadi kesalahan, Gagal menyimpan data karyawan $namaJobsite!");
        }
        return redirect("karyawan/$namaJobsite");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($jobsite, Karyawan $karyawan)
    {
        $namaJobsite = $jobsite;
        $jobsites = Jobsite::get();
        return view("dashboard.karyawan.edit", compact("namaJobsite", "jobsite", "jobsites", "karyawan"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $namaJobsite, Karyawan $karyawan)
    {

        $validation = Validator::make(
            $request->all(),
            [
                'nrp' => [
                    Rule::unique('karyawans')->ignore($request->id),
                ],
            ],
            [
                'nrp.unique'   => 'NRP sudah ada!',
            ]
        );

        if ($validation->fails()) {
            return redirect()->back()->with('errors', $validation->errors());
        }

        try {
            $status = $karyawan->update([
                'nrp' => $request->nrp,
                'nama' => $request->nama,
                'departemen' => $request->departemen,
                'posisi' => $request->posisi,
            ]);
        } catch (QueryException $err) {
            return redirect()->back()->with('gagal', "Terjadi kesalahan");
        }

        if ($status) {
            session()->flash("berhasil", "Berhasil menyimpan data karyawan $namaJobsite!");
        } else {
            session()->flash("gagal", "Terjadi kesalahan, Gagal menyimpan data karyawan $namaJobsite!");
        }
        return redirect("karyawan/$namaJobsite");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($namaJobsite, Karyawan $karyawan)
    {
        try {
            $status = $karyawan->delete();
        } catch (QueryException $err) {
            return redirect()->back()->with('gagal', "Terjadi kesalahan");
        }

        if ($status) {
            session()->flash("berhasil", "Berhasil hapus data karyawan $namaJobsite!");
        } else {
            session()->flash("gagal", "Terjadi kesalahan, Gagal hapus data karyawan $namaJobsite!");
        }
        return redirect("karyawan/$namaJobsite");
    }

    public function ubahStatus($namaJobsite, Karyawan $karyawan)
    {
        try {
            $status = ($karyawan->status == "Aktif") ? "Tidak Aktif" : "Aktif";
            $status = $karyawan->update(["status" => $status]);
        } catch (QueryException $err) {
            return redirect()->back()->with('gagal', "Terjadi kesalahan");
        }

        if ($status) {
            session()->flash("berhasil", "Berhasil ubah status data karyawan $namaJobsite!");
        } else {
            session()->flash("gagal", "Terjadi kesalahan, Gagal ubah status data karyawan $namaJobsite!");
        }
        return redirect("karyawan/$namaJobsite");
    }

    public function getKaryawan($nrp)
    {
        return Karyawan::where("nrp", $nrp)->first();
    }

    public function export($jobsite)
    {
        return Excel::download(new KaryawanExport($jobsite), date("d-m-Y") . "karyawan.xlsx");
    }

    public function import(Request $request, $jobsite)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');

        // membuat nama file unik
        $nama_file = $file->hashName();

        // simpan file sementara
        $file->move(public_path('storage/export/'), $nama_file);

        $filePath = public_path('storage/export/' . $nama_file);

        // import data
        $import = Excel::import(new KaryawansImport(), $filePath);

        // hapus file
        unlink($filePath);

        if ($import) {
            session()->flash("berhasil", "Berhasil import data karyawan!");
        } else {
            session()->flash("gagal", "Terjadi kesalahan, Gagal import data karyawan!");
        }
        return redirect("karyawan/$jobsite");
    }
}
